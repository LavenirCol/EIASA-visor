<?php

namespace frontend\controllers;

use app\models\Module;
use app\models\Folder;
use app\models\Document;
use app\models\Client;
use app\models\Settings;
use Yii;
use yii\helpers\Url;
//use Imagick;
use tpmanc\imagick\Imagick;

/**
 * Clase visor manager
 *
 * @author jcbobadi
 */
class VisorController extends \yii\web\Controller {

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    private function result ($returndata){
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $returndata;
        $response->statusCode = 200;
        return $response;
    }
    
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionFilemanager() {
        $modulos = Module::find()->indexBy('idmodule')->all();
        return $this->render('filemanager', array('modulos' => $modulos));
    }

    public function actionGetfolders() {
        if (Yii::$app->request->isAjax) {

            $input = Yii::$app->request->post();
            $idmodule = $input['idmodule'];
            $idparentfolder = $input['idfolder'];
            //$folders = Folder::find()->where(['idmodule' => $idmodule, 'idParentFolder' => $idparentfolder])->orderBy('folderName')->all();

            $connection = Yii::$app->getDb();
            
            $sql = "SELECT f.`idfolder`,
               f.`folderName`,
               f.`folderDefault`,
               f.`idParentFolder`,
               f.`folderCreationDate`,
               f.`folderCreationUserId`,
               f.`folderReadOnly`,
               f.`idmodule`, 
               count(d.iddocument) as files, 
               CASE
                    WHEN ABS(sum(d.size)) < 1024 THEN CONCAT( ROUND( sum(d.size), 2 ), ' Bytes')
                  WHEN ABS(sum(d.size)) < 1048576 THEN CONCAT( ROUND( (sum(d.size)/1024), 2 ), ' KB')
                  WHEN ABS(sum(d.size)) < 1073741824 THEN CONCAT( ROUND( (sum(d.size)/1048576), 2 ), ' MB')
                  WHEN ABS(sum(d.size)) < 1099511627776 THEN CONCAT( ROUND( (sum(d.size)/1073741824), 2 ), ' GB' )
                END as size
               FROM folder f
            left join document d on f.idfolder = d.idFolder
            where f.idmodule = $idmodule and idParentFolder = $idparentfolder
            group by f.`idfolder`,  f.`folderName`,  f.`folderDefault`,  f.`idParentFolder`,  f.`folderCreationDate`,  f.`folderCreationUserId`,  f.`folderReadOnly`,  f.`idmodule`
            order by f.folderName";

            $command = $connection->createCommand($sql);
            $folders = $command->queryAll();

            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = ['data' => $folders];
            $response->statusCode = 200;
            return $response;
        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }

    public function actionCreatefolder() {
        if (Yii::$app->request->isAjax) {

            try {
                $input = Yii::$app->request->post();
                $idmodule = $input['idmodule'];
                $idparentfolder = $input['idfolder'];
                $foldername = $input['foldername'];

                if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $foldername))
                {
                     $returndata = ['data' => '', 'error' => 'El nombre de la carpeta tiene carácteres no válidos'];
                     return $this->result($returndata);
                }

                //verifica si ya extiste
                $existefolder = Folder::find()->where(['idmodule' => $idmodule,
                            'idParentFolder' => $idparentfolder,
                            'LOWER(folderName)' => $foldername])->exists();

                if ($existefolder) {
                    $returndata = ['data' => '', 'error' => 'Ya existe una carpeta con ese nombre'];
                    return $this->result($returndata);
                } 

                //varifica directorio raiz
                $keyfolderraiz = Settings::find()->where(['key' => 'RUTARAIZDOCS'])->one();
                $root_path = $keyfolderraiz->value;
                if (!@is_dir($root_path)) {

                    $returndata = ['data' => '', 'error' => 'Directorio raíz no encontrado! '.$root_path];
                    return $this->result($returndata);
                }
                    
                //crear en disco path
                $fpath = "";
                $idpfolder = $idparentfolder;
                do {
                  $pfolder=  Folder::find()->where(['idmodule' => $idmodule,'idfolder' => $idpfolder])->one();
                  $fpath = $pfolder->folderName. '/'. $fpath;  
                  $idpfolder = $pfolder->idParentFolder;
                } while ($idpfolder > 0);

                $modulo = Module::find()->where(['idmodule' => $idmodule])->one();
                $fpath = $root_path . '/' . $modulo->moduleName. '/' . $fpath . '/' . $foldername;

                $rights=0777;
                $dirs = explode('/', $fpath);
                $dir='';
                foreach ($dirs as $part) {
                    $dir.=$part.'/';
                    if (!is_dir($dir) && strlen($dir)>0){
                        mkdir($dir, $rights);
                    }
                }

                //crear en base de datos
                $newfolder = new Folder();
                $newfolder->folderName= $foldername;
                $newfolder->folderDefault = 0; //userfolder
                $newfolder->idParentFolder = $idparentfolder;
                $newfolder->folderCreationDate = date("Y-m-d H:i:s");
                $newfolder->folderCreationUserId = Yii::$app->user->id;
                $newfolder->folderReadOnly = 0;
                $newfolder->idmodule = $idmodule;

                $newfolder->save();

                $returndata = ['data' => $newfolder, 'error' => ''];
                return $this->result($returndata);

            } catch (Exception $ex) {
                $returndata = ['data' => '', 'error' => $ex->getMessage()];
                return $this->result($returndata);
            }

        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }

    public function actionGetfilesfolder() {
        if (Yii::$app->request->isAjax) {

            $input = Yii::$app->request->post();
            $idfolder = $input['idfolder'];
            $files = Document::find()->where(['idFolder' => $idfolder])->orderBy('name')->all();

            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = ['data' => $files];
            $response->statusCode = 200;
            return $response;
        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }
    
    
    public function actionUpload(){

        try{
            $idmodule = Yii::$app->request->post('active_module');
            $idfolder = Yii::$app->request->post('active_folder');
            $actualfolder=  Folder::find()->where(['idmodule' => $idmodule,'idfolder' => $idfolder])->one();

            $foldername =  $actualfolder->folderName;
            $idparentfolder = $actualfolder->idParentFolder;

            //directorio raiz
            $keyfolderraiz = Settings::find()->where(['key' => 'RUTARAIZDOCS'])->one();
            $root_path = $keyfolderraiz->value;

            //crear en disco path
            $fpath = "";
            $idpfolder = $idparentfolder;
            if($idpfolder > 0){
                do {
                  $pfolder=  Folder::find()->where(['idmodule' => $idmodule,'idfolder' => $idpfolder])->one();
                  $fpath = $pfolder->folderName. '/'. $fpath;  
                  $idpfolder = $pfolder->idParentFolder;
                } while ($idpfolder > 0);
            }
            $modulo = Module::find()->where(['idmodule' => $idmodule])->one();
            $vpath = Url::base(true). '/' . $modulo->moduleName. $fpath . '/' . $foldername;
            $fpath = $root_path . '/' . $modulo->moduleName. $fpath . '/' . $foldername;
            
            $rights=0777;
            $dirs = explode('/', $fpath);
            $dir='';
            foreach ($dirs as $part) {
                $dir.=$part.'/';
                if (!is_dir($dir) && strlen($dir)>0){
                    mkdir($dir, $rights);
                }
            }

            foreach($_FILES['file']['tmp_name'] as $key => $value) {
                $tempFile = $_FILES['file']['tmp_name'][$key];
                $targetFile =  $fpath.'/'. $_FILES['file']['name'][$key];
                move_uploaded_file($tempFile,$targetFile);
                
                // adicionar FILE a BD
                $newdocument = new Document();
                $newdocument->name= $_FILES['file']['name'][$key];
                $newdocument->path= $fpath;
                $newdocument->level1name= 0;
                $newdocument->relativename= $vpath .'/'. $_FILES['file']['name'][$key];
                $newdocument->fullname= $_FILES['file']['name'][$key];
                $newdocument->date= date("Y-m-d H:i:s");
                $newdocument->size= $_FILES['file']['size'][$key];
                $newdocument->type= $_FILES['file']['type'][$key];
                $newdocument->iddocumentType= 1; // archivo cargado
                $newdocument->idFolder = $idfolder;
                $newdocument->fileUploadedUserId = Yii::$app->user->id;
                $newdocument->save(false);             
            }
 
            $returndata = ['data' => ''. $targetFile, 'error' => ''];
            return $this->result($returndata);
            
        } catch (Exception $ex) {
            $returndata = ['data' => '', 'error' => $ex->getMessage()];
            return $this->result($returndata);
        }       
    }

    
    public function actionGetfile($id, $d = false, $t = false){
        //$id= 20;
        $file=  Document::find()->where(['iddocument' => $id])->one();
        if ($file === null) {
             throw new NotFoundHttpException(Yii::t('app', 'El archivo no existe'));
        }
      
        header('Content-Description: EIASA Visor File Transfer');
        header('Content-Type: '. $file->type);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        if($d === 'true')
        {
            header('Content-Disposition: attachment; filename="'.$file->name.'"');
            header('Content-Length: ' . $file->size);            
        }
        if($t == 'true'){
            return readfile($file->path . '/' . $file->fullname . '.jpg');

        }else{
            return readfile($file->path . '/' . $file->fullname);
        }        
    }
    

    public function actionGetcontactsclient() {
        if (Yii::$app->request->isAjax) {

            $input = Yii::$app->request->post();
            $idclient = $input['idclient'];
            $client = Client::find()->where(['idClient' => $idclient])->one();

            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = ['data' => $files];
            $response->statusCode = 200;
            return $response;
        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }

}

<?php

namespace frontend\controllers;

use app\models\Module;
use app\models\Folder;
use app\models\Document;
use app\models\Client;
use app\models\Settings;
use Yii;

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
            $folders = Folder::find()->where(['idmodule' => $idmodule, 'idParentFolder' => $idparentfolder])->orderBy('folderName')->all();

            //TODO: consultar cantida dde archivos

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

<?php
namespace frontend\controllers;

use app\models\Module;
use app\models\Folder;
use app\models\Document;
use app\models\Client;
use app\models\Settings;
use Yii;
use yii\helpers\Url;
use yii\base\Exception;
//use Imagick;
use tpmanc\imagick\Imagick;
use Fpdf\Fpdf;
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
        ini_set('memory_limit', '8192M'); 
        $modulos = Module::find()->indexBy('idmodule')->all();
        //directorio raiz
        $keyassignedsize = Settings::find()->where(['key' => 'ASSIGNEDDISKSIZEGB'])->one();

        $connection = Yii::$app->getDb();

        $sql = "SELECT 
                CASE
                    WHEN ABS(sum(d.size)) < 1024 THEN CONCAT( ROUND( sum(d.size), 2 ), ' Bytes')
                    WHEN ABS(sum(d.size)) < 1048576 THEN CONCAT( ROUND( (sum(d.size)/1024), 2 ), ' KB')
                    WHEN ABS(sum(d.size)) < 1073741824 THEN CONCAT( ROUND( (sum(d.size)/1048576), 2 ), ' MB')
                    WHEN ABS(sum(d.size)) < 1099511627776 THEN CONCAT( ROUND( (sum(d.size)/1073741824), 2 ), ' GB' )
                 END as actualsize,
                 ROUND( (sum(d.size)/1073741824), 2 ) as sizeingb
                FROM document d ";

        $sizes = $connection->createCommand($sql)->queryOne();

        return $this->render('filemanager', array('modulos' => $modulos,
                'assignedsize' => $keyassignedsize->value,
                'actualsize' => $sizes['actualsize'],
                'sizeingb' => $sizes['sizeingb']));
    }

    /// Server side

    public function actionClientsserver() {

        try {
            $requestData = $_REQUEST;

            $columns = array(
                0 => 'code_client',
                1 => 'idprof1',
                2 => 'name',
                3 => 'state',
                4 => 'town',
                5 => 'address',
                6 => 'phone',
                7 => 'email'
            );

            $totalData = Yii::$app->db->createCommand('SELECT COUNT(*) FROM client')->queryScalar();
            $totalFiltered = $totalData;

            $sql = "SELECT * FROM `client` where 1=1 ";

            if (!empty($requestData['search']['value'])) {
                $sql .= " AND ( name LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR idprof1 LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR code_client LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR town LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR state LIKE '" . $requestData['search']['value'] . "%')";
            }
            
            if (!empty($requestData['export'])) {
                
            } else {
                $sqlc = str_replace("*", "COUNT(*)", $sql);
                $totalFiltered = Yii::$app->db->createCommand($sqlc)->queryScalar();

                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
                        $requestData['length'] . "   ";
            }

            $result = Yii::$app->db->createCommand($sql)->queryAll();

            $data = array();
            foreach ($result as $key => $row) {
                $nestedData = array();
                $nestedData[] = '<button class="btn btn-primary btn-sm selectsuscriptor" style="padding: 5px;font-size: 10px;margin-top: 5px;" data-idcliente="'. $row["idClient"].'" >Seleccionar</button>';
                $nestedData[] = $row["code_client"];
                $nestedData[] = $row["idprof1"];
                $nestedData[] = $row["name"];
                $nestedData[] = $row["state"];
                $nestedData[] = $row["town"];
                $nestedData[] = $row["address"];
                $nestedData[] = $row["phone"];
                $nestedData[] = $row["email"] == null ? '' : $row["email"];

                $data[] = $nestedData;
            }

            if (!empty($requestData['export'])) {
                if ($requestData['export'] == 'csv') {
                    ob_start();
                    ob_start('ob_gzhandler');
                    header('Content-Type: text/csv; charset=windows-1251');
                    header('Content-Disposition: attachment; filename=ClientesExport.csv');
                    $output = fopen('php://output', 'w');
                    fwrite($output, "\xEF\xBB\xBF");
                    fputcsv($output, ['', 'code_client', 'Cédula', 'Nombre', 'Departamento', 'Municipio', 'Barrio / Dirección', 'Teléfono', 'Email'], ';');
                    foreach ($data as $key => $value) {
                        fputcsv($output, $value, ';');
                    }
                    fclose($output);
                    ob_end_flush();
                }
                if ($requestData['export'] == 'pdf') {
                    $pdf = new Fpdf();
                    /* Column headings */
                    $header = array('', 'code_client', 'Cédula', 'Nombre', 'Departamento', 'Municipio', 'Barrio / Dirección', 'Teléfono', 'Email');
                    /* Data loading */
                    $pdf->AddPage('L', 'Legal');
                    $pdf->SetFont('Courier', '', 6);
                    /* Column widths */
                    $w = array(30, 27, 20, 8, 20, 10, 20, 95, 15);
                    /* Header */
                    for ($i = 0; $i < count($header); $i++) {
                        $pdf->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C');
                    }
                    $pdf->Ln();
                    /* Data */
                    foreach ($data as $row) {
                        for ($i = 0; $i < 7; $i++) {
                            $pdf->Cell($w[$i], 6, utf8_decode($row[$i]), 'LR');
                        }

                        $barr = utf8_decode($row[7]);
                        if (strlen($barr) > 70) {
                            $barr = substr($barr, 0, 70) . '...';
                        }

                        $pdf->Cell($w[7], 6, $barr, 'LR');

                        for ($i = 8; $i < 9; $i++) {
                            $pdf->Cell($w[$i], 6, utf8_decode($row[$i]), 'LR');
                        }
                        $pdf->Ln();
                    }
                    /* Closing line */
                    $pdf->Cell(array_sum($w), 0, '', 'T');
                    $pdf->Output('D', 'ClientesExport.pdf', true);
                }
            } else {

                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $data   // total data array
                );

                echo json_encode($json_data);
            }
        } catch (\Exception $ex) {
            $returndata = ['error' => $ex->getMessage()];
            echo json_encode($returndata);
        }
    }
    
    public function actionGetfolders() {
        if (Yii::$app->request->isAjax) {

            $input = Yii::$app->request->post();
            $idmodule = $input['idmodule'];
            $idparentfolder = $input['idfolder'];
            $idcliente = $input['idcliente'];
            //$folders = Folder::find()->where(['idmodule' => $idmodule, 'idParentFolder' => $idparentfolder])->orderBy('folderName')->all();
            if((int)$idmodule === 1 || (int)$idmodule === 2){ // suscriptores -facturacion
                if((int)$idcliente === 0)
                {
                    $idparentfolder = -1;
                }
            }
            
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
            where f.idmodule = $idmodule and idParentFolder = $idparentfolder ";
            
            if((int)$idcliente > 0 && (int)$idmodule === 1 ) //suscriptores
            {
                $sql = $sql . " and (d.idfolder in ( select idFolder from contract co where co.fk_soc = '$idcliente' )";
                $sql = $sql . " or d.idfolder in ( select idFolder from proposal pr where pr.socid = '$idcliente' )";
                $sql = $sql . " or d.idfolder in ( select idFolder from hstask pr where pr.socid = '$idcliente' ))";
            }
            
            if((int)$idcliente > 0 && (int)$idmodule === 2 ) // facturacion
            {
                $sql = $sql . " and d.idfolder in ( select idFolder from invoices inv where inv.socid = '$idcliente' )";
            }
            
            $sql = $sql . " group by f.`idfolder`,  f.`folderName`,  f.`folderDefault`,  f.`idParentFolder`,  f.`folderCreationDate`,  f.`folderCreationUserId`,  f.`folderReadOnly`,  f.`idmodule`
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
                if($idpfolder > 0){
                    do {
                      $pfolder=  Folder::find()->where(['idmodule' => $idmodule,'idfolder' => $idpfolder])->one();
                      $fpath = $pfolder->folderName. '/'. $fpath;  
                      $idpfolder = $pfolder->idParentFolder;
                    } while ($idpfolder > 0);
                }
                $modulo = Module::find()->where(['idmodule' => $idmodule])->one();
                $fpath = $root_path . '/' . $modulo->moduleName. '/' . $fpath . $foldername;

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

            } catch (\Exception $ex) {
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
            //$files = Document::find()->where(['idFolder' => $idfolder])->orderBy('name')->all();

            $connection = Yii::$app->getDb();
            
            $sql = "SELECT `document`.`iddocument`,
                    `document`.`name`,
                    `document`.`path`,
                    `document`.`level1name`,
                    `document`.`relativename`,
                    `document`.`fullname`,
                    `document`.`date`,
                    CASE
                        WHEN ABS(`document`.`size`) < 1024 THEN CONCAT( ROUND( `document`.`size`, 2 ), ' Bytes')
                        WHEN ABS(`document`.`size`) < 1048576 THEN CONCAT( ROUND( (`document`.`size`/1024), 2 ), ' KB')
                        WHEN ABS(`document`.`size`) < 1073741824 THEN CONCAT( ROUND( (`document`.`size`/1048576), 2 ), ' MB')
                        WHEN ABS(`document`.`size`) < 1099511627776 THEN CONCAT( ROUND( (`document`.`size`/1073741824), 2 ), ' GB' )
                    END as size,
                    `document`.`type`,
                    `document`.`iddocumentType`,
                    `document`.`idFolder`,
                    `document`.`fileUploadedUserId`
                    FROM document
                    where idFolder = $idfolder
                    order by `document`.`name`";

            $command = $connection->createCommand($sql);
            $files = $command->queryAll();
            
            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = ['data' => $files];
            $response->statusCode = 200;
            return $response;
        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }
    
     public function actionGetfilessearch() {
        if (Yii::$app->request->isAjax) {

            $input = Yii::$app->request->post();
            $term = $input['term'];

            $connection = Yii::$app->getDb();
            
            $sql = "SELECT `document`.`iddocument`,
                    `document`.`name`,
                    `document`.`path`,
                    `document`.`level1name`,
                    `document`.`relativename`,
                    `document`.`fullname`,
                    `document`.`date`,
                    CASE
                        WHEN ABS(`document`.`size`) < 1024 THEN CONCAT( ROUND( `document`.`size`, 2 ), ' Bytes')
                        WHEN ABS(`document`.`size`) < 1048576 THEN CONCAT( ROUND( (`document`.`size`/1024), 2 ), ' KB')
                        WHEN ABS(`document`.`size`) < 1073741824 THEN CONCAT( ROUND( (`document`.`size`/1048576), 2 ), ' MB')
                        WHEN ABS(`document`.`size`) < 1099511627776 THEN CONCAT( ROUND( (`document`.`size`/1073741824), 2 ), ' GB' )
                    END as size,
                    `document`.`type`,
                    `document`.`iddocumentType`,
                    `document`.`idFolder`,
                    `document`.`fileUploadedUserId`
                    FROM document
                    where `document`.`name` like '%$term%'
                    order by `document`.`name`";

            $command = $connection->createCommand($sql);
            $files = $command->queryAll();
            
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
            $modulo = Module::find()->where(['idmodule' => $idmodule])->one();
            if($idpfolder > 0){
                do {
                  $pfolder=  Folder::find()->where(['idmodule' => $idmodule,'idfolder' => $idpfolder])->one();
                  $fpath = $pfolder->folderName. '/'. $fpath;  
                  $idpfolder = $pfolder->idParentFolder;
                } while ($idpfolder > 0);
                $vpath = Url::base(true). '/' . $modulo->moduleName. '/' . $fpath. $foldername;
                $fpath = $root_path . '/' . $modulo->moduleName. '/' . $fpath . $foldername;
            }else{
                $vpath = Url::base(true). '/' . $modulo->moduleName. '/' . $fpath. $foldername;
                $fpath = $root_path . '/' . $modulo->moduleName. '/' . $fpath . $foldername;
            }
            
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
                //var_dump($targetFile);exit;
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
                
                //$this->createimage(154,120,$destination,'s',$type);
            }
 
            $returndata = ['data' => ''. $targetFile, 'error' => ''];
            return $this->result($returndata);
            
        } catch (\Exception $ex) {
            $returndata = ['data' => '', 'error' => $ex->getMessage()];
            return $this->result($returndata);
        }       
    }

    public function createimage($width,$height,$destination,$thumb,$type)
    {
        $obj_imgl = new thumbnail_images;
        $obj_imgl->PathImgOld = $destination.".".$type;
        $obj_imgl->PathImgNew = $destination."$thumb.".$type;
        $obj_imgl->NewWidth = $width;
        if($height!='')
        {
            $obj_imgl->NewHeight = $height;
        }
        if (!$obj_imgl->create_thumbnail_images()) 
            echo "error"; 
    }
    
    public function actionGetfile($id, $d = false, $t = false){  
        //$id= 20;
        $file=  Document::find()->where(['iddocument' => $id])->one();
        if ($file === null) {
             throw new NotFoundHttpException(Yii::t('app', 'El archivo no existe'));
        }
        ob_start();
        ob_start('ob_gzhandler');
        
        header('Content-Description: EIASA Visor File Transfer');
        header('Content-Type: '. $file->type);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file->size);            
        header('Content-Disposition: inline; filename="'.$file->name.'"');
        
        if($d === 'true')
        {
            header('Content-Disposition: attachment; filename="'.$file->name.'"');
        }
        
        ob_end_flush();
        
        if($t == 'true'){
            return Yii::$app->response->sendFile($file->path . '/' . $file->name);
            //return readfile($file->path . '/' . $file->name);
        }else{
            return Yii::$app->response->sendFile($file->path . '/' . $file->name);
            //return readfile($file->path . '/' . $file->name);
        }        
    }
    
    
    public function actionRenamefile() {
        if (Yii::$app->request->isAjax) {

            try {
                $input = Yii::$app->request->post();
                $idmodule = $input['idmodule'];
                $idfolder = $input['idfolder'];
                $iddocument = $input['idfile'];
                $newname = $input['newname'];

                if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $newname))
                {
                     $returndata = ['data' => '', 'error' => 'El nombre del archivo tiene carácteres no válidos'];
                     return $this->result($returndata);
                }
                
                $document = Document::find()->where(['iddocument'=>$iddocument])->one();
                $ext = $ext = substr(strrchr($document->name, '.'), 1);
                $newname = $newname.'.'.$ext;
                
                //verifica si ya extiste
                $existearchivo = Document::find()->where(['idFolder' => $idfolder,
                            'LOWER(name)' => $newname])->exists();

                if ($existearchivo) {
                    $returndata = ['data' => '', 'error' => 'Ya existe una archivo con ese nombre'];
                    return $this->result($returndata);
                } 

                if(rename($document->path ."/".$document->name, $document->path ."/".$newname)){
                    $document->relativename = str_replace($document->name, $newname, $document->relativename);
                    $document->name = $newname;
                    $document->fullname = $newname;
                    $document->save();

                    $returndata = ['data' => $newname, 'error' => ''];
                    return $this->result($returndata);
                }else{
                    $returndata = ['data' => '', 'error' => 'No se puede renombrar el archivo'];
                    return $this->result($returndata);                    
                }                

            } catch (\Exception $ex) {
                $returndata = ['data' => '', 'error' => $ex->getMessage()];
                return $this->result($returndata);
            }

        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }
    
    public function actionDeletefile() {
        if (Yii::$app->request->isAjax) {

            try {
                $input = Yii::$app->request->post();
                $iddocument = $input['idfile'];

                $document = Document::find()->where(['iddocument'=>$iddocument])->one();
                
                if(!file_exists($document->path ."/".$document->name)){
                    $returndata = ['data' => '', 'error' => 'No existe el archivo'];
                    return $this->result($returndata);    
                }
                
                if(unlink($document->path ."/".$document->name)){
                    
                    $document->delete();
                    $returndata = ['data' => 'Archivo eliminado correctamente', 'error' => ''];
                    return $this->result($returndata);

                }else{
                    $returndata = ['data' => '', 'error' => 'No se puede eliminar el archivo'];
                    return $this->result($returndata);                    
                }                

            } catch (\Exception $ex) {
                $returndata = ['data' => '', 'error' => $ex->getMessage()];
                return $this->result($returndata);
            }

        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }
    
    public function actionRenamefolder() {
        if (Yii::$app->request->isAjax) {

            try {
                $input = Yii::$app->request->post();
                $idmodule = $input['idmodule'];
                $idfolder = $input['idfolder'];
                $newname = $input['newname'];

                if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $newname))
                {
                     $returndata = ['data' => '', 'error' => 'El nombre de la carpeta tiene carácteres no válidos'];
                     return $this->result($returndata);
                }

                //verifica si ya extiste
                $existefolder = Folder::find()->where(['idmodule' => $idmodule,
                            'LOWER(folderName)' => $newname])->exists();

                if ($existefolder) {
                    $returndata = ['data' => '', 'error' => 'Ya existe una carpeta con ese nombre'];
                    return $this->result($returndata);
                } 

                $actualfolder=  Folder::find()->where(['idmodule' => $idmodule,'idfolder' => $idfolder])->one();

                $foldername =  $actualfolder->folderName;
                $idparentfolder = $actualfolder->idParentFolder;

                //directorio raiz
                $keyfolderraiz = Settings::find()->where(['key' => 'RUTARAIZDOCS'])->one();
                $root_path = $keyfolderraiz->value;

                //crear en disco path
                $fpath = "";
                $npath = "";
                $idpfolder = $idparentfolder;
                $modulo = Module::find()->where(['idmodule' => $idmodule])->one();
                if($idpfolder > 0){
                    do {
                      $pfolder=  Folder::find()->where(['idmodule' => $idmodule,'idfolder' => $idpfolder])->one();
                      $fpath = $pfolder->folderName. '/'. $fpath;  
                      $idpfolder = $pfolder->idParentFolder;
                    } while ($idpfolder > 0);
                    $fpath = $root_path . '/' . $modulo->moduleName. '/' . $fpath . $foldername;
                    $npath = $root_path . '/' . $modulo->moduleName. '/' . $npath . $newname;
                }else{
                    $fpath = $root_path . '/' . $modulo->moduleName. '/' . $fpath . $foldername;
                    $npath = $root_path . '/' . $modulo->moduleName. '/' . $npath . $newname;
                }

                if(rename($fpath, $npath)){
                    $actualfolder->folderName = $newname;
                    $actualfolder->save();

                    $returndata = ['data' => $newname, 'error' => ''];
                    return $this->result($returndata);
                }else{
                    $returndata = ['data' => '', 'error' => 'No se puede renombrar la carpeta'];
                    return $this->result($returndata);                    
                }                

            } catch (\Exception $ex) {
                $returndata = ['data' => '', 'error' => $ex->getMessage()];
                return $this->result($returndata);
            }

        } else {
            throw new \yii\web\BadRequestHttpException;
        }
    }
    
    public function actionDeletefolder() {
        if (Yii::$app->request->isAjax) {

            try {
                $input = Yii::$app->request->post();
                $idfolder = $input['idfolder'];

                $actualfolder=  Folder::find()->where(['idfolder' => $idfolder])->one();
                $idmodule = $actualfolder->idmodule;
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
                
                if(!is_dir($fpath)){
                    $returndata = ['data' => '', 'error' => 'No existe la carpeta'];
                    return $this->result($returndata);    
                }
                
                if(rmdir($fpath)){
                    
                    $actualfolder->delete();
                    $returndata = ['data' => 'Carpeta eliminada correctamente', 'error' => ''];
                    return $this->result($returndata);

                }else{
                    $returndata = ['data' => '', 'error' => 'No se puede eliminar la carpeta'];
                    return $this->result($returndata);                    
                }                

            } catch (\Exception $ex) {
                $returndata = ['data' => '', 'error' => $ex->getMessage()];
                return $this->result($returndata);
            }

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

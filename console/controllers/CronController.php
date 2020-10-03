<?php

namespace console\controllers;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\BaseConsole;

use app\models\Client;
use app\models\Contract;
use app\models\Document;
use app\models\Documenttype;
use app\models\Folder;
use app\models\Module;
use app\models\Settings;

class CronController extends Controller {
    
    /*
     * ejecucion API dollibar
     */
    function callAPI($method, $entity, $data = false)
    {
        //prod
        //$apikey = 'gFmK1A57ZQolc0V33727Jo4ohxyAGIPh';
        //$url = 'https://megayacrm.lavenirapps.co/api/index.php/'.$entity;
        
        //dev
        $apikey = '1bo1dgm0B4Xd48nW3iNZXvaJh1AXCH36';
        $url = 'https://eiasa-dev.lavenirapps.co/api/index.php/'.$entity;
        $curl = curl_init();
        $httpheader = ['DOLAPIKEY: '.$apikey];

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                $httpheader[] = "Content-Type:application/json";

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

                break;
            case "PUT":

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                $httpheader[] = "Content-Type:application/json";

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        //    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        if ($result === false) {
            $err = 'Curl error: ' . curl_error($curl);
            //$output_array[] = array('code' => 0, 'message' => $err);

            echo 'EIASAVISOR - CURLError:'.$err .'\n';
            echo 'EIASAVISOR - MESSAGE:'.json_encode($data)."\n";
        } 
        
        curl_close($curl);

        return $result;
    }
    
    /*
     * Crea un nuevo folde en disco y base de datos
     */
    function Createfolder($idmodule, $idparentfolder, $foldername) {
        try {

            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $foldername))
            {
                 $returndata = ['data' => '', 'error' => 'El nombre de la carpeta tiene carácteres no válidos'];
                 return $returndata;
            }

            //verifica si ya extiste
            $existefolder = Folder::find()->where(['idmodule' => $idmodule,
                        'idParentFolder' => $idparentfolder,
                        'LOWER(folderName)' => $foldername])->exists();

            if ($existefolder) {
                $returndata = ['data' => '', 'error' => 'Ya existe una carpeta con ese nombre'];
                return $returndata;
            } 

            //varifica directorio raiz
            $keyfolderraiz = Settings::find()->where(['key' => 'RUTARAIZDOCS'])->one();
            $root_path = $keyfolderraiz->value;
            if (!@is_dir($root_path)) {

                $returndata = ['data' => '', 'error' => 'Directorio raíz no encontrado! '.$root_path];
                return $returndata;
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
            $newfolder->folderDefault = 1; //syncfolder
            $newfolder->idParentFolder = $idparentfolder;
            $newfolder->folderCreationDate = date("Y-m-d H:i:s");
            $newfolder->folderCreationUserId = 1;//Administrator Yii::$app->user->id;
            $newfolder->folderReadOnly = 0;
            $newfolder->idmodule = $idmodule;

            $newfolder->save();

            $returndata = ['data' => $newfolder, 'error' => ''];
            return $returndata;

        } catch (Exception $ex) {
            $returndata = ['data' => '', 'error' => $ex->getMessage()];
            return $returndata;
        }
    }   
    
    /*
     *  funcion publica de inicio cron
     */    
    public function actionSyncdata() {
        echo "Inicio cron job \n"; // your logic for deleting old post goes here
        echo "Consultando Clientes...\n"; // your logic for deleting old post goes here
        $this->syncClients(); 
        // sincroniza archivos
        
        exit();
    }    
    
    /*
     * Sincroniza clientes, contratos y crea folders sucriptores y facturacion
     */
    public function syncClients() {

        // consulta lista de clientes
	$clientSearch = json_decode($this->CallAPI("GET", "thirdparties", array(
		"sortfield" => "t.rowid", 
		"sortorder" => "ASC", 
		"mode" => "1",
                "sqlfilters" => "(t.idprof6 != '')"
		)
	), true);
        
	if (isset($clientSearch["error"]) && $clientSearch["error"]["code"] >= "300") {
            echo "Error Clientes ". $clientSearch["error"]["message"] ."\n";
	} else {
            echo "Clientes Encontrados: ". sizeof( $clientSearch)."\n";
            foreach ((array)$clientSearch as $client) {
                echo "Procesando. ". $client['name'] ."\n"; 
               $currentclient = Client::find()->where(['idClient' => $client['id']])->one();
               if(!isset($currentclient)){
                   //Cliente nuevo
                    echo "Cliente Nuevo \n";
                    $newclient = new Client();
                    $newclient->attributes = $client;
                    $newclient->idClient = $client['id'];
                    $newclient->access_id = $client['idprof6'];
                    $newclient->save(false);
                    
                    // consulta contratos
                    $contracts = json_decode($this->CallAPI("GET", "contracts", array(
                            "sortfield" => "t.rowid", 
                            "sortorder" => "ASC", 
                            "thirdparty_ids" => $client['id']
                            )
                    ), true);
                    
                    echo "procesando contratos \n";
                    if(isset($contracts)){
                        foreach ((array)$contracts as $contract) {
                                                
                            // crea folder de cliente en modulo suscriptores
                            $idmodule = 1; //suscriptores
                            $suscfolder = $this->Createfolder($idmodule, 0, $contract['ref']);
                            if($suscfolder['error'] == ""){
                                // crea contract
                                $newcontracts = new Contract();
                                $newcontracts->id = $contract['id'];
                                $newcontracts->entity = $contract['entity'];
                                $newcontracts->socid = $contract['socid'];
                                $newcontracts->ref = $contract['ref'];
                                $newcontracts->fk_soc = $contract['fk_soc'];
                                $newcontracts->idFolder = $suscfolder['data']->idfolder;
                                $newcontracts->save(false);
                            }
                            
                            // crea folder de cliente en modulo facturacion
                            $idmodule = 2; //facturacion
                            $factfolder = $this->Createfolder($idmodule, 0, $contract['ref']);
                            if($factfolder['error'] == ''){
                                // crea contract
                                $newcontractf = new Contract();
                                $newcontractf->id = $contract['id'];
                                $newcontractf->entity = $contract['entity'];
                                $newcontractf->socid = $contract['socid'];
                                $newcontractf->ref = $contract['ref'];
                                $newcontractf->fk_soc = $contract['fk_soc'];
                                $newcontractf->idFolder = $factfolder['data']->idfolder;
                                $newcontractf->save(false);
                            }


                        }
                    }
                    
               }else{
                   
               }
            }
	}
        
    }
}
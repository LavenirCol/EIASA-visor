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
use app\models\Invoices;
use app\models\Module;
use app\models\Proposal;
use app\models\Settings;
use app\models\Tickets;

class CronController extends Controller {
    
    
    public $root_path = "";
    public $root_vpath = "";
    public $idmodulesusc = 1; //suscriptores
    public $idmodulefact = 2; //facturacion
    public $modulosusc;
    public $modulofact;
    
    /*
     * ejecucion API dollibar
     */

    function callAPI($method, $entity, $data = false) {
        //prod
        $apikey = 'gFmK1A57ZQolc0V33727Jo4ohxyAGIPh';
        $url = 'https://megayacrm.lavenirapps.co/api/index.php/'.$entity;
        //dev
        //$apikey = '1bo1dgm0B4Xd48nW3iNZXvaJh1AXCH36';
        //$url = 'https://eiasa-dev.lavenirapps.co/api/index.php/' . $entity;
        $curl = curl_init();
        $httpheader = ['DOLAPIKEY: ' . $apikey];

        switch ($method) {
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

            echo 'EIASAVISOR - CURLError:' . $err . '\n';
            echo 'EIASAVISOR - MESSAGE:' . json_encode($data) . "\n";
        }

        curl_close($curl);

        return $result;
    }

    /*
     * Crea un nuevo folde en disco y base de datos
     */

    function Createfolder($idmodule, $idparentfolder, $foldername) {
        try {

            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $foldername)) {
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

                $returndata = ['data' => '', 'error' => 'Directorio raíz no encontrado! ' . $root_path];
                return $returndata;
            }

            //crear en disco path
            $fpath = "";
            $idpfolder = $idparentfolder;
            if ($idpfolder > 0) {
                do {
                    $pfolder = Folder::find()->where(['idmodule' => $idmodule, 'idfolder' => $idpfolder])->one();
                    $fpath = $pfolder->folderName . '/' . $fpath;
                    $idpfolder = $pfolder->idParentFolder;
                } while ($idpfolder > 0);
            }
            $modulo = Module::find()->where(['idmodule' => $idmodule])->one();
            $fpath = $root_path . '/' . $modulo->moduleName . '/' . $fpath . $foldername;

            $rights = 0777;
            $dirs = explode('/', $fpath);
            $dir = '';
            foreach ($dirs as $part) {
                $dir .= $part . '/';
                if (!is_dir($dir) && strlen($dir) > 0) {
                    mkdir($dir, $rights);
                }
            }

            //crear en base de datos
            $newfolder = new Folder();
            $newfolder->folderName = $foldername;
            $newfolder->folderDefault = 1; //syncfolder
            $newfolder->idParentFolder = $idparentfolder;
            $newfolder->folderCreationDate = date("Y-m-d H:i:s");
            $newfolder->folderCreationUserId = 1; //Administrator Yii::$app->user->id;
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
        //verifica directorio raiz
        $keyfolderraiz = Settings::find()->where(['key' => 'RUTARAIZDOCS'])->one();
        $this->root_path = $keyfolderraiz->value;
        //varifica urlbase raiz
        $keyurlbase = Settings::find()->where(['key' => 'URLBASE'])->one();
        $this->root_vpath = $keyurlbase->value;        // modulo suscriptores

        //modulo suscriptores
        $this->modulosusc = Module::find()->where(['idmodule' => $this->idmodulesusc])->one();
        // modulo facturacion
        $this->modulofact = Module::find()->where(['idmodule' => $this->idmodulefact])->one();
        
        echo "Consultando Clientes...\n"; // your logic for deleting old post goes here
        $this->syncClients();
        // sincroniza archivos
        echo "Sincronizando Archivos...\n"; // your logic for deleting old post goes here
        $this->syncFiles();
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
            echo "Error Clientes " . $clientSearch["error"]["message"] . "\n";
        } else {
            echo "Clientes Encontrados: " . sizeof($clientSearch) . "\n";
            foreach ((array) $clientSearch as $client) {                
                echo "----------------------------------------\n";
                echo "Inicio Cliente ".date("Y-m-d H:i:s"). "\n";
                echo "Procesando. " . $client['name'] . "\n";
                $currentclient = Client::find()->where(['idClient' => $client['id']])->one();
                if (!isset($currentclient)) {
                    //Cliente nuevo
                    echo "Cliente Nuevo thirdparty_id(".$client['id'].") \n";
                    $newclient = new Client();
                    $newclient->attributes = $client;
                    $newclient->idClient = $client['id'];
                    $newclient->access_id = $client['idprof6'];
                    $newclient->save(false);
                    
                    $this->processcontracts($client['id']);
                    $this->processproposals($client['id']);
                    $this->processinvoices($client['id']);
                    $this->processtickets($client['id']);
                    
                } else {

                    //TODO:CLiente existe
                }
            
                echo "Fin Cliente ".date("Y-m-d H:i:s"). "\n";

            }
        }
    }

    /*
     * Procesa contracts
     */
    
    public function processcontracts($thirdparty_id){        
        // consulta contratos
        echo "consultando contracts thirdparty_id(". $thirdparty_id .")\n";
        $contracts = json_decode($this->CallAPI("GET", "contracts", array(
                    "sortfield" => "t.rowid",
                    "sortorder" => "ASC",
                    "thirdparty_ids" => $thirdparty_id
                        )
                ), true);

        if (isset($contracts["error"]) && $contracts["error"]["code"] >= 300) {
            echo "procesando contracts (0)\n";
            return;
        }
        
        echo "procesando contracts (". sizeof($contracts) .")\n";
        foreach ((array) $contracts as $contract) {

            // crea folder de cliente en modulo suscriptores                        
            if(isset($contract['ref'])){
                $suscfolder = $this->Createfolder($this->idmodulesusc, 0, $contract['ref']);

                if ($suscfolder['error'] == "") {
                    //crear disco path
                    $fpath = $this->root_path . '/' . $this->modulosusc->moduleName . '/' . $suscfolder['data']->folderName;
                    $vpath = $this->root_vpath . '/' . $this->modulosusc->moduleName. '/' . $suscfolder['data']->folderName . '/';

                    // crea contract
                    $newcontracts = new Contract();
                    $newcontracts->id = $contract['id'];
                    $newcontracts->entity = $contract['entity'];
                    $newcontracts->socid = $contract['socid'];
                    $newcontracts->ref = $contract['ref'];
                    $newcontracts->fk_soc = $contract['fk_soc'];
                    $newcontracts->idFolder = $suscfolder['data']->idfolder;
                    $newcontracts->save(false);

                    // consulta documentos contract
                    $documents = json_decode($this->CallAPI("GET", "documents", array(
                                "modulepart" => "contract",
                                "sortfield" => "name",
                                "sortorder" => "ASC",
                                "id" => $contract['id']
                                    )
                            ), true);

                    echo "procesando documents contract (". sizeof($documents) .")\n";
                    foreach ((array) $documents as $document) {
                        //var_dump($document);                                                                                
                        $newdocument = new Document();
                        $newdocument->attributes = $document;
                        $newdocument->date = gmdate("Y-m-d H:i:s", $document['date']);
                        $newdocument->iddocumentType = 2; // documento contract
                        $newdocument->idFolder = $suscfolder['data']->idfolder;
                        $newdocument->type = 'pending';
                        $newdocument->path = $fpath;
                        $newdocument->relativename = $vpath . $document['name'];
                        $newdocument->save(false);
                    }                                               
                }
            }
            
        }
    }

    /*
     * Process proposals
     */
    
    public function processproposals($thirdparty_id){
        // consulta proposal
        echo "consultando proposals thirdparty_id(". $thirdparty_id .")\n";
        $proposals = json_decode($this->CallAPI("GET", "proposals", array(
                    "sortfield" => "t.rowid",
                    "sortorder" => "ASC",
                    "thirdparty_ids" => $thirdparty_id
                        )
                ), true);

        if (isset($proposals["error"]) && $proposals["error"]["code"] >= 300) {
            echo "procesando proposals (0)\n";
            return;
        }

        echo "procesando proposals (". sizeof($proposals) .")\n";
        foreach ((array) $proposals as $proposal) {

            if(isset($proposal['ref'])){
                // crea folder de cliente en modulo suscriptores                        
                $suscfolder = $this->Createfolder($this->idmodulesusc, 0, $proposal['ref']);

                if ($suscfolder['error'] == "") {
                    //crear disco path
                    $fpath = $this->root_path . '/' . $this->modulosusc->moduleName . '/' . $suscfolder['data']->folderName;
                    $vpath = $this->root_vpath . '/' . $this->modulosusc->moduleName. '/' . $suscfolder['data']->folderName . '/';

                    // crea proposal
                    $newproposal = new Proposal();
                    $newproposal->id = $proposal['id'];
                    $newproposal->entity = $proposal['entity'];
                    $newproposal->socid = $proposal['socid'];
                    $newproposal->ref = $proposal['ref'];
                    $newproposal->idFolder = $suscfolder['data']->idfolder;
                    $newproposal->save(false);

                    // consulta documentos proposal
                    $documents = json_decode($this->CallAPI("GET", "documents", array(
                                "modulepart" => "proposal",
                                "sortfield" => "name",
                                "sortorder" => "ASC",
                                "id" => $proposal['id']
                                    )
                            ), true);

                    echo "procesando documents proposal (". sizeof($documents) .")\n";
                    foreach ((array) $documents as $document) {
                        //var_dump($document);                                                                                
                        $newdocument = new Document();
                        $newdocument->attributes = $document;
                        $newdocument->date = gmdate("Y-m-d H:i:s", $document['date']);
                        $newdocument->iddocumentType = 3; // documento proposal
                        $newdocument->idFolder = $suscfolder['data']->idfolder;
                        $newdocument->type = 'pending';
                        $newdocument->path = $fpath;
                        $newdocument->relativename = $vpath . $document['name'];
                        $newdocument->save(false);
                    }                                               
                }
            }
        }
    }

    /*
     * Process invoices
     */
    
    public function processinvoices($thirdparty_id){        
        // consulta invoices
        echo "consultando invoices thirdparty_id(". $thirdparty_id .")\n";
        $invoices = json_decode($this->CallAPI("GET", "invoices", array(
                    "sortfield" => "t.rowid",
                    "sortorder" => "ASC",
                    "thirdparty_ids" => $thirdparty_id
                        )
                ), true);

        if (isset($invoices["error"]) && $invoices["error"]["code"] >= 300) {
            echo "procesando invoices (0)\n";
            return;
        }
        
        echo "procesando invoices (". sizeof($invoices) .")\n";
        foreach ((array) $invoices as $invoice) {

            if(isset( $invoice['ref'])){
                // crea folder de cliente en modulo suscriptores                        
                $suscfolder = $this->Createfolder($this->idmodulefact, 0, $invoice['ref']);

                if ($suscfolder['error'] == "") {
                    //crear disco path
                    $fpath = $this->root_path . '/' . $this->modulofact->moduleName . '/' . $suscfolder['data']->folderName;
                    $vpath = $this->root_vpath . '/' . $this->modulofact->moduleName. '/' . $suscfolder['data']->folderName . '/';

                    // crea invoice
                    $newinvoice = new Invoices();
                    $newinvoice->id = $invoice['id'];
                    $newinvoice->entity = $invoice['entity'];
                    $newinvoice->socid = $invoice['socid'];
                    $newinvoice->ref = $invoice['ref'];
                    $newinvoice->idFolder = $suscfolder['data']->idfolder;
                    $newinvoice->save(false);

                    // consulta documentos invoices
                    $documents = json_decode($this->CallAPI("GET", "documents", array(
                                "modulepart" => "invoice",
                                "sortfield" => "name",
                                "sortorder" => "ASC",
                                "id" => $invoice['id']
                                    )
                            ), true);

                    echo "procesando documents proposal (". sizeof($documents) .")\n";
                    foreach ((array) $documents as $document) {
                        //var_dump($document);                                                                                
                        $newdocument = new Document();
                        $newdocument->attributes = $document;
                        $newdocument->date = gmdate("Y-m-d H:i:s", $document['date']);
                        $newdocument->iddocumentType = 4; // documento invoices
                        $newdocument->idFolder = $suscfolder['data']->idfolder;
                        $newdocument->type = 'pending';
                        $newdocument->path = $fpath;
                        $newdocument->relativename = $vpath . $document['name'];
                        $newdocument->save(false);
                    }                                               
                }
            
            }
        }
    }
    
    
   /*
     * Process tickets
     */
    
    public function processtickets($thirdparty_id){        
        // consulta tickets
        echo "consultando tickets thirdparty_id(". $thirdparty_id .")\n";
        $tickets = json_decode($this->CallAPI("GET", "tickets", array(
                    "sortfield" => "t.rowid",
                    "sortorder" => "ASC",
                    "socid" => $thirdparty_id
                        )
                ), true);

        if (isset($tickets["error"]) && $tickets["error"]["code"] >= 300) {
            echo "procesando tickets (0)\n";
            return;
        }
        
        echo "procesando tickets (". sizeof($tickets) .")\n";
        foreach ((array) $tickets as $ticket) {

            if(isset( $ticket['ref'])){
                // crea ticket
                $newticket = new Tickets();
                $newticket->id = $ticket['id'];
                $newticket->socid = $ticket['socid'];
                $newticket->ref = $ticket['ref'];
                $newticket->fk_soc = $ticket['fk_soc'];
                $newticket->subject = $ticket['subject'];
                $newticket->message = $ticket['message'];
                $newticket->type_label = $ticket['type_label'];
                $newticket->category_label = $ticket['category_label'];
                $newticket->severity_label = $ticket['severity_label'];
                $newticket->datec = $ticket['datec'];
                $newticket->date_read = $ticket['date_read'];
                $newticket->date_close = $ticket['date_close'];

                $newticket->save(false);            
            }
        }
    }
    
    /*
     * Sincroniza archivos
     */
    
    public function syncFiles() {
        echo "----------------------------------------\n";
        echo "Inicio syncdodumentos ".date("Y-m-d H:i:s"). "\n";
        $documents = Document::find()->where(['type'=>'pending'])->all();
        echo "procesando documents (". sizeof($documents) .")\n";
        foreach ($documents as $document){
            //consulta documento
            $modulepart = "";
            if($document['iddocumentType']===2){ // contract
                $modulepart = "contract";
            }
            if($document['iddocumentType']===3){ // proposal
                $modulepart = "propale";
            }
            if($document['iddocumentType']===4){ // invoice
                $modulepart = "facture";
            }
            
            // consulta documento download
            $download = json_decode($this->CallAPI("GET", "documents/download", array(
                    "modulepart" => $modulepart,
                    "original_file" => $document['level1name'] . "/". $document['name'])
                ), true);
            
            if (isset($download["error"]) && $download["error"]["code"] >= "300") {
                echo "Error download " . $document['level1name'] . "/" . $document['name'] . " - " . $download["error"]["message"] . "\n";
            }else{
                //actualiza document
                $document->type = $download["content-type"];
                //$document->fileUploadedUserId = -1;
                $document->save();
                
                //guarda bas64
                $this->base64ToFile($download["content"], $document['path'] . "/" . $document['name'] );
            }
        }
        echo "Fin syncdodumentos ".date("Y-m-d H:i:s"). "\n";
    }

    
    function base64ToFile($base64_string, $output_file) {
        $file = fopen($output_file, "wb");
        fwrite($file, base64_decode($base64_string));
        fclose($file);

        return $output_file;
    }
}

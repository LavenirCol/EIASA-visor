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
use app\models\Hsstock;
use app\models\Hstask;

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
        $url = 'https://megayacrm.lavenirapps.co/api/index.php/' . $entity;
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

        // TODO: Borrar todo antes de iniciar
//        DELETE FROM `eiasavisor`.`document` where fileUploadedUserId is null;
//        DELETE FROM `eiasavisor`.`contract`;
//        DELETE FROM `eiasavisor`.`proposal`;
//        DELETE FROM `eiasavisor`.`invoices`;
//        DELETE FROM `eiasavisor`.`tickets`;
//        DELETE FROM `eiasavisor`.`folder` WHERE folderdefault > 0;
//        DELETE FROM `eiasavisor`.`client`;

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
        //ciclos
        $limit = 100;
        $cycles = 230; // 23000 clientes aprox
        for ($i = 1; $i <= $cycles; $i++) {
            $this->syncClients($limit, $i);
        }
        // sincroniza archivos
        echo "Sincronizando Archivos...\n"; // your logic for deleting old post goes here
        $this->syncFiles();

        exit();
    }

    public function actionSynconlyfiles() {
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

        // sincroniza archivos
        //echo "Sincronizando Archivos...\n"; // your logic for deleting old post goes here        
        //$this->syncFiles();
        // sincroniza archivos instalacion
        echo "Sincronizando Archivos Instalacion...\n"; // your logic for deleting old post goes here        
        $this->syncInstalationfiles();

        exit();
    }

    /*
     * Sincroniza clientes, contratos y crea folders sucriptores y facturacion
     */

    public function syncClients($limit, $page) {
        // consulta lista de clientes
        $clientSearch = json_decode($this->CallAPI("GET", "thirdparties", array(
                    "sortfield" => "t.rowid",
                    "sortorder" => "ASC",
                    "mode" => "1",
                    "limit" => $limit,
                    "page" => $page,
                    "sqlfilters" => "(t.idprof6 != '')"
                        )
                ), true);

        if (isset($clientSearch["error"]) && $clientSearch["error"]["code"] >= "300") {
            echo "($page) Error Clientes " . $clientSearch["error"]["message"] . "\n";
        } else {
            echo "($page) Clientes Encontrados: " . sizeof($clientSearch) . "\n";
            foreach ((array) $clientSearch as $client) {
                echo "----------------------------------------\n";
                echo "Inicio Cliente " . date("Y-m-d H:i:s") . "\n";
                echo "Procesando. " . $client['name'] . "\n";
                $currentclient = Client::find()->where(['idClient' => $client['id']])->one();
                if (!isset($currentclient)) {
                    //Cliente nuevo
                    echo "Cliente Nuevo thirdparty_id(" . $client['id'] . ") \n";
                    $newclient = new Client();
                    $newclient->attributes = $client;
                    $newclient->idClient = $client['id'];
                    $newclient->access_id = $client['idprof6'];
                    $newclient->address = str_replace(array("\n", "\r", "\r\n", "\xE2\x80\x9010"), ' ', $client['address']);
                    $newclient->name = str_replace("\xC5\x84", 'ñ', $client['name']);
                    
                    $latlng = '';
                    if(isset($client['array_options'])){
                        if(isset($client['array_options']["options_lat"])){
                            $latlng = $latlng . ' Lat: ' . $client['array_options']["options_lat"];
                        }
                        if(isset($client['array_options']["options_lon"])){
                            $latlng = $latlng . ' Lon: ' . $client['array_options']["options_lon"];
                        }
                    }
                    
                    $newclient->latlng = $latlng;
                    $newclient->save(false);

                    $this->processcontracts($client['id']);
                    $this->processproposals($client['id']);
                    $this->processinvoices($client['id']);
                    $this->processtickets($client['id']);
                } else {

                    //TODO:CLiente existe
                }

                echo "Fin Cliente " . date("Y-m-d H:i:s") . "\n";
            }
        }
    }

    /*
     * Procesa contracts
     */

    public function processcontracts($thirdparty_id) {
        // consulta contratos
        echo "consultando contracts thirdparty_id(" . $thirdparty_id . ")\n";
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

        echo "procesando contracts (" . sizeof($contracts) . ")\n";
        foreach ((array) $contracts as $contract) {

            // crea folder de cliente en modulo suscriptores                        
            if (isset($contract['ref'])) {
                $suscfolder = $this->Createfolder($this->idmodulesusc, 0, $contract['ref']);

                if ($suscfolder['error'] == "") {
                    //crear disco path
                    $fpath = $this->root_path . '/' . $this->modulosusc->moduleName . '/' . $suscfolder['data']->folderName;
                    $vpath = $this->root_vpath . '/' . $this->modulosusc->moduleName . '/' . $suscfolder['data']->folderName . '/';

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

                    if (isset($proposals["error"]) && $proposals["error"]["code"] >= 300) {
                        echo "procesando documents contract (0)\n";
                    } else {

                        echo "procesando documents contract (" . sizeof($documents) . ")\n";
                        foreach ((array) $documents as $document) {
                            //var_dump($document);
                            if (isset($document['name'])) {
                                $name = $document['name'];
                                $names = explode('.', $name);
                                $ext = end($names);
                                if ($ext === 'odt' || $ext === 'json') {
                                    //no inserta tipo odt, json
                                } else {
                                    $newdocument = new Document();
                                    $newdocument->attributes = $document;
                                    $newdocument->date = isset($document['date']) ? gmdate("Y-m-d H:i:s", $document['date']) : "";
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
            }
        }
    }

    /*
     * Process proposals
     */

    public function processproposals($thirdparty_id) {
        // consulta proposal
        echo "consultando proposals thirdparty_id(" . $thirdparty_id . ")\n";
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

        echo "procesando proposals (" . sizeof($proposals) . ")\n";
        foreach ((array) $proposals as $proposal) {

            if (isset($proposal['ref'])) {
                // crea folder de cliente en modulo suscriptores                        
                $suscfolder = $this->Createfolder($this->idmodulesusc, 0, $proposal['ref']);

                if ($suscfolder['error'] == "") {
                    //crear disco path
                    $fpath = $this->root_path . '/' . $this->modulosusc->moduleName . '/' . $suscfolder['data']->folderName;
                    $vpath = $this->root_vpath . '/' . $this->modulosusc->moduleName . '/' . $suscfolder['data']->folderName . '/';

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

                    if (isset($proposals["error"]) && $proposals["error"]["code"] >= 300) {
                        echo "procesando documents contract (0)\n";
                    } else {
                        echo "procesando documents proposal (" . sizeof($documents) . ")\n";
                        foreach ((array) $documents as $document) {
                            //var_dump($document);                 
                            if (isset($document['name'])) {
                                $newdocument = new Document();
                                $newdocument->attributes = $document;
                                $newdocument->date = isset($document['date']) ? gmdate("Y-m-d H:i:s", $document['date']) : "";
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
        }
    }

    /*
     * Process invoices
     */

    public function processinvoices($thirdparty_id) {
        // consulta invoices
        echo "consultando invoices thirdparty_id(" . $thirdparty_id . ")\n";
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

        echo "procesando invoices (" . sizeof($invoices) . ")\n";
        foreach ((array) $invoices as $invoice) {

            if (isset($invoice['ref'])) {
                // crea folder de cliente en modulo suscriptores                        
                $suscfolder = $this->Createfolder($this->idmodulefact, 0, $invoice['ref']);

                if ($suscfolder['error'] == "") {
                    //crear disco path
                    $fpath = $this->root_path . '/' . $this->modulofact->moduleName . '/' . $suscfolder['data']->folderName;
                    $vpath = $this->root_vpath . '/' . $this->modulofact->moduleName . '/' . $suscfolder['data']->folderName . '/';

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

                    if (isset($documents["error"]) && $documents["error"]["code"] >= 300) {
                        echo "procesando documents invoices (0)\n";
                    } else {
                        echo "procesando documents invoices (" . sizeof($documents) . ")\n";
                        foreach ((array) $documents as $document) {
                            //var_dump($document);      
                            if (isset($document['name'])) {
                                $name = $document['name'];

                                if ($name === 'response.json') {
                                    $newdocument = new Document();
                                    $newdocument->attributes = $document;
                                    $newdocument->date = isset($document['date']) ? gmdate("Y-m-d H:i:s", $document['date']) : "";
                                    $newdocument->iddocumentType = 4; // documento invoices
                                    $newdocument->idFolder = $suscfolder['data']->idfolder;
                                    $newdocument->type = 'pending';
                                    $newdocument->path = $fpath;
                                    $newdocument->name = $document['level1name'] . '.pdf';
                                    $newdocument->relativename = $vpath . $document['level1name'] . '.pdf'; // $document['name'];
                                    $newdocument->save(false);
                                }

//                                $names = explode('.', $name );
//                                $ext = end($names);
//                                if($ext === 'odt' || $ext === 'json'){
//                                    //no inserta tipo odt, json
//                                }else{
//
//                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /*
     * Process tickets
     */

    public function processtickets($thirdparty_id) {
        // consulta tickets
        echo "consultando tickets thirdparty_id(" . $thirdparty_id . ")\n";
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

        echo "procesando tickets (" . sizeof($tickets) . ")\n";
        foreach ((array) $tickets as $ticket) {

            if (isset($ticket['ref'])) {
                // crea ticket
                $newticket = new Tickets();
                $newticket->id = $ticket['id'];
                $newticket->socid = $ticket['socid'];
                $newticket->ref = $ticket['ref'];
                $newticket->fk_soc = $ticket['fk_soc'];
                $newticket->subject = $ticket['subject'];
                $newticket->message = $ticket['message'];
                $newticket->message = str_replace(array("\n", "\r", "\r\n", "\xE2\x80\x9010", "\xE2\x80\xAC"), ' ', $ticket['message']);                  
                $newticket->type_label = $ticket['type_label'];
                $newticket->category_label = $ticket['category_label'];
                $newticket->severity_label = $ticket['severity_label'];
                $newticket->datec = $ticket['datec'];
                $newticket->date_read = $ticket['date_read'];
                $newticket->date_close = $ticket['date_close'];
                $newticket->messages = '';

                $newticket->save(false);

                $this->processticketdetail($ticket['id']);
            }
        }
    }

    /*
     * Process ticket detail
     */

    public function processticketdetail($ticket_id) {
        // consulta tickets
        echo "consultando tickets ticket_id (" . $ticket_id . ")\n";
        $ticket = json_decode($this->CallAPI("GET", "tickets/" . $ticket_id, array("id" => $ticket_id)), true);

        if (isset($ticket["error"]) && $ticket["error"]["code"] >= 300) {
            echo "procesando tickets (0)\n";
            return;
        }
        if (isset($ticket['ref'])) {
            // crea ticket
            $currentticket = Tickets::find()->where(['id' => $ticket_id])->one();
            if (isset($ticket['messages'])) {
                $currentticket->messages = json_encode($ticket['messages']);
                $currentticket->save(false);
            }
        }
    }

    /*
     * Sincroniza archivos
     */

    public function syncFiles() {
        echo "----------------------------------------\n";
        echo "Inicio syncdodumentos " . date("Y-m-d H:i:s") . "\n";
        $documents = Document::find()->where(['type' => 'pending'])->all();
        echo "procesando documents (" . sizeof($documents) . ")\n";
        foreach ($documents as $document) {
            //consulta documento
            $modulepart = "";

            if ($document['iddocumentType'] === 4) { // invoice
                $modulepart = "facture";

                // consulta documento download
                $download = json_decode($this->CallAPI("GET", "documents/download", array(
                            "modulepart" => $modulepart,
                            "original_file" => $document['level1name'] . "/" . 'response.json')
                        ), true);

                if (isset($download["error"]) && $download["error"]["code"] >= "300") {
                    echo "Error download " . $document['level1name'] . "/" . 'response.json' . " - " . $download["error"]["message"] . "\n";
                } else {

                    $content = json_decode(base64_decode($download["content"]), true);
                    if (isset($content["resultado"]["url_representacion_grafica"])) {
                        //actualiza document
                        $document->type = 'application/pdf';
                        //$document->fileUploadedUserId = -1;
                        $document->save();

                        $url = $content["resultado"]["url_representacion_grafica"];
                        $path = $document['path'] . "/" . $document['name'];

                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_REFERER, 'https://www.sedi.ca/sedi/SVTReportsAccessController?menukey=15.03.00&locale=en_CA');

                        $data = curl_exec($ch);

                        $result = file_put_contents($path, $data);

                        if (!$result) {
                            echo "Error download " . $document['level1name'] . "/" . $document['name'] . " - " . curl_error($ch) . "\n";
                        }

                        curl_close($ch);
                    }
                }
            } else {
                if ($document['iddocumentType'] === 2) { // contract
                    $modulepart = "contract";
                }
                if ($document['iddocumentType'] === 3) { // proposal
                    $modulepart = "propale";
                }
                // consulta documento download
                $download = json_decode($this->CallAPI("GET", "documents/download", array(
                            "modulepart" => $modulepart,
                            "original_file" => $document['level1name'] . "/" . $document['name'])
                        ), true);

                if (isset($download["error"]) && $download["error"]["code"] >= "300") {
                    echo "Error download " . $document['level1name'] . "/" . $document['name'] . " - " . $download["error"]["message"] . "\n";
                } else {
                    //actualiza document
                    $document->type = $download["content-type"];
                    //$document->fileUploadedUserId = -1;
                    $document->save();

                    //guarda bas64
                    $this->base64ToFile($download["content"], $document['path'] . "/" . $document['name']);
                }
            }
        }
        echo "Fin syncdodumentos " . date("Y-m-d H:i:s") . "\n";
    }

    function base64ToFile($base64_string, $output_file) {
        $file = fopen($output_file, "wb");
        fwrite($file, base64_decode($base64_string));
        fclose($file);

        return $output_file;
    }

    /*     * ********UMBRELLA ********* */

    /*
     * ejecucion API umbrella
     */

    function callAPIUmbrella($method, $entity, $data = false) {
        //prod
        $url = 'https://megaya.lavenirapps.co/api/' . $entity;
        //dev
        //$url = 'http://dev-umbrellav2.lavenirapps.co/web/api/' . $entity;
        $curl = curl_init();
        $httpheader = [];

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

        // Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "visor:b6b9bb58d10c866a7ed07504e28ba831");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        if ($result === false) {
            $err = 'Curl error: ' . curl_error($curl);
            //$output_array[] = array('code' => 0, 'message' => $err);

            echo 'EIASAVISOR UMBRELLA - CURLError:' . $err . '\n';
            echo 'EIASAVISOR UMBRELLA - MESSAGE:' . json_encode($data) . "\n";
        }

        curl_close($curl);

        return $result;
    }

    public function actionSyncnetwork() {
        echo "Inicio cron job network \n"; // your logic for deleting old post goes here

        echo "Borrando datos locales \n";

        Yii::$app->db->createCommand()->truncateTable('hsstock')->execute();
        Yii::$app->db->createCommand()->truncateTable('hstask')->execute();
        Yii::$app->db->createCommand("DELETE FROM document where fileUploadedUserId is null and path like '/eiasadocs/Suscriptores/TS%' ")->execute();
        Yii::$app->db->createCommand("DELETE FROM folder where folderdefault > 0 and folderName like 'TS%' ")->execute();

        shell_exec('sudo find /eiasadocs/Suscriptores/. -maxdepth 1 -name "TS*" -exec rm -r {} \;');

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

        $this->syncInventory();
        $this->syncTasks();

        // sincroniza archivos instalacion
        echo "Sincronizando Archivos Instalacion...\n"; // your logic for deleting old post goes here        
        $this->syncInstalationfiles();

        exit();
    }

    /*
     * Sincroniza inventario
     */

    public function syncInventory() {
        echo "----------------------------------------\n";
        echo "Inicio syncinventory " . date("Y-m-d H:i:s") . "\n";
        $inventories = json_decode($this->callAPIUmbrella("POST", "hsStock", json_encode(array(
                    //"datecreate" => "t.rowid",
                    "rows" => "1",
                    "page" => "0"
                                )
                )), true);

        if ($inventories["code"] === '0') {
            echo "procesando inventario error " . $inventories['error'] . " \n";
            return;
        }

        $filter = $inventories["filter"];
        $rows = 1000;
        $total = (int) $filter["items"];
        if ($total == 0) {
            echo "procesando total inventario (0)\n";
            return;
        }

        $pages = ceil($total / $rows);

        echo "procesando total inventario (" . $total . ")\n";
        echo "procesando paginas inventario (" . $pages . ")\n";

        for ($i = 0; $i < $pages; $i++) {
            $inventories = json_decode($this->callAPIUmbrella("POST", "hsStock", json_encode(array(
                        //"datecreate" => "t.rowid",
                        "rows" => $rows,
                        "page" => $i
                                    )
                    )), true);

            if ($inventories["code"] === '0') {
                echo "procesando inventario pagina ($i) error " . $inventories['error'] . " \n";
                continue;
            }
            echo "procesando inventario pagina ($i) (" . sizeof($inventories["data"]) . ")\n";

            foreach ((array) $inventories["data"] as $inv) {
                // crea inventario
                $newinv = new Hsstock();
                $newinv->id = $inv['id'];
                $newinv->pid = $inv['pid'];
                $newinv->uuid = $inv['uuid'];
                $newinv->name = $inv['name'];
                $newinv->factory = $inv['factory'];
                $newinv->model = $inv['model'];
                $newinv->datecreate = $inv['datecreate'];
                $newinv->sku = $inv['sku'];
                $newinv->health_reg = $inv['health_reg'];
                $newinv->quantity = $inv['quantity'];
                $newinv->measure = $inv['measure'];
                $newinv->location = $inv['location'];
                $newinv->city = $inv['city'];
                $newinv->city_code = $inv['city_code'];
                $newinv->district = $inv['district'];
                $newinv->district_code = $inv['district_code'];
                $newinv->lat = $inv['lat'];
                $newinv->lng = $inv['lng'];

                $newinv->save(false);
            }
        }

        echo "Fin syncinventory " . date("Y-m-d H:i:s") . "\n";
    }

    /*
     * Sincroniza servicios
     */

    public function syncTasks() {
        echo "----------------------------------------\n";
        echo "Inicio synctasks " . date("Y-m-d H:i:s") . "\n";
        $tasks = json_decode($this->callAPIUmbrella("POST", "hsTask", json_encode(array(
                    //"datecreate" => "t.rowid",
                    "rows" => "1",
                    "page" => "0"
                                )
                )), true);

        if ($tasks["code"] === '0') {
            echo "procesando tasks error " . $tasks['error'] . " \n";
            return;
        }

        $filter = $tasks["filter"];
        $rows = 1000;
        $total = (int) $filter["items"];
        if ($total == 0) {
            echo "procesando total tasks (0)\n";
            return;
        }

        $pages = ceil($total / $rows);

        echo "procesando total tasks (" . $total . ")\n";
        echo "procesando paginas tasks (" . $pages . ")\n";

        for ($i = 0; $i < $pages; $i++) {
            $tasks = json_decode($this->callAPIUmbrella("POST", "hsTask", json_encode(array(
                        //"datecreate" => "t.rowid",
                        "rows" => $rows,
                        "page" => $i
                                    )
                    )), true);

            if ($tasks["code"] === '0') {
                echo "procesando tasks pagina ($i) error " . $tasks['error'] . " \n";
                continue;
            }
            echo "procesando tasks pagina ($i) (" . sizeof($tasks["data"]) . ")\n";

            foreach ((array) $tasks["data"] as $task) {
                // crea task
                $newtask = new Hstask();
                $newtask->uuid = $task['uuid'];
                $newtask->datecreate = $task['datecreate'];
                $newtask->dateupdate = $task['dateupdate'];
                $newtask->reference = $task['reference'];
                $newtask->template = $task['template'];
                $newtask->address = $task['address'];
                $newtask->city = $task['city'];
                $newtask->district = $task['district'];
                $newtask->code = $task['code'];
                $newtask->status = $task['status'];
                $newtask->pdf = $task['pdf'];
                $newtask->account = $task['account'];
                $newtask->account_id = $task['account_id'];
                $newtask->save(false);
            }
        }

        echo "Fin synctasks " . date("Y-m-d H:i:s") . "\n";
    }

    /*
     * Sincroniza archivos instalacion
     */

    public function syncInstalationfiles() {
        echo "----------------------------------------\n";
        echo "Inicio syncdocinstalacion " . date("Y-m-d H:i:s") . "\n";

        Yii::$app->db->createCommand('UPDATE hstask
                                        INNER JOIN client ON client.idprof1 = hstask.account_id 
                                        SET hstask.socid = client.ref
                                        WHERE hstask.socid is null')->execute();

        $hstasks = Hstask::find()->andWhere(['IS NOT', 'socid', new \yii\db\Expression('null')])->all();
        //$hstasks = Hstask::find()->where(['in', 'uuid', ['5FAB9C-AF832','5FAB9C-AF85B']])->all();

        echo "procesando docinstalacions (" . sizeof($hstasks) . ")\n";
        $i = 0;
        foreach ($hstasks as $hstask) {
            $i = $i +1;
            echo $i.": ". round(($i*100)/sizeof($hstasks),2). "% - $hstask->idFolder\n";
            if (empty($hstask->idFolder)) {
                echo "procesando docinstalacions (" . 'TS' . $hstask['uuid'] . ")\n";
                //consulta documento
                // crea folder de cliente en modulo suscriptores                        
                $suscfolder = $this->Createfolder($this->idmodulesusc, 0, 'TS' . $hstask['uuid']);
                
                if ($suscfolder['error'] == "") {
                    //crear disco path
                    $fpath = $this->root_path . '/' . $this->modulosusc->moduleName . '/' . $suscfolder['data']->folderName;
                    $vpath = $this->root_vpath . '/' . $this->modulosusc->moduleName . '/' . $suscfolder['data']->folderName . '/';

                    // actualiza task 
                    $hstask->idFolder = $suscfolder['data']->idfolder;
                    $hstask->save(false);

                    // descarga documento
                    // Initialize the cURL session 
                    $ch = curl_init($hstask['pdf']);

                    // Save file into file location 
                    $save_file_loc = $fpath . '/' . $hstask['uuid'] . '.pdf';

                    // Open file  
                    $fp = fopen($save_file_loc, 'wb');

                    // It set an option for a cURL transfer 
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_HEADER, 0);

                    // Perform a cURL session 
                    curl_exec($ch);

                    // Closes a cURL session and frees all resources 
                    curl_close($ch);

                    // Close file 
                    fclose($fp);

                    // crea documentos intalacion
                    $newdocument = new Document();
                    $newdocument->name = $hstask['uuid'] . '.pdf';
                    $newdocument->path = $fpath;
                    $newdocument->level1name = $hstask['uuid'];
                    $newdocument->relativename = $vpath . $hstask['uuid'] . '.pdf';
                    $newdocument->fullname = $save_file_loc;
                    $newdocument->date = date("Y-m-d H:i:s");
                    $newdocument->size = filesize($save_file_loc);
                    $newdocument->type = 'application/pdf';
                    $newdocument->iddocumentType = 5; // documento instalacion
                    $newdocument->idFolder = $suscfolder['data']->idfolder;

                    $newdocument->save(false);
                }else{
                    echo 'error: ('. $suscfolder['error']. ")\n";
                }
            }
        }
        echo "Fin syncdocinstalacion " . date("Y-m-d H:i:s") . "\n";
    }

}

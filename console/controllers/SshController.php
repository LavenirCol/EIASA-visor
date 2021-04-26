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
use app\models\TraficoOlts;
use app\models\TraficoServicesPort;
use app\models\TraficoServicesHistory;
use app\models\TraficoOltHistory;
use common\models\RabbitMQ;
use console\controllers\ComponentSSH;
use Exception;

class SshController extends Controller {

    public $root_path = "";
    public $root_vpath = "";
    public $idmodulesusc = 1; //suscriptores
    public $idmodulefact = 2; //facturacion
    public $modulosusc;
    public $modulofact;
    public $defaultAction = 'exec';
    public $ssh;
    public $pagebreak = '  -----------------------------------------------------------------------------';

    public function beforeAction($action) {
        date_default_timezone_set('America/Bogota');
        return parent::beforeAction($action);
    }

    public function actionExec() {

        // consulta OLT's en estado activo
        $olts = TraficoOlts::find()->where(['activo' => 1])->orderBy('id')->all();

        echo "Procesando olts (" . sizeof($olts) . ")\n";
        foreach ($olts as $olt) {
            echo "Procesando olt (" . $olt['id'] . " - " . $olt['poblacion'] . ") \n";

            //procesa services port
            $this->processServicePort($olt);

            // consulta copnfiguracion seriales
            $this->processServicePortConfig($olt);

        }
    }

    public function actionExecolt() {

        // consulta OLT's en estado activo
        $olts = TraficoOlts::find()->where(['activo' => 1])->orderBy('id')->all();

        echo "Procesando olts (" . sizeof($olts) . ")\n";
        foreach ($olts as $olt) {
            echo "Procesando olt (" . $olt['id'] . " - " . $olt['poblacion'] . ") \n";

            //procesa trafico global OLT
            $this->processOltTraffic($olt);

        }
    }
    
    public function processServicePort($olt) {

        // conecta ssh        
        $this->makesshaction('open', $olt);

        // consulta puertos en servicio
        $this->ssh->shellCmd(['display service-port all'], true);
        //$idx = 1;
        while (str_contains($this->ssh->getLastOutput(), '---- More') == true) {
            $this->ssh->shellCmd([' '], true);

            //for test purposes
//            $idx = $idx + 1;
//            if ($idx == 2) {
//                break;
//            }
        }
        //procesa puertos en servicio
        $output = $this->ssh->getOutput();

        try {

            foreach ($output as $line) {

                $line = str_replace("/", " ", $line);
                $line = str_replace("  ", " ", $line);
                $linearr = explode(' ', $line);

                $index = $linearr[0];
                $current_state = $linearr[13];

                $service = TraficoServicesPort::find()->where(['index' => $index,'id_olt' => $olt['id']])->one();

                if (isset($service)) {
                    $service->last_state = $current_state;
                    $service->updated_at = date("Y-m-d H:i:s");
                    $service->save();
                } else {
                    $newservice = new TraficoServicesPort();
                    $newservice->index = $index;
                    $newservice->id_olt = $olt['id'];
                    $newservice->vlan_id = $linearr[1];
                    $newservice->vlan_attr = $linearr[2];
                    $newservice->port_type = $linearr[3];
                    $newservice->frame = $linearr[4];
                    $newservice->slot = $linearr[5];
                    $newservice->port = $linearr[6];
                    $newservice->vpi = $linearr[7];
                    $newservice->vci = $linearr[8];
                    $newservice->flow_type = $linearr[9];
                    $newservice->flow_para = $linearr[10];
                    $newservice->rx = $linearr[11];
                    $newservice->tx = $linearr[12];
                    $newservice->last_state = $current_state;
                    $newservice->created_at = date("Y-m-d H:i:s");
                    $newservice->save();
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

        // cierra ssh        
        $this->makesshaction('close', $olt);
    }

    public function processServicePortConfig($olt) {
        $onlypattern = array('display current-configuration ont','sn-auth','display traffic', '                ');
        // conecta ssh        
        $this->makesshaction('open', $olt);

        // consulta puertos en servicio en bd
        $services = TraficoServicesPort::find()->where(['id_olt' => $olt['id']])->orderby('index')->all();//->limit(15)->all();
        foreach ($services as $service) {
            //consulta seriales            
            $cmd = sprintf('display current-configuration ont %s/%s/%s %s', $service->frame, $service->slot, $service->port, $service->vpi);
            $this->ssh->shellCmd([$cmd], true, $onlypattern);
            //consulta trafico
            $cmd = sprintf('display traffic service-port %s', $service->index);
            $this->ssh->shellCmd([$cmd], true, $onlypattern);
        }

        //last cmd
        $cmd = sprintf('display traffic service-port %s', 0);
        $this->ssh->shellCmd([$cmd], true, $onlypattern);
        
        $results = array_unique($this->ssh->getOutput());
        $output = array_values($results);
        var_dump($output);
        
        foreach ($services as $service) {
            try{
                $cmd = sprintf('display current-configuration ont %s/%s/%s %s', $service->frame, $service->slot, $service->port, $service->vpi);
                $linecmd = array_search($cmd,$output,true);
                $sn_auth = str_replace('"','',explode(' ',$output[$linecmd+1])[5]);          
                $port = explode(' ',$output[$linecmd+3])[0];
                $ups = explode(' ',$output[$linecmd+3])[1];
                $down = explode(' ',$output[$linecmd+3])[2];

                $service->last_sn = $sn_auth;
                $service->updated_at = date("Y-m-d H:i:s");
                $service->save();

                $newserviceh = new TraficoServicesHistory();
                $newserviceh->service_port = $port;
                $newserviceh->ont_id = $service->vpi;
                $newserviceh->state = $service->last_state;
                $newserviceh->sn = $sn_auth;
                $newserviceh->downstream = $down;
                $newserviceh->upstream = $ups;
                $newserviceh->created_at = $service->updated_at;
                $newserviceh->save();
            }catch(Exception $ex){
                echo $ex->getMessage();
            }
        }
        
        // cierra ssh        
        $this->makesshaction('close', $olt);
    }

    public function processOltTraffic($olt) {

        $onlypattern = array('common');
        // conecta ssh        
        $this->makesshaction('open', $olt);
        // consulta puertos en servicio
        $this->ssh->shellCmd(['display vlan all'], true, $onlypattern);
        //procesa puertos en servicio
        $output = $this->ssh->getOutput();
        // cierra ssh        
        $this->makesshaction('close', $olt);
        
        try {
           
            foreach ($output as $line) {

                $line = str_replace("  ", " ", $line);
                $linearr = explode(' ', $line);

                $vlan = $linearr[0];
                $serv_port = $linearr[4];
                
                if (isset($serv_port)) {
                    if(is_numeric($serv_port) === false){
                        continue;
                    }
                    if(intval($serv_port)  === 0){
                        continue;
                    }
                    
                    // ejecuta comando de trafico                    
                    $onlypatternt = array('%');
                    // conecta ssh        
                    $this->makesshaction('open', $olt);
                    // consulta puertos en servicio
                    $this->ssh->shellCmd(['display traffic vlan ' . $vlan], true, $onlypatternt);
                    //procesa puertos en servicio
                    $outputt = $this->ssh->getOutput();
                    // cierra ssh        
                    $this->makesshaction('close', $olt);
        
                    foreach ($outputt as $linet) {
                        $linet = str_replace("  ", " ", $linet);
                        $linetarr = explode(' ', $linet);

                        $newolth = new TraficoOltHistory();
                        $newolth->olt_id = $olt['id'];
                        $newolth->vlan = $vlan;
                        $newolth->serv_port = $serv_port;
                        $newolth->upstream = $linetarr[1];
                        $newolth->downstream = $linetarr[2];
                        $newolth->upstream_occupied = $linetarr[3];
                        $newolth->downstream_occupied = $linetarr[4];
                        $newolth->created_at = date("Y-m-d H:i:s");
                        $newolth->save();
                    }
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }


    }
    
    public function makesshaction($action, $olt) {

        switch ($action) {
            case 'open': {
                    //realiza conexion
                    $this->ssh = new ComponentSSH($olt['wan_olt'], $olt['username'], $olt['password'], $olt['puerto']);
                    $this->ssh->openStream();
                    // habilita modo configcls
                    $this->ssh->shellCmd(['enable', 'config', 'scroll 512']);
                    break;
                }
            case 'close': {
                    //cierra el stream
                    $this->ssh->closeStream();
                    // desconecta terminal
                    $this->ssh->disconnect();
                    break;
                }
        }
    }

}

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
use app\models\TraficoOltStatusHistory;
use app\models\TraficoOltsPing;
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

    /// Accion que procesa puertos de servicio

    public function actionExecport() {

        // consulta OLT's en estado activo
        $olts = TraficoOlts::find()->where(['activo' => 1])->orderBy('id')->all();

        echo date("Y-m-d H:i:s") . " - " . "Procesando olts (" . sizeof($olts) . ")\n";
        foreach ($olts as $olt) {
            echo date("Y-m-d H:i:s") . " - " . "Procesando olt (" . $olt['id'] . " - " . $olt['poblacion'] . ") \n";

            if ($this->ping($olt['wan_olt'])) {
                //procesa services port
                $this->processServicePort($olt);
            } else {
                echo 'OLT sin conexion';
            }
        }
    }

    /// Accion que procesa trafico global OLT

    public function actionExecolt() {

        // consulta OLT's en estado activo
        $olts = TraficoOlts::find()->where(['activo' => 1])->orderBy('id')->all();

        echo date("Y-m-d H:i:s") . " - " . "Procesando olts (" . sizeof($olts) . ")\n";
        foreach ($olts as $olt) {
            echo date("Y-m-d H:i:s") . " - " . "Procesando olt (" . $olt['id'] . " - " . $olt['poblacion'] . ") \n";

            if ($this->ping($olt['wan_olt'])) {
                //procesa trafico global OLT
                $this->processOltTraffic($olt);
            } else {
                echo 'OLT sin conexion';
            }
        }
    }

    /// Accion que procesa detalle de configuracion de puerto

    public function actionExecportconfig() {

        // consulta OLT's en estado activo
        $olts = TraficoOlts::find()->where(['activo' => 1])->orderBy('id')->all();

        echo date("Y-m-d H:i:s") . " - " . "Procesando olts (" . sizeof($olts) . ")\n";
        foreach ($olts as $olt) {
            echo date("Y-m-d H:i:s") . " - " . "Procesando olt (" . $olt['id'] . " - " . $olt['poblacion'] . ") \n";

            if ($this->ping($olt['wan_olt'])) {
                // execute external action
                if ($this->myOS()) {
                    //windows - desarrollo
                    $cmd = 'cd D:\wamp32\www\wwweiasa && yii ssh/execportconfigdetail ' . $olt['id'];
                    $outputfile = 'sshportconfig_' . $olt['id'] . '.log';
                    $pidfile = 'sshportconfigpid_' . $olt['id'] . '.log';
                    exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));
                } else {
                    //linux
                    $cmd = 'cd /var/www/html && sudo /usr/bin/php yii ssh/execportconfigdetail ' . $olt['id'];
                    $outputfile = 'sshportconfig_' . $olt['id'] . '_`date +\%Y-\%m-\%d_\%H:\%M:\%S`.log';
                    $pidfile = 'sshportconfigpid_' . $olt['id'] . '_`date +\%Y-\%m-\%d_\%H:\%M:\%S`.log';
                    exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));
                }
            } else {
                echo 'OLT sin conexion';
            }
        }
    }

    /// Accion qu eprocesa el detalle de una OLT
    public function actionExecportconfigdetail($oltid) {
        // consulta OLT's en estado activo
        $olt = TraficoOlts::find()->where(['id' => $oltid])->one();
        echo date("Y-m-d H:i:s") . " - " . "Procesando detalle olt (" . $olt['id'] . " - " . $olt['poblacion'] . ") \n";
        // consulta copnfiguracion seriales
        $this->processServicePortConfig($olt);
    }

    /// Accion que procesa los estados de coenxion de cada OLT
    public function actionExecoltstatus() {

        // consulta OLT's en estado activo
        $olts = TraficoOlts::find()->where(['activo' => 1])->orderBy('id')->all();

        echo date("Y-m-d H:i:s") . " - " . "Procesando olts (" . sizeof($olts) . ")\n";
        foreach ($olts as $olt) {
            echo date("Y-m-d H:i:s") . " - " . "Procesando olt (" . $olt['id'] . " - " . $olt['poblacion'] . ") \n";

            //if($this->ping($olt['wan_olt'])){
            // consulta copnfiguracion seriales
            $this->processOltStatus($olt);
            //}else{
            //    echo 'OLT sin conexion';
            //}           
        }
    }

    /// Accion que procesa los ping de coenxion de cada OLT
    public function actionExecpingstatus() {
       
        // consulta OLT's en estado activo
        $olts = TraficoOlts::find()->where(['activo' => 1])->orderBy('id')->all();

        echo date("Y-m-d H:i:s") . " - " . "Procesando olts (" . sizeof($olts) . ")\n";
        foreach ($olts as $olt) {
            echo date("Y-m-d H:i:s") . " - " . "Procesando olt (" . $olt['id'] . " - " . $olt['poblacion'] . ") \n";

            $result = $this->pinglost($olt['wan_olt']);
            $this->processOltStatusLost($olt, $result);

        }
    }
    
    /// Consulta puertos de servicio por cada OLT
    public function processServicePort($olt) {

        $onlypattern = array('gpon');

        // conecta ssh        
        $this->makesshaction('open', $olt);

        // consulta puertos en servicio
        $this->ssh->shellCmd(['display service-port all'], true, $onlypattern);
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

                $service = TraficoServicesPort::find()->where(['index' => $index, 'id_olt' => $olt['id']])->one();

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
                    $newservice->vpi = $linearr[7]; //
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
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

        // cierra ssh        
        $this->makesshaction('close', $olt);
    }

    /// Consulta detalle de puertos de servicio por OLT
    public function processServicePortConfig($olt) {
        $onlypattern = array('display current-configuration ont', 'sn-auth', 'display traffic', '                ');

        // fetch 10 services at a time
        // consulta puertos en servicio en bd
        foreach (TraficoServicesPort::find()->where(['id_olt' => $olt['id']])->orderby('index')->batch(10) as $services) {
            
            //TODO:new thread
            echo date("Y-m-d H:i:s")." - Procesando (" . $olt['id'] . ") services ".sizeof($services) . PHP_EOL;
            // conecta ssh        
            $this->makesshaction('open', $olt);

            // consulta puertos en servicio en bd
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
            //var_dump($output);
            // cierra ssh        
            $this->makesshaction('close', $olt);

            //procesa output
            foreach ($services as $service) {
                try {
                    $cmd = sprintf('display current-configuration ont %s/%s/%s %s', $service->frame, $service->slot, $service->port, $service->vpi);
                    $linecmd = array_search($cmd, $output, true);                    
                    $sn_auth = str_replace('"', '', explode(' ', $output[$linecmd + 1])[5]);
                    $port = explode(' ', $output[$linecmd + 3])[0];
                    $ups = explode(' ', $output[$linecmd + 3])[1];
                    $down = explode(' ', $output[$linecmd + 3])[2];

                    $service->last_sn = $sn_auth;
                    $service->updated_at = date("Y-m-d H:i:s");
                    $service->save();

                    $newserviceh = new TraficoServicesHistory();
                    $newserviceh->service_port = $service->index;
                    $newserviceh->ont_id = $service->vpi;
                    $newserviceh->state = $service->last_state;
                    $newserviceh->sn = $sn_auth;
                    $newserviceh->downstream = $down;
                    $newserviceh->upstream = $ups;
                    $newserviceh->created_at = $service->updated_at;
                    $newserviceh->save();
                } catch (Exception $ex) {
                     echo date("Y-m-d H:i:s")." Error procesando service ".$service->index. " ". $ex->getMessage() . PHP_EOL;
                }
            }
        }
    }

    /// Consulta trafico total de OLT
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
                    if (is_numeric($serv_port) === false) {
                        continue;
                    }
                    if (intval($serv_port) === 0) {
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

    /// Consulta stado de red de cada OLT con ping
    public function processOltStatus($olt) {

        try {
            $status = $this->ping($olt['wan_olt']);
            $newolth = new TraficoOltStatusHistory();
            $newolth->olt_id = $olt['id'];
            $newolth->status = $status ? 1 : 0;
            $newolth->created_at = date("Y-m-d H:i:s");
            $newolth->save();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    /// Consulta stado de red de cada OLT con ping y retorna detalle de ping
    public function processOltStatusLost($olt, $result) {
        var_dump($result);
        try {                      
            $newoltp = new TraficoOltsPing();
            $newoltp->olt_id = $olt['id'];
            $newoltp->created_at = date("Y-m-d H:i:s");
            
            //procesa resultado bytes
            $arrb = explode(" ", $result[0]);
            preg_match('#\((.*?)\)#', $arrb[3], $match);
            $bytes = $match[1];
            $newoltp->bytes_of_data = intval($bytes);
            
            $conteo = count($result);
            
            //statistics
            $arrs = explode(",", $result[$conteo-2]);
            $pt = str_replace(" packets transmitted","",$arrs[0]);
            $newoltp->packets_transmitted = intval($pt);
            
            $pr = trim(str_replace(" received","",$arrs[1]));
            $newoltp->packets_recived = intval($pr);

            $pl = trim(str_replace("% packet loss","",$arrs[2]));
            $newoltp->packets_lost_percent = intval($pl);
            
            $pti = trim(str_replace(array(" time","ms"),"",$arrs[3]));
            $newoltp->packets_time = intval($pti);
                        
            //rtt
            $arrt = explode("/", str_replace(array("rtt min/avg/max/mdev = "," ms"),"",$result[$conteo -1]));
            if(count($arrt) > 3){
                $newoltp->rtt_min = floatval($arrt[0]);
                $newoltp->rtt_avg = floatval($arrt[1]);
                $newoltp->rtt_max = floatval($arrt[2]);
                $newoltp->rtt_mdev = floatval($arrt[3]);
            }else{
                $newoltp->rtt_min = 0;
                $newoltp->rtt_avg = 0;
                $newoltp->rtt_max = 0;
                $newoltp->rtt_mdev = 0;                
            }
            $newoltp->save(false);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /// HELPERS
    /// ejecuta acciones comunes de SSH
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

    // funciones de ping para garficas de equipos centrales y alimentacion electrica
    public function myOS() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === (chr(87) . chr(73) . chr(78)))
            return true;

        return false;
    }

    // ejecuta ping
    public function ping($ip_addr) {
        if ($this->myOS()) {
            if (!exec("ping -n 1 -w 1 " . $ip_addr . " 2>NUL > NUL && (echo 0) || (echo 1)"))
                return true;
        } else {
            if (!exec("ping -q -c1 " . $ip_addr . " >/dev/null 2>&1 ; echo $?"))
                return true;
        }

        return false;
    }

    // ejecuta pinglost
    public function pinglost($ip_addr) {
        // Pings a ejecutar
        $pingscount = 10;
        $pingsinterval = 10;
        $output = "";         
        if ($this->myOS()) {
            // not implemented windows
        } else {
            exec("ping -c $pingscount -w $pingsinterval " . $ip_addr . " ", $output, $status);
        }

        return $output;
    }
}

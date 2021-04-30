<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace console\controllers;

/**
 * Description of ComponentSSH
 *
 * @author jcbob
 */
class ComponentSSH {

    private $host;
    private $user;
    private $pass;
    private $port;
    private $conn = false;
    private $error;
    private $stream;
    private $shellStream;
    private $stream_timeout = 100;
    private $log = array();
    private $lastLog;
    private $bloqued = array('-------', 'INDEX', 'ATTR', 'display service-port', '<cr>', 'Command:', 'Switch',
        "(config)", "Total :", "Note :", "v/e--vlan","ppp--pppoe","FLOW TYPE", "hexadecimal format", "priorities 0-7",
        "significant bit", "FLOW PARA", "binary format");

    public function __construct($host, $user, $pass, $port) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->port = $port;
        $this->log = array();

        if ($this->connect()->authenticate()) {
            return true;
        }
    }

    public function isConnected() {
        return (boolean) $this->conn;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function connect() {
        $this->logAction("Connecting to {$this->host}");
        if ($this->conn = ssh2_connect($this->host, $this->port)) {
            return $this;
        }
        $this->logAction("Connection to {$this->host} failed");
        throw new Exception("Unable to connect to {$this->host}");
    }

    public function authenticate() {
        $this->logAction("Authenticating to {$this->host}");
        if (ssh2_auth_password($this->conn, $this->user, $this->pass)) {
            return $this;
        }
        $this->logAction("Authentication to {$this->host} failed");
        throw new Exception("Unable to authenticate to {$this->host}");
    }

    public function sendFile($localFile, $remoteFile, $permision = 0644) {
        if (!is_file($localFile))
            throw new Exception("Local file {$localFile} does not exist");
        $this->logAction("Sending file $localFile as $remoteFile");

        $sftp = ssh2_sftp($this->conn);
        $sftpStream = @fopen('ssh2.sftp://' . $sftp . $remoteFile, 'w');
        if (!$sftpStream) {
            //  if 1 method failes try the other one
            if (!@ssh2_scp_send($this->conn, $localFile, $remoteFile, $permision)) {
                throw new Exception("Could not open remote file: $remoteFile");
            } else {
                return true;
            }
        }

        $data_to_send = @file_get_contents($localFile);

        if (@fwrite($sftpStream, $data_to_send) === false) {
            throw new Exception("Could not send data from file: $localFile.");
        }

        fclose($sftpStream);

        $this->logAction("Sending file $localFile as $remoteFile succeeded");
        return true;
    }

    public function getFile($remoteFile, $localFile) {
        $this->logAction("Receiving file $remoteFile as $localFile");
        if (ssh2_scp_recv($this->conn, $remoteFile, $localFile)) {
            return true;
        }
        $this->logAction("Receiving file $remoteFile as $localFile failed");
        throw new Exception("Unable to get file to {$remoteFile}");
    }

    public function cmd($cmd, $returnOutput = false) {
        $this->logAction("Executing command $cmd");
        $this->stream = ssh2_exec($this->conn, $cmd);

        if (FALSE === $this->stream) {
            $this->logAction("Unable to execute command $cmd");
            throw new Exception("Unable to execute command '$cmd'");
        }
        $this->logAction("$cmd was executed");

        stream_set_blocking($this->stream, true);
        stream_set_timeout($this->stream, $this->stream_timeout);
        $this->lastLog = stream_get_contents($this->stream);

        $this->logAction("$cmd output: {$this->lastLog}");
        fclose($this->stream);
        $this->log .= $this->lastLog . "\n";
        return ( $returnOutput ) ? $this->lastLog : $this;
    }

    public function shellCmd($cmds = array(), $setlog = false, $onlypattern = array()) {
        foreach ($cmds as $cmd) {
            $this->logAction("ssh2 shell command $cmd");
                        
            $out = ''; //. PHP_EOL;
            settype($out, 'string');
            fwrite($this->shellStream, "$cmd"."\n" . PHP_EOL);
            
            if($cmd == " " || str_contains($cmd,"display")){
                sleep(1);
            }else{
                usleep(200000);
            }

            while ($line = fgets($this->shellStream)) {
                $this->lastLog = $line;
                //$this->logAction($line);
                if($cmd == " " || str_contains($cmd,"display")){               
                    usleep(80000);
                }
                //sleep ( 1 );
                if ($setlog == true && strlen($line) > 1 && $this->contains($line, $this->bloqued) == false) {
                    
                    if(sizeof($onlypattern) == 0){
                        $out .= $this->clean_string($line) . '|';
                    }else{
                        if($this->contains($line, $onlypattern) == true){
                            $out .= $this->clean_string($line) . '|';
                        }
                    }                    
                }
            }

            if ($setlog == true && strlen($out) > 0) {
                $results = explode("|", $out);
                //limpia el array
                for ($i = 0; $i < count($results); $i++) {
                    if (strlen($results[$i]) > 0) {
                        $this->log[] = $results[$i];
                    }
                }
            }
            // for debug purposes only
            //$this->logAction("ssh2 shell command $cmd output: $out");

            flush();
        }
    }

    function clean_string($string) {

        //$out = preg_replace("/\s+|[[:^print:]]/", " ", $out);
        $s = str_replace(array("\r\n", '\\n', '\\r', "\n", "\r", "\t", "\0", "\x0B", "\x1B", "\x9B", "[37D ", "---- More ( Press 'Q' to break ) ----"), "", $string);
        $s = trim($s);
        //remove extra tabs and newlines
        $s = preg_replace('/(\xF0\x9F[\x00-\xFF][\x00-\xFF])/', '', $s);
        //$s = preg_replace('/[^\p{L}\s]/u','',$s);
        //$s = preg_replace('/[\000-\031\200-\377]/', '', $s);
        $s = iconv("UTF-8", "UTF-8//IGNORE", $s); // drop all non utf-8 characters
        // this is some bad utf-8 byte sequence that makes mysql complain - control and formatting i think
        $s = preg_replace('/(?>[\x00-\x1F]|\xC2[\x80-\x9F]|\xE2[\x80-\x8F]{2}|\xE2\x80[\xA4-\xA8]|\xE2\x81[\x9F-\xAF])/', ' ', $s);

        $s = preg_replace('/\s+/', ' ', $s); // reduce all multiple whitespace to a single space

        return $s;
    }

    public function contains($str, array $arr) {
        foreach ($arr as $a) {
            if (stripos($str, $a) !== false)
                return true;
        }
        return false;
    }

    public function getLastOutput() {
        return $this->lastLog;
    }

    public function getOutput() {
        return $this->log;
    }
    
    public function clearOutput() {
        $this->log = array();
    }


    public function disconnect() {
        $this->logAction("Disconnecting from {$this->host}");
        // if disconnect function is available call it..
        if (function_exists('ssh2_disconnect')) {
            ssh2_disconnect($this->conn);
        } else { // if no disconnect func is available, close conn, unset var
            @fclose($this->conn);
            $this->conn = false;
        }
        // return null always
        return NULL;
    }

    public function fileExists($path) {
        $output = $this->cmd("[ -f $path ] && echo 1 || echo 0", true);
        return (bool) trim($output);
    }

    public function logAction($strlog) {
        echo date("Y-m-d H:i:s")." - ".$strlog . PHP_EOL;
    }

    public function openStream() {
        $this->logAction("Openning ssh2 shell");
        $this->shellStream = ssh2_shell($this->conn, "xterm");
    }

    public function closeStream() {
        $this->logAction("Closing shell stream");
        fclose($this->shellStream);
    }

}

<?php

namespace frontend\controllers;
use Yii;

class ReportsController extends \yii\web\Controller
{
    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionInventarios()
    {
        return $this->render('inventarios');
    }

    public function actionInstalacion()
    {
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM hstask h limit 0";        
        $inst = $connection->createCommand($sql)->queryAll();

        return $this->render('instalacion', array('inst' => $inst));
    }
    
    public function actionOperacion()
    {
        return $this->render('operacion');
    }
    
    public function actionPqrs()
    {
        $connection = Yii::$app->getDb();
        $sql = "SELECT t.*, c.access_id, c.name, c.town, c.state FROM tickets t inner join client c on t.fk_soc = c.idClient";

        $pqrs = $connection->createCommand($sql)->queryAll();
        
        return $this->render('pqrs', array('pqrs' => $pqrs));
    }
    
    public function actionInstalaciondash()
    {
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM avances_metas_instalacion h";        
        $insts = $connection->createCommand($sql)->queryAll();

        return $this->render('instalaciondash', array('insts' => $insts));
    }
    
    public function actionInstalaciondetails()
    {
        $request = Yii::$app->request;
        $dane = $request->get('dane');
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM sabana_accesos_instalacion h WHERE Dane_Municipio = '$dane' ";        
        $insts = $connection->createCommand($sql)->queryAll();

        return $this->render('instalaciondetails', array('insts' => $insts));
    }
    
    /// Server side
    
    public function actionInventariosserver() {
        
        $requestData = $_REQUEST;
        
        $columns = array(
            0 => 'id',
            1 => 'pid',
            2 => 'name',
            3 => 'sku',
            4 => 'health_reg',
            5 => 'location',
            6 => 'city',
            7 => 'district',
            8 => 'code',
            9 => 'lat',
            10 => 'lng'
        );
        
        $sql = "SELECT `hsstock`.`id`,
                    `hsstock`.`pid`,
                    `hsstock`.`name`,
                    `hsstock`.`sku`,
                    `hsstock`.`health_reg`,
                    `hsstock`.`location`,
                    `hsstock`.`city`,
                    `hsstock`.`district`,
                    `hsstock`.`code`,
                    `hsstock`.`lat`,
                    `hsstock`.`lng`
                FROM `hsstock` where 1=1 ";        

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        
        $totalData = count($data);
        $totalFiltered = $totalData;
     
        if (!empty($requestData['search']['value']))
        {
            $sql.=" AND ( name LIKE '" . $requestData['search']['value'] . "%' ";
            $sql.=" OR sku LIKE '" . $requestData['search']['value'] . "%'";
            $sql.=" OR location LIKE '" . $requestData['search']['value'] . "%'";
            $sql.=" OR city LIKE '" . $requestData['search']['value'] . "%'";
            $sql.=" OR district LIKE '" . $requestData['search']['value'] . "%')";
          
        }
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $totalFiltered = count($data);
       
        $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . 
        $requestData['length'] . "   ";
       
        $result = Yii::$app->db->createCommand($sql)->queryAll();
       
        $data = array();
        foreach ($result as $key => $row)
        {          
            $nestedData = array();
            $nestedData[] = $row["id"];
            $nestedData[] = $row["pid"];
            $nestedData[] = $row["name"];
            $nestedData[] = $row["sku"];
            $nestedData[] = $row["health_reg"];
            $nestedData[] = $row["location"];
            $nestedData[] = $row["city"];
            $nestedData[] = $row["district"];
            $nestedData[] = $row["code"];
            $nestedData[] = $row["lat"];
            $nestedData[] = $row["lng"];
            $data[] = $nestedData;
        }
        
        $json_data = array(
            "draw" => intval($requestData['draw']), 
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }
    
        
    public function actionInstalacionserver() {
        
        $requestData = $_REQUEST;
        
        $columns = array(
            0 => 'uuid',
            1 => 'datecreate',
            2 => 'dateupdate',
            3 => 'reference',
            4 => 'template',
            5 => 'address',
            6 => 'city',
            7 => 'district',
            8 => 'code',
            9 => 'lat',
            10 => 'lng',
            11 => 'status',
            12 => 'pdf'
        );
        
        $sql = "SELECT `hstask`.`uuid`,
                    `hstask`.`reference`,
                    `hstask`.`template`,
                    `hstask`.`address`,
                    `hstask`.`city`,
                    `hstask`.`district`,
                    `hstask`.`code`,
                    `hstask`.`lat`,
                    `hstask`.`lng`,
                    `hstask`.`status`,
                    `hstask`.`pdf`,
                    `hstask`.`datecreate`,
                    `hstask`.`dateupdate`
                FROM `hstask` where 1=1 ";        

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        
        $totalData = count($data);
        $totalFiltered = $totalData;
     
        if (!empty($requestData['search']['value']))
        {
            $sql.=" AND ( uuid LIKE '" . $requestData['search']['value'] . "%' ";
            $sql.=" OR reference LIKE '" . $requestData['search']['value'] . "%'";
            $sql.=" OR address LIKE '" . $requestData['search']['value'] . "%'";
            $sql.=" OR city LIKE '" . $requestData['search']['value'] . "%'";
            $sql.=" OR district LIKE '" . $requestData['search']['value'] . "%')";
          
        }
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $totalFiltered = count($data);
       
        $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . 
        $requestData['length'] . "   ";
       
        $result = Yii::$app->db->createCommand($sql)->queryAll();
       
        $data = array();
        foreach ($result as $key => $row)
        {          
            $nestedData = array();
            $nestedData[] = $row["uuid"];
            $nestedData[] = $row["reference"];
            $nestedData[] = $row["template"];
            $nestedData[] = $row["address"];
            $nestedData[] = $row["city"];
            $nestedData[] = $row["district"];
            $nestedData[] = $row["code"];
            $nestedData[] = $row["lat"];
            $nestedData[] = $row["lng"];
            $nestedData[] = $row["status"];
            $nestedData[] = $row["pdf"];            
            $nestedData[] = $row["datecreate"];
            $nestedData[] = $row["dateupdate"];
            $data[] = $nestedData;
        }
        
        $json_data = array(
            "draw" => intval($requestData['draw']), 
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }
}

<?php

namespace frontend\controllers;
use Yii;
use yii\base\Exception;
use Fpdf\Fpdf;

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
        $connection = Yii::$app->getDb();
        
        $sql = "SELECT distinct city FROM hsstock h order by 1";        
        $deptos = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT distinct district FROM hsstock h order by 1";        
        $mpios = $connection->createCommand($sql)->queryAll();
        
        $sql = "SELECT distinct name FROM hsstock h order by 1";        
        $materials = $connection->createCommand($sql)->queryAll();
        
        $sql = "SELECT distinct factory FROM hsstock h order by 1";        
        $factories = $connection->createCommand($sql)->queryAll();
        
        $sql = "SELECT distinct model FROM hsstock h order by 1";        
        $models = $connection->createCommand($sql)->queryAll();
        
        $sql = "SELECT h.* FROM hsstock h limit 0";        
        $rows = $connection->createCommand($sql)->queryAll();
        
        return $this->render('inventarios', [
            'deptos' => $deptos, 
            'mpios' => $mpios,
            'materials' => $materials, 
            'factories' => $factories, 
            'models' => $models,
            'rows' => $rows
        ]);
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
        
        $sql = "SELECT distinct Departamento as city FROM sabana_reporte_instalacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' limit 1";  
        $deptos = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT distinct Municipio as district FROM sabana_reporte_instalacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' limit 1";
        $mpios = $connection->createCommand($sql)->queryAll();
                
        $sql = "SELECT h.* FROM sabana_reporte_instalacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' ";        
        $insts = $connection->createCommand($sql)->queryAll();
        $municipio = $insts[0]['Departamento'].' - '.$insts[0]['Municipio'];
        return $this->render('instalaciondetails', array(
            'deptos' => $deptos, 
            'mpios' => $mpios, 
            'insts' => $insts, 
            'municipio' => $municipio));
    }

    public function actionOperaciondash()
    {
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM avances_meta_operacion h";        
        $insts = $connection->createCommand($sql)->queryAll();

        return $this->render('operaciondash', array('insts' => $insts));
    }
    
    public function actionOperaciondetails()
    {
        $request = Yii::$app->request;
        $dane = $request->get('dane');
        $connection = Yii::$app->getDb();
        
        $sql = "SELECT distinct Departamento as city FROM sabana_reporte_operacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' limit 1";  
        $deptos = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT distinct Municipio as district FROM sabana_reporte_operacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' limit 1";
        $mpios = $connection->createCommand($sql)->queryAll();
                
        $sql = "SELECT h.* FROM sabana_reporte_operacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' ";        
        $insts = $connection->createCommand($sql)->queryAll();
        $municipio = $insts[0]['Departamento'].' - '.$insts[0]['Municipio'];
        return $this->render('operaciondetails', array(
            'deptos' => $deptos, 
            'mpios' => $mpios, 
            'insts' => $insts, 
            'municipio' => $municipio));
    }
        
    /// Server side
    
    public function actionInventariosserver() {
        
        try{
            $requestData = $_REQUEST;

            $columns = array(
                0 => 'id',
                1 => 'pid',
                2 => 'uuid',
                3 => 'name',
                4 => 'factory',
                5 => 'model',
                6 => 'datecreate',
                7 => 'sku',
                8 => 'health_reg',
                9 => 'quantity',
                10 => 'measure',
                11 => 'location',
                12 => 'city',
                13 => 'city_code',
                14 => 'district',
                15 => 'district_code',
                16 => 'lat',
                17 => 'lng'
            );


            $totalData = Yii::$app->db->createCommand('SELECT COUNT(*) FROM hsstock')->queryScalar();        
            $totalFiltered = $totalData;

            $sql = "SELECT * FROM `hsstock` where 1=1 ";  

            if (!empty($requestData['search']['value']))
            {
                $sql.=" AND ( name LIKE '" . $requestData['search']['value'] . "%' ";
                $sql.=" OR sku LIKE '" . $requestData['search']['value'] . "%'";
                $sql.=" OR location LIKE '" . $requestData['search']['value'] . "%'";
                $sql.=" OR city LIKE '" . $requestData['search']['value'] . "%'";
                $sql.=" OR district LIKE '" . $requestData['search']['value'] . "%')";          
            }

            $pdptos = empty($requestData['dptos'])?'-1':$requestData['dptos'];
            $pmpios = empty($requestData['mpios'])?'-1':$requestData['mpios'];
            $pmaterials = empty($requestData['materials'])?'-1':$requestData['materials'];
            $pfactories = empty($requestData['factories'])?'-1':$requestData['factories'];
            $pmodels = empty($requestData['models'])?'-1':$requestData['models'];

            if($pdptos != '-1'){
                $sql.=" AND city = '" . $pdptos . "'";
            }        
            if($pmpios != '-1'){
                $sql.=" AND district = '" . $pmpios . "'";
            }
            if($pmaterials != '-1'){
                $sql.=" AND name = '" . $pmaterials . "'";
            }
            if($pfactories != '-1'){
                $sql.=" AND factory = '" . $pfactories . "'";
            }
            if($pmodels != '-1'){
                $sql.=" AND model = '" . $pmodels . "'";
            }

            if(!empty($requestData['export'])){


            }else{
                $sqlc = str_replace("*", "COUNT(*)", $sql);
                $totalFiltered = Yii::$app->db->createCommand($sqlc)->queryScalar();

                $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . 
                $requestData['length'] . "   ";
            }


            $result = Yii::$app->db->createCommand($sql)->queryAll();

            $data = array();
            foreach ($result as $key => $row)
            {          
                $nestedData = array();
                $nestedData[] = $row["sku"];
                $nestedData[] = $row["model"];
                $nestedData[] = 'Noroccidente'; //$row["id"];
                $nestedData[] = $row["city_code"] == '' ? '-' : substr($row["city_code"],0,2);
                $nestedData[] = $row["city"] == '' ? '-' : $row["city"];
                $nestedData[] = $row["district_code"];
                $nestedData[] = $row["district"];
                $nestedData[] = $row["location"];
                $nestedData[] = $row["name"];
                $nestedData[] = $row["factory"];
                $nestedData[] = $row["measure"];
                $nestedData[] = $row["quantity"];
                $nestedData[] = $row["lat"] .','. $row["lng"];
                $nestedData[] = $row["city_code"] == '' ? '-' : 'Instalado';
                $nestedData[] = 'Recursos de Fomento';
                $data[] = $nestedData;
            }

            if(!empty($requestData['export'])){
                if($requestData['export'] == 'csv')
                {
                    ob_start();
                    ob_start('ob_gzhandler');
                    header( 'Content-Type: text/csv; charset=windows-1251' );
                    header( 'Content-Disposition: attachment; filename=InventariosExport.csv' );
                    $output = fopen( 'php://output', 'w' );
                    fwrite( $output, "\xEF\xBB\xBF" );
                    fputcsv( $output, [ 'Serial o MAC', 'Modelo', 'Región', 'Código DANE Departamento', 'Departamento', 'Código DANE Municipio', 'Municipio', 'Barrio / Dirección', 'Descripción Material', 'Fabricante', 'Unidad de Medida', 'Cantidad', 'Coordenadas GPS', 'Estado', 'Fuente de Financiación'], ';' );
                    foreach ( $data as $key => $value ) {
                        fputcsv( $output, $value, ';' );
                    }
                    fclose($output);
                    ob_end_flush();
                }
                if($requestData['export'] == 'pdf')
                {
                    $pdf = new Fpdf();
                    /* Column headings */
                    $header = array('Serial o MAC', 'Modelo', 'Región', 'Depto', 'Departamento', 'Mpio', 'Municipio', 'Barrio / Dirección', 'Material', 'Fabricante', 'Unidad', 'Cantidad', 'Coordenadas GPS', 'Estado', 'Fuente de Financiación');
                    /* Data loading */
                    $pdf->AddPage('L','Legal');
                    $pdf->SetFont('Courier','',6);
                    /* Column widths */
                    $w = array(30, 27, 20, 8, 20, 10, 20, 95, 15, 15, 10, 10, 20, 15, 28);
                    /* Header */
                    for($i=0;$i<count($header);$i++){
                       $pdf->Cell($w[$i],7,utf8_decode($header[$i]),1,0,'C');
                    }
                    $pdf->Ln();
                    /* Data */
                    foreach($data as $row)
                    {
                        for($i = 0; $i < 7; $i++){
                            $pdf->Cell($w[$i],6,utf8_decode($row[$i]),'LR');
                        }
                        
                        $barr = utf8_decode($row[7]);
                        if(strlen($barr) > 70){
                            $barr = substr ( $barr , 0 ,70 ).'...';
                        }
                        
                        $pdf->Cell($w[7],6,$barr,'LR');
                         
                        for($i = 8; $i < 15; $i++){
                           $pdf->Cell($w[$i],6,utf8_decode($row[$i]),'LR');
                        }
                        $pdf->Ln();
                    }
                    /* Closing line */
                    $pdf->Cell(array_sum($w),0,'','T');
                    $pdf->Output('D', 'InventariosExport.pdf', true);
                }
            }else{

                $json_data = array(
                    "draw" => intval($requestData['draw']), 
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $data   // total data array
                );

               echo json_encode($json_data);
            }
        }catch (\Exception $ex){
            $returndata = ['error' => $ex->getMessage()];
            echo json_encode($returndata);
        }
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

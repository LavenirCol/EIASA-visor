<?php

namespace frontend\controllers;

use Yii;
use yii\base\Exception;
use Fpdf\Fpdf;
use yii\helpers\Url;
use \yii\db;
use yii\data\Pagination;

class ReportsController extends \yii\web\Controller {

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionInventarios() {
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

    public function actionInstalacion() {
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM hstask h limit 0";
        $inst = $connection->createCommand($sql)->queryAll();

        return $this->render('instalacion', array('inst' => $inst));
    }

    public function actionOperacion() {
        return $this->render('operacion');
    }

    public function actionPqrsdash() {
        $connection = Yii::$app->getDb();
        $sql = "SELECT distinct c.state FROM tickets t inner join client c on t.fk_soc = c.idClient order by 1";
        $deptos = $connection->createCommand($sql)->queryAll();
        $sql = "SELECT distinct c.town FROM tickets t inner join client c on t.fk_soc = c.idClient order by 1";
        $mpios = $connection->createCommand($sql)->queryAll();
 
        return $this->render('pqrsdash', [
                    'deptos' => $deptos,
                    'mpios' => $mpios
        ]);
    }

    public function actionTicketsseverity()
    {
        $connection = Yii::$app->getDb();
        $requestData = $_REQUEST;
        $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
        $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];
        
        $sql = "SELECT category_label, type_label, severity_label, count(*) as conteo FROM tickets t inner join  client c ON t.fk_soc = c.idClient WHERE 1 =1 ";
        
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND ( category_label LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR type_label LIKE '" . $requestData['search']['value'] . "%'";
            $sql .= " OR severity_label LIKE '" . $requestData['search']['value'] . "%')";
        }

        if ($pdptos != '-1') {
            $sql .= " AND state = '" . $pdptos . "'";
        }
        if ($pmpios != '-1') {
            $sql .= " AND town = '" . $pmpios . "'";
        }
        $sql .= " group by category_label, type_label, severity_label order by 1,2,3";        
        $tickets_severity = $connection->createCommand($sql)->queryAll();
        $sql = "SELECT count(*) FROM tickets t inner join  client c ON t.fk_soc = c.idClient WHERE 1 =1";
        $total = $connection->createCommand($sql)->queryScalar();
        $data = array();
        foreach ($tickets_severity as $key => $row) {
            $nestedData = array();
            $nestedData[] = $row["category_label"];
            $nestedData[] = $row["type_label"];            
            $nestedData[] = $row["severity_label"];            
            $nestedData[] = $row["conteo"];
            $data[] = $nestedData;
        }
    

        ob_start();
        ob_start('ob_gzhandler');
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($total),
            "recordsFiltered" => intval(count($tickets_severity)),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
        ob_end_flush();
    }

    public function actionTicketsdays()
    {
        $connection = Yii::$app->getDb();
        $request = Yii::$app->request;
        $pdptos = $request->post('dptos');
        $pmpios = $request->post('mpios');
        //dias promedio abiertos
        $sql = "SELECT ROUND(AVG(DATEDIFF(NOW(), DATE_FORMAT(FROM_UNIXTIME(`datec`), '%Y-%m-%d')) ),2) AS days FROM tickets t  inner join  client c ON t.fk_soc = c.idClient  where t.date_close  = ''";
        if ($pdptos != '-1') {
            $sql .= " AND state = '" . $pdptos . "'";
        }
        if ($pmpios != '-1') {
            $sql .= " AND town = '" . $pmpios . "'";
        }
         
         $daysopen = $connection->createCommand($sql)->queryOne();
 
         //dias promedio cierre
         $sql = "SELECT ROUND(AVG(DATEDIFF(DATE_FORMAT(FROM_UNIXTIME(`date_close`), '%Y-%m-%d'), DATE_FORMAT(FROM_UNIXTIME(`datec`), '%Y-%m-%d')) ),2) AS days FROM tickets t  inner join  client c ON t.fk_soc = c.idClient  where t.date_close  <> ''";
         if ($pdptos != '-1') {
            $sql .= " AND state = '" . $pdptos . "'";
        }
        if ($pmpios != '-1') {
            $sql .= " AND town = '" . $pmpios . "'";
        }
         $daysclosed = $connection->createCommand($sql)->queryOne();

        echo json_encode([ 'daysopen' => $daysopen,
                           'daysclosed' => $daysclosed ]);
    }

    public function actionTicketsprocess()
    {
        $request = Yii::$app->request;
        $pdptos = $request->post('dptos');
        $pmpios = $request->post('mpios');
        $connection = Yii::$app->getDb();
        $sql = "select fecha, SUM(CASE WHEN (estado='registrado') THEN conteo ELSE 0 END) AS registrados, SUM(CASE WHEN (estado='open') THEN conteo ELSE 0 END) AS abiertos, SUM(CASE WHEN (estado='closed') THEN conteo ELSE 0 END) AS cerrados from vwticketsbystate "; 
        $sql .= " WHERE 1=1 ";
        $sql .="group by fecha";

        if ($pdptos != '-1') {
            $sql .= " AND state = '" . $pdptos . "'";
        }
        if ($pmpios != '-1') {
            $sql .= " AND town = '" . $pmpios . "'";
        }
        $tickets_estado = $connection->createCommand($sql)->queryAll();
        echo json_encode($tickets_estado);
    }

    public function actionTicketsgroups()
    {
        $request = Yii::$app->request;
        $pdptos = $request->post('dptos');
        $pmpios = $request->post('mpios');

        $connection = Yii::$app->getDb();
        $sql = "SELECT distinct t.category_label FROM tickets t inner join client c on t.fk_soc = c.idClient order by 1";
        $categories = $connection->createCommand($sql)->queryAll();
        //tickets por grupo
        $sql = "select DATE_FORMAT(FROM_UNIXTIME(`t`.`datec`), '%Y-%m') as fecha, ";
        foreach ($categories as $key => $row) {
            $sql = $sql . " SUM(CASE WHEN (category_label='" . $row["category_label"] . "') THEN 1 ELSE 0 END) AS '" . $row["category_label"] . "',";
        }

        $sql = $sql . "count(*) as total from tickets t inner join  client c ON t.fk_soc = c.idClient WHERE 1=1 ";
       
        if ($pdptos != '-1') {
            $sql .= " AND state = '" . $pdptos . "'";
        }
        if ($pmpios != '-1') {
            $sql .= " AND town = '" . $pmpios . "'";
        }
        $sql = $sql . " group by  DATE_FORMAT(FROM_UNIXTIME(`t`.`datec`), '%Y-%m') ";
        $tickets_grupo = $connection->createCommand($sql)->queryAll();
        echo json_encode($tickets_grupo);
    }

    public function actionPqrs() {
        $connection = Yii::$app->getDb();

        $sql = "SELECT distinct c.state FROM tickets t inner join client c on t.fk_soc = c.idClient";
        $deptos = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT distinct c.town FROM tickets t inner join client c on t.fk_soc = c.idClient";
        $mpios = $connection->createCommand($sql)->queryAll();

        return $this->render('pqrs', [
                    'deptos' => $deptos,
                    'mpios' => $mpios
        ]);
    }

    public function actionInstalaciondash() {
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM avances_metas_instalacion h";
        $insts = $connection->createCommand($sql)->queryAll();
        
        $sql = "SELECT distinct Departamento FROM sabana_reporte_instalacion";
        $deptos = $connection->createCommand($sql)->queryAll();

        return $this->render('instalaciondash', [
                    'deptos' => $deptos,
                    'insts' => $insts
        ]);
    }

    public function actionInstalaciondetails() {
        $request = Yii::$app->request;
        $dane = $request->get('dane');
        $connection = Yii::$app->getDb();

        $sql = "SELECT distinct Departamento as city FROM sabana_reporte_instalacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' limit 1";
        $deptos = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT distinct Municipio as district FROM sabana_reporte_instalacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' limit 1";
        $mpios = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT h.* FROM sabana_reporte_instalacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' ";
        $insts = $connection->createCommand($sql)->queryAll();
        $municipio = $insts[0]['Departamento'] . ' - ' . $insts[0]['Municipio'];
        return $this->render('instalaciondetails', array(
                    'deptos' => $deptos,
                    'mpios' => $mpios,
                    'insts' => $insts,
                    'municipio' => $municipio));
    }

    public function actionOperaciondash() {
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM avances_meta_operacion h";
        $insts = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT distinct Departamento FROM sabana_reporte_operacion";
        $deptos = $connection->createCommand($sql)->queryAll();

        return $this->render('operaciondash', [
                    'deptos' => $deptos,
                    'insts' => $insts
        ]);        
    }

    public function actionOperaciondetails() {
        $request = Yii::$app->request;
        $dane = $request->get('dane');
        $connection = Yii::$app->getDb();

        $sql = "SELECT distinct Departamento as city FROM sabana_reporte_operacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' limit 1";
        $deptos = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT distinct Municipio as district FROM sabana_reporte_operacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' limit 1";
        $mpios = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT h.* FROM sabana_reporte_operacion h WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '$dane' limit 1 ";
        $insts = $connection->createCommand($sql)->queryAll();
        $municipio = $insts[0]['Departamento'] . ' - ' . $insts[0]['Municipio'];
        return $this->render('operaciondetails', array(
                    'deptos' => $deptos,
                    'mpios' => $mpios,
                    'insts' => $insts,
                    'municipio' => $municipio));
    }

    public function actionCambiosreemplazos() {
        $connection = Yii::$app->getDb();
        $sql = "SELECT distinct Departamento_Old as city FROM sabana_reporte_cambios_reemplazos";
        $deptos = $connection->createCommand($sql)->queryAll();
        $sql = "SELECT distinct Municipio_Old as district FROM sabana_reporte_cambios_reemplazos";
        $mpios = $connection->createCommand($sql)->queryAll();
        $sql = "SELECT * FROM sabana_reporte_cambios_reemplazos";
        $insts = $connection->createCommand($sql)->queryAll();

        return $this->render('cambiosreemplazos', array(
                    'deptos' => $deptos,
                    'mpios' => $mpios,
                    'insts' => $insts));
    }

    /// Server side

    public function actionInventariosserver() {

        try {
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

            if (!empty($requestData['search']['value'])) {
                $sql .= " AND ( name LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR sku LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR location LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR city LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR district LIKE '" . $requestData['search']['value'] . "%')";
            }

            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];
            $pmaterials = empty($requestData['materials']) ? '-1' : $requestData['materials'];
            $pfactories = empty($requestData['factories']) ? '-1' : $requestData['factories'];
            $pmodels = empty($requestData['models']) ? '-1' : $requestData['models'];

            if ($pdptos != '-1') {
                $sql .= " AND city = '" . $pdptos . "'";
            }
            if ($pmpios != '-1') {
                $sql .= " AND district = '" . $pmpios . "'";
            }
            if ($pmaterials != '-1') {
                $sql .= " AND name = '" . $pmaterials . "'";
            }
            if ($pfactories != '-1') {
                $sql .= " AND factory = '" . $pfactories . "'";
            }
            if ($pmodels != '-1') {
                $sql .= " AND model = '" . $pmodels . "'";
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
                $nestedData[] = $row["sku"];
                $nestedData[] = $row["model"];
                $nestedData[] = 'Noroccidente'; //$row["id"];
                $nestedData[] = $row["city_code"] == '' ? '-' : substr($row["city_code"], 0, 2);
                $nestedData[] = $row["city"] == '' ? '-' : $row["city"];
                $nestedData[] = $row["district_code"];
                $nestedData[] = $row["district"];
                $nestedData[] = $row["location"];
                $nestedData[] = $row["name"];
                $nestedData[] = $row["factory"];
                $nestedData[] = $row["measure"];
                $nestedData[] = $row["quantity"];
                $nestedData[] = $row["lat"] . ',' . $row["lng"];
                $nestedData[] = $row["city_code"] == '' ? '-' : 'Operativo';
                $nestedData[] = 'Recursos de Fomento';
                $data[] = $nestedData;
            }

            if (!empty($requestData['export'])) {
                if ($requestData['export'] == 'csv') {
                    ob_start();
                    ob_start('ob_gzhandler');
                    header('Content-Type: text/csv; charset=windows-1251');
                    header('Content-Disposition: attachment; filename=InventariosExport.csv');
                    $output = fopen('php://output', 'w');
                    fwrite($output, "\xEF\xBB\xBF");
                    fputcsv($output, ['Serial o MAC', 'Modelo', 'Región', 'Código DANE Departamento', 'Departamento', 'Código DANE Municipio', 'Municipio', 'Barrio / Dirección', 'Descripción Material', 'Fabricante', 'Unidad de Medida', 'Cantidad', 'Coordenadas GPS', 'Estado', 'Fuente de Financiación'], ';');
                    foreach ($data as $key => $value) {
                        fputcsv($output, $value, ';');
                    }
                    fclose($output);
                    ob_end_flush();
                }
                if ($requestData['export'] == 'pdf') {
                    $pdf = new Fpdf();
                    /* Column headings */
                    $header = array('Serial o MAC', 'Modelo', 'Región', 'Depto', 'Departamento', 'Mpio', 'Municipio', 'Barrio / Dirección', 'Material', 'Fabricante', 'Unidad', 'Cantidad', 'Coordenadas GPS', 'Estado', 'Fuente de Financiación');
                    /* Data loading */
                    $pdf->AddPage('L', 'Legal');
                    $pdf->SetFont('Courier', '', 6);
                    /* Column widths */
                    $w = array(30, 27, 20, 8, 20, 10, 20, 95, 15, 15, 10, 10, 20, 15, 28);
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

                        for ($i = 8; $i < 15; $i++) {
                            $pdf->Cell($w[$i], 6, utf8_decode($row[$i]), 'LR');
                        }
                        $pdf->Ln();
                    }
                    /* Closing line */
                    $pdf->Cell(array_sum($w), 0, '', 'T');
                    $pdf->Output('D', 'InventariosExport.pdf', true);
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

        if (!empty($requestData['search']['value'])) {
            $sql .= " AND ( uuid LIKE '" . $requestData['search']['value'] . "%' ";
            $sql .= " OR reference LIKE '" . $requestData['search']['value'] . "%'";
            $sql .= " OR address LIKE '" . $requestData['search']['value'] . "%'";
            $sql .= " OR city LIKE '" . $requestData['search']['value'] . "%'";
            $sql .= " OR district LIKE '" . $requestData['search']['value'] . "%')";
        }
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $totalFiltered = count($data);

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
                $requestData['length'] . "   ";

        $result = Yii::$app->db->createCommand($sql)->queryAll();

        $data = array();
        foreach ($result as $key => $row) {
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

    public function actionOperaciondetailsserver() {

        try {
            ini_set('memory_limit','-1');
            $requestData = $_REQUEST;            

            $columns = array(
                0 => 'sabana_reporte_operacion_id',
                1 => 'Operador',
                2 => 'Documento_cliente_acceso',
                3 => 'Dane_Mun_ID_Punto',
                4 => 'Estado_actual',
                5 => 'Region',
                6 => 'Dane_Departamento',
                7 => 'Departamento',
                8 => 'Dane_Municipio',
                9 => 'Municipio',
                10 => 'Barrio',
                11 => 'Direccion',
                12 => 'Estrato',
                13 => 'Dificultad__de_acceso_al_municipio',
                14 => 'Coordenadas_Grados_decimales',
                15 => 'Nombre_Cliente',
                16 => 'Telefono',
                17 => 'Celular',
                18 => 'Correo_Electronico',
                19 => 'VIP',
                20 => 'Codigo_Proyecto_VIP',
                21 => 'Nombre_Proyecto_VIP',
                22 => 'Velocidad_Contratada_Downstream',
                23 => 'Meta',
                24 => 'Fecha_max_de_cumplimiento_de_meta',
                25 => 'Tipo_Solucion_UM_Operatividad',
                26 => 'Operador_Prestante',
                27 => 'IP',
                28 => 'Olt',
                29 => 'PuertoOlt',
                30 => 'Serial_ONT',
                31 => 'Port_ONT',
                32 => 'Nodo',
                33 => 'Armario',
                34 => 'Red_Primaria',
                35 => 'Red_Secundaria',
                36 => 'Nodo2',
                37 => 'Amplificador',
                38 => 'Tap_Boca',
                39 => 'Mac_Cpe',
                40 => 'Fecha_Instalado',
                41 => 'Fecha_Activo',
                42 => 'Fecha_inicio_operación',
                43 => 'Fecha_Solicitud_Traslado_PQR',
                44 => 'Semaforo',
                45 => 'Fecha_Inactivo',
                46 => 'Fecha_Desinstalado',
                47 => 'Sexo',
                48 => 'Genero',
                49 => 'Orientacion_Sexual',
                50 => 'Educacion_',
                51 => 'Etnias',
                52 => 'Discapacidad',
                53 => 'Estratos',
                54 => 'Beneficiario_Ley_1699_de_2013',
                55 => 'SISBEN_IV',
            );


            $totalData = Yii::$app->db->createCommand('SELECT COUNT(*) FROM sabana_reporte_operacion')->queryScalar();
            $totalFiltered = $totalData;

            $sql = "SELECT * FROM `sabana_reporte_operacion` where 1=1 ";

            if (!empty($requestData['search']['value'])) {
                $sql .= " AND ( Documento_cliente_acceso LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR Dane_Mun_ID_Punto LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Departamento LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Municipio LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Nombre_Cliente LIKE '" . $requestData['search']['value'] . "%')";
            }

            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];

            if ($pdptos != '-1') {
                $sql .= " AND Departamento = '" . $pdptos . "'";
            }
            if ($pmpios != '-1') {
                $sql .= " AND Municipio = '" . $pmpios . "'";
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
//                $nestedData[] = $row['sabana_reporte_operacion_id'];
                $nestedData[] = $row['Operador'];
                $nestedData[] = $row['Documento_cliente_acceso'];
                $nestedData[] = $row['Dane_Mun_ID_Punto'];
                $nestedData[] = $row['Estado_actual'];
                $nestedData[] = $row['Region'];
                $nestedData[] = $row['Dane_Departamento'];
                $nestedData[] = $row['Departamento'];
                $nestedData[] = $row['Dane_Municipio'];
                $nestedData[] = $row['Municipio'];
                $nestedData[] = $row['Barrio'];
                $nestedData[] = $row['Direccion'];
                $nestedData[] = $row['Estrato'];
                $nestedData[] = $row['Dificultad__de_acceso_al_municipio'];
                $nestedData[] = $row['Coordenadas_Grados_decimales'];
                $nestedData[] = $row['Nombre_Cliente'];
                $nestedData[] = $row['Telefono'];
                $nestedData[] = $row['Celular'];
                $nestedData[] = $row['Correo_Electronico'];
                $nestedData[] = $row['VIP'];
                $nestedData[] = $row['Codigo_Proyecto_VIP'];
                $nestedData[] = $row['Nombre_Proyecto_VIP'];
                $nestedData[] = $row['Velocidad_Contratada_Downstream'];
                $nestedData[] = $row['Meta'];
                $nestedData[] = $this->formatdate($row['Fecha_max_de_cumplimiento_de_meta']);
                $nestedData[] = $row['Tipo_Solucion_UM_Operatividad'];
                $nestedData[] = $row['Operador_Prestante'];
                $nestedData[] = $row['IP'];
                $nestedData[] = $row['Olt'];
                $nestedData[] = $row['PuertoOlt'];
                $nestedData[] = $row['Serial_ONT'];
                $nestedData[] = $row['Port_ONT'];
                $nestedData[] = $row['Nodo'];
                $nestedData[] = $row['Armario'];
                $nestedData[] = $row['Red_Primaria'];
                $nestedData[] = $row['Red_Secundaria'];
                $nestedData[] = $row['Nodo2'];
                $nestedData[] = $row['Amplificador'];
                $nestedData[] = $row['Tap_Boca'];
                $nestedData[] = $row['Mac_Cpe'];
                $nestedData[] = $this->formatdate($row['Fecha_Instalado']);
                $nestedData[] = $this->formatdate($row['Fecha_Activo']);
                $nestedData[] = $this->formatdate($row['Fecha_inicio_operación']);
                $nestedData[] = $this->formatdate($row['Fecha_Solicitud_Traslado_PQR']);
                $diasp = 0;
                $semaforo = 'blanco';
                // calcula dias
                // Punto 5: el semáforo debe quedar así: 0 - 5  días (verde), 6-10 días (amarillo), 11 a 15 días (rojo) y > a 15 días hábiles morado.
                if (isset($row['Fecha_Solicitud_Traslado_PQR'])) {
                    if ($row['Fecha_Solicitud_Traslado_PQR'] !== '') {
                        $d = $row['Fecha_Solicitud_Traslado_PQR'];
                        $CheckInX = explode("/", $d);
                        $date1 = mktime(0, 0, 0, $CheckInX[1], $CheckInX[0], $CheckInX[2]);
                        $date2 = time();
                        $diasp = ceil(($date2 - $date1) / (3600 * 24));
                    }
                }
                if ($diasp > 15) {
                    $semaforo = 'morado';
                }
                if ($diasp > 11 && $diasp <= 15) {
                    $semaforo = 'rojo';
                }
                if ($diasp > 6 && $diasp <= 10) {
                    $semaforo = 'amarillo';
                }
                if ($diasp > 0 && $diasp <= 5) {
                    $semaforo = 'verde';
                }
                $nestedData[] = $diasp . " días. <img src='" . Url::base(true) . "/img/bandera_" . $semaforo . ".png' alt='' width='16'/>";
                $nestedData[] = $this->formatdate($row['Fecha_Inactivo']);
                $nestedData[] = $this->formatdate($row['Fecha_Desinstalado']);
                $nestedData[] = $row['Sexo'];
                $nestedData[] = $row['Genero'];
                $nestedData[] = $row['Orientacion_Sexual'];
                $nestedData[] = $row['Educacion_'];
                $nestedData[] = $row['Etnias'];
                $nestedData[] = $row['Discapacidad'];
                $nestedData[] = $row['Estratos'];
                $nestedData[] = $row['Beneficiario_Ley_1699_de_2013'];
                $nestedData[] = $row['SISBEN_IV'];

                $data[] = $nestedData;
            }

            if (!empty($requestData['export'])) {
                if ($requestData['export'] == 'csv') {                    
                    ob_start();
                    ob_start('ob_gzhandler');
                    header('Content-Type: text/csv; charset=windows-1251');
                    header('Content-Disposition: attachment; filename=AccesosOperacionExport.csv');
                    $output = fopen('php://output', 'w');
                    fwrite($output, "\xEF\xBB\xBF");
                    fputcsv($output, ['Operador', 'Documento_cliente_acceso', 'Dane_Mun_ID_Punto', 'Estado_actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Direccion', 'Estrato', 'Dificultad__de_acceso_al_municipio', 'Coordenadas_Grados_decimales', 'Nombre_Cliente', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_Downstream', 'Meta', 'Fecha_max_de_cumplimiento_de_meta', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo2', 'Amplificador', 'Tap_Boca', 'Mac_Cpe', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_inicio_operación', 'Fecha_Solicitud_Traslado_PQR', 'Fecha_Inactivo', 'Fecha_Desinstalado', 'Sexo', 'Genero', 'Orientacion_Sexual', 'Educacion_', 'Etnias', 'Discapacidad', 'Estratos', 'Beneficiario_Ley_1699_de_2013', 'SISBEN_IV'], ';');
                    foreach ($data as $key => $value) {
                        fputcsv($output, $value, ';');
                    }
                    fclose($output);
                    ob_end_flush();
                }
                if ($requestData['export'] == 'pdf') {
                    $pdf = new Fpdf();
                    /* Column headings */
                    $header = array('Operador', 'Documento_cliente_acceso', 'Dane_Mun_ID_Punto', 'Estado_actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Direccion', 'Estrato', 'Dificultad__de_acceso_al_municipio', 'Coordenadas_Grados_decimales', 'Nombre_Cliente', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_Downstream', 'Meta', 'Fecha_max_de_cumplimiento_de_meta', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo2', 'Amplificador', 'Tap_Boca', 'Mac_Cpe', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_inicio_operación', 'Fecha_Solicitud_Traslado_PQR', 'Fecha_Inactivo', 'Fecha_Desinstalado', 'Sexo', 'Genero', 'Orientacion_Sexual', 'Educacion_', 'Etnias', 'Discapacidad', 'Estratos', 'Beneficiario_Ley_1699_de_2013', 'SISBEN_IV');
                    /* Data loading */
                    $pdf->AddPage('L', 'Legal');
                    $pdf->SetFont('Courier', '', 6);
                    /* Column widths */
                    $w = array(30, 27, 20, 8, 20, 10, 20, 95, 15, 15, 10, 10, 20, 15, 28);
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

                        for ($i = 8; $i < 15; $i++) {
                            $pdf->Cell($w[$i], 6, utf8_decode($row[$i]), 'LR');
                        }
                        $pdf->Ln();
                    }
                    /* Closing line */
                    $pdf->Cell(array_sum($w), 0, '', 'T');
                    $pdf->Output('D', 'AccesosOperacionExport.pdf', true);
                }
            } else {
                ob_start();
                ob_start('ob_gzhandler');
                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $data   // total data array
                );

                echo json_encode($json_data);
                ob_end_flush();
            }
        } catch (\Exception $ex) {
            $returndata = ['error' => $ex->getMessage()];
            echo json_encode($returndata);
        }
    }

    public function actionCambiosreemplazosserver() {

        try {

            $requestData = $_REQUEST;

            $columns = array(
                0 => 'sabana_reporte_cambios_reemplazos_id',
                1 => 'Ejecutor',
                2 => 'Documento_Cliente_Acceso_Old',
                3 => 'Dane_Mun_ID_Punto_Old',
                4 => 'Estado_Actual_Old',
                5 => 'Region_Old',
                6 => 'Dane_Departamento_Old',
                7 => 'Departamento_Old',
                8 => 'Dane_Municipio_Old',
                9 => 'Municipio_Old',
                10 => 'Barrio_Old',
                11 => 'Direccion_Old',
                12 => 'Estrato_Old',
                13 => 'Coordenadas_Grados_decimales_Old',
                14 => 'Nombre_Cliente_Completo_Old',
                15 => 'Telefono_Old',
                16 => 'Celular_Old',
                17 => 'Correo_Electronico_Old',
                18 => 'VIP_Old',
                19 => 'Codigo_Proyecto_VIP_Old',
                20 => 'Nombre_Proyecto_VIP_Old',
                21 => 'Velocidad_Contratada_MB_Old',
                22 => 'Meta_Old',
                23 => 'Tipo_Solucion_UM_Operatividad_Old',
                24 => 'Operador_Prestante_Old',
                25 => 'IP_fibra_optica_Old',
                26 => 'Olt_fibra_optica_Old',
                27 => 'PuertoOlt_fibra_optica_Old',
                28 => 'Mac_Onu_fibra_optica_Old',
                29 => 'Port_Onu_fibra_optica_Old',
                30 => 'Nodo_red_cobre_Old',
                31 => 'Armario_red_cobre_Old',
                32 => 'Red_Primaria_red_cobre_Old',
                33 => 'Red_Secundaria_red_cobre_Old',
                34 => 'Nodo_red_hfc_Old',
                35 => 'Amplificador_red_hfc_Old',
                36 => 'Tap_Boca_red_hfc_Old',
                37 => 'Mac_Cpe_Old',
                38 => 'Documento_Cliente_Acceso_New',
                39 => 'Region_New',
                40 => 'Dane_Departamento_New',
                41 => 'Departamento_New',
                42 => 'Dane_Municipio_New',
                43 => 'Municipio_New',
                44 => 'Barrio_New',
                45 => 'Direccion_New',
                46 => 'Estrato_New',
                47 => 'Coordenadas_Grados_decimales_New',
                48 => 'Nombre_Cliente_Completo_New',
                49 => 'Telefono_New',
                50 => 'Celular_New',
                51 => 'Correo_Electronico_New',
                52 => 'VIP_New',
                53 => 'Codigo_Proyecto_VIP_New',
                54 => 'Nombre_Proyecto_VIP_New',
                55 => 'Velocidad_Contratada_MB_New',
                56 => 'Meta_New',
                57 => 'Tipo_Solucion_UM_Operatividad_New',
                58 => 'Operador_Prestante_New',
                59 => 'IP_fibra_optica_New',
                60 => 'Olt_fibra_optica_New',
                61 => 'PuertoOlt_fibra_optica_New',
                62 => 'Mac_Onu_fibra_optica_New',
                63 => 'Port_Onu_fibra_optica_New',
                64 => 'Nodo_red_cobre_New',
                65 => 'Armario_red_cobre_New',
                66 => 'Red_Primaria_red_cobre_New',
                67 => 'Red_Secundaria_red_cobre_New',
                68 => 'Nodo_red_hfc_New',
                69 => 'Amplificador_red_hfc_New',
                70 => 'Tap_Boca_red_hfc_New',
                71 => 'Mac_Cpe_New',
            );


            $totalData = Yii::$app->db->createCommand('SELECT COUNT(*) FROM sabana_reporte_cambios_reemplazos')->queryScalar();
            $totalFiltered = $totalData;

            $sql = "SELECT * FROM `sabana_reporte_cambios_reemplazos` where 1=1 ";

            if (!empty($requestData['search']['value'])) {
                $sql .= " AND ( Documento_Cliente_Acceso_Old LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR Documento_Cliente_Acceso_New LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Dane_Municipio_Old LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Departamento_Old LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Municipio_Old LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Nombre_Cliente_Completo_Old LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Dane_Municipio_New LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Departamento_New LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Municipio_New LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Nombre_Cliente_Completo_New LIKE '" . $requestData['search']['value'] . "%')";
            }

            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];

            if ($pdptos != '-1') {
                $sql .= " AND Departamento_Old = '" . $pdptos . "'";
            }
            if ($pmpios != '-1') {
                $sql .= " AND Municipio_Old = '" . $pmpios . "'";
            }

            if (empty($requestData['export'])) {
                $sqlc = str_replace("*", "COUNT(*)", $sql);
                $totalFiltered = Yii::$app->db->createCommand($sqlc)->queryScalar();

                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
                        $requestData['length'] . "   ";
            }


            $result = Yii::$app->db->createCommand($sql)->queryAll();

            $data = array();
            foreach ($result as $key => $row) {
                $nestedData = array();
                $nestedData[] = $row['Ejecutor'];
                $nestedData[] = $row['Documento_Cliente_Acceso_Old'];
                $nestedData[] = $row['Dane_Mun_ID_Punto_Old'];
                $nestedData[] = $row['Estado_Actual_Old'];
                $nestedData[] = $row['Region_Old'];
                $nestedData[] = $row['Dane_Departamento_Old'];
                $nestedData[] = $row['Departamento_Old'];
                $nestedData[] = $row['Dane_Municipio_Old'];
                $nestedData[] = $row['Municipio_Old'];
                $nestedData[] = $row['Barrio_Old'];
                $nestedData[] = $row['Direccion_Old'];
                $nestedData[] = $row['Estrato_Old'];
                $nestedData[] = $row['Coordenadas_Grados_decimales_Old'];
                $nestedData[] = $row['Nombre_Cliente_Completo_Old'];
                $nestedData[] = $row['Telefono_Old'];
                $nestedData[] = $row['Celular_Old'];
                $nestedData[] = $row['Correo_Electronico_Old'];
                $nestedData[] = $row['VIP_Old'];
                $nestedData[] = $row['Codigo_Proyecto_VIP_Old'];
                $nestedData[] = $row['Nombre_Proyecto_VIP_Old'];
                $nestedData[] = $row['Velocidad_Contratada_MB_Old'];
                $nestedData[] = $row['Meta_Old'];
                $nestedData[] = $row['Tipo_Solucion_UM_Operatividad_Old'];
                $nestedData[] = $row['Operador_Prestante_Old'];
                $nestedData[] = $row['IP_fibra_optica_Old'];
                $nestedData[] = $row['Olt_fibra_optica_Old'];
                $nestedData[] = $row['PuertoOlt_fibra_optica_Old'];
                $nestedData[] = $row['Mac_Onu_fibra_optica_Old'];
                $nestedData[] = $row['Port_Onu_fibra_optica_Old'];
                $nestedData[] = $row['Nodo_red_cobre_Old'];
                $nestedData[] = $row['Armario_red_cobre_Old'];
                $nestedData[] = $row['Red_Primaria_red_cobre_Old'];
                $nestedData[] = $row['Red_Secundaria_red_cobre_Old'];
                $nestedData[] = $row['Nodo_red_hfc_Old'];
                $nestedData[] = $row['Amplificador_red_hfc_Old'];
                $nestedData[] = $row['Tap_Boca_red_hfc_Old'];
                $nestedData[] = $row['Mac_Cpe_Old'];
                $nestedData[] = $row['Documento_Cliente_Acceso_New'];
                $nestedData[] = $row['Region_New'];
                $nestedData[] = $row['Dane_Departamento_New'];
                $nestedData[] = $row['Departamento_New'];
                $nestedData[] = $row['Dane_Municipio_New'];
                $nestedData[] = $row['Municipio_New'];
                $nestedData[] = $row['Barrio_New'];
                $nestedData[] = $row['Direccion_New'];
                $nestedData[] = $row['Estrato_New'];
                $nestedData[] = $row['Coordenadas_Grados_decimales_New'];
                $nestedData[] = $row['Nombre_Cliente_Completo_New'];
                $nestedData[] = $row['Telefono_New'];
                $nestedData[] = $row['Celular_New'];
                $nestedData[] = $row['Correo_Electronico_New'];
                $nestedData[] = $row['VIP_New'];
                $nestedData[] = $row['Codigo_Proyecto_VIP_New'];
                $nestedData[] = $row['Nombre_Proyecto_VIP_New'];
                $nestedData[] = $row['Velocidad_Contratada_MB_New'];
                $nestedData[] = $row['Meta_New'];
                $nestedData[] = $row['Tipo_Solucion_UM_Operatividad_New'];
                $nestedData[] = $row['Operador_Prestante_New'];
                $nestedData[] = $row['IP_fibra_optica_New'];
                $nestedData[] = $row['Olt_fibra_optica_New'];
                $nestedData[] = $row['PuertoOlt_fibra_optica_New'];
                $nestedData[] = $row['Mac_Onu_fibra_optica_New'];
                $nestedData[] = $row['Port_Onu_fibra_optica_New'];
                $nestedData[] = $row['Nodo_red_cobre_New'];
                $nestedData[] = $row['Armario_red_cobre_New'];
                $nestedData[] = $row['Red_Primaria_red_cobre_New'];
                $nestedData[] = $row['Red_Secundaria_red_cobre_New'];
                $nestedData[] = $row['Nodo_red_hfc_New'];
                $nestedData[] = $row['Amplificador_red_hfc_New'];
                $nestedData[] = $row['Tap_Boca_red_hfc_New'];
                $nestedData[] = $row['Mac_Cpe_New'];
                $data[] = $nestedData;
            }

            if (!empty($requestData['export'])) {
                if ($requestData['export'] == 'csv') {
                    ob_start();
                    ob_start('ob_gzhandler');
                    header('Content-Type: text/csv; charset=windows-1251');
                    header('Content-Disposition: attachment; filename=CambiosyReemplazosExport.csv');
                    $output = fopen('php://output', 'w');
                    fwrite($output, "\xEF\xBB\xBF");
                    fputcsv($output, ['Ejecutor', 'Documento Cliente Acceso', 'Dane Mun - ID Punto', 'Estado Actual', 'Region', 'Dane Departamento', 'Departamento', 'Dane Municipio', 'Municipio', 'Barrio', 'Dirección', 'Estrato', 'Coordenadas Grados-decimales', 'Nombre Cliente Completo', 'Telefono', 'Celular', 'Correo Electronico', 'VIP (Si o No)', 'Codigo Proyecto VIP', 'Nombre Proyecto VIP', 'Velocidad Contratada MB', 'Meta', 'Tipo Solucion UM Operatividad', 'Operador Prestante', 'IP', 'Olt', 'PuertoOlt', 'Mac Onu', 'Port Onu', 'Nodo', 'Armario', 'Red Primaria', 'Red Secundaria', 'Nodo', 'Amplificador', 'Tap-Boca', 'Mac Cpe', 'Documento Cliente Acceso', 'Region', 'Dane Departamento', 'Departamento', 'Dane Municipio', 'Municipio', 'Barrio', 'Dirección', 'Estrato', 'Coordenadas Grados-decimales', 'Nombre Cliente Completo', 'Telefono', 'Celular', 'Correo Electronico', 'VIP (Si o No)', 'Codigo Proyecto VIP', 'Nombre Proyecto VIP', 'Velocidad Contratada MB', 'Meta', 'Tipo Solucion UM Operatividad', 'Operador Prestante', 'IP', 'Olt', 'PuertoOlt', 'Mac Onu', 'Port Onu', 'Nodo', 'Armario', 'Red Primaria', 'Red Secundaria', 'Nodo', 'Amplificador', 'Tap-Boca', 'Mac Cpe'], ';');
                    foreach ($data as $key => $value) {
                        fputcsv($output, $value, ';');
                    }
                    fclose($output);
                    ob_end_flush();
                }
                if ($requestData['export'] == 'pdf') {
                    $pdf = new Fpdf();
                    /* Column headings */
                    $header = array('Ejecutor', 'Documento Cliente Acceso', 'Dane Mun - ID Punto', 'Estado Actual', 'Region', 'Dane Departamento', 'Departamento', 'Dane Municipio', 'Municipio', 'Barrio', 'Dirección', 'Estrato', 'Coordenadas Grados-decimales', 'Nombre Cliente Completo', 'Telefono', 'Celular', 'Correo Electronico', 'VIP (Si o No)', 'Codigo Proyecto VIP', 'Nombre Proyecto VIP', 'Velocidad Contratada MB', 'Meta', 'Tipo Solucion UM Operatividad', 'Operador Prestante', 'IP', 'Olt', 'PuertoOlt', 'Mac Onu', 'Port Onu', 'Nodo', 'Armario', 'Red Primaria', 'Red Secundaria', 'Nodo', 'Amplificador', 'Tap-Boca', 'Mac Cpe', 'Documento Cliente Acceso', 'Region', 'Dane Departamento', 'Departamento', 'Dane Municipio', 'Municipio', 'Barrio', 'Dirección', 'Estrato', 'Coordenadas Grados-decimales', 'Nombre Cliente Completo', 'Telefono', 'Celular', 'Correo Electronico', 'VIP (Si o No)', 'Codigo Proyecto VIP', 'Nombre Proyecto VIP', 'Velocidad Contratada MB', 'Meta', 'Tipo Solucion UM Operatividad', 'Operador Prestante', 'IP', 'Olt', 'PuertoOlt', 'Mac Onu', 'Port Onu', 'Nodo', 'Armario', 'Red Primaria', 'Red Secundaria', 'Nodo', 'Amplificador', 'Tap-Boca', 'Mac Cpe');
                    /* Data loading */
                    $pdf->AddPage('L', 'Legal');
                    $pdf->SetFont('Courier', '', 6);
                    /* Column widths */
                    $w = array(30, 27, 20, 8, 20, 10, 20, 95, 15, 15, 10, 10, 20, 15, 28);
                    /* Header */
                    for ($i = 0; $i < 15; $i++) {
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

                        for ($i = 8; $i < 15; $i++) {
                            $pdf->Cell($w[$i], 6, utf8_decode($row[$i]), 'LR');
                        }
                        $pdf->Ln();
                    }
                    /* Closing line */
                    $pdf->Cell(array_sum($w), 0, '', 'T');
                    $pdf->Output('D', 'CambiosyReemplazosExport.pdf', true);
                }
            } else {
                ob_start();
                ob_start('ob_gzhandler');
                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $data   // total data array
                );

                echo json_encode($json_data);
                ob_end_flush();
            }
        } catch (\Exception $ex) {
            $returndata = ['error' => $ex->getMessage()];
            echo json_encode($returndata);
        }
    }

    public function actionInstalaciondetailsserver() {

        try {
            ini_set('memory_limit','-1');
            $requestData = $_REQUEST;            
            $columns = array(
                0 => 'sabana_reporte_instalacion_id',
                1 => 'Operador',
                2 => 'Documento_cliente_acceso',
                3 => 'Dane_Mun_ID_Punto',
                4 => 'Estado_actual',
                5 => 'Region',
                6 => 'Dane_Departamento',
                7 => 'Departamento',
                8 => 'Dane_Municipio',
                9 => 'Municipio',
                10 => 'Barrio',
                11 => 'Direccion',
                12 => 'Estrato',
                13 => 'Dificultad__de_acceso_al_municipio',
                14 => 'Coordenadas_Grados_decimales',
                15 => 'Nombre_Cliente',
                16 => 'Telefono',
                17 => 'Celular',
                18 => 'Correo_Electronico',
                19 => 'VIP',
                20 => 'Codigo_Proyecto_VIP',
                21 => 'Nombre_Proyecto_VIP',
                22 => 'Velocidad_Contratada_Downstream',
                23 => 'Meta',
                24 => 'Fecha_max_de_cumplimiento_de_meta',
                25 => 'Dias_pendientes_de_la_fecha_de_cumplimiento',
                26 => 'FECHA_APROBACION_INTERVENTORIA',
                27 => 'FECHA_APROBACION_META_SUPERVISION',
                28 => 'Tipo_Solucion_UM_Operatividad',
                29 => 'Operador_Prestante',
                30 => 'IP',
                31 => 'Olt',
                32 => 'PuertoOlt',
                33 => 'Serial_ONT',
                34 => 'Port_ONT',
                35 => 'Nodo',
                36 => 'Armario',
                37 => 'Red_Primaria',
                38 => 'Red_Secundaria',
                39 => 'Nodo2',
                40 => 'Amplificador',
                41 => 'Tap_Boca',
                42 => 'Mac_Cpe',
                43 => 'Fecha_Asignado_o_Presupuestado',
                44 => 'Fecha_En_proceso_de_Instalacion',
                45 => 'Fecha_Anulado',
                46 => 'Fecha_Instalado',
                47 => 'Fecha_Activo',
                48 => 'Fecha_aprobacion_de_meta',
                49 => 'Sexo',
                50 => 'Genero',
                51 => 'Orientacion_Sexual',
                52 => 'Educacion',
                53 => 'Etnias',
                54 => 'Discapacidad',
                55 => 'Estratos',
                56 => 'Beneficiario_Ley_1699_de_2013',
                57 => 'SISBEN_IV',
            );


            $totalData = Yii::$app->db->createCommand('SELECT COUNT(*) FROM sabana_reporte_instalacion')->queryScalar();
            $totalFiltered = $totalData;

            $sql = "SELECT * FROM `sabana_reporte_instalacion` where 1=1 ";

            if (!empty($requestData['search']['value'])) {
                $sql .= " AND ( Documento_cliente_acceso LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR Dane_Mun_ID_Punto LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Departamento LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Municipio LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR Nombre_Cliente LIKE '" . $requestData['search']['value'] . "%')";
            }

            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];

            if ($pdptos != '-1') {
                $sql .= " AND Departamento = '" . $pdptos . "'";
            }
            if ($pmpios != '-1') {
                $sql .= " AND Municipio = '" . $pmpios . "'";
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
//                $nestedData[] = $row['sabana_reporte_instalacion_id'];
                $nestedData[] = $row['Operador'];
                $nestedData[] = $row['Documento_cliente_acceso'];
                $nestedData[] = $row['Dane_Mun_ID_Punto'];
                $nestedData[] = $row['Estado_actual'];
                $nestedData[] = $row['Region'];
                $nestedData[] = $row['Dane_Departamento'];
                $nestedData[] = $row['Departamento'];
                $nestedData[] = $row['Dane_Municipio'];
                $nestedData[] = $row['Municipio'];
                $nestedData[] = $row['Barrio'];
                $nestedData[] = $row['Direccion'];
                $nestedData[] = $row['Estrato'];
                $nestedData[] = $row['Dificultad__de_acceso_al_municipio'];
                $nestedData[] = $row['Coordenadas_Grados_decimales'];
                $nestedData[] = $row['Nombre_Cliente'];
                $nestedData[] = $row['Telefono'];
                $nestedData[] = $row['Celular'];
                $nestedData[] = $row['Correo_Electronico'];
                $nestedData[] = $row['VIP'];
                $nestedData[] = $row['Codigo_Proyecto_VIP'];
                $nestedData[] = $row['Nombre_Proyecto_VIP'];
                $nestedData[] = $row['Velocidad_Contratada_Downstream'];
                $nestedData[] = $row['Meta'];
                $nestedData[] = $this->formatdate($row['Fecha_max_de_cumplimiento_de_meta']);
                $diasp = 0;
                $semaforo = 'blanco';
                // calcula dias
                // Punto 4: el semáforo debe quedar así: verde mayor a  30 días calendario, amarillo entre 30 y 10 días, rojo entre 10 a 0 días calendario
                if (isset($row['Fecha_max_de_cumplimiento_de_meta'])) {
                    if ($row['Fecha_max_de_cumplimiento_de_meta'] !== '') {
                        $d = $row['Fecha_max_de_cumplimiento_de_meta'];
                        $CheckInX = explode("/", $d);
                        $date1 = mktime(0, 0, 0, $CheckInX[1], $CheckInX[0], $CheckInX[2]);
                        $date2 = time();
                        $diasp = ceil(($date2 - $date1) / (3600 * 24));
                        $diasp = $diasp * -1;
                    }
                }
                if ($diasp > 30) {
                    $semaforo = 'verde';
                }
                if ($diasp > 10 && $diasp <= 30) {
                    $semaforo = 'amarillo';
                }
                if ($diasp > 0 && $diasp <= 10) {
                    $semaforo = 'rojo';
                }
                $nestedData[] = $diasp . " días. <img src='" . Url::base(true) . "/img/bandera_" . $semaforo . ".png' alt='' width='16'/>";
                //$nestedData[] = $row['Dias_pendientes_de_la_fecha_de_cumplimiento'];
                $nestedData[] = $this->formatdate($row['FECHA_APROBACION_INTERVENTORIA']);
                $nestedData[] = $this->formatdate($row['FECHA_APROBACION_META_SUPERVISION']);
                $nestedData[] = $row['Tipo_Solucion_UM_Operatividad'];
                $nestedData[] = $row['Operador_Prestante'];
                $nestedData[] = $row['IP'];
                $nestedData[] = $row['Olt'];
                $nestedData[] = $row['PuertoOlt'];
                $nestedData[] = $row['Serial_ONT'];
                $nestedData[] = $row['Port_ONT'];
                $nestedData[] = $row['Nodo'];
                $nestedData[] = $row['Armario'];
                $nestedData[] = $row['Red_Primaria'];
                $nestedData[] = $row['Red_Secundaria'];
                $nestedData[] = $row['Nodo2'];
                $nestedData[] = $row['Amplificador'];
                $nestedData[] = $row['Tap_Boca'];
                $nestedData[] = $row['Mac_Cpe'];
                $nestedData[] = $this->formatdate($row['Fecha_Asignado_o_Presupuestado']);
                $nestedData[] = $this->formatdate($row['Fecha_En_proceso_de_Instalacion']);
                $nestedData[] = $this->formatdate($row['Fecha_Anulado']);
                $nestedData[] = $this->formatdate($row['Fecha_Instalado']);
                $nestedData[] = $this->formatdate($row['Fecha_Activo']);
                $nestedData[] = $this->formatdate($row['Fecha_aprobacion_de_meta']);
                $nestedData[] = $row['Sexo'];
                $nestedData[] = $row['Genero'];
                $nestedData[] = $row['Orientacion_Sexual'];
                $nestedData[] = $row['Educacion'];
                $nestedData[] = $row['Etnias'];
                $nestedData[] = $row['Discapacidad'];
                $nestedData[] = $row['Estratos'];
                $nestedData[] = $row['Beneficiario_Ley_1699_de_2013'];
                $nestedData[] = $row['SISBEN_IV'];

                $data[] = $nestedData;
            }

            if (!empty($requestData['export'])) {
                if ($requestData['export'] == 'csv') {
                    ob_start();
                    ob_start('ob_gzhandler');
                    header('Content-Type: text/csv; charset=windows-1251');
                    header('Content-Disposition: attachment; filename=AccesosInstalacionExport.csv');
                    $output = fopen('php://output', 'w');
                    fwrite($output, "\xEF\xBB\xBF");
                    fputcsv($output, ['Operador', 'Documento_cliente_acceso', 'Dane_Mun_ID_Punto', 'Estado_actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Direccion', 'Estrato', 'Dificultad__de_acceso_al_municipio', 'Coordenadas_Grados_decimales', 'Nombre_Cliente', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_Downstream', 'Meta', 'Fecha_max_de_cumplimiento_de_meta', 'Dias_pendientes_de_la_fecha_de_cumplimiento', 'FECHA_APROBACION_INTERVENTORIA', 'FECHA_APROBACION_META_SUPERVISION', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo2', 'Amplificador', 'Tap_Boca', 'Mac_Cpe', 'Fecha_Asignado_o_Presupuestado', 'Fecha_En_proceso_de_Instalacion', 'Fecha_Anulado', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_aprobacion_de_meta', 'Sexo', 'Genero', 'Orientacion_Sexual', 'Educacion', 'Etnias', 'Discapacidad', 'Estratos', 'Beneficiario_Ley_1699_de_2013', 'SISBEN_IV'], ';');
                    foreach ($data as $key => $value) {
                        fputcsv($output, $value, ';');
                    }
                    fclose($output);
                    ob_end_flush();
                }
                if ($requestData['export'] == 'pdf') {
                    $pdf = new Fpdf();
                    /* Column headings */
                    $header = array('Operador', 'Documento_cliente_acceso', 'Dane_Mun_ID_Punto', 'Estado_actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Direccion', 'Estrato', 'Dificultad__de_acceso_al_municipio', 'Coordenadas_Grados_decimales', 'Nombre_Cliente', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_Downstream', 'Meta', 'Fecha_max_de_cumplimiento_de_meta', 'Dias_pendientes_de_la_fecha_de_cumplimiento', 'FECHA_APROBACION_INTERVENTORIA', 'FECHA_APROBACION_META_SUPERVISION', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo2', 'Amplificador', 'Tap_Boca', 'Mac_Cpe', 'Fecha_Asignado_o_Presupuestado', 'Fecha_En_proceso_de_Instalacion', 'Fecha_Anulado', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_aprobacion_de_meta', 'Sexo', 'Genero', 'Orientacion_Sexual', 'Educacion', 'Etnias', 'Discapacidad', 'Estratos', 'Beneficiario_Ley_1699_de_2013', 'SISBEN_IV');
                    /* Data loading */
                    $pdf->AddPage('L', 'Legal');
                    $pdf->SetFont('Courier', '', 6);
                    /* Column widths */
                    $w = array(30, 27, 20, 8, 20, 10, 20, 95, 15, 15, 10, 10, 20, 15, 28);
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

                        for ($i = 8; $i < 15; $i++) {
                            $pdf->Cell($w[$i], 6, utf8_decode($row[$i]), 'LR');
                        }
                        $pdf->Ln();
                    }
                    /* Closing line */
                    $pdf->Cell(array_sum($w), 0, '', 'T');
                    $pdf->Output('D', 'AccesosInstalacionExport.pdf', true);
                }
            } else {
                ob_start();
                ob_start('ob_gzhandler');
                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $data   // total data array
                );

                echo json_encode($json_data);
                ob_end_flush();
            }
        } catch (\Exception $ex) {
            $returndata = ['error' => $ex->getMessage()];
            echo json_encode($returndata);
        }
    }

    public function actionPqrsserver() {

        try {
            $requestData = $_REQUEST;

            $columns = array(
                0 => 'idTicket',
                1 => 'id',
                2 => 'socid',
                3 => 'tickets.ref',
                4 => 'fk_soc',
                5 => 'subject',
                6 => 'message',
                7 => 'type_label',
                8 => 'category_label',
                9 => 'severity_label',
                10 => 'datec',
                11 => 'date_read',
                12 => 'date_close',
                13 => 'messages',
                14 => 'idClient',
                15 => 'entity',
                16 => 'name',
                17 => 'state_id',
                18 => 'state_code',
                19 => 'state',
                20 => 'town',
                21 => 'email',
                22 => 'phone',
                23 => 'idprof1',
                24 => 'code_client',
                25 => 'ref',
                26 => 'country_id',
                27 => 'country_code',
                28 => 'country',
                29 => 'access_id',
                30 => 'address',
                31 => 'latlng'
            );                    
            $totalData = Yii::$app->db->createCommand('SELECT COUNT(*) FROM tickets t inner join client c on t.fk_soc = c.idClient')->queryScalar();
            $totalFiltered = $totalData;            

            $sql = "SELECT * FROM tickets t inner join client c on t.fk_soc = c.idClient where 1=1 ";

            if (!empty($requestData['search']['value'])) {
                $sql .= " AND ( t.ref LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR c.ref LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR c.name LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR c.state LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR c.town LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR c.code_client LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR subject LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR type_label LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR category_label LIKE '" . $requestData['search']['value'] . "%'";
                $sql .= " OR severity_label LIKE '" . $requestData['search']['value'] . "%')";
            }
        
            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];

            if ($pdptos != '-1') {
                $sql .= " AND state = '" . $pdptos . "'";
            }
            if ($pmpios != '-1') {
                $sql .= " AND town = '" . $pmpios . "'";
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
                $nestedData[] = $row['state'];
                $nestedData[] = $row['town'];
                $nestedData[] = $row['access_id'];
                $nestedData[] = $row['name'];
                $nestedData[] = $row['ref'];
                $nestedData[] = $row['category_label'];
                $nestedData[] = $row['type_label'];
                $nestedData[] = $row['severity_label'];
                $nestedData[] = $row['subject'];
                $nestedData[] = $this->formatdate(date('Y-m-d', $row['datec']) . ' 00:00:00');

                //calculo fecha limite
                $next5WD = "";
                if (isset($row['datec'])) {
                    $holidayDates = array(
                        '2020-08-17',
                        '2020-10-12',
                        '2020-11-02',
                        '2020-11-16',
                        '2020-12-08',
                        '2020-12-25',
                        '2021-01-01',
                        '2021-01-11',
                        '2021-03-22',
                        '2021-04-01',
                        '2021-04-02',
                        '2021-05-01',
                        '2021-05-17',
                        '2021-06-03',
                        '2021-06-14',
                        '2021-07-05',
                        '2021-07-20',
                        '2021-08-07',
                        '2021-08-16',
                        '2021-10-18',
                        '2021-11-01',
                        '2021-11-15',
                        '2021-12-08',
                        '2021-12-25',
                    );

                    $count5WD = 0;
                    $d = date('Y-m-d', $row['datec']) . ' 00:00:00';
                    //echo $d;
                    $temp = strtotime($d); //example as today is 2016-03-25
                    while ($count5WD < 15) {
                        $next1WD = strtotime('+1 weekday', $temp);
                        $next1WDDate = date('Y-m-d', $next1WD);
                        if (!in_array($next1WDDate, $holidayDates)) {
                            $count5WD++;
                        }
                        $temp = $next1WD;
                    }

                    $next5WD = date("d/m/Y", $temp);
                }
                $nestedData[] = $this->formatdate($next5WD);
                $nestedData[] = ($row['date_close'] !== '')?$this->formatdate(date('Y-m-d', $row['date_close']) . ' 00:00:00'):'';
                $nestedData[] = 'Call Center';
                // Datos Cliente
                $nestedData[] = $row['idprof1'];
                $nestedData[] = $row['phone'];
                $nestedData[] = $row['email'];
                $nestedData[] = $row['address'];
                $nestedData[] = str_replace(array('Lat: ','Lon: '),'',$row['latlng']);
                $nestedData[] = $row['message'];

                $autor ="";
                $msg = "<ul>";
                $jsond = json_decode($row['messages']);
                foreach ((array) $jsond as $key => $mesa) {
                    $msg = $msg . '<li>' . date("Y-m-d H:i:s", $mesa->datec) . ' - ' . $mesa->message . '</li>';
                    if(stripos($mesa->message,'creado'))
                    {
                       $autor =  str_replace(array('Autor: ','Ticket'),'',$mesa->message);
                       $s = explode(' ',$autor);
                       if(sizeof($s)> 0){
                           $autor = str_replace('Ticket','',$s[0]);
                       }
                    }
                }
                $msg = $msg . "</ul>";

                $nestedData[] =  $msg;
                $nestedData[] =  $autor;
                
//                $nestedData[] = $row['idTicket'];
//                $nestedData[] = $row['id'];
//                $nestedData[] = $row['socid'];
//                $nestedData[] = $row['ref'];
//                $nestedData[] = $row['fk_soc'];
//                $nestedData[] = $row['date_read'];
//                $nestedData[] = $row['messages'];
//                $nestedData[] = $row['idClient'];
//                $nestedData[] = $row['entity'];
//                $nestedData[] = $row['state_id'];
//                $nestedData[] = $row['state_code'];
//                $nestedData[] = $row['code_client'];
//                $nestedData[] = $row['country_id'];
//                $nestedData[] = $row['country_code'];
//                $nestedData[] = $row['country'];

                        $data[] = $nestedData;
            }

            if (!empty($requestData['export'])) {
                if ($requestData['export'] == 'csv') {
                    ob_start();
                    ob_start('ob_gzhandler');
                    header('Content-Type: text/csv; charset=windows-1251');
                    header('Content-Disposition: attachment; filename=PqrsExport.csv');
                    $output = fopen('php://output', 'w');
                    fwrite($output, "\xEF\xBB\xBF");
                    fputcsv($output, ['Departamento','Municipio','Código Acceso','Cliente','Ref Ticket','Grupo','Tipo','Prioridad','Asunto','Fecha Creación','Fecha Limite','Fecha Cierre','Origen de Reporte','Cédula','Teléfonos','Email','Dirección / Barrio','Coordenadas','Detalle','Historial','Autor'], ';');
                    foreach ($data as $key => $value) {
                        fputcsv($output, $value, ';');
                    }
                    fclose($output);
                    ob_end_flush();
                }
                if ($requestData['export'] == 'pdf') {
                    $pdf = new Fpdf();
                    /* Column headings */
                    $header = array('Departamento','Municipio','Código Acceso','Cliente','Ref Ticket','Grupo','Tipo','Prioridad','Asunto','Fecha Creación','Fecha Limite','Fecha Cierre','Origen de Reporte','Cédula','Teléfonos','Email','Dirección / Barrio','Coordenadas','Detalle','Historial','Autor');
                    /* Data loading */
                    $pdf->AddPage('L', 'Legal');
                    $pdf->SetFont('Courier', '', 6);
                    /* Column widths */
                    $w = array(30, 27, 20, 8, 20, 10, 20, 95, 15, 15, 10, 10, 20, 15, 28);
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

                        for ($i = 8; $i < 15; $i++) {
                            $pdf->Cell($w[$i], 6, utf8_decode($row[$i]), 'LR');
                        }
                        $pdf->Ln();
                    }
                    /* Closing line */
                    $pdf->Cell(array_sum($w), 0, '', 'T');
                    $pdf->Output('D', 'PqrsExport.pdf', true);
                }
            } else {

                ob_start();
                ob_start('ob_gzhandler');
                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $data   // total data array
                );

                echo json_encode($json_data);
                ob_end_flush();
            }
        } catch (\Exception $ex) {
            $returndata = ['error' => $ex->getMessage()];
            echo json_encode($returndata);
        }
    }

    public function formatdate($date) {
        if (isset($date) && strlen($date) > 0) {
            return date("Y-m-d", strtotime(str_replace('/', '-', $date)));
        } else {
            return '';
        }
    }

}

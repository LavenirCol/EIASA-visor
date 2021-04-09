<?php

namespace frontend\controllers;

use app\models\Document;
use app\models\Settings;
use Yii;
use yii\base\Exception;
use Fpdf\Fpdf;
use yii\helpers\Url;
use \yii\db;
use yii\data\Pagination;
use frontend\utils\ExcelUtils;
use GuzzleHttp\Psr7\Query;
use phpDocumentor\Reflection\Types\Object_;

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

        $daneCodeList = (new \yii\db\Query())
        ->select(['district_code'])
        ->from('hsstock')
        ->distinct()
        ->orderBy(['district_code' => SORT_ASC])
        ->all();
        

        return $this->render('inventarios', [
                    'deptos' => $deptos,
                    'mpios' => $mpios,
                    'daneCodeList' => $daneCodeList,
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
        
        $queryTicketsSeverity = (new \yii\db\Query())
        ->select(['category_label', 'type_label', 'severity_label', 'conteo' => 'count(*)' ])
        ->from('tickets')
        ->innerJoin('client', 'client.idClient = tickets.fk_soc');
        $queryTotal = $queryTicketsSeverity;
        
        if (!empty($requestData['search']['value'])) {
            $queryTicketsSeverity->Where(['LIKE', 'category_label', $requestData['search']['value']."%", false])
            ->orWhere(['LIKE', 'type_label',$requestData['search']['value']."%", false])
            ->orWhere(['LIKE', 'severity_label',$requestData['search']['value']."%", false]);           
        }

        if ($pdptos != '-1') {            
            $queryTicketsSeverity->andWhere(['=', 'state', $pdptos]);
        }
        if ($pmpios != '-1') {            
            $queryTicketsSeverity->andWhere(['=', 'town', $pmpios]);
        }
        if(Yii::$app->user->identity->attributes['idProfile'] != 1)
        {
            $category = $this->getCategoryTicketsFilter();
            $queryTicketsSeverity->andWhere(['category_label' => $category]);
        }
        $queryTicketsSeverity->groupBy(['category_label', 'type_label', 'severity_label']);
        $queryTicketsSeverity->orderBy(['category_label' => SORT_ASC, 'type_label'  => SORT_ASC, 'severity_label'  => SORT_ASC]);
        $total = $queryTotal->count();
        
        $tickets_severity = $queryTicketsSeverity->all();
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
        $draw = (isset($requestData['draw']))?intval($requestData['draw']):1;
        $json_data = array(
            "draw" => $draw,
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
        $queryAvgOpenedByDay = (new \yii\db\Query())
        ->select(["days" => "ROUND(AVG(DATEDIFF(NOW(), DATE_FORMAT(FROM_UNIXTIME(`datec`), '%Y-%m-%d')) ),2)"])
        ->from("tickets")
        ->innerJoin("client", "client.idClient = tickets.fk_soc")
        ->where(['=','date_close', '']);
        $queryAvgClosedByDay = (new \yii\db\Query())
        ->select(["days" => "ROUND(AVG(DATEDIFF(DATE_FORMAT(FROM_UNIXTIME(`date_close`), '%Y-%m-%d'), DATE_FORMAT(FROM_UNIXTIME(`datec`), '%Y-%m-%d')) ),2)"])
        ->from("tickets")
        ->innerJoin("client", "client.idClient = tickets.fk_soc")
        ->where(['<>','date_close', '']);
        if ($pdptos != '-1') {            
            $queryAvgOpenedByDay->andWhere(['=', 'state', $pdptos]);
            $queryAvgClosedByDay->andWhere(['=', 'state', $pdptos]);
        }
        if ($pmpios != '-1') {            
            $queryAvgOpenedByDay->andWhere(['=', 'town', $pmpios]);
            $queryAvgClosedByDay->andWhere(['=', 'town', $pmpios]);
        }
        if(Yii::$app->user->identity->attributes['idProfile'] != 1)
        {
            $category = $this->getCategoryTicketsFilter();
            $queryAvgOpenedByDay->andWhere(['category_label' => $category]);
            $queryAvgClosedByDay->andWhere(['category_label' => $category]);
        }
        $daysopen = $queryAvgOpenedByDay->one();
        $daysclosed = $queryAvgClosedByDay->one();
        echo json_encode([ 'daysopen' => $daysopen,
                           'daysclosed' => $daysclosed ]);
        exit(0);
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
        exit(0);
    }

    public function actionTicketsgroups()
    {
        $request = Yii::$app->request;
        $pdptos = $request->post('dptos');
        $pmpios = $request->post('mpios');

        $connection = Yii::$app->getDb();
        $queryTicketsgroups = (new \yii\db\Query())
        ->from('tickets')
        ->select(['category_label'])
        ->innerJoin('client', 'tickets.fk_soc = client.idClient');
        if(Yii::$app->user->identity->attributes['idProfile'] != 1)
        {
            $category = $this->getCategoryTicketsFilter();
            $queryTicketsgroups->andWhere(['category_label' => $category]);
        }
        $queryTicketsgroups->distinct()
        ->orderBy(['category_label' => SORT_ASC]);

        $sql = "SELECT distinct t.category_label FROM tickets t inner join client c on t.fk_soc = c.idClient order by 1";
        $categories = $queryTicketsgroups->all();
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
        exit(0);
    }

    public function actionPqrs() {
        $connection = Yii::$app->getDb();

        $sql = "SELECT distinct c.state FROM tickets t inner join client c on t.fk_soc = c.idClient";
        $deptos = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT distinct c.town FROM tickets t inner join client c on t.fk_soc = c.idClient";
        $mpios = $connection->createCommand($sql)->queryAll();

        $daneCodeList = (new \yii\db\Query())
            ->select(['daneCode' => 'sys_district.code'])
            ->from('tickets')
            ->innerJoin('client', 'tickets.fk_soc = client.idClient')
            ->innerJoin('sys_city', 'sys_city.name = client.state')
            ->innerJoin('sys_district', 'upper(sys_district.name) = upper(client.town) and sys_city.id = sys_district.id_city')
            ->distinct()
            ->orderBy(['daneCode' => SORT_ASC])
            ->all();

        return $this->render('pqrs', [
                    'deptos' => $deptos,
                    'mpios' => $mpios,
                    'daneCodeList' => $daneCodeList,
        ]);
    }

    public function actionInstalaciondash() {
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM avances_metas_instalacion h";
        $insts = $connection->createCommand($sql)->queryAll();
        
        $sql = "SELECT distinct Departamento FROM sabana_reporte_instalacion";
        $deptos = $connection->createCommand($sql)->queryAll();

        $daneCodeList = (new \yii\db\Query())
        ->select(['daneCode' => 'DANE'])
        ->from('avances_metas_instalacion')
        ->distinct()
        ->orderBy(['daneCode' => SORT_ASC])
        ->all();

        return $this->render('instalaciondash', [
                    'deptos' => $deptos,
                    'daneCodeList' => $daneCodeList,
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
        $municipio = (isset($insts[0]['Departamento'],$insts[0]['Municipio'])) ? ($insts[0]['Departamento'] . ' - ' . $insts[0]['Municipio']) : '';
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

        $daneCodeList = (new \yii\db\Query())
        ->select(['daneCode' => 'DANE'])
        ->from('avances_meta_operacion')
        ->distinct()
        ->orderBy(['daneCode' => SORT_ASC])
        ->all();

        return $this->render('operaciondash', [
                    'deptos' => $deptos,
                    'daneCodeList' => $daneCodeList,
                    'insts' => $insts
        ]);        
    }

    public function actionOperaciondashserver() {

        try {
            $requestData = $_REQUEST;           
            $columns = array(
                0 => 'DANE',
                1 => 'Departamento',
                2 => 'Municipio',
                3 => 'Meta',
                4 => 'Beneficiarios_En_Operacion',
                5 => 'Meta_Tiempo_en_servicio',
                6 => 'Tiempo_en_servicio',
                7 => 'Avance'
            );

            $totalData = (new \yii\db\Query())->from('avances_meta_operacion')->count();
            $totalFiltered = $totalData;

            $dataReport = (new \yii\db\Query())
            ->select(
                [
                    "DANE",
                    "Departamento",
                    "Municipio",
                    "Meta" => "CONVERT(SUBSTRING_INDEX(`Meta`,'-',-1),UNSIGNED INTEGER)",
                    "Beneficiarios_En_Operacion" => "CONVERT(SUBSTRING_INDEX(`Beneficiarios_En_Operacion`,'-',-1),UNSIGNED INTEGER)",
                    "Meta_Tiempo_en_servicio" => "CONVERT(SUBSTRING_INDEX(`Meta_Tiempo_en_servicio`,'-',-1),UNSIGNED INTEGER)",
                    "Tiempo_en_servicio" => "(Tiempo_en_servicio + 0.0)",
                    "Avance" => "(Avance + 0.0)"
                ]
            )
            ->from('avances_meta_operacion');
            if (!empty($requestData['search']['value'])){
                $dataReport->Where(['LIKE', 'DANE', $requestData['search']['value']."%", false])                
                ->orWhere(['LIKE', 'Departamento', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Municipio', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Meta', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Beneficiarios_En_Operacion', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Meta_Tiempo_en_servicio', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Tiempo_en_servicio', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Avance', $requestData['search']['value']."%", false]);  
            }            
            $daneCodeFilter = empty($requestData['daneCodeFilter']) ? '-1' : $requestData['daneCodeFilter'];            
            if ($daneCodeFilter != '-1') {
                $dataReport->andWhere(['=', 'DANE', $daneCodeFilter]);
            }

            if (empty($requestData['export'])){
                $totalFiltered = $dataReport->count();
                $order =  ($requestData['order'][0]['dir'] == 'asc') ?  SORT_ASC : SORT_DESC;                
                $dataReport->orderBy([$columns[$requestData['order'][0]['column']] => $order]);
                $dataReport->offset($requestData['start']);
                $dataReport->limit($requestData['length']);     
            }            
            $result = $dataReport->all();
            $data = array();
            foreach ($result as $key => $row) {
                $nestedData = array();
                $nestedData[] = $row["DANE"];
                $nestedData[] = $row["Departamento"];
                $nestedData[] = $row['Municipio'];
                $nestedData[] = number_format($row["Meta"],0,",",".");
                $nestedData[] = number_format($row["Beneficiarios_En_Operacion"],0,",",".");
                $nestedData[] = number_format($row["Meta_Tiempo_en_servicio"],0,",",".");
                $nestedData[] = number_format($row["Tiempo_en_servicio"],0,",",".");
                $nestedData[] = number_format($row["Avance"],2,",",".")." %";
                $nestedData[] = ($row['Beneficiarios_En_Operacion'] > 0  && empty($requestData['export'])) ? "<a href='".Url::toRoute('reports/operaciondetails')."?dane=".$row['DANE']."' class='btn btn-sm btn-primary'>Detalles</a>" : "";
                $data[] = $nestedData;
            }

            if (!empty($requestData['export'])) {
                if ($requestData['export'] == 'csv') {
                   $header = ['DANE', 'Departamento', 'Municipio', 'Meta', 'Beneficiarios Instalados', 'Meta Tiempo en Servicio','Tiempo en Servicio','Avance']; 
                   $excel = new ExcelUtils();
                   $excel->export("Dashboard Operación.xlsx",$header,$data);                   
                }
                if ($requestData['export'] == 'pdf') {
                    $pdf = new Fpdf();
                    /* Column headings */
                    $header = array('DANE', 'Departamento', 'Municipio', 'Meta', 'Beneficiarios Instalados', 'Meta Tiempo en Servicio','Tiempo en Servicio','Avance');
                    /* Data loading */
                    $pdf->AddPage('L', 'Legal');
                    $pdf->SetFont('Courier', 'B', 10);
                    /* Column widths */
                    $w = array(30, 40, 60, 20, 60, 60, 40, 30);
                    /* Header */
                    for ($index = 0; $index < count($header); $index++){
                        $pdf->Cell($w[$index], 7, utf8_decode($header[$index]), 1, 0, 'C');
                    }
                    $pdf->Ln();
                    /* Data */
                    $pdf->SetFont('Courier', '', 10);
                    foreach ($data as $row) {
                        for ($index = 0; $index < 8; $index++){
                            if($index > 2)
                            {
                                $pdf->Cell($w[$index], 6, utf8_decode($row[$index]), 1,0,'R');
                            }else{
                                $pdf->Cell($w[$index], 6, utf8_decode($row[$index]), 1,0,'L');
                            }                            
                        }
                        $pdf->Ln();
                    }
                    /* Closing line */
                    $pdf->Cell(array_sum($w), 0, '', 'T');
                    $pdf->Output('D', 'Dashboard Operación.pdf', true);
                }
            } else {

                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $data   // total data array
                );

                return json_encode($json_data);
            }
        }catch (\Exception $ex){
            $returndata = ['error' => $ex->getMessage()];
            return json_encode($returndata);
        }
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

        $daneCodeList = (new \yii\db\Query())
        ->select(['daneCode' => 'Concat(Dane_Departamento_Old, Dane_Municipio_Old)'])
        ->from('sabana_reporte_cambios_reemplazos')
        ->distinct()
        ->orderBy(['daneCode' => SORT_ASC])
        ->all();

        return $this->render('cambiosreemplazos', array(
                    'deptos' => $deptos,
                    'mpios' => $mpios,
                    'daneCodeList' => $daneCodeList,
                    'insts' => $insts));
    }

    /// Server side
    public function actionInstalaciondashserver() {

        try {
            $requestData = $_REQUEST;           
            $columns = array(
                0 => 'DANE',
                1 => 'Departamento',
                2 => 'Municipio',
                3 => 'Meta',
                4 => 'Beneficiarios_Instalados',
                5 => 'Avance'
            );

            $totalData = (new \yii\db\Query())->from('avances_metas_instalacion')->count();
            $totalFiltered = $totalData;

            $dataReport = (new \yii\db\Query())
            ->select(
                [
                    "DANE",
                    "Departamento",
                    "Municipio",
                    "Meta" => "CONVERT(SUBSTRING_INDEX(`Meta`,'-',-1),UNSIGNED INTEGER)",
                    "Beneficiarios_Instalados" => "CONVERT(SUBSTRING_INDEX(`Beneficiarios_Instalados`,'-',-1),UNSIGNED INTEGER)",
                    "Avance" => "(Avance + 0.0)"
                ]
            )
            ->from('avances_metas_instalacion');
            if (!empty($requestData['search']['value'])){
                $dataReport->Where(['LIKE', 'DANE', $requestData['search']['value']."%", false])                
                ->orWhere(['LIKE', 'Departamento', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Municipio', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Meta', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Beneficiarios_Instalados', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Avance', $requestData['search']['value']."%", false]);  
            }            
            $daneCodeFilter = empty($requestData['daneCodeFilter']) ? '-1' : $requestData['daneCodeFilter'];            
            if ($daneCodeFilter != '-1') {
                $dataReport->andWhere(['=', 'DANE', $daneCodeFilter]);
            }

            if (empty($requestData['export'])){
                $totalFiltered = $dataReport->count();
                $order =  ($requestData['order'][0]['dir'] == 'asc') ?  SORT_ASC : SORT_DESC;                
                $dataReport->orderBy([$columns[$requestData['order'][0]['column']] => $order]);
                $dataReport->offset($requestData['start']);
                $dataReport->limit($requestData['length']);     
            }            
            $result = $dataReport->all();
            $data = array();
            foreach ($result as $key => $row) {
                $nestedData = array();
                $nestedData[] = $row["DANE"];
                $nestedData[] = $row["Departamento"];
                $nestedData[] = $row['Municipio'];
                $nestedData[] = number_format($row["Meta"],0,",",".");
                $nestedData[] = number_format($row["Beneficiarios_Instalados"],0,",",".");
                $nestedData[] = number_format($row["Avance"],2,",",".")." %";
                $nestedData[] = ($row['Beneficiarios_Instalados'] > 0  && empty($requestData['export'])) ? "<a href='".Url::toRoute('reports/instalaciondetails')."?dane=".$row['DANE']."' class='btn btn-sm btn-primary'>Detalles</a>" : "";
                $data[] = $nestedData;
            }

            if (!empty($requestData['export'])) {
                if ($requestData['export'] == 'csv') {
                    $header = ['DANE', 'Departamento', 'Municipio', 'Meta', 'Beneficiarios Instalados', 'Avances']; 
                    $excel = new ExcelUtils();
                    $excel->export("Dashboard Instalación.xlsx",$header,$data);
                }
                if ($requestData['export'] == 'pdf') {
                    $pdf = new Fpdf();
                    /* Column headings */
                    $header = array('DANE', 'Departamento', 'Municipio', 'Meta', 'Beneficiarios Instalados', 'Avances');
                    /* Data loading */
                    $pdf->AddPage('L', 'Legal');
                    $pdf->SetFont('Courier', 'B', 12);
                    /* Column widths */
                    $w = array(30, 40, 70, 40, 70, 30);
                    /* Header */
                    for ($index = 0; $index < count($header); $index++){
                        $pdf->Cell($w[$index], 7, utf8_decode($header[$index]), 1, 0, 'C');
                    }
                    $pdf->Ln();
                    /* Data */
                    $pdf->SetFont('Courier', '', 10);
                    foreach ($data as $row) {
                        for ($index = 0; $index < 6; $index++){
                            if($index > 2)
                            {
                                $pdf->Cell($w[$index], 6, utf8_decode($row[$index]), 1,0,'R');
                            }else{
                                $pdf->Cell($w[$index], 6, utf8_decode($row[$index]), 1,0,'L');
                            }                            
                        }
                        $pdf->Ln();
                    }
                    /* Closing line */
                    $pdf->Cell(array_sum($w), 0, '', 'T');
                    $pdf->Output('D', 'Dashboard Instalación.pdf', true);
                }
            } else {

                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $data   // total data array
                );

                return json_encode($json_data);
            }
        }catch (\Exception $ex){
            $returndata = ['error' => $ex->getMessage()];
            return json_encode($returndata);
        }
    }



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

            $hsStockData = (new \yii\db\Query())->from('hsstock');
            if (!empty($requestData['search']['value'])){
                $hsStockData->Where(['LIKE', 'name', $requestData['search']['value']."%", false])                
                ->orWhere(['LIKE', 'sku', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'location', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'city', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'district', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'district_code', $requestData['search']['value']."%", false]);  
            }
            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];
            $daneCodeFilter = empty($requestData['daneCodeFilter']) ? '-1' : $requestData['daneCodeFilter'];
            $pmaterials = empty($requestData['materials']) ? '-1' : $requestData['materials'];
            $pfactories = empty($requestData['factories']) ? '-1' : $requestData['factories'];
            $pmodels = empty($requestData['models']) ? '-1' : $requestData['models'];

            if ($pdptos != '-1') {                
                $hsStockData->andWhere(['=', 'city', $pdptos]);
            }
            if ($pmpios != '-1') {                
                $hsStockData->andWhere(['=', 'district', $pmpios]);
            }
            if ($pmaterials != '-1') {                
                $hsStockData->andWhere(['=', 'name', $pmaterials]);
            }
            if ($pfactories != '-1') {
                $hsStockData->andWhere(['=', 'factory', $pfactories]);
            }
            if ($pmodels != '-1') {
                $hsStockData->andWhere(['=', 'model', $pmodels]);
            }
            if ($daneCodeFilter != '-1') {
                $hsStockData->andWhere(['=', 'district_code', $daneCodeFilter]);
            }

            if (empty($requestData['export'])){
                $totalFiltered = $hsStockData->count();
                $order =  ($requestData['order'][0]['dir'] == 'asc') ?  SORT_ASC : SORT_DESC;
                $pagination = new Pagination(['totalCount' => $totalFiltered, 'pageSize' => $requestData['length'], 'page' => $requestData['start']]);
                $hsStockData->orderBy([$columns[$requestData['order'][0]['column']] => $order]);
                $hsStockData->offset($pagination->offset);
                $hsStockData->limit($pagination->limit);     
            }
            $result = $hsStockData->all();
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
                    $header = ['Serial o MAC', 'Modelo', 'Región', 'Código DANE Departamento', 'Departamento', 'Código DANE Municipio', 'Municipio', 'Barrio / Dirección', 'Descripción Material', 'Fabricante', 'Unidad de Medida', 'Cantidad', 'Coordenadas GPS', 'Estado', 'Fuente de Financiación']; 
                    $excel = new ExcelUtils();
                    $excel->export("InventariosExport.xlsx",$header,$data);
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

                return json_encode($json_data);
            }
        } catch (\Exception $ex) {
            $returndata = ['error' => $ex->getMessage()];
            return json_encode($returndata);
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
                45 => 'Fecha_Inactivo2',
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
            $dataReport = (new \yii\db\Query())->from('sabana_reporte_operacion');
            $totalData = $dataReport->count(); 
            $totalFiltered = $totalData;
            if (!empty($requestData['search']['value'])) {
                $dataReport->Where(['LIKE', 'Documento_cliente_acceso', $requestData['search']['value'].'%', false ])
                ->orWhere(['LIKE', 'Dane_Mun_ID_Punto', $requestData['search']['value'].'%', false ])
                ->orWhere(['LIKE', 'Departamento', $requestData['search']['value'].'%', false ])
                ->orWhere(['LIKE', 'Municipio', $requestData['search']['value'].'%', false ])
                ->orWhere(['LIKE', 'Nombre_Cliente', $requestData['search']['value'].'%', false ]);
            }

            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];
            if ($pdptos != '-1') {
                $dataReport->andWhere(['=','Departamento',$pdptos]);                
            }
            if ($pmpios != '-1') {                
                $dataReport->andWhere(['=','Municipio',$pmpios]); 
            }
            if (empty($requestData['export'])){
                $totalFiltered = $dataReport->count();
                $order =  ($requestData['order'][0]['dir'] == 'asc') ?  SORT_ASC : SORT_DESC;
                $dataReport->orderBy([$columns[$requestData['order'][0]['column']] => $order]);
                $dataReport->offset($requestData['start']);
                $dataReport->limit($requestData['length']);
            }
            $result = $dataReport->all();
            $data = array();
            foreach ($result as $key => $row) {
                $nestedData = array();
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
                $nestedData[] = $this->getFlagColor($row['Fecha_Solicitud_Traslado_PQR'], !empty($requestData['export']));
                $nestedData[] = $this->formatdate($row['Fecha_Inactivo']);
                $nestedData[] = $this->getFlagColor($row['Fecha_Inactivo'], !empty($requestData['export']));
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
                   $header = ['Operador', 'Documento_cliente_acceso', 'Dane_Mun_ID_Punto', 'Estado_actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Direccion', 'Estrato', 'Dificultad__de_acceso_al_municipio', 'Coordenadas_Grados_decimales', 'Nombre_Cliente', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_Downstream', 'Meta', 'Fecha_max_de_cumplimiento_de_meta', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo2', 'Amplificador', 'Tap_Boca', 'Mac_Cpe', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_inicio_operación', 'Fecha_Solicitud_Traslado_PQR','Semáforo_Solicitud_Traslado_PQR', 'Fecha_Inactivo', 'Semáforo_Fecha_Inactivo', 'Fecha_Desinstalado', 'Sexo', 'Genero', 'Orientacion_Sexual', 'Educacion_', 'Etnias', 'Discapacidad', 'Estratos', 'Beneficiario_Ley_1699_de_2013', 'SISBEN_IV']; 
                   $excel = new ExcelUtils();
                   $excel->export("AccesosOperacionExport.xlsx",$header,$data);
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
            $totalData = (new \yii\db\Query())->from('sabana_reporte_cambios_reemplazos')->count();
            $totalFiltered = $totalData;           
            $dataReport = (new \yii\db\Query())->from('sabana_reporte_cambios_reemplazos');
            if (!empty($requestData['search']['value'])) {
                $dataReport->Where(['LIKE', 'Documento_Cliente_Acceso_Old', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Documento_Cliente_Acceso_New', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Dane_Municipio_Old', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Departamento_Old', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Municipio_Old', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Nombre_Cliente_Completo_Old', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Dane_Municipio_New', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Departamento_New', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Municipio_New', $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Nombre_Cliente_Completo_New', $requestData['search']['value']."%", false]);
            }
            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];
            $daneCodeFilter = empty($requestData['daneCodeFilter']) ? '-1' : $requestData['daneCodeFilter'];

            if ($pdptos != '-1') {
                $dataReport->andWhere(['=', 'Departamento_Old', $pdptos]);
            }
            if ($pmpios != '-1') {                
                $dataReport->andWhere(['=', 'Municipio_Old', $pmpios]);
            }
            if ($daneCodeFilter != '-1') {                
                $dataReport->andWhere(['=', 'Concat(Dane_Departamento_Old, Dane_Municipio_Old)', $daneCodeFilter]);
            }

            if (empty($requestData['export'])) {
                $totalFiltered = $dataReport->count();
                $order =  ($requestData['order'][0]['dir'] == 'asc') ?  SORT_ASC : SORT_DESC;
                $pagination = new Pagination(['totalCount' => $totalFiltered, 'pageSize' => $requestData['length'], 'page' => $requestData['start']]);
                $dataReport->orderBy([$columns[$requestData['order'][0]['column']] => $order]);
                $dataReport->offset($pagination->offset);
                $dataReport->limit($pagination->limit);
            }
            $result =  $dataReport->all();

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
                   $header = ['Ejecutor', 'Documento Cliente Acceso', 'Dane Mun - ID Punto', 'Estado Actual', 'Region', 'Dane Departamento', 'Departamento', 'Dane Municipio', 'Municipio', 'Barrio', 'Dirección', 'Estrato', 'Coordenadas Grados-decimales', 'Nombre Cliente Completo', 'Telefono', 'Celular', 'Correo Electronico', 'VIP (Si o No)', 'Codigo Proyecto VIP', 'Nombre Proyecto VIP', 'Velocidad Contratada MB', 'Meta', 'Tipo Solucion UM Operatividad', 'Operador Prestante', 'IP', 'Olt', 'PuertoOlt', 'Mac Onu', 'Port Onu', 'Nodo', 'Armario', 'Red Primaria', 'Red Secundaria', 'Nodo', 'Amplificador', 'Tap-Boca', 'Mac Cpe', 'Documento Cliente Acceso', 'Region', 'Dane Departamento', 'Departamento', 'Dane Municipio', 'Municipio', 'Barrio', 'Dirección', 'Estrato', 'Coordenadas Grados-decimales', 'Nombre Cliente Completo', 'Telefono', 'Celular', 'Correo Electronico', 'VIP (Si o No)', 'Codigo Proyecto VIP', 'Nombre Proyecto VIP', 'Velocidad Contratada MB', 'Meta', 'Tipo Solucion UM Operatividad', 'Operador Prestante', 'IP', 'Olt', 'PuertoOlt', 'Mac Onu', 'Port Onu', 'Nodo', 'Armario', 'Red Primaria', 'Red Secundaria', 'Nodo', 'Amplificador', 'Tap-Boca', 'Mac Cpe'];
                   $excel = new ExcelUtils();
                   $excel->export("CambiosyReemplazosExport.xlsx",$header,$data);
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

            $dataReport = (new \yii\db\Query())->from('sabana_reporte_instalacion');
            
            $sql = "SELECT * FROM `sabana_reporte_instalacion` where 1=1 ";

            if (!empty($requestData['search']['value'])) {
                $dataReport->Where(['LIKE', 'Documento_cliente_acceso',  $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Dane_Mun_ID_Punto',  $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Departamento',  $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Municipio',  $requestData['search']['value']."%", false])
                ->orWhere(['LIKE', 'Nombre_Cliente',  $requestData['search']['value']."%", false]);
            }

            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];

            if ($pdptos != '-1') {               
                $dataReport->andWhere(['=', 'Departamento',  $pdptos]);
            }
            if ($pmpios != '-1') {
                $dataReport->andWhere(['=', 'Municipio',  $pmpios]);
            }

            if (empty($requestData['export'])){                
                $totalFiltered = $dataReport->count();
                $order =  ($requestData['order'][0]['dir'] == 'asc') ?  SORT_ASC : SORT_DESC;
                $dataReport->orderBy([$columns[$requestData['order'][0]['column']] => $order]);
                $dataReport->offset($requestData['start']);
                $dataReport->limit($requestData['length']);
            }
            $result = $dataReport->all();
            $data = array();
            foreach ($result as $key => $row) {
                $nestedData = array();
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
                $nestedData[] = $this->getFlagColor($row['Fecha_max_de_cumplimiento_de_meta'], !empty($requestData['export']));
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
                   $header = ['Operador', 'Documento_cliente_acceso', 'Dane_Mun_ID_Punto', 'Estado_actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Direccion', 'Estrato', 'Dificultad__de_acceso_al_municipio', 'Coordenadas_Grados_decimales', 'Nombre_Cliente', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_Downstream', 'Meta', 'Fecha_max_de_cumplimiento_de_meta', 'Dias_pendientes_de_la_fecha_de_cumplimiento', 'FECHA_APROBACION_INTERVENTORIA', 'FECHA_APROBACION_META_SUPERVISION', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo2', 'Amplificador', 'Tap_Boca', 'Mac_Cpe', 'Fecha_Asignado_o_Presupuestado', 'Fecha_En_proceso_de_Instalacion', 'Fecha_Anulado', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_aprobacion_de_meta', 'Sexo', 'Genero', 'Orientacion_Sexual', 'Educacion', 'Etnias', 'Discapacidad', 'Estratos', 'Beneficiario_Ley_1699_de_2013', 'SISBEN_IV']; 
                   $excel = new ExcelUtils();
                   $excel->export("AccesosInstalacionExport.xlsx",$header,$data);
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
                16 => 'client.name',
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
                31 => 'lat',
                32 => 'lng',
                33 => 'documentList'
            );
            $queryTickets = (new \yii\db\Query())
            ->select(['state','town','daneCode' => 'sys_district.code','access_id','name' => 'client.name','tickets.ref','category_label','type_label','severity_label','subject','datec','date_close','idprof1','phone','email','address','lat','lng','tickets.message', 'tickets.messages', 'status' => 'status_ticket.name'])            
            ->from('tickets')
            ->innerJoin('client', 'tickets.fk_soc = client.idClient')
            ->innerJoin('sys_city', 'sys_city.name = client.state')
            ->innerJoin('sys_district', 'upper(sys_district.name) = upper(client.town) and sys_city.id = sys_district.id_city')
            ->leftJoin('status_ticket', 'status_ticket.status_code = tickets.fk_statut');            
            $totalData = $queryTickets->count();
            $totalFiltered = $totalData;
            if (!empty($requestData['search']['value']))
            {
                $queryTickets->andWhere(['LIKE', 'tickets.ref', $requestData['search']['value']."%", false])
                 ->orWhere(['LIKE', 'subject', $requestData['search']['value']."%", false])
                 ->orWhere(['LIKE', 'type_label', $requestData['search']['value']."%", false])
                 ->orWhere(['LIKE', 'category_label', $requestData['search']['value']."%", false])
                 ->orWhere(['LIKE', 'severity_label', $requestData['search']['value']."%", false])
                 ->orWhere(['LIKE', 'client.ref', $requestData['search']['value']."%", false])
                 ->orWhere(['LIKE', 'client.name', $requestData['search']['value']."%", false])
                 ->orWhere(['LIKE', 'client.state', $requestData['search']['value']."%", false])
                 ->orWhere(['LIKE', 'client.town', $requestData['search']['value']."%", false])
                 ->orWhere(['LIKE', 'client.code_client', $requestData['search']['value']."%", false]) 
                 ->orWhere(['LIKE', 'sys_district.code', $requestData['search']['value']."%", false]);                 
            }
            $pdptos = empty($requestData['dptos']) ? '-1' : $requestData['dptos'];
            $pmpios = empty($requestData['mpios']) ? '-1' : $requestData['mpios'];
            $daneCodeFilter = empty($requestData['daneCodeFilter']) ? '-1' : $requestData['daneCodeFilter'];
            if ($pdptos != '-1') {
                $queryTickets->andWhere(['=', 'state', $pdptos]);
            }
            if ($pmpios != '-1') {
                $queryTickets->andWhere(['=', 'town', $pmpios]);
            }
            if ($daneCodeFilter != '-1') {
                $queryTickets->andWhere(['=', 'sys_district.code', $daneCodeFilter]);
            }
            if(Yii::$app->user->identity->attributes['idProfile'] != 1)
            {
                $category = $this->getCategoryTicketsFilter();
                $queryTickets->andWhere(['category_label' => $category]);
            }
            
            if (empty($requestData['export'])) 
            {
                $totalFiltered = $queryTickets->count();
                $order =  ($requestData['order'][0]['dir'] == 'asc') ?  SORT_ASC : SORT_DESC;
                $pagination = new Pagination(['totalCount' => $totalFiltered, 'pageSize' => $requestData['length'], 'page' => $requestData['start']]);
                $queryTickets->orderBy([$columns[$requestData['order'][0]['column']] => $order]);
                $queryTickets->offset($requestData['start']);
                $queryTickets->limit($requestData['length']);
            }            
            $result = $queryTickets->all();
            $data = array();
            foreach ($result as $key => $row) {
                $nestedData = array();
                $nestedData[] = $row['state'];
                $nestedData[] = $row['town'];
                $nestedData[] = $row['daneCode'];
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
                    $temp = strtotime(date('Y-m-d', $row['datec']) . ' 00:00:00');
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
                $nestedData[] = isset($row['lat'],$row['lng'])? $row['lat'].", ".$row['lng'] : '';
                $nestedData[] = $row['message'];
                $author ="";
                $status = "";
                $isExportData = !empty($requestData['export']);
                $arrayMessage = (array)json_decode($row['messages']);
                $history = $this->getHistoryFromMessageTicket($arrayMessage , $isExportData);     
                $author = $this->getAuthorFromMessageTicket($arrayMessage);
                $status = $row['status'];
                $nestedData[] =  $history;
                $nestedData[] =  $author;
                $nestedData[] =  $status;
                $nestedData[] = "";
                $nestedData[] = $this->getDocumentTicketList($row['ref']);
                $data[] = $nestedData;
            }

            if (!empty($requestData['export'])) {
                if ($requestData['export'] == 'csv') {
                    $header = ['Departamento','Municipio','Código Dane','Código Acceso','Cliente','Ref Ticket','Grupo','Tipo','Prioridad','Asunto','Fecha Creación','Fecha Limite','Fecha Cierre','Origen de Reporte','Cédula','Teléfonos','Email','Dirección / Barrio','Coordenadas','Detalle','Historial','Autor', 'Estado Ticket'];
                    $excel = new ExcelUtils();
                    $excel->export("PqrExport.xlsx",$header,$data);
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
            $returndata = ['error' => $ex->__toString()];
            echo json_encode($returndata);
        }
    }

    private function getCategoryTicketsFilter()
    {
        $config = Settings::find()->where(['=','key','CATEGORIAS VISIBLE TICKETS'])
        ->one();
        $category = explode(',',$config->value);

        return $category;
    }
    
    private function getDocumentTicketList($refTicket)
    {
        
        $documentList = Document::find()->where(['level1name' => $refTicket, 'iddocumentType' => 6 ])->all();
        $keyurlbase = Settings::find()->where(['key' => 'URLBASE'])->one();
        $htmlDocument ='<div class="row row-xs" id="filecontainer" style="margin-bottom: 60px; cursor:pointer">';
         foreach($documentList as $document)
         {             
            $extensionFile = pathinfo($document->name, PATHINFO_EXTENSION);
            $iconStyle = $this->getIconStyle($extensionFile, $keyurlbase, $document->iddocument);
            $htmlDocument .=  
            '<div class="col-xs-6 col-sm-4 col-md-3 colfile" data-iddocument="'.$document->iddocument.'" onClick="previewFile(\''.$keyurlbase->value.'/visor/getfile?id='.$document->iddocument.'\',\''.$extensionFile.'\' )">
             <div class="card card-file">
              <div class="dropdown-file">
                <a href="" class="dropdown-link" data-toggle="dropdown"><i data-feather="more-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-right">                  
                </div>
              </div><!-- dropdown -->
              <div class="card-file-thumb '.$iconStyle->color.'" style="'.$iconStyle->background.'">
                <i class="far '.$iconStyle->icon.'"></i>
              </div>
              <div class="card-body">
                <h6>'.$document->name.'</h6>
                <p>'.mime_content_type($document->path.$document->name).'</p>
                <p>'.$document->date.'</p>
                <span>'.$document->size.'</span>
              </div>
              </div>
            </div><!-- col -->';
         }
        $htmlDocument .= '</div>';
        return $htmlDocument ;
    }

    private function getIconStyle($extensionFile, $keyurlbase, $idDocument)
    {
        $iconStyle = new Object_();
        $iconStyle->icon = 'fa-file';
        $iconStyle->color = "tx-teal";
        $iconStyle->background = "";
        switch(strtolower($extensionFile))
        {   
            case "doc": 
                $iconStyle->icon = "fa-file-word";
                $iconStyle->color = "tx-primary";
            break;
            case "xls": 
                $iconStyle->icon = "fa-file-excel";
                $iconStyle->color = "tx-success";
                break;
            case "ppt": 
                $iconStyle->icon = "fa-file-powerpoint";
                $iconStyle->color = "tx-orange";
                break;
            case "pdf": 
                $iconStyle->icon = "fa-file-pdf";
                $iconStyle->color = "tx-danger";
                break;
            case "zip": 
                $iconStyle->icon = "fa-file-archive";
                $iconStyle->color = "tx-warning";
                break;
            case "rar": 
                $iconStyle->icon = "fa-file-archive";
                $iconStyle->color = "tx-purple";
                break;
            case "txt": 
                $iconStyle->icon = "fa-file-alt";
                $iconStyle->color = "tx-black";
                break;
            case "gif":
            case "jpg":
            case "jpeg":
            case "png":
            case "bmp":     
                $iconStyle->icon = "";
                $iconStyle->color = "";
                $iconStyle->background = "background-image: url(".$keyurlbase->value."/visor/getfile?id=".$idDocument."&t=true); filter: opacity(0.5);";
                break;            
        }
        return $iconStyle;
    }
    public function formatdate($date) {
        if (isset($date) && strlen($date) > 0) {
            return date("Y-m-d", strtotime(str_replace('/', '-', $date)));
        } else {
            return '';
        }
    }

    private function getStateFromMessageTicket($arrayMessage)
    {
        $status = "";
        if(count($arrayMessage)> 0)
        {            
            $itemMessage = array_values($arrayMessage)[0];
            $arrayString = explode(' ',$itemMessage->message);
            $status = (count($arrayString) > 0)?  ucwords($arrayString[count($arrayString) - 1]) : "";
        }

        return $status;
    }

    private function getAuthorFromMessageTicket($arrayMessage)
    {
        $author = '';
        foreach ((array) $arrayMessage as $key => $itemMessage) {        
            if(stripos($itemMessage->message,'creado')){                
                $author =  str_replace(array('Autor: ','Ticket'),'',$itemMessage->message);
                $array_author = explode(' ',$author);
                if(sizeof($array_author)> 0){
                    $author = str_replace('Ticket','',$array_author[0]);
                }        
                break;
            }    
        }       
       
        return $author;
    }

    private function getHistoryFromMessageTicket($arrayMessage, $exportData = false)
    {
        $history = (!$exportData)? "<ul>" : "";
        $initialLine = (!$exportData)? "<li>" : "";
        $finalLine = (!$exportData)? "</li>" : "\n";
        foreach ((array) $arrayMessage as $key => $itemMessage) {
            $history .= $initialLine . $itemMessage->id . ' - ' . date("Y-m-d H:i:s", $itemMessage->datec) . ' - ' . $this->getClearMessage($itemMessage->message,$exportData) . $finalLine;                      
        }
        $history .= (!$exportData)? "</ul>" : "";
        
        return $history; 
    }

    private function getClearMessage($message, $exportData = false)
    {
        $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
        $reemplazar=array("", "", "", "");
        $message = strip_tags($message);
        return (($exportData) ? html_entity_decode(str_ireplace($buscar,$reemplazar,$message)) : $message); 
    }

    private function getFlagColor($date, $isExportData)
    {
        $daysDifference = 0;
        $flagColor = 'blanco';
        if (isset($date)) {
            if ($date !== '') {                
                $CheckInX = explode("/", $date);
                $initialTime = mktime(0, 0, 0, $CheckInX[1], $CheckInX[0], $CheckInX[2]);
                $finalTime = time();
                $daysDifference = ceil(($finalTime - $initialTime) / (3600 * 24));
            }
        }
        if ($daysDifference > 15) {
            $flagColor = 'morado';
        }else  if ($daysDifference > 11 && $daysDifference <= 15) {
            $flagColor = 'rojo';
        }else if ($daysDifference > 6 && $daysDifference <= 10) {
            $flagColor = 'amarillo';
        }else if ($daysDifference > 0 && $daysDifference <= 5) {
            $flagColor = 'verde';
        }    

        return $daysDifference . " días. ". (($isExportData)? ucwords($flagColor) :  "<img src='" . Url::base(true) . "/img/bandera_" . $flagColor . ".png' alt='' width='16'/>");
    }

}

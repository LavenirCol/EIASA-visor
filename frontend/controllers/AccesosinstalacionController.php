<?php

namespace frontend\controllers;

use Yii;
use app\models\SabanaReporteInstalacion;
use app\models\SabanaReporteInstalacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Settings;
use app\models\AvancesMetasInstalacion;
use yii\base\Exception;
use PHPExcel;
use PHPExcel_IOFactory;

/**
 * SabanaAccesosInstalacionController implements the CRUD actions for SabanaAccesosInstalacion model.
 */
class AccesosinstalacionController extends Controller {

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SabanaAccesosInstalacion models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SabanaAccesosInstalacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpload() {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $returndata = ['data' => '', 'error' => ''];
            return $this->render('upload', $returndata);
        }
        try {

            //directorio raiz
            $keyfolderraiz = Settings::find()->where(['key' => 'RUTARAIZDOCS'])->one();
            $root_path = $keyfolderraiz->value;

            //crear en disco path
            $fpath = $root_path . '/temp';

            $rights = 0777;
            $dirs = explode('/', $fpath);
            $dir = '';
            foreach ($dirs as $part) {
                $dir .= $part . '/';
                if (!is_dir($dir) && strlen($dir) > 0) {
                    mkdir($dir, $rights);
                }
            }

            if (isset($_FILES['file']['tmp_name'])) {
                $tempFile = $_FILES['file']['tmp_name'];
                $targetFile = $fpath . '/' . $_FILES['file']['name'];
                //var_dump($targetFile);exit;
                move_uploaded_file($tempFile, $targetFile);

                set_time_limit(600);
                
                $inputFileType = \PHPExcel_IOFactory::identify($targetFile);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($targetFile);

                $input = Yii::$app->request->post();
                $filetype = $input['filetype'];

                if ($filetype == "1") { // archivo sabanas
                    Yii::$app->db->createCommand()->truncateTable('sabana_reporte_instalacion')->execute();

                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColunm = $sheet->getHighestColumn();

                    for ($row = 3; $row <= $highestRow; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColunm . $row, NULL, TRUE, FALSE);
                        $newrec = new SabanaReporteInstalacion();
                        $newrec->Operador = $rowData[0][0];
                        $newrec->Documento_cliente_acceso = $rowData[0][1];
                        $newrec->Dane_Mun_ID_Punto = $rowData[0][2];
                        $newrec->Estado_actual = $rowData[0][3];
                        $newrec->Region = $rowData[0][4];
                        $newrec->Dane_Departamento = $rowData[0][5];
                        $newrec->Departamento = $rowData[0][6];
                        $newrec->Dane_Municipio = $rowData[0][7];
                        $newrec->Municipio = $rowData[0][8];
                        $newrec->Barrio = $rowData[0][9];
                        $newrec->Direccion = $rowData[0][10];
                        $newrec->Estrato = $rowData[0][11];
                        $newrec->Dificultad__de_acceso_al_municipio = $rowData[0][12];
                        $newrec->Coordenadas_Grados_decimales = $rowData[0][13];
                        $newrec->Nombre_Cliente = $rowData[0][14];
                        $newrec->Telefono = $rowData[0][15];
                        $newrec->Celular = $rowData[0][16];
                        $newrec->Correo_Electronico = $rowData[0][17];
                        $newrec->VIP = $rowData[0][18];
                        $newrec->Codigo_Proyecto_VIP = $rowData[0][19];
                        $newrec->Nombre_Proyecto_VIP = $rowData[0][20];
                        $newrec->Velocidad_Contratada_Downstream = $rowData[0][21];
                        $newrec->Meta = $rowData[0][22];
                        $newrec->Fecha_max_de_cumplimiento_de_meta = $rowData[0][23];
                        $newrec->Dias_pendientes_de_la_fecha_de_cumplimiento = $rowData[0][24];
                        $newrec->FECHA_APROBACION_INTERVENTORIA = $rowData[0][25];
                        $newrec->FECHA_APROBACION_META_SUPERVISION = $rowData[0][26];
                        $newrec->Tipo_Solucion_UM_Operatividad = $rowData[0][27];
                        $newrec->Operador_Prestante = $rowData[0][28];
                        $newrec->IP = $rowData[0][29];
                        $newrec->Olt = $rowData[0][30];
                        $newrec->PuertoOlt = $rowData[0][31];
                        $newrec->Serial_ONT = $rowData[0][32];
                        $newrec->Port_ONT = $rowData[0][33];
                        $newrec->Nodo = $rowData[0][34];
                        $newrec->Armario = $rowData[0][35];
                        $newrec->Red_Primaria = $rowData[0][36];
                        $newrec->Red_Secundaria = $rowData[0][37];
                        $newrec->Nodo2 = $rowData[0][38];
                        $newrec->Amplificador = $rowData[0][39];
                        $newrec->Tap_Boca = $rowData[0][40];
                        $newrec->Mac_Cpe = $rowData[0][41];
                        $newrec->Fecha_Asignado_o_Presupuestado = $rowData[0][42];
                        $newrec->Fecha_En_proceso_de_Instalacion = $rowData[0][43];
                        $newrec->Fecha_Anulado = $rowData[0][44];
                        $newrec->Fecha_Instalado = $rowData[0][45];
                        $newrec->Fecha_Activo = $rowData[0][46];
                        $newrec->Fecha_aprobacion_de_meta = $rowData[0][47];
                        $newrec->Sexo = $rowData[0][48];
                        $newrec->Genero = $rowData[0][49];
                        $newrec->Orientacion_Sexual = $rowData[0][50];
                        $newrec->Educacion = $rowData[0][51];
                        $newrec->Etnias = $rowData[0][52];
                        $newrec->Discapacidad = $rowData[0][53];
                        $newrec->Estratos = $rowData[0][54];
                        $newrec->Beneficiario_Ley_1699_de_2013 = $rowData[0][55];
                        $newrec->SISBEN_IV = $rowData[0][56];

                        $newrec->save(false);
                    };

                    $returndata = ['data' => 'ok', 'error' => $targetFile . ' - (' . ($highestRow - 2) . ') Registros Procesados. '];
                    return $this->render('upload', $returndata);
                }
                if ($filetype == "2") { // archivo metas
                    Yii::$app->db->createCommand()->truncateTable('avances_metas_instalacion')->execute();

                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColunm = $sheet->getHighestColumn();
                    $highestColunm = 'E'; //sin formulas
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColunm . $row, NULL, TRUE, FALSE);

                        $sql = "SELECT count(*) as cantidad
                                FROM sabana_reporte_instalacion s 
                                WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '" . $rowData[0][0] . "'";

                        $conteo = Yii::$app->db->createCommand($sql)->queryOne();
                        $meta = (float) ($rowData[0][3]);
                        $cantidad = (float) ($conteo['cantidad']);
                        $newrec = new AvancesMetasInstalacion();
                        $newrec->DANE = $rowData[0][0];
                        $newrec->Departamento = $rowData[0][1];
                        $newrec->Municipio = $rowData[0][2];
                        $newrec->Meta = $meta;
                        $newrec->Beneficiarios_Instalados = $cantidad;
                        $newrec->Avance = 0;
                        if ($cantidad > 0) {
                            $result = (float) ($cantidad / $meta);
                            $newrec->Avance = number_format($result, 2);
                        }
                        $newrec->save(false);
                    };

                    $returndata = ['data' => 'ok', 'error' => $targetFile . ' - (' . ($highestRow - 1) . ') Registros Procesados. '];
                    return $this->render('upload', $returndata);
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
            $returndata = ['data' => 'notok', 'error' => $ex->getMessage()];
            return $this->render('upload', $returndata);
        }
    }

    /**
     * Displays a single SabanaAccesosInstalacion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SabanaAccesosInstalacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new SabanaAccesosInstalacion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sabana_accesos_instalacion_id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing SabanaAccesosInstalacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sabana_accesos_instalacion_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SabanaAccesosInstalacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SabanaAccesosInstalacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SabanaAccesosInstalacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SabanaAccesosInstalacion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}

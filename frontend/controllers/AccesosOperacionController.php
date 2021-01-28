<?php

namespace frontend\controllers;

use Yii;
use app\models\SabanaReporteOperacion;
use app\models\SabanaReporteOperacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Settings;
use app\models\AvancesMetaOperacion;
use yii\base\Exception;
use PHPExcel;
use PHPExcel_IOFactory;
use Vtiful\Kernel\Excel;
use Fpdf\Fpdf;

/**
 * SabanaAccesosOperacionController implements the CRUD actions for SabanaAccesosOperacion model.
 */
class AccesosoperacionController extends Controller {

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

    public function actionUpload() {
        ini_set('memory_limit', '-1');
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

                $input = Yii::$app->request->post();
                $filetype = $input['filetype'];

                $config = ['path' => $fpath];
                $excel = new Excel($config);

                $data = $excel->openFile($_FILES['file']['name'])
                        ->openSheet()
                        ->getSheetData();

                if ($filetype == "1") { // archivo sabanas
                    Yii::$app->db->createCommand()->truncateTable('sabana_reporte_operacion')->execute();
//                    var_dump($data);                    
                    $i = 0;
                    foreach ($data as $rowData) {
                        if ($i > 0) {
                            $newrec = new SabanaReporteOperacion();
                            $newrec->Operador = $rowData[0];
                            $newrec->Documento_cliente_acceso = $rowData[1];
                            $newrec->Dane_Mun_ID_Punto = isset($rowData[2]) ? $rowData[2] : '';
                            $newrec->Estado_actual = $rowData[3];
                            $newrec->Region = $rowData[4];
                            $newrec->Dane_Departamento = str_pad(strval($rowData[5]), 2, "0", STR_PAD_LEFT);
                            $newrec->Departamento = $rowData[6];
                            $newrec->Dane_Municipio = str_pad(strval($rowData[7]), 3, "0", STR_PAD_LEFT);
                            $newrec->Municipio = $rowData[8];
                            $newrec->Barrio = $rowData[9];
                            $newrec->Direccion = $rowData[10];
                            $newrec->Estrato = $rowData[11];
                            $newrec->Dificultad__de_acceso_al_municipio = $rowData[12];
                            $newrec->Coordenadas_Grados_decimales = $rowData[13];
                            $newrec->Nombre_Cliente = $rowData[14];
                            $newrec->Telefono = $rowData[15];
                            $newrec->Celular = $rowData[16];
                            $newrec->Correo_Electronico = $rowData[17];
                            $newrec->VIP = $rowData[18];
                            $newrec->Codigo_Proyecto_VIP = $rowData[19];
                            $newrec->Nombre_Proyecto_VIP = $rowData[20];
                            $newrec->Velocidad_Contratada_Downstream = $rowData[21];
                            $newrec->Meta = $rowData[22];
                            $newrec->Fecha_max_de_cumplimiento_de_meta = $rowData[23];
                            $newrec->Tipo_Solucion_UM_Operatividad = $rowData[24];
                            $newrec->Operador_Prestante = $rowData[25];
                            $newrec->IP = $rowData[26];
                            $newrec->Olt = $rowData[27];
                            $newrec->PuertoOlt = $rowData[28];
                            $newrec->Serial_ONT = $rowData[29];
                            $newrec->Port_ONT = $rowData[30];
                            $newrec->Nodo = $rowData[31];
                            $newrec->Armario = $rowData[32];
                            $newrec->Red_Primaria = $rowData[33];
                            $newrec->Red_Secundaria = $rowData[34];
                            $newrec->Nodo2 = $rowData[35];
                            $newrec->Amplificador = $rowData[36];
                            $newrec->Tap_Boca = $rowData[37];
                            $newrec->Mac_Cpe = $rowData[38];
                            $newrec->Fecha_Instalado = is_numeric($rowData[39]) ? gmdate("d/m/Y", intval(($rowData[39] - 25569) * 86400)) : '';
                            $newrec->Fecha_Activo = is_numeric($rowData[40]) ? gmdate("d/m/Y", intval(($rowData[40] - 25569) * 86400)) : '';
                            $newrec->Fecha_inicio_operación = is_numeric($rowData[41]) ? gmdate("d/m/Y", intval(($rowData[41] - 25569) * 86400)) : '';
                            $newrec->Fecha_Solicitud_Traslado_PQR = is_numeric($rowData[42]) ? gmdate("d/m/Y", intval(($rowData[42] - 25569) * 86400)) : '';
                            $newrec->Fecha_Inactivo = is_numeric($rowData[43]) ? gmdate("d/m/Y", intval(($rowData[43] - 25569) * 86400)) : '';
                            $newrec->Fecha_Desinstalado = is_numeric($rowData[44]) ? gmdate("d/m/Y", intval(($rowData[44] - 25569) * 86400)) : '';
                            $newrec->Sexo = $rowData[45];
                            $newrec->Genero = $rowData[46];
                            $newrec->Orientacion_Sexual = $rowData[47];
                            $newrec->Educacion_ = $rowData[48];
                            $newrec->Etnias = $rowData[49];
                            $newrec->Discapacidad = $rowData[50];
                            $newrec->Estratos = $rowData[51];
                            $newrec->Beneficiario_Ley_1699_de_2013 = $rowData[52];
                            $newrec->SISBEN_IV = $rowData[53];
                            $newrec->save(false);
                        }
                        $i = $i + 1;
                    };

                    $returndata = ['data' => 'ok', 'error' => $targetFile . ' - (' . $i . ') Registros Procesados. '];
                    return $this->render('upload', $returndata);
                }
                if ($filetype == "2") { // archivo metas
                    Yii::$app->db->createCommand()->truncateTable('avances_meta_operacion')->execute();

                    $i = 0;
                    foreach ($data as $rowData) {
                        if ($i > 1) {
                            $sql = "SELECT count(*) as cantidad
                                FROM sabana_reporte_operacion s 
                                WHERE CONCAT(Dane_Departamento,Dane_Municipio) = '" . str_pad(strval($rowData[0]), 5, "0", STR_PAD_LEFT) . "'";

                            $conteo = Yii::$app->db->createCommand($sql)->queryOne();
                            $meta = (float) ($rowData[3]);
                            $cantidad = (float) ($conteo['cantidad']);
                            $newrec = new AvancesMetaOperacion();
                            $newrec->DANE = str_pad(strval($rowData[0]), 5, "0", STR_PAD_LEFT);
                            $newrec->Departamento = $rowData[1];
                            $newrec->Municipio = $rowData[2];
                            $newrec->Meta = $meta;
                            $newrec->Beneficiarios_En_Operacion = $cantidad;
                            $newrec->Meta_Tiempo_en_servicio = $rowData[5];
                            $newrec->Tiempo_en_servicio = $rowData[6];
                            $newrec->Avance = 0;
                            if ($cantidad > 0) {
                                $result = (float) ($cantidad / $meta);
                                $newrec->Avance = round(($result * 100), 2);
                            }
                            $newrec->save(false);
                        }
                        $i = $i + 1;
                    };

                    $returndata = ['data' => 'ok', 'error' => $targetFile . ' - (' . $i . ') Registros Procesados. '];
                    return $this->render('upload', $returndata);
                }
            }
        } catch (\Exception $ex) {
            $returndata = ['data' => 'notok', 'error' => $ex->getMessage()];
            return $this->render('upload', $returndata);
        }
    }

    /**
     * Lists all SabanaAccesosOperacion models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SabanaAccesosOperacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SabanaAccesosOperacion model.
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
     * Creates a new SabanaAccesosOperacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new SabanaAccesosOperacion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sabana_accesos_operacion_id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing SabanaAccesosOperacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sabana_accesos_operacion_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SabanaAccesosOperacion model.
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
     * Finds the SabanaAccesosOperacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SabanaAccesosOperacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SabanaAccesosOperacion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}

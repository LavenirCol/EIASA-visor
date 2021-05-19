<?php

namespace frontend\controllers;

use Yii;
use app\models\Settings;
use app\models\TraficoOlts;
use app\models\TraficoOltsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Vtiful\Kernel\Excel;

/**
 * TraficoOltsController implements the CRUD actions for TraficoOlts model.
 */
class TraficoOltsController extends Controller {

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
     * Lists all TraficoOlts models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TraficoOltsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TraficoOlts model.
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
     * Creates a new TraficoOlts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TraficoOlts();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing TraficoOlts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TraficoOlts model.
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
     * Finds the TraficoOlts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TraficoOlts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TraficoOlts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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
                
                $i = 0;
                foreach ($data as $rowData) {
                    if ($i > 1) {

                        //consulta OLT
                        $olt = TraficoOlts::find()->where(['poblacion' => $this->eliminar_acentos($rowData[2])])->one();
                        if (isset($olt)) {
                            //var_dump($rowData[3]);
                            $olt->bandwidth = $rowData[3];
                            $olt->save(false);
                        }                        
                    }
                    $i = $i + 1;
                };

                $returndata = ['data' => 'ok', 'error' => $targetFile . ' - (' . $i . ') Registros Procesados. '];
                return $this->render('upload', $returndata);
            }
        } catch (\Exception $ex) {
            $returndata = ['data' => 'notok', 'error' => $ex->getMessage()];
            return $this->render('upload', $returndata);
        }
    }
    
    public function eliminar_acentos($cadena){
		
        //Reemplazamos la A y a
        $cadena = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena );

        //Reemplazamos la I y i
        $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena );

        //Reemplazamos la O y o
        $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena );

        //Reemplazamos la U y u
        $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç'),
        array('N', 'n', 'C', 'c'),
        $cadena
        );

        return $cadena;
    }

}

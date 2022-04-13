<?php

namespace frontend\controllers;

use Yii;
use app\models\Hsstock;
use app\models\HsstockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Vtiful\Kernel\Excel;
use yii\base\Exception;
use app\models\Settings;

/**
 * HsstockController implements the CRUD actions for Hsstock model.
 */
class HsstockController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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
     * Lists all Hsstock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HsstockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hsstock model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Hsstock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hsstock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Hsstock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Hsstock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Hsstock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Hsstock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hsstock::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    
    public function actionUpload() {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 0);
        set_time_limit(0);
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

                $input = Yii::$app->request->post();
                $filetype = $input['filetype'];

                $config = ['path' => $fpath];
                $excel = new Excel($config);

                $data = $excel->openFile($_FILES['file']['name'])
                        ->openSheet()
                        ->getSheetData();

                if ($filetype == "1") { // archivo inventarios
                    Yii::$app->db->createCommand()->truncateTable('hsstock')->execute();
//                    var_dump($data);   
                    $i = 0;
                    foreach ($data as $rowData) {
                        if ($i > 1) {
                            if(!isset($rowData[0]) || empty($rowData[0])){
                                continue;
                            }
                            // crea inventario
                            $newinv = new Hsstock();
                            $newinv->id = $i;
                            $newinv->pid = $i;
                            $newinv->uuid = $i;
                            $newinv->name = $rowData[8];
                            $newinv->factory = $rowData[9];
                            $newinv->model = $rowData[1];
                            $newinv->datecreate = '01/10/2021  12:00';
                            $newinv->sku = $rowData[0];
                            $newinv->health_reg = $i;
                            $newinv->quantity = $rowData[11];
                            $newinv->measure = $rowData[10];
                            $newinv->location = $rowData[7];
                            $newinv->city = $rowData[4];
                            $newinv->city_code = $rowData[3];
                            $newinv->district = $rowData[6];
                            $newinv->district_code = $rowData[5];
                            $str_coord = explode (",", $rowData[12]); 
                            $newinv->lat = $str_coord[0];
                            $newinv->lng = $str_coord[1];
                            $newinv->Estado = $rowData[13];
                            $newinv->FuenteFinanciacion = $rowData[14];

                            $newinv->save(false);

                        }
                        $i = $i + 1;
                    };

                    $returndata = ['data' => 'ok', 'error' => $targetFile . ' - (' . $i . ') Registros Procesados. '];
                    return $this->render('upload', $returndata);
                }

            }
        } catch (\Exception $ex) {
            throw $ex;
            $returndata = ['data' => 'notok', 'error' => $ex->getMessage()];
            return $this->render('upload', $returndata);
        }
    }
}

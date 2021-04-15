<?php

namespace frontend\controllers;

use Yii;
use app\models\Privileges;
use app\models\PrivilegesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PrivilegesController implements the CRUD actions for Privileges model.
 */
class PrivilegesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Privileges models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrivilegesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Privileges model.
     * @param string $idProfile
     * @param string $idOption
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($idProfile, $idOption)
    {
        return $this->render('view', [
            'model' => $this->findModel($idProfile, $idOption),
        ]);
    }

    /**
     * Creates a new Privileges model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Privileges();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idProfile' => $model->idProfile, 'idOption' => $model->idOption]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Privileges model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $idProfile
     * @param string $idOption
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($idProfile, $idOption)
    {
        $model = $this->findModel($idProfile, $idOption);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idProfile' => $model->idProfile, 'idOption' => $model->idOption]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Privileges model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $idProfile
     * @param string $idOption
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($idProfile, $idOption)
    {
        $this->findModel($idProfile, $idOption)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Privileges model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $idProfile
     * @param string $idOption
     * @return Privileges the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idProfile, $idOption)
    {
        if (($model = Privileges::findOne(['idProfile' => $idProfile, 'idOption' => $idOption])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

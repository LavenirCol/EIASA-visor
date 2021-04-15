<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Settings;
use app\models\Tickets;
use yii\filters\AccessControl;
use yii\base\Exception;

class ConfigController extends Controller
{
    public function beforeAction($action) 
    {  
        return parent::beforeAction($action);
    }
    public function behaviors()
    {
        
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'edit', 'updateconfigtickets'],
                'rules' => [
                    [
                        'actions' => ['index', 'edit', 'updateconfigtickets'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'  => ['GET'],
                    'edit'   => ['GET'],
                    'updateconfigtickets' => ['PUT'],                    
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $settings = Settings::find()->where(['!=','key','RUTARAIZDOCS'])
        ->andWhere(['!=','key', 'ASSIGNEDDISKSIZEGB'])
        ->andWhere(['!=','key', 'URLBASE'])
        ->all();
        return $this->render('index',['settings' => $settings]);
    }

    public function actionEdit()
    {
        $categoryList = Tickets::find()->select('category_label')
        ->distinct()
        ->orderBy('category_label')
        ->all();
        $configList = Settings::find()->where(['=','key','CATEGORIAS VISIBLE TICKETS'])
        ->one();
        return $this->render('edit',['categoryList' => $categoryList, 'configList' => $configList->value]);
    }

    public function actionUpdateconfigtickets()
    {
        $response = Yii::$app->response;
        try
        {
            $configTickets = Settings::find()->where(['=','key','CATEGORIAS VISIBLE TICKETS'])
            ->one();
            $configList = Yii::$app->request->bodyParams['configList'];
            $configTickets->value = implode(",",$configList);
            $configTickets->save();
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->statusCode = 200;
            $response->data = ['message' => 'Configuration was saved'];            
            
        }catch(Exception $exception)
        {
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->statusCode = 500;
            $response->data = ['message' => $exception->getMessage()];
        }
        return $response;
    }
}
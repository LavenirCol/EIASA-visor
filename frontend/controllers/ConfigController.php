<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Settings;
use app\models\Tickets;

class ConfigController extends Controller
{
    public function beforeAction($action) 
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public function behaviors()
    {
        
        return [
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
        $configTickets = Settings::find()->where(['=','key','CATEGORIAS VISIBLE TICKETS'])
        ->one();
        $configList = Yii::$app->request->bodyParams['configList'];
        
        
        $configTickets->value = implode(",",$configList);
        $configTickets->save();

        return json_encode("ok");
    }
}
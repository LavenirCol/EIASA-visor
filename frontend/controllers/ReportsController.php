<?php

namespace frontend\controllers;

class ReportsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionInventarios()
    {
        return $this->render('inventarios');
    }

    public function actionInstalacion()
    {
        return $this->render('instalacion');
    }
    
    public function actionOperacion()
    {
        return $this->render('operacion');
    }
    
    public function actionPqrs()
    {
        return $this->render('pqrs');
    }
}

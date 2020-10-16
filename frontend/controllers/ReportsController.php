<?php

namespace frontend\controllers;
use Yii;

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
        $connection = Yii::$app->getDb();
        $sql = "SELECT t.*, c.access_id, c.name, c.town, c.state FROM tickets t inner join client c on t.fk_soc = c.idClient";

        $pqrs = $connection->createCommand($sql)->queryAll();
        
        return $this->render('pqrs', array('pqrs' => $pqrs));
    }
}

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
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM hsstock h";        
        $invs = $connection->createCommand($sql)->queryAll();

        return $this->render('inventarios', array('invs' => $invs));
    }

    public function actionInstalacion()
    {
        $connection = Yii::$app->getDb();
        $sql = "SELECT h.* FROM hstask h";        
        $inst = $connection->createCommand($sql)->queryAll();

        return $this->render('instalacion', array('inst' => $inst));
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

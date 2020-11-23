<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteInstalacion */

$this->title = 'Update Sabana Reporte Instalacion: ' . $model->sabana_reporte_instalacion_id;
$this->params['breadcrumbs'][] = ['label' => 'Sabana Reporte Instalacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sabana_reporte_instalacion_id, 'url' => ['view', 'id' => $model->sabana_reporte_instalacion_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sabana-reporte-instalacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteCambiosReemplazos */

$this->title = 'Update Sabana Reporte Cambios Reemplazos: ' . $model->sabana_reporte_cambios_reemplazos_id;
$this->params['breadcrumbs'][] = ['label' => 'Sabana Reporte Cambios Reemplazos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sabana_reporte_cambios_reemplazos_id, 'url' => ['view', 'id' => $model->sabana_reporte_cambios_reemplazos_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sabana-reporte-cambios-reemplazos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

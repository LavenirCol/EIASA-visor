<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteOperacion */

$this->title = 'Update Sabana Reporte Operacion: ' . $model->sabana_reporte_operacion_id;
$this->params['breadcrumbs'][] = ['label' => 'Sabana Reporte Operacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sabana_reporte_operacion_id, 'url' => ['view', 'id' => $model->sabana_reporte_operacion_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sabana-reporte-operacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

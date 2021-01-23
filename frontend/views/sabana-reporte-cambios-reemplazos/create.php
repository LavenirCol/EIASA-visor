<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteCambiosReemplazos */

$this->title = 'Create Sabana Reporte Cambios Reemplazos';
$this->params['breadcrumbs'][] = ['label' => 'Sabana Reporte Cambios Reemplazos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sabana-reporte-cambios-reemplazos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

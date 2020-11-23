<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteOperacion */

$this->title = 'Create Sabana Reporte Operacion';
$this->params['breadcrumbs'][] = ['label' => 'Sabana Reporte Operacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sabana-reporte-operacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

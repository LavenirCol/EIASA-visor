<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteInstalacion */

$this->title = 'Create Sabana Reporte Instalacion';
$this->params['breadcrumbs'][] = ['label' => 'Sabana Reporte Instalacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sabana-reporte-instalacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

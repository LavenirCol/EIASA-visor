<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaAccesosOperacion */

$this->title = Yii::t('app', 'Update Sabana Accesos Operacion: {name}', [
    'name' => $model->sabana_accesos_operacion_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sabana Accesos Operacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sabana_accesos_operacion_id, 'url' => ['view', 'id' => $model->sabana_accesos_operacion_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sabana-accesos-operacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

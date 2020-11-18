<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AvancesMetaOperacion */

$this->title = Yii::t('app', 'Update Avances Meta Operacion: {name}', [
    'name' => $model->avances_meta_operacion_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Avances Meta Operacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->avances_meta_operacion_id, 'url' => ['view', 'id' => $model->avances_meta_operacion_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="avances-meta-operacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

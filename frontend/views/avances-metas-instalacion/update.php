<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AvancesMetasInstalacion */

$this->title = Yii::t('app', 'Update Avances Metas Instalacion: {name}', [
    'name' => $model->avances_metas_instalacion_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Avances Metas Instalacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->avances_metas_instalacion_id, 'url' => ['view', 'id' => $model->avances_metas_instalacion_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="avances-metas-instalacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

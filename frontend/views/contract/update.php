<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contract */

$this->title = Yii::t('app', 'Update Contract: {name}', [
    'name' => $model->idContract,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contracts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idContract, 'url' => ['view', 'id' => $model->idContract]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="contract-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

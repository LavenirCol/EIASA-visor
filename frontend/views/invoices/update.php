<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Invoices */

$this->title = Yii::t('app', 'Update Invoices: {name}', [
    'name' => $model->idInvoice,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idInvoice, 'url' => ['view', 'id' => $model->idInvoice]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="invoices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

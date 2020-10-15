<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tickets */

$this->title = Yii::t('app', 'Update Tickets: {name}', [
    'name' => $model->idTicket,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idTicket, 'url' => ['view', 'id' => $model->idTicket]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tickets-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

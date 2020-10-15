<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TicketsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tickets-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'idTicket') ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'socid') ?>

    <?= $form->field($model, 'ref') ?>

    <?= $form->field($model, 'fk_soc') ?>

    <?php // echo $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'type_label') ?>

    <?php // echo $form->field($model, 'category_label') ?>

    <?php // echo $form->field($model, 'severity_label') ?>

    <?php // echo $form->field($model, 'datec') ?>

    <?php // echo $form->field($model, 'date_read') ?>

    <?php // echo $form->field($model, 'date_close') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

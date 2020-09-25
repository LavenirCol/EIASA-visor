<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'idClient') ?>

    <?= $form->field($model, 'entity') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'state_id') ?>

    <?= $form->field($model, 'state_code') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'town') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'idprof1') ?>

    <?php // echo $form->field($model, 'code_client') ?>

    <?php // echo $form->field($model, 'ref') ?>

    <?php // echo $form->field($model, 'country_id') ?>

    <?php // echo $form->field($model, 'country_code') ?>

    <?php // echo $form->field($model, 'country') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

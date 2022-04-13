<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HsstockSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hsstock-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'pid') ?>

    <?= $form->field($model, 'uuid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'factory') ?>

    <?php // echo $form->field($model, 'model') ?>

    <?php // echo $form->field($model, 'datecreate') ?>

    <?php // echo $form->field($model, 'sku') ?>

    <?php // echo $form->field($model, 'health_reg') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'measure') ?>

    <?php // echo $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'city_code') ?>

    <?php // echo $form->field($model, 'district') ?>

    <?php // echo $form->field($model, 'district_code') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'lng') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

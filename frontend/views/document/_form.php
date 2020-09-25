<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'level1name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'relativename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'size')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iddocumentType')->textInput() ?>

    <?= $form->field($model, 'idFolder')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

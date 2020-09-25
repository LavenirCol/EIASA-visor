<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Module */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="module-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'moduleName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'moduleReadOnly')->textInput() ?>

    <?= $form->field($model, 'moduleCreationDate')->textInput() ?>

    <?= $form->field($model, 'moduleCreationUserId')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

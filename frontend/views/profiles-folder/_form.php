<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProfilesFolder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profiles-folder-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idProfile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idFolder')->textInput() ?>

    <?= $form->field($model, 'creationDate')->textInput() ?>

    <?= $form->field($model, 'creationUserId')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Privileges */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="privileges-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idProfile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idOption')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

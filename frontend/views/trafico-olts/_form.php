<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TraficoOlts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trafico-olts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'poblacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wan_olt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'activo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

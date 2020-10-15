<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tickets */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tickets-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'socid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ref')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fk_soc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type_label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'severity_label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'datec')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_read')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_close')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

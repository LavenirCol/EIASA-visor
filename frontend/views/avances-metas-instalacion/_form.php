<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AvancesMetasInstalacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="avances-metas-instalacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'DANE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Departamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Meta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Beneficiarios_Instalados')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Avance')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

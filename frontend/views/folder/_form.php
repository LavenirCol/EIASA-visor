<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Folder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="folder-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'folderName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'folderDefault')->textInput() ?>

    <?= $form->field($model, 'idParentFolder')->textInput() ?>

    <?= $form->field($model, 'folderCreationDate')->textInput() ?>

    <?= $form->field($model, 'folderCreationUserId')->textInput() ?>

    <?= $form->field($model, 'folderReadOnly')->textInput() ?>

    <?= $form->field($model, 'idmodule')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

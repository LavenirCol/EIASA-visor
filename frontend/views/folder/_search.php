<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FolderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="folder-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'idfolder') ?>

    <?= $form->field($model, 'folderName') ?>

    <?= $form->field($model, 'folderDefault') ?>

    <?= $form->field($model, 'idParentFolder') ?>

    <?= $form->field($model, 'folderCreationDate') ?>

    <?php // echo $form->field($model, 'folderCreationUserId') ?>

    <?php // echo $form->field($model, 'folderReadOnly') ?>

    <?php // echo $form->field($model, 'idmodule') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

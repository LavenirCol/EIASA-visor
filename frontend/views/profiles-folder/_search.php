<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProfilesFolderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profiles-folder-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'idprofilefolder') ?>

    <?= $form->field($model, 'idProfile') ?>

    <?= $form->field($model, 'idFolder') ?>

    <?= $form->field($model, 'creationDate') ?>

    <?= $form->field($model, 'creationUserId') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

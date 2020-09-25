<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'iddocument') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'path') ?>

    <?= $form->field($model, 'level1name') ?>

    <?= $form->field($model, 'relativename') ?>

    <?php // echo $form->field($model, 'fullname') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'iddocumentType') ?>

    <?php // echo $form->field($model, 'idFolder') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

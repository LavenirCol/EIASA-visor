<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ContractSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'idContract') ?>

    <?= $form->field($model, 'entityID') ?>

    <?= $form->field($model, 'socID') ?>

    <?= $form->field($model, 'ref') ?>

    <?= $form->field($model, 'fkSoc') ?>

    <?php // echo $form->field($model, 'idFolder') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

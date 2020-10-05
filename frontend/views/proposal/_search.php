<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProposalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="proposal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'idProposal') ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'entity') ?>

    <?= $form->field($model, 'socid') ?>

    <?= $form->field($model, 'ref') ?>

    <?php // echo $form->field($model, 'idFolder') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AvancesMetasInstalacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="avances-metas-instalacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'avances_metas_instalacion_id') ?>

    <?= $form->field($model, 'DANE') ?>

    <?= $form->field($model, 'Departamento') ?>

    <?= $form->field($model, 'Municipio') ?>

    <?= $form->field($model, 'Meta') ?>

    <?php // echo $form->field($model, 'Beneficiarios_Instalados') ?>

    <?php // echo $form->field($model, 'Avance') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

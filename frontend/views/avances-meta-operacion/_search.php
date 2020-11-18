<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AvancesMetaOperacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="avances-meta-operacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'avances_meta_operacion_id') ?>

    <?= $form->field($model, 'DANE') ?>

    <?= $form->field($model, 'Departamento') ?>

    <?= $form->field($model, 'Municipio') ?>

    <?= $form->field($model, 'Meta') ?>

    <?php // echo $form->field($model, 'Beneficiarios_En_Operacion') ?>

    <?php // echo $form->field($model, 'Meta_Tiempo_en_servicio') ?>

    <?php // echo $form->field($model, 'Tiempo_en_servicio') ?>

    <?php // echo $form->field($model, 'Avance') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaAccesosInstalacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sabana-accesos-instalacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'sabana_accesos_instalacion_id') ?>

    <?= $form->field($model, 'Operador') ?>

    <?= $form->field($model, 'Documento_Cliente_Acceso') ?>

    <?= $form->field($model, 'Dane_Mun_ID_Punto') ?>

    <?= $form->field($model, 'Estado_Actual') ?>

    <?php // echo $form->field($model, 'Region') ?>

    <?php // echo $form->field($model, 'Dane_Departamento') ?>

    <?php // echo $form->field($model, 'Departamento') ?>

    <?php // echo $form->field($model, 'Dane_Municipio') ?>

    <?php // echo $form->field($model, 'Municipio') ?>

    <?php // echo $form->field($model, 'Barrio') ?>

    <?php // echo $form->field($model, 'Dirección') ?>

    <?php // echo $form->field($model, 'Estrato') ?>

    <?php // echo $form->field($model, 'Dificultad_de_acceso_al_municipio') ?>

    <?php // echo $form->field($model, 'Coordenadas_Grados-decimales') ?>

    <?php // echo $form->field($model, 'Nombre_Cliente_Completo') ?>

    <?php // echo $form->field($model, 'Telefono') ?>

    <?php // echo $form->field($model, 'Celular') ?>

    <?php // echo $form->field($model, 'Correo_Electronico') ?>

    <?php // echo $form->field($model, 'VIP') ?>

    <?php // echo $form->field($model, 'Codigo_Proyecto_VIP') ?>

    <?php // echo $form->field($model, 'Nombre_Proyecto_VIP') ?>

    <?php // echo $form->field($model, 'Velocidad_Contratada_MB') ?>

    <?php // echo $form->field($model, 'Meta') ?>

    <?php // echo $form->field($model, 'Fecha_max_de_cumplimiento_de_meta') ?>

    <?php // echo $form->field($model, 'Dias_pendientes_de_la_fecha_de_cumplimiento') ?>

    <?php // echo $form->field($model, 'FECHA_APROBACION_INTERVENTORIA') ?>

    <?php // echo $form->field($model, 'FECHA_APROBACION_META_SUPERVISION') ?>

    <?php // echo $form->field($model, 'Tipo_Solucion_UM_Operatividad') ?>

    <?php // echo $form->field($model, 'Operador_Prestante') ?>

    <?php // echo $form->field($model, 'IP') ?>

    <?php // echo $form->field($model, 'Olt') ?>

    <?php // echo $form->field($model, 'PuertoOlt') ?>

    <?php // echo $form->field($model, 'Serial_ONT') ?>

    <?php // echo $form->field($model, 'Port_ONT') ?>

    <?php // echo $form->field($model, 'Nodo_Cobre') ?>

    <?php // echo $form->field($model, 'Armario') ?>

    <?php // echo $form->field($model, 'Red_Primaria') ?>

    <?php // echo $form->field($model, 'Red_Secundaria') ?>

    <?php // echo $form->field($model, 'Nodo_HFC') ?>

    <?php // echo $form->field($model, 'Amplificador') ?>

    <?php // echo $form->field($model, 'Tap-Boca') ?>

    <?php // echo $form->field($model, 'Mac_Cpe') ?>

    <?php // echo $form->field($model, 'Fecha_Asignado_o_Presupuestado') ?>

    <?php // echo $form->field($model, 'Fecha_En_proceso_de_Instalacion') ?>

    <?php // echo $form->field($model, 'Fecha_Anulado') ?>

    <?php // echo $form->field($model, 'Fecha_Instalado') ?>

    <?php // echo $form->field($model, 'Fecha_Activo') ?>

    <?php // echo $form->field($model, 'Fecha_aprobacion_de_meta') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

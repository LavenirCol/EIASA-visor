<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteInstalacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sabana-reporte-instalacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'sabana_reporte_instalacion_id') ?>

    <?= $form->field($model, 'Operador') ?>

    <?= $form->field($model, 'Documento_cliente_acceso') ?>

    <?= $form->field($model, 'Dane_Mun_ID_Punto') ?>

    <?= $form->field($model, 'Estado_actual') ?>

    <?php // echo $form->field($model, 'Region') ?>

    <?php // echo $form->field($model, 'Dane_Departamento') ?>

    <?php // echo $form->field($model, 'Departamento') ?>

    <?php // echo $form->field($model, 'Dane_Municipio') ?>

    <?php // echo $form->field($model, 'Municipio') ?>

    <?php // echo $form->field($model, 'Barrio') ?>

    <?php // echo $form->field($model, 'Direccion') ?>

    <?php // echo $form->field($model, 'Estrato') ?>

    <?php // echo $form->field($model, 'Dificultad__de_acceso_al_municipio') ?>

    <?php // echo $form->field($model, 'Coordenadas_Grados_decimales') ?>

    <?php // echo $form->field($model, 'Nombre_Cliente') ?>

    <?php // echo $form->field($model, 'Telefono') ?>

    <?php // echo $form->field($model, 'Celular') ?>

    <?php // echo $form->field($model, 'Correo_Electronico') ?>

    <?php // echo $form->field($model, 'VIP') ?>

    <?php // echo $form->field($model, 'Codigo_Proyecto_VIP') ?>

    <?php // echo $form->field($model, 'Nombre_Proyecto_VIP') ?>

    <?php // echo $form->field($model, 'Velocidad_Contratada_Downstream') ?>

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

    <?php // echo $form->field($model, 'Nodo') ?>

    <?php // echo $form->field($model, 'Armario') ?>

    <?php // echo $form->field($model, 'Red_Primaria') ?>

    <?php // echo $form->field($model, 'Red_Secundaria') ?>

    <?php // echo $form->field($model, 'Nodo2') ?>

    <?php // echo $form->field($model, 'Amplificador') ?>

    <?php // echo $form->field($model, 'Tap_Boca') ?>

    <?php // echo $form->field($model, 'Mac_Cpe') ?>

    <?php // echo $form->field($model, 'Fecha_Asignado_o_Presupuestado') ?>

    <?php // echo $form->field($model, 'Fecha_En_proceso_de_Instalacion') ?>

    <?php // echo $form->field($model, 'Fecha_Anulado') ?>

    <?php // echo $form->field($model, 'Fecha_Instalado') ?>

    <?php // echo $form->field($model, 'Fecha_Activo') ?>

    <?php // echo $form->field($model, 'Fecha_aprobacion_de_meta') ?>

    <?php // echo $form->field($model, 'Sexo') ?>

    <?php // echo $form->field($model, 'Genero') ?>

    <?php // echo $form->field($model, 'Orientacion_Sexual') ?>

    <?php // echo $form->field($model, 'Educacion') ?>

    <?php // echo $form->field($model, 'Etnias') ?>

    <?php // echo $form->field($model, 'Discapacidad') ?>

    <?php // echo $form->field($model, 'Estratos') ?>

    <?php // echo $form->field($model, 'Beneficiario_Ley_1699_de_2013') ?>

    <?php // echo $form->field($model, 'SISBEN_IV') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

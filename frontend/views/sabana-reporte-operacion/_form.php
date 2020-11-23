<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteOperacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sabana-reporte-operacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Operador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Documento_cliente_acceso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Mun_ID_Punto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Estado_actual')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Region')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Departamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Departamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Barrio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Estrato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dificultad__de_acceso_al_municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Coordenadas_Grados_decimales')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nombre_Cliente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Correo_Electronico')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'VIP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Codigo_Proyecto_VIP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nombre_Proyecto_VIP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Velocidad_Contratada_Downstream')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Meta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_max_de_cumplimiento_de_meta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Tipo_Solucion_UM_Operatividad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Operador_Prestante')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Olt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PuertoOlt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Serial_ONT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Port_ONT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nodo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Armario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Red_Primaria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Red_Secundaria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nodo2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Amplificador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Tap_Boca')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Mac_Cpe')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_Instalado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_Activo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_inicio_operación')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_Solicitud_Traslado_PQR')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_Inactivo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_Desinstalado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Sexo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Genero')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Orientacion_Sexual')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Educacion_')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Etnias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Discapacidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Estratos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Beneficiario_Ley_1699_de_2013')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SISBEN_IV')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteCambiosReemplazos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sabana-reporte-cambios-reemplazos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Ejecutor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Documento_Cliente_Acceso_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Mun_ID_Punto_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Estado_Actual_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Region_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Departamento_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Departamento_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Municipio_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Municipio_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Barrio_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Direccion_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Estrato_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Coordenadas_Grados_decimales_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nombre_Cliente_Completo_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Telefono_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Celular_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Correo_Electronico_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'VIP_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Codigo_Proyecto_VIP_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nombre_Proyecto_VIP_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Velocidad_Contratada_MB_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Meta_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Tipo_Solucion_UM_Operatividad_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Operador_Prestante_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IP_fibra_optica_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Olt_fibra_optica_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PuertoOlt_fibra_optica_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Mac_Onu_fibra_optica_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Port_Onu_fibra_optica_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nodo_red_cobre_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Armario_red_cobre_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Red_Primaria_red_cobre_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Red_Secundaria_red_cobre_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nodo_red_hfc_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Amplificador_red_hfc_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Tap_Boca_red_hfc_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Mac_Cpe_Old')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Documento_Cliente_Acceso_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Region_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Departamento_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Departamento_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Municipio_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Municipio_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Barrio_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Direccion_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Estrato_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Coordenadas_Grados_decimales_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nombre_Cliente_Completo_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Telefono_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Celular_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Correo_Electronico_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'VIP_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Codigo_Proyecto_VIP_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nombre_Proyecto_VIP_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Velocidad_Contratada_MB_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Meta_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Tipo_Solucion_UM_Operatividad_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Operador_Prestante_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IP_fibra_optica_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Olt_fibra_optica_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PuertoOlt_fibra_optica_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Mac_Onu_fibra_optica_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Port_Onu_fibra_optica_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nodo_red_cobre_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Armario_red_cobre_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Red_Primaria_red_cobre_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Red_Secundaria_red_cobre_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nodo_red_hfc_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Amplificador_red_hfc_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Tap_Boca_red_hfc_New')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Mac_Cpe_New')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteCambiosReemplazosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sabana-reporte-cambios-reemplazos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sabana_reporte_cambios_reemplazos_id') ?>

    <?= $form->field($model, 'Ejecutor') ?>

    <?= $form->field($model, 'Documento_Cliente_Acceso_Old') ?>

    <?= $form->field($model, 'Dane_Mun_ID_Punto_Old') ?>

    <?= $form->field($model, 'Estado_Actual_Old') ?>

    <?php // echo $form->field($model, 'Region_Old') ?>

    <?php // echo $form->field($model, 'Dane_Departamento_Old') ?>

    <?php // echo $form->field($model, 'Departamento_Old') ?>

    <?php // echo $form->field($model, 'Dane_Municipio_Old') ?>

    <?php // echo $form->field($model, 'Municipio_Old') ?>

    <?php // echo $form->field($model, 'Barrio_Old') ?>

    <?php // echo $form->field($model, 'Direccion_Old') ?>

    <?php // echo $form->field($model, 'Estrato_Old') ?>

    <?php // echo $form->field($model, 'Coordenadas_Grados_decimales_Old') ?>

    <?php // echo $form->field($model, 'Nombre_Cliente_Completo_Old') ?>

    <?php // echo $form->field($model, 'Telefono_Old') ?>

    <?php // echo $form->field($model, 'Celular_Old') ?>

    <?php // echo $form->field($model, 'Correo_Electronico_Old') ?>

    <?php // echo $form->field($model, 'VIP_Old') ?>

    <?php // echo $form->field($model, 'Codigo_Proyecto_VIP_Old') ?>

    <?php // echo $form->field($model, 'Nombre_Proyecto_VIP_Old') ?>

    <?php // echo $form->field($model, 'Velocidad_Contratada_MB_Old') ?>

    <?php // echo $form->field($model, 'Meta_Old') ?>

    <?php // echo $form->field($model, 'Tipo_Solucion_UM_Operatividad_Old') ?>

    <?php // echo $form->field($model, 'Operador_Prestante_Old') ?>

    <?php // echo $form->field($model, 'IP_fibra_optica_Old') ?>

    <?php // echo $form->field($model, 'Olt_fibra_optica_Old') ?>

    <?php // echo $form->field($model, 'PuertoOlt_fibra_optica_Old') ?>

    <?php // echo $form->field($model, 'Mac_Onu_fibra_optica_Old') ?>

    <?php // echo $form->field($model, 'Port_Onu_fibra_optica_Old') ?>

    <?php // echo $form->field($model, 'Nodo_red_cobre_Old') ?>

    <?php // echo $form->field($model, 'Armario_red_cobre_Old') ?>

    <?php // echo $form->field($model, 'Red_Primaria_red_cobre_Old') ?>

    <?php // echo $form->field($model, 'Red_Secundaria_red_cobre_Old') ?>

    <?php // echo $form->field($model, 'Nodo_red_hfc_Old') ?>

    <?php // echo $form->field($model, 'Amplificador_red_hfc_Old') ?>

    <?php // echo $form->field($model, 'Tap_Boca_red_hfc_Old') ?>

    <?php // echo $form->field($model, 'Mac_Cpe_Old') ?>

    <?php // echo $form->field($model, 'Documento_Cliente_Acceso_New') ?>

    <?php // echo $form->field($model, 'Region_New') ?>

    <?php // echo $form->field($model, 'Dane_Departamento_New') ?>

    <?php // echo $form->field($model, 'Departamento_New') ?>

    <?php // echo $form->field($model, 'Dane_Municipio_New') ?>

    <?php // echo $form->field($model, 'Municipio_New') ?>

    <?php // echo $form->field($model, 'Barrio_New') ?>

    <?php // echo $form->field($model, 'Direccion_New') ?>

    <?php // echo $form->field($model, 'Estrato_New') ?>

    <?php // echo $form->field($model, 'Coordenadas_Grados_decimales_New') ?>

    <?php // echo $form->field($model, 'Nombre_Cliente_Completo_New') ?>

    <?php // echo $form->field($model, 'Telefono_New') ?>

    <?php // echo $form->field($model, 'Celular_New') ?>

    <?php // echo $form->field($model, 'Correo_Electronico_New') ?>

    <?php // echo $form->field($model, 'VIP_New') ?>

    <?php // echo $form->field($model, 'Codigo_Proyecto_VIP_New') ?>

    <?php // echo $form->field($model, 'Nombre_Proyecto_VIP_New') ?>

    <?php // echo $form->field($model, 'Velocidad_Contratada_MB_New') ?>

    <?php // echo $form->field($model, 'Meta_New') ?>

    <?php // echo $form->field($model, 'Tipo_Solucion_UM_Operatividad_New') ?>

    <?php // echo $form->field($model, 'Operador_Prestante_New') ?>

    <?php // echo $form->field($model, 'IP_fibra_optica_New') ?>

    <?php // echo $form->field($model, 'Olt_fibra_optica_New') ?>

    <?php // echo $form->field($model, 'PuertoOlt_fibra_optica_New') ?>

    <?php // echo $form->field($model, 'Mac_Onu_fibra_optica_New') ?>

    <?php // echo $form->field($model, 'Port_Onu_fibra_optica_New') ?>

    <?php // echo $form->field($model, 'Nodo_red_cobre_New') ?>

    <?php // echo $form->field($model, 'Armario_red_cobre_New') ?>

    <?php // echo $form->field($model, 'Red_Primaria_red_cobre_New') ?>

    <?php // echo $form->field($model, 'Red_Secundaria_red_cobre_New') ?>

    <?php // echo $form->field($model, 'Nodo_red_hfc_New') ?>

    <?php // echo $form->field($model, 'Amplificador_red_hfc_New') ?>

    <?php // echo $form->field($model, 'Tap_Boca_red_hfc_New') ?>

    <?php // echo $form->field($model, 'Mac_Cpe_New') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

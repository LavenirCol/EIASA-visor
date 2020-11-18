<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaAccesosInstalacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sabana-accesos-instalacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Operador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Documento_Cliente_Acceso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Mun_ID_Punto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Estado_Actual')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Region')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Departamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Departamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dane_Municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Barrio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dirección')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Estrato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dificultad_de_acceso_al_municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Coordenadas_Grados-decimales')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nombre_Cliente_Completo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Correo_Electronico')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'VIP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Codigo_Proyecto_VIP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nombre_Proyecto_VIP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Velocidad_Contratada_MB')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Meta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_max_de_cumplimiento_de_meta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Dias_pendientes_de_la_fecha_de_cumplimiento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FECHA_APROBACION_INTERVENTORIA')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FECHA_APROBACION_META_SUPERVISION')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Tipo_Solucion_UM_Operatividad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Operador_Prestante')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Olt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'PuertoOlt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Serial_ONT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Port_ONT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nodo_Cobre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Armario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Red_Primaria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Red_Secundaria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Nodo_HFC')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Amplificador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Tap-Boca')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Mac_Cpe')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_Asignado_o_Presupuestado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_En_proceso_de_Instalacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_Anulado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_Instalado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_Activo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fecha_aprobacion_de_meta')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

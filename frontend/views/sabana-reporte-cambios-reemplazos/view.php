<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteCambiosReemplazos */

$this->title = $model->sabana_reporte_cambios_reemplazos_id;
$this->params['breadcrumbs'][] = ['label' => 'Sabana Reporte Cambios Reemplazos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sabana-reporte-cambios-reemplazos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->sabana_reporte_cambios_reemplazos_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->sabana_reporte_cambios_reemplazos_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sabana_reporte_cambios_reemplazos_id',
            'Ejecutor',
            'Documento_Cliente_Acceso_Old',
            'Dane_Mun_ID_Punto_Old',
            'Estado_Actual_Old',
            'Region_Old',
            'Dane_Departamento_Old',
            'Departamento_Old',
            'Dane_Municipio_Old',
            'Municipio_Old',
            'Barrio_Old',
            'Direccion_Old',
            'Estrato_Old',
            'Coordenadas_Grados_decimales_Old',
            'Nombre_Cliente_Completo_Old',
            'Telefono_Old',
            'Celular_Old',
            'Correo_Electronico_Old',
            'VIP_Old',
            'Codigo_Proyecto_VIP_Old',
            'Nombre_Proyecto_VIP_Old',
            'Velocidad_Contratada_MB_Old',
            'Meta_Old',
            'Tipo_Solucion_UM_Operatividad_Old',
            'Operador_Prestante_Old',
            'IP_fibra_optica_Old',
            'Olt_fibra_optica_Old',
            'PuertoOlt_fibra_optica_Old',
            'Mac_Onu_fibra_optica_Old',
            'Port_Onu_fibra_optica_Old',
            'Nodo_red_cobre_Old',
            'Armario_red_cobre_Old',
            'Red_Primaria_red_cobre_Old',
            'Red_Secundaria_red_cobre_Old',
            'Nodo_red_hfc_Old',
            'Amplificador_red_hfc_Old',
            'Tap_Boca_red_hfc_Old',
            'Mac_Cpe_Old',
            'Documento_Cliente_Acceso_New',
            'Region_New',
            'Dane_Departamento_New',
            'Departamento_New',
            'Dane_Municipio_New',
            'Municipio_New',
            'Barrio_New',
            'Direccion_New',
            'Estrato_New',
            'Coordenadas_Grados_decimales_New',
            'Nombre_Cliente_Completo_New',
            'Telefono_New',
            'Celular_New',
            'Correo_Electronico_New',
            'VIP_New',
            'Codigo_Proyecto_VIP_New',
            'Nombre_Proyecto_VIP_New',
            'Velocidad_Contratada_MB_New',
            'Meta_New',
            'Tipo_Solucion_UM_Operatividad_New',
            'Operador_Prestante_New',
            'IP_fibra_optica_New',
            'Olt_fibra_optica_New',
            'PuertoOlt_fibra_optica_New',
            'Mac_Onu_fibra_optica_New',
            'Port_Onu_fibra_optica_New',
            'Nodo_red_cobre_New',
            'Armario_red_cobre_New',
            'Red_Primaria_red_cobre_New',
            'Red_Secundaria_red_cobre_New',
            'Nodo_red_hfc_New',
            'Amplificador_red_hfc_New',
            'Tap_Boca_red_hfc_New',
            'Mac_Cpe_New',
        ],
    ]) ?>

</div>

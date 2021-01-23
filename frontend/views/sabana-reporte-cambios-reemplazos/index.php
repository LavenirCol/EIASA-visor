<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SabanaReporteCambiosReemplazosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sabana Reporte Cambios Reemplazos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sabana-reporte-cambios-reemplazos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sabana Reporte Cambios Reemplazos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sabana_reporte_cambios_reemplazos_id',
            'Ejecutor',
            'Documento_Cliente_Acceso_Old',
            'Dane_Mun_ID_Punto_Old',
            'Estado_Actual_Old',
            //'Region_Old',
            //'Dane_Departamento_Old',
            //'Departamento_Old',
            //'Dane_Municipio_Old',
            //'Municipio_Old',
            //'Barrio_Old',
            //'Direccion_Old',
            //'Estrato_Old',
            //'Coordenadas_Grados_decimales_Old',
            //'Nombre_Cliente_Completo_Old',
            //'Telefono_Old',
            //'Celular_Old',
            //'Correo_Electronico_Old',
            //'VIP_Old',
            //'Codigo_Proyecto_VIP_Old',
            //'Nombre_Proyecto_VIP_Old',
            //'Velocidad_Contratada_MB_Old',
            //'Meta_Old',
            //'Tipo_Solucion_UM_Operatividad_Old',
            //'Operador_Prestante_Old',
            //'IP_fibra_optica_Old',
            //'Olt_fibra_optica_Old',
            //'PuertoOlt_fibra_optica_Old',
            //'Mac_Onu_fibra_optica_Old',
            //'Port_Onu_fibra_optica_Old',
            //'Nodo_red_cobre_Old',
            //'Armario_red_cobre_Old',
            //'Red_Primaria_red_cobre_Old',
            //'Red_Secundaria_red_cobre_Old',
            //'Nodo_red_hfc_Old',
            //'Amplificador_red_hfc_Old',
            //'Tap_Boca_red_hfc_Old',
            //'Mac_Cpe_Old',
            //'Documento_Cliente_Acceso_New',
            //'Region_New',
            //'Dane_Departamento_New',
            //'Departamento_New',
            //'Dane_Municipio_New',
            //'Municipio_New',
            //'Barrio_New',
            //'Direccion_New',
            //'Estrato_New',
            //'Coordenadas_Grados_decimales_New',
            //'Nombre_Cliente_Completo_New',
            //'Telefono_New',
            //'Celular_New',
            //'Correo_Electronico_New',
            //'VIP_New',
            //'Codigo_Proyecto_VIP_New',
            //'Nombre_Proyecto_VIP_New',
            //'Velocidad_Contratada_MB_New',
            //'Meta_New',
            //'Tipo_Solucion_UM_Operatividad_New',
            //'Operador_Prestante_New',
            //'IP_fibra_optica_New',
            //'Olt_fibra_optica_New',
            //'PuertoOlt_fibra_optica_New',
            //'Mac_Onu_fibra_optica_New',
            //'Port_Onu_fibra_optica_New',
            //'Nodo_red_cobre_New',
            //'Armario_red_cobre_New',
            //'Red_Primaria_red_cobre_New',
            //'Red_Secundaria_red_cobre_New',
            //'Nodo_red_hfc_New',
            //'Amplificador_red_hfc_New',
            //'Tap_Boca_red_hfc_New',
            //'Mac_Cpe_New',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

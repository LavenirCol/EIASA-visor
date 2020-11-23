<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaReporteInstalacion */

$this->title = $model->sabana_reporte_instalacion_id;
$this->params['breadcrumbs'][] = ['label' => 'Sabana Reporte Instalacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sabana-reporte-instalacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->sabana_reporte_instalacion_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->sabana_reporte_instalacion_id], [
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
            'sabana_reporte_instalacion_id',
            'Operador',
            'Documento_cliente_acceso',
            'Dane_Mun_ID_Punto',
            'Estado_actual',
            'Region',
            'Dane_Departamento',
            'Departamento',
            'Dane_Municipio',
            'Municipio',
            'Barrio',
            'Direccion',
            'Estrato',
            'Dificultad__de_acceso_al_municipio',
            'Coordenadas_Grados_decimales',
            'Nombre_Cliente',
            'Telefono',
            'Celular',
            'Correo_Electronico',
            'VIP',
            'Codigo_Proyecto_VIP',
            'Nombre_Proyecto_VIP',
            'Velocidad_Contratada_Downstream',
            'Meta',
            'Fecha_max_de_cumplimiento_de_meta',
            'Dias_pendientes_de_la_fecha_de_cumplimiento',
            'FECHA_APROBACION_INTERVENTORIA',
            'FECHA_APROBACION_META_SUPERVISION',
            'Tipo_Solucion_UM_Operatividad',
            'Operador_Prestante',
            'IP',
            'Olt',
            'PuertoOlt',
            'Serial_ONT',
            'Port_ONT',
            'Nodo',
            'Armario',
            'Red_Primaria',
            'Red_Secundaria',
            'Nodo2',
            'Amplificador',
            'Tap_Boca',
            'Mac_Cpe',
            'Fecha_Asignado_o_Presupuestado',
            'Fecha_En_proceso_de_Instalacion',
            'Fecha_Anulado',
            'Fecha_Instalado',
            'Fecha_Activo',
            'Fecha_aprobacion_de_meta',
            'Sexo',
            'Genero',
            'Orientacion_Sexual',
            'Educacion',
            'Etnias',
            'Discapacidad',
            'Estratos',
            'Beneficiario_Ley_1699_de_2013',
            'SISBEN_IV',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaAccesosInstalacion */

$this->title = $model->sabana_accesos_instalacion_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sabana Accesos Instalacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sabana-accesos-instalacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->sabana_accesos_instalacion_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->sabana_accesos_instalacion_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sabana_accesos_instalacion_id',
            'Operador',
            'Documento_Cliente_Acceso',
            'Dane_Mun_ID_Punto',
            'Estado_Actual',
            'Region',
            'Dane_Departamento',
            'Departamento',
            'Dane_Municipio',
            'Municipio',
            'Barrio',
            'Dirección',
            'Estrato',
            'Dificultad_de_acceso_al_municipio',
            'Coordenadas_Grados-decimales',
            'Nombre_Cliente_Completo',
            'Telefono',
            'Celular',
            'Correo_Electronico',
            'VIP',
            'Codigo_Proyecto_VIP',
            'Nombre_Proyecto_VIP',
            'Velocidad_Contratada_MB',
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
            'Nodo_Cobre',
            'Armario',
            'Red_Primaria',
            'Red_Secundaria',
            'Nodo_HFC',
            'Amplificador',
            'Tap-Boca',
            'Mac_Cpe',
            'Fecha_Asignado_o_Presupuestado',
            'Fecha_En_proceso_de_Instalacion',
            'Fecha_Anulado',
            'Fecha_Instalado',
            'Fecha_Activo',
            'Fecha_aprobacion_de_meta',
        ],
    ]) ?>

</div>

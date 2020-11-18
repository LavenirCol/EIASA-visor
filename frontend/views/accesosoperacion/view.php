<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaAccesosOperacion */

$this->title = $model->sabana_accesos_operacion_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sabana Accesos Operacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sabana-accesos-operacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->sabana_accesos_operacion_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->sabana_accesos_operacion_id], [
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
            'sabana_accesos_operacion_id',
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
            'Fecha_de_cumplimiento_de_meta',
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
            'Fecha_Instalado',
            'Fecha_Activo',
            'Fecha_inicio_operacion',
            'Fecha_Solicitud_Traslado_PQR',
            'Fecha_Inactivo',
            'Fecha_Desinstalado',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AvancesMetaOperacion */

$this->title = $model->avances_meta_operacion_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Avances Meta Operacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="avances-meta-operacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->avances_meta_operacion_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->avances_meta_operacion_id], [
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
            'avances_meta_operacion_id',
            'DANE',
            'Departamento',
            'Municipio',
            'Meta',
            'Beneficiarios_En_Operacion',
            'Meta_Tiempo_en_servicio',
            'Tiempo_en_servicio',
            'Avance',
        ],
    ]) ?>

</div>

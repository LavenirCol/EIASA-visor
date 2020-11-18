<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaAccesosOperacion */

$this->title = Yii::t('app', 'Create Sabana Accesos Operacion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sabana Accesos Operacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sabana-accesos-operacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

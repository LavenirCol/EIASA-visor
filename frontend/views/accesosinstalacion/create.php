<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SabanaAccesosInstalacion */

$this->title = Yii::t('app', 'Create Sabana Accesos Instalacion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sabana Accesos Instalacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sabana-accesos-instalacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

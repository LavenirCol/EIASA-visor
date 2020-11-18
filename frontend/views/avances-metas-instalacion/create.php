<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AvancesMetasInstalacion */

$this->title = Yii::t('app', 'Create Avances Metas Instalacion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Avances Metas Instalacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="avances-metas-instalacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

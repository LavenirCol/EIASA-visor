<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = Yii::t('app', 'Update Module: {name}', [
    'name' => $model->idmodule,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Modules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idmodule, 'url' => ['view', 'id' => $model->idmodule]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="module-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

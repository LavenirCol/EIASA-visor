<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Privileges */

$this->title = Yii::t('app', 'Update Privileges: {name}', [
    'name' => $model->idProfile,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Privileges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idProfile, 'url' => ['view', 'idProfile' => $model->idProfile, 'idOption' => $model->idOption]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="privileges-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

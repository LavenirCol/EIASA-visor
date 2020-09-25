<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Folder */

$this->title = Yii::t('app', 'Update Folder: {name}', [
    'name' => $model->idfolder,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Folders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idfolder, 'url' => ['view', 'id' => $model->idfolder]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="folder-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

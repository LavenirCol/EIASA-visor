<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProfilesFolder */

$this->title = Yii::t('app', 'Update Profiles Folder: {name}', [
    'name' => $model->idprofilefolder,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profiles Folders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idprofilefolder, 'url' => ['view', 'id' => $model->idprofilefolder]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="profiles-folder-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

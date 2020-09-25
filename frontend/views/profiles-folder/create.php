<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProfilesFolder */

$this->title = Yii::t('app', 'Create Profiles Folder');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profiles Folders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profiles-folder-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

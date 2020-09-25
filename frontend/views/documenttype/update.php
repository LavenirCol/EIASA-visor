<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Documenttype */

$this->title = Yii::t('app', 'Update Documenttype: {name}', [
    'name' => $model->iddocumentType,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documenttypes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->iddocumentType, 'url' => ['view', 'id' => $model->iddocumentType]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="documenttype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

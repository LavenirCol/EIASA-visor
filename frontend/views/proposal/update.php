<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Proposal */

$this->title = Yii::t('app', 'Update Proposal: {name}', [
    'name' => $model->idProposal,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Proposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idProposal, 'url' => ['view', 'id' => $model->idProposal]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="proposal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

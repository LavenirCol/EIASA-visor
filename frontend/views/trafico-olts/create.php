<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TraficoOlts */

$this->title = Yii::t('app', 'Create Trafico Olts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trafico Olts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trafico-olts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

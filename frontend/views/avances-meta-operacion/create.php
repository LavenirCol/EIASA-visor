<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AvancesMetaOperacion */

$this->title = Yii::t('app', 'Create Avances Meta Operacion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Avances Meta Operacions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="avances-meta-operacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

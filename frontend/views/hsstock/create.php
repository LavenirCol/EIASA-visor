<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Hsstock */

$this->title = Yii::t('app', 'Create Hsstock');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hsstocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hsstock-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

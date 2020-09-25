<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Profiles */

$this->title = Yii::t('app', 'Create Profiles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profiles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

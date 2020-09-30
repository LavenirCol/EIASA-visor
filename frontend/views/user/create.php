<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content  bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Usuarios</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->
<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <div class="row">
            <div class="col-md-12">
                <div class="user-create">

                    <h1><?= Html::encode($this->title) ?></h1>

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>

    </div>
</div>
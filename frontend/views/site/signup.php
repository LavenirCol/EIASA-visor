<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//
//$this->title = Yii::t('app', 'Users');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content mt-5 bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Crear Usuario</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->
<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="row">
            <div class="col-md-12">
                <div class="user-index">
                    <p>Diligencie los siguientes campos para crear el usuario:</p>

                    <div class="row">
                        <div class="col-lg-5">
                            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'autocomplete' => 'off'])->label('Nombre de Usuario')  ?>

                                <?= $form->field($model, 'email')->label('Email')  ?>

                                <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off'])->label('Contraseña')  ?>

                            <div class="form-group">
                                <label class="label-form">Perfil</label>
                                <select class="form-control required" id="idProfile" name="idProfile">
                                    <option value="1">Administrador</option>
                                    <option value="2" selected>Operaciones</option>
                                    <option value="3">Consulta</option>
                                </select>
                                
                            </div>
                                <div class="form-group">
                                    <?= Html::submitButton('Registrar', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                                </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
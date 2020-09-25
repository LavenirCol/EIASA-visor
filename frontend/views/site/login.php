<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="content content-fixed content-auth">
      <div class="container">
        <div class="media align-items-stretch justify-content-center ht-100p pos-relative">
          <div class="media-body align-items-center d-none d-lg-flex">
            <div class="mx-wd-600">
              <img src="https://image.freepik.com/free-photo/facebook-phone-laptop_23-2147651285.jpg" class="img-fluid" alt="">
            </div>
          </div><!-- media-body -->
          <div class="sign-wrapper mg-lg-l-50 mg-xl-l-60">
            <div class="wd-100p">
              <h3 class="tx-color-01 mg-b-5"><?= Html::encode($this->title) ?></h3>
              <p class="tx-color-03 tx-16 mg-b-40">Por favor ingrese para continuar.</p>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true])
                        ->input('text',['placeholder' => 'nombre usuario'])
                        ->label('Usuario')?>

                    <?= $form->field($model, 'password')->passwordInput()->input('password', ['placeholder' => 'contraseña'])->label('Contraseña') ?>

                    <?= $form->field($model, 'rememberMe')->checkbox()->label('Recordarme') ?>

                    <div style="color:#999;margin:1em 0">
                        Olvidó su contraseña? pueder reiniciarla haciendo <?= Html::a('clic aquí', ['site/request-password-reset']) ?>.
                        <br>
                        Necesita reenviar email de verificación? <?= Html::a('reenviar', ['site/resend-verification-email']) ?>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Ingresar', ['class' => 'btn btn-brand-02 btn-block', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
          </div><!-- sign-wrapper -->
        </div><!-- media -->
      </div><!-- container -->
    </div><!-- content -->

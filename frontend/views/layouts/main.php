<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        .navbar-menu .nav-link svg{
            display: flex !important;
        }
        
        .filemgr-content-body > div:first-child{
            max-width: 100% !Important;
        }
    </style>
    <script>
        var baseurl = '<?= Url::base(true);  ?>'; 
    </script>
</head>
<body class="app-filemgr">
<?php $this->beginBody() ?>
<header class="navbar navbar-header navbar-header-fixed">
    <a href="" id="mainMenuOpen" class="burger-menu d-none"><i data-feather="menu"></i></a>
    <a href="" id="filemgrMenu" class="burger-menu d-lg-none"><i data-feather="arrow-left"></i></a>
    <div class="navbar-brand">
        <a href="<?php echo Url::toRoute('site/index'); ?>" class="df-logo"><img width="70px" src="https://www.eiasa.com.co/wp-content/uploads/2020/06/Logotipo_eia.png"/></a>
    </div><!-- navbar-brand -->
    <div id="navbarMenu" class="navbar-menu-wrapper">
      <div class="navbar-menu-header">
        <a href="<?php echo Url::toRoute('site/index'); ?>" class="df-logo"><img width="70px" src="https://www.eiasa.com.co/wp-content/uploads/2020/06/Logotipo_eia.png"/></a>
        <a id="mainMenuClose" href=""><i data-feather="x"></i></a>
      </div><!-- navbar-menu-header -->
      <ul class="nav navbar-menu">
        <?php
          if (! Yii::$app->user->isGuest) {
        ?>
        <li class="nav-item"><a href="<?php echo Url::toRoute('site/index'); ?>" class="nav-link"><i data-feather="home"></i> Inicio</a></li>
        <li class="nav-item"><a href="<?php echo Url::toRoute('reports/index'); ?>" class="nav-link"><i data-feather="box"></i> Reportes</a></li>
        <li class="nav-item"><a href="<?php echo Url::toRoute('visor/filemanager'); ?>" class="nav-link"><i data-feather="file-text"></i> File Manager</a></li>
            <?php if(Yii::$app->user->identity->attributes['idProfile']  == 1){ ?>
            <li class="nav-item"><a href="<?php echo Url::toRoute('user/index'); ?>" class="nav-link"><i data-feather="users"></i> Usuarios</a></li>
            <?php } ?>
        <?php
          }
        ?>
      </ul>
    </div><!-- navbar-menu-wrapper -->
    <div class="navbar-right">
        <?php
          if (Yii::$app->user->isGuest) {
        ?>
        <ul class="nav navbar-menu">
          <li class="nav-item"><a href="../../collections/" class="nav-link"><i data-feather="users"></i> Ingresar</a></li>
        </ul>
        <?php
          } else{
        ?>
          <div class="dropdown dropdown-profile">
          <a href="" class="dropdown-link" data-toggle="dropdown" data-display="static">
              <div class="avatar avatar-sm"><img src="<?= Url::base(true);  ?>/img/avatardefault_92824.png" class="rounded-circle" alt=""></div>
          </a><!-- dropdown-link -->
          <div class="dropdown-menu dropdown-menu-right tx-13">
            <div class="avatar avatar-lg mg-b-15"><img src="<?= Url::base(true);  ?>/img/avatardefault_92824.png" class="rounded-circle" alt=""></div>
            <h6 class="tx-semibold mg-b-5"><?php echo Yii::$app->user->identity->username; ?></h6>
            <p class="mg-b-25 tx-12 tx-color-03">Rol: Administrador</p>

            <a href="" class="dropdown-item"><i data-feather="edit-3"></i> Editar Usuario</a>
            <div class="dropdown-divider"></div>
             <?php echo Html::beginForm(['/site/logout'], 'post') ?>
                <button type="submit" class="dropdown-item"><i data-feather="log-out"></i>Salir</button>                
             <?php echo Html::endForm() ?>
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
        <?php
          } 
        ?>

    </div><!-- navbar-right -->
</header><!-- navbar -->

<!--<div class="content-body pd-0">    -->
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>
<!--</div>-->

<footer class="footer fixed-bottom">
    <div class="container">
        <p class="pull-left">&copy; EIASA Visor <?= date('Y') ?></p>
<!--        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>-->
<!--        <p class="pull-right"><?= Yii::powered() ?></p>-->
    </div>
</footer>

<?php $this->endBody() ?>
<script>

    $(document).ajaxStart(function () {
        $("#loadingindicator").show();
    });
    $(document).ajaxStop(function () {
        setTimeout(function(){            
            $("#loadingindicator").hide();
        },500);
    });

</script>
</body>
</html>
<?php $this->endPage() ?>

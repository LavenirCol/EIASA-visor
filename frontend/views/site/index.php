<?php
use \yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'Sistema de Información Visor Claro';
?>
<div class="content content-fixed bd-b pb-0">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item active" aria-current="page">Inicio</li>
          </ol>
        </nav>
        <h4 class="mg-b-0"><?php echo $this->title ?></h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->
<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="row">
        <div class="col-lg-12">
          <div class="row row-xs mg-b-25">
            <div class="col-sm-6 col-md-4 mg-t-10 mg-sm-t-0">
              <div class="card card-profile">
                <img src="<?= Url::base(true);  ?>/img/img4.jpg" class="card-img-top" alt="">
                <div class="card-body tx-13">
                  <div>
                    <a href="">
                      <div class="avatar avatar-lg"><span class="avatar-initial rounded-circle bg-dark">R</span></div>
                    </a>
                    <h5><a href="">Reportes</a></h5>
                    <p class="text-center">Consulta de información <br>en tablas para descargar</p>

                    <div class="img-group img-group-sm mg-b-5">
                        <i data-feather="box"></i> 
                    </div>
                    <div class="mg-b-25"><span class="tx-12 tx-color-03">Inventarios, Operación e Instalación</span></div>

                    <a href="<?php echo Url::toRoute('reports/index'); ?>"  class="btn btn-block btn-primary">Reportes</a>
                  </div>
                </div>
              </div><!-- card -->
            </div><!-- col -->
            <div class="col-sm-6 col-md-4 mg-t-10 mg-md-t-0">
              <div class="card card-profile">
                <img src="<?= Url::base(true);  ?>/img/img3.jpg" class="card-img-top" alt="">
                <div class="card-body tx-13">
                  <div>
                    <a href="">
                      <div class="avatar avatar-lg"><span class="avatar-initial rounded-circle bg-dark">FM</span></div>
                    </a>
                    <h5><a href="">File Manager</a></h5>
                    <p class="text-center">Gestión de documentos <br>relacionados al proyecto</p>

                    <div class="img-group img-group-sm mg-b-5">
                      <i data-feather="file-text"></i>
                    </div>
                    <div class="mg-b-25"><span class="tx-12 tx-color-03">Clientes, Contratos, Documentos</span></div>

                    <a href="<?php echo Url::toRoute('visor/index'); ?>" class="btn btn-block btn-primary">File Manager</a>
                  </div>
                </div>
              </div><!-- card -->
            </div><!-- col -->

          </div><!-- row -->
        </div><!-- col -->

      </div><!-- row -->
    </div><!-- container -->
</div>

<?php
/* @var $this yii\web\View */
use \yii\helpers\Url;
?>
<div class="content content-fixed bd-b pb-0">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item active" aria-current="page">File Manager</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">File Manager</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->

<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="row">
          <div class="col-lg-4">
            <div class="card-deck">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Gestión Documental</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Administración de archivos</h6>
                    <p class="card-text">En este módulo puede gestionar las carpteas y documentos relacionados con el proyecto.</p>
                    <a href="<?php echo Url::toRoute('visor/filemanager'); ?>" class="card-link">Consultar</a>
                  </div>
                </div>
                              
            </div>
          </div>
      </div>
    </div>
</div>
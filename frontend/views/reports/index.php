<?php
/* @var $this yii\web\View */
use \yii\helpers\Url;
?>
<div class="content content-fixed bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item active" aria-current="page">Reportes</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Reportes</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->

<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="row">
          <div class="col-lg-12">
            <div class="card-deck">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Módulo de Inventarios</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Tabla Inventarios</h6>
                    <p class="card-text">En este módulo se consulta el inventario de materiales del proyecto.</p>
                    <a href="<?php echo Url::toRoute('reports/inventarios'); ?>" class="card-link">Consultar</a>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Módulo de Instalación</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Formato módulo instalación</h6>
                    <p class="card-text">En este módulo se visualiza la información cargada en el formato de instalación.</p>
                    <a href="<?php echo Url::toRoute('reports/instalacion'); ?>" class="card-link">Consultar</a>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Módulo de Operación</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Formato módulo operación</h6>
                    <p class="card-text">En este módulo se visualiza la información cargada en el formato de operación</p>
                    <a href="<?php echo Url::toRoute('reports/operacion'); ?>" class="card-link">Consultar</a>
                  </div>
                </div>    
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Módulo PQR's y Mantenimiento</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Tickets asociados</h6>
                    <p class="card-text">En este módulo se visualiza la información de los tickets asociados a clientes</p>
                    <a href="<?php echo Url::toRoute('reports/pqrs'); ?>" class="card-link">Consultar</a>
                  </div>
                </div>                  
            </div>
          </div>
      </div>
    </div>
</div>
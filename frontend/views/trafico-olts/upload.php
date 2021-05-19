<?php
/* @var $this yii\web\View */

use \yii\web\View;
use \yii\helpers\Url;
?>
<div class="content content-fixed bd-b pb-3">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page">Inicio</li>
                        <li class="breadcrumb-item">Reportes</li>
                        <li class="breadcrumb-item active" aria-current="page">Actualizar Ancho de Banda de OLT's</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0">Actualizar Ancho de Banda de OLT's</h4>
            </div>
        </div>
    </div><!-- container -->
</div><!-- content -->

<div class="content">
    <?php if($data == 'notok'){ ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Ocurrió un error procesando el archivo!</strong>
      <p><?php echo $error; ?></p>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <?php } ?>    
    <?php if($data == 'ok'){ ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Archivo <?php echo $error; ?> Procesado Correctamente !</strong>      
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <?php } ?>    
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="row">
            <div class="col-lg-4">
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <form id="formupload" action="<?php echo Url::toRoute('trafico-olts/upload'); ?>" method="POST" enctype="multipart/form-data">
                            <h5 class="card-title">1. Seleccione el archivo Excel para Actualizar el Ancho Asignado a OLT's</h5>
                            <p class="card-text">El archivo de Excel debe ser el formato de instalación predefinido.</p>
                            <input type="file" name="file" id="file" />
                            <input type="hidden" name="filetype" id="filetype" value ="1" />
                            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                            <p>&nbsp;</p>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if (Yii::$app->user->identity->attributes['idProfile'] < 3) { ?>
                                        <button type="submit" class="btn btn-primary btn-sm pull-right">Actualizar Ancho de Banda</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>            
                </div>
            </div>         
            <div class="col-lg-4">
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ejemplo de archivo Excel para Actualizar el Ancho de Banda de OLT's</h5>
                            <p class="card-text">Puede descargar el formato a utilizar en los cargues de información desde los siguienets links.</p>
                            <ul>
                                <li>
                                    <a href="<?= Url::base(true);  ?>/files/Cargue BW.xlsx" class="">Archivo Excel Ancho de Banda OLT's</a>
                                </li>
                            </ul>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </div>
</div>
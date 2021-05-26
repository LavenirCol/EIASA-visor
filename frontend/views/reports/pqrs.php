<?php
 use yii\web\View;
?>
<style>  
.card-file-thumb{
        background-size: cover; 
        background-repeat: no-repeat;
    }
.fancybox-slide--iframe .fancybox-content {
        width  :  80%;
        height :  80%;
        max-width  : 80%;
        max-height : 80%;
        margin: 0;
    }
</style>  
<div class="content content-fixed bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item" aria-current="page">Reportes</li>
            <li class="breadcrumb-item active" aria-current="page">Módulo PQR's y Mantenimiento</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Módulo PQR's y Mantenimiento</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->

<div class="content">
    <form method="post">
        <div class="container bd bd-2 p-3 rounded-10">
            <div class="row">
                <div class="col-lg-2">
                    <label class="label-form" label-for="deptos">Departamento</label>
                    <select class="custom-select" id="dptos" name="deptos">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($deptos as $depto) { ?>
                        <option value="<?php echo $depto['state']; ?>"><?php echo $depto['state']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="label-form" label-for="mpios">Municipio</label>
                    <select class="custom-select" id="mpios" name="mpios">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($mpios as $mpio) { ?>
                            <option value="<?php echo $mpio['town']; ?>"><?php echo $mpio['town']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="label-form" label-for="daneCodeFilter">Código DANE</label>
                    <select class="custom-select" id="daneCodeFilter" name="daneCodeFilter">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($daneCodeList as $daneCodeItem) { ?>
                            <option value="<?php echo $daneCodeItem['daneCode']; ?>"><?php echo $daneCodeItem['daneCode']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">

                </div>
                <div class="col-lg-2">

                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-primary btn-sm" id="btnsearch" style="width:100%">Buscar</button><br>
                    <button type="clear" class="btn btn-secondary btn-sm mt-2" style="width:100%">Borrar</button>
                </div>
            </div>  
        </div>
        <div>
            <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
        </div>
    </form>        
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="row">
      <p>&nbsp;</p>
          <div class="col-lg-12 mb-5">
            <table class="table table-condensed table-striped responsive" style="width:100%" id="dataTablePqrs">
                <thead>
                    <tr>
                        <th data-priority="1" width="10%">Departamento</th>
                        <th data-priority="2" width="10%">Municipio</th>
                        <th data-priority="2" width="10%">Código DANE</th>
                        <th data-priority="3" width="10%">Código Acceso</th>
                        <th data-priority="4" width="15%">Cliente</th>
                        <th data-priority="5" width="10%">Ref Ticket</th>
                        <th data-priority="6" width="10%">Grupo</th>
                        <th data-priority="6" width="10%">Tipo</th>
                        <th data-priority="6" width="10%">Prioridad</th>
                        <th data-priority="7" width="15%">Asunto</th>
                        <th data-priority="5" width="10%">Fecha Creación</th>
                        <th data-priority="5" width="10%">Fecha Limite</th>
                        <th data-priority="5" width="10%">Fecha Cierre</th>
                        <th data-priority="5" width="10%">Origen de Reporte</th>
                        <th data-priority="5" width="10%">Cédula</th>
                        <th data-priority="5" width="10%">Teléfonos</th>
                        <th data-priority="5" width="10%">Email</th>
                        <th data-priority="5" width="10%">Dirección / Barrio</th>
                        <th data-priority="5" width="10%">Coordenadas</th>
                        <th data-priority="5" width="10%">Detalle</th>
                        <th data-priority="5" width="10%">Historial</th>
                        <th data-priority="5" width="10%">Autor</th>
                        <th data-priority="5" width="10%">Estado</th>
                        <th data-priority="5" width="10%"></th>
                        <th data-priority="5" width="10%">Archivos Adjuntos</th>
                    </tr>
                </thead>
                <tbody>                                    
                </tbody>
            </table>
          </div>
      </div>
    </div>
</div>
<style>
    .swal2-content{
        text-align: justify !important;
    }
</style>
<?php 
    $this->registerJs(
        "
        function showdetail(obj){
           var msg = $(obj).data('detail');
            Swal.fire({
              title: '',
              icon: 'info',
              html: msg,
              showCloseButton: false,
              focusConfirm: false
            });
        }        
        ",
        View::POS_HEAD,
        '.btndetail'
    );
?>
    
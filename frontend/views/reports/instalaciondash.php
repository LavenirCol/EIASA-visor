<?php
/* @var $this yii\web\View */

use \yii\helpers\Url;
?>
<script>
    var title = 'Dashboard Instalación';
</script>
<div class="content content-fixed bd-b pb-3">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page">Inicio</li>
                        <li class="breadcrumb-item" aria-current="page">Reportes</li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard Instalación</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0">Dashboard Instalación</h4>
                <br>
                <nav>
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li>
                            <a href="#" onclick="window.open('<?php echo Url::toRoute('reports/instalaciondetailsserver'); ?>?export=csv');" class="btn btn-sm btn-primary">Descargar Todo</a> 
                        </li>
                        <li style="width:100px;"></li>
                        <li>        
                            <select class="custom-select" id="dptos" name="dptos">
                                <option value="-1">--Seleccione--</option>
                                <?php foreach ($deptos as $depto) { ?>
                                    <option value="<?php echo $depto['Departamento']; ?>"><?php echo $depto['Departamento']; ?></option>
                                <?php } ?>
                            </select>
                        </li>
                        <li>
                            <a href="#" onclick="$('#dptos').val() == '-1'?'':window.open('<?php echo Url::toRoute('reports/instalaciondetailsserver'); ?>?export=csv&dptos=' + $('#dptos').val());" class="btn btn-sm btn-primary">Descargar Departamento</a>        
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- container -->
</div><!-- content -->

<div class="content">
    <form method="post">
        <div class="container bd bd-2 p-3 rounded-10">
            <div class="row">                
                <div class="col-lg-2">
                    <label class="label-form" label-for="deptos">Código DANE</label>
                    <select class="custom-select" id="daneCodeFilter" name="daneCodeFilter">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($daneCodeList as $daneCodeItem){ ?>
                            <option value="<?php echo $daneCodeItem['daneCode']; ?>"><?php echo $daneCodeItem['daneCode']; ?></option>
                        <?php } ?>
                    </select>
                </div>                
                <div class="col-lg-2"></div>
                <div class="col-lg-2"></div>
                <div class="col-lg-2"></div>
                <div class="col-lg-2"></div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-primary btn-sm" id="btnsearch" style="width:100%">Buscar</button><br>
                    <button type="clear" class="btn btn-secondary btn-sm mt-2" style="width:100%">Borrar</button>
                </div>
            </div>  
        </div>
    </form> 
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="row">
            <div class="col-lg-12 mb-5">
                <p>&nbsp;</p>
                <h4 class="mg-b-0"></h4>
                <table class="table table-condensed table-striped" id="dataTableInstalaciondash" style="width:100%">                
                    <thead>
                        <tr>
                            <th>DANE</th>
                            <th>Departamento</th>
                            <th>Municipio</th>
                            <th class="dt-body-right">Meta</th>
                            <th class="dt-body-right">Beneficiarios Instalados</th>
                            <th class="dt-body-right">Avance</th>
                            <th></th>                     
                        </tr>
                    </thead>                    
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
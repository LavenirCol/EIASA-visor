<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
?>
<script>
    var title = 'Cambios y Reemplazos';
</script>
<div class="content content-fixed bd-b pb-3">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page">Inicio</li>
                        <li class="breadcrumb-item" aria-current="page">Reportes</li>
                        <li class="breadcrumb-item active" aria-current="page">Cambios y Reemplazos</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0">Cambios y Reemplazos</h4>
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
                        <option value="-1" selected="selected">--Seleccione--</option>
                        <?php foreach ($deptos as $depto) { ?>
                        <option value="<?php echo $depto['city']; ?>"><?php echo $depto['city']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="label-form" label-for="mpios">Municipio</label>
                    <select class="custom-select" id="mpios" name="mpios">
                        <option value="-1" selected="selected">--Seleccione--</option>
                        <?php foreach ($mpios as $mpio) { ?>
                            <option value="<?php echo $mpio['district']; ?>"><?php echo $mpio['district']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">

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
    </form>    
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="row">
            <div class="col-lg-12 mb-5">
                <p>&nbsp;</p>
                <h4 class="mg-b-0"></h4>
                <p>&nbsp;</p>
                <table class="table table-condensed table-striped" id="dataTableCambiosReemplazos" style="width:100%">
                    <thead>
                        <tr>
                            <th>Ejecutor</th>
                            <th>Documento Cliente Acceso</th>
                            <th>Dane Mun - ID Punto</th>
                            <th>Estado Actual</th>
                            <th>Region</th>
                            <th>Dane Departamento</th>
                            <th>Departamento</th>
                            <th>Dane Municipio</th>
                            <th>Municipio</th>
                            <th>Barrio</th>
                            <th>Dirección</th>
                            <th>Estrato</th>
                            <th>Coordenadas Grados-decimales</th>
                            <th>Nombre Cliente Completo</th>
                            <th>Telefono</th>
                            <th>Celular</th>
                            <th>Correo Electronico</th>
                            <th>VIP (Si o No)</th>
                            <th>Codigo Proyecto VIP</th>
                            <th>Nombre Proyecto VIP</th>
                            <th>Velocidad Contratada MB</th>
                            <th>Meta</th>
                            <th>Tipo Solucion UM Operatividad</th>
                            <th>Operador Prestante</th>
                            <th>IP</th>
                            <th>Olt</th>
                            <th>PuertoOlt</th>
                            <th>Mac Onu</th>
                            <th>Port Onu</th>
                            <th>Nodo</th>
                            <th>Armario</th>
                            <th>Red Primaria</th>
                            <th>Red Secundaria</th>
                            <th>Nodo</th>
                            <th>Amplificador</th>
                            <th>Tap-Boca</th>
                            <th>Mac Cpe</th>
                            <th>Documento Cliente Acceso</th>
                            <th>Region</th>
                            <th>Dane Departamento</th>
                            <th>Departamento</th>
                            <th>Dane Municipio</th>
                            <th>Municipio</th>
                            <th>Barrio</th>
                            <th>Dirección</th>
                            <th>Estrato</th>
                            <th>Coordenadas Grados-decimales</th>
                            <th>Nombre Cliente Completo</th>
                            <th>Telefono</th>
                            <th>Celular</th>
                            <th>Correo Electronico</th>
                            <th>VIP (Si o No)</th>
                            <th>Codigo Proyecto VIP</th>
                            <th>Nombre Proyecto VIP</th>
                            <th>Velocidad Contratada MB</th>
                            <th>Meta</th>
                            <th>Tipo Solucion UM Operatividad</th>
                            <th>Operador Prestante</th>
                            <th>IP</th>
                            <th>Olt</th>
                            <th>PuertoOlt</th>
                            <th>Mac Onu</th>
                            <th>Port Onu</th>
                            <th>Nodo</th>
                            <th>Armario</th>
                            <th>Red Primaria</th>
                            <th>Red Secundaria</th>
                            <th>Nodo</th>
                            <th>Amplificador</th>
                            <th>Tap-Boca</th>
                            <th>Mac Cpe</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
?>
<script>
    var title = 'Operación por Municipio';
</script>
<div class="content content-fixed bd-b pb-3">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page">Inicio</li>
                        <li class="breadcrumb-item" aria-current="page">Reportes</li>
                        <li class="breadcrumb-item active" aria-current="page">Operación por Municipio</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0">Operación por Municipio</h4>
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
<!--                        <option value="-1">--Seleccione--</option>-->
                        <?php foreach ($deptos as $depto) { ?>
                        <option value="<?php echo $depto['city']; ?>" selected="selected"><?php echo $depto['city']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="label-form" label-for="mpios">Municipio</label>
                    <select class="custom-select" id="mpios" name="mpios">
<!--                        <option value="-1">--Seleccione--</option>-->
                        <?php foreach ($mpios as $mpio) { ?>
                            <option value="<?php echo $mpio['district']; ?>" selected="selected"><?php echo $mpio['district']; ?></option>
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
        <div>
            <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
        </div>
    </form>    
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="row">
            <div class="col-lg-12 mb-5">
                <p>&nbsp;</p>
                <h4 class="mg-b-0"><?php echo $municipio; ?></h4>
                <p>&nbsp;</p>
                <table class="table table-condensed table-striped" id="dataTableOperaciondetails" style="width:100%">
                    <thead>
                        <tr>
                            <th>Operador</th>
                            <th>Documento_cliente_acceso</th>
                            <th>Dane_Mun_ID_Punto</th>
                            <th>Estado_actual</th>
                            <th>Region</th>
                            <th>Dane_Departamento</th>
                            <th>Departamento</th>
                            <th>Dane_Municipio</th>
                            <th>Municipio</th>
                            <th>Barrio</th>
                            <th>Direccion</th>
                            <th>Estrato</th>
                            <th>Dificultad__de_acceso_al_municipio</th>
                            <th>Coordenadas_Grados_decimales</th>
                            <th>Nombre_Cliente</th>
                            <th>Telefono</th>
                            <th>Celular</th>
                            <th>Correo_Electronico</th>
                            <th>VIP</th>
                            <th>Codigo_Proyecto_VIP</th>
                            <th>Nombre_Proyecto_VIP</th>
                            <th>Velocidad_Contratada_Downstream</th>
                            <th>Meta</th>
                            <th>Fecha_max_de_cumplimiento_de_meta</th>
                            <th>Tipo_Solucion_UM_Operatividad</th>
                            <th>Operador_Prestante</th>
                            <th>IP</th>
                            <th>Olt</th>
                            <th>PuertoOlt</th>
                            <th>Serial_ONT</th>
                            <th>Port_ONT</th>
                            <th>Nodo</th>
                            <th>Armario</th>
                            <th>Red_Primaria</th>
                            <th>Red_Secundaria</th>
                            <th>Nodo2</th>
                            <th>Amplificador</th>
                            <th>Tap_Boca</th>
                            <th>Mac_Cpe</th>
                            <th>Fecha_Instalado</th>
                            <th>Fecha_Activo</th>
                            <th>Fecha_inicio_operación</th>
                            <th>Fecha_Solicitud_Traslado_PQR</th>
                            <th>Semáforo_Solicitud_Traslado_PQR</th>
                            <th>Fecha_Inactivo</th>
                            <th>Semáforo_Fecha_Inactivo</th>
                            <th>Fecha_Desinstalado</th>
                            <th>Sexo</th>
                            <th>Genero</th>
                            <th>Orientacion_Sexual</th>
                            <th>Educacion_</th>
                            <th>Etnias</th>
                            <th>Discapacidad</th>
                            <th>Estratos</th>
                            <th>Beneficiario_Ley_1699_de_2013</th>
                            <th>SISBEN_IV</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
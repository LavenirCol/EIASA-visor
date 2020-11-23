<?php
/* @var $this yii\web\View */
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
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($deptos as $depto) { ?>
                            <option value="<?php echo $depto['city']; ?>"><?php echo $depto['city']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="label-form" label-for="mpios">Municipio</label>
                    <select class="custom-select" id="mpios" name="mpios">
                        <option value="-1">--Seleccione--</option>
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
                <h4 class="mg-b-0"><?php echo $municipio; ?></h4>
                <p>&nbsp;</p>
                <table class="table table-condensed table-striped dataTable" id="dataTableOperaciondetails" style="width:100%">
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
                            <th>Fecha_Inactivo</th>
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
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ((array) $insts as $inst) { ?>
                            <tr>
                                <td><?php echo $inst['Operador'] ?></td>
                                <td><?php echo $inst['Documento_cliente_acceso'] ?></td>
                                <td><?php echo $inst['Dane_Mun_ID_Punto'] ?></td>
                                <td><?php echo $inst['Estado_actual'] ?></td>
                                <td><?php echo $inst['Region'] ?></td>
                                <td><?php echo $inst['Dane_Departamento'] ?></td>
                                <td><?php echo $inst['Departamento'] ?></td>
                                <td><?php echo $inst['Dane_Municipio'] ?></td>
                                <td><?php echo $inst['Municipio'] ?></td>
                                <td><?php echo $inst['Barrio'] ?></td>
                                <td><?php echo $inst['Direccion'] ?></td>
                                <td><?php echo $inst['Estrato'] ?></td>
                                <td><?php echo $inst['Dificultad__de_acceso_al_municipio'] ?></td>
                                <td><?php echo $inst['Coordenadas_Grados_decimales'] ?></td>
                                <td><?php echo $inst['Nombre_Cliente'] ?></td>
                                <td><?php echo $inst['Telefono'] ?></td>
                                <td><?php echo $inst['Celular'] ?></td>
                                <td><?php echo $inst['Correo_Electronico'] ?></td>
                                <td><?php echo $inst['VIP'] ?></td>
                                <td><?php echo $inst['Codigo_Proyecto_VIP'] ?></td>
                                <td><?php echo $inst['Nombre_Proyecto_VIP'] ?></td>
                                <td><?php echo $inst['Velocidad_Contratada_Downstream'] ?></td>
                                <td><?php echo $inst['Meta'] ?></td>
                                <td><?php echo $inst['Fecha_max_de_cumplimiento_de_meta'] ?></td>
                                <td><?php echo $inst['Tipo_Solucion_UM_Operatividad'] ?></td>
                                <td><?php echo $inst['Operador_Prestante'] ?></td>
                                <td><?php echo $inst['IP'] ?></td>
                                <td><?php echo $inst['Olt'] ?></td>
                                <td><?php echo $inst['PuertoOlt'] ?></td>
                                <td><?php echo $inst['Serial_ONT'] ?></td>
                                <td><?php echo $inst['Port_ONT'] ?></td>
                                <td><?php echo $inst['Nodo'] ?></td>
                                <td><?php echo $inst['Armario'] ?></td>
                                <td><?php echo $inst['Red_Primaria'] ?></td>
                                <td><?php echo $inst['Red_Secundaria'] ?></td>
                                <td><?php echo $inst['Nodo2'] ?></td>
                                <td><?php echo $inst['Amplificador'] ?></td>
                                <td><?php echo $inst['Tap_Boca'] ?></td>
                                <td><?php echo $inst['Mac_Cpe'] ?></td>
                                <td><?php echo $inst['Fecha_Instalado'] ?></td>
                                <td><?php echo $inst['Fecha_Activo'] ?></td>
                                <td><?php echo $inst['Fecha_inicio_operación'] ?></td>
                                <td><?php echo $inst['Fecha_Solicitud_Traslado_PQR'] ?></td>
                                <td><?php echo $inst['Fecha_Inactivo'] ?></td>
                                <td><?php echo $inst['Fecha_Desinstalado'] ?></td>
                                <td><?php echo $inst['Sexo'] ?></td>
                                <td><?php echo $inst['Genero'] ?></td>
                                <td><?php echo $inst['Orientacion_Sexual'] ?></td>
                                <td><?php echo $inst['Educacion_'] ?></td>
                                <td><?php echo $inst['Etnias'] ?></td>
                                <td><?php echo $inst['Discapacidad'] ?></td>
                                <td><?php echo $inst['Estratos'] ?></td>
                                <td><?php echo $inst['Beneficiario_Ley_1699_de_2013'] ?></td>
                                <td><?php echo $inst['SISBEN_IV'] ?></td>
                                <td></td>
                            </tr>
                        <?php } ?>      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
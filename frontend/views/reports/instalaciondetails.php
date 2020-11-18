<?php
/* @var $this yii\web\View */
?>

<div class="content content-fixed bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item" aria-current="page">Reportes</li>
            <li class="breadcrumb-item active" aria-current="page">Instalaciones por Municipio</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Instalaciones por Municipio</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->

<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="row">
          <div class="col-lg-12 mb-5">
            <table class="table table-condensed table-striped dataTablec" id="dataTableInstalaciondetails" style="width:100%">
                <thead>
                    <tr>
<!--                        <th>sabana_accesos_instalacion_id</th>-->
                        <th>Operador</th>
                        <th>Documento_Cliente_Acceso</th>
                        <th>Dane_Mun_ID_Punto</th>
                        <th>Estado_Actual</th>
                        <th>Region</th>
                        <th>Dane_Departamento</th>
                        <th>Departamento</th>
                        <th>Dane_Municipio</th>
                        <th>Municipio</th>
                        <th>Barrio</th>
                        <th>Dirección</th>
                        <th>Estrato</th>
                        <th>Dificultad_de_acceso_al_municipio</th>
                        <th>Coordenadas_Grados_decimales</th>
                        <th>Nombre_Cliente_Completo</th>
                        <th>Telefono</th>
                        <th>Celular</th>
                        <th>Correo_Electronico</th>
                        <th>VIP</th>
                        <th>Codigo_Proyecto_VIP</th>
                        <th>Nombre_Proyecto_VIP</th>
                        <th>Velocidad_Contratada_MB</th>
                        <th>Meta</th>
                        <th>Fecha_max_de_cumplimiento_de_meta</th>
                        <th>Dias_pendientes_de_la_fecha_de_cumplimiento</th>
                        <th>FECHA_APROBACION_INTERVENTORIA</th>
                        <th>FECHA_APROBACION_META_SUPERVISION</th>
                        <th>Tipo_Solucion_UM_Operatividad</th>
                        <th>Operador_Prestante</th>
                        <th>IP</th>
                        <th>Olt</th>
                        <th>PuertoOlt</th>
                        <th>Serial_ONT</th>
                        <th>Port_ONT</th>
                        <th>Nodo_Cobre</th>
                        <th>Armario</th>
                        <th>Red_Primaria</th>
                        <th>Red_Secundaria</th>
                        <th>Nodo_HFC</th>
                        <th>Amplificador</th>
                        <th>Tap_Boca</th>
                        <th>Mac_Cpe</th>
                        <th>Fecha_Asignado_o_Presupuestado</th>
                        <th>Fecha_En_proceso_de_Instalacion</th>
                        <th>Fecha_Anulado</th>
                        <th>Fecha_Instalado</th>
                        <th>Fecha_Activo</th>
                        <th>Fecha_aprobacion_de_meta</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach((array)$insts as $inst){ ?>
                    <tr>
<!--                        <td><?php echo $inst['sabana_accesos_instalacion_id'] ?></td>-->
                        <td><?php echo $inst['Operador'] ?></td>
                        <td><?php echo $inst['Documento_Cliente_Acceso'] ?></td>
                        <td><?php echo $inst['Dane_Mun_ID_Punto'] ?></td>
                        <td><?php echo $inst['Estado_Actual'] ?></td>
                        <td><?php echo $inst['Region'] ?></td>
                        <td><?php echo $inst['Dane_Departamento'] ?></td>
                        <td><?php echo $inst['Departamento'] ?></td>
                        <td><?php echo $inst['Dane_Municipio'] ?></td>
                        <td><?php echo $inst['Municipio'] ?></td>
                        <td><?php echo $inst['Barrio'] ?></td>
                        <td><?php echo $inst['Dirección'] ?></td>
                        <td><?php echo $inst['Estrato'] ?></td>
                        <td><?php echo $inst['Dificultad_de_acceso_al_municipio'] ?></td>
                        <td><?php echo $inst['Coordenadas_Grados_decimales'] ?></td>
                        <td><?php echo $inst['Nombre_Cliente_Completo'] ?></td>
                        <td><?php echo $inst['Telefono'] ?></td>
                        <td><?php echo $inst['Celular'] ?></td>
                        <td><?php echo $inst['Correo_Electronico'] ?></td>
                        <td><?php echo $inst['VIP'] ?></td>
                        <td><?php echo $inst['Codigo_Proyecto_VIP'] ?></td>
                        <td><?php echo $inst['Nombre_Proyecto_VIP'] ?></td>
                        <td><?php echo $inst['Velocidad_Contratada_MB'] ?></td>
                        <td><?php echo $inst['Meta'] ?></td>
                        <td><?php echo $inst['Fecha_max_de_cumplimiento_de_meta'] ?></td>
                        <td><?php echo $inst['Dias_pendientes_de_la_fecha_de_cumplimiento'] ?></td>
                        <td><?php echo $inst['FECHA_APROBACION_INTERVENTORIA'] ?></td>
                        <td><?php echo $inst['FECHA_APROBACION_META_SUPERVISION'] ?></td>
                        <td><?php echo $inst['Tipo_Solucion_UM_Operatividad'] ?></td>
                        <td><?php echo $inst['Operador_Prestante'] ?></td>
                        <td><?php echo $inst['IP'] ?></td>
                        <td><?php echo $inst['Olt'] ?></td>
                        <td><?php echo $inst['PuertoOlt'] ?></td>
                        <td><?php echo $inst['Serial_ONT'] ?></td>
                        <td><?php echo $inst['Port_ONT'] ?></td>
                        <td><?php echo $inst['Nodo_Cobre'] ?></td>
                        <td><?php echo $inst['Armario'] ?></td>
                        <td><?php echo $inst['Red_Primaria'] ?></td>
                        <td><?php echo $inst['Red_Secundaria'] ?></td>
                        <td><?php echo $inst['Nodo_HFC'] ?></td>
                        <td><?php echo $inst['Amplificador'] ?></td>
                        <td><?php echo $inst['Tap_Boca'] ?></td>
                        <td><?php echo $inst['Mac_Cpe'] ?></td>
                        <td><?php echo $inst['Fecha_Asignado_o_Presupuestado'] ?></td>
                        <td><?php echo $inst['Fecha_En_proceso_de_Instalacion'] ?></td>
                        <td><?php echo $inst['Fecha_Anulado'] ?></td>
                        <td><?php echo $inst['Fecha_Instalado'] ?></td>
                        <td><?php echo $inst['Fecha_Activo'] ?></td>
                        <td><?php echo $inst['Fecha_aprobacion_de_meta'] ?></td>
                        <td></td>
                    </tr>
                    <?php } ?>      
                </tbody>
            </table>
          </div>
      </div>
    </div>
</div>
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
            <li class="breadcrumb-item active" aria-current="page">Módulo Inventarios</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Módulo Inventarios</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->

<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="row">
          <div class="col-lg-12">
            <table class="table table-condensed table-striped dataTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Id Producto</th>
                        <th>Nombre Producto</th>
                        <th>SKU</th>
                        <th>SN/MAC</th>
                        <th>Ubicación</th>
                        <th>Ciudad</th>
                        <th>Departamento</th>
                        <th>Código</th>
                        <th>Latitud</th>
                        <th>Longitud</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach((array)$invs as $inv){ ?>
                    <tr>
                        <td><?php echo $inv['id'] ?></td>
                        <td><?php echo $inv['pid'] ?></td>
                        <td><?php echo $inv['name'] ?></td>
                        <td><?php echo $inv['sku'] ?></td>
                        <td><?php echo $inv['health_reg'] ?></td>
                        <td><?php echo $inv['location'] ?></td>
                        <td><?php echo $inv['city'] ?></td>
                        <td><?php echo $inv['district'] ?></td>
                        <td><?php echo $inv['code'] ?></td>
                        <td><?php echo $inv['lat'] ?></td>
                        <td><?php echo $inv['lng'] ?></td>
                        <td></td>
                    </tr>
                    <?php } ?>   
              
                </tbody>
            </table>
          </div>
      </div>
    </div>
</div>
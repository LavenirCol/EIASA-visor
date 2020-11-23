<?php
/* @var $this yii\web\View */
use \yii\helpers\Url;
?>
<script>
var title = 'Dashboard Operación';
</script>
<div class="content content-fixed bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item" aria-current="page">Reportes</li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard Operación</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Dashboard Operación</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->

<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="row">
          <div class="col-lg-12 mb-5">
            <table class="table table-condensed table-striped dataTablec" id="dataTableOperaciondash" style="width:100%">
                <thead>
                    <tr>
                        <th>DANE</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Meta</th>
                        <th>Beneficiarios En Operación</th>
                        <th>Meta Tiempo en Servicio</th>
                        <th>Tiempo en Servicio</th>
                        <th>Avance</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach((array)$insts as $inst){ ?>
                    <tr>
                        <td><?php echo $inst['DANE'] ?></td>
                        <td><?php echo $inst['Departamento'] ?></td>
                        <td><?php echo $inst['Municipio'] ?></td>
                        <td><?php echo $inst['Meta'] ?></td>
                        <td><?php echo $inst['Beneficiarios_En_Operacion'] ?></td>
                        <td><?php echo $inst['Meta_Tiempo_en_servicio'] ?></td>
                        <td><?php echo $inst['Tiempo_en_servicio'] ?></td>
                        <td><?php echo $inst['Avance'] ?> %</td>
                        <td>
                            <?php if($inst['Beneficiarios_En_Operacion'] > 0){ ?>
                            <a href="<?php echo Url::toRoute('reports/operaciondetails'); ?>?dane=<?php echo $inst['DANE']; ?>">Detalles</a> 
                            <?php } ?>
                        </td>
                        <td></td>
                    </tr>
                    <?php } ?>      
                </tbody>
            </table>
          </div>
      </div>
    </div>
</div>
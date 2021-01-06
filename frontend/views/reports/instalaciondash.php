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
        <a href="#" onclick="window.open('<?php echo Url::toRoute('reports/instalaciondetailsserver'); ?>?export=csv');" class="btn btn-sm btn-primary">Descargar Todo</a>        
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->

<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="row">
          <div class="col-lg-12 mb-5">
            <table class="table table-condensed table-striped dataTablec" id="dataTableInstalaciondash" style="width:100%">
                <thead>
                    <tr>
                        <th>DANE</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th align="center">Meta</th>
                        <th align="center">Beneficiarios Instalados</th>
                        <th align="center">Avance</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $metas = 0;
                    $beneficiarios = 0;
                    
                    foreach((array)$insts as $inst){ 
                    
                        $metas = $metas + intval($inst['Meta']);
                        $beneficiarios = $beneficiarios + intval($inst['Beneficiarios_Instalados']);
                        
                        ?>
                    <tr>
                        <td><?php echo $inst['DANE'] ?></td>
                        <td><?php echo $inst['Departamento'] ?></td>
                        <td><?php echo $inst['Municipio'] ?></td>
                        <td align="center"><?php echo number_format($inst['Meta']) ?></td>
                        <td align="center"><?php echo number_format($inst['Beneficiarios_Instalados']) ?></td>
                        <td align="center"><?php echo number_format($inst['Avance']) ?> %</td>
                        <td align="center">
                            <?php if($inst['Beneficiarios_Instalados'] > 0){ ?>
                            <a href="<?php echo Url::toRoute('reports/instalaciondetails'); ?>?dane=<?php echo $inst['DANE']; ?>" class="btn btn-sm btn-primary">Detalles</a> 
                            <?php } ?>
                        </td>
                        <td></td>
                    </tr>
                    <?php } ?>      
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold">
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td align="center"><?php echo number_format($metas) ?></td>
                        <td align="center"><?php echo number_format($beneficiarios) ?></td>
                        <td align="center"><?php 
                            $avance = 0;
                            if ((float) ($beneficiarios) > 0) {
                                $result = (float) ($beneficiarios / $metas);
                                $avance = round($result*100,2);
                            }

                            echo number_format($avance, 2, '.', '');
                        ?> %</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
          </div>
      </div>
    </div>
</div>
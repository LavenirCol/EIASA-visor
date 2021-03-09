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
                <br>
                <nav>
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li>
                            <a href="#" onclick="window.open('<?php echo Url::toRoute('reports/operaciondetailsserver'); ?>?export=csv');" class="btn btn-sm btn-primary">Descargar Todo</a> 
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
                           &nbsp;<a href="#" onclick="$('#dptos').val() == '-1' ? '' : window.open('<?php echo Url::toRoute('reports/operaciondetailsserver'); ?>?export=csv&dptos=' + $('#dptos').val());" class="btn btn-sm btn-primary">Descargar Departamento</a>        
                        </li>
                    </ol>
                </nav>        
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
                        <?php
                        $metas = 0;
                        $beneficiarios = 0;

                        foreach ((array) $insts as $inst) {

                            $metas = $metas + intval($inst['Meta']);
                            $beneficiarios = $beneficiarios + intval($inst['Beneficiarios_En_Operacion']);
                            ?>
                            <tr>
                                <td><?php echo $inst['DANE'] ?></td>
                                <td><?php echo $inst['Departamento'] ?></td>
                                <td><?php echo $inst['Municipio'] ?></td>
                                <td align="center"><?php echo $inst['Meta'] ?></td>
                                <td align="center"><?php echo $inst['Beneficiarios_En_Operacion'] ?></td>
                                <td align="center"><?php echo $inst['Meta_Tiempo_en_servicio'] ?></td>
                                <td align="center"><?php echo number_format($inst['Tiempo_en_servicio'], 2, '.', ''); ?></td>
                                <td align="center"><?php echo $inst['Avance'] ?> %</td>
                                <td>
                                    <?php if ($inst['Beneficiarios_En_Operacion'] > 0) { ?>
                                        <a href="<?php echo Url::toRoute('reports/operaciondetails'); ?>?dane=<?php echo $inst['DANE']; ?>" class="btn btn-sm btn-primary">Detalles</a> 
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
                            <td></td>
                            <td></td>
                            <td align="center"><?php
                                $avance = 0;
                                if ((float) ($beneficiarios) > 0) {
                                    $result = (float) ($beneficiarios / $metas);
                                    $avance = round($result * 100, 2);
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
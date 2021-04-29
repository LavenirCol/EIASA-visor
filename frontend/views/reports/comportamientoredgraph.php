<?php
/* @var $this yii\web\View */

use \yii\web\View;
use \yii\helpers\Url;

$this->registerJs("var down = " . json_encode(array('data' => array_map('array_values', $down)), JSON_NUMERIC_CHECK) . ";", View::POS_END, 'down');
$this->registerJs("var up = " . json_encode(array('data' => array_map('array_values', $up)), JSON_NUMERIC_CHECK) . ";", View::POS_END, 'up');
?>
<script>
    var title = 'Comportamiento de Red';
</script>
<div class="content content-fixed bd-b pb-3">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page">Inicio</li>
                        <li class="breadcrumb-item" aria-current="page">Reportes</li>
                        <li class="breadcrumb-item" aria-current="page">Comportamiento de Red</li>
                        <li class="breadcrumb-item active" aria-current="page">Tráfico de Cliente</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0">Tráfico de Cliente</h4>

            </div>
        </div>
    </div><!-- container -->
</div><!-- content -->

<div class="content">
    <form  action="/reports/comportamientoreddash" method="get">
        <div class="container p-3">
            <div class="row">                
                <div class="col-lg-10">
                    <table class="table table-condensed table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Municipio/OLT</th>
                                <th>Service Port</th>
                                <th>Cliente</th>
                                <th>Serial</th>
                                <th>VLAN</th>
                                <th>Port Type</th>
                                <th>F / S / P</th>
<!--                                <th>Vpi</th>-->
                                <th>Estado</th>
                                <th>Actualizado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $olt['poblacion'];?></td>
                                <td><?php echo $service['index'];?></td>
                                <td>Cliente</td>
                                <td><?php echo $service['last_sn'];?></td>
                                <td><?php echo $service['vlan_id'];?></td>
                                <td><?php echo $service['port_type'];?></td>
                                <td><?php echo $service['frame'];?> / <?php echo $service['slot'];?> / <?php echo $service['port'];?></td>
<!--                                <td><?php echo $service['vpi'];?></td>-->
                                <td><?php echo $service['last_state'];?></td>
                                <td><?php echo $service['updated_at'];?></td>                                
                            </tr>
                        </tbody>
                    </table>
                </div>                

                <div class="col-lg-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="window.history.back()" style="width:100%">Regresar</button><br>
                </div>
            </div>  
        </div>
        <div>
            <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>"/>
        </div>
    </form>
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0 bd bd-2 rounded-10">
        <div class="row">
            <div class="col-lg-12 mb-5">
                <figure class="highcharts-figure">
                    <div id="container"></div>
                </figure>

            </div>
        </div>
    </div>
    </div>
<?php
$this->registerJs("
   var startDate = new Date(down.data[down.data.length - 1][0]), // Get year of last data point
        minRate = 1,
        maxRate = 0,
        startPeriod,
        date,
        rate,
        index;

    startDate.setMonth(startDate.getMonth() - 3); // a quarter of a year before last data point
    startPeriod = Date.UTC(startDate.getFullYear(), startDate.getMonth(), startDate.getDate());

    // Create the chart
    Highcharts.stockChart('container', {

        rangeSelector: {
            selected: 1,
          buttons: [{
              type: 'month',
              count: 1,
              text: '1m',
              title: 'Ver 1 mes'
          }, {
              type: 'month',
              count: 3,
              text: '3m',
              title: 'Ver 3 meses'
          }, {
              type: 'month',
              count: 6,
              text: '6m',
              title: 'Ver 6 meses'
          }, {
              type: 'ytd',
              text: 'ADH',
              title: 'Ver un año desde hoy'
          }, {
              type: 'year',
              count: 1,
              text: '1a',
              title: 'Ver 1 año'
          }, {
              type: 'all',
              text: 'Todo',
              title: 'Ver todo'
          }]
        },

        title: {
            text: 'Tráfico de Cliente'
        },
        legend:{
          enabled:true
        },
        yAxis: {
            title: {
                text: 'Kilobytes'
            }
        },
        series: [{
            type: 'area',
            name: 'DownStream',
            color:'#7F1973',
            data: down.data
        },
        {
            type: 'area',
            name: 'Upstream',
            data: up.data
        }]
    });

", View::POS_END, 'graph');
?>
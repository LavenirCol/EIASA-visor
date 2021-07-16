<?php
/* @var $this yii\web\View */

use \yii\web\View;
use \yii\helpers\Url;

$this->registerJs("var details = " . json_encode(array('data' => array_map('array_values', $details)), JSON_NUMERIC_CHECK) . ";", View::POS_END, 'details');
$this->registerJs("var percent = " . json_encode(array('data' => array_map('array_values', $percent)), JSON_NUMERIC_CHECK) . ";", View::POS_END, 'percent');
$this->registerJs("var avg = " . json_encode(array('data' => array_map('array_values', $avg)), JSON_NUMERIC_CHECK) . ";", View::POS_END, 'avg');
?>
<script>
    var title = 'Comportamiento de Red Total OLT - PINGS';
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
                <h4 class="mg-b-0">Gráficas OLT - PINGS</h4>

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
                                <th>Ancho de Banda Asignado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $olt['poblacion'];?></td>                         
                                <td><?php echo $olt['bandwidth'];?> Kb</td>                         
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
    <p>&nbsp;</p>
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0 bd bd-2 rounded-10">
        <div class="row">
            <div class="col-lg-12 mb-5">
                <figure class="highcharts-figure">
                    <div id="containeravg"></div>
                </figure>

            </div>
        </div>
    </div>
    
    <p>&nbsp;</p>
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0 bd bd-2 rounded-10">
        <div class="row">
            <div class="col-lg-12 mb-5 text-center">
                <p>&nbsp;</p>
                <h5 class="mg-b-0">Detalle PINGS</h5> 
                <table class="table table-condensed table-striped dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th data-priority="1" width="10%">Fecha/Hora</th>
                            <th data-priority="2" width="10%">Bytes</th>
                            <th data-priority="2" width="10%">Paquetes Transmitidos</th>
                            <th data-priority="3" width="10%">Paquetes Recibidos</th>
                            <th data-priority="4" width="10%">Paquetes Perdidos %</th>
                            <th data-priority="5" width="10%">Tiempo Total (ms)</th>
                            <th data-priority="5" width="10%">RTT Min (ms)</th>
                            <th data-priority="5" width="10%">RTT Avg (ms)</th>
                            <th data-priority="5" width="10%">RTT Max (ms)</th>
                            <th data-priority="5" width="10%">RTT Mdev (ms)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($details as $det) { ?>
                        <tr>
                            <td><?php echo $det['created_at']; ?></td>
                            <td><?php echo $det['bytes_of_data']; ?></td>
                            <td><?php echo $det['packets_transmitted']; ?></td>
                            <td><?php echo $det['packets_recived']; ?></td>
                            <td><?php echo $det['packets_lost_percent']; ?></td>
                            <td><?php echo $det['packets_time']; ?></td>
                            <td><?php echo $det['rtt_min']; ?></td>
                            <td><?php echo $det['rtt_avg']; ?></td>
                            <td><?php echo $det['rtt_max']; ?></td>
                            <td><?php echo $det['rtt_mdev']; ?></td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs("
   var startDate = new Date(percent.data[percent.data.length - 1][0]), // Get year of last data point
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
            text: '% Paquetes Perdidos'
        },
        legend:{
          enabled:true
        },
        yAxis: {
            title: {
                text: '%'
            },
        },

        series: [{
            type: 'area',
            name: '% Paquetes Perdidos',
            color:'#7F1973',
            data: percent.data
        }]
    });
    
    //Promedio en ms
    // Create the chart
    Highcharts.stockChart('containeravg', {

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
            text: 'Promedio RTT Tiempo de Ida y Vuelta (ms)'
        },
        legend:{
          enabled:true
        },
        yAxis: {
            title: {
                text: 'ms'
            }
        },

        series: [{
            type: 'area',
            name: 'rtt avg (ms)',
            color:'#7F1973',
            data: avg.data
        }]
    });
", View::POS_END, 'graph');
?>
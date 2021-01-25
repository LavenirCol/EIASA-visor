<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\web\View;
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
                        <li class="breadcrumb-item active" aria-current="page">Dashboard PQR's y Mantenimiento</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0">Dashboard Módulo PQR's y Mantenimiento</h4>
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
                            <option value="<?php echo $depto['state']; ?>" ><?php echo $depto['state']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="label-form" label-for="mpios">Municipio</label>
                    <select class="custom-select" id="mpios" name="mpios">
                        <option value="-1" selected="selected">--Seleccione--</option>
                        <?php foreach ($mpios as $mpio) { ?>
                            <option value="<?php echo $mpio['town']; ?>" ><?php echo $mpio['town']; ?></option>
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
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <!-- START card-->
                <div class="card border-1">
                    <div class="row row-flush">
                        <div class="col-4 bg-primary text-center d-flex align-items-center justify-content-center rounded-left"><em class="fa fa-clipboard-list fa-2x text-white"></em></div>
                        <div class="col-8">
                            <div class="card-body text-center">
                                <h4 class="mt-0" id="div_tickets_abiertos"></h4>
                                <p class="mb-0 text-muted">Total Tickets Abiertos</p>
                            </div>
                        </div>
                    </div>
                </div><!-- END card-->
            </div>
            <div class="col-xl-3 col-md-6">
                <!-- START card-->
                <div class="card border-1">
                    <div class="row row-flush">
                        <div class="col-4 bg-secondary text-center d-flex align-items-center justify-content-center rounded-left"><em class="fa fa-clipboard-check fa-2x text-white"></em></div>
                        <div class="col-8">
                            <div class="card-body text-center">
                                <h4 class="mt-0" id="div_tickets_cerrados"></h4>
                                <p class="mb-0 text-muted">Total Tickets Cerrados</p>
                            </div>
                        </div>
                    </div>
                </div><!-- END card-->
            </div>
            <div class="col-xl-3 col-md-6">
                <!-- START card-->
                <div class="card border-1">
                    <div class="row row-flush">
                        <div class="col-4 bg-info text-center d-flex align-items-center justify-content-center rounded-left"><em class="fa fa-window-maximize fa-2x text-white"></em></div>
                        <div class="col-8">
                            <div class="card-body text-center">
                                <h4 class="mt-0"><?php echo $daysopen['days'] ?> Días</h4>
                                <p class="mb-0 text-muted">Promedio duración estado abierto</p>
                            </div>
                        </div>
                    </div>
                </div><!-- END card-->
            </div>
            <div class="col-xl-3 col-md-6">
                <!-- START card-->
                <div class="card border-1">
                    <div class="row row-flush">
                        <div class="col-4 bg-danger text-center d-flex align-items-center justify-content-center rounded-left"><em class="fa fa-window-close fa-2x text-white"></em></div>
                        <div class="col-8">
                            <div class="card-body text-center">
                                <h4 class="mt-0"><?php echo $daysclosed['days'] ?> Días</h4>
                                <p class="mb-0 text-muted">Promedio duración cierre</p>
                            </div>
                        </div>
                    </div>
                </div><!-- END card-->
            </div>
        </div>         
        <div class="row">
            <div class="col-lg-12 mb-5">
            </div>
        </div>         
        <div class="row">
            <div class="col-lg-6 mb-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tickets Mensuales</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Agrupados por estado</h6>
                        <div id="container_estados"></div>
                        <div class="card-footer">
                            <p class="highcharts-description">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tickets Mensuales</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Agrupados por Grupo</h6>
                        <div id="container_grupos"></div>
                        <div class="card-footer">
                            <p class="highcharts-description">
                            </p>
                        </div>
                    </div>
                </div>
            </div>            
        </div> 
        <div class="row">
            <div class="col-lg-12 mb-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detalle Tickets</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Agrupados por Grupo, Tipo y Prioridad</h6>
                        <table class="table table-condensed table-striped dataTable responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th data-priority="1" width="25%">Grupo</th>
                                    <th data-priority="2" width="25%">Tipo</th>
                                    <th data-priority="3" width="25%">Prioridad</th>
                                    <th data-priority="4" width="25%">Cantidad</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ((array) $tickets_severity as $ticket) { ?>
                                    <tr>
                                        <td><?php echo $ticket['category_label'] ?></td>
                                        <td><?php echo $ticket['type_label'] ?></td>
                                        <td><?php echo $ticket['severity_label'] ?></td>
                                        <td><?php echo $ticket['conteo'] ?></td>
                                        <td></td>
                                    </tr>
                                <?php } ?>                   
                            </tbody>
                        </table>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs("var tickets = " . json_encode($tickets_estado) . ";", View::POS_READY, 'my-tickets');
$this->registerJs("
    var periodos = new Array();
    var ticketsregistrados = new Array();
    var ticketsabiertos = new Array();
    var ticketscerrados = new Array();
    
    $.each(tickets, function(idx, item){
        periodos.push(item.fecha);
        ticketsregistrados.push(Number(item.registrados));
        ticketsabiertos.push(Number(item.abiertos));
        ticketscerrados.push(Number(item.cerrados));
    });
    
    $('#div_tickets_abiertos').html(ticketsabiertos.reduce((a, b) => a + b, 0));
    $('#div_tickets_cerrados').html(ticketscerrados.reduce((a, b) => a + b, 0));

", View::POS_READY, 'my-ticketsprocess');
$this->registerJs("
Highcharts.chart('container_estados', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: periodos,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Cantidad Tickets'
        }
    },
    tooltip: {
        headerFormat: '<span style=font-size:10px>{point.key}</span><table>',
        pointFormat: '<tr><td style=color:{series.color};padding:>{series.name}: </td>' +
            '<td style=padding:0><b>{point.y:.0f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Registrados',
        data: ticketsregistrados

    },{
        name: 'Abiertos',
        data: ticketsabiertos

    }, {
        name: 'Cerrados',
        data: ticketscerrados

    }]
});", View::POS_READY, 'my-tickets-bar');

//por grupo
$this->registerJs("var tickets_grupo = " . json_encode($tickets_grupo) . ";", View::POS_READY, 'my-tickets-grupo');
$this->registerJs("

    var facturacion = new Array();
    var infogeneral = new Array();
    var otros = new Array();
    var peticion = new Array();
    var reportefalla = new Array();
    
    $.each(tickets_grupo, function(idx, item){
        facturacion.push(Number(item['Facturación']));
        infogeneral.push(Number(item['Información General']));
        otros.push(Number(item['Other']));
        peticion.push(Number(item['Petición']));
        reportefalla.push(Number(item['Reporte de Falla']));
    });
", View::POS_READY, 'my-tickets-grupo-process');
$this->registerJs("
Highcharts.chart('container_grupos', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: periodos,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Cantidad Tickets'
        }
    },
    tooltip: {
        headerFormat: '<span style=font-size:10px>{point.key}</span><table>',
        pointFormat: '<tr><td style=color:{series.color};padding:>{series.name}: </td>' +
            '<td style=padding:0><b>{point.y:.0f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Facturación',
        data: facturacion

    },{
        name: 'Información General',
        data: infogeneral

    }, {
        name: 'Petición',
        data: peticion

    }, {
        name: 'Reporte de Falla',
        data: reportefalla

    }, {
        name: 'Otros',
        data: otros

    }]
});", View::POS_READY, 'my-tickets-grupo-bar');
?>
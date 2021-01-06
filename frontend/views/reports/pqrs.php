<?php
 use yii\web\View
?>

<div class="content content-fixed bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item" aria-current="page">Reportes</li>
            <li class="breadcrumb-item active" aria-current="page">Módulo PQR's y Mantenimiento</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Módulo PQR's y Mantenimiento</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->

<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="row">
          <div class="col-lg-12 mb-5">
            <table class="table table-condensed table-striped dataTable responsive" style="width:100%">
                <thead>
                    <tr>
                        <th data-priority="1" width="10%">Departamento</th>
                        <th data-priority="2" width="10%">Municipio</th>
                        <th data-priority="3" width="10%">Código Acceso</th>
                        <th data-priority="4" width="15%">Cliente</th>
                        <th data-priority="5" width="10%">Ref Ticket</th>
                        <th data-priority="6" width="10%">Tipo</th>
                        <th data-priority="7" width="15%">Asunto</th>
                        <th data-priority="5" width="10%">Fecha Creación</th>
                        <th data-priority="5" width="10%">Fecha Limite</th>
                        <th data-priority="5" width="10%">Fecha Cierre</th>
                        <th data-priority="5" width="10%">Origen de Reporte</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach((array)$pqrs as $pqr){ ?>
                    <tr>
                        <td><?php echo $pqr['state'] ?></td>
                        <td><?php echo $pqr['town'] ?></td>
                        <td><?php echo $pqr['access_id'] ?></td>
                        <td><?php echo $pqr['name'] ?></td>
                        <td><?php echo $pqr['ref'] ?></td>
                        <td><?php echo $pqr['type_label'] ?><br><small><?php echo $pqr['category_label'] ?> - <?php echo $pqr['severity_label'] ?></small></td>
                        <td><?php echo $pqr['subject'] ?></td>                        
                        <td><?php echo isset($pqr['datec']) ? date("d/m/Y",$pqr['datec']) : '' ?></td>                        
                        <td><?php 
                        
                        if(isset($pqr['datec'])){
                            $holidayDates = array(
                                '2020-08-17',
                                '2020-10-12',
                                '2020-11-02',
                                '2020-11-16',
                                '2020-12-08',
                                '2020-12-25',
                                '2021-01-01',
                            );

                            $count5WD = 0;
                            $d = date('Y-m-d', $pqr['datec']) .' 00:00:00';
                            //echo $d;
                            $temp = strtotime($d ); //example as today is 2016-03-25
                            while($count5WD<15){
                                $next1WD = strtotime('+1 weekday', $temp);
                                $next1WDDate = date('Y-m-d', $next1WD);
                                if(!in_array($next1WDDate, $holidayDates)){
                                    $count5WD++;
                                }
                                $temp = $next1WD;
                            }

                            $next5WD = date("d/m/Y", $temp);
                            echo $next5WD; 
                                
                        }
                        ?></td>
                        <td>
                            <?php                             
                                if (isset($pqr['date_close'])){
                                    if($pqr['date_close'] !== ''){
                                        echo date("d/m/Y",$pqr['date_close']);
                                    }
                                } 
                            ?>
                        </td>       
                        <td>Call Center</td>
                        <td><button class="btn btn-sm btn-primary btndetail" data-detail="
                            <?php echo htmlspecialchars($pqr['message']) ?><br><br>                        
                            <p><b>Mensajes:</b></p>                            
                            <ul style='padding:0px; font-size:11px'><?php
                                $msg = '';
                                $jsond = json_decode($pqr['messages']);
                                foreach((array)$jsond as $key => $mesa){
                                    $msg = $msg.'<li>'.date("Y-m-d H:i:s",$mesa->datec).' - '.htmlspecialchars($mesa->message).'</li>';
                                }
                                echo $msg;
                                ?></ul>" onclick="javascript:showdetail($(this));">Detalle</button>
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
<style>
    .swal2-content{
        text-align: justify !important;
    }
</style>
<?php 
    $this->registerJs(
        "
        function showdetail(obj){
           var msg = $(obj).data('detail');
            Swal.fire({
              title: '',
              icon: 'info',
              html: msg,
              showCloseButton: false,
              focusConfirm: false
            });
        }
        ",
        View::POS_HEAD,
        '.btndetail'
    );
?>
    
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
            <table class="table table-condensed table-striped dataTable" style="width:100%">
                <thead>
                    <tr>
                        <th data-priority="1" width="10%">Departamento</th>
                        <th data-priority="2" width="10%">Municipio</th>
                        <th data-priority="3" width="10%">Código Acceso</th>
                        <th data-priority="4" width="10%">Cliente</th>
                        <th data-priority="5" width="10%">Ref Ticket</th>
                        <th data-priority="6" width="15%">Tipo</th>
                        <th data-priority="7" width="15%">Asunto</th>
                        <th data-priority="7" width="20%">Detalle</th>
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
                        <td><?php echo $pqr['message'] ?><br><br>
                        
                            <p>
                                <b>Mensajes:</b>
                            </p>
                            
                            <ul style="padding:0px; font-size:10px"><?php
                                $msg = '';
                                $jsond = json_decode($pqr['messages']);
                                foreach((array)$jsond as $key => $mesa){
                                    $msg = $msg.'<li>'.date("Y-m-d H:i:s",$mesa->datec).' - '.$mesa->message.'</li>';
                                }
                                echo $msg;
                            ?></ul>
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
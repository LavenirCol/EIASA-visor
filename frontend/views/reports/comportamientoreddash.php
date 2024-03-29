<?php
/* @var $this yii\web\View */

use \yii\web\View;
use \yii\helpers\Url;
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
                        <li class="breadcrumb-item active" aria-current="page">Comportamiento de Red</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0">Comportamiento de Red</h4>
                <br>
                <nav>
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li>
                            <a href="#" onclick="window.open('<?php echo Url::toRoute('reports/comportamientoredserver'); ?>?export=csv&exportall=1');" class="btn btn-sm btn-primary">Descargar Todo</a> 
                        </li>
                        <li style="width:100px;"></li>
                        <li>        
                        </li>
                        <li>
                        </li>
                    </ol>
                </nav> 
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
                            <option value="<?php echo $depto['Departamento']; ?>" ><?php echo $depto['Departamento']; ?></option>
<?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="label-form" label-for="deptos">Municipio / OLT</label>
                    <select class="custom-select" id="oltCodeFilter" name="oltCodeFilter">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($oltsCodeList as $oltCodeItem) { ?>
                            <option value="<?php echo $oltCodeItem['id']; ?>"><?php echo $oltCodeItem['poblacion']; ?></option>
<?php } ?>
                    </select>
                </div>                
                <div class="col-lg-2"></div>
                <div class="col-lg-2"></div>

                <div class="col-lg-2">
                    <button type="button" class="btn btn-primary btn-sm" id="btnsearch" style="width:100%">Buscar</button><br>
                    <button type="clear" class="btn btn-secondary btn-sm mt-2" style="width:100%">Borrar</button>
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-primary btn-sm" id="btnshowtotalgraphic" style="width:100%">Gráfica Tráfico</button><br>
                    <button type="button" class="btn btn-primary btn-sm mt-2" id="btnshowothergraphic" style="width:100%">Otras Gráficas</button>
                </div>
            </div>  
        </div>
        <div>
            <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>"/>
        </div>
    </form>
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="row">
            <div class="col-lg-12 mb-5">
                <p>&nbsp;</p>
                <h4 class="mg-b-0"></h4> 
                <table class="table table-condensed table-striped" id="dataTableComportamientoReddash" style="width:100%">
                    <thead>
                        <tr>
                            <th data-priority="1" width="10%">Departamento</th>
                            <th data-priority="2" width="10%">Municipio</th>
                            <th data-priority="2" width="10%">Código DANE</th>
                            <th data-priority="3" width="10%">Código Acceso</th>
                            <th data-priority="4" width="15%">Cliente</th>
                            <th data-priority="5" width="10%">Cédula</th>
                            <th data-priority="5" width="10%">Teléfonos</th>
                            <th data-priority="5" width="10%">Email</th>
                            <th data-priority="5" width="10%">Dirección / Barrio</th>
                            <th>Service Port</th>
                            <th>Serial</th>
                            <th>VLAN</th>
                            <th>Port Type</th>
                            <th>F / S / P</th>
                            <th>Vpi</th>
                            <th>Actualizado</th>
                            <th></th>
                        </tr>
                    </thead>                    
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs("
    $('#btnshowtotalgraphic').click(function(){
        var olt = $('#oltCodeFilter').val();
        if(olt == '-1'){
            window.alert('Debe seleccionar una OLT para consultar');
        }else{
            window.location.href = '/reports/comportamientoredtotalgraph?olt='+ olt; //olt
        }
    });
    $('#btnshowothergraphic').click(function(){
        var olt = $('#oltCodeFilter').val();
        if(olt == '-1'){
            window.alert('Debe seleccionar una OLT para consultar');
        }else{
            window.location.href = '/reports/comportamientoredothergraph?olt='+ olt; //olt
        }
    });    
", View::POS_END, 'totalgrpah');
?>
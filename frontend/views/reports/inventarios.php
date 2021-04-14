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
    <form method="post">
        <div class="container bd bd-2 p-3 rounded-10">
            <div class="row">
                <div class="col-lg-2 pd-4">
                    <label class="label-form" label-for="deptos">Departamento</label>
                    <select class="custom-select" id="dptos" name="deptos">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($deptos as $depto){ ?>
                            <option value="<?php echo $depto['city']; ?>"><?php echo $depto['city']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 pd-4">
                    <label class="label-form" label-for="mpios">Municipio</label>
                    <select class="custom-select" id="mpios" name="mpios">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($mpios as $mpio){ ?>
                            <option value="<?php echo $mpio['district']; ?>"><?php echo $mpio['district']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 pd-4">
                    <label class="label-form" label-for="daneCodeFilter">Código DANE</label>
                    <select class="custom-select" id="daneCodeFilter" name="daneCodeFilter">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($daneCodeList as $daneCodeItem){ ?>
                            <option value="<?php echo $daneCodeItem['district_code']; ?>"><?php echo $daneCodeItem['district_code']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 pd-4">
                    <label class="label-form" label-for="materials">Material</label>
                    <select class="custom-select" id="materials" name="materials">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($materials as $material){ ?>
                            <option value="<?php echo $material['name']; ?>"><?php echo $material['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 pd-4">
                    <label class="label-form" label-for="factories">Fabricante</label>
                    <select class="custom-select" id="factories" name="factories">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($factories as $factory){ ?>
                            <option value="<?php echo $factory['factory']; ?>"><?php echo $factory['factory']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 pd-4">
                    <label class="label-form" label-for="models">Modelo</label>
                    <select class="custom-select" id="models" name="models">
                        <option value="-1">--Seleccione--</option>
                        <?php foreach ($models as $model){ ?>
                            <option value="<?php echo $model['model']; ?>"><?php echo $model['model']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2 pd-4">
                    <button type="button" class="btn btn-primary btn-sm" id="btnsearch" style="width:100%">Buscar</button><br>
                    <button type="clear" class="btn btn-secondary btn-sm mt-2" style="width:100%">Borrar</button>
                </div>
            </div>  
        </div>
    </form>
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0 mt-3">              
      <div class="row">
          <div class="col-lg-12 mb-5">
            <table class="table table-condensed table-striped" id="dataTableInventarios" style="width:100%">
                <thead>
                    <tr>
                        <th>Serial o MAC</th>
                        <th>Modelo</th>
                        <th>Región</th>
                        <th>Código DANE Departamento</th>
                        <th>Departamento</th>
                        <th>Código DANE Municipio</th>
                        <th>Municipio</th>
                        <th>Barrio / Dirección</th>
                        <th>Descripción Material</th>
                        <th>Fabricante</th>
                        <th>Unidad de Medida</th>
                        <th>Cantidad</th>
                        <th>Coordenadas GPS</th>
                        <th>Estado</th>
                        <th>Fuente de Financiación</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
          </div>
      </div>
    </div>
    <div>
        <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
    </div>
</div>
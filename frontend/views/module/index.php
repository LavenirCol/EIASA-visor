<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ModuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Modules');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content  bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item active" aria-current="page">Módulos</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Módulos</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->
<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

<div class="row">
    <div class="col-md-12">
        <div class="user-index">

            <p>
                <?= Html::a(Yii::t('app', 'Nuevo Módulo'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <table class="table table-condensed table-striped dataTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Módulo</th>
                        <th>SoloLectura</th>
                        <th>Creado En</th>
                        <th>Usuario</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach((array) $modules as $module){ ?>
                    <tr>
                        <td><?php echo $module['idmodule'] ?></td>
                        <td><?php echo $module['moduleName'] ?></td>
                        <td><?php echo $module['moduleReadOnly'] ?></td>
                        <td><?php echo $module['moduleCreationDate'] ?></td>
                        <td><?php echo $module['username'] ?></td>
                        <td></td>
                    </tr>
                    
                    <?php 
                    }
                    //var_dump($users);
                    ?>
                </tbody>
            </table>

        </div>

    </div>
</div>

    </div>
</div>
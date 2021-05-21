<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content  bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Usuarios</h4>
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
                <?= Html::a(Yii::t('app', 'Nuevo Usuario'), ['site/signup'], ['class' => 'btn btn-primary']) ?>
            </p>

            <table class="table table-condensed table-striped dataTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Creado En</th>
                        <th>Perfil</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach((array) $users as $user){ ?>
                    <tr>
                        <td><?php echo $user['id'] ?></td>
                        <td><?php echo $user['username'] ?></td>
                        <td><?php echo $user['email'] ?></td>
                        <td><?php echo $user['status'] ?></td>
                        <td><?php echo gmdate("Y-m-d H:i:s", $user['created_at']); ?></td>
                        <td><?php echo $user['name'] ?></td>
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
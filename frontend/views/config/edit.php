<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->registerJsFile(Yii::$app->homeUrl . 'js/config.js', [\yii\web\JqueryAsset::className()]);

$this->title = Yii::t('app', 'Configuración');
$this->params['breadcrumbs'][] = $this->title;

function configIsCheck($config,$configList)
{
    
    foreach(explode(',',$configList) as $item)
    {     
        if($item==$config)return "checked";
    }
       return "";
}
?>
<?php Pjax::begin(); ?>
<div class="content  bd-b pb-3">
  <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="d-sm-flex align-items-center justify-content-between">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">Inicio</li>
            <li class="breadcrumb-item" aria-current="page">Configuración</li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <h4 class="mg-b-0">Configuración</h4>
      </div>
    </div>
  </div><!-- container -->
</div><!-- content -->
<div class="content">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
<div class="row">
    <div class="col-md-12">
        <div class="user-index">
            <table class="table table-condensed table-striped dataTable" style="width:100%">
                <thead>
                    <tr>                        
                        <th>Categorías</th>
                        <th>Visible</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 0;
                     foreach((array) $categoryList as $category){ ?>
                    <tr>
                        <td><?php echo $category['category_label'] ?></td>
                        <td><div class="checkbox checkbox-success"><input onclick="saveConfigTickets()" type="checkbox" <?php echo configIsCheck($category['category_label'],$configList) ?> id=<?php echo $index?>" name="<?php echo $category['category_label'] ?>"><label for="<?php echo $index?>"></label></td>                        
                        <td></td>
                    </tr>                    
                    <?php 
                    }                   
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div>
        <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
    </div>
</div>

    </div>
</div>
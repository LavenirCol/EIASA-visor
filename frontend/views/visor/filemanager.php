<?php
/* @var $this yii\web\View */
use \yii\helpers\Url;
?>
<script>
    var profile = <?php echo Yii::$app->user->identity->attributes['idProfile'] ?>;
</script>
<style>            
    .link-02{
        cursor: pointer;
    }
    .link-02:hover{
        cursor: pointer;
        text-decoration:underline;
    }
    .folderactive{
        cursor: pointer;
        border: 2px solid #0168fa;
    }
    .card-file p{
        font-size: 9px;
    }
    
    #formupload{
        width: 100%;
    }
    
    .dropzone{
        cursor: pointer;
        width: 100%;
        border: 2px dashed #0087F7;
        border-radius: 5px;
        background: white;
    }
    
    .card-file-thumb{
        background-size: cover; 
        background-repeat: no-repeat;
    }
    
    .fancybox-slide--iframe .fancybox-content {
        width  :  80%;
        height :  80%;
        max-width  : 80%;
        max-height : 80%;
        margin: 0;
    }
    
    .search-form{
        border: 1px solid #0168fa;
        padding: 0px 0px 0px 11px;
        border-radius: 7px;
    }
    
    .search-form button{
        border: none;
        color: white;
    }
    
    .dropdown-file .dropdown-link{
        color: #0168fa;        
    }
    
/*    .dropdown-file .dropdown-link svg{
        width: 25px;
        height: 25px;
    }*/

    table.dataTable thead th{
        font-size: 12px !important;
    }
    
    tbody td{
        font-size: 12px;
    }
</style>
<div class="filemgr-wrapper">
      <div class="filemgr-sidebar">
        <div class="filemgr-sidebar-header">
        <?php if(Yii::$app->user->identity->attributes['idProfile']  < 3){ ?>
          <div class="dropdown dropdown-icon flex-fill mg-l-10">
            <button class="btn btn-xs btn-block btn-primary" data-toggle="dropdown">Archivos <i data-feather="chevron-down"></i></button>
            <div class="dropdown-menu tx-13">
              <a href="#" id="btnaddfile" class="dropdown-item"><i data-feather="file"></i><span>Subir Archivo</span></a>
              <a href="#" id="btnaddfolder" class="dropdown-item"><i data-feather="folder"></i><span>Nueva Carpeta</span></a>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
        <?php } ?>
        </div><!-- filemgr-sidebar-header -->
        <div class="filemgr-sidebar-body">
          <div class="pd-t-20 pd-b-10 pd-x-10">
            <label class="tx-sans tx-uppercase tx-medium tx-10 tx-spacing-1 tx-color-03 pd-l-10">Módulos</label>
            <nav class="nav nav-sidebar tx-13" id="navmodulos">
                <?php foreach ($modulos as $mod){ ?>
                <a href="#" class="nav-link" data-idmodule="<?php echo $mod['idmodule']; ?>" data-susctable="<?php echo $mod['moduleReadOnly']; ?>"><i data-feather="folder"></i> <span><?php echo $mod['moduleName']; ?></span></a>                
                <?php } ?>
             </nav>
          </div>
          <div class="pd-y-10 pd-x-20">
            <label class="tx-sans tx-uppercase tx-medium tx-10 tx-spacing-1 tx-color-03 mg-b-15">Estado del Disco</label>

            <div class="media">
              <i data-feather="database" class="wd-30 ht-30"></i>
              <div class="media-body mg-l-10">
                <div class="tx-12 mg-b-4"><?php echo $actualsize ?> de <?php echo $assignedsize ?> GB (<?php echo round(($sizeingb/$assignedsize)*100) ?>%)</div>
                <div class="progress ht-3 mg-b-20">
                    <div class="progress-bar" style="width:<?php echo round(($sizeingb/$assignedsize)*100) ?>%" role="progressbar" aria-valuenow="<?php echo ($sizeingb/$assignedsize) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div><!-- media-body -->
            </div><!-- media -->
          </div>
        </div><!-- filemgr-sidebar-body -->
      </div><!-- filemgr-sidebar -->

      <div class="filemgr-content">
        <div class="filemgr-content-header">
          
          <div class="search-form">
            <i data-feather="search"></i>
            <div class="input-group">
              <input type="search" id="txtsearch" class="form-control" placeholder="Buscar archivos..." aria-label="Buscar archivos..." aria-describedby="button-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="btnsearch">Buscar</button>
              </div>
            </div>
          </div><!-- search-form -->
          <nav class="nav d-none d-sm-flex mg-l-auto">            
             <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="loadingindicator" style="display:none; margin: auto; background: rgb(255, 255, 255); shape-rendering: auto;" width="50px" height="50px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
<circle cx="75" cy="50" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.9166666666666666s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.9166666666666666s"></animate>
</circle><circle cx="71.65063509461098" cy="62.5" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.8333333333333334s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.8333333333333334s"></animate>
</circle><circle cx="62.5" cy="71.65063509461096" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.75s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.75s"></animate>
</circle><circle cx="50" cy="75" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.6666666666666666s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.6666666666666666s"></animate>
</circle><circle cx="37.50000000000001" cy="71.65063509461098" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.5833333333333334s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.5833333333333334s"></animate>
</circle><circle cx="28.34936490538903" cy="62.5" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.5s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.5s"></animate>
</circle><circle cx="25" cy="50" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.4166666666666667s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.4166666666666667s"></animate>
</circle><circle cx="28.34936490538903" cy="37.50000000000001" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.3333333333333333s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.3333333333333333s"></animate>
</circle><circle cx="37.499999999999986" cy="28.349364905389038" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.25s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.25s"></animate>
</circle><circle cx="49.99999999999999" cy="25" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.16666666666666666s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.16666666666666666s"></animate>
</circle><circle cx="62.5" cy="28.349364905389034" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.08333333333333333s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="-0.08333333333333333s"></animate>
</circle><circle cx="71.65063509461096" cy="37.499999999999986" fill="#b7d4fe" r="5">
  <animate attributeName="r" values="3;3;5;3;3" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="0s"></animate>
  <animate attributeName="fill" values="#b7d4fe;#b7d4fe;#0168fa;#b7d4fe;#b7d4fe" repeatCount="indefinite" times="0;0.1;0.2;0.3;1" dur="1s" begin="0s"></animate>
</circle>
<!-- [ldio] generated by https://loading.io/ --></svg>
<!--            <a href="" class="nav-link"><i data-feather="list"></i></a>
            <a href="" class="nav-link"><i data-feather="alert-circle"></i></a>
            <a href="" class="nav-link"><i data-feather="settings"></i></a>-->
          </nav>
        </div><!-- filemgr-content-header -->
        <div class="filemgr-content-body">
          <div class="pd-20 pd-lg-25 pd-xl-30" style="margin-bottom:150px">
            <h4 class="mg-b-15 mg-lg-b-25" id="actualfoldertitle"></h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mg-b-0">
<!--                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Library</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data</li>-->
              </ol>
            </nav>
            <hr class="mg-y-40 bd-0">
            
            <div id="divtablasuscriptores" style="width:100%; display:none">
                <div class="toast" id="cardclient" role="alert" aria-live="assertive" aria-atomic="true" style=" display:none; opacity: 1;max-width: 100%; width:100%">
                    <div class="toast-header">
                      <h6 class="tx-inverse tx-14 mg-b-0 mg-r-auto">Centro Digital</h6>
<!--                      <small>11 mins ago</small>-->
                      <button type="button" class="ml-2 mb-1 close tx-normal" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="toast-body">
                        <div class="row">
                            <div class="col-md-2">                                
                                <small><b>Id</b></small><br><small id="data-1"></small>
                                
                            </div>
                            <div class="col-md-3">
                                <small><b>Nombre</b></small><br><small id="data-2"></small>                                
                                <br>
                                <small><b>Código Dane</b></small><br><small id="data-3"></small>
                            </div>
                            <div class="col-md-2">                               
                                <small><b>Departamento</b></small><br><small id="data-4"></small>
                                <br>
                                <small><b>Municipio</b></small><br><small id="data-5"></small>
                            </div>
                            <div class="col-md-2">
                                <small><b>Dirección</b></small><br><small id="data-6"></small>
                                <br>
                                <small><b>Teléfono</b></small><br><small id="data-7"></small>
                            </div>
                            <div class="col-md-3">
                                <small><b>Correo</b></small><br><small id="data-8"></small>
                                <br>
                                <small><b>Coordenadas</b></small><br><small id="data-9"></small>
                            </div>
                        </div>
                        <div class="row"><div class="col-md-12"><button class="btn btn-sm btn-primary" style="padding:5px 7px;font-size: 10px;" id="btnclosecard"><< Atrás</button></div></div>
                    </div>
                </div>

                <label class="d-block tx-medium tx-10 tx-uppercase tx-sans tx-spacing-1 tx-color-03 mg-b-15" id="lblbusqueda">Búsqueda</label>
                <table class="table table-condensed table-striped" id="dataTableClientes" style="width:100%;">
                    <thead>
                        <tr>
<!--                            <th>Código Cliente</th>-->
                            <th></th>                            
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Código Dane</th>
                            <th>Departamento</th>
                            <th>Municipio</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Coordenadas</th>
                        </tr>
                    </thead>
                    <tbody>                        
                    </tbody>
                </table>            
            </div>
            <div id="divfileupload" style="display:none">
                <hr class="mg-y-40 bd-0">
                <label class="d-block tx-medium tx-10 tx-uppercase tx-sans tx-spacing-1 tx-color-03 mg-b-15">Subir Archivos</label>
                <div class="row row-xs" style="margin-bottom: 60px;">
                    
                    <!-- dropzone as a div in a form -->
                    <form id="formupload" action="<?php echo Url::toRoute('visor/upload'); ?>" method="POST" enctype="multipart/form-data">
                        <!-- dropzone field -->
                        <div id="myDropzone" class="dropzone"></div>
                    </form>

                    <!-- submit button -->
                    <button id="dropzoneSubmit" class="btn btn-block btn-primary">Subir</button>
<!--                    <form action="<?php echo Url::toRoute('visor/upload'); ?>" class="dropzone" id="my-awesome-dropzone"></form>-->
                </div><!-- row -->
            </div>
            <div id="divfoldercontainer" style="display:none">
                <hr class="mg-y-40 bd-0">
                <label class="d-block tx-medium tx-10 tx-uppercase tx-sans tx-spacing-1 tx-color-03 mg-b-15">Carpetas</label>
                <div class="row row-xs" id="foldercontainer">
                </div><!-- row -->
            </div>
            <div id="divfilecontainer" style="display:none">
                <hr class="mg-y-40 bd-0">
                <label class="d-block tx-medium tx-10 tx-uppercase tx-sans tx-spacing-1 tx-color-03 mg-b-15">Archivos</label>
                <div class="row row-xs" id="filecontainer" style="margin-bottom: 60px;">

                </div><!-- row -->                        
            </div>
          </div>
        </div><!-- filemgr-content-body -->
      </div><!-- filemgr-content -->

    </div><!-- filemgr-wrapper -->

    <div class="modal fade effect-scale" id="modalViewDetails" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body pd-20 pd-sm-30">
            <button type="button" class="close pos-absolute t-15 r-20" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

            <h5 class="tx-18 tx-sm-20 mg-b-30">View Details</h5>

            <div class="row mg-b-10">
              <div class="col-4">Filename:</div>
              <div class="col-8">Medical Certificate.pdf</div>
            </div><!-- row -->
            <div class="row mg-b-10">
              <div class="col-4">File ype:</div>
              <div class="col-8">PDF File</div>
            </div><!-- row -->
            <div class="row mg-b-10">
              <div class="col-4">File Size:</div>
              <div class="col-8">10.45 KB</div>
            </div><!-- row -->
            <div class="row mg-b-10">
              <div class="col-4">Created:</div>
              <div class="col-8">Monday, July 02, 2018 9:34am</div>
            </div><!-- row -->
            <div class="row mg-b-10">
              <div class="col-4">Modified:</div>
              <div class="col-8">Monday, July 02, 2018 9:34am</div>
            </div><!-- row -->
            <div class="row mg-b-10">
              <div class="col-4">Accessed:</div>
              <div class="col-8">Monday, July 02, 2018 9:34am</div>
            </div><!-- row -->
            <div class="row mg-b-10">
              <div class="col-4">Description:</div>
              <div class="col-8">
                <textarea class="form-control mg-t-5" rows="2" placeholder="Add description"></textarea>
              </div>
            </div><!-- row -->

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary mg-sm-l-5" data-dismiss="modal">Close</button>
          </div><!-- modal-footer -->
        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
    </div><!-- modal -->

    <div class="modal fade effect-scale" id="modalShare" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body pd-20 pd-sm-30">
            <button type="button" class="close pos-absolute t-15 r-20" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

            <h5 class="tx-18 tx-sm-20 mg-b-30">Share With Others</h5>

            <div class="mg-t-20">
              <label class="d-block">Other users:</label>
              <input class="form-control" placeholder="Enter names or email addresses">
              <div class="dropdown mg-t-10">
                Rights: <a href="" class="dropdown-link" data-toggle="dropdown">Allow editing <i class="icon ion-chevron-down tx-12"></i></a>
              </div><!-- dropdown -->
              <hr>
              <label class="d-block">More:</label>
              <nav class="nav nav-social">
                <a href="" class="nav-link"><i data-feather="facebook"></i></a>
                <a href="" class="nav-link"><i data-feather="twitter"></i></a>
              </nav>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Share</button>
          </div><!-- modal-footer -->
        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
    </div><!-- modal -->

    <div class="modal fade effect-scale" id="modalCopy" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body pd-20 pd-sm-30">
            <button type="button" class="close pos-absolute t-15 r-20" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

            <h5 class="tx-18 tx-sm-20 mg-b-0">Copy Item to</h5>
            <p class="mg-b-25 tx-color-03">Please select a folder</p>

            <div class="bd bg-ui-01 pd-10">
              <ul class="nav nav-sidebar flex-column tx-13">
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> Downloads</a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> Personal Stuff</a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> 3d Objects</a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> Recordings</a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> Support</a></li>
              </ul>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary mg-sm-r-5" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Copy</button>
          </div><!-- modal-footer -->
        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
    </div><!-- modal -->

    <div class="modal fade effect-scale" id="modalMove" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body pd-20 pd-sm-30">
            <button type="button" class="close pos-absolute t-15 r-20" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

            <h5 class="tx-18 tx-sm-20 mg-b-0">Move Item to</h5>
            <p class="mg-b-25 tx-color-03">Please select a folder</p>

            <div class="bd bg-ui-01 pd-10">
              <ul class="nav nav-sidebar flex-column tx-13">
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> Downloads</a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> Personal Stuff</a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> 3d Objects</a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> Recordings</a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="folder"></i> Support</a></li>
              </ul>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary mg-sm-r-5" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Move</button>
          </div><!-- modal-footer -->
        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
    </div><!-- modal -->

    <div class="pos-fixed b-10 r-10">
      <div id="toast" class="toast bg-dark bd-0 wd-300" data-delay="3000" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-transparent bd-white-1">
          <h6 class="tx-white mg-b-0 mg-r-auto">Downloading</h6>
          <button type="button" class="ml-2 mb-1 close tx-normal tx-shadow-none" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body pd-10 tx-white">
          <h6 class="mg-b-0">Medical_Certificate.pdf</h6>
          <span class="tx-color-03 tx-11">1.2mb of 4.5mb</span>
          <div class="progress ht-5 mg-t-5">
            <div class="progress-bar wd-50p" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div><!-- toast -->
    </div>

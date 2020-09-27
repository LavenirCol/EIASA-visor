<?php
/* @var $this yii\web\View */
use \yii\helpers\Url;
?>
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
</style>
<div class="filemgr-wrapper">
      <div class="filemgr-sidebar">
        <div class="filemgr-sidebar-header">
          <div class="dropdown dropdown-icon flex-fill mg-l-10">
            <button class="btn btn-xs btn-block btn-primary" data-toggle="dropdown">Archivos <i data-feather="chevron-down"></i></button>
            <div class="dropdown-menu tx-13">
              <a href="#" id="btnaddfile" class="dropdown-item"><i data-feather="file"></i><span>Subir Archivo</span></a>
              <a href="#" id="btnaddfolder" class="dropdown-item"><i data-feather="folder"></i><span>Nueva Carpeta</span></a>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
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
                <div class="tx-12 mg-b-4">5.2GB usados de 50GB</div>
                <div class="progress ht-3 mg-b-20">
                  <div class="progress-bar wd-15p" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div><!-- media-body -->
            </div><!-- media -->
          </div>
        </div><!-- filemgr-sidebar-body -->
      </div><!-- filemgr-sidebar -->

      <div class="filemgr-content">
        <div class="filemgr-content-header">
          <i data-feather="search"></i>
          <div class="search-form">
            <input type="search" class="form-control" placeholder="Buscar archivos...">
          </div><!-- search-form -->
          <nav class="nav d-none d-sm-flex mg-l-auto">
<!--            <a href="" class="nav-link"><i data-feather="list"></i></a>
            <a href="" class="nav-link"><i data-feather="alert-circle"></i></a>
            <a href="" class="nav-link"><i data-feather="settings"></i></a>-->
          </nav>
        </div><!-- filemgr-content-header -->
        <div class="filemgr-content-body">
          <div class="pd-20 pd-lg-25 pd-xl-30">
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
                <label class="d-block tx-medium tx-10 tx-uppercase tx-sans tx-spacing-1 tx-color-03 mg-b-15">Búsqueda</label>
                <table class="table table-condensed table-striped dataTable" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Código Cliente</th>
                            <th>Nombres</th>
                            <th>Departamento</th>
                            <th>Municipio</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CU2007-00003</td>
                            <td>Gustavo Barragan</td>
                            <td>Colombia - Bogotá</td>
                            <td>Bogotá DC</td>
                            <td>CAlle 71 a # 91A 33</td>
                            <td>3143390071</td>
                            <td>gustavoabarragn@lavenir.com.co</td>
                            <td><button class="btn btn-primary btn-sm selectsuscriptor" data-idcliente="1" >Seleccionar</button></td>
                        </tr> 
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

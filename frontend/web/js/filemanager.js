/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var active_module = 0;
var active_folder = 0;

var foldertemplate = '<div class="col-sm-6 col-lg-4 col-xl-3">';
foldertemplate += '    <div class="media media-folder checkfolder" data-idmodule="{{MODULEID}}" data-idfolder="{{FOLDERID}}">';
foldertemplate += '        <i data-feather="folder"></i>';
foldertemplate += '        <div class="media-body">';
foldertemplate += '             <h6><div class="link-02">{{FOLDERNAME}}</div></h6>';
foldertemplate += '             <span>{{FOLDERSIZE}}</span>';
foldertemplate += '        </div><!-- media-body -->';
foldertemplate += '        <div class="dropdown-file">';
foldertemplate += '             <a href="" class="dropdown-link" data-toggle="dropdown"><i data-feather="more-vertical"></i></a>';
foldertemplate += '             <div class="dropdown-menu dropdown-menu-right">';
foldertemplate += '                 <a href="#modalViewDetails" data-toggle="modal" class="dropdown-item details"><i data-feather="info"></i>Ver Detalles</a>';
foldertemplate += '                 <a href="" class="dropdown-item download"><i data-feather="download"></i>Descargar</a>';
//foldertemplate += '                 <a href="#" class="dropdown-item rename"><i data-feather="edit"></i>Renombrar</a>';
foldertemplate += '                 <a href="#" class="dropdown-item delete"><i data-feather="trash"></i>Borrar</a>';
foldertemplate += '              </div>';
foldertemplate += '         </div><!-- dropdown -->';
foldertemplate += '     </div><!-- media -->';
foldertemplate += '</div><!-- col -->';

var filetemplate = '<div class="col-6 col-sm-4 col-md-3 col-xl">';
filetemplate += '  <div class="card card-file">';
filetemplate += '    <div class="dropdown-file">';
filetemplate += '      <a href="" class="dropdown-link" data-toggle="dropdown"><i data-feather="more-vertical"></i></a>';
filetemplate += '      <div class="dropdown-menu dropdown-menu-right">';
//filetemplate += '        <a href="#modalViewDetails" data-toggle="modal" class="dropdown-item details"><i data-feather="info"></i>Ver Detalles</a>';
filetemplate += '        <a href="{{FILEDOWNLOAD}}" class="dropdown-item download"><i data-feather="download"></i>Descargar</a>';
//filetemplate += '        <a href="#" class="dropdown-item rename"><i data-feather="edit"></i>Renombrar</a>';
filetemplate += '        <a href="#" class="dropdown-item delete"><i data-feather="trash"></i>Eliminar</a>';
filetemplate += '      </div>';
filetemplate += '    </div><!-- dropdown -->';
filetemplate += '    <div class="card-file-thumb {{FILECOLOR}}" style="{{FILESTYLE}}">';
filetemplate += '      <i class="far {{FILEICON}}"></i>';
filetemplate += '    </div>';
filetemplate += '    <div class="card-body">';
filetemplate += '      <h6><a href="{{FILEPREVIEW}}" class="linkpreview link-02">{{FILENAME}}</a></h6>';
filetemplate += '      <p>{{FILETYPE}}</p>';
filetemplate += '      <p>{{FILEDATE}}</p>';
filetemplate += '      <span>{{FILESIZE}}</span>';
filetemplate += '    </div>';
filetemplate += '  </div>';
filetemplate += '</div><!-- col -->';

$(document).ready(function () {

    //add folder
    $("#btnaddfolder").click(function (e) {
        e.preventDefault();
        addFolder();
    });

    //add files
    $("#btnaddfile").click(function (e) {
        e.preventDefault();
        addFile();
    });
    
    // modulos
    $("#navmodulos").find(".nav-link").click(function () {

        $("#navmodulos").find(".nav-link").removeClass('active');
        $(this).addClass('active');

        active_module = $(this).data("idmodule");
        active_folder = 0;
        
        var title = $(this).find("span").html();
        $("#actualfoldertitle").html(title);
        $("#divfoldercontainer").hide();
        $("#foldercontainer").html('');
        $("#divfilecontainer").hide();
        $("#filecontainer").html('');
        
        // add breadcumb
        $(".breadcrumb").html('');
        $(".breadcrumb").append("<li class='breadcrumb-item active' data-idmodule='"+active_module+"' data-idfolder='0'>"+title+"<li>");

        if ($(this).data("susctable") === 1)
        {
            $("#divtablasuscriptores").show();
            $('#divtablasuscriptores').off('click').on('click', 'button.selectsuscriptor', function () {
                var idcliente = $(this).data("idcliente");
                //TODO: get folders client
                getFoldersModule(idcliente);
            });

        } else {
            $("#divtablasuscriptores").hide();
            getFolders();
        }
    });

});

function getFolders() {

    $("#divfoldercontainer").hide();    
    $("#divfilecontainer").hide();
    $("#filecontainer").html('');
    $.ajax({
        type: 'POST',
        traditional: true,
        data: {idmodule: active_module, idfolder: active_folder},
        url: baseurl + '/visor/getfolders'
    }).then(function (result) {
        //console.log(result);
        $("#divfoldercontainer").show();    
        if (result.data.length == 0) {
            $("#foldercontainer").html('<h6>No hay carpetas asociadas a la carpeta</h6>');
        } else {
            var htmlf = "";
            $.each(result.data, function (idx, item) {
                var ctrl = foldertemplate.replace('{{FOLDERNAME}}', item.folderName)
                        .replace('{{FOLDERSIZE}}', item.files + ' archivos, ' + (item.size == null? '0 Bytes':  item.size))
                        .replace('{{MODULEID}}', item.idmodule)
                        .replace('{{FOLDERID}}', item.idfolder);
                htmlf += ctrl;
            });
            //show folder content
            $("#foldercontainer").html(htmlf);
            feather.replace();

            //bind events folder
            $('#divfoldercontainer').off('click').on('click', 'div.link-02', function () {
                active_module = $(this).parent().parent().parent().data("idmodule");
                active_folder = $(this).parent().parent().parent().data("idfolder");
                $("#divfoldercontainer").find(".checkfolder").removeClass('folderactive');
                $(this).addClass('folderactive');
                
                buildbreadcumbs($(this).html());
                getFolders();
                getFilesFolder();
            });
        }
    });
}

function buildbreadcumbs(newitem){
    
    //actuales
    var britems = [];
    if(active_folder> 0){
        $.each($(".breadcrumb-item"), function(idx,item){
            britems.push({ idmodule: $(item).data("idmodule"), idfolder: $(item).data("idfolder"), foldername: $(item).html()});
        });
//        if(britems.length > 1){
//            britems.pop();
//        }
    }
    //nuevos
    $(".breadcrumb").html('');
    for(var i =0; i< britems.length; i++){
        $(".breadcrumb").append("<li class='breadcrumb-item active'><a href='#' data-idmodule='"+britems[i].idmodule+"' data-idfolder='"+britems[i].idfolder+"'>"+britems[i].foldername+"</a></li>");
    }
    $(".breadcrumb").append("<li class='breadcrumb-item active' data-idmodule='"+active_module+"' data-idfolder='"+active_folder+"'>"+newitem+"</li>");

    $('.breadcrumb a').click(function(e){
        e.preventDefault();
        active_module = $(this).data("idmodule");
        active_folder = $(this).data("idfolder");
        var actualmenu = $(this).html();
        $(this).parent().nextAll().remove();
        $(this).parent().remove();
        buildbreadcumbs(actualmenu);
        getFolders();
    });
}

function getFilesFolder() {

    $.ajax({
        type: 'POST',
        traditional: true,
        data: {idfolder: active_folder},
        url: baseurl + '/visor/getfilesfolder'
    }).then(function (result) {
        //console.log(result);
        $("#divfilecontainer").show();
        if (result.data.length == 0) {
            $("#filecontainer").html('<h6>No hay archivos asociados a la carpeta</h6>');
        } else {
            var htmlf = "";
            $.each(result.data, function (idx, item) {

                var ext = item.name.slice((item.name.lastIndexOf(".") - 1 >>> 0) + 2);
                var filestyle = "";
                var fileicon = "fa-file";                
                var color = "tx-teal";
                
                if (ext.indexOf('doc') > -1) {
                    color = "tx-primary";
                    fileicon = "fa-file-word";
                }
                if (ext.indexOf('xls') > -1) {
                    color = "tx-success";
                    fileicon = "fa-file-excel";
                }
                if (ext.indexOf('ppt') > -1) {
                    color = "tx-orange";
                    fileicon = "fa-file-powerpoint";
                }
                if (ext.indexOf('pdf') > -1) {
                    color = "tx-danger";
                    fileicon = "fa-file-pdf";
                }
                if (ext.indexOf('zip') > -1) {
                    color = "tx-warning";
                    fileicon = "fa-file-archive";
                }
                if (ext.indexOf('rar') > -1) {
                    color = "tx-purple";
                    fileicon = "fa-file-archive";
                }

                //console.log(ext);
                if(ext.indexOf('gif') > -1 
                        || ext.indexOf('jpg') > -1
                        || ext.indexOf('jpeg') > -1
                        || ext.indexOf('png') > -1){
                    color = "";
                    fileicon = "";
                    filestyle = 'background-image: url(' + baseurl + '/visor/getfile?id=' + item.iddocument + '&t=true);';                    
                }    
                
                htmlf += filetemplate.replace('{{FILENAME}}', item.name)
                        .replace('{{FILESIZE}}', item.size)
                        .replace('{{FILEICON}}', fileicon)
                        .replace('{{FILETYPE}}', getmimetype(item.name))
                        .replace('{{FILEDATE}}', item.date)
                        .replace('{{FILECOLOR}}', color)
                        .replace('{{FILESTYLE}}', filestyle)
                        .replace('{{FILEDOWNLOAD}}', baseurl + '/visor/getfile?id=' + item.iddocument + '&d=true')
                        .replace('{{FILEPREVIEW}}', '//docs.google.com/gview?url=' + baseurl + '/visor/getfile?id=' + item.iddocument + '&embedded=true');
            
    
            });
            //show files content
            $("#filecontainer").html(htmlf);
            feather.replace();

            //bind events files
            $(".linkpreview").fancybox({
                'width': 600, // or whatever
                'height': 320,
                'type': 'iframe'
            });
        }
    });
}

function addFolder() {
    if (active_module == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Seleccione un módulo',
            text: 'Debe seleccionar un módulo para adicionar una carpeta.'
        });
        return;
    }

    Swal.fire({
        title: 'Nueva carpeta',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Crear',
        showLoaderOnConfirm: true,
        preConfirm: (foldername) => {
           return  $.ajax({
                type: 'POST',
                traditional: true,
                data: {idmodule: active_module, idfolder: active_folder, foldername: foldername},
                url: baseurl + '/visor/createfolder'
            }).then(function (result) {
                //console.log(result);
                if(result.error !== ''){
                    Swal.showValidationMessage(result.error);
                    return false;
                }else{
                    console.log(result);
                    getFolders();
                    return true;
                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                text: 'Carpeta creada correctamente'
            });
        }
    });
}

function addFile(){
    if (active_module == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Seleccione un módulo',
            text: 'Debe seleccionar un módulo para adicionar un archivo.'
        });
        return;
    }
    if (active_folder == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Seleccione una carpeta',
            text: 'Debe seleccionar una carpeta para adicionar un archivo.'
        });
        return;
    }

    $("#divfileupload").fadeIn(1000);
}

Dropzone.autoDiscover = false;

// init dropzone on id (form or div)
$(document).ready(function() {

    var myDropzone = new Dropzone("#myDropzone", {
        url: baseurl + "/visor/upload",
        paramName: "file",
        autoProcessQueue: false,
        uploadMultiple: true, // uplaod files in a single request
        parallelUploads: 100, // use it with uploadMultiple
        maxFilesize: 100, // MB
        maxFiles: 5,
        timeout: 180000,
        //acceptedFiles: ".jpg, .jpeg, .png, .gif, .pdf",
        addRemoveLinks: true,
        // Language Strings
        dictFileTooBig: "Archivo es muy grande ({{filesize}}mb). El máximo permitido es {{maxFilesize}}mb",
        //dictInvalidFileType: "Invalid File Type",
        dictCancelUpload: "Cancelar",
        dictRemoveFile: "Remover",
        dictMaxFilesExceeded: "Solo {{maxFiles}} archivos son permitidos",
        dictDefaultMessage: "Haga click o arrastre los archivos aqui para subirlos",
    });

});

Dropzone.options.myDropzone = {
    // The setting up of the dropzone
    init: function() {
        var myDropzone = this;

        // First change the button to actually tell Dropzone to process the queue.
        $("#dropzoneSubmit").on("click", function(e) {
            // Make sure that the form isn't actually being sent.
            e.preventDefault();
            e.stopPropagation();
            
            if (myDropzone.files != "") {
                myDropzone.processQueue();
            } else {
                $("#myDropzone").submit();
            }

        });

        // on add file
        this.on("addedfile", function(file) {
            // console.log(file);
        });
        // on error
        this.on("error", function(file, response) {
            console.log(response);
        });        
        this.on('sending', function(file, xhr, formData){
            formData.append('active_module', active_module);
            formData.append('active_folder', active_folder);
        });
        // on start
        this.on("sendingmultiple", function(file) {
             // console.log(file);
        });
        // on complete
        this.on("complete", function(file) { 
            this.removeAllFiles(true); 
        });
        // on success
        this.on("successmultiple", function(file) {
            // submit form
            $("#divfileupload").fadeOut(1000);
            getFilesFolder();
            //$("#formupload").submit();
        });
    }
};
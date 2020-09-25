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
foldertemplate += '                 <a href="#" class="dropdown-item rename"><i data-feather="edit"></i>Renombrar</a>';
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
filetemplate += '        <a href="#modalViewDetails" data-toggle="modal" class="dropdown-item details"><i data-feather="info"></i>Ver Detalles</a>';
filetemplate += '        <a href="" class="dropdown-item download"><i data-feather="download"></i>Descargar</a>';
filetemplate += '        <a href="#" class="dropdown-item rename"><i data-feather="edit"></i>Renombrar</a>';
filetemplate += '        <a href="#" class="dropdown-item delete"><i data-feather="trash"></i>Eliminar</a>';
filetemplate += '      </div>';
filetemplate += '    </div><!-- dropdown -->';
filetemplate += '    <div class="card-file-thumb {{FILECOLOR}}">';
filetemplate += '      <i class="far {{FILEICON}}"></i>';
filetemplate += '    </div>';
filetemplate += '    <div class="card-body">';
filetemplate += '      <h6><a href="" class="link-02">{{FILENAME}}</a></h6>';
filetemplate += '      <p>{{FILETYPE}}</p>';
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

                var color = "tx-primary";
                if (item.type.indexOf('excel') > 0) {
                    color = "tx-success";
                }
                if (item.type.indexOf('powerpoint') > 0) {
                    color = "tx-orange";
                }
                if (item.type.indexOf('pdf') > 0) {
                    color = "tx-danger";
                }
                if (item.type.indexOf('image') > 0) {
                    color = "tx-indigo";
                }

                htmlf += filetemplate.replace('{{FILENAME}}', item.name)
                        .replace('{{FILESIZE}}', item.size + ' KB')
                        .replace('{{FILEICON}}', item.type)
                        .replace('{{FILETYPE}}', getmimetype(item.name))
                        .replace('{{FILECOLOR}}', color);
            });
            //show files content
            $("#filecontainer").html(htmlf);
            feather.replace();

            //bind events files
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
}


//uploadas Handles
var Upload = function (file, id) {
    this.file = file;
    this.id = id;
};

Upload.prototype.getType = function () {
    return this.file.type;
};
Upload.prototype.getSize = function () {
    return this.file.size;
};
Upload.prototype.getName = function () {
    return this.file.name;
};
Upload.prototype.doUpload = function (inputresult) {
    var that = this;
    var formData = new FormData();
    $("#progress-wrp").show();

    // add assoc key values, this will be posts values
    formData.append("id", this.id);
    formData.append("file", this.file, this.getName());
    formData.append("upload_file", true);

    $.ajax({
        type: "POST",
        url: "/webapi/UploadFile",
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', that.progressHandling, false);
            }
            return myXhr;
        },
        success: function (result) {
            // your callback here
            $(inputresult).val(result.data.archivo);
            setTimeout(function () {
                $("#progress-wrp").hide();
            }, 500);
        },
        error: function (error) {
            // handle error
        },
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000
    });
};

Upload.prototype.progressHandling = function (event) {
    var percent = 0;
    var position = event.loaded || event.position;
    var total = event.total;
    var progress_bar_id = "#progress-wrp";
    if (event.lengthComputable) {
        percent = Math.ceil(position / total * 100);
    }
    // update progressbars classes so it fits your code
    $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
    $(progress_bar_id + " .status").text(percent + "%");
};
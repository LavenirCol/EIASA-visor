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
//foldertemplate += '                 <a href="" class="dropdown-item download"><i data-feather="download"></i>Descargar</a>';
if(profile < 3 ){
    foldertemplate += '                 <a href="#" class="dropdown-item renamef"><i data-feather="edit"></i>Renombrar</a>';    
    foldertemplate += '                 <a href="#" class="dropdown-item deletef"><i data-feather="trash"></i>Borrar</a>';
}
foldertemplate += '              </div>';
foldertemplate += '         </div><!-- dropdown -->';
foldertemplate += '     </div><!-- media -->';
foldertemplate += '</div><!-- col -->';

var filetemplate = '<div class="col-xs-6 col-sm-4 col-md-3 colfile" data-iddocument="{{FILEID}}">';
filetemplate += '  <div class="card card-file">';
filetemplate += '    <div class="dropdown-file">';
filetemplate += '      <a href="" class="dropdown-link" data-toggle="dropdown"><i data-feather="more-vertical"></i></a>';
filetemplate += '      <div class="dropdown-menu dropdown-menu-right">';
//filetemplate += '        <a href="#modalViewDetails" data-toggle="modal" class="dropdown-item details"><i data-feather="info"></i>Ver Detalles</a>';
filetemplate += '        <a href="{{FILEDOWNLOAD}}" class="dropdown-item download"><i data-feather="download"></i>Descargar</a>';
if(profile < 3 ){
    filetemplate += '        <a href="#" class="dropdown-item rename"><i data-feather="edit"></i>Renombrar</a>';
    filetemplate += '        <a href="#" class="dropdown-item delete"><i data-feather="trash"></i>Eliminar</a>';
}
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

    //search
    $("#btnsearch").click(function (e) {
        e.preventDefault();
        search();
    });

    // modulos
    $("#navmodulos").find(".nav-link").click(function () {

        $("#navmodulos").find(".nav-link").removeClass('active');
        $(this).addClass('active');
        $( ".filemgr-content-body" ).scrollTop(0);

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
        $(".breadcrumb").append("<li class='breadcrumb-item active' data-idmodule='" + active_module + "' data-idfolder='0'>" + title + "<li>");

        if ($(this).data("susctable") === 1)
        {
            $("#divtablasuscriptores").show();
            $("#cardclient").hide();
            $("#lblbusqueda").addClass("d-block");
            $("#lblbusqueda").show();
            $("#dataTableClientes_wrapper").show();
            $(window).trigger('resize');
            
            $('#divtablasuscriptores').off('click').on('click', 'button.selectsuscriptor', function () {
                
                var table = $("#dataTableClientes").DataTable();
                var row;
//                if ($(this).closest('table').hasClass("collapsed")) {
//                    var child = $(this).parents("tr.child");
//                    row = $(child).prevAll(".parent");
//                } else {
                    row = $(this).parents('tr');
                //}

                var d = table.row( row ).data();
                console.log(d);
                for (var i = 1; i<=8; i++){
                    $("#data-"+i).html(d[i]);
                }
                
                $("#cardclient").show();
                $("#lblbusqueda").removeClass("d-block");
                $("#lblbusqueda").hide();
                $("#dataTableClientes_wrapper").hide();
                
                var idcliente = $(this).data("idcliente");
                //get folders client
                active_folder = 0; // siempre en = para buscar en clientes
                getFolders(idcliente);
            });
            
            $("#btnclosecard").off('click').on('click', function(e){
               e.preventDefault();
                $("#cardclient").hide();
                $("#lblbusqueda").addClass("d-block");
                $("#lblbusqueda").show();
                $("#dataTableClientes_wrapper").show();
                
                if(active_module === 1){
                    $("#navmodulos").find(".nav-link:nth-child(1)").trigger("click");
                }
                if(active_module === 2){
                    $("#navmodulos").find(".nav-link:nth-child(2)").trigger("click");
                }
            });

        } else {
            $("#divtablasuscriptores").hide();
            getFolders();
        }
    });

});

function getFolders(idcliente) {
    if(idcliente === undefined){
        idcliente = 0;
    }   
    $("#divfoldercontainer").hide();
    $("#divfilecontainer").hide();
    $("#filecontainer").html('');
    $.ajax({
        type: 'POST',
        traditional: true,
        data: {idmodule: active_module, idfolder: active_folder, idcliente: idcliente},
        url: '/visor/getfolders'
    }).then(function (result) {
        //console.log(result);
        $("#divfoldercontainer").show();
        if (result.data.length == 0) {
            $("#foldercontainer").html('<h6>No hay carpetas asociadas a la carpeta</h6>');
        } else {
            var htmlf = "";
            $.each(result.data, function (idx, item) {
                var foldername ="";
                if(idcliente > 0){
                    if(item.folderName.indexOf('CT') == 0){
                        foldername = "CONTRATO " + item.folderName;
                    }else if(item.folderName.indexOf('PR') == 0){
                        foldername = "DOCUMENTOS SOPORTE " + item.folderName;
                    }else if(item.folderName.indexOf('TS') == 0){
                        foldername = "FORMATO INSTALACIÓN " + item.folderName;
                    }else{
                        foldername = item.folderName;
                    }
                }else{
                    foldername = item.folderName;
                }
                var ctrl = foldertemplate.replace('{{FOLDERNAME}}', foldername)
                        .replace('{{FOLDERSIZE}}', item.files + ' archivos, ' + (item.size === null ? '0 Bytes' : item.size))
                        .replace('{{MODULEID}}', item.idmodule)
                        .replace('{{FOLDERID}}', item.idfolder)
                        .replace('renamef', (Number(item.files) === 0 ? 'renamef' : 'd-none'))
                        .replace('deletef', (Number(item.files) === 0 ? 'deletef' : 'd-none'));
                htmlf += ctrl;
            });
            //show folder content
            $("#foldercontainer").html(htmlf);
            feather.replace();
            $( ".filemgr-content-body" ).scrollTop(0);

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
            
            //renombrar folder
            $(".renamef").off("click").on("click", function (e) {
                e.preventDefault();
                var idfolder = $(this).closest(".checkfolder").data("idfolder");
                renamefolder(idfolder);
            });

            //eliminar folder
            $(".deletef").off("click").on("click", function (e) {
                e.preventDefault();
                var idfolder = $(this).closest(".checkfolder").data("idfolder");
                deletefolder(idfolder);
            });
        }
    });
}

function buildbreadcumbs(newitem) {

    //actuales
    var britems = [];
    if (active_folder > 0) {
        $.each($(".breadcrumb-item"), function (idx, item) {
            britems.push({idmodule: $(item).data("idmodule"), idfolder: $(item).data("idfolder"), foldername: $(item).html()});
        });
//        if(britems.length > 1){
//            britems.pop();
//        }
    }
    //nuevos
    $(".breadcrumb").html('');
    for (var i = 0; i < britems.length; i++) {
        $(".breadcrumb").append("<li class='breadcrumb-item active'><a href='#' data-idmodule='" + britems[i].idmodule + "' data-idfolder='" + britems[i].idfolder + "'>" + britems[i].foldername + "</a></li>");
    }
    $(".breadcrumb").append("<li class='breadcrumb-item active' data-idmodule='" + active_module + "' data-idfolder='" + active_folder + "'>" + newitem + "</li>");

    $('.breadcrumb a').click(function (e) {
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
        url: '/visor/getfilesfolder'
    }).then(function (result) {
        //console.log(result);
        processfiles(result, 0);
    });
}

function processfiles(result, isfolder) {
    $("#divfilecontainer").show();
    if (result.data.length === 0) {
        if (isfolder === 0) {
            $("#filecontainer").html('<h6>No hay archivos asociados a la carpeta</h6>');
        }
        if (isfolder === 1) {
            $("#filecontainer").html('<h6>No se encontraron archivos con el nombre buscado</h6>');
        }
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

            var filepreview = "#";
            //console.log(ext);
            if (ext.indexOf('gif') > -1
                    || ext.indexOf('jpg') > -1
                    || ext.indexOf('jpeg') > -1
                    || ext.indexOf('png') > -1) {
                color = "";
                fileicon = "";
                filestyle = 'background-image: url(/visor/getfile?id=' + item.iddocument + '&t=true); filter: opacity(0.5);';
            }else{
                filepreview = '//docs.google.com/gview?url=/visor/getfile?id=' + item.iddocument + '&embedded=true';
            }

            htmlf += filetemplate.replace('{{FILENAME}}', item.name)
                    .replace('{{FILEID}}', item.iddocument)
                    .replace('{{FILESIZE}}', item.size)
                    .replace('{{FILEICON}}', fileicon)
                    .replace('{{FILETYPE}}', getmimetype(item.name))
                    .replace('{{FILEDATE}}', item.date)
                    .replace('{{FILECOLOR}}', color)
                    .replace('{{FILESTYLE}}', filestyle)
                    .replace('{{FILEDOWNLOAD}}', '/visor/getfile?id=' + item.iddocument + '&d=true')
                    .replace('{{FILEPREVIEW}}', filepreview);


        });
        //show files content
        $("#filecontainer").html(htmlf);
        feather.replace();

        //bind events files
        $(".linkpreview").click(function(e){
            e.preventDefault();
            var dest = $(this).attr('href');
            if(dest === '#'){
                var img = $(this).parent().parent().parent().find(".card-file-thumb").css("background-image").replace('url("','').replace('")','');
                $.fancybox.open({
                    src: img, // the URL of the image
                });                
            }else{
                 $.fancybox.open({
                    'src': dest,
                    'type': 'iframe'
                });
            }
        });

        //renombrar archivo
        $(".rename").off("click").on("click", function (e) {
            e.preventDefault();
            var iddocument = $(this).closest(".colfile").data("iddocument");
            renamefile(iddocument);
        });
        
        //eliminar archivo
        $(".delete").off("click").on("click", function (e) {
            e.preventDefault();
            var iddocument = $(this).closest(".colfile").data("iddocument");
            deletefile(iddocument);
        });

    }
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
                url: '/visor/createfolder'
            }).then(function (result) {
                //console.log(result);
                if (result.error !== '') {
                    Swal.showValidationMessage(result.error);
                    return false;
                } else {
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

function addFile() {
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

function search() {

    var title = 'Búsqueda';
    $("#actualfoldertitle").html(title);
    $("#divfoldercontainer").hide();
    $("#foldercontainer").html('');
    $("#divfilecontainer").hide();
    $("#filecontainer").html('');

    var term = $("#txtsearch").val();

    if (term.length < 3) {
        Swal.fire({
            icon: 'error',
            title: 'Búsqueda archivos',
            text: 'Debe digitar una nombre de archivo para buscar.'
        });
        return;
    }

    $.ajax({
        type: 'POST',
        traditional: true,
        data: {term: term},
        url: '/visor/getfilessearch'
    }).then(function (result) {
        //console.log(result);
        processfiles(result, 1);
    });

}

function renamefolder(idfolder) {
    console.log(idfolder);
    Swal.fire({
        title: 'Renombrar carpeta',
        text: 'Digite el nuevo nombre de carpeta',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Renombrar',
        showLoaderOnConfirm: true,
        preConfirm: (newfoldername) => {
            return  $.ajax({
                type: 'POST',
                traditional: true,
                data: {idmodule: active_module, idfolder: idfolder, newname: newfoldername},
                url: '/visor/renamefolder'
            }).then(function (result) {
                //console.log(result);
                if (result.error !== '') {
                    Swal.showValidationMessage(result.error);
                    return false;
                } else {
                    console.log(result);
                    getFolders();
                    getFilesFolder();
                    return true;
                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {

        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                text: 'Carpeta renombrada correctamente'
            });
        }
    });
}

function deletefolder(idfolder) {
    console.log(idfolder);

    Swal.fire({
        title: 'Está seguro de eliminar esta carpeta?',
        text: "No podrá recuperar el contenido de la carpeta!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar la carpeta!'
    }).then((result) => {
        if (result.isConfirmed) {            
            $.ajax({
                type: 'POST',
                traditional: true,
                data: {idfolder: idfolder},
                url: '/visor/deletefolder'
            }).then(function (resultado) {
                //console.log(result);
                if (resultado.error !== '') {
                    Swal.fire({
                        icon: 'error',
                        text: resultado.error
                    });
                } else {
                    getFolders();
                    //getFilesFolder();
                    Swal.fire({
                        icon: 'success',
                        text: 'Carpeta y su contenido eliminado correctamente'
                    });
                }
            });

        }
    });    
}

function renamefile(iddocument) {
    console.log(iddocument);
    Swal.fire({
        title: 'Renombrar archivo',
        text: 'Digite el nuevo nombre de archivo (sin extensión)',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Renombrar',
        showLoaderOnConfirm: true,
        preConfirm: (newfilename) => {
            return  $.ajax({
                type: 'POST',
                traditional: true,
                data: {idmodule: active_module, idfolder: active_folder, idfile: iddocument, newname: newfilename},
                url: '/visor/renamefile'
            }).then(function (result) {
                //console.log(result);
                if (result.error !== '') {
                    Swal.showValidationMessage(result.error);
                    return false;
                } else {
                    console.log(result);
                    getFilesFolder();
                    return true;
                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {

        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                text: 'Archivo renombrado correctamente'
            });
        }
    });
}

function deletefile(iddocument) {
    console.log(iddocument);

    Swal.fire({
        title: 'Está seguro de eliminar el archivo?',
        text: "No podrá recuperar el archivo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar el archivo!'
    }).then((result) => {
        if (result.isConfirmed) {            
            $.ajax({
                type: 'POST',
                traditional: true,
                data: {idfile: iddocument},
                url: '/visor/deletefile'
            }).then(function (resultado) {
                //console.log(result);
                if (resultado.error !== '') {
                    Swal.fire({
                        icon: 'error',
                        text: resultado.error
                    });
                } else {
                    
                    getFilesFolder();
                    Swal.fire({
                        icon: 'success',
                        text: 'Archivo eliminado correctamente'
                    });
                }
            });

        }
    });    
}

///DROPZONE
Dropzone.autoDiscover = false;

// init dropzone on id (form or div)
$(document).ready(function () {

    var myDropzone = new Dropzone("#myDropzone", {
        url: "/visor/upload",
        paramName: "file",
        autoProcessQueue: false,
        uploadMultiple: true, // uplaod files in a single request
        parallelUploads: 100, // use it with uploadMultiple
        maxFilesize: 1024, // MB
        maxFiles: 2500,
        //chunking: true,
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
    init: function () {
        var myDropzone = this;

        // First change the button to actually tell Dropzone to process the queue.
        $("#dropzoneSubmit").on("click", function (e) {
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
        this.on("addedfile", function (file) {
            // console.log(file);
        });
        // on error
        this.on("error", function (file, response) {
            console.log(response);
        });
        this.on('sending', function (file, xhr, formData) {
            formData.append('active_module', active_module);
            formData.append('active_folder', active_folder);
        });
        // on start
        this.on("sendingmultiple", function (file) {
            // console.log(file);
        });
        // on complete
        this.on("complete", function (file) {
            this.removeAllFiles(true);
        });
        // on success
        this.on("successmultiple", function (file) {
            // submit form
            $("#divfileupload").fadeOut(1000);
            getFilesFolder();
            //$("#formupload").submit();
        });
    }
};
$(document).ready(function(){

    $.extend(true, $.fn.dataTable.defaults, {
        "searching": true,
        "ordering": true,
        "pageLength": 10,
        lengthMenu: [
            [ 10, 25, 50, 100, -1 ],
            [ '10 filas', '25 filas', '50 filas', '100 filas', 'Todo' ]
        ],
        "autoWidth": true,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
            
    $('.dataTable').DataTable({
        'paging': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true,
        'dom': 'Bfrtip',
        'buttons': [{
                extend: 'excel',
                title: (typeof title === 'undefined' ?'Datos Exportados': title)
            },
            {
                extend: 'pdf',
                title: (typeof title === 'undefined' ?'Datos Exportados': title),
                orientation: 'landscape',
                pageSize: 'TABLOID'
            }],        
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        columnDefs: [ {
            className: 'control',
            orderable: false,
            targets:   -1
        } ],
    });    
    
    $('.dataTablec').DataTable({
        'paging': false,
        'searching': true,
        'ordering': true,
        "order": [[ 3, "desc" ]],
        'info': true,
        'autoWidth': true,
        'dom': 'Bfrtip',
        'buttons': [{
                extend: 'excel',
                title: (typeof title === 'undefined' ?'Datos Exportados': title)
            },
            {
                extend: 'pdf',
                title: (typeof title === 'undefined' ?'Datos Exportados': title),
                orientation: 'landscape',
                pageSize: 'TABLOID'
            }],        
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        columnDefs: [ {
            className: 'control',
            orderable: false,
            targets:   -1
        } ]
    });
    
    // Serever side
    
    function makeserverprocessing(datatable, ajaxcall){       
        if($(datatable).length === 0){ return; }
        $(datatable).DataTable({            
            'paging': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            'responsive': true,
            'processing': true,
            'serverSide': true,
            'dom': 'Bfrtip',
            columnDefs: columnDefsDataTable(datatable),
            'buttons': [
                {
                    text: 'Excel',
                    action: function ( e, dt, node, config ) {
                        var url = baseurl + ajaxcall;
                        url = url + '?dptos='+$('#dptos').val();
                        url = url + '&mpios='+$('#mpios').val();
                        url = url + '&materials='+$('#materials').val();
                        url = url + '&factories='+$('#factories').val();
                        url = url + '&models='+$('#models').val();
                        url = url + '&daneCodeFilter='+$('#daneCodeFilter').val();
                        url = url + '&export=csv';
                        window.open(url);
                    }
                },
                {
                    text: 'PDF',
                    action: function ( e, dt, node, config ) {
                        
                        if(datatable === "#dataTableOperaciondetails" || datatable === "#dataTableInstalaciondetails" || datatable === "#dataTableCambiosReemplazos" || datatable === '#dataTablePqrs'){
                            window.alert("Funcion no soportada en sabana de datos")
                        }else{
                            var url = baseurl + ajaxcall;
                            url = url + '?dptos='+$('#dptos').val();
                            url = url + '&mpios='+$('#mpios').val();
                            url = url + '&materials='+$('#materials').val();
                            url = url + '&factories='+$('#factories').val();
                            url = url + '&models='+$('#models').val();
                            url = url + '&daneCodeFilter='+$('#daneCodeFilter').val();
                            url = url + '&export=pdf';
                            window.open(url);
                        }
                    }
                }                
            ],
            "ajax": {
                url: baseurl + ajaxcall,
                type: "post",
                error: function(e)
                {
                   alert('error¨: ' + e);
                },
                data: function(d){
                    d.dptos = $('#dptos').val();
                    d.mpios = $('#mpios').val();
                    d.materials = $('#materials').val();
                    d.factories = $('#factories').val();
                    d.models = $('#models').val();
                    d.daneCodeFilter = $('#daneCodeFilter').val();
                }
            },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) 
            { 
                if(datatable === "#dataTableCambiosReemplazos")
                {
                    $(nRow).children().each(function(index, td)
                    {                        
                        if (index > 36)
                        {                    
                            aData[index] = '<span class="text-primary">' + aData[index] + '</span>';
                        } 
                    }); 
                }
                
                return nRow; 
            }, 
        });        
        $("#btnsearch").click(function(e){            
            $(datatable).DataTable().ajax.reload();
        });
    }

    function columnDefsDataTable(datatable)
    {
        if(datatable === "#dataTableInstalaciondash"){
            return [{className: "dt-body-center", targets: [3,4,5,6]}];
        }
        else if(datatable === "#dataTableOperaciondash")
        {
            return [{className: "dt-body-center", targets: [3,4,5,6,7,8]}];
        }

        return null;
    }

    makeserverprocessing('#dataTableInventarios','/reports/inventariosserver');
    makeserverprocessing('#dataTableInstalacion','/reports/instalacionserver');
    makeserverprocessing('#dataTableInstalaciondash','/reports/instalaciondashserver');
    makeserverprocessing('#dataTableOperaciondetails','/reports/operaciondetailsserver');
    makeserverprocessing('#dataTableOperaciondash','/reports/operaciondashserver');
    makeserverprocessing('#dataTableInstalaciondetails','/reports/instalaciondetailsserver');
    makeserverprocessing('#dataTableCambiosReemplazos','/reports/cambiosreemplazosserver');
    makeserverprocessing('#dataTablePqrs','/reports/pqrsserver');
    makeserverprocessing('#dataTableClientes','/visor/clientsserver');
    makeserverprocessing('#dataTableDetailsTickets','/reports/ticketsseverity');
});
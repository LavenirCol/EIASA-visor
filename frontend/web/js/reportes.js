/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    //table settings
    $.extend(true, $.fn.dataTable.defaults, {
        "searching": true,
        "ordering": true,
        "pageLength": 5,
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
        'dom': 'Bfrtip',
        'buttons': [
            'copy', 'excel', 'print'
        ]
    });
    
    
    // Serever side
    
    function makeserverprocessing(datatable, ajaxcall){
        $(datatable).DataTable({
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'paging': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            responsive: true,
//            responsive: {
//                details: {
//                    type: 'column',
//                    target: -1
//                }
//            },
//            columnDefs: [ {
//                className: 'control',
//                orderable: false,
//                targets:   -1
//            } ],
            'dom': 'Bfrtip',
            'buttons': [
                'copy', 'excel', 'print'
            ],
            "processing": true,
            "serverSide": true,

            "ajax": {
                url: baseurl + ajaxcall,
                type: "post",
                error: function()
                {
                   alert('error');
                }

            }
        });
    }
    
    //inventarios
    makeserverprocessing('#dataTableInventarios','/reports/inventariosserver');
    
    //instalacion
    makeserverprocessing('#dataTableInstalacion','/reports/instalacionserver');
});
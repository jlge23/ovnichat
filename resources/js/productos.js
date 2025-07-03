import './app';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'jquery';
import 'bootstrap';
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';

import Swal from 'sweetalert2'
import '@fortawesome/fontawesome-free/css/all.css';
import { Value } from 'sass';
import Modal from 'bootstrap/js/dist/modal';

$(function() {

    //Lenguaje para Datatables
    var language = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la  columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    };

    //Datatables
    $("table#DT_productos").DataTable({
        "language" : language,
        "responsive":false,
        "scrollX": true,
        "order": [[0, 'desc']],        "id": 1,
        "active": 1,
        "columns" : [
            {"data":"id"},
            {"data":"gtin"},
            {"data":"sku"},
            {"data":"nombre"},
            {"data":"unidad_medida"},
            {"data":"precio_detal"},
            {"data":"marca"},
            {"data":"categoria"},
            {"data":"embalaje"},
            {"data":"proveedor"},
            {"data":"stock_actual"},
            {"data":"image"},
            {"data":"active"},
            {"data":"defaultContent"}
        ],
    });
    //Ajax para modal Información
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //alert($('meta[name="csrf-token"]').attr('content'));
    //alert(import.meta.env.APP_URL);

    var table = $("table#DT_productos").DataTable();
    $(document).on('click', '.ver-detalles', function() {
        const modal = new Modal(document.getElementById('modalDetalles'));
        var fila = $(this).data('id');
        var D = table.row($(this).parents('tr')).data();
        $.getJSON("/productos",{id:fila}, function (data) {
            $.each(data, function(indice, valor) {
                $('h5#modalDetallesLabel').html('Detalles del producto: ' + valor.descripcion + " " + D.marca);
                $('input#descr').val(valor.descripcion);
                $('input#marca').val(D.marca);
                $('input#gtin').val(valor.codigo_gs1);
                $('input#sencamer').val(valor.codigo_sencamer);
                $('input#mpps').val(valor.codigo_sacs_mpps);
                $('input#sku').val(valor.codigo_sku);
                if (valor.importado == 0) {
                    $('input#importado').val("Producto nacional");
                } else {
                    $('input#importado').val("Producto importado");
                }
                $('input#detal').val(valor.precio_detal);
                $('input#mayor').val(valor.precio_mayor);
                $('input#und').val(valor.unidades_por_embalaje);
                $('input#fisico').val(valor.estado_fisico.estado);
                $('input#propiedad').val(valor.propiedad_producto.propiedad);

                $('a#edit').attr("href","/productos/"+fila+"/edit");
                $('img#img').attr("src","storage/images/"+valor.images[0].path);
                modal.show();
                //unidadesMedida
                var tableUnidades= $("table#DT_ProductosUnidades").DataTable({
                    "retrieve": true,
                    "paging": false,
                    "searching": false,
                    "language" : language,
                    "columns" : [
                        {"data":"id"},
                        {"data":"nombre"},
                        {"data":"id","render": function (data, type, row) {
                                return row.pivot.valor+row.simbolo;
                            }
                        },
                        {"data":"pivot.descripcion"}
                    ]
                });
                tableUnidades.rows.add(valor.valores);
                tableUnidades.draw();

                $(document).on('click', '#modalClose', function() {
                    $('form#detalles')[0].reset();
                    tableUnidades.clear();
                    modal.hide();
                });
            });
        });
    });
});



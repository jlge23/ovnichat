import './app';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'jquery';
import 'bootstrap';
import 'datatables.net-bs4';
import 'datatables.net-buttons-bs4';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.html5.js'; // Para exportación a Excel
import 'datatables.net-buttons/js/buttons.print.js'; // Para exportación a PDF
import 'jszip'; // Necesario para la exportación a Excel
import 'pdfmake'; // Necesario para la exportación a PDF
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
pdfMake.vfs = pdfFonts.pdfMake.vfs;

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
    //dolaR bcv
    function USD(){
        //$.getJSON('https://pydolarvenezuela-api.vercel.app/api/v1/dollar?page=bcv', function(data) {
        /* $.getJSON('http://pydolarve.org/api/v1/dollar', function(data) { */
        $.getJSON('/api/proxy-dollar', function(data) {
            var fecha = data.datetime.date + ', ' + data.datetime.time;
            var valorDolar = data.monitors.bcv.price;
            $('div#dolarInfo').html('Fecha: ' + fecha + '<br>Valor del Dólar: ' + valorDolar.toFixed(2));
            $("input#usd").val(null).val(valorDolar.toFixed(2));
            $("img#img").attr("src",data.monitors.bcv.image);
        });
    }
    /* function USD(){
        $.getJSON('http://'+window.location.host+'/api/proxy', function(data) {
            var fecha = data.datetime.date + ', ' + data.datetime.time;
            var valorDolar = data.monitors.usd.price;
            $('div#dolarInfo').html('Fecha: ' + fecha + '<br>Valor del Dólar: ' + valorDolar.toFixed(2));
            $("input#usd").val(null).val(valorDolar.toFixed(2));
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error al obtener datos: ", textStatus, errorThrown);
        });
    } */
    USD();
    $("button#actualizar").on("click",function(){
        USD();
    });


    $("input#calcular").on('change', function() {
        if (this.checked) {
            if($('input#precio_detal').val() != ""){
                var numero1 = $('input#precio_detal').val();
                if($('input#unidades_por_embalaje').val() != ""){
                    var numero2 = $('input#unidades_por_embalaje').val();
                    var resultado = $('input#precio_mayor');
                    var multiplicacion = parseFloat(numero1) * parseInt(numero2);
                    resultado.val(multiplicacion.toFixed(2));
                }else{
                    $('input#unidades_por_embalaje').trigger('focus');;
                    $("input#calcular").prop('checked', false);
                }
            }else{
                $('input#precio_detal').trigger('focus');;
                $("input#calcular").prop('checked', false);
            }
        } else {
            $('input#precio_mayor').val('');
            $("input#calcular").prop('checked', false);
        }
    });
    //bsToUsd
    $("input#bsToUsd").on('change', function() {
        if (this.checked) {
            if($('input#precio_detal').val() != ""){
                var numero1 = $('input#precio_detal').val();
                if($("input#usd").val() != ""){
                    var usd = $("input#usd").val();
                    var division = (numero1 / usd);
                    $('input#precio_detal').val(division.toFixed(2));
                }else{
                    Swal.fire('Error','No se pudo calcular el valor equivalente en dolares de este monto en Bs ','error');
                    $('input#precio_detal').trigger('focus');;
                    $("input#bsToUsd").prop('checked', false);
                }
            }else{
                $('input#precio_detal').trigger('focus');;
                $("input#bsToUsd").prop('checked', false);
            }
        } else {
            $("input#bsToUsd").prop('checked', false);
        }
    });
    //calcular comision
    $("select#comision").on('change', function() {
        if($(this).find(":selected").val() != ''){
            if($('input#precio_detal').val() != ""){
                var numero1 = $('input#precio_detal').val();
                var val = $(this).find(":selected").val();
                /* alert(parseFloat(numero1) + " * " + parseFloat(val) + " + " + parseFloat(numero1)); */
                var multiplicacion = parseFloat(numero1) * parseFloat(val) + parseFloat(numero1);
                $('input#precio_detal').val(multiplicacion.toFixed(2));
            }else{
                $('input#precio_detal').trigger('focus');
            }
        }else{
            $(this).trigger('focus');
        }
    });
    /*
    Swal.fire('¡Hola!'); */
    //Datatables
    $("table#DT_productos").DataTable({
        "language" : language,
        "responsive":false,
        "scrollX": true,
        "order": [[0, 'desc']],
        "columns" : [
            {"data":"id"},
            {"data":"images"},
            {"data":"codigo_gs1"},
            {"data":"descripcion"},
            {"data":"marca"},
            {"data":"precio_detal"},
            {"data":"precio_mayor"},
            {"data":"unidades_por_embalaje"},
            {"data":"valores"},
            {"data":"imagenes"},
            {"data":"defaultContent"}
        ],
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7] // Especifica las columnas que quieres exportar
                }
            },
            {
                extend: 'excel',
                text: 'Exportar a Excel',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7] // Especifica las columnas que quieres exportar
                },
                charset: 'UTF-8'
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7] // Especifica las columnas que quieres exportar
                },
                charset: 'UTF-8'
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape', // Establece la orientación en horizontal
                pageSize: 'LETTER', // Opcional: establece el tamaño de la página
                filename: 'Listado de precios de productos - Torrefactora y Envasadora G&S 1957 C.A',
                title: 'Listado de productos',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7] // Especifica las columnas que quieres exportar
                },
                charset: 'UTF-8',
                customize: function (doc) {
                    doc.content.splice(0, 0, {
                        text: 'Torrefactora y Envasadora G&S 1957 C.A.  J-502565760',
                        style: 'title'
                    });
                    var fechaEmision = new Date();
                    doc.content.splice(1, 0, {
                        text: 'Fecha de Emisión: ' + fechaEmision.toLocaleDateString('es-ES', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        }),
                        style: 'subtitle'
                    });
                    var fechaVencimiento = new Date();
                    fechaVencimiento.setDate(fechaEmision.getDate() + 30);
                    doc.content.splice(2, 0, {
                        text: 'Fecha de Vencimiento: ' + fechaVencimiento.toLocaleDateString('es-ES', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        }),
                        style: 'subtitle'
                    });
                    var CondicionPago = "Contado";
                    doc.content.splice(3, 0, {
                        text: 'Condición de pago: ' + CondicionPago,
                        style: 'subtitle'
                    });
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7] // Especifica las columnas que quieres exportar
                }
            }
        ],
        dom: 'fBrtip', // Define la estructura de la tabla y los botones
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



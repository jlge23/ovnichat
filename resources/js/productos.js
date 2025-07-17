import './app';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'jquery';
import 'bootstrap';
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-fixedcolumns-bs5';



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
        'fixedColumns': {
            'leftColumns': 1 // Esto fijará la primera columna (“Editar”)
        },
        "columns" : [
            {"data":"defaultContent"},
            {"data":"gtin"},
            {"data":"sku"},
            {"data":"producto"},
            {"data":"unidad_medida"},
            {"data":"precio_detal"},
            {"data":"marca"},
            {"data":"categoria"},
            {"data":"embalaje"},
            {"data":"proveedor"},
            {"data":"stock_actual"},
            {"data":"image"},
            {"data":"active"}
        ],
    });
    //Ajax para modal Información
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});



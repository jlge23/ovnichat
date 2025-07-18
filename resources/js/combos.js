import './app';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'jquery';
import 'bootstrap';
import 'datatables.net-bs5';
import Swal from 'sweetalert2'
import '@fortawesome/fontawesome-free/css/all.css';
import Modal from 'bootstrap/js/dist/modal';

$(function(){
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
        "sSearch":         "Buscar producto:",
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

    var table = $("table#DT_combos").DataTable({
        "language" : language,
        "responsive":false,
        "scrollX": true,
        "order": [[0, 'desc']],
        "columns" : [
            {"data":"id"},
            {"data":"combo"},
            {"data":"descripcion"},
            {"data":"precio"},
            {"data":"status",
                render: function (data, type, row) {
                    switch(row.status){
                        case "activo":
                            return "<p class='bg-success text-primary px-2'><b>"+row.status+"</b></p>";
                        break;
                        case "inactivo":
                            return "<p class='bg-warning text-secondary px-2'><b>"+row.status+"</b></p>";
                        break;
                        case "agotado":
                            return "<p class='bg-danger text-danger px-2'><b>"+row.status+"</b></p>";
                        break;
                        default:
                            return "<p class='bg-light text px-2'><b>"+row.status+"</b></p>";
                    }
                }
            },
            {"data":"productos"},
            {"data":"defaultContent"}
        ],
    });
    //Agregar o editar productos del combo
    $(document).on('click', '.ver_productos', function() {
        if ($.fn.DataTable.isDataTable('#DT_ProdAsoc')) {
            $('#DT_ProdAsoc').DataTable().clear().destroy();
        }
        const modal = new Modal(document.getElementById('modalProductos'));
        var D = table.row($(this).parents('tr')).data();
        const url = `/combos/${D.id}/edit`; // url para json de datatables
        const UpdateUrl = `/combos/${D.id}`;// url para el action del formulario
        $('form#FRM_asociar').attr('action', UpdateUrl);
        $('h5#ModalTitle').html(D.producto);
        var DT_ProdAsoc = $("table#DT_ProdAsoc").DataTable({
            "language" : language,
            "responsive":false,
            "scrollX": true,
            "paging": false,
            "searching": true,
            "scrollResize": true,
            "scrollY": '400px',
            "scrollCollapse": true,
            "ajax": {
                "url": url,
                "type": "get"
            },
            "columns" : [
                {"data":"id"},
                {"data":"producto"},
                {"data":"descripcion"},
                {"data":"stock_actual"},
                {"data":"asignado",
                    render: function (data, type, row) {
                        if(row.asignado)
                            return "<input type='checkbox' name='productos["+row.id+"][id]' class='form-check-input check-producto' value="+row.id+" checked>";
                        else
                            return "<input type='checkbox' name='productos["+row.id+"][id]' class='form-check-input check-producto' value="+row.id+">";
                    }
                },
                {"data":"cantidad",
                    render: function (data, type, row) {
                        if(row.cantidad > 0)
                            return "<input class='form-control input-cantidad' type='number' name='productos["+row.id+"][cantidad]' value="+row.cantidad+" max="+row.stock_actual+">";
                        else
                            return "<input class='form-control input-cantidad' type='number' name='productos["+row.id+"][cantidad]' value="+row.stock_actual+" max="+row.stock_actual+">";
                    }
                },
            ],
        });

        //DT_ProdAsoc.ajax.url(url).load();
        $("button.MD_cerrar").on('click', function(){
            DT_ProdAsoc.clear().draw();
            modal.hide();
        });
        $('form#FRM_asociar').on('submit', function (e) {
            e.preventDefault(); // Detiene el envío inmediato
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción enviará el formulario.',
                icon: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#DT_ProdAsoc tbody tr').each(function () {
                        const checkbox = $(this).find('input[type="checkbox"]');
                        const cantidad = $(this).find('input[type="number"]');
                        if (!checkbox.is(':checked')) {
                            cantidad.prop('disabled', true); // evita que se envíe
                        }
                    });
                    this.submit(); // Envío manual si el usuario confirma
                }
            });
        });
        modal.show();
    });

    $('button#btnNuevoCombo').on('click', function () {
        $("form#FRM_NuevoCombo")[0].reset();
        $('div#NuevoCombo').slideToggle(); // Anima el mostrar/ocultar
    });

    setTimeout(function () {
        let alert = document.getElementById('alert-success');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
            alert.style.opacity = 0;
        }
    }, 3000); // 3 segundos

});

$(document).ready(function() {
    var TUsuario = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "25%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

     var $usuario = $('form#frmusuario #usuario');
     var $clave = $('#clave');

    $('#btnaccion').on('click', function() {
        var $perfil = $('select#perfil option').filter(":selected").text();
        var $estatus = $('select#estatus option').filter(":selected").text();

        $('#accion').remove();

        var $accion = ' <input type="hidden" id="accion" name="accion" value="" />';
        $($accion).appendTo($(this));


        var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
        var eliminar  = '<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';


        $('#accion').val('Agregar');
        $.post('controlador/Usuario.php', $('#frmusuario').serialize(), function(data) {

//            var cod_msg = parseInt(data.error_codmensaje);
//            var mensaje = data.error_mensaje;
//            var tipo = data.tipo_error;
//
//            jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');
            TUsuario.fnAddData([$usuario.val(), $clave.val(), $perfil, $estatus, modificar, eliminar]);

            $usuario.val('');
            limpiar();

        }, 'json');
    });


    $('#btnlimpiar').on('click', function() {
        $usuario.val('');
        limpiar();
    });

});

function limpiar() {
    $('#usuario').val('');
    $('#clave').val('');
    $('#repclave').val('');
    $('#accion').remove();
    $('form#frmusuario select#perfil').val(0);
    $('form#frmusuario select#estatus').val(2);
}

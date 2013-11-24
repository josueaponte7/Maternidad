$(document).ready(function() {
    var TUsuario = $('#tabla_usuarios').dataTable({
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

     var $frmusuario   = $('form#frmusuario');
     var $usuario      = $frmusuario.find('input:text#usuario');
     var $div_usuario  = $frmusuario.find('div#div_usuario');
     var $clave        = $frmusuario.find('input:password#clave');
     var $div_clave    = $frmusuario.find('div#div_clave');
     var $repclave     = $frmusuario.find('input:password#repclave');
     var $div_repclave = $frmusuario.find('div#div_repclave');
     var $perfil       = $frmusuario.find('select#perfil');
     var $swestatus    = $frmusuario.find('div#swestatus');
     var $estatus      = $frmusuario.find('input:hidden#estatus');
     var $btnaccion    = $frmusuario.find('input:button#btnaccion');
     var $btnlimpiar   = $frmusuario.find('input:button#btnlimpiar');

    $swestatus.on('switch-change', function(e, data) {
        $estatus.val(data.value);
    });

    $perfil.select2();

    var url = '../../controlador/Usuario.php';
    $btnaccion.on('click', function() {

        if ($usuario.val() === null || $usuario.val().length === 0 || /^\s+$/.test($usuario.val())) {
            $div_usuario.addClass('has-error');
            $usuario.focus();
        }else if($clave.val() === null || $clave.val().length === 0 || /^\s+$/.test($clave.val())){
            $div_clave.addClass('has-error');
            $clave.focus();
        }else if($repclave.val() === null || $repclave.val().length === 0 || /^\s+$/.test($repclave.val())){
            $div_repclave.addClass('has-error');
            $repclave.focus();
        }else if($clave.val() != $repclave.val()){
            $div_clave.addClass('has-error');
            $div_repclave.addClass('has-error');
        }else if ($perfil.val() == 0) {
            $perfil.select2("container").addClass("error_select");
            $('#s2id_perfil a.select2-choice').addClass('error_select');
        }else{

            var f        = new Date();
            var fecha    = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
            var perfil   = $perfil.find(' option').filter(":selected").text();
            var swstatus = $swestatus.bootstrapSwitch('status');
            var estatus  = '';
            if(swstatus == true){
                estatus = 'Activo';
            }else{
               estatus = 'Inactivo';
            }
            $('#accion').remove();

            var $accion = ' <input type="hidden" id="accion" name="accion" value="" />';
            $($accion).appendTo($(this));

            var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
            var eliminar  = '<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';


            $('#accion').val('Agregar');
            $.post(url, $frmusuario.serialize(), function(data) {

                 var cod_msg = parseInt(data.error_codmensaje);
                 var mensaje = data.error_mensaje;
                 var tipo = data.tipo_error;
                 window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                 TUsuario.fnAddData([$usuario.val(), perfil,estatus, fecha,modificar, eliminar]);

                $usuario.val('');
                $swestatus.bootstrapSwitch('setState', true); // true || false
                limpiar();

            }, 'json');
        }

    });
    
     $('table#tabla_usuarios').on('click', 'img.modificar', function() {
         $estatus.val(false);
     })
    $btnlimpiar.on('click', function() {
        $usuario.val('');
        limpiar();
    });

});

function limpiar() {
    $('#usuario').val('');
    $('#clave').val('');
    $('#repclave').val('');
    $('#accion').remove();
    $('form#frmusuario select#perfil').select2('val',0);
    $('div#swestatus').bootstrapSwitch('setState', true);
}
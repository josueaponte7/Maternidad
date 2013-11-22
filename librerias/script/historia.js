$.tools.dateinput.localize("es", {
    months: 'Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Novimbre,Dicimbre',
    shortMonths: 'Ene,Feb,Mar,Abr,May,Jun,Jul,Aog,Sep,Oct,Nov,Dic',
    days: 'Domingo,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado',
    shortDays: 'Dom,Lun,Mar,Mie,Jue,Vie,Sab'
});

$(document).ready(function() {
    var THistoria = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmhistoria = $('form#frmhistoria');
    var $text_nac = $frmhistoria.find('input:text#text_nac');
    var $hnac = $frmhistoria.find('input:hidden#hnac');
    var $historia = $frmhistoria.find('input:text#historia');
    var $hhistoria = $frmhistoria.find('input:hidden#hhistoria');
    var $cedula_p = $frmhistoria.find('input:text#cedula_p');
    var $nombre = $frmhistoria.find('input:text#nombre');
    var $apellido = $frmhistoria.find('input:text#apellido');
    var $fecha = $frmhistoria.find('input:text#fecha_nacimiento');
    var $edad = $frmhistoria.find('input:text#edad');
    var $tamano = $frmhistoria.find('input:text#tamano');
    var $peso = $frmhistoria.find('input:text#peso');
    var $tension = $frmhistoria.find('input:text#tension');
    var $fur = $frmhistoria.find('input:text#fur');
    var $fpp = $frmhistoria.find('input:text#fpp');
    var $lugar_control = $frmhistoria.find('textarea#lugar_control');
    var $diagnostico = $frmhistoria.find('textarea#diagnostico');
    var $observacion = $frmhistoria.find('textarea#observacion');
    var $btnaccion = $frmhistoria.find('input:button#btnaccion');
    var $btnlimpiar = $frmhistoria.find('input:button#btnlimpiar');
    var $btnbuscar = $frmhistoria.find('input:button#btnbuscar');
    var $div_cedula = $frmhistoria.find('div#div_cedula');

    var val_cedula = '1234567890';
    var val_direccion = ' abcdefghijklmnopqrstuvwyzáéíóúñ#/º-';

    $cedula_p.validar(val_cedula);
    $lugar_control.validar(val_direccion);
    $diagnostico.validar(val_direccion);
    $observacion.validar(val_direccion);

    var dateinput = $fur.dateinput({
        lang: 'es',
        format: 'dd/mm/yyyy', // the format displayed for the user
        selectors: true, // whether month/year dropdowns are shown
        min: -270, // min selectable day (100 days backwards)
        max: -1, // max selectable day (100 days onwards)
        offset: [10, 20], // tweak the position of the calendar
        speed: 'fast', // calendar reveal speed
        firstDay: 0
    });

    var dateinput = $fpp.dateinput({
        lang: 'es',
        format: 'dd/mm/yyyy', // the format displayed for the user
        selectors: true, // whether month/year dropdowns are shown
        min: 1, // min selectable day (100 days backwards)
        max: 270, // max selectable day (100 days onwards)
        offset: [10, 20], // tweak the position of the calendar
        speed: 'fast', // calendar reveal speed
        firstDay: 0
    });


    $("ul#nacionalidad > li > span").click(function() {

        var nac = $(this).attr('id');
        if (nac != 'N') {
            $hnac.val(nac);
            $text_nac.val(nac + '-');
            $cedula_p.focus();
        } else {
            $hnac.val('');
            $text_nac.val('');
            $cedula_p.val('');
        }
    });

    var url = '../../controlador/historia.php';
    $btnbuscar.on('click', function() {
        var cedula_p = $cedula_p.val();
        if (cedula_p != '') {
            $.post(url, {cedula_p: cedula_p, accion: 'BuscarDatos'}, function(data) {

                var cod_msg = parseInt(data.error_codmensaje);
                var mensaje = data.error_mensaje;
                //var tipo    = data.tipo_error;
                if (cod_msg === 17) {
                    $nombre.val('');
                    $apellido.val('');
                    $fecha.val('');
                    $edad.val('');
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                        $cedula_p.focus();
                    });
                } else if (cod_msg > 16) {
                    $nombre.val('');
                    $apellido.val('');
                    $fecha.val('');
                    $edad.val('');
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                        $cedula_p.focus();
                    });
                } else {
                    $hhistoria.val(data.his_o);
                    $historia.val(data.historia);
                    $nombre.val(data.nombre);
                    $apellido.val(data.apellido);
                    $fecha.val(data.fecha);
                    $edad.val(data.edad);
                    $tamano.val(data.tamano);
                    $peso.val(data.peso);
                    $tension.val(data.tension);

                    $fpp.val(data.fpp);
                    $fur.val(data.fur);
                    $lugar_control.val(data.lugar_control);
                    $diagnostico.val(data.diagnostico);
                    $observacion.val(data.observacion_medica);

                    $btnaccion.prop('disabled', false);
                }
            }, 'json');
        } else {
            window.parent.apprise('Debe ingresar el Num&eacute;ro de C&eacute;dula', {'textOk': 'Aceptar'}, function() {
                $div_cedula.addClass('has-error');
                $cedula_p.focus();
            });
        }
    });



    $btnaccion.click(function() {
        $('#accion').remove();
        var accion = $(this).val();
        var historia = $historia.val();


        var $accion = ' <input type="hidden" id="accion" name="accion" value="' + accion + '" />';

        $($accion).prependTo($(this));
        $($hhistoria).prependTo($(this));
        $.post(url, $frmhistoria.serialize(), function(data) {
            var cod_msg = parseInt(data.error_codmensaje);
            var mensaje = data.error_mensaje;
            var tipo = data.tipo_error;
            limpiar();
            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
        }, 'json');
    });

    $btnlimpiar.click(function() {
        limpiar();
    });
});

function limpiar()
{
    var $frmhistoria = $('form#frmhistoria');
    $('input:text#text_nac').val('')
    $('input:hidden#hnac');
    $('input:text#historia').val('');
    $('input:hidden#hhistoria');
    $('input:text#cedula_p').val('');
    $('input:text#nombre').val('');
    $('input:text#apellido').val('');
    $('input:text#fecha_nacimiento').val('');
    $('input:text#edad');
    $('input:text#tamano').val('');
    $('input:text#peso').val('');
    $('input:text#tension').val('');
    $('input:text#fur').val('');
    $('input:text#fpp').val('');
    $('textarea#lugar_control').val('');
    $('textarea#diagnostico').val('');
    $('textarea#observacion').val('');
    $('#accion').remove();
    $('#fila').remove();
}
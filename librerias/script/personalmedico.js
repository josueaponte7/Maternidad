$(document).ready(function() {
    var TMedico = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "10%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });


    var $frmpersonalmedico = $('form#frmpersonalmedico');
    var $text_nac = $frmpersonalmedico.find('input:text#text_nac');
    var $hnac = $frmpersonalmedico.find('input:hidden#hnac');
    var $div_cedula = $frmpersonalmedico.find('div#div_cedula');
    var $cedula_pm = $frmpersonalmedico.find('input:text#cedula_pm');
    var $div_nombre = $frmpersonalmedico.find('div#div_nombre');
    var $nombre = $frmpersonalmedico.find('input:text#nombre');
    var $div_apellido = $frmpersonalmedico.find('div#div_apellido');
    var $apellido = $frmpersonalmedico.find('input:text#apellido');
    var $div_telefono = $frmpersonalmedico.find('div#div_telefono');
    var $hcod_telefono = $frmpersonalmedico.find('input:hidden#hcod_telefono');
    var $cod_telefono = $frmpersonalmedico.find('input:text#cod_telefono');
    var $telefono = $frmpersonalmedico.find('input:text#telefono');
    var $div_direccion = $frmpersonalmedico.find('div#div_direccion');
    var $direccion = $frmpersonalmedico.find('textarea#direccion');
    var $especialidad = $frmpersonalmedico.find('select#cod_esp');
    var $consultorio = $frmpersonalmedico.find('select#num_cons');
    var $turno = $frmpersonalmedico.find('select#turno');
    var $btnaccion = $frmpersonalmedico.find('input:button#btnaccion');
    var $btn_nac = $frmpersonalmedico.find('button:button#btn_nac');
    var $btn_codlocal = $frmpersonalmedico.find('button:button#btn_codlocal');

    $especialidad.select2();
    $consultorio.select2();
    $turno.select2();

    // caracteres permitidos para la caja de texto sector cuando escriba
    var val_cedula = '1234567890';
    var val_letra = ' abcdefghijklmnopqrstuvwxyzáéíóúñ';
    var val_direccion = ' abcdefghijklmnopqrstuvwxyzáéíóúñ#/º-1234567890';

    // validación de la caja de texto sector mientras este escribiendo
    $cedula_pm.validar(val_cedula);
    $nombre.validar(val_letra);
    $apellido.validar(val_letra);
    $telefono.validar(val_cedula);
    $direccion.validar(val_direccion);

    $('#imgcedula').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>La C&eacute;dula no puede estar en <span class="alerta">blanco</span>,<br/> no debe tener menos de <span class="alerta">7 digitos</span>, ni comenzar con 0,<br/> debe seleccionar la nacionalidad en<span class="alerta"> (N)</span>'
    });

    $('#imgnombre').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>El Nombre no debe estar en <span class="alerta">blanco</span>,<br/> solo acepta<span class="alerta"> letras</span>'
    });

    $('#imgapellido').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>El Apellido no debe estar en <span class="alerta">blanco</span>,<br/> solo acepta<span class="alerta"> letras</span>'
    });

    $('#imgtelefono').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>El Tel&eacute;fono no puede estar en <span class="alerta">blanco</span>,<br/>no debe tener menos de <span class="alerta">9 digitos</span> ,<br/>debe seleccionar el C&oacute;digo <span class="alerta">(Cod)</span>'
    });

    $('#imgdireccion').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>La Direcci&oacute;n no debe estar en <span class="alerta">blanco</span><br/> caracteres permitidos<span class="alerta"> #/º-</span><br/>no debe tener menos de <span class="alerta">10 caracteres</span> '
    });

    $('#imgespecialidad').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido '
    });

    $('#imgconsutorio').tooltip({
        html: true,
        placement: 'left',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido '
    });
    $('#imgturno').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>Este campo es requerido '
    });

    var url = '../../controlador/medicos/personalmedico.php';

    $("ul#nacionalidad > li > span").click(function() {
        var nac = $(this).attr('id');
        $cedula_pm.val('');
        if (nac != 'N') {
            $btn_nac.removeClass('btn-danger');
            if (nac == 'V') {
                var tamano = 8;
            } else {
                tamano = 9;
            }
            $hnac.val(nac);
            $text_nac.val(nac + '-');
            $cedula_pm.attr('maxlength', tamano).focus();
        } else {
            $hnac.val('');
            $text_nac.val('');
        }
    });

    $("ul#cod_local > li > span").click(function() {

        var cod_local = $(this).text();
        var id = $(this).attr('id');
        if (cod_local != 'Cod') {
            $btn_codlocal.removeClass('btn-danger');
            $hcod_telefono.val(id);
            $cod_telefono.val(cod_local + '-');
            $telefono.focus();
        } else {
            $hcod_telefono.val('');
            $cod_telefono.val('');
            $telefono.val('');
        }
    });

    $especialidad.on('change', function() {
        var $cod_esp = $(this).val();
        if ($cod_esp > 0) {
            $consultorio.find('option:gt(0)').remove().end();
            $.post(url, {cod_esp: $cod_esp, accion: 'BuscarCons'}, function(data) {
                var option = "";
                if (data != 0) {
                    $.each(data, function(i) {
                        option += "<option value=" + data[i].num_consultorio + ">" + data[i].consultorio + "</option>";
                    });
                    $consultorio.append(option);
                } else {
                    $consultorio.find('option:gt(0)').remove().end();
                    $turno.find('option:gt(0)').remove().end();
                    $consultorio.select2('val', 0);
                    $turno.select2('val', 0);
                }
            }, 'json');
        } else {
            $consultorio.find('option:gt(0)').remove().end();
            $turno.find('option:gt(0)').remove().end();
            $consultorio.select2('val', 0);
            $turno.select2('val', 0);
        }
    });

    $consultorio.on('change', function() {
        var $num_cons = $(this).val();
        if ($num_cons > 0) {
            $turno.find('option:gt(0)').remove().end();
            $.post(url, {num_cons: $num_cons, accion: 'BuscarTur'}, function(data) {
                var option = "";
                if (data != 0) {
                    $.each(data, function(i) {
                        option += "<option value=" + data[i].cod_turno + ">" + data[i].turno + "</option>";
                    });
                    $turno.append(option);
                } else {
                    $turno.find('option:gt(0)').remove().end();
                    $turno.select2('val', 0);
                }
            }, 'json');
        } else {
            $turno.find('option:gt(0)').remove().end();
            $turno.select2('val', 0);
        }
    });

    var p_cedula = /^[0-9]{7,9}$/;
    var p_telefono = /^[0-9]{7}$/;
    var p_direccion = /^[a-zA-Z0-9áéíóúñÁÉÍÓÚÑ#º\-\s\/]{10,150}$/;
    $btnaccion.on('click', function() {

        $('#accion').remove();

        var accion = $(this).val();
        var $accion = ' <input type="hidden" id="accion" name="accion" value="" />';
        $($accion).prependTo($(this));

        var especialidad = $especialidad.find('option').filter(":selected").text();
        var consultorio = $consultorio.find('option').filter(":selected").text();
        var turno = $turno.find('option').filter(":selected").text();

        if ($text_nac.val() == '') {
            $btn_nac.addClass('btn-danger');
            $btn_nac.focus();
        } else if ($cedula_pm.val() === null || $cedula_pm.val().length === 0 || /^\s+$/.test($cedula_pm.val())) {
            $div_cedula.addClass('has-error');
            $cedula_pm.focus();
        } else if (!p_cedula.test($cedula_pm.val())) {
            $div_cedula.addClass('has-error');
            $cedula_pm.focus();
        } else if ($nombre.val() === null || $nombre.val().length === 0 || /^\s+$/.test($nombre.val())) {
            $div_nombre.addClass('has-error');
            $nombre.focus();
        } else if ($apellido.val() === null || $apellido.val().length === 0 || /^\s+$/.test($apellido.val())) {
            $div_apellido.addClass('has-error');
            $apellido.focus();
        } else if ($cod_telefono.val() == '') {
            $btn_codlocal.addClass('btn-danger');
        } else if ($telefono.val() === null || $telefono.val().length === 0 || /^\s+$/.test($telefono.val())) {
            $div_telefono.addClass('has-error');
            $telefono.focus();
        } else if (!p_telefono.test($telefono.val())) {
            $div_telefono.addClass('has-error');
            $telefono.focus();
        } else if ($direccion.val() === null || $direccion.val().length === 0 || /^\s+$/.test($direccion.val())) {
            $div_direccion.addClass('has-error');
            $direccion.focus();
        } else if (!p_direccion.test($direccion.val())) {
            $div_direccion.addClass('has-error');
            $direccion.focus();
        } else if ($especialidad.val() == 0) {
            $especialidad.addClass("has-error");
        } else if ($consultorio.val() == 0) {
            $consultorio.addClass("has-error");
        } else if ($turno.val() == 0) {
            $turno.addClass("has-error");
        } else {
            if (accion === 'Agregar') {
                $('#accion').val('Agregar');
                $.post(url, $frmpersonalmedico.serialize(), function(data) {
                    var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg == 21) {
                        TMedico.fnAddData([
                            $text_nac.val() + '' + $cedula_pm.val(),
                            $nombre.val() + ' ' + $apellido.val(),
                            especialidad,
                            consultorio,
                            turno,
                            modificar
                        ]);
                        limpiar();
                    }

                }, 'json');
            } else {
                $('#accion').val('Modificar');

                window.parent.apprise('&iquest;Desea Modificar los datos del registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                        $('#hcedula_pm').remove();
                        var cedula = $cedula_pm.val();

                        var nac = $text_nac.val();
                        var nac = nac.substring(0, nac.length - 1);
                        $hnac.val(nac);

                        var $fila = '<input type="hidden" id="hcedula_pm"  value="' + cedula + '" name="hcedula_pm">';
                        $($fila).appendTo('#btnaccion');
                        $.post(url, $frmpersonalmedico.serialize(), function(data) {

                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;
                            var tipo = data.tipo_error;

                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});

                            var fila = $('#fila').val();

                            $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(2)").text(especialidad);
                            $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(3)").text(consultorio);
                            $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(4)").text(turno);
                            limpiar();
                        }, 'json');
                    }

                });
            }
        }
    });

    $('table#tabla').on('click', 'img.modificar', function() {

        limpiar();
        var padre = $(this).closest('tr');
        var fila = $(this).closest('tr').index();
        var cedula_completa = padre.children('td:eq(0)').text();

        var datos = cedula_completa.split('-');
        var nacionalidad = datos[0];
        var cedula_pm = datos[1];
        $btn_nac.prop('disabled', true);
        $text_nac.prop('disabled', true);
        $text_nac.val(nacionalidad + '-');

        $cedula_pm.prop('disabled', true);
        $cedula_pm.val(cedula_pm);

        $('#fila').remove();
        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).appendTo('#btnaccion');
        $.post(url, {cedula_pm: cedula_pm, accion: 'BuscarDatos'}, function(data) {

            $nombre.val(data.nombre);
            $nombre.prop('disabled', true);
            $apellido.val(data.apellido);
            $apellido.prop('disabled', true);
            $cod_telefono.val('0' + data.codigo + '-');
            $telefono.val(data.telefono);
            $direccion.val(data.direccion);
            $direccion.val(data.direccion);
            $hcod_telefono.val(data.cod_telefono);
            $especialidad.select2('val', data.cod_especialidad);
            var num_cons = parseInt(data.num_consultorio);
            var cod_turno = parseInt(data.cod_turno);
            $consultorio.find('option:gt(0)').remove().end();
            $.post(url, {cod_esp: data.cod_especialidad, accion: 'BuscarCons'}, function(datos) {
                var option = "";
                $.each(datos, function(i, obj) {
                    option += "<option value=" + obj.num_consultorio + ">" + obj.consultorio + "</option>";
                });
                $consultorio.append(option);
                $consultorio.select2('val', num_cons);
            }, 'json');

            $turno.find('option:gt(0)').remove().end();
            $.post(url, {num_cons: num_cons, accion: 'BuscarTur'}, function(dates) {
                var option = "";
                $.each(dates, function(i, obj1) {
                    option += "<option value=" + obj1.cod_turno + ">" + obj1.turno + "</option>";
                });
                $turno.append(option);
                $turno.select2("val", cod_turno);
            }, 'json');


        }, 'json');
        $('#btnaccion').val('Modificar');

    });
    $('#btnlimpiar').on('click', function() {
        limpiar();
    });
});


function limpiar() {

    $('div,select').removeClass('has-error');
    $('input:text,textarea').val('');
    $('#btn_nac,#btn_codlocal').prop('disabled', false).removeClass('btn-danger');
    $('#cedula_pm,#nombre,#apellido,#txt_nac').prop('disabled', false);
    $('#accion').remove();
    $('select').select2('val', 0);
    $('select').select2('val', 0);
    $('select#num_cons').find('option:gt(0)').remove().end();
    $('select#turno').find('option:gt(0)').remove().end();
    $('#btnaccion').val('Agregar');
}


$(document).ready(function() {
    var TMedico = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "10%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });


    var $select_especialidad = $('form#frmpersonalmedico select#cod_esp');
    var $select_consultorio = $('form#frmpersonalmedico select#num_cons');
    var $select_turno = $('form#frmpersonalmedico select#turno');

    var url = 'controlador/medicos/personalmedico.php';

    $select_especialidad.on('change', function() {
        var $cod_esp = $(this).val();
        if ($cod_esp > 0) {
            $select_consultorio.find('option:gt(0)').remove().end();
            $.post(url, {cod_esp: $cod_esp, accion: 'BuscarCons'}, function(data) {
                var option = "";
                $.each(data, function(i) {
                    option += "<option value=" + data[i].num_consultorio + ">" + data[i].consultorio + "</option>";
                });
                $select_consultorio.append(option);
            }, 'json');
        }
    });

    $select_consultorio.on('change', function() {
        var $num_cons = $(this).val();
        if ($num_cons > 0) {
            $select_turno.find('option:gt(0)').remove().end();
            $.post(url, {num_cons: $num_cons, accion: 'BuscarTur'}, function(data) {
                var option = "";
                $.each(data, function(i) {
                    option += "<option value=" + data[i].cod_turno + ">" + data[i].turno + "</option>";
                });
                $select_turno.append(option);
            }, 'json');
        }
    });


    var $cedula_pm = $('#cedula_pm');
    var $nombre = $('#nombre');
    var $apellido = $('#apellido');
    var $telefono = $('#telefono');
    var $direccion = $('#direccion');

    $cedula_pm.numeric();
    $nombre.alpha({allow: " "});
    $apellido.alpha({allow: " "});
    $telefono.numeric();
    $direccion.alpha({allow: " "});

    var $personalmedico = $('form#frmpersonalmedico #sector');


    $('#btnaccion').on('click', function() {
        var $especialidad     = $('select#cod_esp option').filter(":selected").text();
        var $num_consultorio  = $('select#num_cons option').filter(":selected").text();
        var $turno            = $('select#turno option').filter(":selected").text();

        $('#accion').remove();
        var accion = $(this).val();
        var $accion = ' <input type="hidden" id="accion" name="accion" value="" />';
        $($accion).prependTo($(this));
//        if (requerido($cedula_pm) === false) {
//            $('.error').fadeOut();
//            var msgerror = 'Campo obligatorio';
//            msgError($cedula_pm, msgerror);
//        } else if (validacion($cedula_pm, 'cedula') === false) {
//            var msgerror = 'El  Dato es Incorrecto';
//            msgError($cedula_pm, msgerror);
//        }

        var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';

        if (accion === 'Agregar') {
            $('#accion').val('Agregar');
            $.post(url, $('#frmpersonalmedico').serialize(), function(data) {

                var cod_msg = parseInt(data.error_codmensaje);
                var mensaje = data.error_mensaje;
                var tipo = data.tipo_error;

                jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');
                TMedico.fnAddData([$cedula_pm.val(), $nombre.val() + ' ' + $apellido.val(), $especialidad, $num_consultorio, $turno, modificar]);

                limpiar();



            }, 'json');
        } else {
            $('#accion').val('Modificar');

            jConfirm('¿Desea Modificar los Datos?', 'Confirmación', function(respuesta) {
                if (respuesta === true) {
                    $.post(url, $('#frmpersonalmedico').serialize(), function(data) {

                        var cod_msg = parseInt(data.error_codmensaje);
                        var mensaje = data.error_mensaje;
                        var tipo = data.tipo_error;

                        jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');
                        var fila = $('#fila').val();

                        /*$("#tabla tbody tr:eq(" + fila + ")").find("td:eq(1)").text($nombre.val() + ' ' + $apellido.val());
                        $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(2)").text($select_especialidad.text());
                        $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(3)").text($select_consultorio.text());
                        $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(4)").text($select_turno.text());*/
                        TMedico.fnUpdate($nombre.val() + ' ' + $apellido.val(), parseInt(fila), 1);
                        TMedico.fnUpdate($especialidad, parseInt(fila), 2);
                        TMedico.fnUpdate($num_consultorio, parseInt(fila), 3);
                        TMedico.fnUpdate($turno, parseInt(fila), 4);
                         
                        limpiar();
                        $('#fila').remove();
                        $('#accion').remove();
                        $('#btnaccion').val('Agregar');


                    }, 'json');
                }

            });
        }
    });


    $('table#tabla').on('click', 'img.modificar', function() {

        var fila = $(this).parent("td").parent().parent().children().index($(this).parent("td").parent());
        var cedula_pm = $(this).parents('tr').children('td:eq(0)').text();

        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).appendTo('#btnaccion');
        $.post(url, {cedula_pm: cedula_pm, accion: 'BuscarDatos'}, function(data) {

            $cedula_pm.val(cedula_pm);
            $nombre.val(data.nombre);
            $apellido.val(data.apellido);
            $telefono.val(data.telefono);
            $direccion.val(data.direccion);
            $direccion.val(data.direccion);
            $select_especialidad.val(data.cod_especialidad);

            var $num_cons = parseInt(data.num_consultorio);
            var $cod_turno = parseInt(data.num_consultorio);
            //$select_consultorio.find('option:gt(0)').remove().end();
            $.post(url, {cod_esp: data.cod_especialidad, accion: 'BuscarCons'}, function(data) {
                var option = "";
                $.each(data, function(i) {
                    option += "<option value=" + data[i].num_consultorio + ">" + data[i].consultorio + "</option>";
                });

                $select_consultorio.append(option);
                $select_consultorio.find('option[value=' + $num_cons + ']').attr("selected", "selected");
            }, 'json');


            $.post(url, {num_cons: $num_cons, accion: 'BuscarTur'}, function(data) {
                var option = "";
                $.each(data, function(i) {
                    option += "<option value=" + data[i].cod_turno + ">" + data[i].turno + "</option>";
                });
                $select_turno.append(option);
                $select_turno.find('option[value=' + $cod_turno + ']').attr("selected", "selected");
            }, 'json');

        }, 'json');


        $cedula_pm.val(cedula_pm);

        $('#btnaccion').val('Modificar');

    });
    $('#btnlimpiar').on('click', function() {
        limpiar();
    });
});





function limpiar() {
    $('#cedula_pm').val('');
    $('#nombre').val('');
    $('#apellido').val('');
    $('#telefono').val('');
    $('#direccion').val('');
    $('#accion').remove();
    $('form#frmpersonalmedico select#cod_esp').val(0);
    $('form#frmpersonalmedico select#num_cons').find('option:gt(0)').remove().end();
    $('form#frmpersonalmedico select#turno').find('option:gt(0)').remove().end();
}


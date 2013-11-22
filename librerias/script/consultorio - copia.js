$(document).ready(function() {
    var TConsultorio = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
        {"sClass": "center", "sWidth": "20%"},
        {"sClass": "center", "sWidth": "20%"},
        {"sClass": "center", "sWidth": "10%"},
        {"sClass": "center", "sWidth": "15%"},
        {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $forconsultorio  = $('form#frmconsultorio');
    var $consultorio     = $forconsultorio.find('input:text#consultorio');
    var $select_espe     = $forconsultorio.find('select#especialidad');
    var $select_turno    = $forconsultorio.find('select#turno');
    var $select_desde    = $forconsultorio.find('select#desde');
    var $select_hasta    = $forconsultorio.find('select#hasta');
    var $div_consultorio = $forconsultorio.find('div#div_consultorio');
    var $btnaccion       = $forconsultorio.find('input:button#btnaccion');

    $select_espe.select2();
    $select_turno.select2();
    $select_desde.select2();
    $select_hasta.select2();

    var letra = ' abcdefghijklmnñopqrstuvwxyzáéíóú#123456789/º,.';

    $consultorio.validar(letra);
    var url = '../../controlador/Mantenimiento/consultorio.php';

    $select_turno.on('change', function() {
        var cod_turno = $(this).val();
        $select_desde.find('option:gt(0)').remove().end();
        $select_desde.select2('val',0);

        $select_hasta.find('option:gt(0)').remove().end();
        $select_hasta.select2('val',0);

        if(cod_turno > 0){
            $.post(url,{cod_turno:cod_turno,accion:'Horario'}, function(data) {
                var option = "";
                if (data != 0) {
                    $.each(data, function(i, obj) {
                        option += "<option value=" + obj.codigo + ">" + obj.descripcion + "</option>";
                    });
                    $select_desde.append(option);
                } else {
                    $select_desde.find('option:gt(0)').remove().end();
                }
            },'json');
        }
    });

    $select_desde.on('change', function() {
        var cod_hora  = $(this).val();
        var cod_turno = $select_turno.find('option').filter(':selected').val();
        $select_hasta.find('option:gt(0)').remove().end();
        $select_hasta.select2('val',0);

        if(cod_hora > 0){
            $.post(url,{cod_turno:cod_turno,cod_hora:cod_hora,accion:'Horario'}, function(data) {
                var option = "";
                if (data != 0) {
                    $.each(data, function(i, obj) {
                        option += "<option value=" + obj.codigo + ">" + obj.descripcion + "</option>";
                    });
                    $select_hasta.append(option);
                } else {
                    $select_hasta.find('option:gt(0)').remove().end();
                }
            },'json');
        }
    });

    // evento click en btaaccion

    var letras = /^[0-9a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\sº#\/,.]{5,20}$/;

    $btnaccion.on('click', function() {

        $('#accion').remove();
        var $accion = ' <input type="hidden" id="accion" name="accion" value="" />';
        $($accion).prependTo($(this));

        var accion = $(this).val();

        var $especilaidad = $select_espe.find('option').filter(':selected').text();
        var $turno        = $select_turno.find('option').filter(':selected').text();
        var $desde        = $select_desde.find('option').filter(':selected').text();
        var $hasta        = $select_hasta.find('option').filter(':selected').text();
        var horario       = $desde + ' a ' + $hasta;

        if ($consultorio.val() === null || $consultorio.val().length === 0 || /^\s+$/.test($consultorio.val())) {
            $div_consultorio.addClass('has-error');
            $consultorio.focus();
        } else if (!letras.test($consultorio.val())) {
            $div_consultorio.addClass('has-error');
            $consultorio.focus();
        }else if($select_espe.val() == 0){
            $select_espe.select2("container").addClass("error_select");
            $('#s2id_especialidad a.select2-choice').addClass('error_select');
        }else if($select_turno.val() == 0){
            $select_espe.select2("container").removeClass("error_select");
            $('#s2id_especialidad a.select2-choice').removeClass('error_select');

            $select_turno.select2("container").addClass("error_select");
            $('#s2id_turno a.select2-choice').addClass('error_select');
        }else if($select_desde.val() == 0){
            $select_turno.select2("container").removeClass("error_select");
            $('#s2id_turno a.select2-choice').removeClass('error_select');

            $select_desde.select2("container").addClass("error_select");
            $('#s2id_desde a.select2-choice').addClass('error_select');
        }else if($select_hasta.val() == 0){
            $select_desde.select2("container").removeClass("error_select");
            $('#s2id_desde a.select2-choice').removeClass('error_select');

            $select_hasta.select2("container").addClass("error_select");
            $('#s2id_hasta a.select2-choice').addClass('error_select');
        }else {
            $select_hasta.select2("container").removeClass("error_select");
            $('#s2id_hasta a.select2-choice').removeClass('error_select');
            if (accion === 'Agregar') {

                $('#accion').val(accion);
                var codigo = TConsultorio.fnGetData().length;
                codigo = codigo + 1;
                var modificar = '<img id="'+codigo+'" class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';

                $.post(url, $forconsultorio.serialize(), function(data) {

                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});

                    if (cod_msg === 21) {
                        TConsultorio.fnAddData([$consultorio.val(), $especilaidad, $turno, horario, modificar]);
                        limpiar();
                    }

                }, 'json');
            }else{
                $('#accion').val('Modificar');

                    $.post(url, $frmconsultorio.serialize(), function(data) {

                        var cod_msg = parseInt(data.error_codmensaje);
                        var mensaje = data.error_mensaje;
                        var tipo = data.tipo_error;

                        jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');
                        var fila = $("#fila").val();

                        if (cod_msg === 22) {

                            TConsultorio.fnUpdate(especilaidad.text(), parseInt(fila), 1);
                            TConsultorio.fnUpdate(turno.text(), parseInt(fila), 2);
                            TConsultorio.fnUpdate(horario, parseInt(fila), 3);

                            limpiar();
                        }

                    }, 'json');
                }

            }
    });

    var $tabla = $('table#tabla');
    $tabla.on('click', 'img.modificar', function() {
        $('#fila').remove();
        $('#num_consultorio').remove();
        $('#accion').remove();

        var $padre       = $(this).closest('tr');
        var fila         = $padre.index();
        var codigo_cons  = $padre.attr('id');
        var especialidad = $padre.children('td').eq(1).text();
        var consultorio  = $padre.children('td').eq(2).text();
        var turno        = $padre.children('td').eq(3).text();

        $consultorio.val(consultorio);

            /***var fila = $(this).parent("td").parent().parent().children().index($(this).parent("td").parent());
            var num_consultorio = $(this).parents('tr').children('td:eq(0)').attr('id');

            var $num_consultorio = '<input type="hidden" id="num_consultorio"  value="' + num_consultorio + '" name="num_consultorio">';
            $($num_consultorio).appendTo($btaccion);

            var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
            $($fila).appendTo($btaccion);

            $.post(url, {num_consultorio: num_consultorio, accion: 'BuscarDatos'}, function(data) {

                $consultorio.val(data.consultorio);
                $select_espe.val(data.cod_especialidad);
                $select_turno.val(data.cod_turno);

                var $turno = parseInt(data.cod_turno);


                if ($turno === 1) {
                    $select_desde.find('option:gt(0)').filter(':lt(' + medio_dia + ')').show();
                    $select_desde.find('option:gt(' + $medio_dia + ')').hide();
                } /*else if ($turno === 2) {
                 $select_desde.find('option:gt(0)').filter(':lt(' + medio_dia + ')').hide();
                 $select_desde.find('option:gt(' + $medio_dia + ')').show();
                 $ultimo.hide();
                 } else {
                 $select_desde.val(0);
                 $select_hasta.find('option').filter(':gt(0)').remove().end();
                 $select_desde.find('option:gt(0)').hide();
                 }

                $select_desde.val(data.desde);


                var $des = parseInt(data.desde);

                /*$select_hasta.find('option').filter(':gt(0)').remove().end();
                 
                 $select_desde.find('option:gt(' + $des + ')').clone().appendTo($select_hasta);
                 
                 $select_hasta.val(data.hasta);
                 
                 $select_hasta.find('option:last').css({'display': 'block'});          
                 
                 $consultorio.attr({'disabled':true});
                 $btaccion.val('Modificar');

         }, 'json');**/

    });

    var $btlimpiar = $('#btnlimpiar');

    $btlimpiar.on('click', function() {
        limpiar();
    });

});

function limpiar() {
    $('form#frmconsultorio input#consultorio').val('');
    $('form#frmconsultorio select#especialidad').select2('val',0);
    $('form#frmconsultorio select#turno').select2('val',0);
    $('form#frmconsultorio select#desde').select2('val',0).find('option:gt(0)').remove().end();
    $("form#frmconsultorio select#hasta").select2('val',0).find('option:gt(0)').remove().end();
}
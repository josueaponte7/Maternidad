$(document).ready(function() {
    var TConsultorio = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "10%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "10%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmconsultorio  = $('form#frmconsultorio');
    var $consultorio     = $frmconsultorio.find('input:text#consultorio');
    var $select_espe     = $frmconsultorio.find('select#especialidad');
    var $select_turno    = $frmconsultorio.find('select#turno');
    var $div_consultorio = $frmconsultorio.find('div#div_consultorio');
    var $btnaccion       = $frmconsultorio.find('input:button#btnaccion');

    $select_espe.select2();
    $select_turno.select2();

    $('input.turno').prettyCheckable();
    var letra = ' abcdefghijklmnñopqrstuvwxyzáéíóú123456789#/º,.';

    $consultorio.validar(letra);

    var cons_texto = '<span class="requerido">Requerido</span><br/>El campo consultorio no debe estar en <span class="alerta">blanco</span>,<br/> solo permite (<span class="alerta"> letras, n&uacute;meros</span>) y (<span class="alerta">#/º,.</span>), entre <span class="alerta">5 y 50</span> caracteres';
    $('#imgconsultorio').tooltip({
        html: true,
        placement: 'right',
        title: cons_texto
    });

    var espes_texto = '<span class="requerido">Requerido</span><br/>Indica la Especialidad para el consultorio';
    $('#imgespecialidad').tooltip({
        html: true,
        placement: 'left',
        title: espes_texto
    });

    var turno_texto = '<span class="requerido">Requerido</span><br/>Indica el Turno para el consultorio';

    $('#imgturno').tooltip({
        html: true,
        placement: 'right',
        title: turno_texto
    });


    var desde_texto = '<span class="requerido">Requerido</span><br/>Indica el horario de comienzo para el consultorio';

    $('#imgdesde').tooltip({
        html: true,
        placement: 'right',
        title: desde_texto
    });

    var hasta_texto = '<span class="requerido">Requerido</span><br/>Indica el horario de fin para el consultorio';

    $('#imghasta').tooltip({
        html: true,
        placement: 'left',
        title: hasta_texto
    });

    var url = '../../controlador/Mantenimiento/consultorio.php';

    // evento click en btaaccion

    var letras = /^[0-9a-zA-Záéíóú�?É�?ÓÚüÜñÑ\sº#\/,.]{5,20}$/;

    $btnaccion.on('click', function() {
        $('#accion').remove();
        var $accion = ' <input type="hidden" id="accion" name="accion" value="" />';
        $($accion).prependTo($(this));

        var accion = $(this).val();

        if ($("input:checkbox + a").hasClass('checked')) {
            var marcado = 1;
        } else {
            marcado = 0;
        }
        if ($consultorio.val() === null || $consultorio.val().length === 0 || /^\s+$/.test($consultorio.val())) {
            $div_consultorio.addClass('has-error');
            $consultorio.focus();
        } else if (!letras.test($consultorio.val())) {
            $div_consultorio.addClass('has-error');
            $consultorio.focus();
        } else if ($select_espe.val() == 0) {
           $select_espe.addClass('has-error');
        } else if (marcado == 0) {
            window.parent.apprise('Debe seleccionar un turno', {'textOk': 'Aceptar'});
        } else {

            var especilaidad = $select_espe.find('option').filter(':selected').text();
            var turno = new Array();
            $("input:checkbox[name='turno[]']:checked").each(function() {
                turno.push($(this).data('label'));
            });
  
            var turnos = turno.join(",");
            
            $('#accion').val(accion);

            if (accion === 'Agregar') {

                var codigo = TConsultorio.fnGetData().length;
                codigo = codigo + 1;
                var modificar = '<img id="' + codigo + '" class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';

                $.post(url, $frmconsultorio.serialize(), function(data) {
  
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});

                    if (cod_msg === 21) {
                        TConsultorio.fnAddData([$consultorio.val(), especilaidad, turnos, modificar]);
                        limpiar();
                    }

                }, 'json');
            } else {
                var turno_s = new Array();
            
                $("input:checkbox").each(function() {
                    if($(this).is(':disabled')){
                        turno_s.push($(this).data('label'));
                    }
                    if($(this).is(':checked')){
                        turno_s.push($(this).data('label'));
                    }
                });
                var turnos_s = turno_s.join(",");
  
                window.parent.apprise('&iquest;Desea Modificar los datos del registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                        $.post(url, $frmconsultorio.serialize(), function(data) {
                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;
      
                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                            var fila = $("#fila").val();

                            if (cod_msg === 22) {
                                $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(0)").html($consultorio.val());
                                $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(1)").html(especilaidad);
                                $("#tabla tbody tr:eq(" + fila + ")").find("td:eq(2)").html(turnos_s);

                                limpiar();
                            }

                        }, 'json');
                    }
                });
            }
        }
    });

    var $tabla = $('table#tabla');
    $tabla.on('click', 'img.modificar', function() {
        
        limpiar();

        var $padre = $(this).closest('tr');
        var fila = $padre.index();
        var num_consultorio = $(this).attr('id');
        var consultorio = $padre.children('td').eq(0).text();

        $consultorio.val(consultorio);
        $.post(url, {num_consultorio: num_consultorio, accion: 'BuscarDatos'}, function(data) {

            $("input:checkbox + a").removeClass('checked');
            $("input:checkbox + a").parent('div').removeClass('disabled');
            $("input:checkbox").prop('checked',false);
            var cod_especialidad = data.cod_especialidad;
            var turnos = data.turnos;
            var cod_turnos = turnos.split(',');

            if (cod_turnos.length == 2) {
                $.post(url, {num_consultorio: num_consultorio, cod_turnos: data.turnos, accion: 'BuscarMedicoTurno'}, function(resultado) {
                    if (cod_turnos.length == 2) {
                        $("input:checkbox + a").addClass('checked');
                        
                        if (resultado.total == 2) {
                            $("input:checkbox[name='turno[]']").prop('checked',true);
                            $("input:checkbox + a").parent('div').addClass('disabled');
                        } else if (resultado.total == 1) {
                            $("input:checkbox").not('#'+resultado.cod_turno).prop('checked',true);
                            $("input:checkbox#"+resultado.cod_turno).prop('disabled',true);
                            $("input:checkbox#" + resultado.cod_turno).next('a').parent('div').addClass('disabled'); 
                        }
                    }
                }, 'json');
            } else {
                $("input:checkbox#" + data.turnos).next('a').addClass('checked');
                $.post(url, {num_consultorio: num_consultorio, cod_turnos: data.turnos, accion: 'BuscarMedicoTurno'}, function(resultado) {
                    if (resultado.total == 1) {
                        $("input:checkbox#" + data.turnos).next('a').parent('div').addClass('disabled');
                    }else{
                        $("input:checkbox#"+ data.turnos).prop('checked',true);
                    }
                }, 'json');
            }
            $select_espe.select2('val', cod_especialidad);

        }, 'json');
        
        var $num_cons = '<input type="hidden" id="num_consultorio"  value="' + num_consultorio + '" name="num_consultorio">';
        $($num_cons).prependTo($btnaccion);

        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).prependTo($btnaccion);
        $btnaccion.val('Modificar');
    });

    var $btlimpiar = $('#btnlimpiar');

    $btlimpiar.on('click', function() {
        limpiar();
    });

});

function limpiar()
{
    $('#fila').remove();
    $('#num_consultorio').remove();
    $('#accion').remove();
    $('form#frmconsultorio input:text#consultorio').val('');
    $('form#frmconsultorio select#especialidad').select2('val', 0);
    $('form#frmconsultorio select#turno').select2('val', 0);
    $('select,div').removeClass('has-error');
    $('form#frmconsultorio input:button#btnaccion').val('Agregar');
    $("input:checkbox").prop('checked', false);
    $("input:checkbox + a").removeClass('checked');
    $("input:checkbox + a").parent('div').removeClass('disabled');
}
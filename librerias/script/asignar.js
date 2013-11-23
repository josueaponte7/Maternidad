$.tools.dateinput.localize("es", {
    months: 'Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Novimbre,Dicimbre',
    shortMonths: 'Ene,Feb,Mar,Abr,May,Jun,Jul,Aog,Sep,Oct,Nov,Dic',
    days: 'Domingo,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado',
    shortDays: 'Dom,Lun,Mar,Mie,Jue,Vie,Sab'
});

jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-uk-pre": function(a) {
        var ukDatea = a.split('-');
        return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
    },
    "date-uk-asc": function(a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
    "date-uk-desc": function(a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});   

$(document).ready(function() {
    var TAsignar = $('#tabla_asignar').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "bFilter": false,
        "bSearchable": false,
        "bInfo": false,
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "aaSorting": [[0, "desc"]],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            { "sType": "date-uk"} ,
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "8%"},
            {"sClass": "center", "sWidth": "40%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmasignar  = $('form#frmasignar');
     var $btn_nac    = $frmasignar.find('button:button#btn_nac');
    var $text_nac    = $frmasignar.find('input:text#text_nac');
    var $hnac        = $frmasignar.find('input:hidden#hnac');
    var $cedula_p    = $frmasignar.find('input:text#cedula_p');
    var $nombre      = $frmasignar.find('input:text#nombre');
    var $apellido    = $frmasignar.find('input:text#apellido');
    var $telefono    = $frmasignar.find('input:text#telefono');
    var $consultorio = $frmasignar.find('select#num_consultorio');
    var $fecha       = $frmasignar.find('input:text#fecha');
    var $btnaccion   = $frmasignar.find('input:button#btnaccion');
    var $btnlimpiar  = $frmasignar.find('input:button#btnlimpiar');
    var $btnbuscar   = $frmasignar.find('input:button#btnbuscar');
    var $div_cedula  = $frmasignar.find('div#div_cedula');
    var $div_fecha   = $frmasignar.find('div#div_fecha');
    var $btn_nac     = $frmasignar.find('button:button#btn_nac');
    var val_cedula = '1234567890';

    $consultorio.select2();
    $cedula_p.validar(val_cedula);

    var dateinput = $fecha.dateinput({
        lang: 'es',
        format: 'dd-mm-yyyy', // the format displayed for the user
        selectors: true, // whether month/year dropdowns are shown
        min: 2, // min selectable day (100 days backwards)
        max: 90, // max selectable day (100 days onwards)
        offset: [10, 20], // tweak the position of the calendar
        speed: 'fast', // calendar reveal speed
        firstDay: 0,
        "onShow": function(event) {
            var calendar = this.getCalendar();
            var conf = this.getConf();
            var classes = conf.css.off + ' ' + conf.css.disabled;
            function disableWeekends() {
                var weeks = calendar.find('.calweek');
                weeks.find('a:first, a:last').addClass(classes);
            }
            calendar.find('#calprev, #calnext').click(disableWeekends);
            disableWeekends();
        }
    });

    var url = '../../controlador/asignar.php';

    $("ul#nacionalidad > li > span").click(function() {
        
        $hnac.val('');
        $text_nac.val('');
        $cedula_p.val('');
        
        var nac = $(this).attr('id');
        if (nac != 'N') {
            $btn_nac.removeClass('btn-danger');
            $hnac.val(nac);
            $text_nac.val(nac + '-');
            $cedula_p.focus();
            if(nac == 'V'){
                $cedula_p.attr('maxlength',8);
            }else{
                $cedula_p.attr('maxlength',9);
            }
        }
    });

    $btnbuscar.on('click', function() {
        
        var ced_val = /^[0-9]{7,}$/;
        $consultorio.select2('enable',true);
        $fecha.prop('disabled',false);
        
        if ($hnac.val() === null || $hnac.val().length === 0 || /^\s+$/.test($hnac.val())) {
            $btn_nac.addClass('btn-danger');
            $btn_nac.focus();
        } else if ($cedula_p.val() === null || $cedula_p.val().length === 0 || /^\s+$/.test($cedula_p.val())) {
            $div_cedula.addClass('has-error');
            $cedula_p.focus();
        } else if (!ced_val.test($cedula_p.val())) {
            $div_cedula.addClass('has-error');
            $cedula_p.focus();
        } else {
            var cedula_p = $cedula_p.val();
            $.post(url, {cedula_p: cedula_p, accion: 'BuscarDatos'}, function(data) {
                var cod_msg = parseInt(data.error_codmensaje);
                var mensaje = data.error_mensaje;
                if (cod_msg === 17) {
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                        $cedula_p.focus();
                        $nombre.val('');
                        $apellido.val('');
                        $telefono.val('');
                        TAsignar.fnClearTable();
                    });
                } else if(cod_msg === 15){
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                        $cedula_p.focus();
                        $nombre.val('');
                        $apellido.val('');
                        $telefono.val('');
                        TAsignar.fnClearTable();
                    });
                } else {
                    var total      = parseInt(data.total);
                    var asistencia = parseInt(data.asistencia);
                    var fech_max   = data.fech_max;
                    $nombre.val(data.nombre);
                    $apellido.val(data.apellido);
                    $telefono.val(data.telefono);
                    $btnaccion.prop('disabled', false);
                    TAsignar.fnClearTable();
                    
    
                    var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                    var img = '';
                    if (total == 1 && asistencia == 0 && fech_max == 0) {
                        $('table#tabla_asignar > thead > tr').find('th').eq(4).html('Eliminar');
                        var img = '<img class="eliminar" title="Eliminar" style="cursor: pointer" src="../../imagenes/datatable/eliminar.png" width="18" height="18" alt="Eliminar"/>';
                    } else if (total === asistencia ) {
                        var img = '';
                    }
                    
                    var dias_max = 0;
                     $.post(url, {cedula_p: cedula_p, accion: 'BuscarCitas'}, function(resultado) {
                        $.each(resultado, function(i, obj) {
                            TAsignar.fnAddData([obj.fecha, obj.nombre, obj.consultorio, obj.observacion, img]);
                        });
                    }, 'json');
                }
            }, 'json');
        }     
    });

    $consultorio.on('change', function() {
        var num_consultorio = $(this).val();

        $("#consultorio_turno tbody tr").remove();
        if (num_consultorio > 0) {
            $("#consultorio_turno").fadeIn(1500);
            $('#tr_turno').fadeIn(1500);
            $.post(url, {num_consultorio: num_consultorio, accion: 'BuscarTurno'}, function(data) {
                var rows = "";
                if (data !== 0) {
                    
                    $.each(data, function(i, obj) {

                        rows += '<tr id="' + obj.cod_turno + ';' + obj.cod_consu_horario + '">'
                                + '<td  align="center">' + obj.nombres + '</td>'
                                + '<td  align="center">' + obj.turno + '</td>'
                                + '</tr>';
                    });
                    $("#mensaje_tabla").fadeIn().html('Debe Hacer click sobre el nombre del M&eacute;dico para seleccionar turno');;
                    $("#consultorio_turno").fadeIn();
                    $("#consultorio_turno tbody").append(rows);
                } else {
                    $("#mensaje_tabla").fadeOut();
                    $('#tr_turno').fadeOut();
                    $("#consultorio_turno").fadeOut();
                    $("#consultorio_turno tbody tr").remove();
                }
            }, 'json');

        } else {
            $("#mensaje_tabla").fadeOut();
            $('#tr_turno').fadeOut();
            $("#consultorio_turno").fadeOut();
            $("#consultorio_turno tbody tr").remove();
        }
    });

    $("table#consultorio_turno > tbody").on('click', 'tr', function() {
        var id = $(this).attr('id');

        var codigo = id.split(';');
        var cod_turno = codigo[0];
        
        var cod_consu_horario = codigo[1];
        var $padre = $(this).closest('tr');
        var medico = $padre.children('td').eq(0).html();
        var turno = $padre.children('td').eq(1).html();

        $('#medico').val(medico);
        $('#turno').val(turno);
        $('#hcod_turno').val(cod_turno);
        $('#cod_consu_horario').val(cod_consu_horario);
    });

    $btnaccion.on('click', function() {
        var accion = $(this).val();

        var $accion = ' <input type="hidden" id="accion" name="accion" value="' + accion + '" />';
        $($accion).prependTo($(this));

        if (accion === 'Asignar') {

            if ($text_nac.val() == '') {
                $btn_nac.addClass('btn-danger');
                $btn_nac.focus();
            } else if ($cedula_p.val() === null || $cedula_p.val().length === 0 || /^\s+$/.test($cedula_p.val())) {
                $div_cedula.addClass('has-error');
                $cedula_p.focus();
            } else if ($consultorio.val() == 0) {
                $consultorio.select2("container").addClass("error_select");
                $('#s2id_num_consultorio a.select2-choice').addClass('error_select');
            } else if ($fecha.val() === null || $fecha.val().length === 0 || /^\s+$/.test($fecha.val())) {
                $div_fecha.addClass('has-error');
                $fecha.focus();
            } else if ($('#hcod_turno').val() === null || $('#hcod_turno').val().length === 0 || /^\s+$/.test($('#hcod_turno').val())) {
                window.parent.apprise('Debe seleccionar el turno del consultorio', {'textOk': 'Aceptar'});
            } else {

                $.post(url, $frmasignar.serialize(), function(data) {
                    var cantidad = TAsignar.fnGetData().length;
                    var consultorio = $consultorio.find('option').filter(':selected').html();
                    var modificar_cita = $('#modifcar_cita').val();
                    
                    var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                    var eliminar   = '<img class="eliminar" title="Eliminar" style="cursor: pointer" src="../../imagenes/datatable/eliminar.png" width="18" height="18" alt="Eliminar"/>';
                    
                    if(modificar_cita == 1){
                        modificar = '';
                    }
                    if(cantidad == 0){
                        modificar = eliminar;
                        $('table#tabla_asignar > thead > tr').find('th').eq(4).html('Elimiar');
                    }
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg === 21) {
                        $btnaccion.prop('disabled', true);
                        //$('table#tabla_asignar > tbody > tr').find('td').eq(4).html('');
                        TAsignar.fnAddData([
                            $fecha.val(),
                            $('#medico').val(),
                            consultorio,
                            '',
                            modificar
                        ]);
                    }

                    limpiar();
                }, 'json');
            }
        }

    });
    
    $('table#tabla_asignar').on('click', 'img.modificar', function() {
        var padre            = $(this).closest('tr');
        var observacion      = padre.children('td:eq(3)');
        var imagen_modificar = padre.children('td:eq(4)');
        var cedula_p    = $cedula_p.val();
       window.parent.apprise('Indique una observaci&oacute;n', {'input':true,'textOk':'Aceptar', 'textCancel':'Cancelar'},function(respuesta){
           if(respuesta != false){
                $.post(url, {cedula_p: cedula_p, observacion: respuesta, accion: 'CancelarCita'}, function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;
                    if (cod_msg == 21) {
                        $('#modifcar_cita').val(1);
                        window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                        observacion.html(respuesta);
                        imagen_modificar.html('');
                        $btnaccion.prop('disabled', false);
                        $fecha.prop('disabled', false);
                        $consultorio.select2("enable", true);
                        $("#mensaje_tabla").fadeIn().html('Debe generar una nueva cita');
                    }
                    
                }, 'json');
            }
       });
    });
    
    
    $('table#tabla_asignar').on('click', 'img.eliminar', function() {
        var padre = $(this).closest('tr');
        var cedula_p = $cedula_p.val();
        var fecha = padre.children('td:eq(0)').html();
        window.parent.apprise('<span style="margin-left:20%">¿Desea Eliminar la cita?</span>', {'confirm': true, 'textOk': 'Aceptar', 'textCancel': 'Cancelar'}, function(r) {
            if (r) {
                $.post(url, {cedula_p: cedula_p, fecha: fecha, accion: 'EliminarCita'}, function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg === 23) {
                        $btnaccion.prop('disabled', false);
                        TAsignar.fnClearTable();
                    }
                },'json');
            }
        });
    });
    
    $btnlimpiar.click(function(){
        limpiar();
        TAsignar.fnClearTable();
    });

});

function limpiar() {
    $('input:text').val('');
    $('#tr_turno').fadeOut();
    $('#accion').remove();
    $('#num_consultorio').select2('val', (0));
    $('button:button#btn_nac').removeClass('has-error');
    $("#consultorio_turno").fadeOut();
    $("#consultorio_turno tbody tr").delay(600).remove();
    $('div').removeClass('has-error');
    $('select').select2("container").removeClass("error_select");
    $('a.select2-choice').removeClass('error_select');
    $("#mensaje_tabla").fadeOut();

}


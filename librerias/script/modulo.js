$(document).ready(function() {

     $('input[type="text"], textarea').on({
        keypress: function() {
            $(this).parent('div').removeClass('has-error');
        }
    });


    var TModulo = $('#tabla_modulo').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "agregar center", "sWidth": "10%"},
            {"sClass": "center", "sWidth": "40%"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
            //{"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false,"sDefaultContent":'<input type="button"/>'}
        ]
    });


    var $formulario   = $('form#frmmodulo');
    var $modulo       = $formulario.find('input:text#modulo');
    var $div_modulo   = $formulario.find('div#div_modulo');
    var $btnaccion    = $formulario.find('input:button#btnaccion');
    var $btnlimpiar   = $formulario.find('input:button#btnlimpiar');
    var $btnbtnlistar = $formulario.find('input:button#btnlistar');
    var $tabla_modulo = $('#tabla_modulo');

    $('#img_modulo').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>El Modulo no debe estar <span class="alerta">blanco</span>'
    });

//    $('.toltip-modulo').tooltip({
//        html: true,
//        placement: 'top',
//        title: '<span class="requerido">Requerido</span><br/>El Modulo no debe estar <span class="alerta">blanco</span>'
//    });

    var letras = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]{2,20}$/;

    var letra = ' abcdefghijklmnñopqrstuvwxyzáéíóú';

    $modulo.validar(letra);
    var urlmod = '../../controlador/seguridad/modulo.php';

    $btnaccion.on("click", function() {
        if ($modulo.val() === null || $modulo.val().length === 0 || /^\s+$/.test($modulo.val())) {
            $div_modulo.addClass('has-error');
            $modulo.focus();
        } else if (!letras.test($modulo.val())) {
            $div_modulo.addClass('has-error');
            $modulo.focus();
        } else {
            $div_modulo.removeClass('has-error');
            var accion = $(this).val();
            var $accion = '<input type="hidden" id="accion"  value="' + accion + '" name="accion">';
            $($accion).appendTo($formulario);

            if ($(this).val() == 'Agregar') {

                $.post(urlmod, $formulario.serialize(), function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;

                    var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                    var eliminar  = '<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';

                    if (cod_msg === 21) {
                        window.parent.apprise(mensaje, {'textOk': 'Aceptar'}, function() {
                            var codigo = TModulo.fnGetData().length;
                            codigo = codigo + 1;
                            TModulo.fnAddData([codigo, $modulo.val(), modificar, eliminar]);
                            limpiar($formulario);
                        });

                    } else if (cod_msg === 15) {
                        $div_modulo.addClass('has-error');
                        $modulo.focus();
                    }
                }, 'json');

            } else {

                window.parent.apprise('&iquest;Desea Modificar el Registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                        $.post(urlmod, $formulario.serialize(), function(data) {
                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;
                            var tipo = data.tipo_error;
                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                            if (cod_msg === 22) {
                                var fila = $("#fila").val();
                                $("#tabla_modulo tbody").children('tr').eq(fila).children('td').eq(1).html($modulo.val());
                                //$("#tabla_modulo tbody tr:eq("+fila+")").find("td:eq(1)").html($modulo.val());
                                limpiar($formulario);
                            }
                        }, 'json');
                    } else {
                        limpiar($formulario);
                    }
                });
            }
        }

    });

    // Cuando presiona la imagen modificar en modulos
    $tabla_modulo.on('click', 'img.modificar', function() {

        limpiar($formulario);
        $('#fila').remove();
        $('#cod_modulo').remove();

        var padre      = $(this).closest('tr');
        var fila       = padre.index();
        var id         = padre.attr('id');
        var cod_modulo = padre.children('td:eq(0)').html();
        var modulo     = padre.children('td').eq(1).html();
        var $codigo = '<input type="hidden" id="cod_modulo"  value="' + cod_modulo + '" name="cod_modulo">';
        var $fila   = '<input type="text" id="fila"  value="' + fila + '" name="fila">';

        $($codigo).appendTo($btnaccion);
        $($fila).appendTo($btnaccion);
        $('#cod_modulo').val(cod_modulo);
        $modulo.val(modulo);
        $btnaccion.val('Modificar');

    });

    // Cuando presiona la imagen eliminar en modulos
    $tabla_modulo.on('click', 'img.eliminar', function() {

        var padre      = $(this).closest('tr');
        var cod_modulo = padre.children('td:eq(0)').html();
        var nRow       = padre[0];
        window.parent.apprise('&iquest;Desea Eliminar el Registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
            if (r) {
                $.post(urlmod, {cod_modulo: cod_modulo, accion: 'Eliminar'}, function(data) {

                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if (cod_msg === 23) {
                        TModulo.fnDeleteRow(nRow);
                    }
                }, 'json');
            }else{
                limpiar($formulario);
            }
        });
    });

    $tabla_modulo.find('tbody tr').on('click',('td:lt(2)'),function() {

        $btnbtnlistar.fadeIn(1000);
        var padre      = $(this).closest('tr');
        var cod_modulo = padre.children('td:eq(0)').html();
        var modulo     = padre.children('td').eq(1).html();

        var $codigo    = '<input type="hidden" id="cod_modulo"  value="' + cod_modulo + '" name="cod_modulo">';
        var $hmodulo    = '<input type="hidden" id="hmodulo"  value="' + modulo + '" name="hmodulo">';

        $($codigo).appendTo('input:button#btnaccionsub');
        $($hmodulo).appendTo('input:button#btnaccionsub');

        $('#cod_modulo').val(cod_modulo);
        $('#hmodulo').val(modulo);

        $btnaccion.prop('disabled',true);
        $btnlimpiar.val('Restablecer');
        $modulo.val(modulo).prop('disabled',true);
    });

    $btnlimpiar.on("click", function() {
        limpiar($formulario);
    });

    /*
     * Fin de Modulo
     */

    /*
     * Sub Modulos
     */

    // Tabla SubModulo
    var TSubModulo = $('#tabla_submodulo').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "10%"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmsubmodulo   = $('form#frmsubmodulo');
    var $div_submodulo  = $frmsubmodulo.find('div#div_submodulo');
    var $nommodulo      = $frmsubmodulo.find('select#nommodulo');
    var $sub_modulo     = $frmsubmodulo.find('input:text#submodulo');
    var $ruta           = $frmsubmodulo.find('input:text#ruta');
    var $btnaccionsub   = $frmsubmodulo.find('input:button#btnaccionsub');
    var $btnlimpiarsub  = $frmsubmodulo.find('input:button#btnlimpiarsub');
    var $btnrestablecer = $frmsubmodulo.find('input:button#btnrestablecer');
    var $div_ruta       = $frmsubmodulo.find('div#div_ruta');

    $nommodulo.select2();

    var rutas = /^[a-zA-Z\.0-9/\-_]{4,50}$/;
    var ruta = 'abcdefghijklmnopqrstuvwxyz/._-1234567890';

    $ruta.validar(ruta);

    $('#img_nommodulo').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>Debe Seleccionar un <span class="alerta">modulo</span>'
    });

    $('#img_submodulo').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>El Subm&oacute;dulo no debe estar <span class="alerta">blanco</span>'
    });

    $('#img_ruta').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>la Ruta no debe estar en <span class="alerta">blanco</span>,<br/>solo acepta (<span class="alerta">/_-.</span>)'
    });

    $sub_modulo.validar(letra);
    // Evento para buscar todos los SubModulos
    var urlsub = '../../controlador/seguridad/submodulo.php';

    $btnbtnlistar.on('click', function() {

        var cod_modulo = $('#cod_modulo').val();
        var nommodulo  = $('#hmodulo').val();;

        $nommodulo.select2("val", cod_modulo);
        $nommodulo.select2("enable", false);
        $('div#divmodulo').slideUp(1500);
        $('div#divsubmodulo').slideDown(1500);

        var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
        var eliminar  = '<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';

        TSubModulo.fnClearTable();
        $.post(urlsub, {cod_modulo: cod_modulo, accion: 'BuscarSubModulos'}, function(data) {
            $.each(data, function(i, obj) {
                TSubModulo.fnAddData([obj.cod_submodulo, nommodulo, obj.submodulo, modificar, eliminar]);
            });
        }, 'json');
    });

    $btnaccionsub.on("click", function() {

        var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
        var eliminar = '<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';

        if ($sub_modulo.val() === null || $sub_modulo.val().length === 0 || /^\s+$/.test($sub_modulo.val())) {
            $div_submodulo.addClass('has-error');
            $sub_modulo.focus();
        } else if (!letras.test($sub_modulo.val())) {
            $div_submodulo.addClass('has-error');
            $sub_modulo.focus();
        }else if ($ruta.val() === null || $ruta.val().length === 0 || /^\s+$/.test($ruta.val())) {
            $div_ruta.addClass('has-error');
            $ruta.focus();
        } else if (!rutas.test($ruta.val())) {
            $div_ruta.addClass('has-error');
            $ruta.focus();
        }else{
            $div_modulo.removeClass('has-error');
            $div_ruta.removeClass('has-error');

            var nommodulo = $('#hmodulo').val();;
            var accion    = $(this).val();
            var $accion   = '<input type="hidden" id="accion"  value="' + accion + '" name="accion">';

            $($accion).appendTo($frmsubmodulo);
             if ($(this).val() == 'Agregar') {

                $.post(urlsub, $frmsubmodulo.serialize(), function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;

                    var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                    var eliminar  = '<img class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';

                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    var codigo = TSubModulo.fnGetData().length;
                    codigo = codigo + 1;
                    TSubModulo.fnAddData([codigo, nommodulo, $sub_modulo.val(), modificar, eliminar]);
                    $sub_modulo.val('');
                    $ruta.val('');
                    $('#accion').remove();

                },'json');
            } else {
                window.parent.apprise('&iquest;Desea Modificar el Registro?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                    if (r) {
                        $('#cod_modulo').remove();
                        $.post(urlsub, $frmsubmodulo.serialize(), function(data) {
                            var modulo         = $nommodulo.find('option').filter(':selected').html();
                            var cod_modulo_new = $nommodulo.find('option').filter(':selected').val();
                            var cod_msg        = parseInt(data.error_codmensaje);
                            var mensaje        = data.error_mensaje;
                            var tipo           = data.tipo_error;
                            var cod_modulo_old = $('#cod_modulo_old').val();


                            window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                            if (cod_msg === 22) {
                                var fila = parseInt($("#fila").val());
                                if(cod_modulo_new != cod_modulo_old){
                                    TSubModulo.fnDeleteRow(nRow);
                                }else{
                                    $("#tabla_submodulo tbody").children('tr').eq(fila).children('td').eq(2).html($sub_modulo.val());
                                }
                            }
                        }, 'json');
                    }
                });
            }
        }
    });

    var nRow = '';
    // Cuando presiona la imagen modificar en submodulos
    $('#tabla_submodulo').on('click', 'img.modificar', function() {

        $('#fila').remove();
        $('#cod_submodulo').remove();

        $('#nrow').remove();
        $('#hmodulo').remove();

        $nommodulo.select2("enable", true);
        var padre          = $(this).closest('tr');
        var fila           = padre.index();
        var cod_submodulo  = padre.children('td:eq(0)').html();
        var modulo         = padre.children('td:eq(1)').html();
        var sub_modulo     = padre.children('td').eq(2).html();
        nRow           = padre[0];
        var cod_modulo_old = $('#cod_modulo').val();
        var $cod_submodulo = '<input type="hidden" id="cod_submodulo"  value="' + cod_submodulo + '" name="cod_submodulo">';
        $($cod_submodulo).appendTo($btnaccionsub);

        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).appendTo($btnaccionsub);

        var $modulo = '<input type="hidden" id="hcod_modulo"  value="' + modulo + '" name="hcod_modulo">';
        $($modulo).appendTo($btnaccionsub);

        var $cod_modulo_old = '<input type="hidden" id="cod_modulo_old"  value="' + cod_modulo_old + '" name="cod_modulo_old">';
        $($cod_modulo_old).appendTo($btnaccionsub);

        $.post(urlsub, {cod_submodulo: cod_submodulo, accion: 'BuscarRuta'}, function(data) {
            $ruta.val(data);
        });

        $('#cod_submodulo').val(cod_submodulo);

        $sub_modulo.val(sub_modulo);
        $btnaccionsub.val('Modificar');
    });

    // Cuando presiona la imagen eliminar en submodulos
    $('#tabla_submodulo').on('click', 'img.eliminar', function() {

        $sub_modulo.val('');
        $btnaccionsub.val("Agregar");

        $('#accion').remove();
        $('#fila').remove();
        $('#cod_submodulo').remove();
        $('#cod_modulo').remove();

        var padre         = $(this).closest('tr');
        var cod_submodulo = padre.children('td:eq(0)').html();
        var nRow          = padre[0];

        delSubModulo(cod_submodulo, nRow, TSubModulo);
    });

    $btnlimpiarsub.on("click", function() {
        var cod_modulo = $('#cod_modulo').val();
        $sub_modulo.val('');
        $ruta.val('');
        $btnaccionsub.val("Agregar");
        $nommodulo.select2("val", cod_modulo);
        $nommodulo.select2("enable", false);
    });

    $btnrestablecer.on("click", function() {

        TSubModulo.fnDraw();
        TSubModulo.fnClearTable();
        $('div#divmodulo').slideDown(3000);
        $('div#divsubmodulo').slideUp(3000, function() {
            TSubModulo.fnClearTable();
        });
        $sub_modulo.val('');
        $btnaccionsub.val('Agregar');

    });

});

/***
 *
 * @Modulo
 */

function limpiar(formulario) {
    $("#div_modulo").removeClass('has-error');
    formulario.find('input:text#modulo').val('').prop('disabled',false);
    formulario.find('input:button#btnaccion').val('Agregar').prop('disabled',false);
    formulario.find('input:button#btnlistar').fadeOut(1000);
    formulario.find('input:button#btnlimpiar').val('Limpiar');
    $('#accion').remove();
    $('#fila').remove();
    $('#cod_modulo').remove();
}

function editSubModulo(formulario, tabla) {


}

function delSubModulo(cod_submodulo, nfila, tabla) {

    jConfirm('¿Desea Eliminar los Datos?', 'Confirmación', function(respuesta) {
        if (respuesta === true) {
            $.post(urlsub, {cod_submodulo: cod_submodulo, accion: 'Eliminar'}, function(data) {

                var cod_msg = parseInt(data.error_codmensaje);
                var mensaje = data.error_mensaje;
                var tipo = data.tipo_error;

                jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');

                if (cod_msg === 23) {
                    tabla.fnDeleteRow(nfila);
                    var cant_column = tabla.fnSettings().fnRecordsTotal();
                    if (cant_column == 0) {
                        $('#frmmodulo').find('input:button#btnlistar').attr({'disabled': true});
                    }
                }
            }, 'json');
        }
    });
}

function getCodModulo(cod_submodulo) {

    $.post(urlsub, {cod_submodulo: cod_submodulo, accion: 'CodMod'}, function(data) {
        $('#cod_modulo').val(data);
    }, 'json');
}

function limpiarSub() {

    $('form#frmsubmodulo input:text#txtsubmodulo').val('');
    $('form#frmsubmodulo input:text#txtorden_submodulo').val('');
    $('#accion').remove();
    $('#fila').remove();
    $('#cod_submodulo').remove();
    $('#cod_modulo').remove();
}
$(document).ready(function() {

    var TPefil = $('#tabla_perfil.dataTable').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "agregar center", "sWidth": "10%"},
            {"sClass": "center"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmperfil    = $('#frmperfil');
    var $perfil       = $frmperfil.find($('input:text#perfil'));
    var $btnaccion    = $frmperfil.find($('input:button#btnaccion'));
    var $btnlimpiar   = $frmperfil.find($('input:button#btnlimpiar'));
    var $btnlistar    = $frmperfil.find($('input:button#btnlistar'));
    var $tabla_perfil = $('#tabla_perfil');

    var urlperfil = '../../controlador/seguridad/perfil.php';

    // Aciones Agregar/Modificar
    $btnaccion.on('click', function() {
        $('#accion').remove();
        var $accion = ' <input type="hidden" id="accion" name="accion" value="ModificarPerfil" />';
        $($accion).prependTo($(this));
        var accion = $(this).val();
        if (accion === 'Agregar') {

            $('#accion').val('AgregarPerfil');
            if (validar($perfil) === true) {
                var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                var eliminar  = '<img class="eliminar"  title="Eliminar"   style="cursor: pointer" src="imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';
                $.post(urlperfil, $frmperfil.serialize(), function(data) {

                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo    = data.tipo_error;

                    jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');
                    if (cod_msg === 21) {
                        var codigo = TPefil.fnGetData().length;
                        codigo = codigo + 1;
                        TPefil.fnAddData([codigo, $perfil.val(), modificar, eliminar]);
                        limpiar();
                    }
                }, 'json');
            }
        } else {
            $('#accion').val('ModificarPerfil');
            if (validar($perfil) === true) {
                jConfirm('¿Desea Modificar los Datos?', 'Confirmación', function(respuesta) {
                    if (respuesta === true) {
                        $.post(urlperfil, $frmperfil.serialize(), function(data) {

                            var cod_msg = parseInt(data.error_codmensaje);
                            var mensaje = data.error_mensaje;
                            var tipo    = data.tipo_error;

                            jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');

                            if (cod_msg === 22) {

                                var fila = $("#fila").val();

                                TPefil.fnUpdate($perfil.val(), parseInt(fila), 1);
                                limpiar();
                            }
                        }, 'json');
                    }
                });
            }
        }
    });
    // fin btn accion

    // Click en la imagen modificar
    $tabla_perfil.on('click', 'img.modificar', function() {
        $('span.error_val').fadeOut();
        $('#accion').remove();

        var $padre = $(this).parents('tr');
        var fila = $padre.index();
        var cod = $padre.children('td:eq(0)').text();
        var perfil = $padre.children('td:eq(1)').text();

        var $cod = ' <input type="hidden" id="cod" name="cod" value="' + cod + '" />';
        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';

        $($fila).prependTo($btnaccion);
        $($cod).prependTo($btnaccion);
        $perfil.val(perfil);
        $btnaccion.val('Modificar');
    });
    // Fin


    $tabla_perfil.on('click', 'img.eliminar', function() {

        limpiar();
        var $padre = $(this).parents('tr');
        var fila = $padre.index();
        var cod = $padre.children('td:eq(0)').text();

        jConfirm('¿Desea Eliminar los Datos?', 'Confirmación', function(respuesta) {
            if (respuesta === true) {
                $.post(urlperfil, {cod: cod, accion: 'EliminarPerfil'}, function(data) {
                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;

                    jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');
                    if (cod_msg === 23) {
                        TPefil.fnDeleteRow(fila);
                    }
                }, 'json');
            }
        });

    });

    // Click en laimagen las primeras columnas
    $tabla_perfil.on('click', 'tr > td', function() {

        $('#cod').remove();
        $('#hperfil').remove();
        var indice = $(this).index();
        if (indice < 2) {

            var cod_perfil     = $(this).parents('tr').children('td:eq(0)').text();
            var perfil  = $(this).parents('tr').children('td:eq(1)').text();
            var $cod_perfil = ' <input type="hidden" id="cod_perfil" name="cod_perfil" value="' + cod_perfil + '" />';

            $($cod_perfil).prependTo($frmperfil);

            $btnlistar.removeAttr("disabled");
            $perfil.attr('disabled', 'disabled').val(perfil);
            $btnaccion.attr('disabled', 'disabled').val('Agregar');
            $btnlimpiar.val('Restablecer');

            var mod = '../../imagenes/datatable/modificar.png';
            var eli = '../../imagenes/datatable/eliminar.png';

            var mod_dis = '../../imagenes/datatable/modificar_disabled.png';
            var eli_dis = '../../imagenes/datatable/eliminar_disabled.png';

            $('img.modificar_disabled').attr({'src': mod, 'title': 'Modificar'}).css({'cursor': 'pointer'}).addClass('modificar');
            $('img.eliminar_disabled').attr({'src': eli, 'title': 'Eliminar'}).css({'cursor': 'pointer'}).addClass('eliminar');

            $(this).parents('tr').children('td:eq(2)').find('img.modificar').attr({'src': mod_dis}).removeAttr('class style title').addClass('modificar_disabled');
            $(this).parents('tr').children('td:eq(3)').find('img.eliminar').attr({'src': eli_dis}).removeAttr('class style title').addClass('eliminar_disabled');
        }
    });

    $btnlimpiar.click(function(){
        limpia_perfil();
    })

    $btnlimpiar.on('click', function() {

        if ($(this).val() == 'Restablecer') {



            $(this).val('Limpiar');

            var mod = '../../imagenes/datatable/modificar.png';
            var eli = '../../imagenes/datatable/eliminar.png';

            $('img.modificar_disabled').attr({'src': mod, 'title': 'Modificar'}).css({'cursor': 'pointer'}).addClass('modificar');
            $('img.eliminar_disabled').attr({'src': eli, 'title': 'Eliminar'}).css({'cursor': 'pointer'}).addClass('eliminar');
        }
    });

    var $frmfrmprivilegio  = $('#frmprivilegio');
    var $nom_perfil        = $frmfrmprivilegio.find('span#nom_perfil');
    var $modulo            = $frmfrmprivilegio.find('select#modulo');
    var $sub_modulo        = $frmfrmprivilegio.find('select#sub_modulo');
    var $btnaccpriv        = $frmfrmprivilegio.find($('input:button#btnaccpriv'));
    var $btnlimpriv        = $frmfrmprivilegio.find($('input:button#btnlimpriv'));
    var $btnrestablecer    = $frmfrmprivilegio.find($('input:button#btnrestablecer'));

    var $tabla_privilegios = $('#tabla_privilegios');

    $modulo.select2();
    $sub_modulo.select2();

    $('input.privilegio').prettyCheckable();


    var TPrivilegios = $('table#tabla_privilegios.dataTable').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false},
            {"sWidth": "3%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });


    $btnlistar.on('click', function() {

        var cod_perfil = $('#cod_perfil').val();

        $nom_perfil.text($perfil.val());
        $.post(urlperfil, {cod_perfil: cod_perfil, accion: 'BuscarPrivilegios'}, function(data) {
            $.each(data, function(i, obj) {
                var modificar = '<img id="'+obj.cod_submodulo+'" class="modificar" title="Modificar" style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                var eliminar  = '<img id="'+obj.cod_submodulo+'" class="eliminar"  title="Eliminar"  style="cursor: pointer" src="../../imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';
                TPrivilegios.fnAddData([obj.modulo,obj.sub_modulo,obj.privilegios, modificar, eliminar]);
            });
        },'json');
        $('div#divperfil').slideUp(3000);
        $('div#privilegios').slideDown(3000).css('display', 'block');
    });


    $modulo.on('change', function() {

        var codigo = $(this).val();
        if (codigo > 0) {
            $sub_modulo.find('option:gt(0)').remove().end();
            $.post(urlperfil, {codigo: codigo, accion: 'BuscarSub'}, function(data) {
                var option = "";
                if (data != 0) {
                    $.each(data, function(i, obj) {
                        option += "<option value=" + obj.codigo + ">" + obj.descripcion + "</option>";
                    });
                    $sub_modulo.append(option);
                } else {
                    $sub_modulo.find('option:gt(0)').remove().end();
                }
            }, 'json');

        } else {
            $sub_modulo.find('option:gt(0)').remove().end();
        }
    });

    $btnaccpriv.click(function() {

        var cod_perfil = $('#cod_perfil').val();

        var $accion     = '<input type="hidden" id="accion"  value="AgregarPrivilegios" name="accion">';
        var $cod_perfil = '<input type="hidden" id="cod_perfil"  value="'+cod_perfil+'" name="cod_perfil">';

        var privilegios = new Array();
        var nomb_privilegio = new Array();
        $("input:checkbox[name='privilegio[]']:checked").each(function() {
            privilegios.push($(this).val());
            nomb_privilegio.push($(this).data('label'));
        });

        var privil = privilegios.join(",");
        var nom_privil = nomb_privilegio.join(",");

        $($accion).prependTo($(this));
        $($cod_perfil).prependTo($(this));

        var nombre_prefil = $nom_perfil.text();
        var modulo        = $modulo.find('option:selected').text();
        var sub_modulo    = $sub_modulo.find('option:selected').text();

        var modificar = '<img class="modificar" title="Modificar" style="cursor: pointer" src="imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
        var eliminar  = '<img class="eliminar"  title="Eliminar"   style="cursor: pointer" src="imagenes/datatable/eliminar.png"  width="18" height="18" alt="Eliminar" />';

        $.post(urlperfil, $frmfrmprivilegio.serialize(), function(data) {
            var cod_msg = parseInt(data.error_codmensaje);
            var mensaje = data.error_mensaje;
            var tipo    = data.tipo_error;

            jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');
            if (cod_msg === 21) {
                var codigo = TPrivilegios.fnGetData().length;
                codigo     = codigo + 1;
                TPrivilegios.fnAddData([codigo,nombre_prefil, modulo, sub_modulo, nom_privil, modificar, eliminar]);
                limpiar();
            }
        }, 'json');
    });

    $tabla_privilegios.on('click', 'img.modificar', function() {

        alert($(this).attr('id'));
        //$('span.error_val').fadeOut();
        $('#accion').remove();

        var $padre     = $(this).parents('tr');
        var fila       = $padre.index();
        var perfil     = $padre.children('td:eq(0)').text();
        var modulo     = $padre.children('td:eq(1)').text();
        var sub_modulo = $padre.children('td:eq(2)').text();
        var cod_sub    = $padre.children('td:eq(3)').attr('id');
        /*var $cod  = ' <input type="hidden" id="cod" name="cod" value="' + cod + '" />';
         var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
         $($fila).prependTo($btnaccion);
         $($cod).prependTo($btnaccion);
         $perfil.val(perfil);
         $btnaccion.val('Modificar');*/
    });

    $btnlimpriv .click(function(){
        limpiar_privilegio();

    });
    $btnrestablecer.click(function(){
        $('div#divperfil').slideDown(3000);
        $('div#privilegios').slideUp(3000);
        limpiar_privilegio();
        TPrivilegios.fnClearTable();
    });
});

function validar(campo) {
    if (requerido(campo) === false) {
        $('span.error_val').fadeOut();
        var msgerror = 'Campo obligatorio';
        msgError(campo, msgerror);
        return  false;
    } else if (validacion(campo, 'letra') === false) {
        var msgerror = 'Debe ser mayor a 3 caracteres';
        msgError(campo, msgerror);
        return  false;
    } else {
        $('span.error_val').fadeOut();
        return  true;
    }
}
function limpia_perfil(){
     $('#accion').remove();
     $('#fila').remove();
     $('#hperfil').remove();
     $('input:text#perfil').val('').prop('disabled',false);
     $('input:button#btnaccion').val('Agregar').prop('disabled',false);
     $('input:button#btnlimpiar').val('Limpiar');
     $('input:button#btnlistar').prop('disabled',true);
     var mod = '../../imagenes/datatable/modificar.png';
     var eli = '../../imagenes/datatable/eliminar.png';
     $('img.modificar_disabled').attr({'src': mod, 'title': 'Modificar'}).css({'cursor': 'pointer'}).addClass('modificar');
     $('img.eliminar_disabled').attr({'src': eli, 'title': 'Eliminar'}).css({'cursor': 'pointer'}).addClass('eliminar');
}
function limpiar_privilegio() {
    $('select#modulo').select2('val',0);
    $('select#sub_modulo').select2('val',0);
    $('select#sub_modulo').find('option:gt(0)').remove().end();
    $('input:button#btnaccpriv').val('Agregar');
    $("input:checkbox").parent('div').removeClass('red');
    $("input:checkbox + a").removeClass('checked');
}
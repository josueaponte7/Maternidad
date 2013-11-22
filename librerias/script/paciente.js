$.tools.dateinput.localize("es", {
    months: 'Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Novimbre,Dicimbre',
    shortMonths: 'Ene,Feb,Mar,Abr,May,Jun,Jul,Aog,Sep,Oct,Nov,Dic',
    days: 'Domingo,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado',
    shortDays: 'Dom,Lun,Mar,Mie,Jue,Vie,Sab'
});

$(document).ready(function() {
    var TPaciente = $('#tabla').dataTable({
        "iDisplayLength": 5,
        "iDisplayStart": 0,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [5, 10, 20, 30, 40, 50],
        "oLanguage": {"sUrl": "../../librerias/js/es.txt"},
        "aoColumns": [
            {"sClass": "center", "sWidth": "8%"},
            {"sClass": "center", "sWidth": "30%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sClass": "center", "sWidth": "20%"},
            {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmpaciente      = $('form#frmpaciente');
    var $text_nac         = $frmpaciente.find('input:text#text_nac');
    var $hnac             = $frmpaciente.find('input:hidden#hnac');
    var $cedula_p         = $frmpaciente.find('input:text#cedula_p');
    var $nombre           = $frmpaciente.find('input:text#nombre');
    var $fecha_nacimiento = $frmpaciente.find('input:text#fecha_nacimiento');
    var $apellido         = $frmpaciente.find('input:text#apellido');
    var $div_cedula       = $frmpaciente.find('div#div_cedula');
    var $div_nombre       = $frmpaciente.find('div#div_nombre');
    var $div_apellido     = $frmpaciente.find('div#div_apellido');
    var $div_fecha        = $frmpaciente.find('div#div_fecha');
    var $hcod_telefono    = $frmpaciente.find('input:hidden#hcod_telefono');
    var $cod_telefono     = $frmpaciente.find('input:text#cod_telefono');
    var $telefono         = $frmpaciente.find('input:text#telefono');
    var $div_telefono     = $frmpaciente.find('div#div_telefono');
    var $hcod_celular     = $frmpaciente.find('input:hidden#hcod_celular');
    var $cod_celular      = $frmpaciente.find('input:text#cod_celular');
    var $celular          = $frmpaciente.find('input:text#celular');
    var $div_celular      = $frmpaciente.find('div#div_celular');
    var $municipio        = $frmpaciente.find('select#municipio');
    var $sector           = $frmpaciente.find('select#sector');
    var $direccion        = $frmpaciente.find('textarea#direccion');
    var $div_direccion    = $frmpaciente.find('div#div_direccion');
    var $btn_nac          = $frmpaciente.find('button:button#btn_nac');
    var $btn_codlocal     = $frmpaciente.find('button:button#btn_codlocal');
    var $btn_codcel       = $frmpaciente.find('button:button#btn_codcel');
    var $cod_celular      = $frmpaciente.find('input:text#cod_celular');
    var $btnaccion        = $frmpaciente.find('input:button#btnaccion');


    $municipio.select2();
    $sector.select2();

    $fecha_nacimiento.dateinput({
        lang: 'es',
        format: 'dd-mm-yyyy', // the format displayed for the user
        selectors: true, // whether month/year dropdowns are shown
        offset: [10, 20], // tweak the position of the calendar
        speed: 'fast', // calendar reveal speed
        firstDay: 0,
        yearRange: [-60, -9]
    });

    $('#imcedula').tooltip({
        html: true,
        placement: 'right',
        title: '<span class="requerido">Requerido</span><br/>La C&eacute;dula no puede estar en <span class="alerta">blanco</span>,<br/> ni comenzar con 0, debe seleccionar la nacionalidad en<span class="alerta">(N)</span>'
    });

    // caracteres permitidos para la caja de texto sector cuando escriba
    var val_cedula    = '1234567890';
    var val_letra     = ' abcdefghijklmnopqrstuvwxyzáéíóúñ';
    var val_direccion = ' abcdefghijklmnopqrstuvwxyzáéíóúñ#/º-1234567890';

    // validación de la caja de texto sector mientras este escribiendo
    $cedula_p.validar(val_cedula);
    $nombre.validar(val_letra);
    $apellido.validar(val_letra);
    $telefono.validar(val_cedula);
    $direccion.validar(val_direccion);

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

    $("ul#cod_local > li > span").click(function() {

        var cod_local = $(this).text();
        var id = $(this).attr('id');
        if (cod_local != 'Cod') {
            $hcod_telefono.val(id);
            $cod_telefono.val(cod_local);
            $telefono.focus();
        } else {
            $hcod_telefono.val('');
            $cod_telefono.val('');
            $telefono.val('');
        }
    });

    $("ul#cod_cel > li > span").click(function() {

        var cod_celular = $(this).text();
        var id = $(this).attr('id');
        if (cod_celular != 'Cod') {
            $hcod_celular.val(id);
            $cod_celular.val(cod_celular);
            $celular.focus();
        } else {
            $hcod_celular.val('');
            $cod_celular.val('');
            $celular.val('');
        }
    });

    $municipio.on('change', function() {
        var codigo_municipio = $(this).val();
        if (codigo_municipio > 0) {
            $sector.find('option:gt(0)').remove().end();
            $.post('../../controlador/paciente.php', {codigo_municipio: codigo_municipio, accion: 'Sector'}, function(data) {
                var option = "";
                $.each(data, function(i) {
                    option += "<option value=" + data[i].cod_sector + ">" + data[i].sector + "</option>";
                });
                $sector.append(option);
            }, 'json');
        } else {
            $sector.find('option:gt(0)').remove().end();
        }
    });

    $sector.on('change', function() {
        var codigo_municipio = $(this).val();
        if (codigo_municipio > 0) {

        }
    });

    var url = '../../controlador/paciente.php';

    $btnaccion.on('click', function() {

        if ($text_nac.val() == '') {
            $btn_nac.addClass('btn-danger');
            $btn_nac.focus();
        } else if ($cedula_p.val() === null || $cedula_p.val().length === 0 || /^\s+$/.test($cedula_p.val())) {
            $div_cedula.addClass('has-error');
            $cedula_p.focus();
        } else if ($cedula_p.val() === null || $cedula_p.val().length === 0 || /^\s+$/.test($cedula_p.val())) {
            $div_cedula.addClass('has-error');
            $cedula_p.focus();
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
        } else if ($telefono.val() === null || $telefono.val().length === 0 || /^\s+$/.test($telefono.val())) {
            $div_telefono.addClass('has-error');
            $telefono.focus();
        } else if ($telefono.val() === null || $telefono.val().length === 0 || /^\s+$/.test($telefono.val())) {
            $div_telefono.addClass('has-error');
            $telefono.focus();
        } else if ($fecha_nacimiento.val() === null || $fecha_nacimiento.val().length === 0 || /^\s+$/.test($fecha_nacimiento.val())) {
            $div_fecha.addClass('has-error');
            $fecha_nacimiento.focus();
        } else if ($cod_celular.val() == '') {
            $btn_codcel.addClass('btn-danger');
        } else if ($celular.val() === null || $celular.val().length === 0 || /^\s+$/.test($celular.val())) {
            $div_celular.addClass('has-error');
            $celular.focus();
        } else if ($municipio.val() == 0) {
            $municipio.select2("container").addClass("error_select");
            $('#s2id_municipio a.select2-choice').addClass('error_select');
        } else if ($sector.val() == 0) {
            $sector.select2("container").addClass("error_select");
            $('#s2id_sector a.select2-choice').addClass('error_select');
       } else if ($direccion.val() === null || $direccion.val().length === 0 || /^\s+$/.test($direccion.val())) {
            $div_direccion.addClass('has-error');
            $direccion.focus();
        } else {

            $('#accion').remove();
            var accion = $(this).val();
            var $accion = ' <input type="hidden" id="accion" name="accion" value="'+accion+'" />';
            $($accion).prependTo($(this));
            if(accion == 'Agregar'){
                $.post(url, $frmpaciente.serialize(), function(data) {

                    var cod_msg = parseInt(data.error_codmensaje);
                    var mensaje = data.error_mensaje;
                    var tipo = data.tipo_error;
                    window.parent.apprise(mensaje, {'textOk': 'Aceptar'});
                    if(cod_msg == 21){
                        var modificar = '<img class="modificar" title="Modificar"         style="cursor: pointer" src="../../imagenes/datatable/modificar.png" width="18" height="18" alt="Modificar"/>';
                        TPaciente.fnAddData(
                                                [
                                                    $text_nac.val()+''+$cedula_p.val(),
                                                    $nombre.val() + ' ' + $apellido.val(),
                                                    $fecha_nacimiento.val(),
                                                    $cod_telefono.val()+''+$telefono.val(),
                                                    modificar
                                                ]
                                            );
                                    limpiar();
                    }
                },'json');
        }
        }

        /*
         var cod_msg = parseInt(data.error_codmensaje);
         var mensaje = data.error_mensaje;
         var tipo = data.tipo_error;

         jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');

         TPaciente.fnAddData([$('#cedula_p').val(), $('#nombre').val() + ' ' + $('#apellido').val(), $('#fecha_nacimiento').val(), $('#telefono').val(), modificar]);

         $('#cedula_p').val('');
         $('#nombre').val('');
         $('#apellido').val('');
         $('#telefono').val('');
         $('#fecha_nacimiento').val('');
         $('#celular').val('');
         $('#municipio').val(0);
         $("select#sector").find('option:gt(0)').remove().end();
         $('#direccion').val('');
         $('#accion').remove();

         }, 'json');

         $('#accion').val('Modificar');
         $.post(url, $('#frmpaciente').serialize(), function(data) {

         var cod_msg = parseInt(data.error_codmensaje);
         var mensaje = data.error_mensaje;
         var tipo = data.tipo_error;

         var fila = $('#fila').val();

         jAlert(tipo, mensaje, 'VENTANA DE MENSAJE');

         TPaciente.fnUpdate($('#nombre').val() + ' ' + $('#apellido').val(), parseInt(fila), 1);
         TPaciente.fnUpdate($('#fecha_nacimiento').val(), parseInt(fila), 2);
         TPaciente.fnUpdate($('#telefono').val(), parseInt(fila), 3);

         limpiar();
         $('#fila').remove();
         $('#accion').remove();
         $('#btnaccion').val('Agregar');

         }, 'json');*/
    });


    $('table#tabla').on('click', 'img.modificar', function() {


        var fila = $(this).parent("td").parent().parent().children().index($(this).parent("td").parent());
        var cedula_p = $(this).parents('tr').children('td:eq(0)').text();
        var fecha_naci = $(this).parents('tr').children('td:eq(2)').text();
        //var telefono   = $(this).parents('tr').children('td:eq(3)').text();

        var $fila = '<input type="hidden" id="fila"  value="' + fila + '" name="fila">';
        $($fila).appendTo('#btnaccion');

        $('#accion').remove();

        $.post(url, {cedula_p: cedula_p, accion: 'BuscarDatos'}, function(data) {

            $('#cedula_p').val(cedula_p);
            $('#nombre').val(data.nombre);
            $('#apellido').val(data.apellido);
            $('#fecha_nacimiento').val(fecha_naci);
            $('#apellido').val(data.apellido);
            $('#fecha_nacimiento').val(fecha_naci);
            $('#telefono').val(data.telefono);
            $('#celular').val(data.celular);
            $('#direccion').val(data.direccion);
            $('#btnaccion').val('Modificar');

            var codigo_municipio = parseInt(data.cod_municipio);
            var codigo_sector = parseInt(data.cod_sector);

            $('select#municipio').find('option[value=' + codigo_municipio + ']').attr("selected", "selected");

            $("select#sector").find('option:gt(0)').remove().end();
            $.post(url, {codigo_municipio: codigo_municipio, accion: 'Sector'}, function(data) {
                var option = "";
                $.each(data, function(i) {
                    option += "<option value=" + data[i].cod_sector + ">" + data[i].sector + "</option>";
                });
                $("select#sector").append(option);
                $('select#sector').find('option[value=' + codigo_sector + ']').attr("selected", "selected");
            }, 'json');

        }, 'json');

    });

    $('#btnlimpiar').on('click', function() {
        limpiar();
    });

});

function limpiar() 
{
    $("form#frmpaciente")[0].reset();
    $('#accion').remove();
    $('#fila').remove();
    $('form#frmpaciente select').select2('val', 0);
    $('form#frmpaciente select#sector').find('option:gt(0)').remove().end();    
}

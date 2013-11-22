$.tools.dateinput.localize("es", {
    months: 'Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Novimbre,Dicimbre',
    shortMonths: 'Ene,Feb,Mar,Abr,May,Jun,Jul,Aog,Sep,Oct,Nov,Dic',
    days: 'Domingo,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado',
    shortDays: 'Dom,Lun,Mar,Mie,Jue,Vie,Sab'
});

$(document).ready(function() {
    var THistoriaparto = $('#tabla').dataTable({
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
        {"sClass": "center", "sWidth": "20%"},
        {"sWidth": "4%", "bSortable": false, "sClass": "center sorting_false", "bSearchable": false}
        ]
    });

    var $frmespecialidad  = $('form#frmespecialidad');
    var $text_nac         = $frmespecialidad.find('input:text#text_nac');
    var $hnac             = $frmespecialidad.find('input:hidden#hnac');
    var $cedula_p         = $frmespecialidad.find('input:text#cedula_p');
    var $nombre           = $frmespecialidad.find('input:text#nombre');
    var $fecha_nacimiento = $frmespecialidad.find('input:text#fecha_nacimiento');
    var $hora_nacimiento  = $frmespecialidad.find('input:text#hora_nacimiento');
    var $tamano           = $frmespecialidad.find('input:text#peso');
    var $peso             = $frmespecialidad.find('input:text#tamano');
    var $observacion      = $frmespecialidad.find('input:text#observacion');

    $fecha_nacimiento.dateinput({
        lang: 'es',
        format: 'dd-mm-yyyy', // the format displayed for the user
        selectors: true, // whether month/year dropdowns are shown
        offset: [10, 20], // tweak the position of the calendar
        speed: 'fast', // calendar reveal speed
        firstDay: 0,
        max: -1,
        yearRange: [-3,1]
    });
});



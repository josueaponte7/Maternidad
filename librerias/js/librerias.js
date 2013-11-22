$(document).ready(function() {
    $('input[type="text"], textarea').on({
        keypress: function() {
            $(this).parent('div').removeClass('has-error');
        }
    });
});

function requerido(campo) {
    if (campo.val() === null || campo.val().length === 0 || /^\s+$/.test(campo.val())) {
        return false;
    } else {
        return  true;
    }
}

function msgError(campo, msgerror) {
    campo
        .focus()
        .select()
        .css({'border': '1px solid #FF0000'})
        .after('<span class="error_val">' + msgerror + '</span>');
}

function validacion(campo, tipo) {
    var patron = '';
    switch (tipo) {
        case 'letra':
            patron = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]{2,15}|[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]{2,15}\s?[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]*$/;
        break;
        case 'orden':
            patron = /^[0-9]{1}$/;
        break;
        case 'cedula':
            patron = /^[0-9]{7,9}$/;
        break;
        case 'fechanac':
            patron = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
        break;
        case 'telefono':
            patron =/^(:?0243|0244)[0-9]{7}$/;
        break;
        case 'celular':
            patron =/^0(:?4(:?1(:?2|4|6)|2(:?4|6)))[0-9]{7}$/;
        break;
        case 'direccion':
            patron =/^[0-9a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]{10,250}$/;
        break;
        case 'telcel':
            patron =/^0(:?(243|244|412|416|414|424|426))[0-9]{7}$/;
        break;
    }
    if (!patron.test(campo.val())) {
        return false;
    } else {
        return true;
    }
}
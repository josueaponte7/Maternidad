$(document).ready(function() {
    
    $('form#frmlogin #ingresar').on('click', function() {

        if ($('#usuario').val() === null || $('#usuario').val().length === 0 || /^\s+$/.test($('#usuario').val())) {
            $('#usuario').focus().css({'border': '1px solid #FF0000'});
            return  false;
        } else if ($('#clave').val() === null || $('#clave').val().length === 0 || /^\s+$/.test($('#clave').val())) {
            $('#clave').focus().css({'border': '1px solid #FF0000'});
            return  false;
        } else {

             $.ajax({
                url: 'controlador/Usuario.php',
                type: "POST",
                data: $('#frmlogin').serialize(),
                dataType: "json",
                error: function() {
                    //alert("AJAX - error()");
                },
                beforeSend: function() {
                    //alert("AJAX - beforeSend()");
                    //$('#cargando').html('<img src="loader.gif"> espere por favor!...').fadeOut(1500);
                },
                complete: function() {
                    //alert("AJAX - complete()");
                },
                success: function(data) {
           
                    var cod_msg = parseInt(data);

                    $('#usuario').val('');
                    $('#clave').val('');
                    if (cod_msg === 14) {
                        $('span#error')
                                .css({'color': '#FF0000'})
                                .text('Usuario o Clave incorrecta')
                                .fadeIn(1000).delay(2000).fadeOut(1000);
                    } else if (cod_msg === 12) {
                        $('span#error')
                                .css({'color': '#FF0000'})
                                .text('Usuario Inactivo')
                                .fadeIn(1000).delay(2000).fadeOut(1000);
                    } else if (cod_msg === 13) {
                        $('span#error')
                                .css({'color': '#FF0000'})
                                .text('Ocurrio un Error')
                                .fadeIn(1000).delay(2000).fadeOut(1000);
                    } else if (cod_msg === 21) {
                        
                        window.location = 'controlador/seguridad/acceso.php';
                    }
                }
            });
        }
    });
});

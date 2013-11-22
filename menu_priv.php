<?php
session_start();
date_default_timezone_set('America/Caracas');
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require 'librerias/globales.php';

if (isset($_SESSION['id_usuario'])) {
    $id_usuario       = $_SESSION['id_usuario'];
    $usuario          = $_SESSION['usuario'];
    $hoy              = date("d-m-Y");
    $hora             = date("g:i A");
    $_SESSION['menu'] = 'menu_priv.php';
}
$sub_modulo = '';
$s_modulo   = '';
if (isset($_SESSION['url'])) {
    $sub_modulo = $_SESSION['url'];
}
if (isset($_SESSION['s_modulo'])) {
    $s_modulo = $_SESSION['s_modulo'];
}
require_once 'modelo/Autenticar.php';
$obj            = new Autenticar();
$result_modulos = $obj->getModulos($id_usuario);
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon"   href="imagenes/sistema/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="librerias/css/maquetacion.css"  />
        <link rel="stylesheet" type="text/css" href="librerias/css/apprise.css"  />
        <link rel="stylesheet" type="text/css" href="librerias/css/basic.css"/>
        <script type="text/javascript" src="librerias/js/jquery.1.10.js"></script>
        <script src="librerias/js/jquery.simplemodal.js" type="text/javascript"></script>
        <script type="text/javascript" src="librerias/js/apprise.js"></script>
        <script type="text/javascript" src="librerias/js/ddsmoothmenu.js"></script>
        <style type="text/css">
            div#menu,div#menu > ul > li > ul > li > span{
                font-family:Arial, Helvetica, sans-serif;
                font-size: 11px;
            }
            span#salir{
                padding:9px;
                float: right;
                border-left: 1px solid #778;
                color: #FFFFFF;
                width:53px;
                text-align: center;
                cursor: pointer;
            }
            span#salir:hover{
                background-color:#AA4B97;
            }
            .usuario{
                font-family:Arial, Helvetica, sans-serif;
                font-size: 12px;
                position: relative;
                top:-40px;
                left: 15px;
                color: #670256;
                font-weight: bold;

            }
            iframe {
                width: 100%;
                height: 100%;
                overflow: hidden;
                border: none;
                background-color:transparent;
                display:block;
                margin: auto;
                /*;width: 100%;height: 100%;min-height: 550px;max-height: 900px;*/
            }
            .activa{
                background-color: red;
            }
        </style>
        <script type="text/javascript">

            ddsmoothmenu.init({
                mainmenuid: "menu", //menu DIV id
                orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
                //classname: 'ddsmoothmenu', //class added to menu's outer DIV
                customtheme: ["#670256", "#AA4B97"],
                contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
            });
            $(document).ready(function() {

                var sub_modulo = '<?php echo $sub_modulo; ?>';
                var s_modulo = '<?php echo $s_modulo; ?>';
                if (sub_modulo != '') {
                    var cargar = sub_modulo;
                }
                if (s_modulo != '') {
                    $('div#menu > ul > li > span#' + s_modulo).css('background-color', '#AA4B97');
                }


                //$('#cuerpo').load(cargar);
                $('#ifrmcuerpo').attr('src', cargar);
                $("div#menu > ul > li > ul > li > span").click(function() {

                    var cargar = $(this).attr('id');
                    var modulo = $(this).parent('li').parent('ul').parent('li');

                    var id = $(this).parent('li').parent('ul').parent('li').children('span').attr('id');

                    $('div#menu > ul > li > span').css('background-color', '#670256');
                    modulo.children('span').css('background-color', '#AA4B97');
                    //var archivo = $(this).attr('id');
                    cargar = cargar + '?modulo=' + id;

                    //$('#cuerpo').load(cargar);

                    //var url = $(this).attr('rel');

                    $('#ifrmcuerpo').attr('src', cargar);

                });

                $("#salir").click(function() {
                    apprise('&iquest;Esta seguro que desea cerrar la sesi&oacute;n?', {'verify': true, 'textYes': 'Aceptar', 'textNo': 'Cancelar'}, function(r) {
                        if (r) {
                            window.location = 'controlador/seguridad/salir.php';
                        }
                    });
                });
            });
        </script>
    </head>
    <body >
        <div id="contenedor">
            <div id="cabecera">
                <img src="imagenes/sistema/header.png" width="960" height="150" alt="header"/>
                <div class="usuario" style="width:200px;font-family:Arial, Helvetica, sans-serif">Usuario: <?php echo $usuario ?></div>
                <div class="usuario" style="width:250px;font-family:Arial, Helvetica, sans-serif">Fecha: <?php echo $hoy . ' Hora: ' . $hora ?></div>
            </div>

            <div id="menu" class="ddsmoothmenu" style="margin-top: 3px;">
                <ul>
                    <?php
                    for ($i = 0; $i < count($result_modulos); $i++) {
                        ?>
                        <li>
                            <span  id="<?php echo "m" . $result_modulos[$i]['cod_modulo']; ?>">
                                <?php echo $result_modulos[$i]['modulo'] ?>
                            </span>
                            <?php
                            $result_submodulos = $obj->getSubModulos($id_usuario, $result_modulos[$i]['cod_modulo']);
                            ?>
                            <ul>
                                <?php
                                for ($j = 0; $j < count($result_submodulos); $j++) {
                                    ?>
                                    <li>
                                        <span  id="<?php echo trim($result_submodulos[$j]['ruta']); ?>">
                                            <?php echo $result_submodulos[$j]['sub_modulo'] ?>
                                        </span>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <span id="salir">Salir</span>
            </div>

            <div id="cuerpo" >
                <iframe align="middle" id="ifrmcuerpo" name="ifrmcuerpo"  frameborder="0" scrolling="no"></iframe>
            </div>
            <div id="pie">
                <img src="imagenes/sistema/pie.png" width="962" height="70" alt="pie"/>
            </div>
        </div>
    </body>
</html>

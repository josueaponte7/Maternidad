<?php
define('BASEPATH', dirname(__DIR__) . '/');
define('BASEURL', substr($_SERVER['PHP_SELF'], 0, - (strlen($_SERVER['SCRIPT_FILENAME']) - strlen(BASEPATH))));

require 'librerias/globales.php';

$id_usuario = 2;
require_once 'modelo/Autenticar.php';
$obj = new Autenticar();
$result_modulos = $obj->getModulos($id_usuario);
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon"   href="imagenes/sistema/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="librerias/css/maquetacion.css"  />

        <script type="text/javascript" src="librerias/js/jquery.1.10.js"></script>

        <script type="text/javascript" src="librerias/js/ddsmoothmenu.js"></script>
        
        <script type="text/javascript">

            ddsmoothmenu.init({
                mainmenuid: "menu", //menu DIV id
                orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
                //classname: 'ddsmoothmenu', //class added to menu's outer DIV
                customtheme: ["#96248D", "#D640C7"],
                contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
            });
        </script>
    </head>
    <body>  
        <div id="contenedor"> 
            <div id="cabecera">
                <div id="titulo_ca">
                    <span style="font-size: 40px;top:60px;position: relative;color:#96248D ">
                        SISTEMA CONTROL DE CITAS
                    </span>
                </div>
                <div id="logo_iz">
                    <img style="" src="imagenes/sistema/logo.png" width="300" height="130" alt="logo"/>
                </div>
            </div>
            
            <div id="menu" class="ddsmoothmenu" style="margin-top: 3px;">
                <ul>
                    <?php
                    for ($i = 0; $i < count($result_modulos); $i++) {
                        ?>
                        <li>
                            <span>
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
                                        <span >
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
                    <li>
                        <span id="salir" style="width:38px;  float: right;position: relative;left: 235px;border-right: none;border-left: 1px solid #778;">Salir</span>
                    </li>
                </ul>
            </div> 
            
            <div id="cuerpo" style="height:1000px;"></div> 
            <div id="pie">
                <span style="color:#F6B7F4">
                    Teléfonos: +58 (0243) 246 51 14 / 246 56 78 / 246.43.98 
                    <br/>
                    Persona Contacto: Dr. Adolfo Aure (Presidente)
                </span> 
            </div>
        </div>
    </body>
</html>

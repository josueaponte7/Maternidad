<?php 
date_default_timezone_set('America/Caracas');
$usuario ='Administrdor';
$hoy = date("d/m/Y");
$hora = date("g:i A"); 
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
        <script type="text/javascript">
            $(document).ready(function() {
                $('#cuerpo').load("vista/cita/asignar.php");
                $("div#menu > ul > li > ul > li > span").click(function() {
                    var directorio = $(this).parents('ul').siblings('span').attr('id');
                    var archivo = $(this).attr('id');
                    var cargar = 'vista/' + directorio + '/' + archivo + '.php';
                    $('#cuerpo').load(cargar);
                });
                
                $("#salir").click(function() {
                    alert('Cerrar la Sesión');
                    window.location = 'controlador/seguridad/salir.php';
                });
            });
        </script>
    </head>
    <body>  
        <div id="contenedor"> 
             <div id="cabecera">
                <img src="imagenes/sistema/header.png" width="960" height="150" alt="header"/>
                <div style="font-size:12px; position: relative;top:-60px;left: 15px;">Usuario:<?php echo $usuario ?></div>
                <div style="font-size:12px;position: relative;top:-55px;left: 15px;">Fecha:<?php echo $hoy .' Hora: '.$hora ?></div>
            </div>
            
            <div id="menu" class="ddsmoothmenu" style="margin-top: 3px;">
                <ul>
                    <li>
                        <span id="cita">Citas</span>
                        <ul>
                            <li><span id="asignar">Asignar</span></li>
                        </ul>
                    </li>
                    <li>
                        <span id="pacientes">Paciente</span>
                        <ul>
                            <li><span id="paciente">Agregar</span></li>
                            <li><span id="historia">Historia Medica</span></li>
                            <li><span id="historiaparto">Historia Parto</span></li>
                        </ul>
                    </li>
                    
                    <li>
                        <span id="medicos">Medico</span>
                        <ul>
                            <li><span id="personalmedico">Agregar</span></li>
                        </ul>
                    </li>
                    
                    <li>
                        <span id="mantenimiento">Mantenimiento</span>
                        <ul>
                            <li><span id="consultorio">Consultorio</span></li>
                            <li><span id="sector">Sector</span></li>
                            <li><span id="especialidad">Especialidad</span></li>
                        </ul>
                    </li>
                    
                    <li>
                        <span>Reportes</span>
                        <ul>
                            <li><span>Cita</span></li>
                            <li><span>Paciente</span></li>
                            <li><span>Medico</span></li>
                            <li><span>Estadistica</span></li>
                        </ul>
                    </li>
                    
                    <li>
                        <span id="seguridad">Seguridad</span>
                        <ul>
                            <li><span id="modulo">Modulos</span></li>
                            <li><span id="privilegio">Privilegios Usuario</span></li>
                            <li><span id="perfiles">Perfiles Usuario</span></li>
                            <li><span id="usuario">Administración Usuario</span></li>
                        </ul>
                    </li>
                    
                    <li>
                        <span>Bitacora</span>
                        <ul>
                            <li><span>Bitacora Usuario</span></li>
                            <li><span>Bitacora del Sistema</span></li>
                        </ul>
                    </li>
                    
                    <li>
                        <span id="salir" style="width:53px;  float: right;position: relative;left: 220px;border-right: none;border-left: 1px solid #778;">Salir</span>
                    </li>
                </ul>
            </div> 
            
            <div id="cuerpo" ></div> 
            <div id="pie">
                <img src="imagenes/sistema/pie.png" width="962" height="70" alt="pie"/>

            </div>
        </div>
    </body>
</html>

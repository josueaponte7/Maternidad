<?php

//define('BASEPATH', str_replace("\\", "/", $system_path));
define('BASEPATH', '');

require_once '../../modelo/Usuario.php';
$user = new Usuario();
if (!isset($_POST['accion'])) {
    
} else {

    $accion = $_POST['accion'];

    if (isset($_POST['usuario'])) {
        $usuario = $_POST['usuario'];
    } else {
        $usuario = "";
    }
    if (isset($_POST['clave'])) {
        $clave = $_POST['clave'];
    } else {
        $usuario = "";
    }
    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
    } else {
        $tipo = "";
    }
    if (isset($_POST['status'])) {
        $status = $_POST['status'];
    } else {
        $status = "";
    }

    switch ($accion) {
        case 'Ingresar':
            $passw = $user->loginUsuario($usuario, $clave, $tipo, $status);
            if ($passw === TRUE) {
                echo 500;
            } else if ($passw == 4) {
                echo $passw;
            } else if ($passw == 2) {
                echo $passw;
            } else if ($passw == 1) {
                echo $passw;
            } else {
                echo 0;
            }
            break;
    }
}
?>

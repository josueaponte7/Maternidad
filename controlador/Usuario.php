<?php

//define('BASEPATH', str_replace("\\", "/", $system_path));
define('BASEPATH', '');
require_once '../modelo/Autenticar.php';
$user = new Autenticar();

if (!isset($_POST['accion'])) {

} else {

    $accion = $_POST['accion'];

    if (isset($_POST['usuario'])) {
        $usuario = addslashes($_POST['usuario']);
    }
    if (isset($_POST['clave'])) {
        $clave = addslashes($_POST['clave']);
    }
    if (isset($_POST['perfil'])) {
        $perfil = $_POST['perfil'];
    }
    if (isset($_POST['estatus'])) {
        $estatus = $_POST['estatus'];
    }
    switch ($accion) {
        case 'Agregar':
            $resultado = $user->addUsuario($usuario, $clave, $perfil, $estatus);
            echo json_encode($resultado);
            break;
        case 'Ingresar':
            $passw = $user->loginUsuario($usuario, $clave);
            if ($passw === TRUE) {
                echo 21;
            } else if ($passw == 14) {
                echo $passw;
            } else if ($passw == 12) {
                echo $passw;
            } else if ($passw == 11) {
                echo $passw;
            } else {
                echo 13;
            }
     break;
    }
}

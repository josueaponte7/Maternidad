<?php

define('BASEPATH', '');

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';
ob_start();
$firephp = new FirePHP();

//$firephp->log($_POST);
//exit;
require_once '../../modelo/Modulo.php';
$obj = new Modulo();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['cod_modulo'])) {
        $data['cod_modulo'] = addslashes($_POST["cod_modulo"]);
    }
    if (isset($_POST['modulo'])) {
        $data['modulo'] = addslashes($_POST["modulo"]);
    }

    switch ($accion) {
        case 'Agregar':
            $resultado = $obj->add($data);
            echo json_encode($resultado);
        break;
        case 'Modificar':
            $resultado = $obj->edit($data);
            echo json_encode($resultado);
        break;
        case 'Eliminar':
            $resultado = $obj->del($data);
            echo json_encode($resultado);
        break;
    }
}
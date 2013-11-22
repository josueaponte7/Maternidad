<?php

define('BASEPATH', '');

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';
ob_start();
$firephp = new FirePHP();

require_once '../../modelo/Perfil.php';
$obj = new Perfil();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['perfil'])) {
        $data['perfil'] = addslashes($_POST["perfil"]);
    }
    if (isset($_POST['cod_modulo'])) {
        $data['cod_modulo'] = addslashes($_POST["cod_modulo"]);
    } 
    if (isset($_POST['cod'])) {
        $data['cod'] = addslashes($_POST["cod"]);
    }
    if (isset($_POST['sub_modulo'])) {
        $data['sub_modulo'] = addslashes($_POST["sub_modulo"]);
    }
    if (isset($_POST['codigo'])) {
        $data['codigo'] = addslashes($_POST["codigo"]);
    }
    if (isset($_POST['cod_perfil'])) {
        $data['cod_perfil'] = addslashes($_POST["cod_perfil"]);
    }
    if (isset($_POST['privilegio'])) {
        foreach ($_POST["privilegio"] as $valor) {
             $data['privilegio'][] = $valor;
        }
    } 
    switch ($accion) {
        case 'AgregarPerfil':
            $resultado = $obj->addPerfil($data);
            
            echo json_encode($resultado);
        break;
        case 'Buscar':
            $resultado = $obj->getPrivilegio($privilegio);
            echo json_encode(array('privilegio' => $resultado['privilegio'], 'privilegio' => $resultado['privilegio']));
        break;
        case 'ModificarPerfil':
            $resultado = $obj->editPerfil($data);
            echo json_encode($resultado);
        break;
        case 'EliminarPerfil':
            $resultado = $obj->delPerfil($data);
            echo json_encode($resultado);
        break;
        case 'BuscarSub':
            $resultado = $obj->getSubModulos($data);
            if (count($resultado) > 0) {
                for ($j = 0; $j < count($resultado); $j++) {
                    $datos[] = array('codigo'      => $resultado[$j]['codigo'], 'descripcion' => $resultado[$j]['descripcion']);
                }
                echo json_encode($datos);
            } else {
                echo 0;
            }
        break;
        case 'BuscarPrivilegios':
            $resultado = $obj->getPrivilegioPerfil($data);
            if (count($resultado) > 0) {
                for ($j = 0; $j < count($resultado); $j++) {
                    $datos[] = array(
                        'modulo'        => $resultado[$j]['modulo'],
                        'cod_submodulo' => $resultado[$j]['cod_submodulo'],
                        'sub_modulo'    => $resultado[$j]['sub_modulo'],
                        'privilegios'   => $resultado[$j]['privilegios']
                    );
                }
                echo json_encode($datos);
            } else {
                echo 0;
            }
        break;
        case 'AgregarPrivilegios':
            $resultado = $obj->addPrivilegios($data);
            echo json_encode($resultado);
        break;
    }
}
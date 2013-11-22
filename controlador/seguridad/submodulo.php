<?php

define('BASEPATH', '');
require_once '../../modelo/SubModulo.php';
$obj = new SubModulo();
//$resulsub  = $obj->getSubModulos(1);

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';
ob_start();
$firephp = new FirePHP();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);

    if (isset($_POST['cod_modulo'])) {
    $data['cod_modulo'] = addslashes($_POST["cod_modulo"]);
    } else if(isset ($_POST['nommodulo'])) {
        $data['cod_modulo'] = $_POST['nommodulo'];
    }
    if (isset($_POST['cod_submodulo'])) {
        $data['cod_submodulo'] = addslashes($_POST["cod_submodulo"]);
    }
    if (isset($_POST['submodulo'])) {
        $data['submodulo'] = addslashes($_POST["submodulo"]);
    }
    if (isset($_POST['ruta'])) {
        $data['ruta'] = addslashes($_POST["ruta"]);
    }

    switch ($accion) {
        case 'Agregar':
            $result = $obj->addSubModulo($data);
            echo json_encode($result);
        break;

        case 'Modificar':
            $resultado = $obj->editSubModulo($data);
            echo json_encode($resultado);
        break;

        case 'Buscar':
            $resultado = $obj->getSubModulo($cod_submodulo);
            echo json_encode(array('sub_modulo' => $resultado['sub_modulo'], 'orden_submodulo' => $resultado['orden_submodulo']));
        break;

        case 'Eliminar':
            $resultado = $obj->deleteSubModulo($data);
            echo json_encode($resultado);
        break;
        case 'BuscarSubModulos':
            $datos = array();
            $resultado = $obj->getSubModulos($data);
            for ($j = 0; $j < count($resultado); $j++) {
                $datos[] = array('cod_submodulo' => $resultado[$j]['cod_submodulo'], 'submodulo' => $resultado[$j]['sub_modulo']);
            }
            echo json_encode($datos);
        break;
        case 'BuscarSub':
            $data      = array();
            $resultado = $obj->getSubModuloAll();
            for ($j = 0; $j < count($resultado); $j++) {
                $data[] = array('cod_modulo' => $resultado[$j]['cod_modulo'], 'modulo' => $resultado[$j]['modulo'], 'cod_submodulo' => $resultado[$j]['cod_submodulo'], 'submodulo' => $resultado[$j]['sub_modulo']);
            }
            echo json_encode($data);
        break;
        case 'CodMod':
            $resultado = $obj->getCodMod($data);
            echo $resultado;
        break;
        case 'BuscarRuta':
            $result = $obj->buscarRuta($data);
            echo $result['ruta'];
        break;
    }
}


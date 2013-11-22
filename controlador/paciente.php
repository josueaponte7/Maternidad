<?php

define('BASEPATH', '');
require_once '../modelo/Paciente.php';
$obj = new Paciente();

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';
$firephp = new FirePHP();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['hnac'])) {
        $datos['nacionalidad'] = addslashes($_POST["hnac"]);
    }
    if (isset($_POST['cedula_p'])) {
        $datos['cedula_p'] = addslashes($_POST["cedula_p"]);
    }
    if (isset($_POST['nombre'])) {
        $datos['nombre'] = addslashes($_POST["nombre"]);
    }
    if (isset($_POST['apellido'])) {
        $datos['apellido'] = addslashes($_POST["apellido"]);
    }
    if (isset($_POST['fecha_nacimiento'])) {
        $datos['fecha_nacimiento'] = addslashes($_POST["fecha_nacimiento"]);
    }
    if (isset($_POST['hcod_telefono'])) {
        $datos['cod_telefono'] = addslashes($_POST["hcod_telefono"]);
    }
    if (isset($_POST['telefono'])) {
        $datos['telefono'] = addslashes($_POST["telefono"]);
    }
    if (isset($_POST['hcod_celular'])) {
        $datos['cod_celular'] = addslashes($_POST["hcod_celular"]);
    }
    if (isset($_POST['celular'])) {
        $datos['celular'] = addslashes($_POST["celular"]);
    }
    if (isset($_POST['direccion'])) {
        $datos['direccion'] = addslashes($_POST["direccion"]);
    }
    if (isset($_POST['sector'])) {
        $datos['sector'] = addslashes($_POST["sector"]);
    }
    if (isset($_POST['codigo_municipio'])) {
        $datos['codigo_municipio'] = addslashes($_POST["codigo_municipio"]);
    }
    switch ($accion) {
        case 'Agregar':
            $resultado = $obj->addPaciente($datos);
            echo json_encode($resultado);
        break;
        case 'BuscarDatos':
            $resultado = $obj->getPaciente($datos);
            echo json_encode(array(
                'nombre'        => $resultado['nombre'],
                'apellido'      => $resultado['apellido'],
                'telefono'      => $resultado['telefono'],
                'celular'       => $resultado['celular'],
                'direccion'     => $resultado['direccion'],
                'cod_municipio' => $resultado['cod_municipio'],
                'cod_sector'    => $resultado['cod_sector'],
            ));
        break;
        case 'Modificar':
            $obj->editPaciente($datos);
        break;
        case 'Eliminar':
            $obj->deletePaciente($cedula_p);
        break;
        case 'Sector':
            $resultado = $obj->getSector($datos);
                for ($j = 0; $j < count($resultado); $j++) {
                    $data[] = array(
                                    'cod_sector' => $resultado[$j]['cod_sector'],
                                    'sector'     => $resultado[$j]['sector']
                                );
                }
            echo json_encode($data);
        break;
    }
}
?>

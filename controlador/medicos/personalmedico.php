<?php
define('BASEPATH', '');


require_once '../../modelo/personalMedico.php';
$obj = new personalMedico();

require_once '../../FirePHP/fb.php';
$firephp = new FirePHP();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['hnac'])) {
        $datos['nacionalidad'] = addslashes($_POST["hnac"]);
    }
    if (isset($_POST['cedula_pm'])) {
        $datos['cedula_pm'] = addslashes($_POST["cedula_pm"]);
    }
    if (isset($_POST['nombre'])) {
        $datos['nombre'] = addslashes($_POST["nombre"]);
    }
    if (isset($_POST['apellido'])) {
         $datos['apellido'] = addslashes($_POST["apellido"]);
    }
    if (isset($_POST['hcod_telefono'])) {
        $datos['cod_telefono'] = addslashes($_POST["hcod_telefono"]);
    }
    if (isset($_POST['telefono'])) {
        $datos['telefono'] = addslashes($_POST["telefono"]);
    }
    if (isset($_POST['direccion'])) {
        $datos['direccion'] = addslashes($_POST["direccion"]);
    }
    if (isset($_POST['num_cons'])) {
        $datos['num_cons'] = addslashes($_POST["num_cons"]);
    }
    if (isset($_POST['cod_esp'])) {
        $datos['cod_esp'] = addslashes($_POST["cod_esp"]);
    }
    if (isset($_POST['turno'])) {
        $datos['cod_turno'] = addslashes($_POST["turno"]);
    }

    switch ($accion) {
        case 'Agregar':
            $resultado = $obj->addpersonalMedico($datos);
            echo json_encode($resultado);
        break;
        case 'BuscarCons':
            $resultado = $obj->getConsultorio($datos);
            $data = array();
            for ($j = 0; $j < count($resultado); $j++) {
                $data[] = array('num_consultorio' => $resultado[$j]['num_consultorio'], 'consultorio' => $resultado[$j]['consultorio']);
            }
            echo json_encode($data);
        break;

        case 'BuscarTur':
            $resultado = $obj->getTurno($datos);
            $data = array();
            for ($j = 0; $j < count($resultado); $j++) {
                $data[] = array('cod_turno' => $resultado[$j]['cod_turno'], 'turno' => $resultado[$j]['turno']);
            }
            echo json_encode($data);
        break;

        case 'BuscarDatos':
            $resultado = $obj->getMedico($datos);
             echo json_encode($resultado);
        break;
        case 'Modificar':
            $obj->editpersonalMedico($datos);
      break;
      case 'Eliminar':
            $obj->deletepersonalMedico($cedula_pm);
      break;
    }
}
?>

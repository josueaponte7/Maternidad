<?php

define('BASEPATH', '');
require_once '../../FirePHP/fb.php';
$firephp = new FirePHP();
require_once '../../modelo/Consultorio.php';
$obj = new Consultorio();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {
    $accion = addslashes($_POST['accion']);
    if(isset($_POST['consultorio'])){
        $datos['consultorio'] = $_POST['consultorio'];
    }
    if(isset($_POST['nombre'])){
        $datos['nombre'] = $_POST['nombre'];
    }
    if(isset($_POST['especialidad'])){
        $datos['especialidad'] = $_POST['especialidad'];
    }
    if(isset($_POST['turno'])){
        $datos['turno'] = $_POST['turno'];
    }
    if(isset($_POST['num_consultorio'])){
        $datos['num_consultorio'] = $_POST['num_consultorio'];
    }
    if(isset($_POST['cod_turnos'])){
        $datos['cod_turnos'] = $_POST['cod_turnos'];
    }
     switch ($accion) {
        case 'Agregar':
            $resultado = $obj->addConsultorio($datos);
            echo json_encode($resultado);
        break;
        case 'BuscarDatos':
            $resultado = $obj->getDatosConsultorio($datos);
            echo json_encode(array(
                'cod_especialidad' => $resultado['cod_especialidad'],
                'turnos'        => $resultado['turnos']
            ));
        break;
        case 'BuscarMedicoTurno':
            $resultado = $obj->getMedico($datos);
            echo json_encode(array(
                'total'     => $resultado['total'],
                'cod_turno' => $resultado['cod_turno']
            ));
        break;
        case 'Modificar':
            $obj->editConsultorio($datos);
        break;
        case 'Horario':
            $resultado = $obj->getHorario($datos);
            for ($j = 0; $j < count($resultado); $j++) {
                $data[] = array(
                    'codigo'      => $resultado[$j]['codigo'],
                    'descripcion' => $resultado[$j]['descripcion']
                    );
            }
        echo json_encode($data);
        break;
    }
}
?>

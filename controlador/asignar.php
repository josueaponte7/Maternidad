<?php

define('BASEPATH', '');

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

$firephp = new FirePHP();

require_once '../modelo/Asignar.php';
$obj = new Asignar();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>
");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['cedula_p'])) {
        $datos['cedula_p'] = addslashes($_POST["cedula_p"]);
    }
    if (isset($_POST['num_consultorio'])) {
        $datos['num_consultorio'] = addslashes($_POST["num_consultorio"]);
    }
    if (isset($_POST['fecha'])) {
        $datos['fecha'] = addslashes($_POST["fecha"]);
    }
    if (isset($_POST['hcod_turno'])) {
        $datos['cod_turno'] = addslashes($_POST["hcod_turno"]);
    }
    if (isset($_POST['hnac'])) {
        $datos['nacionalidad'] = addslashes($_POST["hnac"]);
    }
    if (isset($_POST['cod_consu_horario'])) {
        $datos['cod_consu_horario'] = addslashes($_POST["cod_consu_horario"]);
    }
    if (isset($_POST['observacion'])) {
        $datos['observacion'] = addslashes($_POST["observacion"]);
    }
    switch ($accion) {
        case 'Asignar':
            $resultado = $obj->addAsignar($datos);
            echo json_encode($resultado);
        break;
        case 'BuscarDatos':
            $resultado = $obj->BuscarDatos($datos);
            if ($resultado === FALSE) {
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 17,
                    'error_mensaje'    => '<span style="color:#FF0000;margin-left:40%">ERROR<br/>Est&aacute; Cédula no se encuentra registrada</span>'
                    ));
            }else if($resultado === 4){
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 15,
                    'error_mensaje'    => '
<span style="color:#FF0000;margin-left:40%">ERROR<br/>Debe esperar 24 horas para generar una cita nueva</span>'
                ));
            } else {
                $datos = array(
                    'total'      => $resultado['total'],
                    'fech_max'   => $resultado['fech_max'],
                    'asistencia' => $resultado['asistencia'],
                    'nombre'     => $resultado['nombre'],
                    'apellido'   => $resultado['apellido'],
                    'telefono'   => $resultado['telefono']
                );
                echo json_encode($datos);
            }

        break;
        case 'CancelarCita':
            $resultado = $obj->CancelarCita($datos);
            echo json_encode($resultado);
        break;
        case 'BuscarCitas':
            $resultado = $obj->BuscarCitas($datos);
            if (!empty($resultado)) {
                for ($j = 0; $j
<                   count($resultado); $j++) {
                        $data[] = array(
                        'dias'        =>$resultado[$j]['dias'],
                        'asistencia'  => $resultado[$j]['asistencia'],
                        'fecha'       => $resultado[$j]['fecha'],
                        'nombre'      => $resultado[$j]['nombre'],
                        'consultorio' => $resultado[$j]['consultorio'],
                        'observacion' => $resultado[$j]['observacion']
                    );
                }
                echo json_encode($data);
            } else {
                echo 0;
            }
        break;
        case 'Modificar':
            $obj->editEspecialidad($cod_esp, $especialidad);
        break;
        case 'EliminarCita':
            $resultado = $obj->EliminarCita($datos);
            if ($resultado === TRUE) {
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 23,
                    'error_mensaje'    => 'El Registro ha sido Eliminado con exito'
                ));
            }else{
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 16,
                    'error_mensaje'    => 'Ocurrio un error comuniquese con informatica'
                ));
            }
        break;
        case 'BuscarTurno':
            $resultado = $obj->getDatosCons($datos);

            if ((boolean)$resultado == 1) {
                for ($j = 0; $j< count($resultado); $j++) {
                    $data[] = array(
                        'cod_consu_horario' =>$resultado[$j]['cod_consu_horario'],
                        'cod_turno'         => $resultado[$j]['cod_turno'],
                        'nombres'           => $resultado[$j]['nombres'],
                        'turno'             => $resultado[$j]['turno']
                    );
                }
                echo json_encode($data);
            }else{
                echo 0;
            }
        break;
    }
}
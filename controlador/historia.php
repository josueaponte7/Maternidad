<?php

define('BASEPATH', '');

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

$firephp = new FirePHP();

require_once '../modelo/Historia.php';
$obj = new Historia();

if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['hnac'])) {
        $data['nacionalidad'] = addslashes($_POST["hnac"]);
    }
    if (isset($_POST['cedula_p'])) {
        $data['cedula_p'] = addslashes($_POST["cedula_p"]);
    }
    if (isset($_POST['hhistoria'])) {
       $data['historia']  = addslashes($_POST["hhistoria"]);
    }
    if (isset($_POST['lugar_control'])) {
        $data['lugar_control'] = addslashes($_POST["lugar_control"]);
    }
    if (isset($_POST['fur'])) {
        $data['fur'] = addslashes($_POST["fur"]);
    }
    if (isset($_POST['fpp'])) {
        $data['fpp'] = addslashes($_POST["fpp"]);
    }
    if (isset($_POST['diagnostico'])) {
        $data['diagnostico'] = addslashes($_POST["diagnostico"]);
    }
    if (isset($_POST['observacion'])) {
         $data['observacion']  = addslashes($_POST["observacion"]);
    }
    if (isset($_POST['hnac'])) {
        $data['nacionalidad'] = addslashes($_POST["hnac"]);
    }
    if (isset($_POST['tamano'])) {
        $data['tamano'] = addslashes($_POST["tamano"]);
    }
    if (isset($_POST['peso'])) {
        $data['peso'] = addslashes($_POST["peso"]);
    }
    if (isset($_POST['tension'])) {
        $data['tension'] = addslashes($_POST["tension"]);
    }
    switch ($accion) {
        case 'Agregar':
            $resultado = $obj->addhistoria($data);
            echo json_encode($resultado);
        break;
        case 'BuscarDatos':
            $resultado = $obj->BuscarDatos($data);
   
            if($resultado === FALSE){
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 17,
                    'error_mensaje'    => 'La Cédula no esta registrada'
                ));
            } else if ($resultado === 1) {
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 18,
                    'error_mensaje'    => '<span style="color:#FF0000">ERROR:La C&eacute;dula no tiene Historia para cargar</span>'
                ));
            } else if ($resultado === 2) {
                echo json_encode(array(
                    'tipo_error'       => 'error',
                    'error_codmensaje' => 19,
                    'error_mensaje'    => '<span style="color:#FF0000">ERROR:La fecha de la cita no ha llegado no puede cargar la historia</span>'
                ));
            }else{
                echo json_encode(
                        array(
                            'his_o'              => $resultado['his_o'],
                            'historia'           => $resultado['historia'],
                            'nombre'             => $resultado['nombre'],
                            'apellido'           => $resultado['apellido'],
                            'fecha'              => $resultado['fecha'],
                            'edad'               => $resultado['edad'],
                            'tamano'             => $resultado['tamano'],
                            'peso'               => $resultado['peso'],
                            'tension'            => $resultado['tension'],
                            'fur'                => $resultado['fur'],
                            'fpp'                => $resultado['fpp'],
                            'lugar_control'      => $resultado['lugar_control'],
                            'diagnostico'        => $resultado['diagnostico'],
                            'observacion_medica' => $resultado['observacion_medica']
                        )
                );
            }
        break;
        case 'Modificar':
            $obj->edithistoria($cedula_p, $historia, $lugar_control, $fur, $fpp, $diagnostico, $observacion);
            break;
        case 'Eliminar':
            $obj->deletehistoria($cedula_p);
            break;
    }
}

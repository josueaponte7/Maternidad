<?php
define('BASEPATH', '');

require_once '../modelo/personalMedico.php';
$obj = new personalMedico();

print_r($_POST);
exit;
if (!isset($_POST['accion'])) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
} else {

    $accion = addslashes($_POST['accion']);
    if (isset($_POST['cedula_pm'])) {
        $cedula_pm = addslashes($_POST["cedula_pm"]);
    } else {
        $cedula_pm = "";
    }
    if (isset($_POST['nombre'])) {
        $nombre = addslashes($_POST["nombre"]);
    } else {
        $nombre = "";
    }
    if (isset($_POST['apellido'])) {
        $apellido = addslashes($_POST["apellido"]);
    } else {
        $apellido = "";
    }
    if (isset($_POST['telefono'])) {
        $telefono = addslashes($_POST["telefono"]);
    } else {
        $telefono = "";
    }
    if (isset($_POST['direccion'])) {
        $direccion = addslashes($_POST["direccion"]);
    } else {
        $direccion = "";
    }
    if (isset($_POST['num_const'])) {
        $num_const = addslashes($_POST["num_const"]);
    } else {
        $num_const = "";
    }
    if (isset($_POST['cod_esp'])) {
        $datos['cod_espe'] = addslashes($_POST["cod_esp"]);
    } else {
        $datos['cod_espe'] = "";
    }

    switch ($accion) {
        case 'Agregar':
            $obj->addpersonalMedico($cedula_pm, $nombre, $apellido, $telefono, $direccion);
        break;
        case 'Buscar':
            $resultado = $obj->getpersonalMedico($cedula_pm);
            echo json_encode(array('personalMedico' => $resultado['personalMedico'], 'cedula_pm' => $resultado['cedula_pm']));
            break;
        case 'BuscarEsp':
            $resultado = $obj->getConsultorio($datos);
            //echo json_encode(array('personalMedico' => $resultado['personalMedico'], 'cedula_pm' => $resultado['cedula_pm']));
        break;
        case 'Modificar':
            $obj->editpersonalMedico($cedula_pm, $nombre, $apellido, $telefono, $direccion, $num_const, $cod_esp);
            break;
        
        case 'Eliminar':
            $obj->deletepersonalMedico($cedula_pm);
            break;
    }
}
?>

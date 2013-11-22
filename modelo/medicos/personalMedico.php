<?php

if (!defined('BASEPATH'))
   exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");

require_once '../../FirePHP/fb.php';



require_once '../MySQL.php';
require_once '../Validacion.php';

class personalMedico extends Validacion {

    private $_mensaje;
    private $_cod_msg;

    public function __construct() {
        $this->_mysql = MySQL::crear();
        $this->_firephp = FirePHP::getInstance(true);
    }

    public function addpersonalMedico($cedula_pm, $nombre, $apellido, $telefono, $direccion) {
        $existe = $this->_mysql->existeReg("personal_medico", "cedula_pm='" . $cedula_pm . "'");

        try {
            if ($this->validar($cedula_pm, 'cedula_pm') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);

//            } else if ($this->validar($nombre, 'letras') === FALSE) {
//
//                $this->_mensaje = "Error";
//                throw new Exception($this->_mensaje, 300);
//                
//            } else if ($this->validar($apellido, 'letras') === FALSE) {
//
//                $this->_mensaje = "Error";
//                throw new Exception($this->_mensaje, 300);
//                
//            } else if ($this->validar($telefono, 'telefono') === FALSE) {
//
//                $this->_mensaje = "Error";
//                throw new Exception($this->_mensaje, 300);
//                
//           } else if ($this->validar($direccion, 'direccion') === FALSE) {
//
//                $this->_mensaje = "Error";
//                throw new Exception($this->_mensaje, 300);
//                               
//                
            } else if ($existe === TRUE) {

                $this->_mensaje = "La Cedula se Encuentra Registrada en el Sistema";
                $this->_cod_msg = 401;
                throw new Exception($this->_mensaje, $this->_cod_msg);
            } else {

                $data = array(
                    "cedula_pm" => $cedula_pm,
                    "nombre"    => $nombre,
                    "apellido"  => $apellido,
                    "telefono"  => $telefono,
                    "direccion" => $direccion
                );

                $insert = $this->_mysql->insert('personal_medico', $data);
                if ($insert === TRUE) {
                    $this->_mensaje = 'Registro con exito';
                    $this->_cod_msg = 500;
                }

                throw new Exception($this->_mensaje, $this->_cod_msg);
            }
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }

    public function getpersonalMedico($cedula_pm) {
        $data = array('tabla' => 'personalMedico', 'campos' => 'cedula_pm,nombre,apellido,telefono,direccion,num_const,cod_esp', 'condicion' => "cedula_pm =$cedula_pm");
        $result = $this->_mysql->row($data);
        return $result;
    }

    public function editpersonalMedico($cedula_pm, $nombre, $apellido, $telefono, $direccion, $num_const, $cod_esp) {
        try {

            if ($this->validar($cedula_pm, 'cedula_pm') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($nombre, 'letras') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($apellido, 'letras') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($telefono, 'telefono') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($direccion, 'direccion') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else {
                $data = array('cedula_pm' => $cedula_pm, 'nombre' => $nombre, 'apellido' => $apellido, 'telefono' => $telefono, 'direccion' => $direccion);
                $where = "cedula_p='$cedula_p'";

                $update = (boolean) $this->_mysql->update('personalMedico', $data, $where);

                if ($update === TRUE) {
                    $this->_mensaje = 'Modificacion con exito';
                    $this->_cod_msg = 501;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                }
            }
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }

    public function deletepersonalMedico($cedula_pm) {
        $delete = $this->_mysql->delete("personalMedico", "cedula_pm=$cedula_pm");

        try {
            if ($delete === TRUE) {
                $this->_mensaje = 'Eliminación con exito';
                $this->_cod_msg = 502;
                throw new Exception($this->_mensaje, $this->_cod_msg);
            }
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }

    public function getPersonalMedicoAll() {
        $data = array(
            "tabla" => "personal_medico", 
            "campos" => "cedula_pm,CONCAT_WS(' ',nombre,apellido) AS nombres, telefono, direccion");
        $result = $this->_mysql->select($data, FALSE);
        return $result;
    }
    
    public function getEspecialidad() {
        $data   = array('tabla'  => 'especialidad', 'campos' => "cod_especialidad,especialidad");
        $result = $this->_mysql->select($data, FALSE);
        return $result;
    }
    
    public function getConsultorio($datos) {
        
        $this->_firephp->info('paseee');
        exit;
        $data   = array(
            'tabla'  => 'consultorio_horario  ch,consultorio c',
            'campos' => "c.consultorio",
            'condicion'=>'ch.num_consultorio=c.num_consultorio AND ch.cod_especialidad=$cod_esp'
        );
        $result = $this->_mysql->select($data, FALSE);
        return $result;
    }
}

?>

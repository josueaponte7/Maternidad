<?php

if (!defined('BASEPATH')){
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}
require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

require_once 'Conexion.php';
class Asignar extends Conexion {

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;

    public function __construct() {

        $this->_firephp = FirePHP::getInstance(true);
    }

    public function addAsignar($data) {

        $cedula_p        = $data['cedula_p'];
        $num_consultorio = $data['num_consultorio'];
        $fecha           = $data['fecha'];
        $cedula_pm       = $data['cedula_pm'];

        //$exi_ced = $this->_mysql->existeReg("cita", "cedula_p='" . $cedula_p . "'");
        $exi_ced = FALSE;
        try {
             if ($exi_ced === TRUE) {
            //if ($exi_ced === TRUE) {
                $this->_tipoerror = 'error';
                $this->_mensaje   = 'El nombre de la Especialidad se encuentra Registrada';
                $this->_cod_msg   = 15;
                throw new Exception($this->_mensaje, $this->_cod_msg);
            } else {

                $fecha = $this->formateaBD($fecha);

                $data = array(
                    "cedula_p"        => $cedula_p,
                    "num_consultorio" => $num_consultorio,
                    "fecha"           => $fecha,
                    "cedula_pm"       => $cedula_pm
                );

                $insert = $this->insert('cita', $data);
                if ($insert === TRUE) {
                    $this->_tipoerror = 'success';
                    $this->_mensaje = 'El Registro ha sido guardado exitosamente';
                    $this->_cod_msg = 21;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                }else{
                    $this->_tipoerror = 'error';
                    $this->_mensaje   = 'Ocurrio un error comuniquese con informatica';
                    $this->_cod_msg   = 16;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                }

            }
        } catch (Exception $e) {
            echo json_encode(array(
                'tipo_error'       => $this->_tipoerror,
                'error_codmensaje' => $e->getCode(),
                'error_mensaje'    => $e->getMessage()
            ));
        }
    }

    public function getAsignar($data) {

        $cedula_p = $data['cedula_p'];
        $exi_ced  = $this->recordExists("paciente", "cedula_p='" . $cedula_p . "'");

        if ($exi_ced === FALSE) {
            return $this->_cod_msg = FALSE;
        } else {
            $data   = array(
                'tabla'     => 'paciente',
                'campos'    => 'nombre,apellido,telefono',
                'condicion' => "cedula_p  =$cedula_p");
            $result = $this->row($data);

            return $result;
        }
    }

    public function editEspecialidad($cod_esp, $especialidad) {
        try {

            if ($this->validar($especialidad, 'especialidad') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else {
                $data  = array('especialidad' => $especialidad);
                $where = "cod_esp='$cod_esp'";

                $update = (boolean) $this->update('especialidad', $data, $where);

                if ($update === TRUE) {
                    $this->_tipoerror = 'info';
                    $this->_mensaje   = 'Modificacion con exito';
                    $this->_cod_msg   = 22;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                } else {
                    $this->_tipoerror = 'error';
                    $this->_mensaje   = 'Ocurrio un error comuniquese con informatica';
                    $this->_cod_msg   = 16;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                }
            }
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }

    public function deletePrivilegio($codigo_privilegio) {
        $delete = $this->delete("privilegio", "codigo_privilegio=$codigo_privilegio");

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

    public function getAsignarAll() {
        $data   = array(
            'tabla'  => 'cita',
            'campos' => "cedula_p, num_consultorio, fecha"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }
     public function getConsultorio() {
        $data   = array(
            'tabla'  => 'consultorio c',
            'campos' => "c.num_consultorio,c.consultorio "
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getDatosCons($datos) {

        $num_consultorio = $datos['num_consultorio'];
        $data   = array(
            'tabla'  => 'consultorio_medico cm,consultorio c,  personal_medico pm,consultorio_horario ch,turno t,hora h',
            'campos' => "c.num_consultorio,
                         pm.cedula_pm,
                         CONCAT_WS(' ',pm.nombre,pm.apellido) AS nombres,
                         t.cod_turno,
                         t.turno,
                         CONCAT_WS(' a ',(SELECT hora FROM hora WHERE cod_hora =ch.cod_hora_desde ), (SELECT hora FROM hora WHERE cod_hora =ch.cod_hora_hasta )) AS horario ",
            'condicion'=>"cm.num_consultorio=$num_consultorio
                          AND cm.cedula_pm=pm.cedula_pm
                          AND cm.num_consultorio=c.num_consultorio
                          AND c.num_consultorio=ch.num_consultorio
                          AND ch.cod_turno=t.cod_turno
                          AND (ch.cod_hora_desde=h.cod_hora)"
            );
        $result = $this->select($data, FALSE);
        return $result;
    }

}

?>

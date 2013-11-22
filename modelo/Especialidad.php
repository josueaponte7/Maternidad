<?php

if (!defined('BASEPATH')){
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

require_once 'Conexion.php';

class Especialidad extends Conexion
{

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;

    public function __construct()
    {
         $this->_firephp = FirePHP::getInstance(true);
    }

    public function addEspecialidad($data)
    {

        $especialidad = $data['especialidad'];

        $exi_esp = $this->recordExists("especialidad", "especialidad='" . $especialidad . "'");

        try {
            if ($exi_esp === TRUE) {
                $this->_tipoerror = 'error';
                $this->_mensaje   = 'El nombre de la Especialidad se encuentra Registrada';
                $this->_cod_msg   = 15;
            } else {
                $this->cod_especialidad = $this->autoIncremet('especialidad', 'cod_especialidad');

                $data = array(
                    "cod_especialidad" => $this->cod_especialidad,
                    "especialidad"     => $especialidad,
                );

                $insert = $this->insert('especialidad', $data);
                if ($insert === TRUE) {
                    $this->_tipoerror = 'success';
                    $this->_mensaje   = 'El Registro ha sido guardado con exito';
                    $this->_cod_msg   = 21;
                } else {
                    $this->_tipoerror = 'error';
                    $this->_mensaje   = 'Ocurrio un error comuniquese con informatica';
                    $this->_cod_msg   = 16;
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array(
                'tipo_error'       => $this->_tipoerror,
                'error_codmensaje' => $e->getCode(),
                'error_mensaje'    => $e->getMessage()
            );
        }
    }

    public function getEspecialidad($especialidad)
    {
        $data   = array('tabla'     => 'especialidad', 'campos'    => 'cod_especialidad,especialidad', 'condicion' => "especialidad =$especialidad");
        $result = $this->row($data);
        return $result;
    }

    public function editEspecialidad($data)
    {

        //$this->_firephp->info($data);

        $cod_especialidad = $data['cod_especialidad'];
        $especialidad     = $data['especialidad'];

        $exi_esp = $this->recordExists("especialidad", "especialidad='" . $especialidad . "' AND cod_especialidad!=$cod_especialidad");
        try {

            if ($exi_esp  === TRUE) {
                $this->_tipoerror = 'error';
                $this->_mensaje   = 'El nombre de la Especialidad se encuentra Registrada con otro C&oacute;digo';
                $this->_cod_msg   = 15;
            } else {
                $data  = array('especialidad' => $especialidad);
                $where = "cod_especialidad='$cod_especialidad'";

                $update = (boolean) $this->update('especialidad', $data, $where);

                if ($update === TRUE) {
                    $this->_tipoerror = 'info';
                    $this->_mensaje   = 'El Regsitro ha sido Modificado con exito';
                    $this->_cod_msg   = 22;
                } else {
                    $this->_tipoerror = 'error';
                    $this->_mensaje   = 'Ocurrio un error comuniquese con informatica';
                    $this->_cod_msg   = 16;
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
           return array(
                'tipo_error'       => $this->_tipoerror,
                'error_codmensaje' => $e->getCode(),
                'error_mensaje'    => $e->getMessage()
            );
        }
    }

    public function deletePrivilegio($data)
    {

        $cod_especialidad = $data['cod_especialidad'];
        $delete = $this->delete("especialidad", "cod_especialidad=$cod_especialidad");

        try {
            if ($delete === TRUE) {
                $this->_tipoerror = 'info';
                $this->_cod_msg   = 23;
                $this->_mensaje   = "El Registro ha sido Eliminado con exito";
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('tipo_error' => $this->_tipoerror, 'error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function getEspecialidadAll()
    {
        $data   = array(
            'tabla'  => 'especialidad',
            'campos' => "cod_especialidad,especialidad"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }
}
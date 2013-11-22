<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';

require_once 'Conexion.php';

class Modulo extends Conexion
{

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;
    private $_comodulo;

    public function __construct()
    {
        ob_start();
        $this->firephp = FirePHP::getInstance(true);
    }

    public function getModuloAll()
    {
        $data   = array('tabla' => 'modulo');
        $result = $this->select($data, FALSE);
        return $result;
    }

    // Agregar Modulos
    public function add($data)
    {
        //$this->firephp->log($data,'Modelo');
        $modulo = $data['modulo'];
        $existe = $this->recordExists("modulo", "modulo='" . $modulo . "'");

        try {
            if ($existe === TRUE) {
                $this->_tipoerror = 'alert alert-danger';
                $this->_cod_msg   = 15;
                $this->_mensaje   = "El Nombre del Modulo ya existe";
            } else {

                $this->_comodulo = $this->autoIncremet('modulo', 'cod_modulo');

                $data = array(
                    'cod_modulo' => $this->_comodulo,
                    'modulo'     => $modulo
                );

                $resultado = $this->insert('modulo', $data);

                if ($resultado === TRUE) {
                    $this->_tipoerror = 'alert alert-success';
                    $this->_cod_msg   = 21;
                    $this->_mensaje   = "El Registro ha sido Guardado Exitosamente";
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('tipo_error' => $this->_tipoerror, 'error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    // Modificar Modulos

    public function edit($data)
    {
        //$this->firephp->log($data,'Modelo');
        $modulo     = $data['modulo'];
        $cod_modulo = $data['cod_modulo'];
        try {

            $data  = array('modulo' => $modulo);
            $where = "cod_modulo='$cod_modulo'";

            $resultado = (boolean) $this->update('modulo', $data, $where);

            if ($resultado === TRUE) {
                $this->_tipoerror = 'alert alert-info';
                $this->_cod_msg   = 22;
                $this->_mensaje   = "El Registro ha sido Modificado Exitosamente";
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('tipo_error' => $this->_tipoerror, 'error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function del($data)
    {
        //$this->firephp->log($data,'Modelo');
        $cod_modulo = $data['cod_modulo'];
        try {
            $resultado = $this->delete("modulo", "cod_modulo=$cod_modulo");
            if ($resultado === TRUE) {
                $this->_tipoerror = 'info';
                $this->_cod_msg   = 23;
                $this->_mensaje   = "El Registro ha sido Eliminado Exitosamente";
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('tipo_error' => $this->_tipoerror, 'error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function getModulo($cod_modulo)
    {
        $data   = array('tabla' => 'modulo', 'campos' => 'modulo,orden_modulo', 'condicion' => "cod_modulo=$cod_modulo");
        $result = $this->_mysql->row($data);
        return $result;
    }
}
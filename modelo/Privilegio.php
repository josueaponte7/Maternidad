<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';

require_once 'Conexion.php';

class Privilegio extends Conexion
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

    public function getPrivilegioAll()
    {
        $data   = array('tabla' => 'privilegio', 'campos' => "codigo_privilegio,privilegio");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function add($data)
    {
        $privilegio = $data['privilegio'];
        $existe     = $this->recordExists("privilegio", "privilegio='" . $privilegio . "'");

        try {

            if ($existe === TRUE) {
                $this->_tipoerror = 'error';
                $this->_cod_msg   = 15;
                $this->_mensaje   = "El Nombre del Privilegio ya existe";
            } else {

                $this->codigo_privilegio = $this->autoIncremet('privilegio', 'codigo_privilegio');

                $data = array(
                    "codigo_privilegio" => $this->codigo_privilegio,
                    "privilegio"        => $privilegio,
                );

                $insert = $this->insert('privilegio', $data);
                if ($insert === TRUE) {
                    $this->_tipoerror = 'success';
                    $this->_cod_msg   = 21;
                    $this->_mensaje   = "El Registro ha sido Guardado Exitosamente";
                }
                throw new Exception($this->_mensaje, $this->_cod_msg);
            }
        } catch (Exception $e) {
            return array('tipo_error' => $this->_tipoerror, 'error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function edit($data)
    {
        $codigo_privilegio = $data['codigo_privilegio'];
        $privilegio        = $data['privilegio'];

        try {

            $data  = array('privilegio' => $privilegio);
            $where = "codigo_privilegio='$codigo_privilegio'";

            $resultado = (boolean) $this->update('privilegio', $data, $where);

            if ($resultado === TRUE) {

                $this->_tipoerror = 'info';
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
        $codigo_privilegio = $data['codigo_privilegio'];
        try {
            $resultado = $this->delete("privilegio", "codigo_privilegio=$codigo_privilegio");

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
}
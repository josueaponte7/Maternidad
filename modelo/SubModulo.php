<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}
require_once 'Conexion.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';

//require_once 'Validacion.php';

class SubModulo extends Conexion
{

    private $_mensaje;
    private $_cod_msg;

    public function __construct()
    {
        ob_start();
        $this->firephp = FirePHP::getInstance(true);
    }

    public function addSubModulo($data)
    {

        $cod_modulo = $data['cod_modulo'];
        $sub_modulo = $data['submodulo'];
        $ruta       = $data['ruta'];
        $existe     = (boolean) $this->recordExists("sub_modulo", "sub_modulo='" . $sub_modulo . "' AND cod_modulo=$cod_modulo");

        try {

            if ($existe === TRUE) {
                $this->_tipoerror = 'error';
                $this->_cod_msg   = 15;
                $this->_mensaje   = "El Nombre del SubModulo ya existe";
            } else {

                $this->sub_modulo = $this->autoIncremet('sub_modulo', 'cod_submodulo');

                $data = array(
                    "cod_submodulo" => $this->sub_modulo,
                    "sub_modulo"    => "$sub_modulo",
                    "cod_modulo"    => "$cod_modulo",
                    "ruta"          => "$ruta"
                );

                $insert = $this->insert('sub_modulo', $data);
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

    public function getCodMod($data)
    {
        $cod_submodulo = $data['cod_submodulo'];
        $result        = $this->get("sub_modulo", "cod_modulo", "cod_submodulo = '" . $cod_submodulo . "'");
        return $result;
    }

    public function editSubModulo($data)
    {

        $cod_modulo    = $data['cod_modulo'];
        $cod_submodulo = $data['cod_submodulo'];
        $sub_modulo    = $data['submodulo'];
        $ruta          = $data['ruta'];
        $existe        = (boolean) $this->recordExists("sub_modulo", "cod_modulo = $cod_modulo AND sub_modulo='" . $sub_modulo . "'");

        try {

            if ($existe === TRUE) {
                $this->_tipoerror = 'error';
                $this->_cod_msg   = 15;
                $this->_mensaje   = "El Nombre del SubModulo ya existe";
            } else {

                $data  = array('sub_modulo' => $sub_modulo,'cod_modulo'=>$cod_modulo,'ruta'=>$ruta);
                $where = "cod_submodulo='$cod_submodulo'";

                $update = (boolean) $this->update('sub_modulo', $data, $where);

                if ($update === TRUE) {
                    $this->_tipoerror = 'info';
                    $this->_cod_msg   = 22;
                    $this->_mensaje   = "El Registro ha sido Modificado Exitosamente";
                }
            }

            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('tipo_error' => $this->_tipoerror, 'error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function deleteSubModulo($data)
    {
        $cod_submodulo = $data['cod_submodulo'];
        $resultado     = $this->delete("sub_modulo", "cod_submodulo=$cod_submodulo");

        try {
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

    public function getSubModulo($cod_submodulo)
    {
        $data   = array('tabla' => 'sub_modulo', 'campos' => 'sub_modulo,orden_submodulo', 'condicion' => "cod_submodulo=$cod_submodulo");
        $result = $this->row($data);
        return $result;
    }

    public function getSubModuloAll()
    {
        $data   = array('tabla' => 'modulo AS m,sub_modulo AS sm', 'campos' => 'm.cod_modulo,m.modulo,sm.cod_submodulo,sm.sub_modulo', 'condicion' => 'sm.cod_modulo=m.cod_modulo');
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getSubModulos($data)
    {
        //$this->firephp->log($data,'ModeloSubMod');
        $cod_modulo = $data['cod_modulo'];
        $datos      = array('tabla' => 'sub_modulo', 'campos' => 'cod_submodulo,sub_modulo', 'condicion' => "cod_modulo=$cod_modulo");
        $result     = $this->select($datos, FALSE);
        return $result;
    }

    public function buscarRuta($data)
    {
        $cod_submodulo = $data['cod_submodulo'];
        $data   = array('tabla' => 'sub_modulo', 'campos' => 'ruta', 'condicion' => "cod_submodulo=$cod_submodulo");
        $result = $this->row($data);
        return $result;
    }
}
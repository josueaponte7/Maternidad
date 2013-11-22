<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';
require_once 'Conexion.php';

class Perfil extends Conexion
{

    private $_mensaje;
    private $_cod_msg;

    public function __construct()
    {
        ob_start();
        $this->firephp = FirePHP::getInstance(true);
    }

    public function addPerfil($data)
    {
        $perfil        = ucfirst(strtolower($data['perfil']));
        $existe = $this->recordExists("perfil", "perfil='" . $perfil . "'");
        try {
            if ($existe === TRUE) {
                $this->_tipoerror = 'error';
                $this->_cod_msg   = 15;
                $this->_mensaje = 'El Perfil se encuentra Registrado';
            } else {

                $this->codigo_perfil = $this->autoIncremet('perfil', 'codigo_perfil');

                $datos   = array("codigo_perfil" => $this->codigo_perfil, "perfil" => $perfil);
                $resultado = $this->insert('perfil', $datos);

                if ($resultado === TRUE) {
                    $this->_tipoerror = 'success';
                    $this->_cod_msg   = 21;
                    $this->_mensaje   = "El Registro ha sido Guardado Exitosamente";
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            return array('tipo_error' => $this->_tipoerror, 'error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage());
        }
    }

    public function editPerfil($data)
    {
        $cod    = $data['cod'];
        $perfil = $data['perfil'];

        try {

            $data  = array('perfil' => $perfil);
            $where = "codigo_perfil='$cod'";

            $resultado = (boolean) $this->update('perfil', $data, $where);

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

    public function delPerfil($data)
    {
        $cod = $data['cod'];
        try {
           $resultado = $this->delete("perfil", "codigo_perfil=$cod");

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

    public function getPerfil()
    {
        $data = array(
            'tabla'     => 'perfil ',
            'campos'    => "codigo_perfil,perfil"
        );

        $result = $this->select($data, FALSE);
        return $result;
    }

    public function addPrivilegios($data)
    {
       $cod_perfil  = $data['cod_perfil'];
       $sub_modulo  = $data['sub_modulo'];
       $privilegios = $data['privilegio'];

       foreach ($privilegios as $value) {

           $this->codigo_privilegios = $this->autoIncremet('perfil_priv_sub', 'cod_perfil_privilegio');
            $datos     = array(
                'cod_perfil_privilegio'=>$this->codigo_privilegios,
                "codigo_perfil" => $cod_perfil,
                "cod_submodulo" => $sub_modulo,
                "codigo_privilegio" => $value
            );
            $resultado = $this->insert('perfil_priv_sub', $datos);

            if($resultado === TRUE){
                $bandera = TRUE;
            }else{
                $bandera = FALSE;
                break;
            }
        }

        if ($bandera === TRUE) {
            $this->_tipoerror = 'success';
            $this->_cod_msg   = 21;
            $this->_mensaje   = "El Registro ha sido Guardado Exitosamente";
        }
       return array('tipo_error' => $this->_tipoerror, 'error_codmensaje' => $this->_cod_msg, 'error_mensaje' => $this->_mensaje);
    }

    public function getPerfilPrivilegio()
    {
        $data = array(
            'tabla'     => 'perfil_priv_sub AS pps,perfil AS p,sub_modulo AS sm, modulo AS m,privilegio pr ',
            'campos'    => "p.perfil,m.modulo,sm.cod_submodulo,sm.sub_modulo,GROUP_CONCAT(DISTINCT(pr.privilegio) ORDER BY pr.codigo_privilegio) AS privilegios ",
            'condicion' => 'pps.codigo_perfil=p.codigo_perfil AND pps.cod_submodulo=sm.cod_submodulo AND sm.cod_modulo=m.cod_modulo AND pps.codigo_privilegio=pr.codigo_privilegio GROUP BY pps.cod_submodulo,pps.codigo_perfil',
            'ordenar'   => 'pps.codigo_perfil'
        );

        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getModuloAll()
    {
        $data   = array('tabla' => 'modulo', 'campos', 'cod_modulo,modulo');
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getSubModulos($data)
    {
        $cod_modulo = $data['codigo'];
        $data       = array('tabla' => 'sub_modulo', 'campos' => 'cod_submodulo AS codigo ,sub_modulo AS descripcion', 'condicion' => "cod_modulo=$cod_modulo");
        $result     = $this->select($data, FALSE);
        return $result;
    }

    public function getPrivilegioAll()
    {
        $data   = array(
            'tabla'   => 'privilegio',
            'campos'  => "codigo_privilegio,privilegio,tipo",
            'ordenar' => 'codigo_privilegio'
        );
        $result = $this->select($data, FALSE);
        return $result;
    }
    public function getPrivilegioPerfil($data)
    {

        $cod_perfil = $data['cod_perfil'];
        $data = array(
            'tabla'     => "perfil_priv_sub AS pps,perfil AS p,sub_modulo AS sm, modulo AS m,privilegio pr ",
            'campos'    => "m.modulo,sm.cod_submodulo,sm.sub_modulo,GROUP_CONCAT(DISTINCT(pr.privilegio) ORDER BY pr.codigo_privilegio) AS privilegios ",
            'condicion' => "p.codigo_perfil=$cod_perfil AND pps.codigo_perfil=p.codigo_perfil AND pps.cod_submodulo=sm.cod_submodulo AND sm.cod_modulo=m.cod_modulo AND pps.codigo_privilegio=pr.codigo_privilegio GROUP BY pps.cod_submodulo,pps.codigo_perfil",
            'ordenar'   => "pps.codigo_perfil"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }
}
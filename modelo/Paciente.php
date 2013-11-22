<?php

if (!defined('BASEPATH')){
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

require_once 'Conexion.php';

class Paciente extends Conexion
{

    private $_mensaje;
    private $_cod_msg;

    public function __construct()
    {
        $this->_firephp = FirePHP::getInstance(true);
    }

    public function addPaciente($datos)
    {
        $nacionalidad     = $datos['nacionalidad'];
        $cedula_p         = $datos['cedula_p'];
        $nombre           = $datos['nombre'];
        $apellido         = $datos['apellido'];
        $fecha_nacimiento = $datos['fecha_nacimiento'];
        $cod_telefono     = $datos['cod_telefono'];
        $telefono         = $datos['telefono'];
        $cod_celular      = $datos['cod_celular'];
        $celular          = $datos['celular'];
        $direccion        = $datos['direccion'];
        $cod_sector       = $datos['sector'];

        $exi_ced = $this->recordExists("paciente", "cedula_p='" . $cedula_p . "'");

        try {
            if ($exi_ced === TRUE) {
                 $this->_cod_msg   = 15;
                 $this->_tipoerror = 'error';
                 $this->_mensaje   = 'La Cédula se encuentra Registrada';
            } else {

                $fecha_nacimiento = $this->formateaBD($fecha_nacimiento);

                $data = array(
                    "nacionalidad"     =>$nacionalidad,
                    "cedula_p"         => $cedula_p,
                    "nombre"           => "$nombre",
                    "apellido"         => "$apellido",
                    "fecha_nacimiento" => "$fecha_nacimiento",
                    "cod_telefono"     => $cod_telefono,
                    "telefono"         => $telefono,
                    "cod_celular"      => $cod_celular,
                    "celular"          => $celular,
                    "direccion"        => "$direccion",
                    "cod_sector"       => $cod_sector
                );

                $insert = $this->insert('paciente', $data);
                if ($insert === TRUE) {
                    $this->_tipoerror = 'exito';
                    $this->_mensaje   = 'El Registro ha sido guardado con exito';
                    $this->_cod_msg   = 21;
                } else {
                    $this->_mysql->delete("personal_medico", "cedula_pm=$cedula_pm");
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

    public function getPaciente($datos)
    {
        $cedula_p = $datos['cedula_p'];

        $data   = array(
                            'tabla' => 'paciente p,sector s',
                            'campos' => 'p.nombre,p.apellido,p.telefono,p.celular,p.direccion,s.cod_municipio, p.cod_sector',
                            'condicion' => "p.cod_sector = s.cod_sector AND p.cedula_p  =$cedula_p");
        $result = $this->row($data);
        return $result;
    }

    public function editPaciente($datos)
    {
        $cedula_p         = $datos['cedula_p'];
        $nombre           = $datos['nombre'];
        $apellido         = $datos['apellido'];
        $fecha_nacimiento = $datos['fecha_nacimiento'];
        $telefono         = $datos['telefono'];
        $celular          = $datos['celular'];
        $direccion        = $datos['direccion'];
        $cod_sector       = $datos['sector'];

        try {

            if ($this->validar($fecha_nacimiento, 'fechanac') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            /*} else if ($this->validar($telefono, 'telefono') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($celular, 'celular') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($direccion, 'direccion') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);*/
            } else {

                $fecha_nacimiento = $this->formateaBD($fecha_nacimiento);

                $data  = array(
                    'nombre'           => $nombre,
                    'apellido'         => $apellido,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'telefono'         => $telefono,
                    'celular'          => $celular,
                    'direccion'        => $direccion,
                    'cod_sector'       => $cod_sector
                );
                $where = "cedula_p='$cedula_p'";

                $update = (boolean) $this->update('paciente', $data, $where);

                if ($update === TRUE) {
                    $this->_cod_msg   = 22;
                    $this->_tipoerror = 'info';
                    $this->_mensaje   = "Modificacion con exito";
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

    public function deletePaciente($cedula_p)
    {
        $delete = $this->_mysql->delete("paciente", "cedula_p=$cedula_p");

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

    public function getPacienteAll()
    {
        $data  = array(
                    'tabla' => 'paciente',
                    'campos' => "CONCAT_WS('-',nacionalidad,cedula_p) AS cedula_p,CONCAT_WS(' ',nombre,apellido) AS nombres,DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fecha,IF(telefono ='',celular,telefono) AS telefono"
                    );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getDatos($datos)
    {
        $cedula_p = $datos['cedula_p'];
        $data   = array('tabla' => 'sector', 'campos' => "cod_sector,sector", "condicion" => "cod_municipio='" . $codigo_municipio . "'");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getMunicipio()
    {
        $data   = array('tabla' => 'municipios', 'campos' => "codigo_municipio,municipio");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getSector($datos)
    {
        $codigo_municipio = $datos['codigo_municipio'];
        $data   = array(
                            'tabla'     => 'sector',
                            'campos'    => "cod_sector,sector",
                            "condicion" => "cod_municipio='" . $codigo_municipio . "'"
                        );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getCodLocal()
    {
        $data   = array('tabla' => 'codigo_telefono', 'campos' => "cod_telefono,codigo", "condicion" => "tipo=1");
        $result = $this->select($data, FALSE);
        return $result;
    }
     public function getCodCelular()
     {
        $data   = array('tabla' => 'codigo_telefono', 'campos' => "cod_telefono,codigo", "condicion" => "tipo=2");
        $result = $this->select($data, FALSE);
        return $result;
    }
}
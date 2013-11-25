<?php

if (!defined('BASEPATH'))
   exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");

require_once '../../FirePHP/fb.php';

require_once 'Conexion.php';

class personalMedico extends Conexion
{

    private $_mensaje;
    private $_cod_msg;

    public function __construct()
    {
        $this->firephp = FirePHP::getInstance(true);
    }

    public function getCod()
    {
        $data   = array('tabla' => 'codigo_telefono', 'campos' => "cod_telefono,codigo");
        $result = $this->select($data, FALSE);
        return $result;
    }
    public function addpersonalMedico($datos)
    {

        $nacionalidad = $datos['nacionalidad'];
        $cedula_pm    = $datos['cedula_pm'];
        $nombre       = $datos['nombre'];
        $apellido     = $datos['apellido'];
        $cod_telefono = $datos['cod_telefono'];
        $telefono     = $datos['telefono'];
        $direccion    = $datos['direccion'];
        $num_cons     = $datos['num_cons'];
        $cod_turno    = $datos['cod_turno'];

        $existe = $this->recordExists("personal_medico", "cedula_pm='" . $cedula_pm . "'");

        try {
            if ($existe === TRUE) {

                $this->_tipoerror = 'error';
                $this->_mensaje   = 'La C&eacute;dula se Encuentra Registrada en el Sistema';
                $this->_cod_msg   = 15;
            } else {

                $data = array(
                    "nacionalidad" =>"$nacionalidad",
                    "cedula_pm"    => $cedula_pm,
                    "nombre"       => $nombre,
                    "apellido"     => $apellido,
                    "cod_telefono" => $cod_telefono,
                    "telefono"     => $telefono,
                    "direccion"    => $direccion
                );

                $insert = $this->insert('personal_medico', $data);
                if ($insert === TRUE) {

                    $cod_consu_horario = $this->get('consultorio_horario','cod_consu_horario',"num_consultorio=$num_cons AND cod_turno=$cod_turno");

                    $data1 = array(
                        'cedula_pm'         => $cedula_pm,
                        'cod_consu_horario' => $cod_consu_horario
                    );

                    $insert1 = $this->insert('consultorio_medico', $data1);

                    if ($insert1 === TRUE) {
                        $this->_tipoerror = 'exito';
                        $this->_mensaje   = 'El Registro ha sido guardado exitosamente';
                        $this->_cod_msg   = 21;
                    }else{

                        $this->delete("personal_medico", "cedula_pm=$cedula_pm");
                        $this->_tipoerror = 'error';
                        $this->_mensaje   = 'Ocurrio un error comuniquese con informatica';
                        $this->_cod_msg   = 16;
                    }
                }
            }
               throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
             //$this->_firephp->info($e);
            return array(
                'tipo_error'       => $this->_tipoerror,
                'error_codmensaje' => $e->getCode(),
                'error_mensaje'    => $e->getMessage()
            );
        }
    }

    public function getpersonalMedico($cedula_pm)
    {
        $data = array('tabla' => 'personalMedico', 'campos' => 'cedula_pm,nombre,apellido,telefono,direccion,num_const,cod_esp', 'condicion' => "cedula_pm =$cedula_pm");
        $result = $this->row($data);
        return $result;
    }

    public function editpersonalMedico($datos)
    {
        
        $cedula_pm    = $datos['cedula_pm'];
        $cod_telefono = $datos['cod_telefono'];
        $telefono     = $datos['telefono'];
        $direccion    = $datos['direccion'];
        $num_cons     = $datos['num_cons'];
        $cod_esp      = $datos['cod_esp'];
        $cod_turno    = $datos['cod_turno'];

        try {
           
            $data  = array('cod_telefono'=>$cod_telefono,'telefono' => $telefono, 'direccion' => $direccion);
            $where = "cedula_pm='$cedula_pm'";

            $update = (boolean) $this->update('personal_medico', $data, $where);

            if ($update === TRUE) {
                
                $where1 = "num_consultorio=$num_cons AND cod_turno=$cod_turno AND cod_especialidad=$cod_esp";
                $cod_consu_horario = $this->get('consultorio_horario', 'cod_consu_horario', $where1);
   
                $data1   = array('cod_consu_horario' => $cod_consu_horario);
                $update1 = (boolean) $this->update('consultorio_medico', $data1, $where);
                if ($update1 === TRUE) {
                    $this->_cod_msg   = 22;
                    $this->_tipoerror = 'info';
                    $this->_mensaje   = "El Registro ha sido  Modificado con exito";
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                } else {
                    $this->_tipoerror = 'error';
                    $this->_mensaje   = '<span style="color:#FF0000">Ocurrio un error comuniquese con informatica</span>';
                    $this->_cod_msg   = 16;
                    throw new Exception($this->_mensaje, $this->_cod_msg);
                }
            } else {
                $this->_tipoerror = 'error';
                $this->_mensaje   = 'Ocurrio un error comuniquese con informatica';
                $this->_cod_msg   = 16;
                throw new Exception($this->_mensaje, $this->_cod_msg);
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'tipo_error'       => $this->_tipoerror,
                'error_codmensaje' => $e->getCode(),
                'error_mensaje'    => $e->getMessage()
            ));
        }
    }

    public function deletepersonalMedico($cedula_pm)
    {
        $delete = $this->delete("personalMedico", "cedula_pm=$cedula_pm");

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
    
    public function getMedico($datos)
    {
        $cedula_pm    = $datos['cedula_pm'];
        $data = array(
            "tabla"     => "personal_medico pm,codigo_telefono AS ct ,consultorio_medico AS cm,consultorio_horario AS ch",
            "campos"    => "pm.nombre,pm.apellido,ct.cod_telefono,ct.codigo,pm.telefono,pm.direccion,ch.cod_especialidad,ch.cod_turno,ch.num_consultorio",
            "condicion" => "pm.cedula_pm=$cedula_pm  AND pm.cedula_pm=cm.cedula_pm AND ct.cod_telefono=pm.cod_telefono AND cm.cod_consu_horario=ch.cod_consu_horario"
        );
        $result = $this->row($data, FALSE);
        return $result;
    }
    
    public function getPersonalMedicoAll()
    {
        $data = array(
            "tabla"     => "personal_medico pm,consultorio_medico cm,consultorio c, consultorio_horario ch,especialidad e,turno t,codigo_telefono AS ct",
            "campos"    => "CONCAT_WS('-',pm.nacionalidad,pm.cedula_pm) AS cedula_pm,CONCAT_WS(' ',pm.nombre,pm.apellido) AS nombres,e.especialidad,c.consultorio,CONCAT('0',CONCAT_WS('',ct.codigo,pm.telefono)) AS telefono,t.turno",
            "condicion" => "pm.cedula_pm=cm.cedula_pm AND cm.cod_consu_horario= ch.cod_consu_horario AND c.num_consultorio=ch.num_consultorio AND ch.cod_especialidad=e.cod_especialidad AND ch.cod_turno=t.cod_turno AND pm.cod_telefono=ct.cod_telefono"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getEspecialidad()
    {
        $data   = array('tabla'  => 'especialidad', 'campos' => "cod_especialidad,especialidad");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getConsultorio($datos)
    {
        $cod_esp = $datos['cod_esp'];

        $data   = array(
            'tabla'  => 'consultorio_horario  ch,consultorio c',
            'campos' => "c.num_consultorio,c.consultorio",
            'condicion'=>"ch.num_consultorio=c.num_consultorio AND ch.cod_especialidad=$cod_esp GROUP BY c.consultorio"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }
    public function getTurno($datos)
    {
        $num_cons = $datos['num_cons'];
        //$this->_firephp->error($datos,'Modelo');
        $data   = array(
            'tabla'  => 'consultorio_horario  ch,turno t',
            'campos' => "t.cod_turno,t.turno",
            'condicion'=>"ch.cod_turno=t.cod_turno AND ch.num_consultorio=$num_cons"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getDatos($datos)
    {
        $cedula_pm = $datos['cedula_pm'];
        $data   = array(
            'tabla'     => 'personal_medico pm,consultorio_medico cm,consultorio c, consultorio_horario ch',
            'campos'    => "pm.nombre,pm.apellido,pm.telefono,pm.direccion,ch.cod_especialidad,ch.num_consultorio,ch.cod_turno",
            'condicion' => "pm.cedula_pm=cm.cedula_pm AND cm.num_consultorio= c.num_consultorio AND c.num_consultorio=ch.num_consultorio AND pm.cedula_pm=$cedula_pm"
        );
        $result = $this->row($data);

        return $result;
    }
}

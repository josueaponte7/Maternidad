<?php

if (!defined('BASEPATH')){
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}
require_once $_SERVER['DOCUMENT_ROOT'].'/Maternidad/FirePHP/fb.php';

require_once 'Conexion.php';
class Asignar extends Conexion
{

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;

    public function __construct()
    {
        $this->firephp = FirePHP::getInstance(true);
    }

    public function addAsignar($data)
    {

        $nacionalidad      = $data['nacionalidad'];
        $cedula_p          = $data['cedula_p'];
        $num_consultorio   = $data['num_consultorio'];
        $fecha             = $data['fecha'];
        $cod_turno         = $data['cod_turno'];
        $cod_consu_horario = $data['cod_consu_horario'];
        try {

            $fecha = $this->formateaBD($fecha);

            $data = array(
                "nacionalidad"      => $nacionalidad,
                "cedula_p"          => $cedula_p,
                "num_consultorio"   => $num_consultorio,
                "fecha"             => $fecha,
                "cod_turno"         => $cod_turno,
                'cod_consu_horario' => $cod_consu_horario
            );

            $insert = $this->insert('cita', $data);
            if ($insert === TRUE) {
                $this->_tipoerror = 'success';
                $this->_mensaje = 'El Registro ha sido guardado exitosamente';
                $this->_cod_msg = 21;
            }else{
                $this->_tipoerror = 'error';
                $this->_mensaje   = 'Ocurrio un error comuniquese con informatica';
                $this->_cod_msg   = 16;
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

     public function BuscarDatos($datos)
     {
        $cedula_p = $datos['cedula_p'];
        $exi_ced  = $this->recordExists("paciente", "cedula_p='" . $cedula_p . "'");
        if ($exi_ced === FALSE) {
            return $this->_cod_msg = FALSE;
        } else {

            $cant_citas = $this->get('consulta', 'COUNT(cedula_p)', "cedula_p = $cedula_p AND fecha = CURRENT_DATE");

            if ($cant_citas == 1) {
                return 4;
            } else {
                $data    = array(
                    'tabla'     => "paciente AS p,codigo_telefono AS c",
                    'campos'    => "p.nombre,p.apellido,CONCAT('0', CONCAT_WS('',c.codigo,p.telefono)) AS telefono ",
                    'condicion' => "p.cedula_p  =$cedula_p AND c.cod_telefono=p.cod_telefono"
                );
                $result1 = $this->row($data);

                $total = $this->numRows("(SELECT ci.cedula_p, ci.fecha FROM cita AS ci UNION SELECT co.cedula_p,co.fecha FROM consulta AS co) AS ci", 'ci.fecha', "ci.cedula_p = $cedula_p");
                if ($total == 0) {
                    $result2 = array('total' => 0, 'asistencia' => 0,'fech_max' => 0);
                } else {
                    if ($total == 1) {
                        $cita = $this->get('cita', 'fecha', "cedula_p = $cedula_p");
                        if($cita !== FALSE){
                            $asistencia = 0;
                        }else{
                            $asistencia = 1;
                        }
                        $result2 = array('total' => $total, 'asistencia' => $asistencia,'fech_max' => 0);
                    } else {
                        $fech_total = $this->numRows("(SELECT ci.cedula_p, ci.fecha FROM cita AS ci UNION SELECT co.cedula_p,co.fecha FROM consulta AS co) AS ci", 'ci.fecha', "ci.cedula_p = $cedula_p AND ci.fecha > CURRENT_DATE()");
                        
                        $asistencia = $this->numRows("consulta", 'asistencia', "cedula_p = $cedula_p AND asistencia = 1 AND fecha < CURRENT_DATE()");
                        $result2    = array('total' => $total, 'asistencia' => $asistencia,'fech_max' => $fech_total);
                    }
                }
                $result = array_merge($result1, $result2);
                return $result;
            }
        }
    }
    
    public function BuscarCitas($datos)
    {  
        $cedula_p = $datos['cedula_p'];
        $data   = array(
            'tabla'     => "(SELECT ci.cedula_p,ci.fecha,ci.cod_consu_horario,'0' AS asistencia,'' AS observacion FROM cita AS ci UNION SELECT co.cedula_p, co.fecha,co.cod_consu_horario,co.asistencia,co.observacion FROM consulta AS co ) AS ci,consultorio_horario AS ch,consultorio_medico AS cm,personal_medico AS pm,consultorio AS co",
            'campos'    => "DATEDIFF(ci.fecha,CURRENT_DATE) dias, DATE_FORMAT(ci.fecha,'%d-%m-%Y') AS fecha,ci.asistencia,CONCAT_WS(' ',pm.nombre,pm.apellido) AS nombre,co.consultorio,ci.observacion ",
            'condicion' => "ci.cedula_p=$cedula_p AND ci.cod_consu_horario=ch.cod_consu_horario AND ch.cod_consu_horario=cm.cod_consu_horario AND cm.cedula_pm=pm.cedula_pm AND ch.num_consultorio=co.num_consultorio",
            'ordenar'   => 'ci.fecha DESC'
        );
        $result = $this->select($data,FALSE);
        return $result;
    }
    
    public function EliminarCita($datos)
    {
        $cedula_p = $datos['cedula_p'];
        $fecha = $datos['fecha'];
        $fecha = $this->formateaBD($fecha);
        
        $resultado = $this->delete('cita', "cedula_p=$cedula_p AND fecha='$fecha'");
        
        if($resultado === TRUE){
            return TRUE;
        }else{
            return FALSE;
        }
  
    }
    
    public function CancelarCita($datos)
    {
        $cedula_p    = $datos['cedula_p'];
        $observacion = $datos['observacion'];
        $sql         = "INSERT INTO consulta(nacionalidad,cedula_p,fecha,num_consultorio,cod_consu_horario,observacion) 
                     SELECT nacionalidad,$cedula_p,fecha,num_consultorio,cod_consu_horario,'$observacion' FROM cita WHERE cedula_p=$cedula_p";
        $result      = $this->execute($sql);
        
        try {
            if ($result === TRUE) {
                $where = "cedula_p=$cedula_p";
                $resul_del = $this->delete('cita', $where);
                if ($resul_del === TRUE) {
                    $this->_tipoerror = 'success';
                    $this->_cod_msg   = 21;
                    $this->_mensaje   = "El Registro ha sido Guardado Exitosamente";
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

    public function editEspecialidad($cod_esp, $especialidad)
    {
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


    public function getAsignarAll()
    {
        $data   = array(
            'tabla'  => 'cita',
            'campos' => "cedula_p, num_consultorio, fecha"
            );
        $result = $this->select($data, FALSE);
        return $result;
    }
    public function getConsultorio()
    {
        $data   = array(
            'tabla'  => 'consultorio c',
            'campos' => "c.num_consultorio,c.consultorio "
            );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getDatosCons($datos)
    {

        $num_consultorio = $datos['num_consultorio'];
        $data = array(
            'tabla'     => "consultorio_horario AS ch,consultorio_medico AS cm,personal_medico AS pm,turno AS t",
            'campos'    => "ch.cod_consu_horario ,CONCAT_WS(' ',pm.nombre,pm.apellido) AS nombres,t.cod_turno,t.turno",
            'condicion' => "ch.num_consultorio=$num_consultorio AND ch.cod_consu_horario=cm.cod_consu_horario AND pm.cedula_pm=cm.cedula_pm AND ch.cod_turno=t.cod_turno "
        );
        $result = $this->select($data, FALSE);
        return $result;
    }
}
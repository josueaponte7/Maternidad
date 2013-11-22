<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';

require_once 'Conexion.php';

class Consultorio extends Conexion
{

    private $_mensaje;
    private $_cod_msg;
    private $_tipoerror;

    public function __construct()
    {
        $this->firephp = FirePHP::getInstance(true);
    }

    // funcion para registrar el consultorio
    public function addConsultorio($datos)
    {
        $insert          = FALSE;
        $num_consultorio = FALSE;
        $cosultorio      = $datos['consultorio'];
        $turno           = $datos['turno'];
        $especialidad    = $datos['especialidad'];
        $turnos          = implode(',', $turno);

        // verificar que no exista un consultorio con el mismo nombre

        try {
            $existe_consultorio = $this->recordExists("consultorio", "consultorio='" . $cosultorio . "'");
            if ($existe_consultorio === FALSE) {

                $this->num_consultorio = $this->autoIncremet('consultorio', 'num_consultorio');

                $data_consutorio = array(
                    "num_consultorio" => $this->num_consultorio,
                    "consultorio"     => $cosultorio,
                );
                $insert          = $this->insert('consultorio', $data_consutorio);
                if ($insert === TRUE) {

                    $num_consultorio = $this->get('consultorio', 'num_consultorio', "consultorio ='" . $cosultorio . "'");
                    $bandera         = count($turno);
                    foreach ($turno as $valor) {

                        $turno_manana = array(
                            "num_consultorio"  => $num_consultorio,
                            "cod_turno"        => $valor,
                            "cod_especialidad" => $especialidad
                        );
                        $insert       = $this->insert('consultorio_horario', $turno_manana);
                        if ($insert === TRUE) {
                            $bandera--;
                        }
                    }

                    if ($bandera === 0) {
                        $this->_cod_msg   = 21;
                        $this->_tipoerror = 'success';
                        $this->_mensaje   = "El Registro ha sido guardado con exito";
                    } else {
                        $this->delete('consultorio_horario', "num_consultorio=$num_consultorio");
                        $this->_cod_msg   = 16;
                        $this->_tipoerror = 'error';
                        $this->_mensaje   = "Ocurrio un error comuniquese con informatica";
                    }
                }
            } else {
                $num_consultorio    = $this->get('consultorio', 'num_consultorio', "consultorio ='" . $cosultorio . "'");
                $existe_consultorio = $this->recordExists("consultorio_horario", "num_consultorio=$num_consultorio AND cod_turno IN($turnos)");
                if ($existe_consultorio === TRUE) {
                    $this->_cod_msg   = 15;
                    $this->_tipoerror = 'error';
                    $this->_mensaje   = 'El nombre del Consultorio se encuentra Registrada';
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

    public function editConsultorio($datos)
    {
        //$consultorio     = $datos['consultorio'];
        $especialidad    = $datos['especialidad'];
        $turno           = $datos['turno'];
        $desde           = $datos['desde'];
        $hasta           = $datos['hasta'];
        $num_consultorio = $datos['num_consultorio'];

        try {

            $data = array(
                'cod_especialidad' => $especialidad,
                'cod_turno'        => $turno,
                'cod_hora_desde'   => $desde,
                'cod_hora_hasta'   => $hasta
            );

            $where = "num_consultorio='$num_consultorio'";

            $update = $this->update('consultorio_horario', $data, $where);

            if ($update === TRUE) {

                $this->_cod_msg   = 22;
                $this->_tipoerror = 'info';
                $this->_mensaje   = "El Registro ha sido  Modificado con exito";
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            echo json_encode(array(
                'tipo_error'       => $this->_tipoerror,
                'error_codmensaje' => $e->getCode(),
                'error_mensaje'    => $e->getMessage()
            ));
        }
    }

    public function getHorarioConsultorio($datos)
    {

        $num_consultorio = $datos['num_consultorio'];

        $data   = array(
            'tabla'     => 'consultorio_horario ch',
            'campos'    => 'ch.cod_especialidad,ch.cod_turno,ch.cod_hora_desde AS desde ,ch.cod_hora_hasta AS hasta',
            'condicion' => "ch.num_consultorio =$num_consultorio"
        );
        $result = $this->row($data);
        return $result;
    }

    public function getDatosConsultorio($datos)
    {

        $num_consultorio = $datos['num_consultorio'];

        $data   = array(
            'tabla'     => 'consultorio_horario ch',
            'campos'    => 'ch.cod_especialidad,GROUP_CONCAT(ch.cod_turno) AS turnos',
            'condicion' => "ch.num_consultorio =$num_consultorio GROUP BY ch.cod_especialidad"
        );
        $result = $this->row($data);
        return $result;
    }
    public function getMedico($datos)
    {

        $cod_turnos      = $datos['cod_turnos'];
        $num_consultorio = $datos['num_consultorio'];

        $data   = array(
            'tabla'     => 'consultorio_horario AS  ch,consultorio_medico AS cm',
            'campos'    => 'COUNT(cm.cedula_pm) AS total, IFNULL(ch.cod_turno,0) AS  cod_turno',
            'condicion' => "ch.cod_consu_horario=cm.cod_consu_horario AND ch.num_consultorio = $num_consultorio AND ch.cod_turno IN($cod_turnos)"
        );
        $result = $this->row($data);
        //$result = $this->get('consultorio_horario AS  ch,consultorio_medico AS cm','COUNT(cm.cedula_pm) AS total',"ch.cod_consu_horario=cm.cod_consu_horario AND ch.num_consultorio = $num_consultorio AND ch.cod_turno IN($cod_turnos)");
        return $result;
    }

    public function getConsultorioAll()
    {
        $data   = array(
            'tabla'     => "consultorio AS c,consultorio_horario AS ch,especialidad AS e,turno AS t",
            'campos'    => "c.num_consultorio,c.consultorio,e.especialidad,GROUP_CONCAT(t.turno ORDER BY t.cod_turno) AS turno ",
            'condicion' => "c.num_consultorio=ch.num_consultorio AND ch.cod_especialidad=e.cod_especialidad AND ch.cod_turno=t.cod_turno GROUP BY c.consultorio"
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getEspecialidad()
    {
        $data   = array('tabla' => 'especialidad', 'campos' => "cod_especialidad,especialidad");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getTurno()
    {
        $data   = array('tabla' => 'turno', 'campos' => "cod_turno,turno");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getHora()
    {
        $data   = array('tabla' => 'hora', 'campos' => "cod_hora,hora");
        $result = $this->select($data, FALSE);
        return $result;
    }

    public function getHorario($datos)
    {
        $condicion = $datos['condicion'];
        if ($condicion == 'turno') {
            $codigo   = $datos['cod_turno'];
            $condicio = "cod_turno = $codigo";
        } else {
            $cod_hora  = $datos['cod_hora'];
            $cod_turno = $datos['cod_turno'];
            $condicio  = "cod_hora > $cod_hora AND cod_turno = $cod_turno";
        }
        $data   = array(
            'tabla'     => 'hora',
            'campos'    => "cod_hora AS codigo,hora AS descripcion",
            'condicion' => $condicio
        );
        $result = $this->select($data, FALSE);
        return $result;
    }

}

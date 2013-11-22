<?php

if (!defined('BASEPATH')) {
    exit("<div style='color:#FF0000;text-align:center;margin:0 auto'>Acceso Denegado</div>");
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Maternidad/FirePHP/fb.php';

require_once 'Conexion.php';

class Historia extends Conexion
{

    private $_mensaje;
    private $_cod_msg;

    public function __construct()
    {
        $this->firephp = FirePHP::getInstance(true);
    }

    public function addhistoria($data)
    {

        $nacionalidad  = $data['nacionalidad'];
        $cedula_p      = $data['cedula_p'];
        $historia      = $data['historia'];
        $lugar_control = $data['lugar_control'];
        $tamano        = $data['tamano'];
        $peso          = $data['peso'];
        $tension       = $data['tension'];
        $fur           = $data['fur'];
        $fpp           = $data['fpp'];
        $diagnostico   = $data['diagnostico'];
        $observacion   = $data['observacion'];

        try {

            /*$cita_asistida = $this->numRows('cita', 'cedula_p', "fecha < CURRENT_DATE");
            if ($cita_asistida === 0) {

            }*/
            $fur = $this->formateaBD($fur);
            $fpp = $this->formateaBD($fpp);

            $data_up = array(
                "fur"           => "$fur",
                "fpp"           => "$fpp",
                "lugar_control" => "$lugar_control"
            );
            $where   = "cedula_p='$cedula_p'";

            $resul_up = $this->update('paciente', $data_up, $where);

            if ($resul_up === TRUE) {
                $sql = "INSERT INTO consulta(nacionalidad,cedula_p,fecha,num_consultorio,cod_consu_horario,tamano,peso,tension,diagnostico,observacion_medica,asistencia)
                        SELECT nacionalidad,cedula_p,fecha,num_consultorio,cod_consu_horario,'$tamano','$peso','$tension','$diagnostico','$observacion',1 FROM cita WHERE cedula_p=$cedula_p";
                $result_inser = $this->execute($sql);
                if ($result_inser === TRUE) {
                    $resul_del = $this->delete('cita', $where);
                    if ($resul_del === TRUE) {
                        $this->_tipoerror = 'success';
                        $this->_mensaje   = 'El Registro ha sido guardado exitosamente';
                        $this->_cod_msg   = 21;
                    }
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

    public function BuscarDatos($data)
    {
        $cedula_p = $data['cedula_p'];
        $exi_ced  = $this->recordExists("paciente", "cedula_p='" . $cedula_p . "'");

        if ($exi_ced === FALSE) {
            return $this->_cod_msg = FALSE;
        } else {

            $existe_cita     = $this->numRows('cita', 'cedula_p', "cedula_p=$cedula_p");
            $cita_pasada     = $this->numRows('cita', 'cedula_p', "cedula_p=$cedula_p AND fecha <= CURRENT_DATE");
            $existe_consulta = $this->numRows('consulta', 'cedula_p', "cedula_p=$cedula_p");

            if($existe_cita === 0 && $existe_consulta === 0 && $cita_pasada === 0){
                return 1;
            }else if($existe_cita === 1 && $existe_consulta === 0 && $cita_pasada === 0){
                return 2;
            }else if($existe_cita === 1 && $existe_consulta === 0 && $cita_pasada === 1){
                $datos_cargar = 1;
            }
            if($datos_cargar == 1){
                 $result2 = array('historia' => '','his_o'=>'','peso'=>'','tamano'=>'','tension'=>'','diagnostico'=>'','observacion_medica'=>'');
                 
            }
            $data = array(
                    'tabla'     => 'paciente AS p,codigo_telefono AS c',
                    'campos'    => "p.nombre,p.apellido,DATE_FORMAT(p.fecha_nacimiento,'%d-%m-%Y') AS fecha,CONCAT(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(p.fecha_nacimiento)), '%Y')+0,' Años') AS edad,IF(p.fur=NULL,NULL,DATE_FORMAT(p.fur,'%d-%m-%Y')) AS fur,IF(p.fpp=NULL,NULL,DATE_FORMAT(p.fpp,'%d-%m-%Y')) AS fpp,p.lugar_control",
                    'condicion' => "p.cedula_p  =$cedula_p AND c.cod_telefono=p.cod_telefono"
                );
                 $result1 = $this->row($data);
                 $result  = array_merge($result1,$result2);
            /*if ($existe_cita == 0) {
                $existe_consulta = $this->numRows('consulta', 'cedula_p', "cedula_p=$cedula_p");
                if ($existe_consulta == 1) {
                    $data    = array(
                        'tabla'     => 'paciente AS p,codigo_telefono AS c',
                        'campos'    => "p.nombre,p.apellido,DATE_FORMAT(p.fecha_nacimiento,'%d-%m-%Y') AS fecha,CONCAT(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(p.fecha_nacimiento)), '%Y')+0,' Años') AS edad,IF(p.fur=NULL,NULL,DATE_FORMAT(p.fur,'%d-%m-%Y')) AS fur,IF(p.fpp=NULL,NULL,DATE_FORMAT(p.fpp,'%d-%m-%Y')) AS fpp,p.lugar_control",
                        'condicion' => "p.cedula_p  =$cedula_p AND c.cod_telefono=p.cod_telefono"
                    );
                    $result1 = $this->row($data);
                    $data1   = array(
                        'tabla'     => 'consulta',
                        'campos'    => 'tamano,peso,tension,diagnostico,observacion_medica',
                        'condicion' => "cedula_p=$cedula_p"
                    );

                    $result3 = $this->row($data1);
                    $result2 = array('historia' => '', 'his_o' => '');
                    $result  = array_merge($result1, $result2, $result3);
                }else{
                    return 4;
                }
            } else {

                $data    = array(
                        'tabla'     => 'paciente AS p,codigo_telefono AS c',
                        'campos'    => "p.nombre,p.apellido,DATE_FORMAT(p.fecha_nacimiento,'%d-%m-%Y') AS fecha,CONCAT(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(p.fecha_nacimiento)), '%Y')+0,' Años') AS edad,IF(p.fur=NULL,NULL,DATE_FORMAT(p.fur,'%d-%m-%Y')) AS fur,IF(p.fpp=NULL,NULL,DATE_FORMAT(p.fpp,'%d-%m-%Y')) AS fpp,p.lugar_control",
                        'condicion' => "p.cedula_p  =$cedula_p AND c.cod_telefono=p.cod_telefono"
                    );
                $result1 = $this->row($data);
                $result2 = array('historia' => '','his_o'=>'','peso'=>'','tamano'=>'','tension'=>'','diagnostico'=>'','observacion_medica'=>'');
                $result  = array_merge($result1,$result2);
            }*/
        }
        return $result;
    }

    public function edithistoria($cedula_p, $historia, $lugar_control, $fur, $fpp, $diagnostico, $observacion)
    {
        try {

            if ($this->validar($lugar_control, 'lugarcontrol') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($fur, 'fur') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($fpp, 'fpp') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($diagnostico, 'diagnostico') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else if ($this->validar($observacion, 'observacion') === FALSE) {

                $this->_mensaje = "Error";
                throw new Exception($this->_mensaje, 300);
            } else {
                $data  = array('lugar_control' => $lugar_control, 'fur' => $fur, 'fpp' => $fpp, 'diagnistico' => $diagnostico, 'observacion' => $observacion);
                $where = "cedula_p='$cedula_p'";
                $update = (boolean) $this->update('historia', $data, $where);

                if ($update === TRUE) {
                    $this->_mensaje = 'Modificacion con exito';
                    $this->_cod_msg = 501;
                }
            }
            throw new Exception($this->_mensaje, $this->_cod_msg);
        } catch (Exception $e) {
            echo json_encode(array('error_codmensaje' => $e->getCode(), 'error_mensaje' => $e->getMessage()));
        }
    }

    public function deletehistoria($cedula_p)
    {
        $delete = $this->_mysql->delete("historia", "cedula_p=$cedula_p");

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

    public function gethistoriaAll()
    {
        $data   = array('tabla' => 'historia');
        $result = $this->_mysql->select($data, FALSE);
        return $result;
    }

}

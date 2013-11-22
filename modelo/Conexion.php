<?php

require_once 'ErrorLog.php';

class Conexion extends ErrorLog
{

    private static $_server   = 'localhost';
    private static $_user     = 'root';
    private static $_password = '';
    protected $_bd            = 'maternidad';
    private static $_instancia;
    private $_conexion        = NULL;
    private $_estado_conexion = FALSE;

    private $_resultado= NULL;

    private function __construct()
    {

    }

    private function __wakeup()
    {

    }

    public static function crear()
    {
        if (!(self::$_instancia instanceof self)) {
            self::$_instancia = new self();
        }
        return self::$_instancia;
    }

    private function _open_connection()
    {
    // Activar los errores para mostrarlos por pantallas
        $this->activeError(TRUE);
        $this->_conexion = new mysqli(self::$_server, self::$_user, self::$_password, $this->_bd);
        if ((int) $this->_conexion->connect_errno > 0) {
            $this->_chequearError((int) $this->_conexion->connect_errno);
            exit(utf8_decode("<div style='color:#FF0000;text-align:center;margin:0 auto'>Ocurrió un Error Comuniquese con informatica</div>"));
        } else {
            $this->_estado_conexion = TRUE;
            $this->_conexion->set_charset('utf8');
            return $this->_conexion;
        }
    }

    // Ejecutar cualquier query

    protected function query($sql = '', $rows = FALSE, $organize = TRUE)
    {
        $this->_open_connection();
        $this->_resultado = $this->_conexion->query($sql);
        if ($this->_resultado === FALSE) {
            return FALSE;
        }
        $rez   = array();
        $count = 0;
        $type  = $organize ? MYSQLI_NUM : MYSQLI_ASSOC;
        while (($rows === FALSE || $count < $rows) && $line  = $this->_resultado->fetch_array($type)) {
            if ($organize === TRUE) {
                foreach ($line as $value) {
                    $finfo = $this->_resultado->fetch_field();
                    $table = $finfo->table;
                    if ($table === '') {
                        $table = 0;
                    }
                    $field = $finfo->name;

                    $rez[$count][$table][$field] = $value;
                }
            } else {
                $rez[$count] = $line;
            }
            ++$count;
        }
        $this->_close_connection();
        if ($this->_resultado->free()) {
            return FALSE;
        } else {
            return $rez;
        }
    }

    protected function execute($sql = '', $seek = FALSE)
    {

        $this->_open_connection();
        $this->_resultado = $this->_conexion->query($sql);
        if ($seek === TRUE) {
            return $this->_resultado ? $this->_resultado : FALSE;
        } else {
            return $this->_resultado ? TRUE : FALSE;
        }
        $this->_close_connection();
    }

    protected function select($options)
    {
        $default = array(
            'tabla'     => '',
            'campos'    => '*',
            'condicion' => '1',
            'ordenar'   => '1',
            'limite'    => 200
            );
        $options = array_merge($default, $options);
        $sql = "SELECT {$options['campos']} FROM {$options['tabla']} WHERE {$options['condicion']} ORDER BY {$options['ordenar']} LIMIT {$options['limite']}";
        return $this->query($sql, FALSE, FALSE);
    }

    protected function row($options)
    {
        $default = array(
            'tabla'     => '',
            'campos'    => '*',
            'condicion' => '1',
            'ordenar'   => '1',
            'limite'    => 1
            );
        $options          = array_merge($default, $options);
        $sql              = "SELECT {$options['campos']} FROM {$options['tabla']} WHERE {$options['condicion']} ORDER BY {$options['ordenar']} LIMIT {$options['limite']}";
        $this->_resultado = $this->execute($sql, TRUE);
        $result           = $this->_resultado->fetch_assoc();
        if (empty($result)) {
            return FALSE;
        } else {
            return $result;
        }
    }

    protected function get($table = NULL, $field = NULL, $conditions = '1')
    {

        if ($table === NULL || $field === NULL) {
            return FALSE;
        } else {
            $sql = "SELECT $field FROM $table  WHERE $conditions ORDER BY 1 LIMIT 1";
            $this->_resultado = $this->execute($sql, TRUE);
            if ($this->_resultado->num_rows > 0) {
                $this->_resultado->data_seek(0);
                $row= $this->_resultado->fetch_row();
                $this->_resultado->free();
                return $row[0];
            } else {
                return FALSE;
            }
        }
    }

    protected function first($table = NULL, $field = NULL)
    {
        if ($table === NULL || $field === NULL) {
            return FALSE;
        } else {
            $sql              = "SELECT $field FROM $table";
            $this->_resultado = $this->execute($sql, TRUE);

            if ($this->_resultado->num_rows > 0) {
                $this->_resultado->data_seek(0);
                $row = $this->_resultado->fetch_row();
                $this->_resultado->free();
                return $row[0];
            } else {
                return FALSE;
            }
        }
    }

    protected function last($table = NULL, $field = NULL)
    {
        if ($table === NULL || $field === NULL) {
            return FALSE;
        } else {
            $sql              = "SELECT $field FROM $table";
            $this->_resultado = $this->execute($sql, TRUE);
            if ($this->_resultado->num_rows > 0) {
                $this->_resultado->data_seek($this->_resultado->num_rows - 1);
                $row = $this->_resultado->fetch_row();
                $this->_resultado->free();
                return $row[0];
            } else {
                return $this->_resultado->num_rows;
            }
        }
    }

    protected function numRows($table = NULL, $field = NULL, $conditions = '1')
    {
        if ($table === NULL || $field === NULL) {
            return FALSE;
        } else {
            $sql              = "SELECT $field FROM $table  WHERE $conditions ORDER BY 1";
            $this->_resultado = $this->execute($sql, TRUE);
            $total            = $this->_resultado->num_rows;
            $this->_resultado->free();
            return $total;
        }
    }

    protected function total($table = NULL, $field = NULL, $conditions = '1')
    {

        if ($table === NULL || $field === NULL) {
            return FALSE;
        } else {
            $sql = "SELECT COUNT($field) AS total FROM $table  WHERE $conditions ORDER BY 1 LIMIT 1";
            $this->_resultado = $this->execute($sql, TRUE);
            if ($this->_resultado->num_rows > 0) {
                $this->_resultado->data_seek(0);
                $row= $this->_resultado->fetch_row();
                $this->_resultado->free();
                return $row[0];
            } else {
                return FALSE;
            }
        }
    }

    protected function autoIncremet($table = NULL, $field = NULL)
    {
        if ($table === NULL || $field === NULL) {
            return FALSE;
        } else {
            $sql = "SELECT $field FROM $table ORDER BY $field DESC LIMIT 1 ";

            $this->_resultado = $this->execute($sql, TRUE);
            if ($this->_resultado->num_rows >= 0) {
                $this->_resultado->data_seek($this->_resultado->num_rows - 1);
                $row = $this->_resultado->fetch_row();
                $this->_resultado->free();
                return (int) $row[0] + 1;
            }
        }
    }

    protected function recordExists($table = NULL, $where = NULL)
    {
        if ($table === NULL || $where === NULL) {
            return FALSE;
        } else {

            $sql = "SELECT 1 FROM $table WHERE $where";
            $this->_resultado = $this->execute($sql, TRUE);
            if ($this->_resultado->num_rows > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    protected function insert($table = NULL, $array_of_values = array())
    {
        $this->_open_connection();
        if ($table === NULL || empty($array_of_values) || !is_array($array_of_values)) {
            return FALSE;
        } else {
            $fields = array();
            $values = array();
            foreach ($array_of_values as $id => $value) {
                $fields[] = $id;
                if (is_array($value) && !empty($value[0])) {
                    $values[] = $value[0];
                } else {
                    $values[] = "'" . $this->_conexion->real_escape_string($value) . "'";
                }
            }
            $sql = "INSERT INTO $table (" . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
            $this->_resultado = $this->_conexion->query($sql);
            if ($this->_resultado) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        $this->_close_connection();
    }

    protected function update($table = NULL, $array_of_values = array(), $conditions = FALSE)
    {
        $this->_open_connection();
        if ($table === NULL || empty($array_of_values)) {
            return FALSE;
        } else {
            $what_to_set = array();
            foreach ($array_of_values as $field => $value) {
                if (is_array($value) && !empty($value[0])) {
                    $what_to_set[] = "$field='{$value[0]}'";
                } else {
                    $what_to_set[] = "$field='" . $this->_conexion->real_escape_string($value) . "'";
                }
            }
            $what_to_set_string = implode(',', $what_to_set);

            $sql = "UPDATE $table SET $what_to_set_string WHERE $conditions";

            $this->_resultado = $this->_conexion->query($sql);
            if ($this->_resultado === TRUE) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        $this->_close_connection();
    }

    protected function delete($table = NULL, $conditions = 'FALSE')
    {
        $this->_open_connection();
        $sql = "DELETE FROM $table WHERE $conditions";
        if ($table === NULL) {
            return FALSE;
        } else {
            $this->_resultado = $this->_conexion->query($sql);
            if ($this->_resultado === TRUE) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        $this->_close_connection();
    }

    private function _close_connection()
    {
        if ($this->_estado_conexion === TRUE) {
            $this->_conexion->close();
            $this->_estado_conexion = FALSE;
        }
    }

    protected function formateaBD($fecha) {
        $fechaesp = preg_split('/[\/-]+/', $fecha);
        $revertirfecha = array_reverse($fechaesp);
        $fechabd = implode('-', $revertirfecha);
        return $fechabd;
    }

}
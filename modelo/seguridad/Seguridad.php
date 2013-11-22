<?php
session_start();
require_once '../Conexion.php';

class Seguridad extends Conexion
{

    public function __construct()
    {
        
    }

    public function url($id)
    {
        echo $id;
    }

}

?>

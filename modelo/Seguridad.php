<?php

class Seguridad
{

    public function __construct()
    {

    }

    public function url($url,$modulo)
    {
        $dividir_ruta = explode("Maternidad/", $url);
        $ruta = $dividir_ruta[1];
        $_SESSION['url'] = $ruta;
        $_SESSION['s_modulo'] = $modulo;
    }
}
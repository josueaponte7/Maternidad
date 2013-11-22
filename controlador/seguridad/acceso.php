<?php

session_start();
$perfil = $_SESSION['perfil'];

header('Location:../../menu_priv.php');

/*if ($perfil == 1) {
    $_SESSION['menu'] = 'menu3.php';
    header('Location:../../menu3.php');
}else if ($perfil == 2) {
     $_SESSION['menu'] = 'menu2.php';
    header('Location:../../menu2.php');
}else if($perfil == 3){
    $_SESSION['menu'] = 'menu4.php';
    header('Location:../../menu4.php');
}else if($perfil == 4){
    $_SESSION['menu'] = 'menu5.php';
    header('Location:../../menu5.php');
     
}*/

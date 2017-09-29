<?php

include("misclases.class.php");

$ob_show_errors=new manage_errors;

$ob_show_errors->active_errors(); //Este llamado a la función va a activar para que aparezcan los mensajes de errores por pantalla

$ob_login=new login;

$ob_login->set_time(); //Este llamado a la funcion set_time de la CLASE login, va a establecer el tiempo de expiración de sesion a 10 minutos
$ob_login->show_time(); //Este llamado a la funcion show_time de la CLASE login, va a mostrar por pantalla cual es el tiempo de expiración de session en minutos.
$ob_login->force_login(); //Este llamado a la funcion force_login de la CLASE login, va a forzar al usuario a que deba de iniciar sesion. 

//echo "<br>" $_SESSION['login'];

?>
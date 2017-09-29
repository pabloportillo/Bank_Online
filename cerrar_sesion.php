<?php

//INI SET sirve para que aparezcan los errores por pantalla. IMPORTANTE.
ini_set('display_errors', 'On'); 
ini_set('dispaly_errors', 1);

	session_start();
	
	if(isset($_SESSION['login']))
	{
		
		$_SESSION['login']= "logout"; 
		$_SESSION['nombre']="";
		$_SESSION['id_user']="";
		
		session_destroy();
		
		//header('Location: index.html');	
		//echo "estamos en la página cerrar sesion <br>" . $_SESSION['login'];
		include ("index.html");
			
	}else
	{
		
		//header('Location: index.html'); //CAPA DE SEGURIDAD: SI LA SESSION LOGIN NO EXISTE, REDIRIGE A INDEX.PHP
		//echo "estamos en la página cerrar sesion";
		include ("index.html");
	}

?>


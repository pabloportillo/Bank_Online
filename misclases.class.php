<?php


//DEFINICI�N DE login: CLASE que va a cambiar el tiempo de expiraci�n de la session, y forzar al usuario a que se loguee
class login
{
	function set_time()
	{
		session_cache_limiter('private'); //Una vez que est� en private podemos cambiar el tiempo limite de la sesiones.
		session_cache_expire(10); //Aqu� hemos cambiado el tiempo l�mite de la sesion a 10 minutos. (Tener en cuenta que session_start est� abajo)	
	}
	
	function show_time()
	{		
		$var=session_cache_expire();
		//echo "<br> El tiempo establecido ahora en minutos es: " . $var;
	}
	
	//!!!!!!!!!WARNING!!!!!!!: SE TIENE QUE LLAMAR PRIMERO A LA FUNCI�N set_time antes que a force_login PORQUE EN ESTA �LTIMA ES DONDE SE INICIALIZA LA SESION.
	
	function force_login()
	{
		session_start();
	
		if(!isset($_SESSION['login']))
		{
			header('Location: index.html'); //CAPA DE SEGURIDAD: SI LA SESSION LOGIN NO EXISTE, REDIRIGE A INDEX.PHP
		}
	}	
}

//DEFINICI�N DE manage_errors: CLASE que activa los mensajes de errores por pantalla Y guarda en una variable global los errores y al final del proceso los guarda en la tabla LOG de la BBDD. 
class manage_errors
{
	function active_errors()
	{
		//INI SET sirve para que aparezcan los errores por pantalla. IMPORTANTE.
		ini_set('display_errors', 'On'); 
		ini_set('dispaly_errors', 1);		
	}
	
	function create_errors()
	{
		//EN CONSTRUCCI�N!!!  ------> NO ADMINTE EL USO DE $php_errormsg DENTRO DE UNA CLASE
		if (!empty($php_errormsg))
		{
			if(isset($GLOBALS['error']))
			{
				$GLOBALS["error"]=$GLOBALS["error"] . "<br>" . $php_errormsg;	
				echo "<br> Esto es si existe ya GLOBALS.";
			}else
			{
				$GLOBALS['error']=$php_errormsg;	
				echo "<br> Esto es cuando crea por primera vez GLOBALS.";				
			}
		}else
		{
			echo "<br> No entra en bucle";
		}
	}
	
	function save_errors()
	{
		
	}
	
	function show_errors()
	{
		echo "<p color='red'>" . "ERROR: " . $php_errormsg . "</p>";		
		
	}
	
	
}

//DEFINICI�N DE conf_banco: Clase que va a permitir configurar una sola vez los datos de acceso a la base de datos (Root/pass)
class conf_banco
{
	function connect_db()
	{
		$GLOBALS['servidor']="mysql.webcindario.com";
		$GLOBALS['usuario']="bancoonline";
		$GLOBALS['clave']="PPortill0";
		$GLOBALS['db']="bancoonline";
		
		$servidor=$GLOBALS['servidor'];
		$usuario=$GLOBALS['usuario'];
		$clave=$GLOBALS['clave'];
		$db=$GLOBALS['db'];
		
		$conexion=@mysqli_connect($servidor, $usuario, $clave, $db);
		
		if (!empty($php_errormsg))
		{
			$GLOBALS['error']=$GLOBALS['error'].$php_errormsg;			
		}else
		{
			//echo "<br> No entra en bucle";
		}
				
		// Chequeamos si se ha conectado. SI no se conecta dara el siguiente error
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		
		return $conexion;
	}
}	

class conf_app
{
	function create_db()
	{
		$sql_query="CREATE DATABASE bancoonline;";
		
	}
	
	function create_tables()
	{
		$sql_query_usuario="CREATE TABLE IF NOT EXISTS bancoonline.usuario(
		ID INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
		Nombre VARCHAR(40) NOT NULL, 
		DNI VARCHAR(9) NOT NULL,
		Fecha DATE NOT NULL,
		Telefono INT(9) NOT NULL,
		Password VARCHAR(8) NOT NULL,
		Email VARCHAR(30) NOT NULL,
		nom_usuario VARCHAR(10) NOT NULL UNIQUE
		) ENGINE = InnoDB;";
		
		$sql_query_cuenta="CREATE TABLE IF NOT EXISTS bancoonline.cuenta(
		ID INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		id_usuario INT(4) NOT NULL,
		Fecha DATE NOT NULL,
		Concepto VARCHAR(40) NOT NULL,
		Ingresos DECIMAL(10,0)
		)ENGINE = InnoDB;";
		
		$sql_query_log="CREATE TABLE IF NOT EXISTS bancoonline.log (
		ID int(40) NOT NULL AUTO_INCREMENT,
		fecha_hora datetime NOT NULL,
		usuario varchar(30) NOT NULL,
		usuario_db varchar(30) NOT NULL,
		mensaje text NOT NULL,
		PRIMARY KEY ('ID')
		) ENGINE=InnoDB;";				
	}
	
	function create_primary_key()
	{
		//LOS CAMBIOS PARA GENERAR UN CAMPO UNIQUE SERIAN AQU� DENTRO TAMBIEN
	}
	
	function end_conf_app()
	{
		//ENVIAR MENSAJE "TODO CREADO, BORRE EL ARCHIVO INSTALL"
	}
	
	
}









?>
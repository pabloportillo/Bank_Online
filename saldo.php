<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<?php
include("misclases.class.php");
$ob_show_errors=new manage_errors;
$ob_show_errors->active_errors(); //Este llamado a la función va a activar para que aparezcan los mensajes de errores por pantalla
$ob_login=new login;
$ob_login->set_time(); //Este llamado a la funcion set_time de la CLASE login, va a establecer el tiempo de expiración de sesion a 10 minutos
$ob_login->show_time(); //Este llamado a la funcion show_time de la CLASE login, va a mostrar por pantalla cual es el tiempo de expiración de session en minutos.
$ob_login->force_login(); //Este llamado a la funcion force_login de la CLASE login, va a forzar al usuario a que deba de iniciar sesion.
?>

<html>
<head> 
  <link href="style.css" rel="stylesheet" type="text/css"/>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>saldo.php</title>
</head>
<body>
<div class="contenedor">

<header><h2>Bienvenido a tu Banco online</h2></p></header>

<div class="header">
	<ul class="nav">
		<li> <a href="bancoonline.php">Inicio</a> </li>
		<li> <a class="active" href="saldo.php">Saldo</a> </li>
		<li> <a href="ingreso.php">Ingresos</a> </li>
		<li> <a href="gastos.php">Gastos</a> </li>
		<li> <a href="fechain.php">Movimientos</a> </li>
		<li> <a href="nominas.php">Nominas</a> </li>
		<li> <a href="transferencia.php">Transferencias</a> </li>
		<li> <a href="">Usuario</a> 
			<ul>
				<li><a href="usuario.php">Modificar</a></li>
				<li><a href="cerrar_sesion.php">Cerrar Sesion</a></li>
			</ul>
		
		</li>
	</ul>
</div>

<?php

$GLOBALS['error']="";
$ob_conf_banco=new conf_banco;
  
//$ob_conf_banco->connect_db();
 
 		$conexion_servidor= $ob_conf_banco->connect_db();
			
		$var_inter=$_SESSION['id_user']; //creamos una variable intermedia para usarla en la sentencia select para la base de datos. Se le asigna la variable SESSION NOMBRE que está activa en esta página.
		
		$consulta="SELECT * FROM cuenta WHERE id_usuario = '$var_inter';";
		
		$resultado_consulta=@mysqli_query($conexion_servidor,$consulta); //realiza la consulta que guardamos anteriormente en la variable $consulta en la base de datos banco
		
		//EN CONSTRUCCIÓN QUE SERÁ MOVIDA A UNA CLASE ------- PROVISIONAL
		if (!empty($php_errormsg))
		{
			$GLOBALS['error']=$php_errormsg;			
		}else
		{
			//echo "<br> No entra en bucle";
		}
		//----------------------------------------------------------------	
		
				
		while($resultado_traducido=@mysqli_fetch_array($resultado_consulta, MYSQLI_ASSOC))
		{
			$saldo=$resultado_traducido['Saldo'];
		}

				
		//EN CONSTRUCCIÓN QUE SERÁ MOVIDA A UNA CLASE ------- PROVISIONAL
		if (!empty($php_errormsg))
		{
			$GLOBALS['error']=$php_errormsg;			
		}else
		{
			//echo "<br> No entra en bucle";
		}
		//----------------------------------------------------------------	
	
	
        echo "<div class='tabla'><table>";
		echo "<tr><th>Fecha</th><th>Saldo actual</th></tr>";
		
		if(isset($saldo))
		{		
			echo "<tr><td>" . date("Y-m-d") . "</td>";

			echo "<td>" . $saldo . "&euro;</td></tr>";			
		}

		echo "</table></div>";
		
		
@mysqli_close($conexion_servidor);

//EN CONSTRUCCIÓN QUE SERÁ MOVIDA A UNA CLASE ------- PROVISIONAL
if (!empty($php_errormsg))
{
	$GLOBALS['error']=$php_errormsg;			
}else
{
	//echo "<br> No entra en bucle";
}
//----------------------------------------------------------------	
 
 
//INTRODUCIMOS LOS MENSAJES DE ERROR EN LA BASE DE DATOS --- TABLA LOG 
 if(isset($GLOBALS['error']) and ($GLOBALS['error']!=""))
 {
	 echo "<br><br><br> En estos momentos momentos es imposible atender su solicitud";	 
	 
	 $conexion_servidor =  $ob_conf_banco->connect_db();
	 
	 $user=$_SESSION['nombre'];
	 
	 $date_log=date("Y-m-d H:i:s");
	 
	 $user_db=$GLOBALS['usuario'];
	 
	 $error_log = $GLOBALS['error'];
	 
	 
	 $sql_query = "INSERT INTO bancoonline.log (fecha_hora,usuario,usuario_db,mensaje) VALUES ('$date_log', '$user', '$user_db', '$error_log');";
	 
	 $resultado_consulta = @mysqli_query($conexion_servidor, $sql_query);
	 
	 @mysqli_close($conexion_servidor);
 }

 
 ?>
</div>
<footer>Produced by Pablo Portillo all right reserved</footer> 
</body>
</html>
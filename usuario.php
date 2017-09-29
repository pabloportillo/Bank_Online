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
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>fechain.php</title>
</head>
<body>
<div class="contenedor">

<header><h2>Bienvenido a tu Banco online</h2></p></header>

<div class="header">
	<ul class="nav">
		<li> <a href="bancoonline.php">Inicio</a> </li>
		<li> <a href="saldo.php">Saldo</a> </li>
		<li> <a href="ingreso.php">Ingresos</a> </li>
		<li> <a href="gastos.php">Gastos</a> </li>
		<li> <a href="fechain.php">Movimientos</a> </li>
		<li> <a href="nominas.php">Nominas</a> </li>
		<li> <a href="transferencia.php">Transferencias</a> </li>
		<li> <a class="active" href="">Usuario</a> 
			<ul>
				<li><a href="usuario.php">Modificar</a></li>
				<li><a href="cerrar_sesion.php">Cerrar Sesion</a></li>
			</ul>
		
		</li>
	</ul>
</div>
<!-- Un formulario con el metodo post va a enviar a la página indicada en action valores que serán recogidos por por el método indicado. Por lo tanto va a crear tantas variables como inputs en el formulario en la página de destino.--> 

<?php
	
	//No adminitmos el campo de password de este formulario que este VACIO
	if(!isset($_POST['password']))
	{
		
		conexion_db_user();
		formulario();
		
	}else
	{
	
		update_db_user();
		
	}


function formulario()
{	
		
	echo "<div class='form_login'>";
	
	echo "<form method='post' action='usuario.php' name='update_usuario'>";

	$email=$GLOBALS['email'];
	$telefono=$GLOBALS['telefono'];
	$password=$GLOBALS['password_db'];

	echo "Email:<br><input name='email' value='$email'><br><br>";

	echo "Teléfono:<br><input name='telefono' value='$telefono'><br><br>";

	echo "Password:<br><input name='password' value='$password'><br><br>";


	echo "<input type='submit' name='cambiar' value='cambiar'></form>";
	
	echo "</div>";
	
}



//Funcion que conecta con la base de datos y lee los campos correspondientes al usuario. Que mas tarde se modificaran.
function conexion_db_user()
{
		$ob_conf_banco= new conf_banco;
	
		$conexion_servidor = $ob_conf_banco->connect_db();

		$var_inter=$_SESSION['nombre']; //creamos una variable intermedia para usarla en la sentencia select para la base de datos. Se le asigna la variable SESSION NOMBRE que está activa en esta página.
		$consulta="SELECT * from usuario where nom_usuario = '$var_inter';";
		
		$resultado_consulta=mysqli_query($conexion_servidor,$consulta); //realiza la consulta que guardamos anteriormente en la variable $consulta en la base de datos banco
				
	/*	$resultado_traducido=mysqli_fetch_array($resultado_consulta, MYSQLI_NUM); 
		/*aqui vamos a guardar en esta variable el resultado que nos da la base de datos ya traducido y ordenado por posicion: La posicion 0 en el array $resultado_traducido es el nom_usuario definido en la $consulta y la posicion 1 la password*/
	/*	
		echo "<br> Nombre: " . $resultado_traducido[0];
		echo "<br> Pass: " . $resultado_traducido[1];
	*/
		$resultado_traducido=mysqli_fetch_array($resultado_consulta, MYSQLI_ASSOC); // Array por ASOCIACIÓN. El nombre del campo de la base de datos se va a ASOCIAR al nombre que le das en el campo del array. Ambos tienen que ser iguales.
	
	/*
		ESTO SIMPLEMENTE PARA QUE SAQUE POR PANTALLA LOS VALORES DE RESULTADO TRADUCIDO. UN EJEMPLO
		echo "<br><br>";
		echo $resultado_traducido["Nombre"] . "<br>" . $resultado_traducido["ID"] . "<br>" . $resultado_traducido["Fecha"] . "<br>" . $resultado_traducido["DNI"] . "<br>" . $resultado_traducido["Password"] . "<br>" . $resultado_traducido["Telefono"] . "<br>" . $resultado_traducido["Email"] . "<br>" . $resultado_traducido["nom_usuario"];
	*/
	
	$nombre_db=$resultado_traducido["nom_usuario"];
	$password_db=$resultado_traducido["Password"];
	$email_db=$resultado_traducido["Email"];
	
	$GLOBALS['nombre_db']=$nombre_db; //esta es la forma de definir una variable Global hasta la version PHP 5.4
	$GLOBALS['password_db']=$password_db; //echo $GLOBALS['password_db']; // esta es la nueva forma de definirla en PHP 7 y no aparece por pantalla porque estamos en PHP 5.4
	$GLOBALS['email']=$email_db;
	$GLOBALS['telefono']=$resultado_traducido["Telefono"]; //Esta variable GLOBALS la hemos creado directamente asignandole el valor desde $resultado_traducido. Sin variable intermedia de por medio como las demas globals.
	
	mysqli_close($conexion_servidor); //cerramos la conexión a la base de datos
}
	
	//Funcion que va a modificar los campos del usuario en la base de datos.
	function update_db_user()
	{
			$ob_conf_banco= new conf_banco;
	
			$conexion_servidor = $ob_conf_banco->connect_db();
		
			//NECESITAMOS DE VARIABLES INTERMEDIAS PARA LAS CONSULTAS PORQUE NO SE PUEDEN UTILIZAR COMILLAS DENTRO DE LAS SENTENCIAS. Ejemplo $_POST['']. Por lo menos en PHP 5.2
			$var_inter=$_SESSION['nombre']; //creamos una variable intermedia para usarla en la sentencias SQL para la base de datos. Se le asigna la variable SESSION NOMBRE que está activa en esta página.
			$var_inter_email=$_POST['email'];
			$var_inter_telefono=$_POST['telefono'];
			$var_inter_password=$_POST['password'];
			
			$consulta="UPDATE usuario SET Email='$var_inter_email', Telefono='$var_inter_telefono', Password='$var_inter_password' WHERE nom_usuario = '$var_inter';";
			
			$resultado_consulta=mysqli_query($conexion_servidor,$consulta); //Aquí se realiza la modificación de los campos en la base de datos. 
			
			//Ahora haremos una nueva consulta para comprobar que se han introducido los valores. 
			$check_consulta="SELECT Email,Telefono,Password from usuario where nom_usuario = '$var_inter';";
			
			$check_resul_consulta=mysqli_query($conexion_servidor,$check_consulta);
			
			$resultado_traducido=mysqli_fetch_array($check_resul_consulta, MYSQLI_ASSOC);

			echo "<div class='form_login'>";
			
			if($_POST['email']==$resultado_traducido['Email'])
			{
				
				echo "<p class='parrafo'> Datos Actualizados:</p>Email: " . $resultado_traducido['Email'];
				
				
			}
			
			if($_POST['telefono']==$resultado_traducido['Telefono'])
			{
				
				echo "<br>Telefono: " . $resultado_traducido['Telefono'];
				
			}
			
			if($_POST['password']==$resultado_traducido['Password'])
			{
				
				echo "<br>Password: " . $resultado_traducido['Password'];
				
			}
			
			echo "</div>";
			
			$GLOBALS['password_db']=$resultado_traducido["Password"];
			$GLOBALS['email']=$resultado_traducido["Email"];
			$GLOBALS['telefono']=$resultado_traducido["Telefono"];
			
			mysqli_close($conexion_servidor);
									
	}
	
//INTRODUCIMOS LOS MENSAJES DE ERROR EN LA BASE DE DATOS --- TABLA LOG 
 if(isset($GLOBALS['error']) and ($GLOBALS['error']!=""))
 {
	 echo "<br><br><br> En estos momentos momentos es imposible atender su solicitud";	 
	 
	 $ob_conf_banco=new conf_banco;
	 
	 $conexion_servidor =  $ob_conf_banco->connect_db();
	 
	 $user=$_SESSION['nombre'];
	 
	 $date_log=date("Y-m-d H:i:s");
	 
	 $user_db=$GLOBALS['usuario'];
	 
	 $error_log = $GLOBALS['error'];
	 
	 
	 $sql_query = "INSERT INTO bancoonline.log (fecha_hora,usuario,usuario_db,mensaje) VALUES ('$date_log', '$user', '$user_db', '$error_log');";
	 
	 $resultado_consulta = @mysqli_query($conexion_servidor, $sql_query);
	 
	 mysqli_close($conexion_servidor); 
 }	
	
?>

</div>
<footer>Produced by Pablo Portillo all right reserved</footer>
</body>
</html>


  

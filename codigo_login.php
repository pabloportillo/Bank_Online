<?php

//INI SET sirve para que aparezcan los errores por pantalla. IMPORTANTE.
ini_set('display_errors', 'On'); 
ini_set('dispaly_errors', 1);

include ('misclases.class.php');

/* Esta parte de codigo va a contener la verificación del usuario con la conexión a la base de datos y redigirá a bancoonline.php si se loguea correctamente.*/
/* Esta página usara las variables $_POST, $_SESSION, y las sentecias con sus funciones MYSQL.*/
//if(isset($_SESSION['contador']))
	//&& (isset($_POST['password']))



//Esta es la función que va a conectar con la base de datos y lo va a mostrar por pantalla.
function conexion_db()
{
		$ob_conf_banco=new conf_banco;
		
		$conexion_servidor= $ob_conf_banco->connect_db();
		
		$var_inter=$_POST['usuario']; //creamos una variable intermedia para usarla en la sentencia select para la base de datos
		$consulta="SELECT nom_usuario,Password,ID from usuario where nom_usuario = '$var_inter';";
		
		$resultado_consulta=mysqli_query($conexion_servidor,$consulta); //realiza la consulta que guardamos anteriormente en la variable $consulta en la base de datos bancoonline
				
	/*	$resultado_traducido=mysqli_fetch_array($resultado_consulta, MYSQLI_NUM); 
		/*aqui vamos a guardar en esta variable el resultado que nos da la base de datos ya traducido y ordenado por posicion: La posicion 0 en el array $resultado_traducido es el nom_usuario definido en la $consulta y la posicion 1 la password*/
	/*	
		echo "<br> Nombre: " . $resultado_traducido[0];
		echo "<br> Pass: " . $resultado_traducido[1];
	*/
		$resultado_traducido=mysqli_fetch_array($resultado_consulta, MYSQLI_ASSOC); 
		
		//printf ("%s %s %s",$resultado_traducido["nom_usuario"],$resultado_traducido["Password"],$resultado_traducido["ID"]);
	
	$nombre_db=$resultado_traducido["nom_usuario"];
	$password_db=$resultado_traducido["Password"];
	$id_usuario_db=$resultado_traducido["ID"];
	
	$GLOBALS['nombre_db']=$nombre_db; //esta es la forma de definir una variable Global hasta la version PHP 5.4
	$GLOBALS['password_db']=$password_db;
	//echo $GLOBALS['password_db']; // esta es la nueva forma de definirla en PHP 7 y no aparece por pantalla porque estamos en PHP 5.4
	$GLOBALS['id_user']=$id_usuario_db;
	
	mysqli_close($conexion_servidor); //cerramos la conexión a la base de datos
}

//Esta función va a comprobar la existencia del usuario que ingresa, en la base de datos.
function check_db()
{
	
	$error="Usuario o Password incorrectos";
	//echo "<br> nombre y password recibidos";
	//echo "<br> La variable Global nombre es: " . $GLOBALS['nombre_db'];
	//echo "<br> La variable Global password es: " . $GLOBALS['password_db'];
	//echo "<br> La variable Global ID es: " . $GLOBALS['id_user'];
		
	if($_POST['usuario']==$GLOBALS['nombre_db'])
	{
		if ($_POST['password']==$GLOBALS['password_db'])
		{
			//echo "<br> Session iniciada satisfactoriamente"; //Si llegó hasta este punto, podemos abrir la sesion ya que ha pasado dos capas de seguridad. Post usuario = usario en bd y password lo mismo.
				
			/*Declaración de la variable SESSION del usuario logueado. La session tiene un Identificador de sesion. COMO EL NOMBRE DE USUARIO ES ÚNICO, VAMOS A BASAR LAS CONSULTAS CON EL NOMBRE DE USUARIO*/
			$_SESSION['login']= "id_login"; 
			$_SESSION['nombre']=$GLOBALS['nombre_db'];
			$_SESSION['id_user']=$GLOBALS['id_user'];
			
			header('Location: bancoonline.php'); // Redirije a la pagina bancoonline.php SOLO SI HA PASADO LOS FILTROS DE LOGUEO.
				
		}else
		{
			echo $error; //si no existe el password saca el error
		}
					
	}else
	{
		
		echo $error; //si no existe usuario saca el error
	}
			
	

}


//Este IF comprueba que existan valores en POST usuario Y POST password. Es decir, que el usuario introduzca valores en ambos campos.
if(isset($_POST['usuario']) && isset($_POST['password']))
{
	
	if(($_POST['usuario']!="") or ($_POST['password']!="")) 
	{	
		//echo "usuario y password INTRODUCIDOS";
		conexion_db(); //Llamamos a la función que va a conectar e imprimir la conexion a la base de datos. (Funcion mas abajo)
		check_db(); //Llamamos a la función que va a comprobar la existencia del usuario que ingresa, en la base de datos.
	}
}


//INTRODUCIMOS LOS MENSAJES DE ERROR EN LA BASE DE DATOS --- TABLA LOG 
 if(isset($GLOBALS['error']) and ($GLOBALS['error']!=""))
 {
	 echo "<br> En estos momentos momentos es imposible atender su solicitud";	 
	 
	 $ob_conf_banco=new conf_banco;
	 
	 $conexion_servidor =  $ob_conf_banco->connect_db();
	 
	 $user=$_SESSION['nombre'];
	 
	 $date_log=date("Y-m-d H:i:s");
	 
	 $user_db=$GLOBALS['usuario'];
	 
	 $error_log = $GLOBALS['error'];
	 
	 
	 $sql_query = "INSERT INTO bancoonline.log (fecha_hora,usuario,usuario_db,mensaje) VALUES ('$date_log', '$user', '$user_db', '$error_log');";
	 
	 $resultado_consulta = @mysqli_query($conexion_servidor, $sql_query);
	 
	 mysqli_close($resultado_consulta);
	 
 }	

?>
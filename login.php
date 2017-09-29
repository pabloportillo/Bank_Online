<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head> 
  <link href="style.css" rel="stylesheet" type="text/css"/>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>login</title>
</head>

<body>
<br>
<br>
<br>
<div class="form_login">
<form method="post" action="login.php" name="login">
USUARIO<br><input name="usuario"><br>

<br>PASSWORD<br><input name="password">
<br><br><input class="log" value="Login" type="submit"><br>
  <br>

</form>



<?php 
//INI SET sirve para que aparezcan los errores por pantalla. IMPORTANTE.
ini_set('display_errors', 'On'); 
ini_set('dispaly_errors', 1);

session_start();

if(isset($_SESSION['contador']))
{
	if($_SESSION['contador']<10)
	{
		//echo "Ha intentado loguearse: " . $_SESSION['contador'] . "veces.";
		$_SESSION['contador']++;
		include("codigo_login.php");
	}else
	{
		$_SESSION['contador']=180; //La igualamos a 10 para que no incremente mas. Como metodo de seguridad. (Ya sabemos que sobrepaso el límite)
		//echo "La sesion se BLOQUEARA <br> porque ha tenido mas de " . $_SESSION['contador'] . " intentos. <br>";
		//echo "<br> Una vez bloqueada no se podra restablecer";
		//session_destroy(); //hay que destruir la sesion, si no ahora, mas adelante. Se bloquea aqui.
		echo "Ha superado el límite de intentos.";
	}
}elseif(!isset($_SESSION['contador'])) 
{
	//el if mas externo siempre ha de acabar con un elseif
	//echo "La session no existe y la crearemos <br>";
	$_SESSION['contador']=0;
	include("codigo_login.php");
		
}


?>
</div>
</body>
</html>
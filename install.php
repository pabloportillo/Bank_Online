<?php

function form_install()
{
	echo "<form method='post' action='install.php' name='install'>";

	echo "Servidor:<br><input name='servidor' value=''><br><br>";

	echo "Usuario root:<br><input name='usuario' value=''><br><br>";

	echo "Password:<br><input name='password' value=''><br><br>";

	echo "<input value='Siguiente' type='submit' name='submit'></form>";
	
}

if(!isset($_POST['submit']))
{
	form_install();
}





?>
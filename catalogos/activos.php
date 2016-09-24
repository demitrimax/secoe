<?php
	
	$user = "moises";
	$pass = "pemex11";
	$bbdd = "SECOE";
	 
    $dbh = mysql_connect("localhost", $user, $pass);
    $db = mysql_select_db($bbdd);
 
    $consulta = "SELECT * from cat_activos WHERE subdir = ".$_GET['id'];
    $query = mysql_query($consulta);
    while ($fila = mysql_fetch_array($query)) {
        echo '<option value="'.$fila['id_activo'].'">'.$fila['ACTIVO'].'</option>';
    };
 
?>
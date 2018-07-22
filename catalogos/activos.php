<?php
	
	$user = "moises";
	$pass = "pemex11";
	$bbdd = "SECOE";
	 
    $dbh = mysqli_connect("localhost", $user, $pass);
    $db = mysqli_select_db($dbh, $bbdd);
 
    $consulta = "SELECT * from cat_activos WHERE subdir = ".$_GET['id'];
    $query = mysqli_query($dbh, $consulta);
    while ($fila = mysqli_fetch_array($query)) {
        echo '<option value="'.$fila['id_activo'].'">'.$fila['ACTIVO'].'</option>';
    };
 
?>
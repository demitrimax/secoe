<?php require_once('../Connections/ResEquipos.php'); ?>
<?php
/**
 * Created by Joe of ExchangeCore.com
 */
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


if(isset($_POST['username']) && isset($_POST['password'])){
	
    $adServer = "ldap://svhedc04.pemex.pmx.com";
	// para conocer el servidor ldap ejecute el siguiente comando nslookup
	
    $ldap = ldap_connect($adServer);
    $username = $_POST['username'];
    $password = $_POST['password'];
	
	
    $ldaprdn = 'pemex' . "\\" . $username;

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($ldap, $ldaprdn, $password);
	
	
    if ($bind) {
        //estas insutrcciones corresponde a un usuario autentificado
		echo "usuario autentificado";
		//ahora verificar que exista en la lista de usuarios admitidos
		$query_usuarios = sprintf("SELECT * FROM usuarios WHERE fusuario = %s", GetSQLValueString($username, "int"));
		$usuarios = mysql_query($query_usuarios, $ResEquipos) or die(mysqli_error($ResEquipos));
		$row_usuarios = mysqli_fetch_assoc($usuarios);
		$totalRows_usuarios = mysqli_num_rows($usuarios);
		$nficha = $username;
		$permiso = "";
		if ($totalRows_usuarios > 0) {
			//obtener los persmisos
			$permiso = $row_usuarios['permiso'];
			echo "permiso concecido nivel ".$permiso." ";
			}
		else{
		echo "Sin permiso ";
		}
		$filter="(sAMAccountName=$username)";
		
		echo $filter;
        $result = ldap_search($ldap,"dc=PEMEX.PMX,dc=COM",$filter);
        ldap_sort($ldap,$result,"sn");
        $info = ldap_get_entries($ldap, $result);
         echo $info['count'];
		for ($i=0; $i<$info["count"]; $i++)
        {
            if($info['count'] > 1)
                echo $info['count'];
            echo "<p>You are accessing <strong> ". $info[$i]["sn"][0] .", " . $info[$i]["givenname"][0] ."</strong><br /> (" . $info[$i]["samaccountname"][0] .")</p>\n";
            echo '<pre>';
            var_dump($info);
            echo '</pre>';
            $userDn = $info[$i]["distinguishedname"][0]; 
        }
        @ldap_close($ldap);
    } else {
        $msg = "Invalid email address / password";
        echo $msg;
    }

}else{
?>
    <form action="#" method="POST">
        <label for="username">Username: </label><input id="username" type="text" name="username" /> 
        <label for="password">Password: </label><input id="password" type="password" name="password" />        <input type="submit" name="submit" value="Iniciar Sesión" />
    </form>
<?php } ?> 


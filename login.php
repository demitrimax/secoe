<?php require_once('../Connections/ResEquipos.php'); ?>
<?php
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['Usuario'])) {
  $loginUsername=$_POST['Usuario'];
  $password=$_POST['Contra'];
  $MM_fldUserAuthorization = "permiso";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = false;
  //login al servidor ldp
      $adServer = "ldap://svhedc04.pemex.pmx.com";
	// para conocer el servidor ldap ejecute el siguiente comando nslookup
	
    $ldap = ldap_connect($adServer);
    $username = $_POST['Usuario'];
    $password = $_POST['Contra'];
	
	
    $ldaprdn = 'pemex' . "\\" . $username;

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($ldap, $ldaprdn, $password);
	
	
    if ($bind) {
        //estas insutrcciones corresponde a un usuario autentificado
		echo "usuario autentificado";
		//ahora verificar que exista en la lista de usuarios admitidos
		 mysql_select_db($database_ResEquipos, $ResEquipos);
		$query_usuarios = sprintf("SELECT * FROM usuarios WHERE fusuario = %s", GetSQLValueString($username, "int"));
		$usuarios = mysql_query($query_usuarios, $ResEquipos) or die(mysql_error());
		$row_usuarios = mysql_fetch_assoc($usuarios);
		$totalRows_usuarios = mysql_num_rows($usuarios);
		$nficha = $username;
		$permiso = "";
		if ($totalRows_usuarios > 0) {
			//obtener los persmisos
			$permiso = $row_usuarios['permiso'];
			echo "permiso concecido nivel ".$permiso." ";
			$_SESSION['iduser'] = $row_usuarios['id_usuario'];
			$_SESSION['permiso'] = $row_usuarios['permiso'];
			$_SESSION['usuario'] = $row_usuarios['usuario'];
			$_SESSION['AccesOK'] = "Yes";
			}
		else {
		echo "Sin permiso ";
			}
	  mysql_select_db($database_ResEquipos, $ResEquipos);
  	
  $LoginRS__query=sprintf("SELECT usuario, fusuario, permiso FROM usuarios WHERE fusuario=%s",
  GetSQLValueString($loginUsername, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $ResEquipos) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'permiso');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
	
	}
  
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['AccesOK'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['AccesOK']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LoginPrueba</title>
<link rel="stylesheet" href="css/form.css">
</head>
<body>
<?php if (!isset($_SESSION['MM_Username'])) { ?>
<p align="center"><img src="images/pps_logo.png" width="287" height="97"/><br />
Inicie Sesión
<form action="<?php echo $loginFormAction; ?>" method="POST" enctype="application/x-www-form-urlencoded" name="form" id="form">
		<input name="Usuario" type="text"  placeholder="Ficha o Psudoficha"/>
    	<input name="Contra" type="password" placeholder="Contraseña"/>

		<input name="enviar" type="submit" value="Iniciar Sesión" />
</form>
<?php } 
else {
?>
<p align="center"><img src="images/pps_logo.png" width="287" height="97" /><br /></p>
<p align="center">Bienvenido <?php echo $_SESSION['usuario']; ?><br /></p>
<p>
<a href="<?php echo $logoutAction ?>">Cerrar sesión</a>
</p>
<?php } ?>
</body>
</html>
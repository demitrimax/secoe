<?php require_once('../../Connections/ResEquipos.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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

//SUPONES QUE EL USUARIO SE LOGEO CORRECTAMENTE REGISTRAR ELIMINACION
 $usuario = $_SESSION['MM_Username'];
 $fechahora = date("Y-m-d H:i:s");
 $pagina_actual = $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
 $ipadress = $_SERVER['REMOTE_ADDR'];
 mysql_select_db($database_ResEquipos, $ResEquipos);
$query_log = "INSERT INTO registros (pagina, usuario, fechahora, ip) VALUES ('$pagina_actual', '$usuario', '$fechahora', '$ipadress')";
//echo $query_log;
$registros = mysql_query($query_log, $ResEquipos) or die(mysql_error());

if ((isset($_GET['idEquipo'])) && ($_GET['idEquipo'] != "")) {
  $deleteSQL = sprintf("UPDATE cat_equipos SET estatus = 9 WHERE idEquipo=%s",
                       GetSQLValueString($_GET['idEquipo'], "int"));

  mysql_select_db($database_ResEquipos, $ResEquipos);
  $Result1 = mysql_query($deleteSQL, $ResEquipos) or die(mysql_error());

  $deleteGoTo = "../cat_equipos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Eliminar Equipo</title>
</head>

<body>
</body>
</html>
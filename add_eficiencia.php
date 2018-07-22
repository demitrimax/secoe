<?php require_once('../Connections/ResEquipos.php'); ?>
<?php


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO eficiencia_equipos (fecha, equipoid, eficiencia, observaciones) VALUES (%s, %s, %s, '%s')",
                       $_POST['fecha'], 
                       $_POST['equipoid'], 
                       $_POST['eficiencia'], 
                       $_POST['observaciones']);
	echo $insertSQL;
  mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysqli_query($ResEquipos, $insertSQL) or die(mysqli_error($ResEquipos));

  $insertGoTo = "detalle_equipo.php?idEquipo=" . $_GET['idEquipo'] . "";
  $Cerrar = 1;
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_Recordset1 = $_GET['idEquipo'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_Recordset1 = sprintf("SELECT * FROM cat_equipos WHERE idEquipo = %s", $colname_Recordset1);
$Recordset1 = mysqli_query($ResEquipos, $query_Recordset1) or die(mysqli_error($ResEquipos));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Eficiencia de Equipos</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/form.css">
<style type="text/css">
  .jquery-lightbox-button-close {
    display:none;
  }
</style>
<script type="text/javascript">
function CerrarVentanaLightbox() {
//* alert("Hola")
<?php	if ($cerrar == 1) {
	echo "$.lightbox().close();";
	} ?>
}
</script>
</head>
<body onload="CerrarVentanaLigtbox">
<section id="content">
<a href="#" onclick="CerrarVentanaLightbox()"> Eficiencia de Equipos</a>
<form action="<?php echo $editFormAction; ?>" method="post" name="form" id="form">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fecha:</td>
      <td><input type="text" name="fecha" value="<?php echo date("Y-m-d")?>" size="32" readonly="readonly" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Equipo:</td>
      <td><input type="hidden" name="equipoid" value="<?php echo $_GET['idEquipo']; ?>" size="32" /><input type="text" name="eq" value="<?php echo $row_Recordset1['Equipo']; ?>" size="32" readonly="readonly"/></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Eficiencia:</td>
      <td><input type="text" name="eficiencia" value="" size="32" required="required" autofocus="autofocus"/></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Observaciones:</td>
      <td><input name="observaciones" type="text" value="" size="20" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insertar registro" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form" />
</form>
</section>
</body>
</html>
<?php
mysqli_free_result($Recordset1);
?>

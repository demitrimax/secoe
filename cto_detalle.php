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

$colname_det_ctto = "-1";
if (isset($_GET['idctto'])) {
  $colname_det_ctto = $_GET['idctto'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_det_ctto = sprintf("SELECT * FROM v_det_ctto WHERE ID_CTTO = %s", GetSQLValueString($colname_det_ctto, "text"));
$det_ctto = mysql_query($query_det_ctto, $ResEquipos) or die(mysql_error());
$row_det_ctto = mysql_fetch_assoc($det_ctto);
$totalRows_det_ctto = mysql_num_rows($det_ctto);
?>
<link rel="stylesheet" href="css/responstable.css">

<table width="100%" border="0" class="responstable">
   <tr>
    <th scope="row">Contrato:</td>
    
    <td> <?php echo $row_det_ctto['NO_CONTRATO']; ?></td>
  </tr>
   <tr>
    <th scope="row">Tipo:</td>
    
    <td> <?php echo $row_det_ctto['TIPOCTTO']; ?></td>
  </tr>

  <tr>
    <th scope="row">Fecha de Inicio</td>
    
    <td><?php echo date("d/m/y", strtotime($row_det_ctto['F_INICIO'])); ?></td>
  </tr>
  <tr>
    <th scope="row">Fecha de Término</th>
    <td><?php echo date("d/m/y", strtotime($row_det_ctto['F_FIN'])); ?></td>
  </tr>
  <tr>
    <th scope="row">Plazo:</th>
    <td><?php echo $row_det_ctto['PLAZO']; ?></td>
  </tr>
  <tr>
    <th scope="row">Tarífa</th>
    <td><?php echo number_format($row_det_ctto['TARIFA']); ?></td>
  </tr>
  <tr>
    <th scope="row">Esquema</th>
    <td><?php echo $row_det_ctto['ESQUEMA']; ?></td>
  </tr>
  <tr>
    <th scope="row">Compañía:</th>
    <td><?php echo $row_det_ctto['NombreCia']; ?></td>
  </tr>
  <tr>
    <th scope="row">Objeto del Contrato:</th>
    <td><?php echo $row_det_ctto['OBJETO_CTO']; ?></td>
  </tr>
  <tr>
    <th scope="row">Estatus</th>
    <td><?php echo $row_det_ctto['ESTATUS']; ?></td>
  </tr>
</table>

<form id="form1" name="form1" method="post" action="catalogos/edit_contrato.php?idctto=<?php echo $colname_det_ctto; ?>">
  <input type="hidden" name="REGRESAR" value="<?php echo $_SERVER['HTTP_REFERER'];?>" />
  <input type="submit" name="button" id="button" value="Modificar" />
</form>
<?php
mysql_free_result($det_ctto);
?>
<?php require_once('../Connections/ResEquipos.php'); ?>
<?php


$colname_Recordset1 = "-1";
if (isset($_GET['id_doc'])) {
  $colname_Recordset1 = $_GET['id_doc'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_Recordset1 = sprintf("SELECT nom_archivo, archivo, tipo, tamano, url_file FROM documentos WHERE id_doc = %s", $colname_Recordset1);
$Recordset1 = mysqli_query($ResEquipos, $query_Recordset1) or die(mysqli_error($ResEquipos));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<?php
mysqli_free_result($Recordset1);

$f = utf8_encode($row_Recordset1['nom_archivo']);
$f_url = utf8_encode($row_Recordset1['url_file']);
$size = $row_Recordset1['tamano'];

header ("Content-type:".$row_Recordset1['tipo']);
header('Content-Disposition: attachment; filename='.$f.''); 
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $size);
$fp=fopen("$f_url", "r");
fpassthru($fp);

//echo $f_url;
//echo utf8_encode($row_Recordset1['nom_archivo']);
?>


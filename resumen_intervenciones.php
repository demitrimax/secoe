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
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_lastpot = "SELECT pot.programoficial FROM pot WHERE pot.id_prog = (select max(pot.id_prog) from pot)";
$lastpot = mysql_query($query_lastpot, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_lastpot = mysqli_fetch_assoc($lastpot);
$totalRows_lastpot = mysqli_num_rows($lastpot);


$colname_programa = $row_lastpot['programoficial'];
if (isset($_GET['programa'])) {
  $colname_programa = $_GET['programa'];
}

$colname_anopot = date('Y');
if (isset($_GET['ano'])) {
  $colname_anopot = $_GET['ano'];
}

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_resinter = "SELECT
cat_subdir.SUBDIRECCION AS Subdireccion,
cat_activos.ACTIVO,
SUM(IF(pot.intervencion='PER',1,0)) AS PER,
SUM(IF(pot.intervencion='TER',1,0)) AS TER,
SUM(IF(pot.intervencion='RMA',1,0)) AS RMA,
SUM(IF(pot.intervencion='RME',1,0)) AS RME,
SUM(IF(pot.intervencion='TAP',1,0)) AS TAP
FROM
pot
INNER JOIN cat_activos ON pot.cv_activo = cat_activos.id_activo
INNER JOIN cat_subdir ON cat_activos.subdir = cat_subdir.id_subdir
WHERE pot.anofin = $colname_anopot AND pot.programoficial = '$colname_programa'
GROUP BY ACTIVO
ORDER BY SUBDIRECCION";
$resinter = mysql_query($query_resinter, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_resinter = mysqli_fetch_assoc($resinter);
$totalRows_resinter = mysqli_num_rows($resinter);
//$TablaOperatividad[] = mysql_fetch_array($Recordset1); 

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_programas = "SELECT DISTINCT pot.programoficial from pot";
$programas = mysql_query($query_programas, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_programas = mysqli_fetch_assoc($programas);
$totalRows_programas = mysqli_num_rows($programas);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_anos = "SELECT DISTINCT pot.anofin FROM pot WHERE pot.programoficial = '$colname_programa' ORDER BY anofin ";
$anos = mysql_query($query_anos, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_anos = mysqli_fetch_assoc($anos);
$totalRows_anos = mysqli_num_rows($anos);

//definir variables de sumas
$sumPer = 0; $sumTer = 0; $sumRMA = 0; $sumRME = 0; $sumTAP = 0; 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

</head>

<label for="ProgramaOp">Seleccione el programa operativo:</label>
            <select name="ProgramaOP" id="ProgramaOP" onChange="AplicarFiltro(this)">
            <?php do { 	?>
            	<option value="<?php echo $row_programas['programoficial']?>"<?php if (!(strcmp($row_programas['programoficial'], $colname_programa))) {echo "selected=\"selected\"";} ?>><?php echo $row_programas['programoficial']?></option>
            <?php
				} while ($row_programas = mysqli_fetch_assoc($programas));
			?>
            </select>
          <label for="ProgramaAno">Seleccione el año:</label>
            <select name="ProgramaAno" id="ProgramaAno" onChange="AplicarFiltro(this)">
          	 <?php do { 	?>
            <option value="<?php echo $row_anos['anofin']?>"<?php if (!(strcmp($row_anos['anofin'], $colname_anopot))) {echo "selected=\"selected\"";} ?>><?php echo $row_anos['anofin']?></option>
            <?php
				} while ($row_anos = mysqli_fetch_assoc($anos));
			?>
          </select> 
          
          <p>
          <table border="0">
  	<thead>
    <tr>
      <th>Subdirección</th>
      <th>Activo</th>
      <th>Perforaciones</th>
      <th>Terminaciones</th>
      <th>Rep. Menores</th>
      <tH>Rep. Mayores</th>
      <th>Taponamientos</th>
      <th>TOTALES</th>
    </tr>
    <tbody>
	<?php do { ?>
    <tr>
      <td><?php echo $row_resinter['Subdireccion']; ?></td> 
      <td><?php echo $row_resinter['ACTIVO']; ?></td>
      <td><?php echo $row_resinter['PER']; ?></td> <?php $sumPer = $sumPer + $row_resinter['PER']; ?>
      <td><?php echo $row_resinter['TER']; ?></td> <?php $sumTer = $sumTer + $row_resinter['TER']; ?>
      <td><?php echo $row_resinter['RMA']; ?></td> <?php $sumRMA = $sumRMA + $row_resinter['RMA']; ?>
      <td><?php echo $row_resinter['RME']; ?></td> <?php $sumRME = $sumRME + $row_resinter['RME']; ?>
      <td><?php echo $row_resinter['TAP']; ?></td> <?php $sumTAP = $sumTAP + $row_resinter['TAP']; ?>
      <?php $TotalFila = $row_resinter['PER'] + $row_resinter['TER'] + $row_resinter['RMA'] + $row_resinter['RME'] + $row_resinter['TAP']; ?>
      <td><?php echo $TotalFila; ?></td>
    </tr>
    <?php } while ($row_resinter = mysqli_fetch_assoc($resinter)); ?>
    <tr>
      <td>&nbsp;</td>
      <td>TOTAL</td>
      <td><?php echo $sumPer; ?></td>
      <td><?php echo $sumTer; ?></td>
      <td><?php echo $sumRMA; ?></td>
      <td><?php echo $sumRME; ?></td>
      <td><?php echo $sumTAP; ?></td>
      <?php $TInter = $sumPer + $sumTer + $sumRMA + $sumRME + $sumTAP; ?>
      <td><?php echo $TInter; ?></td>
    </tr>
  </tbody>
</table>

<body>
</body>
</html>
<?php
mysqli_free_result($resinter);
?>
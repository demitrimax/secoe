<?php require_once('../Connections/SECOE.php'); ?>
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

$colname_ProgEquipos = "-1";
if (isset($_GET['idequipo'])) {
  $colname_ProgEquipos = $_GET['idequipo'];
}
$Colores = "";
$VarAno = "";
$anofin = "";
if (isset($_POST['anograf'])) {
	$anofin = $_POST['anograf'];
  $VarAno = " AND anofin = ". $_POST['anograf'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_ProgEquipos = sprintf("SELECT * FROM v_intervenciones WHERE idequipo = %s".$VarAno, GetSQLValueString($colname_ProgEquipos, "int"));
$ProgEquipos = mysql_query($query_ProgEquipos, $ResEquipos) or die(mysql_error());
$row_ProgEquipos = mysql_fetch_assoc($ProgEquipos);
$totalRows_ProgEquipos = mysql_num_rows($ProgEquipos);

$colname_anosequipo = "-1";
if (isset($_GET['idequipo'])) {
  $colname_anosequipo = $_GET['idequipo'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_anosequipo = sprintf("SELECT anofin FROM v_intervenciones WHERE idequipo = %s GROUP BY anofin ORDER BY anofin ASC", GetSQLValueString($colname_anosequipo, "int"));
$anosequipo = mysql_query($query_anosequipo, $ResEquipos) or die(mysql_error());
$row_anosequipo = mysql_fetch_assoc($anosequipo);
$totalRows_anosequipo = mysql_num_rows($anosequipo);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<script type="text/javascript" src="../js/jsapi.js"></script>

<script type="text/javascript">
    google.load("visualization", "1", {packages: ["timeline"]});
	google.setOnLoadCallback(drawChart);

  function drawChart() {
    var container = document.getElementById('example4.2');
    var chart = new google.visualization.Timeline(container);
    var dataTable = new google.visualization.DataTable();
    
    dataTable.addColumn({ type: 'string', id: 'Group' });
    dataTable.addColumn({ type: 'string', role: 'tooltip' });
    dataTable.addColumn({ type: 'string', id: 'ID' });
    dataTable.addColumn({ type: 'date', id: 'Start' });
    dataTable.addColumn({ type: 'date', id: 'End' });
    dataTable.addRows([
      <?php do { 
	  $fini_dia = date("d", strtotime($row_ProgEquipos['fec_ini']));
	  $fini_mes = intval(date("m", strtotime($row_ProgEquipos['fec_ini'])))-1;
	  $fini_ano = date("Y", strtotime($row_ProgEquipos['fec_ini']));
	  $ffin_dia = date("d", strtotime($row_ProgEquipos['fec_fin']));
	  $ffin_mes = intval(date("m", strtotime($row_ProgEquipos['fec_fin'])))-1;
	  $ffin_ano = date("Y", strtotime($row_ProgEquipos['fec_fin']));
	  ?>
	  <?php echo "['".$row_ProgEquipos['programa']."', '".$row_ProgEquipos['intervencion']."', '".$row_ProgEquipos['pozo']."', new Date(".$fini_ano.", ".$fini_mes.", ".$fini_dia."), new Date(".$ffin_ano.", ".$ffin_mes.", ".$ffin_dia.")]"?>, 
	  <?php $Colores = $Colores ."'".$row_ProgEquipos['CodColor']."', "; ?>
	  <?php } while ($row_ProgEquipos = mysql_fetch_assoc($ProgEquipos)); ?>
            
    ]);
    
    var colors = [];
    var colorMap = {
        // should contain a map of category -> color for every category
        PER: '#7fff00',
        TER: '#ffff00',
        RMA: '#238c00',
		RME: '#592df7',
		MOV: '#4da6ff',
		EST: '#e63b6f',
		INAC: '#e63b6f'
    }
    for (var i = 0; i < dataTable.getNumberOfRows(); i++) {
        colors.push(colorMap[dataTable.getValue(i, 1)]);
    }
    
    var rowHeight = 41;
    var chartHeight = (dataTable.getNumberOfRows() + 1) * rowHeight;
    
    var options = {
        timeline: { 

        },                          
        avoidOverlappingGridLines: true,
        height: chartHeight,
        width: '100%',
        colors: colors
    };
    
    // use a DataView to hide the category column from the Timeline
    var view = new google.visualization.DataView(dataTable);
    //view.setColumns([0, 2, 3, 4]);
    
    chart.draw(view, options);
}
google.load('visualization', '1', {packages:['timeline'], callback: drawChart});
</script>

<div id="example4.2" style="height: 300px;"></div>

<body>
</body>
</html>
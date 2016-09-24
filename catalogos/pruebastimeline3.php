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
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_ProgEquipos = sprintf("SELECT * FROM v_intervenciones WHERE idequipo = %s", GetSQLValueString($colname_ProgEquipos, "int"));
$ProgEquipos = mysql_query($query_ProgEquipos, $ResEquipos) or die(mysql_error());
$row_ProgEquipos = mysql_fetch_assoc($ProgEquipos);
$totalRows_ProgEquipos = mysql_num_rows($ProgEquipos);

mysql_select_db($database_ResEquipos, $ResEquipos);
$query_programas = sprintf("SELECT programa, unicoprograma FROM v_intervenciones WHERE idequipo = %s GROUP BY programa", GetSQLValueString($colname_ProgEquipos, "int"));
$programas = mysql_query($query_programas, $ResEquipos) or die(mysql_error());
$row_programas = mysql_fetch_assoc($programas);
$totalRows_programas = mysql_num_rows($programas);



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
   <script src="../vis/dist/vis.js"></script>
  <link href="../vis/dist/vis.css" rel="stylesheet" type="text/css" />

  <style type="text/css">
    body, html {
      font-family: sans-serif;
    }
	    /* alternating column backgrounds */
    .vis-time-axis .vis-grid.vis-odd {
      background: #f5f5f5;
    }
	    /* gray background in weekends, white text color */
    .vis-time-axis .vis-grid.vis-saturday,
    .vis-time-axis .vis-grid.vis-sunday {
      background: gray;
    }
    .vis-time-axis .vis-text.vis-saturday,
    .vis-time-axis .vis-text.vis-sunday {
      color: white;
    }
	 /* custom styles for individual items, load this after vis.css */
    .vis-item.verde {
      background-color: greenyellow;
      border-color:green;
    }
	.vis-item.azul {
	background-color: #009;
	border-color: #06F;
	color: white;
    }
	.vis-item.azulclaro {
	background-color: #0FF;
	border-color: #06F;
	color: #000;
    }
	.vis-item.amarillo {
	background-color: #FF0;
	border-color: #F90;
    }
	.vis-item.naranja {
	background-color: #F60;
	border-color: #FC0;
    }
	.vis-item.rojofuerte {
	background-color: #900;
	border-color: #F00;
	color: white;
    }
	.vis-item.melon {
	background-color: #FC0;
	border-color: #F63;
    }
	.vis-item.verdeclaro {
	background-color: #0F0;
	border-color: #0C0;
    }

  </style>


<body>
<p>
  <input type="button" id="focus2016" value="Solo mostrar 2016"><br>
 </p>
<div id="visualization"></div>
<script type="text/javascript">
<?php $contador = 0; 
$contador2 = -1;
?>
  // DOM element where the Timeline will be attached
  var container = document.getElementById('visualization');
  
   var groups = new vis.DataSet([
           <?php do { 
		   $contador2++;
		   ?>
    {id: <?php echo $row_programas['unicoprograma']; ?>, content: '<?php echo $row_programas['programa']; ?>', value: <?php echo $contador2; ?>},
	<?php } while ($row_programas = mysql_fetch_assoc($programas)); ?>
  ]);

  // Create a DataSet (allows two way data-binding)
  var items = new vis.DataSet([
        <?php do { 
	  $fini_dia = date("d", strtotime($row_ProgEquipos['fec_ini']));
	  $fini_mes = date("m", strtotime($row_ProgEquipos['fec_ini']));
	  $fini_ano = date("Y", strtotime($row_ProgEquipos['fec_ini']));
	  $ffin_dia = date("d", strtotime($row_ProgEquipos['fec_fin']));
	  $ffin_mes = date("m", strtotime($row_ProgEquipos['fec_fin']));
	  $ffin_ano = date("Y", strtotime($row_ProgEquipos['fec_fin']));
	  $tooltip =  $row_ProgEquipos['pozo']." / ".$row_ProgEquipos['intervencion']." | ".date("d-m-Y", strtotime($row_ProgEquipos['fec_ini']))." a ".date("d-m-Y", strtotime($row_ProgEquipos['fec_fin']));
	  $contador++;
	  ?>
 {id: <?php echo $contador; ?>, group: <?php echo $row_ProgEquipos['unicoprograma']; ?>, content: '<?php echo $row_ProgEquipos['pozo']; ?>', start: '<?php echo $fini_ano."-".$fini_mes."-".$fini_dia;?>', end: '<?php echo $ffin_ano."-".$ffin_mes."-".$ffin_dia;?>', className: '<?php echo $row_ProgEquipos['clasecolor']; ?>', title: '<?php echo $tooltip; ?>'},
<?php } while ($row_ProgEquipos = mysql_fetch_assoc($ProgEquipos)); ?>
  ]);

  // Configuration for the Timeline
  var options = {
	  locale: 'es',
	  stack: false,
	  selectable: true
	  };

  // Create a Timeline
	var timeline = new vis.Timeline(container);
  timeline.setOptions(options);
  timeline.setGroups(groups);
  timeline.setItems(items);
  
  document.getElementById('focus2016').onclick = function() {
	  timeline.setWindow('2016-01-01', '2016-12-31');
  }
</script>    
</body>
</html>
<?php
mysql_free_result($ProgEquipos);
mysql_free_result($programas);
?>

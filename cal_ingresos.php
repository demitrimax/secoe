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

$colname_anopot = "2016";
if (isset($_GET['ano'])) {
  $colname_anopot = $_GET['ano'];
}


$colname_pmxcia = "";
if (isset($_GET['pmxcia'])) {
  $colname_pmxcia = $_GET['pmxcia'];
		$colname_pmxcia = "AND tequipo = '$colname_pmxcia'";
}

$colname_inter = "'PER','TER','RMA','RME','SPERF','STERM','SRMA','SRME','EST'";
if (isset($_GET['inter'])) {
  $colname_inter = $_GET['inter'];
}
$colname_inter_array = explode(",", $colname_inter);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_Recordset1 = "SELECT
cat_equipos.idEquipo,
cat_equipos.Equipo,
cat_equipos.Equ_corto,
cat_equipos.TEquipo,
anexoc.RentaDiariaUSD as CuotaAnexoC,
cat_equipocaracteristicas.Caracteristicas,
cat_tipoequipo.tipoequipo,
SUM(IF(operatividad.mes_ano= 'JAN/$colname_anopot', operatividad.dias, 0)) AS ENE,
SUM(IF(operatividad.mes_ano= 'FEB/$colname_anopot', operatividad.dias, 0)) AS FEB,
SUM(IF(operatividad.mes_ano= 'MAR/$colname_anopot', operatividad.dias, 0)) AS MAR,  
SUM(IF(operatividad.mes_ano= 'APR/$colname_anopot', operatividad.dias, 0)) AS ABR,
SUM(IF(operatividad.mes_ano= 'MAY/$colname_anopot', operatividad.dias, 0)) AS MAY,
SUM(IF(operatividad.mes_ano= 'JUN/$colname_anopot', operatividad.dias, 0)) AS JUN,
SUM(IF(operatividad.mes_ano= 'JUL/$colname_anopot', operatividad.dias, 0)) AS JUL,
SUM(IF(operatividad.mes_ano= 'AUG/$colname_anopot', operatividad.dias, 0)) AS AGO,
SUM(IF(operatividad.mes_ano= 'SEP/$colname_anopot', operatividad.dias, 0)) AS SEP,
SUM(IF(operatividad.mes_ano= 'OCT/$colname_anopot', operatividad.dias, 0)) AS OCT,     
SUM(IF(operatividad.mes_ano= 'NOV/$colname_anopot', operatividad.dias, 0)) AS NOV,
SUM(IF(operatividad.mes_ano= 'DEC/$colname_anopot', operatividad.dias, 0)) AS DIC,
sum(operatividad.dias) as TotalDias,
if(cat_equipos.Cia=2,1,2) AS PMXCIA   
FROM
cat_equipos
INNER JOIN pot ON cat_equipos.idEquipo = pot.idequipo
INNER JOIN operatividad ON pot.id_prog = operatividad.id_pot
LEFT JOIN cat_equipocaracteristicas ON cat_equipos.Caracteristicas = cat_equipocaracteristicas.IdCar
LEFT JOIN anexoc ON cat_equipos.ID_TARIFA = anexoc.id_anexoc
LEFT JOIN cat_tipoequipo ON cat_equipos.TEquipo = cat_tipoequipo.idtequipo
WHERE
pot.programoficial = '$colname_programa' AND pot.intervencion IN ($colname_inter) AND RIGHT(operatividad.mes_ano,4) = '$colname_anopot'
GROUP BY 
cat_equipos.idEquipo";
//echo $query_Recordset1;
$Recordset1 = mysql_query($query_Recordset1, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

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

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_inters = "SELECT DISTINCT pot.intervencion FROM pot WHERE pot.programoficial = '$colname_programa' ORDER BY intervencion";
$intervencion = mysql_query($query_inters, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_intervencion = mysqli_fetch_assoc($intervencion);
$totalRows_intervencion = mysqli_num_rows($intervencion);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_anexoc = "SELECT * FROM anexoc";
$anexoc = mysql_query($query_anexoc, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_anexoc = mysqli_fetch_assoc($anexoc);
$totalRows_anexoc = mysqli_num_rows($anexoc);

function OperaMes($dias, $mesano) {
	if ($dias > 0) {
		$mes = substr($mesano, 0, 3);
		$ano = substr($mesano, 4, 4);
		$ultimodiames = date("d",(mktime(0,0,0,$mes+1,1,$ano)-1));
		$operando = $dias/$ultimodiames;
		if ( $operando > 0.1) {
			return 1;
		}
	}
	else {
	return 0;
		}
}
?>
<!DOCTYPE html>
<html lang="es" xmlns:spry="http://ns.adobe.com/spry">
<head>
<title>Calculo de ingresos</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responstable.css">
<script src="js/jquery.js"></script>
<script src="js/jquery-migrate-1.4.1.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/script.js"></script> 
<script src="js/superfish.js"></script>
<script src="js/jquery.equalheights.js"></script>
<script src="js/jquery.mobilemenu.js"></script>
<script src="js/tmStickUp.js"></script>
<script src="js/jquery.ui.totop.js"></script>
<script>
$(window).load(function(){
  $().UItoTop({ easingType: 'easeOutQuart' });
  $('#stuck_container').tmStickUp({});  
 });

</script>
<!--[if lt IE 8]>
 <div style=' clear: both; text-align:center; position: relative;'>
   <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
     <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
   </a>
</div>
<![endif]-->
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<link rel="stylesheet" media="screen" href="css/ie.css">
<![endif]-->
<!-- Script para DataTables -->
	<link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="DataTables/extensions/Buttons/css/buttons.dataTables.min.css">
	
	<script type="text/javascript" charset="utf8" src="DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="DataTables/extensions/Responsive/css/responsive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.html5.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.print.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/examples/resources/demo.js">
	</script>
    <script type="text/javascript" language="javascript" src="DataTables/extensions/jszip.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/extensions/pdfmake.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/extensions/vfs_fonts.js">
	</script>

<!-- Scripts para las graficas --> 
<script src="Chart.js/Chart.bundle.js"></script>
<script src="Chart.js/samples/utils.js"></script>

  <link rel="stylesheet" type="text/css" href="jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css" />
  <script type="text/javascript" src="jquery.lightbox/js/lightbox/jquery.lightbox.min.js"></script>
   <!-- // <script type="text/javascript" src="jquery.lightbox/jquery.lightbox.js"></script>   -->

  <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox();
    });
$(document).ready( function () {
    $('#equiposutil')
		.addClass( 'nowrap' )
		.DataTable({
			dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
			'print'
        ],
			"language": {
                "url": "DataTables/spanish/spanish.json"
						}
	 
	});
} );
  </script>
    <script type="text/javascript">
  	function AplicarFiltro(sel) {
    	var valueA = document.getElementById("ProgramaOP").value;
		var valueB = document.getElementById("ProgramaAno").value;
		var selInter = new Array();
		$('input[name="tintervencion"]:checked').each(function() {
						selInter.push(this.value);
					});
		window.location.href = "operatividad_dias.php?programa="+valueA+"&ano="+valueB+"&inter="+selInter; 
	}
	
  </script>
</head>


<!--==============================
              header
=================================-->
<header>
  <div class="container">
    <div class="row">
      <div class="grid_12 rel">
        <h1>
          <a href="index.html">
            <img src="images/logo2.png" alt="Logo alt">
          </a>
        </h1>
      </div>
    </div>
  </div>
  <section id="stuck_container">
  <!--==============================
              Stuck menu
  =================================-->
    <div class="container">
      <div class="row">
        <div class="grid_12 ">
          <div class="navigation ">
            <nav>
              <ul class="sf-menu">
               <li class="current">Calculo de Ingresos POT: <?php echo $colname_programa;?> | Año: <?php echo $colname_anopot; ?></li>
             </ul>
            </nav>
            <div class="clear"></div>
          </div>       
         <div class="clear"></div>  
        </div>
     </div> 
    </div> 
  </section>
</header>
<!--=====================
          Content
======================-->
<section id="content"><div class="ic"></div>
  <div class="container">
    <div class="row">
    <div class="grid_12">
        <h3>Calculo de Ingresos por Equipo</h3>
        
        <p>Días Operando por equipo</p>
          <p>  
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
          </p>
          <p> Seleccione las intervenciones a filtrar 
           <?php $contG = 0; ?>
		   <?php do { 	?>
          <input type="checkbox" name="tintervencion" value="'<?php echo $row_intervencion['intervencion'];?>'" id="CheckboxGroup1_<?php echo $contG; ?>"<?php if (in_array("'".$row_intervencion['intervencion']."'", $colname_inter_array)) {echo "checked";} ?>>
      		<label for="CheckboxGroup1_<?php echo $contG; ?>"><span></span><?php echo $row_intervencion['intervencion'];?></label> |
            <?php
			$contG++;
				} while ($row_intervencion = mysqli_fetch_assoc($intervencion));
				//print_r($colname_inter_array);
			?>
            <input id="btnSubmit" type="button" value="Actualizar" onClick="AplicarFiltro()" />
          </p>
          <?php do { ?>
          <?php 
			$EqDias[] = array($row_Recordset1['Equ_corto'], $row_Recordset1['TEquipo'], $row_Recordset1['ENE'], $row_Recordset1['FEB'], $row_Recordset1['MAR'], $row_Recordset1['ABR'], $row_Recordset1['MAY'], $row_Recordset1['JUN'], $row_Recordset1['JUL'], $row_Recordset1['AGO'], $row_Recordset1['SEP'], $row_Recordset1['OCT'], $row_Recordset1['NOV'], $row_Recordset1['DIC'], $row_Recordset1['PMXCIA'], $row_Recordset1['idEquipo'], $row_Recordset1['CuotaAnexoC'], $row_Recordset1['tipoequipo'], $row_Recordset1['Caracteristicas']);
			} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
             <?php 
		 $meses = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
		 for ($i=0; $i<count($EqDias); $i++) {
				$EqOp[] = array($EqDias[$i][0], $EqDias[$i][1], $EqDias[$i][2], $EqDias[$i][3], $EqDias[$i][4], $EqDias[$i][5], $EqDias[$i][6], $EqDias[$i][7], $EqDias[$i][8], $EqDias[$i][9], $EqDias[$i][10], $EqDias[$i][11], $EqDias[$i][12], $EqDias[$i][13], $EqDias[$i][14], $EqDias[$i][15], $EqDias[$i][16], $EqDias[$i][17], $EqDias[$i][18]);
		 }
		 //Ejecutar Suma Totales
		 $MEne = 0; $MFeb = 0; $MMar = 0; $MAbr = 0; $MMay = 0; $MJun = 0; $MJul = 0; $MAgo = 0; $MSep = 0; $MOct = 0; $MOct = 0; $MNov = 0; $MDic = 0;
		 $AEPMX = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
		 $AECIA = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
		 $MOD = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
		 $EMP = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
		 $TERPMX = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
		 $TERCIA = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
		 $SS = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
		 for ($i=0; $i<count($EqOp); $i++) {
		 	$MEne += $EqOp[$i][2];
			$MFeb += $EqOp[$i][3];
			$MMar += $EqOp[$i][4];
			$MAbr += $EqOp[$i][5];
			$MMay += $EqOp[$i][6];
			$MJun += $EqOp[$i][7];
			$MJul += $EqOp[$i][8];
			$MAgo += $EqOp[$i][9];
			$MSep += $EqOp[$i][10];
			$MOct += $EqOp[$i][11];
			$MNov += $EqOp[$i][12];
			$MDic += $EqOp[$i][13];
		 if ($EqOp[$i][1]==1 && $EqOp[$i][14]==1) {
			 for ($j=0; $j<12; $j++) {
				$AEPMX[$j] += $EqOp[$i][$j+2];
			 	}
		 	}
		if ($EqOp[$i][1]==1 && $EqOp[$i][14]==2) {
			 for ($j=0; $j<12; $j++) {
				$AECIA[$j] += $EqOp[$i][$j+2];
			 	}
		 	}
		if ($EqOp[$i][1]==2 && $EqOp[$i][14]==2) {
			 for ($j=0; $j<12; $j++) {
				$MOD[$j] += $EqOp[$i][$j+2];
			 	}
		 	}
		if ($EqOp[$i][1]==9 && $EqOp[$i][14]==1) {
			 for ($j=0; $j<12; $j++) {
				$EMP[$j] += $EqOp[$i][$j+2];
			 	}
		 	}
		if (($EqOp[$i][1]==4 || $EqOp[$i][1]==8) && $EqOp[$i][14]==1) {
			 for ($j=0; $j<12; $j++) {
				$TERPMX[$j] += $EqOp[$i][$j+2];
			 	}
		 	}
		if ($EqOp[$i][1]==4 && $EqOp[$i][14]==2) {
			 for ($j=0; $j<12; $j++) {
				$TERCIA[$j] += $EqOp[$i][$j+2];
			 	}
		 	}
		if ($EqOp[$i][1]==3 && $EqOp[$i][14]==2) {
			 for ($j=0; $j<12; $j++) {
				$SS[$j] += $EqOp[$i][$j+2];
			 	}
		 	}
		 }
		for ($i=0; $i<12; $i++) {
		$AEPMX[12] += $AEPMX[$i];
		$AECIA[12] += $AECIA[$i];
		$MOD[12] += $MOD[$i];
		$EMP[12] += $EMP[$i];
		$TERPMX[12] += $TERPMX[$i];
		$TERCIA[12] += $TERCIA[$i];
		$SS[12] += $SS[$i];
		}
		 ?>
          
          <table width="100%" border="0" id="equiposutil" class="display nowrap">
            <thead>
            <tr>
              <th width="300" scope="col"><span class="ta__center"><strong>Equipo</strong></span></th>
              <th width="50" scope="col"><strong>Tipo</strong></th>
              <th width="50" scope="col"><strong>Capacidad</strong></th>
              <th width="50" scope="col"><strong>ENE</strong></th>
              <th width="50" scope="col"><strong>FEB</strong></th>
              <th width="50" scope="col"><strong>MAR</strong></th>
              <th width="50" scope="col"><strong>ABR</strong></th>
              <th width="50" scope="col"><strong>MAY</strong></th>
              <th width="50" scope="col"><strong>JUN</strong></th>
              <th width="50" scope="col"><strong>JUL</strong></th>
              <th width="50" scope="col"><strong>AGO</strong></th>
              <th width="50" scope="col"><strong>SEP</strong></th>
              <th width="50" scope="col"><strong>OCT</strong></th>
              <th width="50" scope="col"><strong>NOV</strong></th>
              <th width="50" scope="col"><strong>DIC</strong></th>
              <th width="50" scope="col"><strong>DIAS ANUAL</strong></th>
              <th width="50" scope="col"><strong>INGR ANUAL USD</strong></th>
            </tr>
            </thead>
            <tbody>
            <?php 
			$AcumDias = 0;
			$AcumIng = 0;
			for ($i=0; $i<count($EqOp); $i++) { 
			$totalDias = $EqDias[$i][2] + $EqDias[$i][3] + $EqDias[$i][4] + $EqDias[$i][5] +$EqDias[$i][6] + $EqDias[$i][7] +$EqDias[$i][8] +$EqDias[$i][9] + $EqDias[$i][10] + $EqDias[$i][11] + $EqDias[$i][12] + $EqDias[$i][13];
			if ( $totalDias > 0 ) {	?>
            <tr>
              <th width="300" scope="row"><a href="equipo_gantt.php?idEquipo=<?php echo $EqOp[$i][15]; ?>&programa='<?php echo $colname_programa; ?>'&ano=<?php echo $colname_anopot; ?>&lightbox[width]=900&lightbox[height]=490&lightbox[iframe]=true" class="lightbox"><?php echo $EqOp[$i][0]; ?></a></th>
              <td width="50"><?php echo $EqOp[$i][17]; ?></td>
              <td width="50"><?php echo $EqOp[$i][18]; ?></td>
              <td width="50"><?php echo $EqOp[$i][2]; ?></td>
              <td width="50"><?php echo $EqOp[$i][3]; ?></td>
              <td width="50"><?php echo $EqOp[$i][4]; ?></td>
              <td width="50"><?php echo $EqOp[$i][5]; ?></td>
              <td width="50"><?php echo $EqOp[$i][6]; ?></td>
              <td width="50"><?php echo $EqOp[$i][7]; ?></td>
              <td width="50"><?php echo $EqOp[$i][8]; ?></td>
              <td width="50"><?php echo $EqOp[$i][9]; ?></td>
              <td width="50"><?php echo $EqOp[$i][10]; ?></td>
              <td width="50"><?php echo $EqOp[$i][11]; ?></td>
              <td width="50"><?php echo $EqOp[$i][12]; ?></td>
              <td width="50"><?php echo $EqOp[$i][13]; ?></td>
              <td width="50"><?php echo $totalDias; ?></td>
              <?php $IngXEquipo = $EqOp[$i][16] * $totalDias; ?>
              <td width="50"><?php echo number_format($IngXEquipo); ?></td>
              <?php 
			  $AcumDias += $totalDias; 
			  $AcumIng += $IngXEquipo;
			  ?>
            </tr>
            <?php 
				}
			} ?>
              </tbody>
              <tfoot>
            <tr>
              <th width="300" scope="row">TOTAL</th>
              <th width="300" scope="row"></th>
              <th width="300" scope="row"></th>
              <td width="50"><strong><?php echo number_format($MEne); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MFeb); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MMar); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MAbr); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MMay); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MJun); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MJul); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MAgo); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MSep); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MOct); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MNov); ?></strong></td>
              <td width="50"><strong><?php echo number_format($MDic); ?></strong></td>
              <td width="50"><strong><?php echo number_format($AcumDias); ?></strong></td>
              <td width="50"><strong><?php echo number_format($AcumIng); ?></strong></td>
            </tr>
          	</tfoot>
        </table>
          <p>Nota: No se contabilizan equipos aligerados, snubing y lacustres.</p>
         
         <p>Por tipo de Equipo</p>
         	<table width="100%" border="0" class="responstable">
            <tr>
              <th width="300" scope="col"><span class="ta__center"><strong>Tipo de Equipo</strong></span></th>
              <th width="50" scope="col"><strong>ENE</strong></th>
              <th width="50" scope="col"><strong>FEB</strong></th>
              <th width="50" scope="col"><strong>MAR</strong></th>
              <th width="50" scope="col"><strong>ABR</strong></th>
              <th width="50" scope="col"><strong>MAY</strong></th>
              <th width="50" scope="col"><strong>JUN</strong></th>
              <th width="50" scope="col"><strong>JUL</strong></th>
              <th width="50" scope="col"><strong>AGO</strong></th>
              <th width="50" scope="col"><strong>SEP</strong></th>
              <th width="50" scope="col"><strong>OCT</strong></th>
              <th width="50" scope="col"><strong>NOV</strong></th>
              <th width="50" scope="col"><strong>DIC</strong></th>
              <th width="50" scope="col"><strong>PROMEDIO ANUAL</strong></th>
             </tr>
             <tr>
              <th width="300" scope="row"> A/E PMX </th>
              <td width="50"><?php echo $AEPMX[0]; ?></td>
              <td width="50"><?php echo $AEPMX[1]; ?></td>
              <td width="50"><?php echo $AEPMX[2]; ?></td>
              <td width="50"><?php echo $AEPMX[3]; ?></td>
              <td width="50"><?php echo $AEPMX[4]; ?></td>
              <td width="50"><?php echo $AEPMX[5]; ?></td>
              <td width="50"><?php echo $AEPMX[6]; ?></td>
              <td width="50"><?php echo $AEPMX[7]; ?></td>
              <td width="50"><?php echo $AEPMX[8]; ?></td>
              <td width="50"><?php echo $AEPMX[9]; ?></td>
              <td width="50"><?php echo $AEPMX[10]; ?></td>
              <td width="50"><?php echo $AEPMX[11]; ?></td>
              <td width="50"><?php echo number_format($AEPMX[12]/365,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> A/E CIA </th>
              <td width="50"><?php echo $AECIA[0]; ?></td>
              <td width="50"><?php echo $AECIA[1]; ?></td>
              <td width="50"><?php echo $AECIA[2]; ?></td>
              <td width="50"><?php echo $AECIA[3]; ?></td>
              <td width="50"><?php echo $AECIA[4]; ?></td>
              <td width="50"><?php echo $AECIA[5]; ?></td>
              <td width="50"><?php echo $AECIA[6]; ?></td>
              <td width="50"><?php echo $AECIA[7]; ?></td>
              <td width="50"><?php echo $AECIA[8]; ?></td>
              <td width="50"><?php echo $AECIA[9]; ?></td>
              <td width="50"><?php echo $AECIA[10]; ?></td>
              <td width="50"><?php echo $AECIA[11]; ?></td>
              <td width="50"><?php echo number_format($AECIA[12]/365,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> MOD CIA </th>
              <td width="50"><?php echo $MOD[0]; ?></td>
              <td width="50"><?php echo $MOD[1]; ?></td>
              <td width="50"><?php echo $MOD[2]; ?></td>
              <td width="50"><?php echo $MOD[3]; ?></td>
              <td width="50"><?php echo $MOD[4]; ?></td>
              <td width="50"><?php echo $MOD[5]; ?></td>
              <td width="50"><?php echo $MOD[6]; ?></td>
              <td width="50"><?php echo $MOD[7]; ?></td>
              <td width="50"><?php echo $MOD[8]; ?></td>
              <td width="50"><?php echo $MOD[9]; ?></td>
              <td width="50"><?php echo $MOD[10]; ?></td>
              <td width="50"><?php echo $MOD[11]; ?></td>
              <td width="50"><?php echo number_format($MOD[12]/365,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> EMPAQUETADOS </th>
              <td width="50"><?php echo $EMP[0]; ?></td>
              <td width="50"><?php echo $EMP[1]; ?></td>
              <td width="50"><?php echo $EMP[2]; ?></td>
              <td width="50"><?php echo $EMP[3]; ?></td>
              <td width="50"><?php echo $EMP[4]; ?></td>
              <td width="50"><?php echo $EMP[5]; ?></td>
              <td width="50"><?php echo $EMP[6]; ?></td>
              <td width="50"><?php echo $EMP[7]; ?></td>
              <td width="50"><?php echo $EMP[8]; ?></td>
              <td width="50"><?php echo $EMP[9]; ?></td>
              <td width="50"><?php echo $EMP[10]; ?></td>
              <td width="50"><?php echo $EMP[11]; ?></td>
              <td width="50"><?php echo number_format($EMP[12]/365,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> TERRESTRE PMX </th>
              <td width="50"><?php echo $TERPMX[0]; ?></td>
              <td width="50"><?php echo $TERPMX[1]; ?></td>
              <td width="50"><?php echo $TERPMX[2]; ?></td>
              <td width="50"><?php echo $TERPMX[3]; ?></td>
              <td width="50"><?php echo $TERPMX[4]; ?></td>
              <td width="50"><?php echo $TERPMX[5]; ?></td>
              <td width="50"><?php echo $TERPMX[6]; ?></td>
              <td width="50"><?php echo $TERPMX[7]; ?></td>
              <td width="50"><?php echo $TERPMX[8]; ?></td>
              <td width="50"><?php echo $TERPMX[9]; ?></td>
              <td width="50"><?php echo $TERPMX[10]; ?></td>
              <td width="50"><?php echo $TERPMX[11]; ?></td>
              <td width="50"><?php echo number_format($TERPMX[12]/365,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> TERRESTRE CIA </th>
              <td width="50"><?php echo $TERCIA[0]; ?></td>
              <td width="50"><?php echo $TERCIA[1]; ?></td>
              <td width="50"><?php echo $TERCIA[2]; ?></td>
              <td width="50"><?php echo $TERCIA[3]; ?></td>
              <td width="50"><?php echo $TERCIA[4]; ?></td>
              <td width="50"><?php echo $TERCIA[5]; ?></td>
              <td width="50"><?php echo $TERCIA[6]; ?></td>
              <td width="50"><?php echo $TERCIA[7]; ?></td>
              <td width="50"><?php echo $TERCIA[8]; ?></td>
              <td width="50"><?php echo $TERCIA[9]; ?></td>
              <td width="50"><?php echo $TERCIA[10]; ?></td>
              <td width="50"><?php echo $TERCIA[11]; ?></td>
              <td width="50"><?php echo number_format($TERCIA[12]/365,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> SEMISUMERGIBLES </th>
              <td width="50"><?php echo $SS[0]; ?></td>
              <td width="50"><?php echo $SS[1]; ?></td>
              <td width="50"><?php echo $SS[2]; ?></td>
              <td width="50"><?php echo $SS[3]; ?></td>
              <td width="50"><?php echo $SS[4]; ?></td>
              <td width="50"><?php echo $SS[5]; ?></td>
              <td width="50"><?php echo $SS[6]; ?></td>
              <td width="50"><?php echo $SS[7]; ?></td>
              <td width="50"><?php echo $SS[8]; ?></td>
              <td width="50"><?php echo $SS[9]; ?></td>
              <td width="50"><?php echo $SS[10]; ?></td>
              <td width="50"><?php echo $SS[11]; ?></td>
              <td width="50"><?php echo number_format($SS[12]/365,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> TOTAL </th>
              <td width="50"><strong><?php echo $MEne; ?></strong></td>
              <td width="50"><strong><?php echo $MFeb; ?></strong></td>
              <td width="50"><strong><?php echo $MMar; ?></strong></td>
              <td width="50"><strong><?php echo $MAbr; ?></strong></td>
              <td width="50"><strong><?php echo $MMay; ?></strong></td>
              <td width="50"><strong><?php echo $MJun; ?></strong></td>
              <td width="50"><strong><?php echo $MJul; ?></strong></td>
              <td width="50"><strong><?php echo $MAgo; ?></strong></td>
              <td width="50"><strong><?php echo $MSep; ?></strong></td>
              <td width="50"><strong><?php echo $MOct; ?></strong></td>
              <td width="50"><strong><?php echo $MNov; ?></strong></td>
              <td width="50"><strong><?php echo $MDic; ?></strong></td>
              <?php $MTotal = $MEne+$MFeb+$MMar+$MAbr+$MMay+$MJun+$MJul+$MAgo+$MSep+$MOct+$MNov+$MDic; ?>
              <td width="50"><strong><?php echo number_format($MTotal/365,2); ?></strong></td>
            </tr>
          </table>
        </ul>
      </div>
    <a href="operatividad.php">Operatividad anual equipos</a> |</div>
    
      <div class="grid_12">
    <a href="index.php">Regresar</a> </div>

        <h3></h3>
         
    </div>
    <div id="codigo1" style="display:none">
        	<p> Sentencia SQL : <?php echo $query_Recordset1; ?> </p>
      	</div>
</section>
<!--==============================
              footer
=================================-->
<footer id="footer">
  <div class="container">
    <div class="row">
      <div class="grid_12"> 
       <div class="copyright"><span class="brand">Pemex Perforación y Servicios</span> &copy; <span id="copyright-year"></span> | <a href="#">Politica de privacidad</a>
          <div class="sub-copy">Website diseñado por <a href="http://intranet.pemex.com/os/pep/unp/gep/Paginas/Home.aspx" rel="nofollow">Gerencia de Estrategias y Planes</a></div>
        </div>
      </div>
    </div>
  </div>  
</footer>
<a href="#" id="toTop" class="fa fa-chevron-up"></a>

</body>
</html>
<?php
mysqli_free_result($Recordset1);
?>

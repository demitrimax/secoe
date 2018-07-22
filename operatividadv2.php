<?php require_once('../Connections/ResEquipos.php'); ?>
<?php

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_lastpot = "SELECT pot.programoficial FROM pot WHERE pot.id_prog = (select max(pot.id_prog) from pot)";
$lastpot = mysqli_query($ResEquipos, $query_lastpot) or die(mysqli_error($ResEquipos));
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
$colname_inter = "'PER','TER','RMA','RME','EST','DESPL','TAP'";
if (isset($_GET['inter'])) {
  $colname_inter = $_GET['inter'];
}

$colname_activo = "'1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24'";
if (isset($_GET['activo'])) {
  $colname_activo = $_GET['activo'];
}

$colname_idesq = "";
if (isset($_GET['idesq'])) {
  $colname_idesq = $_GET['idesq'];
}

$colname_inter_array = explode(",", $colname_inter);
$colname_esquema_array = explode(",", $colname_idesq);
$colname_activo_array = explode(",", $colname_activo);

// Función para restar fechas
function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);
	$interval = date_diff($datetime1, $datetime2);
	return $interval->format($differenceFormat);
}
// Termina Función para restar fechas
$FechaInicio = $colname_anopot-"01-01";
$FechaTermino = $colname_anopot-"12-31";
$DiasAno = dateDifference($FechaInicio,$FechaTermino);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_Recordset1 = "SELECT
cat_equipos.idEquipo,
cat_equipos.Equipo,
cat_equipos.Equ_corto,
cat_equipos.TEquipo,
cat_tipoequipo.tipoequipo,
cat_equipocaracteristicas.Caracteristicas,
cat_equipos.PERREP,
if(cat_equipos.Cia=2,1,2) AS PMXCIA,
IF (cat_equipos.Cia = 2, 'PPS', 'CIA') AS PMXCIA2,
cat_subdir.SIGLAS_SUBDIR,
cat_activos.ACTIVO_CORTO,
v_ult_contrato.NO_CONTRATO,
cat_esquemacto.ESQ_CORTO AS ESQUEMA,
if (cat_equipos.Cia=2,'ADMON',cat_esquemacto.ESQUEMA) AS ESQUEMA2,
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
SUM(IF(operatividad.mes_ano= 'DEC/$colname_anopot', operatividad.dias, 0)) AS DIC
FROM
cat_equipos
INNER JOIN pot ON cat_equipos.idEquipo = pot.idequipo
INNER JOIN operatividad ON pot.id_prog = operatividad.id_pot
INNER JOIN cat_subdir ON cat_equipos.SUBDIR = cat_subdir.id_subdir
INNER JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo
INNER JOIN cat_tipoequipo ON cat_equipos.TEquipo = cat_tipoequipo.idtequipo
INNER JOIN cat_equipocaracteristicas ON cat_equipos.Caracteristicas = cat_equipocaracteristicas.idcar
LEFT JOIN v_ult_contrato ON cat_equipos.idEquipo = v_ult_contrato.EQUIPOID
LEFT JOIN cat_esquemacto ON v_ult_contrato.ESQUEMA = cat_esquemacto.IDESQ

WHERE
pot.programoficial = '$colname_programa' AND pot.intervencion IN ($colname_inter) AND cat_equipos.ACTIVO IN ($colname_activo)
GROUP BY 
cat_equipos.idEquipo";
//echo $query_Recordset1;
$Recordset1 = mysqli_query($ResEquipos, $query_Recordset1) or die(mysqli_error($ResEquipos));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
$TablaOperatividad[] = ""; //mysqli_fetch_array($Recordset1); 

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_programas = "SELECT DISTINCT pot.programoficial from pot";
$programas = mysqli_query($ResEquipos, $query_programas) or die(mysqli_error($ResEquipos));
$row_programas = mysqli_fetch_assoc($programas);
$totalRows_programas = mysqli_num_rows($programas);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_anos = "SELECT DISTINCT pot.anofin FROM pot WHERE pot.programoficial = '$colname_programa' ORDER BY anofin ";
$anos = mysqli_query($ResEquipos, $query_anos) or die(mysqli_error($ResEquipos));
$row_anos = mysqli_fetch_assoc($anos);
$totalRows_anos = mysqli_num_rows($anos);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_inters = "SELECT DISTINCT pot.intervencion FROM pot WHERE pot.programoficial = '$colname_programa' ORDER BY intervencion";
$intervencion = mysqli_query($ResEquipos, $query_inters) or die(mysql_error($ResEquipos));
$row_intervencion = mysqli_fetch_assoc($intervencion);
$totalRows_intervencion = mysqli_num_rows($intervencion);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_esquema = "SELECT * FROM cat_esquemacto ORDER BY cat_esquemacto.esquema";
$esquemas = mysqli_query($ResEquipos, $query_esquema) or die(mysql_error($ResEquipos));
$row_esquemas = mysqli_fetch_assoc($esquemas);
$totalRows_esquemas = mysqli_num_rows($esquemas);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_activos = "SELECT * FROM cat_activos WHERE visible = 1";
$activos = mysqli_query($ResEquipos, $query_activos) or die(mysql_error($ResEquipos));
$row_activos = mysqli_fetch_assoc($activos);
$totalRows_activos = mysqli_num_rows($activos);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_resinter = "SELECT
cat_subdir.SIGLAS_SUBDIR AS Subdireccion,
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
AND pot.cv_activo IN ($colname_activo)
GROUP BY ACTIVO
ORDER BY SUBDIRECCION";
$resinter = mysqli_query($ResEquipos, $query_resinter) or die(mysql_error($ResEquipos));
$row_resinter = mysqli_fetch_assoc($resinter);
$totalRows_resinter = mysqli_num_rows($resinter);

//definir variables de sumas
$sumPer = 0; $sumTer = 0; $sumRMA = 0; $sumRME = 0; $sumTAP = 0; 

function OperaMes($dias, $mesano) {
	if ($dias > 0) {
		$mes = substr($mesano, 0, 3);
		$ano = substr($mesano, 4, 7);
		$mes_ = date("m", strtotime($ano."-".$mes."-01"));
		$ano_ = date("Y", strtotime($ano."-".$mes."-01"));
		$ultimodiames = date("d",(mktime(0,0,0,$mes_+1,0,$ano_)));
		$operando = $dias/$ultimodiames;
		if ( $operando > 0.1) {
			return 1;
		}
		else {
			return 0;
		}
	}
	else {
	return 0;
		}
}
function generateCsv($data, $delimiter = ',', $enclosure = '"') {
       $handle = fopen('php://temp', 'r+');
      
		foreach ($data as $line) {
               fputcsv($handle, $line, $delimiter, $enclosure);
       }
       rewind($handle);
       while (!feof($handle)) {
               $contents .= fread($handle, 8192);
       }
       fclose($handle);
       return $contents;
}
?>
<!DOCTYPE html>
<html lang="es" xmlns:spry="http://ns.adobe.com/spry">
<head>
<title>Operatividad de Equipos <?php echo $colname_programa;?></title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style_3.css"> 
<link rel="stylesheet" href="css/responstable.css">
<!-- las hojas de estilos para el multiselect -->
    <link rel="stylesheet" href="js/multiple-select.css" />
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
	<link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="DataTables/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="DataTables/examples/resources/demo.css">
	<link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="DataTables/extensions/Responsive/css/responsive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>
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
			"language": {
                "url": "DataTables/spanish/spanish.json"
						},
			"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
			var colNumber = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
			            // Total over all pages
            for (i = 0; i< colNumber.length; i++) {
				var colNo = colNumber[i];
				
				total = api
					.column( colNo )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Total over this page
				pageTotal = api
					.column( colNo, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Update footer
				$( api.column(colNo).footer() ).html(
					'<strong>'+ total +'</strong> (' + pageTotal +')'
				);
			}
		}
	});
} );


  </script>
    <script type="text/javascript">
  	function AplicarFiltro(sel) {
    	var valueA = document.getElementById("ProgramaOP").value;
		var valueB = document.getElementById("ProgramaAno").value;
		var selInter = new Array();
		selInter=$('#interven').multipleSelect('getSelects');
		var selEsq = new Array();
		$('input[name="tesquema"]:checked').each(function() {
						selEsq.push(this.value);
					});
		var selAct = new Array();
		
		selAct = $('#ms').multipleSelect('getSelects');

		window.location.href = "operatividadv2.php?programa="+valueA+"&ano="+valueB+"&inter="+selInter+"&esquema="+selEsq+"&activo="+selAct; 
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
               <li class="current">Operatividad de Equipos POT: <?php echo $colname_programa;?> | Año: <?php echo $colname_anopot; ?></li>
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
        <h3>Operatividad de equipos</h3>
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
          <select id="interven" multiple="multiple">
		   <?php do { 	?>
			<option value="'<?php echo $row_intervencion['intervencion'];?>'" <?php if (in_array("'".$row_intervencion['intervencion']."'", $colname_inter_array)) {echo "selected";} ?>> <?php echo $row_intervencion['intervencion'];?> </option>
           <?php
				} while ($row_intervencion = mysqli_fetch_assoc($intervencion));
				//print_r($colname_inter_array);
			?>
            <input id="btnSubmit" type="button" value="Actualizar" onClick="AplicarFiltro()" />
          </p>
          <p> Seleccione las Activos a Filtrar 
          <select id="ms" multiple="multiple">
             <?php do { 	?>
            <option value="'<?php echo $row_activos['id_activo'];?>'"<?php if (in_array("'".$row_activos['id_activo']."'", $colname_activo_array)) {echo "selected";} ?>><?php echo $row_activos['ACTIVO'];?></option>
<?php    } while ($row_activos = mysqli_fetch_assoc($activos)); ?>
        </select>

          <input id="btnSubmit" type="button" value="Actualizar" onClick="AplicarFiltro()" />
          </p>
<!--
<script src="js/multiple-select-master/demos/assets/jquery.min.js"></script> -->
<script src="js/multiple-select.js"></script>
<script>
    $(function() {
        $('#ms').change(function() {
            console.log($(this).val());
        }).multipleSelect({
            width: '50%'
        });
		 $('#interven').change(function() {
            console.log($(this).val());
        }).multipleSelect({
            width: '50%'
        });
    });
</script>
          
        <?php do { ?>
          <?php 
			$EqDias[] = array($row_Recordset1['Equ_corto'], $row_Recordset1['TEquipo'], $row_Recordset1['ENE'], $row_Recordset1['FEB'], $row_Recordset1['MAR'], $row_Recordset1['ABR'], $row_Recordset1['MAY'], $row_Recordset1['JUN'], $row_Recordset1['JUL'], $row_Recordset1['AGO'], $row_Recordset1['SEP'], $row_Recordset1['OCT'], $row_Recordset1['NOV'], $row_Recordset1['DIC'], $row_Recordset1['PMXCIA'], $row_Recordset1['idEquipo'], $row_Recordset1['Caracteristicas'], $row_Recordset1['PMXCIA2'], $row_Recordset1['tipoequipo'], $row_Recordset1['ESQUEMA2'], $row_Recordset1['PERREP']);
			 
			 //MATRIZ ALMACENAR DIAS
			 $EquipDias[] = array('EqCorto'=>$row_Recordset1['Equ_corto'], 'TipoEquipo'=>$row_Recordset1['tipoequipo'],'TEquipo'=>$row_Recordset1['TEquipo'],'ENE'=>$row_Recordset1['ENE'], 'FEB'=>$row_Recordset1['FEB'], 'MAR'=>$row_Recordset1['MAR'], 'ABR'=>$row_Recordset1['ABR'], 'MAY'=>$row_Recordset1['MAY'], 'JUN'=>$row_Recordset1['JUN'], 'JUL'=>$row_Recordset1['JUL'], 'AGO'=>$row_Recordset1['AGO'], 'SEP'=>$row_Recordset1['SEP'], 'OCT'=>$row_Recordset1['OCT'], 'NOV'=>$row_Recordset1['NOV'], 'DIC'=>$row_Recordset1['DIC'], 'PMXCIA'=>$row_Recordset1['PMXCIA2'], 'IDEQUIPO'=>$row_Recordset1['idEquipo'], 'CARACTERISTICAS'=>$row_Recordset1['Caracteristicas'], 'ESQUEMA'=>$row_Recordset1['ESQUEMA2'], 'PERREP'=>$row_Recordset1['PERREP']);
			} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); 
			 
			 //CONVERTIR DIAS POR MES A SOLO 1
			 
		 $meses = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
		 for ($i=0; $i<count($EqDias); $i++) {
				$EqOp[] = array($EqDias[$i][0], $EqDias[$i][1], OperaMes($EqDias[$i][2],"JAN/$colname_anopot"), OperaMes($EqDias[$i][3],"FEB/$colname_anopot"), OperaMes($EqDias[$i][4],"MAR/$colname_anopot"), OperaMes($EqDias[$i][5],"APR/$colname_anopot"), OperaMes($EqDias[$i][6],"MAY/$colname_anopot"), OperaMes($EqDias[$i][7],"JUN/$colname_anopot"), OperaMes($EqDias[$i][8],"JUL/$colname_anopot"), OperaMes($EqDias[$i][9],"AUG/$colname_anopot"), OperaMes($EqDias[$i][10],"SEP/$colname_anopot"), OperaMes($EqDias[$i][11],"OCT/$colname_anopot"), OperaMes($EqDias[$i][12],"NOV/$colname_anopot"), OperaMes($EqDias[$i][13],"DEC/$colname_anopot"), $EqDias[$i][14], $EqDias[$i][15], $EqDias[$i][17], $EqDias[$i][18], $EqDias[$i][19] );
		// CONVERTIR DIAS POR MES A SOLO 1 (VERSION MATRIZ)				
			 $EquipOp[] = array('EqCorto'=>$EquipDias[$i]['EqCorto'], 'TipoEquipo'=>$EquipDias[$i]['TipoEquipo'], 'TEquipo'=>$EquipDias[$i]['TEquipo'],'PMXCIA'=>$EquipDias[$i]['PMXCIA'], 'ESQUEMA'=>$EquipDias[$i]['ESQUEMA'], 'PERREP'=>$EquipDias[$i]['PERREP'], 'ENE'=>OperaMes($EquipDias[$i]['ENE'],"JAN/$colname_anopot"), 'FEB'=>OperaMes($EquipDias[$i]['FEB'],"FEB/$colname_anopot"), 'MAR'=>OperaMes($EquipDias[$i]['MAR'],"MAR/$colname_anopot"), 'ABR'=>OperaMes($EquipDias[$i]['ABR'],"APR/$colname_anopot"), 'MAY'=>OperaMes($EquipDias[$i]['MAY'],"MAY/$colname_anopot"), 'JUN'=>OperaMes($EquipDias[$i]['JUN'],"JUN/$colname_anopot"), 'JUL'=>OperaMes($EquipDias[$i]['JUL'],"JUL/$colname_anopot"), 'AGO'=>OperaMes($EquipDias[$i]['AGO'],"AUG/$colname_anopot"), 'SEP'=>OperaMes($EquipDias[$i]['SEP'],"SEP/$colname_anopot"), 'OCT'=>OperaMes($EquipDias[$i]['OCT'],"OCT/$colname_anopot"), 'NOV'=>OperaMes($EquipDias[$i]['NOV'],"NOV/$colname_anopot"), 'DIC'=>OperaMes($EquipDias[$i]['DIC'],"DEC/$colname_anopot"));
		 }
		
		 //Ejecutar Suma Totales por tipo de equipo
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
		
		//SUMAR UNOS (1) DE LA MATRIZ REPORTE ADMON, REMI Y REMIMIXTO
		for ($i=0; $i<count($EquipOp); $i++) {
			//ADMINISTRACION
			if ($EquipOp[$i]['ESQUEMA']=="ADMON" ){
				$admon[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMI
			if ($EquipOp[$i]['ESQUEMA']=="REMI" OR $EquipOp[$i]['ESQUEMA']=="VIRTUAL" ){
				$remi[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMI-MIXTO
			if ($EquipOp[$i]['ESQUEMA']=="REMI-MIXTO" ){
				$remimixto[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			// ADMON TERRESTRES
			if ($EquipOp[$i]['ESQUEMA']=="ADMON" AND $EquipOp[$i]['TipoEquipo'] == "FIJO"){
				$admonter[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			// ADMON MARINOS
			if ($EquipOp[$i]['ESQUEMA']=="ADMON" AND ($EquipOp[$i]['TipoEquipo'] = "A/E" || "EMP")){
				$admonmar[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			// REMI TERRESTRES
			if ($EquipOp[$i]['ESQUEMA']=="REMI" AND $EquipOp[$i]['TipoEquipo'] == "FIJO"){
				$remiter[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMI MARINOS
			if (($EquipOp[$i]['ESQUEMA']=="REMI" OR $EquipOp[$i]['ESQUEMA']=="VIRTUAL") AND ($EquipOp[$i]['TipoEquipo'] = "A/E"||"MOD"||"S/S")) {
				$remimar[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMIMIXTO MARINO
			if ($EquipOp[$i]['ESQUEMA']=="REMI-MIXTO" AND ($EquipOp[$i]['TipoEquipo'] = "A/E"||"MOD"||"S/S")) {
				$remimixtomar[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//ADMON PER
			if ($EquipOp[$i]['ESQUEMA']=="ADMON" AND $EquipOp[$i]['TEquipo'] == 4  AND $EquipOp[$i]['PERREP'] == "P") {
				$admonper[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//ADMON REP
			if ($EquipOp[$i]['ESQUEMA']=="ADMON" AND $EquipOp[$i]['TEquipo'] == 4 AND $EquipOp[$i]['PERREP'] == "R") {
				$admonrep[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//ADMON A/E
			if ($EquipOp[$i]['ESQUEMA']=="ADMON" AND $EquipOp[$i]['TEquipo'] == 1) {
				$admonae[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//ADMON EMP
			if ($EquipOp[$i]['ESQUEMA']=="ADMON" AND $EquipOp[$i]['TEquipo'] == 9) {
				$admonemp[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMI A/E
			if (($EquipOp[$i]['ESQUEMA']=="REMI" OR $EquipOp[$i]['ESQUEMA']=="VIRTUAL") AND $EquipOp[$i]['TEquipo'] == 1) {
				$remiae[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMI MOD
			if (($EquipOp[$i]['ESQUEMA']=="REMI" OR $EquipOp[$i]['ESQUEMA']=="VIRTUAL") AND $EquipOp[$i]['TEquipo'] == 2) {
				$remimod[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMI FIJO
			if (($EquipOp[$i]['ESQUEMA']=="REMI" OR $EquipOp[$i]['ESQUEMA']=="VIRTUAL") AND $EquipOp[$i]['TEquipo'] == 4) {
				$remifijo[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMI-MIXTO A/E
			if ($EquipOp[$i]['ESQUEMA']=="REMI-MIXTO" AND $EquipOp[$i]['TEquipo'] == 1) {
				$remixae[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMI-MIXTO MOD
			if ($EquipOp[$i]['ESQUEMA']=="REMI-MIXTO" AND $EquipOp[$i]['TEquipo'] == 2) {
				$remixmod[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
			//REMI-MIXTO S/S
			if ($EquipOp[$i]['ESQUEMA']=="REMI-MIXTO" AND $EquipOp[$i]['TEquipo'] == 3) {
				$remixss[] = array('ENE'=>$EquipOp[$i]['ENE'],'FEB'=>$EquipOp[$i]['FEB'], 'MAR'=>$EquipOp[$i]['MAR'],'ABR'=>$EquipOp[$i]['ABR'], 'MAY'=>$EquipOp[$i]['MAY'], 'JUN'=>$EquipOp[$i]['JUN'], 'JUL'=>$EquipOp[$i]['JUL'], 'AGO'=>$EquipOp[$i]['AGO'],'SEP'=>$EquipOp[$i]['SEP'], 'OCT'=>$EquipOp[$i]['OCT'], 'NOV'=>$EquipOp[$i]['NOV'], 'DIC'=>$EquipOp[$i]['DIC'] );
			}
		}
		// Notificar solamente errores de ejecución
		error_reporting(E_ERROR | E_PARSE);
		$admons = array("ENE"=>array_sum(array_column($admon,'ENE')), "FEB"=>array_sum(array_column($admon,'FEB')), "MAR"=> array_sum(array_column($admon,'MAR')), "ABR"=>array_sum(array_column($admon,'ABR')), "MAY"=>array_sum(array_column($admon,'MAY')), "JUN"=>array_sum(array_column($admon,'JUN')), "JUL"=>array_sum(array_column($admon,'JUL')), "AGO"=>array_sum(array_column($admon,'AGO')), "SEP"=>array_sum(array_column($admon,'SEP')), "OCT"=>array_sum(array_column($admon,'OCT')), "NOV"=>array_sum(array_column($admon,'NOV')), "DIC"=>array_sum(array_column($admon,'DIC')));
		
		$remis = array("ENE"=>array_sum(array_column($remi,'ENE')), "FEB"=>array_sum(array_column($remi,'FEB')), "MAR"=> array_sum(array_column($remi,'MAR')), "ABR"=>array_sum(array_column($remi,'ABR')), "MAY"=>array_sum(array_column($remi,'MAY')), "JUN"=>array_sum(array_column($remi,'JUN')), "JUL"=>array_sum(array_column($remi,'JUL')), "AGO"=>array_sum(array_column($remi,'AGO')), "SEP"=>array_sum(array_column($remi,'SEP')), "OCT"=>array_sum(array_column($remi,'OCT')), "NOV"=>array_sum(array_column($remi,'NOV')), "DIC"=>array_sum(array_column($remi,'DIC')));
		
		$remimixtos = array("ENE"=>array_sum(array_column($remimixto,'ENE')), "FEB"=>array_sum(array_column($remimixto,'FEB')), "MAR"=>array_sum(array_column($remimixto,'MAR')), "ABR"=>array_sum(array_column($remimixto,'ABR')), "MAY"=>array_sum(array_column($remimixto,'MAY')), "JUN"=>array_sum(array_column($remimixto,'JUN')), "JUL"=>array_sum(array_column($remimixto,'JUL')), "AGO"=>array_sum(array_column($remimixto,'AGO')), "SEP"=>array_sum(array_column($remimixto,'SEP')), "OCT"=>array_sum(array_column($remimixto,'OCT')), "NOV"=>array_sum(array_column($remimixto,'NOV')), "DIC"=>array_sum(array_column($remimixto,'DIC')));

		//ADMON TERR PERF
		$admonpers = array("ENE"=>array_sum(array_column($admonper,'ENE')), "FEB"=>array_sum(array_column($admonper,'FEB')), "MAR"=>array_sum(array_column($admonper,'MAR')), "ABR"=>array_sum(array_column($admonper,'ABR')), "MAY"=>array_sum(array_column($admonper,'MAY')), "JUN"=>array_sum(array_column($admonper,'JUN')), "JUL"=>array_sum(array_column($admonper,'JUL')), "AGO"=>array_sum(array_column($admonper,'AGO')), "SEP"=>array_sum(array_column($admonper,'SEP')), "OCT"=>array_sum(array_column($admonper,'OCT')), "NOV"=>array_sum(array_column($admonper,'NOV')), "DIC"=>array_sum(array_column($admonper,'DIC')));
		
		//ADMON TERR REP
		$admonreps = array("ENE"=>array_sum(array_column($admonrep,'ENE')), "FEB"=>array_sum(array_column($admonrep,'FEB')), "MAR"=>array_sum(array_column($admonrep,'MAR')), "ABR"=>array_sum(array_column($admonrep,'ABR')), "MAY"=>array_sum(array_column($admonrep,'MAY')), "JUN"=>array_sum(array_column($admonrep,'JUN')), "JUL"=>array_sum(array_column($admonrep,'JUL')), "AGO"=>array_sum(array_column($admonrep,'AGO')), "SEP"=>array_sum(array_column($admonrep,'SEP')), "OCT"=>array_sum(array_column($admonrep,'OCT')), "NOV"=>array_sum(array_column($admonrep,'NOV')), "DIC"=>array_sum(array_column($admonrep,'DIC')));
		
		//ADMON A/E
		$admonaes = array("ENE"=>array_sum(array_column($admonae,'ENE')), "FEB"=>array_sum(array_column($admonae,'FEB')), "MAR"=>array_sum(array_column($admonae,'MAR')), "ABR"=>array_sum(array_column($admonae,'ABR')), "MAY"=>array_sum(array_column($admonae,'MAY')), "JUN"=>array_sum(array_column($admonae,'JUN')), "JUL"=>array_sum(array_column($admonae,'JUL')), "AGO"=>array_sum(array_column($admonae,'AGO')), "SEP"=>array_sum(array_column($admonae,'SEP')), "OCT"=>array_sum(array_column($admonae,'OCT')), "NOV"=>array_sum(array_column($admonae,'NOV')), "DIC"=>array_sum(array_column($admonae,'DIC')));
		
		//ADMON EMP
		$admonemps = array("ENE"=>array_sum(array_column($admonemp,'ENE')), "FEB"=>array_sum(array_column($admonemp,'FEB')), "MAR"=>array_sum(array_column($admonemp,'MAR')), "ABR"=>array_sum(array_column($admonemp,'ABR')), "MAY"=>array_sum(array_column($admonemp,'MAY')), "JUN"=>array_sum(array_column($admonemp,'JUN')), "JUL"=>array_sum(array_column($admonemp,'JUL')), "AGO"=>array_sum(array_column($admonemp,'AGO')), "SEP"=>array_sum(array_column($admonemp,'SEP')), "OCT"=>array_sum(array_column($admonemp,'OCT')), "NOV"=>array_sum(array_column($admonemp,'NOV')), "DIC"=>array_sum(array_column($admonemp,'DIC')));
		 
		//REMI A/E
		$remiaes = array("ENE"=>array_sum(array_column($remiae,'ENE')), "FEB"=>array_sum(array_column($remiae,'FEB')), "MAR"=>array_sum(array_column($remiae,'MAR')), "ABR"=>array_sum(array_column($remiae,'ABR')), "MAY"=>array_sum(array_column($remiae,'MAY')), "JUN"=>array_sum(array_column($remiae,'JUN')), "JUL"=>array_sum(array_column($remiae,'JUL')), "AGO"=>array_sum(array_column($remiae,'AGO')), "SEP"=>array_sum(array_column($remiae,'SEP')), "OCT"=>array_sum(array_column($remiae,'OCT')), "NOV"=>array_sum(array_column($remiae,'NOV')), "DIC"=>array_sum(array_column($remiae,'DIC')));
		//REMI MOD
		$remimods = array("ENE"=>array_sum(array_column($remimod,'ENE')), "FEB"=>array_sum(array_column($remimod,'FEB')), "MAR"=>array_sum(array_column($remimod,'MAR')), "ABR"=>array_sum(array_column($remimod,'ABR')), "MAY"=>array_sum(array_column($remimod,'MAY')), "JUN"=>array_sum(array_column($remimod,'JUN')), "JUL"=>array_sum(array_column($remimod,'JUL')), "AGO"=>array_sum(array_column($remimod,'AGO')), "SEP"=>array_sum(array_column($remimod,'SEP')), "OCT"=>array_sum(array_column($remimod,'OCT')), "NOV"=>array_sum(array_column($remimod,'NOV')), "DIC"=>array_sum(array_column($remimod,'DIC')));
		//REMI FIJO
		$remifijos = array("ENE"=>array_sum(array_column($remifijo,'ENE')), "FEB"=>array_sum(array_column($remifijo,'FEB')), "MAR"=>array_sum(array_column($remifijo,'MAR')), "ABR"=>array_sum(array_column($remifijo,'ABR')), "MAY"=>array_sum(array_column($remifijo,'MAY')), "JUN"=>array_sum(array_column($remifijo,'JUN')), "JUL"=>array_sum(array_column($remifijo,'JUL')), "AGO"=>array_sum(array_column($remifijo,'AGO')), "SEP"=>array_sum(array_column($remifijo,'SEP')), "OCT"=>array_sum(array_column($remifijo,'OCT')), "NOV"=>array_sum(array_column($remifijo,'NOV')), "DIC"=>array_sum(array_column($remifijo,'DIC')));
		//REMI-MIXTO A/E
		$remixaes = array("ENE"=>array_sum(array_column($remixae,'ENE')), "FEB"=>array_sum(array_column($remixae,'FEB')), "MAR"=>array_sum(array_column($remixae,'MAR')), "ABR"=>array_sum(array_column($remixae,'ABR')), "MAY"=>array_sum(array_column($remixae,'MAY')), "JUN"=>array_sum(array_column($remixae,'JUN')), "JUL"=>array_sum(array_column($remixae,'JUL')), "AGO"=>array_sum(array_column($remixae,'AGO')), "SEP"=>array_sum(array_column($remixae,'SEP')), "OCT"=>array_sum(array_column($remixae,'OCT')), "NOV"=>array_sum(array_column($remixae,'NOV')), "DIC"=>array_sum(array_column($remixae,'DIC')));
		//REMI-MIXTO MOD
		$remixmods = array("ENE"=>array_sum(array_column($remixmod,'ENE')), "FEB"=>array_sum(array_column($remixmod,'FEB')), "MAR"=>array_sum(array_column($remixmod,'MAR')), "ABR"=>array_sum(array_column($remixmod,'ABR')), "MAY"=>array_sum(array_column($remixmod,'MAY')), "JUN"=>array_sum(array_column($remixmod,'JUN')), "JUL"=>array_sum(array_column($remixmod,'JUL')), "AGO"=>array_sum(array_column($remixmod,'AGO')), "SEP"=>array_sum(array_column($remixmod,'SEP')), "OCT"=>array_sum(array_column($remixmod,'OCT')), "NOV"=>array_sum(array_column($remixmod,'NOV')), "DIC"=>array_sum(array_column($remixmod,'DIC')));
		//REMI-MIXTO S/S
		$remixsss = array("ENE"=>array_sum(array_column($remixss,'ENE')), "FEB"=>array_sum(array_column($remixss,'FEB')), "MAR"=>array_sum(array_column($remixss,'MAR')), "ABR"=>array_sum(array_column($remixss,'ABR')), "MAY"=>array_sum(array_column($remixss,'MAY')), "JUN"=>array_sum(array_column($remixss,'JUN')), "JUL"=>array_sum(array_column($remixss,'JUL')), "AGO"=>array_sum(array_column($remixss,'AGO')), "SEP"=>array_sum(array_column($remixss,'SEP')), "OCT"=>array_sum(array_column($remixss,'OCT')), "NOV"=>array_sum(array_column($remixss,'NOV')), "DIC"=>array_sum(array_column($remixss,'DIC')));
		// Notificar todos los errores de PHP
		error_reporting(-1);
		?>
         <div id="container" style="width: 100%;">
        	<canvas id="canvas"></canvas>
    	</div>
          <script>
        	
		var MONTHS = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        var color = Chart.helpers.color;
        var barChartData = {
            labels: ["Enero", "Febrero", "Mazo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            datasets: [ {
                type: 'bar',
				label: 'Terrestres PMX',
                backgroundColor: color(window.chartColors.green1).alpha(0.5).rgbString(),
                borderColor: window.chartColors.green1,
                borderWidth: 1,
                data: [
                    <?php
						$dataseries = "";
						for ($i = 0; $i<12; $i++) {
							$dataseries .= $TERPMX[$i].",";
						}
						echo substr($dataseries,0,strlen($dataseries)-1);	?>
                ]
            },{
                type: 'bar',                
				label: 'Terrestres CIA',
                backgroundColor: color(window.chartColors.brown).alpha(0.5).rgbString(),
                borderColor: window.chartColors.brown,
                borderWidth: 1,
                data: [
                    <?php
						$dataseries = "";
						for ($i = 0; $i<12; $i++) {
							$dataseries .= $TERCIA[$i].",";
						}
						echo substr($dataseries,0,strlen($dataseries)-1);	?>
                ]
            },{
                type: 'bar',                
				label: 'Autoelevables PMX',
                backgroundColor: color(window.chartColors.green2).alpha(0.5).rgbString(),
                borderColor: window.chartColors.green2,
                borderWidth: 1,
                data: [ <?php
						$dataseries = "";
						for ($i = 0; $i<12; $i++) {
							$dataseries .= $AEPMX[$i].",";
						}
						echo substr($dataseries,0,strlen($dataseries)-1);	?>
                ]
            },{
                type: 'bar',
                label: 'Autoelevable CIA',
                backgroundColor: color(window.chartColors.blue1).alpha(0.5).rgbString(),
                borderColor: window.chartColors.blue1,
                borderWidth: 1,
                data: [
                    <?php
						$dataseries = "";
						for ($i = 0; $i<12; $i++) {
							$dataseries .= $AECIA[$i].",";
						}
						echo substr($dataseries,0,strlen($dataseries)-1);	?>
                ]
            }, {
                type: 'bar',
                label: 'Empaquetados PMX',
                backgroundColor: color(window.chartColors.green_3).alpha(0.5).rgbString(),
                borderColor: window.chartColors.green_3,
                borderWidth: 1,
                data: [
                    <?php
						$dataseries = "";
						for ($i = 0; $i<12; $i++) {
							$dataseries .= $EMP[$i].",";
						}
						echo substr($dataseries,0,strlen($dataseries)-1);	?>
                ]
            },{
                type: 'bar',
                label: 'Modulares CIA',
                backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 1,
                data: [
                    <?php
						$dataseries = "";
						for ($i = 0; $i<12; $i++) {
							$dataseries .= $MOD[$i].",";
						}
						echo substr($dataseries,0,strlen($dataseries)-1);	?>
                ]
            },
			{
                type: 'bar',
                label: 'Semisumergibles',
                backgroundColor: color(window.chartColors.aqua).alpha(0.5).rgbString(),
                borderColor: window.chartColors.aqua,
                borderWidth: 1,
                data: [
                    <?php
						$dataseries = "";
						for ($i = 0; $i<12; $i++) {
							$dataseries .= $SS[$i].",";
						}
						echo substr($dataseries,0,strlen($dataseries)-1);	?>
                ]
            },
			{
                type: 'line',
                label: 'Total',
                borderColor: window.chartColors.red,
                borderWidth: 1,
				fill: false,
                data: [ <?php echo $MEne.",".$MFeb.",".$MMar.",".$MAbr.",".$MMay.",".$MJun.",".$MJul.",".$MAgo.",".$MSep.",".$MOct.",".$MNov.",".$MDic; ?> ]
            }
			
			]

        };

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
					scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    },
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distribución por Tipo de Equipo'
                    }
                }
            });

        };
		 var colorNames = Object.keys(window.chartColors);
        
		</script>
          <table width="100%" border="0" class="display" id="equiposutil">
            <thead>
            <tr>
              <th width="300" scope="col"><span class="ta__center"><strong>Equipo</strong></span></th>
              <th width="300" scope="col"><span class="ta__center"><strong>Tipo</strong></span></th>
              <th width="300" scope="col"><span class="ta__center"><strong>Esquema</strong></span></th>
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
            </tr>
            </thead>
            <tbody>
            <?php for ($i=0; $i<count($EqOp); $i++) { 
			$totalDias = $EqDias[$i][2] + $EqDias[$i][3] + $EqDias[$i][4] + $EqDias[$i][5] +$EqDias[$i][6] + $EqDias[$i][7] +$EqDias[$i][8] +$EqDias[$i][9] + $EqDias[$i][10] + $EqDias[$i][11] + $EqDias[$i][12] + $EqDias[$i][13];
			if ( $totalDias > 0 ) {	?>
            <tr>
              <th width="300" scope="row"><a href="equipo_gantt.php?idEquipo=<?php echo $EqOp[$i][15]; ?>&programa='<?php echo $colname_programa; ?>'&ano=<?php echo $colname_anopot; ?>&lightbox[width]=900&lightbox[height]=490&lightbox[iframe]=true" class="lightbox"><?php echo $EqOp[$i][0]; ?></a></th>
              <th><?php echo $EqOp[$i][17]." ".$EqOp[$i][16]; ?></th>
              <th><?php echo $EqOp[$i][18]; ?></th>
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
              <td width="50" class="sum"><strong><?php echo $MEne; ?></strong></td>
              <td width="50" class="sum"><strong><?php echo $MFeb; ?></strong></td>
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
            </tr>
          	</tfoot>
        </table>
          <p>Nota: No se contabilizan equipos aligerados, snubing y lacustres.</p>
         
         <h3><p>Por tipo de Equipo</p></h3>
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
              <td width="50"><?php echo number_format($AEPMX[12]/12,2); ?></td>
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
              <td width="50"><?php echo number_format($AECIA[12]/12,2); ?></td>
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
              <td width="50"><?php echo number_format($MOD[12]/12,2); ?></td>
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
              <td width="50"><?php echo number_format($EMP[12]/12,2); ?></td>
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
              <td width="50"><?php echo number_format($TERPMX[12]/12,2); ?></td>
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
              <td width="50"><?php echo number_format($TERCIA[12]/12,2); ?></td>
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
              <td width="50"><?php echo number_format($SS[12]/12,2); ?></td>
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
              <td width="50"><strong><?php echo number_format($MTotal/12,2); ?></strong></td>
            </tr>
          </table>
          
          <h3><p>Por Esquema de Contratación</p></h3>
         	<table width="100%" border="0" class="responstable">
            <tr>
              <th width="300" scope="col"><span class="ta__center"><strong>Esquema</strong></span></th>
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
              <th width="300" scope="row"> ADMINISTRACION </th>
              <td width="50"><?php echo $admons['ENE']; ?></td>
              <td width="50"><?php echo $admons['FEB']; ?></td>
              <td width="50"><?php echo $admons['MAR']; ?></td>
              <td width="50"><?php echo $admons['ABR']; ?></td>
              <td width="50"><?php echo $admons['MAY']; ?></td>
              <td width="50"><?php echo $admons['JUN']; ?></td>
              <td width="50"><?php echo $admons['JUL']; ?></td>
              <td width="50"><?php echo $admons['AGO']; ?></td>
              <td width="50"><?php echo $admons['SEP']; ?></td>
              <td width="50"><?php echo $admons['OCT']; ?></td>
              <td width="50"><?php echo $admons['NOV']; ?></td>
              <td width="50"><?php echo $admons['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($admons)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> REMI </th>
              <td width="50"><?php echo $remis['ENE']; ?></td>
              <td width="50"><?php echo $remis['FEB']; ?></td>
              <td width="50"><?php echo $remis['MAR']; ?></td>
              <td width="50"><?php echo $remis['ABR']; ?></td>
              <td width="50"><?php echo $remis['MAY']; ?></td>
              <td width="50"><?php echo $remis['JUN']; ?></td>
              <td width="50"><?php echo $remis['JUL']; ?></td>
              <td width="50"><?php echo $remis['AGO']; ?></td>
              <td width="50"><?php echo $remis['SEP']; ?></td>
              <td width="50"><?php echo $remis['OCT']; ?></td>
              <td width="50"><?php echo $remis['NOV']; ?></td>
              <td width="50"><?php echo $remis['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($remis)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> REMI-MIXTO </th>
              <td width="50"><?php echo $remimixtos['ENE']; ?></td>
              <td width="50"><?php echo $remimixtos['FEB']; ?></td>
              <td width="50"><?php echo $remimixtos['MAR']; ?></td>
              <td width="50"><?php echo $remimixtos['ABR']; ?></td>
              <td width="50"><?php echo $remimixtos['MAY']; ?></td>
              <td width="50"><?php echo $remimixtos['JUN']; ?></td>
              <td width="50"><?php echo $remimixtos['JUL']; ?></td>
              <td width="50"><?php echo $remimixtos['AGO']; ?></td>
              <td width="50"><?php echo $remimixtos['SEP']; ?></td>
              <td width="50"><?php echo $remimixtos['OCT']; ?></td>
              <td width="50"><?php echo $remimixtos['NOV']; ?></td>
              <td width="50"><?php echo $remimixtos['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($remimixtos)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> TOTAL </th>
              <td width="50"><strong><?php echo $admons['ENE']+$remis['ENE']+$remimixtos['ENE']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['FEB']+$remis['FEB']+$remimixtos['FEB']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['MAR']+$remis['MAR']+$remimixtos['MAR']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['ABR']+$remis['ABR']+$remimixtos['ABR']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['MAY']+$remis['MAY']+$remimixtos['MAY']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['JUN']+$remis['JUN']+$remimixtos['JUN']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['JUL']+$remis['JUL']+$remimixtos['JUL']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['AGO']+$remis['AGO']+$remimixtos['AGO']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['SEP']+$remis['SEP']+$remimixtos['SEP']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['OCT']+$remis['OCT']+$remimixtos['OCT']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['NOV']+$remis['NOV']+$remimixtos['NOV']; ?></strong></td>
              <td width="50"><strong><?php echo $admons['DIC']+$remis['DIC']+$remimixtos['DIC']; ?></strong></td>
              <?php $Total = array_sum($admons)+array_sum($remis)+array_sum($remimixtos); ?>
              <td width="50"><strong><?php echo number_format($Total/12,2); ?></strong></td>
            </tr>
          </table>
          
          <h3><p>Equipos de PPS</p></h3>
         	<table width="100%" border="0" class="responstable">
            <tr>
              <th width="300" scope="col"><span class="ta__center"><strong>Equipos de PPS</strong></span></th>
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
              <th width="300" scope="row"> TERRESTRE PERF </th>
              <td width="50"><?php echo $admonpers['ENE']; ?></td>
              <td width="50"><?php echo $admonpers['FEB']; ?></td>
              <td width="50"><?php echo $admonpers['MAR']; ?></td>
              <td width="50"><?php echo $admonpers['ABR']; ?></td>
              <td width="50"><?php echo $admonpers['MAY']; ?></td>
              <td width="50"><?php echo $admonpers['JUN']; ?></td>
              <td width="50"><?php echo $admonpers['JUL']; ?></td>
              <td width="50"><?php echo $admonpers['AGO']; ?></td>
              <td width="50"><?php echo $admonpers['SEP']; ?></td>
              <td width="50"><?php echo $admonpers['OCT']; ?></td>
              <td width="50"><?php echo $admonpers['NOV']; ?></td>
              <td width="50"><?php echo $admonpers['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($admonpers)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> TERRESTRE REP </th>
              <td width="50"><?php echo $admonreps['ENE']; ?></td>
              <td width="50"><?php echo $admonreps['FEB']; ?></td>
              <td width="50"><?php echo $admonreps['MAR']; ?></td>
              <td width="50"><?php echo $admonreps['ABR']; ?></td>
              <td width="50"><?php echo $admonreps['MAY']; ?></td>
              <td width="50"><?php echo $admonreps['JUN']; ?></td>
              <td width="50"><?php echo $admonreps['JUL']; ?></td>
              <td width="50"><?php echo $admonreps['AGO']; ?></td>
              <td width="50"><?php echo $admonreps['SEP']; ?></td>
              <td width="50"><?php echo $admonreps['OCT']; ?></td>
              <td width="50"><?php echo $admonreps['NOV']; ?></td>
              <td width="50"><?php echo $admonreps['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($admonreps)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> A/E </th>
              <td width="50"><?php echo $admonaes['ENE']; ?></td>
              <td width="50"><?php echo $admonaes['FEB']; ?></td>
              <td width="50"><?php echo $admonaes['MAR']; ?></td>
              <td width="50"><?php echo $admonaes['ABR']; ?></td>
              <td width="50"><?php echo $admonaes['MAY']; ?></td>
              <td width="50"><?php echo $admonaes['JUN']; ?></td>
              <td width="50"><?php echo $admonaes['JUL']; ?></td>
              <td width="50"><?php echo $admonaes['AGO']; ?></td>
              <td width="50"><?php echo $admonaes['SEP']; ?></td>
              <td width="50"><?php echo $admonaes['OCT']; ?></td>
              <td width="50"><?php echo $admonaes['NOV']; ?></td>
              <td width="50"><?php echo $admonaes['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($admonaes)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> EMP </th>
              <td width="50"><?php echo $admonemps['ENE']; ?></td>
              <td width="50"><?php echo $admonemps['FEB']; ?></td>
              <td width="50"><?php echo $admonemps['MAR']; ?></td>
              <td width="50"><?php echo $admonemps['ABR']; ?></td>
              <td width="50"><?php echo $admonemps['MAY']; ?></td>
              <td width="50"><?php echo $admonemps['JUN']; ?></td>
              <td width="50"><?php echo $admonemps['JUL']; ?></td>
              <td width="50"><?php echo $admonemps['AGO']; ?></td>
              <td width="50"><?php echo $admonemps['SEP']; ?></td>
              <td width="50"><?php echo $admonemps['OCT']; ?></td>
              <td width="50"><?php echo $admonemps['NOV']; ?></td>
              <td width="50"><?php echo $admonemps['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($admonemps)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> TOTAL </th>
              <td width="50"><strong><?php echo $admonpers['ENE']+$admonreps['ENE']+$admonaes['ENE']+$admonemps['ENE']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['FEB']+$admonreps['FEB']+$admonaes['FEB']+$admonemps['FEB']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['MAR']+$admonreps['MAR']+$admonaes['MAR']+$admonemps['MAR']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['ABR']+$admonreps['ABR']+$admonaes['ABR']+$admonemps['ABR']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['MAY']+$admonreps['MAY']+$admonaes['MAY']+$admonemps['MAY']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['JUN']+$admonreps['JUN']+$admonaes['JUN']+$admonemps['JUN']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['JUL']+$admonreps['JUL']+$admonaes['JUL']+$admonemps['JUL']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['AGO']+$admonreps['AGO']+$admonaes['AGO']+$admonemps['AGO']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['SEP']+$admonreps['SEP']+$admonaes['SEP']+$admonemps['SEP']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['OCT']+$admonreps['OCT']+$admonaes['OCT']+$admonemps['OCT']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['NOV']+$admonreps['NOV']+$admonaes['NOV']+$admonemps['NOV']; ?></strong></td>
              <td width="50"><strong><?php echo $admonpers['DIC']+$admonreps['DIC']+$admonaes['DIC']+$admonemps['DIC']; ?></strong></td>
              <?php $Total = array_sum($admonpers)+array_sum($admonreps)+array_sum($admonaes)+array_sum($admonemps); ?>
              <td width="50"><strong><?php echo number_format($Total/12,2); ?></strong></td>
            </tr>
          </table>
          
          <h3><p>Equipos REMI</p></h3>
         	<table width="100%" border="0" class="responstable">
            <tr>
              <th width="300" scope="col"><span class="ta__center"><strong>Equipos REMI</strong></span></th>
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
              <th width="300" scope="row"> A/E </th>
              <td width="50"><?php echo $remiaes['ENE']; ?></td>
              <td width="50"><?php echo $remiaes['FEB']; ?></td>
              <td width="50"><?php echo $remiaes['MAR']; ?></td>
              <td width="50"><?php echo $remiaes['ABR']; ?></td>
              <td width="50"><?php echo $remiaes['MAY']; ?></td>
              <td width="50"><?php echo $remiaes['JUN']; ?></td>
              <td width="50"><?php echo $remiaes['JUL']; ?></td>
              <td width="50"><?php echo $remiaes['AGO']; ?></td>
              <td width="50"><?php echo $remiaes['SEP']; ?></td>
              <td width="50"><?php echo $remiaes['OCT']; ?></td>
              <td width="50"><?php echo $remiaes['NOV']; ?></td>
              <td width="50"><?php echo $remiaes['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($remiaes)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> MOD </th>
              <td width="50"><?php echo $remimods['ENE']; ?></td>
              <td width="50"><?php echo $remimods['FEB']; ?></td>
              <td width="50"><?php echo $remimods['MAR']; ?></td>
              <td width="50"><?php echo $remimods['ABR']; ?></td>
              <td width="50"><?php echo $remimods['MAY']; ?></td>
              <td width="50"><?php echo $remimods['JUN']; ?></td>
              <td width="50"><?php echo $remimods['JUL']; ?></td>
              <td width="50"><?php echo $remimods['AGO']; ?></td>
              <td width="50"><?php echo $remimods['SEP']; ?></td>
              <td width="50"><?php echo $remimods['OCT']; ?></td>
              <td width="50"><?php echo $remimods['NOV']; ?></td>
              <td width="50"><?php echo $remimods['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($remimods)/12,2); ?></td>
            </tr>
           
            <tr>
              <th width="300" scope="row"> FIJO </th>
              <td width="50"><?php echo $remifijos['ENE']; ?></td>
              <td width="50"><?php echo $remifijos['FEB']; ?></td>
              <td width="50"><?php echo $remifijos['MAR']; ?></td>
              <td width="50"><?php echo $remifijos['ABR']; ?></td>
              <td width="50"><?php echo $remifijos['MAY']; ?></td>
              <td width="50"><?php echo $remifijos['JUN']; ?></td>
              <td width="50"><?php echo $remifijos['JUL']; ?></td>
              <td width="50"><?php echo $remifijos['AGO']; ?></td>
              <td width="50"><?php echo $remifijos['SEP']; ?></td>
              <td width="50"><?php echo $remifijos['OCT']; ?></td>
              <td width="50"><?php echo $remifijos['NOV']; ?></td>
              <td width="50"><?php echo $remifijos['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($remifijos)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> TOTAL </th>
              <td width="50"><strong><?php echo $remifijos['ENE']+$remiaes['ENE']+$remimods['ENE']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['FEB']+$remiaes['FEB']+$remimods['FEB']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['MAR']+$remiaes['MAR']+$remimods['MAR']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['ABR']+$remiaes['ABR']+$remimods['ABR']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['MAY']+$remiaes['MAY']+$remimods['MAY']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['JUN']+$remiaes['JUN']+$remimods['JUN']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['JUL']+$remiaes['JUL']+$remimods['JUL']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['AGO']+$remiaes['AGO']+$remimods['AGO']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['SEP']+$remiaes['SEP']+$remimods['SEP']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['OCT']+$remiaes['OCT']+$remimods['OCT']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['NOV']+$remiaes['NOV']+$remimods['NOV']; ?></strong></td>
              <td width="50"><strong><?php echo $remifijos['DIC']+$remiaes['DIC']+$remimods['DIC']; ?></strong></td>
              <?php $Total = array_sum($remifijos)+array_sum($remiaes)+array_sum($remimods); ?>
              <td width="50"><strong><?php echo number_format($Total/12,2); ?></strong></td>
            </tr>
          </table>
          <h3><p>Equipos REMI-MIXTO</p></h3>
         	<table width="100%" border="0" class="responstable">
            <tr>
              <th width="300" scope="col"><span class="ta__center"><strong>Equipos REMI-MIXTO</strong></span></th>
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
              <th width="300" scope="row"> A/E </th>
              <td width="50"><?php echo $remixaes['ENE']; ?></td>
              <td width="50"><?php echo $remixaes['FEB']; ?></td>
              <td width="50"><?php echo $remixaes['MAR']; ?></td>
              <td width="50"><?php echo $remixaes['ABR']; ?></td>
              <td width="50"><?php echo $remixaes['MAY']; ?></td>
              <td width="50"><?php echo $remixaes['JUN']; ?></td>
              <td width="50"><?php echo $remixaes['JUL']; ?></td>
              <td width="50"><?php echo $remixaes['AGO']; ?></td>
              <td width="50"><?php echo $remixaes['SEP']; ?></td>
              <td width="50"><?php echo $remixaes['OCT']; ?></td>
              <td width="50"><?php echo $remixaes['NOV']; ?></td>
              <td width="50"><?php echo $remixaes['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($remixaes)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> MOD </th>
              <td width="50"><?php echo $remixmods['ENE']; ?></td>
              <td width="50"><?php echo $remixmods['FEB']; ?></td>
              <td width="50"><?php echo $remixmods['MAR']; ?></td>
              <td width="50"><?php echo $remixmods['ABR']; ?></td>
              <td width="50"><?php echo $remixmods['MAY']; ?></td>
              <td width="50"><?php echo $remixmods['JUN']; ?></td>
              <td width="50"><?php echo $remixmods['JUL']; ?></td>
              <td width="50"><?php echo $remixmods['AGO']; ?></td>
              <td width="50"><?php echo $remixmods['SEP']; ?></td>
              <td width="50"><?php echo $remixmods['OCT']; ?></td>
              <td width="50"><?php echo $remixmods['NOV']; ?></td>
              <td width="50"><?php echo $remixmods['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($remixmods)/12,2); ?></td>
            </tr>
           
            <tr>
              <th width="300" scope="row"> S/S </th>
              <td width="50"><?php echo $remixsss['ENE']; ?></td>
              <td width="50"><?php echo $remixsss['FEB']; ?></td>
              <td width="50"><?php echo $remixsss['MAR']; ?></td>
              <td width="50"><?php echo $remixsss['ABR']; ?></td>
              <td width="50"><?php echo $remixsss['MAY']; ?></td>
              <td width="50"><?php echo $remixsss['JUN']; ?></td>
              <td width="50"><?php echo $remixsss['JUL']; ?></td>
              <td width="50"><?php echo $remixsss['AGO']; ?></td>
              <td width="50"><?php echo $remixsss['SEP']; ?></td>
              <td width="50"><?php echo $remixsss['OCT']; ?></td>
              <td width="50"><?php echo $remixsss['NOV']; ?></td>
              <td width="50"><?php echo $remixsss['DIC']; ?></td>
              <td width="50"><?php echo number_format(array_sum($remixsss)/12,2); ?></td>
            </tr>
            <tr>
              <th width="300" scope="row"> TOTAL </th>
              <td width="50"><strong><?php echo $remixsss['ENE']+$remixaes['ENE']+$remixmods['ENE']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['FEB']+$remixaes['FEB']+$remixmods['FEB']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['MAR']+$remixaes['MAR']+$remixmods['MAR']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['ABR']+$remixaes['ABR']+$remixmods['ABR']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['MAY']+$remixaes['MAY']+$remixmods['MAY']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['JUN']+$remixaes['JUN']+$remixmods['JUN']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['JUL']+$remixaes['JUL']+$remixmods['JUL']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['AGO']+$remixaes['AGO']+$remixmods['AGO']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['SEP']+$remixaes['SEP']+$remixmods['SEP']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['OCT']+$remixaes['OCT']+$remixmods['OCT']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['NOV']+$remixaes['NOV']+$remixmods['NOV']; ?></strong></td>
              <td width="50"><strong><?php echo $remixsss['DIC']+$remixaes['DIC']+$remixmods['DIC']; ?></strong></td>
              <?php $Total = array_sum($remixsss)+array_sum($remixaes)+array_sum($remixmods); ?>
              <td width="50"><strong><?php echo number_format($Total/12,2); ?></strong></td>
            </tr>
          </table>
          
          <h3>Tabla de Resumen de Intervenciones</h3>
          
          <table border="0" class="responstable">
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
      <th><?php echo $row_resinter['Subdireccion']; ?></th> 
      <th><?php echo $row_resinter['ACTIVO']; ?></th>
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
      <th>TOTAL</th>
      <th><?php echo $sumPer; ?></th>
      <th><?php echo $sumTer; ?></th>
      <th><?php echo $sumRMA; ?></th>
      <th><?php echo $sumRME; ?></th>
      <th><?php echo $sumTAP; ?></th>
      <?php $TInter = $sumPer + $sumTer + $sumRMA + $sumRME + $sumTAP; ?>
      <th><?php echo $TInter; ?></th>
    </tr>
  </tbody>
</table>
        </ul>
      </div>
          <a href="operatividad_dias.php">Operatividad por Días Operando </a>| <a href="xcelsius/distribucionequipos.html">Versión Flash (Xcelsius)</a> |</div>
    <p>Mostrar código <input type="button" value="Mostrar" onClick="MostrarOcultar('codigo1', 'mostrar')"> </p>
    	<script>
		function MostrarOcultar(elemt, mostrar) {
			if (mostrar == 'mostrar') {
				document.getElementById(elemt).style.display = 'block';
			}
			if (mostrar == 'ocultar') {
				document.getElementById(elemt).style.display = 'none';
			}
		}
        </script>
    	<div id="codigo1" style="display:none">
        	<p> Sentencia SQL : <?php echo $query_Recordset1; ?> </p>
            <p> Sentencia SQL Intervenciones : <?php echo $query_resinter; ?></p>
            <p> Tabla Operatividad: <?php print_r($TablaOperatividad); ?><br>
            
            <p> Matriz Dias: <?php// print_r($EquipDias); ?> <br>
            
            <P> Matriz Equipos Operando Unos: <?php print_r($EquipOp); ?></P>
            <p> pruebas: REMI-> Enero -> <?php echo array_sum(array_column($remi,'ENE')); ?></p>
            
            <p> pruebas: REMI->  <?php echo array_sum($remis); ?></p>
            <input type="button" value="Ocultar" onClick="MostrarOcultar('codigo1', 'ocultar')">
      	</div>
      <div class="grid_12">
    <a href="index.php">Regresar</a> </div>

        <h3></h3>
         <div id="banner" class="container" ></div>
  </div>
    </div>
  </div>

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
mysqli_free_result($resinter);
?>
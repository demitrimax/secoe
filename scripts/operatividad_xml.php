<?php require_once('../../Connections/ResEquipos.php'); ?>
<?php

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_lastpot = "SELECT pot.programoficial FROM pot WHERE pot.id_prog = (select max(pot.id_prog) from pot)";
$lastpot = mysqli_query($ResEquipos, $query_lastpot) or dies(mysqli_error($ResEquipos));
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
$Recordset1 = mysqli_query($ResEquipos, $query_Recordset1) or die(mysql_error($ResEquipos));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_programas = "SELECT DISTINCT pot.programoficial from pot ORDER BY pot.id_prog DESC ";
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
   do { 
	$EqDias[] = array($row_Recordset1['Equipo'], $row_Recordset1['TEquipo'], $row_Recordset1['ENE'], $row_Recordset1['FEB'], $row_Recordset1['MAR'], $row_Recordset1['ABR'], $row_Recordset1['MAY'], $row_Recordset1['JUN'], $row_Recordset1['JUL'], $row_Recordset1['AGO'], $row_Recordset1['SEP'], $row_Recordset1['OCT'], $row_Recordset1['NOV'], $row_Recordset1['DIC'], $row_Recordset1['PMXCIA']);
	   
	//MATRIZ ALMACENAR DIAS
	 $EquipDias[] = array('EqCorto'=>$row_Recordset1['Equ_corto'], 'TipoEquipo'=>$row_Recordset1['tipoequipo'],'TEquipo'=>$row_Recordset1['TEquipo'],'ENE'=>$row_Recordset1['ENE'], 'FEB'=>$row_Recordset1['FEB'], 'MAR'=>$row_Recordset1['MAR'], 'ABR'=>$row_Recordset1['ABR'], 'MAY'=>$row_Recordset1['MAY'], 'JUN'=>$row_Recordset1['JUN'], 'JUL'=>$row_Recordset1['JUL'], 'AGO'=>$row_Recordset1['AGO'], 'SEP'=>$row_Recordset1['SEP'], 'OCT'=>$row_Recordset1['OCT'], 'NOV'=>$row_Recordset1['NOV'], 'DIC'=>$row_Recordset1['DIC'], 'PMXCIA'=>$row_Recordset1['PMXCIA2'], 'IDEQUIPO'=>$row_Recordset1['idEquipo'], 'CARACTERISTICAS'=>$row_Recordset1['Caracteristicas'], 'ESQUEMA'=>$row_Recordset1['ESQUEMA2'], 'PERREP'=>$row_Recordset1['PERREP']);
	} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); 
      
		 $meses = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
		 for ($i=0; $i<count($EqDias); $i++) {
				$EqOp[] = array($EqDias[$i][0], $EqDias[$i][1], OperaMes($EqDias[$i][2],"JAN/$colname_anopot"), OperaMes($EqDias[$i][3],"FEB/$colname_anopot"), OperaMes($EqDias[$i][4],"MAR/$colname_anopot"), OperaMes($EqDias[$i][5],"APR/$colname_anopot"), OperaMes($EqDias[$i][6],"MAY/$colname_anopot"), OperaMes($EqDias[$i][7],"JUN/$colname_anopot"), OperaMes($EqDias[$i][8],"JUL/$colname_anopot"), OperaMes($EqDias[$i][9],"AUG/$colname_anopot"), OperaMes($EqDias[$i][10],"SEP/$colname_anopot"), OperaMes($EqDias[$i][11],"OCT/$colname_anopot"), OperaMes($EqDias[$i][12],"NOV/$colname_anopot"), OperaMes($EqDias[$i][13],"DEC/$colname_anopot"), $EqDias[$i][14]);
			 
			 // CONVERTIR DIAS POR MES A SOLO 1 (VERSION MATRIZ)				
			 $EquipOp[] = array('EqCorto'=>$EquipDias[$i]['EqCorto'], 'TipoEquipo'=>$EquipDias[$i]['TipoEquipo'], 'TEquipo'=>$EquipDias[$i]['TEquipo'],'PMXCIA'=>$EquipDias[$i]['PMXCIA'], 'ESQUEMA'=>$EquipDias[$i]['ESQUEMA'], 'PERREP'=>$EquipDias[$i]['PERREP'], 'ENE'=>OperaMes($EquipDias[$i]['ENE'],"JAN/$colname_anopot"), 'FEB'=>OperaMes($EquipDias[$i]['FEB'],"FEB/$colname_anopot"), 'MAR'=>OperaMes($EquipDias[$i]['MAR'],"MAR/$colname_anopot"), 'ABR'=>OperaMes($EquipDias[$i]['ABR'],"APR/$colname_anopot"), 'MAY'=>OperaMes($EquipDias[$i]['MAY'],"MAY/$colname_anopot"), 'JUN'=>OperaMes($EquipDias[$i]['JUN'],"JUN/$colname_anopot"), 'JUL'=>OperaMes($EquipDias[$i]['JUL'],"JUL/$colname_anopot"), 'AGO'=>OperaMes($EquipDias[$i]['AGO'],"AUG/$colname_anopot"), 'SEP'=>OperaMes($EquipDias[$i]['SEP'],"SEP/$colname_anopot"), 'OCT'=>OperaMes($EquipDias[$i]['OCT'],"OCT/$colname_anopot"), 'NOV'=>OperaMes($EquipDias[$i]['NOV'],"NOV/$colname_anopot"), 'DIC'=>OperaMes($EquipDias[$i]['DIC'],"DEC/$colname_anopot"));
		 }
		 //Ejecutar Suma Totales
		 $MEne = 0; $MFeb = 0; $MMar = 0; $MAbr = 0; $MMay = 0; $MJun = 0; $MJul = 0; $MAgo = 0; $MSep = 0; $MOct = 0; $MOct = 0; $MNov = 0; $MDic = 0;
		 $AEPMX = array(0,0,0,0,0,0,0,0,0,0,0,0);
		 $AECIA = array(0,0,0,0,0,0,0,0,0,0,0,0);
		 $MOD = array(0,0,0,0,0,0,0,0,0,0,0,0);
		 $EMP = array(0,0,0,0,0,0,0,0,0,0,0,0);
		 $TERPMX = array(0,0,0,0,0,0,0,0,0,0,0,0);
		 $TERCIA = array(0,0,0,0,0,0,0,0,0,0,0,0);
		 $SS = array(0,0,0,0,0,0,0,0,0,0,0,0);
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
		 
		 for ($i=0;$i<12; $i++) {
			$Resultados[] = array($AEPMX[$i],$AECIA[$i], $MOD[$i], $EMP[$i], $TERPMX[$i], $TERCIA[$i], $SS[$i]);
			}
		 
		 //print_r($Resultados);

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
			if ($EquipOp[$i]['ESQUEMA']=="REMI" AND ($EquipOp[$i]['TipoEquipo'] = "A/E"||"MOD"||"S/S")) {
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
 		
		error_reporting(-1);
mysqli_free_result($Recordset1);

$xml = new DomDocument('1.0', 'UTF-8');
$raiz = $xml->createElement('data');
	$raiz = $xml->appendChild($raiz);
		$variable = $xml->createElement('variable');
		$variable = $raiz->appendChild($variable);
		$variable->setAttribute('name','DatosRec');
			$fila=array('A/E PMX', 'A/E CIA', 'MOD', 'EMP', 'TER PMX', 'TER CIA', 'SEMI');
			for ($r=0; $r<count($fila); $r++) {
			$NameTemp = $fila[$r];
			$fila[$r] = $xml->createElement('row');
			$fila[$r] = $variable->appendChild($fila[$r]);
				$Columna = $xml->createElement('column', $NameTemp);
				$Columna = $fila[$r]->appendChild($Columna);
				$Columna=array(0,0,0,0,0,0,0,0,0,0,0,0);
				for ($i=0; $i<12; $i++) {
					$Columna[$i] = $xml->createElement('column', $Resultados[$i][$r]);
					$Columna[$i] = $fila[$r]->appendChild($Columna[$i]);
				}
			}
		$variable = $xml->createElement('variable');
		$variable = $raiz->appendChild($variable);
		$variable->setAttribute('name','Parametros');
			$datos = $xml->createElement('Datos', 'ano: '.$colname_anopot);
			$datos= $variable->appendChild($datos);
			$datos = $xml->createElement('Datos', 'programa: '.$colname_programa);
			$datos= $variable->appendChild($datos);
			$datos = $xml->createElement('Datos', 'inter: '.$colname_inter);
			$datos= $variable->appendChild($datos);
			//$datos = $xml->createElement('Datos', 'SQL:'.$query_Recordset1);
			//$datos= $variable->appendChild($datos);
		$variable = $xml->createElement('variable');
		$variable = $raiz->appendChild($variable);
		$variable->setAttribute('name','DatosDias');
	
	// lista de programas
		$variable = $xml->createElement('variable');
		$variable = $raiz->appendChild($variable);
		$variable->setAttribute('name','programas');
			$i=0;
			do {
				$i++;
			$fila[$i] = $xml->createElement('row');
			$fila[$i] = $variable->appendChild($fila[$i]);
				$columna[$i] = $xml->createElement('column', $row_programas['programoficial']);
				$columna[$i] = $fila[$i]->appendChild($columna[$i]);
			
			} while ($row_programas = mysqli_fetch_assoc($programas));
	
	//lista de años
		$variable = $xml->createElement('variable');
		$variable = $raiz->appendChild($variable);
		$variable->setAttribute('name','anos');
			$i=0;
			do {
				$i++;
			$fila[$i] = $xml->createElement('row');
			$fila[$i] = $variable->appendChild($fila[$i]);
				$columna[$i] = $xml->createElement('column', $row_anos['anofin']);
				$columna[$i] = $fila[$i]->appendChild($columna[$i]);
			
			} while ($row_anos = mysqli_fetch_assoc($anos));
	
	//lista de intervenciones
		$variable = $xml->createElement('variable');
		$variable = $raiz->appendChild($variable);
		$variable->setAttribute('name','intervencion');
			$i=0;
			do {
				$i++;
			$fila[$i] = $xml->createElement('row', $row_intervencion['intervencion']);
			$fila[$i] = $variable->appendChild($fila[$i]);
			} while ($row_intervencion = mysqli_fetch_assoc($intervencion));  
//matriz de equipos
	$variable = $xml->createElement('variable');
	$variable = $raiz->appendChild($variable);
	$variable->setAttribute('name','resumen1');
		$mesest=array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
		$filat=array('admonaes', 'admonemps', 'admonpers', 'admonreps', 'remiaes', 'remimods', 'remifijos','remixaes','remixmods','remixsss');
		
		for ($r=0; $r<count($filat); $r++){
			$NameTemp = $filat[$r];
			$filat[$r] = $xml->createElement('row');
			$filat[$r] = $variable->appendChild($filat[$r]);
				
				$Columna2 = $xml->createElement('column', $NameTemp);
				$Columna2 = $filat[$r]->appendChild($Columna2);
				$Columna2=array(0,0,0,0,0,0,0,0,0,0,0,0);
				for ($i=0; $i<12; $i++) {
					
					$Columna2[$i] = $xml->createElement('column',${$NameTemp}[$mesest[$i]] );
					$Columna2[$i] = $filat[$r]->appendChild($Columna2[$i]);
				}

		} 
		
	
$xml->formatOutput = true;
$xml->saveXML();
$xml->save('resumen_equipos.xml');

$el_xml = $xml->saveXML();
echo $el_xml;
?>
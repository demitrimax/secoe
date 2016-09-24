<?php
date_default_timezone_set('America/Monterrey');
?>
<?php require_once('../Connections/ResEquipos.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "consulta,auditor,admin";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$colname_Recordset1 = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_Recordset1 = $_GET['idEquipo'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_Recordset1 = sprintf("SELECT * FROM detalleequipo WHERE idEquipo = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $ResEquipos) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$maxRows_comentarios = 1;
$pageNum_comentarios = 0;
if (isset($_GET['pageNum_comentarios'])) {
  $pageNum_comentarios = $_GET['pageNum_comentarios'];
}
$startRow_comentarios = $pageNum_comentarios * $maxRows_comentarios;

$colname_comentarios = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_comentarios = $_GET['idEquipo'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_comentarios = sprintf("SELECT
eqcomentarios.id_com,
eqcomentarios.comentario,
eqcomentarios.fec_coment,
cat_estatusop.estatusop,
cat_activos.ACTIVO,
cat_activos.ACTIVO_CORTO
FROM
eqcomentarios
LEFT JOIN cat_estatusop ON eqcomentarios.estatus_operativo = cat_estatusop.id_statusop
LEFT JOIN cat_activos ON eqcomentarios.activo = cat_activos.id_activo
WHERE eqcomentarios.equipo = %s
ORDER BY
eqcomentarios.fec_coment DESC", GetSQLValueString($colname_comentarios, "int"));
$query_limit_comentarios = sprintf("%s LIMIT %d, %d", $query_comentarios, $startRow_comentarios, $maxRows_comentarios);
$comentarios = mysql_query($query_limit_comentarios, $ResEquipos) or die(mysql_error());
$row_comentarios = mysql_fetch_assoc($comentarios);

if (isset($_GET['totalRows_comentarios'])) {
  $totalRows_comentarios = $_GET['totalRows_comentarios'];
} else {
  $all_comentarios = mysql_query($query_comentarios);
  $totalRows_comentarios = mysql_num_rows($all_comentarios);
}
$totalPages_comentarios = ceil($totalRows_comentarios/$maxRows_comentarios)-1;

$colname_documentos = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_documentos = $_GET['idEquipo'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_documentos = sprintf("SELECT * FROM documentos WHERE equipo_asociado = %s ORDER BY fecha DESC", GetSQLValueString($colname_documentos, "int"));
$documentos = mysql_query($query_documentos, $ResEquipos) or die(mysql_error());
$row_documentos = mysql_fetch_assoc($documentos);
$totalRows_documentos = mysql_num_rows($documentos);

$colname_contratos = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_contratos = $_GET['idEquipo'];
  $colname_contratos = $row_Recordset1['idEquipo'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_contratos = sprintf("SELECT
contrato.ID_CTTO,
contrato.NO_CONTRATO,
contrato.F_INICIO,
contrato.F_FIN,
contrato.PLAZO,
contrato.TARIFA,
contrato.OBJETO_CTO,
cat_cias.NombreCia,
cat_esquemacto.ESQUEMA,
cat_ctostatus.ESTATUS,
cat_ctostatus.SEMAFORO,
cat_cias.InicialCia,
contrato.EQUIPOID,
contrato.T_CTTO,
cat_tctto.TIPOCTTO
FROM
contrato
INNER JOIN cat_cias ON contrato.COMPANIA = cat_cias.id_cia
INNER JOIN cat_esquemacto ON contrato.ESQUEMA = cat_esquemacto.IDESQ
INNER JOIN cat_ctostatus ON contrato.ESTATUS = cat_ctostatus.ID_STATUS
LEFT JOIN cat_tctto ON contrato.T_CTTO = cat_tctto.ID WHERE EQUIPOID = %s", GetSQLValueString($colname_contratos, "int"));
$contratos = mysql_query($query_contratos, $ResEquipos) or die(mysql_error());
$row_contratos = mysql_fetch_assoc($contratos);
$totalRows_contratos = mysql_num_rows($contratos);

$colname_cronograma = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_cronograma = $_GET['idEquipo'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_cronograma = sprintf("SELECT a.id, a.fechacro, b.cat_crono FROM cronograma a, cat_cronograma b WHERE a.cronograma = b.id and a.equipo = %s", GetSQLValueString($colname_cronograma, "int"));
$cronograma = mysql_query($query_cronograma, $ResEquipos) or die(mysql_error());
$row_cronograma = mysql_fetch_assoc($cronograma);
$totalRows_cronograma = mysql_num_rows($cronograma);

$colname_eficiencia = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_eficiencia = $_GET['idEquipo'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_eficiencia = sprintf("SELECT * FROM eficiencia_equipos WHERE equipoid = %s ORDER BY fecha DESC LIMIT 1", GetSQLValueString($colname_eficiencia, "int"));
$eficiencia = mysql_query($query_eficiencia, $ResEquipos) or die(mysql_error());
$row_eficiencia = mysql_fetch_assoc($eficiencia);
$totalRows_eficiencia = mysql_num_rows($eficiencia);

$colname_ProgEquipos = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_ProgEquipos = $_GET['idEquipo'];
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

mysql_select_db($database_ResEquipos, $ResEquipos);
$query_estatusmantto = sprintf("SELECT
estatus_mantto.id_estatus,
estatus_mantto.id_equipo,
estatus_mantto.ubicacion,
estatus_mantto.uop,
estatus_mantto.fecha,
cat_estatus.ESTATUS,
cat_estatus.SEMAFORO
FROM
estatus_mantto
INNER JOIN cat_estatus ON estatus_mantto.id_estatus = cat_estatus.ID_ESTATUS
WHERE id_equipo = %s
ORDER BY estatus_mantto.fecha DESC", GetSQLValueString($colname_ProgEquipos, "int"));
$estatusmantto = mysql_query($query_estatusmantto, $ResEquipos) or die(mysql_error());
$row_estatusmantto = mysql_fetch_assoc($estatusmantto);
$totalRows_estatusmantto = mysql_num_rows($estatusmantto);


$NoContrato = $row_contratos['NO_CONTRATO'];
$idcia = $row_Recordset1['Cia'];

//SUPONES QUE EL USUARIO SE LOGEO CORRECTAMENTE
 $usuario = $_SESSION['MM_Username'];
 $fechahora = date("Y-m-d H:i:s");
 $pagina_actual = $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
 $ipadress = $_SERVER['REMOTE_ADDR'];
 mysql_select_db($database_ResEquipos, $ResEquipos);
$query_log = "INSERT INTO registros (pagina, usuario, fechahora, ip) VALUES ('$pagina_actual', '$usuario', '$fechahora', '$ipadress')";
//echo $query_log;
$registros = mysql_query($query_log, $ResEquipos) or die(mysql_error());

?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Detalle del Equipo <?php echo $row_Recordset1['Equipo']; ?></title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responstable.css">
<script src="js/jquery.js"></script>
<script src="js/jquery-migrate-1.1.1.js"></script>
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
<script type="text/javascript" charset="utf8" src="DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="DataTables/extensions/Responsive/css/responsive.dataTables.css"/>
 
<script type="text/javascript" src="DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>
</script>
  <link rel="stylesheet" type="text/css" href="jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css" />
  <script type="text/javascript" src="jquery.lightbox/js/lightbox/jquery.lightbox.min.js"></script>
   <!-- // <script type="text/javascript" src="jquery.lightbox/jquery.lightbox.js"></script>   -->

  <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox();
    });
  </script>
     <script src="vis/dist/vis.js"></script>
     <script src="vis/moment-with-locales.js"></script>
  <link href="vis/dist/vis.css" rel="stylesheet" type="text/css" />


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
	background-color: #060;
	border-color: #000;
	color: white;
    }
	.vis-item.azul {
	background-color: #009;
	border-color: #000;
	color: white;
    }
	.vis-item.azulclaro {
	background-color: #0FF;
	border-color: #000;
	color: #000;
    }
	.vis-item.amarillo {
	background-color: #FF0;
	border-color: #000;
    }
	.vis-item.naranja {
	background-color: #F60;
	border-color: #000;
    }
	.vis-item.rojofuerte {
	background-color: #900;
	border-color: #000;
	color: white;
    }
	.vis-item.melon {
	background-color: #FC0;
	border-color: #000;
    }
	.vis-item.verdeclaro {
	background-color: #0F0;
	border-color: #000;
    }

  </style>


</head>
<body>
<script type="text/javascript">
$(document).ready( function () {
    $('#filest')
		.addClass( 'nowrap' )
		.DataTable({
		 	responsive: true,
			"language": {
                "url": "DataTables/spanish/spanish.json"
						}
	});
} );
</script>


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
               <li class="current"><?php echo $row_Recordset1['Equipo']; ?></li>
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
        <h3><?php echo $row_Recordset1['Equipo']; ?> </h3>
        <img src="<?php echo $row_Recordset1['imagen']; ?>" alt="" width="190" height="220" class="img_inner fleft" />
        <div class="extra_wrapper">
     <table border="0">
  <tr>
    <th class="ta__left" scope="row"><strong>No. Equipo:</strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['CLVE_EQUIPO']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Tipo de Equipo:</strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['tipoequipo']; ?>&nbsp;</td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Carácteristicas:</strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['Caracteristicas']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>HP:</strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['HP']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Marca Malacate: </strong> </th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['marcamalacate']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Capacidad de Perforación:</strong> </th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['CAPPERF']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Tirante de Agua: </strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td> <?php echo $row_Recordset1['TA']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Compañía:</strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['NombreCia']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"> <strong>Sección Sindical: </strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['SECCSINDICAL']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Estatus: </strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo utf8_encode($row_Recordset1['ESTATUS']); ?><img src="images/sem/sem_<?php echo $row_Recordset1['SEMAFORO']; ?>.png" width="16" height="16" title="<?php echo utf8_encode($row_Recordset1['DESCRIPCION']); ?>"> <?php if ($totalRows_estatusmantto > 0) { echo " | ".$row_estatusmantto['ESTATUS']." (".date("d/m/y",strtotime($row_estatusmantto['fecha'])).")"; }?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Clase: </strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['clase']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Eficiencia: </strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_eficiencia['eficiencia']; ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Año de Construcción: </strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['ANO_CONSTR']; ?><br></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Tarifa Diaria: </strong></th>
    <th width="20" class="ta__left" scope="row">&nbsp;</th>
    <td> <?php echo number_format($row_Recordset1['tarifa']); ?></td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Subdireccion</strong></th>
    <th class="ta__left" scope="row">&nbsp;</th>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th class="ta__left" scope="row"><strong>Activ<?php echo $row_Recordset1['SUBDIRECCION']; ?>o</strong></th>
    <th class="ta__left" scope="row">&nbsp;</th>
    <td><?php echo $row_Recordset1['ACTIVO']; ?></td>
  </tr>
     </table>
</div>
        <div class="extra_wrapper">
        	<div class="row">
        		<div class="grid_5">
                No. de Equipo   4551
                </div>
                <div class="grid_4">
                  Tipo de Equipo
                </div>
                <div class="grid_3">
                Caracteristicas: 
                </div> 
        </div>
          
                     
           <p>
             <?php if ($totalRows_comentarios > 0) { // Show if recordset not empty ?>
           </p>
           <div class="blog">
            <?php $FechaComentario = $row_comentarios['fec_coment']; 
			$DiaComent = date("d", strtotime($FechaComentario));
			$AnoComent = date("Y", strtotime($FechaComentario));
			$MesComent = date("M", strtotime($FechaComentario));
			?>
			<time datetime="<?php echo date("d/m/y H:i:s", strtotime($row_comentarios['fec_coment'])); ?> "><span class="count"><?php echo $DiaComent; ?></span><strong><?php echo $MesComent; ?></strong><?php echo $AnoComent; ?></time>
				<div class="extra_wrapper">
                <a href="catalogos/detalle_comentarios.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>&lightbox[width]=600&lightbox[height]=500&lightbox[iframe]=true" class="lightbox"><span class="fa fa-comments"></span><?php echo $totalRows_comentarios; ?></a>
				<p><span class="fwn"><a href="#">Estado: <?php echo utf8_encode($row_comentarios['estatusop']); ?></a></span><em>Publicado por <a href="#">Admin</a></em><?php echo $_SESSION['iduser'];?></p>
                <strong><?php echo $row_comentarios['ACTIVO']; ?></strong><br>
                <?php echo $row_comentarios['comentario']; ?>
				</div>
        </div>
          
        <a href="catalogos/detalle_comentarios.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>&lightbox[width]=600&lightbox[height]=500&lightbox[iframe]=true" class="lightbox">Ver más comentarios <?php echo $totalRows_comentarios; ?> </a> | <a href="add_coment.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>&lightbox[width]=600&lightbox[height]=500&lightbox[iframe]=true" class="lightbox">Agregar Comentarios</a> | <a href="mod_coment.php?id_com=<?php echo $row_comentarios['id_com']; ?>&lightbox[width]=600&lightbox[height]=500&lightbox[iframe]=true" class="lightbox">Modificar</a>
<?php } ?>
          <p>
          <?php if ($totalRows_cronograma > 0) { // Show if recordset not empty ?>
              Cronograma:</p>
              <table border="0">
                <?php do { ?>
                  <tr>
                    <td width="185"><strong><?php echo $row_cronograma['cat_crono']; ?></strong></td>
                    <td width="78"><?php echo date("d/m/y",strtotime($row_cronograma['fechacro'])); ?></td>
                  </tr>
                  <?php } while ($row_cronograma = mysql_fetch_assoc($cronograma)); ?>  
              </table>
              <?php } ?>
               <p>
               <?php if ($totalRows_contratos > 0) { // Show if recordset not empty ?>
        <table border="0" class="responstable" id="contratost" width="100%">
                  
                  <tr>
                    <th>Contrato</th>
                    <th>Tipo</th>
                    <th>Compañía</th>
                    <th>Inicio</th>
                    <th>Termino</th>
                    <th>Esquema</th>
                    <th>Tarifa DLS</th>
                  </tr>
                  
                  
                  <?php do { ?>
                    <tr>
                      <td><a href="cto_detalle.php?idctto=<?php echo $row_contratos['ID_CTTO']; ?>&lightbox[width]=500&lightbox[height]=600" class="lightbox"><?php echo $row_contratos['NO_CONTRATO']; ?></a><img src="images/sem/sem_<?php echo $row_contratos['SEMAFORO']; ?>.png" width="16" height="16" title="<?php echo utf8_encode($row_contratos['ESTATUS']); ?>"></td>
                      <td><?php echo $row_contratos['TIPOCTTO']; ?></td>
                      <td><?php echo $row_contratos['InicialCia']; ?> </td>
                      <td><?php echo date("d/m/Y", strtotime($row_contratos['F_INICIO'])); ?></td>
                      <td><?php echo date("d/m/Y", strtotime($row_contratos['F_FIN'])); ?></td>
                      <td> <?php echo $row_contratos['ESQUEMA']; ?> </td>
                      <td><?php echo number_format($row_contratos['TARIFA']); ?></td>
                    </tr>
                    <?php } while ($row_contratos = mysql_fetch_assoc($contratos)); ?>
                    
        </table>
              </p>
              <?php } ?>
               </p>
         <?php if ($totalRows_ProgEquipos > 0) { // Show if recordset not empty ?>

<p>
  <input type="button" id="focus2016" value="Solo mostrar 2016"><br>
  <input type="button" id="fit" value="Mostrar todo"><br>
 </p>
<div id="visualization">
    <div class="menu">
        <input type="button" id="zoomIn" value="Acercar +"/>
        <input type="button" id="zoomOut" value="Alejar -"/>
        <input type="button" id="moveLeft" value="Mover izq"/>
        <input type="button" id="moveRight" value="Mover der"/>
    </div>
</div>
<script type="text/javascript">
<?php $contador = 0; 
$contador2 = -1;
?>
  // DOM element where the Timeline will be attached
  var container = document.getElementById('visualization');
  
  $.ajax({
    type: "GET",
	data: {"idEquipo" : <?php echo $colname_ProgEquipos; ?>},
	dataType: "json",
	url: 'catalogos/ganttdatos.php',
	
    success: function (data) {
      // hide the "loading..." message
      //document.getElementById('loading').style.display = 'none';
		//txtData.value = JSON.stringify(data['datos'], null, 2);
		//txtGrupos.value = JSON.stringify(data['grupos'], null, 2);
      // DOM element where the Timeline will be attached
      var container = document.getElementById('visualization');
		//los grupos...igual por ajax
	  var grupos = new vis.DataSet(data['grupos']);
      // Create a DataSet (allows two way data-binding)
      var items = new vis.DataSet(data['datos']);


  //Locale
  
  // Configuration for the Timeline
  var options = {
	  locales: {
		  //crear uno nuevo para espanol
		  myespa: {
			  current: 'current',
			  time: 'time',
		  }
	  },
	  locale: 'myespa',
	  stack: false,
	  selectable: true,
	  clickToUse: true
	  };
	 
	     function move (percentage) {
        var range = timeline.getWindow();
        var interval = range.end - range.start;

        timeline.setWindow({
            start: range.start.valueOf() - interval * percentage,
            end:   range.end.valueOf()   - interval * percentage
        });
    }

    /**
     * Zoom the timeline a given percentage in or out
     * @param {Number} percentage   For example 0.1 (zoom out) or -0.1 (zoom in)
     */
    function zoom (percentage) {
        var range = timeline.getWindow();
        var interval = range.end - range.start;

        timeline.setWindow({
            start: range.start.valueOf() - interval * percentage,
            end:   range.end.valueOf()   + interval * percentage
        });
    }

  // Create a Timeline
	var timeline = new vis.Timeline(container);
  timeline.setOptions(options);
  timeline.setGroups(grupos);
  timeline.setItems(items);
  // attach events to the navigation buttons
    document.getElementById('zoomIn').onclick    = function () { zoom(-0.2); };
    document.getElementById('zoomOut').onclick   = function () { zoom( 0.2); };
    document.getElementById('moveLeft').onclick  = function () { move( 0.2); };
    document.getElementById('moveRight').onclick = function () { move(-0.2); };
  
  document.getElementById('focus2016').onclick = function() {
	  timeline.setWindow('2016-01-01', '2016-12-31');
  }
    document.getElementById('fit').onclick = function() {
    timeline.fit();
  };
  
  	}
  });
</script>    

<?php } ?>
               
			   <?php if ($totalRows_documentos > 0) { // Show if recordset not empty ?>
                <table border="0" class="display" id="filest" width="100%">
                  <thead>
                  <tr>
                    <td width="32">Fecha</td>
                    <td width="150">Descripcion</td>
                    <td width="219">Acciones</td>
                  </tr>
                  </thead>
                  <tbody>
                  <?php do { ?>
                    <tr>
                      <td><?php echo date("d/m/y H:m:s", strtotime($row_documentos['fecha'])); ?></td>
                      <td><a href="doc_view.php?id_doc=<?php echo $row_documentos['id_doc']; ?>" target="_blank"><?php echo $row_documentos['descripcion']; ?></a></td>
                      <td><a href="doc_down.php?id_doc=<?php echo $row_documentos['id_doc']; ?>" target="_blank">Descargar</a> | Modificar | Eliminar</td>
                    </tr>
                    <?php } while ($row_documentos = mysql_fetch_assoc($documentos)); ?>
                  </tbody>
              </table>
              </p>
              <?php } ?>
              
          </table>
          <p><a href="cat_equipos.php">Regresar</a> | <a href="add_coment.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>">Agregar Comentario</a> | <a href="add_imgequipo.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>">Agregar Imagen</a> | <a href="agregardocu.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>">Agregar Documento</a> | <a href="Edit_Equipo.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>">Editar Equipo</a> | <a href="catalogos/Agregar_Ctto.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>&no_ctto=<?php echo $NoContrato ?>&idcia=<?php echo $idcia ?>">Agregar Contrato</a> | <a href="add_eficiencia.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>&lightbox[width]=500&lightbox[height]=300&lightbox[modal]=true" class="lightbox">Agregar Eficiencia</a> | <?php if ($_SESSION['permiso']=="admin") { ?><a href="catalogos/eliminar_equipo.php?idEquipo=<?php echo $row_Recordset1['idEquipo']; ?>">Eliminar Equipo</a> <?php } ?></p>
      </div>
    
      <div class="grid_12">
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
mysql_free_result($Recordset1);

mysql_free_result($comentarios);

mysql_free_result($documentos);

mysql_free_result($contratos);

mysql_free_result($cronograma);

mysql_free_result($eficiencia);

mysql_free_result($ProgEquipos);

?>
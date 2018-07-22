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
$query_Recordset1 = "SELECT 
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 1 AND ESTATUS IN (1, 2, 5) AND TEquipo IN (1, 2, 3, 4, 8, 9)) AS EQSAO, 
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 2 AND ESTATUS IN (1, 2, 5) AND TEquipo IN (1, 2, 3, 4, 8, 9)) AS EQSDCA, 
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 3 AND ESTATUS IN (1, 2, 5) AND TEquipo IN (1, 2, 3, 4, 8, 9)) AS EQSPAS,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 4 AND ESTATUS IN (1, 2, 5) AND TEquipo IN (1, 2, 3, 4, 8, 9)) AS EQSPCGNA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 5 AND ESTATUS IN (1, 2, 5) AND TEquipo IN (1, 2, 3, 4, 8, 9)) AS EQSPCT,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 6 AND ESTATUS IN (1, 2, 5) AND TEquipo IN (1, 2, 3, 4, 8, 9)) AS EQSPCNC,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 1 AND TEquipo = 1 AND ESTATUS IN (1, 2, 5)) AS AE_SAO,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 1 AND TEquipo = 2 AND ESTATUS IN (1, 2, 5)) AS MOD_SAO,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 1 AND TEquipo = 3 AND ESTATUS IN (1, 2, 5)) AS SS_SAO,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 1 AND TEquipo = 9 AND ESTATUS IN (1, 2, 5)) AS EMP_SAO,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 1 AND TEquipo IN (4, 8) AND ESTATUS IN (1, 2, 5)) AS T_SAO,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 2 AND TEquipo = 1 AND ESTATUS IN (1, 2, 5)) AS AE_SDCA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 2 AND TEquipo = 2 AND ESTATUS IN (1, 2, 5)) AS MOD_SDCA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 2 AND TEquipo = 3 AND ESTATUS IN (1, 2, 5)) AS SS_SDCA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 2 AND TEquipo = 9 AND ESTATUS IN (1, 2, 5)) AS EMP_SDCA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 2 AND TEquipo IN (4, 8) AND ESTATUS IN (1, 2, 5)) AS T_SDCA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 3 AND TEquipo = 1 AND ESTATUS IN (1, 2, 5)) AS AE_SPAS,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 3 AND TEquipo = 2 AND ESTATUS IN (1, 2, 5)) AS MOD_SPAS,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 3 AND TEquipo = 3 AND ESTATUS IN (1, 2, 5)) AS SS_SPAS,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 3 AND TEquipo = 9 AND ESTATUS IN (1, 2, 5)) AS EMP_SPAS,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 3 AND TEquipo IN (4, 8) AND ESTATUS IN (1, 2, 5)) AS T_SPAS,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 4 AND TEquipo = 1 AND ESTATUS IN (1, 2, 5)) AS AE_SPCGNA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 4 AND TEquipo = 2 AND ESTATUS IN (1, 2, 5)) AS MOD_SPCGNA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 4 AND TEquipo = 3 AND ESTATUS IN (1, 2, 5)) AS SS_SPCGNA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 4 AND TEquipo = 9 AND ESTATUS IN (1, 2, 5)) AS EMP_SPCGNA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 4 AND TEquipo IN (4, 8) AND ESTATUS IN (1, 2, 5)) AS T_SPCGNA,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 5 AND TEquipo = 1 AND ESTATUS IN (1, 2, 5)) AS AE_SPCT,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 5 AND TEquipo = 2 AND ESTATUS IN (1, 2, 5)) AS MOD_SPCT,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 5 AND TEquipo = 3 AND ESTATUS IN (1, 2, 5)) AS SS_SPCT,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 5 AND TEquipo = 9 AND ESTATUS IN (1, 2, 5)) AS EMP_SPCT,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 5 AND TEquipo IN (4, 8) AND ESTATUS IN (1, 2, 5)) AS T_SPCT,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 6 AND TEquipo = 1 AND ESTATUS IN (1, 2, 5)) AS AE_SPCNC,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 6 AND TEquipo = 2 AND ESTATUS IN (1, 2, 5)) AS MOD_SPCNC,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 6 AND TEquipo = 3 AND ESTATUS IN (1, 2, 5)) AS SS_SPCNC,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 6 AND TEquipo = 9 AND ESTATUS IN (1, 2, 5)) AS EMP_SPCNC,
(SELECT COUNT(cat_equipos.Equipo) FROM cat_equipos WHERE SUBDIR = 6 AND TEquipo IN (4, 8) AND ESTATUS IN (1, 2, 5)) AS T_SPCNC,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo = 1 AND ESTATUS IN (1, 2, 5) AND SUBDIR IN (1, 2, 3, 4, 5, 6)) AS AE,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo = 2 AND ESTATUS IN (1, 2, 5) AND SUBDIR IN (1, 2, 3, 4, 5, 6)) AS MODD,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo = 3 AND ESTATUS IN (1, 2, 5) AND SUBDIR IN (1, 2, 3, 4, 5, 6)) AS SS,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo = 9 AND ESTATUS IN (1, 2, 5) AND SUBDIR IN (1, 2, 3, 4, 5, 6)) AS EMP,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (4, 8) AND ESTATUS IN (1, 2, 5) AND SUBDIR IN (1, 2, 3, 4, 5, 6)) AS TERR,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND ESTATUS IN (1, 2, 5) AND SUBDIR IN (1, 2, 3, 4, 5, 6)) AS TTOTAL,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia = 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 1)) AS PMXSAO,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia <> 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 1)) AS CIASAO,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia = 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 2)) AS PMXSDCA,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia <> 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 2)) AS CIASDCA,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia = 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 3)) AS PMXSPAS,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia <> 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 3)) AS CIASPAS,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia = 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 4)) AS PMXSPCGNA,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia <> 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 4)) AS CIASPCGNA,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia = 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 5)) AS PMXSPCT,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia <> 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 5)) AS CIASPCT,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia = 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 6)) AS PMXSPCNC,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia <> 2) AND ESTATUS IN (1, 2, 5) AND (SUBDIR = 6)) AS CIASPCNC,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia = 2) AND ESTATUS IN (1, 2, 5) AND SUBDIR IN (1, 2, 3, 4, 5, 6)) AS PMXTTAL,
(SELECT Count(cat_equipos.Equipo) FROM cat_equipos WHERE TEquipo IN (1, 2, 3, 4, 8, 9) AND (Cia <> 2) AND ESTATUS IN (1, 2, 5) AND SUBDIR IN (1, 2, 3, 4, 5, 6)) AS CIATTAL";
$Recordset1 = mysql_query($query_Recordset1, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!DOCTYPE html>
<html lang="es" xmlns:spry="http://ns.adobe.com/spry">
<head>
<title>Resumen de equipos operando por Subdirección</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responstable.css">
<link href="../SpryAssets/SpryMasterDetail.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js"></script>
<script src="js/jquery-migrate-1.4.1.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/script.js"></script> 
<script src="js/superfish.js"></script>
<script src="js/jquery.equalheights.js"></script>
<script src="js/jquery.mobilemenu.js"></script>
<script src="js/tmStickUp.js"></script>
<script src="js/jquery.ui.totop.js"></script>
<script src="../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
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
	<style type="text/css" class="init">
	
td.details-control {
	background: url('DataTables/examples/resources/details_open.png') no-repeat center center;
	cursor: pointer;
}
tr.details td.details-control {
	background: url('DataTables/examples/resources/details_close.png') no-repeat center center;
}

	</style>
<script src="Chart.js/Chart.js"></script>

  <link rel="stylesheet" type="text/css" href="jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css" />
  <script type="text/javascript" src="jquery.lightbox/js/lightbox/jquery.lightbox.min.js"></script>
   <!-- // <script type="text/javascript" src="jquery.lightbox/jquery.lightbox.js"></script>   -->

  <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox();
    });
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
               <li class="current">Resumen de Equipos por Subdirección</li>
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
        <h3>Resumen de Equipos por Subdirección</h3>
        <div class="extra_wrapper">
          <p>Equipos Operando e Inactivos por Subdireccion</p>
          <table width="100%" border="0" class="responstable">
            <tr>
              <th width="300" scope="col"><span class="ta__center"><strong>SUBDIRECCION</strong></span></th>
              <th width="80" scope="col"><strong>A/E</strong></th>
              <th width="80" scope="col"><strong>EMP</strong></th>
              <th width="80" scope="col"><strong>MOD</strong></th>
              <th width="80" scope="col"><strong>S/S</strong></th>
              <th width="50" scope="col"><strong>T</strong></th>
              <th width="17" scope="col">&nbsp;</th>
              <th width="50" scope="col"><strong>PEMEX</strong></th>
              <th width="50" scope="col"><strong>COMPAÑIA</strong></th>
              <th width="200" scope="col"><strong>TOTAL</strong></th>
              
            </tr>
            <tr>
              <th width="300" scope="row">Aseguramiento Operativo</th>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=AE_SAO&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['AE_SAO'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=EMP_SAO&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EMP_SAO'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=MOD_SAO&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['MOD_SAO'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=SS_SAO&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['SS_SAO'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=T_SAO&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['T_SAO'];?></a></td>
              <th width="17">&nbsp;</th>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=PMXSAO&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['PMXSAO'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=CIASAO&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['CIASAO'];?></a></td>
              <td width="200"><a href="catalogos/list_eq_detalle.php?tconsulta=TTAL_SAO&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EQSAO'];?></a></td>
            </tr>
            <tr>
              <th width="300" scope="row">Desarrollo de Campos</th>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=AE_SDCA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['AE_SDCA'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=EMP_SDCA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EMP_SDCA'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=MOD_SDCA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['MOD_SDCA'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=SS_SDCA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['SS_SDCA'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=T_SDCA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['T_SDCA'];?></a></td>
              <th width="17">&nbsp;</th>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=PMXSDCA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['PMXSDCA'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=CIASDCA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['CIASDCA'];?></a></td>
              <td width="200"><a href="catalogos/list_eq_detalle.php?tconsulta=TTAL_SDCA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EQSDCA'];?></a></td>
            </tr>
            <tr>
              <th width="300" scope="row">Aguas Someras</th>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=AE_SPAS&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['AE_SPAS'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=EMP_SPAS&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EMP_SPAS'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=MOD_SPAS&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['MOD_SPAS'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=SS_SPAS&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['SS_SPAS'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=T_SPAS&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['T_SPAS'];?></a></td>
              <th width="17">&nbsp;</th>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=PMXSAS&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['PMXSPAS'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=CIASAS&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['CIASPAS'];?></a></td>
              <td width="200"><a href="catalogos/list_eq_detalle.php?tconsulta=TTAL_SAS&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EQSPAS'];?></a></td>
            </tr>
            <tr>
              <th width="300" scope="row">Campos de Gas no Asociado</th>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=AE_CGNA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['AE_SPCGNA'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=EMP_CGNA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EMP_SPCGNA'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=MOD_CGNA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['MOD_SPCGNA'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=SS_CGNA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['SS_SPCGNA'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=T_CGNA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['T_SPCGNA'];?></a></td>
              <th width="17">&nbsp;</th>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=PMXSCGNA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['PMXSPCGNA'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=CIASCGNA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['CIASPCGNA'];?></a></td>
              <td width="200"><a href="catalogos/list_eq_detalle.php?tconsulta=TTAL_SCGNA&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EQSPCGNA'];?></a></td>
            </tr>
            <tr>
              <th width="300" scope="row">Campos Terrestres</th>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=AE_SPCT&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['AE_SPCT'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=EMP_SPCT&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EMP_SPCT'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=MOD_SPCT&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['MOD_SPCT'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=SS_SPCT&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['SS_SPCT'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=T_SPCT&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['T_SPCT'];?></a></td>
              <th width="17">&nbsp;</th>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=PMXSCT&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['PMXSPCT'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=CIASCT&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['CIASPCT'];?></a></td>
              <td width="200"><a href="catalogos/list_eq_detalle.php?tconsulta=TTAL_SCT&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EQSPCT'];?></a></td>
            </tr>
            <tr>
              <th width="300" scope="row">Campos No Convencionales</th>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=AE_CNC&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['AE_SPCNC'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=EMP_CNC&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EMP_SPCNC'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=MOD_CNC&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['MOD_SPCNC'];?></a></td>
              <td width="80"><a href="catalogos/list_eq_detalle.php?tconsulta=SS_CNC&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['SS_SPCNC'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=T_CNC&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['T_SPCNC'];?></a></td>
              <th width="17">&nbsp;</th>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=PMXSCNC&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['PMXSPCNC'];?></a></td>
              <td width="50"><a href="catalogos/list_eq_detalle.php?tconsulta=CIASCNC&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['CIASPCNC'];?></a></td>
              <td width="200"><a href="catalogos/list_eq_detalle.php?tconsulta=TTAL_SCNC&&lightbox[iframe]=true&lightbox[width]=1034&lightbox[height]=650" class="lightbox"><?php echo $row_Recordset1['EQSPCNC'];?></a></td>
            </tr>
            <tr>
              <th width="300" scope="row">Total</th>
              <td width="80"><?php echo $row_Recordset1['AE'];?></td>
              <td width="80"><?php echo $row_Recordset1['EMP'];?></td>
              <td width="80"><?php echo $row_Recordset1['MODD'];?></td>
              <td width="80"><?php echo $row_Recordset1['SS'];?></td>
              <td width="50"><?php echo $row_Recordset1['TERR'];?></td>
              <th width="17">&nbsp;</th>
              <td width="50"><?php echo $row_Recordset1['PMXTTAL'];?></td>
              <td width="50"><?php echo $row_Recordset1['CIATTAL'];?></td>
              <td width="200"><?php echo $row_Recordset1['TTOTAL'];?></td>
            </tr>
          </table>
          <p>Nota: No se contabilizan equipos aligerados, snubing y lacustres.</p>
          <div id="canvas-holder">
			Distribución de Equipos
            <canvas id="chart-area" width="300" height="300"/>
		</div>


	<script>

		var pieData = [
				{
					value: <?php echo $row_Recordset1['EQSAO'];?>,
					color:"#0059B2",
					highlight: "#FF5A5E",
					label: "Aseguramiento Operativo"
				},
				{
					value: <?php echo $row_Recordset1['EQSDCA'];?>,
					color: "#B20000",
					highlight: "#5AD3D1",
					label: "Desarrollo de Campos"
				},
				{
					value: <?php echo $row_Recordset1['EQSPAS'];?>,
					color: "#00661A",
					highlight: "#FFC870",
					label: "Aguas Someras"
				},
				{
					value: <?php echo $row_Recordset1['EQSPCGNA'];?>,
					color: "#AAAAAA",
					highlight: "#A8B3C5",
					label: "Campos de Gas no Asociado"
				},
				{
					value: <?php echo $row_Recordset1['EQSPCT'];?>,
					color: "#777777",
					highlight: "#616774",
					label: "Campos Terrestres"
				},
				{
					value: <?php echo $row_Recordset1['EQSPCNC'];?>,
					color: "#FF8000",
					highlight: "#616774",
					label: "Campos No Convencionales"
				}

			];

			window.onload = function(){
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData);
			};



	</script>
          
          
          
        </div>
            </ul>
      </div>
          Resumen de Intervenciones | <a href="rep_estatus_equipos.php">Equipos por Estatus</a> | <a href="operatividad.php">OPERATIVIDAD DE EQUIPOS</a> |</div>
    
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
?>

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

mysql_select_db($database_ResEquipos, $ResEquipos);
$query_CATEquipos = "SELECT
cat_equipos.idEquipo,
cat_equipos.CLVE_EQUIPO,
cat_equipos.Equipo,
cat_tipoequipo.Tipo,
cat_equipocaracteristicas.Caracteristicas,
cat_cias.InicialCia,
cat_equipos.marcamalacate,
cat_equipos.HP,
cat_equipos.TA,
cat_equipos.CAPPERF,
cat_equipos.SECCSINDICAL,
cat_estatus.SEMAFORO,
cat_estatus.ESTATUS
FROM
cat_equipos
INNER JOIN cat_tipoequipo ON cat_tipoequipo.idtequipo = cat_equipos.TEquipo
INNER JOIN cat_equipocaracteristicas ON cat_equipocaracteristicas.IdCar = cat_equipos.Caracteristicas
INNER JOIN cat_cias ON cat_cias.id_cia = cat_equipos.Cia
INNER JOIN cat_estatus ON cat_estatus.ID_ESTATUS = cat_equipos.ESTATUS WHERE (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2) AND cat_equipos.CIA = 2";
$CATEquipos = mysql_query($query_CATEquipos, $ResEquipos) or die(mysql_error());
$row_CATEquipos = mysql_fetch_assoc($CATEquipos);
$totalRows_CATEquipos = mysql_num_rows($CATEquipos);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Catálogo de Equipos</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
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

<link rel="stylesheet" type="text/css" href="DataTables/extensions/Responsive/css/responive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>

	
	<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
	$('#equipost')
	.addClass( 'nowrap' )
	.DataTable(
	{
	 responsive: true,
	"language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.10/i18n/Spanish.json"
				}
	});
	
} );

	</script>

</head>
<body>
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
               <li class="current"><a href="index.html">Inicio</a></li>
               <li><a href="about.html">Acerca de</a></li>
               <li><a href="services.html">Objetivos</a></li>
               <li><a href="http://intranet.pemex.com/os/pep/unp/Paginas/default.aspx">Intranet PPS</a></li>
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
<section id="content"><div class="ic">More Website Templates @ TemplateMonster.com - July 28, 2014!</div>
  <div class="container">
    <div class="row">
      <div class="grid_12">
        <h3>Equipos Operando de PPS</h3>
         <div id="banner" class="container" >
    <table class="display" id="equipost" cellspacing="0" width="100%">
          <thead>
            <tr>
              <td>No. Equipo</td>
              <td>Equipo</td>
              <td>Tipo equipo</td>
              <td>Características</td>
              <td>Compañía</td>
              <td>Estatus </td>
            </tr>
      </thead>
            <tbody>
            <?php do { ?>
              <tr>
                <td><a href="detalle_equipo.php?idEquipo=<?php echo $row_CATEquipos['idEquipo']; ?>"><?php echo $row_CATEquipos['idEquipo']; ?></a></td>
                <td><a href="detalle_equipo.php?idEquipo=<?php echo $row_CATEquipos['idEquipo']; ?>"><?php echo $row_CATEquipos['Equipo']; ?></a></td>
                <td><?php echo $row_CATEquipos['Tipo']; ?></td>
                <td><?php echo $row_CATEquipos['Caracteristicas']; ?></td>
                <td><?php echo $row_CATEquipos['InicialCia']; ?></td>
                <td> <img src="images/sem/sem_<?php echo $row_CATEquipos['SEMAFORO']; ?>.png" width="16" height="16" title="<?php echo utf8_encode($row_CATEquipos['ESTATUS']); ?>"></td>
              </tr>
              <?php } while ($row_CATEquipos = mysql_fetch_assoc($CATEquipos)); ?>
      </tbody>
    </table>
    <p>&nbsp;</p>
          <p><a href="Add_Equipo.php">Agregar Equipos</a> | Inactivos</p>
    </div>
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
</footer>
<a href="#" id="toTop" class="fa fa-chevron-up"></a>
</body>
</html>
<?php
mysql_free_result($CATEquipos);
?>
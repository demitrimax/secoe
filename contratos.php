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
<?php require_once('Connections/ResEquipos.php'); ?>
<?php


mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_Recordset1 = "SELECT * FROM list_contratos ORDER BY F_FIN ASC";
$Recordset1 = mysqli_query($ResEquipos, $query_Recordset1) or die(mysqli_error($ResEquipos));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!DOCTYPE html>
<html lang="es" xmlns:spry="http://ns.adobe.com/spry">
<head>
<title>Contratos de Equipos</title>
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

	<script type="text/javascript" language="javascript" src="DataTables/media/js/jquery.dataTables.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/examples/resources/syntax/shCore.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/examples/resources/demo.js">
	</script>
	<script type="text/javascript" language="javascript" class="init">
	
$(document).ready(function() {
	$('#listctos')
	.DataTable(
	{
		stateSave: true,
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
               <li class="current">Contratos de Equipos de Perforacion</li>
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
        <h3>Contratos de Equipos Vigentes</h3>
        <div class="extra_wrapper">
          <p>Lista de Contratos </p>
          
          
          <table border="0" id="listctos" class="display">
            <thead>
            <tr>
              <td>No. de Contrato</td>
              <td>Plazo</td>
              <td>Fecha de Inicio</td>
              <td>Fecha de Termino</td>
              <td>Esquema</td>
              <td>Nombre de la Compañía</td>
              <td>Equipos</td>
              <td>Semáforo</td>
            </tr>
            </thead>
            <tbody>
			<?php do { ?>
              <tr>
                <td><a href="catalogos/detalle_ctto.php?no_ctto=<?php echo $row_Recordset1['NO_CONTRATO']; ?>"><?php echo $row_Recordset1['NO_CONTRATO']; ?></a></td>
                <td><?php echo $row_Recordset1['PLAZO']; ?></td>
                <td><?php echo date("d-m-Y",strtotime($row_Recordset1['F_INICIO'])); ?></td>
                <td><?php echo date("d-m-Y",strtotime($row_Recordset1['F_FIN'])); ?></td>
                <td><?php echo $row_Recordset1['ESQUEMA']; ?></td>
                <td><?php echo utf8_encode($row_Recordset1['NombreCia']); ?></td>
                <td><?php echo $row_Recordset1['equipos']; ?></td>
                <td><img src="images/sem/sem_<?php echo $row_Recordset1['SEMAFORO']; ?>.png" width="16" height="16" title="<?php echo utf8_encode($row_Recordset1['ESTATUS']); ?>"></td>
              </tr>
              <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
            </tbody>
          </table>
        </div>
            </ul>
      </div>
          <p>&nbsp;</p>
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
?>

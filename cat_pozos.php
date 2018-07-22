<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "admin,consulta";
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
<!DOCTYPE html>
<html lang="en">
<head>
<title>Catálogo de Pozos</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/form.css">
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
		
	<script type="text/javascript" charset="utf8" src="DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="DataTables/extensions/Responsive/css/responsive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" class="init">
	
	$(document).ready(function() {
    $('#catpozos').DataTable( {
        "processing": true,
        "serverSide": true,
		"stateSave": true,
	 	"responsive": true,
		"language": {
                "url": "DataTables/spanish/spanish.json"
						},
        "ajax": {
            "url": "catalogos/resul_catpozos.php",
            "type": "POST"
        },
		"columns": [
            { "data": "idpozo" },
            { "data": "nombrepozo"},
            { "data": "campo" },
            { "data": "numero" },
            { "data": "tipo" },
            { "data": "profundidad" },
			{ "data": "UOP_CORTO" }
        ],
		"columnDefs": [ {
    		"targets": 0,
			"data": "idpozo",
		    "render": function ( data, type, full, meta) {
					//var itemID = data2;
			      	return '<a href="pozo.php?idpozo='+data+'">'+data+'</a>';
    		}
 			 } ],
		
    } )
	});
	
	
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
               <li>Catálogo de Pozos</li>
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
        <h3>Catálogo de Pozos</h3>
			<div class="grid_12">
					<table id="catpozos" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Pozo</th>
                <th>Campo</th>
                <th>Número</th>
                <th>Tipo</th>
                <th>Profundidad</th>
                <th>Unidad Operativa</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Nombre Pozo</th>
                <th>Campo</th>
                <th>Número</th>
                <th>Tipo</th>
                <th>Profundidad</th>
                <th>Unidad Operativa</th>
            </tr>
        </tfoot>
    </table>
           <a href="index.php">REGRESAR</a> | <a href="agregar_pozo.php">AGREGAR POZO</a>| <a href="catalogopozos.php">BASE PLANA </a>
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


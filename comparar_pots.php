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
$colname_Equipo = -1;
if (isset($_GET['idequipo'])) {
  $colname_Equipo = $_GET['idequipo'];
}

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_equipos = "SELECT * FROM cat_equipos";
$equipos = mysql_query($query_equipos, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_equipos = mysqli_fetch_assoc($equipos);
$totalRows_equipos = mysqli_num_rows($equipos);


?>
<!DOCTYPE html>
<html lang="es" xmlns:spry="http://ns.adobe.com/spry">
<head>
<title>Gantt de los equipos</title>
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

  <link rel="stylesheet" type="text/css" href="jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css" />
  <script type="text/javascript" src="jquery.lightbox/js/lightbox/jquery.lightbox.min.js"></script>
   <!-- // <script type="text/javascript" src="jquery.lightbox/jquery.lightbox.js"></script>   -->

    <script type='text/javascript' src='quickselect/quicksilver.js'></script>
  	<script type='text/javascript' src='quickselect/jquery.quickselect.js'></script>
<link rel="stylesheet" type="text/css" href="quickselect/jquery.quickselect.css" />

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


<!--==============================
              header
=================================-->
<header>
  <div class="container">
    <div class="row">
      <div class="grid_12 rel">
        <h1>
          <a href="index.php">
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
               <li class="current">Comparativo de Programas Operativos</li>
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
        <h3>Gantt para comparar POT's</h3>
        <div class="extra_wrapper">
          Seleccione uno o más equipos
            <select name="equipos" multiple="MULTIPLE" id="equipos">
              <?php
				do {  
				?>
              <option value="<?php echo $row_equipos['idEquipo'];?>"><?php echo $row_equipos['Equipo']; ?></option>
              <?php 
			  } while ($row_equipos = mysqli_fetch_assoc($equipos));
  				$rows = mysqli_num_rows($equipos);
			  if($rows > 0) {
		      		mysql_data_seek($equipos, 0);
	  				$row_equipos = mysqli_fetch_assoc($equipos);
  				}
?>
            </select>
            <script type='text/javascript'>$(function(){ $('#equipos').quickselect(); });</script>
			<p>Seleccioe el Programa</p><br>
            
        </div>
      </div>
          <p>&nbsp;</p>
    </div>
    
      <div class="grid_12">
    <a href="index.php">Regresar</a></div>

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
mysqli_free_result($equipos);

?>

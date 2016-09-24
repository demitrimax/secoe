<?php require_once('../../Connections/ResEquipos.php'); ?>
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
$query_cat_cias = "select * from cat_cias";
$cat_cias = mysql_query($query_cat_cias, $ResEquipos) or die(mysql_error());
$row_cat_cias = mysql_fetch_assoc($cat_cias);
$totalRows_cat_cias = mysql_num_rows($cat_cias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Catálogo de Compañías</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="../images/favicon.ico">
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="../css/style.css">
<script src="../js/jquery.js"></script>
<script src="../js/jquery-migrate-1.1.1.js"></script>
<script src="../js/jquery.easing.1.3.js"></script>
<script src="../js/script.js"></script> 
<script src="../js/superfish.js"></script>
<script src="../js/jquery.equalheights.js"></script>
<script src="../js/jquery.mobilemenu.js"></script>
<script src="../js/tmStickUp.js"></script>
<script src="../js/jquery.ui.totop.js"></script>
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

</script>
  <link rel="stylesheet" type="text/css" href="../jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css" />
  <script type="text/javascript" src="../jquery.lightbox/js/lightbox/jquery.lightbox.min.js"></script>
   <!-- // <script type="text/javascript" src="jquery.lightbox/jquery.lightbox.js"></script>   -->

  <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox();
    });
  </script>


<link rel="stylesheet" type="text/css" href="../DataTables/media/css/jquery.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="../DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="../DataTables/extensions/Responsive/css/responive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="../DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>

	
	<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
	$('#equipost')
	.addClass( 'nowrap' )
	.DataTable(
	{
	 responsive: true,
	"language": {
                "url": "DataTables/spanish/spanish.json"
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
          <a href="../index.html">
            <img src="../images/logo2.png" alt="Logo alt">
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
               <li class="current"><a href="../index.html">Inicio</a></li>
               <li><a href="../about.html">Acerca de</a></li>
               <li><a href="../services.html">Objetivos</a></li>
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
<section id="content"><div class="ic"></div>
  <div class="container">
    <div class="row">
      <div class="grid_12">
        <h3>Catálogo de Compañías</h3>
         <div id="banner" class="container" >
    <table class="display" id="equipost" cellspacing="0" width="100%">
          <thead>
            <tr>
              <td>Compañía</td>
              <td>Clave</td>
              <td>Nacionalidad</td>
              <td>Acciones</td>
            </tr>
      </thead>
            <tbody>
            <?php do { ?>
              <tr>
                <td><a href="catcias_edit.php?id_cia=<?php echo $row_cat_cias['id_cia']; ?>"><?php echo $row_cat_cias['NombreCia']; ?></a></td>
                <td><?php echo $row_cat_cias['InicialCia']; ?></td>
                <td><?php echo $row_cat_cias['Nacionalidad']; ?></td>
                <td></td>
              </tr>
              <?php } while ($row_cat_cias = mysql_fetch_assoc($cat_cias)); ?>
      </tbody>
    </table>
    <p>&nbsp;</p>
          <p> Agregar Compañía </p>
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
mysql_free_result($cat_cias);
?>
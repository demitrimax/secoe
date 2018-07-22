<?php require_once('Connections/ResEquipos.php'); 
  $correcodigo = "true"; 
if (!isset($_SESSION)) {
  session_start();
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

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_EstatusGen = "SELECT * FROM estatusgen";

$EstatusGen = mysqli_query($ResEquipos, $query_EstatusGen) or die(mysqli_error($ResEquipos));
$row_EstatusGen = mysqli_fetch_assoc($EstatusGen);
$totalRows_EstatusGen = mysqli_num_rows($EstatusGen);

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['AccesOK'] = NULL;
  $_SESSION['usuario'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['AccesOK']);
  unset($_SESSION['usuario']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
  
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>SECOE</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" type="image/png" href="favicon.png" />
<link rel="shortcut icon" href="favicon.png" />
<link rel="stylesheet" href="css/style.css">
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
<!--- ************** lightbox.jquery script ***************************** -->
<script type="text/javascript" src="jquery.lightbox/js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css" />
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" href="jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
<![endif]-->
<script type="text/javascript" src="jquery.lightbox/js/lightbox/jquery.lightbox.min.js"></script>
<!--- **********************lightbox.jquery script ********************** -->
</head>
<body class="page1" id="top">
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
               <li class="current"><a href="index.php">Inicio</a></li>
               <li><a href="about.html">Acerca de</a></li>
               <li><a href="catalogos/cat_docs.php">Correspondencia</a></li>
               <li><a href="http://intranet.pemex.com/os/pep/unp/Paginas/default.aspx">Intranet PPS</a></li>
               <?php if (isset($_SESSION['usuario'])) { ?>
               <li>Bienvenido <?php echo $_SESSION['usuario']; ?> <a href="<?php echo $logoutAction ?>"> [Cerrar Sesión]</a></li>
             <?php 
			 $correcodigo = "false";
			 } ?>
             </ul>
            </nav>
            <div class="clear"></div>
          </div>       
         <div class="clear"></div>  
        </div>
     </div> 
    </div> 
  </section>
  <section class="page1_header">
    <div class="container">
      <div class="row">
        <div class="grid_4">
          <a href="cat_pozos.php" class="banner "><div class="maxheight">
            <div class="fa fa-linode"></div>
            Catálogo de Pozos</div>
          </a>
          <a href="eq_opcia.php" class="banner "><div class="maxheight">
            <div class="fa fa-lightbulb-o"></div>
            Equipos por Compañía</div>
          </a>
          <a href="cat_equipos.php" class="banner "><div class="maxheight1">
            <div class="fa fa-cog"></div>SECOE</div>
          </a>
          <a href="contratos.php" class="banner "><div class="maxheight1">
            <div class="fa fa-briefcase"></div>Contratos</div>
          </a>
        </div>
        <div class="grid_5">
          <h2>Seguimiento <br> Estratégico de <br> Contratación de <br>Equipos</h2>
          Sistema de información de equipos de perforación de PPS
        </div>
      </div>
    </div>
  </section>
</header>
<div class="block-1">
  <div class="container">
    <div class="row">
      <div class="grid_3">
        <div class="block-1_count"><?php echo $row_EstatusGen['EQ_PMX']; ?></div>
        Equipos de <br> PPS
        <div class="clear"></div>
      </div>
      <div class="grid_3">
        <div class="block-1_count"><?php echo $row_EstatusGen['EQ_CIA']; ?></div>
        Equipos <br> de Compañía
        <div class="clear"></div>
      </div>
      <div class="grid_3">
        <div class="block-1_count"><?php echo $row_EstatusGen['NCTOS']; ?></div>
        Contratos <br> de equipos
        <div class="clear"></div>
      </div>
      <div class="grid_3">
        <a href="https://siav.pemex.com/vpn/index.html" class="support" target="_blank"><img src="images/support2.png" alt=""></a>
      </div>
    </div>
  </div>
</div>
<!--=====================
          Content
======================-->
<section id="content"><div class="ic"></div>
  <div class="container">
    <div class="row">
      <div class="grid_10 preffix_1 ta__center">
        <div class="greet">
          <h2 class="head__1">
            Bienvenido
          </h2>
          <p>Conozca más acerca de <a href="about.html" rel="nofollow" class="color1">SECOE</a> aquí. <br> Vaya al sitio de la intranet de <a href="http://intranet.pemex.com/os/pep/unp/Paginas/default.aspx" rel="nofollow" class="color1">Pemex Perforación y Servicios</a> (PPS). </p>
          Este sitio se creó a partir de la necesidad de tener toda la información de los equipos de pemex y de compañía en un solo lugar...accesible y para todo PPS. 
        </div>
      </div>
    </div>
  </div>
  <article class="content_gray">
    <div class="container">
      <div class="row">
        <div class="grid_7">
          <h3>Reportes especiales</h3>
          <div class="block-2">
            <img src="images/page1_img1.jpg" alt="" class="img_inner fleft">
            <div class="extra_wrapper">
              <div class="text1"><a href="operatividad.php">Reporte de Operatividad de Equipos</a></div>
              <p>Reporte de operatividad de equipos, incluye equipos de PPS y de Compañía operando, inactivos de pemex y de compañía con contrato vigente. Además podrá clasificar rápidamente el estatus de los equipos.
              <br>
              <a href="rep_subdir_activo.php" class="link-1">más</a>
            </div>
          </div>
          <div class="block-2">
            <img src="images/page1_img2.jpg" alt="" class="img_inner fleft">
            <div class="extra_wrapper">
              <div class="text1"><a href="gantt_equipos.php">Graficas de Gantt por Activo</a></div>
              <p>Visualice de forma gráfica las gráficas de Gantt para cada equipo operando, disponible e inactivo.</p>La mayoría de los equipos tienen carga de trabajo y en esta sección podrá visualizarlo.
              <br>
              <a href="#" class="link-1">más...</a>
            </div>
          </div>
        </div>
        <div class="grid_4 preffix_1">
          <h3>Testimonios</h3>
          <blockquote class="bq1">
            <p>“Curabitur vel lorem sit amet nulla erero fermentum. In vitae varius auguectetu ligula. Etiam dui eros, laoreet site am est vel commodo venenatisipiscing... ”</p>
            <span>Liza Jons</span>
          </blockquote>
          <blockquote class="bq1">
            <p>“Burabitur vel lorem sit amet nulla erero fermentum. In vitae varius auguectetu ligula. Etiam dui eros, laoreet site am ast vel commodo venenatisipiscino... ”</p>
            <span>Mark Brown</span>
          </blockquote>
        </div>
      </div>
    </div>
  </article>
  <div class="container">
    <div class="row">
      <div class="grid_5">
        <h4>Nuestra empresa PPS</h4>
        <img src="images/page1_img3.jpg" alt="" class="img_inner fleft">
          <p>Curabitur vel lorem sit amet nulla ullamcorper fermentum In vitae dert arius augue, eu consectetur </p>
          <p class="offset__1">Eligulaam dui eros dertolisce dertolo adipiscing quam id risus sagittis</p>
          Curabitur vel lorem sit amet nulla ullamcorper fermentum In vitae dert rius augue, eu consectetur larem dui eros dertolisce dertolo 
      </div>
      <div class="grid_4">
        <h4>Solutions</h4>
        <ul class="list-1">
          <li><a href="#">Vivamus at magna non nunc tristique </a></li>
          <li><a href="#">Aliquam nibh ante, egestas id</a></li>
          <li><a href="#">Ommodo luctus libero</a></li>
          <li><a href="#">Faucibus malesuada faucibusonec </a></li>
          <li><a href="#">Laoreet metus id laoreet</a></li>
          <li><a href="#">Jalesuadaorem ipsum dolor sit ame</a></li>
        </ul>
      </div>
      <div class="grid_3">
        <h4>Información de Contacto</h4>
        <address>
          <ul class="cont_address">
            <li>Av. Ruiz Cortínez #1202 Fracc. Oropeza</li>
            <li>Edificio Pirámide de Pemex, Piso 7. Gerencia de Estrategias y Planez</li>
            <li>(993) 310 62 62 Ext. 21175</li>
            <li><a href="mailto:joel.jimenez@pemex.com">joel.jimenez@pemex.com</a></li>
          </ul>
        </address>
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
<?php if ($correcodigo == "true") { ?>
<script type="text/javascript">
  jQuery(document).ready(function($){
  
    $.lightbox("login.php?lightbox[width]=500&lightbox[height]=300&lightbox[modal]=true");

  });
</script>
	<?php } ?>
</body>
</html>
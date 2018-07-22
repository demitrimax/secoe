<?php require_once('Connections/ResEquipos.php'); ?>
<?php require_once("../webassist/file_manipulation/helperphp.php"); ?>
<?php
// WA_UploadResult1 Params Start
$WA_UploadResult1_Params = array();
// WA_UploadResult1_1 Start
$WA_UploadResult1_Params["WA_UploadResult1_1"] = array(
	'UploadFolder' => "imgequipos/",
	'FileName' => "".$_GET['idEquipo']  ."",
	'DefaultFileName' => "",
	'ResizeType' => "0",
	'ResizeWidth' => "120",
	'ResizeHeight' => "120",
	'ResizeFillColor' => "#FFFFFF" );
// WA_UploadResult1_1 End
// WA_UploadResult1 Params End?>
<?php
WA_DFP_SetupUploadStatusStruct("WA_UploadResult1");
if($_SERVER["REQUEST_METHOD"] == "POST"){
	WA_DFP_UploadFiles("WA_UploadResult1", "imagen", "0", "", "JPG:90", $WA_UploadResult1_Params);
}
?>
<?php


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO imagenes (id_img, id_equipo, imagen, descripcionimg) VALUES (%s, %s, %s, %s)",
                       $_POST['id_img'],
                       $_POST['id_equipo'],
                       "imgequipos/".$_GET['idEquipo'].".jpg",
                       $_POST['descripcionimg']);

  mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysql_query($insertSQL, $ResEquipos) or die(mysqli_error($ResEquipos));

  $insertGoTo = "detalle_equipo.php?idEquipo=" . $_GET['idEquipo'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Agregar Imagen de Equipo</title>
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
        <h3>Agregar Imágen</h3>
         <div id="banner" class="container" >
         <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" id="form">
        <table align="center">
              <tr valign="baseline">
                <td width="72" align="right">Id_equipo:</td>
                <td width="214"><input name="id_equipo" type="text" value="<?php echo $_GET['idEquipo']; ?>" size="32" readonly /></td>
              </tr>
              <tr valign="baseline">
                <td align="right">Imagen:</td>
                <td>
                <input name="imagen" type="file" id="imagen" value="" required/></td>
              </tr>
              <tr valign="baseline">
                <td align="right"><p>Descripcion:</p></td>
                <td><textarea name="descripcionimg" cols="32" required></textarea></td>
              </tr>
              <tr valign="baseline">
                <td align="right">&nbsp;</td>
                <td><input type="submit" value="Agregar Imagen" /></td>
              </tr>
        </table>
            <input type="hidden" name="id_img" value="" />
            <input type="hidden" name="MM_insert" value="form1" />
          </form>
    
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

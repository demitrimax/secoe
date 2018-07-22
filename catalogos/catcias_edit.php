<?php require_once('../../Connections/ResEquipos.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE cat_cias SET NombreCia=%s, InicialCia=%s, Direccion=%s, Telefono1=%s, Telefono2=%s, Observaciones=%s, Nacionalidad=%s WHERE id_cia=%s",
                       $_POST['NombreCia'], 
                       $_POST['InicialCia'], 
                       $_POST['Direccion'], 
                       $_POST['Telefono1'], 
                       $_POST['Telefono2'], 
                       $_POST['Observaciones'], 
                       $_POST['Nacionalidad'], 
                       $_POST['id_cia']);

  mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysqli_query($ResEquipos, $updateSQL) or die(mysql_error($ResEquipos));

  $updateGoTo = "cat_companias.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$colname_cat_cias = "-1";
if (isset($_GET['id_cia'])) {
  $colname_cat_cias = $_GET['id_cia'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_cat_cias = sprintf("SELECT * FROM cat_cias WHERE id_cia = %s", $colname_cat_cias);
$cat_cias = mysqli_query($ResEquipos, $query_cat_cias) or die(mysql_error($ResEquipos));
$row_cat_cias = mysqli_fetch_assoc($cat_cias);
$totalRows_cat_cias = mysqli_num_rows($cat_cias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Editar Compañías</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="../images/favicon.ico">
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form.css">
<script src="../js/jquery.js"></script>
<script src="../js/jquery-migrate-1.4.1.js"></script>
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
        <h3>Editar Información de Compañías</h3>
         <div id="banner" class="container" >

    <form method="post" name="form" action="<?php echo $editFormAction; ?>" id="form">
      <table align="center">
        <tr valign="baseline">
          <td width="84" align="right" nowrap>Id_cia:</td>
          <td width="347"><?php echo $row_cat_cias['id_cia']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">NombreCia:</td>
          <td><input type="text" name="NombreCia" value="<?php echo htmlentities($row_cat_cias['NombreCia'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">InicialCia:</td>
          <td><input type="text" name="InicialCia" value="<?php echo htmlentities($row_cat_cias['InicialCia'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" valign="top">Direccion:</td>
          <td><textarea name="Direccion" cols="50" rows="5"><?php echo htmlentities($row_cat_cias['Direccion'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Telefono1:</td>
          <td><input type="text" name="Telefono1" value="<?php echo htmlentities($row_cat_cias['Telefono1'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Telefono2:</td>
          <td><input type="text" name="Telefono2" value="<?php echo htmlentities($row_cat_cias['Telefono2'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" valign="top">Observaciones:</td>
          <td><textarea name="Observaciones" cols="50" rows="5"><?php echo htmlentities($row_cat_cias['Observaciones'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Nacionalidad:</td>
          <td><input type="text" name="Nacionalidad" value="<?php echo htmlentities($row_cat_cias['Nacionalidad'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="Actualizar registro"></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1">
      <input type="hidden" name="id_cia" value="<?php echo $row_cat_cias['id_cia']; ?>">
    </form>
    <p>&nbsp;</p>
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
mysqli_free_result($cat_cias);
?>
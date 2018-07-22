<?php require_once('../../Connections/ResEquipos.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO ctos_comentarios (tipo_coment, comentario, fecha, cto_asociado) VALUES (%s, %s, %s, %s)",
                       $_POST['tcomentario'], 
                       $_POST['Comentario'], 
                       $_POST['fecha'], 
                       $_POST['id_contrato']);

  mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysqli_query($ResEquipos, $insertSQL) or die(mysqli_error($ResEquipos));

  $insertGoTo = "detalle_ctto.php?idctto=".$_GET['no_ctto'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_tipocoment = "SELECT * FROM cat_tipo_comentario";
$tipocoment = mysqli_query($ResEquipos, $query_tipocoment) or die(mysqli_error($ResEquipos));
$row_tipocoment = mysqli_fetch_assoc($tipocoment);
$totalRows_tipocoment = mysqli_num_rows($tipocoment);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Agregar comentario</title>
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
	<script src="../ckeditor/ckeditor.js"></script>

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
               <li class="current">Agregar comentario del contrato</li>
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
        <h3>Agregar comentario del contrato</h3>
         <div id="banner" class="container" >

    <form action="<?php echo $editFormAction; ?>" method="POST" name="form" id="form">
      <table align="center">
        <tr valign="baseline">
          <td width="84" align="right" nowrap>CONTRATO:</td>
          <td width="347"><?php echo $_GET['no_ctto']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Tipo de comentario</td>
          <td><select name="tcomentario">
            <?php
do {  
?>
            <option value="<?php echo $row_tipocoment['id_tipo']?>"><?php echo utf8_encode($row_tipocoment['tipo'])?></option>
            <?php
} while ($row_tipocoment = mysqli_fetch_assoc($tipocoment));
  $rows = mysqli_num_rows($tipocoment);
  if($rows > 0) {
      mysqli_data_seek($tipocoment, 0);
	  $row_tipocoment = mysqli_fetch_assoc($tipocoment);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Fecha:</td>
          <td><input type="date" name="fecha" size="32" required></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" valign="top">Comentario</td>
          <td><textarea name="Comentario" cols="50" rows="5" required>
        
          </textarea>
           <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'Comentario' );
            </script>
          </td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="Agregar Comentario"></td>
        </tr>
      </table>
      <input name="id_contrato" type="hidden" value="<?php echo $_GET['no_ctto']; ?>">
      <input type="hidden" name="MM_insert" value="form">
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
mysqli_free_result($tipocoment);
?>

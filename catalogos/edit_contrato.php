<?php require_once('../Connections/ResEquipos.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE contrato SET F_INICIO='%s', F_FIN='%s', PLAZO='%s', TARIFA='%s', NO_CONTRATO='%s', OBJETO_CTO='%s', COMPANIA='%s', EQUIPOID='%s', ESQUEMA='%s', ESTATUS='%s', T_CTTO='%s' WHERE ID_CTTO=%s",
                       $_POST['F_INICIO'], 
                       $_POST['F_FIN'], 
                       $_POST['PLAZO'], 
                       $_POST['TARIFA'], 
                       $_POST['NO_CONTRATO'], 
                       $_POST['OBJETO_CTO'], 
                       $_POST['COMPANIA'], 
                       $_POST['EQUIPOID'], 
                       $_POST['ESQUEMA'], 
                       $_POST['ESTATUS'], 
					   $_POST['TIPOCTTO'], 
                       $_POST['ID_CTTO']);

  mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysqli_query($ResEquipos, $updateSQL) or die(mysqli_error($ResEquipos));
	

  $updateGoTo = "../contratos.php";
  	if (isset($_POST['REGRESAR'])) {
	$updateGoTo=$_POST['REGRESAR'];	
	}
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_contratos = "-1";
if (isset($_GET['idctto'])) {
  $colname_contratos = $_GET['idctto'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_contratos = sprintf("SELECT * FROM contrato WHERE ID_CTTO = %s", $colname_contratos);
$contratos = mysqli_query($ResEquipos, $query_contratos) or die(mysqli_error($ResEquipos));
$row_contratos = mysqli_fetch_assoc($contratos);
$totalRows_contratos = mysqli_num_rows($contratos);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_companias = "SELECT * FROM cat_cias";
$companias = mysqli_query($ResEquipos, $query_companias) or die(mysqli_error($ResEquipos));
$row_companias = mysqli_fetch_assoc($companias);
$totalRows_companias = mysqli_num_rows($companias);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_esquema = "SELECT * FROM cat_esquemacto";
$esquema = mysqli_query($ResEquipos, $query_esquema) or die(mysqli_error($ResEquipos));
$row_esquema = mysqli_fetch_assoc($esquema);
$totalRows_esquema = mysqli_num_rows($esquema);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_estatusctto = "SELECT * FROM cat_ctostatus";
$estatusctto = mysqli_query($ResEquipos, $query_estatusctto) or die(mysqli_error($ResEquipos));
$row_estatusctto = mysqli_fetch_assoc($estatusctto);
$totalRows_estatusctto = mysqli_num_rows($estatusctto);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_tipoctto = "SELECT * FROM cat_tctto";
$tipoctto = mysqli_query($ResEquipos, $query_tipoctto) or die(mysqli_error($ResEquipos));
$row_tipoctto = mysqli_fetch_assoc($tipoctto);
$totalRows_tipoctto = mysqli_num_rows($tipoctto);

$rplazo = htmlentities($row_contratos['PLAZO'], ENT_COMPAT, 'utf-8');
if ($rplazo =="") {
	$rplazo = strtotime($row_contratos['F_FIN']) - strtotime($row_contratos['F_INICIO']) ;
	$rplazo = intval($rplazo/60/60/24)+1;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Editar Contrato</title>
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
  <script type='text/javascript' src='../quickselect/quicksilver.js'></script>
  <script type='text/javascript' src='../quickselect/jquery.quickselect.js'></script>
  <link rel="stylesheet" type="text/css" href="../quickselect/jquery.quickselect.css" />
<script language="JavaScript" type="text/JavaScript">
    $(document).ready(function(){
        $("#subdireccion").change(function(event){
            var id = $("#subdireccion").find(':selected').val();
            $("#activo").load('catalogos/activos.php?id='+id);
        });
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
               <li><a href="#">Editar Contrato</a> </li>
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
      <div class="grid_5">
        <h3>Editar Contrato</h3>
        <p>&nbsp;</p>
        <form method="post" name="form" action="<?php echo $editFormAction; ?>" id="form">
          <table align="center">
            <tr valign="baseline">
              <td nowrap align="right">Tipo de Contrato:</td>
              <td><select name="TIPOCTTO">
                <?php
do {  
?>
                <option value="<?php echo $row_tipoctto['ID']?>"<?php if (!(strcmp($row_tipoctto['ID'], htmlentities($row_contratos['T_CTTO'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipoctto['TIPOCTTO']?></option>
                <?php
} while ($row_tipoctto = mysqli_fetch_assoc($tipoctto));
  $rows = mysqli_num_rows($tipoctto);
  if($rows > 0) {
      mysqli_data_seek($tipoctto, 0);
	  $row_tipoctto = mysqli_fetch_assoc($tipoctto);
  }
?>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Fecha de Inicio</td>
              <td><input type="date" name="F_INICIO" value="<?php echo htmlentities($row_contratos['F_INICIO'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Fecha de Termino</td>
              <td><input type="date" name="F_FIN" value="<?php echo htmlentities($row_contratos['F_FIN'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Plazo:</td>
              <td><input type="number" name="PLAZO" value="<?php echo $rplazo ?>" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Tarifa:</td>
              <td><input type="text" name="TARIFA" value="<?php echo htmlentities($row_contratos['TARIFA'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Número de Contrato:</td>
              <td><input type="text" name="NO_CONTRATO" value="<?php echo htmlentities($row_contratos['NO_CONTRATO'], ENT_COMPAT, 'utf-8'); ?>" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Objeto del Contrato:</td>
              <td><textarea name="OBJETO_CTO" cols="32"><?php echo htmlentities($row_contratos['OBJETO_CTO'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Compañía:</td>
              <td><select name="COMPANIA">
                <?php 
do {  
?>
                <option value="<?php echo $row_companias['id_cia']?>" <?php if (!(strcmp($row_companias['id_cia'], htmlentities($row_contratos['COMPANIA'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_companias['NombreCia']?></option>
                <?php
} while ($row_companias = mysqli_fetch_assoc($companias));
?>
              </select></td>
            <tr>
            <tr valign="baseline">
              <td nowrap align="right">Esquema</td>
              <td><select name="ESQUEMA">
                <?php 
do {  
?>
                <option value="<?php echo $row_esquema['IDESQ']?>" <?php if (!(strcmp($row_esquema['IDESQ'], htmlentities($row_contratos['ESQUEMA'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_esquema['ESQUEMA']?></option>
                <?php
} while ($row_esquema = mysqli_fetch_assoc($esquema));
?>
              </select></td>
            <tr>
            <tr valign="baseline">
              <td nowrap align="right">Estatus</td>
              <td><select name="ESTATUS">
                <?php 
do {  
?>
                <option value="<?php echo $row_estatusctto['ID_STATUS']?>" <?php if (!(strcmp($row_estatusctto['ID_STATUS'], htmlentities($row_contratos['ESTATUS'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_estatusctto['ESTATUS']?></option>
                <?php
} while ($row_estatusctto = mysqli_fetch_assoc($estatusctto));
?>
              </select></td>
            <tr>
            <tr valign="baseline">
              <td nowrap align="right">&nbsp;</td>
              <td><input type="submit" value="Actualizar registro"></td>
            </tr>
          </table>
          <input type="hidden" name="ID_CTTO" value="<?php echo $row_contratos['ID_CTTO']; ?>">
          <input type="hidden" name="EQUIPOID" value="<?php echo htmlentities($row_contratos['EQUIPOID'], ENT_COMPAT, 'utf-8'); ?>">
          <input type="hidden" name="MM_update" value="form1">
          <input type="hidden" name="REGRESAR" value="<?php if(isset($_POST['REGRESAR'])){ echo $_POST['REGRESAR']; }?>">
          <input type="hidden" name="ID_CTTO" value="<?php echo $row_contratos['ID_CTTO']; ?>">
        </form>
        <p>&nbsp;</p>
<div class="grid_5"></div>
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
mysqli_free_result($contratos);

mysqli_free_result($companias);

mysqli_free_result($esquema);

mysqli_free_result($estatusctto);

mysqli_free_result($tipoctto);
?>

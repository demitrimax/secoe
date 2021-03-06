<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "admin";
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


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
	$query_ultimoregistro = "SELECT MAX(idEquipo) as lastID FROM cat_equipos;";
	  mysqli_select_db($ResEquipos, $database_ResEquipos);
	$ultimoregistro = mysqli_query($ResEquipos, $query_ultimoregistro) or die(mysqli_error($ResEquipos));
	$ultimoreg = mysqli_fetch_assoc($ultimoregistro);
	$yaelultimo = $ultimoreg['lastID'];
	$Siguiente = $yaelultimo + 1;

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO cat_equipos (Equipo, TEquipo, Caracteristicas, Cia, marcamalacate, HP, TA, CAPPERF, SECCSINDICAL, ESTATUS, EFICIENCIA, CLASE, ANO_CONSTR) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                       $_POST['Equipo'],
                       $_POST['TEquipo'], 
                       $_POST['Caracteristicas'],
                       $_POST['Cia'],
                       $_POST['marcamalacate'], 
                       $_POST['HP'],
                       $_POST['TA'],
                       $_POST['CAPPERF'],
                       $_POST['SECCSINDICAL'], 
                       $_POST['ESTATUS'], 
					   $_POST['EFICIENCIA'], 
					   $_POST['CLASE'], 
					   $_POST['ANOCONSTRUCCION']);

  mysqli_select_db($ResEquipos, $database_ResEquipos);
   echo $insertSQL;
  $Result1 = mysqli_query($ResEquipos, $insertSQL) or die(mysqli_error($ResEquipos));
	


  $insertGoTo = "detalle_equipo.php?idEquipo=".$Siguiente;
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_Equipos = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_Equipos = $_GET['idEquipo'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_Equipos = sprintf("SELECT * FROM cat_equipos WHERE idEquipo = %s", $colname_Equipos);
$Equipos = mysqli_query($ResEquipos, $query_Equipos) or die(mysqli_error($ResEquipos));
$row_Equipos = mysqli_fetch_assoc($Equipos);
$totalRows_Equipos = mysqli_num_rows($Equipos);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_catcias = "SELECT * FROM cat_cias ORDER BY NombreCia ASC";
$catcias = mysqli_query($ResEquipos, $query_catcias) or die(mysqli_error($ResEquipos));
$row_catcias = mysqli_fetch_assoc($catcias);
$totalRows_catcias = mysqli_num_rows($catcias);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_caracteristicas = "SELECT * FROM cat_equipocaracteristicas ORDER BY orden ASC";
$caracteristicas = mysqli_query($ResEquipos, $query_caracteristicas) or die(mysqli_error($ResEquipos));
$row_caracteristicas = mysqli_fetch_assoc($caracteristicas);
$totalRows_caracteristicas = mysqli_num_rows($caracteristicas);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_tipoequipo = "SELECT * FROM cat_tipoequipo";
$tipoequipo = mysqli_query($ResEquipos, $query_tipoequipo) or die(mysqli_error($ResEquipos));
$row_tipoequipo = mysqli_fetch_assoc($tipoequipo);
$totalRows_tipoequipo = mysqli_num_rows($tipoequipo);
//estatus del equipo
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_cat_estatus = "SELECT * FROM cat_estatus";
$cat_estatus = mysqli_query($ResEquipos, $query_cat_estatus) or die(mysqli_error($ResEquipos));
$row_cat_estatus = mysqli_fetch_assoc($cat_estatus);
$totalRows_cat_estatus = mysqli_num_rows($cat_estatus);
//clase del equipo: automatizado, convencional
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_cat_clase = "SELECT * FROM cat_clase";
$cat_clase = mysqli_query($ResEquipos, $query_cat_clase) or die(mysqli_error($ResEquipos));
$row_cat_clase = mysqli_fetch_assoc($cat_clase);
$totalRows_cat_clase = mysqli_num_rows($cat_clase);
//SUPONES QUE EL USUARIO SE LOGEO CORRECTAMENTE
$usuario = $_SESSION['MM_Username'];
$fechahora = date("Y-m-d H:i:s");
$pagina_actual = $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
$ipadress = $_SERVER['REMOTE_ADDR'];
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_log = "INSERT INTO registros (pagina, usuario, fechahora, ip) VALUES ('$pagina_actual', '$usuario', '$fechahora', '$ipadress')";
//echo $query_log;
$registros = mysqli_query($ResEquipos, $query_log) or die(mysqli_error($ResEquipos));

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Agregar Equipo</title>
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
               <li><a href="#">Agregar Equipo</a> <?php echo htmlentities($row_Equipos['Equipo'], ENT_COMPAT, 'utf-8'); ?></li>
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
        <h3>Agregar Equipo</h3>
			<div class="grid_5">

            	<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" id="form">
                <div class="contact-form-loader"></div>
                <fieldset>
                
                <input type="text" name="idEquipo" value="<?php echo $Siguiente; ?>" size="32" readonly hidden="true" />
                <label class="name"></label>
                
                <input type="text" name="Equipo" value="<?php echo htmlentities($row_Equipos['Equipo'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
                <label class="name">Equipo </label>
                
                <select name="TEquipo">
        <?php 
do {  
?>
        <option value="<?php echo $row_tipoequipo['idtequipo']?>" <?php if (!(strcmp($row_tipoequipo['idtequipo'], htmlentities($row_Equipos['TEquipo'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_tipoequipo['Tipo']?></option>
        <?php
} while ($row_tipoequipo = mysqli_fetch_assoc($tipoequipo));
?>
      </select>
      <label for="TEquipo"> Tipo de Equipo </label>
      
      <select name="Caracteristicas">
        <?php 
do {  
?>
        <option value="<?php echo $row_caracteristicas['IdCar']?>" <?php if (!(strcmp($row_caracteristicas['IdCar'], htmlentities($row_Equipos['Caracteristicas'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_caracteristicas['Caracteristicas']?></option>
        <?php
} while ($row_caracteristicas = mysqli_fetch_assoc($caracteristicas));
?>
      </select>
      <label for="Caracteristicas"> Característica principal</label>
      	
      		<select name="Cia">
        <?php 
do {  
?>
        <option value="<?php echo $row_catcias['id_cia']?>" <?php if (!(strcmp($row_catcias['id_cia'], htmlentities($row_Equipos['Cia'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_catcias['NombreCia']?></option>
        <?php
} while ($row_catcias = mysqli_fetch_assoc($catcias));
?>
      </select>
      <label for="Cia"> Compañía </label>
      <input type="text" name="marcamalacate" value="<?php echo htmlentities($row_Equipos['marcamalacate'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <label for="marcamalacate"> Marca Malacate</label>
      <input type="text" name="HP" value="<?php echo htmlentities($row_Equipos['HP'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <label for="HP"> HP (Caballaje) </label>
      <input type="text" name="TA" value="<?php echo htmlentities($row_Equipos['TA'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <label for="TA">Tirante de Agua</label>
      <input type="text" name="CAPPERF" value="<?php echo htmlentities($row_Equipos['CAPPERF'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <label for="CAPPERF"> Capacidad de Perforación </label>
      <input type="text" name="SECCSINDICAL" value="<?php echo htmlentities($row_Equipos['SECCSINDICAL'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <label for="SECCSINDICAL"> Sección Sindical </label>
      
      <select name="ESTATUS">
        <?php 
do {  
?>
        <option value="<?php echo $row_cat_estatus['ID_ESTATUS']?>" <?php if (!(strcmp($row_cat_estatus['ID_ESTATUS'], htmlentities($row_Equipos['ESTATUS'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_cat_estatus['ESTATUS']?></option>
        <?php
} while ($row_cat_estatus = mysqli_fetch_assoc($cat_estatus));
?>
</select>
<label for="ESTATUS"> Estatus </label>

      <input type="text" name="EFICIENCIA" value="<?php echo htmlentities($row_Equipos['EFICIENCIA'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <label for="EFICIENCIA"> Eficiencia</label>
      
      <select name="CLASE">
        <?php 
do {  
?>
        <option value="<?php echo $row_cat_clase['id_clase']?>" <?php if (!(strcmp($row_cat_clase['id_clase'], htmlentities($row_Equipos['CLASE'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_cat_clase['clase']?></option>
        <?php
} while ($row_cat_clase = mysqli_fetch_assoc($cat_clase));
?>
</select>
<label for="CLASE"> Clase </label>

      <input type="text" name="ANOCONSTRUCCION" value="<?php echo htmlentities($row_Equipos['ANO_CONSTR'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <label for="ANOCONSTRUCCION"> Año de Construcción: </label>
      
                </fieldset>
                <input type="submit" value="Agregar" />
                <input type="hidden" name="MM_insert" value="form" />
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
  </div>  
</footer>
<a href="#" id="toTop" class="fa fa-chevron-up"></a>
</body>
</html>
<?php
mysqli_free_result($Equipos);

mysqli_free_result($catcias);
?>

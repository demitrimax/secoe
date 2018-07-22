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
?>
<?php require_once('../Connections/ResEquipos.php'); ?>

<?php
//SUPONES QUE EL USUARIO SE LOGEO CORRECTAMENTE
 $usuario = $_SESSION['MM_Username'];
 $fechahora = date("Y-m-d H:i:s");
 $pagina_actual = $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
 $ipadress = $_SERVER['REMOTE_ADDR'];
 mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_log = "INSERT INTO registros (pagina, usuario, fechahora, ip) VALUES ('$pagina_actual', '$usuario', '$fechahora', '$ipadress')";
//echo $query_log;
$registros = mysqli_query($ResEquipos, $query_log) or die(mysql_error($ResEquipos));

 

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

<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE cat_equipos SET Equipo='%s', TEquipo='%s', Caracteristicas='%s', Cia='%s', marcamalacate='%s', HP='%s', TA='%s', CAPPERF='%s', SECCSINDICAL='%s', ESTATUS='%s', EFICIENCIA='%s', CLASE='%s', ANO_CONSTR='%s', CLVE_EQUIPO='%s', SUBDIR='%s', ACTIVO='%s', Equ_Corto='%s'  WHERE idEquipo=%s",
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
					   $_POST['ANOCONSTRUCCION'], 
					   $_POST['CLVE_EQUIPO'],
					   $_POST['subdireccion'],
					   $_POST['activo'], 
					   $_POST['EqCorto'], 
					   $_POST['idEquipo']
					   );
//	echo $updateSQL;
  mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysqli_query($ResEquipos, $updateSQL) or die(mysqli_error($ResEquipos));

  $updateGoTo = "detalle_equipo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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
//SUBDIRECCION A LA QUE PERTECE EL EQUIPO
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_cat_subdir = "SELECT * FROM cat_subdir";
$cat_subdir = mysqli_query($ResEquipos, $query_cat_subdir) or die(mysqli_error($ResEquipos));
$row_cat_subdir = mysqli_fetch_assoc($cat_subdir);
$totalRows_cat_subdir = mysqli_num_rows($cat_subdir);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_cat_activos = "SELECT * FROM cat_activos ORDER BY ACTIVO ASC";
$cat_activos = mysqli_query($ResEquipos, $query_cat_activos) or die(mysqli_error($ResEquipos));
$row_cat_activos = mysqli_fetch_assoc($cat_activos);
$totalRows_cat_activos = mysqli_num_rows($cat_activos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Editar Equipo</title>
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
  <script type='text/javascript' src='quickselect/quicksilver.js'></script>
  <script type='text/javascript' src='quickselect/jquery.quickselect.js'></script>
  <link rel="stylesheet" type="text/css" href="quickselect/jquery.quickselect.css" />
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
               <li><a href="#">Editar Equipo</a> <?php echo htmlentities($row_Equipos['Equipo'], ENT_COMPAT, 'utf-8'); ?></li>
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
        <h3>Editar Equipo</h3>
			<div class="grid_5">

            	<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" id="form">
                <div class="contact-form-loader"></div>
                <fieldset>
                
                <input type="hidden" name="idEquipo" value="<?php echo $row_Equipos['idEquipo']; ?>" size="32"/>
                <input type="text" name="CLVE_EQUIPO" value="<?php echo $row_Equipos['CLVE_EQUIPO']; ?>" size="32" />
                <label class="name">No. Equipo </label>
                
                <input type="text" name="Equipo" value="<?php echo htmlentities($row_Equipos['Equipo'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
                <label class="name">Nombre del Equipo </label>
                
                <input type="text" name="EqCorto" value="<?php echo htmlentities($row_Equipos['Equ_corto'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
                <label class="name">Nombre corto del Equipo </label>
                
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
      	
      		<select name="Cia" id="comboboxCia">
        <?php 
do {  
?>
        <option value="<?php echo $row_catcias['id_cia']?>" <?php if (!(strcmp($row_catcias['id_cia'], htmlentities($row_Equipos['Cia'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_catcias['NombreCia']?></option>
        <?php
} while ($row_catcias = mysqli_fetch_assoc($catcias));
?>
      </select>
       <script type='text/javascript'>$(function(){ $('#comboboxCia').quickselect(); });</script>
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
        <option value="<?php echo $row_cat_estatus['ID_ESTATUS']?>" <?php if (!(strcmp($row_cat_estatus['ID_ESTATUS'], utf8_encode($row_Equipos['ESTATUS'])))) {echo "SELECTED";} ?>><?php echo utf8_encode($row_cat_estatus['ESTATUS'])?></option>
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
        <option value="<?php echo $row_cat_clase['id_clase']?>" <?php if (!(strcmp($row_cat_clase['id_clase'], htmlentities($row_Equipos['CLASE'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo utf8_encode($row_cat_clase['clase'])?></option>
        <?php
} while ($row_cat_clase = mysqli_fetch_assoc($cat_clase));
?>
</select>
<label for="CLASE"> Clase </label>

<select name="subdireccion" id="subdireccion">
  <?php
do {  
?>
  <option value="<?php echo $row_cat_subdir['id_subdir']?>"<?php if (!(strcmp($row_cat_subdir['id_subdir'], htmlentities($row_Equipos['SUBDIR'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_cat_subdir['SUBDIRECCION']?></option>
  <?php
} while ($row_cat_subdir = mysqli_fetch_assoc($cat_subdir));
  $rows = mysqli_num_rows($cat_subdir);
  if($rows > 0) {
      mysqli_data_seek($cat_subdir, 0);
	  $row_cat_subdir = mysqli_fetch_assoc($cat_subdir);
  }
?>
</select>
<label for="subdir"> SUBDIRECCION </label>

<select name="activo" id="activo">
  <?php
do {  
?>
  <option value="<?php echo $row_cat_activos['id_activo']?>"<?php if (!(strcmp($row_cat_activos['id_activo'], htmlentities($row_Equipos['ACTIVO'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_cat_activos['ACTIVO']?></option>
  <?php
} while ($row_cat_activos = mysqli_fetch_assoc($cat_activos));
  $rows = mysqli_num_rows($cat_activos);
  if($rows > 0) {
      mysqli_data_seek($cat_activos, 0);
	  $row_cat_activos = mysqli_fetch_assoc($cat_activos);
  }
?>
</select>
<label for="activo"> ACTIVO </label>

      <input type="text" name="ANOCONSTRUCCION" value="<?php echo htmlentities($row_Equipos['ANO_CONSTR'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <label for="ANOCONSTRUCCION"> Año de Construcción: </label>
      
                </fieldset>
                <input type="submit" value="Actualizar" />
                <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="idEquipo" value="<?php echo $row_Equipos['idEquipo']; ?>" />
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

mysqli_free_result($cat_subdir);

mysqli_free_result($cat_activos);
?>

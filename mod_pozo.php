<?php
date_default_timezone_set('America/Monterrey');
?>
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

$MM_restrictGoTo = "../index.php";
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

//REGISTRAS LA VISITA A ESTA PAGINA EN LA BASE DE DATOS
//SUPONES QUE EL USUARIO SE LOGEO CORRECTAMENTE
 $usuario = $_SESSION['MM_Username'];
 $fechahora = date("Y-m-d H:i:s");
 $pagina_actual = $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
 $ipadress = $_SERVER['REMOTE_ADDR'];
 mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_log = "INSERT INTO registros (pagina, usuario, fechahora, ip) VALUES ('$pagina_actual', '$usuario', '$fechahora', '$ipadress')";
//echo $query_log;
$registros = mysqli_query($ResEquipos, $query_log) or die(mysql_error($ResEquipos));

 
//TERMINA EL REGISTRO EN LA BD

$colname_idpozo = "-1";
if (isset($_GET['idpozo'])) {
  $colname_idpozo = $_GET['idpozo'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_pozodetalle = sprintf("SELECT * FROM v_cat_pozos WHERE idpozo = %s", $colname_idpozo);
$pozodetalle = mysqli_query($ResEquipos, $query_pozodetalle) or die(mysqli_error($ResEquipos));
$row_pozodetalle = mysqli_fetch_assoc($pozodetalle);
$totalRows_pozodetalle = mysqli_num_rows($pozodetalle);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_interven = sprintf("SELECT
pot.programoficial,
pot.intervencion,
pot.fec_ini,
pot.fec_fin,
pot.pozo,
cat_equipos.Equ_corto,
cat_equipos.CLVE_EQUIPO,
pot.idequipo
FROM
pot
LEFT JOIN cat_equipos ON pot.idequipo = cat_equipos.idEquipo
WHERE
pot.id_cat_pozos = %s", $colname_idpozo);
$intervenciones = mysqli_query($ResEquipos, $query_interven) or die(mysqli_error($ResEquipos));
$row_intervenciones = mysqli_fetch_assoc($intervenciones);
$totalRows_intervenciones = mysqli_num_rows($intervenciones);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_activos = "SELECT * FROM cat_activos WHERE visible = 1 ORDER BY subdir asc";
$activos = mysqli_query($ResEquipos, $query_activos) or die(mysqli_error($ResEquipos));
$row_activos = mysqli_fetch_assoc($activos);
$totalRows_activos = mysqli_num_rows($activos);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_uops = "SELECT * FROM cat_uop WHERE visible = 1 ORDER BY UnidadOperativa asc";
$uops = mysqli_query($ResEquipos, $query_uops) or die(mysqli_error($ResEquipos));
$row_uops = mysqli_fetch_assoc($uops);
$totalRows_uops = mysqli_num_rows($uops);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE cat_pozos SET nombrepozo='%s', campo='%s', numero='%s', tipo='%s', modalidad='%s', tirante_agua='%s', prof_ver='%s', prof_des='%s', profundidad='%s', activo='%s', uop='%s', comentario='%s' WHERE idEquipo=%s ",
                       $_POST['NombrePozo'],
                       $_POST['Campo'],
                       $_POST['Numero'],
                       $_POST['Tipo'],
                       $_POST['modalidad'],
                       $_POST['tiranteagua'],
                       $_POST['ProfVertical '],
                       $_POST['ProfDesarrollada'],
                       $_POST['Profundidad'],
                       $_POST['activo'],
					   $_POST['uoperativa'],
					   $_POST['idpozo']
					   );

  mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysqli_query($ResEquipos, $updateSQL) or die(mysqli_error($ResEquipos));

  $updateGoTo = "detalle_equipo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Editar Pozo <?php echo utf8_encode($row_pozodetalle['nombrepozo']); ?></title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/form.css">
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
               <li>Editar detalles del pozo <?php echo utf8_encode($row_pozodetalle['nombrepozo']); ?></li>
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
        <h3>Pozo: <?php echo utf8_encode($row_pozodetalle['nombrepozo']); ?></h3>
		<div>
        <div class="container">

    <div class="grid_12">
			
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <input type="text" name="NombrePozo" size="32" value="<?php echo $row_pozodetalle['nombrepozo'];?>"/>
   	<label for="NombrePozo">Nombre Pozo:</label> 
    <input type="text" name="Campo"  size="32" />
   	<label for="Campo">Campo:</label> 
    <input type="text" name="Numero"  size="32" />
    <label for="Numero">Numero y Letra:</label> 
    <select name="Tipo">
      <option value="T">Terrestre</option>
      <option value="M">Marino</option>
    </select>
    <label for="Tipo">Tipo:</label>

    <input type="text" name="modalidad"/>
    <label for="modalidad">Modalidad:</label> 

    <input type="text" name="tiranteagua"/>
    <label for="tiranteagua">Tirante de Agua:</label> 

    <input type="text" name="ProfVertical"/>
    <label for="ProfVertical">Profundidad Vertical:</label> 
    
    <input type="text" name="ProfDesarrollada"/>
    <label for="ProfDesarrollada">Profundidad Desarrollada:</label> 

    <input type="text" name="Profundidad"/>
    <label for="Profundidad">Profundidad:</label> 

<select name="activo" id="activo">
  <?php
do {  
?>
  <option value="<?php echo $row_activos['id_activo']?>" ><?php echo utf8_encode($row_activos['ACTIVO'])?></option>
  <?php
} while ($row_activos = mysqli_fetch_assoc($activos));
  $rows = mysqli_num_rows($activos);
  if($rows > 0) {
      mysqli_data_seek($activos, 0);
	  $row_activos = mysqli_fetch_assoc($activos);
  }
?>
</select>
<label for="activo">Activo</label>

<select name="uoperativa" id="uoperativa">
  <?php
do {  
?>
  <option value="<?php echo $row_uops['id']?>" <?php if ($row_uops['refActivo']  = $row_activos['id_activo']) {echo "selected=\"selected\"";} ?>><?php echo utf8_encode($row_uops['UnidadOperativa'])?></option>
  <?php
} while ($row_uops = mysqli_fetch_assoc($uops));
  $rows = mysqli_num_rows($uops);
  if($rows > 0) {
      mysqli_data_seek($uops, 0);
	  $row_uops = mysqli_fetch_assoc($uops);
  }
?>
</select>
<label for="activo">Unidad Operativa</label>
  
<input type="submit" value="Agregar Pozo" />
<input type="hidden" name="MM_update" value="form1" />
<input type="hidden" name="idpozo" value="<?php echo $colname_idpozo; ?>" />

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


<?php require_once('../Connections/ResEquipos.php'); 
date_default_timezone_set('America/Monterrey');
$Cerrar = 0;
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form")) {
  $updateSQL = sprintf("UPDATE eqcomentarios SET equipo=%s, comentario=%s, fec_coment=%s, estatus_operativo=%s, activo=%s WHERE id_com=%s",
                       GetSQLValueString($_POST['equipo'], "int"),
                       GetSQLValueString($_POST['comentario'], "text"),
                       GetSQLValueString($_POST['fec_coment'], "date"),
                       GetSQLValueString($_POST['estatusop'], "int"),
                       GetSQLValueString($_POST['activos'], "int"),
                       GetSQLValueString($_POST['id_com'], "int"));

  mysql_select_db($database_ResEquipos, $ResEquipos);
  $Result1 = mysql_query($updateSQL, $ResEquipos) or die(mysql_error());
  
  $insertGoTo = "detalle_equipo.php?idEquipo=" . $_GET['idEquipo'] . "";
  $Cerrar = 1;
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  
  //header(sprintf("Location: %s", $insertGoTo));
}

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
$query_StatusOp = "SELECT * FROM cat_estatusop";
$StatusOp = mysql_query($query_StatusOp, $ResEquipos) or die(mysql_error());
$row_StatusOp = mysql_fetch_assoc($StatusOp);
$totalRows_StatusOp = mysql_num_rows($StatusOp);

mysql_select_db($database_ResEquipos, $ResEquipos);
$query_activos = "SELECT * FROM cat_activos WHERE visible = 1 ORDER BY subdir asc";
$activos = mysql_query($query_activos, $ResEquipos) or die(mysql_error());
$row_activos = mysql_fetch_assoc($activos);
$totalRows_activos = mysql_num_rows($activos);

$colname_comentario = "-1";
if (isset($_GET['id_com'])) {
  $colname_comentario = $_GET['id_com'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_comentario = sprintf("SELECT * FROM eqcomentarios WHERE id_com = %s", GetSQLValueString($colname_comentario, "int"));
$comentario = mysql_query($query_comentario, $ResEquipos) or die(mysql_error());
$row_comentario = mysql_fetch_assoc($comentario);
$totalRows_comentario = mysql_num_rows($comentario);

?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Modificar Comentario</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/form.css">
<script src="js/jquery.js"></script>
<script src="js/jquery-migrate-1.1.1.js"></script>
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
	<script src="ckeditor/ckeditor.js"></script>
    <script type='text/javascript' src='quickselect/quicksilver.js'></script>
  	<script type='text/javascript' src='quickselect/jquery.quickselect.js'></script>
  <link rel="stylesheet" type="text/css" href="quickselect/jquery.quickselect.css" />

  <script type="text/javascript">
function CerrarVentanaLightbox() {
//* alert("Hola")
<?php	if ($Cerrar == 1) {
	echo "$.lightbox().close();";
	} ?>
}
</script>


</head>
<body onload="CerrarVentanaLigtbox()">
<!--=====================
          Content
======================-->
<section id="content">
  <div class="container">
    <div class="row">
      <div class="grid_14">
        <h3><a href="#" onclick="CerrarVentanaLightbox()">Modificar Comentarios</a></h3>
        
        <div class="grid_14">
        <form action="<?php echo $editFormAction; ?>&idEquipo=<?php echo $row_comentario['equipo']; ?>" name="form" method="POST" id="form">

        <input name="equipo" type="hidden" value="<?php echo $row_comentario['equipo']; ?>" size="32" readonly />
                

                  <textarea name="comentario" rows="10" cols="80" required><?php echo $row_comentario['comentario']; ?></textarea>
                 <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'comentario' );
            </script>
        <label for="comentario">Comentario:</label>

<input name="fec_coment" type="text" value="<?php echo date("Y-m-d H:i:s"); ?>" size="32" readonly />
<label for="fec_coment">Fecha: </label>
<select name="estatusop" contenteditable="true" contextmenu="Activo que pertenece">
  <?php
do {  
?>
  <option value="<?php echo $row_StatusOp['id_statusop']?>"><?php echo utf8_encode($row_StatusOp['estatusop'])?></option>
  <?php
} while ($row_StatusOp = mysql_fetch_assoc($StatusOp));
  $rows = mysql_num_rows($StatusOp);
  if($rows > 0) {
      mysql_data_seek($StatusOp, 0);
	  $row_StatusOp = mysql_fetch_assoc($StatusOp);
  }
?>
</select>
<label for="estatusop">Estatus Operativo</label>
<select name="activos" id="Activos">
  <?php
do {  
?>
  <option value="<?php echo $row_activos['id_activo']?>" <?php if (!(strcmp($row_activos['id_activo'], htmlentities($row_comentario['activo'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo utf8_encode($row_activos['ACTIVO'])?></option>
  <?php
} while ($row_activos = mysql_fetch_assoc($activos));
  $rows = mysql_num_rows($activos);
  if($rows > 0) {
      mysql_data_seek($activos, 0);
	  $row_activos = mysql_fetch_assoc($activos);
  }
?>
</select>
<script type='text/javascript'>$(function(){ $('#Activos').quickselect(); });</script>
<label for="activo">Activo</label>
<input type="submit" value="Modificar Comentario" />
 <input type="hidden" name="id_com" value="<?php echo $row_comentario['id_com']; ?>" />
 <input type="hidden" name="MM_update" value="form">
        </form>
  		
          
        </div>
      </div>
    </div>
  </div>
  
</section>

</body>
</html>
<?php

mysql_free_result($activos);

mysql_free_result($comentario);
?>
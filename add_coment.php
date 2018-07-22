<?php require_once('../Connections/ResEquipos.php'); 
date_default_timezone_set('America/Monterrey');
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO eqcomentarios (id_com, equipo, comentario, fec_coment, estatus_operativo, activo, id_usuario, idpozo, nombrepozo, uop) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                      $_POST['id_com'],
                       $_POST['equipo'], 
                       $_POST['comentario'], 
                       $_POST['fec_coment'], 
					   $_POST['estatusop'], 
					   $_POST['activos'], 
					   $_SESSION['iduser'],
					   $_POST['idpozo'],
					   $_POST['nompozo'],
					   $_POST['uoperativas']
					   );

  mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysqli_query($ResEquipos, $insertSQL) or die(mysqli_error($ResEquipos));

  $insertGoTo = "detalle_equipo.php?close=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


$maxRows_comentarios = 2;
$pageNum_comentarios = 0;
if (isset($_GET['pageNum_comentarios'])) {
  $pageNum_comentarios = $_GET['pageNum_comentarios'];
}
$startRow_comentarios = $pageNum_comentarios * $maxRows_comentarios;

$colname_comentarios = "-1";
$colname_Equipos = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_comentarios = $_GET['idEquipo'];
  $colname_Equipos = $_GET['idEquipo'];
}

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_comentarios = sprintf("SELECT * FROM eqcomentarios WHERE equipo = %s ORDER BY fec_coment ASC", $colname_comentarios);
$comentarios = mysqli_query($ResEquipos, $query_comentarios) or die(mysqli_error($ResEquipos));
$row_comentarios = mysqli_fetch_assoc($comentarios);
$totalRows_comentarios = mysqli_num_rows($comentarios);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_Equipos = sprintf("SELECT * FROM cat_equipos WHERE idEquipo = %s", $colname_Equipos);
$Equipos = mysqli_query($ResEquipos, $query_Equipos) or die(mysqli_error($ResEquipos));
$row_Equipos = mysqli_fetch_assoc($Equipos);
$totalRows_Equipos = mysqli_num_rows($Equipos);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_StatusOp = "SELECT * FROM cat_estatusop";
$StatusOp = mysqli_query($ResEquipos, $query_StatusOp) or die(mysqli_error($ResEquipos));
$row_StatusOp = mysqli_fetch_assoc($StatusOp);
$totalRows_StatusOp = mysqli_num_rows($StatusOp);

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



?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Agregar comentario</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/form.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery.js"></script>
<!-- <script src="js/jquery-migrate-1.4.1.js"></script> -->
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/script.js"></script> 
<script src="js/superfish.js"></script>
<script src="js/jquery.equalheights.js"></script>
<script src="js/jquery.mobilemenu.js"></script>
<script src="js/tmStickUp.js"></script>
<script src="js/jquery.ui.totop.js"></script>
<script src="js/jquery-ui.js"></script>

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
  <style>
  .ui-autocomplete-loading {
    background: white url("images/ajax-loader.gif") right center no-repeat;
  }
  </style>
  <script>
  $(function() {
   
    // configuramos el control para realizar la busqueda de pozos
    $("#pozo").autocomplete({
        source: "catalogos/buscar_pozo.php",
        minLength: 2, /* basta con escribir dos letras */
         
		select: function( event, ui ) {
        	$('input[name=nompozo]').val(ui.item.label);
			$('input[name=idpozo]').val(ui.item.value);
      	}
	  
    })
                                                                   
});
</script>


</head>
<body>

        <h3>Agregar comentario <?php echo $row_Equipos['Equipo']; ?></h3>
        
        <div class="grid_14">
        <form action="<?php echo $editFormAction; ?>" method="post" id="form">

        <input name="equipo" type="hidden" value="<?php echo $_GET['idEquipo']; ?>" size="32" readonly />
                

                  <textarea name="comentario" rows="10" cols="80" required></textarea>
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
} while ($row_StatusOp = mysqli_fetch_assoc($StatusOp));
  $rows = mysqli_num_rows($StatusOp);
  if($rows > 0) {
      mysqli_data_seek($StatusOp, 0);
	  $row_StatusOp = mysqli_fetch_assoc($StatusOp);
  }
?>
</select>
<label for="estatusop">Estatus Operativo</label>
<select name="activos" id="Activos">
  <?php
do {  
?>
  <option value="<?php echo $row_activos['id_activo']?>" <?php if (!(strcmp($row_activos['id_activo'], htmlentities($row_Equipos['ACTIVO'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo utf8_encode($row_activos['ACTIVO'])?></option>
  <?php
} while ($row_activos = mysqli_fetch_assoc($activos));
  $rows = mysqli_num_rows($activos);
  if($rows > 0) {
      mysqli_data_seek($activos, 0);
	  $row_activos = mysqli_fetch_assoc($activos);
  }
?>
</select>
<script type='text/javascript'>$(function(){ $('#Activos').quickselect(); });</script>
<label for="activo">Activo</label>
<select name="uoperativas" id="UOperativas">
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
<script type='text/javascript'>$(function(){ $('#UOperativas').quickselect(); });</script>
<label for="activo">Unidad Operativa</label>

<input id="pozo"/>
<label for="pozo">Pozo</label>

Nota: si el nombre del pozo no aparece en la lista anterior, porfavor escribalo en el siguiente cuadro (Texto del pozo).
<input name="nompozo"/>
<input name="idpozo" type="hidden"/>
<label for="nompozo">Texto Pozo</label>


<input type="submit" value="Agregar Comentario" />
 <input type="hidden" name="id_com" value="" />
            <input type="hidden" name="MM_insert" value="form1" />
          </form>
  		
          
</body>
</html>
<?php
mysqli_free_result($comentarios);

mysqli_free_result($activos);

mysqli_free_result($uops);
?>
<?php require_once('../../Connections/ResEquipos.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO contrato (F_INICIO, F_FIN, PLAZO, TARIFA, NO_CONTRATO, OBJETO_CTO, COMPANIA, EQUIPOID, ESQUEMA, ESTATUS, T_CTTO) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['F_INICIO'], "date"),
                       GetSQLValueString($_POST['F_FIN'], "date"),
                       GetSQLValueString($_POST['PLAZO'], "int"),
                       GetSQLValueString($_POST['TARIFA'], "int"),
                       GetSQLValueString($_POST['NO_CONTRATO'], "text"),
                       GetSQLValueString($_POST['OBJETO_CTO'], "text"),
                       GetSQLValueString($_POST['COMPANIA'], "int"),
                       GetSQLValueString($_POST['EQUIPOID'], "int"),
                       GetSQLValueString($_POST['ESQUEMA'], "int"),
                       GetSQLValueString($_POST['ESTATUS'], "int"),
					   GetSQLValueString($_POST['TIPOCTTO'], "int"));

  mysql_select_db($database_ResEquipos, $ResEquipos);
  $Result1 = mysql_query($insertSQL, $ResEquipos) or die(mysql_error());

  $insertGoTo = "../detalle_equipo.php?idEquipo=" . $_POST['EQUIPOID'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_ResEquipos, $ResEquipos);
$query_companias = "SELECT * FROM cat_cias";
$companias = mysql_query($query_companias, $ResEquipos) or die(mysql_error());
$row_companias = mysql_fetch_assoc($companias);
$totalRows_companias = mysql_num_rows($companias);

mysql_select_db($database_ResEquipos, $ResEquipos);
$query_esquema = "SELECT * FROM cat_esquemacto";
$esquema = mysql_query($query_esquema, $ResEquipos) or die(mysql_error());
$row_esquema = mysql_fetch_assoc($esquema);
$totalRows_esquema = mysql_num_rows($esquema);

mysql_select_db($database_ResEquipos, $ResEquipos);
$query_estatusctto = "SELECT * FROM cat_ctostatus";
$estatusctto = mysql_query($query_estatusctto, $ResEquipos) or die(mysql_error());
$row_estatusctto = mysql_fetch_assoc($estatusctto);
$totalRows_estatusctto = mysql_num_rows($estatusctto);

$colname_equipos = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_equipos = $_GET['idEquipo'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_equipos = sprintf("SELECT * FROM baseequipos WHERE idEquipo = %s", GetSQLValueString($colname_equipos, "int"));
$equipos = mysql_query($query_equipos, $ResEquipos) or die(mysql_error());
$row_equipos = mysql_fetch_assoc($equipos);
$totalRows_equipos = mysql_num_rows($equipos);

mysql_select_db($database_ResEquipos, $ResEquipos);
$query_tipoctto = "SELECT * FROM cat_tctto";
$tipoctto = mysql_query($query_tipoctto, $ResEquipos) or die(mysql_error());
$row_tipoctto = mysql_fetch_assoc($tipoctto);
$totalRows_tipoctto = mysql_num_rows($tipoctto);

$no_ctto = "";
if (isset($_GET['no_ctto'])) {
  $no_ctto = $_GET['no_ctto'];
}
$idcia2 = "";
if (isset($_GET['idcia'])) {
  $idcia2 = $_GET['idcia'];
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Agregar Contrato</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="../images/favicon.ico">
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form.css">
<script src="../js/jquery.js"></script>
<script src="../js/jquery-migrate-1.1.1.js"></script>
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
               <li><a href="#">Agregar Contrato</a> </li>
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
      <div class="grid_5">
        <h3>Agregar Contrato para el Equipo <?php echo $row_equipos['Equipo']; ?></h3>
        <p>&nbsp;</p>
        <form action="<?php echo $editFormAction; ?>" method="POST" name="form" id="form">
          <table align="center">
            <tr valign="baseline">
              <td nowrap align="right">Equipo</td>
              <td><input type="text" name="ID_EQUIPO" value="<?php echo $_GET['idEquipo']; ?>" size="32" readonly></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Tipo</td>
              <td><select name="TIPOCTTO">
                <?php
do {  
?>
                <option value="<?php echo $row_tipoctto['ID']?>"><?php echo $row_tipoctto['TIPOCTTO']?></option>
                <?php
} while ($row_tipoctto = mysql_fetch_assoc($tipoctto));
  $rows = mysql_num_rows($tipoctto);
  if($rows > 0) {
      mysql_data_seek($tipoctto, 0);
	  $row_tipoctto = mysql_fetch_assoc($tipoctto);
  }
?>
              </select></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Fecha de Inicio</td>
              <td><input type="date" name="F_INICIO" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Fecha de Termino</td>
              <td><input type="date" name="F_FIN" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Plazo:</td>
              <td><input type="number" name="PLAZO" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Tarifa:</td>
              <td><input type="number" name="TARIFA" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Número de Contrato:</td>
              <td><input type="text" name="NO_CONTRATO" size="32" value="<?php echo $no_ctto; ?>"></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Objeto del Contrato:</td>
              <td><textarea name="OBJETO_CTO" cols="32"></textarea></td>
            </tr>
            <tr valign="baseline">
              <td nowrap align="right">Compañía:</td>
              <td><select name="COMPANIA">
                <?php
do {  
?>
                <option value="<?php echo $row_companias['id_cia']?>"<?php if (!(strcmp($row_companias['id_cia'], htmlentities($row_equipos['Cia'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_companias['NombreCia']?></option>
                <?php
} while ($row_companias = mysql_fetch_assoc($companias));
  $rows = mysql_num_rows($companias);
  if($rows > 0) {
      mysql_data_seek($companias, 0);
	  $row_companias = mysql_fetch_assoc($companias);
  }
?>
              </select></td>
            <tr>
            <tr valign="baseline">
              <td nowrap align="right">Esquema</td>
              <td><select name="ESQUEMA">
                <?php
do {  
?>
                <option value="<?php echo $row_esquema['IDESQ']?>"><?php echo $row_esquema['ESQUEMA']?></option>
                <?php
} while ($row_esquema = mysql_fetch_assoc($esquema));
  $rows = mysql_num_rows($esquema);
  if($rows > 0) {
      mysql_data_seek($esquema, 0);
	  $row_esquema = mysql_fetch_assoc($esquema);
  }
?>
              </select></td>
            <tr>
            <tr valign="baseline">
              <td nowrap align="right">Estatus</td>
              <td><select name="ESTATUS">
                <?php
do {  
?>
                <option value="<?php echo $row_estatusctto['ID_STATUS']?>"><?php echo $row_estatusctto['ESTATUS']?></option>
                <?php
} while ($row_estatusctto = mysql_fetch_assoc($estatusctto));
  $rows = mysql_num_rows($estatusctto);
  if($rows > 0) {
      mysql_data_seek($estatusctto, 0);
	  $row_estatusctto = mysql_fetch_assoc($estatusctto);
  }
?>
              </select></td>
            <tr>
            <tr valign="baseline">
              <td nowrap align="right">&nbsp;</td>
              <td><input type="submit" value="Agregar Contrato"></td>
            </tr>
          </table>
          <input type="hidden" name="EQUIPOID" value="<?php echo $colname_equipos; ?>">
          <input type="hidden" name="MM_insert" value="form">
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
mysql_free_result($companias);

mysql_free_result($esquema);

mysql_free_result($estatusctto);

mysql_free_result($equipos);
?>

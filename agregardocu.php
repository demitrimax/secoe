<?php
date_default_timezone_set('America/Monterrey');
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "auditor,admin";
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
<?php require_once('Connections/ResEquipos.php'); ?>
<?php


$colname_Recordset1 = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_Recordset1 = $_GET['idEquipo'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_Recordset1 = sprintf("SELECT * FROM detalleequipo WHERE idEquipo = %s", $colname_Recordset1);
$Recordset1 = mysqli_query($ResEquipos, $query_Recordset1) or die(mysql_error($ResEquipos));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
error_reporting(E_ALL); // or E_STRICT
ini_set("display_errors",1);
ini_set("memory_limit","1024M");

$archivo = $_FILES['archivo']['tmp_name']; //nombre temp en el servidor este se utiliza para guardarlo en la base de datos
$tamano = $_FILES['archivo']['size'];
$tipo = $_FILES['archivo']['type'];

		$uploaddir = 'documentos/';
 		$nombre  = $_FILES["archivo"]["name"];
		$prefijo = substr(md5(uniqid(rand())),0,6); //esto es para evitar duplicidad en los archivos



//print_r($_FILES);
if ($archivo != 'none') {
	$id_doc = $_POST['id_doc'];
	$equipo_asociado = $_POST['equipo_asociado'];
	$fecha = $_POST['fecha'];
	$descripcion = $_POST['descripcion'];
	$nom_archivo = utf8_decode($_FILES['archivo']['name']);
	
	$destino =  $uploaddir.$prefijo."_".utf8_decode($nombre);
	$url_file = $destino;
	/* ya no se guardara en la base de datos
	$fp = fopen($archivo, 'rb')	;
	$contenido = fread($fp, $tamano);
	$contenido = addslashes($contenido);
	fclose($fp);
	*/


/*  $insertSQL = sprintf("INSERT INTO documentos (id_doc, equipo_asociado, fecha, descripcion, nom_archivo, archivo, tipo, tamano) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_doc'], "int"),
                       GetSQLValueString($_POST['equipo_asociado'], "int"),
                       GetSQLValueString($_POST['fecha'], "date"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_FILES['archivo']['name'], "text"),
					   GetSQLValueString(file_get_contents($_FILES['archivo']['tmp_name']),"text"),
					   GetSQLValueString($tipo, "text"), 
					   GetSQLValueString($tamano, "int"));
*/
				
				$insertSQL = "INSERT INTO documentos (equipo_asociado, fecha, descripcion, nom_archivo, tipo, tamano, url_file, estatus) VALUES ('$equipo_asociado','$fecha','$descripcion','$nom_archivo','$tipo','$tamano','$url_file', '1')";	   
	//echo $insertSQL;
   mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysqli_query($insertSQL, $ResEquipos) or die(mysqli_error($ResEquipos));
 
 if(mysqli_affected_rows($ResEquipos) > 0)
  					{
						$resultado = "Se ha subido correctamente";
						
						if (move_uploaded_file($_FILES['archivo']['tmp_name'],$destino)) 
							{
        						$status = "Archivo subido: <b>".$archivo."</b>";
        						header("Location: detalle_equipo.php?idEquipo=".$equipo_asociado."&#Documentos");
							} 
						else 
							{
            				$status = "Error al subir el archivo";
        					}
		
					}
				else $resultado = "No se ha podido guardar el archivo en el servidor";
			
			}
			else
		$resultado = "No se ha podido subir el archivo";
	}
  

  

?>
<!DOCTYPE html>
<html lang="eS">
<head>
<title>Agregar Documentos</title>
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
          <a href="index.php">
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
               <li><?php echo $row_Recordset1['Equipo']; ?></li>
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
        <h3>Agregar Documentos para el Equipo <?php echo $row_Recordset1['Equipo']; ?></h3>
        <div class="blog">
        
          <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" id="form">
            <table align="center">
              <tr valign="baseline">
                <td align="right">Equipo:</td>
                <td><input type="hidden" name="equipo_asociado" value="<?php echo $_GET['idEquipo']; ?>" size="32" readonly />
                <input type="text" name="equipo" value="<?php echo $row_Recordset1['Equipo']; ?>" size="32" readonly /></td>
              </tr>
              <tr valign="baseline">
                <td align="right">Fecha:</td>
                <td><input id="FechaDoc" name="fecha" type="datetime" value="<?php echo date("Y-m-d h:i:s")?>"/></td>
              </tr>
              <tr valign="baseline">
                <td align="right">Descripcion:</td>
                <td><textarea name="descripcion" cols="50" rows="5" required></textarea></td>
              </tr>
              <tr valign="baseline">
                <td align="right">Archivo:</td>
                <td>
                <input type="file" name="archivo" id="archivo" required/></td>
              </tr>
              <tr valign="baseline">
                <td align="right">&nbsp;</td>
                <td><input type="submit" value="Agregar Documento" /></td>
              </tr>
            </table>
            <input type="hidden" name="id_doc" value="" />
            <input type="hidden" name="MM_insert" value="form1" />
          </form>
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
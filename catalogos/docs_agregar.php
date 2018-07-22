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


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
error_reporting(E_ALL); // or E_STRICT
ini_set("display_errors",1);
ini_set("memory_limit","1024M");

$archivo = $_FILES['archivo']['tmp_name']; //nombre temp en el servidor este se utiliza para guardarlo en la base de datos
$tamano = $_FILES['archivo']['size'];
$tipo = $_FILES['archivo']['type'];

		$uploaddir = '../documentos/';
 		$nombre  = $_FILES["archivo"]["name"];
		$prefijo = substr(md5(uniqid(rand())),0,6); //esto es para evitar duplicidad en los archivos



//print_r($_FILES);
if ($archivo != 'none') {
	$id_doc = $_POST['id_doc'];
	$titulo = $_POST['titulo'];
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
				
$insertSQL = "INSERT INTO documentos (archivo, fecha, descripcion, nom_archivo, tipo, tamano, url_file, estatus) VALUES ('$titulo','$fecha','$descripcion','$nom_archivo','$tipo','$tamano','$url_file', '2')";	   
	echo $insertSQL;
   mysqli_select_db($ResEquipos, $database_ResEquipos);
  $Result1 = mysql_query($insertSQL, $ResEquipos) or die(mysqli_error($ResEquipos));
 
 if(mysql_affected_rows($ResEquipos) > 0)
  					{
						$resultado = "Se ha subido correctamente";
						
						if (move_uploaded_file($_FILES['archivo']['tmp_name'],$destino)) 
							{
        						$status = "Archivo subido: <b>".$archivo."</b>";
        						header("Location: cat_docs.php");
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
          <a href="../index.php">
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
               <li>Agregar documentos</li>
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
        <h3>Agregar Documento</h3>
        <div class="blog">
        
          <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" id="form">
            <table align="center">
              <tr valign="baseline">
                <td align="right">Titulo:</td>
                <td>
                <input type="text" name="titulo" value="" size="32"/></td>
              </tr>
              <tr valign="baseline">
                <td align="right">Fecha:</td>
                <td><input id="FechaDoc" name="fecha" type="date" value="<?php echo date("Y-m-d")?>"/></td>
              </tr>
              <tr valign="baseline">
                <td align="right">Descripcion:</td>
                <td><textarea name="descripcion" cols="50" rows="5" required></textarea></td>
              </tr>
               <tr valign="baseline">
                <td align="right">Área:</td>
                <td><input id="FechaDoc" name="fecha" type="text" value=""/></td>
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
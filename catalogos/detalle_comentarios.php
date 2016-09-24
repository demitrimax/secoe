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


$colname_comentarios = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_comentarios = $_GET['idEquipo'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_comentarios = sprintf("SELECT
* FROM
eqcomentarios
WHERE eqcomentarios.equipo = %s
ORDER BY
eqcomentarios.fec_coment DESC", GetSQLValueString($colname_comentarios, "int"));

$comentarios = mysql_query($query_comentarios, $ResEquipos) or die(mysql_error());
$row_comentarios = mysql_fetch_assoc($comentarios);


?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Catálogo de Documentos</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="../images/favicon.ico">
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="../css/style.css">
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

<link rel="stylesheet" type="text/css" href="../DataTables/media/css/jquery.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="../DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="../DataTables/extensions/Responsive/css/responsive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="../DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>

	
	<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
	$('#comentarios')
	.DataTable(
	{
	"language": {
                "url": "../DataTables/spanish/spanish.json"
				}
	});
	
} );

	</script>

</head>
<body>
<section id="content"><div class="ic"></div>
    <table class="display" id="comentarios" cellspacing="0" width="100%">
          <thead>
            <tr>
              <td>ID</td>
              <td>Fecha</td>
              <td>Comentarios</td>
            </tr>
      </thead>
            <tbody>
            <?php do { ?>
              <tr>
                <td><?php echo $row_comentarios['id_com']; ?></td>
                <td><?php echo $row_comentarios['fec_coment']; ?></td>
                <td><?php echo $row_comentarios['comentario']; ?></td>
              </tr>
              <?php } while ($row_comentarios = mysql_fetch_assoc($comentarios)); ?>
      </tbody>
    </table>
</section>

</body>
</html>
<?php
mysql_free_result($comentarios);
?>
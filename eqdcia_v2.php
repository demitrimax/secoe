<?php require_once('../Connections/ResEquipos.php'); ?>
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

$colname_Recordset1 = "-1";
if (isset($_GET['idCia'])) {
  $colname_Recordset1 = $_GET['idCia'];
}
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_CiasEQ = sprintf("SELECT
cat_equipos.CLVE_EQUIPO,
cat_equipos.IdEquipo,
cat_equipos.Equipo,
cat_equipocaracteristicas.Caracteristicas,
cat_estatus.ESTATUS,
cat_cias.NombreCia,
cat_estatus.SEMAFORO
FROM
cat_equipos
INNER JOIN cat_equipocaracteristicas ON cat_equipos.Caracteristicas = cat_equipocaracteristicas.IdCar
INNER JOIN cat_estatus ON cat_equipos.ESTATUS = cat_estatus.ID_ESTATUS
INNER JOIN cat_cias ON cat_equipos.Cia = cat_cias.id_cia
WHERE (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND cat_equipos.Cia = %s" , GetSQLValueString($colname_Recordset1, "text"));;

$CiasEQ = mysql_query($query_CiasEQ, $ResEquipos) or die(mysql_error());
$row_CiasEQ = mysql_fetch_assoc($CiasEQ);
$totalRows_CiasEQ = mysql_num_rows($CiasEQ);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Equipos por Compañía</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
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
  <link rel="stylesheet" type="text/css" href="jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css" />
  <script type="text/javascript" src="jquery.lightbox/js/lightbox/jquery.lightbox.min.js"></script>
   <!-- // <script type="text/javascript" src="jquery.lightbox/jquery.lightbox.js"></script>   -->

  <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox();
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

<link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="DataTables/extensions/Responsive/css/responive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>

	
	<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
	$('#equipost')
	.addClass( 'nowrap' )
	.DataTable(
	{
	 responsive: true,
	"language": {
                "url": "DataTables/spanish/spanish.json"
				}
	});
	
} );

	</script>

</head>
<body>
<section id="content"><div class="ic"></div>
  <div>
        <h3> Compañía <?php echo $row_CiasEQ['NombreCia']; ?></h3>
         <div id="banner" >
    <table class="display" id="equipost" cellspacing="0" width="100%">
          <thead>
            <tr>
              <td>Número</td>
              <td>Equipo</td>
              <td>Carácteristicas</td>
              <td>Estado</td>
            </tr>
      </thead>
            <tbody>
            <?php do { ?>
              <tr>
                <td><?php echo $row_CiasEQ['CLVE_EQUIPO']; ?></a></td>
                <td><a href="detalle_equipo.php?idEquipo=<?php echo $row_CiasEQ['IdEquipo']; ?>"><?php echo $row_CiasEQ['Equipo']; ?></a></a></td>
                <td><?php echo $row_CiasEQ['Caracteristicas']; ?></td>
                <td><img src="images/sem/sem_<?php echo $row_CiasEQ['SEMAFORO']; ?>.png" width="16" height="16" title="<?php echo utf8_encode($row_CiasEQ['ESTATUS']); ?>"></td>
              </tr>
              <?php } while ($row_CiasEQ = mysql_fetch_assoc($CiasEQ)); ?>
      </tbody>
    </table>
    <p>&nbsp;</p>
          <p><a href="eq_opcia.php">Regresar</a></p>

  </div>

  </div>
</section>
<!--==============================
              footer
=================================-->

</body>
</html>
<?php
mysql_free_result($CiasEQ);
?>
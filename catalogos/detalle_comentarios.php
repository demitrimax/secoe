<?php require_once('../../Connections/ResEquipos.php'); ?>
<?php

$colname_comentarios = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_comentarios = $_GET['idEquipo'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_comentarios = sprintf("SELECT
* FROM
eqcomentarios
WHERE eqcomentarios.equipo = %s
ORDER BY
eqcomentarios.fec_coment DESC", $colname_comentarios);

$comentarios = mysqli_query($ResEquipos, $query_comentarios) or die(mysqli_error($ResEquipos));
$row_comentarios = mysqli_fetch_assoc($comentarios);


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
              <?php } while ($row_comentarios = mysqli_fetch_assoc($comentarios)); ?>
      </tbody>
    </table>
</section>

</body>
</html>
<?php
mysqli_free_result($comentarios);
?>
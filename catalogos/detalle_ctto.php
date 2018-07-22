<?php require_once('../Connections/ResEquipos.php'); ?>
<?php

$colname_det_ctto = "-1";
if (isset($_GET['no_ctto'])) {
  $colname_det_ctto = $_GET['no_ctto'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_det_ctto = sprintf("SELECT * FROM list_contratos WHERE NO_CONTRATO = %s", $colname_det_ctto);
$det_ctto = mysqli_query($ResEquipos, $query_det_ctto) or die(mysqli_error($ResEquipos));
$row_det_ctto = mysqli_fetch_assoc($det_ctto);
$totalRows_det_ctto = mysqli_num_rows($det_ctto);

$colname_comentario_ctto = "-1";
if (isset($_GET['no_ctto'])) {
  $colname_comentario_ctto = $_GET['no_ctto'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_comentario_ctto = sprintf("SELECT
ctos_comentarios.id_coment,
ctos_comentarios.comentario,
ctos_comentarios.fecha,
ctos_comentarios.cto_asociado,
cat_tipo_comentario.tipo
FROM
ctos_comentarios
INNER JOIN cat_tipo_comentario ON ctos_comentarios.tipo_coment = cat_tipo_comentario.id_tipo 
WHERE cto_asociado = %s
ORDER BY ctos_comentarios.fecha DESC", $colname_comentario_ctto);
$comentario_ctto = mysqli_query($ResEquipos, $query_comentario_ctto) or die(mysqli_error($ResEquipos));
$row_comentario_ctto = mysqli_fetch_assoc($comentario_ctto);
$totalRows_comentario_ctto = mysqli_num_rows($comentario_ctto);

$colname_ct_cttos = "-1";
if (isset($_GET['no_ctto'])) {
  $colname_ct_cttos = $_GET['no_ctto'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_ct_cttos = sprintf("SELECT
contrato.ID_CTTO,
contrato.F_INICIO,
contrato.F_FIN,
contrato.PLAZO,
contrato.TARIFA,
contrato.NO_CONTRATO,
contrato.OBJETO_CTO,
contrato.COMPANIA,
contrato.EQUIPOID,
contrato.ESQUEMA,
contrato.ACTIVO,
contrato.ESTATUS,
cat_equipos.Equipo,
cat_esquemacto.ESQ_CORTO,
cat_cias.NombreCia,
cat_ctostatus.ESTATUS,
cat_tctto.TIPOCTTO
FROM
contrato
LEFT JOIN cat_equipos ON contrato.EQUIPOID = cat_equipos.idEquipo
LEFT JOIN cat_esquemacto ON contrato.ESQUEMA = cat_esquemacto.IDESQ
LEFT JOIN cat_cias ON contrato.COMPANIA = cat_cias.id_cia
LEFT JOIN cat_ctostatus ON contrato.ESTATUS = cat_ctostatus.ID_STATUS
LEFT JOIN cat_tctto ON contrato.T_CTTO = cat_tctto.ID 
WHERE NO_CONTRATO = %s", $colname_ct_cttos);
$ct_cttos = mysqli_query($ResEquipos, $query_ct_cttos) or die(mysqli_error($ResEquipos));
$row_ct_cttos = mysqli_fetch_assoc($ct_cttos);
$totalRows_ct_cttos = mysqli_num_rows($ct_cttos);

$RegresarLink = "../contratos.php";
if (isset($_POST['REGRESAR'])) {
	$RegresarLink=$_POST['REGRESAR'];	
	}
?>
<!DOCTYPE html>
<html lang="es" xmlns:spry="http://ns.adobe.com/spry">
<head>
<title>Detalle del Contrato <?php echo $row_det_ctto['NO_CONTRATO']; ?></title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="../images/favicon.ico">
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/responstable.css">
<link href="file:///D|/xampp/htdocs/SECOE/SpryAssets/SpryMasterDetail.css" rel="stylesheet" type="text/css">
<link href="file:///D|/xampp/htdocs/SECOE/SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css">
<script src="../js/jquery.js"></script>
<script src="../js/jquery-migrate-1.4.1.js"></script>
<script src="../js/jquery.easing.1.3.js"></script>
<script src="../js/script.js"></script> 
<script src="../js/superfish.js"></script>
<script src="../js/jquery.equalheights.js"></script>
<script src="../js/jquery.mobilemenu.js"></script>
<script src="../js/tmStickUp.js"></script>
<script src="../js/jquery.ui.totop.js"></script>
<script src="file:///D|/xampp/htdocs/SECOE/SpryAssets/xpath.js" type="text/javascript"></script>
<script src="file:///D|/xampp/htdocs/SECOE/SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="file:///D|/xampp/htdocs/SECOE/SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
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
	<link rel="stylesheet" type="text/css" href="../DataTables/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="../DataTables/examples/resources/demo.css">
	<style type="text/css" class="init">
	
td.details-control {
	background: url('../DataTables/examples/resources/details_open.png') no-repeat center center;
	cursor: pointer;
}
tr.details td.details-control {
	background: url('../DataTables/examples/resources/details_close.png') no-repeat center center;
}

	</style>

	<script type="text/javascript" language="javascript" src="../DataTables/media/js/jquery.dataTables.js">
	</script>
	<script type="text/javascript" language="javascript" src="../DataTables/examples/resources/syntax/shCore.js">
	</script>
	<script type="text/javascript" language="javascript" src="../DataTables/examples/resources/demo.js">
	</script>
<script type="text/javascript">
$(document).ready( function () {
    $('#contratos')
		.DataTable({
		 	responsive: true,
			"language": {
                "url": "../DataTables/spanish/spanish.json"
						}
	});
	$('#comentarios')
		.DataTable({
		 	responsive: true,
			"language": {
                "url": "../DataTables/spanish/spanish.json"
						}
	});
} );
</script>


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
               <li class="current">Detalle del contrato <?php echo $row_det_ctto['NO_CONTRATO']; ?></li>
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
    <div class="grid_12">
        <h3>Detalle del Contrato</h3>
        <div class="extra_wrapper">
          <p>No. Contrato </p>
          <link rel="stylesheet" href="../css/responstable.css">

<table border="0" class="responstable">
   <tr>
    <th scope="row">Contrato:</td>
    
    <td> <?php echo $row_det_ctto['NO_CONTRATO']; ?></td>
  </tr>

  <tr>
    <th scope="row">Fecha de Inicio</td>
    
    <td><?php echo date("d/m/y", strtotime($row_det_ctto['F_INICIO'])); ?></td>
  </tr>
  <tr>
    <th scope="row">Fecha de Término</th>
    <td><?php echo date("d/m/y", strtotime($row_det_ctto['F_FIN'])); ?></td>
  </tr>
  <tr>
    <th scope="row">Plazo:</th>
    <td><?php echo $row_det_ctto['PLAZO']; ?></td>
  </tr>
  <tr>
    <th scope="row">Tarífa</th>
    <td><?php echo number_format($row_det_ctto['TARIFA']); ?></td>
  </tr>
  <tr>
    <th scope="row">Esquema</th>
    <td><?php echo $row_det_ctto['ESQUEMA']; ?></td>
  </tr>
  <tr>
    <th scope="row">Compañía:</th>
    <td><?php echo $row_det_ctto['NombreCia']; ?></td>
  </tr>
  <tr>
    <th scope="row">Objeto del Contrato:</th>
    <td><?php echo $row_det_ctto['OBJETO_CTO']; ?></td>
  </tr>
  <tr>
    <th scope="row">Estatus</th>
    <td><?php echo $row_det_ctto['ESTATUS']; ?></td>
  </tr>
</table>
</div>
<div class="extra_wrapper">
        <h3>Equipos asociados</h3>
<table border="0" id="contratos" width="100%">
 <thead>
  <tr>
    <td>ID</td>
    <td>INICIO</td>
    <td>FIN</td>
    <td>PLAZO</td>
    <td>TARIFA</td>
    <td>OBJETO</td>
    <td>EQUIPO</td>
    <td>NOMBRE EQUIPO</td>
    <td>ESQUEMA</td>
    <td>ACTIVO</td>
    <td>ESTATUS</td>
  </tr>
  </thead>
  <tbody>
  <?php do { ?>
    <tr>
      <td><a href="edit_contrato.php?idctto=<?php echo $row_ct_cttos['ID_CTTO']; ?>"><?php echo $row_ct_cttos['ID_CTTO']; ?></a></td>
      <td><?php echo date("d/m/Y", strtotime($row_ct_cttos['F_INICIO'])); ?></td>
      <td><?php echo date("d/m/Y", strtotime($row_ct_cttos['F_FIN'])); ?></td>
      <td><?php echo $row_ct_cttos['PLAZO']; ?></td>
      <td><?php echo number_format($row_ct_cttos['TARIFA']); ?></td>
      <td><?php echo $row_ct_cttos['OBJETO_CTO']; ?></td>
      <td><?php echo $row_ct_cttos['EQUIPOID']; ?></td>
      <td><?php echo $row_ct_cttos['Equipo']; ?></td>
      <td><?php echo $row_ct_cttos['ESQ_CORTO']; ?></td>
      <td><?php echo $row_ct_cttos['ACTIVO']; ?></td>
      <td><?php echo $row_ct_cttos['ESTATUS']; ?></td>
    </tr>
    <?php } while ($row_ct_cttos = mysqli_fetch_assoc($ct_cttos)); ?>
    </tbody>
</table>

        <h3>Comentarios</h3>
        <table border="0" id="comentarios" width="100%" class="display">
          <thead>
          <tr>
            <td>Id</td>
            <td>Tipo Comentario</td>
            <td>Fecha</td>
            <td>Comentario</td>
          </tr>
          </thead>
          <tbody>
          <?php do { ?>
            <tr>
              <td><?php echo $row_comentario_ctto['id_coment']; ?></td>
              <td><?php echo utf8_encode($row_comentario_ctto['tipo']); ?></td>
              <td><?php echo $row_comentario_ctto['fecha']; ?></td>
              <td><?php echo $row_comentario_ctto['comentario']; ?></td>
            </tr>
            <?php } while ($row_comentario_ctto = mysqli_fetch_assoc($comentario_ctto)); ?>
        </tbody>
        </table>
</div>
            </ul>
      </div>
          <p>&nbsp;</p>
    </div>
    
      <div class="grid_12">
    <a href="<?php echo $RegresarLink; ?>">Regresar</a> | <a href="agregar_coment_ctto.php?no_ctto=<?php echo $row_det_ctto['NO_CONTRATO']; ?>">Agregar Comentario</a></div>

        <h3></h3>
         <div id="banner" class="container" ></div>
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
mysqli_free_result($det_ctto);

mysqli_free_result($comentario_ctto);

mysqli_free_result($ct_cttos);
?>

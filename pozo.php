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
<?php require_once('Connections/ResEquipos.php'); ?>
<?php

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


?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Pozo <?php echo utf8_encode($row_pozodetalle['nombrepozo']); ?></title>
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


<!-- Script para DataTables -->
	<link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.min.css">
		
	<script type="text/javascript" charset="utf8" src="DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="DataTables/extensions/Responsive/css/responsive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>

<style>
*, *:before, *:after {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}
#page-wrapper {
  width: 640px;
  background: #FFFFFF;
  padding: 1em;
  margin: 1em auto;
  border-top: 5px solid #69c773;
  box-shadow: 0 2px 10px rgba(0,0,0,0.8);
}
details {
  border-radius: 3px;
  background: #EEE;
  margin: 1em 0;
}
summary {
  background: #333;
  color: #FFF;
  border-radius: 3px;
  padding: 5px 10px;
  outline: none;
}

/* Style the summary when details box is open */
details[open] summary {
  background: #69c773;
  color: #333;
}

/* Custom Markers */
#custom-marker summary {
  font-size: 17px;
  vertical-align: top;
}

#custom-marker summary::-webkit-details-marker {
  display: none;
}

#custom-marker summary:before {
  display: inline-block;
  width: 18px;
  height: 18px;
  margin-right: 8px;
  content: "";
  background-image: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/4621/treehouse-icon-sprite.png);
  background-repeat: no-repeat;
  background-position: 0 0;
}

#custom-marker[open] summary:before {
  background-position: -18px 0;
}

</style>
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
               <li>Detalle del Pozo <?php echo utf8_encode($row_pozodetalle['nombrepozo']); ?></li>
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
			<div class="grid_12">
                <details>
                <summary>Detalles del pozo</summary>
                
                <table>
                  <tr>
                    <th scope="row"><strong>Campo:</strong></th>
                    <td><?php echo utf8_encode($row_pozodetalle['campo']); ?></td>
                  </tr>
                  <tr>
                    <th scope="row"><strong>Número:</strong></th>
                    <td><?php echo $row_pozodetalle['numero']; ?></td>
                  </tr>
                  <tr>
                    <th scope="row"><strong>Tipo de Pozo</strong></th>
                    <td><?php echo $row_pozodetalle['tipo']; ?></td>
                  </tr>
                  <tr>
                    <th scope="row"><strong>Modalidad</strong></th>
                    <td><?php echo $row_pozodetalle['modalidad']; ?></td>
                  </tr>
                  <tr>
                    <th scope="row"><strong>Tirante de Agua</strong></th>
                    <td><?php echo $row_pozodetalle['tirante_agua']; ?></td>
                  </tr>
                  <tr>
                    <th scope="row"><strong>Profundidad Vertical:</strong></th>
                    <td><?php echo $row_pozodetalle['prof_ver']; ?></td>
                  </tr>
                  <tr>
                    <th scope="row"><strong>Profundidad Desarrollada</strong></th>
                    <td><?php echo $row_pozodetalle['prof_des']; ?></td>
                  </tr>
                  <tr>
                  <tr>
                    <th scope="row"><strong>Profundidad</strong></th>
                    <td><?php echo $row_pozodetalle['profundidad']; ?></td>
                  </tr>
                  <tr>
                    <th scope="row"><strong>Activo</strong></th>
                    <td><?php echo $row_pozodetalle['ACTIVO']; ?></td>
                  </tr>
                  <tr>
                    <th scope="row"><strong>Unidad Operativa</strong></th>
                    <td><?php echo $row_pozodetalle['UnidadOperativa']; ?></td>
                  </tr>
                  <tr>
                    <th scope="row"><strong>Comentarios</strong></th>
                    <td>
                      <?php echo $row_pozodetalle['comentario']; ?>
                    </td>
                  </tr>
                </table>
              </details>
              
              <details>
                <summary>Intrevenciones Registradas</summary>
                   <table id="intervenciones" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><strong>Programa</strong></th>
                            <th><strong>Intervención</strong></th>
                            <th><strong>Fecha Inicio</strong></th>
                            <th><strong>Fecha Termino</strong></th>
                            <th><strong>Pozo</strong></th>
                            <th><strong>Equipo</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php do { ?>
                        <tr>
                        	<td><?php echo $row_intervenciones['programoficial']; ?></td>
                            <td><?php echo $row_intervenciones['intervencion']; ?></td>
                            <td><?php echo date("d/m/Y", strtotime($row_intervenciones['fec_ini'])); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($row_intervenciones['fec_fin'])); ?></td>
                            <td><?php echo $row_intervenciones['pozo']; ?></td>
                            <td><?php echo $row_intervenciones['CLVE_EQUIPO']."|<a href='detalle_equipo_v2.php?idEquipo=".$row_intervenciones['idequipo']."'>".$row_intervenciones['Equ_corto']; ?></a></td>
                        </td>
                        <?php } while ($row_intervenciones = mysqli_fetch_assoc($intervenciones)); ?>
                    </tbody>
                </table>
              </details>
              
              <a href="cat_pozos.php">REGRESAR</a> | <a href="mod_pozo.php?idpozo=<?php echo $colname_idpozo; ?>">MODIFICAR POZO</a> | 
             
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


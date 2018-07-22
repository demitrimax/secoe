<?php require_once('../Connections/ResEquipos.php'); ?>
<?php

$colname_ProgEquipos = 'POT-2';
if (isset($_GET['programa'])) {
  $colname_ProgEquipos = $_GET['programa'];
}
$varactivo = "1";
if (isset($_GET['activo'])) {
  $varactivo = $_GET['activo'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_ProgEquipos = sprintf("SELECT * FROM v_intervenciones WHERE programoficial = '%s' AND idactivo = %s", $colname_ProgEquipos, $varactivo);
$ProgEquipos = mysqli_query($ResEquipos, $query_ProgEquipos) or die(mysqli_error($ResEquipos));
$row_ProgEquipos = mysqli_fetch_assoc($ProgEquipos);
$totalRows_ProgEquipos = mysqli_num_rows($ProgEquipos);
//echo $query_ProgEquipos."<br>";

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_programas = sprintf("SELECT Equipo, idequipo FROM v_intervenciones WHERE programoficial = '%s' AND idactivo = %s GROUP BY idequipo", $colname_ProgEquipos, $varactivo);
$programas = mysqli_query($ResEquipos, $query_programas) or die(mysqli_error($ResEquipos));
$row_programas = mysqli_fetch_assoc($programas);
$totalRows_programas = mysqli_num_rows($programas);
//echo $query_programas."<br>";

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_activos = "SELECT * FROM cat_activos";
$activos = mysqli_query($ResEquipos, $query_activos) or die(mysqli_error($ResEquipos));
$row_activos = mysqli_fetch_assoc($activos);
$totalRows_activos = mysqli_num_rows($activos);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_programoficial = "SELECT
pot.programoficial
FROM
pot
GROUP BY
pot.programoficial";
$programoficial = mysqli_query($ResEquipos, $query_programoficial) or die(mysqli_error($ResEquipos));
$row_programoficial = mysqli_fetch_assoc($programoficial);
$totalRows_programoficial = mysqli_num_rows($programoficial);
?>
<!DOCTYPE html>
<html lang="es" xmlns:spry="http://ns.adobe.com/spry">
<head>
<title>Gantt de los equipos</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responstable.css">

<script src="js/jquery.js"></script>
<script src="js/jquery-migrate-1.4.1.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/script.js"></script> 
<script src="js/superfish.js"></script>
<script src="js/jquery.equalheights.js"></script>
<script src="js/jquery.mobilemenu.js"></script>
<script src="js/tmStickUp.js"></script>
<script src="js/jquery.ui.totop.js"></script>
<script src="../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
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

  <link rel="stylesheet" type="text/css" href="jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css" />
  <script type="text/javascript" src="jquery.lightbox/js/lightbox/jquery.lightbox.min.js"></script>
   <!-- // <script type="text/javascript" src="jquery.lightbox/jquery.lightbox.js"></script>   -->

  <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox();
    });
  </script>
  <script type="text/javascript">
  	function getComboA(sel) {
    	var valueA = document.getElementById("activos").value;
		var valueB = document.getElementById("programa").value;
		window.location.href = 'gantt_equipos.php?activo='+valueA+'&programa='+valueB; 
	}
  </script>
  
      <script src="vis/dist/vis.js"></script>
     <script src="vis/moment-with-locales.js"></script>
  <link href="vis/dist/vis.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
    body, html {
      font-family: sans-serif;
    }
	    /* alternating column backgrounds */
    .vis-time-axis .vis-grid.vis-odd {
      background: #f5f5f5;
    }
	    /* gray background in weekends, white text color */
    .vis-time-axis .vis-grid.vis-saturday,
    .vis-time-axis .vis-grid.vis-sunday {
      background: gray;
    }
    .vis-time-axis .vis-text.vis-saturday,
    .vis-time-axis .vis-text.vis-sunday {
      color: white;
    }
	 /* custom styles for individual items, load this after vis.css */
    .vis-item.verde {
	background-color: #060;
	border-color: #000;
	color: white;
    }
	.vis-item.azul {
	background-color: #009;
	border-color: #000;
	color: white;
    }
	.vis-item.azulclaro {
	background-color: #0FF;
	border-color: #000;
	color: #000;
    }
	.vis-item.amarillo {
	background-color: #FF0;
	border-color: #000;
    }
	.vis-item.naranja {
	background-color: #F60;
	border-color: #000;
    }
	.vis-item.rojofuerte {
	background-color: #900;
	border-color: #000;
	color: white;
    }
	.vis-item.melon {
	background-color: #FC0;
	border-color: #000;
    }
	.vis-item.verdeclaro {
	background-color: #0F0;
	border-color: #000;
    }

  </style>

</head>


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
               <li class="current">Programa de Trabajo de equipos</li>
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
        <h3>Reporte de Equipos por Activo</h3>
        <div class="extra_wrapper">
        <p>&nbsp;</p>
          <p><?php echo $row_ProgEquipos['ACTIVO'] ?></p>
          <form action="" method="get" enctype="application/x-www-form-urlencoded" name="form1">
            <label for="activos">Activos</label>
            <select name="activos" id="activos" onChange="getComboA(this)">
              <?php
do {  
?>
              <option value="<?php echo $row_activos['id_activo']?>"<?php if (!(strcmp($row_activos['id_activo'], $varactivo))) {echo "selected=\"selected\"";} ?>><?php echo $row_activos['ACTIVO']?></option>
              <?php
} while ($row_activos = mysqli_fetch_assoc($activos));
  $rows = mysqli_num_rows($activos);
  if($rows > 0) {
      mysqli_data_seek($activos, 0);
	  $row_activos = mysqli_fetch_assoc($activos);
  }
?>
            </select><br>
            <label for="programa">Programa</label>
            <select name="programa" id="programa" onChange="getComboA(this)">
            <?php
do {  
?>
              <option value="<?php echo $row_programoficial['programoficial']?>"<?php if (!(strcmp($row_programoficial['programoficial'], $colname_ProgEquipos))) {echo "selected=\"selected\"";} ?>><?php echo $row_programoficial['programoficial']?></option>
              <?php
} while ($row_programoficial = mysqli_fetch_assoc($programoficial));
  $rows = mysqli_num_rows($programoficial);
  if($rows > 0) {
      mysqli_data_seek($programoficial, 0);
	  $row_programoficial = mysqli_fetch_assoc($programoficial);
  }
?>
            </select>
          </form>
          <p>
            <input type="button" id="focus2016" value="Solo mostrar 2016"><br>
 </p>
<div id="visualization">
    <div class="menu">
        <input type="button" id="zoomIn" value="Acercar +"/>
        <input type="button" id="zoomOut" value="Alejar -"/>
        <input type="button" id="moveLeft" value="Mover izq"/>
        <input type="button" id="moveRight" value="Mover der"/>
    </div>
</div>
<script type="text/javascript">
<?php $contador = 0; 
$contador2 = -1;
?>
  // DOM element where the Timeline will be attached
  var container = document.getElementById('visualization');
  
   var groups = new vis.DataSet([
           <?php do { 
		   $contador2++;
		   ?>
    {id: <?php echo $row_programas['idequipo']; ?>, content: '<?php echo $row_programas['Equipo']; ?>', value: <?php echo $contador2; ?>},
	<?php } while ($row_programas = mysqli_fetch_assoc($programas)); ?>
  ]);

  // Create a DataSet (allows two way data-binding)
  var items = new vis.DataSet([
        <?php do { 
	  $fini_dia = date("d", strtotime($row_ProgEquipos['fec_ini']));
	  $fini_mes = date("m", strtotime($row_ProgEquipos['fec_ini']));
	  $fini_ano = date("Y", strtotime($row_ProgEquipos['fec_ini']));
	  $ffin_dia = date("d", strtotime($row_ProgEquipos['fec_fin']));
	  $ffin_mes = date("m", strtotime($row_ProgEquipos['fec_fin']));
	  $ffin_ano = date("Y", strtotime($row_ProgEquipos['fec_fin']));
	  $tooltip =  utf8_encode($row_ProgEquipos['pozo'])." / ".$row_ProgEquipos['intervencion']." | ".date("d-m-Y", strtotime($row_ProgEquipos['fec_ini']))." a ".date("d-m-Y", strtotime($row_ProgEquipos['fec_fin']));
	  $contador++;
	  ?>
 {id: <?php echo $contador; ?>, group: <?php echo $row_ProgEquipos['idequipo']; ?>, content: '<?php echo utf8_encode($row_ProgEquipos['pozo']); ?>', start: '<?php echo $fini_ano."-".$fini_mes."-".$fini_dia;?> 00:00:00', end: '<?php echo $ffin_ano."-".$ffin_mes."-".$ffin_dia;?> 23:59:00', className: '<?php echo $row_ProgEquipos['clasecolor']; ?>', title: '<?php echo $tooltip; ?>'},
<?php } while ($row_ProgEquipos = mysqli_fetch_assoc($ProgEquipos)); ?>
  ]);

  // Configuration for the Timeline
  var options = {
	  locale: 'es',
	  stack: false,
	  selectable: true
	  };
	 
	     function move (percentage) {
        var range = timeline.getWindow();
        var interval = range.end - range.start;

        timeline.setWindow({
            start: range.start.valueOf() - interval * percentage,
            end:   range.end.valueOf()   - interval * percentage
        });
    }

    /**
     * Zoom the timeline a given percentage in or out
     * @param {Number} percentage   For example 0.1 (zoom out) or -0.1 (zoom in)
     */
    function zoom (percentage) {
        var range = timeline.getWindow();
        var interval = range.end - range.start;

        timeline.setWindow({
            start: range.start.valueOf() - interval * percentage,
            end:   range.end.valueOf()   + interval * percentage
        });
    }

  // Create a Timeline
	var timeline = new vis.Timeline(container);
  timeline.setOptions(options);
  timeline.setGroups(groups);
  timeline.setItems(items);
  // attach events to the navigation buttons
    document.getElementById('zoomIn').onclick    = function () { zoom(-0.2); };
    document.getElementById('zoomOut').onclick   = function () { zoom( 0.2); };
    document.getElementById('moveLeft').onclick  = function () { move( 0.2); };
    document.getElementById('moveRight').onclick = function () { move(-0.2); };
  
  document.getElementById('focus2016').onclick = function() {
	  timeline.setWindow('2016-01-01', '2016-12-31');
  }
</script>    

          
          
        </div>
            </ul>
      </div>
          <p>&nbsp;</p>
    </div>
    
      <div class="grid_12">
    <a href="index.php">Regresar</a> | Comparar POT's |</div>

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
mysqli_free_result($programas);

mysqli_free_result($activos);
mysqli_free_result($ProgEquipos);
?>

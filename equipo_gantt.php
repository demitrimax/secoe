<?php
date_default_timezone_set('America/Monterrey');
?>
<?php require_once('Connections/ResEquipos.php'); ?>

<?php


$colname_idEquipo = "-1";
if (isset($_GET['idEquipo'])) {
  $colname_idEquipo = $_GET['idEquipo'];
}
$colname_programa = "-1";
if (isset($_GET['programa'])) {
  $colname_programa = $_GET['programa'];
}
$colname_ano = "2016";
if (isset($_GET['ano'])) {
  $colname_ano = $_GET['ano'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_Recordset1 = sprintf("SELECT * FROM detalleequipo WHERE idEquipo = %s", $colname_idEquipo);
$Recordset1 = mysqli_query($ResEquipos, $query_Recordset1) or die(mysqli_error($ResEquipos));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_ProgEquipos = sprintf("SELECT * FROM v_intervenciones WHERE idequipo = %s", $colname_idEquipo);
$ProgEquipos = mysqli_query($ResEquipos, $query_ProgEquipos) or die(mysqli_error($ResEquipos));
$row_ProgEquipos = mysqli_fetch_assoc($ProgEquipos);
$totalRows_ProgEquipos = mysqli_num_rows($ProgEquipos);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_programofi = sprintf("SELECT programoficial, programa, unicoprograma FROM v_intervenciones WHERE idequipo = %s GROUP BY programoficial ORDER BY
v_intervenciones.unicoprograma ASC", $colname_idEquipo);
$programofi = mysqli_query($ResEquipos, $query_programofi) or die(mysqli_error($ResEquipos));
$row_programofi = mysqli_fetch_assoc($programofi);
$totalRows_programofi = mysqli_num_rows($programofi);


?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Detalle del Equipo <?php echo $row_Recordset1['Equipo']; ?></title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responstable.css">
<script src="js/jquery-3.1.1.js"></script>
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

<link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="DataTables/extensions/Responsive/css/responsive.dataTables.css"/>
 
<script type="text/javascript" src="DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>
</script>
  <link rel="stylesheet" type="text/css" href="jquery.lightbox/js/lightbox/themes/default/jquery.lightbox.css" />
  <script type="text/javascript" src="jquery.lightbox/js/lightbox/jquery.lightbox.min.js"></script>
   <!-- // <script type="text/javascript" src="jquery.lightbox/jquery.lightbox.js"></script>   -->

  <script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox();
    });
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
<body>
<!--=====================
          Content
======================-->
<section id="content"><div class="ic"></div>
        <h3><?php echo $row_Recordset1['Equipo']; ?> </h3>
        
 <p>   
<!--<textarea id="txtData" rows="5"> </textarea><br>-->
<?php $anio_actual = date('Y'); ?>
<input type="button" id="focusanio" value="Solo <?php echo $anio_actual; ?>"> <input type="button" id="focusactualysig" value="<?php echo $anio_actual; ?> y <?php echo $anio_actual+1; ?>"><br>
  <input type="button" id="fit" value="Mostrar todo"><br>
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
  
  var post_data = {"idEquipo": <?php echo $colname_idEquipo; ?>, "programa": <?php echo $colname_programa; ?> }; 
   
  $.ajax({
    type: "GET",
	data: post_data,
	dataType: "json",
	url: 'catalogos/ganttdatos.php',
	
    success: function (data) {
      // hide the "loading..." message
      //document.getElementById('loading').style.display = 'none';
		//txtData.value = JSON.stringify(data, null, 2);
		//txtGrupos.value = JSON.stringify(data['grupos'], null, 2);
      // DOM element where the Timeline will be attached
      var container = document.getElementById('visualization');
		//los grupos...igual por ajax
	  var grupos = new vis.DataSet(data['grupos']);
      // Create a DataSet (allows two way data-binding)
      var items = new vis.DataSet(data['datos']);

  //Locale
  
  // Configuration for the Timeline
  var options = {
	  locales: {
		  //crear uno nuevo para espanol
		  myespa: {
			  current: 'current',
			  time: 'time',
		  }
	  },
	  start: '<?php echo $colname_ano; ?>-01-01',
	  end: '<?php echo $colname_ano; ?>-12-31',
	  locale: 'myespa',
	  stack: false,
	  selectable: true,
	  clickToUse: true
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
					//document.getElementById('zoomIn').onclick    = function () { zoom(-0.2); };
					
					$("#btnSubmit").click(function(){
					var selectedPOT = new Array();
					$('input[name="ProgOperativos"]:checked').each(function() {
						selectedPOT.push(this.value);
					});
					var post_datos = {"idEquipo": <?php echo $colname_idEquipo; ?>, "programa": selectedPOT}; 
					//("#visualization").load(post_datos);
					$.ajax({
    					type: "GET",
						data: post_datos,
						dataType: "json",
						url: 'catalogos/ganttdatos.php',
    					success: function (data) {
							//txtData.value = JSON.stringify(data, null, 2);
							//txtData.value = txtData.value + JSON.stringify(post_datos, null, 2);
							
							var grupos = new vis.DataSet(data['grupos']);
      						var items = new vis.DataSet(data['datos']);
							
									timeline.setGroups(grupos);
  									timeline.setItems(items);
		
									timeline.fit
						}

               		});
			   		
				
				//alert("Numero de POTs Seleccionado: "+selectedPOT.length+"\n"+"Y, son: "+selectedPOT);
				});
	
	function ActuaizaDatos(datos) {
  		

		alert("datos actualizados");
	}
	
  // Create a Timeline
	var timeline = new vis.Timeline(container);
  timeline.setOptions(options);
  timeline.setGroups(grupos);
  timeline.setItems(items);
  // attach events to the navigation buttons
    document.getElementById('zoomIn').onclick    = function () { zoom(-0.2); };
    document.getElementById('zoomOut').onclick   = function () { zoom( 0.2); };
    document.getElementById('moveLeft').onclick  = function () { move( 0.2); };
    document.getElementById('moveRight').onclick = function () { move(-0.2); };
  
  document.getElementById('focusanio').onclick = function() {
	  timeline.setWindow('<?php echo $anio_actual; ?>-01-01', '<?php echo $anio_actual; ?>-12-31');
  }
    document.getElementById('focusactualysig').onclick = function() {
	  timeline.setWindow('<?php echo $anio_actual; ?>-01-01', '<?php echo $anio_actual+1; ?>-12-31');
  }
    document.getElementById('fit').onclick = function() {
    timeline.fit();
  };
  
    	}
  });
</script>    
      <div class="grid_12">
        <h3></h3>
         <div id="banner" class="container" ></div>
      </div>

  </div>
 <div class="grid_12"> Leyenda
<img src="images/cod_colores.fw.png" alt=""/></section>
</div>
<!--==============================
              footer
=================================-->
<a href="#" id="toTop" class="fa fa-chevron-up"></a>
</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($ProgEquipos);

?>
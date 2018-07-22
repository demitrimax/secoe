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

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_equipos = "SELECT * FROM CAT_EQUIPOS";
$equipos = mysql_query($query_equipos, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_equipos = mysqli_fetch_assoc($equipos);
$totalRows_equipos = mysqli_num_rows($equipos);
$idEquipo = 0;
if (isset($_GET['idEquipo'])){
	$idEquipo = $_GET['idEquipo'];
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Escenario de Equipos</title>
     <script src="../js/jquery.js"></script>
	 <script src="../vis/dist/vis.js"></script>
     <script src="../vis/moment-with-locales.js"></script>
  <link href="../vis/dist/vis.css" rel="stylesheet" type="text/css" />


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

Seleccione el equipo para graficar: 
<form id="form1" name="form1" method="GET" action="gantt.php">
  <label for="equipo">Equipo</label>
  <select name="idEquipo" id="equipo" >
    <option value="" <?php if (!(strcmp("", $row_equipos['idEquipo']))) {echo "selected=\"selected\"";} ?>></option>
    <?php
do {  
?>
    <option value="<?php echo $row_equipos['idEquipo']?>"<?php if (!(strcmp($row_equipos['idEquipo'],$idEquipo))) {echo "selected=\"selected\"";} ?>><?php echo $row_equipos['Equipo']?></option>
    <?php
} while ($row_equipos = mysqli_fetch_assoc($equipos));
  $rows = mysqli_num_rows($equipos);
  if($rows > 0) {
      mysql_data_seek($equipos, 0);
	  $row_equipos = mysqli_fetch_assoc($equipos);
  }
?>
  </select>
  <input type="submit" name="button" id="button" value="Enviar" />
</form>

<p>&nbsp;</p>
<textarea name="grupos" cols="120" rows="10" id="grupos">
Información de los grupos
</textarea>

<textarea name="data" cols="120" rows="10" id="data">
[
  {"id": 1, "content": "item 1<br />start", "start": "2014-01-23"},
  {"id": 2, "content": "item 2", "start": "2014-01-18"},
  {"id": 3, "content": "item 3", "start": "2014-01-21"},
  {"id": 4, "content": "item 4", "start": "2014-01-19", "end": "2014-01-24"},
  {"id": 5, "content": "item 5", "start": "2014-01-28", "type": "point"},
  {"id": 6, "content": "item 6", "start": "2014-01-26"}
]
</textarea>
<br />
  <input type="button" id="load" value="&darr; Load" title="Load data from textarea into the Timeline"> 
<p>
  <input type="button" id="focus2016" value="Solo mostrar 2016"><br>
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

$(document).ready(function(){
	var txtData = document.getElementById('data');
	var txtGrupos = document.getElementById('grupos');
	var equipoid = document.getElementById('equipo');
  	// DOM element where the Timeline will be attached
	var container = document.getElementById('visualization');
  
  // var groups = new vis.DataSet([
  //]);

  // Create a DataSet (allows two way data-binding)
  //var items = new vis.DataSet([
  //]);

  $.ajax({
    type: "GET",
	data: {"idEquipo" : equipoid.value},
	dataType: "json",
	url: 'ganttdatos.php',
	
    success: function (data) {
      // hide the "loading..." message
      //document.getElementById('loading').style.display = 'none';
		txtData.value = JSON.stringify(data['datos'], null, 2);
		txtGrupos.value = JSON.stringify(data['grupos'], null, 2);
      // DOM element where the Timeline will be attached
      var container = document.getElementById('visualization');
		//los grupos...igual por ajax
	  var groups = new vis.DataSet(data['grupos']);
      // Create a DataSet (allows two way data-binding)
      var items = new vis.DataSet(data['datos']);


      // Configuration for the Timeline
        var options = {
			editable: true,
			stack: false,
	  		selectable: true,
	  		clickToUse: true

	  	};

      // Create a Timeline
      //var timeline = new vis.Timeline(container, items, options);
	    // Create a Timeline
	
  var timeline = new vis.Timeline(container);
  timeline.setOptions(options);
  timeline.setGroups(groups);
  timeline.setItems(items);
			function loadData () {
    		// get and deserialize the data
    		var data = JSON.parse(txtData.value);

    		// update the data in the DataSet
    		//
    		// Note: when retrieving updated data from a server instead of a complete
    		// new set of data, one can simply update the existing data like:
    		//
    		//   items.update(data);
    		//
    		// Existing items will then be updated, and new items will be added.
    		items.clear();
    		items.add(data);

    		// adjust the timeline window such that we see the loaded data
    		timeline.fit();
  			}
 // btnLoad.onclick = loadData;
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
		
		  // attach events to the navigation buttons
    document.getElementById('load').onclick = function(){loadData();};
	document.getElementById('zoomIn').onclick    = function () { zoom(-0.2); };
    document.getElementById('zoomOut').onclick   = function () { zoom( 0.2); };
    document.getElementById('moveLeft').onclick  = function () { move( 0.2); };
    document.getElementById('moveRight').onclick = function () { move(-0.2); };
  
  document.getElementById('focus2016').onclick = function() {
	  timeline.setWindow('2016-01-01', '2016-12-31');
  }
    document.getElementById('fit').onclick = function() {
    timeline.fit();
  };
		
    },
    error: function (err) {
     console.log('Error', err);
      if (err.status === 0) {
        alert('Failed to load data/basic.json.\nPlease run this example on a server.');
      }
      else {
        alert('Failed to load data/basic.json.');
      }
    }
  });
});
</script>
</body>
</html>
<?php
mysqli_free_result($equipos);
?>

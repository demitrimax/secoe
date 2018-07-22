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

$condicion = "";
$subdir = NULL;
$tequipo = NULL;

if (isset($_GET['tconsulta'])) {
  $colname_tconsulta = $_GET['tconsulta'];
  if ($colname_tconsulta == "TTAL_SAO") { $condicion = "WHERE cat_equipos.SUBDIR = 1 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9)"; }

if ($colname_tconsulta == "TTAL_SDCA") { $condicion = "WHERE cat_equipos.SUBDIR = 2 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9)"; }

if ($colname_tconsulta == "TTAL_SAS") { $condicion = "WHERE cat_equipos.SUBDIR = 3 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9)"; }

if ($colname_tconsulta == "TTAL_SCGNA") { $condicion = "WHERE cat_equipos.SUBDIR = 4 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9)"; }

if ($colname_tconsulta == "TTAL_SCT") { $condicion = "WHERE cat_equipos.SUBDIR = 5 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9)"; }

if ($colname_tconsulta == "TTAL_SCNC") { $condicion = "WHERE cat_equipos.SUBDIR = 6 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9)"; }

if ($colname_tconsulta == "PMXSAO") { $condicion = "WHERE cat_equipos.SUBDIR = 1 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia = 2"; }
if ($colname_tconsulta == "CIASAO") { $condicion = "WHERE cat_equipos.SUBDIR = 1 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia <> 2"; }

if ($colname_tconsulta == "PMXSDCA") { $condicion = "WHERE cat_equipos.SUBDIR = 2 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia = 2"; }

if ($colname_tconsulta == "CIASDCA") { $condicion = "WHERE cat_equipos.SUBDIR = 2 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia <> 2"; }

if ($colname_tconsulta == "PMXSAS") { $condicion = "WHERE cat_equipos.SUBDIR = 3 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia = 2"; }

if ($colname_tconsulta == "CIASAS") { $condicion = "WHERE cat_equipos.SUBDIR = 3 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia <> 2"; }

if ($colname_tconsulta == "PMXSCGNA") { $condicion = "WHERE cat_equipos.SUBDIR = 4 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia = 2"; }

if ($colname_tconsulta == "CIASCGNA") { $condicion = "WHERE cat_equipos.SUBDIR = 4 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8) AND cat_equipos.Cia <> 2"; }

if ($colname_tconsulta == "PMXSCT") { $condicion = "WHERE cat_equipos.SUBDIR = 5 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia = 2"; }

if ($colname_tconsulta == "CIASCT") { $condicion = "WHERE cat_equipos.SUBDIR = 5 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia <> 2"; }

if ($colname_tconsulta == "PMXSCNC") { $condicion = "WHERE cat_equipos.SUBDIR = 6 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia = 2"; }

if ($colname_tconsulta == "CIASCNC") { $condicion = "WHERE cat_equipos.SUBDIR = 6 AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND
(cat_equipos.TEquipo = 1 OR cat_equipos.TEquipo = 2 OR cat_equipos.TEquipo = 3 OR cat_equipos.TEquipo = 4 OR cat_equipos.TEquipo = 8 OR cat_equipos.TEquipo = 9) AND cat_equipos.Cia <> 2"; }

if ($colname_tconsulta == "AE_SAO") {
	$subdir = 1;
	$tequipo = 1;
	}
if ($colname_tconsulta == "EMP_SAO") {
	$subdir = 1;
	$tequipo = 9;
	}
if ($colname_tconsulta == "MOD_SAO") {
	$subdir = 1;
	$tequipo = 2;
	}
if ($colname_tconsulta == "SS_SAO") {
	$subdir = 1;
	$tequipo = 3;
	}
if ($colname_tconsulta == "T_SAO") {
	$subdir = 1;
	$tequipo = '4 OR cat_equipos.TEquipo = 8';
	}
if ($colname_tconsulta == "AE_SDCA") {
	$subdir = 2;
	$tequipo = 1;
	}
if ($colname_tconsulta == "EMP_SDCA") {
	$subdir = 2;
	$tequipo = 9;
	}
if ($colname_tconsulta == "MOD_SDCA") {
	$subdir = 2;
	$tequipo = 2;
	}
if ($colname_tconsulta == "SS_SDCA") {
	$subdir = 2;
	$tequipo = 3;
	}
if ($colname_tconsulta == "T_SDCA") {
	$subdir = 2;
	$tequipo = '4 OR cat_equipos.TEquipo = 8';
	}
if ($colname_tconsulta == "AE_SPAS") {
	$subdir = 3;
	$tequipo = 1;
	}
if ($colname_tconsulta == "EMP_SPAS") {
	$subdir = 3;
	$tequipo = 9;
	}
if ($colname_tconsulta == "MOD_SPAS") {
	$subdir = 3;
	$tequipo = 2;
	}
if ($colname_tconsulta == "SS_SPAS") {
	$subdir = 3;
	$tequipo = 3;
	}
if ($colname_tconsulta == "T_SPAS") {
	$subdir = 3;
	$tequipo = '4 OR cat_equipos.TEquipo = 8';
	}
if ($colname_tconsulta == "AE_CGNA") {
	$subdir = 4;
	$tequipo = 1;
	}
if ($colname_tconsulta == "EMP_CGNA") {
	$subdir = 4;
	$tequipo = 9;
	}
if ($colname_tconsulta == "MOD_CGNA") {
	$subdir = 4;
	$tequipo = 2;
	}
if ($colname_tconsulta == "SS_CGNA") {
	$subdir = 4;
	$tequipo = 3;
	}
if ($colname_tconsulta == "T_CGNA") {
	$subdir = 4;
	$tequipo = '4 OR cat_equipos.TEquipo = 8';
	}
if ($colname_tconsulta == "AE_SPCT") {
	$subdir = 5;
	$tequipo = 1;
	}
if ($colname_tconsulta == "EMP_SPCT") {
	$subdir = 5;
	$tequipo = 9;
	}
if ($colname_tconsulta == "MOD_SPCT") {
	$subdir = 5;
	$tequipo = 2;
	}
if ($colname_tconsulta == "SS_SPCT") {
	$subdir = 5;
	$tequipo = 3;
	}
if ($colname_tconsulta == "T_SPCT") {
	$subdir = 5;
	$tequipo = '4 OR cat_equipos.TEquipo = 8';
	}
if ($colname_tconsulta == "AE_CNC") {
	$subdir = 6;
	$tequipo = 1;
	}
if ($colname_tconsulta == "EMP_CNC") {
	$subdir = 6;
	$tequipo = 9;
	}
if ($colname_tconsulta == "MOD_CNC") {
	$subdir = 6;
	$tequipo = 2;
	}
if ($colname_tconsulta == "SS_CNC") {
	$subdir = 6;
	$tequipo = 3;
	}
if ($colname_tconsulta == "T_CNC") {
	$subdir = 6;
	$tequipo = '4 OR cat_equipos.TEquipo = 8';
	}

if ((!$subdir==NULL) and (!$tequipo==NULL)) {
  		$condicion = "WHERE cat_equipos.SUBDIR = $subdir AND (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2 OR cat_equipos.ESTATUS = 5) AND (cat_equipos.TEquipo = $tequipo)"; 
		}

}
		//echo $condicion.'<br>';
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_equipos = sprintf("SELECT cat_equipos.idEquipo, cat_equipos.CLVE_EQUIPO, cat_equipos.Equipo, cat_tipoequipo.Tipo, cat_equipocaracteristicas.Caracteristicas, cat_cias.InicialCia, cat_cias.NombreCia, cat_equipos.marcamalacate, cat_equipos.HP, cat_equipos.TA, cat_equipos.CAPPERF, cat_equipos.SECCSINDICAL, cat_estatus.SEMAFORO, cat_estatus.ESTATUS, cat_estatus.DESCRIPCION, cat_activos.ACTIVO_CORTO, cat_activos.ACTIVO, cat_equipos.TEquipo AS CLV_TEQUIPO, cat_equipos.Cia AS CLV_CIA, cat_equipos.ESTATUS AS CLV_ESTATUS, cat_equipos.ACTIVO AS CLV_ACTIVO, cat_equipos.SUBDIR AS CLV_SUBDIR FROM cat_equipos INNER JOIN cat_tipoequipo ON cat_tipoequipo.idtequipo = cat_equipos.TEquipo INNER JOIN cat_equipocaracteristicas ON cat_equipocaracteristicas.IdCar = cat_equipos.Caracteristicas INNER JOIN cat_cias ON cat_cias.id_cia = cat_equipos.Cia INNER JOIN cat_estatus ON cat_estatus.ID_ESTATUS = cat_equipos.ESTATUS LEFT JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo %s ;", $condicion);
//echo $query_equipos;
$equipos = mysql_query($query_equipos, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_equipos = mysqli_fetch_assoc($equipos);
$totalRows_equipos = mysqli_num_rows($equipos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Equipos Listados</title>
<style type="text/css">
<!--
body {
	font: 100%/1.4 Verdana, Arial, Helvetica, sans-serif;
	background-color: #42413C;
	margin: 0;
	padding: 0;
	color: #000;
}

/* ~~ Selectores de elemento/etiqueta ~~ */
ul, ol, dl { /* Debido a las diferencias existentes entre los navegadores, es recomendable no añadir relleno ni márgenes en las listas. Para lograr coherencia, puede especificar las cantidades deseadas aquí o en los elementos de lista (LI, DT, DD) que contienen. Recuerde que lo que haga aquí se aplicará en cascada en la lista .nav, a no ser que escriba un selector más específico. */
	padding: 0;
	margin: 0;
}
h1, h2, h3, h4, h5, h6, p {
	margin-top: 0;	 /* la eliminación del margen superior resuelve un problema que origina que los márgenes escapen de la etiqueta div contenedora. El margen inferior restante lo mantendrá separado de los elementos de que le sigan. */
	padding-right: 15px;
	padding-left: 15px; /* la adición de relleno a los lados del elemento dentro de las divs, en lugar de en las divs propiamente dichas, elimina todas las matemáticas de modelo de cuadro. Una div anidada con relleno lateral también puede usarse como método alternativo. */
}
a img { /* este selector elimina el borde azul predeterminado que se muestra en algunos navegadores alrededor de una imagen cuando está rodeada por un vínculo */
	border: none;
}
/* ~~ La aplicación de estilo a los vínculos del sitio debe permanecer en este orden (incluido el grupo de selectores que crea el efecto hover -paso por encima-). ~~ */
a:link {
	color: #42413C;
	text-decoration: underline; /* a no ser que aplique estilos a los vínculos para que tengan un aspecto muy exclusivo, es recomendable proporcionar subrayados para facilitar una identificación visual rápida */
}
a:visited {
	color: #6E6C64;
	text-decoration: underline;
}
a:hover, a:active, a:focus { /* este grupo de selectores proporcionará a un usuario que navegue mediante el teclado la misma experiencia de hover (paso por encima) que experimenta un usuario que emplea un ratón. */
	text-decoration: none;
}

/* ~~ este contenedor de anchura fija rodea a todos los demás elementos ~~ */
.container {
	width: 1024px;
	background-color: #FFF;
	margin: 0 auto; /* el valor automático de los lados, unido a la anchura, centra el diseño */
}

/* ~~ Esta es la información de diseño. ~~ 

1) El relleno sólo se sitúa en la parte superior y/o inferior de la div. Los elementos situados dentro de esta div tienen relleno a los lados. Esto le ahorra las "matemáticas de modelo de cuadro". Recuerde que si añade relleno o borde lateral a la div propiamente dicha, éste se añadirá a la anchura que defina para crear la anchura *total*. También puede optar por eliminar el relleno del elemento en la div y colocar una segunda div dentro de ésta sin anchura y el relleno necesario para el diseño deseado.

*/
.content {

	padding: 10px 0;
}

/* ~~ clases float/clear varias ~~ */
.fltrt {  /* esta clase puede utilizarse para que un elemento flote en la parte derecha de la página. El elemento flotante debe preceder al elemento junto al que debe aparecer en la página. */
	float: right;
	margin-left: 8px;
}
.fltlft { /* esta clase puede utilizarse para que un elemento flote en la parte izquierda de la página. El elemento flotante debe preceder al elemento junto al que debe aparecer en la página. */
	float: left;
	margin-right: 8px;
}
.clearfloat { /* esta clase puede situarse en una <br /> o div vacía como elemento final tras la última div flotante (dentro de #container) si se elimina overflow:hidden en .container */
	clear:both;
	height:0;
	font-size: 1px;
	line-height: 0px;
}
-->
</style>
<script src="../js/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="../DataTables/media/css/jquery.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="../DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="../DataTables/extensions/Responsive/css/responsive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="../DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>

	
	<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
	$('#equipost')
	.addClass( 'nowrap' )
	.DataTable(
	{
	 responsive: true,
	"language": {
                "url": "../DataTables/spanish/spanish.json"
				}
	});
	
} );

	</script>
</head>

<body>

<div class="container">
  <div class="content">
    <h1>Equipos <?PHP echo $colname_tconsulta; ?></h1>
    <p>&nbsp;</p>
    <table class="display" id="equipost" cellspacing="0" width="100%">
          <thead>
            <tr>
              <td>No. Equipo</td>
              <td>Equipo</td>
              <td>Tipo equipo</td>
              <td>Características</td>
              <td>Compañía</td>
              <td>Activo</td>
              <td>Estatus </td>
            </tr>
      </thead>
            <tbody>
            <?php do { ?>
              <tr>
                <td><a href="detalle_equipo.php?idEquipo=<?php echo $row_equipos['idEquipo']; ?>"><?php echo $row_equipos['CLVE_EQUIPO']; ?></a></td>
                <td><a href="../detalle_equipo.php?idEquipo=<?php echo $row_equipos['idEquipo']; ?>"><?php echo $row_equipos['Equipo']; ?></a></td>
                <td><?php echo $row_equipos['Tipo']; ?></td>
                <td><?php echo $row_equipos['Caracteristicas']; ?></td>
                <td title="<?php echo $row_equipos['NombreCia']; ?>"><?php echo $row_equipos['InicialCia']; ?></td>
                <td title="<?php echo $row_equipos['ACTIVO']; ?>"> <?php echo $row_equipos['ACTIVO_CORTO']; ?></td>
                <td> <img src="../images/sem/sem_<?php echo $row_equipos['SEMAFORO']; ?>.png" width="16" height="16" title="<?php echo utf8_encode($row_equipos['DESCRIPCION']); ?>"></td>
              </tr>
              <?php } while ($row_equipos = mysqli_fetch_assoc($equipos)); ?>
      </tbody>
    </table>
    <!-- end .content --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($equipos);
?>

<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "consulta,auditor,admin";
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

$MM_restrictGoTo = "index.html";
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
date_default_timezone_set('America/Monterrey');

//SUPONES QUE EL USUARIO SE LOGEO CORRECTAMENTE
 $usuario = $_SESSION['MM_Username'];
 $fechahora = date("Y-m-d H:i:s");
 $pagina_actual = $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
 $ipadress = $_SERVER['REMOTE_ADDR'];
 mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_log = "INSERT INTO registros (pagina, usuario, fechahora, ip) VALUES ('$pagina_actual', '$usuario', '$fechahora', '$ipadress')";
//echo $query_log;
$registros = mysqli_query($ResEquipos, $query_log) or die(mysql_error($ResEquipos));

 
//
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_CATEquipos = "SELECT
cat_equipos.idEquipo,
cat_equipos.CLVE_EQUIPO,
cat_equipos.Equipo,
cat_tipoequipo.Tipo,
cat_equipocaracteristicas.Caracteristicas,
cat_cias.InicialCia,
cat_cias.NombreCia,
cat_equipos.marcamalacate,
cat_equipos.HP,
cat_equipos.TA,
cat_equipos.CAPPERF,
cat_equipos.SECCSINDICAL,
cat_estatus.SEMAFORO,
cat_estatus.ESTATUS,
cat_estatus.DESCRIPCION,
cat_activos.ACTIVO_CORTO,
cat_activos.ACTIVO
FROM
cat_equipos
INNER JOIN cat_tipoequipo ON cat_tipoequipo.idtequipo = cat_equipos.TEquipo
INNER JOIN cat_equipocaracteristicas ON cat_equipocaracteristicas.IdCar = cat_equipos.Caracteristicas
INNER JOIN cat_cias ON cat_cias.id_cia = cat_equipos.Cia
INNER JOIN cat_estatus ON cat_estatus.ID_ESTATUS = cat_equipos.ESTATUS
LEFT JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo
WHERE cat_equipos.ESTATUS <> 3";

if (isset($_GET['EqBaja'])) {
	$query_CATEquipos = "SELECT
cat_equipos.idEquipo,
cat_equipos.CLVE_EQUIPO,
cat_equipos.Equipo,
cat_tipoequipo.Tipo,
cat_equipocaracteristicas.Caracteristicas,
cat_cias.InicialCia,
cat_cias.NombreCia,
cat_equipos.marcamalacate,
cat_equipos.HP,
cat_equipos.TA,
cat_equipos.CAPPERF,
cat_equipos.SECCSINDICAL,
cat_estatus.SEMAFORO,
cat_estatus.ESTATUS,
cat_estatus.DESCRIPCION,
cat_activos.ACTIVO_CORTO,
cat_activos.ACTIVO
FROM
cat_equipos
INNER JOIN cat_tipoequipo ON cat_tipoequipo.idtequipo = cat_equipos.TEquipo
INNER JOIN cat_equipocaracteristicas ON cat_equipocaracteristicas.IdCar = cat_equipos.Caracteristicas
INNER JOIN cat_cias ON cat_cias.id_cia = cat_equipos.Cia
INNER JOIN cat_estatus ON cat_estatus.ID_ESTATUS = cat_equipos.ESTATUS
LEFT JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo
WHERE cat_equipos.ESTATUS = 3";
}

if (isset($_GET['EqOperando'])) {
	$query_CATEquipos = "SELECT
cat_equipos.idEquipo,
cat_equipos.CLVE_EQUIPO,
cat_equipos.Equipo,
cat_tipoequipo.Tipo,
cat_equipocaracteristicas.Caracteristicas,
cat_cias.InicialCia,
cat_cias.NombreCia,
cat_equipos.marcamalacate,
cat_equipos.HP,
cat_equipos.TA,
cat_equipos.CAPPERF,
cat_equipos.SECCSINDICAL,
cat_estatus.SEMAFORO,
cat_estatus.ESTATUS,
cat_estatus.DESCRIPCION,
cat_activos.ACTIVO_CORTO,
cat_activos.ACTIVO
FROM
cat_equipos
INNER JOIN cat_tipoequipo ON cat_tipoequipo.idtequipo = cat_equipos.TEquipo
INNER JOIN cat_equipocaracteristicas ON cat_equipocaracteristicas.IdCar = cat_equipos.Caracteristicas
INNER JOIN cat_cias ON cat_cias.id_cia = cat_equipos.Cia
INNER JOIN cat_estatus ON cat_estatus.ID_ESTATUS = cat_equipos.ESTATUS
LEFT JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo WHERE cat_equipos.ESTATUS = 1";
}

if (isset($_GET['EqDocumen'])) {
	$query_CATEquipos = "SELECT
cat_equipos.idEquipo,
cat_equipos.CLVE_EQUIPO,
cat_equipos.Equipo,
cat_tipoequipo.Tipo,
cat_equipocaracteristicas.Caracteristicas,
cat_cias.InicialCia,
cat_cias.NombreCia,
cat_equipos.marcamalacate,
cat_equipos.HP,
cat_equipos.TA,
cat_equipos.CAPPERF,
cat_equipos.SECCSINDICAL,
cat_estatus.SEMAFORO,
cat_estatus.ESTATUS,
cat_estatus.DESCRIPCION,
cat_activos.ACTIVO_CORTO,
cat_activos.ACTIVO
FROM
cat_equipos
INNER JOIN cat_tipoequipo ON cat_tipoequipo.idtequipo = cat_equipos.TEquipo
INNER JOIN cat_equipocaracteristicas ON cat_equipocaracteristicas.IdCar = cat_equipos.Caracteristicas
INNER JOIN cat_cias ON cat_cias.id_cia = cat_equipos.Cia
INNER JOIN cat_estatus ON cat_estatus.ID_ESTATUS = cat_equipos.ESTATUS
LEFT JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo WHERE cat_equipos.ESTATUS = 4";
}

if (isset($_GET['Inactivos'])) {
	$query_CATEquipos = "SELECT
cat_equipos.idEquipo,
cat_equipos.CLVE_EQUIPO,
cat_equipos.Equipo,
cat_tipoequipo.Tipo,
cat_equipocaracteristicas.Caracteristicas,
cat_cias.InicialCia,
cat_cias.NombreCia,
cat_equipos.marcamalacate,
cat_equipos.HP,
cat_equipos.TA,
cat_equipos.CAPPERF,
cat_equipos.SECCSINDICAL,
cat_estatus.SEMAFORO,
cat_estatus.ESTATUS,
cat_estatus.DESCRIPCION,
cat_activos.ACTIVO_CORTO,
cat_activos.ACTIVO
FROM
cat_equipos
INNER JOIN cat_tipoequipo ON cat_tipoequipo.idtequipo = cat_equipos.TEquipo
INNER JOIN cat_equipocaracteristicas ON cat_equipocaracteristicas.IdCar = cat_equipos.Caracteristicas
INNER JOIN cat_cias ON cat_cias.id_cia = cat_equipos.Cia
INNER JOIN cat_estatus ON cat_estatus.ID_ESTATUS = cat_equipos.ESTATUS
LEFT JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo 
WHERE cat_equipos.ESTATUS = 2";
}

if (isset($_GET['EqPemex'])) {
	$query_CATEquipos = "SELECT
cat_equipos.idEquipo,
cat_equipos.CLVE_EQUIPO,
cat_equipos.Equipo,
cat_tipoequipo.Tipo,
cat_equipocaracteristicas.Caracteristicas,
cat_cias.InicialCia,
cat_cias.NombreCia,
cat_equipos.marcamalacate,
cat_equipos.HP,
cat_equipos.TA,
cat_equipos.CAPPERF,
cat_equipos.SECCSINDICAL,
cat_estatus.SEMAFORO,
cat_estatus.ESTATUS,
cat_estatus.DESCRIPCION,
cat_activos.ACTIVO_CORTO,
cat_activos.ACTIVO
FROM
cat_equipos
INNER JOIN cat_tipoequipo ON cat_tipoequipo.idtequipo = cat_equipos.TEquipo
INNER JOIN cat_equipocaracteristicas ON cat_equipocaracteristicas.IdCar = cat_equipos.Caracteristicas
INNER JOIN cat_cias ON cat_cias.id_cia = cat_equipos.Cia
INNER JOIN cat_estatus ON cat_estatus.ID_ESTATUS = cat_equipos.ESTATUS
LEFT JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo 
WHERE (cat_equipos.ESTATUS = 1 OR cat_equipos.ESTATUS = 2) AND cat_equipos.Cia = 2";
}


if (isset($_GET['todos'])) {
	$query_CATEquipos = "SELECT
cat_equipos.idEquipo,
cat_equipos.CLVE_EQUIPO,
cat_equipos.Equipo,
cat_tipoequipo.Tipo,
cat_equipocaracteristicas.Caracteristicas,
cat_cias.InicialCia,
cat_cias.NombreCia,
cat_equipos.marcamalacate,
cat_equipos.HP,
cat_equipos.TA,
cat_equipos.CAPPERF,
cat_equipos.SECCSINDICAL,
cat_estatus.SEMAFORO,
cat_estatus.ESTATUS,
cat_estatus.DESCRIPCION,
cat_activos.ACTIVO_CORTO,
cat_activos.ACTIVO
FROM
cat_equipos
INNER JOIN cat_tipoequipo ON cat_tipoequipo.idtequipo = cat_equipos.TEquipo
INNER JOIN cat_equipocaracteristicas ON cat_equipocaracteristicas.IdCar = cat_equipos.Caracteristicas
INNER JOIN cat_cias ON cat_cias.id_cia = cat_equipos.Cia
INNER JOIN cat_estatus ON cat_estatus.ID_ESTATUS = cat_equipos.ESTATUS
LEFT JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo";
}

$CATEquipos = mysqli_query($ResEquipos, $query_CATEquipos) or die(mysqli_error($ResEquipos));
$row_CATEquipos = mysqli_fetch_assoc($CATEquipos);
$totalRows_CATEquipos = mysqli_num_rows($CATEquipos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Catálogo de Equipos</title>
<meta charset="utf-8">
<meta name="format-detection" content="telephone=no" />
<link rel="icon" href="images/favicon.ico">
<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/style.css">
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
	<link rel="stylesheet" type="text/css" href="DataTables/extensions/Buttons/css/buttons.dataTables.min.css">
	
	<script type="text/javascript" charset="utf8" src="DataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="DataTables/extensions/Responsive/css/responsive.dataTables.css"> 
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" charset="utf8" src="DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.html5.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/extensions/Buttons/js/buttons.print.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/examples/resources/demo.js">
	</script>
    <script type="text/javascript" language="javascript" src="DataTables/extensions/jszip.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/extensions/pdfmake.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="DataTables/extensions/vfs_fonts.js">
	</script>

	
	<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#equipost tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
    } );
 
    // DataTable
    var table = $('#equipost').DataTable({
		dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
			'print'
        ],
		stateSave: true,
	 	responsive: true,
		});
 
    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
} );

	</script>
    
    <script language="javascript">
$(document).ready(function() {
     $(".botonExcel").click(function(event) {
     $("#datos_a_enviar").val( $("<div>").append( $("#equipost").eq(0).clone()).html());
     $("#FormularioExportacion").submit();
});
});
</script>

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
               <li class="current"><a href="index.php">Inicio</a></li>
               <li><a href="about.html">Acerca de</a></li>
               <li><a href="services.html">Objetivos</a></li>
               <li><a href="http://intranet.pemex.com/os/pep/unp/Paginas/default.aspx">Intranet PPS</a></li>
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
<section id="content">
  <div class="container">
    <div class="row">
      <div class="grid_12">
        <h3>Catálogo de Equipos</h3>
         <div id="banner" class="container" >
         <input type="button" value="Todos" onClick="location='cat_equipos.php?todos=1'">
         <input type="button" value="Operando" onClick="location='cat_equipos.php?EqOperando=1'">
         <input type="button" value="de Baja" onClick="location='cat_equipos.php?EqBaja=1'">
         <input type="button" value="Eq. Pemex" onClick="location='cat_equipos.php?EqPemex=1'">
    <table class="display" id="equipost" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>No. Equipo</th>
              <th>Equipo</th>
              <th>Tipo equipo</th>
              <th>Características</th>
              <th>Compañía</th>
              <th>Activo</th>
              <th>Estatus </th>
            </tr>
      </thead>
       <tbody>
            <?php do { ?>
              <tr>
                <td><a href="detalle_equipo.php?idEquipo=<?php echo $row_CATEquipos['idEquipo']; ?>"><?php echo $row_CATEquipos['CLVE_EQUIPO']; ?></a></td>
                <td><a href="detalle_equipo.php?idEquipo=<?php echo $row_CATEquipos['idEquipo']; ?>&<?php echo $_SERVER['QUERY_STRING']; ?>"><?php echo $row_CATEquipos['Equipo']; ?></a></td>
                <td><?php echo $row_CATEquipos['Tipo']; ?></td>
                <td><?php echo $row_CATEquipos['Caracteristicas']; ?></td>
                <td title="<?php echo $row_CATEquipos['NombreCia']; ?>"><?php echo $row_CATEquipos['InicialCia']; ?></td>
                <td title="<?php echo $row_CATEquipos['ACTIVO']; ?>"> <?php echo $row_CATEquipos['ACTIVO_CORTO']; ?></td>
                <td> <img src="images/sem/sem_<?php echo $row_CATEquipos['SEMAFORO']; ?>.png" width="16" height="16" title="<?php echo utf8_encode($row_CATEquipos['DESCRIPCION']); ?>"> <?php echo utf8_encode($row_CATEquipos['ESTATUS']); ?> </td>
              </tr>
              <?php } while ($row_CATEquipos = mysqli_fetch_assoc($CATEquipos)); ?>
      </tbody>
      <tfoot>
            <tr>
              <td></td>
              <th>Equipo</th>
              <th>Tipo equipo</th>
              <th>Características</th>
              <th>Compañía</th>
              <th>Activo</th>
              <th>Estatus </th>
            </tr>
       </tfoot>
    </table>
    <p>&nbsp;</p>
          <p><a href="Add_Equipo.php">Agregar Equipos</a> | <a href="cat_equipos.php?EqBaja=1" >Equipos de Baja</a> | <a href="cat_equipos.php?EqOperando=1" >Equipos Operando</a> | <a href="cat_equipos.php?EqDocumen=1" >En Documentación</a> | <a href="cat_equipos.php?Inactivos=1" >Inactivos </a> |<a href="cat_equipos.php?todos=1">Todos</a>| <a href="catalogoequipos.php">Base Plana</a> | <a href="compara_status.php">Comparar Estatus GME</a> | </p>
<form action="ExportExcel.php" method="post" target="_blank" id="FormularioExportacion">
<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
</form> 
    </div>
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
mysqli_free_result($CATEquipos);
?>
<?php
if( isset($_GET['idEquipo']) ) {
   if (isset($_GET['programa'])) {
	   get_equipos($_GET['idEquipo'], $_GET['programa']);
   }
   	else {
	 get_equipos($_GET['idEquipo']);
	}
} else {
  die("Solicitud no válida.");
}

function get_equipos( $id, $programa = null) {
 
  //Cambia por los detalles de tu base datos
  include('../Connections/OtherConnections.php');
  /* $dbserver = "localhost";
  $dbuser = "root";
  $password = "";
  $dbname = "SECOE";
 */

  $database = new mysqli($dbserver, $dbuser, $password, $dbname);

  if($database->connect_errno) {
    die("No se pudo conectar a la base de datos");
  }
//Sanitize ipnut y preparar query
  $query_programa = "";
  if (isset($programa)) {
	if (is_array($programa)) {
		//$programa = array_map('intval', $programa);
		$query_programa = " AND programoficial IN ('".  implode( "','", $programa ) ."')"; 
		}
	else
		{
		$query_programa = " AND programoficial = '" . $programa."'";	
			}
	}
  
  if( is_array($id) ) {
    $id = array_map('intval', $id);
    $querywhere = "WHERE `idequipo` IN (" . implode( ',', $id ) . ")" . $query_programa;
  } else {
    $id = intval($id);
    $querywhere = "WHERE `idequipo` = " . $id . $query_programa;
  }

//echo $querywhere;
	

 if ( $result = $database->query( "SELECT * FROM `v_intervenciones` " . $querywhere ) ) {
	if( $result->num_rows > 0 ) {
		$row_res = mysqli_fetch_assoc($result);
		$contador = 1;  
        $jsondata = array();
		
		  do {
	  $fini_dia = date("d", strtotime($row_res['fec_ini']));
	  $fini_mes = date("m", strtotime($row_res['fec_ini']));
	  $fini_ano = date("Y", strtotime($row_res['fec_ini']));
	  $ffin_dia = date("d", strtotime($row_res['fec_fin']));
	  $ffin_mes = date("m", strtotime($row_res['fec_fin']));
	  $ffin_ano = date("Y", strtotime($row_res['fec_fin']));
	  $tooltip =  utf8_encode($row_res['pozo'])." | ".$row_res['intervencion']." | ".date("d-m-Y", strtotime($row_res['fec_ini']))." a ".date("d-m-Y", strtotime($row_res['fec_fin']));
	  
	  //$jsondata[]["id"] = $contador;
	  $jsondata['datos'][] = array('id' => $contador, 'group' => $row_res['unicoprograma'] ,'content' => utf8_encode($row_res['pozo']), 'start' => $fini_ano."-".$fini_mes."-".$fini_dia." 00:00:00", 'end' => $ffin_ano."-".$ffin_mes."-".$ffin_dia." 23:59:00", 'className' => $row_res['clasecolor'], 'title' => $tooltip);
	  $contador++;

		} while ($row_res = mysqli_fetch_assoc($result));
 
 if ( $result = $database->query( "SELECT programa, unicoprograma FROM v_intervenciones " . $querywhere." GROUP BY programa;" ) ) {
	if( $result->num_rows > 0 ) {
		$row_res = mysqli_fetch_assoc($result);
		$contador = 1;  
        //$jsondata = array();
		
		  do {

	  $jsondata['grupos'][] = array('id' => $row_res['unicoprograma'] ,'content' => $row_res['programa'], 'value' => $contador);
	  
	  $contador++;

		} while ($row_res = mysqli_fetch_assoc($result));
		$jsondata['consulta']= array('query' => $querywhere);
	}
	}
 
   } else {
 
     $jsondata["success"] = false;
     $jsondata["data"] = array(
       'message' => 'No se encontró ningún resultado.'
     );
	 $jsondata["data"] = array(
      'message' => $database->error, 'query' =>$querywhere
    );
 
   }
 
   $result->close();
 
  } else {
 
    $jsondata["success"] = false;
    $jsondata["data"] = array(
      'message' => $database->error, 'query' =>$querywhere
    );
 
  }
 
  header('Content-type: application/json; charset=utf-8');
  echo json_encode($jsondata, JSON_PRETTY_PRINT);
  //$a = array ('a' => 'manzana', 'b' => 'banana', 'c' => array ('x', 'y', 'z'));
	//print_r ($jsondata);
 
  $database->close();
 
}

exit(); 


?>

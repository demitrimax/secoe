<?php
if( isset($_GET['idEquipo']) ) {
  get_equipos($_GET['idEquipo']);
} else {
  die("Solicitud no válida.");
}

function get_equipos( $id ) {
 
  //Cambia por los detalles de tu base datos
   include('../Connections/OtherConnections.php');
  /* $dbserver = "localhost";
  $dbuser = "moises";
  $password = "pemex11";
  $dbname = "SECOE"; */
 
  $database = new mysqli($dbserver, $dbuser, $password, $dbname);

  if($database->connect_errno) {
    die("No se pudo conectar a la base de datos");
  }
//Sanitize ipnut y preparar query
  if( is_array($id) ) {
    $id = array_map('intval', $id);
    $querywhere = "WHERE `idequipo` IN (" . implode( ',', $id ) . ")";
  } else {
    $id = intval($id);
    $querywhere = "WHERE `idequipo` = " . $id;
  }
//SELECT programa, unicoprograma FROM v_intervenciones WHERE idequipo = %s GROUP BY programa
 if ( $result = $database->query( "SELECT programa, unicoprograma FROM v_intervenciones " . $querywhere." GROUP BY programa;" ) ) {
	if( $result->num_rows > 0 ) {
		$row_res = mysqli_fetch_assoc($result);
		$contador = 1;  
        $jsondata = array();
		
		  do {

	  $jsondata[] = array('id' => $row_res['unicoprograma'] ,'content' => $row_res['programa'], 'value' => $contador);
	  
	  $contador++;

		} while ($row_res = mysqli_fetch_assoc($result));
 
   } else {
 
     $jsondata["success"] = false;
     $jsondata["data"] = array(
       'message' => 'No se encontró ningún resultado.'
     );
 
   }
 
   $result->close();
 
  } else {
 
    $jsondata["success"] = false;
    $jsondata["data"] = array(
      'message' => $database->error
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

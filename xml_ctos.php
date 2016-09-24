<?php require_once('../Connections/ResEquipos.php'); ?>
<?php
  crear(); //Creamos el archivo
  leer();  //Luego lo leemos
   
  //Para crear el archivo
  function crear(){
      $bd = new mysqli('localhost', 'moises', 'pemex11', 'secoe') or die("Error al conectar con MySQL-> ".mysql_error());
	  
    
       $stmt = $bd->prepare("SELECT NO_CONTRATO, tarifa, F_INICIO, F_FIN, EQUIPOID, ESQUEMA, NombreCia, Equipo, Caracteristicas FROM list_contratos");
       $stmt->execute();
       $stmt->store_result();
       $stmt->bind_result($NO_CONTRATO, $tarifa, $F_INICIO, $F_FIN, $EQUIPOID, $ESQUEMA, $NombreCia, $Equipo, $Caracteristicas); 
  
       $xml = new DomDocument('1.0', 'UTF-8');
  
      $contratos = $xml->createElement('contratos');
      $contratos = $xml->appendChild($contratos);
  
      while($stmt->fetch()) {
     
        $contrato = $xml->createElement('contrato');
        $contrato = $contratos->appendChild($contrato);
 
        $nodo_nocontrato = $xml->createElement('NoContrato', $NO_CONTRATO);
        $nodo_nocontrato = $contrato->appendChild($nodo_nocontrato);
		
		$nodo_tarifa = $xml->createElement('tarifa', $tarifa);
        $nodo_tarifa = $contrato->appendChild($nodo_tarifa); 
		
		$nodo_finicio = $xml->createElement('finicio', $F_INICIO);
        $nodo_finicio = $contrato->appendChild($nodo_finicio); 
		
		$nodo_ffin = $xml->createElement('ffin', $F_FIN);
        $nodo_ffin = $contrato->appendChild($nodo_ffin);
		
		$nodo_equipoid = $xml->createElement('equipoid', $EQUIPOID);
        $nodo_equipoid = $contrato->appendChild($nodo_equipoid);
		
		$nodo_esquema = $xml->createElement('esquema', $ESQUEMA);
        $nodo_esquema = $contrato->appendChild($nodo_esquema);
        
		$nodo_nomcia = $xml->createElement('nomcia', utf8_encode($NombreCia));
        $nodo_nomcia = $contrato->appendChild($nodo_nomcia);
		
		$nodo_equipo = $xml->createElement('equipo', $Equipo);
        $nodo_equipo = $contrato->appendChild($nodo_equipo);
		
       }
    
       $stmt->free_result();
       $bd->close();
    
      $xml->formatOutput = true;
      $el_xml = $xml->saveXML();
      $xml->save('contratos.xml');
      
      //Mostramos el XML puro
      echo "<p><b>El XML ha sido creado.... Mostrando en texto plano:</b></p>".
           htmlentities($el_xml)."<br/><hr>";
  }
  
  //Para leerlo
  function leer(){
    echo "<p><b>Ahora mostrandolo con estilo</b></p>";
  
    $xml = simplexml_load_file('contratos.xml');
    $salida ="";
  
    foreach($xml->contrato as $item){
      $salida .=
        "<b>No. Contrato</b> " . $item->NoContrato . "<br/>".
		"<b>Tarifa:</b> " . $item->tarifa . "<br/>".
		"<b>Fecha Inicio:</b> " . $item->finicio . "<br/>"."<br/><hr/>";
    }
  
    echo $salida;
  }
?>
<?php require_once('../../Connections/ResEquipos.php'); ?>
<?php
//ini_set('max_execution_time', 300); //300 seconds = 5 minutes


mysqli_select_db($ResEquipos, $database_ResEquipos);
$colname_programa = "POT-IV-2017_PRE_7SEP";
$query_pot = "SELECT * FROM pot WHERE pot.programoficial = '$colname_programa' ; "; //limito el número de registros
$pot = mysqli_query($ResEquipos, $query_pot) or die(mysql_error($ResEquipos));
$row_pot = mysqli_fetch_assoc($pot);
$totalRows_pot = mysqli_num_rows($pot);

$link = mysqli_connect("localhost", "moises", "pemex11");
mysqli_select_db($link, "SECOE");
//$tildes = $link->query("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente

	 		$fechaInicio = date("Y-m-d");
			$fechaTermino = date("Y-m-d");
			$DatosTabla = array();
			$conteo = 0;
			$vanterior = 0;
	 do { 
			$fechaini = date($row_pot['fec_ini']);
			$fechafin = date($row_pot['fec_fin']);
			if($fechaini < $fechaInicio) {
				$fechaInicio  = $fechaini;
			}
    		if($fechafin > $fechaTermino) {
        		$fechaTermino = $fechafin;
			}
			// Meter los datos de la tabla en un array
			$DatosTabla[] = array('idprog'=>$row_pot['id_prog'], 'programa'=>$row_pot['programa'], 'pozo'=>$row_pot['pozo'], 'intervencion'=>$row_pot['intervencion'], 'fec_ini'=>$row_pot['fec_ini'], 'fec_fin'=>$row_pot['fec_fin'], 'Equipo'=>$row_pot['idequipo'], 'ACTIVO'=>$row_pot['cv_activo']);
		 	$conteo++;
			$avance = intval(($conteo / $totalRows_pot)*100);
			//echo $conteo." / ".$totalRows_pot." = ".$avance."<br>";
			if ($avance > $vanterior) {
					echo $avance."% | ";
					$vanterior = $avance;
			}
      } while ($row_pot = mysqli_fetch_assoc($pot));
	 echo "\nSe procesaron $conteo Registros\n";
 
			echo "Fecha de Inicio: " . $fechaInicio."\n";
		 	echo "Fecha de Termino: ". $fechaTermino."\n";

			// Función para restar fechas
			function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
			{
    			$datetime1 = date_create($date_1);
    			$datetime2 = date_create($date_2);
    			$interval = date_diff($datetime1, $datetime2);
    			return $interval->format($differenceFormat);
			}
			// Termina Función para restar fechas

			$MesIni = date("m", strtotime($fechaInicio));
			$MesFin = date("m", strtotime($fechaTermino));
			$MesFin2 = substr($fechaTermino, 5, 2);
			$anoIni = date("Y", strtotime($fechaInicio));
			$anoFin = date("Y", strtotime($fechaTermino));
			$anoFin2 = substr($fechaTermino, 0, 4);

			$Tanos = $anoFin2 - $anoIni;
			$mesesini = 13 - $MesIni;
			$tmeses = (($Tanos - 1) * 12) + $mesesini + $MesFin2;
			echo "Mes Inicial: ".$MesIni."\n";
			echo "Mes Final: ".$MesFin2."\n";
			echo "Año Inicio:".$anoIni."\n";
			echo "Año Final: ".$anoFin2."\n";
			echo "Total de Años:".$Tanos."\n";
			echo "Total de Meses:".$tmeses."\n";
			$meses = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
			$fechas = array($tmeses);
			$cont = 0;
			// procesando espacio temporal de fechas
			$avance = 0;
      		$vanterior = 0;
			echo "Generando espacio temporal fechas \n";
			for ($Ano = $anoIni; $Ano <= $anoFin2; $Ano++) {
				//echo $Ano . " | ";
					for ($m = $MesIni; $m <= 12; $m++) {
						$MesSel = $meses[$MesIni-1];
						$MesIni++;
						//echo $MesSel."|\n";
						$fechas[$cont] =  $MesSel."/".$Ano;
						//$fechas[$cont] = $m."/".$Ano;
						//echo "Mes: " . $fechas[$cont]."\n";
						$cont++;
						$avance = intval(($cont / $tmeses )*100);
						if ($avance > $vanterior) {
							echo $avance."% | ";
							$vanterior = $avance;
            				//flush();
            				//ob_flush();
							}
						if ($m == $MesFin2 && $Ano == $anoFin2) {
							break;
							}
				}
				$MesIni = 1;
			}
	
echo "\nLimpiando la base de datos \n";
//$sqlLimpiar = "TRUNCATE secoe.operatividad;";
//aqui se limpia la base de datos eliminando los registros si esque ya existian
$losvalores = implode(',', array_column($DatosTabla,'idprog'));
$sqlLimpiar = "DELETE FROM operatividad WHERE operatividad.id_pot IN ($losvalores);";
//echo $sqlLimpiar."\n";
            mysqli_query($link, $sqlLimpiar);

			echo "\n Generando los registros para el programa operativo: $colname_programa \n";
			$diasxmes = 0;
			$contador = 0;
			$totalRegistros = count($DatosTabla);
			$vanterior = 0;
			for ($i = 0; $i < count($DatosTabla); $i++) {
				$ano_ini = substr($DatosTabla[$i]['fec_ini'], 0, 4);
				$ano_fin = substr($DatosTabla[$i]['fec_fin'], 0, 4);
				$mes_ini = $meses[substr($DatosTabla[$i]['fec_ini'], 5, 2)-1];
				$mes_fin = $meses[substr($DatosTabla[$i]['fec_fin'], 5, 2)-1];
				$fecha_inicio = date("Y-m-d", strtotime($DatosTabla[$i]['fec_ini']));
				$fecha_termino = date("Y-m-d", strtotime($DatosTabla[$i]['fec_fin']));
				for ($f = 0; $f < count($fechas); $f++) {
						$Mes = substr($fechas[$f],0,3);
						$Ano = substr($fechas[$f],4,4);

						//primer día del mes y año.
						$Fecha = date($Ano."-".$Mes."-01");
						$FFecha = date("Y-m-d", strtotime($Ano."-".$Mes."-01"));
												//echo $Fecha."\n";
												//echo $FFecha."\n";

						//Obtener la fecha del ultimo dia del mes y año.
						$FMesNum = date("m", strtotime($Ano."-".$Mes."-01"));
						$Fultimodiames = date("d",(mktime(0,0,0,$FMesNum+1,1,$Ano)-1));;
						$Fultimodiafecha = $Ano."-".$FMesNum."-".$Fultimodiames;


					//Verificar que el mes y el año de inicio sean el mismo de la fecha de inicio
					if  ($Mes == $mes_ini &&  $Ano == $ano_ini)  {
						//echo "$i Se cumple Mes ($Mes) = mes inicial ($mes_ini) y Ano($Ano) = Ano Inicial($ano_ini)\n";
						$MesNum = date("m", strtotime($Ano."-".$Mes."-01"));
						$ultimodiames = date("d",(mktime(0,0,0,$MesNum+1,1,$Ano)-1));;
						$ultimodiafecha = $Ano."-".$MesNum."-".$ultimodiames;
						$diasxmes =  dateDifference($fecha_inicio, $ultimodiafecha) + 1;
						//echo "$i OP1 Se cumple Operacion: $ultimodiafecha - $fecha_inicio = $diasxmes \n";
					}
					//Verificar que el mes y el año de termino sean el mismo de la fecha de termino
					if ($Ano == $ano_fin && $Mes == $mes_fin) {
						
						$MesNum = date("m", strtotime($ano_fin."-".$mes_fin."-01"));
						$ultimodiames = date("d",(mktime(0,0,0,$MesNum+1,1,$ano_fin)-1));;
						$ultimodiafecha = $ano_fin."-".$MesNum."-".$ultimodiames;
						$diasxmes =  dateDifference($FFecha, $fecha_termino) + 1;
						//echo "$i OP2 Operacion: $FFecha - $fecha_termino = $diasxmes \n";
					}
					// Verificar que la fecha este en rango de fechas
					if ($fecha_inicio < $FFecha  && $fecha_termino > $Fultimodiafecha ) {
						$diasxmes = $Fultimodiames;
						//echo "$i OP3 Operacion $fecha_inicio < $FFecha y $fecha_termino > $Fultimodiafecha = $diasxmes \n";
					}
					//Verificar que la fecha de inicio y fecha de termino esten en el mismo mes
					if ($Ano == $ano_ini && $Ano == $ano_fin && $Mes == $mes_ini && $Mes == $mes_fin) {
						$diasxmes =  dateDifference($fecha_inicio, $fecha_termino) + 1;
						//echo "$i OP4 $Ano = $ano_ini y $Ano == $ano_fin y  $Mes = $mes_ini y $Mes == $mes_fin | Operacion: $ultimodiafecha - $fecha_termino = $diasxmes \n";
					}
					if ($diasxmes > 0) {
						//echo $i." - ".$DatosTabla[$i]['idprog']." | ".$DatosTabla[$i]['pozo']."| Inter: ".$DatosTabla[$i]['intervencion']."| Fecha Inicio: ".$fecha_inicio."| Fecha Termino: ".$fecha_termino."| Mes/Año: ".$Mes."/".$Ano." | Dias: ".$diasxmes."| MesIni = ".$mes_ini."|AñoIni = ".$ano_ini."<br>";
						$sqlInsert = "INSERT INTO operatividad (id_pot, mes_ano, dias, fecha) values ( ".$DatosTabla[$i]['idprog'] .",'".$Mes."/".$Ano."', ".$diasxmes.", '".$FFecha."');";
						//echo $sqlInsert."\n";
            mysqli_query($link, $sqlInsert);
            $contador++;
						$diasxmes = 0;
            $avance = intval(($i / $totalRegistros )*100);
            //echo "Avance: ".$avance."| Contador: ". $contador."<br>";
              if ($avance > $vanterior) {
  							echo $avance."%\n";
  							$vanterior = $avance;
              				//flush();
              				//ob_flush();
  							}
					}

				}
			}
			$Final = "Procedimiento terminado, Se han generado ".$contador ." registros.";


echo $Final
?>
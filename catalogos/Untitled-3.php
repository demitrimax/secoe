<?php
header( 'Content-type: text/html; charset=utf-8' );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Documento sin título</title>
</head>
<body>
<p>Mostrar código <input type="button" value="Mostrar" onClick="MostrarOcultar('codigo1', 'mostrar')"> </p>
    	<script>
		function MostrarOcultar(elemt, mostrar) {
			if (mostrar == 'mostrar') {
				document.getElementById(elemt).style.display = 'block';
			}
			if (mostrar == 'ocultar') {
				document.getElementById(elemt).style.display = 'none';
			}
		}
        </script>
    	<div id="codigo1" style="display:none">
        	<p> Sentencia SQL : SELECT
cat_equipos.idEquipo,
cat_equipos.Equipo,
cat_equipos.TEquipo,
if(cat_equipos.Cia=2,1,2) AS PMXCIA,
cat_subdir.SIGLAS_SUBDIR,
cat_activos.ACTIVO_CORTO,
v_ult_contrato.NO_CONTRATO,
cat_esquemacto.ESQ_CORTO AS ESQUEMA,
if (cat_equipos.Cia=2,'ADMON',cat_esquemacto.ESQUEMA) AS ESQUEMA2,
SUM(IF(operatividad.mes_ano= 'JAN/2016', operatividad.dias, 0)) AS ENE,
SUM(IF(operatividad.mes_ano= 'FEB/2016', operatividad.dias, 0)) AS FEB,
SUM(IF(operatividad.mes_ano= 'MAR/2016', operatividad.dias, 0)) AS MAR,  
SUM(IF(operatividad.mes_ano= 'APR/2016', operatividad.dias, 0)) AS ABR,
SUM(IF(operatividad.mes_ano= 'MAY/2016', operatividad.dias, 0)) AS MAY,
SUM(IF(operatividad.mes_ano= 'JUN/2016', operatividad.dias, 0)) AS JUN,
SUM(IF(operatividad.mes_ano= 'JUL/2016', operatividad.dias, 0)) AS JUL,
SUM(IF(operatividad.mes_ano= 'AUG/2016', operatividad.dias, 0)) AS AGO,
SUM(IF(operatividad.mes_ano= 'SEP/2016', operatividad.dias, 0)) AS SEP,
SUM(IF(operatividad.mes_ano= 'OCT/2016', operatividad.dias, 0)) AS OCT,     
SUM(IF(operatividad.mes_ano= 'NOV/2016', operatividad.dias, 0)) AS NOV,
SUM(IF(operatividad.mes_ano= 'DEC/2016', operatividad.dias, 0)) AS DIC,
if(cat_equipos.Cia=2,1,2) AS PMXCIA   
FROM
cat_equipos
INNER JOIN pot ON cat_equipos.idEquipo = pot.idequipo
INNER JOIN operatividad ON pot.id_prog = operatividad.id_pot
INNER JOIN cat_subdir ON cat_equipos.SUBDIR = cat_subdir.id_subdir
INNER JOIN cat_activos ON cat_equipos.ACTIVO = cat_activos.id_activo
LEFT JOIN v_ult_contrato ON cat_equipos.idEquipo = v_ult_contrato.EQUIPOID
LEFT JOIN cat_esquemacto ON v_ult_contrato.ESQUEMA = cat_esquemacto.IDESQ
WHERE
pot.programoficial = 'POT I 2017' AND pot.intervencion IN ('PER','TER','RMA','RME')
GROUP BY 
cat_equipos.idEquipo </p>
            <input type="button" value="Ocultar" onClick="MostrarOcultar('codigo1', 'ocultar')">
      	</div>
    Recorrido de la tabla....terminado. 
</body>
</html>
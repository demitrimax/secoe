<?php require_once('Connections/ResEquipos.php'); ?>
<?php
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_lastpot = "SELECT pot.programoficial FROM pot WHERE pot.id_prog = (select max(pot.id_prog) from pot)";
$lastpot = mysql_query($query_lastpot, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_lastpot = mysqli_fetch_assoc($lastpot);
$totalRows_lastpot = mysqli_num_rows($lastpot);

$colname_ultimopot = $row_lastpot['programoficial'];
if (isset($_GET['programa'])) {
	$colname_ultimopot = $_GET['programa'];
}

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_programas = "SELECT DISTINCT pot.programoficial FROM pot";
$programas = mysql_query($query_programas, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_programas = mysqli_fetch_assoc($programas);
$totalRows_programas = mysqli_num_rows($programas);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_operamensual = "SELECT
cat_equipos.idEquipo,
IF(ISNULL(cat_equipos.CLVE_EQUIPO), cat_equipos.idEquipo, cat_equipos.CLVE_EQUIPO ) AS cvequipo,
cat_equipos.Equipo,
cat_tipoequipo.Tipo,
if(cat_equipos.Cia=2,'PMX','CIA') AS PMXCIA,
SUM(IF(operatividad.mes_ano= 'JAN/2016', operatividad.dias, 0)) AS 'ENE/16',
SUM(IF(operatividad.mes_ano= 'FEB/2016', operatividad.dias, 0)) AS 'FEB/16',
SUM(IF(operatividad.mes_ano= 'MAR/2016', operatividad.dias, 0)) AS 'MAR/16',
SUM(IF(operatividad.mes_ano= 'APR/2016', operatividad.dias, 0)) AS 'ABR/16',
SUM(IF(operatividad.mes_ano= 'MAY/2016', operatividad.dias, 0)) AS 'MAY/16',
SUM(IF(operatividad.mes_ano= 'JUN/2016', operatividad.dias, 0)) AS 'JUN/16',
SUM(IF(operatividad.mes_ano= 'JUL/2016', operatividad.dias, 0)) AS 'JUL/16',
SUM(IF(operatividad.mes_ano= 'AUG/2016', operatividad.dias, 0)) AS 'AGO/16',
SUM(IF(operatividad.mes_ano= 'SEP/2016', operatividad.dias, 0)) AS 'SEP/16',
SUM(IF(operatividad.mes_ano= 'OCT/2016', operatividad.dias, 0)) AS 'OCT/16',   
SUM(IF(operatividad.mes_ano= 'NOV/2016', operatividad.dias, 0)) AS 'NOV/16',
SUM(IF(operatividad.mes_ano= 'DEC/2016', operatividad.dias, 0)) AS 'DIC/16',
SUM(IF(operatividad.mes_ano= 'JAN/2017', operatividad.dias, 0)) AS 'ENE/17',
SUM(IF(operatividad.mes_ano= 'FEB/2017', operatividad.dias, 0)) AS 'FEB/17',
SUM(IF(operatividad.mes_ano= 'MAR/2017', operatividad.dias, 0)) AS 'MAR/17',
SUM(IF(operatividad.mes_ano= 'APR/2017', operatividad.dias, 0)) AS 'ABR/17',
SUM(IF(operatividad.mes_ano= 'MAY/2017', operatividad.dias, 0)) AS 'MAY/17',
SUM(IF(operatividad.mes_ano= 'JUN/2017', operatividad.dias, 0)) AS 'JUN/17',
SUM(IF(operatividad.mes_ano= 'JUL/2017', operatividad.dias, 0)) AS 'JUL/17',
SUM(IF(operatividad.mes_ano= 'AUG/2017', operatividad.dias, 0)) AS 'AGO/17',
SUM(IF(operatividad.mes_ano= 'SEP/2017', operatividad.dias, 0)) AS 'SEP/17',
SUM(IF(operatividad.mes_ano= 'OCT/2017', operatividad.dias, 0)) AS 'OCT/17',
SUM(IF(operatividad.mes_ano= 'NOV/2017', operatividad.dias, 0)) AS 'NOV/17',
SUM(IF(operatividad.mes_ano= 'DEC/2017', operatividad.dias, 0)) AS 'DIC/17',
SUM(IF(operatividad.mes_ano= 'JAN/2018', operatividad.dias, 0)) AS 'ENE/18',
SUM(IF(operatividad.mes_ano= 'FEB/2018', operatividad.dias, 0)) AS 'FEB/18',
SUM(IF(operatividad.mes_ano= 'MAR/2018', operatividad.dias, 0)) AS 'MAR/18',
SUM(IF(operatividad.mes_ano= 'APR/2018', operatividad.dias, 0)) AS 'ABR/18',
SUM(IF(operatividad.mes_ano= 'MAY/2018', operatividad.dias, 0)) AS 'MAY/18',
SUM(IF(operatividad.mes_ano= 'JUN/2018', operatividad.dias, 0)) AS 'JUN/18',
SUM(IF(operatividad.mes_ano= 'JUL/2018', operatividad.dias, 0)) AS 'JUL/18',
SUM(IF(operatividad.mes_ano= 'AUG/2018', operatividad.dias, 0)) AS 'AGO/18',
SUM(IF(operatividad.mes_ano= 'SEP/2018', operatividad.dias, 0)) AS 'SEP/18',
SUM(IF(operatividad.mes_ano= 'OCT/2018', operatividad.dias, 0)) AS 'OCT/18',
SUM(IF(operatividad.mes_ano= 'NOV/2018', operatividad.dias, 0)) AS 'NOV/18',
SUM(IF(operatividad.mes_ano= 'DEC/2018', operatividad.dias, 0)) AS 'DIC/18',
SUM(IF(operatividad.mes_ano= 'JAN/2019', operatividad.dias, 0)) AS 'ENE/19',
SUM(IF(operatividad.mes_ano= 'FEB/2019', operatividad.dias, 0)) AS 'FEB/19',
SUM(IF(operatividad.mes_ano= 'MAR/2019', operatividad.dias, 0)) AS 'MAR/19',
SUM(IF(operatividad.mes_ano= 'APR/2019', operatividad.dias, 0)) AS 'ABR/19',
SUM(IF(operatividad.mes_ano= 'MAY/2019', operatividad.dias, 0)) AS 'MAY/19',
SUM(IF(operatividad.mes_ano= 'JUN/2019', operatividad.dias, 0)) AS 'JUN/19',
SUM(IF(operatividad.mes_ano= 'JUL/2019', operatividad.dias, 0)) AS 'JUL/19',
SUM(IF(operatividad.mes_ano= 'AUG/2019', operatividad.dias, 0)) AS 'AGO/19',
SUM(IF(operatividad.mes_ano= 'SEP/2019', operatividad.dias, 0)) AS 'SEP/19',
SUM(IF(operatividad.mes_ano= 'OCT/2019', operatividad.dias, 0)) AS 'OCT/19',
SUM(IF(operatividad.mes_ano= 'NOV/2019', operatividad.dias, 0)) AS 'NOV/19',
SUM(IF(operatividad.mes_ano= 'DEC/2019', operatividad.dias, 0)) AS 'DIC/19',
SUM(IF(operatividad.mes_ano= 'JAN/2020', operatividad.dias, 0)) AS 'ENE/20',
SUM(IF(operatividad.mes_ano= 'FEB/2020', operatividad.dias, 0)) AS 'FEB/20',
SUM(IF(operatividad.mes_ano= 'MAR/2020', operatividad.dias, 0)) AS 'MAR/20',
SUM(IF(operatividad.mes_ano= 'APR/2020', operatividad.dias, 0)) AS 'ABR/20',
SUM(IF(operatividad.mes_ano= 'MAY/2020', operatividad.dias, 0)) AS 'MAY/20',
SUM(IF(operatividad.mes_ano= 'JUN/2020', operatividad.dias, 0)) AS 'JUN/20',
SUM(IF(operatividad.mes_ano= 'JUL/2020', operatividad.dias, 0)) AS 'JUL/20',
SUM(IF(operatividad.mes_ano= 'AUG/2020', operatividad.dias, 0)) AS 'AGO/20',
SUM(IF(operatividad.mes_ano= 'SEP/2020', operatividad.dias, 0)) AS 'SEP/20',
SUM(IF(operatividad.mes_ano= 'OCT/2020', operatividad.dias, 0)) AS 'OCT/20',
SUM(IF(operatividad.mes_ano= 'NOV/2020', operatividad.dias, 0)) AS 'NOV/20',
SUM(IF(operatividad.mes_ano= 'DEC/2020', operatividad.dias, 0)) AS 'DIC/20',
SUM(IF(operatividad.mes_ano= 'JAN/2021', operatividad.dias, 0)) AS 'ENE/21',
SUM(IF(operatividad.mes_ano= 'FEB/2021', operatividad.dias, 0)) AS 'FEB/21',
SUM(IF(operatividad.mes_ano= 'MAR/2021', operatividad.dias, 0)) AS 'MAR/21',
SUM(IF(operatividad.mes_ano= 'APR/2021', operatividad.dias, 0)) AS 'ABR/21',
SUM(IF(operatividad.mes_ano= 'MAY/2021', operatividad.dias, 0)) AS 'MAY/21',
SUM(IF(operatividad.mes_ano= 'JUN/2021', operatividad.dias, 0)) AS 'JUN/21',
SUM(IF(operatividad.mes_ano= 'JUL/2021', operatividad.dias, 0)) AS 'JUL/21',
SUM(IF(operatividad.mes_ano= 'AUG/2021', operatividad.dias, 0)) AS 'AGO/21',
SUM(IF(operatividad.mes_ano= 'SEP/2021', operatividad.dias, 0)) AS 'SEP/21',
SUM(IF(operatividad.mes_ano= 'OCT/2021', operatividad.dias, 0)) AS 'OCT/21',
SUM(IF(operatividad.mes_ano= 'NOV/2021', operatividad.dias, 0)) AS 'NOV/21',
SUM(IF(operatividad.mes_ano= 'DEC/2021', operatividad.dias, 0)) AS 'DIC/21'
FROM
cat_equipos
INNER JOIN pot ON cat_equipos.idEquipo = pot.idequipo
INNER JOIN operatividad ON pot.id_prog = operatividad.id_pot
INNER JOIN cat_tipoequipo ON cat_equipos.TEquipo = cat_tipoequipo.idtequipo
WHERE
pot.programoficial = '$colname_ultimopot' AND pot.intervencion IN ('PER','TER','RMA','RME','EST','DESPL', 'TAP')
GROUP BY 
cat_equipos.idEquipo";
$operamensual = mysql_query($query_operamensual, $ResEquipos) or die(mysqli_error($ResEquipos));
$row_operamensual = mysqli_fetch_assoc($operamensual);
$totalRows_operamensual = mysqli_num_rows($operamensual);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BASE PLANA SECOE</title>
<link rel="stylesheet" href="css/responstable.css">
<style type="text/css">
@import url(//fonts.googleapis.com/css?family=Open+Sans:400);
@import url(//fonts.googleapis.com/css?family=Open+Sans:300);
@import url(//fonts.googleapis.com/css?family=Open+Sans:600);
@import url(//fonts.googleapis.com/css?family=Open+Sans:700);
@import url(//fonts.googleapis.com/css?family=Open+Sans:300italic);
body {
  position: relative;
  color: #000000;
  font: 300 14px/20px 'Open Sans', sans-serif;
}
</style>
</head>
<body>
Seleccione el programa operativo: <select name="ProgramaOP" id="ProgramaOP" onChange="AplicarFiltro(this)">
<?php do { 	?>
            	<option value="<?php echo $row_programas['programoficial']?>"<?php if (!(strcmp($row_programas['programoficial'], $colname_ultimopot))) {echo "selected=\"selected\"";} ?>><?php echo $row_programas['programoficial']?></option>
            <?php
				} while ($row_programas = mysqli_fetch_assoc($programas));
			?>
</select>
<table id="equipost" cellspacing="0" class="responstable">
          <thead>
            <tr>
              <th>IdEquipo</th>
              <th>Clv_Equipo</th>
              <th>Equipo</th>
              <th>Tipo</th>
              <th>PMXCIA</th>
              <th>ENE/16</th>
              <th>FEB/16</th>
              <th>MAR/16</th>
              <th>ABR/16</th>
              <th>MAY/16</th>
              <th>JUN/16</th>
              <th>JUL/16</th>
              <th>AGO/16</th>
              <th>SEP/16</th>
              <th>OCT/16</th>
              <th>NOV/16</th>
              <th>DIC/16</th>
              <th>ENE/17</th>
              <th>FEB/17</th>
              <th>MAR/17</th>
              <th>ABR/17</th>
              <th>MAY/17</th>
              <th>JUN/17</th>
              <th>JUL/17</th>
              <th>AGO/17</th>
              <th>SEP/17</th>
              <th>OCT/17</th>
              <th>NOV/17</th>
              <th>DIC/17</th>
              <th>ENE/18</th>
              <th>FEB/18</th>
              <th>MAR/18</th>
              <th>ABR/18</th>
              <th>MAY/18</th>
              <th>JUN/18</th>
              <th>JUL/18</th>
              <th>AGO/18</th>
              <th>SEP/18</th>
              <th>OCT/18</th>
              <th>NOV/18</th>
              <th>DIC/18</th>
              <th>ENE/19</th>
              <th>FEB/19</th>
              <th>MAR/19</th>
              <th>ABR/19</th>
              <th>MAY/19</th>
              <th>JUN/19</th>
              <th>JUL/19</th>
              <th>AGO/19</th>
              <th>SEP/19</th>
              <th>OCT/19</th>
              <th>NOV/19</th>
              <th>DIC/19</th>
			  <th>ENE/20</th>
              <th>FEB/20</th>
              <th>MAR/20</th>
              <th>ABR/20</th>
              <th>MAY/20</th>
              <th>JUN/20</th>
              <th>JUL/20</th>
              <th>AGO/20</th>
              <th>SEP/20</th>
              <th>OCT/20</th>
              <th>NOV/20</th>
              <th>DIC/20</th>
              <th>ENE/21</th>
              <th>FEB/21</th>
              <th>MAR/21</th>
              <th>ABR/21</th>
              <th>MAY/21</th>
              <th>JUN/21</th>
              <th>JUL/21</th>
              <th>AGO/21</th>
              <th>SEP/21</th>
              <th>OCT/21</th>
              <th>NOV/21</th>
              <th>DIC/21</th>
            </tr>
      </thead>
            <tbody>
            <?php do { ?>
              <tr>
                <td><?php echo $row_operamensual['idEquipo']; ?></td>
                <td><?php echo $row_operamensual['cvequipo']; ?></td>
                <td><?php echo $row_operamensual['Equipo']; ?></td>
                <td><?php echo $row_operamensual['Tipo']; ?></td>
                <td><?php echo $row_operamensual['PMXCIA']; ?></td>
                <td><?php echo $row_operamensual['ENE/16']; ?></td>
                <td><?php echo $row_operamensual['FEB/16']; ?></td>
                <td><?php echo $row_operamensual['MAR/16']; ?></td>
                <td><?php echo $row_operamensual['ABR/16']; ?></td>
                <td><?php echo $row_operamensual['MAY/16']; ?></td>
                <td><?php echo $row_operamensual['JUN/16']; ?></td>
                <td><?php echo $row_operamensual['JUL/16']; ?></td>
                <td><?php echo $row_operamensual['AGO/16']; ?></td>
                <td><?php echo $row_operamensual['SEP/16']; ?></td>
                <td><?php echo $row_operamensual['OCT/16']; ?></td>
                <td><?php echo $row_operamensual['NOV/16']; ?></td>
                <td><?php echo $row_operamensual['DIC/16']; ?></td>
                <td><?php echo $row_operamensual['ENE/17']; ?></td>
                <td><?php echo $row_operamensual['FEB/17']; ?></td>
                <td><?php echo $row_operamensual['MAR/17']; ?></td>
                <td><?php echo $row_operamensual['ABR/17']; ?></td>
                <td><?php echo $row_operamensual['MAY/17']; ?></td>
                <td><?php echo $row_operamensual['JUN/17']; ?></td>
                <td><?php echo $row_operamensual['JUL/17']; ?></td>
                <td><?php echo $row_operamensual['AGO/17']; ?></td>
                <td><?php echo $row_operamensual['SEP/17']; ?></td>
                <td><?php echo $row_operamensual['OCT/17']; ?></td>
                <td><?php echo $row_operamensual['NOV/17']; ?></td>
                <td><?php echo $row_operamensual['DIC/17']; ?></td>
                <td><?php echo $row_operamensual['ENE/18']; ?></td>
                <td><?php echo $row_operamensual['FEB/18']; ?></td>
                <td><?php echo $row_operamensual['MAR/18']; ?></td>
                <td><?php echo $row_operamensual['ABR/18']; ?></td>
                <td><?php echo $row_operamensual['MAY/18']; ?></td>
                <td><?php echo $row_operamensual['JUN/18']; ?></td>
                <td><?php echo $row_operamensual['JUL/18']; ?></td>
                <td><?php echo $row_operamensual['AGO/18']; ?></td>
                <td><?php echo $row_operamensual['SEP/18']; ?></td>
                <td><?php echo $row_operamensual['OCT/18']; ?></td>
                <td><?php echo $row_operamensual['NOV/18']; ?></td>
                <td><?php echo $row_operamensual['DIC/18']; ?></td>
                <td><?php echo $row_operamensual['ENE/19']; ?></td>
                <td><?php echo $row_operamensual['FEB/19']; ?></td>
                <td><?php echo $row_operamensual['MAR/19']; ?></td>
                <td><?php echo $row_operamensual['ABR/19']; ?></td>
                <td><?php echo $row_operamensual['MAY/19']; ?></td>
                <td><?php echo $row_operamensual['JUN/19']; ?></td>
                <td><?php echo $row_operamensual['JUL/19']; ?></td>
                <td><?php echo $row_operamensual['AGO/19']; ?></td>
                <td><?php echo $row_operamensual['SEP/19']; ?></td>
                <td><?php echo $row_operamensual['OCT/19']; ?></td>
                <td><?php echo $row_operamensual['NOV/19']; ?></td>
                <td><?php echo $row_operamensual['DIC/19']; ?></td>
                <td><?php echo $row_operamensual['ENE/20']; ?></td>
                <td><?php echo $row_operamensual['FEB/20']; ?></td>
                <td><?php echo $row_operamensual['MAR/20']; ?></td>
                <td><?php echo $row_operamensual['ABR/20']; ?></td>
                <td><?php echo $row_operamensual['MAY/20']; ?></td>
                <td><?php echo $row_operamensual['JUN/20']; ?></td>
                <td><?php echo $row_operamensual['JUL/20']; ?></td>
                <td><?php echo $row_operamensual['AGO/20']; ?></td>
                <td><?php echo $row_operamensual['SEP/20']; ?></td>
                <td><?php echo $row_operamensual['OCT/20']; ?></td>
                <td><?php echo $row_operamensual['NOV/20']; ?></td>
                <td><?php echo $row_operamensual['DIC/20']; ?></td>
                <td><?php echo $row_operamensual['ENE/21']; ?></td>
                <td><?php echo $row_operamensual['FEB/21']; ?></td>
                <td><?php echo $row_operamensual['MAR/21']; ?></td>
                <td><?php echo $row_operamensual['ABR/21']; ?></td>
                <td><?php echo $row_operamensual['MAY/21']; ?></td>
                <td><?php echo $row_operamensual['JUN/21']; ?></td>
                <td><?php echo $row_operamensual['JUL/21']; ?></td>
                <td><?php echo $row_operamensual['AGO/21']; ?></td>
                <td><?php echo $row_operamensual['SEP/21']; ?></td>
                <td><?php echo $row_operamensual['OCT/21']; ?></td>
                <td><?php echo $row_operamensual['NOV/21']; ?></td>
                <td><?php echo $row_operamensual['DIC/21']; ?></td>
                
              </tr>
              <?php } while ($row_operamensual = mysqli_fetch_assoc($operamensual)); ?>
      </tbody>
    </table>
</body>
</html>
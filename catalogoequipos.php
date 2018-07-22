<?php require_once('../Connections/ResEquipos.php'); ?>
<?php
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_CATEquipos = "SELECT * FROM detalleequipo_v2";
$CATEquipos = mysqli_query($ResEquipos, $query_CATEquipos) or die(mysqli_error($ResEquipos));
$row_CATEquipos = mysqli_fetch_assoc($CATEquipos);
$totalRows_CATEquipos = mysqli_num_rows($CATEquipos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CATALOGO DE EQUIPOS</title>
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
<table id="equipost" cellspacing="0" width="100%" class="responstable">
          <thead>
            <tr>
              <th>IdEquipo</th>
              <th>No. Equipo</th>
              <th>Equipo</th>
              <th>Nomb Corto</th>
              <th>Tipo equipo</th>
              <th>Tequipo</th>
              <th>Características</th>
              <th>Tarifa USD</th>
              <th>Clave Compañía</th>
              <th>Compañía</th>
              <th>Activo</th>
              <th>Estatus </th>
              <th>Esquema </th>
              <th>CIA/PMX </th>
              <th>PER/REP</th>
              <th>CUOTA ANEXO C</th>
            </tr>
      </thead>
            <tbody>
            <?php do { ?>
              <tr>
                <td><?php echo $row_CATEquipos['idEquipo']; ?></td>
                <td><?php echo $row_CATEquipos['CLVE_EQUIPO']; ?></td>
                <td><?php echo $row_CATEquipos['Equipo']; ?></td>
                <td><?php echo $row_CATEquipos['Equ_corto']; ?></td>
                <td><?php echo $row_CATEquipos['tipoequipo']; ?></td>
                <td><?php echo $row_CATEquipos['Tequip']; ?></td>
                <td><?php echo $row_CATEquipos['Caracteristicas']; ?></td>
                <td><?php echo number_format($row_CATEquipos['tarifa']); ?></td>
                <td><?php echo $row_CATEquipos['InicialCia']; ?></td>
                <td><?php echo $row_CATEquipos['NombreCia']; ?></td>
                <td><?php echo $row_CATEquipos['ACTIVO']; ?></td>
                <td><?php echo utf8_encode($row_CATEquipos['ESTATUS']); ?> </td>
                <td><?php echo $row_CATEquipos['esquema2']; ?></td>
                <td><?php echo $row_CATEquipos['PMXCIA']; ?></td>
                <td><?php echo $row_CATEquipos['PERREP']; ?></td>
                <td><?php echo number_format($row_CATEquipos['CuotaAnexoC']); ?></td>
              </tr>
              <?php } while ($row_CATEquipos = mysqli_fetch_assoc($CATEquipos)); ?>
      </tbody>
    </table>
</body>
</html>
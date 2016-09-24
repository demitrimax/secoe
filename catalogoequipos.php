<?php require_once('../Connections/ResEquipos.php'); ?>
<?php
mysql_select_db($database_ResEquipos, $ResEquipos);
$query_CATEquipos = "SELECT * FROM detalleequipo_v2";
$CATEquipos = mysql_query($query_CATEquipos, $ResEquipos) or die(mysql_error());
$row_CATEquipos = mysql_fetch_assoc($CATEquipos);
$totalRows_CATEquipos = mysql_num_rows($CATEquipos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CATALOGO DE EQUIPOS</title>
</head>
<body>
<table class="display" id="equipost" cellspacing="0" width="100%">
          <thead>
            <tr>
              <td>IdEquipo</td>
              <td>No. Equipo</td>
              <td>Equipo</td>
              <td>Tipo equipo</td>
              <td>Tequipo</td>
              <td>Características</td>
              <td>Tarifa</td>
              <td>Clave Compañía</td>
              <td>Compañía</td>
              <td>Activo</td>
              <td>Estatus </td>
              <td>Esquema </td>
              <td>CIA/PMX </td>
            </tr>
      </thead>
            <tbody>
            <?php do { ?>
              <tr>
                <td><?php echo $row_CATEquipos['idEquipo']; ?></td>
                <td><?php echo $row_CATEquipos['CLVE_EQUIPO']; ?></td>
                <td><?php echo $row_CATEquipos['Equipo']; ?></td>
                <td><?php echo $row_CATEquipos['tipoequipo']; ?></td>
                <td><?php echo $row_CATEquipos['Tequip']; ?></td>
                <td><?php echo $row_CATEquipos['Caracteristicas']; ?></td>
                <td><?php echo $row_CATEquipos['tarifa']; ?></td>
                <td><?php echo $row_CATEquipos['InicialCia']; ?></td>
                <td><?php echo $row_CATEquipos['NombreCia']; ?></td>
                <td><?php echo $row_CATEquipos['ACTIVO']; ?></td>
                <td><?php echo utf8_encode($row_CATEquipos['ESTATUS']); ?> </td>
                <td><?php echo $row_CATEquipos['esquema2']; ?></td>
                <td><?php echo $row_CATEquipos['PMXCIA']; ?></td>
              </tr>
              <?php } while ($row_CATEquipos = mysql_fetch_assoc($CATEquipos)); ?>
      </tbody>
    </table>
</body>
</html>
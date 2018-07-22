<?php require_once('../Connections/ResEquipos.php'); ?>
<?php
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_CATPozos = "SELECT * FROM v_cat_pozos";
$CATPozos = mysqli_query($ResEquipos, $query_CATPozos) or die(mysqli_error($ResEquipos));
$row_CATPozos = mysqli_fetch_assoc($CATPozos);
$totalRows_CATPozos = mysqli_num_rows($CATPozos);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CATALOGO DE POZOS</title>
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
              <th>Id</th>
              <th>Nombre Pozo</th>
              <th>Campo</th>
              <th>Número</th>
              <th>Tipo</th>
              <th>Modalidad</th>
              <th>Tirante de Agua</th>
              <th>Profundidad Vertical</th>
              <th>Prof Desarrollada</th>
              <th>Profundidad</th>
              <th>Activo</th>
              <th>Activo Corto</th>
              <th>UOP</th>
              <th>Unidad Operativa</th>
              <th>Idpozo_copia</th>
            </tr>
      </thead>
            <tbody>
            <?php do { ?>
              <tr>
                <td><?php echo $row_CATPozos['idpozo']; ?></td>
                <td><?php echo $row_CATPozos['nombrepozo']; ?></td>
                <td><?php echo $row_CATPozos['campo']; ?></td>
                <td><?php echo $row_CATPozos['numero']; ?></td>
                <td><?php echo $row_CATPozos['tipo']; ?></td>
                <td><?php echo $row_CATPozos['modalidad']; ?></td>
                <td><?php echo $row_CATPozos['tirante_agua']; ?></td>
                <td><?php echo $row_CATPozos['prof_ver']; ?></td>
                <td><?php echo $row_CATPozos['prof_des']; ?></td>
                <td><?php echo $row_CATPozos['profundidad']; ?></td>
                <td><?php echo $row_CATPozos['ACTIVO']; ?></td>
                <td><?php echo $row_CATPozos['ACTIVO_CORTO']; ?></td>
                <td><?php echo $row_CATPozos['UOP_CORTO']; ?> </td>
                <td><?php echo $row_CATPozos['UnidadOperativa']; ?></td>
                <td><?php echo $row_CATPozos['idpozo']; ?></td>
              </tr>
              <?php } while ($row_CATPozos = mysqli_fetch_assoc($CATPozos)); ?>
      </tbody>
    </table>
</body>
</html>
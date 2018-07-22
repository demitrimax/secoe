<?php require_once('../Connections/ResEquipos.php'); ?>
<?php


$colname_det_ctto = "-1";
if (isset($_GET['idctto'])) {
  $colname_det_ctto = $_GET['idctto'];
}
mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_det_ctto = sprintf("SELECT * FROM v_det_ctto WHERE ID_CTTO = %s", $colname_det_ctto);
$det_ctto = mysqli_query($ResEquipos, $query_det_ctto) or die(mysqli_error($ResEquipos));
$row_det_ctto = mysqli_fetch_assoc($det_ctto);
$totalRows_det_ctto = mysqli_num_rows($det_ctto);

mysqli_select_db($ResEquipos, $database_ResEquipos);
$query_detcto_coments = sprintf("SELECT
ctos_comentarios.id_coment,
ctos_comentarios.comentario,
ctos_comentarios.fecha,
ctos_comentarios.cto_asociado,
cat_tipo_comentario.tipo,
contrato.ID_CTTO
FROM
ctos_comentarios
INNER JOIN cat_tipo_comentario ON ctos_comentarios.tipo_coment = cat_tipo_comentario.id_tipo
INNER JOIN contrato ON ctos_comentarios.cto_asociado = contrato.NO_CONTRATO
WHERE ID_CTTO = %s
ORDER BY
ctos_comentarios.fecha ASC", $colname_det_ctto);
$detcto_coments = mysqli_query($ResEquipos, $query_detcto_coments) or die(mysqli_error($ResEquipos));
$row_detcto_coments = mysqli_fetch_assoc($detcto_coments);
$totalRows_detcto_coments = mysqli_num_rows($detcto_coments);
?>
<link rel="stylesheet" href="css/responstable.css">

<table width="100%" border="0" class="responstable">
   <tr>
    <th scope="row">Contrato:</td>
    
    <td> <?php echo $row_det_ctto['NO_CONTRATO']; ?></td>
  </tr>
   <tr>
    <th scope="row">Tipo:</td>
    
    <td> <?php echo $row_det_ctto['TIPOCTTO']; ?></td>
  </tr>

  <tr>
    <th scope="row">Fecha de Inicio</td>
    
    <td><?php echo date("d/m/y", strtotime($row_det_ctto['F_INICIO'])); ?></td>
  </tr>
  <tr>
    <th scope="row">Fecha de Término</th>
    <td><?php echo date("d/m/y", strtotime($row_det_ctto['F_FIN'])); ?></td>
  </tr>
  <tr>
    <th scope="row">Plazo:</th>
    <td><?php echo $row_det_ctto['PLAZO']; ?></td>
  </tr>
  <tr>
    <th scope="row">Tarífa</th>
    <td><?php echo number_format($row_det_ctto['TARIFA']); ?></td>
  </tr>
  <tr>
    <th scope="row">Esquema</th>
    <td><?php echo $row_det_ctto['ESQUEMA']; ?></td>
  </tr>
  <tr>
    <th scope="row">Compañía:</th>
    <td><?php echo $row_det_ctto['NombreCia']; ?></td>
  </tr>
  <tr>
    <th scope="row">Objeto del Contrato:</th>
    <td><?php echo $row_det_ctto['OBJETO_CTO']; ?></td>
  </tr>
  <tr>
    <th scope="row">Estatus</th>
    <td><?php echo $row_det_ctto['ESTATUS']; ?></td>
  </tr>
</table>
<?php if($row_detcto_coments>0) { ?>
<h4>Comentarios del Contrato:<br></h4>
<?php do { ?>
<strong>Fecha: </strong><?php echo $row_detcto_coments['fecha']; ?><br>
<strong><?php echo utf8_encode($row_detcto_coments['tipo']); ?>:</strong><?php echo utf8_encode($row_detcto_coments['comentario']); ?><br>
 <?php } while ($row_detcto_coments = mysqli_fetch_assoc($detcto_coments)); ?>
<?php } ?>
<form id="form1" name="form1" method="post" action="catalogos/edit_contrato.php?idctto=<?php echo $colname_det_ctto; ?>">
  <input type="hidden" name="REGRESAR" value="<?php echo $_SERVER['HTTP_REFERER'];?>" />
  <input type="submit" name="button" id="button" value="Modificar datos del Contrato" />
</form>
<form id="form2" name="form2" method="post" action="catalogos/detalle_ctto.php?no_ctto=<?php echo $row_det_ctto['NO_CONTRATO']; ?>">
  <input type="hidden" name="REGRESAR" value="<?php echo $_SERVER['HTTP_REFERER'];?>" />
  <input type="submit" name="button" id="button" value="Agregar Comentarios al contrato" />
</form>
<?php
mysqli_free_result($det_ctto);
?>
<?php

// idespecialidad
// descripcion
// estado

?>
<?php if ($especialidad->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $especialidad->TableCaption() ?></h4> -->
<table id="tbl_especialidadmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($especialidad->idespecialidad->Visible) { // idespecialidad ?>
		<tr id="r_idespecialidad">
			<td><?php echo $especialidad->idespecialidad->FldCaption() ?></td>
			<td<?php echo $especialidad->idespecialidad->CellAttributes() ?>>
<span id="el_especialidad_idespecialidad" class="form-group">
<span<?php echo $especialidad->idespecialidad->ViewAttributes() ?>>
<?php echo $especialidad->idespecialidad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($especialidad->descripcion->Visible) { // descripcion ?>
		<tr id="r_descripcion">
			<td><?php echo $especialidad->descripcion->FldCaption() ?></td>
			<td<?php echo $especialidad->descripcion->CellAttributes() ?>>
<span id="el_especialidad_descripcion" class="form-group">
<span<?php echo $especialidad->descripcion->ViewAttributes() ?>>
<?php echo $especialidad->descripcion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($especialidad->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $especialidad->estado->FldCaption() ?></td>
			<td<?php echo $especialidad->estado->CellAttributes() ?>>
<span id="el_especialidad_estado" class="form-group">
<span<?php echo $especialidad->estado->ViewAttributes() ?>>
<?php echo $especialidad->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

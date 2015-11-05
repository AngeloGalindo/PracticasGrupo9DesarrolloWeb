<?php

// descripcion
// idtipo_turno
// idhospital
// estado

?>
<?php if ($turno->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $turno->TableCaption() ?></h4> -->
<table id="tbl_turnomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($turno->descripcion->Visible) { // descripcion ?>
		<tr id="r_descripcion">
			<td><?php echo $turno->descripcion->FldCaption() ?></td>
			<td<?php echo $turno->descripcion->CellAttributes() ?>>
<span id="el_turno_descripcion" class="form-group">
<span<?php echo $turno->descripcion->ViewAttributes() ?>>
<?php echo $turno->descripcion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($turno->idtipo_turno->Visible) { // idtipo_turno ?>
		<tr id="r_idtipo_turno">
			<td><?php echo $turno->idtipo_turno->FldCaption() ?></td>
			<td<?php echo $turno->idtipo_turno->CellAttributes() ?>>
<span id="el_turno_idtipo_turno" class="form-group">
<span<?php echo $turno->idtipo_turno->ViewAttributes() ?>>
<?php echo $turno->idtipo_turno->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($turno->idhospital->Visible) { // idhospital ?>
		<tr id="r_idhospital">
			<td><?php echo $turno->idhospital->FldCaption() ?></td>
			<td<?php echo $turno->idhospital->CellAttributes() ?>>
<span id="el_turno_idhospital" class="form-group">
<span<?php echo $turno->idhospital->ViewAttributes() ?>>
<?php echo $turno->idhospital->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($turno->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $turno->estado->FldCaption() ?></td>
			<td<?php echo $turno->estado->CellAttributes() ?>>
<span id="el_turno_estado" class="form-group">
<span<?php echo $turno->estado->ViewAttributes() ?>>
<?php echo $turno->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

<?php

// idhabitacion
// numero
// idsala
// estado

?>
<?php if ($habitacion->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $habitacion->TableCaption() ?></h4> -->
<table id="tbl_habitacionmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($habitacion->idhabitacion->Visible) { // idhabitacion ?>
		<tr id="r_idhabitacion">
			<td><?php echo $habitacion->idhabitacion->FldCaption() ?></td>
			<td<?php echo $habitacion->idhabitacion->CellAttributes() ?>>
<span id="el_habitacion_idhabitacion" class="form-group">
<span<?php echo $habitacion->idhabitacion->ViewAttributes() ?>>
<?php echo $habitacion->idhabitacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($habitacion->numero->Visible) { // numero ?>
		<tr id="r_numero">
			<td><?php echo $habitacion->numero->FldCaption() ?></td>
			<td<?php echo $habitacion->numero->CellAttributes() ?>>
<span id="el_habitacion_numero" class="form-group">
<span<?php echo $habitacion->numero->ViewAttributes() ?>>
<?php echo $habitacion->numero->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($habitacion->idsala->Visible) { // idsala ?>
		<tr id="r_idsala">
			<td><?php echo $habitacion->idsala->FldCaption() ?></td>
			<td<?php echo $habitacion->idsala->CellAttributes() ?>>
<span id="el_habitacion_idsala" class="form-group">
<span<?php echo $habitacion->idsala->ViewAttributes() ?>>
<?php echo $habitacion->idsala->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($habitacion->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $habitacion->estado->FldCaption() ?></td>
			<td<?php echo $habitacion->estado->CellAttributes() ?>>
<span id="el_habitacion_estado" class="form-group">
<span<?php echo $habitacion->estado->ViewAttributes() ?>>
<?php echo $habitacion->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

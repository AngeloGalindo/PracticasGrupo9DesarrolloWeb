<?php

// nombre
// apellido
// cui
// telefono
// direccion
// idturno
// estado

?>
<?php if ($doctor->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $doctor->TableCaption() ?></h4> -->
<table id="tbl_doctormaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($doctor->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $doctor->nombre->FldCaption() ?></td>
			<td<?php echo $doctor->nombre->CellAttributes() ?>>
<span id="el_doctor_nombre" class="form-group">
<span<?php echo $doctor->nombre->ViewAttributes() ?>>
<?php echo $doctor->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($doctor->apellido->Visible) { // apellido ?>
		<tr id="r_apellido">
			<td><?php echo $doctor->apellido->FldCaption() ?></td>
			<td<?php echo $doctor->apellido->CellAttributes() ?>>
<span id="el_doctor_apellido" class="form-group">
<span<?php echo $doctor->apellido->ViewAttributes() ?>>
<?php echo $doctor->apellido->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($doctor->cui->Visible) { // cui ?>
		<tr id="r_cui">
			<td><?php echo $doctor->cui->FldCaption() ?></td>
			<td<?php echo $doctor->cui->CellAttributes() ?>>
<span id="el_doctor_cui" class="form-group">
<span<?php echo $doctor->cui->ViewAttributes() ?>>
<?php echo $doctor->cui->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($doctor->telefono->Visible) { // telefono ?>
		<tr id="r_telefono">
			<td><?php echo $doctor->telefono->FldCaption() ?></td>
			<td<?php echo $doctor->telefono->CellAttributes() ?>>
<span id="el_doctor_telefono" class="form-group">
<span<?php echo $doctor->telefono->ViewAttributes() ?>>
<?php echo $doctor->telefono->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($doctor->direccion->Visible) { // direccion ?>
		<tr id="r_direccion">
			<td><?php echo $doctor->direccion->FldCaption() ?></td>
			<td<?php echo $doctor->direccion->CellAttributes() ?>>
<span id="el_doctor_direccion" class="form-group">
<span<?php echo $doctor->direccion->ViewAttributes() ?>>
<?php echo $doctor->direccion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($doctor->idturno->Visible) { // idturno ?>
		<tr id="r_idturno">
			<td><?php echo $doctor->idturno->FldCaption() ?></td>
			<td<?php echo $doctor->idturno->CellAttributes() ?>>
<span id="el_doctor_idturno" class="form-group">
<span<?php echo $doctor->idturno->ViewAttributes() ?>>
<?php echo $doctor->idturno->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($doctor->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $doctor->estado->FldCaption() ?></td>
			<td<?php echo $doctor->estado->CellAttributes() ?>>
<span id="el_doctor_estado" class="form-group">
<span<?php echo $doctor->estado->ViewAttributes() ?>>
<?php echo $doctor->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

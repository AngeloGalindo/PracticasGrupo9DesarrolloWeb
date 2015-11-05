<?php

// nombre
// apellido
// cui
// telefono
// estado

?>
<?php if ($paciente->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $paciente->TableCaption() ?></h4> -->
<table id="tbl_pacientemaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($paciente->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $paciente->nombre->FldCaption() ?></td>
			<td<?php echo $paciente->nombre->CellAttributes() ?>>
<span id="el_paciente_nombre" class="form-group">
<span<?php echo $paciente->nombre->ViewAttributes() ?>>
<?php echo $paciente->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($paciente->apellido->Visible) { // apellido ?>
		<tr id="r_apellido">
			<td><?php echo $paciente->apellido->FldCaption() ?></td>
			<td<?php echo $paciente->apellido->CellAttributes() ?>>
<span id="el_paciente_apellido" class="form-group">
<span<?php echo $paciente->apellido->ViewAttributes() ?>>
<?php echo $paciente->apellido->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($paciente->cui->Visible) { // cui ?>
		<tr id="r_cui">
			<td><?php echo $paciente->cui->FldCaption() ?></td>
			<td<?php echo $paciente->cui->CellAttributes() ?>>
<span id="el_paciente_cui" class="form-group">
<span<?php echo $paciente->cui->ViewAttributes() ?>>
<?php echo $paciente->cui->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($paciente->telefono->Visible) { // telefono ?>
		<tr id="r_telefono">
			<td><?php echo $paciente->telefono->FldCaption() ?></td>
			<td<?php echo $paciente->telefono->CellAttributes() ?>>
<span id="el_paciente_telefono" class="form-group">
<span<?php echo $paciente->telefono->ViewAttributes() ?>>
<?php echo $paciente->telefono->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($paciente->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $paciente->estado->FldCaption() ?></td>
			<td<?php echo $paciente->estado->CellAttributes() ?>>
<span id="el_paciente_estado" class="form-group">
<span<?php echo $paciente->estado->ViewAttributes() ?>>
<?php echo $paciente->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

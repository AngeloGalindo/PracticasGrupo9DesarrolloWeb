<?php

// idempleado
// nombre
// apellido
// cui
// telefono
// direccion
// estado
// idhospital

?>
<?php if ($empleado->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $empleado->TableCaption() ?></h4> -->
<table id="tbl_empleadomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($empleado->idempleado->Visible) { // idempleado ?>
		<tr id="r_idempleado">
			<td><?php echo $empleado->idempleado->FldCaption() ?></td>
			<td<?php echo $empleado->idempleado->CellAttributes() ?>>
<span id="el_empleado_idempleado" class="form-group">
<span<?php echo $empleado->idempleado->ViewAttributes() ?>>
<?php echo $empleado->idempleado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empleado->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $empleado->nombre->FldCaption() ?></td>
			<td<?php echo $empleado->nombre->CellAttributes() ?>>
<span id="el_empleado_nombre" class="form-group">
<span<?php echo $empleado->nombre->ViewAttributes() ?>>
<?php echo $empleado->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empleado->apellido->Visible) { // apellido ?>
		<tr id="r_apellido">
			<td><?php echo $empleado->apellido->FldCaption() ?></td>
			<td<?php echo $empleado->apellido->CellAttributes() ?>>
<span id="el_empleado_apellido" class="form-group">
<span<?php echo $empleado->apellido->ViewAttributes() ?>>
<?php echo $empleado->apellido->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empleado->cui->Visible) { // cui ?>
		<tr id="r_cui">
			<td><?php echo $empleado->cui->FldCaption() ?></td>
			<td<?php echo $empleado->cui->CellAttributes() ?>>
<span id="el_empleado_cui" class="form-group">
<span<?php echo $empleado->cui->ViewAttributes() ?>>
<?php echo $empleado->cui->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empleado->telefono->Visible) { // telefono ?>
		<tr id="r_telefono">
			<td><?php echo $empleado->telefono->FldCaption() ?></td>
			<td<?php echo $empleado->telefono->CellAttributes() ?>>
<span id="el_empleado_telefono" class="form-group">
<span<?php echo $empleado->telefono->ViewAttributes() ?>>
<?php echo $empleado->telefono->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empleado->direccion->Visible) { // direccion ?>
		<tr id="r_direccion">
			<td><?php echo $empleado->direccion->FldCaption() ?></td>
			<td<?php echo $empleado->direccion->CellAttributes() ?>>
<span id="el_empleado_direccion" class="form-group">
<span<?php echo $empleado->direccion->ViewAttributes() ?>>
<?php echo $empleado->direccion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empleado->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $empleado->estado->FldCaption() ?></td>
			<td<?php echo $empleado->estado->CellAttributes() ?>>
<span id="el_empleado_estado" class="form-group">
<span<?php echo $empleado->estado->ViewAttributes() ?>>
<?php echo $empleado->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empleado->idhospital->Visible) { // idhospital ?>
		<tr id="r_idhospital">
			<td><?php echo $empleado->idhospital->FldCaption() ?></td>
			<td<?php echo $empleado->idhospital->CellAttributes() ?>>
<span id="el_empleado_idhospital" class="form-group">
<span<?php echo $empleado->idhospital->ViewAttributes() ?>>
<?php echo $empleado->idhospital->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

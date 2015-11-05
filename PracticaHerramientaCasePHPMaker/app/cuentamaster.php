<?php

// idpaciente
// fecha_inicio
// fecha_final
// estado

?>
<?php if ($cuenta->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $cuenta->TableCaption() ?></h4> -->
<table id="tbl_cuentamaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($cuenta->idpaciente->Visible) { // idpaciente ?>
		<tr id="r_idpaciente">
			<td><?php echo $cuenta->idpaciente->FldCaption() ?></td>
			<td<?php echo $cuenta->idpaciente->CellAttributes() ?>>
<span id="el_cuenta_idpaciente" class="form-group">
<span<?php echo $cuenta->idpaciente->ViewAttributes() ?>>
<?php echo $cuenta->idpaciente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cuenta->fecha_inicio->Visible) { // fecha_inicio ?>
		<tr id="r_fecha_inicio">
			<td><?php echo $cuenta->fecha_inicio->FldCaption() ?></td>
			<td<?php echo $cuenta->fecha_inicio->CellAttributes() ?>>
<span id="el_cuenta_fecha_inicio" class="form-group">
<span<?php echo $cuenta->fecha_inicio->ViewAttributes() ?>>
<?php echo $cuenta->fecha_inicio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cuenta->fecha_final->Visible) { // fecha_final ?>
		<tr id="r_fecha_final">
			<td><?php echo $cuenta->fecha_final->FldCaption() ?></td>
			<td<?php echo $cuenta->fecha_final->CellAttributes() ?>>
<span id="el_cuenta_fecha_final" class="form-group">
<span<?php echo $cuenta->fecha_final->ViewAttributes() ?>>
<?php echo $cuenta->fecha_final->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cuenta->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $cuenta->estado->FldCaption() ?></td>
			<td<?php echo $cuenta->estado->CellAttributes() ?>>
<span id="el_cuenta_estado" class="form-group">
<span<?php echo $cuenta->estado->ViewAttributes() ?>>
<?php echo $cuenta->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

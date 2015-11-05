<?php

// idcuenta
// idservicio_medico
// estado
// costo
// fecha_inicio
// fecha_final

?>
<?php if ($servicio_medico_prestado->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $servicio_medico_prestado->TableCaption() ?></h4> -->
<table id="tbl_servicio_medico_prestadomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($servicio_medico_prestado->idcuenta->Visible) { // idcuenta ?>
		<tr id="r_idcuenta">
			<td><?php echo $servicio_medico_prestado->idcuenta->FldCaption() ?></td>
			<td<?php echo $servicio_medico_prestado->idcuenta->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_idcuenta" class="form-group">
<span<?php echo $servicio_medico_prestado->idcuenta->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->idcuenta->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->idservicio_medico->Visible) { // idservicio_medico ?>
		<tr id="r_idservicio_medico">
			<td><?php echo $servicio_medico_prestado->idservicio_medico->FldCaption() ?></td>
			<td<?php echo $servicio_medico_prestado->idservicio_medico->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_idservicio_medico" class="form-group">
<span<?php echo $servicio_medico_prestado->idservicio_medico->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->idservicio_medico->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $servicio_medico_prestado->estado->FldCaption() ?></td>
			<td<?php echo $servicio_medico_prestado->estado->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_estado" class="form-group">
<span<?php echo $servicio_medico_prestado->estado->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->costo->Visible) { // costo ?>
		<tr id="r_costo">
			<td><?php echo $servicio_medico_prestado->costo->FldCaption() ?></td>
			<td<?php echo $servicio_medico_prestado->costo->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_costo" class="form-group">
<span<?php echo $servicio_medico_prestado->costo->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->costo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->fecha_inicio->Visible) { // fecha_inicio ?>
		<tr id="r_fecha_inicio">
			<td><?php echo $servicio_medico_prestado->fecha_inicio->FldCaption() ?></td>
			<td<?php echo $servicio_medico_prestado->fecha_inicio->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_fecha_inicio" class="form-group">
<span<?php echo $servicio_medico_prestado->fecha_inicio->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->fecha_inicio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->fecha_final->Visible) { // fecha_final ?>
		<tr id="r_fecha_final">
			<td><?php echo $servicio_medico_prestado->fecha_final->FldCaption() ?></td>
			<td<?php echo $servicio_medico_prestado->fecha_final->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_fecha_final" class="form-group">
<span<?php echo $servicio_medico_prestado->fecha_final->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->fecha_final->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

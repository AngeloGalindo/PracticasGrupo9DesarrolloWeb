<?php

// fecha_inicio
// fecha_final
// estado
// fecha
// idhabitacion
// idpaciente
// es_operacion

?>
<?php if ($internado->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $internado->TableCaption() ?></h4> -->
<table id="tbl_internadomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($internado->fecha_inicio->Visible) { // fecha_inicio ?>
		<tr id="r_fecha_inicio">
			<td><?php echo $internado->fecha_inicio->FldCaption() ?></td>
			<td<?php echo $internado->fecha_inicio->CellAttributes() ?>>
<span id="el_internado_fecha_inicio" class="form-group">
<span<?php echo $internado->fecha_inicio->ViewAttributes() ?>>
<?php echo $internado->fecha_inicio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($internado->fecha_final->Visible) { // fecha_final ?>
		<tr id="r_fecha_final">
			<td><?php echo $internado->fecha_final->FldCaption() ?></td>
			<td<?php echo $internado->fecha_final->CellAttributes() ?>>
<span id="el_internado_fecha_final" class="form-group">
<span<?php echo $internado->fecha_final->ViewAttributes() ?>>
<?php echo $internado->fecha_final->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($internado->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $internado->estado->FldCaption() ?></td>
			<td<?php echo $internado->estado->CellAttributes() ?>>
<span id="el_internado_estado" class="form-group">
<span<?php echo $internado->estado->ViewAttributes() ?>>
<?php echo $internado->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($internado->fecha->Visible) { // fecha ?>
		<tr id="r_fecha">
			<td><?php echo $internado->fecha->FldCaption() ?></td>
			<td<?php echo $internado->fecha->CellAttributes() ?>>
<span id="el_internado_fecha" class="form-group">
<span<?php echo $internado->fecha->ViewAttributes() ?>>
<?php echo $internado->fecha->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($internado->idhabitacion->Visible) { // idhabitacion ?>
		<tr id="r_idhabitacion">
			<td><?php echo $internado->idhabitacion->FldCaption() ?></td>
			<td<?php echo $internado->idhabitacion->CellAttributes() ?>>
<span id="el_internado_idhabitacion" class="form-group">
<span<?php echo $internado->idhabitacion->ViewAttributes() ?>>
<?php echo $internado->idhabitacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($internado->idpaciente->Visible) { // idpaciente ?>
		<tr id="r_idpaciente">
			<td><?php echo $internado->idpaciente->FldCaption() ?></td>
			<td<?php echo $internado->idpaciente->CellAttributes() ?>>
<span id="el_internado_idpaciente" class="form-group">
<span<?php echo $internado->idpaciente->ViewAttributes() ?>>
<?php echo $internado->idpaciente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($internado->es_operacion->Visible) { // es_operacion ?>
		<tr id="r_es_operacion">
			<td><?php echo $internado->es_operacion->FldCaption() ?></td>
			<td<?php echo $internado->es_operacion->CellAttributes() ?>>
<span id="el_internado_es_operacion" class="form-group">
<span<?php echo $internado->es_operacion->ViewAttributes() ?>>
<?php echo $internado->es_operacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

<?php

// descripcion
// estado
// es_operacion

?>
<?php if ($servicio_medico->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $servicio_medico->TableCaption() ?></h4> -->
<table id="tbl_servicio_medicomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($servicio_medico->descripcion->Visible) { // descripcion ?>
		<tr id="r_descripcion">
			<td><?php echo $servicio_medico->descripcion->FldCaption() ?></td>
			<td<?php echo $servicio_medico->descripcion->CellAttributes() ?>>
<span id="el_servicio_medico_descripcion" class="form-group">
<span<?php echo $servicio_medico->descripcion->ViewAttributes() ?>>
<?php echo $servicio_medico->descripcion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($servicio_medico->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $servicio_medico->estado->FldCaption() ?></td>
			<td<?php echo $servicio_medico->estado->CellAttributes() ?>>
<span id="el_servicio_medico_estado" class="form-group">
<span<?php echo $servicio_medico->estado->ViewAttributes() ?>>
<?php echo $servicio_medico->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($servicio_medico->es_operacion->Visible) { // es_operacion ?>
		<tr id="r_es_operacion">
			<td><?php echo $servicio_medico->es_operacion->FldCaption() ?></td>
			<td<?php echo $servicio_medico->es_operacion->CellAttributes() ?>>
<span id="el_servicio_medico_es_operacion" class="form-group">
<span<?php echo $servicio_medico->es_operacion->ViewAttributes() ?>>
<?php echo $servicio_medico->es_operacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

<?php

// descripcion
// horario_inicio
// horario_fin
// tipo

?>
<?php if ($tipo_turno->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $tipo_turno->TableCaption() ?></h4> -->
<table id="tbl_tipo_turnomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($tipo_turno->descripcion->Visible) { // descripcion ?>
		<tr id="r_descripcion">
			<td><?php echo $tipo_turno->descripcion->FldCaption() ?></td>
			<td<?php echo $tipo_turno->descripcion->CellAttributes() ?>>
<span id="el_tipo_turno_descripcion" class="form-group">
<span<?php echo $tipo_turno->descripcion->ViewAttributes() ?>>
<?php echo $tipo_turno->descripcion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tipo_turno->horario_inicio->Visible) { // horario_inicio ?>
		<tr id="r_horario_inicio">
			<td><?php echo $tipo_turno->horario_inicio->FldCaption() ?></td>
			<td<?php echo $tipo_turno->horario_inicio->CellAttributes() ?>>
<span id="el_tipo_turno_horario_inicio" class="form-group">
<span<?php echo $tipo_turno->horario_inicio->ViewAttributes() ?>>
<?php echo $tipo_turno->horario_inicio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tipo_turno->horario_fin->Visible) { // horario_fin ?>
		<tr id="r_horario_fin">
			<td><?php echo $tipo_turno->horario_fin->FldCaption() ?></td>
			<td<?php echo $tipo_turno->horario_fin->CellAttributes() ?>>
<span id="el_tipo_turno_horario_fin" class="form-group">
<span<?php echo $tipo_turno->horario_fin->ViewAttributes() ?>>
<?php echo $tipo_turno->horario_fin->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tipo_turno->tipo->Visible) { // tipo ?>
		<tr id="r_tipo">
			<td><?php echo $tipo_turno->tipo->FldCaption() ?></td>
			<td<?php echo $tipo_turno->tipo->CellAttributes() ?>>
<span id="el_tipo_turno_tipo" class="form-group">
<span<?php echo $tipo_turno->tipo->ViewAttributes() ?>>
<?php echo $tipo_turno->tipo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

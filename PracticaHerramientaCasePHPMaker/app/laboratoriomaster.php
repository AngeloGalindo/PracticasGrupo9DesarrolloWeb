<?php

// idlaboratorio
// descripcion
// estado
// idpais

?>
<?php if ($laboratorio->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $laboratorio->TableCaption() ?></h4> -->
<table id="tbl_laboratoriomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($laboratorio->idlaboratorio->Visible) { // idlaboratorio ?>
		<tr id="r_idlaboratorio">
			<td><?php echo $laboratorio->idlaboratorio->FldCaption() ?></td>
			<td<?php echo $laboratorio->idlaboratorio->CellAttributes() ?>>
<span id="el_laboratorio_idlaboratorio" class="form-group">
<span<?php echo $laboratorio->idlaboratorio->ViewAttributes() ?>>
<?php echo $laboratorio->idlaboratorio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($laboratorio->descripcion->Visible) { // descripcion ?>
		<tr id="r_descripcion">
			<td><?php echo $laboratorio->descripcion->FldCaption() ?></td>
			<td<?php echo $laboratorio->descripcion->CellAttributes() ?>>
<span id="el_laboratorio_descripcion" class="form-group">
<span<?php echo $laboratorio->descripcion->ViewAttributes() ?>>
<?php echo $laboratorio->descripcion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($laboratorio->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $laboratorio->estado->FldCaption() ?></td>
			<td<?php echo $laboratorio->estado->CellAttributes() ?>>
<span id="el_laboratorio_estado" class="form-group">
<span<?php echo $laboratorio->estado->ViewAttributes() ?>>
<?php echo $laboratorio->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($laboratorio->idpais->Visible) { // idpais ?>
		<tr id="r_idpais">
			<td><?php echo $laboratorio->idpais->FldCaption() ?></td>
			<td<?php echo $laboratorio->idpais->CellAttributes() ?>>
<span id="el_laboratorio_idpais" class="form-group">
<span<?php echo $laboratorio->idpais->ViewAttributes() ?>>
<?php echo $laboratorio->idpais->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

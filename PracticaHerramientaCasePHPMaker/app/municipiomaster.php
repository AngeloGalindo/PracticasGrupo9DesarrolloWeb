<?php

// nombre
// estado
// iddepartamento

?>
<?php if ($municipio->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $municipio->TableCaption() ?></h4> -->
<table id="tbl_municipiomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($municipio->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $municipio->nombre->FldCaption() ?></td>
			<td<?php echo $municipio->nombre->CellAttributes() ?>>
<span id="el_municipio_nombre" class="form-group">
<span<?php echo $municipio->nombre->ViewAttributes() ?>>
<?php echo $municipio->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($municipio->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $municipio->estado->FldCaption() ?></td>
			<td<?php echo $municipio->estado->CellAttributes() ?>>
<span id="el_municipio_estado" class="form-group">
<span<?php echo $municipio->estado->ViewAttributes() ?>>
<?php echo $municipio->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($municipio->iddepartamento->Visible) { // iddepartamento ?>
		<tr id="r_iddepartamento">
			<td><?php echo $municipio->iddepartamento->FldCaption() ?></td>
			<td<?php echo $municipio->iddepartamento->CellAttributes() ?>>
<span id="el_municipio_iddepartamento" class="form-group">
<span<?php echo $municipio->iddepartamento->ViewAttributes() ?>>
<?php echo $municipio->iddepartamento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

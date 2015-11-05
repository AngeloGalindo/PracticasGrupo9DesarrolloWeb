<?php

// nombre
// estado
// idpais

?>
<?php if ($departamento->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $departamento->TableCaption() ?></h4> -->
<table id="tbl_departamentomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($departamento->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $departamento->nombre->FldCaption() ?></td>
			<td<?php echo $departamento->nombre->CellAttributes() ?>>
<span id="el_departamento_nombre" class="form-group">
<span<?php echo $departamento->nombre->ViewAttributes() ?>>
<?php echo $departamento->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($departamento->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $departamento->estado->FldCaption() ?></td>
			<td<?php echo $departamento->estado->CellAttributes() ?>>
<span id="el_departamento_estado" class="form-group">
<span<?php echo $departamento->estado->ViewAttributes() ?>>
<?php echo $departamento->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($departamento->idpais->Visible) { // idpais ?>
		<tr id="r_idpais">
			<td><?php echo $departamento->idpais->FldCaption() ?></td>
			<td<?php echo $departamento->idpais->CellAttributes() ?>>
<span id="el_departamento_idpais" class="form-group">
<span<?php echo $departamento->idpais->ViewAttributes() ?>>
<?php echo $departamento->idpais->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

<?php

// nombre
// estado

?>
<?php if ($continente->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $continente->TableCaption() ?></h4> -->
<table id="tbl_continentemaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($continente->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $continente->nombre->FldCaption() ?></td>
			<td<?php echo $continente->nombre->CellAttributes() ?>>
<span id="el_continente_nombre" class="form-group">
<span<?php echo $continente->nombre->ViewAttributes() ?>>
<?php echo $continente->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($continente->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $continente->estado->FldCaption() ?></td>
			<td<?php echo $continente->estado->CellAttributes() ?>>
<span id="el_continente_estado" class="form-group">
<span<?php echo $continente->estado->ViewAttributes() ?>>
<?php echo $continente->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

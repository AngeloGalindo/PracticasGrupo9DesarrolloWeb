<?php

// nombre
// telefono
// estado

?>
<?php if ($hospital->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $hospital->TableCaption() ?></h4> -->
<table id="tbl_hospitalmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($hospital->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $hospital->nombre->FldCaption() ?></td>
			<td<?php echo $hospital->nombre->CellAttributes() ?>>
<span id="el_hospital_nombre" class="form-group">
<span<?php echo $hospital->nombre->ViewAttributes() ?>>
<?php echo $hospital->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hospital->telefono->Visible) { // telefono ?>
		<tr id="r_telefono">
			<td><?php echo $hospital->telefono->FldCaption() ?></td>
			<td<?php echo $hospital->telefono->CellAttributes() ?>>
<span id="el_hospital_telefono" class="form-group">
<span<?php echo $hospital->telefono->ViewAttributes() ?>>
<?php echo $hospital->telefono->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($hospital->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $hospital->estado->FldCaption() ?></td>
			<td<?php echo $hospital->estado->CellAttributes() ?>>
<span id="el_hospital_estado" class="form-group">
<span<?php echo $hospital->estado->ViewAttributes() ?>>
<?php echo $hospital->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

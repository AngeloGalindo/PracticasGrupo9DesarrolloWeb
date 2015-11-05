<?php

// descripcion
// estado
// idnivel

?>
<?php if ($sala->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $sala->TableCaption() ?></h4> -->
<table id="tbl_salamaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($sala->descripcion->Visible) { // descripcion ?>
		<tr id="r_descripcion">
			<td><?php echo $sala->descripcion->FldCaption() ?></td>
			<td<?php echo $sala->descripcion->CellAttributes() ?>>
<span id="el_sala_descripcion" class="form-group">
<span<?php echo $sala->descripcion->ViewAttributes() ?>>
<?php echo $sala->descripcion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sala->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $sala->estado->FldCaption() ?></td>
			<td<?php echo $sala->estado->CellAttributes() ?>>
<span id="el_sala_estado" class="form-group">
<span<?php echo $sala->estado->ViewAttributes() ?>>
<?php echo $sala->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sala->idnivel->Visible) { // idnivel ?>
		<tr id="r_idnivel">
			<td><?php echo $sala->idnivel->FldCaption() ?></td>
			<td<?php echo $sala->idnivel->CellAttributes() ?>>
<span id="el_sala_idnivel" class="form-group">
<span<?php echo $sala->idnivel->ViewAttributes() ?>>
<?php echo $sala->idnivel->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

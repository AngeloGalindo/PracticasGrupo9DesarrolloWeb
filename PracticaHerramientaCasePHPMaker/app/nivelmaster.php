<?php

// descripcion
// estado
// idhospital

?>
<?php if ($nivel->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $nivel->TableCaption() ?></h4> -->
<table id="tbl_nivelmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($nivel->descripcion->Visible) { // descripcion ?>
		<tr id="r_descripcion">
			<td><?php echo $nivel->descripcion->FldCaption() ?></td>
			<td<?php echo $nivel->descripcion->CellAttributes() ?>>
<span id="el_nivel_descripcion" class="form-group">
<span<?php echo $nivel->descripcion->ViewAttributes() ?>>
<?php echo $nivel->descripcion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($nivel->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $nivel->estado->FldCaption() ?></td>
			<td<?php echo $nivel->estado->CellAttributes() ?>>
<span id="el_nivel_estado" class="form-group">
<span<?php echo $nivel->estado->ViewAttributes() ?>>
<?php echo $nivel->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($nivel->idhospital->Visible) { // idhospital ?>
		<tr id="r_idhospital">
			<td><?php echo $nivel->idhospital->FldCaption() ?></td>
			<td<?php echo $nivel->idhospital->CellAttributes() ?>>
<span id="el_nivel_idhospital" class="form-group">
<span<?php echo $nivel->idhospital->ViewAttributes() ?>>
<?php echo $nivel->idhospital->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

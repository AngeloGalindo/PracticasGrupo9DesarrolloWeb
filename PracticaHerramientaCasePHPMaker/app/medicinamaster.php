<?php

// idmedicina
// descripcion
// estado
// idlaboratorio
// idhospital

?>
<?php if ($medicina->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $medicina->TableCaption() ?></h4> -->
<table id="tbl_medicinamaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($medicina->idmedicina->Visible) { // idmedicina ?>
		<tr id="r_idmedicina">
			<td><?php echo $medicina->idmedicina->FldCaption() ?></td>
			<td<?php echo $medicina->idmedicina->CellAttributes() ?>>
<span id="el_medicina_idmedicina" class="form-group">
<span<?php echo $medicina->idmedicina->ViewAttributes() ?>>
<?php echo $medicina->idmedicina->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($medicina->descripcion->Visible) { // descripcion ?>
		<tr id="r_descripcion">
			<td><?php echo $medicina->descripcion->FldCaption() ?></td>
			<td<?php echo $medicina->descripcion->CellAttributes() ?>>
<span id="el_medicina_descripcion" class="form-group">
<span<?php echo $medicina->descripcion->ViewAttributes() ?>>
<?php echo $medicina->descripcion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($medicina->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $medicina->estado->FldCaption() ?></td>
			<td<?php echo $medicina->estado->CellAttributes() ?>>
<span id="el_medicina_estado" class="form-group">
<span<?php echo $medicina->estado->ViewAttributes() ?>>
<?php echo $medicina->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($medicina->idlaboratorio->Visible) { // idlaboratorio ?>
		<tr id="r_idlaboratorio">
			<td><?php echo $medicina->idlaboratorio->FldCaption() ?></td>
			<td<?php echo $medicina->idlaboratorio->CellAttributes() ?>>
<span id="el_medicina_idlaboratorio" class="form-group">
<span<?php echo $medicina->idlaboratorio->ViewAttributes() ?>>
<?php echo $medicina->idlaboratorio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($medicina->idhospital->Visible) { // idhospital ?>
		<tr id="r_idhospital">
			<td><?php echo $medicina->idhospital->FldCaption() ?></td>
			<td<?php echo $medicina->idhospital->CellAttributes() ?>>
<span id="el_medicina_idhospital" class="form-group">
<span<?php echo $medicina->idhospital->ViewAttributes() ?>>
<?php echo $medicina->idhospital->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

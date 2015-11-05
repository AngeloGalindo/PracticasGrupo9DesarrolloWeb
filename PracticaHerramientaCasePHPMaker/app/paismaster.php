<?php

// nombre
// nombre oficial
// gentilicio
// flag
// idcontinente
// estado

?>
<?php if ($pais->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $pais->TableCaption() ?></h4> -->
<table id="tbl_paismaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($pais->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $pais->nombre->FldCaption() ?></td>
			<td<?php echo $pais->nombre->CellAttributes() ?>>
<span id="el_pais_nombre" class="form-group">
<span<?php echo $pais->nombre->ViewAttributes() ?>>
<?php echo $pais->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pais->nombre_oficial->Visible) { // nombre oficial ?>
		<tr id="r_nombre_oficial">
			<td><?php echo $pais->nombre_oficial->FldCaption() ?></td>
			<td<?php echo $pais->nombre_oficial->CellAttributes() ?>>
<span id="el_pais_nombre_oficial" class="form-group">
<span<?php echo $pais->nombre_oficial->ViewAttributes() ?>>
<?php echo $pais->nombre_oficial->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pais->gentilicio->Visible) { // gentilicio ?>
		<tr id="r_gentilicio">
			<td><?php echo $pais->gentilicio->FldCaption() ?></td>
			<td<?php echo $pais->gentilicio->CellAttributes() ?>>
<span id="el_pais_gentilicio" class="form-group">
<span<?php echo $pais->gentilicio->ViewAttributes() ?>>
<?php echo $pais->gentilicio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pais->flag->Visible) { // flag ?>
		<tr id="r_flag">
			<td><?php echo $pais->flag->FldCaption() ?></td>
			<td<?php echo $pais->flag->CellAttributes() ?>>
<span id="el_pais_flag" class="form-group">
<span<?php echo $pais->flag->ViewAttributes() ?>>
<?php echo $pais->flag->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pais->idcontinente->Visible) { // idcontinente ?>
		<tr id="r_idcontinente">
			<td><?php echo $pais->idcontinente->FldCaption() ?></td>
			<td<?php echo $pais->idcontinente->CellAttributes() ?>>
<span id="el_pais_idcontinente" class="form-group">
<span<?php echo $pais->idcontinente->ViewAttributes() ?>>
<?php echo $pais->idcontinente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pais->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td><?php echo $pais->estado->FldCaption() ?></td>
			<td<?php echo $pais->estado->CellAttributes() ?>>
<span id="el_pais_estado" class="form-group">
<span<?php echo $pais->estado->ViewAttributes() ?>>
<?php echo $pais->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

<?php

// iddoctor_especialidad
// iddoctor
// idespecialidad

?>
<?php if ($doctor_especialidad->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $doctor_especialidad->TableCaption() ?></h4> -->
<table id="tbl_doctor_especialidadmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($doctor_especialidad->iddoctor_especialidad->Visible) { // iddoctor_especialidad ?>
		<tr id="r_iddoctor_especialidad">
			<td><?php echo $doctor_especialidad->iddoctor_especialidad->FldCaption() ?></td>
			<td<?php echo $doctor_especialidad->iddoctor_especialidad->CellAttributes() ?>>
<span id="el_doctor_especialidad_iddoctor_especialidad" class="form-group">
<span<?php echo $doctor_especialidad->iddoctor_especialidad->ViewAttributes() ?>>
<?php echo $doctor_especialidad->iddoctor_especialidad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($doctor_especialidad->iddoctor->Visible) { // iddoctor ?>
		<tr id="r_iddoctor">
			<td><?php echo $doctor_especialidad->iddoctor->FldCaption() ?></td>
			<td<?php echo $doctor_especialidad->iddoctor->CellAttributes() ?>>
<span id="el_doctor_especialidad_iddoctor" class="form-group">
<span<?php echo $doctor_especialidad->iddoctor->ViewAttributes() ?>>
<?php echo $doctor_especialidad->iddoctor->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($doctor_especialidad->idespecialidad->Visible) { // idespecialidad ?>
		<tr id="r_idespecialidad">
			<td><?php echo $doctor_especialidad->idespecialidad->FldCaption() ?></td>
			<td<?php echo $doctor_especialidad->idespecialidad->CellAttributes() ?>>
<span id="el_doctor_especialidad_idespecialidad" class="form-group">
<span<?php echo $doctor_especialidad->idespecialidad->ViewAttributes() ?>>
<?php echo $doctor_especialidad->idespecialidad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

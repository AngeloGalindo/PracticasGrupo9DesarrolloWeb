<?php

// Create page object
if (!isset($doctor_grid)) $doctor_grid = new cdoctor_grid();

// Page init
$doctor_grid->Page_Init();

// Page main
$doctor_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$doctor_grid->Page_Render();
?>
<?php if ($doctor->Export == "") { ?>
<script type="text/javascript">

// Page object
var doctor_grid = new ew_Page("doctor_grid");
doctor_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = doctor_grid.PageID; // For backward compatibility

// Form object
var fdoctorgrid = new ew_Form("fdoctorgrid");
fdoctorgrid.FormKeyCountName = '<?php echo $doctor_grid->FormKeyCountName ?>';

// Validate form
fdoctorgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor->nombre->FldCaption(), $doctor->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idturno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor->idturno->FldCaption(), $doctor->idturno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idturno");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($doctor->idturno->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor->estado->FldCaption(), $doctor->estado->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fdoctorgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "apellido", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cui", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telefono", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direccion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idturno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	return true;
}

// Form_CustomValidate event
fdoctorgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdoctorgrid.ValidateRequired = true;
<?php } else { ?>
fdoctorgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdoctorgrid.Lists["x_idturno"] = {"LinkField":"x_idturno","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($doctor->CurrentAction == "gridadd") {
	if ($doctor->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$doctor_grid->TotalRecs = $doctor->SelectRecordCount();
			$doctor_grid->Recordset = $doctor_grid->LoadRecordset($doctor_grid->StartRec-1, $doctor_grid->DisplayRecs);
		} else {
			if ($doctor_grid->Recordset = $doctor_grid->LoadRecordset())
				$doctor_grid->TotalRecs = $doctor_grid->Recordset->RecordCount();
		}
		$doctor_grid->StartRec = 1;
		$doctor_grid->DisplayRecs = $doctor_grid->TotalRecs;
	} else {
		$doctor->CurrentFilter = "0=1";
		$doctor_grid->StartRec = 1;
		$doctor_grid->DisplayRecs = $doctor->GridAddRowCount;
	}
	$doctor_grid->TotalRecs = $doctor_grid->DisplayRecs;
	$doctor_grid->StopRec = $doctor_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$doctor_grid->TotalRecs = $doctor->SelectRecordCount();
	} else {
		if ($doctor_grid->Recordset = $doctor_grid->LoadRecordset())
			$doctor_grid->TotalRecs = $doctor_grid->Recordset->RecordCount();
	}
	$doctor_grid->StartRec = 1;
	$doctor_grid->DisplayRecs = $doctor_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$doctor_grid->Recordset = $doctor_grid->LoadRecordset($doctor_grid->StartRec-1, $doctor_grid->DisplayRecs);

	// Set no record found message
	if ($doctor->CurrentAction == "" && $doctor_grid->TotalRecs == 0) {
		if ($doctor_grid->SearchWhere == "0=101")
			$doctor_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$doctor_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$doctor_grid->RenderOtherOptions();
?>
<?php $doctor_grid->ShowPageHeader(); ?>
<?php
$doctor_grid->ShowMessage();
?>
<?php if ($doctor_grid->TotalRecs > 0 || $doctor->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdoctorgrid" class="ewForm form-inline">
<div id="gmp_doctor" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_doctorgrid" class="table ewTable">
<?php echo $doctor->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$doctor_grid->RenderListOptions();

// Render list options (header, left)
$doctor_grid->ListOptions->Render("header", "left");
?>
<?php if ($doctor->nombre->Visible) { // nombre ?>
	<?php if ($doctor->SortUrl($doctor->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_doctor_nombre" class="doctor_nombre"><div class="ewTableHeaderCaption"><?php echo $doctor->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div><div id="elh_doctor_nombre" class="doctor_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor->apellido->Visible) { // apellido ?>
	<?php if ($doctor->SortUrl($doctor->apellido) == "") { ?>
		<th data-name="apellido"><div id="elh_doctor_apellido" class="doctor_apellido"><div class="ewTableHeaderCaption"><?php echo $doctor->apellido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellido"><div><div id="elh_doctor_apellido" class="doctor_apellido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor->apellido->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor->apellido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor->apellido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor->cui->Visible) { // cui ?>
	<?php if ($doctor->SortUrl($doctor->cui) == "") { ?>
		<th data-name="cui"><div id="elh_doctor_cui" class="doctor_cui"><div class="ewTableHeaderCaption"><?php echo $doctor->cui->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cui"><div><div id="elh_doctor_cui" class="doctor_cui">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor->cui->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor->cui->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor->cui->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor->telefono->Visible) { // telefono ?>
	<?php if ($doctor->SortUrl($doctor->telefono) == "") { ?>
		<th data-name="telefono"><div id="elh_doctor_telefono" class="doctor_telefono"><div class="ewTableHeaderCaption"><?php echo $doctor->telefono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono"><div><div id="elh_doctor_telefono" class="doctor_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor->telefono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor->direccion->Visible) { // direccion ?>
	<?php if ($doctor->SortUrl($doctor->direccion) == "") { ?>
		<th data-name="direccion"><div id="elh_doctor_direccion" class="doctor_direccion"><div class="ewTableHeaderCaption"><?php echo $doctor->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion"><div><div id="elh_doctor_direccion" class="doctor_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor->direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor->idturno->Visible) { // idturno ?>
	<?php if ($doctor->SortUrl($doctor->idturno) == "") { ?>
		<th data-name="idturno"><div id="elh_doctor_idturno" class="doctor_idturno"><div class="ewTableHeaderCaption"><?php echo $doctor->idturno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idturno"><div><div id="elh_doctor_idturno" class="doctor_idturno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor->idturno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor->idturno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor->idturno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor->estado->Visible) { // estado ?>
	<?php if ($doctor->SortUrl($doctor->estado) == "") { ?>
		<th data-name="estado"><div id="elh_doctor_estado" class="doctor_estado"><div class="ewTableHeaderCaption"><?php echo $doctor->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_doctor_estado" class="doctor_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$doctor_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$doctor_grid->StartRec = 1;
$doctor_grid->StopRec = $doctor_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($doctor_grid->FormKeyCountName) && ($doctor->CurrentAction == "gridadd" || $doctor->CurrentAction == "gridedit" || $doctor->CurrentAction == "F")) {
		$doctor_grid->KeyCount = $objForm->GetValue($doctor_grid->FormKeyCountName);
		$doctor_grid->StopRec = $doctor_grid->StartRec + $doctor_grid->KeyCount - 1;
	}
}
$doctor_grid->RecCnt = $doctor_grid->StartRec - 1;
if ($doctor_grid->Recordset && !$doctor_grid->Recordset->EOF) {
	$doctor_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $doctor_grid->StartRec > 1)
		$doctor_grid->Recordset->Move($doctor_grid->StartRec - 1);
} elseif (!$doctor->AllowAddDeleteRow && $doctor_grid->StopRec == 0) {
	$doctor_grid->StopRec = $doctor->GridAddRowCount;
}

// Initialize aggregate
$doctor->RowType = EW_ROWTYPE_AGGREGATEINIT;
$doctor->ResetAttrs();
$doctor_grid->RenderRow();
if ($doctor->CurrentAction == "gridadd")
	$doctor_grid->RowIndex = 0;
if ($doctor->CurrentAction == "gridedit")
	$doctor_grid->RowIndex = 0;
while ($doctor_grid->RecCnt < $doctor_grid->StopRec) {
	$doctor_grid->RecCnt++;
	if (intval($doctor_grid->RecCnt) >= intval($doctor_grid->StartRec)) {
		$doctor_grid->RowCnt++;
		if ($doctor->CurrentAction == "gridadd" || $doctor->CurrentAction == "gridedit" || $doctor->CurrentAction == "F") {
			$doctor_grid->RowIndex++;
			$objForm->Index = $doctor_grid->RowIndex;
			if ($objForm->HasValue($doctor_grid->FormActionName))
				$doctor_grid->RowAction = strval($objForm->GetValue($doctor_grid->FormActionName));
			elseif ($doctor->CurrentAction == "gridadd")
				$doctor_grid->RowAction = "insert";
			else
				$doctor_grid->RowAction = "";
		}

		// Set up key count
		$doctor_grid->KeyCount = $doctor_grid->RowIndex;

		// Init row class and style
		$doctor->ResetAttrs();
		$doctor->CssClass = "";
		if ($doctor->CurrentAction == "gridadd") {
			if ($doctor->CurrentMode == "copy") {
				$doctor_grid->LoadRowValues($doctor_grid->Recordset); // Load row values
				$doctor_grid->SetRecordKey($doctor_grid->RowOldKey, $doctor_grid->Recordset); // Set old record key
			} else {
				$doctor_grid->LoadDefaultValues(); // Load default values
				$doctor_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$doctor_grid->LoadRowValues($doctor_grid->Recordset); // Load row values
		}
		$doctor->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($doctor->CurrentAction == "gridadd") // Grid add
			$doctor->RowType = EW_ROWTYPE_ADD; // Render add
		if ($doctor->CurrentAction == "gridadd" && $doctor->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$doctor_grid->RestoreCurrentRowFormValues($doctor_grid->RowIndex); // Restore form values
		if ($doctor->CurrentAction == "gridedit") { // Grid edit
			if ($doctor->EventCancelled) {
				$doctor_grid->RestoreCurrentRowFormValues($doctor_grid->RowIndex); // Restore form values
			}
			if ($doctor_grid->RowAction == "insert")
				$doctor->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$doctor->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($doctor->CurrentAction == "gridedit" && ($doctor->RowType == EW_ROWTYPE_EDIT || $doctor->RowType == EW_ROWTYPE_ADD) && $doctor->EventCancelled) // Update failed
			$doctor_grid->RestoreCurrentRowFormValues($doctor_grid->RowIndex); // Restore form values
		if ($doctor->RowType == EW_ROWTYPE_EDIT) // Edit row
			$doctor_grid->EditRowCnt++;
		if ($doctor->CurrentAction == "F") // Confirm row
			$doctor_grid->RestoreCurrentRowFormValues($doctor_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$doctor->RowAttrs = array_merge($doctor->RowAttrs, array('data-rowindex'=>$doctor_grid->RowCnt, 'id'=>'r' . $doctor_grid->RowCnt . '_doctor', 'data-rowtype'=>$doctor->RowType));

		// Render row
		$doctor_grid->RenderRow();

		// Render list options
		$doctor_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($doctor_grid->RowAction <> "delete" && $doctor_grid->RowAction <> "insertdelete" && !($doctor_grid->RowAction == "insert" && $doctor->CurrentAction == "F" && $doctor_grid->EmptyRow())) {
?>
	<tr<?php echo $doctor->RowAttributes() ?>>
<?php

// Render list options (body, left)
$doctor_grid->ListOptions->Render("body", "left", $doctor_grid->RowCnt);
?>
	<?php if ($doctor->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $doctor->nombre->CellAttributes() ?>>
<?php if ($doctor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_nombre" class="form-group doctor_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $doctor_grid->RowIndex ?>_nombre" id="x<?php echo $doctor_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->nombre->PlaceHolder) ?>" value="<?php echo $doctor->nombre->EditValue ?>"<?php echo $doctor->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nombre" name="o<?php echo $doctor_grid->RowIndex ?>_nombre" id="o<?php echo $doctor_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($doctor->nombre->OldValue) ?>">
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_nombre" class="form-group doctor_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $doctor_grid->RowIndex ?>_nombre" id="x<?php echo $doctor_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->nombre->PlaceHolder) ?>" value="<?php echo $doctor->nombre->EditValue ?>"<?php echo $doctor->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor->nombre->ViewAttributes() ?>>
<?php echo $doctor->nombre->ListViewValue() ?></span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $doctor_grid->RowIndex ?>_nombre" id="x<?php echo $doctor_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($doctor->nombre->FormValue) ?>">
<input type="hidden" data-field="x_nombre" name="o<?php echo $doctor_grid->RowIndex ?>_nombre" id="o<?php echo $doctor_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($doctor->nombre->OldValue) ?>">
<?php } ?>
<a id="<?php echo $doctor_grid->PageObjName . "_row_" . $doctor_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_iddoctor" name="x<?php echo $doctor_grid->RowIndex ?>_iddoctor" id="x<?php echo $doctor_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor->iddoctor->CurrentValue) ?>">
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $doctor_grid->RowIndex ?>_iddoctor" id="o<?php echo $doctor_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor->iddoctor->OldValue) ?>">
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_EDIT || $doctor->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_iddoctor" name="x<?php echo $doctor_grid->RowIndex ?>_iddoctor" id="x<?php echo $doctor_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor->iddoctor->CurrentValue) ?>">
<?php } ?>
	<?php if ($doctor->apellido->Visible) { // apellido ?>
		<td data-name="apellido"<?php echo $doctor->apellido->CellAttributes() ?>>
<?php if ($doctor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_apellido" class="form-group doctor_apellido">
<input type="text" data-field="x_apellido" name="x<?php echo $doctor_grid->RowIndex ?>_apellido" id="x<?php echo $doctor_grid->RowIndex ?>_apellido" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->apellido->PlaceHolder) ?>" value="<?php echo $doctor->apellido->EditValue ?>"<?php echo $doctor->apellido->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_apellido" name="o<?php echo $doctor_grid->RowIndex ?>_apellido" id="o<?php echo $doctor_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($doctor->apellido->OldValue) ?>">
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_apellido" class="form-group doctor_apellido">
<input type="text" data-field="x_apellido" name="x<?php echo $doctor_grid->RowIndex ?>_apellido" id="x<?php echo $doctor_grid->RowIndex ?>_apellido" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->apellido->PlaceHolder) ?>" value="<?php echo $doctor->apellido->EditValue ?>"<?php echo $doctor->apellido->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor->apellido->ViewAttributes() ?>>
<?php echo $doctor->apellido->ListViewValue() ?></span>
<input type="hidden" data-field="x_apellido" name="x<?php echo $doctor_grid->RowIndex ?>_apellido" id="x<?php echo $doctor_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($doctor->apellido->FormValue) ?>">
<input type="hidden" data-field="x_apellido" name="o<?php echo $doctor_grid->RowIndex ?>_apellido" id="o<?php echo $doctor_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($doctor->apellido->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($doctor->cui->Visible) { // cui ?>
		<td data-name="cui"<?php echo $doctor->cui->CellAttributes() ?>>
<?php if ($doctor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_cui" class="form-group doctor_cui">
<input type="text" data-field="x_cui" name="x<?php echo $doctor_grid->RowIndex ?>_cui" id="x<?php echo $doctor_grid->RowIndex ?>_cui" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->cui->PlaceHolder) ?>" value="<?php echo $doctor->cui->EditValue ?>"<?php echo $doctor->cui->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cui" name="o<?php echo $doctor_grid->RowIndex ?>_cui" id="o<?php echo $doctor_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($doctor->cui->OldValue) ?>">
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_cui" class="form-group doctor_cui">
<input type="text" data-field="x_cui" name="x<?php echo $doctor_grid->RowIndex ?>_cui" id="x<?php echo $doctor_grid->RowIndex ?>_cui" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->cui->PlaceHolder) ?>" value="<?php echo $doctor->cui->EditValue ?>"<?php echo $doctor->cui->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor->cui->ViewAttributes() ?>>
<?php echo $doctor->cui->ListViewValue() ?></span>
<input type="hidden" data-field="x_cui" name="x<?php echo $doctor_grid->RowIndex ?>_cui" id="x<?php echo $doctor_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($doctor->cui->FormValue) ?>">
<input type="hidden" data-field="x_cui" name="o<?php echo $doctor_grid->RowIndex ?>_cui" id="o<?php echo $doctor_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($doctor->cui->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($doctor->telefono->Visible) { // telefono ?>
		<td data-name="telefono"<?php echo $doctor->telefono->CellAttributes() ?>>
<?php if ($doctor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_telefono" class="form-group doctor_telefono">
<input type="text" data-field="x_telefono" name="x<?php echo $doctor_grid->RowIndex ?>_telefono" id="x<?php echo $doctor_grid->RowIndex ?>_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->telefono->PlaceHolder) ?>" value="<?php echo $doctor->telefono->EditValue ?>"<?php echo $doctor->telefono->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_telefono" name="o<?php echo $doctor_grid->RowIndex ?>_telefono" id="o<?php echo $doctor_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($doctor->telefono->OldValue) ?>">
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_telefono" class="form-group doctor_telefono">
<input type="text" data-field="x_telefono" name="x<?php echo $doctor_grid->RowIndex ?>_telefono" id="x<?php echo $doctor_grid->RowIndex ?>_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->telefono->PlaceHolder) ?>" value="<?php echo $doctor->telefono->EditValue ?>"<?php echo $doctor->telefono->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor->telefono->ViewAttributes() ?>>
<?php echo $doctor->telefono->ListViewValue() ?></span>
<input type="hidden" data-field="x_telefono" name="x<?php echo $doctor_grid->RowIndex ?>_telefono" id="x<?php echo $doctor_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($doctor->telefono->FormValue) ?>">
<input type="hidden" data-field="x_telefono" name="o<?php echo $doctor_grid->RowIndex ?>_telefono" id="o<?php echo $doctor_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($doctor->telefono->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($doctor->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $doctor->direccion->CellAttributes() ?>>
<?php if ($doctor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_direccion" class="form-group doctor_direccion">
<input type="text" data-field="x_direccion" name="x<?php echo $doctor_grid->RowIndex ?>_direccion" id="x<?php echo $doctor_grid->RowIndex ?>_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->direccion->PlaceHolder) ?>" value="<?php echo $doctor->direccion->EditValue ?>"<?php echo $doctor->direccion->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_direccion" name="o<?php echo $doctor_grid->RowIndex ?>_direccion" id="o<?php echo $doctor_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($doctor->direccion->OldValue) ?>">
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_direccion" class="form-group doctor_direccion">
<input type="text" data-field="x_direccion" name="x<?php echo $doctor_grid->RowIndex ?>_direccion" id="x<?php echo $doctor_grid->RowIndex ?>_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->direccion->PlaceHolder) ?>" value="<?php echo $doctor->direccion->EditValue ?>"<?php echo $doctor->direccion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor->direccion->ViewAttributes() ?>>
<?php echo $doctor->direccion->ListViewValue() ?></span>
<input type="hidden" data-field="x_direccion" name="x<?php echo $doctor_grid->RowIndex ?>_direccion" id="x<?php echo $doctor_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($doctor->direccion->FormValue) ?>">
<input type="hidden" data-field="x_direccion" name="o<?php echo $doctor_grid->RowIndex ?>_direccion" id="o<?php echo $doctor_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($doctor->direccion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($doctor->idturno->Visible) { // idturno ?>
		<td data-name="idturno"<?php echo $doctor->idturno->CellAttributes() ?>>
<?php if ($doctor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($doctor->idturno->getSessionValue() <> "") { ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_idturno" class="form-group doctor_idturno">
<span<?php echo $doctor->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $doctor_grid->RowIndex ?>_idturno" name="x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_idturno" class="form-group doctor_idturno">
<?php
	$wrkonchange = trim(" " . @$doctor->idturno->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$doctor->idturno->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $doctor_grid->RowIndex ?>_idturno" style="white-space: nowrap; z-index: <?php echo (9000 - $doctor_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $doctor_grid->RowIndex ?>_idturno" id="sv_x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo $doctor->idturno->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($doctor->idturno->PlaceHolder) ?>"<?php echo $doctor->idturno->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_idturno" name="x<?php echo $doctor_grid->RowIndex ?>_idturno" id="x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($doctor->idturno->PlaceHolder) ?>">
<?php
 $sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld` FROM `turno`";
 $sWhereWrk = "`descripcion` LIKE '{query_value}%'";

 // Call Lookup selecting
 $doctor->Lookup_Selecting($doctor->idturno, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $doctor_grid->RowIndex ?>_idturno" id="q_x<?php echo $doctor_grid->RowIndex ?>_idturno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fdoctorgrid.CreateAutoSuggest("x<?php echo $doctor_grid->RowIndex ?>_idturno", true);
</script>
</span>
<?php } ?>
<input type="hidden" data-field="x_idturno" name="o<?php echo $doctor_grid->RowIndex ?>_idturno" id="o<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->OldValue) ?>">
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($doctor->idturno->getSessionValue() <> "") { ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_idturno" class="form-group doctor_idturno">
<span<?php echo $doctor->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $doctor_grid->RowIndex ?>_idturno" name="x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_idturno" class="form-group doctor_idturno">
<?php
	$wrkonchange = trim(" " . @$doctor->idturno->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$doctor->idturno->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $doctor_grid->RowIndex ?>_idturno" style="white-space: nowrap; z-index: <?php echo (9000 - $doctor_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $doctor_grid->RowIndex ?>_idturno" id="sv_x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo $doctor->idturno->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($doctor->idturno->PlaceHolder) ?>"<?php echo $doctor->idturno->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_idturno" name="x<?php echo $doctor_grid->RowIndex ?>_idturno" id="x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($doctor->idturno->PlaceHolder) ?>">
<?php
 $sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld` FROM `turno`";
 $sWhereWrk = "`descripcion` LIKE '{query_value}%'";

 // Call Lookup selecting
 $doctor->Lookup_Selecting($doctor->idturno, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $doctor_grid->RowIndex ?>_idturno" id="q_x<?php echo $doctor_grid->RowIndex ?>_idturno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fdoctorgrid.CreateAutoSuggest("x<?php echo $doctor_grid->RowIndex ?>_idturno", true);
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor->idturno->ViewAttributes() ?>>
<?php echo $doctor->idturno->ListViewValue() ?></span>
<input type="hidden" data-field="x_idturno" name="x<?php echo $doctor_grid->RowIndex ?>_idturno" id="x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->FormValue) ?>">
<input type="hidden" data-field="x_idturno" name="o<?php echo $doctor_grid->RowIndex ?>_idturno" id="o<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($doctor->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $doctor->estado->CellAttributes() ?>>
<?php if ($doctor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_estado" class="form-group doctor_estado">
<select data-field="x_estado" id="x<?php echo $doctor_grid->RowIndex ?>_estado" name="x<?php echo $doctor_grid->RowIndex ?>_estado"<?php echo $doctor->estado->EditAttributes() ?>>
<?php
if (is_array($doctor->estado->EditValue)) {
	$arwrk = $doctor->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor->estado->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $doctor_grid->RowIndex ?>_estado" id="o<?php echo $doctor_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($doctor->estado->OldValue) ?>">
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_grid->RowCnt ?>_doctor_estado" class="form-group doctor_estado">
<select data-field="x_estado" id="x<?php echo $doctor_grid->RowIndex ?>_estado" name="x<?php echo $doctor_grid->RowIndex ?>_estado"<?php echo $doctor->estado->EditAttributes() ?>>
<?php
if (is_array($doctor->estado->EditValue)) {
	$arwrk = $doctor->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor->estado->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($doctor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor->estado->ViewAttributes() ?>>
<?php echo $doctor->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $doctor_grid->RowIndex ?>_estado" id="x<?php echo $doctor_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($doctor->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $doctor_grid->RowIndex ?>_estado" id="o<?php echo $doctor_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($doctor->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$doctor_grid->ListOptions->Render("body", "right", $doctor_grid->RowCnt);
?>
	</tr>
<?php if ($doctor->RowType == EW_ROWTYPE_ADD || $doctor->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdoctorgrid.UpdateOpts(<?php echo $doctor_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($doctor->CurrentAction <> "gridadd" || $doctor->CurrentMode == "copy")
		if (!$doctor_grid->Recordset->EOF) $doctor_grid->Recordset->MoveNext();
}
?>
<?php
	if ($doctor->CurrentMode == "add" || $doctor->CurrentMode == "copy" || $doctor->CurrentMode == "edit") {
		$doctor_grid->RowIndex = '$rowindex$';
		$doctor_grid->LoadDefaultValues();

		// Set row properties
		$doctor->ResetAttrs();
		$doctor->RowAttrs = array_merge($doctor->RowAttrs, array('data-rowindex'=>$doctor_grid->RowIndex, 'id'=>'r0_doctor', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($doctor->RowAttrs["class"], "ewTemplate");
		$doctor->RowType = EW_ROWTYPE_ADD;

		// Render row
		$doctor_grid->RenderRow();

		// Render list options
		$doctor_grid->RenderListOptions();
		$doctor_grid->StartRowCnt = 0;
?>
	<tr<?php echo $doctor->RowAttributes() ?>>
<?php

// Render list options (body, left)
$doctor_grid->ListOptions->Render("body", "left", $doctor_grid->RowIndex);
?>
	<?php if ($doctor->nombre->Visible) { // nombre ?>
		<td>
<?php if ($doctor->CurrentAction <> "F") { ?>
<span id="el$rowindex$_doctor_nombre" class="form-group doctor_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $doctor_grid->RowIndex ?>_nombre" id="x<?php echo $doctor_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->nombre->PlaceHolder) ?>" value="<?php echo $doctor->nombre->EditValue ?>"<?php echo $doctor->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_doctor_nombre" class="form-group doctor_nombre">
<span<?php echo $doctor->nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $doctor_grid->RowIndex ?>_nombre" id="x<?php echo $doctor_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($doctor->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nombre" name="o<?php echo $doctor_grid->RowIndex ?>_nombre" id="o<?php echo $doctor_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($doctor->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor->apellido->Visible) { // apellido ?>
		<td>
<?php if ($doctor->CurrentAction <> "F") { ?>
<span id="el$rowindex$_doctor_apellido" class="form-group doctor_apellido">
<input type="text" data-field="x_apellido" name="x<?php echo $doctor_grid->RowIndex ?>_apellido" id="x<?php echo $doctor_grid->RowIndex ?>_apellido" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->apellido->PlaceHolder) ?>" value="<?php echo $doctor->apellido->EditValue ?>"<?php echo $doctor->apellido->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_doctor_apellido" class="form-group doctor_apellido">
<span<?php echo $doctor->apellido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->apellido->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_apellido" name="x<?php echo $doctor_grid->RowIndex ?>_apellido" id="x<?php echo $doctor_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($doctor->apellido->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_apellido" name="o<?php echo $doctor_grid->RowIndex ?>_apellido" id="o<?php echo $doctor_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($doctor->apellido->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor->cui->Visible) { // cui ?>
		<td>
<?php if ($doctor->CurrentAction <> "F") { ?>
<span id="el$rowindex$_doctor_cui" class="form-group doctor_cui">
<input type="text" data-field="x_cui" name="x<?php echo $doctor_grid->RowIndex ?>_cui" id="x<?php echo $doctor_grid->RowIndex ?>_cui" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->cui->PlaceHolder) ?>" value="<?php echo $doctor->cui->EditValue ?>"<?php echo $doctor->cui->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_doctor_cui" class="form-group doctor_cui">
<span<?php echo $doctor->cui->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->cui->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_cui" name="x<?php echo $doctor_grid->RowIndex ?>_cui" id="x<?php echo $doctor_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($doctor->cui->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_cui" name="o<?php echo $doctor_grid->RowIndex ?>_cui" id="o<?php echo $doctor_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($doctor->cui->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor->telefono->Visible) { // telefono ?>
		<td>
<?php if ($doctor->CurrentAction <> "F") { ?>
<span id="el$rowindex$_doctor_telefono" class="form-group doctor_telefono">
<input type="text" data-field="x_telefono" name="x<?php echo $doctor_grid->RowIndex ?>_telefono" id="x<?php echo $doctor_grid->RowIndex ?>_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->telefono->PlaceHolder) ?>" value="<?php echo $doctor->telefono->EditValue ?>"<?php echo $doctor->telefono->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_doctor_telefono" class="form-group doctor_telefono">
<span<?php echo $doctor->telefono->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->telefono->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_telefono" name="x<?php echo $doctor_grid->RowIndex ?>_telefono" id="x<?php echo $doctor_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($doctor->telefono->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_telefono" name="o<?php echo $doctor_grid->RowIndex ?>_telefono" id="o<?php echo $doctor_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($doctor->telefono->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor->direccion->Visible) { // direccion ?>
		<td>
<?php if ($doctor->CurrentAction <> "F") { ?>
<span id="el$rowindex$_doctor_direccion" class="form-group doctor_direccion">
<input type="text" data-field="x_direccion" name="x<?php echo $doctor_grid->RowIndex ?>_direccion" id="x<?php echo $doctor_grid->RowIndex ?>_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->direccion->PlaceHolder) ?>" value="<?php echo $doctor->direccion->EditValue ?>"<?php echo $doctor->direccion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_doctor_direccion" class="form-group doctor_direccion">
<span<?php echo $doctor->direccion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->direccion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_direccion" name="x<?php echo $doctor_grid->RowIndex ?>_direccion" id="x<?php echo $doctor_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($doctor->direccion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_direccion" name="o<?php echo $doctor_grid->RowIndex ?>_direccion" id="o<?php echo $doctor_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($doctor->direccion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor->idturno->Visible) { // idturno ?>
		<td>
<?php if ($doctor->CurrentAction <> "F") { ?>
<?php if ($doctor->idturno->getSessionValue() <> "") { ?>
<span id="el$rowindex$_doctor_idturno" class="form-group doctor_idturno">
<span<?php echo $doctor->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $doctor_grid->RowIndex ?>_idturno" name="x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_doctor_idturno" class="form-group doctor_idturno">
<?php
	$wrkonchange = trim(" " . @$doctor->idturno->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$doctor->idturno->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $doctor_grid->RowIndex ?>_idturno" style="white-space: nowrap; z-index: <?php echo (9000 - $doctor_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $doctor_grid->RowIndex ?>_idturno" id="sv_x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo $doctor->idturno->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($doctor->idturno->PlaceHolder) ?>"<?php echo $doctor->idturno->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_idturno" name="x<?php echo $doctor_grid->RowIndex ?>_idturno" id="x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($doctor->idturno->PlaceHolder) ?>">
<?php
 $sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld` FROM `turno`";
 $sWhereWrk = "`descripcion` LIKE '{query_value}%'";

 // Call Lookup selecting
 $doctor->Lookup_Selecting($doctor->idturno, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $doctor_grid->RowIndex ?>_idturno" id="q_x<?php echo $doctor_grid->RowIndex ?>_idturno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fdoctorgrid.CreateAutoSuggest("x<?php echo $doctor_grid->RowIndex ?>_idturno", true);
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_doctor_idturno" class="form-group doctor_idturno">
<span<?php echo $doctor->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idturno" name="x<?php echo $doctor_grid->RowIndex ?>_idturno" id="x<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idturno" name="o<?php echo $doctor_grid->RowIndex ?>_idturno" id="o<?php echo $doctor_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor->estado->Visible) { // estado ?>
		<td>
<?php if ($doctor->CurrentAction <> "F") { ?>
<span id="el$rowindex$_doctor_estado" class="form-group doctor_estado">
<select data-field="x_estado" id="x<?php echo $doctor_grid->RowIndex ?>_estado" name="x<?php echo $doctor_grid->RowIndex ?>_estado"<?php echo $doctor->estado->EditAttributes() ?>>
<?php
if (is_array($doctor->estado->EditValue)) {
	$arwrk = $doctor->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor->estado->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_doctor_estado" class="form-group doctor_estado">
<span<?php echo $doctor->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $doctor_grid->RowIndex ?>_estado" id="x<?php echo $doctor_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($doctor->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $doctor_grid->RowIndex ?>_estado" id="o<?php echo $doctor_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($doctor->estado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$doctor_grid->ListOptions->Render("body", "right", $doctor_grid->RowCnt);
?>
<script type="text/javascript">
fdoctorgrid.UpdateOpts(<?php echo $doctor_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($doctor->CurrentMode == "add" || $doctor->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $doctor_grid->FormKeyCountName ?>" id="<?php echo $doctor_grid->FormKeyCountName ?>" value="<?php echo $doctor_grid->KeyCount ?>">
<?php echo $doctor_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($doctor->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $doctor_grid->FormKeyCountName ?>" id="<?php echo $doctor_grid->FormKeyCountName ?>" value="<?php echo $doctor_grid->KeyCount ?>">
<?php echo $doctor_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($doctor->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdoctorgrid">
</div>
<?php

// Close recordset
if ($doctor_grid->Recordset)
	$doctor_grid->Recordset->Close();
?>
<?php if ($doctor_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($doctor_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($doctor_grid->TotalRecs == 0 && $doctor->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($doctor_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($doctor->Export == "") { ?>
<script type="text/javascript">
fdoctorgrid.Init();
</script>
<?php } ?>
<?php
$doctor_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$doctor_grid->Page_Terminate();
?>

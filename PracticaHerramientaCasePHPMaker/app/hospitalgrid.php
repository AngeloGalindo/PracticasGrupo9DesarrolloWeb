<?php

// Create page object
if (!isset($hospital_grid)) $hospital_grid = new chospital_grid();

// Page init
$hospital_grid->Page_Init();

// Page main
$hospital_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hospital_grid->Page_Render();
?>
<?php if ($hospital->Export == "") { ?>
<script type="text/javascript">

// Page object
var hospital_grid = new ew_Page("hospital_grid");
hospital_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = hospital_grid.PageID; // For backward compatibility

// Form object
var fhospitalgrid = new ew_Form("fhospitalgrid");
fhospitalgrid.FormKeyCountName = '<?php echo $hospital_grid->FormKeyCountName ?>';

// Validate form
fhospitalgrid.Validate = function() {
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
fhospitalgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telefono", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	return true;
}

// Form_CustomValidate event
fhospitalgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhospitalgrid.ValidateRequired = true;
<?php } else { ?>
fhospitalgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($hospital->CurrentAction == "gridadd") {
	if ($hospital->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$hospital_grid->TotalRecs = $hospital->SelectRecordCount();
			$hospital_grid->Recordset = $hospital_grid->LoadRecordset($hospital_grid->StartRec-1, $hospital_grid->DisplayRecs);
		} else {
			if ($hospital_grid->Recordset = $hospital_grid->LoadRecordset())
				$hospital_grid->TotalRecs = $hospital_grid->Recordset->RecordCount();
		}
		$hospital_grid->StartRec = 1;
		$hospital_grid->DisplayRecs = $hospital_grid->TotalRecs;
	} else {
		$hospital->CurrentFilter = "0=1";
		$hospital_grid->StartRec = 1;
		$hospital_grid->DisplayRecs = $hospital->GridAddRowCount;
	}
	$hospital_grid->TotalRecs = $hospital_grid->DisplayRecs;
	$hospital_grid->StopRec = $hospital_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$hospital_grid->TotalRecs = $hospital->SelectRecordCount();
	} else {
		if ($hospital_grid->Recordset = $hospital_grid->LoadRecordset())
			$hospital_grid->TotalRecs = $hospital_grid->Recordset->RecordCount();
	}
	$hospital_grid->StartRec = 1;
	$hospital_grid->DisplayRecs = $hospital_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$hospital_grid->Recordset = $hospital_grid->LoadRecordset($hospital_grid->StartRec-1, $hospital_grid->DisplayRecs);

	// Set no record found message
	if ($hospital->CurrentAction == "" && $hospital_grid->TotalRecs == 0) {
		if ($hospital_grid->SearchWhere == "0=101")
			$hospital_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$hospital_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$hospital_grid->RenderOtherOptions();
?>
<?php $hospital_grid->ShowPageHeader(); ?>
<?php
$hospital_grid->ShowMessage();
?>
<?php if ($hospital_grid->TotalRecs > 0 || $hospital->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fhospitalgrid" class="ewForm form-inline">
<div id="gmp_hospital" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_hospitalgrid" class="table ewTable">
<?php echo $hospital->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$hospital_grid->RenderListOptions();

// Render list options (header, left)
$hospital_grid->ListOptions->Render("header", "left");
?>
<?php if ($hospital->nombre->Visible) { // nombre ?>
	<?php if ($hospital->SortUrl($hospital->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_hospital_nombre" class="hospital_nombre"><div class="ewTableHeaderCaption"><?php echo $hospital->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div><div id="elh_hospital_nombre" class="hospital_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hospital->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hospital->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hospital->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hospital->telefono->Visible) { // telefono ?>
	<?php if ($hospital->SortUrl($hospital->telefono) == "") { ?>
		<th data-name="telefono"><div id="elh_hospital_telefono" class="hospital_telefono"><div class="ewTableHeaderCaption"><?php echo $hospital->telefono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono"><div><div id="elh_hospital_telefono" class="hospital_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hospital->telefono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hospital->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hospital->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($hospital->estado->Visible) { // estado ?>
	<?php if ($hospital->SortUrl($hospital->estado) == "") { ?>
		<th data-name="estado"><div id="elh_hospital_estado" class="hospital_estado"><div class="ewTableHeaderCaption"><?php echo $hospital->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_hospital_estado" class="hospital_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $hospital->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($hospital->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($hospital->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$hospital_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$hospital_grid->StartRec = 1;
$hospital_grid->StopRec = $hospital_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($hospital_grid->FormKeyCountName) && ($hospital->CurrentAction == "gridadd" || $hospital->CurrentAction == "gridedit" || $hospital->CurrentAction == "F")) {
		$hospital_grid->KeyCount = $objForm->GetValue($hospital_grid->FormKeyCountName);
		$hospital_grid->StopRec = $hospital_grid->StartRec + $hospital_grid->KeyCount - 1;
	}
}
$hospital_grid->RecCnt = $hospital_grid->StartRec - 1;
if ($hospital_grid->Recordset && !$hospital_grid->Recordset->EOF) {
	$hospital_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $hospital_grid->StartRec > 1)
		$hospital_grid->Recordset->Move($hospital_grid->StartRec - 1);
} elseif (!$hospital->AllowAddDeleteRow && $hospital_grid->StopRec == 0) {
	$hospital_grid->StopRec = $hospital->GridAddRowCount;
}

// Initialize aggregate
$hospital->RowType = EW_ROWTYPE_AGGREGATEINIT;
$hospital->ResetAttrs();
$hospital_grid->RenderRow();
if ($hospital->CurrentAction == "gridadd")
	$hospital_grid->RowIndex = 0;
if ($hospital->CurrentAction == "gridedit")
	$hospital_grid->RowIndex = 0;
while ($hospital_grid->RecCnt < $hospital_grid->StopRec) {
	$hospital_grid->RecCnt++;
	if (intval($hospital_grid->RecCnt) >= intval($hospital_grid->StartRec)) {
		$hospital_grid->RowCnt++;
		if ($hospital->CurrentAction == "gridadd" || $hospital->CurrentAction == "gridedit" || $hospital->CurrentAction == "F") {
			$hospital_grid->RowIndex++;
			$objForm->Index = $hospital_grid->RowIndex;
			if ($objForm->HasValue($hospital_grid->FormActionName))
				$hospital_grid->RowAction = strval($objForm->GetValue($hospital_grid->FormActionName));
			elseif ($hospital->CurrentAction == "gridadd")
				$hospital_grid->RowAction = "insert";
			else
				$hospital_grid->RowAction = "";
		}

		// Set up key count
		$hospital_grid->KeyCount = $hospital_grid->RowIndex;

		// Init row class and style
		$hospital->ResetAttrs();
		$hospital->CssClass = "";
		if ($hospital->CurrentAction == "gridadd") {
			if ($hospital->CurrentMode == "copy") {
				$hospital_grid->LoadRowValues($hospital_grid->Recordset); // Load row values
				$hospital_grid->SetRecordKey($hospital_grid->RowOldKey, $hospital_grid->Recordset); // Set old record key
			} else {
				$hospital_grid->LoadDefaultValues(); // Load default values
				$hospital_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$hospital_grid->LoadRowValues($hospital_grid->Recordset); // Load row values
		}
		$hospital->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($hospital->CurrentAction == "gridadd") // Grid add
			$hospital->RowType = EW_ROWTYPE_ADD; // Render add
		if ($hospital->CurrentAction == "gridadd" && $hospital->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$hospital_grid->RestoreCurrentRowFormValues($hospital_grid->RowIndex); // Restore form values
		if ($hospital->CurrentAction == "gridedit") { // Grid edit
			if ($hospital->EventCancelled) {
				$hospital_grid->RestoreCurrentRowFormValues($hospital_grid->RowIndex); // Restore form values
			}
			if ($hospital_grid->RowAction == "insert")
				$hospital->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$hospital->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($hospital->CurrentAction == "gridedit" && ($hospital->RowType == EW_ROWTYPE_EDIT || $hospital->RowType == EW_ROWTYPE_ADD) && $hospital->EventCancelled) // Update failed
			$hospital_grid->RestoreCurrentRowFormValues($hospital_grid->RowIndex); // Restore form values
		if ($hospital->RowType == EW_ROWTYPE_EDIT) // Edit row
			$hospital_grid->EditRowCnt++;
		if ($hospital->CurrentAction == "F") // Confirm row
			$hospital_grid->RestoreCurrentRowFormValues($hospital_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$hospital->RowAttrs = array_merge($hospital->RowAttrs, array('data-rowindex'=>$hospital_grid->RowCnt, 'id'=>'r' . $hospital_grid->RowCnt . '_hospital', 'data-rowtype'=>$hospital->RowType));

		// Render row
		$hospital_grid->RenderRow();

		// Render list options
		$hospital_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($hospital_grid->RowAction <> "delete" && $hospital_grid->RowAction <> "insertdelete" && !($hospital_grid->RowAction == "insert" && $hospital->CurrentAction == "F" && $hospital_grid->EmptyRow())) {
?>
	<tr<?php echo $hospital->RowAttributes() ?>>
<?php

// Render list options (body, left)
$hospital_grid->ListOptions->Render("body", "left", $hospital_grid->RowCnt);
?>
	<?php if ($hospital->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $hospital->nombre->CellAttributes() ?>>
<?php if ($hospital->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $hospital_grid->RowCnt ?>_hospital_nombre" class="form-group hospital_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $hospital_grid->RowIndex ?>_nombre" id="x<?php echo $hospital_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($hospital->nombre->PlaceHolder) ?>" value="<?php echo $hospital->nombre->EditValue ?>"<?php echo $hospital->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nombre" name="o<?php echo $hospital_grid->RowIndex ?>_nombre" id="o<?php echo $hospital_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($hospital->nombre->OldValue) ?>">
<?php } ?>
<?php if ($hospital->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $hospital_grid->RowCnt ?>_hospital_nombre" class="form-group hospital_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $hospital_grid->RowIndex ?>_nombre" id="x<?php echo $hospital_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($hospital->nombre->PlaceHolder) ?>" value="<?php echo $hospital->nombre->EditValue ?>"<?php echo $hospital->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($hospital->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $hospital->nombre->ViewAttributes() ?>>
<?php echo $hospital->nombre->ListViewValue() ?></span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $hospital_grid->RowIndex ?>_nombre" id="x<?php echo $hospital_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($hospital->nombre->FormValue) ?>">
<input type="hidden" data-field="x_nombre" name="o<?php echo $hospital_grid->RowIndex ?>_nombre" id="o<?php echo $hospital_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($hospital->nombre->OldValue) ?>">
<?php } ?>
<a id="<?php echo $hospital_grid->PageObjName . "_row_" . $hospital_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($hospital->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $hospital_grid->RowIndex ?>_idhospital" id="x<?php echo $hospital_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($hospital->idhospital->CurrentValue) ?>">
<input type="hidden" data-field="x_idhospital" name="o<?php echo $hospital_grid->RowIndex ?>_idhospital" id="o<?php echo $hospital_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($hospital->idhospital->OldValue) ?>">
<?php } ?>
<?php if ($hospital->RowType == EW_ROWTYPE_EDIT || $hospital->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $hospital_grid->RowIndex ?>_idhospital" id="x<?php echo $hospital_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($hospital->idhospital->CurrentValue) ?>">
<?php } ?>
	<?php if ($hospital->telefono->Visible) { // telefono ?>
		<td data-name="telefono"<?php echo $hospital->telefono->CellAttributes() ?>>
<?php if ($hospital->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $hospital_grid->RowCnt ?>_hospital_telefono" class="form-group hospital_telefono">
<input type="text" data-field="x_telefono" name="x<?php echo $hospital_grid->RowIndex ?>_telefono" id="x<?php echo $hospital_grid->RowIndex ?>_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($hospital->telefono->PlaceHolder) ?>" value="<?php echo $hospital->telefono->EditValue ?>"<?php echo $hospital->telefono->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_telefono" name="o<?php echo $hospital_grid->RowIndex ?>_telefono" id="o<?php echo $hospital_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($hospital->telefono->OldValue) ?>">
<?php } ?>
<?php if ($hospital->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $hospital_grid->RowCnt ?>_hospital_telefono" class="form-group hospital_telefono">
<input type="text" data-field="x_telefono" name="x<?php echo $hospital_grid->RowIndex ?>_telefono" id="x<?php echo $hospital_grid->RowIndex ?>_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($hospital->telefono->PlaceHolder) ?>" value="<?php echo $hospital->telefono->EditValue ?>"<?php echo $hospital->telefono->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($hospital->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $hospital->telefono->ViewAttributes() ?>>
<?php echo $hospital->telefono->ListViewValue() ?></span>
<input type="hidden" data-field="x_telefono" name="x<?php echo $hospital_grid->RowIndex ?>_telefono" id="x<?php echo $hospital_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($hospital->telefono->FormValue) ?>">
<input type="hidden" data-field="x_telefono" name="o<?php echo $hospital_grid->RowIndex ?>_telefono" id="o<?php echo $hospital_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($hospital->telefono->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($hospital->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $hospital->estado->CellAttributes() ?>>
<?php if ($hospital->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $hospital_grid->RowCnt ?>_hospital_estado" class="form-group hospital_estado">
<select data-field="x_estado" id="x<?php echo $hospital_grid->RowIndex ?>_estado" name="x<?php echo $hospital_grid->RowIndex ?>_estado"<?php echo $hospital->estado->EditAttributes() ?>>
<?php
if (is_array($hospital->estado->EditValue)) {
	$arwrk = $hospital->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hospital->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $hospital->estado->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $hospital_grid->RowIndex ?>_estado" id="o<?php echo $hospital_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($hospital->estado->OldValue) ?>">
<?php } ?>
<?php if ($hospital->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $hospital_grid->RowCnt ?>_hospital_estado" class="form-group hospital_estado">
<select data-field="x_estado" id="x<?php echo $hospital_grid->RowIndex ?>_estado" name="x<?php echo $hospital_grid->RowIndex ?>_estado"<?php echo $hospital->estado->EditAttributes() ?>>
<?php
if (is_array($hospital->estado->EditValue)) {
	$arwrk = $hospital->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hospital->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $hospital->estado->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($hospital->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $hospital->estado->ViewAttributes() ?>>
<?php echo $hospital->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $hospital_grid->RowIndex ?>_estado" id="x<?php echo $hospital_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($hospital->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $hospital_grid->RowIndex ?>_estado" id="o<?php echo $hospital_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($hospital->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$hospital_grid->ListOptions->Render("body", "right", $hospital_grid->RowCnt);
?>
	</tr>
<?php if ($hospital->RowType == EW_ROWTYPE_ADD || $hospital->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fhospitalgrid.UpdateOpts(<?php echo $hospital_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($hospital->CurrentAction <> "gridadd" || $hospital->CurrentMode == "copy")
		if (!$hospital_grid->Recordset->EOF) $hospital_grid->Recordset->MoveNext();
}
?>
<?php
	if ($hospital->CurrentMode == "add" || $hospital->CurrentMode == "copy" || $hospital->CurrentMode == "edit") {
		$hospital_grid->RowIndex = '$rowindex$';
		$hospital_grid->LoadDefaultValues();

		// Set row properties
		$hospital->ResetAttrs();
		$hospital->RowAttrs = array_merge($hospital->RowAttrs, array('data-rowindex'=>$hospital_grid->RowIndex, 'id'=>'r0_hospital', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($hospital->RowAttrs["class"], "ewTemplate");
		$hospital->RowType = EW_ROWTYPE_ADD;

		// Render row
		$hospital_grid->RenderRow();

		// Render list options
		$hospital_grid->RenderListOptions();
		$hospital_grid->StartRowCnt = 0;
?>
	<tr<?php echo $hospital->RowAttributes() ?>>
<?php

// Render list options (body, left)
$hospital_grid->ListOptions->Render("body", "left", $hospital_grid->RowIndex);
?>
	<?php if ($hospital->nombre->Visible) { // nombre ?>
		<td>
<?php if ($hospital->CurrentAction <> "F") { ?>
<span id="el$rowindex$_hospital_nombre" class="form-group hospital_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $hospital_grid->RowIndex ?>_nombre" id="x<?php echo $hospital_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($hospital->nombre->PlaceHolder) ?>" value="<?php echo $hospital->nombre->EditValue ?>"<?php echo $hospital->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_hospital_nombre" class="form-group hospital_nombre">
<span<?php echo $hospital->nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $hospital->nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $hospital_grid->RowIndex ?>_nombre" id="x<?php echo $hospital_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($hospital->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nombre" name="o<?php echo $hospital_grid->RowIndex ?>_nombre" id="o<?php echo $hospital_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($hospital->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($hospital->telefono->Visible) { // telefono ?>
		<td>
<?php if ($hospital->CurrentAction <> "F") { ?>
<span id="el$rowindex$_hospital_telefono" class="form-group hospital_telefono">
<input type="text" data-field="x_telefono" name="x<?php echo $hospital_grid->RowIndex ?>_telefono" id="x<?php echo $hospital_grid->RowIndex ?>_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($hospital->telefono->PlaceHolder) ?>" value="<?php echo $hospital->telefono->EditValue ?>"<?php echo $hospital->telefono->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_hospital_telefono" class="form-group hospital_telefono">
<span<?php echo $hospital->telefono->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $hospital->telefono->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_telefono" name="x<?php echo $hospital_grid->RowIndex ?>_telefono" id="x<?php echo $hospital_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($hospital->telefono->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_telefono" name="o<?php echo $hospital_grid->RowIndex ?>_telefono" id="o<?php echo $hospital_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($hospital->telefono->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($hospital->estado->Visible) { // estado ?>
		<td>
<?php if ($hospital->CurrentAction <> "F") { ?>
<span id="el$rowindex$_hospital_estado" class="form-group hospital_estado">
<select data-field="x_estado" id="x<?php echo $hospital_grid->RowIndex ?>_estado" name="x<?php echo $hospital_grid->RowIndex ?>_estado"<?php echo $hospital->estado->EditAttributes() ?>>
<?php
if (is_array($hospital->estado->EditValue)) {
	$arwrk = $hospital->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hospital->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $hospital->estado->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_hospital_estado" class="form-group hospital_estado">
<span<?php echo $hospital->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $hospital->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $hospital_grid->RowIndex ?>_estado" id="x<?php echo $hospital_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($hospital->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $hospital_grid->RowIndex ?>_estado" id="o<?php echo $hospital_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($hospital->estado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$hospital_grid->ListOptions->Render("body", "right", $hospital_grid->RowCnt);
?>
<script type="text/javascript">
fhospitalgrid.UpdateOpts(<?php echo $hospital_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($hospital->CurrentMode == "add" || $hospital->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $hospital_grid->FormKeyCountName ?>" id="<?php echo $hospital_grid->FormKeyCountName ?>" value="<?php echo $hospital_grid->KeyCount ?>">
<?php echo $hospital_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($hospital->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $hospital_grid->FormKeyCountName ?>" id="<?php echo $hospital_grid->FormKeyCountName ?>" value="<?php echo $hospital_grid->KeyCount ?>">
<?php echo $hospital_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($hospital->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fhospitalgrid">
</div>
<?php

// Close recordset
if ($hospital_grid->Recordset)
	$hospital_grid->Recordset->Close();
?>
<?php if ($hospital_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($hospital_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($hospital_grid->TotalRecs == 0 && $hospital->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($hospital_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($hospital->Export == "") { ?>
<script type="text/javascript">
fhospitalgrid.Init();
</script>
<?php } ?>
<?php
$hospital_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$hospital_grid->Page_Terminate();
?>

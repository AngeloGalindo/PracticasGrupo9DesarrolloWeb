<?php

// Create page object
if (!isset($doctor_especialidad_grid)) $doctor_especialidad_grid = new cdoctor_especialidad_grid();

// Page init
$doctor_especialidad_grid->Page_Init();

// Page main
$doctor_especialidad_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$doctor_especialidad_grid->Page_Render();
?>
<?php if ($doctor_especialidad->Export == "") { ?>
<script type="text/javascript">

// Page object
var doctor_especialidad_grid = new ew_Page("doctor_especialidad_grid");
doctor_especialidad_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = doctor_especialidad_grid.PageID; // For backward compatibility

// Form object
var fdoctor_especialidadgrid = new ew_Form("fdoctor_especialidadgrid");
fdoctor_especialidadgrid.FormKeyCountName = '<?php echo $doctor_especialidad_grid->FormKeyCountName ?>';

// Validate form
fdoctor_especialidadgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_iddoctor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor_especialidad->iddoctor->FldCaption(), $doctor_especialidad->iddoctor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idespecialidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor_especialidad->idespecialidad->FldCaption(), $doctor_especialidad->idespecialidad->ReqErrMsg)) ?>");

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
fdoctor_especialidadgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "iddoctor", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idespecialidad", false)) return false;
	return true;
}

// Form_CustomValidate event
fdoctor_especialidadgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdoctor_especialidadgrid.ValidateRequired = true;
<?php } else { ?>
fdoctor_especialidadgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdoctor_especialidadgrid.Lists["x_iddoctor"] = {"LinkField":"x_iddoctor","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdoctor_especialidadgrid.Lists["x_idespecialidad"] = {"LinkField":"x_idespecialidad","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($doctor_especialidad->CurrentAction == "gridadd") {
	if ($doctor_especialidad->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$doctor_especialidad_grid->TotalRecs = $doctor_especialidad->SelectRecordCount();
			$doctor_especialidad_grid->Recordset = $doctor_especialidad_grid->LoadRecordset($doctor_especialidad_grid->StartRec-1, $doctor_especialidad_grid->DisplayRecs);
		} else {
			if ($doctor_especialidad_grid->Recordset = $doctor_especialidad_grid->LoadRecordset())
				$doctor_especialidad_grid->TotalRecs = $doctor_especialidad_grid->Recordset->RecordCount();
		}
		$doctor_especialidad_grid->StartRec = 1;
		$doctor_especialidad_grid->DisplayRecs = $doctor_especialidad_grid->TotalRecs;
	} else {
		$doctor_especialidad->CurrentFilter = "0=1";
		$doctor_especialidad_grid->StartRec = 1;
		$doctor_especialidad_grid->DisplayRecs = $doctor_especialidad->GridAddRowCount;
	}
	$doctor_especialidad_grid->TotalRecs = $doctor_especialidad_grid->DisplayRecs;
	$doctor_especialidad_grid->StopRec = $doctor_especialidad_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$doctor_especialidad_grid->TotalRecs = $doctor_especialidad->SelectRecordCount();
	} else {
		if ($doctor_especialidad_grid->Recordset = $doctor_especialidad_grid->LoadRecordset())
			$doctor_especialidad_grid->TotalRecs = $doctor_especialidad_grid->Recordset->RecordCount();
	}
	$doctor_especialidad_grid->StartRec = 1;
	$doctor_especialidad_grid->DisplayRecs = $doctor_especialidad_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$doctor_especialidad_grid->Recordset = $doctor_especialidad_grid->LoadRecordset($doctor_especialidad_grid->StartRec-1, $doctor_especialidad_grid->DisplayRecs);

	// Set no record found message
	if ($doctor_especialidad->CurrentAction == "" && $doctor_especialidad_grid->TotalRecs == 0) {
		if ($doctor_especialidad_grid->SearchWhere == "0=101")
			$doctor_especialidad_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$doctor_especialidad_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$doctor_especialidad_grid->RenderOtherOptions();
?>
<?php $doctor_especialidad_grid->ShowPageHeader(); ?>
<?php
$doctor_especialidad_grid->ShowMessage();
?>
<?php if ($doctor_especialidad_grid->TotalRecs > 0 || $doctor_especialidad->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdoctor_especialidadgrid" class="ewForm form-inline">
<div id="gmp_doctor_especialidad" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_doctor_especialidadgrid" class="table ewTable">
<?php echo $doctor_especialidad->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$doctor_especialidad_grid->RenderListOptions();

// Render list options (header, left)
$doctor_especialidad_grid->ListOptions->Render("header", "left");
?>
<?php if ($doctor_especialidad->iddoctor_especialidad->Visible) { // iddoctor_especialidad ?>
	<?php if ($doctor_especialidad->SortUrl($doctor_especialidad->iddoctor_especialidad) == "") { ?>
		<th data-name="iddoctor_especialidad"><div id="elh_doctor_especialidad_iddoctor_especialidad" class="doctor_especialidad_iddoctor_especialidad"><div class="ewTableHeaderCaption"><?php echo $doctor_especialidad->iddoctor_especialidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iddoctor_especialidad"><div><div id="elh_doctor_especialidad_iddoctor_especialidad" class="doctor_especialidad_iddoctor_especialidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor_especialidad->iddoctor_especialidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor_especialidad->iddoctor_especialidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor_especialidad->iddoctor_especialidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor_especialidad->iddoctor->Visible) { // iddoctor ?>
	<?php if ($doctor_especialidad->SortUrl($doctor_especialidad->iddoctor) == "") { ?>
		<th data-name="iddoctor"><div id="elh_doctor_especialidad_iddoctor" class="doctor_especialidad_iddoctor"><div class="ewTableHeaderCaption"><?php echo $doctor_especialidad->iddoctor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iddoctor"><div><div id="elh_doctor_especialidad_iddoctor" class="doctor_especialidad_iddoctor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor_especialidad->iddoctor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor_especialidad->iddoctor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor_especialidad->iddoctor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor_especialidad->idespecialidad->Visible) { // idespecialidad ?>
	<?php if ($doctor_especialidad->SortUrl($doctor_especialidad->idespecialidad) == "") { ?>
		<th data-name="idespecialidad"><div id="elh_doctor_especialidad_idespecialidad" class="doctor_especialidad_idespecialidad"><div class="ewTableHeaderCaption"><?php echo $doctor_especialidad->idespecialidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idespecialidad"><div><div id="elh_doctor_especialidad_idespecialidad" class="doctor_especialidad_idespecialidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor_especialidad->idespecialidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor_especialidad->idespecialidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor_especialidad->idespecialidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$doctor_especialidad_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$doctor_especialidad_grid->StartRec = 1;
$doctor_especialidad_grid->StopRec = $doctor_especialidad_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($doctor_especialidad_grid->FormKeyCountName) && ($doctor_especialidad->CurrentAction == "gridadd" || $doctor_especialidad->CurrentAction == "gridedit" || $doctor_especialidad->CurrentAction == "F")) {
		$doctor_especialidad_grid->KeyCount = $objForm->GetValue($doctor_especialidad_grid->FormKeyCountName);
		$doctor_especialidad_grid->StopRec = $doctor_especialidad_grid->StartRec + $doctor_especialidad_grid->KeyCount - 1;
	}
}
$doctor_especialidad_grid->RecCnt = $doctor_especialidad_grid->StartRec - 1;
if ($doctor_especialidad_grid->Recordset && !$doctor_especialidad_grid->Recordset->EOF) {
	$doctor_especialidad_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $doctor_especialidad_grid->StartRec > 1)
		$doctor_especialidad_grid->Recordset->Move($doctor_especialidad_grid->StartRec - 1);
} elseif (!$doctor_especialidad->AllowAddDeleteRow && $doctor_especialidad_grid->StopRec == 0) {
	$doctor_especialidad_grid->StopRec = $doctor_especialidad->GridAddRowCount;
}

// Initialize aggregate
$doctor_especialidad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$doctor_especialidad->ResetAttrs();
$doctor_especialidad_grid->RenderRow();
if ($doctor_especialidad->CurrentAction == "gridadd")
	$doctor_especialidad_grid->RowIndex = 0;
if ($doctor_especialidad->CurrentAction == "gridedit")
	$doctor_especialidad_grid->RowIndex = 0;
while ($doctor_especialidad_grid->RecCnt < $doctor_especialidad_grid->StopRec) {
	$doctor_especialidad_grid->RecCnt++;
	if (intval($doctor_especialidad_grid->RecCnt) >= intval($doctor_especialidad_grid->StartRec)) {
		$doctor_especialidad_grid->RowCnt++;
		if ($doctor_especialidad->CurrentAction == "gridadd" || $doctor_especialidad->CurrentAction == "gridedit" || $doctor_especialidad->CurrentAction == "F") {
			$doctor_especialidad_grid->RowIndex++;
			$objForm->Index = $doctor_especialidad_grid->RowIndex;
			if ($objForm->HasValue($doctor_especialidad_grid->FormActionName))
				$doctor_especialidad_grid->RowAction = strval($objForm->GetValue($doctor_especialidad_grid->FormActionName));
			elseif ($doctor_especialidad->CurrentAction == "gridadd")
				$doctor_especialidad_grid->RowAction = "insert";
			else
				$doctor_especialidad_grid->RowAction = "";
		}

		// Set up key count
		$doctor_especialidad_grid->KeyCount = $doctor_especialidad_grid->RowIndex;

		// Init row class and style
		$doctor_especialidad->ResetAttrs();
		$doctor_especialidad->CssClass = "";
		if ($doctor_especialidad->CurrentAction == "gridadd") {
			if ($doctor_especialidad->CurrentMode == "copy") {
				$doctor_especialidad_grid->LoadRowValues($doctor_especialidad_grid->Recordset); // Load row values
				$doctor_especialidad_grid->SetRecordKey($doctor_especialidad_grid->RowOldKey, $doctor_especialidad_grid->Recordset); // Set old record key
			} else {
				$doctor_especialidad_grid->LoadDefaultValues(); // Load default values
				$doctor_especialidad_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$doctor_especialidad_grid->LoadRowValues($doctor_especialidad_grid->Recordset); // Load row values
		}
		$doctor_especialidad->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($doctor_especialidad->CurrentAction == "gridadd") // Grid add
			$doctor_especialidad->RowType = EW_ROWTYPE_ADD; // Render add
		if ($doctor_especialidad->CurrentAction == "gridadd" && $doctor_especialidad->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$doctor_especialidad_grid->RestoreCurrentRowFormValues($doctor_especialidad_grid->RowIndex); // Restore form values
		if ($doctor_especialidad->CurrentAction == "gridedit") { // Grid edit
			if ($doctor_especialidad->EventCancelled) {
				$doctor_especialidad_grid->RestoreCurrentRowFormValues($doctor_especialidad_grid->RowIndex); // Restore form values
			}
			if ($doctor_especialidad_grid->RowAction == "insert")
				$doctor_especialidad->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$doctor_especialidad->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($doctor_especialidad->CurrentAction == "gridedit" && ($doctor_especialidad->RowType == EW_ROWTYPE_EDIT || $doctor_especialidad->RowType == EW_ROWTYPE_ADD) && $doctor_especialidad->EventCancelled) // Update failed
			$doctor_especialidad_grid->RestoreCurrentRowFormValues($doctor_especialidad_grid->RowIndex); // Restore form values
		if ($doctor_especialidad->RowType == EW_ROWTYPE_EDIT) // Edit row
			$doctor_especialidad_grid->EditRowCnt++;
		if ($doctor_especialidad->CurrentAction == "F") // Confirm row
			$doctor_especialidad_grid->RestoreCurrentRowFormValues($doctor_especialidad_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$doctor_especialidad->RowAttrs = array_merge($doctor_especialidad->RowAttrs, array('data-rowindex'=>$doctor_especialidad_grid->RowCnt, 'id'=>'r' . $doctor_especialidad_grid->RowCnt . '_doctor_especialidad', 'data-rowtype'=>$doctor_especialidad->RowType));

		// Render row
		$doctor_especialidad_grid->RenderRow();

		// Render list options
		$doctor_especialidad_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($doctor_especialidad_grid->RowAction <> "delete" && $doctor_especialidad_grid->RowAction <> "insertdelete" && !($doctor_especialidad_grid->RowAction == "insert" && $doctor_especialidad->CurrentAction == "F" && $doctor_especialidad_grid->EmptyRow())) {
?>
	<tr<?php echo $doctor_especialidad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$doctor_especialidad_grid->ListOptions->Render("body", "left", $doctor_especialidad_grid->RowCnt);
?>
	<?php if ($doctor_especialidad->iddoctor_especialidad->Visible) { // iddoctor_especialidad ?>
		<td data-name="iddoctor_especialidad"<?php echo $doctor_especialidad->iddoctor_especialidad->CellAttributes() ?>>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_iddoctor_especialidad" name="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" id="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor_especialidad->OldValue) ?>">
<?php } ?>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_especialidad_grid->RowCnt ?>_doctor_especialidad_iddoctor_especialidad" class="form-group doctor_especialidad_iddoctor_especialidad">
<span<?php echo $doctor_especialidad->iddoctor_especialidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_especialidad->iddoctor_especialidad->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_iddoctor_especialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor_especialidad->CurrentValue) ?>">
<?php } ?>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor_especialidad->iddoctor_especialidad->ViewAttributes() ?>>
<?php echo $doctor_especialidad->iddoctor_especialidad->ListViewValue() ?></span>
<input type="hidden" data-field="x_iddoctor_especialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor_especialidad->FormValue) ?>">
<input type="hidden" data-field="x_iddoctor_especialidad" name="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" id="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor_especialidad->OldValue) ?>">
<?php } ?>
<a id="<?php echo $doctor_especialidad_grid->PageObjName . "_row_" . $doctor_especialidad_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($doctor_especialidad->iddoctor->Visible) { // iddoctor ?>
		<td data-name="iddoctor"<?php echo $doctor_especialidad->iddoctor->CellAttributes() ?>>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $doctor_especialidad_grid->RowCnt ?>_doctor_especialidad_iddoctor" class="form-group doctor_especialidad_iddoctor">
<select data-field="x_iddoctor" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor"<?php echo $doctor_especialidad->iddoctor->EditAttributes() ?>>
<?php
if (is_array($doctor_especialidad->iddoctor->EditValue)) {
	$arwrk = $doctor_especialidad->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_especialidad->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor_especialidad->iddoctor->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $doctor_especialidad->Lookup_Selecting($doctor_especialidad->iddoctor, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" id="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" id="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor->OldValue) ?>">
<?php } ?>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_especialidad_grid->RowCnt ?>_doctor_especialidad_iddoctor" class="form-group doctor_especialidad_iddoctor">
<select data-field="x_iddoctor" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor"<?php echo $doctor_especialidad->iddoctor->EditAttributes() ?>>
<?php
if (is_array($doctor_especialidad->iddoctor->EditValue)) {
	$arwrk = $doctor_especialidad->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_especialidad->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor_especialidad->iddoctor->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $doctor_especialidad->Lookup_Selecting($doctor_especialidad->iddoctor, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" id="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor_especialidad->iddoctor->ViewAttributes() ?>>
<?php echo $doctor_especialidad->iddoctor->ListViewValue() ?></span>
<input type="hidden" data-field="x_iddoctor" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor->FormValue) ?>">
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" id="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($doctor_especialidad->idespecialidad->Visible) { // idespecialidad ?>
		<td data-name="idespecialidad"<?php echo $doctor_especialidad->idespecialidad->CellAttributes() ?>>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($doctor_especialidad->idespecialidad->getSessionValue() <> "") { ?>
<span id="el<?php echo $doctor_especialidad_grid->RowCnt ?>_doctor_especialidad_idespecialidad" class="form-group doctor_especialidad_idespecialidad">
<span<?php echo $doctor_especialidad->idespecialidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_especialidad->idespecialidad->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->idespecialidad->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $doctor_especialidad_grid->RowCnt ?>_doctor_especialidad_idespecialidad" class="form-group doctor_especialidad_idespecialidad">
<select data-field="x_idespecialidad" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad"<?php echo $doctor_especialidad->idespecialidad->EditAttributes() ?>>
<?php
if (is_array($doctor_especialidad->idespecialidad->EditValue)) {
	$arwrk = $doctor_especialidad->idespecialidad->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_especialidad->idespecialidad->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor_especialidad->idespecialidad->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idespecialidad`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `especialidad`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $doctor_especialidad->Lookup_Selecting($doctor_especialidad->idespecialidad, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" id="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idespecialidad` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idespecialidad" name="o<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" id="o<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->idespecialidad->OldValue) ?>">
<?php } ?>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($doctor_especialidad->idespecialidad->getSessionValue() <> "") { ?>
<span id="el<?php echo $doctor_especialidad_grid->RowCnt ?>_doctor_especialidad_idespecialidad" class="form-group doctor_especialidad_idespecialidad">
<span<?php echo $doctor_especialidad->idespecialidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_especialidad->idespecialidad->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->idespecialidad->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $doctor_especialidad_grid->RowCnt ?>_doctor_especialidad_idespecialidad" class="form-group doctor_especialidad_idespecialidad">
<select data-field="x_idespecialidad" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad"<?php echo $doctor_especialidad->idespecialidad->EditAttributes() ?>>
<?php
if (is_array($doctor_especialidad->idespecialidad->EditValue)) {
	$arwrk = $doctor_especialidad->idespecialidad->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_especialidad->idespecialidad->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor_especialidad->idespecialidad->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idespecialidad`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `especialidad`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $doctor_especialidad->Lookup_Selecting($doctor_especialidad->idespecialidad, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" id="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idespecialidad` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor_especialidad->idespecialidad->ViewAttributes() ?>>
<?php echo $doctor_especialidad->idespecialidad->ListViewValue() ?></span>
<input type="hidden" data-field="x_idespecialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->idespecialidad->FormValue) ?>">
<input type="hidden" data-field="x_idespecialidad" name="o<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" id="o<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->idespecialidad->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$doctor_especialidad_grid->ListOptions->Render("body", "right", $doctor_especialidad_grid->RowCnt);
?>
	</tr>
<?php if ($doctor_especialidad->RowType == EW_ROWTYPE_ADD || $doctor_especialidad->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdoctor_especialidadgrid.UpdateOpts(<?php echo $doctor_especialidad_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($doctor_especialidad->CurrentAction <> "gridadd" || $doctor_especialidad->CurrentMode == "copy")
		if (!$doctor_especialidad_grid->Recordset->EOF) $doctor_especialidad_grid->Recordset->MoveNext();
}
?>
<?php
	if ($doctor_especialidad->CurrentMode == "add" || $doctor_especialidad->CurrentMode == "copy" || $doctor_especialidad->CurrentMode == "edit") {
		$doctor_especialidad_grid->RowIndex = '$rowindex$';
		$doctor_especialidad_grid->LoadDefaultValues();

		// Set row properties
		$doctor_especialidad->ResetAttrs();
		$doctor_especialidad->RowAttrs = array_merge($doctor_especialidad->RowAttrs, array('data-rowindex'=>$doctor_especialidad_grid->RowIndex, 'id'=>'r0_doctor_especialidad', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($doctor_especialidad->RowAttrs["class"], "ewTemplate");
		$doctor_especialidad->RowType = EW_ROWTYPE_ADD;

		// Render row
		$doctor_especialidad_grid->RenderRow();

		// Render list options
		$doctor_especialidad_grid->RenderListOptions();
		$doctor_especialidad_grid->StartRowCnt = 0;
?>
	<tr<?php echo $doctor_especialidad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$doctor_especialidad_grid->ListOptions->Render("body", "left", $doctor_especialidad_grid->RowIndex);
?>
	<?php if ($doctor_especialidad->iddoctor_especialidad->Visible) { // iddoctor_especialidad ?>
		<td>
<?php if ($doctor_especialidad->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_doctor_especialidad_iddoctor_especialidad" class="form-group doctor_especialidad_iddoctor_especialidad">
<span<?php echo $doctor_especialidad->iddoctor_especialidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_especialidad->iddoctor_especialidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iddoctor_especialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor_especialidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iddoctor_especialidad" name="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" id="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor_especialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor_especialidad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor_especialidad->iddoctor->Visible) { // iddoctor ?>
		<td>
<?php if ($doctor_especialidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_doctor_especialidad_iddoctor" class="form-group doctor_especialidad_iddoctor">
<select data-field="x_iddoctor" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor"<?php echo $doctor_especialidad->iddoctor->EditAttributes() ?>>
<?php
if (is_array($doctor_especialidad->iddoctor->EditValue)) {
	$arwrk = $doctor_especialidad->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_especialidad->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor_especialidad->iddoctor->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $doctor_especialidad->Lookup_Selecting($doctor_especialidad->iddoctor, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" id="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_doctor_especialidad_iddoctor" class="form-group doctor_especialidad_iddoctor">
<span<?php echo $doctor_especialidad->iddoctor->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_especialidad->iddoctor->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iddoctor" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" id="o<?php echo $doctor_especialidad_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor_especialidad->idespecialidad->Visible) { // idespecialidad ?>
		<td>
<?php if ($doctor_especialidad->CurrentAction <> "F") { ?>
<?php if ($doctor_especialidad->idespecialidad->getSessionValue() <> "") { ?>
<span id="el$rowindex$_doctor_especialidad_idespecialidad" class="form-group doctor_especialidad_idespecialidad">
<span<?php echo $doctor_especialidad->idespecialidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_especialidad->idespecialidad->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->idespecialidad->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_doctor_especialidad_idespecialidad" class="form-group doctor_especialidad_idespecialidad">
<select data-field="x_idespecialidad" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad"<?php echo $doctor_especialidad->idespecialidad->EditAttributes() ?>>
<?php
if (is_array($doctor_especialidad->idespecialidad->EditValue)) {
	$arwrk = $doctor_especialidad->idespecialidad->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_especialidad->idespecialidad->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor_especialidad->idespecialidad->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idespecialidad`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `especialidad`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $doctor_especialidad->Lookup_Selecting($doctor_especialidad->idespecialidad, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" id="s_x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idespecialidad` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_doctor_especialidad_idespecialidad" class="form-group doctor_especialidad_idespecialidad">
<span<?php echo $doctor_especialidad->idespecialidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_especialidad->idespecialidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idespecialidad" name="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" id="x<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->idespecialidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idespecialidad" name="o<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" id="o<?php echo $doctor_especialidad_grid->RowIndex ?>_idespecialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->idespecialidad->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$doctor_especialidad_grid->ListOptions->Render("body", "right", $doctor_especialidad_grid->RowCnt);
?>
<script type="text/javascript">
fdoctor_especialidadgrid.UpdateOpts(<?php echo $doctor_especialidad_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($doctor_especialidad->CurrentMode == "add" || $doctor_especialidad->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $doctor_especialidad_grid->FormKeyCountName ?>" id="<?php echo $doctor_especialidad_grid->FormKeyCountName ?>" value="<?php echo $doctor_especialidad_grid->KeyCount ?>">
<?php echo $doctor_especialidad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($doctor_especialidad->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $doctor_especialidad_grid->FormKeyCountName ?>" id="<?php echo $doctor_especialidad_grid->FormKeyCountName ?>" value="<?php echo $doctor_especialidad_grid->KeyCount ?>">
<?php echo $doctor_especialidad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($doctor_especialidad->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdoctor_especialidadgrid">
</div>
<?php

// Close recordset
if ($doctor_especialidad_grid->Recordset)
	$doctor_especialidad_grid->Recordset->Close();
?>
<?php if ($doctor_especialidad_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($doctor_especialidad_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($doctor_especialidad_grid->TotalRecs == 0 && $doctor_especialidad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($doctor_especialidad_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($doctor_especialidad->Export == "") { ?>
<script type="text/javascript">
fdoctor_especialidadgrid.Init();
</script>
<?php } ?>
<?php
$doctor_especialidad_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$doctor_especialidad_grid->Page_Terminate();
?>

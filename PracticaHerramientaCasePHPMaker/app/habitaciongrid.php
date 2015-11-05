<?php

// Create page object
if (!isset($habitacion_grid)) $habitacion_grid = new chabitacion_grid();

// Page init
$habitacion_grid->Page_Init();

// Page main
$habitacion_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$habitacion_grid->Page_Render();
?>
<?php if ($habitacion->Export == "") { ?>
<script type="text/javascript">

// Page object
var habitacion_grid = new ew_Page("habitacion_grid");
habitacion_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = habitacion_grid.PageID; // For backward compatibility

// Form object
var fhabitaciongrid = new ew_Form("fhabitaciongrid");
fhabitaciongrid.FormKeyCountName = '<?php echo $habitacion_grid->FormKeyCountName ?>';

// Validate form
fhabitaciongrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idsala");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $habitacion->idsala->FldCaption(), $habitacion->idsala->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $habitacion->estado->FldCaption(), $habitacion->estado->ReqErrMsg)) ?>");

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
fhabitaciongrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "numero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idsala", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	return true;
}

// Form_CustomValidate event
fhabitaciongrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhabitaciongrid.ValidateRequired = true;
<?php } else { ?>
fhabitaciongrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhabitaciongrid.Lists["x_idsala"] = {"LinkField":"x_idsala","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($habitacion->CurrentAction == "gridadd") {
	if ($habitacion->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$habitacion_grid->TotalRecs = $habitacion->SelectRecordCount();
			$habitacion_grid->Recordset = $habitacion_grid->LoadRecordset($habitacion_grid->StartRec-1, $habitacion_grid->DisplayRecs);
		} else {
			if ($habitacion_grid->Recordset = $habitacion_grid->LoadRecordset())
				$habitacion_grid->TotalRecs = $habitacion_grid->Recordset->RecordCount();
		}
		$habitacion_grid->StartRec = 1;
		$habitacion_grid->DisplayRecs = $habitacion_grid->TotalRecs;
	} else {
		$habitacion->CurrentFilter = "0=1";
		$habitacion_grid->StartRec = 1;
		$habitacion_grid->DisplayRecs = $habitacion->GridAddRowCount;
	}
	$habitacion_grid->TotalRecs = $habitacion_grid->DisplayRecs;
	$habitacion_grid->StopRec = $habitacion_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$habitacion_grid->TotalRecs = $habitacion->SelectRecordCount();
	} else {
		if ($habitacion_grid->Recordset = $habitacion_grid->LoadRecordset())
			$habitacion_grid->TotalRecs = $habitacion_grid->Recordset->RecordCount();
	}
	$habitacion_grid->StartRec = 1;
	$habitacion_grid->DisplayRecs = $habitacion_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$habitacion_grid->Recordset = $habitacion_grid->LoadRecordset($habitacion_grid->StartRec-1, $habitacion_grid->DisplayRecs);

	// Set no record found message
	if ($habitacion->CurrentAction == "" && $habitacion_grid->TotalRecs == 0) {
		if ($habitacion_grid->SearchWhere == "0=101")
			$habitacion_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$habitacion_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$habitacion_grid->RenderOtherOptions();
?>
<?php $habitacion_grid->ShowPageHeader(); ?>
<?php
$habitacion_grid->ShowMessage();
?>
<?php if ($habitacion_grid->TotalRecs > 0 || $habitacion->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fhabitaciongrid" class="ewForm form-inline">
<div id="gmp_habitacion" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_habitaciongrid" class="table ewTable">
<?php echo $habitacion->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$habitacion_grid->RenderListOptions();

// Render list options (header, left)
$habitacion_grid->ListOptions->Render("header", "left");
?>
<?php if ($habitacion->idhabitacion->Visible) { // idhabitacion ?>
	<?php if ($habitacion->SortUrl($habitacion->idhabitacion) == "") { ?>
		<th data-name="idhabitacion"><div id="elh_habitacion_idhabitacion" class="habitacion_idhabitacion"><div class="ewTableHeaderCaption"><?php echo $habitacion->idhabitacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idhabitacion"><div><div id="elh_habitacion_idhabitacion" class="habitacion_idhabitacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $habitacion->idhabitacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($habitacion->idhabitacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($habitacion->idhabitacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($habitacion->numero->Visible) { // numero ?>
	<?php if ($habitacion->SortUrl($habitacion->numero) == "") { ?>
		<th data-name="numero"><div id="elh_habitacion_numero" class="habitacion_numero"><div class="ewTableHeaderCaption"><?php echo $habitacion->numero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numero"><div><div id="elh_habitacion_numero" class="habitacion_numero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $habitacion->numero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($habitacion->numero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($habitacion->numero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($habitacion->idsala->Visible) { // idsala ?>
	<?php if ($habitacion->SortUrl($habitacion->idsala) == "") { ?>
		<th data-name="idsala"><div id="elh_habitacion_idsala" class="habitacion_idsala"><div class="ewTableHeaderCaption"><?php echo $habitacion->idsala->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idsala"><div><div id="elh_habitacion_idsala" class="habitacion_idsala">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $habitacion->idsala->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($habitacion->idsala->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($habitacion->idsala->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($habitacion->estado->Visible) { // estado ?>
	<?php if ($habitacion->SortUrl($habitacion->estado) == "") { ?>
		<th data-name="estado"><div id="elh_habitacion_estado" class="habitacion_estado"><div class="ewTableHeaderCaption"><?php echo $habitacion->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_habitacion_estado" class="habitacion_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $habitacion->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($habitacion->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($habitacion->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$habitacion_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$habitacion_grid->StartRec = 1;
$habitacion_grid->StopRec = $habitacion_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($habitacion_grid->FormKeyCountName) && ($habitacion->CurrentAction == "gridadd" || $habitacion->CurrentAction == "gridedit" || $habitacion->CurrentAction == "F")) {
		$habitacion_grid->KeyCount = $objForm->GetValue($habitacion_grid->FormKeyCountName);
		$habitacion_grid->StopRec = $habitacion_grid->StartRec + $habitacion_grid->KeyCount - 1;
	}
}
$habitacion_grid->RecCnt = $habitacion_grid->StartRec - 1;
if ($habitacion_grid->Recordset && !$habitacion_grid->Recordset->EOF) {
	$habitacion_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $habitacion_grid->StartRec > 1)
		$habitacion_grid->Recordset->Move($habitacion_grid->StartRec - 1);
} elseif (!$habitacion->AllowAddDeleteRow && $habitacion_grid->StopRec == 0) {
	$habitacion_grid->StopRec = $habitacion->GridAddRowCount;
}

// Initialize aggregate
$habitacion->RowType = EW_ROWTYPE_AGGREGATEINIT;
$habitacion->ResetAttrs();
$habitacion_grid->RenderRow();
if ($habitacion->CurrentAction == "gridadd")
	$habitacion_grid->RowIndex = 0;
if ($habitacion->CurrentAction == "gridedit")
	$habitacion_grid->RowIndex = 0;
while ($habitacion_grid->RecCnt < $habitacion_grid->StopRec) {
	$habitacion_grid->RecCnt++;
	if (intval($habitacion_grid->RecCnt) >= intval($habitacion_grid->StartRec)) {
		$habitacion_grid->RowCnt++;
		if ($habitacion->CurrentAction == "gridadd" || $habitacion->CurrentAction == "gridedit" || $habitacion->CurrentAction == "F") {
			$habitacion_grid->RowIndex++;
			$objForm->Index = $habitacion_grid->RowIndex;
			if ($objForm->HasValue($habitacion_grid->FormActionName))
				$habitacion_grid->RowAction = strval($objForm->GetValue($habitacion_grid->FormActionName));
			elseif ($habitacion->CurrentAction == "gridadd")
				$habitacion_grid->RowAction = "insert";
			else
				$habitacion_grid->RowAction = "";
		}

		// Set up key count
		$habitacion_grid->KeyCount = $habitacion_grid->RowIndex;

		// Init row class and style
		$habitacion->ResetAttrs();
		$habitacion->CssClass = "";
		if ($habitacion->CurrentAction == "gridadd") {
			if ($habitacion->CurrentMode == "copy") {
				$habitacion_grid->LoadRowValues($habitacion_grid->Recordset); // Load row values
				$habitacion_grid->SetRecordKey($habitacion_grid->RowOldKey, $habitacion_grid->Recordset); // Set old record key
			} else {
				$habitacion_grid->LoadDefaultValues(); // Load default values
				$habitacion_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$habitacion_grid->LoadRowValues($habitacion_grid->Recordset); // Load row values
		}
		$habitacion->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($habitacion->CurrentAction == "gridadd") // Grid add
			$habitacion->RowType = EW_ROWTYPE_ADD; // Render add
		if ($habitacion->CurrentAction == "gridadd" && $habitacion->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$habitacion_grid->RestoreCurrentRowFormValues($habitacion_grid->RowIndex); // Restore form values
		if ($habitacion->CurrentAction == "gridedit") { // Grid edit
			if ($habitacion->EventCancelled) {
				$habitacion_grid->RestoreCurrentRowFormValues($habitacion_grid->RowIndex); // Restore form values
			}
			if ($habitacion_grid->RowAction == "insert")
				$habitacion->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$habitacion->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($habitacion->CurrentAction == "gridedit" && ($habitacion->RowType == EW_ROWTYPE_EDIT || $habitacion->RowType == EW_ROWTYPE_ADD) && $habitacion->EventCancelled) // Update failed
			$habitacion_grid->RestoreCurrentRowFormValues($habitacion_grid->RowIndex); // Restore form values
		if ($habitacion->RowType == EW_ROWTYPE_EDIT) // Edit row
			$habitacion_grid->EditRowCnt++;
		if ($habitacion->CurrentAction == "F") // Confirm row
			$habitacion_grid->RestoreCurrentRowFormValues($habitacion_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$habitacion->RowAttrs = array_merge($habitacion->RowAttrs, array('data-rowindex'=>$habitacion_grid->RowCnt, 'id'=>'r' . $habitacion_grid->RowCnt . '_habitacion', 'data-rowtype'=>$habitacion->RowType));

		// Render row
		$habitacion_grid->RenderRow();

		// Render list options
		$habitacion_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($habitacion_grid->RowAction <> "delete" && $habitacion_grid->RowAction <> "insertdelete" && !($habitacion_grid->RowAction == "insert" && $habitacion->CurrentAction == "F" && $habitacion_grid->EmptyRow())) {
?>
	<tr<?php echo $habitacion->RowAttributes() ?>>
<?php

// Render list options (body, left)
$habitacion_grid->ListOptions->Render("body", "left", $habitacion_grid->RowCnt);
?>
	<?php if ($habitacion->idhabitacion->Visible) { // idhabitacion ?>
		<td data-name="idhabitacion"<?php echo $habitacion->idhabitacion->CellAttributes() ?>>
<?php if ($habitacion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idhabitacion" name="o<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" id="o<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($habitacion->idhabitacion->OldValue) ?>">
<?php } ?>
<?php if ($habitacion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $habitacion_grid->RowCnt ?>_habitacion_idhabitacion" class="form-group habitacion_idhabitacion">
<span<?php echo $habitacion->idhabitacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $habitacion->idhabitacion->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_idhabitacion" name="x<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" id="x<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($habitacion->idhabitacion->CurrentValue) ?>">
<?php } ?>
<?php if ($habitacion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $habitacion->idhabitacion->ViewAttributes() ?>>
<?php echo $habitacion->idhabitacion->ListViewValue() ?></span>
<input type="hidden" data-field="x_idhabitacion" name="x<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" id="x<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($habitacion->idhabitacion->FormValue) ?>">
<input type="hidden" data-field="x_idhabitacion" name="o<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" id="o<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($habitacion->idhabitacion->OldValue) ?>">
<?php } ?>
<a id="<?php echo $habitacion_grid->PageObjName . "_row_" . $habitacion_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($habitacion->numero->Visible) { // numero ?>
		<td data-name="numero"<?php echo $habitacion->numero->CellAttributes() ?>>
<?php if ($habitacion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $habitacion_grid->RowCnt ?>_habitacion_numero" class="form-group habitacion_numero">
<input type="text" data-field="x_numero" name="x<?php echo $habitacion_grid->RowIndex ?>_numero" id="x<?php echo $habitacion_grid->RowIndex ?>_numero" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($habitacion->numero->PlaceHolder) ?>" value="<?php echo $habitacion->numero->EditValue ?>"<?php echo $habitacion->numero->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_numero" name="o<?php echo $habitacion_grid->RowIndex ?>_numero" id="o<?php echo $habitacion_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($habitacion->numero->OldValue) ?>">
<?php } ?>
<?php if ($habitacion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $habitacion_grid->RowCnt ?>_habitacion_numero" class="form-group habitacion_numero">
<input type="text" data-field="x_numero" name="x<?php echo $habitacion_grid->RowIndex ?>_numero" id="x<?php echo $habitacion_grid->RowIndex ?>_numero" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($habitacion->numero->PlaceHolder) ?>" value="<?php echo $habitacion->numero->EditValue ?>"<?php echo $habitacion->numero->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($habitacion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $habitacion->numero->ViewAttributes() ?>>
<?php echo $habitacion->numero->ListViewValue() ?></span>
<input type="hidden" data-field="x_numero" name="x<?php echo $habitacion_grid->RowIndex ?>_numero" id="x<?php echo $habitacion_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($habitacion->numero->FormValue) ?>">
<input type="hidden" data-field="x_numero" name="o<?php echo $habitacion_grid->RowIndex ?>_numero" id="o<?php echo $habitacion_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($habitacion->numero->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($habitacion->idsala->Visible) { // idsala ?>
		<td data-name="idsala"<?php echo $habitacion->idsala->CellAttributes() ?>>
<?php if ($habitacion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $habitacion_grid->RowCnt ?>_habitacion_idsala" class="form-group habitacion_idsala">
<select data-field="x_idsala" id="x<?php echo $habitacion_grid->RowIndex ?>_idsala" name="x<?php echo $habitacion_grid->RowIndex ?>_idsala"<?php echo $habitacion->idsala->EditAttributes() ?>>
<?php
if (is_array($habitacion->idsala->EditValue)) {
	$arwrk = $habitacion->idsala->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($habitacion->idsala->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $habitacion->idsala->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idsala`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sala`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $habitacion->Lookup_Selecting($habitacion->idsala, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $habitacion_grid->RowIndex ?>_idsala" id="s_x<?php echo $habitacion_grid->RowIndex ?>_idsala" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idsala` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_idsala" name="o<?php echo $habitacion_grid->RowIndex ?>_idsala" id="o<?php echo $habitacion_grid->RowIndex ?>_idsala" value="<?php echo ew_HtmlEncode($habitacion->idsala->OldValue) ?>">
<?php } ?>
<?php if ($habitacion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $habitacion_grid->RowCnt ?>_habitacion_idsala" class="form-group habitacion_idsala">
<select data-field="x_idsala" id="x<?php echo $habitacion_grid->RowIndex ?>_idsala" name="x<?php echo $habitacion_grid->RowIndex ?>_idsala"<?php echo $habitacion->idsala->EditAttributes() ?>>
<?php
if (is_array($habitacion->idsala->EditValue)) {
	$arwrk = $habitacion->idsala->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($habitacion->idsala->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $habitacion->idsala->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idsala`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sala`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $habitacion->Lookup_Selecting($habitacion->idsala, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $habitacion_grid->RowIndex ?>_idsala" id="s_x<?php echo $habitacion_grid->RowIndex ?>_idsala" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idsala` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($habitacion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $habitacion->idsala->ViewAttributes() ?>>
<?php echo $habitacion->idsala->ListViewValue() ?></span>
<input type="hidden" data-field="x_idsala" name="x<?php echo $habitacion_grid->RowIndex ?>_idsala" id="x<?php echo $habitacion_grid->RowIndex ?>_idsala" value="<?php echo ew_HtmlEncode($habitacion->idsala->FormValue) ?>">
<input type="hidden" data-field="x_idsala" name="o<?php echo $habitacion_grid->RowIndex ?>_idsala" id="o<?php echo $habitacion_grid->RowIndex ?>_idsala" value="<?php echo ew_HtmlEncode($habitacion->idsala->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($habitacion->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $habitacion->estado->CellAttributes() ?>>
<?php if ($habitacion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $habitacion_grid->RowCnt ?>_habitacion_estado" class="form-group habitacion_estado">
<div id="tp_x<?php echo $habitacion_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $habitacion_grid->RowIndex ?>_estado" id="x<?php echo $habitacion_grid->RowIndex ?>_estado" value="{value}"<?php echo $habitacion->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $habitacion_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $habitacion->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($habitacion->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $habitacion_grid->RowIndex ?>_estado" id="x<?php echo $habitacion_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $habitacion->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $habitacion->estado->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $habitacion_grid->RowIndex ?>_estado" id="o<?php echo $habitacion_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($habitacion->estado->OldValue) ?>">
<?php } ?>
<?php if ($habitacion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $habitacion_grid->RowCnt ?>_habitacion_estado" class="form-group habitacion_estado">
<div id="tp_x<?php echo $habitacion_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $habitacion_grid->RowIndex ?>_estado" id="x<?php echo $habitacion_grid->RowIndex ?>_estado" value="{value}"<?php echo $habitacion->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $habitacion_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $habitacion->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($habitacion->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $habitacion_grid->RowIndex ?>_estado" id="x<?php echo $habitacion_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $habitacion->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $habitacion->estado->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($habitacion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $habitacion->estado->ViewAttributes() ?>>
<?php echo $habitacion->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $habitacion_grid->RowIndex ?>_estado" id="x<?php echo $habitacion_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($habitacion->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $habitacion_grid->RowIndex ?>_estado" id="o<?php echo $habitacion_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($habitacion->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$habitacion_grid->ListOptions->Render("body", "right", $habitacion_grid->RowCnt);
?>
	</tr>
<?php if ($habitacion->RowType == EW_ROWTYPE_ADD || $habitacion->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fhabitaciongrid.UpdateOpts(<?php echo $habitacion_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($habitacion->CurrentAction <> "gridadd" || $habitacion->CurrentMode == "copy")
		if (!$habitacion_grid->Recordset->EOF) $habitacion_grid->Recordset->MoveNext();
}
?>
<?php
	if ($habitacion->CurrentMode == "add" || $habitacion->CurrentMode == "copy" || $habitacion->CurrentMode == "edit") {
		$habitacion_grid->RowIndex = '$rowindex$';
		$habitacion_grid->LoadDefaultValues();

		// Set row properties
		$habitacion->ResetAttrs();
		$habitacion->RowAttrs = array_merge($habitacion->RowAttrs, array('data-rowindex'=>$habitacion_grid->RowIndex, 'id'=>'r0_habitacion', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($habitacion->RowAttrs["class"], "ewTemplate");
		$habitacion->RowType = EW_ROWTYPE_ADD;

		// Render row
		$habitacion_grid->RenderRow();

		// Render list options
		$habitacion_grid->RenderListOptions();
		$habitacion_grid->StartRowCnt = 0;
?>
	<tr<?php echo $habitacion->RowAttributes() ?>>
<?php

// Render list options (body, left)
$habitacion_grid->ListOptions->Render("body", "left", $habitacion_grid->RowIndex);
?>
	<?php if ($habitacion->idhabitacion->Visible) { // idhabitacion ?>
		<td>
<?php if ($habitacion->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_habitacion_idhabitacion" class="form-group habitacion_idhabitacion">
<span<?php echo $habitacion->idhabitacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $habitacion->idhabitacion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idhabitacion" name="x<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" id="x<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($habitacion->idhabitacion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idhabitacion" name="o<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" id="o<?php echo $habitacion_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($habitacion->idhabitacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($habitacion->numero->Visible) { // numero ?>
		<td>
<?php if ($habitacion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_habitacion_numero" class="form-group habitacion_numero">
<input type="text" data-field="x_numero" name="x<?php echo $habitacion_grid->RowIndex ?>_numero" id="x<?php echo $habitacion_grid->RowIndex ?>_numero" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($habitacion->numero->PlaceHolder) ?>" value="<?php echo $habitacion->numero->EditValue ?>"<?php echo $habitacion->numero->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_habitacion_numero" class="form-group habitacion_numero">
<span<?php echo $habitacion->numero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $habitacion->numero->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_numero" name="x<?php echo $habitacion_grid->RowIndex ?>_numero" id="x<?php echo $habitacion_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($habitacion->numero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_numero" name="o<?php echo $habitacion_grid->RowIndex ?>_numero" id="o<?php echo $habitacion_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($habitacion->numero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($habitacion->idsala->Visible) { // idsala ?>
		<td>
<?php if ($habitacion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_habitacion_idsala" class="form-group habitacion_idsala">
<select data-field="x_idsala" id="x<?php echo $habitacion_grid->RowIndex ?>_idsala" name="x<?php echo $habitacion_grid->RowIndex ?>_idsala"<?php echo $habitacion->idsala->EditAttributes() ?>>
<?php
if (is_array($habitacion->idsala->EditValue)) {
	$arwrk = $habitacion->idsala->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($habitacion->idsala->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $habitacion->idsala->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idsala`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sala`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $habitacion->Lookup_Selecting($habitacion->idsala, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $habitacion_grid->RowIndex ?>_idsala" id="s_x<?php echo $habitacion_grid->RowIndex ?>_idsala" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idsala` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_habitacion_idsala" class="form-group habitacion_idsala">
<span<?php echo $habitacion->idsala->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $habitacion->idsala->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idsala" name="x<?php echo $habitacion_grid->RowIndex ?>_idsala" id="x<?php echo $habitacion_grid->RowIndex ?>_idsala" value="<?php echo ew_HtmlEncode($habitacion->idsala->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idsala" name="o<?php echo $habitacion_grid->RowIndex ?>_idsala" id="o<?php echo $habitacion_grid->RowIndex ?>_idsala" value="<?php echo ew_HtmlEncode($habitacion->idsala->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($habitacion->estado->Visible) { // estado ?>
		<td>
<?php if ($habitacion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_habitacion_estado" class="form-group habitacion_estado">
<div id="tp_x<?php echo $habitacion_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $habitacion_grid->RowIndex ?>_estado" id="x<?php echo $habitacion_grid->RowIndex ?>_estado" value="{value}"<?php echo $habitacion->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $habitacion_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $habitacion->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($habitacion->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $habitacion_grid->RowIndex ?>_estado" id="x<?php echo $habitacion_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $habitacion->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $habitacion->estado->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_habitacion_estado" class="form-group habitacion_estado">
<span<?php echo $habitacion->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $habitacion->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $habitacion_grid->RowIndex ?>_estado" id="x<?php echo $habitacion_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($habitacion->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $habitacion_grid->RowIndex ?>_estado" id="o<?php echo $habitacion_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($habitacion->estado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$habitacion_grid->ListOptions->Render("body", "right", $habitacion_grid->RowCnt);
?>
<script type="text/javascript">
fhabitaciongrid.UpdateOpts(<?php echo $habitacion_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($habitacion->CurrentMode == "add" || $habitacion->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $habitacion_grid->FormKeyCountName ?>" id="<?php echo $habitacion_grid->FormKeyCountName ?>" value="<?php echo $habitacion_grid->KeyCount ?>">
<?php echo $habitacion_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($habitacion->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $habitacion_grid->FormKeyCountName ?>" id="<?php echo $habitacion_grid->FormKeyCountName ?>" value="<?php echo $habitacion_grid->KeyCount ?>">
<?php echo $habitacion_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($habitacion->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fhabitaciongrid">
</div>
<?php

// Close recordset
if ($habitacion_grid->Recordset)
	$habitacion_grid->Recordset->Close();
?>
<?php if ($habitacion_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($habitacion_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($habitacion_grid->TotalRecs == 0 && $habitacion->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($habitacion_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($habitacion->Export == "") { ?>
<script type="text/javascript">
fhabitaciongrid.Init();
</script>
<?php } ?>
<?php
$habitacion_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$habitacion_grid->Page_Terminate();
?>

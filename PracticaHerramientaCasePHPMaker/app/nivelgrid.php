<?php

// Create page object
if (!isset($nivel_grid)) $nivel_grid = new cnivel_grid();

// Page init
$nivel_grid->Page_Init();

// Page main
$nivel_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nivel_grid->Page_Render();
?>
<?php if ($nivel->Export == "") { ?>
<script type="text/javascript">

// Page object
var nivel_grid = new ew_Page("nivel_grid");
nivel_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = nivel_grid.PageID; // For backward compatibility

// Form object
var fnivelgrid = new ew_Form("fnivelgrid");
fnivelgrid.FormKeyCountName = '<?php echo $nivel_grid->FormKeyCountName ?>';

// Validate form
fnivelgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $nivel->estado->FldCaption(), $nivel->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idhospital");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $nivel->idhospital->FldCaption(), $nivel->idhospital->ReqErrMsg)) ?>");

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
fnivelgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "descripcion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idhospital", false)) return false;
	return true;
}

// Form_CustomValidate event
fnivelgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnivelgrid.ValidateRequired = true;
<?php } else { ?>
fnivelgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnivelgrid.Lists["x_idhospital"] = {"LinkField":"x_idhospital","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($nivel->CurrentAction == "gridadd") {
	if ($nivel->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$nivel_grid->TotalRecs = $nivel->SelectRecordCount();
			$nivel_grid->Recordset = $nivel_grid->LoadRecordset($nivel_grid->StartRec-1, $nivel_grid->DisplayRecs);
		} else {
			if ($nivel_grid->Recordset = $nivel_grid->LoadRecordset())
				$nivel_grid->TotalRecs = $nivel_grid->Recordset->RecordCount();
		}
		$nivel_grid->StartRec = 1;
		$nivel_grid->DisplayRecs = $nivel_grid->TotalRecs;
	} else {
		$nivel->CurrentFilter = "0=1";
		$nivel_grid->StartRec = 1;
		$nivel_grid->DisplayRecs = $nivel->GridAddRowCount;
	}
	$nivel_grid->TotalRecs = $nivel_grid->DisplayRecs;
	$nivel_grid->StopRec = $nivel_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$nivel_grid->TotalRecs = $nivel->SelectRecordCount();
	} else {
		if ($nivel_grid->Recordset = $nivel_grid->LoadRecordset())
			$nivel_grid->TotalRecs = $nivel_grid->Recordset->RecordCount();
	}
	$nivel_grid->StartRec = 1;
	$nivel_grid->DisplayRecs = $nivel_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$nivel_grid->Recordset = $nivel_grid->LoadRecordset($nivel_grid->StartRec-1, $nivel_grid->DisplayRecs);

	// Set no record found message
	if ($nivel->CurrentAction == "" && $nivel_grid->TotalRecs == 0) {
		if ($nivel_grid->SearchWhere == "0=101")
			$nivel_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$nivel_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$nivel_grid->RenderOtherOptions();
?>
<?php $nivel_grid->ShowPageHeader(); ?>
<?php
$nivel_grid->ShowMessage();
?>
<?php if ($nivel_grid->TotalRecs > 0 || $nivel->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fnivelgrid" class="ewForm form-inline">
<div id="gmp_nivel" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_nivelgrid" class="table ewTable">
<?php echo $nivel->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$nivel_grid->RenderListOptions();

// Render list options (header, left)
$nivel_grid->ListOptions->Render("header", "left");
?>
<?php if ($nivel->descripcion->Visible) { // descripcion ?>
	<?php if ($nivel->SortUrl($nivel->descripcion) == "") { ?>
		<th data-name="descripcion"><div id="elh_nivel_descripcion" class="nivel_descripcion"><div class="ewTableHeaderCaption"><?php echo $nivel->descripcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descripcion"><div><div id="elh_nivel_descripcion" class="nivel_descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $nivel->descripcion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($nivel->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($nivel->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($nivel->estado->Visible) { // estado ?>
	<?php if ($nivel->SortUrl($nivel->estado) == "") { ?>
		<th data-name="estado"><div id="elh_nivel_estado" class="nivel_estado"><div class="ewTableHeaderCaption"><?php echo $nivel->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_nivel_estado" class="nivel_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $nivel->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($nivel->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($nivel->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($nivel->idhospital->Visible) { // idhospital ?>
	<?php if ($nivel->SortUrl($nivel->idhospital) == "") { ?>
		<th data-name="idhospital"><div id="elh_nivel_idhospital" class="nivel_idhospital"><div class="ewTableHeaderCaption"><?php echo $nivel->idhospital->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idhospital"><div><div id="elh_nivel_idhospital" class="nivel_idhospital">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $nivel->idhospital->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($nivel->idhospital->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($nivel->idhospital->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$nivel_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$nivel_grid->StartRec = 1;
$nivel_grid->StopRec = $nivel_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($nivel_grid->FormKeyCountName) && ($nivel->CurrentAction == "gridadd" || $nivel->CurrentAction == "gridedit" || $nivel->CurrentAction == "F")) {
		$nivel_grid->KeyCount = $objForm->GetValue($nivel_grid->FormKeyCountName);
		$nivel_grid->StopRec = $nivel_grid->StartRec + $nivel_grid->KeyCount - 1;
	}
}
$nivel_grid->RecCnt = $nivel_grid->StartRec - 1;
if ($nivel_grid->Recordset && !$nivel_grid->Recordset->EOF) {
	$nivel_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $nivel_grid->StartRec > 1)
		$nivel_grid->Recordset->Move($nivel_grid->StartRec - 1);
} elseif (!$nivel->AllowAddDeleteRow && $nivel_grid->StopRec == 0) {
	$nivel_grid->StopRec = $nivel->GridAddRowCount;
}

// Initialize aggregate
$nivel->RowType = EW_ROWTYPE_AGGREGATEINIT;
$nivel->ResetAttrs();
$nivel_grid->RenderRow();
if ($nivel->CurrentAction == "gridadd")
	$nivel_grid->RowIndex = 0;
if ($nivel->CurrentAction == "gridedit")
	$nivel_grid->RowIndex = 0;
while ($nivel_grid->RecCnt < $nivel_grid->StopRec) {
	$nivel_grid->RecCnt++;
	if (intval($nivel_grid->RecCnt) >= intval($nivel_grid->StartRec)) {
		$nivel_grid->RowCnt++;
		if ($nivel->CurrentAction == "gridadd" || $nivel->CurrentAction == "gridedit" || $nivel->CurrentAction == "F") {
			$nivel_grid->RowIndex++;
			$objForm->Index = $nivel_grid->RowIndex;
			if ($objForm->HasValue($nivel_grid->FormActionName))
				$nivel_grid->RowAction = strval($objForm->GetValue($nivel_grid->FormActionName));
			elseif ($nivel->CurrentAction == "gridadd")
				$nivel_grid->RowAction = "insert";
			else
				$nivel_grid->RowAction = "";
		}

		// Set up key count
		$nivel_grid->KeyCount = $nivel_grid->RowIndex;

		// Init row class and style
		$nivel->ResetAttrs();
		$nivel->CssClass = "";
		if ($nivel->CurrentAction == "gridadd") {
			if ($nivel->CurrentMode == "copy") {
				$nivel_grid->LoadRowValues($nivel_grid->Recordset); // Load row values
				$nivel_grid->SetRecordKey($nivel_grid->RowOldKey, $nivel_grid->Recordset); // Set old record key
			} else {
				$nivel_grid->LoadDefaultValues(); // Load default values
				$nivel_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$nivel_grid->LoadRowValues($nivel_grid->Recordset); // Load row values
		}
		$nivel->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($nivel->CurrentAction == "gridadd") // Grid add
			$nivel->RowType = EW_ROWTYPE_ADD; // Render add
		if ($nivel->CurrentAction == "gridadd" && $nivel->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$nivel_grid->RestoreCurrentRowFormValues($nivel_grid->RowIndex); // Restore form values
		if ($nivel->CurrentAction == "gridedit") { // Grid edit
			if ($nivel->EventCancelled) {
				$nivel_grid->RestoreCurrentRowFormValues($nivel_grid->RowIndex); // Restore form values
			}
			if ($nivel_grid->RowAction == "insert")
				$nivel->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$nivel->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($nivel->CurrentAction == "gridedit" && ($nivel->RowType == EW_ROWTYPE_EDIT || $nivel->RowType == EW_ROWTYPE_ADD) && $nivel->EventCancelled) // Update failed
			$nivel_grid->RestoreCurrentRowFormValues($nivel_grid->RowIndex); // Restore form values
		if ($nivel->RowType == EW_ROWTYPE_EDIT) // Edit row
			$nivel_grid->EditRowCnt++;
		if ($nivel->CurrentAction == "F") // Confirm row
			$nivel_grid->RestoreCurrentRowFormValues($nivel_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$nivel->RowAttrs = array_merge($nivel->RowAttrs, array('data-rowindex'=>$nivel_grid->RowCnt, 'id'=>'r' . $nivel_grid->RowCnt . '_nivel', 'data-rowtype'=>$nivel->RowType));

		// Render row
		$nivel_grid->RenderRow();

		// Render list options
		$nivel_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($nivel_grid->RowAction <> "delete" && $nivel_grid->RowAction <> "insertdelete" && !($nivel_grid->RowAction == "insert" && $nivel->CurrentAction == "F" && $nivel_grid->EmptyRow())) {
?>
	<tr<?php echo $nivel->RowAttributes() ?>>
<?php

// Render list options (body, left)
$nivel_grid->ListOptions->Render("body", "left", $nivel_grid->RowCnt);
?>
	<?php if ($nivel->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion"<?php echo $nivel->descripcion->CellAttributes() ?>>
<?php if ($nivel->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $nivel_grid->RowCnt ?>_nivel_descripcion" class="form-group nivel_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $nivel_grid->RowIndex ?>_descripcion" id="x<?php echo $nivel_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($nivel->descripcion->PlaceHolder) ?>" value="<?php echo $nivel->descripcion->EditValue ?>"<?php echo $nivel->descripcion->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $nivel_grid->RowIndex ?>_descripcion" id="o<?php echo $nivel_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($nivel->descripcion->OldValue) ?>">
<?php } ?>
<?php if ($nivel->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $nivel_grid->RowCnt ?>_nivel_descripcion" class="form-group nivel_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $nivel_grid->RowIndex ?>_descripcion" id="x<?php echo $nivel_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($nivel->descripcion->PlaceHolder) ?>" value="<?php echo $nivel->descripcion->EditValue ?>"<?php echo $nivel->descripcion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($nivel->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $nivel->descripcion->ViewAttributes() ?>>
<?php echo $nivel->descripcion->ListViewValue() ?></span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $nivel_grid->RowIndex ?>_descripcion" id="x<?php echo $nivel_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($nivel->descripcion->FormValue) ?>">
<input type="hidden" data-field="x_descripcion" name="o<?php echo $nivel_grid->RowIndex ?>_descripcion" id="o<?php echo $nivel_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($nivel->descripcion->OldValue) ?>">
<?php } ?>
<a id="<?php echo $nivel_grid->PageObjName . "_row_" . $nivel_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($nivel->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idnivel" name="x<?php echo $nivel_grid->RowIndex ?>_idnivel" id="x<?php echo $nivel_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($nivel->idnivel->CurrentValue) ?>">
<input type="hidden" data-field="x_idnivel" name="o<?php echo $nivel_grid->RowIndex ?>_idnivel" id="o<?php echo $nivel_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($nivel->idnivel->OldValue) ?>">
<?php } ?>
<?php if ($nivel->RowType == EW_ROWTYPE_EDIT || $nivel->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_idnivel" name="x<?php echo $nivel_grid->RowIndex ?>_idnivel" id="x<?php echo $nivel_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($nivel->idnivel->CurrentValue) ?>">
<?php } ?>
	<?php if ($nivel->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $nivel->estado->CellAttributes() ?>>
<?php if ($nivel->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $nivel_grid->RowCnt ?>_nivel_estado" class="form-group nivel_estado">
<select data-field="x_estado" id="x<?php echo $nivel_grid->RowIndex ?>_estado" name="x<?php echo $nivel_grid->RowIndex ?>_estado"<?php echo $nivel->estado->EditAttributes() ?>>
<?php
if (is_array($nivel->estado->EditValue)) {
	$arwrk = $nivel->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($nivel->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $nivel->estado->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $nivel_grid->RowIndex ?>_estado" id="o<?php echo $nivel_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($nivel->estado->OldValue) ?>">
<?php } ?>
<?php if ($nivel->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $nivel_grid->RowCnt ?>_nivel_estado" class="form-group nivel_estado">
<select data-field="x_estado" id="x<?php echo $nivel_grid->RowIndex ?>_estado" name="x<?php echo $nivel_grid->RowIndex ?>_estado"<?php echo $nivel->estado->EditAttributes() ?>>
<?php
if (is_array($nivel->estado->EditValue)) {
	$arwrk = $nivel->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($nivel->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $nivel->estado->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($nivel->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $nivel->estado->ViewAttributes() ?>>
<?php echo $nivel->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $nivel_grid->RowIndex ?>_estado" id="x<?php echo $nivel_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($nivel->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $nivel_grid->RowIndex ?>_estado" id="o<?php echo $nivel_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($nivel->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($nivel->idhospital->Visible) { // idhospital ?>
		<td data-name="idhospital"<?php echo $nivel->idhospital->CellAttributes() ?>>
<?php if ($nivel->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($nivel->idhospital->getSessionValue() <> "") { ?>
<span id="el<?php echo $nivel_grid->RowCnt ?>_nivel_idhospital" class="form-group nivel_idhospital">
<span<?php echo $nivel->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $nivel->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $nivel_grid->RowIndex ?>_idhospital" name="x<?php echo $nivel_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($nivel->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $nivel_grid->RowCnt ?>_nivel_idhospital" class="form-group nivel_idhospital">
<select data-field="x_idhospital" id="x<?php echo $nivel_grid->RowIndex ?>_idhospital" name="x<?php echo $nivel_grid->RowIndex ?>_idhospital"<?php echo $nivel->idhospital->EditAttributes() ?>>
<?php
if (is_array($nivel->idhospital->EditValue)) {
	$arwrk = $nivel->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($nivel->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $nivel->idhospital->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $nivel->Lookup_Selecting($nivel->idhospital, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $nivel_grid->RowIndex ?>_idhospital" id="s_x<?php echo $nivel_grid->RowIndex ?>_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idhospital" name="o<?php echo $nivel_grid->RowIndex ?>_idhospital" id="o<?php echo $nivel_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($nivel->idhospital->OldValue) ?>">
<?php } ?>
<?php if ($nivel->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($nivel->idhospital->getSessionValue() <> "") { ?>
<span id="el<?php echo $nivel_grid->RowCnt ?>_nivel_idhospital" class="form-group nivel_idhospital">
<span<?php echo $nivel->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $nivel->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $nivel_grid->RowIndex ?>_idhospital" name="x<?php echo $nivel_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($nivel->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $nivel_grid->RowCnt ?>_nivel_idhospital" class="form-group nivel_idhospital">
<select data-field="x_idhospital" id="x<?php echo $nivel_grid->RowIndex ?>_idhospital" name="x<?php echo $nivel_grid->RowIndex ?>_idhospital"<?php echo $nivel->idhospital->EditAttributes() ?>>
<?php
if (is_array($nivel->idhospital->EditValue)) {
	$arwrk = $nivel->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($nivel->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $nivel->idhospital->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $nivel->Lookup_Selecting($nivel->idhospital, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $nivel_grid->RowIndex ?>_idhospital" id="s_x<?php echo $nivel_grid->RowIndex ?>_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($nivel->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $nivel->idhospital->ViewAttributes() ?>>
<?php echo $nivel->idhospital->ListViewValue() ?></span>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $nivel_grid->RowIndex ?>_idhospital" id="x<?php echo $nivel_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($nivel->idhospital->FormValue) ?>">
<input type="hidden" data-field="x_idhospital" name="o<?php echo $nivel_grid->RowIndex ?>_idhospital" id="o<?php echo $nivel_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($nivel->idhospital->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$nivel_grid->ListOptions->Render("body", "right", $nivel_grid->RowCnt);
?>
	</tr>
<?php if ($nivel->RowType == EW_ROWTYPE_ADD || $nivel->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fnivelgrid.UpdateOpts(<?php echo $nivel_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($nivel->CurrentAction <> "gridadd" || $nivel->CurrentMode == "copy")
		if (!$nivel_grid->Recordset->EOF) $nivel_grid->Recordset->MoveNext();
}
?>
<?php
	if ($nivel->CurrentMode == "add" || $nivel->CurrentMode == "copy" || $nivel->CurrentMode == "edit") {
		$nivel_grid->RowIndex = '$rowindex$';
		$nivel_grid->LoadDefaultValues();

		// Set row properties
		$nivel->ResetAttrs();
		$nivel->RowAttrs = array_merge($nivel->RowAttrs, array('data-rowindex'=>$nivel_grid->RowIndex, 'id'=>'r0_nivel', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($nivel->RowAttrs["class"], "ewTemplate");
		$nivel->RowType = EW_ROWTYPE_ADD;

		// Render row
		$nivel_grid->RenderRow();

		// Render list options
		$nivel_grid->RenderListOptions();
		$nivel_grid->StartRowCnt = 0;
?>
	<tr<?php echo $nivel->RowAttributes() ?>>
<?php

// Render list options (body, left)
$nivel_grid->ListOptions->Render("body", "left", $nivel_grid->RowIndex);
?>
	<?php if ($nivel->descripcion->Visible) { // descripcion ?>
		<td>
<?php if ($nivel->CurrentAction <> "F") { ?>
<span id="el$rowindex$_nivel_descripcion" class="form-group nivel_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $nivel_grid->RowIndex ?>_descripcion" id="x<?php echo $nivel_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($nivel->descripcion->PlaceHolder) ?>" value="<?php echo $nivel->descripcion->EditValue ?>"<?php echo $nivel->descripcion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_nivel_descripcion" class="form-group nivel_descripcion">
<span<?php echo $nivel->descripcion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $nivel->descripcion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $nivel_grid->RowIndex ?>_descripcion" id="x<?php echo $nivel_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($nivel->descripcion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $nivel_grid->RowIndex ?>_descripcion" id="o<?php echo $nivel_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($nivel->descripcion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($nivel->estado->Visible) { // estado ?>
		<td>
<?php if ($nivel->CurrentAction <> "F") { ?>
<span id="el$rowindex$_nivel_estado" class="form-group nivel_estado">
<select data-field="x_estado" id="x<?php echo $nivel_grid->RowIndex ?>_estado" name="x<?php echo $nivel_grid->RowIndex ?>_estado"<?php echo $nivel->estado->EditAttributes() ?>>
<?php
if (is_array($nivel->estado->EditValue)) {
	$arwrk = $nivel->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($nivel->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $nivel->estado->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_nivel_estado" class="form-group nivel_estado">
<span<?php echo $nivel->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $nivel->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $nivel_grid->RowIndex ?>_estado" id="x<?php echo $nivel_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($nivel->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $nivel_grid->RowIndex ?>_estado" id="o<?php echo $nivel_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($nivel->estado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($nivel->idhospital->Visible) { // idhospital ?>
		<td>
<?php if ($nivel->CurrentAction <> "F") { ?>
<?php if ($nivel->idhospital->getSessionValue() <> "") { ?>
<span id="el$rowindex$_nivel_idhospital" class="form-group nivel_idhospital">
<span<?php echo $nivel->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $nivel->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $nivel_grid->RowIndex ?>_idhospital" name="x<?php echo $nivel_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($nivel->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_nivel_idhospital" class="form-group nivel_idhospital">
<select data-field="x_idhospital" id="x<?php echo $nivel_grid->RowIndex ?>_idhospital" name="x<?php echo $nivel_grid->RowIndex ?>_idhospital"<?php echo $nivel->idhospital->EditAttributes() ?>>
<?php
if (is_array($nivel->idhospital->EditValue)) {
	$arwrk = $nivel->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($nivel->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $nivel->idhospital->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $nivel->Lookup_Selecting($nivel->idhospital, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $nivel_grid->RowIndex ?>_idhospital" id="s_x<?php echo $nivel_grid->RowIndex ?>_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_nivel_idhospital" class="form-group nivel_idhospital">
<span<?php echo $nivel->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $nivel->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $nivel_grid->RowIndex ?>_idhospital" id="x<?php echo $nivel_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($nivel->idhospital->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idhospital" name="o<?php echo $nivel_grid->RowIndex ?>_idhospital" id="o<?php echo $nivel_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($nivel->idhospital->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$nivel_grid->ListOptions->Render("body", "right", $nivel_grid->RowCnt);
?>
<script type="text/javascript">
fnivelgrid.UpdateOpts(<?php echo $nivel_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($nivel->CurrentMode == "add" || $nivel->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $nivel_grid->FormKeyCountName ?>" id="<?php echo $nivel_grid->FormKeyCountName ?>" value="<?php echo $nivel_grid->KeyCount ?>">
<?php echo $nivel_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($nivel->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $nivel_grid->FormKeyCountName ?>" id="<?php echo $nivel_grid->FormKeyCountName ?>" value="<?php echo $nivel_grid->KeyCount ?>">
<?php echo $nivel_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($nivel->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fnivelgrid">
</div>
<?php

// Close recordset
if ($nivel_grid->Recordset)
	$nivel_grid->Recordset->Close();
?>
<?php if ($nivel_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($nivel_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($nivel_grid->TotalRecs == 0 && $nivel->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($nivel_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($nivel->Export == "") { ?>
<script type="text/javascript">
fnivelgrid.Init();
</script>
<?php } ?>
<?php
$nivel_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$nivel_grid->Page_Terminate();
?>

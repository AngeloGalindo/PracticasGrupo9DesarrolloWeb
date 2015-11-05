<?php

// Create page object
if (!isset($sala_grid)) $sala_grid = new csala_grid();

// Page init
$sala_grid->Page_Init();

// Page main
$sala_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sala_grid->Page_Render();
?>
<?php if ($sala->Export == "") { ?>
<script type="text/javascript">

// Page object
var sala_grid = new ew_Page("sala_grid");
sala_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = sala_grid.PageID; // For backward compatibility

// Form object
var fsalagrid = new ew_Form("fsalagrid");
fsalagrid.FormKeyCountName = '<?php echo $sala_grid->FormKeyCountName ?>';

// Validate form
fsalagrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sala->estado->FldCaption(), $sala->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idnivel");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sala->idnivel->FldCaption(), $sala->idnivel->ReqErrMsg)) ?>");

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
fsalagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "descripcion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idnivel", false)) return false;
	return true;
}

// Form_CustomValidate event
fsalagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsalagrid.ValidateRequired = true;
<?php } else { ?>
fsalagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsalagrid.Lists["x_idnivel"] = {"LinkField":"x_idnivel","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($sala->CurrentAction == "gridadd") {
	if ($sala->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$sala_grid->TotalRecs = $sala->SelectRecordCount();
			$sala_grid->Recordset = $sala_grid->LoadRecordset($sala_grid->StartRec-1, $sala_grid->DisplayRecs);
		} else {
			if ($sala_grid->Recordset = $sala_grid->LoadRecordset())
				$sala_grid->TotalRecs = $sala_grid->Recordset->RecordCount();
		}
		$sala_grid->StartRec = 1;
		$sala_grid->DisplayRecs = $sala_grid->TotalRecs;
	} else {
		$sala->CurrentFilter = "0=1";
		$sala_grid->StartRec = 1;
		$sala_grid->DisplayRecs = $sala->GridAddRowCount;
	}
	$sala_grid->TotalRecs = $sala_grid->DisplayRecs;
	$sala_grid->StopRec = $sala_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$sala_grid->TotalRecs = $sala->SelectRecordCount();
	} else {
		if ($sala_grid->Recordset = $sala_grid->LoadRecordset())
			$sala_grid->TotalRecs = $sala_grid->Recordset->RecordCount();
	}
	$sala_grid->StartRec = 1;
	$sala_grid->DisplayRecs = $sala_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$sala_grid->Recordset = $sala_grid->LoadRecordset($sala_grid->StartRec-1, $sala_grid->DisplayRecs);

	// Set no record found message
	if ($sala->CurrentAction == "" && $sala_grid->TotalRecs == 0) {
		if ($sala_grid->SearchWhere == "0=101")
			$sala_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$sala_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$sala_grid->RenderOtherOptions();
?>
<?php $sala_grid->ShowPageHeader(); ?>
<?php
$sala_grid->ShowMessage();
?>
<?php if ($sala_grid->TotalRecs > 0 || $sala->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fsalagrid" class="ewForm form-inline">
<div id="gmp_sala" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_salagrid" class="table ewTable">
<?php echo $sala->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$sala_grid->RenderListOptions();

// Render list options (header, left)
$sala_grid->ListOptions->Render("header", "left");
?>
<?php if ($sala->descripcion->Visible) { // descripcion ?>
	<?php if ($sala->SortUrl($sala->descripcion) == "") { ?>
		<th data-name="descripcion"><div id="elh_sala_descripcion" class="sala_descripcion"><div class="ewTableHeaderCaption"><?php echo $sala->descripcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descripcion"><div><div id="elh_sala_descripcion" class="sala_descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sala->descripcion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sala->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sala->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sala->estado->Visible) { // estado ?>
	<?php if ($sala->SortUrl($sala->estado) == "") { ?>
		<th data-name="estado"><div id="elh_sala_estado" class="sala_estado"><div class="ewTableHeaderCaption"><?php echo $sala->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_sala_estado" class="sala_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sala->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sala->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sala->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sala->idnivel->Visible) { // idnivel ?>
	<?php if ($sala->SortUrl($sala->idnivel) == "") { ?>
		<th data-name="idnivel"><div id="elh_sala_idnivel" class="sala_idnivel"><div class="ewTableHeaderCaption"><?php echo $sala->idnivel->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idnivel"><div><div id="elh_sala_idnivel" class="sala_idnivel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sala->idnivel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sala->idnivel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sala->idnivel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$sala_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$sala_grid->StartRec = 1;
$sala_grid->StopRec = $sala_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($sala_grid->FormKeyCountName) && ($sala->CurrentAction == "gridadd" || $sala->CurrentAction == "gridedit" || $sala->CurrentAction == "F")) {
		$sala_grid->KeyCount = $objForm->GetValue($sala_grid->FormKeyCountName);
		$sala_grid->StopRec = $sala_grid->StartRec + $sala_grid->KeyCount - 1;
	}
}
$sala_grid->RecCnt = $sala_grid->StartRec - 1;
if ($sala_grid->Recordset && !$sala_grid->Recordset->EOF) {
	$sala_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $sala_grid->StartRec > 1)
		$sala_grid->Recordset->Move($sala_grid->StartRec - 1);
} elseif (!$sala->AllowAddDeleteRow && $sala_grid->StopRec == 0) {
	$sala_grid->StopRec = $sala->GridAddRowCount;
}

// Initialize aggregate
$sala->RowType = EW_ROWTYPE_AGGREGATEINIT;
$sala->ResetAttrs();
$sala_grid->RenderRow();
if ($sala->CurrentAction == "gridadd")
	$sala_grid->RowIndex = 0;
if ($sala->CurrentAction == "gridedit")
	$sala_grid->RowIndex = 0;
while ($sala_grid->RecCnt < $sala_grid->StopRec) {
	$sala_grid->RecCnt++;
	if (intval($sala_grid->RecCnt) >= intval($sala_grid->StartRec)) {
		$sala_grid->RowCnt++;
		if ($sala->CurrentAction == "gridadd" || $sala->CurrentAction == "gridedit" || $sala->CurrentAction == "F") {
			$sala_grid->RowIndex++;
			$objForm->Index = $sala_grid->RowIndex;
			if ($objForm->HasValue($sala_grid->FormActionName))
				$sala_grid->RowAction = strval($objForm->GetValue($sala_grid->FormActionName));
			elseif ($sala->CurrentAction == "gridadd")
				$sala_grid->RowAction = "insert";
			else
				$sala_grid->RowAction = "";
		}

		// Set up key count
		$sala_grid->KeyCount = $sala_grid->RowIndex;

		// Init row class and style
		$sala->ResetAttrs();
		$sala->CssClass = "";
		if ($sala->CurrentAction == "gridadd") {
			if ($sala->CurrentMode == "copy") {
				$sala_grid->LoadRowValues($sala_grid->Recordset); // Load row values
				$sala_grid->SetRecordKey($sala_grid->RowOldKey, $sala_grid->Recordset); // Set old record key
			} else {
				$sala_grid->LoadDefaultValues(); // Load default values
				$sala_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$sala_grid->LoadRowValues($sala_grid->Recordset); // Load row values
		}
		$sala->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($sala->CurrentAction == "gridadd") // Grid add
			$sala->RowType = EW_ROWTYPE_ADD; // Render add
		if ($sala->CurrentAction == "gridadd" && $sala->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$sala_grid->RestoreCurrentRowFormValues($sala_grid->RowIndex); // Restore form values
		if ($sala->CurrentAction == "gridedit") { // Grid edit
			if ($sala->EventCancelled) {
				$sala_grid->RestoreCurrentRowFormValues($sala_grid->RowIndex); // Restore form values
			}
			if ($sala_grid->RowAction == "insert")
				$sala->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$sala->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($sala->CurrentAction == "gridedit" && ($sala->RowType == EW_ROWTYPE_EDIT || $sala->RowType == EW_ROWTYPE_ADD) && $sala->EventCancelled) // Update failed
			$sala_grid->RestoreCurrentRowFormValues($sala_grid->RowIndex); // Restore form values
		if ($sala->RowType == EW_ROWTYPE_EDIT) // Edit row
			$sala_grid->EditRowCnt++;
		if ($sala->CurrentAction == "F") // Confirm row
			$sala_grid->RestoreCurrentRowFormValues($sala_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$sala->RowAttrs = array_merge($sala->RowAttrs, array('data-rowindex'=>$sala_grid->RowCnt, 'id'=>'r' . $sala_grid->RowCnt . '_sala', 'data-rowtype'=>$sala->RowType));

		// Render row
		$sala_grid->RenderRow();

		// Render list options
		$sala_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($sala_grid->RowAction <> "delete" && $sala_grid->RowAction <> "insertdelete" && !($sala_grid->RowAction == "insert" && $sala->CurrentAction == "F" && $sala_grid->EmptyRow())) {
?>
	<tr<?php echo $sala->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sala_grid->ListOptions->Render("body", "left", $sala_grid->RowCnt);
?>
	<?php if ($sala->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion"<?php echo $sala->descripcion->CellAttributes() ?>>
<?php if ($sala->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $sala_grid->RowCnt ?>_sala_descripcion" class="form-group sala_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $sala_grid->RowIndex ?>_descripcion" id="x<?php echo $sala_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($sala->descripcion->PlaceHolder) ?>" value="<?php echo $sala->descripcion->EditValue ?>"<?php echo $sala->descripcion->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $sala_grid->RowIndex ?>_descripcion" id="o<?php echo $sala_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($sala->descripcion->OldValue) ?>">
<?php } ?>
<?php if ($sala->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $sala_grid->RowCnt ?>_sala_descripcion" class="form-group sala_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $sala_grid->RowIndex ?>_descripcion" id="x<?php echo $sala_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($sala->descripcion->PlaceHolder) ?>" value="<?php echo $sala->descripcion->EditValue ?>"<?php echo $sala->descripcion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($sala->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $sala->descripcion->ViewAttributes() ?>>
<?php echo $sala->descripcion->ListViewValue() ?></span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $sala_grid->RowIndex ?>_descripcion" id="x<?php echo $sala_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($sala->descripcion->FormValue) ?>">
<input type="hidden" data-field="x_descripcion" name="o<?php echo $sala_grid->RowIndex ?>_descripcion" id="o<?php echo $sala_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($sala->descripcion->OldValue) ?>">
<?php } ?>
<a id="<?php echo $sala_grid->PageObjName . "_row_" . $sala_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($sala->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idsala" name="x<?php echo $sala_grid->RowIndex ?>_idsala" id="x<?php echo $sala_grid->RowIndex ?>_idsala" value="<?php echo ew_HtmlEncode($sala->idsala->CurrentValue) ?>">
<input type="hidden" data-field="x_idsala" name="o<?php echo $sala_grid->RowIndex ?>_idsala" id="o<?php echo $sala_grid->RowIndex ?>_idsala" value="<?php echo ew_HtmlEncode($sala->idsala->OldValue) ?>">
<?php } ?>
<?php if ($sala->RowType == EW_ROWTYPE_EDIT || $sala->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_idsala" name="x<?php echo $sala_grid->RowIndex ?>_idsala" id="x<?php echo $sala_grid->RowIndex ?>_idsala" value="<?php echo ew_HtmlEncode($sala->idsala->CurrentValue) ?>">
<?php } ?>
	<?php if ($sala->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $sala->estado->CellAttributes() ?>>
<?php if ($sala->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $sala_grid->RowCnt ?>_sala_estado" class="form-group sala_estado">
<select data-field="x_estado" id="x<?php echo $sala_grid->RowIndex ?>_estado" name="x<?php echo $sala_grid->RowIndex ?>_estado"<?php echo $sala->estado->EditAttributes() ?>>
<?php
if (is_array($sala->estado->EditValue)) {
	$arwrk = $sala->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($sala->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $sala->estado->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $sala_grid->RowIndex ?>_estado" id="o<?php echo $sala_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($sala->estado->OldValue) ?>">
<?php } ?>
<?php if ($sala->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $sala_grid->RowCnt ?>_sala_estado" class="form-group sala_estado">
<select data-field="x_estado" id="x<?php echo $sala_grid->RowIndex ?>_estado" name="x<?php echo $sala_grid->RowIndex ?>_estado"<?php echo $sala->estado->EditAttributes() ?>>
<?php
if (is_array($sala->estado->EditValue)) {
	$arwrk = $sala->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($sala->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $sala->estado->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($sala->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $sala->estado->ViewAttributes() ?>>
<?php echo $sala->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $sala_grid->RowIndex ?>_estado" id="x<?php echo $sala_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($sala->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $sala_grid->RowIndex ?>_estado" id="o<?php echo $sala_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($sala->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($sala->idnivel->Visible) { // idnivel ?>
		<td data-name="idnivel"<?php echo $sala->idnivel->CellAttributes() ?>>
<?php if ($sala->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($sala->idnivel->getSessionValue() <> "") { ?>
<span id="el<?php echo $sala_grid->RowCnt ?>_sala_idnivel" class="form-group sala_idnivel">
<span<?php echo $sala->idnivel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sala->idnivel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sala_grid->RowIndex ?>_idnivel" name="x<?php echo $sala_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($sala->idnivel->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $sala_grid->RowCnt ?>_sala_idnivel" class="form-group sala_idnivel">
<select data-field="x_idnivel" id="x<?php echo $sala_grid->RowIndex ?>_idnivel" name="x<?php echo $sala_grid->RowIndex ?>_idnivel"<?php echo $sala->idnivel->EditAttributes() ?>>
<?php
if (is_array($sala->idnivel->EditValue)) {
	$arwrk = $sala->idnivel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($sala->idnivel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $sala->idnivel->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idnivel`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `nivel`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $sala->Lookup_Selecting($sala->idnivel, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $sala_grid->RowIndex ?>_idnivel" id="s_x<?php echo $sala_grid->RowIndex ?>_idnivel" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idnivel` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idnivel" name="o<?php echo $sala_grid->RowIndex ?>_idnivel" id="o<?php echo $sala_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($sala->idnivel->OldValue) ?>">
<?php } ?>
<?php if ($sala->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($sala->idnivel->getSessionValue() <> "") { ?>
<span id="el<?php echo $sala_grid->RowCnt ?>_sala_idnivel" class="form-group sala_idnivel">
<span<?php echo $sala->idnivel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sala->idnivel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sala_grid->RowIndex ?>_idnivel" name="x<?php echo $sala_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($sala->idnivel->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $sala_grid->RowCnt ?>_sala_idnivel" class="form-group sala_idnivel">
<select data-field="x_idnivel" id="x<?php echo $sala_grid->RowIndex ?>_idnivel" name="x<?php echo $sala_grid->RowIndex ?>_idnivel"<?php echo $sala->idnivel->EditAttributes() ?>>
<?php
if (is_array($sala->idnivel->EditValue)) {
	$arwrk = $sala->idnivel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($sala->idnivel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $sala->idnivel->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idnivel`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `nivel`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $sala->Lookup_Selecting($sala->idnivel, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $sala_grid->RowIndex ?>_idnivel" id="s_x<?php echo $sala_grid->RowIndex ?>_idnivel" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idnivel` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($sala->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $sala->idnivel->ViewAttributes() ?>>
<?php echo $sala->idnivel->ListViewValue() ?></span>
<input type="hidden" data-field="x_idnivel" name="x<?php echo $sala_grid->RowIndex ?>_idnivel" id="x<?php echo $sala_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($sala->idnivel->FormValue) ?>">
<input type="hidden" data-field="x_idnivel" name="o<?php echo $sala_grid->RowIndex ?>_idnivel" id="o<?php echo $sala_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($sala->idnivel->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sala_grid->ListOptions->Render("body", "right", $sala_grid->RowCnt);
?>
	</tr>
<?php if ($sala->RowType == EW_ROWTYPE_ADD || $sala->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsalagrid.UpdateOpts(<?php echo $sala_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($sala->CurrentAction <> "gridadd" || $sala->CurrentMode == "copy")
		if (!$sala_grid->Recordset->EOF) $sala_grid->Recordset->MoveNext();
}
?>
<?php
	if ($sala->CurrentMode == "add" || $sala->CurrentMode == "copy" || $sala->CurrentMode == "edit") {
		$sala_grid->RowIndex = '$rowindex$';
		$sala_grid->LoadDefaultValues();

		// Set row properties
		$sala->ResetAttrs();
		$sala->RowAttrs = array_merge($sala->RowAttrs, array('data-rowindex'=>$sala_grid->RowIndex, 'id'=>'r0_sala', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($sala->RowAttrs["class"], "ewTemplate");
		$sala->RowType = EW_ROWTYPE_ADD;

		// Render row
		$sala_grid->RenderRow();

		// Render list options
		$sala_grid->RenderListOptions();
		$sala_grid->StartRowCnt = 0;
?>
	<tr<?php echo $sala->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sala_grid->ListOptions->Render("body", "left", $sala_grid->RowIndex);
?>
	<?php if ($sala->descripcion->Visible) { // descripcion ?>
		<td>
<?php if ($sala->CurrentAction <> "F") { ?>
<span id="el$rowindex$_sala_descripcion" class="form-group sala_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $sala_grid->RowIndex ?>_descripcion" id="x<?php echo $sala_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($sala->descripcion->PlaceHolder) ?>" value="<?php echo $sala->descripcion->EditValue ?>"<?php echo $sala->descripcion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_sala_descripcion" class="form-group sala_descripcion">
<span<?php echo $sala->descripcion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sala->descripcion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $sala_grid->RowIndex ?>_descripcion" id="x<?php echo $sala_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($sala->descripcion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $sala_grid->RowIndex ?>_descripcion" id="o<?php echo $sala_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($sala->descripcion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sala->estado->Visible) { // estado ?>
		<td>
<?php if ($sala->CurrentAction <> "F") { ?>
<span id="el$rowindex$_sala_estado" class="form-group sala_estado">
<select data-field="x_estado" id="x<?php echo $sala_grid->RowIndex ?>_estado" name="x<?php echo $sala_grid->RowIndex ?>_estado"<?php echo $sala->estado->EditAttributes() ?>>
<?php
if (is_array($sala->estado->EditValue)) {
	$arwrk = $sala->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($sala->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $sala->estado->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_sala_estado" class="form-group sala_estado">
<span<?php echo $sala->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sala->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $sala_grid->RowIndex ?>_estado" id="x<?php echo $sala_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($sala->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $sala_grid->RowIndex ?>_estado" id="o<?php echo $sala_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($sala->estado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sala->idnivel->Visible) { // idnivel ?>
		<td>
<?php if ($sala->CurrentAction <> "F") { ?>
<?php if ($sala->idnivel->getSessionValue() <> "") { ?>
<span id="el$rowindex$_sala_idnivel" class="form-group sala_idnivel">
<span<?php echo $sala->idnivel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sala->idnivel->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sala_grid->RowIndex ?>_idnivel" name="x<?php echo $sala_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($sala->idnivel->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_sala_idnivel" class="form-group sala_idnivel">
<select data-field="x_idnivel" id="x<?php echo $sala_grid->RowIndex ?>_idnivel" name="x<?php echo $sala_grid->RowIndex ?>_idnivel"<?php echo $sala->idnivel->EditAttributes() ?>>
<?php
if (is_array($sala->idnivel->EditValue)) {
	$arwrk = $sala->idnivel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($sala->idnivel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $sala->idnivel->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idnivel`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `nivel`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $sala->Lookup_Selecting($sala->idnivel, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $sala_grid->RowIndex ?>_idnivel" id="s_x<?php echo $sala_grid->RowIndex ?>_idnivel" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idnivel` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_sala_idnivel" class="form-group sala_idnivel">
<span<?php echo $sala->idnivel->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sala->idnivel->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idnivel" name="x<?php echo $sala_grid->RowIndex ?>_idnivel" id="x<?php echo $sala_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($sala->idnivel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idnivel" name="o<?php echo $sala_grid->RowIndex ?>_idnivel" id="o<?php echo $sala_grid->RowIndex ?>_idnivel" value="<?php echo ew_HtmlEncode($sala->idnivel->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sala_grid->ListOptions->Render("body", "right", $sala_grid->RowCnt);
?>
<script type="text/javascript">
fsalagrid.UpdateOpts(<?php echo $sala_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($sala->CurrentMode == "add" || $sala->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $sala_grid->FormKeyCountName ?>" id="<?php echo $sala_grid->FormKeyCountName ?>" value="<?php echo $sala_grid->KeyCount ?>">
<?php echo $sala_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($sala->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $sala_grid->FormKeyCountName ?>" id="<?php echo $sala_grid->FormKeyCountName ?>" value="<?php echo $sala_grid->KeyCount ?>">
<?php echo $sala_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($sala->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsalagrid">
</div>
<?php

// Close recordset
if ($sala_grid->Recordset)
	$sala_grid->Recordset->Close();
?>
<?php if ($sala_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($sala_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($sala_grid->TotalRecs == 0 && $sala->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sala_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($sala->Export == "") { ?>
<script type="text/javascript">
fsalagrid.Init();
</script>
<?php } ?>
<?php
$sala_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$sala_grid->Page_Terminate();
?>

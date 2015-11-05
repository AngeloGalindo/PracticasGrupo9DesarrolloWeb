<?php

// Create page object
if (!isset($internado_diario_grid)) $internado_diario_grid = new cinternado_diario_grid();

// Page init
$internado_diario_grid->Page_Init();

// Page main
$internado_diario_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$internado_diario_grid->Page_Render();
?>
<?php if ($internado_diario->Export == "") { ?>
<script type="text/javascript">

// Page object
var internado_diario_grid = new ew_Page("internado_diario_grid");
internado_diario_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = internado_diario_grid.PageID; // For backward compatibility

// Form object
var finternado_diariogrid = new ew_Form("finternado_diariogrid");
finternado_diariogrid.FormKeyCountName = '<?php echo $internado_diario_grid->FormKeyCountName ?>';

// Validate form
finternado_diariogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idinternado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado_diario->idinternado->FldCaption(), $internado_diario->idinternado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idcuenta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado_diario->idcuenta->FldCaption(), $internado_diario->idcuenta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($internado_diario->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_costo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado_diario->costo->FldCaption(), $internado_diario->costo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_costo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($internado_diario->costo->FldErrMsg()) ?>");

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
finternado_diariogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idinternado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idcuenta", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha", false)) return false;
	if (ew_ValueChanged(fobj, infix, "costo", false)) return false;
	return true;
}

// Form_CustomValidate event
finternado_diariogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finternado_diariogrid.ValidateRequired = true;
<?php } else { ?>
finternado_diariogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
finternado_diariogrid.Lists["x_idinternado"] = {"LinkField":"x_idinternado","Ajax":true,"AutoFill":false,"DisplayFields":["x_idinternado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
finternado_diariogrid.Lists["x_idcuenta"] = {"LinkField":"x_idcuenta","Ajax":true,"AutoFill":false,"DisplayFields":["x_idcuenta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($internado_diario->CurrentAction == "gridadd") {
	if ($internado_diario->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$internado_diario_grid->TotalRecs = $internado_diario->SelectRecordCount();
			$internado_diario_grid->Recordset = $internado_diario_grid->LoadRecordset($internado_diario_grid->StartRec-1, $internado_diario_grid->DisplayRecs);
		} else {
			if ($internado_diario_grid->Recordset = $internado_diario_grid->LoadRecordset())
				$internado_diario_grid->TotalRecs = $internado_diario_grid->Recordset->RecordCount();
		}
		$internado_diario_grid->StartRec = 1;
		$internado_diario_grid->DisplayRecs = $internado_diario_grid->TotalRecs;
	} else {
		$internado_diario->CurrentFilter = "0=1";
		$internado_diario_grid->StartRec = 1;
		$internado_diario_grid->DisplayRecs = $internado_diario->GridAddRowCount;
	}
	$internado_diario_grid->TotalRecs = $internado_diario_grid->DisplayRecs;
	$internado_diario_grid->StopRec = $internado_diario_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$internado_diario_grid->TotalRecs = $internado_diario->SelectRecordCount();
	} else {
		if ($internado_diario_grid->Recordset = $internado_diario_grid->LoadRecordset())
			$internado_diario_grid->TotalRecs = $internado_diario_grid->Recordset->RecordCount();
	}
	$internado_diario_grid->StartRec = 1;
	$internado_diario_grid->DisplayRecs = $internado_diario_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$internado_diario_grid->Recordset = $internado_diario_grid->LoadRecordset($internado_diario_grid->StartRec-1, $internado_diario_grid->DisplayRecs);

	// Set no record found message
	if ($internado_diario->CurrentAction == "" && $internado_diario_grid->TotalRecs == 0) {
		if ($internado_diario_grid->SearchWhere == "0=101")
			$internado_diario_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$internado_diario_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$internado_diario_grid->RenderOtherOptions();
?>
<?php $internado_diario_grid->ShowPageHeader(); ?>
<?php
$internado_diario_grid->ShowMessage();
?>
<?php if ($internado_diario_grid->TotalRecs > 0 || $internado_diario->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="finternado_diariogrid" class="ewForm form-inline">
<div id="gmp_internado_diario" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_internado_diariogrid" class="table ewTable">
<?php echo $internado_diario->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$internado_diario_grid->RenderListOptions();

// Render list options (header, left)
$internado_diario_grid->ListOptions->Render("header", "left");
?>
<?php if ($internado_diario->idinternado_diario->Visible) { // idinternado_diario ?>
	<?php if ($internado_diario->SortUrl($internado_diario->idinternado_diario) == "") { ?>
		<th data-name="idinternado_diario"><div id="elh_internado_diario_idinternado_diario" class="internado_diario_idinternado_diario"><div class="ewTableHeaderCaption"><?php echo $internado_diario->idinternado_diario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idinternado_diario"><div><div id="elh_internado_diario_idinternado_diario" class="internado_diario_idinternado_diario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado_diario->idinternado_diario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado_diario->idinternado_diario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado_diario->idinternado_diario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado_diario->idinternado->Visible) { // idinternado ?>
	<?php if ($internado_diario->SortUrl($internado_diario->idinternado) == "") { ?>
		<th data-name="idinternado"><div id="elh_internado_diario_idinternado" class="internado_diario_idinternado"><div class="ewTableHeaderCaption"><?php echo $internado_diario->idinternado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idinternado"><div><div id="elh_internado_diario_idinternado" class="internado_diario_idinternado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado_diario->idinternado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado_diario->idinternado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado_diario->idinternado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado_diario->idcuenta->Visible) { // idcuenta ?>
	<?php if ($internado_diario->SortUrl($internado_diario->idcuenta) == "") { ?>
		<th data-name="idcuenta"><div id="elh_internado_diario_idcuenta" class="internado_diario_idcuenta"><div class="ewTableHeaderCaption"><?php echo $internado_diario->idcuenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idcuenta"><div><div id="elh_internado_diario_idcuenta" class="internado_diario_idcuenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado_diario->idcuenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado_diario->idcuenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado_diario->idcuenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado_diario->fecha->Visible) { // fecha ?>
	<?php if ($internado_diario->SortUrl($internado_diario->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_internado_diario_fecha" class="internado_diario_fecha"><div class="ewTableHeaderCaption"><?php echo $internado_diario->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_internado_diario_fecha" class="internado_diario_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado_diario->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado_diario->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado_diario->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado_diario->costo->Visible) { // costo ?>
	<?php if ($internado_diario->SortUrl($internado_diario->costo) == "") { ?>
		<th data-name="costo"><div id="elh_internado_diario_costo" class="internado_diario_costo"><div class="ewTableHeaderCaption"><?php echo $internado_diario->costo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="costo"><div><div id="elh_internado_diario_costo" class="internado_diario_costo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado_diario->costo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado_diario->costo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado_diario->costo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$internado_diario_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$internado_diario_grid->StartRec = 1;
$internado_diario_grid->StopRec = $internado_diario_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($internado_diario_grid->FormKeyCountName) && ($internado_diario->CurrentAction == "gridadd" || $internado_diario->CurrentAction == "gridedit" || $internado_diario->CurrentAction == "F")) {
		$internado_diario_grid->KeyCount = $objForm->GetValue($internado_diario_grid->FormKeyCountName);
		$internado_diario_grid->StopRec = $internado_diario_grid->StartRec + $internado_diario_grid->KeyCount - 1;
	}
}
$internado_diario_grid->RecCnt = $internado_diario_grid->StartRec - 1;
if ($internado_diario_grid->Recordset && !$internado_diario_grid->Recordset->EOF) {
	$internado_diario_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $internado_diario_grid->StartRec > 1)
		$internado_diario_grid->Recordset->Move($internado_diario_grid->StartRec - 1);
} elseif (!$internado_diario->AllowAddDeleteRow && $internado_diario_grid->StopRec == 0) {
	$internado_diario_grid->StopRec = $internado_diario->GridAddRowCount;
}

// Initialize aggregate
$internado_diario->RowType = EW_ROWTYPE_AGGREGATEINIT;
$internado_diario->ResetAttrs();
$internado_diario_grid->RenderRow();
if ($internado_diario->CurrentAction == "gridadd")
	$internado_diario_grid->RowIndex = 0;
if ($internado_diario->CurrentAction == "gridedit")
	$internado_diario_grid->RowIndex = 0;
while ($internado_diario_grid->RecCnt < $internado_diario_grid->StopRec) {
	$internado_diario_grid->RecCnt++;
	if (intval($internado_diario_grid->RecCnt) >= intval($internado_diario_grid->StartRec)) {
		$internado_diario_grid->RowCnt++;
		if ($internado_diario->CurrentAction == "gridadd" || $internado_diario->CurrentAction == "gridedit" || $internado_diario->CurrentAction == "F") {
			$internado_diario_grid->RowIndex++;
			$objForm->Index = $internado_diario_grid->RowIndex;
			if ($objForm->HasValue($internado_diario_grid->FormActionName))
				$internado_diario_grid->RowAction = strval($objForm->GetValue($internado_diario_grid->FormActionName));
			elseif ($internado_diario->CurrentAction == "gridadd")
				$internado_diario_grid->RowAction = "insert";
			else
				$internado_diario_grid->RowAction = "";
		}

		// Set up key count
		$internado_diario_grid->KeyCount = $internado_diario_grid->RowIndex;

		// Init row class and style
		$internado_diario->ResetAttrs();
		$internado_diario->CssClass = "";
		if ($internado_diario->CurrentAction == "gridadd") {
			if ($internado_diario->CurrentMode == "copy") {
				$internado_diario_grid->LoadRowValues($internado_diario_grid->Recordset); // Load row values
				$internado_diario_grid->SetRecordKey($internado_diario_grid->RowOldKey, $internado_diario_grid->Recordset); // Set old record key
			} else {
				$internado_diario_grid->LoadDefaultValues(); // Load default values
				$internado_diario_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$internado_diario_grid->LoadRowValues($internado_diario_grid->Recordset); // Load row values
		}
		$internado_diario->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($internado_diario->CurrentAction == "gridadd") // Grid add
			$internado_diario->RowType = EW_ROWTYPE_ADD; // Render add
		if ($internado_diario->CurrentAction == "gridadd" && $internado_diario->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$internado_diario_grid->RestoreCurrentRowFormValues($internado_diario_grid->RowIndex); // Restore form values
		if ($internado_diario->CurrentAction == "gridedit") { // Grid edit
			if ($internado_diario->EventCancelled) {
				$internado_diario_grid->RestoreCurrentRowFormValues($internado_diario_grid->RowIndex); // Restore form values
			}
			if ($internado_diario_grid->RowAction == "insert")
				$internado_diario->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$internado_diario->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($internado_diario->CurrentAction == "gridedit" && ($internado_diario->RowType == EW_ROWTYPE_EDIT || $internado_diario->RowType == EW_ROWTYPE_ADD) && $internado_diario->EventCancelled) // Update failed
			$internado_diario_grid->RestoreCurrentRowFormValues($internado_diario_grid->RowIndex); // Restore form values
		if ($internado_diario->RowType == EW_ROWTYPE_EDIT) // Edit row
			$internado_diario_grid->EditRowCnt++;
		if ($internado_diario->CurrentAction == "F") // Confirm row
			$internado_diario_grid->RestoreCurrentRowFormValues($internado_diario_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$internado_diario->RowAttrs = array_merge($internado_diario->RowAttrs, array('data-rowindex'=>$internado_diario_grid->RowCnt, 'id'=>'r' . $internado_diario_grid->RowCnt . '_internado_diario', 'data-rowtype'=>$internado_diario->RowType));

		// Render row
		$internado_diario_grid->RenderRow();

		// Render list options
		$internado_diario_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($internado_diario_grid->RowAction <> "delete" && $internado_diario_grid->RowAction <> "insertdelete" && !($internado_diario_grid->RowAction == "insert" && $internado_diario->CurrentAction == "F" && $internado_diario_grid->EmptyRow())) {
?>
	<tr<?php echo $internado_diario->RowAttributes() ?>>
<?php

// Render list options (body, left)
$internado_diario_grid->ListOptions->Render("body", "left", $internado_diario_grid->RowCnt);
?>
	<?php if ($internado_diario->idinternado_diario->Visible) { // idinternado_diario ?>
		<td data-name="idinternado_diario"<?php echo $internado_diario->idinternado_diario->CellAttributes() ?>>
<?php if ($internado_diario->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idinternado_diario" name="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" id="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" value="<?php echo ew_HtmlEncode($internado_diario->idinternado_diario->OldValue) ?>">
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_idinternado_diario" class="form-group internado_diario_idinternado_diario">
<span<?php echo $internado_diario->idinternado_diario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idinternado_diario->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_idinternado_diario" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" value="<?php echo ew_HtmlEncode($internado_diario->idinternado_diario->CurrentValue) ?>">
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado_diario->idinternado_diario->ViewAttributes() ?>>
<?php echo $internado_diario->idinternado_diario->ListViewValue() ?></span>
<input type="hidden" data-field="x_idinternado_diario" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" value="<?php echo ew_HtmlEncode($internado_diario->idinternado_diario->FormValue) ?>">
<input type="hidden" data-field="x_idinternado_diario" name="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" id="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" value="<?php echo ew_HtmlEncode($internado_diario->idinternado_diario->OldValue) ?>">
<?php } ?>
<a id="<?php echo $internado_diario_grid->PageObjName . "_row_" . $internado_diario_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($internado_diario->idinternado->Visible) { // idinternado ?>
		<td data-name="idinternado"<?php echo $internado_diario->idinternado->CellAttributes() ?>>
<?php if ($internado_diario->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($internado_diario->idinternado->getSessionValue() <> "") { ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_idinternado" class="form-group internado_diario_idinternado">
<span<?php echo $internado_diario->idinternado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idinternado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado_diario->idinternado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_idinternado" class="form-group internado_diario_idinternado">
<select data-field="x_idinternado" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado"<?php echo $internado_diario->idinternado->EditAttributes() ?>>
<?php
if (is_array($internado_diario->idinternado->EditValue)) {
	$arwrk = $internado_diario->idinternado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado_diario->idinternado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado_diario->idinternado->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idinternado`, `idinternado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `internado`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado_diario->Lookup_Selecting($internado_diario->idinternado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" id="s_x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idinternado` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idinternado" name="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado" id="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado_diario->idinternado->OldValue) ?>">
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($internado_diario->idinternado->getSessionValue() <> "") { ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_idinternado" class="form-group internado_diario_idinternado">
<span<?php echo $internado_diario->idinternado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idinternado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado_diario->idinternado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_idinternado" class="form-group internado_diario_idinternado">
<select data-field="x_idinternado" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado"<?php echo $internado_diario->idinternado->EditAttributes() ?>>
<?php
if (is_array($internado_diario->idinternado->EditValue)) {
	$arwrk = $internado_diario->idinternado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado_diario->idinternado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado_diario->idinternado->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idinternado`, `idinternado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `internado`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado_diario->Lookup_Selecting($internado_diario->idinternado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" id="s_x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idinternado` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado_diario->idinternado->ViewAttributes() ?>>
<?php echo $internado_diario->idinternado->ListViewValue() ?></span>
<input type="hidden" data-field="x_idinternado" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado_diario->idinternado->FormValue) ?>">
<input type="hidden" data-field="x_idinternado" name="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado" id="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado_diario->idinternado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($internado_diario->idcuenta->Visible) { // idcuenta ?>
		<td data-name="idcuenta"<?php echo $internado_diario->idcuenta->CellAttributes() ?>>
<?php if ($internado_diario->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($internado_diario->idcuenta->getSessionValue() <> "") { ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_idcuenta" class="form-group internado_diario_idcuenta">
<span<?php echo $internado_diario->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" name="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($internado_diario->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_idcuenta" class="form-group internado_diario_idcuenta">
<select data-field="x_idcuenta" id="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" name="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta"<?php echo $internado_diario->idcuenta->EditAttributes() ?>>
<?php
if (is_array($internado_diario->idcuenta->EditValue)) {
	$arwrk = $internado_diario->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado_diario->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado_diario->idcuenta->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado_diario->Lookup_Selecting($internado_diario->idcuenta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" id="s_x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" id="o<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($internado_diario->idcuenta->OldValue) ?>">
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($internado_diario->idcuenta->getSessionValue() <> "") { ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_idcuenta" class="form-group internado_diario_idcuenta">
<span<?php echo $internado_diario->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" name="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($internado_diario->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_idcuenta" class="form-group internado_diario_idcuenta">
<select data-field="x_idcuenta" id="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" name="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta"<?php echo $internado_diario->idcuenta->EditAttributes() ?>>
<?php
if (is_array($internado_diario->idcuenta->EditValue)) {
	$arwrk = $internado_diario->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado_diario->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado_diario->idcuenta->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado_diario->Lookup_Selecting($internado_diario->idcuenta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" id="s_x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado_diario->idcuenta->ViewAttributes() ?>>
<?php echo $internado_diario->idcuenta->ListViewValue() ?></span>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" id="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($internado_diario->idcuenta->FormValue) ?>">
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" id="o<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($internado_diario->idcuenta->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($internado_diario->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $internado_diario->fecha->CellAttributes() ?>>
<?php if ($internado_diario->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_fecha" class="form-group internado_diario_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" id="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($internado_diario->fecha->PlaceHolder) ?>" value="<?php echo $internado_diario->fecha->EditValue ?>"<?php echo $internado_diario->fecha->EditAttributes() ?>>
<?php if (!$internado_diario->fecha->ReadOnly && !$internado_diario->fecha->Disabled && @$internado_diario->fecha->EditAttrs["readonly"] == "" && @$internado_diario->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("finternado_diariogrid", "x<?php echo $internado_diario_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $internado_diario_grid->RowIndex ?>_fecha" id="o<?php echo $internado_diario_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado_diario->fecha->OldValue) ?>">
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_fecha" class="form-group internado_diario_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" id="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($internado_diario->fecha->PlaceHolder) ?>" value="<?php echo $internado_diario->fecha->EditValue ?>"<?php echo $internado_diario->fecha->EditAttributes() ?>>
<?php if (!$internado_diario->fecha->ReadOnly && !$internado_diario->fecha->Disabled && @$internado_diario->fecha->EditAttrs["readonly"] == "" && @$internado_diario->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("finternado_diariogrid", "x<?php echo $internado_diario_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado_diario->fecha->ViewAttributes() ?>>
<?php echo $internado_diario->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" id="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado_diario->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $internado_diario_grid->RowIndex ?>_fecha" id="o<?php echo $internado_diario_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado_diario->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($internado_diario->costo->Visible) { // costo ?>
		<td data-name="costo"<?php echo $internado_diario->costo->CellAttributes() ?>>
<?php if ($internado_diario->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_costo" class="form-group internado_diario_costo">
<input type="text" data-field="x_costo" name="x<?php echo $internado_diario_grid->RowIndex ?>_costo" id="x<?php echo $internado_diario_grid->RowIndex ?>_costo" size="30" placeholder="<?php echo ew_HtmlEncode($internado_diario->costo->PlaceHolder) ?>" value="<?php echo $internado_diario->costo->EditValue ?>"<?php echo $internado_diario->costo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_costo" name="o<?php echo $internado_diario_grid->RowIndex ?>_costo" id="o<?php echo $internado_diario_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($internado_diario->costo->OldValue) ?>">
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $internado_diario_grid->RowCnt ?>_internado_diario_costo" class="form-group internado_diario_costo">
<input type="text" data-field="x_costo" name="x<?php echo $internado_diario_grid->RowIndex ?>_costo" id="x<?php echo $internado_diario_grid->RowIndex ?>_costo" size="30" placeholder="<?php echo ew_HtmlEncode($internado_diario->costo->PlaceHolder) ?>" value="<?php echo $internado_diario->costo->EditValue ?>"<?php echo $internado_diario->costo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($internado_diario->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado_diario->costo->ViewAttributes() ?>>
<?php echo $internado_diario->costo->ListViewValue() ?></span>
<input type="hidden" data-field="x_costo" name="x<?php echo $internado_diario_grid->RowIndex ?>_costo" id="x<?php echo $internado_diario_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($internado_diario->costo->FormValue) ?>">
<input type="hidden" data-field="x_costo" name="o<?php echo $internado_diario_grid->RowIndex ?>_costo" id="o<?php echo $internado_diario_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($internado_diario->costo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$internado_diario_grid->ListOptions->Render("body", "right", $internado_diario_grid->RowCnt);
?>
	</tr>
<?php if ($internado_diario->RowType == EW_ROWTYPE_ADD || $internado_diario->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
finternado_diariogrid.UpdateOpts(<?php echo $internado_diario_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($internado_diario->CurrentAction <> "gridadd" || $internado_diario->CurrentMode == "copy")
		if (!$internado_diario_grid->Recordset->EOF) $internado_diario_grid->Recordset->MoveNext();
}
?>
<?php
	if ($internado_diario->CurrentMode == "add" || $internado_diario->CurrentMode == "copy" || $internado_diario->CurrentMode == "edit") {
		$internado_diario_grid->RowIndex = '$rowindex$';
		$internado_diario_grid->LoadDefaultValues();

		// Set row properties
		$internado_diario->ResetAttrs();
		$internado_diario->RowAttrs = array_merge($internado_diario->RowAttrs, array('data-rowindex'=>$internado_diario_grid->RowIndex, 'id'=>'r0_internado_diario', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($internado_diario->RowAttrs["class"], "ewTemplate");
		$internado_diario->RowType = EW_ROWTYPE_ADD;

		// Render row
		$internado_diario_grid->RenderRow();

		// Render list options
		$internado_diario_grid->RenderListOptions();
		$internado_diario_grid->StartRowCnt = 0;
?>
	<tr<?php echo $internado_diario->RowAttributes() ?>>
<?php

// Render list options (body, left)
$internado_diario_grid->ListOptions->Render("body", "left", $internado_diario_grid->RowIndex);
?>
	<?php if ($internado_diario->idinternado_diario->Visible) { // idinternado_diario ?>
		<td>
<?php if ($internado_diario->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_internado_diario_idinternado_diario" class="form-group internado_diario_idinternado_diario">
<span<?php echo $internado_diario->idinternado_diario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idinternado_diario->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idinternado_diario" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" value="<?php echo ew_HtmlEncode($internado_diario->idinternado_diario->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idinternado_diario" name="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" id="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado_diario" value="<?php echo ew_HtmlEncode($internado_diario->idinternado_diario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado_diario->idinternado->Visible) { // idinternado ?>
		<td>
<?php if ($internado_diario->CurrentAction <> "F") { ?>
<?php if ($internado_diario->idinternado->getSessionValue() <> "") { ?>
<span id="el$rowindex$_internado_diario_idinternado" class="form-group internado_diario_idinternado">
<span<?php echo $internado_diario->idinternado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idinternado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado_diario->idinternado->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_internado_diario_idinternado" class="form-group internado_diario_idinternado">
<select data-field="x_idinternado" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado"<?php echo $internado_diario->idinternado->EditAttributes() ?>>
<?php
if (is_array($internado_diario->idinternado->EditValue)) {
	$arwrk = $internado_diario->idinternado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado_diario->idinternado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado_diario->idinternado->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idinternado`, `idinternado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `internado`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado_diario->Lookup_Selecting($internado_diario->idinternado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" id="s_x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idinternado` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_internado_diario_idinternado" class="form-group internado_diario_idinternado">
<span<?php echo $internado_diario->idinternado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idinternado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idinternado" name="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" id="x<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado_diario->idinternado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idinternado" name="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado" id="o<?php echo $internado_diario_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado_diario->idinternado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado_diario->idcuenta->Visible) { // idcuenta ?>
		<td>
<?php if ($internado_diario->CurrentAction <> "F") { ?>
<?php if ($internado_diario->idcuenta->getSessionValue() <> "") { ?>
<span id="el$rowindex$_internado_diario_idcuenta" class="form-group internado_diario_idcuenta">
<span<?php echo $internado_diario->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" name="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($internado_diario->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_internado_diario_idcuenta" class="form-group internado_diario_idcuenta">
<select data-field="x_idcuenta" id="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" name="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta"<?php echo $internado_diario->idcuenta->EditAttributes() ?>>
<?php
if (is_array($internado_diario->idcuenta->EditValue)) {
	$arwrk = $internado_diario->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado_diario->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado_diario->idcuenta->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado_diario->Lookup_Selecting($internado_diario->idcuenta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" id="s_x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_internado_diario_idcuenta" class="form-group internado_diario_idcuenta">
<span<?php echo $internado_diario->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" id="x<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($internado_diario->idcuenta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" id="o<?php echo $internado_diario_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($internado_diario->idcuenta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado_diario->fecha->Visible) { // fecha ?>
		<td>
<?php if ($internado_diario->CurrentAction <> "F") { ?>
<span id="el$rowindex$_internado_diario_fecha" class="form-group internado_diario_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" id="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($internado_diario->fecha->PlaceHolder) ?>" value="<?php echo $internado_diario->fecha->EditValue ?>"<?php echo $internado_diario->fecha->EditAttributes() ?>>
<?php if (!$internado_diario->fecha->ReadOnly && !$internado_diario->fecha->Disabled && @$internado_diario->fecha->EditAttrs["readonly"] == "" && @$internado_diario->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("finternado_diariogrid", "x<?php echo $internado_diario_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_internado_diario_fecha" class="form-group internado_diario_fecha">
<span<?php echo $internado_diario->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" id="x<?php echo $internado_diario_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado_diario->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $internado_diario_grid->RowIndex ?>_fecha" id="o<?php echo $internado_diario_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado_diario->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado_diario->costo->Visible) { // costo ?>
		<td>
<?php if ($internado_diario->CurrentAction <> "F") { ?>
<span id="el$rowindex$_internado_diario_costo" class="form-group internado_diario_costo">
<input type="text" data-field="x_costo" name="x<?php echo $internado_diario_grid->RowIndex ?>_costo" id="x<?php echo $internado_diario_grid->RowIndex ?>_costo" size="30" placeholder="<?php echo ew_HtmlEncode($internado_diario->costo->PlaceHolder) ?>" value="<?php echo $internado_diario->costo->EditValue ?>"<?php echo $internado_diario->costo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_internado_diario_costo" class="form-group internado_diario_costo">
<span<?php echo $internado_diario->costo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado_diario->costo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_costo" name="x<?php echo $internado_diario_grid->RowIndex ?>_costo" id="x<?php echo $internado_diario_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($internado_diario->costo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_costo" name="o<?php echo $internado_diario_grid->RowIndex ?>_costo" id="o<?php echo $internado_diario_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($internado_diario->costo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$internado_diario_grid->ListOptions->Render("body", "right", $internado_diario_grid->RowCnt);
?>
<script type="text/javascript">
finternado_diariogrid.UpdateOpts(<?php echo $internado_diario_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($internado_diario->CurrentMode == "add" || $internado_diario->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $internado_diario_grid->FormKeyCountName ?>" id="<?php echo $internado_diario_grid->FormKeyCountName ?>" value="<?php echo $internado_diario_grid->KeyCount ?>">
<?php echo $internado_diario_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($internado_diario->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $internado_diario_grid->FormKeyCountName ?>" id="<?php echo $internado_diario_grid->FormKeyCountName ?>" value="<?php echo $internado_diario_grid->KeyCount ?>">
<?php echo $internado_diario_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($internado_diario->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="finternado_diariogrid">
</div>
<?php

// Close recordset
if ($internado_diario_grid->Recordset)
	$internado_diario_grid->Recordset->Close();
?>
<?php if ($internado_diario_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($internado_diario_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($internado_diario_grid->TotalRecs == 0 && $internado_diario->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($internado_diario_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($internado_diario->Export == "") { ?>
<script type="text/javascript">
finternado_diariogrid.Init();
</script>
<?php } ?>
<?php
$internado_diario_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$internado_diario_grid->Page_Terminate();
?>

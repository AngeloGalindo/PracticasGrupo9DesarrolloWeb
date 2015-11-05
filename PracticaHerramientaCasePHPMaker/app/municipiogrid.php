<?php

// Create page object
if (!isset($municipio_grid)) $municipio_grid = new cmunicipio_grid();

// Page init
$municipio_grid->Page_Init();

// Page main
$municipio_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$municipio_grid->Page_Render();
?>
<?php if ($municipio->Export == "") { ?>
<script type="text/javascript">

// Page object
var municipio_grid = new ew_Page("municipio_grid");
municipio_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = municipio_grid.PageID; // For backward compatibility

// Form object
var fmunicipiogrid = new ew_Form("fmunicipiogrid");
fmunicipiogrid.FormKeyCountName = '<?php echo $municipio_grid->FormKeyCountName ?>';

// Validate form
fmunicipiogrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $municipio->estado->FldCaption(), $municipio->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_iddepartamento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $municipio->iddepartamento->FldCaption(), $municipio->iddepartamento->ReqErrMsg)) ?>");

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
fmunicipiogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "iddepartamento", false)) return false;
	return true;
}

// Form_CustomValidate event
fmunicipiogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmunicipiogrid.ValidateRequired = true;
<?php } else { ?>
fmunicipiogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmunicipiogrid.Lists["x_iddepartamento"] = {"LinkField":"x_iddepartamento","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($municipio->CurrentAction == "gridadd") {
	if ($municipio->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$municipio_grid->TotalRecs = $municipio->SelectRecordCount();
			$municipio_grid->Recordset = $municipio_grid->LoadRecordset($municipio_grid->StartRec-1, $municipio_grid->DisplayRecs);
		} else {
			if ($municipio_grid->Recordset = $municipio_grid->LoadRecordset())
				$municipio_grid->TotalRecs = $municipio_grid->Recordset->RecordCount();
		}
		$municipio_grid->StartRec = 1;
		$municipio_grid->DisplayRecs = $municipio_grid->TotalRecs;
	} else {
		$municipio->CurrentFilter = "0=1";
		$municipio_grid->StartRec = 1;
		$municipio_grid->DisplayRecs = $municipio->GridAddRowCount;
	}
	$municipio_grid->TotalRecs = $municipio_grid->DisplayRecs;
	$municipio_grid->StopRec = $municipio_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$municipio_grid->TotalRecs = $municipio->SelectRecordCount();
	} else {
		if ($municipio_grid->Recordset = $municipio_grid->LoadRecordset())
			$municipio_grid->TotalRecs = $municipio_grid->Recordset->RecordCount();
	}
	$municipio_grid->StartRec = 1;
	$municipio_grid->DisplayRecs = $municipio_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$municipio_grid->Recordset = $municipio_grid->LoadRecordset($municipio_grid->StartRec-1, $municipio_grid->DisplayRecs);

	// Set no record found message
	if ($municipio->CurrentAction == "" && $municipio_grid->TotalRecs == 0) {
		if ($municipio_grid->SearchWhere == "0=101")
			$municipio_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$municipio_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$municipio_grid->RenderOtherOptions();
?>
<?php $municipio_grid->ShowPageHeader(); ?>
<?php
$municipio_grid->ShowMessage();
?>
<?php if ($municipio_grid->TotalRecs > 0 || $municipio->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fmunicipiogrid" class="ewForm form-inline">
<div id="gmp_municipio" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_municipiogrid" class="table ewTable">
<?php echo $municipio->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$municipio_grid->RenderListOptions();

// Render list options (header, left)
$municipio_grid->ListOptions->Render("header", "left");
?>
<?php if ($municipio->nombre->Visible) { // nombre ?>
	<?php if ($municipio->SortUrl($municipio->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_municipio_nombre" class="municipio_nombre"><div class="ewTableHeaderCaption"><?php echo $municipio->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div><div id="elh_municipio_nombre" class="municipio_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $municipio->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($municipio->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($municipio->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($municipio->estado->Visible) { // estado ?>
	<?php if ($municipio->SortUrl($municipio->estado) == "") { ?>
		<th data-name="estado"><div id="elh_municipio_estado" class="municipio_estado"><div class="ewTableHeaderCaption"><?php echo $municipio->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_municipio_estado" class="municipio_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $municipio->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($municipio->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($municipio->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($municipio->iddepartamento->Visible) { // iddepartamento ?>
	<?php if ($municipio->SortUrl($municipio->iddepartamento) == "") { ?>
		<th data-name="iddepartamento"><div id="elh_municipio_iddepartamento" class="municipio_iddepartamento"><div class="ewTableHeaderCaption"><?php echo $municipio->iddepartamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iddepartamento"><div><div id="elh_municipio_iddepartamento" class="municipio_iddepartamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $municipio->iddepartamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($municipio->iddepartamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($municipio->iddepartamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$municipio_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$municipio_grid->StartRec = 1;
$municipio_grid->StopRec = $municipio_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($municipio_grid->FormKeyCountName) && ($municipio->CurrentAction == "gridadd" || $municipio->CurrentAction == "gridedit" || $municipio->CurrentAction == "F")) {
		$municipio_grid->KeyCount = $objForm->GetValue($municipio_grid->FormKeyCountName);
		$municipio_grid->StopRec = $municipio_grid->StartRec + $municipio_grid->KeyCount - 1;
	}
}
$municipio_grid->RecCnt = $municipio_grid->StartRec - 1;
if ($municipio_grid->Recordset && !$municipio_grid->Recordset->EOF) {
	$municipio_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $municipio_grid->StartRec > 1)
		$municipio_grid->Recordset->Move($municipio_grid->StartRec - 1);
} elseif (!$municipio->AllowAddDeleteRow && $municipio_grid->StopRec == 0) {
	$municipio_grid->StopRec = $municipio->GridAddRowCount;
}

// Initialize aggregate
$municipio->RowType = EW_ROWTYPE_AGGREGATEINIT;
$municipio->ResetAttrs();
$municipio_grid->RenderRow();
if ($municipio->CurrentAction == "gridadd")
	$municipio_grid->RowIndex = 0;
if ($municipio->CurrentAction == "gridedit")
	$municipio_grid->RowIndex = 0;
while ($municipio_grid->RecCnt < $municipio_grid->StopRec) {
	$municipio_grid->RecCnt++;
	if (intval($municipio_grid->RecCnt) >= intval($municipio_grid->StartRec)) {
		$municipio_grid->RowCnt++;
		if ($municipio->CurrentAction == "gridadd" || $municipio->CurrentAction == "gridedit" || $municipio->CurrentAction == "F") {
			$municipio_grid->RowIndex++;
			$objForm->Index = $municipio_grid->RowIndex;
			if ($objForm->HasValue($municipio_grid->FormActionName))
				$municipio_grid->RowAction = strval($objForm->GetValue($municipio_grid->FormActionName));
			elseif ($municipio->CurrentAction == "gridadd")
				$municipio_grid->RowAction = "insert";
			else
				$municipio_grid->RowAction = "";
		}

		// Set up key count
		$municipio_grid->KeyCount = $municipio_grid->RowIndex;

		// Init row class and style
		$municipio->ResetAttrs();
		$municipio->CssClass = "";
		if ($municipio->CurrentAction == "gridadd") {
			if ($municipio->CurrentMode == "copy") {
				$municipio_grid->LoadRowValues($municipio_grid->Recordset); // Load row values
				$municipio_grid->SetRecordKey($municipio_grid->RowOldKey, $municipio_grid->Recordset); // Set old record key
			} else {
				$municipio_grid->LoadDefaultValues(); // Load default values
				$municipio_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$municipio_grid->LoadRowValues($municipio_grid->Recordset); // Load row values
		}
		$municipio->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($municipio->CurrentAction == "gridadd") // Grid add
			$municipio->RowType = EW_ROWTYPE_ADD; // Render add
		if ($municipio->CurrentAction == "gridadd" && $municipio->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$municipio_grid->RestoreCurrentRowFormValues($municipio_grid->RowIndex); // Restore form values
		if ($municipio->CurrentAction == "gridedit") { // Grid edit
			if ($municipio->EventCancelled) {
				$municipio_grid->RestoreCurrentRowFormValues($municipio_grid->RowIndex); // Restore form values
			}
			if ($municipio_grid->RowAction == "insert")
				$municipio->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$municipio->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($municipio->CurrentAction == "gridedit" && ($municipio->RowType == EW_ROWTYPE_EDIT || $municipio->RowType == EW_ROWTYPE_ADD) && $municipio->EventCancelled) // Update failed
			$municipio_grid->RestoreCurrentRowFormValues($municipio_grid->RowIndex); // Restore form values
		if ($municipio->RowType == EW_ROWTYPE_EDIT) // Edit row
			$municipio_grid->EditRowCnt++;
		if ($municipio->CurrentAction == "F") // Confirm row
			$municipio_grid->RestoreCurrentRowFormValues($municipio_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$municipio->RowAttrs = array_merge($municipio->RowAttrs, array('data-rowindex'=>$municipio_grid->RowCnt, 'id'=>'r' . $municipio_grid->RowCnt . '_municipio', 'data-rowtype'=>$municipio->RowType));

		// Render row
		$municipio_grid->RenderRow();

		// Render list options
		$municipio_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($municipio_grid->RowAction <> "delete" && $municipio_grid->RowAction <> "insertdelete" && !($municipio_grid->RowAction == "insert" && $municipio->CurrentAction == "F" && $municipio_grid->EmptyRow())) {
?>
	<tr<?php echo $municipio->RowAttributes() ?>>
<?php

// Render list options (body, left)
$municipio_grid->ListOptions->Render("body", "left", $municipio_grid->RowCnt);
?>
	<?php if ($municipio->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $municipio->nombre->CellAttributes() ?>>
<?php if ($municipio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $municipio_grid->RowCnt ?>_municipio_nombre" class="form-group municipio_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $municipio_grid->RowIndex ?>_nombre" id="x<?php echo $municipio_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($municipio->nombre->PlaceHolder) ?>" value="<?php echo $municipio->nombre->EditValue ?>"<?php echo $municipio->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nombre" name="o<?php echo $municipio_grid->RowIndex ?>_nombre" id="o<?php echo $municipio_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($municipio->nombre->OldValue) ?>">
<?php } ?>
<?php if ($municipio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $municipio_grid->RowCnt ?>_municipio_nombre" class="form-group municipio_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $municipio_grid->RowIndex ?>_nombre" id="x<?php echo $municipio_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($municipio->nombre->PlaceHolder) ?>" value="<?php echo $municipio->nombre->EditValue ?>"<?php echo $municipio->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($municipio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $municipio->nombre->ViewAttributes() ?>>
<?php echo $municipio->nombre->ListViewValue() ?></span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $municipio_grid->RowIndex ?>_nombre" id="x<?php echo $municipio_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($municipio->nombre->FormValue) ?>">
<input type="hidden" data-field="x_nombre" name="o<?php echo $municipio_grid->RowIndex ?>_nombre" id="o<?php echo $municipio_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($municipio->nombre->OldValue) ?>">
<?php } ?>
<a id="<?php echo $municipio_grid->PageObjName . "_row_" . $municipio_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($municipio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idmunicipio" name="x<?php echo $municipio_grid->RowIndex ?>_idmunicipio" id="x<?php echo $municipio_grid->RowIndex ?>_idmunicipio" value="<?php echo ew_HtmlEncode($municipio->idmunicipio->CurrentValue) ?>">
<input type="hidden" data-field="x_idmunicipio" name="o<?php echo $municipio_grid->RowIndex ?>_idmunicipio" id="o<?php echo $municipio_grid->RowIndex ?>_idmunicipio" value="<?php echo ew_HtmlEncode($municipio->idmunicipio->OldValue) ?>">
<?php } ?>
<?php if ($municipio->RowType == EW_ROWTYPE_EDIT || $municipio->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_idmunicipio" name="x<?php echo $municipio_grid->RowIndex ?>_idmunicipio" id="x<?php echo $municipio_grid->RowIndex ?>_idmunicipio" value="<?php echo ew_HtmlEncode($municipio->idmunicipio->CurrentValue) ?>">
<?php } ?>
	<?php if ($municipio->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $municipio->estado->CellAttributes() ?>>
<?php if ($municipio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $municipio_grid->RowCnt ?>_municipio_estado" class="form-group municipio_estado">
<select data-field="x_estado" id="x<?php echo $municipio_grid->RowIndex ?>_estado" name="x<?php echo $municipio_grid->RowIndex ?>_estado"<?php echo $municipio->estado->EditAttributes() ?>>
<?php
if (is_array($municipio->estado->EditValue)) {
	$arwrk = $municipio->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($municipio->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $municipio->estado->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $municipio_grid->RowIndex ?>_estado" id="o<?php echo $municipio_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($municipio->estado->OldValue) ?>">
<?php } ?>
<?php if ($municipio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $municipio_grid->RowCnt ?>_municipio_estado" class="form-group municipio_estado">
<select data-field="x_estado" id="x<?php echo $municipio_grid->RowIndex ?>_estado" name="x<?php echo $municipio_grid->RowIndex ?>_estado"<?php echo $municipio->estado->EditAttributes() ?>>
<?php
if (is_array($municipio->estado->EditValue)) {
	$arwrk = $municipio->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($municipio->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $municipio->estado->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($municipio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $municipio->estado->ViewAttributes() ?>>
<?php echo $municipio->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $municipio_grid->RowIndex ?>_estado" id="x<?php echo $municipio_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($municipio->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $municipio_grid->RowIndex ?>_estado" id="o<?php echo $municipio_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($municipio->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($municipio->iddepartamento->Visible) { // iddepartamento ?>
		<td data-name="iddepartamento"<?php echo $municipio->iddepartamento->CellAttributes() ?>>
<?php if ($municipio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($municipio->iddepartamento->getSessionValue() <> "") { ?>
<span id="el<?php echo $municipio_grid->RowCnt ?>_municipio_iddepartamento" class="form-group municipio_iddepartamento">
<span<?php echo $municipio->iddepartamento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $municipio->iddepartamento->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" name="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($municipio->iddepartamento->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $municipio_grid->RowCnt ?>_municipio_iddepartamento" class="form-group municipio_iddepartamento">
<select data-field="x_iddepartamento" id="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" name="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento"<?php echo $municipio->iddepartamento->EditAttributes() ?>>
<?php
if (is_array($municipio->iddepartamento->EditValue)) {
	$arwrk = $municipio->iddepartamento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($municipio->iddepartamento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $municipio->iddepartamento->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddepartamento`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $municipio->Lookup_Selecting($municipio->iddepartamento, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" id="s_x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddepartamento` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_iddepartamento" name="o<?php echo $municipio_grid->RowIndex ?>_iddepartamento" id="o<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($municipio->iddepartamento->OldValue) ?>">
<?php } ?>
<?php if ($municipio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($municipio->iddepartamento->getSessionValue() <> "") { ?>
<span id="el<?php echo $municipio_grid->RowCnt ?>_municipio_iddepartamento" class="form-group municipio_iddepartamento">
<span<?php echo $municipio->iddepartamento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $municipio->iddepartamento->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" name="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($municipio->iddepartamento->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $municipio_grid->RowCnt ?>_municipio_iddepartamento" class="form-group municipio_iddepartamento">
<select data-field="x_iddepartamento" id="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" name="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento"<?php echo $municipio->iddepartamento->EditAttributes() ?>>
<?php
if (is_array($municipio->iddepartamento->EditValue)) {
	$arwrk = $municipio->iddepartamento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($municipio->iddepartamento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $municipio->iddepartamento->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddepartamento`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $municipio->Lookup_Selecting($municipio->iddepartamento, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" id="s_x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddepartamento` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($municipio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $municipio->iddepartamento->ViewAttributes() ?>>
<?php echo $municipio->iddepartamento->ListViewValue() ?></span>
<input type="hidden" data-field="x_iddepartamento" name="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" id="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($municipio->iddepartamento->FormValue) ?>">
<input type="hidden" data-field="x_iddepartamento" name="o<?php echo $municipio_grid->RowIndex ?>_iddepartamento" id="o<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($municipio->iddepartamento->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$municipio_grid->ListOptions->Render("body", "right", $municipio_grid->RowCnt);
?>
	</tr>
<?php if ($municipio->RowType == EW_ROWTYPE_ADD || $municipio->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fmunicipiogrid.UpdateOpts(<?php echo $municipio_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($municipio->CurrentAction <> "gridadd" || $municipio->CurrentMode == "copy")
		if (!$municipio_grid->Recordset->EOF) $municipio_grid->Recordset->MoveNext();
}
?>
<?php
	if ($municipio->CurrentMode == "add" || $municipio->CurrentMode == "copy" || $municipio->CurrentMode == "edit") {
		$municipio_grid->RowIndex = '$rowindex$';
		$municipio_grid->LoadDefaultValues();

		// Set row properties
		$municipio->ResetAttrs();
		$municipio->RowAttrs = array_merge($municipio->RowAttrs, array('data-rowindex'=>$municipio_grid->RowIndex, 'id'=>'r0_municipio', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($municipio->RowAttrs["class"], "ewTemplate");
		$municipio->RowType = EW_ROWTYPE_ADD;

		// Render row
		$municipio_grid->RenderRow();

		// Render list options
		$municipio_grid->RenderListOptions();
		$municipio_grid->StartRowCnt = 0;
?>
	<tr<?php echo $municipio->RowAttributes() ?>>
<?php

// Render list options (body, left)
$municipio_grid->ListOptions->Render("body", "left", $municipio_grid->RowIndex);
?>
	<?php if ($municipio->nombre->Visible) { // nombre ?>
		<td>
<?php if ($municipio->CurrentAction <> "F") { ?>
<span id="el$rowindex$_municipio_nombre" class="form-group municipio_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $municipio_grid->RowIndex ?>_nombre" id="x<?php echo $municipio_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($municipio->nombre->PlaceHolder) ?>" value="<?php echo $municipio->nombre->EditValue ?>"<?php echo $municipio->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_municipio_nombre" class="form-group municipio_nombre">
<span<?php echo $municipio->nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $municipio->nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $municipio_grid->RowIndex ?>_nombre" id="x<?php echo $municipio_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($municipio->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nombre" name="o<?php echo $municipio_grid->RowIndex ?>_nombre" id="o<?php echo $municipio_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($municipio->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($municipio->estado->Visible) { // estado ?>
		<td>
<?php if ($municipio->CurrentAction <> "F") { ?>
<span id="el$rowindex$_municipio_estado" class="form-group municipio_estado">
<select data-field="x_estado" id="x<?php echo $municipio_grid->RowIndex ?>_estado" name="x<?php echo $municipio_grid->RowIndex ?>_estado"<?php echo $municipio->estado->EditAttributes() ?>>
<?php
if (is_array($municipio->estado->EditValue)) {
	$arwrk = $municipio->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($municipio->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $municipio->estado->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_municipio_estado" class="form-group municipio_estado">
<span<?php echo $municipio->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $municipio->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $municipio_grid->RowIndex ?>_estado" id="x<?php echo $municipio_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($municipio->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $municipio_grid->RowIndex ?>_estado" id="o<?php echo $municipio_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($municipio->estado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($municipio->iddepartamento->Visible) { // iddepartamento ?>
		<td>
<?php if ($municipio->CurrentAction <> "F") { ?>
<?php if ($municipio->iddepartamento->getSessionValue() <> "") { ?>
<span id="el$rowindex$_municipio_iddepartamento" class="form-group municipio_iddepartamento">
<span<?php echo $municipio->iddepartamento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $municipio->iddepartamento->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" name="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($municipio->iddepartamento->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_municipio_iddepartamento" class="form-group municipio_iddepartamento">
<select data-field="x_iddepartamento" id="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" name="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento"<?php echo $municipio->iddepartamento->EditAttributes() ?>>
<?php
if (is_array($municipio->iddepartamento->EditValue)) {
	$arwrk = $municipio->iddepartamento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($municipio->iddepartamento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $municipio->iddepartamento->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddepartamento`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $municipio->Lookup_Selecting($municipio->iddepartamento, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" id="s_x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddepartamento` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_municipio_iddepartamento" class="form-group municipio_iddepartamento">
<span<?php echo $municipio->iddepartamento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $municipio->iddepartamento->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iddepartamento" name="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" id="x<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($municipio->iddepartamento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iddepartamento" name="o<?php echo $municipio_grid->RowIndex ?>_iddepartamento" id="o<?php echo $municipio_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($municipio->iddepartamento->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$municipio_grid->ListOptions->Render("body", "right", $municipio_grid->RowCnt);
?>
<script type="text/javascript">
fmunicipiogrid.UpdateOpts(<?php echo $municipio_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($municipio->CurrentMode == "add" || $municipio->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $municipio_grid->FormKeyCountName ?>" id="<?php echo $municipio_grid->FormKeyCountName ?>" value="<?php echo $municipio_grid->KeyCount ?>">
<?php echo $municipio_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($municipio->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $municipio_grid->FormKeyCountName ?>" id="<?php echo $municipio_grid->FormKeyCountName ?>" value="<?php echo $municipio_grid->KeyCount ?>">
<?php echo $municipio_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($municipio->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fmunicipiogrid">
</div>
<?php

// Close recordset
if ($municipio_grid->Recordset)
	$municipio_grid->Recordset->Close();
?>
<?php if ($municipio_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($municipio_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($municipio_grid->TotalRecs == 0 && $municipio->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($municipio_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($municipio->Export == "") { ?>
<script type="text/javascript">
fmunicipiogrid.Init();
</script>
<?php } ?>
<?php
$municipio_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$municipio_grid->Page_Terminate();
?>

<?php

// Create page object
if (!isset($departamento_grid)) $departamento_grid = new cdepartamento_grid();

// Page init
$departamento_grid->Page_Init();

// Page main
$departamento_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$departamento_grid->Page_Render();
?>
<?php if ($departamento->Export == "") { ?>
<script type="text/javascript">

// Page object
var departamento_grid = new ew_Page("departamento_grid");
departamento_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = departamento_grid.PageID; // For backward compatibility

// Form object
var fdepartamentogrid = new ew_Form("fdepartamentogrid");
fdepartamentogrid.FormKeyCountName = '<?php echo $departamento_grid->FormKeyCountName ?>';

// Validate form
fdepartamentogrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $departamento->estado->FldCaption(), $departamento->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idpais");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $departamento->idpais->FldCaption(), $departamento->idpais->ReqErrMsg)) ?>");

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
fdepartamentogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idpais", false)) return false;
	return true;
}

// Form_CustomValidate event
fdepartamentogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdepartamentogrid.ValidateRequired = true;
<?php } else { ?>
fdepartamentogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdepartamentogrid.Lists["x_idpais"] = {"LinkField":"x_idpais","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($departamento->CurrentAction == "gridadd") {
	if ($departamento->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$departamento_grid->TotalRecs = $departamento->SelectRecordCount();
			$departamento_grid->Recordset = $departamento_grid->LoadRecordset($departamento_grid->StartRec-1, $departamento_grid->DisplayRecs);
		} else {
			if ($departamento_grid->Recordset = $departamento_grid->LoadRecordset())
				$departamento_grid->TotalRecs = $departamento_grid->Recordset->RecordCount();
		}
		$departamento_grid->StartRec = 1;
		$departamento_grid->DisplayRecs = $departamento_grid->TotalRecs;
	} else {
		$departamento->CurrentFilter = "0=1";
		$departamento_grid->StartRec = 1;
		$departamento_grid->DisplayRecs = $departamento->GridAddRowCount;
	}
	$departamento_grid->TotalRecs = $departamento_grid->DisplayRecs;
	$departamento_grid->StopRec = $departamento_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$departamento_grid->TotalRecs = $departamento->SelectRecordCount();
	} else {
		if ($departamento_grid->Recordset = $departamento_grid->LoadRecordset())
			$departamento_grid->TotalRecs = $departamento_grid->Recordset->RecordCount();
	}
	$departamento_grid->StartRec = 1;
	$departamento_grid->DisplayRecs = $departamento_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$departamento_grid->Recordset = $departamento_grid->LoadRecordset($departamento_grid->StartRec-1, $departamento_grid->DisplayRecs);

	// Set no record found message
	if ($departamento->CurrentAction == "" && $departamento_grid->TotalRecs == 0) {
		if ($departamento_grid->SearchWhere == "0=101")
			$departamento_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$departamento_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$departamento_grid->RenderOtherOptions();
?>
<?php $departamento_grid->ShowPageHeader(); ?>
<?php
$departamento_grid->ShowMessage();
?>
<?php if ($departamento_grid->TotalRecs > 0 || $departamento->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdepartamentogrid" class="ewForm form-inline">
<div id="gmp_departamento" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_departamentogrid" class="table ewTable">
<?php echo $departamento->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$departamento_grid->RenderListOptions();

// Render list options (header, left)
$departamento_grid->ListOptions->Render("header", "left");
?>
<?php if ($departamento->nombre->Visible) { // nombre ?>
	<?php if ($departamento->SortUrl($departamento->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_departamento_nombre" class="departamento_nombre"><div class="ewTableHeaderCaption"><?php echo $departamento->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div><div id="elh_departamento_nombre" class="departamento_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $departamento->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($departamento->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($departamento->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($departamento->estado->Visible) { // estado ?>
	<?php if ($departamento->SortUrl($departamento->estado) == "") { ?>
		<th data-name="estado"><div id="elh_departamento_estado" class="departamento_estado"><div class="ewTableHeaderCaption"><?php echo $departamento->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_departamento_estado" class="departamento_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $departamento->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($departamento->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($departamento->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($departamento->idpais->Visible) { // idpais ?>
	<?php if ($departamento->SortUrl($departamento->idpais) == "") { ?>
		<th data-name="idpais"><div id="elh_departamento_idpais" class="departamento_idpais"><div class="ewTableHeaderCaption"><?php echo $departamento->idpais->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idpais"><div><div id="elh_departamento_idpais" class="departamento_idpais">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $departamento->idpais->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($departamento->idpais->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($departamento->idpais->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$departamento_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$departamento_grid->StartRec = 1;
$departamento_grid->StopRec = $departamento_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($departamento_grid->FormKeyCountName) && ($departamento->CurrentAction == "gridadd" || $departamento->CurrentAction == "gridedit" || $departamento->CurrentAction == "F")) {
		$departamento_grid->KeyCount = $objForm->GetValue($departamento_grid->FormKeyCountName);
		$departamento_grid->StopRec = $departamento_grid->StartRec + $departamento_grid->KeyCount - 1;
	}
}
$departamento_grid->RecCnt = $departamento_grid->StartRec - 1;
if ($departamento_grid->Recordset && !$departamento_grid->Recordset->EOF) {
	$departamento_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $departamento_grid->StartRec > 1)
		$departamento_grid->Recordset->Move($departamento_grid->StartRec - 1);
} elseif (!$departamento->AllowAddDeleteRow && $departamento_grid->StopRec == 0) {
	$departamento_grid->StopRec = $departamento->GridAddRowCount;
}

// Initialize aggregate
$departamento->RowType = EW_ROWTYPE_AGGREGATEINIT;
$departamento->ResetAttrs();
$departamento_grid->RenderRow();
if ($departamento->CurrentAction == "gridadd")
	$departamento_grid->RowIndex = 0;
if ($departamento->CurrentAction == "gridedit")
	$departamento_grid->RowIndex = 0;
while ($departamento_grid->RecCnt < $departamento_grid->StopRec) {
	$departamento_grid->RecCnt++;
	if (intval($departamento_grid->RecCnt) >= intval($departamento_grid->StartRec)) {
		$departamento_grid->RowCnt++;
		if ($departamento->CurrentAction == "gridadd" || $departamento->CurrentAction == "gridedit" || $departamento->CurrentAction == "F") {
			$departamento_grid->RowIndex++;
			$objForm->Index = $departamento_grid->RowIndex;
			if ($objForm->HasValue($departamento_grid->FormActionName))
				$departamento_grid->RowAction = strval($objForm->GetValue($departamento_grid->FormActionName));
			elseif ($departamento->CurrentAction == "gridadd")
				$departamento_grid->RowAction = "insert";
			else
				$departamento_grid->RowAction = "";
		}

		// Set up key count
		$departamento_grid->KeyCount = $departamento_grid->RowIndex;

		// Init row class and style
		$departamento->ResetAttrs();
		$departamento->CssClass = "";
		if ($departamento->CurrentAction == "gridadd") {
			if ($departamento->CurrentMode == "copy") {
				$departamento_grid->LoadRowValues($departamento_grid->Recordset); // Load row values
				$departamento_grid->SetRecordKey($departamento_grid->RowOldKey, $departamento_grid->Recordset); // Set old record key
			} else {
				$departamento_grid->LoadDefaultValues(); // Load default values
				$departamento_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$departamento_grid->LoadRowValues($departamento_grid->Recordset); // Load row values
		}
		$departamento->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($departamento->CurrentAction == "gridadd") // Grid add
			$departamento->RowType = EW_ROWTYPE_ADD; // Render add
		if ($departamento->CurrentAction == "gridadd" && $departamento->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$departamento_grid->RestoreCurrentRowFormValues($departamento_grid->RowIndex); // Restore form values
		if ($departamento->CurrentAction == "gridedit") { // Grid edit
			if ($departamento->EventCancelled) {
				$departamento_grid->RestoreCurrentRowFormValues($departamento_grid->RowIndex); // Restore form values
			}
			if ($departamento_grid->RowAction == "insert")
				$departamento->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$departamento->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($departamento->CurrentAction == "gridedit" && ($departamento->RowType == EW_ROWTYPE_EDIT || $departamento->RowType == EW_ROWTYPE_ADD) && $departamento->EventCancelled) // Update failed
			$departamento_grid->RestoreCurrentRowFormValues($departamento_grid->RowIndex); // Restore form values
		if ($departamento->RowType == EW_ROWTYPE_EDIT) // Edit row
			$departamento_grid->EditRowCnt++;
		if ($departamento->CurrentAction == "F") // Confirm row
			$departamento_grid->RestoreCurrentRowFormValues($departamento_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$departamento->RowAttrs = array_merge($departamento->RowAttrs, array('data-rowindex'=>$departamento_grid->RowCnt, 'id'=>'r' . $departamento_grid->RowCnt . '_departamento', 'data-rowtype'=>$departamento->RowType));

		// Render row
		$departamento_grid->RenderRow();

		// Render list options
		$departamento_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($departamento_grid->RowAction <> "delete" && $departamento_grid->RowAction <> "insertdelete" && !($departamento_grid->RowAction == "insert" && $departamento->CurrentAction == "F" && $departamento_grid->EmptyRow())) {
?>
	<tr<?php echo $departamento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$departamento_grid->ListOptions->Render("body", "left", $departamento_grid->RowCnt);
?>
	<?php if ($departamento->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $departamento->nombre->CellAttributes() ?>>
<?php if ($departamento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $departamento_grid->RowCnt ?>_departamento_nombre" class="form-group departamento_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $departamento_grid->RowIndex ?>_nombre" id="x<?php echo $departamento_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($departamento->nombre->PlaceHolder) ?>" value="<?php echo $departamento->nombre->EditValue ?>"<?php echo $departamento->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nombre" name="o<?php echo $departamento_grid->RowIndex ?>_nombre" id="o<?php echo $departamento_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($departamento->nombre->OldValue) ?>">
<?php } ?>
<?php if ($departamento->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $departamento_grid->RowCnt ?>_departamento_nombre" class="form-group departamento_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $departamento_grid->RowIndex ?>_nombre" id="x<?php echo $departamento_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($departamento->nombre->PlaceHolder) ?>" value="<?php echo $departamento->nombre->EditValue ?>"<?php echo $departamento->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($departamento->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $departamento->nombre->ViewAttributes() ?>>
<?php echo $departamento->nombre->ListViewValue() ?></span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $departamento_grid->RowIndex ?>_nombre" id="x<?php echo $departamento_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($departamento->nombre->FormValue) ?>">
<input type="hidden" data-field="x_nombre" name="o<?php echo $departamento_grid->RowIndex ?>_nombre" id="o<?php echo $departamento_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($departamento->nombre->OldValue) ?>">
<?php } ?>
<a id="<?php echo $departamento_grid->PageObjName . "_row_" . $departamento_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($departamento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_iddepartamento" name="x<?php echo $departamento_grid->RowIndex ?>_iddepartamento" id="x<?php echo $departamento_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($departamento->iddepartamento->CurrentValue) ?>">
<input type="hidden" data-field="x_iddepartamento" name="o<?php echo $departamento_grid->RowIndex ?>_iddepartamento" id="o<?php echo $departamento_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($departamento->iddepartamento->OldValue) ?>">
<?php } ?>
<?php if ($departamento->RowType == EW_ROWTYPE_EDIT || $departamento->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_iddepartamento" name="x<?php echo $departamento_grid->RowIndex ?>_iddepartamento" id="x<?php echo $departamento_grid->RowIndex ?>_iddepartamento" value="<?php echo ew_HtmlEncode($departamento->iddepartamento->CurrentValue) ?>">
<?php } ?>
	<?php if ($departamento->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $departamento->estado->CellAttributes() ?>>
<?php if ($departamento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $departamento_grid->RowCnt ?>_departamento_estado" class="form-group departamento_estado">
<div id="tp_x<?php echo $departamento_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $departamento_grid->RowIndex ?>_estado" id="x<?php echo $departamento_grid->RowIndex ?>_estado" value="{value}"<?php echo $departamento->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $departamento_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $departamento->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($departamento->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $departamento_grid->RowIndex ?>_estado" id="x<?php echo $departamento_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $departamento->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $departamento->estado->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $departamento_grid->RowIndex ?>_estado" id="o<?php echo $departamento_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($departamento->estado->OldValue) ?>">
<?php } ?>
<?php if ($departamento->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $departamento_grid->RowCnt ?>_departamento_estado" class="form-group departamento_estado">
<div id="tp_x<?php echo $departamento_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $departamento_grid->RowIndex ?>_estado" id="x<?php echo $departamento_grid->RowIndex ?>_estado" value="{value}"<?php echo $departamento->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $departamento_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $departamento->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($departamento->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $departamento_grid->RowIndex ?>_estado" id="x<?php echo $departamento_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $departamento->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $departamento->estado->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($departamento->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $departamento->estado->ViewAttributes() ?>>
<?php echo $departamento->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $departamento_grid->RowIndex ?>_estado" id="x<?php echo $departamento_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($departamento->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $departamento_grid->RowIndex ?>_estado" id="o<?php echo $departamento_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($departamento->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($departamento->idpais->Visible) { // idpais ?>
		<td data-name="idpais"<?php echo $departamento->idpais->CellAttributes() ?>>
<?php if ($departamento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($departamento->idpais->getSessionValue() <> "") { ?>
<span id="el<?php echo $departamento_grid->RowCnt ?>_departamento_idpais" class="form-group departamento_idpais">
<span<?php echo $departamento->idpais->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $departamento->idpais->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $departamento_grid->RowIndex ?>_idpais" name="x<?php echo $departamento_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($departamento->idpais->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $departamento_grid->RowCnt ?>_departamento_idpais" class="form-group departamento_idpais">
<select data-field="x_idpais" id="x<?php echo $departamento_grid->RowIndex ?>_idpais" name="x<?php echo $departamento_grid->RowIndex ?>_idpais"<?php echo $departamento->idpais->EditAttributes() ?>>
<?php
if (is_array($departamento->idpais->EditValue)) {
	$arwrk = $departamento->idpais->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($departamento->idpais->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $departamento->idpais->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpais`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pais`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $departamento->Lookup_Selecting($departamento->idpais, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $departamento_grid->RowIndex ?>_idpais" id="s_x<?php echo $departamento_grid->RowIndex ?>_idpais" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpais` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idpais" name="o<?php echo $departamento_grid->RowIndex ?>_idpais" id="o<?php echo $departamento_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($departamento->idpais->OldValue) ?>">
<?php } ?>
<?php if ($departamento->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($departamento->idpais->getSessionValue() <> "") { ?>
<span id="el<?php echo $departamento_grid->RowCnt ?>_departamento_idpais" class="form-group departamento_idpais">
<span<?php echo $departamento->idpais->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $departamento->idpais->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $departamento_grid->RowIndex ?>_idpais" name="x<?php echo $departamento_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($departamento->idpais->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $departamento_grid->RowCnt ?>_departamento_idpais" class="form-group departamento_idpais">
<select data-field="x_idpais" id="x<?php echo $departamento_grid->RowIndex ?>_idpais" name="x<?php echo $departamento_grid->RowIndex ?>_idpais"<?php echo $departamento->idpais->EditAttributes() ?>>
<?php
if (is_array($departamento->idpais->EditValue)) {
	$arwrk = $departamento->idpais->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($departamento->idpais->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $departamento->idpais->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpais`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pais`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $departamento->Lookup_Selecting($departamento->idpais, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $departamento_grid->RowIndex ?>_idpais" id="s_x<?php echo $departamento_grid->RowIndex ?>_idpais" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpais` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($departamento->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $departamento->idpais->ViewAttributes() ?>>
<?php echo $departamento->idpais->ListViewValue() ?></span>
<input type="hidden" data-field="x_idpais" name="x<?php echo $departamento_grid->RowIndex ?>_idpais" id="x<?php echo $departamento_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($departamento->idpais->FormValue) ?>">
<input type="hidden" data-field="x_idpais" name="o<?php echo $departamento_grid->RowIndex ?>_idpais" id="o<?php echo $departamento_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($departamento->idpais->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$departamento_grid->ListOptions->Render("body", "right", $departamento_grid->RowCnt);
?>
	</tr>
<?php if ($departamento->RowType == EW_ROWTYPE_ADD || $departamento->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdepartamentogrid.UpdateOpts(<?php echo $departamento_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($departamento->CurrentAction <> "gridadd" || $departamento->CurrentMode == "copy")
		if (!$departamento_grid->Recordset->EOF) $departamento_grid->Recordset->MoveNext();
}
?>
<?php
	if ($departamento->CurrentMode == "add" || $departamento->CurrentMode == "copy" || $departamento->CurrentMode == "edit") {
		$departamento_grid->RowIndex = '$rowindex$';
		$departamento_grid->LoadDefaultValues();

		// Set row properties
		$departamento->ResetAttrs();
		$departamento->RowAttrs = array_merge($departamento->RowAttrs, array('data-rowindex'=>$departamento_grid->RowIndex, 'id'=>'r0_departamento', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($departamento->RowAttrs["class"], "ewTemplate");
		$departamento->RowType = EW_ROWTYPE_ADD;

		// Render row
		$departamento_grid->RenderRow();

		// Render list options
		$departamento_grid->RenderListOptions();
		$departamento_grid->StartRowCnt = 0;
?>
	<tr<?php echo $departamento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$departamento_grid->ListOptions->Render("body", "left", $departamento_grid->RowIndex);
?>
	<?php if ($departamento->nombre->Visible) { // nombre ?>
		<td>
<?php if ($departamento->CurrentAction <> "F") { ?>
<span id="el$rowindex$_departamento_nombre" class="form-group departamento_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $departamento_grid->RowIndex ?>_nombre" id="x<?php echo $departamento_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($departamento->nombre->PlaceHolder) ?>" value="<?php echo $departamento->nombre->EditValue ?>"<?php echo $departamento->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_departamento_nombre" class="form-group departamento_nombre">
<span<?php echo $departamento->nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $departamento->nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $departamento_grid->RowIndex ?>_nombre" id="x<?php echo $departamento_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($departamento->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nombre" name="o<?php echo $departamento_grid->RowIndex ?>_nombre" id="o<?php echo $departamento_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($departamento->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($departamento->estado->Visible) { // estado ?>
		<td>
<?php if ($departamento->CurrentAction <> "F") { ?>
<span id="el$rowindex$_departamento_estado" class="form-group departamento_estado">
<div id="tp_x<?php echo $departamento_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $departamento_grid->RowIndex ?>_estado" id="x<?php echo $departamento_grid->RowIndex ?>_estado" value="{value}"<?php echo $departamento->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $departamento_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $departamento->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($departamento->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $departamento_grid->RowIndex ?>_estado" id="x<?php echo $departamento_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $departamento->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $departamento->estado->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_departamento_estado" class="form-group departamento_estado">
<span<?php echo $departamento->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $departamento->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $departamento_grid->RowIndex ?>_estado" id="x<?php echo $departamento_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($departamento->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $departamento_grid->RowIndex ?>_estado" id="o<?php echo $departamento_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($departamento->estado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($departamento->idpais->Visible) { // idpais ?>
		<td>
<?php if ($departamento->CurrentAction <> "F") { ?>
<?php if ($departamento->idpais->getSessionValue() <> "") { ?>
<span id="el$rowindex$_departamento_idpais" class="form-group departamento_idpais">
<span<?php echo $departamento->idpais->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $departamento->idpais->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $departamento_grid->RowIndex ?>_idpais" name="x<?php echo $departamento_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($departamento->idpais->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_departamento_idpais" class="form-group departamento_idpais">
<select data-field="x_idpais" id="x<?php echo $departamento_grid->RowIndex ?>_idpais" name="x<?php echo $departamento_grid->RowIndex ?>_idpais"<?php echo $departamento->idpais->EditAttributes() ?>>
<?php
if (is_array($departamento->idpais->EditValue)) {
	$arwrk = $departamento->idpais->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($departamento->idpais->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $departamento->idpais->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpais`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pais`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $departamento->Lookup_Selecting($departamento->idpais, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $departamento_grid->RowIndex ?>_idpais" id="s_x<?php echo $departamento_grid->RowIndex ?>_idpais" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpais` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_departamento_idpais" class="form-group departamento_idpais">
<span<?php echo $departamento->idpais->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $departamento->idpais->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idpais" name="x<?php echo $departamento_grid->RowIndex ?>_idpais" id="x<?php echo $departamento_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($departamento->idpais->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idpais" name="o<?php echo $departamento_grid->RowIndex ?>_idpais" id="o<?php echo $departamento_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($departamento->idpais->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$departamento_grid->ListOptions->Render("body", "right", $departamento_grid->RowCnt);
?>
<script type="text/javascript">
fdepartamentogrid.UpdateOpts(<?php echo $departamento_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($departamento->CurrentMode == "add" || $departamento->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $departamento_grid->FormKeyCountName ?>" id="<?php echo $departamento_grid->FormKeyCountName ?>" value="<?php echo $departamento_grid->KeyCount ?>">
<?php echo $departamento_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($departamento->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $departamento_grid->FormKeyCountName ?>" id="<?php echo $departamento_grid->FormKeyCountName ?>" value="<?php echo $departamento_grid->KeyCount ?>">
<?php echo $departamento_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($departamento->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdepartamentogrid">
</div>
<?php

// Close recordset
if ($departamento_grid->Recordset)
	$departamento_grid->Recordset->Close();
?>
<?php if ($departamento_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($departamento_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($departamento_grid->TotalRecs == 0 && $departamento->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($departamento_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($departamento->Export == "") { ?>
<script type="text/javascript">
fdepartamentogrid.Init();
</script>
<?php } ?>
<?php
$departamento_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$departamento_grid->Page_Terminate();
?>

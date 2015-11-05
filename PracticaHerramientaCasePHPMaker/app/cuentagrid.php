<?php

// Create page object
if (!isset($cuenta_grid)) $cuenta_grid = new ccuenta_grid();

// Page init
$cuenta_grid->Page_Init();

// Page main
$cuenta_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cuenta_grid->Page_Render();
?>
<?php if ($cuenta->Export == "") { ?>
<script type="text/javascript">

// Page object
var cuenta_grid = new ew_Page("cuenta_grid");
cuenta_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = cuenta_grid.PageID; // For backward compatibility

// Form object
var fcuentagrid = new ew_Form("fcuentagrid");
fcuentagrid.FormKeyCountName = '<?php echo $cuenta_grid->FormKeyCountName ?>';

// Validate form
fcuentagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idpaciente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuenta->idpaciente->FldCaption(), $cuenta->idpaciente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cuenta->fecha_inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_final");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cuenta->fecha_final->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuenta->estado->FldCaption(), $cuenta->estado->ReqErrMsg)) ?>");

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
fcuentagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idpaciente", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_inicio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_final", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	return true;
}

// Form_CustomValidate event
fcuentagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcuentagrid.ValidateRequired = true;
<?php } else { ?>
fcuentagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcuentagrid.Lists["x_idpaciente"] = {"LinkField":"x_idpaciente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($cuenta->CurrentAction == "gridadd") {
	if ($cuenta->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$cuenta_grid->TotalRecs = $cuenta->SelectRecordCount();
			$cuenta_grid->Recordset = $cuenta_grid->LoadRecordset($cuenta_grid->StartRec-1, $cuenta_grid->DisplayRecs);
		} else {
			if ($cuenta_grid->Recordset = $cuenta_grid->LoadRecordset())
				$cuenta_grid->TotalRecs = $cuenta_grid->Recordset->RecordCount();
		}
		$cuenta_grid->StartRec = 1;
		$cuenta_grid->DisplayRecs = $cuenta_grid->TotalRecs;
	} else {
		$cuenta->CurrentFilter = "0=1";
		$cuenta_grid->StartRec = 1;
		$cuenta_grid->DisplayRecs = $cuenta->GridAddRowCount;
	}
	$cuenta_grid->TotalRecs = $cuenta_grid->DisplayRecs;
	$cuenta_grid->StopRec = $cuenta_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$cuenta_grid->TotalRecs = $cuenta->SelectRecordCount();
	} else {
		if ($cuenta_grid->Recordset = $cuenta_grid->LoadRecordset())
			$cuenta_grid->TotalRecs = $cuenta_grid->Recordset->RecordCount();
	}
	$cuenta_grid->StartRec = 1;
	$cuenta_grid->DisplayRecs = $cuenta_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$cuenta_grid->Recordset = $cuenta_grid->LoadRecordset($cuenta_grid->StartRec-1, $cuenta_grid->DisplayRecs);

	// Set no record found message
	if ($cuenta->CurrentAction == "" && $cuenta_grid->TotalRecs == 0) {
		if ($cuenta_grid->SearchWhere == "0=101")
			$cuenta_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$cuenta_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$cuenta_grid->RenderOtherOptions();
?>
<?php $cuenta_grid->ShowPageHeader(); ?>
<?php
$cuenta_grid->ShowMessage();
?>
<?php if ($cuenta_grid->TotalRecs > 0 || $cuenta->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fcuentagrid" class="ewForm form-inline">
<div id="gmp_cuenta" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_cuentagrid" class="table ewTable">
<?php echo $cuenta->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$cuenta_grid->RenderListOptions();

// Render list options (header, left)
$cuenta_grid->ListOptions->Render("header", "left");
?>
<?php if ($cuenta->idpaciente->Visible) { // idpaciente ?>
	<?php if ($cuenta->SortUrl($cuenta->idpaciente) == "") { ?>
		<th data-name="idpaciente"><div id="elh_cuenta_idpaciente" class="cuenta_idpaciente"><div class="ewTableHeaderCaption"><?php echo $cuenta->idpaciente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idpaciente"><div><div id="elh_cuenta_idpaciente" class="cuenta_idpaciente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cuenta->idpaciente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cuenta->idpaciente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cuenta->idpaciente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cuenta->fecha_inicio->Visible) { // fecha_inicio ?>
	<?php if ($cuenta->SortUrl($cuenta->fecha_inicio) == "") { ?>
		<th data-name="fecha_inicio"><div id="elh_cuenta_fecha_inicio" class="cuenta_fecha_inicio"><div class="ewTableHeaderCaption"><?php echo $cuenta->fecha_inicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_inicio"><div><div id="elh_cuenta_fecha_inicio" class="cuenta_fecha_inicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cuenta->fecha_inicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cuenta->fecha_inicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cuenta->fecha_inicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cuenta->fecha_final->Visible) { // fecha_final ?>
	<?php if ($cuenta->SortUrl($cuenta->fecha_final) == "") { ?>
		<th data-name="fecha_final"><div id="elh_cuenta_fecha_final" class="cuenta_fecha_final"><div class="ewTableHeaderCaption"><?php echo $cuenta->fecha_final->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_final"><div><div id="elh_cuenta_fecha_final" class="cuenta_fecha_final">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cuenta->fecha_final->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cuenta->fecha_final->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cuenta->fecha_final->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cuenta->estado->Visible) { // estado ?>
	<?php if ($cuenta->SortUrl($cuenta->estado) == "") { ?>
		<th data-name="estado"><div id="elh_cuenta_estado" class="cuenta_estado"><div class="ewTableHeaderCaption"><?php echo $cuenta->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_cuenta_estado" class="cuenta_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cuenta->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cuenta->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cuenta->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$cuenta_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$cuenta_grid->StartRec = 1;
$cuenta_grid->StopRec = $cuenta_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($cuenta_grid->FormKeyCountName) && ($cuenta->CurrentAction == "gridadd" || $cuenta->CurrentAction == "gridedit" || $cuenta->CurrentAction == "F")) {
		$cuenta_grid->KeyCount = $objForm->GetValue($cuenta_grid->FormKeyCountName);
		$cuenta_grid->StopRec = $cuenta_grid->StartRec + $cuenta_grid->KeyCount - 1;
	}
}
$cuenta_grid->RecCnt = $cuenta_grid->StartRec - 1;
if ($cuenta_grid->Recordset && !$cuenta_grid->Recordset->EOF) {
	$cuenta_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $cuenta_grid->StartRec > 1)
		$cuenta_grid->Recordset->Move($cuenta_grid->StartRec - 1);
} elseif (!$cuenta->AllowAddDeleteRow && $cuenta_grid->StopRec == 0) {
	$cuenta_grid->StopRec = $cuenta->GridAddRowCount;
}

// Initialize aggregate
$cuenta->RowType = EW_ROWTYPE_AGGREGATEINIT;
$cuenta->ResetAttrs();
$cuenta_grid->RenderRow();
if ($cuenta->CurrentAction == "gridadd")
	$cuenta_grid->RowIndex = 0;
if ($cuenta->CurrentAction == "gridedit")
	$cuenta_grid->RowIndex = 0;
while ($cuenta_grid->RecCnt < $cuenta_grid->StopRec) {
	$cuenta_grid->RecCnt++;
	if (intval($cuenta_grid->RecCnt) >= intval($cuenta_grid->StartRec)) {
		$cuenta_grid->RowCnt++;
		if ($cuenta->CurrentAction == "gridadd" || $cuenta->CurrentAction == "gridedit" || $cuenta->CurrentAction == "F") {
			$cuenta_grid->RowIndex++;
			$objForm->Index = $cuenta_grid->RowIndex;
			if ($objForm->HasValue($cuenta_grid->FormActionName))
				$cuenta_grid->RowAction = strval($objForm->GetValue($cuenta_grid->FormActionName));
			elseif ($cuenta->CurrentAction == "gridadd")
				$cuenta_grid->RowAction = "insert";
			else
				$cuenta_grid->RowAction = "";
		}

		// Set up key count
		$cuenta_grid->KeyCount = $cuenta_grid->RowIndex;

		// Init row class and style
		$cuenta->ResetAttrs();
		$cuenta->CssClass = "";
		if ($cuenta->CurrentAction == "gridadd") {
			if ($cuenta->CurrentMode == "copy") {
				$cuenta_grid->LoadRowValues($cuenta_grid->Recordset); // Load row values
				$cuenta_grid->SetRecordKey($cuenta_grid->RowOldKey, $cuenta_grid->Recordset); // Set old record key
			} else {
				$cuenta_grid->LoadDefaultValues(); // Load default values
				$cuenta_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$cuenta_grid->LoadRowValues($cuenta_grid->Recordset); // Load row values
		}
		$cuenta->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($cuenta->CurrentAction == "gridadd") // Grid add
			$cuenta->RowType = EW_ROWTYPE_ADD; // Render add
		if ($cuenta->CurrentAction == "gridadd" && $cuenta->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$cuenta_grid->RestoreCurrentRowFormValues($cuenta_grid->RowIndex); // Restore form values
		if ($cuenta->CurrentAction == "gridedit") { // Grid edit
			if ($cuenta->EventCancelled) {
				$cuenta_grid->RestoreCurrentRowFormValues($cuenta_grid->RowIndex); // Restore form values
			}
			if ($cuenta_grid->RowAction == "insert")
				$cuenta->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$cuenta->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($cuenta->CurrentAction == "gridedit" && ($cuenta->RowType == EW_ROWTYPE_EDIT || $cuenta->RowType == EW_ROWTYPE_ADD) && $cuenta->EventCancelled) // Update failed
			$cuenta_grid->RestoreCurrentRowFormValues($cuenta_grid->RowIndex); // Restore form values
		if ($cuenta->RowType == EW_ROWTYPE_EDIT) // Edit row
			$cuenta_grid->EditRowCnt++;
		if ($cuenta->CurrentAction == "F") // Confirm row
			$cuenta_grid->RestoreCurrentRowFormValues($cuenta_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$cuenta->RowAttrs = array_merge($cuenta->RowAttrs, array('data-rowindex'=>$cuenta_grid->RowCnt, 'id'=>'r' . $cuenta_grid->RowCnt . '_cuenta', 'data-rowtype'=>$cuenta->RowType));

		// Render row
		$cuenta_grid->RenderRow();

		// Render list options
		$cuenta_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($cuenta_grid->RowAction <> "delete" && $cuenta_grid->RowAction <> "insertdelete" && !($cuenta_grid->RowAction == "insert" && $cuenta->CurrentAction == "F" && $cuenta_grid->EmptyRow())) {
?>
	<tr<?php echo $cuenta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$cuenta_grid->ListOptions->Render("body", "left", $cuenta_grid->RowCnt);
?>
	<?php if ($cuenta->idpaciente->Visible) { // idpaciente ?>
		<td data-name="idpaciente"<?php echo $cuenta->idpaciente->CellAttributes() ?>>
<?php if ($cuenta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($cuenta->idpaciente->getSessionValue() <> "") { ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_idpaciente" class="form-group cuenta_idpaciente">
<span<?php echo $cuenta->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cuenta->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" name="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($cuenta->idpaciente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_idpaciente" class="form-group cuenta_idpaciente">
<select data-field="x_idpaciente" id="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" name="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente"<?php echo $cuenta->idpaciente->EditAttributes() ?>>
<?php
if (is_array($cuenta->idpaciente->EditValue)) {
	$arwrk = $cuenta->idpaciente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cuenta->idpaciente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cuenta->idpaciente->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cuenta->Lookup_Selecting($cuenta->idpaciente, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" id="s_x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpaciente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idpaciente" name="o<?php echo $cuenta_grid->RowIndex ?>_idpaciente" id="o<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($cuenta->idpaciente->OldValue) ?>">
<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($cuenta->idpaciente->getSessionValue() <> "") { ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_idpaciente" class="form-group cuenta_idpaciente">
<span<?php echo $cuenta->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cuenta->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" name="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($cuenta->idpaciente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_idpaciente" class="form-group cuenta_idpaciente">
<select data-field="x_idpaciente" id="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" name="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente"<?php echo $cuenta->idpaciente->EditAttributes() ?>>
<?php
if (is_array($cuenta->idpaciente->EditValue)) {
	$arwrk = $cuenta->idpaciente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cuenta->idpaciente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cuenta->idpaciente->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cuenta->Lookup_Selecting($cuenta->idpaciente, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" id="s_x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpaciente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cuenta->idpaciente->ViewAttributes() ?>>
<?php echo $cuenta->idpaciente->ListViewValue() ?></span>
<input type="hidden" data-field="x_idpaciente" name="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" id="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($cuenta->idpaciente->FormValue) ?>">
<input type="hidden" data-field="x_idpaciente" name="o<?php echo $cuenta_grid->RowIndex ?>_idpaciente" id="o<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($cuenta->idpaciente->OldValue) ?>">
<?php } ?>
<a id="<?php echo $cuenta_grid->PageObjName . "_row_" . $cuenta_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $cuenta_grid->RowIndex ?>_idcuenta" id="x<?php echo $cuenta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($cuenta->idcuenta->CurrentValue) ?>">
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $cuenta_grid->RowIndex ?>_idcuenta" id="o<?php echo $cuenta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($cuenta->idcuenta->OldValue) ?>">
<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_EDIT || $cuenta->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $cuenta_grid->RowIndex ?>_idcuenta" id="x<?php echo $cuenta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($cuenta->idcuenta->CurrentValue) ?>">
<?php } ?>
	<?php if ($cuenta->fecha_inicio->Visible) { // fecha_inicio ?>
		<td data-name="fecha_inicio"<?php echo $cuenta->fecha_inicio->CellAttributes() ?>>
<?php if ($cuenta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_fecha_inicio" class="form-group cuenta_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($cuenta->fecha_inicio->PlaceHolder) ?>" value="<?php echo $cuenta->fecha_inicio->EditValue ?>"<?php echo $cuenta->fecha_inicio->EditAttributes() ?>>
<?php if (!$cuenta->fecha_inicio->ReadOnly && !$cuenta->fecha_inicio->Disabled && @$cuenta->fecha_inicio->EditAttrs["readonly"] == "" && @$cuenta->fecha_inicio->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcuentagrid", "x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($cuenta->fecha_inicio->OldValue) ?>">
<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_fecha_inicio" class="form-group cuenta_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($cuenta->fecha_inicio->PlaceHolder) ?>" value="<?php echo $cuenta->fecha_inicio->EditValue ?>"<?php echo $cuenta->fecha_inicio->EditAttributes() ?>>
<?php if (!$cuenta->fecha_inicio->ReadOnly && !$cuenta->fecha_inicio->Disabled && @$cuenta->fecha_inicio->EditAttrs["readonly"] == "" && @$cuenta->fecha_inicio->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcuentagrid", "x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cuenta->fecha_inicio->ViewAttributes() ?>>
<?php echo $cuenta->fecha_inicio->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_inicio" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($cuenta->fecha_inicio->FormValue) ?>">
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($cuenta->fecha_inicio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cuenta->fecha_final->Visible) { // fecha_final ?>
		<td data-name="fecha_final"<?php echo $cuenta->fecha_final->CellAttributes() ?>>
<?php if ($cuenta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_fecha_final" class="form-group cuenta_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($cuenta->fecha_final->PlaceHolder) ?>" value="<?php echo $cuenta->fecha_final->EditValue ?>"<?php echo $cuenta->fecha_final->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $cuenta_grid->RowIndex ?>_fecha_final" id="o<?php echo $cuenta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($cuenta->fecha_final->OldValue) ?>">
<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_fecha_final" class="form-group cuenta_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($cuenta->fecha_final->PlaceHolder) ?>" value="<?php echo $cuenta->fecha_final->EditValue ?>"<?php echo $cuenta->fecha_final->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cuenta->fecha_final->ViewAttributes() ?>>
<?php echo $cuenta->fecha_final->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_final" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($cuenta->fecha_final->FormValue) ?>">
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $cuenta_grid->RowIndex ?>_fecha_final" id="o<?php echo $cuenta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($cuenta->fecha_final->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($cuenta->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $cuenta->estado->CellAttributes() ?>>
<?php if ($cuenta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_estado" class="form-group cuenta_estado">
<div id="tp_x<?php echo $cuenta_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $cuenta_grid->RowIndex ?>_estado" id="x<?php echo $cuenta_grid->RowIndex ?>_estado" value="{value}"<?php echo $cuenta->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $cuenta_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $cuenta->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cuenta->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $cuenta_grid->RowIndex ?>_estado" id="x<?php echo $cuenta_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cuenta->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $cuenta->estado->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $cuenta_grid->RowIndex ?>_estado" id="o<?php echo $cuenta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cuenta->estado->OldValue) ?>">
<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $cuenta_grid->RowCnt ?>_cuenta_estado" class="form-group cuenta_estado">
<div id="tp_x<?php echo $cuenta_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $cuenta_grid->RowIndex ?>_estado" id="x<?php echo $cuenta_grid->RowIndex ?>_estado" value="{value}"<?php echo $cuenta->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $cuenta_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $cuenta->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cuenta->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $cuenta_grid->RowIndex ?>_estado" id="x<?php echo $cuenta_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cuenta->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $cuenta->estado->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($cuenta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $cuenta->estado->ViewAttributes() ?>>
<?php echo $cuenta->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $cuenta_grid->RowIndex ?>_estado" id="x<?php echo $cuenta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cuenta->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $cuenta_grid->RowIndex ?>_estado" id="o<?php echo $cuenta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cuenta->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$cuenta_grid->ListOptions->Render("body", "right", $cuenta_grid->RowCnt);
?>
	</tr>
<?php if ($cuenta->RowType == EW_ROWTYPE_ADD || $cuenta->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcuentagrid.UpdateOpts(<?php echo $cuenta_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($cuenta->CurrentAction <> "gridadd" || $cuenta->CurrentMode == "copy")
		if (!$cuenta_grid->Recordset->EOF) $cuenta_grid->Recordset->MoveNext();
}
?>
<?php
	if ($cuenta->CurrentMode == "add" || $cuenta->CurrentMode == "copy" || $cuenta->CurrentMode == "edit") {
		$cuenta_grid->RowIndex = '$rowindex$';
		$cuenta_grid->LoadDefaultValues();

		// Set row properties
		$cuenta->ResetAttrs();
		$cuenta->RowAttrs = array_merge($cuenta->RowAttrs, array('data-rowindex'=>$cuenta_grid->RowIndex, 'id'=>'r0_cuenta', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($cuenta->RowAttrs["class"], "ewTemplate");
		$cuenta->RowType = EW_ROWTYPE_ADD;

		// Render row
		$cuenta_grid->RenderRow();

		// Render list options
		$cuenta_grid->RenderListOptions();
		$cuenta_grid->StartRowCnt = 0;
?>
	<tr<?php echo $cuenta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$cuenta_grid->ListOptions->Render("body", "left", $cuenta_grid->RowIndex);
?>
	<?php if ($cuenta->idpaciente->Visible) { // idpaciente ?>
		<td>
<?php if ($cuenta->CurrentAction <> "F") { ?>
<?php if ($cuenta->idpaciente->getSessionValue() <> "") { ?>
<span id="el$rowindex$_cuenta_idpaciente" class="form-group cuenta_idpaciente">
<span<?php echo $cuenta->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cuenta->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" name="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($cuenta->idpaciente->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_cuenta_idpaciente" class="form-group cuenta_idpaciente">
<select data-field="x_idpaciente" id="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" name="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente"<?php echo $cuenta->idpaciente->EditAttributes() ?>>
<?php
if (is_array($cuenta->idpaciente->EditValue)) {
	$arwrk = $cuenta->idpaciente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cuenta->idpaciente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $cuenta->idpaciente->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $cuenta->Lookup_Selecting($cuenta->idpaciente, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" id="s_x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpaciente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_cuenta_idpaciente" class="form-group cuenta_idpaciente">
<span<?php echo $cuenta->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cuenta->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idpaciente" name="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" id="x<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($cuenta->idpaciente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idpaciente" name="o<?php echo $cuenta_grid->RowIndex ?>_idpaciente" id="o<?php echo $cuenta_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($cuenta->idpaciente->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cuenta->fecha_inicio->Visible) { // fecha_inicio ?>
		<td>
<?php if ($cuenta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cuenta_fecha_inicio" class="form-group cuenta_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($cuenta->fecha_inicio->PlaceHolder) ?>" value="<?php echo $cuenta->fecha_inicio->EditValue ?>"<?php echo $cuenta->fecha_inicio->EditAttributes() ?>>
<?php if (!$cuenta->fecha_inicio->ReadOnly && !$cuenta->fecha_inicio->Disabled && @$cuenta->fecha_inicio->EditAttrs["readonly"] == "" && @$cuenta->fecha_inicio->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcuentagrid", "x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_cuenta_fecha_inicio" class="form-group cuenta_fecha_inicio">
<span<?php echo $cuenta->fecha_inicio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cuenta->fecha_inicio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_inicio" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($cuenta->fecha_inicio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $cuenta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($cuenta->fecha_inicio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cuenta->fecha_final->Visible) { // fecha_final ?>
		<td>
<?php if ($cuenta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cuenta_fecha_final" class="form-group cuenta_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($cuenta->fecha_final->PlaceHolder) ?>" value="<?php echo $cuenta->fecha_final->EditValue ?>"<?php echo $cuenta->fecha_final->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cuenta_fecha_final" class="form-group cuenta_fecha_final">
<span<?php echo $cuenta->fecha_final->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cuenta->fecha_final->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_final" name="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" id="x<?php echo $cuenta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($cuenta->fecha_final->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $cuenta_grid->RowIndex ?>_fecha_final" id="o<?php echo $cuenta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($cuenta->fecha_final->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($cuenta->estado->Visible) { // estado ?>
		<td>
<?php if ($cuenta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_cuenta_estado" class="form-group cuenta_estado">
<div id="tp_x<?php echo $cuenta_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $cuenta_grid->RowIndex ?>_estado" id="x<?php echo $cuenta_grid->RowIndex ?>_estado" value="{value}"<?php echo $cuenta->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $cuenta_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $cuenta->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cuenta->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $cuenta_grid->RowIndex ?>_estado" id="x<?php echo $cuenta_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $cuenta->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $cuenta->estado->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cuenta_estado" class="form-group cuenta_estado">
<span<?php echo $cuenta->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cuenta->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $cuenta_grid->RowIndex ?>_estado" id="x<?php echo $cuenta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cuenta->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $cuenta_grid->RowIndex ?>_estado" id="o<?php echo $cuenta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($cuenta->estado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$cuenta_grid->ListOptions->Render("body", "right", $cuenta_grid->RowCnt);
?>
<script type="text/javascript">
fcuentagrid.UpdateOpts(<?php echo $cuenta_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($cuenta->CurrentMode == "add" || $cuenta->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $cuenta_grid->FormKeyCountName ?>" id="<?php echo $cuenta_grid->FormKeyCountName ?>" value="<?php echo $cuenta_grid->KeyCount ?>">
<?php echo $cuenta_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($cuenta->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $cuenta_grid->FormKeyCountName ?>" id="<?php echo $cuenta_grid->FormKeyCountName ?>" value="<?php echo $cuenta_grid->KeyCount ?>">
<?php echo $cuenta_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($cuenta->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcuentagrid">
</div>
<?php

// Close recordset
if ($cuenta_grid->Recordset)
	$cuenta_grid->Recordset->Close();
?>
<?php if ($cuenta_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($cuenta_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($cuenta_grid->TotalRecs == 0 && $cuenta->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($cuenta_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($cuenta->Export == "") { ?>
<script type="text/javascript">
fcuentagrid.Init();
</script>
<?php } ?>
<?php
$cuenta_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$cuenta_grid->Page_Terminate();
?>

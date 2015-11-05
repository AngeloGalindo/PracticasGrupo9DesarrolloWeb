<?php

// Create page object
if (!isset($turno_grid)) $turno_grid = new cturno_grid();

// Page init
$turno_grid->Page_Init();

// Page main
$turno_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$turno_grid->Page_Render();
?>
<?php if ($turno->Export == "") { ?>
<script type="text/javascript">

// Page object
var turno_grid = new ew_Page("turno_grid");
turno_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = turno_grid.PageID; // For backward compatibility

// Form object
var fturnogrid = new ew_Form("fturnogrid");
fturnogrid.FormKeyCountName = '<?php echo $turno_grid->FormKeyCountName ?>';

// Validate form
fturnogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idtipo_turno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $turno->idtipo_turno->FldCaption(), $turno->idtipo_turno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idhospital");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $turno->idhospital->FldCaption(), $turno->idhospital->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $turno->estado->FldCaption(), $turno->estado->ReqErrMsg)) ?>");

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
fturnogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "descripcion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idtipo_turno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idhospital", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	return true;
}

// Form_CustomValidate event
fturnogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fturnogrid.ValidateRequired = true;
<?php } else { ?>
fturnogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fturnogrid.Lists["x_idtipo_turno"] = {"LinkField":"x_idtipo_turno","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fturnogrid.Lists["x_idhospital"] = {"LinkField":"x_idhospital","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($turno->CurrentAction == "gridadd") {
	if ($turno->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$turno_grid->TotalRecs = $turno->SelectRecordCount();
			$turno_grid->Recordset = $turno_grid->LoadRecordset($turno_grid->StartRec-1, $turno_grid->DisplayRecs);
		} else {
			if ($turno_grid->Recordset = $turno_grid->LoadRecordset())
				$turno_grid->TotalRecs = $turno_grid->Recordset->RecordCount();
		}
		$turno_grid->StartRec = 1;
		$turno_grid->DisplayRecs = $turno_grid->TotalRecs;
	} else {
		$turno->CurrentFilter = "0=1";
		$turno_grid->StartRec = 1;
		$turno_grid->DisplayRecs = $turno->GridAddRowCount;
	}
	$turno_grid->TotalRecs = $turno_grid->DisplayRecs;
	$turno_grid->StopRec = $turno_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$turno_grid->TotalRecs = $turno->SelectRecordCount();
	} else {
		if ($turno_grid->Recordset = $turno_grid->LoadRecordset())
			$turno_grid->TotalRecs = $turno_grid->Recordset->RecordCount();
	}
	$turno_grid->StartRec = 1;
	$turno_grid->DisplayRecs = $turno_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$turno_grid->Recordset = $turno_grid->LoadRecordset($turno_grid->StartRec-1, $turno_grid->DisplayRecs);

	// Set no record found message
	if ($turno->CurrentAction == "" && $turno_grid->TotalRecs == 0) {
		if ($turno_grid->SearchWhere == "0=101")
			$turno_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$turno_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$turno_grid->RenderOtherOptions();
?>
<?php $turno_grid->ShowPageHeader(); ?>
<?php
$turno_grid->ShowMessage();
?>
<?php if ($turno_grid->TotalRecs > 0 || $turno->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fturnogrid" class="ewForm form-inline">
<div id="gmp_turno" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_turnogrid" class="table ewTable">
<?php echo $turno->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$turno_grid->RenderListOptions();

// Render list options (header, left)
$turno_grid->ListOptions->Render("header", "left");
?>
<?php if ($turno->descripcion->Visible) { // descripcion ?>
	<?php if ($turno->SortUrl($turno->descripcion) == "") { ?>
		<th data-name="descripcion"><div id="elh_turno_descripcion" class="turno_descripcion"><div class="ewTableHeaderCaption"><?php echo $turno->descripcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descripcion"><div><div id="elh_turno_descripcion" class="turno_descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $turno->descripcion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($turno->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($turno->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($turno->idtipo_turno->Visible) { // idtipo_turno ?>
	<?php if ($turno->SortUrl($turno->idtipo_turno) == "") { ?>
		<th data-name="idtipo_turno"><div id="elh_turno_idtipo_turno" class="turno_idtipo_turno"><div class="ewTableHeaderCaption"><?php echo $turno->idtipo_turno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idtipo_turno"><div><div id="elh_turno_idtipo_turno" class="turno_idtipo_turno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $turno->idtipo_turno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($turno->idtipo_turno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($turno->idtipo_turno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($turno->idhospital->Visible) { // idhospital ?>
	<?php if ($turno->SortUrl($turno->idhospital) == "") { ?>
		<th data-name="idhospital"><div id="elh_turno_idhospital" class="turno_idhospital"><div class="ewTableHeaderCaption"><?php echo $turno->idhospital->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idhospital"><div><div id="elh_turno_idhospital" class="turno_idhospital">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $turno->idhospital->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($turno->idhospital->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($turno->idhospital->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($turno->estado->Visible) { // estado ?>
	<?php if ($turno->SortUrl($turno->estado) == "") { ?>
		<th data-name="estado"><div id="elh_turno_estado" class="turno_estado"><div class="ewTableHeaderCaption"><?php echo $turno->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_turno_estado" class="turno_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $turno->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($turno->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($turno->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$turno_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$turno_grid->StartRec = 1;
$turno_grid->StopRec = $turno_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($turno_grid->FormKeyCountName) && ($turno->CurrentAction == "gridadd" || $turno->CurrentAction == "gridedit" || $turno->CurrentAction == "F")) {
		$turno_grid->KeyCount = $objForm->GetValue($turno_grid->FormKeyCountName);
		$turno_grid->StopRec = $turno_grid->StartRec + $turno_grid->KeyCount - 1;
	}
}
$turno_grid->RecCnt = $turno_grid->StartRec - 1;
if ($turno_grid->Recordset && !$turno_grid->Recordset->EOF) {
	$turno_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $turno_grid->StartRec > 1)
		$turno_grid->Recordset->Move($turno_grid->StartRec - 1);
} elseif (!$turno->AllowAddDeleteRow && $turno_grid->StopRec == 0) {
	$turno_grid->StopRec = $turno->GridAddRowCount;
}

// Initialize aggregate
$turno->RowType = EW_ROWTYPE_AGGREGATEINIT;
$turno->ResetAttrs();
$turno_grid->RenderRow();
if ($turno->CurrentAction == "gridadd")
	$turno_grid->RowIndex = 0;
if ($turno->CurrentAction == "gridedit")
	$turno_grid->RowIndex = 0;
while ($turno_grid->RecCnt < $turno_grid->StopRec) {
	$turno_grid->RecCnt++;
	if (intval($turno_grid->RecCnt) >= intval($turno_grid->StartRec)) {
		$turno_grid->RowCnt++;
		if ($turno->CurrentAction == "gridadd" || $turno->CurrentAction == "gridedit" || $turno->CurrentAction == "F") {
			$turno_grid->RowIndex++;
			$objForm->Index = $turno_grid->RowIndex;
			if ($objForm->HasValue($turno_grid->FormActionName))
				$turno_grid->RowAction = strval($objForm->GetValue($turno_grid->FormActionName));
			elseif ($turno->CurrentAction == "gridadd")
				$turno_grid->RowAction = "insert";
			else
				$turno_grid->RowAction = "";
		}

		// Set up key count
		$turno_grid->KeyCount = $turno_grid->RowIndex;

		// Init row class and style
		$turno->ResetAttrs();
		$turno->CssClass = "";
		if ($turno->CurrentAction == "gridadd") {
			if ($turno->CurrentMode == "copy") {
				$turno_grid->LoadRowValues($turno_grid->Recordset); // Load row values
				$turno_grid->SetRecordKey($turno_grid->RowOldKey, $turno_grid->Recordset); // Set old record key
			} else {
				$turno_grid->LoadDefaultValues(); // Load default values
				$turno_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$turno_grid->LoadRowValues($turno_grid->Recordset); // Load row values
		}
		$turno->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($turno->CurrentAction == "gridadd") // Grid add
			$turno->RowType = EW_ROWTYPE_ADD; // Render add
		if ($turno->CurrentAction == "gridadd" && $turno->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$turno_grid->RestoreCurrentRowFormValues($turno_grid->RowIndex); // Restore form values
		if ($turno->CurrentAction == "gridedit") { // Grid edit
			if ($turno->EventCancelled) {
				$turno_grid->RestoreCurrentRowFormValues($turno_grid->RowIndex); // Restore form values
			}
			if ($turno_grid->RowAction == "insert")
				$turno->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$turno->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($turno->CurrentAction == "gridedit" && ($turno->RowType == EW_ROWTYPE_EDIT || $turno->RowType == EW_ROWTYPE_ADD) && $turno->EventCancelled) // Update failed
			$turno_grid->RestoreCurrentRowFormValues($turno_grid->RowIndex); // Restore form values
		if ($turno->RowType == EW_ROWTYPE_EDIT) // Edit row
			$turno_grid->EditRowCnt++;
		if ($turno->CurrentAction == "F") // Confirm row
			$turno_grid->RestoreCurrentRowFormValues($turno_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$turno->RowAttrs = array_merge($turno->RowAttrs, array('data-rowindex'=>$turno_grid->RowCnt, 'id'=>'r' . $turno_grid->RowCnt . '_turno', 'data-rowtype'=>$turno->RowType));

		// Render row
		$turno_grid->RenderRow();

		// Render list options
		$turno_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($turno_grid->RowAction <> "delete" && $turno_grid->RowAction <> "insertdelete" && !($turno_grid->RowAction == "insert" && $turno->CurrentAction == "F" && $turno_grid->EmptyRow())) {
?>
	<tr<?php echo $turno->RowAttributes() ?>>
<?php

// Render list options (body, left)
$turno_grid->ListOptions->Render("body", "left", $turno_grid->RowCnt);
?>
	<?php if ($turno->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion"<?php echo $turno->descripcion->CellAttributes() ?>>
<?php if ($turno->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_descripcion" class="form-group turno_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $turno_grid->RowIndex ?>_descripcion" id="x<?php echo $turno_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($turno->descripcion->PlaceHolder) ?>" value="<?php echo $turno->descripcion->EditValue ?>"<?php echo $turno->descripcion->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $turno_grid->RowIndex ?>_descripcion" id="o<?php echo $turno_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($turno->descripcion->OldValue) ?>">
<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_descripcion" class="form-group turno_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $turno_grid->RowIndex ?>_descripcion" id="x<?php echo $turno_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($turno->descripcion->PlaceHolder) ?>" value="<?php echo $turno->descripcion->EditValue ?>"<?php echo $turno->descripcion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $turno->descripcion->ViewAttributes() ?>>
<?php echo $turno->descripcion->ListViewValue() ?></span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $turno_grid->RowIndex ?>_descripcion" id="x<?php echo $turno_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($turno->descripcion->FormValue) ?>">
<input type="hidden" data-field="x_descripcion" name="o<?php echo $turno_grid->RowIndex ?>_descripcion" id="o<?php echo $turno_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($turno->descripcion->OldValue) ?>">
<?php } ?>
<a id="<?php echo $turno_grid->PageObjName . "_row_" . $turno_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idturno" name="x<?php echo $turno_grid->RowIndex ?>_idturno" id="x<?php echo $turno_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($turno->idturno->CurrentValue) ?>">
<input type="hidden" data-field="x_idturno" name="o<?php echo $turno_grid->RowIndex ?>_idturno" id="o<?php echo $turno_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($turno->idturno->OldValue) ?>">
<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_EDIT || $turno->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_idturno" name="x<?php echo $turno_grid->RowIndex ?>_idturno" id="x<?php echo $turno_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($turno->idturno->CurrentValue) ?>">
<?php } ?>
	<?php if ($turno->idtipo_turno->Visible) { // idtipo_turno ?>
		<td data-name="idtipo_turno"<?php echo $turno->idtipo_turno->CellAttributes() ?>>
<?php if ($turno->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($turno->idtipo_turno->getSessionValue() <> "") { ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_idtipo_turno" class="form-group turno_idtipo_turno">
<span<?php echo $turno->idtipo_turno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idtipo_turno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" name="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="<?php echo ew_HtmlEncode($turno->idtipo_turno->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_idtipo_turno" class="form-group turno_idtipo_turno">
<select data-field="x_idtipo_turno" id="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" name="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno"<?php echo $turno->idtipo_turno->EditAttributes() ?>>
<?php
if (is_array($turno->idtipo_turno->EditValue)) {
	$arwrk = $turno->idtipo_turno->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->idtipo_turno->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $turno->idtipo_turno->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idtipo_turno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_turno`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $turno->Lookup_Selecting($turno->idtipo_turno, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" id="s_x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idtipo_turno` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idtipo_turno" name="o<?php echo $turno_grid->RowIndex ?>_idtipo_turno" id="o<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="<?php echo ew_HtmlEncode($turno->idtipo_turno->OldValue) ?>">
<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($turno->idtipo_turno->getSessionValue() <> "") { ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_idtipo_turno" class="form-group turno_idtipo_turno">
<span<?php echo $turno->idtipo_turno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idtipo_turno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" name="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="<?php echo ew_HtmlEncode($turno->idtipo_turno->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_idtipo_turno" class="form-group turno_idtipo_turno">
<select data-field="x_idtipo_turno" id="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" name="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno"<?php echo $turno->idtipo_turno->EditAttributes() ?>>
<?php
if (is_array($turno->idtipo_turno->EditValue)) {
	$arwrk = $turno->idtipo_turno->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->idtipo_turno->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $turno->idtipo_turno->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idtipo_turno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_turno`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $turno->Lookup_Selecting($turno->idtipo_turno, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" id="s_x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idtipo_turno` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $turno->idtipo_turno->ViewAttributes() ?>>
<?php echo $turno->idtipo_turno->ListViewValue() ?></span>
<input type="hidden" data-field="x_idtipo_turno" name="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" id="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="<?php echo ew_HtmlEncode($turno->idtipo_turno->FormValue) ?>">
<input type="hidden" data-field="x_idtipo_turno" name="o<?php echo $turno_grid->RowIndex ?>_idtipo_turno" id="o<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="<?php echo ew_HtmlEncode($turno->idtipo_turno->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($turno->idhospital->Visible) { // idhospital ?>
		<td data-name="idhospital"<?php echo $turno->idhospital->CellAttributes() ?>>
<?php if ($turno->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($turno->idhospital->getSessionValue() <> "") { ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_idhospital" class="form-group turno_idhospital">
<span<?php echo $turno->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $turno_grid->RowIndex ?>_idhospital" name="x<?php echo $turno_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($turno->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_idhospital" class="form-group turno_idhospital">
<select data-field="x_idhospital" id="x<?php echo $turno_grid->RowIndex ?>_idhospital" name="x<?php echo $turno_grid->RowIndex ?>_idhospital"<?php echo $turno->idhospital->EditAttributes() ?>>
<?php
if (is_array($turno->idhospital->EditValue)) {
	$arwrk = $turno->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $turno->idhospital->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $turno->Lookup_Selecting($turno->idhospital, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $turno_grid->RowIndex ?>_idhospital" id="s_x<?php echo $turno_grid->RowIndex ?>_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idhospital" name="o<?php echo $turno_grid->RowIndex ?>_idhospital" id="o<?php echo $turno_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($turno->idhospital->OldValue) ?>">
<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($turno->idhospital->getSessionValue() <> "") { ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_idhospital" class="form-group turno_idhospital">
<span<?php echo $turno->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $turno_grid->RowIndex ?>_idhospital" name="x<?php echo $turno_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($turno->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_idhospital" class="form-group turno_idhospital">
<select data-field="x_idhospital" id="x<?php echo $turno_grid->RowIndex ?>_idhospital" name="x<?php echo $turno_grid->RowIndex ?>_idhospital"<?php echo $turno->idhospital->EditAttributes() ?>>
<?php
if (is_array($turno->idhospital->EditValue)) {
	$arwrk = $turno->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $turno->idhospital->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $turno->Lookup_Selecting($turno->idhospital, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $turno_grid->RowIndex ?>_idhospital" id="s_x<?php echo $turno_grid->RowIndex ?>_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $turno->idhospital->ViewAttributes() ?>>
<?php echo $turno->idhospital->ListViewValue() ?></span>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $turno_grid->RowIndex ?>_idhospital" id="x<?php echo $turno_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($turno->idhospital->FormValue) ?>">
<input type="hidden" data-field="x_idhospital" name="o<?php echo $turno_grid->RowIndex ?>_idhospital" id="o<?php echo $turno_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($turno->idhospital->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($turno->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $turno->estado->CellAttributes() ?>>
<?php if ($turno->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_estado" class="form-group turno_estado">
<div id="tp_x<?php echo $turno_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $turno_grid->RowIndex ?>_estado" id="x<?php echo $turno_grid->RowIndex ?>_estado" value="{value}"<?php echo $turno->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $turno_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $turno->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $turno_grid->RowIndex ?>_estado" id="x<?php echo $turno_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $turno->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $turno->estado->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $turno_grid->RowIndex ?>_estado" id="o<?php echo $turno_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($turno->estado->OldValue) ?>">
<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $turno_grid->RowCnt ?>_turno_estado" class="form-group turno_estado">
<div id="tp_x<?php echo $turno_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $turno_grid->RowIndex ?>_estado" id="x<?php echo $turno_grid->RowIndex ?>_estado" value="{value}"<?php echo $turno->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $turno_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $turno->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $turno_grid->RowIndex ?>_estado" id="x<?php echo $turno_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $turno->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $turno->estado->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($turno->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $turno->estado->ViewAttributes() ?>>
<?php echo $turno->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $turno_grid->RowIndex ?>_estado" id="x<?php echo $turno_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($turno->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $turno_grid->RowIndex ?>_estado" id="o<?php echo $turno_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($turno->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$turno_grid->ListOptions->Render("body", "right", $turno_grid->RowCnt);
?>
	</tr>
<?php if ($turno->RowType == EW_ROWTYPE_ADD || $turno->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fturnogrid.UpdateOpts(<?php echo $turno_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($turno->CurrentAction <> "gridadd" || $turno->CurrentMode == "copy")
		if (!$turno_grid->Recordset->EOF) $turno_grid->Recordset->MoveNext();
}
?>
<?php
	if ($turno->CurrentMode == "add" || $turno->CurrentMode == "copy" || $turno->CurrentMode == "edit") {
		$turno_grid->RowIndex = '$rowindex$';
		$turno_grid->LoadDefaultValues();

		// Set row properties
		$turno->ResetAttrs();
		$turno->RowAttrs = array_merge($turno->RowAttrs, array('data-rowindex'=>$turno_grid->RowIndex, 'id'=>'r0_turno', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($turno->RowAttrs["class"], "ewTemplate");
		$turno->RowType = EW_ROWTYPE_ADD;

		// Render row
		$turno_grid->RenderRow();

		// Render list options
		$turno_grid->RenderListOptions();
		$turno_grid->StartRowCnt = 0;
?>
	<tr<?php echo $turno->RowAttributes() ?>>
<?php

// Render list options (body, left)
$turno_grid->ListOptions->Render("body", "left", $turno_grid->RowIndex);
?>
	<?php if ($turno->descripcion->Visible) { // descripcion ?>
		<td>
<?php if ($turno->CurrentAction <> "F") { ?>
<span id="el$rowindex$_turno_descripcion" class="form-group turno_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $turno_grid->RowIndex ?>_descripcion" id="x<?php echo $turno_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($turno->descripcion->PlaceHolder) ?>" value="<?php echo $turno->descripcion->EditValue ?>"<?php echo $turno->descripcion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_turno_descripcion" class="form-group turno_descripcion">
<span<?php echo $turno->descripcion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->descripcion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $turno_grid->RowIndex ?>_descripcion" id="x<?php echo $turno_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($turno->descripcion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $turno_grid->RowIndex ?>_descripcion" id="o<?php echo $turno_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($turno->descripcion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($turno->idtipo_turno->Visible) { // idtipo_turno ?>
		<td>
<?php if ($turno->CurrentAction <> "F") { ?>
<?php if ($turno->idtipo_turno->getSessionValue() <> "") { ?>
<span id="el$rowindex$_turno_idtipo_turno" class="form-group turno_idtipo_turno">
<span<?php echo $turno->idtipo_turno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idtipo_turno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" name="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="<?php echo ew_HtmlEncode($turno->idtipo_turno->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_turno_idtipo_turno" class="form-group turno_idtipo_turno">
<select data-field="x_idtipo_turno" id="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" name="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno"<?php echo $turno->idtipo_turno->EditAttributes() ?>>
<?php
if (is_array($turno->idtipo_turno->EditValue)) {
	$arwrk = $turno->idtipo_turno->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->idtipo_turno->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $turno->idtipo_turno->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idtipo_turno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_turno`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $turno->Lookup_Selecting($turno->idtipo_turno, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" id="s_x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idtipo_turno` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_turno_idtipo_turno" class="form-group turno_idtipo_turno">
<span<?php echo $turno->idtipo_turno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idtipo_turno->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idtipo_turno" name="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" id="x<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="<?php echo ew_HtmlEncode($turno->idtipo_turno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idtipo_turno" name="o<?php echo $turno_grid->RowIndex ?>_idtipo_turno" id="o<?php echo $turno_grid->RowIndex ?>_idtipo_turno" value="<?php echo ew_HtmlEncode($turno->idtipo_turno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($turno->idhospital->Visible) { // idhospital ?>
		<td>
<?php if ($turno->CurrentAction <> "F") { ?>
<?php if ($turno->idhospital->getSessionValue() <> "") { ?>
<span id="el$rowindex$_turno_idhospital" class="form-group turno_idhospital">
<span<?php echo $turno->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $turno_grid->RowIndex ?>_idhospital" name="x<?php echo $turno_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($turno->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_turno_idhospital" class="form-group turno_idhospital">
<select data-field="x_idhospital" id="x<?php echo $turno_grid->RowIndex ?>_idhospital" name="x<?php echo $turno_grid->RowIndex ?>_idhospital"<?php echo $turno->idhospital->EditAttributes() ?>>
<?php
if (is_array($turno->idhospital->EditValue)) {
	$arwrk = $turno->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $turno->idhospital->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $turno->Lookup_Selecting($turno->idhospital, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $turno_grid->RowIndex ?>_idhospital" id="s_x<?php echo $turno_grid->RowIndex ?>_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_turno_idhospital" class="form-group turno_idhospital">
<span<?php echo $turno->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $turno_grid->RowIndex ?>_idhospital" id="x<?php echo $turno_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($turno->idhospital->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idhospital" name="o<?php echo $turno_grid->RowIndex ?>_idhospital" id="o<?php echo $turno_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($turno->idhospital->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($turno->estado->Visible) { // estado ?>
		<td>
<?php if ($turno->CurrentAction <> "F") { ?>
<span id="el$rowindex$_turno_estado" class="form-group turno_estado">
<div id="tp_x<?php echo $turno_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $turno_grid->RowIndex ?>_estado" id="x<?php echo $turno_grid->RowIndex ?>_estado" value="{value}"<?php echo $turno->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $turno_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $turno->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $turno_grid->RowIndex ?>_estado" id="x<?php echo $turno_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $turno->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $turno->estado->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_turno_estado" class="form-group turno_estado">
<span<?php echo $turno->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $turno_grid->RowIndex ?>_estado" id="x<?php echo $turno_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($turno->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $turno_grid->RowIndex ?>_estado" id="o<?php echo $turno_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($turno->estado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$turno_grid->ListOptions->Render("body", "right", $turno_grid->RowCnt);
?>
<script type="text/javascript">
fturnogrid.UpdateOpts(<?php echo $turno_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($turno->CurrentMode == "add" || $turno->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $turno_grid->FormKeyCountName ?>" id="<?php echo $turno_grid->FormKeyCountName ?>" value="<?php echo $turno_grid->KeyCount ?>">
<?php echo $turno_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($turno->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $turno_grid->FormKeyCountName ?>" id="<?php echo $turno_grid->FormKeyCountName ?>" value="<?php echo $turno_grid->KeyCount ?>">
<?php echo $turno_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($turno->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fturnogrid">
</div>
<?php

// Close recordset
if ($turno_grid->Recordset)
	$turno_grid->Recordset->Close();
?>
<?php if ($turno_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($turno_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($turno_grid->TotalRecs == 0 && $turno->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($turno_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($turno->Export == "") { ?>
<script type="text/javascript">
fturnogrid.Init();
</script>
<?php } ?>
<?php
$turno_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$turno_grid->Page_Terminate();
?>

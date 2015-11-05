<?php

// Create page object
if (!isset($medicina_grid)) $medicina_grid = new cmedicina_grid();

// Page init
$medicina_grid->Page_Init();

// Page main
$medicina_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$medicina_grid->Page_Render();
?>
<?php if ($medicina->Export == "") { ?>
<script type="text/javascript">

// Page object
var medicina_grid = new ew_Page("medicina_grid");
medicina_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = medicina_grid.PageID; // For backward compatibility

// Form object
var fmedicinagrid = new ew_Form("fmedicinagrid");
fmedicinagrid.FormKeyCountName = '<?php echo $medicina_grid->FormKeyCountName ?>';

// Validate form
fmedicinagrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $medicina->estado->FldCaption(), $medicina->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idlaboratorio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $medicina->idlaboratorio->FldCaption(), $medicina->idlaboratorio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idhospital");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $medicina->idhospital->FldCaption(), $medicina->idhospital->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idhospital");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($medicina->idhospital->FldErrMsg()) ?>");

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
fmedicinagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "descripcion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idlaboratorio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idhospital", false)) return false;
	return true;
}

// Form_CustomValidate event
fmedicinagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmedicinagrid.ValidateRequired = true;
<?php } else { ?>
fmedicinagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmedicinagrid.Lists["x_idlaboratorio"] = {"LinkField":"x_idlaboratorio","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($medicina->CurrentAction == "gridadd") {
	if ($medicina->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$medicina_grid->TotalRecs = $medicina->SelectRecordCount();
			$medicina_grid->Recordset = $medicina_grid->LoadRecordset($medicina_grid->StartRec-1, $medicina_grid->DisplayRecs);
		} else {
			if ($medicina_grid->Recordset = $medicina_grid->LoadRecordset())
				$medicina_grid->TotalRecs = $medicina_grid->Recordset->RecordCount();
		}
		$medicina_grid->StartRec = 1;
		$medicina_grid->DisplayRecs = $medicina_grid->TotalRecs;
	} else {
		$medicina->CurrentFilter = "0=1";
		$medicina_grid->StartRec = 1;
		$medicina_grid->DisplayRecs = $medicina->GridAddRowCount;
	}
	$medicina_grid->TotalRecs = $medicina_grid->DisplayRecs;
	$medicina_grid->StopRec = $medicina_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$medicina_grid->TotalRecs = $medicina->SelectRecordCount();
	} else {
		if ($medicina_grid->Recordset = $medicina_grid->LoadRecordset())
			$medicina_grid->TotalRecs = $medicina_grid->Recordset->RecordCount();
	}
	$medicina_grid->StartRec = 1;
	$medicina_grid->DisplayRecs = $medicina_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$medicina_grid->Recordset = $medicina_grid->LoadRecordset($medicina_grid->StartRec-1, $medicina_grid->DisplayRecs);

	// Set no record found message
	if ($medicina->CurrentAction == "" && $medicina_grid->TotalRecs == 0) {
		if ($medicina_grid->SearchWhere == "0=101")
			$medicina_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$medicina_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$medicina_grid->RenderOtherOptions();
?>
<?php $medicina_grid->ShowPageHeader(); ?>
<?php
$medicina_grid->ShowMessage();
?>
<?php if ($medicina_grid->TotalRecs > 0 || $medicina->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fmedicinagrid" class="ewForm form-inline">
<div id="gmp_medicina" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_medicinagrid" class="table ewTable">
<?php echo $medicina->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$medicina_grid->RenderListOptions();

// Render list options (header, left)
$medicina_grid->ListOptions->Render("header", "left");
?>
<?php if ($medicina->idmedicina->Visible) { // idmedicina ?>
	<?php if ($medicina->SortUrl($medicina->idmedicina) == "") { ?>
		<th data-name="idmedicina"><div id="elh_medicina_idmedicina" class="medicina_idmedicina"><div class="ewTableHeaderCaption"><?php echo $medicina->idmedicina->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idmedicina"><div><div id="elh_medicina_idmedicina" class="medicina_idmedicina">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $medicina->idmedicina->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($medicina->idmedicina->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($medicina->idmedicina->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($medicina->descripcion->Visible) { // descripcion ?>
	<?php if ($medicina->SortUrl($medicina->descripcion) == "") { ?>
		<th data-name="descripcion"><div id="elh_medicina_descripcion" class="medicina_descripcion"><div class="ewTableHeaderCaption"><?php echo $medicina->descripcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="descripcion"><div><div id="elh_medicina_descripcion" class="medicina_descripcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $medicina->descripcion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($medicina->descripcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($medicina->descripcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($medicina->estado->Visible) { // estado ?>
	<?php if ($medicina->SortUrl($medicina->estado) == "") { ?>
		<th data-name="estado"><div id="elh_medicina_estado" class="medicina_estado"><div class="ewTableHeaderCaption"><?php echo $medicina->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_medicina_estado" class="medicina_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $medicina->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($medicina->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($medicina->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($medicina->idlaboratorio->Visible) { // idlaboratorio ?>
	<?php if ($medicina->SortUrl($medicina->idlaboratorio) == "") { ?>
		<th data-name="idlaboratorio"><div id="elh_medicina_idlaboratorio" class="medicina_idlaboratorio"><div class="ewTableHeaderCaption"><?php echo $medicina->idlaboratorio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idlaboratorio"><div><div id="elh_medicina_idlaboratorio" class="medicina_idlaboratorio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $medicina->idlaboratorio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($medicina->idlaboratorio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($medicina->idlaboratorio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($medicina->idhospital->Visible) { // idhospital ?>
	<?php if ($medicina->SortUrl($medicina->idhospital) == "") { ?>
		<th data-name="idhospital"><div id="elh_medicina_idhospital" class="medicina_idhospital"><div class="ewTableHeaderCaption"><?php echo $medicina->idhospital->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idhospital"><div><div id="elh_medicina_idhospital" class="medicina_idhospital">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $medicina->idhospital->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($medicina->idhospital->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($medicina->idhospital->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$medicina_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$medicina_grid->StartRec = 1;
$medicina_grid->StopRec = $medicina_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($medicina_grid->FormKeyCountName) && ($medicina->CurrentAction == "gridadd" || $medicina->CurrentAction == "gridedit" || $medicina->CurrentAction == "F")) {
		$medicina_grid->KeyCount = $objForm->GetValue($medicina_grid->FormKeyCountName);
		$medicina_grid->StopRec = $medicina_grid->StartRec + $medicina_grid->KeyCount - 1;
	}
}
$medicina_grid->RecCnt = $medicina_grid->StartRec - 1;
if ($medicina_grid->Recordset && !$medicina_grid->Recordset->EOF) {
	$medicina_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $medicina_grid->StartRec > 1)
		$medicina_grid->Recordset->Move($medicina_grid->StartRec - 1);
} elseif (!$medicina->AllowAddDeleteRow && $medicina_grid->StopRec == 0) {
	$medicina_grid->StopRec = $medicina->GridAddRowCount;
}

// Initialize aggregate
$medicina->RowType = EW_ROWTYPE_AGGREGATEINIT;
$medicina->ResetAttrs();
$medicina_grid->RenderRow();
if ($medicina->CurrentAction == "gridadd")
	$medicina_grid->RowIndex = 0;
if ($medicina->CurrentAction == "gridedit")
	$medicina_grid->RowIndex = 0;
while ($medicina_grid->RecCnt < $medicina_grid->StopRec) {
	$medicina_grid->RecCnt++;
	if (intval($medicina_grid->RecCnt) >= intval($medicina_grid->StartRec)) {
		$medicina_grid->RowCnt++;
		if ($medicina->CurrentAction == "gridadd" || $medicina->CurrentAction == "gridedit" || $medicina->CurrentAction == "F") {
			$medicina_grid->RowIndex++;
			$objForm->Index = $medicina_grid->RowIndex;
			if ($objForm->HasValue($medicina_grid->FormActionName))
				$medicina_grid->RowAction = strval($objForm->GetValue($medicina_grid->FormActionName));
			elseif ($medicina->CurrentAction == "gridadd")
				$medicina_grid->RowAction = "insert";
			else
				$medicina_grid->RowAction = "";
		}

		// Set up key count
		$medicina_grid->KeyCount = $medicina_grid->RowIndex;

		// Init row class and style
		$medicina->ResetAttrs();
		$medicina->CssClass = "";
		if ($medicina->CurrentAction == "gridadd") {
			if ($medicina->CurrentMode == "copy") {
				$medicina_grid->LoadRowValues($medicina_grid->Recordset); // Load row values
				$medicina_grid->SetRecordKey($medicina_grid->RowOldKey, $medicina_grid->Recordset); // Set old record key
			} else {
				$medicina_grid->LoadDefaultValues(); // Load default values
				$medicina_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$medicina_grid->LoadRowValues($medicina_grid->Recordset); // Load row values
		}
		$medicina->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($medicina->CurrentAction == "gridadd") // Grid add
			$medicina->RowType = EW_ROWTYPE_ADD; // Render add
		if ($medicina->CurrentAction == "gridadd" && $medicina->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$medicina_grid->RestoreCurrentRowFormValues($medicina_grid->RowIndex); // Restore form values
		if ($medicina->CurrentAction == "gridedit") { // Grid edit
			if ($medicina->EventCancelled) {
				$medicina_grid->RestoreCurrentRowFormValues($medicina_grid->RowIndex); // Restore form values
			}
			if ($medicina_grid->RowAction == "insert")
				$medicina->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$medicina->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($medicina->CurrentAction == "gridedit" && ($medicina->RowType == EW_ROWTYPE_EDIT || $medicina->RowType == EW_ROWTYPE_ADD) && $medicina->EventCancelled) // Update failed
			$medicina_grid->RestoreCurrentRowFormValues($medicina_grid->RowIndex); // Restore form values
		if ($medicina->RowType == EW_ROWTYPE_EDIT) // Edit row
			$medicina_grid->EditRowCnt++;
		if ($medicina->CurrentAction == "F") // Confirm row
			$medicina_grid->RestoreCurrentRowFormValues($medicina_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$medicina->RowAttrs = array_merge($medicina->RowAttrs, array('data-rowindex'=>$medicina_grid->RowCnt, 'id'=>'r' . $medicina_grid->RowCnt . '_medicina', 'data-rowtype'=>$medicina->RowType));

		// Render row
		$medicina_grid->RenderRow();

		// Render list options
		$medicina_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($medicina_grid->RowAction <> "delete" && $medicina_grid->RowAction <> "insertdelete" && !($medicina_grid->RowAction == "insert" && $medicina->CurrentAction == "F" && $medicina_grid->EmptyRow())) {
?>
	<tr<?php echo $medicina->RowAttributes() ?>>
<?php

// Render list options (body, left)
$medicina_grid->ListOptions->Render("body", "left", $medicina_grid->RowCnt);
?>
	<?php if ($medicina->idmedicina->Visible) { // idmedicina ?>
		<td data-name="idmedicina"<?php echo $medicina->idmedicina->CellAttributes() ?>>
<?php if ($medicina->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idmedicina" name="o<?php echo $medicina_grid->RowIndex ?>_idmedicina" id="o<?php echo $medicina_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($medicina->idmedicina->OldValue) ?>">
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_idmedicina" class="form-group medicina_idmedicina">
<span<?php echo $medicina->idmedicina->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->idmedicina->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_idmedicina" name="x<?php echo $medicina_grid->RowIndex ?>_idmedicina" id="x<?php echo $medicina_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($medicina->idmedicina->CurrentValue) ?>">
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $medicina->idmedicina->ViewAttributes() ?>>
<?php echo $medicina->idmedicina->ListViewValue() ?></span>
<input type="hidden" data-field="x_idmedicina" name="x<?php echo $medicina_grid->RowIndex ?>_idmedicina" id="x<?php echo $medicina_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($medicina->idmedicina->FormValue) ?>">
<input type="hidden" data-field="x_idmedicina" name="o<?php echo $medicina_grid->RowIndex ?>_idmedicina" id="o<?php echo $medicina_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($medicina->idmedicina->OldValue) ?>">
<?php } ?>
<a id="<?php echo $medicina_grid->PageObjName . "_row_" . $medicina_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($medicina->descripcion->Visible) { // descripcion ?>
		<td data-name="descripcion"<?php echo $medicina->descripcion->CellAttributes() ?>>
<?php if ($medicina->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_descripcion" class="form-group medicina_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $medicina_grid->RowIndex ?>_descripcion" id="x<?php echo $medicina_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($medicina->descripcion->PlaceHolder) ?>" value="<?php echo $medicina->descripcion->EditValue ?>"<?php echo $medicina->descripcion->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $medicina_grid->RowIndex ?>_descripcion" id="o<?php echo $medicina_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($medicina->descripcion->OldValue) ?>">
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_descripcion" class="form-group medicina_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $medicina_grid->RowIndex ?>_descripcion" id="x<?php echo $medicina_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($medicina->descripcion->PlaceHolder) ?>" value="<?php echo $medicina->descripcion->EditValue ?>"<?php echo $medicina->descripcion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $medicina->descripcion->ViewAttributes() ?>>
<?php echo $medicina->descripcion->ListViewValue() ?></span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $medicina_grid->RowIndex ?>_descripcion" id="x<?php echo $medicina_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($medicina->descripcion->FormValue) ?>">
<input type="hidden" data-field="x_descripcion" name="o<?php echo $medicina_grid->RowIndex ?>_descripcion" id="o<?php echo $medicina_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($medicina->descripcion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($medicina->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $medicina->estado->CellAttributes() ?>>
<?php if ($medicina->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_estado" class="form-group medicina_estado">
<select data-field="x_estado" id="x<?php echo $medicina_grid->RowIndex ?>_estado" name="x<?php echo $medicina_grid->RowIndex ?>_estado"<?php echo $medicina->estado->EditAttributes() ?>>
<?php
if (is_array($medicina->estado->EditValue)) {
	$arwrk = $medicina->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($medicina->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $medicina->estado->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $medicina_grid->RowIndex ?>_estado" id="o<?php echo $medicina_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($medicina->estado->OldValue) ?>">
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_estado" class="form-group medicina_estado">
<select data-field="x_estado" id="x<?php echo $medicina_grid->RowIndex ?>_estado" name="x<?php echo $medicina_grid->RowIndex ?>_estado"<?php echo $medicina->estado->EditAttributes() ?>>
<?php
if (is_array($medicina->estado->EditValue)) {
	$arwrk = $medicina->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($medicina->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $medicina->estado->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $medicina->estado->ViewAttributes() ?>>
<?php echo $medicina->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $medicina_grid->RowIndex ?>_estado" id="x<?php echo $medicina_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($medicina->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $medicina_grid->RowIndex ?>_estado" id="o<?php echo $medicina_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($medicina->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($medicina->idlaboratorio->Visible) { // idlaboratorio ?>
		<td data-name="idlaboratorio"<?php echo $medicina->idlaboratorio->CellAttributes() ?>>
<?php if ($medicina->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($medicina->idlaboratorio->getSessionValue() <> "") { ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_idlaboratorio" class="form-group medicina_idlaboratorio">
<span<?php echo $medicina->idlaboratorio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->idlaboratorio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" name="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="<?php echo ew_HtmlEncode($medicina->idlaboratorio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_idlaboratorio" class="form-group medicina_idlaboratorio">
<select data-field="x_idlaboratorio" id="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" name="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio"<?php echo $medicina->idlaboratorio->EditAttributes() ?>>
<?php
if (is_array($medicina->idlaboratorio->EditValue)) {
	$arwrk = $medicina->idlaboratorio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($medicina->idlaboratorio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $medicina->idlaboratorio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idlaboratorio`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `laboratorio`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $medicina->Lookup_Selecting($medicina->idlaboratorio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" id="s_x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idlaboratorio` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idlaboratorio" name="o<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" id="o<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="<?php echo ew_HtmlEncode($medicina->idlaboratorio->OldValue) ?>">
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($medicina->idlaboratorio->getSessionValue() <> "") { ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_idlaboratorio" class="form-group medicina_idlaboratorio">
<span<?php echo $medicina->idlaboratorio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->idlaboratorio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" name="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="<?php echo ew_HtmlEncode($medicina->idlaboratorio->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_idlaboratorio" class="form-group medicina_idlaboratorio">
<select data-field="x_idlaboratorio" id="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" name="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio"<?php echo $medicina->idlaboratorio->EditAttributes() ?>>
<?php
if (is_array($medicina->idlaboratorio->EditValue)) {
	$arwrk = $medicina->idlaboratorio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($medicina->idlaboratorio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $medicina->idlaboratorio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idlaboratorio`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `laboratorio`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $medicina->Lookup_Selecting($medicina->idlaboratorio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" id="s_x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idlaboratorio` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $medicina->idlaboratorio->ViewAttributes() ?>>
<?php echo $medicina->idlaboratorio->ListViewValue() ?></span>
<input type="hidden" data-field="x_idlaboratorio" name="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" id="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="<?php echo ew_HtmlEncode($medicina->idlaboratorio->FormValue) ?>">
<input type="hidden" data-field="x_idlaboratorio" name="o<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" id="o<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="<?php echo ew_HtmlEncode($medicina->idlaboratorio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($medicina->idhospital->Visible) { // idhospital ?>
		<td data-name="idhospital"<?php echo $medicina->idhospital->CellAttributes() ?>>
<?php if ($medicina->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_idhospital" class="form-group medicina_idhospital">
<input type="text" data-field="x_idhospital" name="x<?php echo $medicina_grid->RowIndex ?>_idhospital" id="x<?php echo $medicina_grid->RowIndex ?>_idhospital" size="30" placeholder="<?php echo ew_HtmlEncode($medicina->idhospital->PlaceHolder) ?>" value="<?php echo $medicina->idhospital->EditValue ?>"<?php echo $medicina->idhospital->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_idhospital" name="o<?php echo $medicina_grid->RowIndex ?>_idhospital" id="o<?php echo $medicina_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($medicina->idhospital->OldValue) ?>">
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $medicina_grid->RowCnt ?>_medicina_idhospital" class="form-group medicina_idhospital">
<input type="text" data-field="x_idhospital" name="x<?php echo $medicina_grid->RowIndex ?>_idhospital" id="x<?php echo $medicina_grid->RowIndex ?>_idhospital" size="30" placeholder="<?php echo ew_HtmlEncode($medicina->idhospital->PlaceHolder) ?>" value="<?php echo $medicina->idhospital->EditValue ?>"<?php echo $medicina->idhospital->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($medicina->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $medicina->idhospital->ViewAttributes() ?>>
<?php echo $medicina->idhospital->ListViewValue() ?></span>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $medicina_grid->RowIndex ?>_idhospital" id="x<?php echo $medicina_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($medicina->idhospital->FormValue) ?>">
<input type="hidden" data-field="x_idhospital" name="o<?php echo $medicina_grid->RowIndex ?>_idhospital" id="o<?php echo $medicina_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($medicina->idhospital->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$medicina_grid->ListOptions->Render("body", "right", $medicina_grid->RowCnt);
?>
	</tr>
<?php if ($medicina->RowType == EW_ROWTYPE_ADD || $medicina->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fmedicinagrid.UpdateOpts(<?php echo $medicina_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($medicina->CurrentAction <> "gridadd" || $medicina->CurrentMode == "copy")
		if (!$medicina_grid->Recordset->EOF) $medicina_grid->Recordset->MoveNext();
}
?>
<?php
	if ($medicina->CurrentMode == "add" || $medicina->CurrentMode == "copy" || $medicina->CurrentMode == "edit") {
		$medicina_grid->RowIndex = '$rowindex$';
		$medicina_grid->LoadDefaultValues();

		// Set row properties
		$medicina->ResetAttrs();
		$medicina->RowAttrs = array_merge($medicina->RowAttrs, array('data-rowindex'=>$medicina_grid->RowIndex, 'id'=>'r0_medicina', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($medicina->RowAttrs["class"], "ewTemplate");
		$medicina->RowType = EW_ROWTYPE_ADD;

		// Render row
		$medicina_grid->RenderRow();

		// Render list options
		$medicina_grid->RenderListOptions();
		$medicina_grid->StartRowCnt = 0;
?>
	<tr<?php echo $medicina->RowAttributes() ?>>
<?php

// Render list options (body, left)
$medicina_grid->ListOptions->Render("body", "left", $medicina_grid->RowIndex);
?>
	<?php if ($medicina->idmedicina->Visible) { // idmedicina ?>
		<td>
<?php if ($medicina->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_medicina_idmedicina" class="form-group medicina_idmedicina">
<span<?php echo $medicina->idmedicina->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->idmedicina->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idmedicina" name="x<?php echo $medicina_grid->RowIndex ?>_idmedicina" id="x<?php echo $medicina_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($medicina->idmedicina->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idmedicina" name="o<?php echo $medicina_grid->RowIndex ?>_idmedicina" id="o<?php echo $medicina_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($medicina->idmedicina->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($medicina->descripcion->Visible) { // descripcion ?>
		<td>
<?php if ($medicina->CurrentAction <> "F") { ?>
<span id="el$rowindex$_medicina_descripcion" class="form-group medicina_descripcion">
<input type="text" data-field="x_descripcion" name="x<?php echo $medicina_grid->RowIndex ?>_descripcion" id="x<?php echo $medicina_grid->RowIndex ?>_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($medicina->descripcion->PlaceHolder) ?>" value="<?php echo $medicina->descripcion->EditValue ?>"<?php echo $medicina->descripcion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_medicina_descripcion" class="form-group medicina_descripcion">
<span<?php echo $medicina->descripcion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->descripcion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_descripcion" name="x<?php echo $medicina_grid->RowIndex ?>_descripcion" id="x<?php echo $medicina_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($medicina->descripcion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_descripcion" name="o<?php echo $medicina_grid->RowIndex ?>_descripcion" id="o<?php echo $medicina_grid->RowIndex ?>_descripcion" value="<?php echo ew_HtmlEncode($medicina->descripcion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($medicina->estado->Visible) { // estado ?>
		<td>
<?php if ($medicina->CurrentAction <> "F") { ?>
<span id="el$rowindex$_medicina_estado" class="form-group medicina_estado">
<select data-field="x_estado" id="x<?php echo $medicina_grid->RowIndex ?>_estado" name="x<?php echo $medicina_grid->RowIndex ?>_estado"<?php echo $medicina->estado->EditAttributes() ?>>
<?php
if (is_array($medicina->estado->EditValue)) {
	$arwrk = $medicina->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($medicina->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $medicina->estado->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_medicina_estado" class="form-group medicina_estado">
<span<?php echo $medicina->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $medicina_grid->RowIndex ?>_estado" id="x<?php echo $medicina_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($medicina->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $medicina_grid->RowIndex ?>_estado" id="o<?php echo $medicina_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($medicina->estado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($medicina->idlaboratorio->Visible) { // idlaboratorio ?>
		<td>
<?php if ($medicina->CurrentAction <> "F") { ?>
<?php if ($medicina->idlaboratorio->getSessionValue() <> "") { ?>
<span id="el$rowindex$_medicina_idlaboratorio" class="form-group medicina_idlaboratorio">
<span<?php echo $medicina->idlaboratorio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->idlaboratorio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" name="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="<?php echo ew_HtmlEncode($medicina->idlaboratorio->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_medicina_idlaboratorio" class="form-group medicina_idlaboratorio">
<select data-field="x_idlaboratorio" id="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" name="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio"<?php echo $medicina->idlaboratorio->EditAttributes() ?>>
<?php
if (is_array($medicina->idlaboratorio->EditValue)) {
	$arwrk = $medicina->idlaboratorio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($medicina->idlaboratorio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $medicina->idlaboratorio->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idlaboratorio`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `laboratorio`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $medicina->Lookup_Selecting($medicina->idlaboratorio, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" id="s_x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idlaboratorio` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_medicina_idlaboratorio" class="form-group medicina_idlaboratorio">
<span<?php echo $medicina->idlaboratorio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->idlaboratorio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idlaboratorio" name="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" id="x<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="<?php echo ew_HtmlEncode($medicina->idlaboratorio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idlaboratorio" name="o<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" id="o<?php echo $medicina_grid->RowIndex ?>_idlaboratorio" value="<?php echo ew_HtmlEncode($medicina->idlaboratorio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($medicina->idhospital->Visible) { // idhospital ?>
		<td>
<?php if ($medicina->CurrentAction <> "F") { ?>
<span id="el$rowindex$_medicina_idhospital" class="form-group medicina_idhospital">
<input type="text" data-field="x_idhospital" name="x<?php echo $medicina_grid->RowIndex ?>_idhospital" id="x<?php echo $medicina_grid->RowIndex ?>_idhospital" size="30" placeholder="<?php echo ew_HtmlEncode($medicina->idhospital->PlaceHolder) ?>" value="<?php echo $medicina->idhospital->EditValue ?>"<?php echo $medicina->idhospital->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_medicina_idhospital" class="form-group medicina_idhospital">
<span<?php echo $medicina->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $medicina_grid->RowIndex ?>_idhospital" id="x<?php echo $medicina_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($medicina->idhospital->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idhospital" name="o<?php echo $medicina_grid->RowIndex ?>_idhospital" id="o<?php echo $medicina_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($medicina->idhospital->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$medicina_grid->ListOptions->Render("body", "right", $medicina_grid->RowCnt);
?>
<script type="text/javascript">
fmedicinagrid.UpdateOpts(<?php echo $medicina_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($medicina->CurrentMode == "add" || $medicina->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $medicina_grid->FormKeyCountName ?>" id="<?php echo $medicina_grid->FormKeyCountName ?>" value="<?php echo $medicina_grid->KeyCount ?>">
<?php echo $medicina_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($medicina->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $medicina_grid->FormKeyCountName ?>" id="<?php echo $medicina_grid->FormKeyCountName ?>" value="<?php echo $medicina_grid->KeyCount ?>">
<?php echo $medicina_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($medicina->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fmedicinagrid">
</div>
<?php

// Close recordset
if ($medicina_grid->Recordset)
	$medicina_grid->Recordset->Close();
?>
<?php if ($medicina_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($medicina_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($medicina_grid->TotalRecs == 0 && $medicina->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($medicina_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($medicina->Export == "") { ?>
<script type="text/javascript">
fmedicinagrid.Init();
</script>
<?php } ?>
<?php
$medicina_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$medicina_grid->Page_Terminate();
?>

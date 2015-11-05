<?php

// Create page object
if (!isset($pais_grid)) $pais_grid = new cpais_grid();

// Page init
$pais_grid->Page_Init();

// Page main
$pais_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pais_grid->Page_Render();
?>
<?php if ($pais->Export == "") { ?>
<script type="text/javascript">

// Page object
var pais_grid = new ew_Page("pais_grid");
pais_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = pais_grid.PageID; // For backward compatibility

// Form object
var fpaisgrid = new ew_Form("fpaisgrid");
fpaisgrid.FormKeyCountName = '<?php echo $pais_grid->FormKeyCountName ?>';

// Validate form
fpaisgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idcontinente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pais->idcontinente->FldCaption(), $pais->idcontinente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pais->estado->FldCaption(), $pais->estado->ReqErrMsg)) ?>");

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
fpaisgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nombre_oficial", false)) return false;
	if (ew_ValueChanged(fobj, infix, "gentilicio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "flag", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idcontinente", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	return true;
}

// Form_CustomValidate event
fpaisgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpaisgrid.ValidateRequired = true;
<?php } else { ?>
fpaisgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpaisgrid.Lists["x_idcontinente"] = {"LinkField":"x_idcontinente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($pais->CurrentAction == "gridadd") {
	if ($pais->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$pais_grid->TotalRecs = $pais->SelectRecordCount();
			$pais_grid->Recordset = $pais_grid->LoadRecordset($pais_grid->StartRec-1, $pais_grid->DisplayRecs);
		} else {
			if ($pais_grid->Recordset = $pais_grid->LoadRecordset())
				$pais_grid->TotalRecs = $pais_grid->Recordset->RecordCount();
		}
		$pais_grid->StartRec = 1;
		$pais_grid->DisplayRecs = $pais_grid->TotalRecs;
	} else {
		$pais->CurrentFilter = "0=1";
		$pais_grid->StartRec = 1;
		$pais_grid->DisplayRecs = $pais->GridAddRowCount;
	}
	$pais_grid->TotalRecs = $pais_grid->DisplayRecs;
	$pais_grid->StopRec = $pais_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$pais_grid->TotalRecs = $pais->SelectRecordCount();
	} else {
		if ($pais_grid->Recordset = $pais_grid->LoadRecordset())
			$pais_grid->TotalRecs = $pais_grid->Recordset->RecordCount();
	}
	$pais_grid->StartRec = 1;
	$pais_grid->DisplayRecs = $pais_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$pais_grid->Recordset = $pais_grid->LoadRecordset($pais_grid->StartRec-1, $pais_grid->DisplayRecs);

	// Set no record found message
	if ($pais->CurrentAction == "" && $pais_grid->TotalRecs == 0) {
		if ($pais_grid->SearchWhere == "0=101")
			$pais_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pais_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$pais_grid->RenderOtherOptions();
?>
<?php $pais_grid->ShowPageHeader(); ?>
<?php
$pais_grid->ShowMessage();
?>
<?php if ($pais_grid->TotalRecs > 0 || $pais->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fpaisgrid" class="ewForm form-inline">
<div id="gmp_pais" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_paisgrid" class="table ewTable">
<?php echo $pais->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$pais_grid->RenderListOptions();

// Render list options (header, left)
$pais_grid->ListOptions->Render("header", "left");
?>
<?php if ($pais->nombre->Visible) { // nombre ?>
	<?php if ($pais->SortUrl($pais->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_pais_nombre" class="pais_nombre"><div class="ewTableHeaderCaption"><?php echo $pais->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div><div id="elh_pais_nombre" class="pais_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pais->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pais->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pais->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pais->nombre_oficial->Visible) { // nombre oficial ?>
	<?php if ($pais->SortUrl($pais->nombre_oficial) == "") { ?>
		<th data-name="nombre_oficial"><div id="elh_pais_nombre_oficial" class="pais_nombre_oficial"><div class="ewTableHeaderCaption"><?php echo $pais->nombre_oficial->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre_oficial"><div><div id="elh_pais_nombre_oficial" class="pais_nombre_oficial">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pais->nombre_oficial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pais->nombre_oficial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pais->nombre_oficial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pais->gentilicio->Visible) { // gentilicio ?>
	<?php if ($pais->SortUrl($pais->gentilicio) == "") { ?>
		<th data-name="gentilicio"><div id="elh_pais_gentilicio" class="pais_gentilicio"><div class="ewTableHeaderCaption"><?php echo $pais->gentilicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gentilicio"><div><div id="elh_pais_gentilicio" class="pais_gentilicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pais->gentilicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pais->gentilicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pais->gentilicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pais->flag->Visible) { // flag ?>
	<?php if ($pais->SortUrl($pais->flag) == "") { ?>
		<th data-name="flag"><div id="elh_pais_flag" class="pais_flag"><div class="ewTableHeaderCaption"><?php echo $pais->flag->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="flag"><div><div id="elh_pais_flag" class="pais_flag">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pais->flag->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pais->flag->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pais->flag->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pais->idcontinente->Visible) { // idcontinente ?>
	<?php if ($pais->SortUrl($pais->idcontinente) == "") { ?>
		<th data-name="idcontinente"><div id="elh_pais_idcontinente" class="pais_idcontinente"><div class="ewTableHeaderCaption"><?php echo $pais->idcontinente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idcontinente"><div><div id="elh_pais_idcontinente" class="pais_idcontinente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pais->idcontinente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pais->idcontinente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pais->idcontinente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pais->estado->Visible) { // estado ?>
	<?php if ($pais->SortUrl($pais->estado) == "") { ?>
		<th data-name="estado"><div id="elh_pais_estado" class="pais_estado"><div class="ewTableHeaderCaption"><?php echo $pais->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_pais_estado" class="pais_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pais->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pais->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pais->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pais_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$pais_grid->StartRec = 1;
$pais_grid->StopRec = $pais_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pais_grid->FormKeyCountName) && ($pais->CurrentAction == "gridadd" || $pais->CurrentAction == "gridedit" || $pais->CurrentAction == "F")) {
		$pais_grid->KeyCount = $objForm->GetValue($pais_grid->FormKeyCountName);
		$pais_grid->StopRec = $pais_grid->StartRec + $pais_grid->KeyCount - 1;
	}
}
$pais_grid->RecCnt = $pais_grid->StartRec - 1;
if ($pais_grid->Recordset && !$pais_grid->Recordset->EOF) {
	$pais_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $pais_grid->StartRec > 1)
		$pais_grid->Recordset->Move($pais_grid->StartRec - 1);
} elseif (!$pais->AllowAddDeleteRow && $pais_grid->StopRec == 0) {
	$pais_grid->StopRec = $pais->GridAddRowCount;
}

// Initialize aggregate
$pais->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pais->ResetAttrs();
$pais_grid->RenderRow();
if ($pais->CurrentAction == "gridadd")
	$pais_grid->RowIndex = 0;
if ($pais->CurrentAction == "gridedit")
	$pais_grid->RowIndex = 0;
while ($pais_grid->RecCnt < $pais_grid->StopRec) {
	$pais_grid->RecCnt++;
	if (intval($pais_grid->RecCnt) >= intval($pais_grid->StartRec)) {
		$pais_grid->RowCnt++;
		if ($pais->CurrentAction == "gridadd" || $pais->CurrentAction == "gridedit" || $pais->CurrentAction == "F") {
			$pais_grid->RowIndex++;
			$objForm->Index = $pais_grid->RowIndex;
			if ($objForm->HasValue($pais_grid->FormActionName))
				$pais_grid->RowAction = strval($objForm->GetValue($pais_grid->FormActionName));
			elseif ($pais->CurrentAction == "gridadd")
				$pais_grid->RowAction = "insert";
			else
				$pais_grid->RowAction = "";
		}

		// Set up key count
		$pais_grid->KeyCount = $pais_grid->RowIndex;

		// Init row class and style
		$pais->ResetAttrs();
		$pais->CssClass = "";
		if ($pais->CurrentAction == "gridadd") {
			if ($pais->CurrentMode == "copy") {
				$pais_grid->LoadRowValues($pais_grid->Recordset); // Load row values
				$pais_grid->SetRecordKey($pais_grid->RowOldKey, $pais_grid->Recordset); // Set old record key
			} else {
				$pais_grid->LoadDefaultValues(); // Load default values
				$pais_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$pais_grid->LoadRowValues($pais_grid->Recordset); // Load row values
		}
		$pais->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pais->CurrentAction == "gridadd") // Grid add
			$pais->RowType = EW_ROWTYPE_ADD; // Render add
		if ($pais->CurrentAction == "gridadd" && $pais->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$pais_grid->RestoreCurrentRowFormValues($pais_grid->RowIndex); // Restore form values
		if ($pais->CurrentAction == "gridedit") { // Grid edit
			if ($pais->EventCancelled) {
				$pais_grid->RestoreCurrentRowFormValues($pais_grid->RowIndex); // Restore form values
			}
			if ($pais_grid->RowAction == "insert")
				$pais->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$pais->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($pais->CurrentAction == "gridedit" && ($pais->RowType == EW_ROWTYPE_EDIT || $pais->RowType == EW_ROWTYPE_ADD) && $pais->EventCancelled) // Update failed
			$pais_grid->RestoreCurrentRowFormValues($pais_grid->RowIndex); // Restore form values
		if ($pais->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pais_grid->EditRowCnt++;
		if ($pais->CurrentAction == "F") // Confirm row
			$pais_grid->RestoreCurrentRowFormValues($pais_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$pais->RowAttrs = array_merge($pais->RowAttrs, array('data-rowindex'=>$pais_grid->RowCnt, 'id'=>'r' . $pais_grid->RowCnt . '_pais', 'data-rowtype'=>$pais->RowType));

		// Render row
		$pais_grid->RenderRow();

		// Render list options
		$pais_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($pais_grid->RowAction <> "delete" && $pais_grid->RowAction <> "insertdelete" && !($pais_grid->RowAction == "insert" && $pais->CurrentAction == "F" && $pais_grid->EmptyRow())) {
?>
	<tr<?php echo $pais->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pais_grid->ListOptions->Render("body", "left", $pais_grid->RowCnt);
?>
	<?php if ($pais->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $pais->nombre->CellAttributes() ?>>
<?php if ($pais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_nombre" class="form-group pais_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $pais_grid->RowIndex ?>_nombre" id="x<?php echo $pais_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->nombre->PlaceHolder) ?>" value="<?php echo $pais->nombre->EditValue ?>"<?php echo $pais->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nombre" name="o<?php echo $pais_grid->RowIndex ?>_nombre" id="o<?php echo $pais_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($pais->nombre->OldValue) ?>">
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_nombre" class="form-group pais_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $pais_grid->RowIndex ?>_nombre" id="x<?php echo $pais_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->nombre->PlaceHolder) ?>" value="<?php echo $pais->nombre->EditValue ?>"<?php echo $pais->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pais->nombre->ViewAttributes() ?>>
<?php echo $pais->nombre->ListViewValue() ?></span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $pais_grid->RowIndex ?>_nombre" id="x<?php echo $pais_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($pais->nombre->FormValue) ?>">
<input type="hidden" data-field="x_nombre" name="o<?php echo $pais_grid->RowIndex ?>_nombre" id="o<?php echo $pais_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($pais->nombre->OldValue) ?>">
<?php } ?>
<a id="<?php echo $pais_grid->PageObjName . "_row_" . $pais_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idpais" name="x<?php echo $pais_grid->RowIndex ?>_idpais" id="x<?php echo $pais_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($pais->idpais->CurrentValue) ?>">
<input type="hidden" data-field="x_idpais" name="o<?php echo $pais_grid->RowIndex ?>_idpais" id="o<?php echo $pais_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($pais->idpais->OldValue) ?>">
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_EDIT || $pais->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_idpais" name="x<?php echo $pais_grid->RowIndex ?>_idpais" id="x<?php echo $pais_grid->RowIndex ?>_idpais" value="<?php echo ew_HtmlEncode($pais->idpais->CurrentValue) ?>">
<?php } ?>
	<?php if ($pais->nombre_oficial->Visible) { // nombre oficial ?>
		<td data-name="nombre_oficial"<?php echo $pais->nombre_oficial->CellAttributes() ?>>
<?php if ($pais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_nombre_oficial" class="form-group pais_nombre_oficial">
<input type="text" data-field="x_nombre_oficial" name="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" id="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->nombre_oficial->PlaceHolder) ?>" value="<?php echo $pais->nombre_oficial->EditValue ?>"<?php echo $pais->nombre_oficial->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nombre_oficial" name="o<?php echo $pais_grid->RowIndex ?>_nombre_oficial" id="o<?php echo $pais_grid->RowIndex ?>_nombre_oficial" value="<?php echo ew_HtmlEncode($pais->nombre_oficial->OldValue) ?>">
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_nombre_oficial" class="form-group pais_nombre_oficial">
<input type="text" data-field="x_nombre_oficial" name="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" id="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->nombre_oficial->PlaceHolder) ?>" value="<?php echo $pais->nombre_oficial->EditValue ?>"<?php echo $pais->nombre_oficial->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pais->nombre_oficial->ViewAttributes() ?>>
<?php echo $pais->nombre_oficial->ListViewValue() ?></span>
<input type="hidden" data-field="x_nombre_oficial" name="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" id="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" value="<?php echo ew_HtmlEncode($pais->nombre_oficial->FormValue) ?>">
<input type="hidden" data-field="x_nombre_oficial" name="o<?php echo $pais_grid->RowIndex ?>_nombre_oficial" id="o<?php echo $pais_grid->RowIndex ?>_nombre_oficial" value="<?php echo ew_HtmlEncode($pais->nombre_oficial->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pais->gentilicio->Visible) { // gentilicio ?>
		<td data-name="gentilicio"<?php echo $pais->gentilicio->CellAttributes() ?>>
<?php if ($pais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_gentilicio" class="form-group pais_gentilicio">
<input type="text" data-field="x_gentilicio" name="x<?php echo $pais_grid->RowIndex ?>_gentilicio" id="x<?php echo $pais_grid->RowIndex ?>_gentilicio" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->gentilicio->PlaceHolder) ?>" value="<?php echo $pais->gentilicio->EditValue ?>"<?php echo $pais->gentilicio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_gentilicio" name="o<?php echo $pais_grid->RowIndex ?>_gentilicio" id="o<?php echo $pais_grid->RowIndex ?>_gentilicio" value="<?php echo ew_HtmlEncode($pais->gentilicio->OldValue) ?>">
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_gentilicio" class="form-group pais_gentilicio">
<input type="text" data-field="x_gentilicio" name="x<?php echo $pais_grid->RowIndex ?>_gentilicio" id="x<?php echo $pais_grid->RowIndex ?>_gentilicio" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->gentilicio->PlaceHolder) ?>" value="<?php echo $pais->gentilicio->EditValue ?>"<?php echo $pais->gentilicio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pais->gentilicio->ViewAttributes() ?>>
<?php echo $pais->gentilicio->ListViewValue() ?></span>
<input type="hidden" data-field="x_gentilicio" name="x<?php echo $pais_grid->RowIndex ?>_gentilicio" id="x<?php echo $pais_grid->RowIndex ?>_gentilicio" value="<?php echo ew_HtmlEncode($pais->gentilicio->FormValue) ?>">
<input type="hidden" data-field="x_gentilicio" name="o<?php echo $pais_grid->RowIndex ?>_gentilicio" id="o<?php echo $pais_grid->RowIndex ?>_gentilicio" value="<?php echo ew_HtmlEncode($pais->gentilicio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pais->flag->Visible) { // flag ?>
		<td data-name="flag"<?php echo $pais->flag->CellAttributes() ?>>
<?php if ($pais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_flag" class="form-group pais_flag">
<input type="text" data-field="x_flag" name="x<?php echo $pais_grid->RowIndex ?>_flag" id="x<?php echo $pais_grid->RowIndex ?>_flag" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->flag->PlaceHolder) ?>" value="<?php echo $pais->flag->EditValue ?>"<?php echo $pais->flag->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_flag" name="o<?php echo $pais_grid->RowIndex ?>_flag" id="o<?php echo $pais_grid->RowIndex ?>_flag" value="<?php echo ew_HtmlEncode($pais->flag->OldValue) ?>">
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_flag" class="form-group pais_flag">
<input type="text" data-field="x_flag" name="x<?php echo $pais_grid->RowIndex ?>_flag" id="x<?php echo $pais_grid->RowIndex ?>_flag" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->flag->PlaceHolder) ?>" value="<?php echo $pais->flag->EditValue ?>"<?php echo $pais->flag->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pais->flag->ViewAttributes() ?>>
<?php echo $pais->flag->ListViewValue() ?></span>
<input type="hidden" data-field="x_flag" name="x<?php echo $pais_grid->RowIndex ?>_flag" id="x<?php echo $pais_grid->RowIndex ?>_flag" value="<?php echo ew_HtmlEncode($pais->flag->FormValue) ?>">
<input type="hidden" data-field="x_flag" name="o<?php echo $pais_grid->RowIndex ?>_flag" id="o<?php echo $pais_grid->RowIndex ?>_flag" value="<?php echo ew_HtmlEncode($pais->flag->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pais->idcontinente->Visible) { // idcontinente ?>
		<td data-name="idcontinente"<?php echo $pais->idcontinente->CellAttributes() ?>>
<?php if ($pais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($pais->idcontinente->getSessionValue() <> "") { ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_idcontinente" class="form-group pais_idcontinente">
<span<?php echo $pais->idcontinente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->idcontinente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $pais_grid->RowIndex ?>_idcontinente" name="x<?php echo $pais_grid->RowIndex ?>_idcontinente" value="<?php echo ew_HtmlEncode($pais->idcontinente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_idcontinente" class="form-group pais_idcontinente">
<select data-field="x_idcontinente" id="x<?php echo $pais_grid->RowIndex ?>_idcontinente" name="x<?php echo $pais_grid->RowIndex ?>_idcontinente"<?php echo $pais->idcontinente->EditAttributes() ?>>
<?php
if (is_array($pais->idcontinente->EditValue)) {
	$arwrk = $pais->idcontinente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pais->idcontinente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pais->idcontinente->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcontinente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `continente`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $pais->Lookup_Selecting($pais->idcontinente, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $pais_grid->RowIndex ?>_idcontinente" id="s_x<?php echo $pais_grid->RowIndex ?>_idcontinente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcontinente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idcontinente" name="o<?php echo $pais_grid->RowIndex ?>_idcontinente" id="o<?php echo $pais_grid->RowIndex ?>_idcontinente" value="<?php echo ew_HtmlEncode($pais->idcontinente->OldValue) ?>">
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($pais->idcontinente->getSessionValue() <> "") { ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_idcontinente" class="form-group pais_idcontinente">
<span<?php echo $pais->idcontinente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->idcontinente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $pais_grid->RowIndex ?>_idcontinente" name="x<?php echo $pais_grid->RowIndex ?>_idcontinente" value="<?php echo ew_HtmlEncode($pais->idcontinente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_idcontinente" class="form-group pais_idcontinente">
<select data-field="x_idcontinente" id="x<?php echo $pais_grid->RowIndex ?>_idcontinente" name="x<?php echo $pais_grid->RowIndex ?>_idcontinente"<?php echo $pais->idcontinente->EditAttributes() ?>>
<?php
if (is_array($pais->idcontinente->EditValue)) {
	$arwrk = $pais->idcontinente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pais->idcontinente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pais->idcontinente->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcontinente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `continente`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $pais->Lookup_Selecting($pais->idcontinente, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $pais_grid->RowIndex ?>_idcontinente" id="s_x<?php echo $pais_grid->RowIndex ?>_idcontinente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcontinente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pais->idcontinente->ViewAttributes() ?>>
<?php echo $pais->idcontinente->ListViewValue() ?></span>
<input type="hidden" data-field="x_idcontinente" name="x<?php echo $pais_grid->RowIndex ?>_idcontinente" id="x<?php echo $pais_grid->RowIndex ?>_idcontinente" value="<?php echo ew_HtmlEncode($pais->idcontinente->FormValue) ?>">
<input type="hidden" data-field="x_idcontinente" name="o<?php echo $pais_grid->RowIndex ?>_idcontinente" id="o<?php echo $pais_grid->RowIndex ?>_idcontinente" value="<?php echo ew_HtmlEncode($pais->idcontinente->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pais->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $pais->estado->CellAttributes() ?>>
<?php if ($pais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_estado" class="form-group pais_estado">
<div id="tp_x<?php echo $pais_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $pais_grid->RowIndex ?>_estado" id="x<?php echo $pais_grid->RowIndex ?>_estado" value="{value}"<?php echo $pais->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $pais_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $pais->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pais->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $pais_grid->RowIndex ?>_estado" id="x<?php echo $pais_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $pais->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $pais->estado->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $pais_grid->RowIndex ?>_estado" id="o<?php echo $pais_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($pais->estado->OldValue) ?>">
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pais_grid->RowCnt ?>_pais_estado" class="form-group pais_estado">
<div id="tp_x<?php echo $pais_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $pais_grid->RowIndex ?>_estado" id="x<?php echo $pais_grid->RowIndex ?>_estado" value="{value}"<?php echo $pais->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $pais_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $pais->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pais->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $pais_grid->RowIndex ?>_estado" id="x<?php echo $pais_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $pais->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $pais->estado->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($pais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pais->estado->ViewAttributes() ?>>
<?php echo $pais->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $pais_grid->RowIndex ?>_estado" id="x<?php echo $pais_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($pais->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $pais_grid->RowIndex ?>_estado" id="o<?php echo $pais_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($pais->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pais_grid->ListOptions->Render("body", "right", $pais_grid->RowCnt);
?>
	</tr>
<?php if ($pais->RowType == EW_ROWTYPE_ADD || $pais->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpaisgrid.UpdateOpts(<?php echo $pais_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($pais->CurrentAction <> "gridadd" || $pais->CurrentMode == "copy")
		if (!$pais_grid->Recordset->EOF) $pais_grid->Recordset->MoveNext();
}
?>
<?php
	if ($pais->CurrentMode == "add" || $pais->CurrentMode == "copy" || $pais->CurrentMode == "edit") {
		$pais_grid->RowIndex = '$rowindex$';
		$pais_grid->LoadDefaultValues();

		// Set row properties
		$pais->ResetAttrs();
		$pais->RowAttrs = array_merge($pais->RowAttrs, array('data-rowindex'=>$pais_grid->RowIndex, 'id'=>'r0_pais', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($pais->RowAttrs["class"], "ewTemplate");
		$pais->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pais_grid->RenderRow();

		// Render list options
		$pais_grid->RenderListOptions();
		$pais_grid->StartRowCnt = 0;
?>
	<tr<?php echo $pais->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pais_grid->ListOptions->Render("body", "left", $pais_grid->RowIndex);
?>
	<?php if ($pais->nombre->Visible) { // nombre ?>
		<td>
<?php if ($pais->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pais_nombre" class="form-group pais_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $pais_grid->RowIndex ?>_nombre" id="x<?php echo $pais_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->nombre->PlaceHolder) ?>" value="<?php echo $pais->nombre->EditValue ?>"<?php echo $pais->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pais_nombre" class="form-group pais_nombre">
<span<?php echo $pais->nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $pais_grid->RowIndex ?>_nombre" id="x<?php echo $pais_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($pais->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nombre" name="o<?php echo $pais_grid->RowIndex ?>_nombre" id="o<?php echo $pais_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($pais->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pais->nombre_oficial->Visible) { // nombre oficial ?>
		<td>
<?php if ($pais->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pais_nombre_oficial" class="form-group pais_nombre_oficial">
<input type="text" data-field="x_nombre_oficial" name="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" id="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->nombre_oficial->PlaceHolder) ?>" value="<?php echo $pais->nombre_oficial->EditValue ?>"<?php echo $pais->nombre_oficial->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pais_nombre_oficial" class="form-group pais_nombre_oficial">
<span<?php echo $pais->nombre_oficial->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->nombre_oficial->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_nombre_oficial" name="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" id="x<?php echo $pais_grid->RowIndex ?>_nombre_oficial" value="<?php echo ew_HtmlEncode($pais->nombre_oficial->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nombre_oficial" name="o<?php echo $pais_grid->RowIndex ?>_nombre_oficial" id="o<?php echo $pais_grid->RowIndex ?>_nombre_oficial" value="<?php echo ew_HtmlEncode($pais->nombre_oficial->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pais->gentilicio->Visible) { // gentilicio ?>
		<td>
<?php if ($pais->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pais_gentilicio" class="form-group pais_gentilicio">
<input type="text" data-field="x_gentilicio" name="x<?php echo $pais_grid->RowIndex ?>_gentilicio" id="x<?php echo $pais_grid->RowIndex ?>_gentilicio" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->gentilicio->PlaceHolder) ?>" value="<?php echo $pais->gentilicio->EditValue ?>"<?php echo $pais->gentilicio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pais_gentilicio" class="form-group pais_gentilicio">
<span<?php echo $pais->gentilicio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->gentilicio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_gentilicio" name="x<?php echo $pais_grid->RowIndex ?>_gentilicio" id="x<?php echo $pais_grid->RowIndex ?>_gentilicio" value="<?php echo ew_HtmlEncode($pais->gentilicio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_gentilicio" name="o<?php echo $pais_grid->RowIndex ?>_gentilicio" id="o<?php echo $pais_grid->RowIndex ?>_gentilicio" value="<?php echo ew_HtmlEncode($pais->gentilicio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pais->flag->Visible) { // flag ?>
		<td>
<?php if ($pais->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pais_flag" class="form-group pais_flag">
<input type="text" data-field="x_flag" name="x<?php echo $pais_grid->RowIndex ?>_flag" id="x<?php echo $pais_grid->RowIndex ?>_flag" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->flag->PlaceHolder) ?>" value="<?php echo $pais->flag->EditValue ?>"<?php echo $pais->flag->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pais_flag" class="form-group pais_flag">
<span<?php echo $pais->flag->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->flag->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_flag" name="x<?php echo $pais_grid->RowIndex ?>_flag" id="x<?php echo $pais_grid->RowIndex ?>_flag" value="<?php echo ew_HtmlEncode($pais->flag->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_flag" name="o<?php echo $pais_grid->RowIndex ?>_flag" id="o<?php echo $pais_grid->RowIndex ?>_flag" value="<?php echo ew_HtmlEncode($pais->flag->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pais->idcontinente->Visible) { // idcontinente ?>
		<td>
<?php if ($pais->CurrentAction <> "F") { ?>
<?php if ($pais->idcontinente->getSessionValue() <> "") { ?>
<span id="el$rowindex$_pais_idcontinente" class="form-group pais_idcontinente">
<span<?php echo $pais->idcontinente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->idcontinente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $pais_grid->RowIndex ?>_idcontinente" name="x<?php echo $pais_grid->RowIndex ?>_idcontinente" value="<?php echo ew_HtmlEncode($pais->idcontinente->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_pais_idcontinente" class="form-group pais_idcontinente">
<select data-field="x_idcontinente" id="x<?php echo $pais_grid->RowIndex ?>_idcontinente" name="x<?php echo $pais_grid->RowIndex ?>_idcontinente"<?php echo $pais->idcontinente->EditAttributes() ?>>
<?php
if (is_array($pais->idcontinente->EditValue)) {
	$arwrk = $pais->idcontinente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pais->idcontinente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pais->idcontinente->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcontinente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `continente`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $pais->Lookup_Selecting($pais->idcontinente, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $pais_grid->RowIndex ?>_idcontinente" id="s_x<?php echo $pais_grid->RowIndex ?>_idcontinente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcontinente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_pais_idcontinente" class="form-group pais_idcontinente">
<span<?php echo $pais->idcontinente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->idcontinente->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idcontinente" name="x<?php echo $pais_grid->RowIndex ?>_idcontinente" id="x<?php echo $pais_grid->RowIndex ?>_idcontinente" value="<?php echo ew_HtmlEncode($pais->idcontinente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idcontinente" name="o<?php echo $pais_grid->RowIndex ?>_idcontinente" id="o<?php echo $pais_grid->RowIndex ?>_idcontinente" value="<?php echo ew_HtmlEncode($pais->idcontinente->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pais->estado->Visible) { // estado ?>
		<td>
<?php if ($pais->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pais_estado" class="form-group pais_estado">
<div id="tp_x<?php echo $pais_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $pais_grid->RowIndex ?>_estado" id="x<?php echo $pais_grid->RowIndex ?>_estado" value="{value}"<?php echo $pais->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $pais_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $pais->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pais->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $pais_grid->RowIndex ?>_estado" id="x<?php echo $pais_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $pais->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $pais->estado->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_pais_estado" class="form-group pais_estado">
<span<?php echo $pais->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $pais_grid->RowIndex ?>_estado" id="x<?php echo $pais_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($pais->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $pais_grid->RowIndex ?>_estado" id="o<?php echo $pais_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($pais->estado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pais_grid->ListOptions->Render("body", "right", $pais_grid->RowCnt);
?>
<script type="text/javascript">
fpaisgrid.UpdateOpts(<?php echo $pais_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($pais->CurrentMode == "add" || $pais->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $pais_grid->FormKeyCountName ?>" id="<?php echo $pais_grid->FormKeyCountName ?>" value="<?php echo $pais_grid->KeyCount ?>">
<?php echo $pais_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pais->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $pais_grid->FormKeyCountName ?>" id="<?php echo $pais_grid->FormKeyCountName ?>" value="<?php echo $pais_grid->KeyCount ?>">
<?php echo $pais_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pais->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpaisgrid">
</div>
<?php

// Close recordset
if ($pais_grid->Recordset)
	$pais_grid->Recordset->Close();
?>
<?php if ($pais_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($pais_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($pais_grid->TotalRecs == 0 && $pais->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pais_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pais->Export == "") { ?>
<script type="text/javascript">
fpaisgrid.Init();
</script>
<?php } ?>
<?php
$pais_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$pais_grid->Page_Terminate();
?>

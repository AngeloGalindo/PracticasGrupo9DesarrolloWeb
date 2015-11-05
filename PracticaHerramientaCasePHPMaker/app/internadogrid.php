<?php

// Create page object
if (!isset($internado_grid)) $internado_grid = new cinternado_grid();

// Page init
$internado_grid->Page_Init();

// Page main
$internado_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$internado_grid->Page_Render();
?>
<?php if ($internado->Export == "") { ?>
<script type="text/javascript">

// Page object
var internado_grid = new ew_Page("internado_grid");
internado_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = internado_grid.PageID; // For backward compatibility

// Form object
var finternadogrid = new ew_Form("finternadogrid");
finternadogrid.FormKeyCountName = '<?php echo $internado_grid->FormKeyCountName ?>';

// Validate form
finternadogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($internado->fecha_inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_final");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($internado->fecha_final->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado->estado->FldCaption(), $internado->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($internado->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idhabitacion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado->idhabitacion->FldCaption(), $internado->idhabitacion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idpaciente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado->idpaciente->FldCaption(), $internado->idpaciente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_es_operacion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado->es_operacion->FldCaption(), $internado->es_operacion->ReqErrMsg)) ?>");

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
finternadogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "fecha_inicio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_final", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idhabitacion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idpaciente", false)) return false;
	if (ew_ValueChanged(fobj, infix, "es_operacion", false)) return false;
	return true;
}

// Form_CustomValidate event
finternadogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finternadogrid.ValidateRequired = true;
<?php } else { ?>
finternadogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
finternadogrid.Lists["x_idhabitacion"] = {"LinkField":"x_idhabitacion","Ajax":true,"AutoFill":false,"DisplayFields":["x_numero","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
finternadogrid.Lists["x_idpaciente"] = {"LinkField":"x_idpaciente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($internado->CurrentAction == "gridadd") {
	if ($internado->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$internado_grid->TotalRecs = $internado->SelectRecordCount();
			$internado_grid->Recordset = $internado_grid->LoadRecordset($internado_grid->StartRec-1, $internado_grid->DisplayRecs);
		} else {
			if ($internado_grid->Recordset = $internado_grid->LoadRecordset())
				$internado_grid->TotalRecs = $internado_grid->Recordset->RecordCount();
		}
		$internado_grid->StartRec = 1;
		$internado_grid->DisplayRecs = $internado_grid->TotalRecs;
	} else {
		$internado->CurrentFilter = "0=1";
		$internado_grid->StartRec = 1;
		$internado_grid->DisplayRecs = $internado->GridAddRowCount;
	}
	$internado_grid->TotalRecs = $internado_grid->DisplayRecs;
	$internado_grid->StopRec = $internado_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$internado_grid->TotalRecs = $internado->SelectRecordCount();
	} else {
		if ($internado_grid->Recordset = $internado_grid->LoadRecordset())
			$internado_grid->TotalRecs = $internado_grid->Recordset->RecordCount();
	}
	$internado_grid->StartRec = 1;
	$internado_grid->DisplayRecs = $internado_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$internado_grid->Recordset = $internado_grid->LoadRecordset($internado_grid->StartRec-1, $internado_grid->DisplayRecs);

	// Set no record found message
	if ($internado->CurrentAction == "" && $internado_grid->TotalRecs == 0) {
		if ($internado_grid->SearchWhere == "0=101")
			$internado_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$internado_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$internado_grid->RenderOtherOptions();
?>
<?php $internado_grid->ShowPageHeader(); ?>
<?php
$internado_grid->ShowMessage();
?>
<?php if ($internado_grid->TotalRecs > 0 || $internado->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="finternadogrid" class="ewForm form-inline">
<div id="gmp_internado" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_internadogrid" class="table ewTable">
<?php echo $internado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$internado_grid->RenderListOptions();

// Render list options (header, left)
$internado_grid->ListOptions->Render("header", "left");
?>
<?php if ($internado->fecha_inicio->Visible) { // fecha_inicio ?>
	<?php if ($internado->SortUrl($internado->fecha_inicio) == "") { ?>
		<th data-name="fecha_inicio"><div id="elh_internado_fecha_inicio" class="internado_fecha_inicio"><div class="ewTableHeaderCaption"><?php echo $internado->fecha_inicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_inicio"><div><div id="elh_internado_fecha_inicio" class="internado_fecha_inicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado->fecha_inicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado->fecha_inicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado->fecha_inicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado->fecha_final->Visible) { // fecha_final ?>
	<?php if ($internado->SortUrl($internado->fecha_final) == "") { ?>
		<th data-name="fecha_final"><div id="elh_internado_fecha_final" class="internado_fecha_final"><div class="ewTableHeaderCaption"><?php echo $internado->fecha_final->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_final"><div><div id="elh_internado_fecha_final" class="internado_fecha_final">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado->fecha_final->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado->fecha_final->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado->fecha_final->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado->estado->Visible) { // estado ?>
	<?php if ($internado->SortUrl($internado->estado) == "") { ?>
		<th data-name="estado"><div id="elh_internado_estado" class="internado_estado"><div class="ewTableHeaderCaption"><?php echo $internado->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_internado_estado" class="internado_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado->fecha->Visible) { // fecha ?>
	<?php if ($internado->SortUrl($internado->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_internado_fecha" class="internado_fecha"><div class="ewTableHeaderCaption"><?php echo $internado->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_internado_fecha" class="internado_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado->idhabitacion->Visible) { // idhabitacion ?>
	<?php if ($internado->SortUrl($internado->idhabitacion) == "") { ?>
		<th data-name="idhabitacion"><div id="elh_internado_idhabitacion" class="internado_idhabitacion"><div class="ewTableHeaderCaption"><?php echo $internado->idhabitacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idhabitacion"><div><div id="elh_internado_idhabitacion" class="internado_idhabitacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado->idhabitacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado->idhabitacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado->idhabitacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado->idpaciente->Visible) { // idpaciente ?>
	<?php if ($internado->SortUrl($internado->idpaciente) == "") { ?>
		<th data-name="idpaciente"><div id="elh_internado_idpaciente" class="internado_idpaciente"><div class="ewTableHeaderCaption"><?php echo $internado->idpaciente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idpaciente"><div><div id="elh_internado_idpaciente" class="internado_idpaciente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado->idpaciente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado->idpaciente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado->idpaciente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($internado->es_operacion->Visible) { // es_operacion ?>
	<?php if ($internado->SortUrl($internado->es_operacion) == "") { ?>
		<th data-name="es_operacion"><div id="elh_internado_es_operacion" class="internado_es_operacion"><div class="ewTableHeaderCaption"><?php echo $internado->es_operacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="es_operacion"><div><div id="elh_internado_es_operacion" class="internado_es_operacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $internado->es_operacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($internado->es_operacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($internado->es_operacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$internado_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$internado_grid->StartRec = 1;
$internado_grid->StopRec = $internado_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($internado_grid->FormKeyCountName) && ($internado->CurrentAction == "gridadd" || $internado->CurrentAction == "gridedit" || $internado->CurrentAction == "F")) {
		$internado_grid->KeyCount = $objForm->GetValue($internado_grid->FormKeyCountName);
		$internado_grid->StopRec = $internado_grid->StartRec + $internado_grid->KeyCount - 1;
	}
}
$internado_grid->RecCnt = $internado_grid->StartRec - 1;
if ($internado_grid->Recordset && !$internado_grid->Recordset->EOF) {
	$internado_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $internado_grid->StartRec > 1)
		$internado_grid->Recordset->Move($internado_grid->StartRec - 1);
} elseif (!$internado->AllowAddDeleteRow && $internado_grid->StopRec == 0) {
	$internado_grid->StopRec = $internado->GridAddRowCount;
}

// Initialize aggregate
$internado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$internado->ResetAttrs();
$internado_grid->RenderRow();
if ($internado->CurrentAction == "gridadd")
	$internado_grid->RowIndex = 0;
if ($internado->CurrentAction == "gridedit")
	$internado_grid->RowIndex = 0;
while ($internado_grid->RecCnt < $internado_grid->StopRec) {
	$internado_grid->RecCnt++;
	if (intval($internado_grid->RecCnt) >= intval($internado_grid->StartRec)) {
		$internado_grid->RowCnt++;
		if ($internado->CurrentAction == "gridadd" || $internado->CurrentAction == "gridedit" || $internado->CurrentAction == "F") {
			$internado_grid->RowIndex++;
			$objForm->Index = $internado_grid->RowIndex;
			if ($objForm->HasValue($internado_grid->FormActionName))
				$internado_grid->RowAction = strval($objForm->GetValue($internado_grid->FormActionName));
			elseif ($internado->CurrentAction == "gridadd")
				$internado_grid->RowAction = "insert";
			else
				$internado_grid->RowAction = "";
		}

		// Set up key count
		$internado_grid->KeyCount = $internado_grid->RowIndex;

		// Init row class and style
		$internado->ResetAttrs();
		$internado->CssClass = "";
		if ($internado->CurrentAction == "gridadd") {
			if ($internado->CurrentMode == "copy") {
				$internado_grid->LoadRowValues($internado_grid->Recordset); // Load row values
				$internado_grid->SetRecordKey($internado_grid->RowOldKey, $internado_grid->Recordset); // Set old record key
			} else {
				$internado_grid->LoadDefaultValues(); // Load default values
				$internado_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$internado_grid->LoadRowValues($internado_grid->Recordset); // Load row values
		}
		$internado->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($internado->CurrentAction == "gridadd") // Grid add
			$internado->RowType = EW_ROWTYPE_ADD; // Render add
		if ($internado->CurrentAction == "gridadd" && $internado->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$internado_grid->RestoreCurrentRowFormValues($internado_grid->RowIndex); // Restore form values
		if ($internado->CurrentAction == "gridedit") { // Grid edit
			if ($internado->EventCancelled) {
				$internado_grid->RestoreCurrentRowFormValues($internado_grid->RowIndex); // Restore form values
			}
			if ($internado_grid->RowAction == "insert")
				$internado->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$internado->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($internado->CurrentAction == "gridedit" && ($internado->RowType == EW_ROWTYPE_EDIT || $internado->RowType == EW_ROWTYPE_ADD) && $internado->EventCancelled) // Update failed
			$internado_grid->RestoreCurrentRowFormValues($internado_grid->RowIndex); // Restore form values
		if ($internado->RowType == EW_ROWTYPE_EDIT) // Edit row
			$internado_grid->EditRowCnt++;
		if ($internado->CurrentAction == "F") // Confirm row
			$internado_grid->RestoreCurrentRowFormValues($internado_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$internado->RowAttrs = array_merge($internado->RowAttrs, array('data-rowindex'=>$internado_grid->RowCnt, 'id'=>'r' . $internado_grid->RowCnt . '_internado', 'data-rowtype'=>$internado->RowType));

		// Render row
		$internado_grid->RenderRow();

		// Render list options
		$internado_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($internado_grid->RowAction <> "delete" && $internado_grid->RowAction <> "insertdelete" && !($internado_grid->RowAction == "insert" && $internado->CurrentAction == "F" && $internado_grid->EmptyRow())) {
?>
	<tr<?php echo $internado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$internado_grid->ListOptions->Render("body", "left", $internado_grid->RowCnt);
?>
	<?php if ($internado->fecha_inicio->Visible) { // fecha_inicio ?>
		<td data-name="fecha_inicio"<?php echo $internado->fecha_inicio->CellAttributes() ?>>
<?php if ($internado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_fecha_inicio" class="form-group internado_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($internado->fecha_inicio->PlaceHolder) ?>" value="<?php echo $internado->fecha_inicio->EditValue ?>"<?php echo $internado->fecha_inicio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $internado_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $internado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($internado->fecha_inicio->OldValue) ?>">
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_fecha_inicio" class="form-group internado_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($internado->fecha_inicio->PlaceHolder) ?>" value="<?php echo $internado->fecha_inicio->EditValue ?>"<?php echo $internado->fecha_inicio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado->fecha_inicio->ViewAttributes() ?>>
<?php echo $internado->fecha_inicio->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_inicio" name="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($internado->fecha_inicio->FormValue) ?>">
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $internado_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $internado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($internado->fecha_inicio->OldValue) ?>">
<?php } ?>
<a id="<?php echo $internado_grid->PageObjName . "_row_" . $internado_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idinternado" name="x<?php echo $internado_grid->RowIndex ?>_idinternado" id="x<?php echo $internado_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado->idinternado->CurrentValue) ?>">
<input type="hidden" data-field="x_idinternado" name="o<?php echo $internado_grid->RowIndex ?>_idinternado" id="o<?php echo $internado_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado->idinternado->OldValue) ?>">
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_EDIT || $internado->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_idinternado" name="x<?php echo $internado_grid->RowIndex ?>_idinternado" id="x<?php echo $internado_grid->RowIndex ?>_idinternado" value="<?php echo ew_HtmlEncode($internado->idinternado->CurrentValue) ?>">
<?php } ?>
	<?php if ($internado->fecha_final->Visible) { // fecha_final ?>
		<td data-name="fecha_final"<?php echo $internado->fecha_final->CellAttributes() ?>>
<?php if ($internado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_fecha_final" class="form-group internado_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $internado_grid->RowIndex ?>_fecha_final" id="x<?php echo $internado_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($internado->fecha_final->PlaceHolder) ?>" value="<?php echo $internado->fecha_final->EditValue ?>"<?php echo $internado->fecha_final->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $internado_grid->RowIndex ?>_fecha_final" id="o<?php echo $internado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($internado->fecha_final->OldValue) ?>">
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_fecha_final" class="form-group internado_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $internado_grid->RowIndex ?>_fecha_final" id="x<?php echo $internado_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($internado->fecha_final->PlaceHolder) ?>" value="<?php echo $internado->fecha_final->EditValue ?>"<?php echo $internado->fecha_final->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado->fecha_final->ViewAttributes() ?>>
<?php echo $internado->fecha_final->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_final" name="x<?php echo $internado_grid->RowIndex ?>_fecha_final" id="x<?php echo $internado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($internado->fecha_final->FormValue) ?>">
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $internado_grid->RowIndex ?>_fecha_final" id="o<?php echo $internado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($internado->fecha_final->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($internado->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $internado->estado->CellAttributes() ?>>
<?php if ($internado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_estado" class="form-group internado_estado">
<div id="tp_x<?php echo $internado_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $internado_grid->RowIndex ?>_estado" id="x<?php echo $internado_grid->RowIndex ?>_estado" value="{value}"<?php echo $internado->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $internado_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $internado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $internado_grid->RowIndex ?>_estado" id="x<?php echo $internado_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $internado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $internado->estado->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $internado_grid->RowIndex ?>_estado" id="o<?php echo $internado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($internado->estado->OldValue) ?>">
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_estado" class="form-group internado_estado">
<div id="tp_x<?php echo $internado_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $internado_grid->RowIndex ?>_estado" id="x<?php echo $internado_grid->RowIndex ?>_estado" value="{value}"<?php echo $internado->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $internado_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $internado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $internado_grid->RowIndex ?>_estado" id="x<?php echo $internado_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $internado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $internado->estado->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado->estado->ViewAttributes() ?>>
<?php echo $internado->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $internado_grid->RowIndex ?>_estado" id="x<?php echo $internado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($internado->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $internado_grid->RowIndex ?>_estado" id="o<?php echo $internado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($internado->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($internado->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $internado->fecha->CellAttributes() ?>>
<?php if ($internado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_fecha" class="form-group internado_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $internado_grid->RowIndex ?>_fecha" id="x<?php echo $internado_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($internado->fecha->PlaceHolder) ?>" value="<?php echo $internado->fecha->EditValue ?>"<?php echo $internado->fecha->EditAttributes() ?>>
<?php if (!$internado->fecha->ReadOnly && !$internado->fecha->Disabled && @$internado->fecha->EditAttrs["readonly"] == "" && @$internado->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("finternadogrid", "x<?php echo $internado_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $internado_grid->RowIndex ?>_fecha" id="o<?php echo $internado_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado->fecha->OldValue) ?>">
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_fecha" class="form-group internado_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $internado_grid->RowIndex ?>_fecha" id="x<?php echo $internado_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($internado->fecha->PlaceHolder) ?>" value="<?php echo $internado->fecha->EditValue ?>"<?php echo $internado->fecha->EditAttributes() ?>>
<?php if (!$internado->fecha->ReadOnly && !$internado->fecha->Disabled && @$internado->fecha->EditAttrs["readonly"] == "" && @$internado->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("finternadogrid", "x<?php echo $internado_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado->fecha->ViewAttributes() ?>>
<?php echo $internado->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $internado_grid->RowIndex ?>_fecha" id="x<?php echo $internado_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $internado_grid->RowIndex ?>_fecha" id="o<?php echo $internado_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($internado->idhabitacion->Visible) { // idhabitacion ?>
		<td data-name="idhabitacion"<?php echo $internado->idhabitacion->CellAttributes() ?>>
<?php if ($internado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($internado->idhabitacion->getSessionValue() <> "") { ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_idhabitacion" class="form-group internado_idhabitacion">
<span<?php echo $internado->idhabitacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idhabitacion->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" name="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($internado->idhabitacion->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_idhabitacion" class="form-group internado_idhabitacion">
<select data-field="x_idhabitacion" id="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" name="x<?php echo $internado_grid->RowIndex ?>_idhabitacion"<?php echo $internado->idhabitacion->EditAttributes() ?>>
<?php
if (is_array($internado->idhabitacion->EditValue)) {
	$arwrk = $internado->idhabitacion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->idhabitacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado->idhabitacion->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhabitacion`, `numero` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `habitacion`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado->Lookup_Selecting($internado->idhabitacion, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_grid->RowIndex ?>_idhabitacion" id="s_x<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhabitacion` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idhabitacion" name="o<?php echo $internado_grid->RowIndex ?>_idhabitacion" id="o<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($internado->idhabitacion->OldValue) ?>">
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($internado->idhabitacion->getSessionValue() <> "") { ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_idhabitacion" class="form-group internado_idhabitacion">
<span<?php echo $internado->idhabitacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idhabitacion->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" name="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($internado->idhabitacion->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_idhabitacion" class="form-group internado_idhabitacion">
<select data-field="x_idhabitacion" id="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" name="x<?php echo $internado_grid->RowIndex ?>_idhabitacion"<?php echo $internado->idhabitacion->EditAttributes() ?>>
<?php
if (is_array($internado->idhabitacion->EditValue)) {
	$arwrk = $internado->idhabitacion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->idhabitacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado->idhabitacion->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhabitacion`, `numero` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `habitacion`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado->Lookup_Selecting($internado->idhabitacion, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_grid->RowIndex ?>_idhabitacion" id="s_x<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhabitacion` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado->idhabitacion->ViewAttributes() ?>>
<?php echo $internado->idhabitacion->ListViewValue() ?></span>
<input type="hidden" data-field="x_idhabitacion" name="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" id="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($internado->idhabitacion->FormValue) ?>">
<input type="hidden" data-field="x_idhabitacion" name="o<?php echo $internado_grid->RowIndex ?>_idhabitacion" id="o<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($internado->idhabitacion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($internado->idpaciente->Visible) { // idpaciente ?>
		<td data-name="idpaciente"<?php echo $internado->idpaciente->CellAttributes() ?>>
<?php if ($internado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($internado->idpaciente->getSessionValue() <> "") { ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_idpaciente" class="form-group internado_idpaciente">
<span<?php echo $internado->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_grid->RowIndex ?>_idpaciente" name="x<?php echo $internado_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($internado->idpaciente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_idpaciente" class="form-group internado_idpaciente">
<select data-field="x_idpaciente" id="x<?php echo $internado_grid->RowIndex ?>_idpaciente" name="x<?php echo $internado_grid->RowIndex ?>_idpaciente"<?php echo $internado->idpaciente->EditAttributes() ?>>
<?php
if (is_array($internado->idpaciente->EditValue)) {
	$arwrk = $internado->idpaciente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->idpaciente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado->idpaciente->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado->Lookup_Selecting($internado->idpaciente, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_grid->RowIndex ?>_idpaciente" id="s_x<?php echo $internado_grid->RowIndex ?>_idpaciente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpaciente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idpaciente" name="o<?php echo $internado_grid->RowIndex ?>_idpaciente" id="o<?php echo $internado_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($internado->idpaciente->OldValue) ?>">
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($internado->idpaciente->getSessionValue() <> "") { ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_idpaciente" class="form-group internado_idpaciente">
<span<?php echo $internado->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_grid->RowIndex ?>_idpaciente" name="x<?php echo $internado_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($internado->idpaciente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_idpaciente" class="form-group internado_idpaciente">
<select data-field="x_idpaciente" id="x<?php echo $internado_grid->RowIndex ?>_idpaciente" name="x<?php echo $internado_grid->RowIndex ?>_idpaciente"<?php echo $internado->idpaciente->EditAttributes() ?>>
<?php
if (is_array($internado->idpaciente->EditValue)) {
	$arwrk = $internado->idpaciente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->idpaciente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado->idpaciente->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado->Lookup_Selecting($internado->idpaciente, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_grid->RowIndex ?>_idpaciente" id="s_x<?php echo $internado_grid->RowIndex ?>_idpaciente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpaciente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado->idpaciente->ViewAttributes() ?>>
<?php echo $internado->idpaciente->ListViewValue() ?></span>
<input type="hidden" data-field="x_idpaciente" name="x<?php echo $internado_grid->RowIndex ?>_idpaciente" id="x<?php echo $internado_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($internado->idpaciente->FormValue) ?>">
<input type="hidden" data-field="x_idpaciente" name="o<?php echo $internado_grid->RowIndex ?>_idpaciente" id="o<?php echo $internado_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($internado->idpaciente->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($internado->es_operacion->Visible) { // es_operacion ?>
		<td data-name="es_operacion"<?php echo $internado->es_operacion->CellAttributes() ?>>
<?php if ($internado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_es_operacion" class="form-group internado_es_operacion">
<div id="tp_x<?php echo $internado_grid->RowIndex ?>_es_operacion" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $internado_grid->RowIndex ?>_es_operacion" id="x<?php echo $internado_grid->RowIndex ?>_es_operacion" value="{value}"<?php echo $internado->es_operacion->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $internado_grid->RowIndex ?>_es_operacion" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $internado->es_operacion->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->es_operacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_es_operacion" name="x<?php echo $internado_grid->RowIndex ?>_es_operacion" id="x<?php echo $internado_grid->RowIndex ?>_es_operacion_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $internado->es_operacion->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $internado->es_operacion->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_es_operacion" name="o<?php echo $internado_grid->RowIndex ?>_es_operacion" id="o<?php echo $internado_grid->RowIndex ?>_es_operacion" value="<?php echo ew_HtmlEncode($internado->es_operacion->OldValue) ?>">
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $internado_grid->RowCnt ?>_internado_es_operacion" class="form-group internado_es_operacion">
<div id="tp_x<?php echo $internado_grid->RowIndex ?>_es_operacion" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $internado_grid->RowIndex ?>_es_operacion" id="x<?php echo $internado_grid->RowIndex ?>_es_operacion" value="{value}"<?php echo $internado->es_operacion->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $internado_grid->RowIndex ?>_es_operacion" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $internado->es_operacion->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->es_operacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_es_operacion" name="x<?php echo $internado_grid->RowIndex ?>_es_operacion" id="x<?php echo $internado_grid->RowIndex ?>_es_operacion_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $internado->es_operacion->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $internado->es_operacion->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($internado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $internado->es_operacion->ViewAttributes() ?>>
<?php echo $internado->es_operacion->ListViewValue() ?></span>
<input type="hidden" data-field="x_es_operacion" name="x<?php echo $internado_grid->RowIndex ?>_es_operacion" id="x<?php echo $internado_grid->RowIndex ?>_es_operacion" value="<?php echo ew_HtmlEncode($internado->es_operacion->FormValue) ?>">
<input type="hidden" data-field="x_es_operacion" name="o<?php echo $internado_grid->RowIndex ?>_es_operacion" id="o<?php echo $internado_grid->RowIndex ?>_es_operacion" value="<?php echo ew_HtmlEncode($internado->es_operacion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$internado_grid->ListOptions->Render("body", "right", $internado_grid->RowCnt);
?>
	</tr>
<?php if ($internado->RowType == EW_ROWTYPE_ADD || $internado->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
finternadogrid.UpdateOpts(<?php echo $internado_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($internado->CurrentAction <> "gridadd" || $internado->CurrentMode == "copy")
		if (!$internado_grid->Recordset->EOF) $internado_grid->Recordset->MoveNext();
}
?>
<?php
	if ($internado->CurrentMode == "add" || $internado->CurrentMode == "copy" || $internado->CurrentMode == "edit") {
		$internado_grid->RowIndex = '$rowindex$';
		$internado_grid->LoadDefaultValues();

		// Set row properties
		$internado->ResetAttrs();
		$internado->RowAttrs = array_merge($internado->RowAttrs, array('data-rowindex'=>$internado_grid->RowIndex, 'id'=>'r0_internado', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($internado->RowAttrs["class"], "ewTemplate");
		$internado->RowType = EW_ROWTYPE_ADD;

		// Render row
		$internado_grid->RenderRow();

		// Render list options
		$internado_grid->RenderListOptions();
		$internado_grid->StartRowCnt = 0;
?>
	<tr<?php echo $internado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$internado_grid->ListOptions->Render("body", "left", $internado_grid->RowIndex);
?>
	<?php if ($internado->fecha_inicio->Visible) { // fecha_inicio ?>
		<td>
<?php if ($internado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_internado_fecha_inicio" class="form-group internado_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($internado->fecha_inicio->PlaceHolder) ?>" value="<?php echo $internado->fecha_inicio->EditValue ?>"<?php echo $internado->fecha_inicio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_internado_fecha_inicio" class="form-group internado_fecha_inicio">
<span<?php echo $internado->fecha_inicio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->fecha_inicio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_inicio" name="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $internado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($internado->fecha_inicio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $internado_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $internado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($internado->fecha_inicio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado->fecha_final->Visible) { // fecha_final ?>
		<td>
<?php if ($internado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_internado_fecha_final" class="form-group internado_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $internado_grid->RowIndex ?>_fecha_final" id="x<?php echo $internado_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($internado->fecha_final->PlaceHolder) ?>" value="<?php echo $internado->fecha_final->EditValue ?>"<?php echo $internado->fecha_final->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_internado_fecha_final" class="form-group internado_fecha_final">
<span<?php echo $internado->fecha_final->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->fecha_final->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_final" name="x<?php echo $internado_grid->RowIndex ?>_fecha_final" id="x<?php echo $internado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($internado->fecha_final->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $internado_grid->RowIndex ?>_fecha_final" id="o<?php echo $internado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($internado->fecha_final->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado->estado->Visible) { // estado ?>
		<td>
<?php if ($internado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_internado_estado" class="form-group internado_estado">
<div id="tp_x<?php echo $internado_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $internado_grid->RowIndex ?>_estado" id="x<?php echo $internado_grid->RowIndex ?>_estado" value="{value}"<?php echo $internado->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $internado_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $internado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $internado_grid->RowIndex ?>_estado" id="x<?php echo $internado_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $internado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $internado->estado->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_internado_estado" class="form-group internado_estado">
<span<?php echo $internado->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $internado_grid->RowIndex ?>_estado" id="x<?php echo $internado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($internado->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $internado_grid->RowIndex ?>_estado" id="o<?php echo $internado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($internado->estado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado->fecha->Visible) { // fecha ?>
		<td>
<?php if ($internado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_internado_fecha" class="form-group internado_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $internado_grid->RowIndex ?>_fecha" id="x<?php echo $internado_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($internado->fecha->PlaceHolder) ?>" value="<?php echo $internado->fecha->EditValue ?>"<?php echo $internado->fecha->EditAttributes() ?>>
<?php if (!$internado->fecha->ReadOnly && !$internado->fecha->Disabled && @$internado->fecha->EditAttrs["readonly"] == "" && @$internado->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("finternadogrid", "x<?php echo $internado_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_internado_fecha" class="form-group internado_fecha">
<span<?php echo $internado->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $internado_grid->RowIndex ?>_fecha" id="x<?php echo $internado_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $internado_grid->RowIndex ?>_fecha" id="o<?php echo $internado_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($internado->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado->idhabitacion->Visible) { // idhabitacion ?>
		<td>
<?php if ($internado->CurrentAction <> "F") { ?>
<?php if ($internado->idhabitacion->getSessionValue() <> "") { ?>
<span id="el$rowindex$_internado_idhabitacion" class="form-group internado_idhabitacion">
<span<?php echo $internado->idhabitacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idhabitacion->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" name="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($internado->idhabitacion->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_internado_idhabitacion" class="form-group internado_idhabitacion">
<select data-field="x_idhabitacion" id="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" name="x<?php echo $internado_grid->RowIndex ?>_idhabitacion"<?php echo $internado->idhabitacion->EditAttributes() ?>>
<?php
if (is_array($internado->idhabitacion->EditValue)) {
	$arwrk = $internado->idhabitacion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->idhabitacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado->idhabitacion->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhabitacion`, `numero` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `habitacion`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado->Lookup_Selecting($internado->idhabitacion, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_grid->RowIndex ?>_idhabitacion" id="s_x<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhabitacion` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_internado_idhabitacion" class="form-group internado_idhabitacion">
<span<?php echo $internado->idhabitacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idhabitacion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idhabitacion" name="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" id="x<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($internado->idhabitacion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idhabitacion" name="o<?php echo $internado_grid->RowIndex ?>_idhabitacion" id="o<?php echo $internado_grid->RowIndex ?>_idhabitacion" value="<?php echo ew_HtmlEncode($internado->idhabitacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado->idpaciente->Visible) { // idpaciente ?>
		<td>
<?php if ($internado->CurrentAction <> "F") { ?>
<?php if ($internado->idpaciente->getSessionValue() <> "") { ?>
<span id="el$rowindex$_internado_idpaciente" class="form-group internado_idpaciente">
<span<?php echo $internado->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $internado_grid->RowIndex ?>_idpaciente" name="x<?php echo $internado_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($internado->idpaciente->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_internado_idpaciente" class="form-group internado_idpaciente">
<select data-field="x_idpaciente" id="x<?php echo $internado_grid->RowIndex ?>_idpaciente" name="x<?php echo $internado_grid->RowIndex ?>_idpaciente"<?php echo $internado->idpaciente->EditAttributes() ?>>
<?php
if (is_array($internado->idpaciente->EditValue)) {
	$arwrk = $internado->idpaciente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->idpaciente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $internado->idpaciente->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $internado->Lookup_Selecting($internado->idpaciente, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $internado_grid->RowIndex ?>_idpaciente" id="s_x<?php echo $internado_grid->RowIndex ?>_idpaciente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpaciente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_internado_idpaciente" class="form-group internado_idpaciente">
<span<?php echo $internado->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idpaciente" name="x<?php echo $internado_grid->RowIndex ?>_idpaciente" id="x<?php echo $internado_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($internado->idpaciente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idpaciente" name="o<?php echo $internado_grid->RowIndex ?>_idpaciente" id="o<?php echo $internado_grid->RowIndex ?>_idpaciente" value="<?php echo ew_HtmlEncode($internado->idpaciente->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($internado->es_operacion->Visible) { // es_operacion ?>
		<td>
<?php if ($internado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_internado_es_operacion" class="form-group internado_es_operacion">
<div id="tp_x<?php echo $internado_grid->RowIndex ?>_es_operacion" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $internado_grid->RowIndex ?>_es_operacion" id="x<?php echo $internado_grid->RowIndex ?>_es_operacion" value="{value}"<?php echo $internado->es_operacion->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $internado_grid->RowIndex ?>_es_operacion" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $internado->es_operacion->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->es_operacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_es_operacion" name="x<?php echo $internado_grid->RowIndex ?>_es_operacion" id="x<?php echo $internado_grid->RowIndex ?>_es_operacion_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $internado->es_operacion->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $internado->es_operacion->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_internado_es_operacion" class="form-group internado_es_operacion">
<span<?php echo $internado->es_operacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->es_operacion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_es_operacion" name="x<?php echo $internado_grid->RowIndex ?>_es_operacion" id="x<?php echo $internado_grid->RowIndex ?>_es_operacion" value="<?php echo ew_HtmlEncode($internado->es_operacion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_es_operacion" name="o<?php echo $internado_grid->RowIndex ?>_es_operacion" id="o<?php echo $internado_grid->RowIndex ?>_es_operacion" value="<?php echo ew_HtmlEncode($internado->es_operacion->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$internado_grid->ListOptions->Render("body", "right", $internado_grid->RowCnt);
?>
<script type="text/javascript">
finternadogrid.UpdateOpts(<?php echo $internado_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($internado->CurrentMode == "add" || $internado->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $internado_grid->FormKeyCountName ?>" id="<?php echo $internado_grid->FormKeyCountName ?>" value="<?php echo $internado_grid->KeyCount ?>">
<?php echo $internado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($internado->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $internado_grid->FormKeyCountName ?>" id="<?php echo $internado_grid->FormKeyCountName ?>" value="<?php echo $internado_grid->KeyCount ?>">
<?php echo $internado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($internado->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="finternadogrid">
</div>
<?php

// Close recordset
if ($internado_grid->Recordset)
	$internado_grid->Recordset->Close();
?>
<?php if ($internado_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($internado_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($internado_grid->TotalRecs == 0 && $internado->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($internado_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($internado->Export == "") { ?>
<script type="text/javascript">
finternadogrid.Init();
</script>
<?php } ?>
<?php
$internado_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$internado_grid->Page_Terminate();
?>

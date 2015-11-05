<?php

// Create page object
if (!isset($servicio_medico_prestado_grid)) $servicio_medico_prestado_grid = new cservicio_medico_prestado_grid();

// Page init
$servicio_medico_prestado_grid->Page_Init();

// Page main
$servicio_medico_prestado_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$servicio_medico_prestado_grid->Page_Render();
?>
<?php if ($servicio_medico_prestado->Export == "") { ?>
<script type="text/javascript">

// Page object
var servicio_medico_prestado_grid = new ew_Page("servicio_medico_prestado_grid");
servicio_medico_prestado_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = servicio_medico_prestado_grid.PageID; // For backward compatibility

// Form object
var fservicio_medico_prestadogrid = new ew_Form("fservicio_medico_prestadogrid");
fservicio_medico_prestadogrid.FormKeyCountName = '<?php echo $servicio_medico_prestado_grid->FormKeyCountName ?>';

// Validate form
fservicio_medico_prestadogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idservicio_medico");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $servicio_medico_prestado->idservicio_medico->FldCaption(), $servicio_medico_prestado->idservicio_medico->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $servicio_medico_prestado->estado->FldCaption(), $servicio_medico_prestado->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_costo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $servicio_medico_prestado->costo->FldCaption(), $servicio_medico_prestado->costo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_costo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicio_medico_prestado->costo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicio_medico_prestado->fecha_inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_final");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicio_medico_prestado->fecha_final->FldErrMsg()) ?>");

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
fservicio_medico_prestadogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idcuenta", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idservicio_medico", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "costo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_inicio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_final", false)) return false;
	return true;
}

// Form_CustomValidate event
fservicio_medico_prestadogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fservicio_medico_prestadogrid.ValidateRequired = true;
<?php } else { ?>
fservicio_medico_prestadogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fservicio_medico_prestadogrid.Lists["x_idcuenta"] = {"LinkField":"x_idcuenta","Ajax":true,"AutoFill":false,"DisplayFields":["x_idcuenta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fservicio_medico_prestadogrid.Lists["x_idservicio_medico"] = {"LinkField":"x_idservicio_medico","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($servicio_medico_prestado->CurrentAction == "gridadd") {
	if ($servicio_medico_prestado->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$servicio_medico_prestado_grid->TotalRecs = $servicio_medico_prestado->SelectRecordCount();
			$servicio_medico_prestado_grid->Recordset = $servicio_medico_prestado_grid->LoadRecordset($servicio_medico_prestado_grid->StartRec-1, $servicio_medico_prestado_grid->DisplayRecs);
		} else {
			if ($servicio_medico_prestado_grid->Recordset = $servicio_medico_prestado_grid->LoadRecordset())
				$servicio_medico_prestado_grid->TotalRecs = $servicio_medico_prestado_grid->Recordset->RecordCount();
		}
		$servicio_medico_prestado_grid->StartRec = 1;
		$servicio_medico_prestado_grid->DisplayRecs = $servicio_medico_prestado_grid->TotalRecs;
	} else {
		$servicio_medico_prestado->CurrentFilter = "0=1";
		$servicio_medico_prestado_grid->StartRec = 1;
		$servicio_medico_prestado_grid->DisplayRecs = $servicio_medico_prestado->GridAddRowCount;
	}
	$servicio_medico_prestado_grid->TotalRecs = $servicio_medico_prestado_grid->DisplayRecs;
	$servicio_medico_prestado_grid->StopRec = $servicio_medico_prestado_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$servicio_medico_prestado_grid->TotalRecs = $servicio_medico_prestado->SelectRecordCount();
	} else {
		if ($servicio_medico_prestado_grid->Recordset = $servicio_medico_prestado_grid->LoadRecordset())
			$servicio_medico_prestado_grid->TotalRecs = $servicio_medico_prestado_grid->Recordset->RecordCount();
	}
	$servicio_medico_prestado_grid->StartRec = 1;
	$servicio_medico_prestado_grid->DisplayRecs = $servicio_medico_prestado_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$servicio_medico_prestado_grid->Recordset = $servicio_medico_prestado_grid->LoadRecordset($servicio_medico_prestado_grid->StartRec-1, $servicio_medico_prestado_grid->DisplayRecs);

	// Set no record found message
	if ($servicio_medico_prestado->CurrentAction == "" && $servicio_medico_prestado_grid->TotalRecs == 0) {
		if ($servicio_medico_prestado_grid->SearchWhere == "0=101")
			$servicio_medico_prestado_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$servicio_medico_prestado_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$servicio_medico_prestado_grid->RenderOtherOptions();
?>
<?php $servicio_medico_prestado_grid->ShowPageHeader(); ?>
<?php
$servicio_medico_prestado_grid->ShowMessage();
?>
<?php if ($servicio_medico_prestado_grid->TotalRecs > 0 || $servicio_medico_prestado->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fservicio_medico_prestadogrid" class="ewForm form-inline">
<div id="gmp_servicio_medico_prestado" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_servicio_medico_prestadogrid" class="table ewTable">
<?php echo $servicio_medico_prestado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$servicio_medico_prestado_grid->RenderListOptions();

// Render list options (header, left)
$servicio_medico_prestado_grid->ListOptions->Render("header", "left");
?>
<?php if ($servicio_medico_prestado->idcuenta->Visible) { // idcuenta ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->idcuenta) == "") { ?>
		<th data-name="idcuenta"><div id="elh_servicio_medico_prestado_idcuenta" class="servicio_medico_prestado_idcuenta"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->idcuenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idcuenta"><div><div id="elh_servicio_medico_prestado_idcuenta" class="servicio_medico_prestado_idcuenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->idcuenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->idcuenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->idcuenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->idservicio_medico->Visible) { // idservicio_medico ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->idservicio_medico) == "") { ?>
		<th data-name="idservicio_medico"><div id="elh_servicio_medico_prestado_idservicio_medico" class="servicio_medico_prestado_idservicio_medico"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->idservicio_medico->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idservicio_medico"><div><div id="elh_servicio_medico_prestado_idservicio_medico" class="servicio_medico_prestado_idservicio_medico">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->idservicio_medico->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->idservicio_medico->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->idservicio_medico->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->estado->Visible) { // estado ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->estado) == "") { ?>
		<th data-name="estado"><div id="elh_servicio_medico_prestado_estado" class="servicio_medico_prestado_estado"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_servicio_medico_prestado_estado" class="servicio_medico_prestado_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->costo->Visible) { // costo ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->costo) == "") { ?>
		<th data-name="costo"><div id="elh_servicio_medico_prestado_costo" class="servicio_medico_prestado_costo"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->costo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="costo"><div><div id="elh_servicio_medico_prestado_costo" class="servicio_medico_prestado_costo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->costo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->costo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->costo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->fecha_inicio->Visible) { // fecha_inicio ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->fecha_inicio) == "") { ?>
		<th data-name="fecha_inicio"><div id="elh_servicio_medico_prestado_fecha_inicio" class="servicio_medico_prestado_fecha_inicio"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->fecha_inicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_inicio"><div><div id="elh_servicio_medico_prestado_fecha_inicio" class="servicio_medico_prestado_fecha_inicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->fecha_inicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->fecha_inicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->fecha_inicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->fecha_final->Visible) { // fecha_final ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->fecha_final) == "") { ?>
		<th data-name="fecha_final"><div id="elh_servicio_medico_prestado_fecha_final" class="servicio_medico_prestado_fecha_final"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->fecha_final->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_final"><div><div id="elh_servicio_medico_prestado_fecha_final" class="servicio_medico_prestado_fecha_final">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->fecha_final->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->fecha_final->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->fecha_final->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$servicio_medico_prestado_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$servicio_medico_prestado_grid->StartRec = 1;
$servicio_medico_prestado_grid->StopRec = $servicio_medico_prestado_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($servicio_medico_prestado_grid->FormKeyCountName) && ($servicio_medico_prestado->CurrentAction == "gridadd" || $servicio_medico_prestado->CurrentAction == "gridedit" || $servicio_medico_prestado->CurrentAction == "F")) {
		$servicio_medico_prestado_grid->KeyCount = $objForm->GetValue($servicio_medico_prestado_grid->FormKeyCountName);
		$servicio_medico_prestado_grid->StopRec = $servicio_medico_prestado_grid->StartRec + $servicio_medico_prestado_grid->KeyCount - 1;
	}
}
$servicio_medico_prestado_grid->RecCnt = $servicio_medico_prestado_grid->StartRec - 1;
if ($servicio_medico_prestado_grid->Recordset && !$servicio_medico_prestado_grid->Recordset->EOF) {
	$servicio_medico_prestado_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $servicio_medico_prestado_grid->StartRec > 1)
		$servicio_medico_prestado_grid->Recordset->Move($servicio_medico_prestado_grid->StartRec - 1);
} elseif (!$servicio_medico_prestado->AllowAddDeleteRow && $servicio_medico_prestado_grid->StopRec == 0) {
	$servicio_medico_prestado_grid->StopRec = $servicio_medico_prestado->GridAddRowCount;
}

// Initialize aggregate
$servicio_medico_prestado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$servicio_medico_prestado->ResetAttrs();
$servicio_medico_prestado_grid->RenderRow();
if ($servicio_medico_prestado->CurrentAction == "gridadd")
	$servicio_medico_prestado_grid->RowIndex = 0;
if ($servicio_medico_prestado->CurrentAction == "gridedit")
	$servicio_medico_prestado_grid->RowIndex = 0;
while ($servicio_medico_prestado_grid->RecCnt < $servicio_medico_prestado_grid->StopRec) {
	$servicio_medico_prestado_grid->RecCnt++;
	if (intval($servicio_medico_prestado_grid->RecCnt) >= intval($servicio_medico_prestado_grid->StartRec)) {
		$servicio_medico_prestado_grid->RowCnt++;
		if ($servicio_medico_prestado->CurrentAction == "gridadd" || $servicio_medico_prestado->CurrentAction == "gridedit" || $servicio_medico_prestado->CurrentAction == "F") {
			$servicio_medico_prestado_grid->RowIndex++;
			$objForm->Index = $servicio_medico_prestado_grid->RowIndex;
			if ($objForm->HasValue($servicio_medico_prestado_grid->FormActionName))
				$servicio_medico_prestado_grid->RowAction = strval($objForm->GetValue($servicio_medico_prestado_grid->FormActionName));
			elseif ($servicio_medico_prestado->CurrentAction == "gridadd")
				$servicio_medico_prestado_grid->RowAction = "insert";
			else
				$servicio_medico_prestado_grid->RowAction = "";
		}

		// Set up key count
		$servicio_medico_prestado_grid->KeyCount = $servicio_medico_prestado_grid->RowIndex;

		// Init row class and style
		$servicio_medico_prestado->ResetAttrs();
		$servicio_medico_prestado->CssClass = "";
		if ($servicio_medico_prestado->CurrentAction == "gridadd") {
			if ($servicio_medico_prestado->CurrentMode == "copy") {
				$servicio_medico_prestado_grid->LoadRowValues($servicio_medico_prestado_grid->Recordset); // Load row values
				$servicio_medico_prestado_grid->SetRecordKey($servicio_medico_prestado_grid->RowOldKey, $servicio_medico_prestado_grid->Recordset); // Set old record key
			} else {
				$servicio_medico_prestado_grid->LoadDefaultValues(); // Load default values
				$servicio_medico_prestado_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$servicio_medico_prestado_grid->LoadRowValues($servicio_medico_prestado_grid->Recordset); // Load row values
		}
		$servicio_medico_prestado->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($servicio_medico_prestado->CurrentAction == "gridadd") // Grid add
			$servicio_medico_prestado->RowType = EW_ROWTYPE_ADD; // Render add
		if ($servicio_medico_prestado->CurrentAction == "gridadd" && $servicio_medico_prestado->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$servicio_medico_prestado_grid->RestoreCurrentRowFormValues($servicio_medico_prestado_grid->RowIndex); // Restore form values
		if ($servicio_medico_prestado->CurrentAction == "gridedit") { // Grid edit
			if ($servicio_medico_prestado->EventCancelled) {
				$servicio_medico_prestado_grid->RestoreCurrentRowFormValues($servicio_medico_prestado_grid->RowIndex); // Restore form values
			}
			if ($servicio_medico_prestado_grid->RowAction == "insert")
				$servicio_medico_prestado->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$servicio_medico_prestado->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($servicio_medico_prestado->CurrentAction == "gridedit" && ($servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT || $servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) && $servicio_medico_prestado->EventCancelled) // Update failed
			$servicio_medico_prestado_grid->RestoreCurrentRowFormValues($servicio_medico_prestado_grid->RowIndex); // Restore form values
		if ($servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) // Edit row
			$servicio_medico_prestado_grid->EditRowCnt++;
		if ($servicio_medico_prestado->CurrentAction == "F") // Confirm row
			$servicio_medico_prestado_grid->RestoreCurrentRowFormValues($servicio_medico_prestado_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$servicio_medico_prestado->RowAttrs = array_merge($servicio_medico_prestado->RowAttrs, array('data-rowindex'=>$servicio_medico_prestado_grid->RowCnt, 'id'=>'r' . $servicio_medico_prestado_grid->RowCnt . '_servicio_medico_prestado', 'data-rowtype'=>$servicio_medico_prestado->RowType));

		// Render row
		$servicio_medico_prestado_grid->RenderRow();

		// Render list options
		$servicio_medico_prestado_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($servicio_medico_prestado_grid->RowAction <> "delete" && $servicio_medico_prestado_grid->RowAction <> "insertdelete" && !($servicio_medico_prestado_grid->RowAction == "insert" && $servicio_medico_prestado->CurrentAction == "F" && $servicio_medico_prestado_grid->EmptyRow())) {
?>
	<tr<?php echo $servicio_medico_prestado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$servicio_medico_prestado_grid->ListOptions->Render("body", "left", $servicio_medico_prestado_grid->RowCnt);
?>
	<?php if ($servicio_medico_prestado->idcuenta->Visible) { // idcuenta ?>
		<td data-name="idcuenta"<?php echo $servicio_medico_prestado->idcuenta->CellAttributes() ?>>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($servicio_medico_prestado->idcuenta->getSessionValue() <> "") { ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_idcuenta" class="form-group servicio_medico_prestado_idcuenta">
<span<?php echo $servicio_medico_prestado->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_idcuenta" class="form-group servicio_medico_prestado_idcuenta">
<select data-field="x_idcuenta" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta"<?php echo $servicio_medico_prestado->idcuenta->EditAttributes() ?>>
<?php
if (is_array($servicio_medico_prestado->idcuenta->EditValue)) {
	$arwrk = $servicio_medico_prestado->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $servicio_medico_prestado->idcuenta->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $servicio_medico_prestado->Lookup_Selecting($servicio_medico_prestado->idcuenta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" id="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idcuenta->OldValue) ?>">
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($servicio_medico_prestado->idcuenta->getSessionValue() <> "") { ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_idcuenta" class="form-group servicio_medico_prestado_idcuenta">
<span<?php echo $servicio_medico_prestado->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_idcuenta" class="form-group servicio_medico_prestado_idcuenta">
<select data-field="x_idcuenta" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta"<?php echo $servicio_medico_prestado->idcuenta->EditAttributes() ?>>
<?php
if (is_array($servicio_medico_prestado->idcuenta->EditValue)) {
	$arwrk = $servicio_medico_prestado->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $servicio_medico_prestado->idcuenta->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $servicio_medico_prestado->Lookup_Selecting($servicio_medico_prestado->idcuenta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" id="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $servicio_medico_prestado->idcuenta->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->idcuenta->ListViewValue() ?></span>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idcuenta->FormValue) ?>">
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idcuenta->OldValue) ?>">
<?php } ?>
<a id="<?php echo $servicio_medico_prestado_grid->PageObjName . "_row_" . $servicio_medico_prestado_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idservicio_medico_prestado" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico_prestado->CurrentValue) ?>">
<input type="hidden" data-field="x_idservicio_medico_prestado" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico_prestado->OldValue) ?>">
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT || $servicio_medico_prestado->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_idservicio_medico_prestado" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico_prestado->CurrentValue) ?>">
<?php } ?>
	<?php if ($servicio_medico_prestado->idservicio_medico->Visible) { // idservicio_medico ?>
		<td data-name="idservicio_medico"<?php echo $servicio_medico_prestado->idservicio_medico->CellAttributes() ?>>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($servicio_medico_prestado->idservicio_medico->getSessionValue() <> "") { ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_idservicio_medico" class="form-group servicio_medico_prestado_idservicio_medico">
<span<?php echo $servicio_medico_prestado->idservicio_medico->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idservicio_medico->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_idservicio_medico" class="form-group servicio_medico_prestado_idservicio_medico">
<select data-field="x_idservicio_medico" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico"<?php echo $servicio_medico_prestado->idservicio_medico->EditAttributes() ?>>
<?php
if (is_array($servicio_medico_prestado->idservicio_medico->EditValue)) {
	$arwrk = $servicio_medico_prestado->idservicio_medico->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->idservicio_medico->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $servicio_medico_prestado->idservicio_medico->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idservicio_medico`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `servicio_medico`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $servicio_medico_prestado->Lookup_Selecting($servicio_medico_prestado->idservicio_medico, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" id="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idservicio_medico` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idservicio_medico" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico->OldValue) ?>">
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($servicio_medico_prestado->idservicio_medico->getSessionValue() <> "") { ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_idservicio_medico" class="form-group servicio_medico_prestado_idservicio_medico">
<span<?php echo $servicio_medico_prestado->idservicio_medico->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idservicio_medico->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_idservicio_medico" class="form-group servicio_medico_prestado_idservicio_medico">
<select data-field="x_idservicio_medico" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico"<?php echo $servicio_medico_prestado->idservicio_medico->EditAttributes() ?>>
<?php
if (is_array($servicio_medico_prestado->idservicio_medico->EditValue)) {
	$arwrk = $servicio_medico_prestado->idservicio_medico->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->idservicio_medico->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $servicio_medico_prestado->idservicio_medico->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idservicio_medico`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `servicio_medico`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $servicio_medico_prestado->Lookup_Selecting($servicio_medico_prestado->idservicio_medico, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" id="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idservicio_medico` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $servicio_medico_prestado->idservicio_medico->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->idservicio_medico->ListViewValue() ?></span>
<input type="hidden" data-field="x_idservicio_medico" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico->FormValue) ?>">
<input type="hidden" data-field="x_idservicio_medico" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $servicio_medico_prestado->estado->CellAttributes() ?>>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_estado" class="form-group servicio_medico_prestado_estado">
<div id="tp_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" value="{value}"<?php echo $servicio_medico_prestado->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $servicio_medico_prestado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $servicio_medico_prestado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $servicio_medico_prestado->estado->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->estado->OldValue) ?>">
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_estado" class="form-group servicio_medico_prestado_estado">
<div id="tp_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" value="{value}"<?php echo $servicio_medico_prestado->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $servicio_medico_prestado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $servicio_medico_prestado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $servicio_medico_prestado->estado->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $servicio_medico_prestado->estado->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->costo->Visible) { // costo ?>
		<td data-name="costo"<?php echo $servicio_medico_prestado->costo->CellAttributes() ?>>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_costo" class="form-group servicio_medico_prestado_costo">
<input type="text" data-field="x_costo" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" size="30" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->costo->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->costo->EditValue ?>"<?php echo $servicio_medico_prestado->costo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_costo" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->costo->OldValue) ?>">
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_costo" class="form-group servicio_medico_prestado_costo">
<input type="text" data-field="x_costo" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" size="30" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->costo->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->costo->EditValue ?>"<?php echo $servicio_medico_prestado->costo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $servicio_medico_prestado->costo->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->costo->ListViewValue() ?></span>
<input type="hidden" data-field="x_costo" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->costo->FormValue) ?>">
<input type="hidden" data-field="x_costo" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->costo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->fecha_inicio->Visible) { // fecha_inicio ?>
		<td data-name="fecha_inicio"<?php echo $servicio_medico_prestado->fecha_inicio->CellAttributes() ?>>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_fecha_inicio" class="form-group servicio_medico_prestado_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_inicio->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->fecha_inicio->EditValue ?>"<?php echo $servicio_medico_prestado->fecha_inicio->EditAttributes() ?>>
<?php if (!$servicio_medico_prestado->fecha_inicio->ReadOnly && !$servicio_medico_prestado->fecha_inicio->Disabled && @$servicio_medico_prestado->fecha_inicio->EditAttrs["readonly"] == "" && @$servicio_medico_prestado->fecha_inicio->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fservicio_medico_prestadogrid", "x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_inicio->OldValue) ?>">
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_fecha_inicio" class="form-group servicio_medico_prestado_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_inicio->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->fecha_inicio->EditValue ?>"<?php echo $servicio_medico_prestado->fecha_inicio->EditAttributes() ?>>
<?php if (!$servicio_medico_prestado->fecha_inicio->ReadOnly && !$servicio_medico_prestado->fecha_inicio->Disabled && @$servicio_medico_prestado->fecha_inicio->EditAttrs["readonly"] == "" && @$servicio_medico_prestado->fecha_inicio->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fservicio_medico_prestadogrid", "x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $servicio_medico_prestado->fecha_inicio->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->fecha_inicio->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_inicio" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_inicio->FormValue) ?>">
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_inicio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->fecha_final->Visible) { // fecha_final ?>
		<td data-name="fecha_final"<?php echo $servicio_medico_prestado->fecha_final->CellAttributes() ?>>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_fecha_final" class="form-group servicio_medico_prestado_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_final->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->fecha_final->EditValue ?>"<?php echo $servicio_medico_prestado->fecha_final->EditAttributes() ?>>
<?php if (!$servicio_medico_prestado->fecha_final->ReadOnly && !$servicio_medico_prestado->fecha_final->Disabled && @$servicio_medico_prestado->fecha_final->EditAttrs["readonly"] == "" && @$servicio_medico_prestado->fecha_final->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fservicio_medico_prestadogrid", "x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_final->OldValue) ?>">
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $servicio_medico_prestado_grid->RowCnt ?>_servicio_medico_prestado_fecha_final" class="form-group servicio_medico_prestado_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_final->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->fecha_final->EditValue ?>"<?php echo $servicio_medico_prestado->fecha_final->EditAttributes() ?>>
<?php if (!$servicio_medico_prestado->fecha_final->ReadOnly && !$servicio_medico_prestado->fecha_final->Disabled && @$servicio_medico_prestado->fecha_final->EditAttrs["readonly"] == "" && @$servicio_medico_prestado->fecha_final->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fservicio_medico_prestadogrid", "x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $servicio_medico_prestado->fecha_final->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->fecha_final->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_final" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_final->FormValue) ?>">
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_final->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$servicio_medico_prestado_grid->ListOptions->Render("body", "right", $servicio_medico_prestado_grid->RowCnt);
?>
	</tr>
<?php if ($servicio_medico_prestado->RowType == EW_ROWTYPE_ADD || $servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fservicio_medico_prestadogrid.UpdateOpts(<?php echo $servicio_medico_prestado_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($servicio_medico_prestado->CurrentAction <> "gridadd" || $servicio_medico_prestado->CurrentMode == "copy")
		if (!$servicio_medico_prestado_grid->Recordset->EOF) $servicio_medico_prestado_grid->Recordset->MoveNext();
}
?>
<?php
	if ($servicio_medico_prestado->CurrentMode == "add" || $servicio_medico_prestado->CurrentMode == "copy" || $servicio_medico_prestado->CurrentMode == "edit") {
		$servicio_medico_prestado_grid->RowIndex = '$rowindex$';
		$servicio_medico_prestado_grid->LoadDefaultValues();

		// Set row properties
		$servicio_medico_prestado->ResetAttrs();
		$servicio_medico_prestado->RowAttrs = array_merge($servicio_medico_prestado->RowAttrs, array('data-rowindex'=>$servicio_medico_prestado_grid->RowIndex, 'id'=>'r0_servicio_medico_prestado', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($servicio_medico_prestado->RowAttrs["class"], "ewTemplate");
		$servicio_medico_prestado->RowType = EW_ROWTYPE_ADD;

		// Render row
		$servicio_medico_prestado_grid->RenderRow();

		// Render list options
		$servicio_medico_prestado_grid->RenderListOptions();
		$servicio_medico_prestado_grid->StartRowCnt = 0;
?>
	<tr<?php echo $servicio_medico_prestado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$servicio_medico_prestado_grid->ListOptions->Render("body", "left", $servicio_medico_prestado_grid->RowIndex);
?>
	<?php if ($servicio_medico_prestado->idcuenta->Visible) { // idcuenta ?>
		<td>
<?php if ($servicio_medico_prestado->CurrentAction <> "F") { ?>
<?php if ($servicio_medico_prestado->idcuenta->getSessionValue() <> "") { ?>
<span id="el$rowindex$_servicio_medico_prestado_idcuenta" class="form-group servicio_medico_prestado_idcuenta">
<span<?php echo $servicio_medico_prestado->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_servicio_medico_prestado_idcuenta" class="form-group servicio_medico_prestado_idcuenta">
<select data-field="x_idcuenta" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta"<?php echo $servicio_medico_prestado->idcuenta->EditAttributes() ?>>
<?php
if (is_array($servicio_medico_prestado->idcuenta->EditValue)) {
	$arwrk = $servicio_medico_prestado->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $servicio_medico_prestado->idcuenta->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $servicio_medico_prestado->Lookup_Selecting($servicio_medico_prestado->idcuenta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" id="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_servicio_medico_prestado_idcuenta" class="form-group servicio_medico_prestado_idcuenta">
<span<?php echo $servicio_medico_prestado->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idcuenta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idcuenta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->idservicio_medico->Visible) { // idservicio_medico ?>
		<td>
<?php if ($servicio_medico_prestado->CurrentAction <> "F") { ?>
<?php if ($servicio_medico_prestado->idservicio_medico->getSessionValue() <> "") { ?>
<span id="el$rowindex$_servicio_medico_prestado_idservicio_medico" class="form-group servicio_medico_prestado_idservicio_medico">
<span<?php echo $servicio_medico_prestado->idservicio_medico->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idservicio_medico->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_servicio_medico_prestado_idservicio_medico" class="form-group servicio_medico_prestado_idservicio_medico">
<select data-field="x_idservicio_medico" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico"<?php echo $servicio_medico_prestado->idservicio_medico->EditAttributes() ?>>
<?php
if (is_array($servicio_medico_prestado->idservicio_medico->EditValue)) {
	$arwrk = $servicio_medico_prestado->idservicio_medico->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->idservicio_medico->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $servicio_medico_prestado->idservicio_medico->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idservicio_medico`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `servicio_medico`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $servicio_medico_prestado->Lookup_Selecting($servicio_medico_prestado->idservicio_medico, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" id="s_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idservicio_medico` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_servicio_medico_prestado_idservicio_medico" class="form-group servicio_medico_prestado_idservicio_medico">
<span<?php echo $servicio_medico_prestado->idservicio_medico->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idservicio_medico->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idservicio_medico" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idservicio_medico" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->estado->Visible) { // estado ?>
		<td>
<?php if ($servicio_medico_prestado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_servicio_medico_prestado_estado" class="form-group servicio_medico_prestado_estado">
<div id="tp_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" value="{value}"<?php echo $servicio_medico_prestado->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $servicio_medico_prestado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $servicio_medico_prestado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $servicio_medico_prestado->estado->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_servicio_medico_prestado_estado" class="form-group servicio_medico_prestado_estado">
<span<?php echo $servicio_medico_prestado->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->estado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->costo->Visible) { // costo ?>
		<td>
<?php if ($servicio_medico_prestado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_servicio_medico_prestado_costo" class="form-group servicio_medico_prestado_costo">
<input type="text" data-field="x_costo" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" size="30" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->costo->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->costo->EditValue ?>"<?php echo $servicio_medico_prestado->costo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_servicio_medico_prestado_costo" class="form-group servicio_medico_prestado_costo">
<span<?php echo $servicio_medico_prestado->costo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->costo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_costo" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->costo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_costo" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->costo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->fecha_inicio->Visible) { // fecha_inicio ?>
		<td>
<?php if ($servicio_medico_prestado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_servicio_medico_prestado_fecha_inicio" class="form-group servicio_medico_prestado_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_inicio->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->fecha_inicio->EditValue ?>"<?php echo $servicio_medico_prestado->fecha_inicio->EditAttributes() ?>>
<?php if (!$servicio_medico_prestado->fecha_inicio->ReadOnly && !$servicio_medico_prestado->fecha_inicio->Disabled && @$servicio_medico_prestado->fecha_inicio->EditAttrs["readonly"] == "" && @$servicio_medico_prestado->fecha_inicio->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fservicio_medico_prestadogrid", "x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_servicio_medico_prestado_fecha_inicio" class="form-group servicio_medico_prestado_fecha_inicio">
<span<?php echo $servicio_medico_prestado->fecha_inicio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->fecha_inicio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_inicio" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_inicio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_inicio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->fecha_final->Visible) { // fecha_final ?>
		<td>
<?php if ($servicio_medico_prestado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_servicio_medico_prestado_fecha_final" class="form-group servicio_medico_prestado_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_final->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->fecha_final->EditValue ?>"<?php echo $servicio_medico_prestado->fecha_final->EditAttributes() ?>>
<?php if (!$servicio_medico_prestado->fecha_final->ReadOnly && !$servicio_medico_prestado->fecha_final->Disabled && @$servicio_medico_prestado->fecha_final->EditAttrs["readonly"] == "" && @$servicio_medico_prestado->fecha_final->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fservicio_medico_prestadogrid", "x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_servicio_medico_prestado_fecha_final" class="form-group servicio_medico_prestado_fecha_final">
<span<?php echo $servicio_medico_prestado->fecha_final->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->fecha_final->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_final" name="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" id="x<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_final->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" id="o<?php echo $servicio_medico_prestado_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->fecha_final->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$servicio_medico_prestado_grid->ListOptions->Render("body", "right", $servicio_medico_prestado_grid->RowCnt);
?>
<script type="text/javascript">
fservicio_medico_prestadogrid.UpdateOpts(<?php echo $servicio_medico_prestado_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($servicio_medico_prestado->CurrentMode == "add" || $servicio_medico_prestado->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $servicio_medico_prestado_grid->FormKeyCountName ?>" id="<?php echo $servicio_medico_prestado_grid->FormKeyCountName ?>" value="<?php echo $servicio_medico_prestado_grid->KeyCount ?>">
<?php echo $servicio_medico_prestado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($servicio_medico_prestado->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $servicio_medico_prestado_grid->FormKeyCountName ?>" id="<?php echo $servicio_medico_prestado_grid->FormKeyCountName ?>" value="<?php echo $servicio_medico_prestado_grid->KeyCount ?>">
<?php echo $servicio_medico_prestado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($servicio_medico_prestado->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fservicio_medico_prestadogrid">
</div>
<?php

// Close recordset
if ($servicio_medico_prestado_grid->Recordset)
	$servicio_medico_prestado_grid->Recordset->Close();
?>
<?php if ($servicio_medico_prestado_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($servicio_medico_prestado_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($servicio_medico_prestado_grid->TotalRecs == 0 && $servicio_medico_prestado->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($servicio_medico_prestado_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($servicio_medico_prestado->Export == "") { ?>
<script type="text/javascript">
fservicio_medico_prestadogrid.Init();
</script>
<?php } ?>
<?php
$servicio_medico_prestado_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$servicio_medico_prestado_grid->Page_Terminate();
?>

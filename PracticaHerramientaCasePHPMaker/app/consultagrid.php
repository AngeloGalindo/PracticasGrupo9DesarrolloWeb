<?php

// Create page object
if (!isset($consulta_grid)) $consulta_grid = new cconsulta_grid();

// Page init
$consulta_grid->Page_Init();

// Page main
$consulta_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$consulta_grid->Page_Render();
?>
<?php if ($consulta->Export == "") { ?>
<script type="text/javascript">

// Page object
var consulta_grid = new ew_Page("consulta_grid");
consulta_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = consulta_grid.PageID; // For backward compatibility

// Form object
var fconsultagrid = new ew_Form("fconsultagrid");
fconsultagrid.FormKeyCountName = '<?php echo $consulta_grid->FormKeyCountName ?>';

// Validate form
fconsultagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idcuenta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $consulta->idcuenta->FldCaption(), $consulta->idcuenta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_cita");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($consulta->fecha_cita->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_costo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($consulta->costo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($consulta->fecha_inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_final");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($consulta->fecha_final->FldErrMsg()) ?>");

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
fconsultagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idcuenta", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_cita", false)) return false;
	if (ew_ValueChanged(fobj, infix, "iddoctor", false)) return false;
	if (ew_ValueChanged(fobj, infix, "costo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_inicio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_final", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	return true;
}

// Form_CustomValidate event
fconsultagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconsultagrid.ValidateRequired = true;
<?php } else { ?>
fconsultagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fconsultagrid.Lists["x_idcuenta"] = {"LinkField":"x_idcuenta","Ajax":true,"AutoFill":false,"DisplayFields":["x_idcuenta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fconsultagrid.Lists["x_iddoctor"] = {"LinkField":"x_iddoctor","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($consulta->CurrentAction == "gridadd") {
	if ($consulta->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$consulta_grid->TotalRecs = $consulta->SelectRecordCount();
			$consulta_grid->Recordset = $consulta_grid->LoadRecordset($consulta_grid->StartRec-1, $consulta_grid->DisplayRecs);
		} else {
			if ($consulta_grid->Recordset = $consulta_grid->LoadRecordset())
				$consulta_grid->TotalRecs = $consulta_grid->Recordset->RecordCount();
		}
		$consulta_grid->StartRec = 1;
		$consulta_grid->DisplayRecs = $consulta_grid->TotalRecs;
	} else {
		$consulta->CurrentFilter = "0=1";
		$consulta_grid->StartRec = 1;
		$consulta_grid->DisplayRecs = $consulta->GridAddRowCount;
	}
	$consulta_grid->TotalRecs = $consulta_grid->DisplayRecs;
	$consulta_grid->StopRec = $consulta_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$consulta_grid->TotalRecs = $consulta->SelectRecordCount();
	} else {
		if ($consulta_grid->Recordset = $consulta_grid->LoadRecordset())
			$consulta_grid->TotalRecs = $consulta_grid->Recordset->RecordCount();
	}
	$consulta_grid->StartRec = 1;
	$consulta_grid->DisplayRecs = $consulta_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$consulta_grid->Recordset = $consulta_grid->LoadRecordset($consulta_grid->StartRec-1, $consulta_grid->DisplayRecs);

	// Set no record found message
	if ($consulta->CurrentAction == "" && $consulta_grid->TotalRecs == 0) {
		if ($consulta_grid->SearchWhere == "0=101")
			$consulta_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$consulta_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$consulta_grid->RenderOtherOptions();
?>
<?php $consulta_grid->ShowPageHeader(); ?>
<?php
$consulta_grid->ShowMessage();
?>
<?php if ($consulta_grid->TotalRecs > 0 || $consulta->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fconsultagrid" class="ewForm form-inline">
<div id="gmp_consulta" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_consultagrid" class="table ewTable">
<?php echo $consulta->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$consulta_grid->RenderListOptions();

// Render list options (header, left)
$consulta_grid->ListOptions->Render("header", "left");
?>
<?php if ($consulta->idconsulta->Visible) { // idconsulta ?>
	<?php if ($consulta->SortUrl($consulta->idconsulta) == "") { ?>
		<th data-name="idconsulta"><div id="elh_consulta_idconsulta" class="consulta_idconsulta"><div class="ewTableHeaderCaption"><?php echo $consulta->idconsulta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idconsulta"><div><div id="elh_consulta_idconsulta" class="consulta_idconsulta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta->idconsulta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta->idconsulta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta->idconsulta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consulta->idcuenta->Visible) { // idcuenta ?>
	<?php if ($consulta->SortUrl($consulta->idcuenta) == "") { ?>
		<th data-name="idcuenta"><div id="elh_consulta_idcuenta" class="consulta_idcuenta"><div class="ewTableHeaderCaption"><?php echo $consulta->idcuenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idcuenta"><div><div id="elh_consulta_idcuenta" class="consulta_idcuenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta->idcuenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta->idcuenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta->idcuenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consulta->fecha_cita->Visible) { // fecha_cita ?>
	<?php if ($consulta->SortUrl($consulta->fecha_cita) == "") { ?>
		<th data-name="fecha_cita"><div id="elh_consulta_fecha_cita" class="consulta_fecha_cita"><div class="ewTableHeaderCaption"><?php echo $consulta->fecha_cita->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_cita"><div><div id="elh_consulta_fecha_cita" class="consulta_fecha_cita">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta->fecha_cita->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta->fecha_cita->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta->fecha_cita->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consulta->iddoctor->Visible) { // iddoctor ?>
	<?php if ($consulta->SortUrl($consulta->iddoctor) == "") { ?>
		<th data-name="iddoctor"><div id="elh_consulta_iddoctor" class="consulta_iddoctor"><div class="ewTableHeaderCaption"><?php echo $consulta->iddoctor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iddoctor"><div><div id="elh_consulta_iddoctor" class="consulta_iddoctor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta->iddoctor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta->iddoctor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta->iddoctor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consulta->costo->Visible) { // costo ?>
	<?php if ($consulta->SortUrl($consulta->costo) == "") { ?>
		<th data-name="costo"><div id="elh_consulta_costo" class="consulta_costo"><div class="ewTableHeaderCaption"><?php echo $consulta->costo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="costo"><div><div id="elh_consulta_costo" class="consulta_costo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta->costo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta->costo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta->costo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consulta->fecha_inicio->Visible) { // fecha_inicio ?>
	<?php if ($consulta->SortUrl($consulta->fecha_inicio) == "") { ?>
		<th data-name="fecha_inicio"><div id="elh_consulta_fecha_inicio" class="consulta_fecha_inicio"><div class="ewTableHeaderCaption"><?php echo $consulta->fecha_inicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_inicio"><div><div id="elh_consulta_fecha_inicio" class="consulta_fecha_inicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta->fecha_inicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta->fecha_inicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta->fecha_inicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consulta->fecha_final->Visible) { // fecha_final ?>
	<?php if ($consulta->SortUrl($consulta->fecha_final) == "") { ?>
		<th data-name="fecha_final"><div id="elh_consulta_fecha_final" class="consulta_fecha_final"><div class="ewTableHeaderCaption"><?php echo $consulta->fecha_final->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_final"><div><div id="elh_consulta_fecha_final" class="consulta_fecha_final">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta->fecha_final->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta->fecha_final->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta->fecha_final->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consulta->estado->Visible) { // estado ?>
	<?php if ($consulta->SortUrl($consulta->estado) == "") { ?>
		<th data-name="estado"><div id="elh_consulta_estado" class="consulta_estado"><div class="ewTableHeaderCaption"><?php echo $consulta->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_consulta_estado" class="consulta_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$consulta_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$consulta_grid->StartRec = 1;
$consulta_grid->StopRec = $consulta_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($consulta_grid->FormKeyCountName) && ($consulta->CurrentAction == "gridadd" || $consulta->CurrentAction == "gridedit" || $consulta->CurrentAction == "F")) {
		$consulta_grid->KeyCount = $objForm->GetValue($consulta_grid->FormKeyCountName);
		$consulta_grid->StopRec = $consulta_grid->StartRec + $consulta_grid->KeyCount - 1;
	}
}
$consulta_grid->RecCnt = $consulta_grid->StartRec - 1;
if ($consulta_grid->Recordset && !$consulta_grid->Recordset->EOF) {
	$consulta_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $consulta_grid->StartRec > 1)
		$consulta_grid->Recordset->Move($consulta_grid->StartRec - 1);
} elseif (!$consulta->AllowAddDeleteRow && $consulta_grid->StopRec == 0) {
	$consulta_grid->StopRec = $consulta->GridAddRowCount;
}

// Initialize aggregate
$consulta->RowType = EW_ROWTYPE_AGGREGATEINIT;
$consulta->ResetAttrs();
$consulta_grid->RenderRow();
if ($consulta->CurrentAction == "gridadd")
	$consulta_grid->RowIndex = 0;
if ($consulta->CurrentAction == "gridedit")
	$consulta_grid->RowIndex = 0;
while ($consulta_grid->RecCnt < $consulta_grid->StopRec) {
	$consulta_grid->RecCnt++;
	if (intval($consulta_grid->RecCnt) >= intval($consulta_grid->StartRec)) {
		$consulta_grid->RowCnt++;
		if ($consulta->CurrentAction == "gridadd" || $consulta->CurrentAction == "gridedit" || $consulta->CurrentAction == "F") {
			$consulta_grid->RowIndex++;
			$objForm->Index = $consulta_grid->RowIndex;
			if ($objForm->HasValue($consulta_grid->FormActionName))
				$consulta_grid->RowAction = strval($objForm->GetValue($consulta_grid->FormActionName));
			elseif ($consulta->CurrentAction == "gridadd")
				$consulta_grid->RowAction = "insert";
			else
				$consulta_grid->RowAction = "";
		}

		// Set up key count
		$consulta_grid->KeyCount = $consulta_grid->RowIndex;

		// Init row class and style
		$consulta->ResetAttrs();
		$consulta->CssClass = "";
		if ($consulta->CurrentAction == "gridadd") {
			if ($consulta->CurrentMode == "copy") {
				$consulta_grid->LoadRowValues($consulta_grid->Recordset); // Load row values
				$consulta_grid->SetRecordKey($consulta_grid->RowOldKey, $consulta_grid->Recordset); // Set old record key
			} else {
				$consulta_grid->LoadDefaultValues(); // Load default values
				$consulta_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$consulta_grid->LoadRowValues($consulta_grid->Recordset); // Load row values
		}
		$consulta->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($consulta->CurrentAction == "gridadd") // Grid add
			$consulta->RowType = EW_ROWTYPE_ADD; // Render add
		if ($consulta->CurrentAction == "gridadd" && $consulta->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$consulta_grid->RestoreCurrentRowFormValues($consulta_grid->RowIndex); // Restore form values
		if ($consulta->CurrentAction == "gridedit") { // Grid edit
			if ($consulta->EventCancelled) {
				$consulta_grid->RestoreCurrentRowFormValues($consulta_grid->RowIndex); // Restore form values
			}
			if ($consulta_grid->RowAction == "insert")
				$consulta->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$consulta->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($consulta->CurrentAction == "gridedit" && ($consulta->RowType == EW_ROWTYPE_EDIT || $consulta->RowType == EW_ROWTYPE_ADD) && $consulta->EventCancelled) // Update failed
			$consulta_grid->RestoreCurrentRowFormValues($consulta_grid->RowIndex); // Restore form values
		if ($consulta->RowType == EW_ROWTYPE_EDIT) // Edit row
			$consulta_grid->EditRowCnt++;
		if ($consulta->CurrentAction == "F") // Confirm row
			$consulta_grid->RestoreCurrentRowFormValues($consulta_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$consulta->RowAttrs = array_merge($consulta->RowAttrs, array('data-rowindex'=>$consulta_grid->RowCnt, 'id'=>'r' . $consulta_grid->RowCnt . '_consulta', 'data-rowtype'=>$consulta->RowType));

		// Render row
		$consulta_grid->RenderRow();

		// Render list options
		$consulta_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($consulta_grid->RowAction <> "delete" && $consulta_grid->RowAction <> "insertdelete" && !($consulta_grid->RowAction == "insert" && $consulta->CurrentAction == "F" && $consulta_grid->EmptyRow())) {
?>
	<tr<?php echo $consulta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$consulta_grid->ListOptions->Render("body", "left", $consulta_grid->RowCnt);
?>
	<?php if ($consulta->idconsulta->Visible) { // idconsulta ?>
		<td data-name="idconsulta"<?php echo $consulta->idconsulta->CellAttributes() ?>>
<?php if ($consulta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idconsulta" name="o<?php echo $consulta_grid->RowIndex ?>_idconsulta" id="o<?php echo $consulta_grid->RowIndex ?>_idconsulta" value="<?php echo ew_HtmlEncode($consulta->idconsulta->OldValue) ?>">
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_idconsulta" class="form-group consulta_idconsulta">
<span<?php echo $consulta->idconsulta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->idconsulta->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_idconsulta" name="x<?php echo $consulta_grid->RowIndex ?>_idconsulta" id="x<?php echo $consulta_grid->RowIndex ?>_idconsulta" value="<?php echo ew_HtmlEncode($consulta->idconsulta->CurrentValue) ?>">
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $consulta->idconsulta->ViewAttributes() ?>>
<?php echo $consulta->idconsulta->ListViewValue() ?></span>
<input type="hidden" data-field="x_idconsulta" name="x<?php echo $consulta_grid->RowIndex ?>_idconsulta" id="x<?php echo $consulta_grid->RowIndex ?>_idconsulta" value="<?php echo ew_HtmlEncode($consulta->idconsulta->FormValue) ?>">
<input type="hidden" data-field="x_idconsulta" name="o<?php echo $consulta_grid->RowIndex ?>_idconsulta" id="o<?php echo $consulta_grid->RowIndex ?>_idconsulta" value="<?php echo ew_HtmlEncode($consulta->idconsulta->OldValue) ?>">
<?php } ?>
<a id="<?php echo $consulta_grid->PageObjName . "_row_" . $consulta_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($consulta->idcuenta->Visible) { // idcuenta ?>
		<td data-name="idcuenta"<?php echo $consulta->idcuenta->CellAttributes() ?>>
<?php if ($consulta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($consulta->idcuenta->getSessionValue() <> "") { ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_idcuenta" class="form-group consulta_idcuenta">
<span<?php echo $consulta->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" name="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($consulta->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_idcuenta" class="form-group consulta_idcuenta">
<select data-field="x_idcuenta" id="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" name="x<?php echo $consulta_grid->RowIndex ?>_idcuenta"<?php echo $consulta->idcuenta->EditAttributes() ?>>
<?php
if (is_array($consulta->idcuenta->EditValue)) {
	$arwrk = $consulta->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $consulta->idcuenta->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $consulta->Lookup_Selecting($consulta->idcuenta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $consulta_grid->RowIndex ?>_idcuenta" id="s_x<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $consulta_grid->RowIndex ?>_idcuenta" id="o<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($consulta->idcuenta->OldValue) ?>">
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($consulta->idcuenta->getSessionValue() <> "") { ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_idcuenta" class="form-group consulta_idcuenta">
<span<?php echo $consulta->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" name="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($consulta->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_idcuenta" class="form-group consulta_idcuenta">
<select data-field="x_idcuenta" id="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" name="x<?php echo $consulta_grid->RowIndex ?>_idcuenta"<?php echo $consulta->idcuenta->EditAttributes() ?>>
<?php
if (is_array($consulta->idcuenta->EditValue)) {
	$arwrk = $consulta->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $consulta->idcuenta->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $consulta->Lookup_Selecting($consulta->idcuenta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $consulta_grid->RowIndex ?>_idcuenta" id="s_x<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $consulta->idcuenta->ViewAttributes() ?>>
<?php echo $consulta->idcuenta->ListViewValue() ?></span>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" id="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($consulta->idcuenta->FormValue) ?>">
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $consulta_grid->RowIndex ?>_idcuenta" id="o<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($consulta->idcuenta->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($consulta->fecha_cita->Visible) { // fecha_cita ?>
		<td data-name="fecha_cita"<?php echo $consulta->fecha_cita->CellAttributes() ?>>
<?php if ($consulta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_fecha_cita" class="form-group consulta_fecha_cita">
<input type="text" data-field="x_fecha_cita" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_cita->PlaceHolder) ?>" value="<?php echo $consulta->fecha_cita->EditValue ?>"<?php echo $consulta->fecha_cita->EditAttributes() ?>>
<?php if (!$consulta->fecha_cita->ReadOnly && !$consulta->fecha_cita->Disabled && @$consulta->fecha_cita->EditAttrs["readonly"] == "" && @$consulta->fecha_cita->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fconsultagrid", "x<?php echo $consulta_grid->RowIndex ?>_fecha_cita", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha_cita" name="o<?php echo $consulta_grid->RowIndex ?>_fecha_cita" id="o<?php echo $consulta_grid->RowIndex ?>_fecha_cita" value="<?php echo ew_HtmlEncode($consulta->fecha_cita->OldValue) ?>">
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_fecha_cita" class="form-group consulta_fecha_cita">
<input type="text" data-field="x_fecha_cita" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_cita->PlaceHolder) ?>" value="<?php echo $consulta->fecha_cita->EditValue ?>"<?php echo $consulta->fecha_cita->EditAttributes() ?>>
<?php if (!$consulta->fecha_cita->ReadOnly && !$consulta->fecha_cita->Disabled && @$consulta->fecha_cita->EditAttrs["readonly"] == "" && @$consulta->fecha_cita->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fconsultagrid", "x<?php echo $consulta_grid->RowIndex ?>_fecha_cita", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $consulta->fecha_cita->ViewAttributes() ?>>
<?php echo $consulta->fecha_cita->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_cita" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" value="<?php echo ew_HtmlEncode($consulta->fecha_cita->FormValue) ?>">
<input type="hidden" data-field="x_fecha_cita" name="o<?php echo $consulta_grid->RowIndex ?>_fecha_cita" id="o<?php echo $consulta_grid->RowIndex ?>_fecha_cita" value="<?php echo ew_HtmlEncode($consulta->fecha_cita->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($consulta->iddoctor->Visible) { // iddoctor ?>
		<td data-name="iddoctor"<?php echo $consulta->iddoctor->CellAttributes() ?>>
<?php if ($consulta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($consulta->iddoctor->getSessionValue() <> "") { ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_iddoctor" class="form-group consulta_iddoctor">
<span<?php echo $consulta->iddoctor->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->iddoctor->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" name="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($consulta->iddoctor->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_iddoctor" class="form-group consulta_iddoctor">
<select data-field="x_iddoctor" id="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" name="x<?php echo $consulta_grid->RowIndex ?>_iddoctor"<?php echo $consulta->iddoctor->EditAttributes() ?>>
<?php
if (is_array($consulta->iddoctor->EditValue)) {
	$arwrk = $consulta->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $consulta->iddoctor->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $consulta->Lookup_Selecting($consulta->iddoctor, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $consulta_grid->RowIndex ?>_iddoctor" id="s_x<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $consulta_grid->RowIndex ?>_iddoctor" id="o<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($consulta->iddoctor->OldValue) ?>">
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($consulta->iddoctor->getSessionValue() <> "") { ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_iddoctor" class="form-group consulta_iddoctor">
<span<?php echo $consulta->iddoctor->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->iddoctor->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" name="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($consulta->iddoctor->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_iddoctor" class="form-group consulta_iddoctor">
<select data-field="x_iddoctor" id="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" name="x<?php echo $consulta_grid->RowIndex ?>_iddoctor"<?php echo $consulta->iddoctor->EditAttributes() ?>>
<?php
if (is_array($consulta->iddoctor->EditValue)) {
	$arwrk = $consulta->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $consulta->iddoctor->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $consulta->Lookup_Selecting($consulta->iddoctor, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $consulta_grid->RowIndex ?>_iddoctor" id="s_x<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $consulta->iddoctor->ViewAttributes() ?>>
<?php echo $consulta->iddoctor->ListViewValue() ?></span>
<input type="hidden" data-field="x_iddoctor" name="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" id="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($consulta->iddoctor->FormValue) ?>">
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $consulta_grid->RowIndex ?>_iddoctor" id="o<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($consulta->iddoctor->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($consulta->costo->Visible) { // costo ?>
		<td data-name="costo"<?php echo $consulta->costo->CellAttributes() ?>>
<?php if ($consulta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_costo" class="form-group consulta_costo">
<input type="text" data-field="x_costo" name="x<?php echo $consulta_grid->RowIndex ?>_costo" id="x<?php echo $consulta_grid->RowIndex ?>_costo" size="30" placeholder="<?php echo ew_HtmlEncode($consulta->costo->PlaceHolder) ?>" value="<?php echo $consulta->costo->EditValue ?>"<?php echo $consulta->costo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_costo" name="o<?php echo $consulta_grid->RowIndex ?>_costo" id="o<?php echo $consulta_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($consulta->costo->OldValue) ?>">
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_costo" class="form-group consulta_costo">
<input type="text" data-field="x_costo" name="x<?php echo $consulta_grid->RowIndex ?>_costo" id="x<?php echo $consulta_grid->RowIndex ?>_costo" size="30" placeholder="<?php echo ew_HtmlEncode($consulta->costo->PlaceHolder) ?>" value="<?php echo $consulta->costo->EditValue ?>"<?php echo $consulta->costo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $consulta->costo->ViewAttributes() ?>>
<?php echo $consulta->costo->ListViewValue() ?></span>
<input type="hidden" data-field="x_costo" name="x<?php echo $consulta_grid->RowIndex ?>_costo" id="x<?php echo $consulta_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($consulta->costo->FormValue) ?>">
<input type="hidden" data-field="x_costo" name="o<?php echo $consulta_grid->RowIndex ?>_costo" id="o<?php echo $consulta_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($consulta->costo->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($consulta->fecha_inicio->Visible) { // fecha_inicio ?>
		<td data-name="fecha_inicio"<?php echo $consulta->fecha_inicio->CellAttributes() ?>>
<?php if ($consulta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_fecha_inicio" class="form-group consulta_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_inicio->PlaceHolder) ?>" value="<?php echo $consulta->fecha_inicio->EditValue ?>"<?php echo $consulta->fecha_inicio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($consulta->fecha_inicio->OldValue) ?>">
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_fecha_inicio" class="form-group consulta_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_inicio->PlaceHolder) ?>" value="<?php echo $consulta->fecha_inicio->EditValue ?>"<?php echo $consulta->fecha_inicio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $consulta->fecha_inicio->ViewAttributes() ?>>
<?php echo $consulta->fecha_inicio->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_inicio" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($consulta->fecha_inicio->FormValue) ?>">
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($consulta->fecha_inicio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($consulta->fecha_final->Visible) { // fecha_final ?>
		<td data-name="fecha_final"<?php echo $consulta->fecha_final->CellAttributes() ?>>
<?php if ($consulta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_fecha_final" class="form-group consulta_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_final->PlaceHolder) ?>" value="<?php echo $consulta->fecha_final->EditValue ?>"<?php echo $consulta->fecha_final->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $consulta_grid->RowIndex ?>_fecha_final" id="o<?php echo $consulta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($consulta->fecha_final->OldValue) ?>">
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_fecha_final" class="form-group consulta_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_final->PlaceHolder) ?>" value="<?php echo $consulta->fecha_final->EditValue ?>"<?php echo $consulta->fecha_final->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $consulta->fecha_final->ViewAttributes() ?>>
<?php echo $consulta->fecha_final->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha_final" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($consulta->fecha_final->FormValue) ?>">
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $consulta_grid->RowIndex ?>_fecha_final" id="o<?php echo $consulta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($consulta->fecha_final->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($consulta->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $consulta->estado->CellAttributes() ?>>
<?php if ($consulta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_estado" class="form-group consulta_estado">
<div id="tp_x<?php echo $consulta_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $consulta_grid->RowIndex ?>_estado" id="x<?php echo $consulta_grid->RowIndex ?>_estado" value="{value}"<?php echo $consulta->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $consulta_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $consulta->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $consulta_grid->RowIndex ?>_estado" id="x<?php echo $consulta_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $consulta->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $consulta->estado->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $consulta_grid->RowIndex ?>_estado" id="o<?php echo $consulta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($consulta->estado->OldValue) ?>">
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $consulta_grid->RowCnt ?>_consulta_estado" class="form-group consulta_estado">
<div id="tp_x<?php echo $consulta_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $consulta_grid->RowIndex ?>_estado" id="x<?php echo $consulta_grid->RowIndex ?>_estado" value="{value}"<?php echo $consulta->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $consulta_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $consulta->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $consulta_grid->RowIndex ?>_estado" id="x<?php echo $consulta_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $consulta->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $consulta->estado->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($consulta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $consulta->estado->ViewAttributes() ?>>
<?php echo $consulta->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $consulta_grid->RowIndex ?>_estado" id="x<?php echo $consulta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($consulta->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $consulta_grid->RowIndex ?>_estado" id="o<?php echo $consulta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($consulta->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$consulta_grid->ListOptions->Render("body", "right", $consulta_grid->RowCnt);
?>
	</tr>
<?php if ($consulta->RowType == EW_ROWTYPE_ADD || $consulta->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fconsultagrid.UpdateOpts(<?php echo $consulta_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($consulta->CurrentAction <> "gridadd" || $consulta->CurrentMode == "copy")
		if (!$consulta_grid->Recordset->EOF) $consulta_grid->Recordset->MoveNext();
}
?>
<?php
	if ($consulta->CurrentMode == "add" || $consulta->CurrentMode == "copy" || $consulta->CurrentMode == "edit") {
		$consulta_grid->RowIndex = '$rowindex$';
		$consulta_grid->LoadDefaultValues();

		// Set row properties
		$consulta->ResetAttrs();
		$consulta->RowAttrs = array_merge($consulta->RowAttrs, array('data-rowindex'=>$consulta_grid->RowIndex, 'id'=>'r0_consulta', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($consulta->RowAttrs["class"], "ewTemplate");
		$consulta->RowType = EW_ROWTYPE_ADD;

		// Render row
		$consulta_grid->RenderRow();

		// Render list options
		$consulta_grid->RenderListOptions();
		$consulta_grid->StartRowCnt = 0;
?>
	<tr<?php echo $consulta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$consulta_grid->ListOptions->Render("body", "left", $consulta_grid->RowIndex);
?>
	<?php if ($consulta->idconsulta->Visible) { // idconsulta ?>
		<td>
<?php if ($consulta->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_consulta_idconsulta" class="form-group consulta_idconsulta">
<span<?php echo $consulta->idconsulta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->idconsulta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idconsulta" name="x<?php echo $consulta_grid->RowIndex ?>_idconsulta" id="x<?php echo $consulta_grid->RowIndex ?>_idconsulta" value="<?php echo ew_HtmlEncode($consulta->idconsulta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idconsulta" name="o<?php echo $consulta_grid->RowIndex ?>_idconsulta" id="o<?php echo $consulta_grid->RowIndex ?>_idconsulta" value="<?php echo ew_HtmlEncode($consulta->idconsulta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($consulta->idcuenta->Visible) { // idcuenta ?>
		<td>
<?php if ($consulta->CurrentAction <> "F") { ?>
<?php if ($consulta->idcuenta->getSessionValue() <> "") { ?>
<span id="el$rowindex$_consulta_idcuenta" class="form-group consulta_idcuenta">
<span<?php echo $consulta->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" name="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($consulta->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_consulta_idcuenta" class="form-group consulta_idcuenta">
<select data-field="x_idcuenta" id="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" name="x<?php echo $consulta_grid->RowIndex ?>_idcuenta"<?php echo $consulta->idcuenta->EditAttributes() ?>>
<?php
if (is_array($consulta->idcuenta->EditValue)) {
	$arwrk = $consulta->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $consulta->idcuenta->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $consulta->Lookup_Selecting($consulta->idcuenta, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $consulta_grid->RowIndex ?>_idcuenta" id="s_x<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_consulta_idcuenta" class="form-group consulta_idcuenta">
<span<?php echo $consulta->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" id="x<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($consulta->idcuenta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $consulta_grid->RowIndex ?>_idcuenta" id="o<?php echo $consulta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($consulta->idcuenta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($consulta->fecha_cita->Visible) { // fecha_cita ?>
		<td>
<?php if ($consulta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_consulta_fecha_cita" class="form-group consulta_fecha_cita">
<input type="text" data-field="x_fecha_cita" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_cita->PlaceHolder) ?>" value="<?php echo $consulta->fecha_cita->EditValue ?>"<?php echo $consulta->fecha_cita->EditAttributes() ?>>
<?php if (!$consulta->fecha_cita->ReadOnly && !$consulta->fecha_cita->Disabled && @$consulta->fecha_cita->EditAttrs["readonly"] == "" && @$consulta->fecha_cita->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fconsultagrid", "x<?php echo $consulta_grid->RowIndex ?>_fecha_cita", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_consulta_fecha_cita" class="form-group consulta_fecha_cita">
<span<?php echo $consulta->fecha_cita->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->fecha_cita->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_cita" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_cita" value="<?php echo ew_HtmlEncode($consulta->fecha_cita->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_cita" name="o<?php echo $consulta_grid->RowIndex ?>_fecha_cita" id="o<?php echo $consulta_grid->RowIndex ?>_fecha_cita" value="<?php echo ew_HtmlEncode($consulta->fecha_cita->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($consulta->iddoctor->Visible) { // iddoctor ?>
		<td>
<?php if ($consulta->CurrentAction <> "F") { ?>
<?php if ($consulta->iddoctor->getSessionValue() <> "") { ?>
<span id="el$rowindex$_consulta_iddoctor" class="form-group consulta_iddoctor">
<span<?php echo $consulta->iddoctor->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->iddoctor->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" name="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($consulta->iddoctor->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_consulta_iddoctor" class="form-group consulta_iddoctor">
<select data-field="x_iddoctor" id="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" name="x<?php echo $consulta_grid->RowIndex ?>_iddoctor"<?php echo $consulta->iddoctor->EditAttributes() ?>>
<?php
if (is_array($consulta->iddoctor->EditValue)) {
	$arwrk = $consulta->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $consulta->iddoctor->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $consulta->Lookup_Selecting($consulta->iddoctor, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $consulta_grid->RowIndex ?>_iddoctor" id="s_x<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_consulta_iddoctor" class="form-group consulta_iddoctor">
<span<?php echo $consulta->iddoctor->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->iddoctor->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iddoctor" name="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" id="x<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($consulta->iddoctor->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $consulta_grid->RowIndex ?>_iddoctor" id="o<?php echo $consulta_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($consulta->iddoctor->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($consulta->costo->Visible) { // costo ?>
		<td>
<?php if ($consulta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_consulta_costo" class="form-group consulta_costo">
<input type="text" data-field="x_costo" name="x<?php echo $consulta_grid->RowIndex ?>_costo" id="x<?php echo $consulta_grid->RowIndex ?>_costo" size="30" placeholder="<?php echo ew_HtmlEncode($consulta->costo->PlaceHolder) ?>" value="<?php echo $consulta->costo->EditValue ?>"<?php echo $consulta->costo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_consulta_costo" class="form-group consulta_costo">
<span<?php echo $consulta->costo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->costo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_costo" name="x<?php echo $consulta_grid->RowIndex ?>_costo" id="x<?php echo $consulta_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($consulta->costo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_costo" name="o<?php echo $consulta_grid->RowIndex ?>_costo" id="o<?php echo $consulta_grid->RowIndex ?>_costo" value="<?php echo ew_HtmlEncode($consulta->costo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($consulta->fecha_inicio->Visible) { // fecha_inicio ?>
		<td>
<?php if ($consulta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_consulta_fecha_inicio" class="form-group consulta_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_inicio->PlaceHolder) ?>" value="<?php echo $consulta->fecha_inicio->EditValue ?>"<?php echo $consulta->fecha_inicio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_consulta_fecha_inicio" class="form-group consulta_fecha_inicio">
<span<?php echo $consulta->fecha_inicio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->fecha_inicio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_inicio" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($consulta->fecha_inicio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_inicio" name="o<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" id="o<?php echo $consulta_grid->RowIndex ?>_fecha_inicio" value="<?php echo ew_HtmlEncode($consulta->fecha_inicio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($consulta->fecha_final->Visible) { // fecha_final ?>
		<td>
<?php if ($consulta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_consulta_fecha_final" class="form-group consulta_fecha_final">
<input type="text" data-field="x_fecha_final" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_final->PlaceHolder) ?>" value="<?php echo $consulta->fecha_final->EditValue ?>"<?php echo $consulta->fecha_final->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_consulta_fecha_final" class="form-group consulta_fecha_final">
<span<?php echo $consulta->fecha_final->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->fecha_final->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha_final" name="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" id="x<?php echo $consulta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($consulta->fecha_final->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha_final" name="o<?php echo $consulta_grid->RowIndex ?>_fecha_final" id="o<?php echo $consulta_grid->RowIndex ?>_fecha_final" value="<?php echo ew_HtmlEncode($consulta->fecha_final->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($consulta->estado->Visible) { // estado ?>
		<td>
<?php if ($consulta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_consulta_estado" class="form-group consulta_estado">
<div id="tp_x<?php echo $consulta_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $consulta_grid->RowIndex ?>_estado" id="x<?php echo $consulta_grid->RowIndex ?>_estado" value="{value}"<?php echo $consulta->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $consulta_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $consulta->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $consulta_grid->RowIndex ?>_estado" id="x<?php echo $consulta_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $consulta->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $consulta->estado->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_consulta_estado" class="form-group consulta_estado">
<span<?php echo $consulta->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $consulta_grid->RowIndex ?>_estado" id="x<?php echo $consulta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($consulta->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $consulta_grid->RowIndex ?>_estado" id="o<?php echo $consulta_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($consulta->estado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$consulta_grid->ListOptions->Render("body", "right", $consulta_grid->RowCnt);
?>
<script type="text/javascript">
fconsultagrid.UpdateOpts(<?php echo $consulta_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($consulta->CurrentMode == "add" || $consulta->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $consulta_grid->FormKeyCountName ?>" id="<?php echo $consulta_grid->FormKeyCountName ?>" value="<?php echo $consulta_grid->KeyCount ?>">
<?php echo $consulta_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($consulta->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $consulta_grid->FormKeyCountName ?>" id="<?php echo $consulta_grid->FormKeyCountName ?>" value="<?php echo $consulta_grid->KeyCount ?>">
<?php echo $consulta_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($consulta->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fconsultagrid">
</div>
<?php

// Close recordset
if ($consulta_grid->Recordset)
	$consulta_grid->Recordset->Close();
?>
<?php if ($consulta_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($consulta_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($consulta_grid->TotalRecs == 0 && $consulta->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($consulta_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($consulta->Export == "") { ?>
<script type="text/javascript">
fconsultagrid.Init();
</script>
<?php } ?>
<?php
$consulta_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$consulta_grid->Page_Terminate();
?>

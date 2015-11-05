<?php

// Create page object
if (!isset($empleado_grid)) $empleado_grid = new cempleado_grid();

// Page init
$empleado_grid->Page_Init();

// Page main
$empleado_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empleado_grid->Page_Render();
?>
<?php if ($empleado->Export == "") { ?>
<script type="text/javascript">

// Page object
var empleado_grid = new ew_Page("empleado_grid");
empleado_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = empleado_grid.PageID; // For backward compatibility

// Form object
var fempleadogrid = new ew_Form("fempleadogrid");
fempleadogrid.FormKeyCountName = '<?php echo $empleado_grid->FormKeyCountName ?>';

// Validate form
fempleadogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->nombre->FldCaption(), $empleado->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->estado->FldCaption(), $empleado->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idhospital");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $empleado->idhospital->FldCaption(), $empleado->idhospital->ReqErrMsg)) ?>");

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
fempleadogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "apellido", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cui", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telefono", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direccion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idhospital", false)) return false;
	return true;
}

// Form_CustomValidate event
fempleadogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempleadogrid.ValidateRequired = true;
<?php } else { ?>
fempleadogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempleadogrid.Lists["x_idhospital"] = {"LinkField":"x_idhospital","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($empleado->CurrentAction == "gridadd") {
	if ($empleado->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$empleado_grid->TotalRecs = $empleado->SelectRecordCount();
			$empleado_grid->Recordset = $empleado_grid->LoadRecordset($empleado_grid->StartRec-1, $empleado_grid->DisplayRecs);
		} else {
			if ($empleado_grid->Recordset = $empleado_grid->LoadRecordset())
				$empleado_grid->TotalRecs = $empleado_grid->Recordset->RecordCount();
		}
		$empleado_grid->StartRec = 1;
		$empleado_grid->DisplayRecs = $empleado_grid->TotalRecs;
	} else {
		$empleado->CurrentFilter = "0=1";
		$empleado_grid->StartRec = 1;
		$empleado_grid->DisplayRecs = $empleado->GridAddRowCount;
	}
	$empleado_grid->TotalRecs = $empleado_grid->DisplayRecs;
	$empleado_grid->StopRec = $empleado_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$empleado_grid->TotalRecs = $empleado->SelectRecordCount();
	} else {
		if ($empleado_grid->Recordset = $empleado_grid->LoadRecordset())
			$empleado_grid->TotalRecs = $empleado_grid->Recordset->RecordCount();
	}
	$empleado_grid->StartRec = 1;
	$empleado_grid->DisplayRecs = $empleado_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$empleado_grid->Recordset = $empleado_grid->LoadRecordset($empleado_grid->StartRec-1, $empleado_grid->DisplayRecs);

	// Set no record found message
	if ($empleado->CurrentAction == "" && $empleado_grid->TotalRecs == 0) {
		if ($empleado_grid->SearchWhere == "0=101")
			$empleado_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$empleado_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$empleado_grid->RenderOtherOptions();
?>
<?php $empleado_grid->ShowPageHeader(); ?>
<?php
$empleado_grid->ShowMessage();
?>
<?php if ($empleado_grid->TotalRecs > 0 || $empleado->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fempleadogrid" class="ewForm form-inline">
<div id="gmp_empleado" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_empleadogrid" class="table ewTable">
<?php echo $empleado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$empleado_grid->RenderListOptions();

// Render list options (header, left)
$empleado_grid->ListOptions->Render("header", "left");
?>
<?php if ($empleado->idempleado->Visible) { // idempleado ?>
	<?php if ($empleado->SortUrl($empleado->idempleado) == "") { ?>
		<th data-name="idempleado"><div id="elh_empleado_idempleado" class="empleado_idempleado"><div class="ewTableHeaderCaption"><?php echo $empleado->idempleado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idempleado"><div><div id="elh_empleado_idempleado" class="empleado_idempleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->idempleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->idempleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->idempleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->nombre->Visible) { // nombre ?>
	<?php if ($empleado->SortUrl($empleado->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_empleado_nombre" class="empleado_nombre"><div class="ewTableHeaderCaption"><?php echo $empleado->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div><div id="elh_empleado_nombre" class="empleado_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->apellido->Visible) { // apellido ?>
	<?php if ($empleado->SortUrl($empleado->apellido) == "") { ?>
		<th data-name="apellido"><div id="elh_empleado_apellido" class="empleado_apellido"><div class="ewTableHeaderCaption"><?php echo $empleado->apellido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="apellido"><div><div id="elh_empleado_apellido" class="empleado_apellido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->apellido->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->apellido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->apellido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->cui->Visible) { // cui ?>
	<?php if ($empleado->SortUrl($empleado->cui) == "") { ?>
		<th data-name="cui"><div id="elh_empleado_cui" class="empleado_cui"><div class="ewTableHeaderCaption"><?php echo $empleado->cui->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cui"><div><div id="elh_empleado_cui" class="empleado_cui">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->cui->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->cui->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->cui->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->telefono->Visible) { // telefono ?>
	<?php if ($empleado->SortUrl($empleado->telefono) == "") { ?>
		<th data-name="telefono"><div id="elh_empleado_telefono" class="empleado_telefono"><div class="ewTableHeaderCaption"><?php echo $empleado->telefono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono"><div><div id="elh_empleado_telefono" class="empleado_telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->telefono->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->direccion->Visible) { // direccion ?>
	<?php if ($empleado->SortUrl($empleado->direccion) == "") { ?>
		<th data-name="direccion"><div id="elh_empleado_direccion" class="empleado_direccion"><div class="ewTableHeaderCaption"><?php echo $empleado->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion"><div><div id="elh_empleado_direccion" class="empleado_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->estado->Visible) { // estado ?>
	<?php if ($empleado->SortUrl($empleado->estado) == "") { ?>
		<th data-name="estado"><div id="elh_empleado_estado" class="empleado_estado"><div class="ewTableHeaderCaption"><?php echo $empleado->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div><div id="elh_empleado_estado" class="empleado_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empleado->idhospital->Visible) { // idhospital ?>
	<?php if ($empleado->SortUrl($empleado->idhospital) == "") { ?>
		<th data-name="idhospital"><div id="elh_empleado_idhospital" class="empleado_idhospital"><div class="ewTableHeaderCaption"><?php echo $empleado->idhospital->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idhospital"><div><div id="elh_empleado_idhospital" class="empleado_idhospital">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empleado->idhospital->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empleado->idhospital->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empleado->idhospital->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$empleado_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$empleado_grid->StartRec = 1;
$empleado_grid->StopRec = $empleado_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($empleado_grid->FormKeyCountName) && ($empleado->CurrentAction == "gridadd" || $empleado->CurrentAction == "gridedit" || $empleado->CurrentAction == "F")) {
		$empleado_grid->KeyCount = $objForm->GetValue($empleado_grid->FormKeyCountName);
		$empleado_grid->StopRec = $empleado_grid->StartRec + $empleado_grid->KeyCount - 1;
	}
}
$empleado_grid->RecCnt = $empleado_grid->StartRec - 1;
if ($empleado_grid->Recordset && !$empleado_grid->Recordset->EOF) {
	$empleado_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $empleado_grid->StartRec > 1)
		$empleado_grid->Recordset->Move($empleado_grid->StartRec - 1);
} elseif (!$empleado->AllowAddDeleteRow && $empleado_grid->StopRec == 0) {
	$empleado_grid->StopRec = $empleado->GridAddRowCount;
}

// Initialize aggregate
$empleado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$empleado->ResetAttrs();
$empleado_grid->RenderRow();
if ($empleado->CurrentAction == "gridadd")
	$empleado_grid->RowIndex = 0;
if ($empleado->CurrentAction == "gridedit")
	$empleado_grid->RowIndex = 0;
while ($empleado_grid->RecCnt < $empleado_grid->StopRec) {
	$empleado_grid->RecCnt++;
	if (intval($empleado_grid->RecCnt) >= intval($empleado_grid->StartRec)) {
		$empleado_grid->RowCnt++;
		if ($empleado->CurrentAction == "gridadd" || $empleado->CurrentAction == "gridedit" || $empleado->CurrentAction == "F") {
			$empleado_grid->RowIndex++;
			$objForm->Index = $empleado_grid->RowIndex;
			if ($objForm->HasValue($empleado_grid->FormActionName))
				$empleado_grid->RowAction = strval($objForm->GetValue($empleado_grid->FormActionName));
			elseif ($empleado->CurrentAction == "gridadd")
				$empleado_grid->RowAction = "insert";
			else
				$empleado_grid->RowAction = "";
		}

		// Set up key count
		$empleado_grid->KeyCount = $empleado_grid->RowIndex;

		// Init row class and style
		$empleado->ResetAttrs();
		$empleado->CssClass = "";
		if ($empleado->CurrentAction == "gridadd") {
			if ($empleado->CurrentMode == "copy") {
				$empleado_grid->LoadRowValues($empleado_grid->Recordset); // Load row values
				$empleado_grid->SetRecordKey($empleado_grid->RowOldKey, $empleado_grid->Recordset); // Set old record key
			} else {
				$empleado_grid->LoadDefaultValues(); // Load default values
				$empleado_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$empleado_grid->LoadRowValues($empleado_grid->Recordset); // Load row values
		}
		$empleado->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($empleado->CurrentAction == "gridadd") // Grid add
			$empleado->RowType = EW_ROWTYPE_ADD; // Render add
		if ($empleado->CurrentAction == "gridadd" && $empleado->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$empleado_grid->RestoreCurrentRowFormValues($empleado_grid->RowIndex); // Restore form values
		if ($empleado->CurrentAction == "gridedit") { // Grid edit
			if ($empleado->EventCancelled) {
				$empleado_grid->RestoreCurrentRowFormValues($empleado_grid->RowIndex); // Restore form values
			}
			if ($empleado_grid->RowAction == "insert")
				$empleado->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$empleado->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($empleado->CurrentAction == "gridedit" && ($empleado->RowType == EW_ROWTYPE_EDIT || $empleado->RowType == EW_ROWTYPE_ADD) && $empleado->EventCancelled) // Update failed
			$empleado_grid->RestoreCurrentRowFormValues($empleado_grid->RowIndex); // Restore form values
		if ($empleado->RowType == EW_ROWTYPE_EDIT) // Edit row
			$empleado_grid->EditRowCnt++;
		if ($empleado->CurrentAction == "F") // Confirm row
			$empleado_grid->RestoreCurrentRowFormValues($empleado_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$empleado->RowAttrs = array_merge($empleado->RowAttrs, array('data-rowindex'=>$empleado_grid->RowCnt, 'id'=>'r' . $empleado_grid->RowCnt . '_empleado', 'data-rowtype'=>$empleado->RowType));

		// Render row
		$empleado_grid->RenderRow();

		// Render list options
		$empleado_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($empleado_grid->RowAction <> "delete" && $empleado_grid->RowAction <> "insertdelete" && !($empleado_grid->RowAction == "insert" && $empleado->CurrentAction == "F" && $empleado_grid->EmptyRow())) {
?>
	<tr<?php echo $empleado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$empleado_grid->ListOptions->Render("body", "left", $empleado_grid->RowCnt);
?>
	<?php if ($empleado->idempleado->Visible) { // idempleado ?>
		<td data-name="idempleado"<?php echo $empleado->idempleado->CellAttributes() ?>>
<?php if ($empleado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idempleado" name="o<?php echo $empleado_grid->RowIndex ?>_idempleado" id="o<?php echo $empleado_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($empleado->idempleado->OldValue) ?>">
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_idempleado" class="form-group empleado_idempleado">
<span<?php echo $empleado->idempleado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->idempleado->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_idempleado" name="x<?php echo $empleado_grid->RowIndex ?>_idempleado" id="x<?php echo $empleado_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($empleado->idempleado->CurrentValue) ?>">
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $empleado->idempleado->ViewAttributes() ?>>
<?php echo $empleado->idempleado->ListViewValue() ?></span>
<input type="hidden" data-field="x_idempleado" name="x<?php echo $empleado_grid->RowIndex ?>_idempleado" id="x<?php echo $empleado_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($empleado->idempleado->FormValue) ?>">
<input type="hidden" data-field="x_idempleado" name="o<?php echo $empleado_grid->RowIndex ?>_idempleado" id="o<?php echo $empleado_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($empleado->idempleado->OldValue) ?>">
<?php } ?>
<a id="<?php echo $empleado_grid->PageObjName . "_row_" . $empleado_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($empleado->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $empleado->nombre->CellAttributes() ?>>
<?php if ($empleado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_nombre" class="form-group empleado_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $empleado_grid->RowIndex ?>_nombre" id="x<?php echo $empleado_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->nombre->PlaceHolder) ?>" value="<?php echo $empleado->nombre->EditValue ?>"<?php echo $empleado->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nombre" name="o<?php echo $empleado_grid->RowIndex ?>_nombre" id="o<?php echo $empleado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($empleado->nombre->OldValue) ?>">
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_nombre" class="form-group empleado_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $empleado_grid->RowIndex ?>_nombre" id="x<?php echo $empleado_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->nombre->PlaceHolder) ?>" value="<?php echo $empleado->nombre->EditValue ?>"<?php echo $empleado->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $empleado->nombre->ViewAttributes() ?>>
<?php echo $empleado->nombre->ListViewValue() ?></span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $empleado_grid->RowIndex ?>_nombre" id="x<?php echo $empleado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($empleado->nombre->FormValue) ?>">
<input type="hidden" data-field="x_nombre" name="o<?php echo $empleado_grid->RowIndex ?>_nombre" id="o<?php echo $empleado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($empleado->nombre->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empleado->apellido->Visible) { // apellido ?>
		<td data-name="apellido"<?php echo $empleado->apellido->CellAttributes() ?>>
<?php if ($empleado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_apellido" class="form-group empleado_apellido">
<input type="text" data-field="x_apellido" name="x<?php echo $empleado_grid->RowIndex ?>_apellido" id="x<?php echo $empleado_grid->RowIndex ?>_apellido" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->apellido->PlaceHolder) ?>" value="<?php echo $empleado->apellido->EditValue ?>"<?php echo $empleado->apellido->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_apellido" name="o<?php echo $empleado_grid->RowIndex ?>_apellido" id="o<?php echo $empleado_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($empleado->apellido->OldValue) ?>">
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_apellido" class="form-group empleado_apellido">
<input type="text" data-field="x_apellido" name="x<?php echo $empleado_grid->RowIndex ?>_apellido" id="x<?php echo $empleado_grid->RowIndex ?>_apellido" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->apellido->PlaceHolder) ?>" value="<?php echo $empleado->apellido->EditValue ?>"<?php echo $empleado->apellido->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $empleado->apellido->ViewAttributes() ?>>
<?php echo $empleado->apellido->ListViewValue() ?></span>
<input type="hidden" data-field="x_apellido" name="x<?php echo $empleado_grid->RowIndex ?>_apellido" id="x<?php echo $empleado_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($empleado->apellido->FormValue) ?>">
<input type="hidden" data-field="x_apellido" name="o<?php echo $empleado_grid->RowIndex ?>_apellido" id="o<?php echo $empleado_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($empleado->apellido->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empleado->cui->Visible) { // cui ?>
		<td data-name="cui"<?php echo $empleado->cui->CellAttributes() ?>>
<?php if ($empleado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_cui" class="form-group empleado_cui">
<input type="text" data-field="x_cui" name="x<?php echo $empleado_grid->RowIndex ?>_cui" id="x<?php echo $empleado_grid->RowIndex ?>_cui" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->cui->PlaceHolder) ?>" value="<?php echo $empleado->cui->EditValue ?>"<?php echo $empleado->cui->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cui" name="o<?php echo $empleado_grid->RowIndex ?>_cui" id="o<?php echo $empleado_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($empleado->cui->OldValue) ?>">
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_cui" class="form-group empleado_cui">
<input type="text" data-field="x_cui" name="x<?php echo $empleado_grid->RowIndex ?>_cui" id="x<?php echo $empleado_grid->RowIndex ?>_cui" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->cui->PlaceHolder) ?>" value="<?php echo $empleado->cui->EditValue ?>"<?php echo $empleado->cui->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $empleado->cui->ViewAttributes() ?>>
<?php echo $empleado->cui->ListViewValue() ?></span>
<input type="hidden" data-field="x_cui" name="x<?php echo $empleado_grid->RowIndex ?>_cui" id="x<?php echo $empleado_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($empleado->cui->FormValue) ?>">
<input type="hidden" data-field="x_cui" name="o<?php echo $empleado_grid->RowIndex ?>_cui" id="o<?php echo $empleado_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($empleado->cui->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empleado->telefono->Visible) { // telefono ?>
		<td data-name="telefono"<?php echo $empleado->telefono->CellAttributes() ?>>
<?php if ($empleado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_telefono" class="form-group empleado_telefono">
<input type="text" data-field="x_telefono" name="x<?php echo $empleado_grid->RowIndex ?>_telefono" id="x<?php echo $empleado_grid->RowIndex ?>_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->telefono->PlaceHolder) ?>" value="<?php echo $empleado->telefono->EditValue ?>"<?php echo $empleado->telefono->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_telefono" name="o<?php echo $empleado_grid->RowIndex ?>_telefono" id="o<?php echo $empleado_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($empleado->telefono->OldValue) ?>">
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_telefono" class="form-group empleado_telefono">
<input type="text" data-field="x_telefono" name="x<?php echo $empleado_grid->RowIndex ?>_telefono" id="x<?php echo $empleado_grid->RowIndex ?>_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->telefono->PlaceHolder) ?>" value="<?php echo $empleado->telefono->EditValue ?>"<?php echo $empleado->telefono->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $empleado->telefono->ViewAttributes() ?>>
<?php echo $empleado->telefono->ListViewValue() ?></span>
<input type="hidden" data-field="x_telefono" name="x<?php echo $empleado_grid->RowIndex ?>_telefono" id="x<?php echo $empleado_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($empleado->telefono->FormValue) ?>">
<input type="hidden" data-field="x_telefono" name="o<?php echo $empleado_grid->RowIndex ?>_telefono" id="o<?php echo $empleado_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($empleado->telefono->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empleado->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $empleado->direccion->CellAttributes() ?>>
<?php if ($empleado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_direccion" class="form-group empleado_direccion">
<input type="text" data-field="x_direccion" name="x<?php echo $empleado_grid->RowIndex ?>_direccion" id="x<?php echo $empleado_grid->RowIndex ?>_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->direccion->PlaceHolder) ?>" value="<?php echo $empleado->direccion->EditValue ?>"<?php echo $empleado->direccion->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_direccion" name="o<?php echo $empleado_grid->RowIndex ?>_direccion" id="o<?php echo $empleado_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($empleado->direccion->OldValue) ?>">
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_direccion" class="form-group empleado_direccion">
<input type="text" data-field="x_direccion" name="x<?php echo $empleado_grid->RowIndex ?>_direccion" id="x<?php echo $empleado_grid->RowIndex ?>_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->direccion->PlaceHolder) ?>" value="<?php echo $empleado->direccion->EditValue ?>"<?php echo $empleado->direccion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $empleado->direccion->ViewAttributes() ?>>
<?php echo $empleado->direccion->ListViewValue() ?></span>
<input type="hidden" data-field="x_direccion" name="x<?php echo $empleado_grid->RowIndex ?>_direccion" id="x<?php echo $empleado_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($empleado->direccion->FormValue) ?>">
<input type="hidden" data-field="x_direccion" name="o<?php echo $empleado_grid->RowIndex ?>_direccion" id="o<?php echo $empleado_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($empleado->direccion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empleado->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $empleado->estado->CellAttributes() ?>>
<?php if ($empleado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_estado" class="form-group empleado_estado">
<div id="tp_x<?php echo $empleado_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $empleado_grid->RowIndex ?>_estado" id="x<?php echo $empleado_grid->RowIndex ?>_estado" value="{value}"<?php echo $empleado->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $empleado_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $empleado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $empleado_grid->RowIndex ?>_estado" id="x<?php echo $empleado_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $empleado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $empleado->estado->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_estado" name="o<?php echo $empleado_grid->RowIndex ?>_estado" id="o<?php echo $empleado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($empleado->estado->OldValue) ?>">
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_estado" class="form-group empleado_estado">
<div id="tp_x<?php echo $empleado_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $empleado_grid->RowIndex ?>_estado" id="x<?php echo $empleado_grid->RowIndex ?>_estado" value="{value}"<?php echo $empleado->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $empleado_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $empleado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $empleado_grid->RowIndex ?>_estado" id="x<?php echo $empleado_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $empleado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $empleado->estado->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $empleado->estado->ViewAttributes() ?>>
<?php echo $empleado->estado->ListViewValue() ?></span>
<input type="hidden" data-field="x_estado" name="x<?php echo $empleado_grid->RowIndex ?>_estado" id="x<?php echo $empleado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($empleado->estado->FormValue) ?>">
<input type="hidden" data-field="x_estado" name="o<?php echo $empleado_grid->RowIndex ?>_estado" id="o<?php echo $empleado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($empleado->estado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($empleado->idhospital->Visible) { // idhospital ?>
		<td data-name="idhospital"<?php echo $empleado->idhospital->CellAttributes() ?>>
<?php if ($empleado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($empleado->idhospital->getSessionValue() <> "") { ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_idhospital" class="form-group empleado_idhospital">
<span<?php echo $empleado->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $empleado_grid->RowIndex ?>_idhospital" name="x<?php echo $empleado_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($empleado->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_idhospital" class="form-group empleado_idhospital">
<select data-field="x_idhospital" id="x<?php echo $empleado_grid->RowIndex ?>_idhospital" name="x<?php echo $empleado_grid->RowIndex ?>_idhospital"<?php echo $empleado->idhospital->EditAttributes() ?>>
<?php
if (is_array($empleado->idhospital->EditValue)) {
	$arwrk = $empleado->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleado->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $empleado->idhospital->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $empleado->Lookup_Selecting($empleado->idhospital, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $empleado_grid->RowIndex ?>_idhospital" id="s_x<?php echo $empleado_grid->RowIndex ?>_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idhospital" name="o<?php echo $empleado_grid->RowIndex ?>_idhospital" id="o<?php echo $empleado_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($empleado->idhospital->OldValue) ?>">
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($empleado->idhospital->getSessionValue() <> "") { ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_idhospital" class="form-group empleado_idhospital">
<span<?php echo $empleado->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $empleado_grid->RowIndex ?>_idhospital" name="x<?php echo $empleado_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($empleado->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $empleado_grid->RowCnt ?>_empleado_idhospital" class="form-group empleado_idhospital">
<select data-field="x_idhospital" id="x<?php echo $empleado_grid->RowIndex ?>_idhospital" name="x<?php echo $empleado_grid->RowIndex ?>_idhospital"<?php echo $empleado->idhospital->EditAttributes() ?>>
<?php
if (is_array($empleado->idhospital->EditValue)) {
	$arwrk = $empleado->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleado->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $empleado->idhospital->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $empleado->Lookup_Selecting($empleado->idhospital, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $empleado_grid->RowIndex ?>_idhospital" id="s_x<?php echo $empleado_grid->RowIndex ?>_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($empleado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $empleado->idhospital->ViewAttributes() ?>>
<?php echo $empleado->idhospital->ListViewValue() ?></span>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $empleado_grid->RowIndex ?>_idhospital" id="x<?php echo $empleado_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($empleado->idhospital->FormValue) ?>">
<input type="hidden" data-field="x_idhospital" name="o<?php echo $empleado_grid->RowIndex ?>_idhospital" id="o<?php echo $empleado_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($empleado->idhospital->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$empleado_grid->ListOptions->Render("body", "right", $empleado_grid->RowCnt);
?>
	</tr>
<?php if ($empleado->RowType == EW_ROWTYPE_ADD || $empleado->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fempleadogrid.UpdateOpts(<?php echo $empleado_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($empleado->CurrentAction <> "gridadd" || $empleado->CurrentMode == "copy")
		if (!$empleado_grid->Recordset->EOF) $empleado_grid->Recordset->MoveNext();
}
?>
<?php
	if ($empleado->CurrentMode == "add" || $empleado->CurrentMode == "copy" || $empleado->CurrentMode == "edit") {
		$empleado_grid->RowIndex = '$rowindex$';
		$empleado_grid->LoadDefaultValues();

		// Set row properties
		$empleado->ResetAttrs();
		$empleado->RowAttrs = array_merge($empleado->RowAttrs, array('data-rowindex'=>$empleado_grid->RowIndex, 'id'=>'r0_empleado', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($empleado->RowAttrs["class"], "ewTemplate");
		$empleado->RowType = EW_ROWTYPE_ADD;

		// Render row
		$empleado_grid->RenderRow();

		// Render list options
		$empleado_grid->RenderListOptions();
		$empleado_grid->StartRowCnt = 0;
?>
	<tr<?php echo $empleado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$empleado_grid->ListOptions->Render("body", "left", $empleado_grid->RowIndex);
?>
	<?php if ($empleado->idempleado->Visible) { // idempleado ?>
		<td>
<?php if ($empleado->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_empleado_idempleado" class="form-group empleado_idempleado">
<span<?php echo $empleado->idempleado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->idempleado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idempleado" name="x<?php echo $empleado_grid->RowIndex ?>_idempleado" id="x<?php echo $empleado_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($empleado->idempleado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idempleado" name="o<?php echo $empleado_grid->RowIndex ?>_idempleado" id="o<?php echo $empleado_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($empleado->idempleado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empleado->nombre->Visible) { // nombre ?>
		<td>
<?php if ($empleado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empleado_nombre" class="form-group empleado_nombre">
<input type="text" data-field="x_nombre" name="x<?php echo $empleado_grid->RowIndex ?>_nombre" id="x<?php echo $empleado_grid->RowIndex ?>_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->nombre->PlaceHolder) ?>" value="<?php echo $empleado->nombre->EditValue ?>"<?php echo $empleado->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empleado_nombre" class="form-group empleado_nombre">
<span<?php echo $empleado->nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_nombre" name="x<?php echo $empleado_grid->RowIndex ?>_nombre" id="x<?php echo $empleado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($empleado->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nombre" name="o<?php echo $empleado_grid->RowIndex ?>_nombre" id="o<?php echo $empleado_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($empleado->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empleado->apellido->Visible) { // apellido ?>
		<td>
<?php if ($empleado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empleado_apellido" class="form-group empleado_apellido">
<input type="text" data-field="x_apellido" name="x<?php echo $empleado_grid->RowIndex ?>_apellido" id="x<?php echo $empleado_grid->RowIndex ?>_apellido" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->apellido->PlaceHolder) ?>" value="<?php echo $empleado->apellido->EditValue ?>"<?php echo $empleado->apellido->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empleado_apellido" class="form-group empleado_apellido">
<span<?php echo $empleado->apellido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->apellido->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_apellido" name="x<?php echo $empleado_grid->RowIndex ?>_apellido" id="x<?php echo $empleado_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($empleado->apellido->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_apellido" name="o<?php echo $empleado_grid->RowIndex ?>_apellido" id="o<?php echo $empleado_grid->RowIndex ?>_apellido" value="<?php echo ew_HtmlEncode($empleado->apellido->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empleado->cui->Visible) { // cui ?>
		<td>
<?php if ($empleado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empleado_cui" class="form-group empleado_cui">
<input type="text" data-field="x_cui" name="x<?php echo $empleado_grid->RowIndex ?>_cui" id="x<?php echo $empleado_grid->RowIndex ?>_cui" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->cui->PlaceHolder) ?>" value="<?php echo $empleado->cui->EditValue ?>"<?php echo $empleado->cui->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empleado_cui" class="form-group empleado_cui">
<span<?php echo $empleado->cui->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->cui->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_cui" name="x<?php echo $empleado_grid->RowIndex ?>_cui" id="x<?php echo $empleado_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($empleado->cui->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_cui" name="o<?php echo $empleado_grid->RowIndex ?>_cui" id="o<?php echo $empleado_grid->RowIndex ?>_cui" value="<?php echo ew_HtmlEncode($empleado->cui->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empleado->telefono->Visible) { // telefono ?>
		<td>
<?php if ($empleado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empleado_telefono" class="form-group empleado_telefono">
<input type="text" data-field="x_telefono" name="x<?php echo $empleado_grid->RowIndex ?>_telefono" id="x<?php echo $empleado_grid->RowIndex ?>_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->telefono->PlaceHolder) ?>" value="<?php echo $empleado->telefono->EditValue ?>"<?php echo $empleado->telefono->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empleado_telefono" class="form-group empleado_telefono">
<span<?php echo $empleado->telefono->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->telefono->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_telefono" name="x<?php echo $empleado_grid->RowIndex ?>_telefono" id="x<?php echo $empleado_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($empleado->telefono->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_telefono" name="o<?php echo $empleado_grid->RowIndex ?>_telefono" id="o<?php echo $empleado_grid->RowIndex ?>_telefono" value="<?php echo ew_HtmlEncode($empleado->telefono->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empleado->direccion->Visible) { // direccion ?>
		<td>
<?php if ($empleado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empleado_direccion" class="form-group empleado_direccion">
<input type="text" data-field="x_direccion" name="x<?php echo $empleado_grid->RowIndex ?>_direccion" id="x<?php echo $empleado_grid->RowIndex ?>_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($empleado->direccion->PlaceHolder) ?>" value="<?php echo $empleado->direccion->EditValue ?>"<?php echo $empleado->direccion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_empleado_direccion" class="form-group empleado_direccion">
<span<?php echo $empleado->direccion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->direccion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_direccion" name="x<?php echo $empleado_grid->RowIndex ?>_direccion" id="x<?php echo $empleado_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($empleado->direccion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_direccion" name="o<?php echo $empleado_grid->RowIndex ?>_direccion" id="o<?php echo $empleado_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($empleado->direccion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empleado->estado->Visible) { // estado ?>
		<td>
<?php if ($empleado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_empleado_estado" class="form-group empleado_estado">
<div id="tp_x<?php echo $empleado_grid->RowIndex ?>_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $empleado_grid->RowIndex ?>_estado" id="x<?php echo $empleado_grid->RowIndex ?>_estado" value="{value}"<?php echo $empleado->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $empleado_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $empleado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x<?php echo $empleado_grid->RowIndex ?>_estado" id="x<?php echo $empleado_grid->RowIndex ?>_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $empleado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $empleado->estado->OldValue = "";
?>
</div>
</span>
<?php } else { ?>
<span id="el$rowindex$_empleado_estado" class="form-group empleado_estado">
<span<?php echo $empleado->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_estado" name="x<?php echo $empleado_grid->RowIndex ?>_estado" id="x<?php echo $empleado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($empleado->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_estado" name="o<?php echo $empleado_grid->RowIndex ?>_estado" id="o<?php echo $empleado_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($empleado->estado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($empleado->idhospital->Visible) { // idhospital ?>
		<td>
<?php if ($empleado->CurrentAction <> "F") { ?>
<?php if ($empleado->idhospital->getSessionValue() <> "") { ?>
<span id="el$rowindex$_empleado_idhospital" class="form-group empleado_idhospital">
<span<?php echo $empleado->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $empleado_grid->RowIndex ?>_idhospital" name="x<?php echo $empleado_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($empleado->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_empleado_idhospital" class="form-group empleado_idhospital">
<select data-field="x_idhospital" id="x<?php echo $empleado_grid->RowIndex ?>_idhospital" name="x<?php echo $empleado_grid->RowIndex ?>_idhospital"<?php echo $empleado->idhospital->EditAttributes() ?>>
<?php
if (is_array($empleado->idhospital->EditValue)) {
	$arwrk = $empleado->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleado->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $empleado->idhospital->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $empleado->Lookup_Selecting($empleado->idhospital, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $empleado_grid->RowIndex ?>_idhospital" id="s_x<?php echo $empleado_grid->RowIndex ?>_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_empleado_idhospital" class="form-group empleado_idhospital">
<span<?php echo $empleado->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $empleado->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idhospital" name="x<?php echo $empleado_grid->RowIndex ?>_idhospital" id="x<?php echo $empleado_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($empleado->idhospital->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idhospital" name="o<?php echo $empleado_grid->RowIndex ?>_idhospital" id="o<?php echo $empleado_grid->RowIndex ?>_idhospital" value="<?php echo ew_HtmlEncode($empleado->idhospital->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$empleado_grid->ListOptions->Render("body", "right", $empleado_grid->RowCnt);
?>
<script type="text/javascript">
fempleadogrid.UpdateOpts(<?php echo $empleado_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($empleado->CurrentMode == "add" || $empleado->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $empleado_grid->FormKeyCountName ?>" id="<?php echo $empleado_grid->FormKeyCountName ?>" value="<?php echo $empleado_grid->KeyCount ?>">
<?php echo $empleado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($empleado->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $empleado_grid->FormKeyCountName ?>" id="<?php echo $empleado_grid->FormKeyCountName ?>" value="<?php echo $empleado_grid->KeyCount ?>">
<?php echo $empleado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($empleado->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fempleadogrid">
</div>
<?php

// Close recordset
if ($empleado_grid->Recordset)
	$empleado_grid->Recordset->Close();
?>
<?php if ($empleado_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($empleado_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($empleado_grid->TotalRecs == 0 && $empleado->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($empleado_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($empleado->Export == "") { ?>
<script type="text/javascript">
fempleadogrid.Init();
</script>
<?php } ?>
<?php
$empleado_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$empleado_grid->Page_Terminate();
?>

<?php

// Create page object
if (!isset($receta_grid)) $receta_grid = new creceta_grid();

// Page init
$receta_grid->Page_Init();

// Page main
$receta_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$receta_grid->Page_Render();
?>
<?php if ($receta->Export == "") { ?>
<script type="text/javascript">

// Page object
var receta_grid = new ew_Page("receta_grid");
receta_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = receta_grid.PageID; // For backward compatibility

// Form object
var frecetagrid = new ew_Form("frecetagrid");
frecetagrid.FormKeyCountName = '<?php echo $receta_grid->FormKeyCountName ?>';

// Validate form
frecetagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idempleado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->idempleado->FldCaption(), $receta->idempleado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idmedicina");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->idmedicina->FldCaption(), $receta->idmedicina->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($receta->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->cantidad->FldCaption(), $receta->cantidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($receta->cantidad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_precio_unitario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->precio_unitario->FldCaption(), $receta->precio_unitario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_precio_unitario");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($receta->precio_unitario->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idturno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->idturno->FldCaption(), $receta->idturno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idcuenta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->idcuenta->FldCaption(), $receta->idcuenta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idcuenta");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($receta->idcuenta->FldErrMsg()) ?>");

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
frecetagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "idempleado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idmedicina", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cantidad", false)) return false;
	if (ew_ValueChanged(fobj, infix, "precio_unitario", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idturno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idcuenta", false)) return false;
	return true;
}

// Form_CustomValidate event
frecetagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frecetagrid.ValidateRequired = true;
<?php } else { ?>
frecetagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frecetagrid.Lists["x_idempleado"] = {"LinkField":"x_idempleado","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frecetagrid.Lists["x_idmedicina"] = {"LinkField":"x_idmedicina","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frecetagrid.Lists["x_idturno"] = {"LinkField":"x_idturno","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($receta->CurrentAction == "gridadd") {
	if ($receta->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$receta_grid->TotalRecs = $receta->SelectRecordCount();
			$receta_grid->Recordset = $receta_grid->LoadRecordset($receta_grid->StartRec-1, $receta_grid->DisplayRecs);
		} else {
			if ($receta_grid->Recordset = $receta_grid->LoadRecordset())
				$receta_grid->TotalRecs = $receta_grid->Recordset->RecordCount();
		}
		$receta_grid->StartRec = 1;
		$receta_grid->DisplayRecs = $receta_grid->TotalRecs;
	} else {
		$receta->CurrentFilter = "0=1";
		$receta_grid->StartRec = 1;
		$receta_grid->DisplayRecs = $receta->GridAddRowCount;
	}
	$receta_grid->TotalRecs = $receta_grid->DisplayRecs;
	$receta_grid->StopRec = $receta_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$receta_grid->TotalRecs = $receta->SelectRecordCount();
	} else {
		if ($receta_grid->Recordset = $receta_grid->LoadRecordset())
			$receta_grid->TotalRecs = $receta_grid->Recordset->RecordCount();
	}
	$receta_grid->StartRec = 1;
	$receta_grid->DisplayRecs = $receta_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$receta_grid->Recordset = $receta_grid->LoadRecordset($receta_grid->StartRec-1, $receta_grid->DisplayRecs);

	// Set no record found message
	if ($receta->CurrentAction == "" && $receta_grid->TotalRecs == 0) {
		if ($receta_grid->SearchWhere == "0=101")
			$receta_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$receta_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$receta_grid->RenderOtherOptions();
?>
<?php $receta_grid->ShowPageHeader(); ?>
<?php
$receta_grid->ShowMessage();
?>
<?php if ($receta_grid->TotalRecs > 0 || $receta->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="frecetagrid" class="ewForm form-inline">
<div id="gmp_receta" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_recetagrid" class="table ewTable">
<?php echo $receta->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$receta_grid->RenderListOptions();

// Render list options (header, left)
$receta_grid->ListOptions->Render("header", "left");
?>
<?php if ($receta->idreceta->Visible) { // idreceta ?>
	<?php if ($receta->SortUrl($receta->idreceta) == "") { ?>
		<th data-name="idreceta"><div id="elh_receta_idreceta" class="receta_idreceta"><div class="ewTableHeaderCaption"><?php echo $receta->idreceta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idreceta"><div><div id="elh_receta_idreceta" class="receta_idreceta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idreceta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idreceta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idreceta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->idempleado->Visible) { // idempleado ?>
	<?php if ($receta->SortUrl($receta->idempleado) == "") { ?>
		<th data-name="idempleado"><div id="elh_receta_idempleado" class="receta_idempleado"><div class="ewTableHeaderCaption"><?php echo $receta->idempleado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idempleado"><div><div id="elh_receta_idempleado" class="receta_idempleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idempleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idempleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idempleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->idmedicina->Visible) { // idmedicina ?>
	<?php if ($receta->SortUrl($receta->idmedicina) == "") { ?>
		<th data-name="idmedicina"><div id="elh_receta_idmedicina" class="receta_idmedicina"><div class="ewTableHeaderCaption"><?php echo $receta->idmedicina->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idmedicina"><div><div id="elh_receta_idmedicina" class="receta_idmedicina">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idmedicina->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idmedicina->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idmedicina->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->fecha->Visible) { // fecha ?>
	<?php if ($receta->SortUrl($receta->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_receta_fecha" class="receta_fecha"><div class="ewTableHeaderCaption"><?php echo $receta->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div><div id="elh_receta_fecha" class="receta_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->cantidad->Visible) { // cantidad ?>
	<?php if ($receta->SortUrl($receta->cantidad) == "") { ?>
		<th data-name="cantidad"><div id="elh_receta_cantidad" class="receta_cantidad"><div class="ewTableHeaderCaption"><?php echo $receta->cantidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cantidad"><div><div id="elh_receta_cantidad" class="receta_cantidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->cantidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->cantidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->cantidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->precio_unitario->Visible) { // precio_unitario ?>
	<?php if ($receta->SortUrl($receta->precio_unitario) == "") { ?>
		<th data-name="precio_unitario"><div id="elh_receta_precio_unitario" class="receta_precio_unitario"><div class="ewTableHeaderCaption"><?php echo $receta->precio_unitario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="precio_unitario"><div><div id="elh_receta_precio_unitario" class="receta_precio_unitario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->precio_unitario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->precio_unitario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->precio_unitario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->idturno->Visible) { // idturno ?>
	<?php if ($receta->SortUrl($receta->idturno) == "") { ?>
		<th data-name="idturno"><div id="elh_receta_idturno" class="receta_idturno"><div class="ewTableHeaderCaption"><?php echo $receta->idturno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idturno"><div><div id="elh_receta_idturno" class="receta_idturno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idturno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idturno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idturno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->idcuenta->Visible) { // idcuenta ?>
	<?php if ($receta->SortUrl($receta->idcuenta) == "") { ?>
		<th data-name="idcuenta"><div id="elh_receta_idcuenta" class="receta_idcuenta"><div class="ewTableHeaderCaption"><?php echo $receta->idcuenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idcuenta"><div><div id="elh_receta_idcuenta" class="receta_idcuenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idcuenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idcuenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idcuenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$receta_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$receta_grid->StartRec = 1;
$receta_grid->StopRec = $receta_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($receta_grid->FormKeyCountName) && ($receta->CurrentAction == "gridadd" || $receta->CurrentAction == "gridedit" || $receta->CurrentAction == "F")) {
		$receta_grid->KeyCount = $objForm->GetValue($receta_grid->FormKeyCountName);
		$receta_grid->StopRec = $receta_grid->StartRec + $receta_grid->KeyCount - 1;
	}
}
$receta_grid->RecCnt = $receta_grid->StartRec - 1;
if ($receta_grid->Recordset && !$receta_grid->Recordset->EOF) {
	$receta_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $receta_grid->StartRec > 1)
		$receta_grid->Recordset->Move($receta_grid->StartRec - 1);
} elseif (!$receta->AllowAddDeleteRow && $receta_grid->StopRec == 0) {
	$receta_grid->StopRec = $receta->GridAddRowCount;
}

// Initialize aggregate
$receta->RowType = EW_ROWTYPE_AGGREGATEINIT;
$receta->ResetAttrs();
$receta_grid->RenderRow();
if ($receta->CurrentAction == "gridadd")
	$receta_grid->RowIndex = 0;
if ($receta->CurrentAction == "gridedit")
	$receta_grid->RowIndex = 0;
while ($receta_grid->RecCnt < $receta_grid->StopRec) {
	$receta_grid->RecCnt++;
	if (intval($receta_grid->RecCnt) >= intval($receta_grid->StartRec)) {
		$receta_grid->RowCnt++;
		if ($receta->CurrentAction == "gridadd" || $receta->CurrentAction == "gridedit" || $receta->CurrentAction == "F") {
			$receta_grid->RowIndex++;
			$objForm->Index = $receta_grid->RowIndex;
			if ($objForm->HasValue($receta_grid->FormActionName))
				$receta_grid->RowAction = strval($objForm->GetValue($receta_grid->FormActionName));
			elseif ($receta->CurrentAction == "gridadd")
				$receta_grid->RowAction = "insert";
			else
				$receta_grid->RowAction = "";
		}

		// Set up key count
		$receta_grid->KeyCount = $receta_grid->RowIndex;

		// Init row class and style
		$receta->ResetAttrs();
		$receta->CssClass = "";
		if ($receta->CurrentAction == "gridadd") {
			if ($receta->CurrentMode == "copy") {
				$receta_grid->LoadRowValues($receta_grid->Recordset); // Load row values
				$receta_grid->SetRecordKey($receta_grid->RowOldKey, $receta_grid->Recordset); // Set old record key
			} else {
				$receta_grid->LoadDefaultValues(); // Load default values
				$receta_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$receta_grid->LoadRowValues($receta_grid->Recordset); // Load row values
		}
		$receta->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($receta->CurrentAction == "gridadd") // Grid add
			$receta->RowType = EW_ROWTYPE_ADD; // Render add
		if ($receta->CurrentAction == "gridadd" && $receta->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$receta_grid->RestoreCurrentRowFormValues($receta_grid->RowIndex); // Restore form values
		if ($receta->CurrentAction == "gridedit") { // Grid edit
			if ($receta->EventCancelled) {
				$receta_grid->RestoreCurrentRowFormValues($receta_grid->RowIndex); // Restore form values
			}
			if ($receta_grid->RowAction == "insert")
				$receta->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$receta->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($receta->CurrentAction == "gridedit" && ($receta->RowType == EW_ROWTYPE_EDIT || $receta->RowType == EW_ROWTYPE_ADD) && $receta->EventCancelled) // Update failed
			$receta_grid->RestoreCurrentRowFormValues($receta_grid->RowIndex); // Restore form values
		if ($receta->RowType == EW_ROWTYPE_EDIT) // Edit row
			$receta_grid->EditRowCnt++;
		if ($receta->CurrentAction == "F") // Confirm row
			$receta_grid->RestoreCurrentRowFormValues($receta_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$receta->RowAttrs = array_merge($receta->RowAttrs, array('data-rowindex'=>$receta_grid->RowCnt, 'id'=>'r' . $receta_grid->RowCnt . '_receta', 'data-rowtype'=>$receta->RowType));

		// Render row
		$receta_grid->RenderRow();

		// Render list options
		$receta_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($receta_grid->RowAction <> "delete" && $receta_grid->RowAction <> "insertdelete" && !($receta_grid->RowAction == "insert" && $receta->CurrentAction == "F" && $receta_grid->EmptyRow())) {
?>
	<tr<?php echo $receta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$receta_grid->ListOptions->Render("body", "left", $receta_grid->RowCnt);
?>
	<?php if ($receta->idreceta->Visible) { // idreceta ?>
		<td data-name="idreceta"<?php echo $receta->idreceta->CellAttributes() ?>>
<?php if ($receta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_idreceta" name="o<?php echo $receta_grid->RowIndex ?>_idreceta" id="o<?php echo $receta_grid->RowIndex ?>_idreceta" value="<?php echo ew_HtmlEncode($receta->idreceta->OldValue) ?>">
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idreceta" class="form-group receta_idreceta">
<span<?php echo $receta->idreceta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idreceta->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_idreceta" name="x<?php echo $receta_grid->RowIndex ?>_idreceta" id="x<?php echo $receta_grid->RowIndex ?>_idreceta" value="<?php echo ew_HtmlEncode($receta->idreceta->CurrentValue) ?>">
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $receta->idreceta->ViewAttributes() ?>>
<?php echo $receta->idreceta->ListViewValue() ?></span>
<input type="hidden" data-field="x_idreceta" name="x<?php echo $receta_grid->RowIndex ?>_idreceta" id="x<?php echo $receta_grid->RowIndex ?>_idreceta" value="<?php echo ew_HtmlEncode($receta->idreceta->FormValue) ?>">
<input type="hidden" data-field="x_idreceta" name="o<?php echo $receta_grid->RowIndex ?>_idreceta" id="o<?php echo $receta_grid->RowIndex ?>_idreceta" value="<?php echo ew_HtmlEncode($receta->idreceta->OldValue) ?>">
<?php } ?>
<a id="<?php echo $receta_grid->PageObjName . "_row_" . $receta_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($receta->idempleado->Visible) { // idempleado ?>
		<td data-name="idempleado"<?php echo $receta->idempleado->CellAttributes() ?>>
<?php if ($receta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($receta->idempleado->getSessionValue() <> "") { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idempleado" class="form-group receta_idempleado">
<span<?php echo $receta->idempleado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idempleado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $receta_grid->RowIndex ?>_idempleado" name="x<?php echo $receta_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($receta->idempleado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idempleado" class="form-group receta_idempleado">
<select data-field="x_idempleado" id="x<?php echo $receta_grid->RowIndex ?>_idempleado" name="x<?php echo $receta_grid->RowIndex ?>_idempleado"<?php echo $receta->idempleado->EditAttributes() ?>>
<?php
if (is_array($receta->idempleado->EditValue)) {
	$arwrk = $receta->idempleado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idempleado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$receta->idempleado) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $receta->idempleado->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idempleado`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $receta->Lookup_Selecting($receta->idempleado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $receta_grid->RowIndex ?>_idempleado" id="s_x<?php echo $receta_grid->RowIndex ?>_idempleado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idempleado` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idempleado" name="o<?php echo $receta_grid->RowIndex ?>_idempleado" id="o<?php echo $receta_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($receta->idempleado->OldValue) ?>">
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($receta->idempleado->getSessionValue() <> "") { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idempleado" class="form-group receta_idempleado">
<span<?php echo $receta->idempleado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idempleado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $receta_grid->RowIndex ?>_idempleado" name="x<?php echo $receta_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($receta->idempleado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idempleado" class="form-group receta_idempleado">
<select data-field="x_idempleado" id="x<?php echo $receta_grid->RowIndex ?>_idempleado" name="x<?php echo $receta_grid->RowIndex ?>_idempleado"<?php echo $receta->idempleado->EditAttributes() ?>>
<?php
if (is_array($receta->idempleado->EditValue)) {
	$arwrk = $receta->idempleado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idempleado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$receta->idempleado) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $receta->idempleado->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idempleado`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $receta->Lookup_Selecting($receta->idempleado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $receta_grid->RowIndex ?>_idempleado" id="s_x<?php echo $receta_grid->RowIndex ?>_idempleado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idempleado` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $receta->idempleado->ViewAttributes() ?>>
<?php echo $receta->idempleado->ListViewValue() ?></span>
<input type="hidden" data-field="x_idempleado" name="x<?php echo $receta_grid->RowIndex ?>_idempleado" id="x<?php echo $receta_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($receta->idempleado->FormValue) ?>">
<input type="hidden" data-field="x_idempleado" name="o<?php echo $receta_grid->RowIndex ?>_idempleado" id="o<?php echo $receta_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($receta->idempleado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($receta->idmedicina->Visible) { // idmedicina ?>
		<td data-name="idmedicina"<?php echo $receta->idmedicina->CellAttributes() ?>>
<?php if ($receta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($receta->idmedicina->getSessionValue() <> "") { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idmedicina" class="form-group receta_idmedicina">
<span<?php echo $receta->idmedicina->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idmedicina->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $receta_grid->RowIndex ?>_idmedicina" name="x<?php echo $receta_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($receta->idmedicina->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idmedicina" class="form-group receta_idmedicina">
<select data-field="x_idmedicina" id="x<?php echo $receta_grid->RowIndex ?>_idmedicina" name="x<?php echo $receta_grid->RowIndex ?>_idmedicina"<?php echo $receta->idmedicina->EditAttributes() ?>>
<?php
if (is_array($receta->idmedicina->EditValue)) {
	$arwrk = $receta->idmedicina->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idmedicina->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $receta->idmedicina->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idmedicina`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `medicina`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $receta->Lookup_Selecting($receta->idmedicina, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $receta_grid->RowIndex ?>_idmedicina" id="s_x<?php echo $receta_grid->RowIndex ?>_idmedicina" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idmedicina` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idmedicina" name="o<?php echo $receta_grid->RowIndex ?>_idmedicina" id="o<?php echo $receta_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($receta->idmedicina->OldValue) ?>">
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($receta->idmedicina->getSessionValue() <> "") { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idmedicina" class="form-group receta_idmedicina">
<span<?php echo $receta->idmedicina->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idmedicina->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $receta_grid->RowIndex ?>_idmedicina" name="x<?php echo $receta_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($receta->idmedicina->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idmedicina" class="form-group receta_idmedicina">
<select data-field="x_idmedicina" id="x<?php echo $receta_grid->RowIndex ?>_idmedicina" name="x<?php echo $receta_grid->RowIndex ?>_idmedicina"<?php echo $receta->idmedicina->EditAttributes() ?>>
<?php
if (is_array($receta->idmedicina->EditValue)) {
	$arwrk = $receta->idmedicina->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idmedicina->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $receta->idmedicina->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idmedicina`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `medicina`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $receta->Lookup_Selecting($receta->idmedicina, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $receta_grid->RowIndex ?>_idmedicina" id="s_x<?php echo $receta_grid->RowIndex ?>_idmedicina" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idmedicina` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $receta->idmedicina->ViewAttributes() ?>>
<?php echo $receta->idmedicina->ListViewValue() ?></span>
<input type="hidden" data-field="x_idmedicina" name="x<?php echo $receta_grid->RowIndex ?>_idmedicina" id="x<?php echo $receta_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($receta->idmedicina->FormValue) ?>">
<input type="hidden" data-field="x_idmedicina" name="o<?php echo $receta_grid->RowIndex ?>_idmedicina" id="o<?php echo $receta_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($receta->idmedicina->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($receta->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $receta->fecha->CellAttributes() ?>>
<?php if ($receta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_fecha" class="form-group receta_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $receta_grid->RowIndex ?>_fecha" id="x<?php echo $receta_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($receta->fecha->PlaceHolder) ?>" value="<?php echo $receta->fecha->EditValue ?>"<?php echo $receta->fecha->EditAttributes() ?>>
<?php if (!$receta->fecha->ReadOnly && !$receta->fecha->Disabled && @$receta->fecha->EditAttrs["readonly"] == "" && @$receta->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("frecetagrid", "x<?php echo $receta_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_fecha" name="o<?php echo $receta_grid->RowIndex ?>_fecha" id="o<?php echo $receta_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($receta->fecha->OldValue) ?>">
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_fecha" class="form-group receta_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $receta_grid->RowIndex ?>_fecha" id="x<?php echo $receta_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($receta->fecha->PlaceHolder) ?>" value="<?php echo $receta->fecha->EditValue ?>"<?php echo $receta->fecha->EditAttributes() ?>>
<?php if (!$receta->fecha->ReadOnly && !$receta->fecha->Disabled && @$receta->fecha->EditAttrs["readonly"] == "" && @$receta->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("frecetagrid", "x<?php echo $receta_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $receta->fecha->ViewAttributes() ?>>
<?php echo $receta->fecha->ListViewValue() ?></span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $receta_grid->RowIndex ?>_fecha" id="x<?php echo $receta_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($receta->fecha->FormValue) ?>">
<input type="hidden" data-field="x_fecha" name="o<?php echo $receta_grid->RowIndex ?>_fecha" id="o<?php echo $receta_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($receta->fecha->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($receta->cantidad->Visible) { // cantidad ?>
		<td data-name="cantidad"<?php echo $receta->cantidad->CellAttributes() ?>>
<?php if ($receta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_cantidad" class="form-group receta_cantidad">
<input type="text" data-field="x_cantidad" name="x<?php echo $receta_grid->RowIndex ?>_cantidad" id="x<?php echo $receta_grid->RowIndex ?>_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($receta->cantidad->PlaceHolder) ?>" value="<?php echo $receta->cantidad->EditValue ?>"<?php echo $receta->cantidad->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_cantidad" name="o<?php echo $receta_grid->RowIndex ?>_cantidad" id="o<?php echo $receta_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($receta->cantidad->OldValue) ?>">
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_cantidad" class="form-group receta_cantidad">
<input type="text" data-field="x_cantidad" name="x<?php echo $receta_grid->RowIndex ?>_cantidad" id="x<?php echo $receta_grid->RowIndex ?>_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($receta->cantidad->PlaceHolder) ?>" value="<?php echo $receta->cantidad->EditValue ?>"<?php echo $receta->cantidad->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $receta->cantidad->ViewAttributes() ?>>
<?php echo $receta->cantidad->ListViewValue() ?></span>
<input type="hidden" data-field="x_cantidad" name="x<?php echo $receta_grid->RowIndex ?>_cantidad" id="x<?php echo $receta_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($receta->cantidad->FormValue) ?>">
<input type="hidden" data-field="x_cantidad" name="o<?php echo $receta_grid->RowIndex ?>_cantidad" id="o<?php echo $receta_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($receta->cantidad->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($receta->precio_unitario->Visible) { // precio_unitario ?>
		<td data-name="precio_unitario"<?php echo $receta->precio_unitario->CellAttributes() ?>>
<?php if ($receta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_precio_unitario" class="form-group receta_precio_unitario">
<input type="text" data-field="x_precio_unitario" name="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" id="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" size="30" placeholder="<?php echo ew_HtmlEncode($receta->precio_unitario->PlaceHolder) ?>" value="<?php echo $receta->precio_unitario->EditValue ?>"<?php echo $receta->precio_unitario->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_precio_unitario" name="o<?php echo $receta_grid->RowIndex ?>_precio_unitario" id="o<?php echo $receta_grid->RowIndex ?>_precio_unitario" value="<?php echo ew_HtmlEncode($receta->precio_unitario->OldValue) ?>">
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_precio_unitario" class="form-group receta_precio_unitario">
<input type="text" data-field="x_precio_unitario" name="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" id="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" size="30" placeholder="<?php echo ew_HtmlEncode($receta->precio_unitario->PlaceHolder) ?>" value="<?php echo $receta->precio_unitario->EditValue ?>"<?php echo $receta->precio_unitario->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $receta->precio_unitario->ViewAttributes() ?>>
<?php echo $receta->precio_unitario->ListViewValue() ?></span>
<input type="hidden" data-field="x_precio_unitario" name="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" id="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" value="<?php echo ew_HtmlEncode($receta->precio_unitario->FormValue) ?>">
<input type="hidden" data-field="x_precio_unitario" name="o<?php echo $receta_grid->RowIndex ?>_precio_unitario" id="o<?php echo $receta_grid->RowIndex ?>_precio_unitario" value="<?php echo ew_HtmlEncode($receta->precio_unitario->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($receta->idturno->Visible) { // idturno ?>
		<td data-name="idturno"<?php echo $receta->idturno->CellAttributes() ?>>
<?php if ($receta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($receta->idturno->getSessionValue() <> "") { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idturno" class="form-group receta_idturno">
<span<?php echo $receta->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $receta_grid->RowIndex ?>_idturno" name="x<?php echo $receta_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($receta->idturno->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idturno" class="form-group receta_idturno">
<select data-field="x_idturno" id="x<?php echo $receta_grid->RowIndex ?>_idturno" name="x<?php echo $receta_grid->RowIndex ?>_idturno"<?php echo $receta->idturno->EditAttributes() ?>>
<?php
if (is_array($receta->idturno->EditValue)) {
	$arwrk = $receta->idturno->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idturno->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $receta->idturno->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `turno`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $receta->Lookup_Selecting($receta->idturno, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $receta_grid->RowIndex ?>_idturno" id="s_x<?php echo $receta_grid->RowIndex ?>_idturno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idturno` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<input type="hidden" data-field="x_idturno" name="o<?php echo $receta_grid->RowIndex ?>_idturno" id="o<?php echo $receta_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($receta->idturno->OldValue) ?>">
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($receta->idturno->getSessionValue() <> "") { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idturno" class="form-group receta_idturno">
<span<?php echo $receta->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $receta_grid->RowIndex ?>_idturno" name="x<?php echo $receta_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($receta->idturno->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idturno" class="form-group receta_idturno">
<select data-field="x_idturno" id="x<?php echo $receta_grid->RowIndex ?>_idturno" name="x<?php echo $receta_grid->RowIndex ?>_idturno"<?php echo $receta->idturno->EditAttributes() ?>>
<?php
if (is_array($receta->idturno->EditValue)) {
	$arwrk = $receta->idturno->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idturno->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $receta->idturno->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `turno`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $receta->Lookup_Selecting($receta->idturno, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $receta_grid->RowIndex ?>_idturno" id="s_x<?php echo $receta_grid->RowIndex ?>_idturno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idturno` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $receta->idturno->ViewAttributes() ?>>
<?php echo $receta->idturno->ListViewValue() ?></span>
<input type="hidden" data-field="x_idturno" name="x<?php echo $receta_grid->RowIndex ?>_idturno" id="x<?php echo $receta_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($receta->idturno->FormValue) ?>">
<input type="hidden" data-field="x_idturno" name="o<?php echo $receta_grid->RowIndex ?>_idturno" id="o<?php echo $receta_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($receta->idturno->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($receta->idcuenta->Visible) { // idcuenta ?>
		<td data-name="idcuenta"<?php echo $receta->idcuenta->CellAttributes() ?>>
<?php if ($receta->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idcuenta" class="form-group receta_idcuenta">
<input type="text" data-field="x_idcuenta" name="x<?php echo $receta_grid->RowIndex ?>_idcuenta" id="x<?php echo $receta_grid->RowIndex ?>_idcuenta" size="30" placeholder="<?php echo ew_HtmlEncode($receta->idcuenta->PlaceHolder) ?>" value="<?php echo $receta->idcuenta->EditValue ?>"<?php echo $receta->idcuenta->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $receta_grid->RowIndex ?>_idcuenta" id="o<?php echo $receta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($receta->idcuenta->OldValue) ?>">
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $receta_grid->RowCnt ?>_receta_idcuenta" class="form-group receta_idcuenta">
<input type="text" data-field="x_idcuenta" name="x<?php echo $receta_grid->RowIndex ?>_idcuenta" id="x<?php echo $receta_grid->RowIndex ?>_idcuenta" size="30" placeholder="<?php echo ew_HtmlEncode($receta->idcuenta->PlaceHolder) ?>" value="<?php echo $receta->idcuenta->EditValue ?>"<?php echo $receta->idcuenta->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($receta->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $receta->idcuenta->ViewAttributes() ?>>
<?php echo $receta->idcuenta->ListViewValue() ?></span>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $receta_grid->RowIndex ?>_idcuenta" id="x<?php echo $receta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($receta->idcuenta->FormValue) ?>">
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $receta_grid->RowIndex ?>_idcuenta" id="o<?php echo $receta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($receta->idcuenta->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$receta_grid->ListOptions->Render("body", "right", $receta_grid->RowCnt);
?>
	</tr>
<?php if ($receta->RowType == EW_ROWTYPE_ADD || $receta->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
frecetagrid.UpdateOpts(<?php echo $receta_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($receta->CurrentAction <> "gridadd" || $receta->CurrentMode == "copy")
		if (!$receta_grid->Recordset->EOF) $receta_grid->Recordset->MoveNext();
}
?>
<?php
	if ($receta->CurrentMode == "add" || $receta->CurrentMode == "copy" || $receta->CurrentMode == "edit") {
		$receta_grid->RowIndex = '$rowindex$';
		$receta_grid->LoadDefaultValues();

		// Set row properties
		$receta->ResetAttrs();
		$receta->RowAttrs = array_merge($receta->RowAttrs, array('data-rowindex'=>$receta_grid->RowIndex, 'id'=>'r0_receta', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($receta->RowAttrs["class"], "ewTemplate");
		$receta->RowType = EW_ROWTYPE_ADD;

		// Render row
		$receta_grid->RenderRow();

		// Render list options
		$receta_grid->RenderListOptions();
		$receta_grid->StartRowCnt = 0;
?>
	<tr<?php echo $receta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$receta_grid->ListOptions->Render("body", "left", $receta_grid->RowIndex);
?>
	<?php if ($receta->idreceta->Visible) { // idreceta ?>
		<td>
<?php if ($receta->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_receta_idreceta" class="form-group receta_idreceta">
<span<?php echo $receta->idreceta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idreceta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idreceta" name="x<?php echo $receta_grid->RowIndex ?>_idreceta" id="x<?php echo $receta_grid->RowIndex ?>_idreceta" value="<?php echo ew_HtmlEncode($receta->idreceta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idreceta" name="o<?php echo $receta_grid->RowIndex ?>_idreceta" id="o<?php echo $receta_grid->RowIndex ?>_idreceta" value="<?php echo ew_HtmlEncode($receta->idreceta->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($receta->idempleado->Visible) { // idempleado ?>
		<td>
<?php if ($receta->CurrentAction <> "F") { ?>
<?php if ($receta->idempleado->getSessionValue() <> "") { ?>
<span id="el$rowindex$_receta_idempleado" class="form-group receta_idempleado">
<span<?php echo $receta->idempleado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idempleado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $receta_grid->RowIndex ?>_idempleado" name="x<?php echo $receta_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($receta->idempleado->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_receta_idempleado" class="form-group receta_idempleado">
<select data-field="x_idempleado" id="x<?php echo $receta_grid->RowIndex ?>_idempleado" name="x<?php echo $receta_grid->RowIndex ?>_idempleado"<?php echo $receta->idempleado->EditAttributes() ?>>
<?php
if (is_array($receta->idempleado->EditValue)) {
	$arwrk = $receta->idempleado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idempleado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$receta->idempleado) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $receta->idempleado->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idempleado`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $receta->Lookup_Selecting($receta->idempleado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $receta_grid->RowIndex ?>_idempleado" id="s_x<?php echo $receta_grid->RowIndex ?>_idempleado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idempleado` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_receta_idempleado" class="form-group receta_idempleado">
<span<?php echo $receta->idempleado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idempleado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idempleado" name="x<?php echo $receta_grid->RowIndex ?>_idempleado" id="x<?php echo $receta_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($receta->idempleado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idempleado" name="o<?php echo $receta_grid->RowIndex ?>_idempleado" id="o<?php echo $receta_grid->RowIndex ?>_idempleado" value="<?php echo ew_HtmlEncode($receta->idempleado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($receta->idmedicina->Visible) { // idmedicina ?>
		<td>
<?php if ($receta->CurrentAction <> "F") { ?>
<?php if ($receta->idmedicina->getSessionValue() <> "") { ?>
<span id="el$rowindex$_receta_idmedicina" class="form-group receta_idmedicina">
<span<?php echo $receta->idmedicina->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idmedicina->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $receta_grid->RowIndex ?>_idmedicina" name="x<?php echo $receta_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($receta->idmedicina->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_receta_idmedicina" class="form-group receta_idmedicina">
<select data-field="x_idmedicina" id="x<?php echo $receta_grid->RowIndex ?>_idmedicina" name="x<?php echo $receta_grid->RowIndex ?>_idmedicina"<?php echo $receta->idmedicina->EditAttributes() ?>>
<?php
if (is_array($receta->idmedicina->EditValue)) {
	$arwrk = $receta->idmedicina->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idmedicina->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $receta->idmedicina->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idmedicina`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `medicina`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $receta->Lookup_Selecting($receta->idmedicina, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $receta_grid->RowIndex ?>_idmedicina" id="s_x<?php echo $receta_grid->RowIndex ?>_idmedicina" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idmedicina` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_receta_idmedicina" class="form-group receta_idmedicina">
<span<?php echo $receta->idmedicina->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idmedicina->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idmedicina" name="x<?php echo $receta_grid->RowIndex ?>_idmedicina" id="x<?php echo $receta_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($receta->idmedicina->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idmedicina" name="o<?php echo $receta_grid->RowIndex ?>_idmedicina" id="o<?php echo $receta_grid->RowIndex ?>_idmedicina" value="<?php echo ew_HtmlEncode($receta->idmedicina->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($receta->fecha->Visible) { // fecha ?>
		<td>
<?php if ($receta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_receta_fecha" class="form-group receta_fecha">
<input type="text" data-field="x_fecha" name="x<?php echo $receta_grid->RowIndex ?>_fecha" id="x<?php echo $receta_grid->RowIndex ?>_fecha" placeholder="<?php echo ew_HtmlEncode($receta->fecha->PlaceHolder) ?>" value="<?php echo $receta->fecha->EditValue ?>"<?php echo $receta->fecha->EditAttributes() ?>>
<?php if (!$receta->fecha->ReadOnly && !$receta->fecha->Disabled && @$receta->fecha->EditAttrs["readonly"] == "" && @$receta->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("frecetagrid", "x<?php echo $receta_grid->RowIndex ?>_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_receta_fecha" class="form-group receta_fecha">
<span<?php echo $receta->fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_fecha" name="x<?php echo $receta_grid->RowIndex ?>_fecha" id="x<?php echo $receta_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($receta->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_fecha" name="o<?php echo $receta_grid->RowIndex ?>_fecha" id="o<?php echo $receta_grid->RowIndex ?>_fecha" value="<?php echo ew_HtmlEncode($receta->fecha->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($receta->cantidad->Visible) { // cantidad ?>
		<td>
<?php if ($receta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_receta_cantidad" class="form-group receta_cantidad">
<input type="text" data-field="x_cantidad" name="x<?php echo $receta_grid->RowIndex ?>_cantidad" id="x<?php echo $receta_grid->RowIndex ?>_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($receta->cantidad->PlaceHolder) ?>" value="<?php echo $receta->cantidad->EditValue ?>"<?php echo $receta->cantidad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_receta_cantidad" class="form-group receta_cantidad">
<span<?php echo $receta->cantidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->cantidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_cantidad" name="x<?php echo $receta_grid->RowIndex ?>_cantidad" id="x<?php echo $receta_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($receta->cantidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_cantidad" name="o<?php echo $receta_grid->RowIndex ?>_cantidad" id="o<?php echo $receta_grid->RowIndex ?>_cantidad" value="<?php echo ew_HtmlEncode($receta->cantidad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($receta->precio_unitario->Visible) { // precio_unitario ?>
		<td>
<?php if ($receta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_receta_precio_unitario" class="form-group receta_precio_unitario">
<input type="text" data-field="x_precio_unitario" name="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" id="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" size="30" placeholder="<?php echo ew_HtmlEncode($receta->precio_unitario->PlaceHolder) ?>" value="<?php echo $receta->precio_unitario->EditValue ?>"<?php echo $receta->precio_unitario->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_receta_precio_unitario" class="form-group receta_precio_unitario">
<span<?php echo $receta->precio_unitario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->precio_unitario->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_precio_unitario" name="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" id="x<?php echo $receta_grid->RowIndex ?>_precio_unitario" value="<?php echo ew_HtmlEncode($receta->precio_unitario->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_precio_unitario" name="o<?php echo $receta_grid->RowIndex ?>_precio_unitario" id="o<?php echo $receta_grid->RowIndex ?>_precio_unitario" value="<?php echo ew_HtmlEncode($receta->precio_unitario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($receta->idturno->Visible) { // idturno ?>
		<td>
<?php if ($receta->CurrentAction <> "F") { ?>
<?php if ($receta->idturno->getSessionValue() <> "") { ?>
<span id="el$rowindex$_receta_idturno" class="form-group receta_idturno">
<span<?php echo $receta->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $receta_grid->RowIndex ?>_idturno" name="x<?php echo $receta_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($receta->idturno->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_receta_idturno" class="form-group receta_idturno">
<select data-field="x_idturno" id="x<?php echo $receta_grid->RowIndex ?>_idturno" name="x<?php echo $receta_grid->RowIndex ?>_idturno"<?php echo $receta->idturno->EditAttributes() ?>>
<?php
if (is_array($receta->idturno->EditValue)) {
	$arwrk = $receta->idturno->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idturno->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $receta->idturno->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `turno`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $receta->Lookup_Selecting($receta->idturno, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $receta_grid->RowIndex ?>_idturno" id="s_x<?php echo $receta_grid->RowIndex ?>_idturno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idturno` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_receta_idturno" class="form-group receta_idturno">
<span<?php echo $receta->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idturno" name="x<?php echo $receta_grid->RowIndex ?>_idturno" id="x<?php echo $receta_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($receta->idturno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idturno" name="o<?php echo $receta_grid->RowIndex ?>_idturno" id="o<?php echo $receta_grid->RowIndex ?>_idturno" value="<?php echo ew_HtmlEncode($receta->idturno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($receta->idcuenta->Visible) { // idcuenta ?>
		<td>
<?php if ($receta->CurrentAction <> "F") { ?>
<span id="el$rowindex$_receta_idcuenta" class="form-group receta_idcuenta">
<input type="text" data-field="x_idcuenta" name="x<?php echo $receta_grid->RowIndex ?>_idcuenta" id="x<?php echo $receta_grid->RowIndex ?>_idcuenta" size="30" placeholder="<?php echo ew_HtmlEncode($receta->idcuenta->PlaceHolder) ?>" value="<?php echo $receta->idcuenta->EditValue ?>"<?php echo $receta->idcuenta->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_receta_idcuenta" class="form-group receta_idcuenta">
<span<?php echo $receta->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idcuenta" name="x<?php echo $receta_grid->RowIndex ?>_idcuenta" id="x<?php echo $receta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($receta->idcuenta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idcuenta" name="o<?php echo $receta_grid->RowIndex ?>_idcuenta" id="o<?php echo $receta_grid->RowIndex ?>_idcuenta" value="<?php echo ew_HtmlEncode($receta->idcuenta->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$receta_grid->ListOptions->Render("body", "right", $receta_grid->RowCnt);
?>
<script type="text/javascript">
frecetagrid.UpdateOpts(<?php echo $receta_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($receta->CurrentMode == "add" || $receta->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $receta_grid->FormKeyCountName ?>" id="<?php echo $receta_grid->FormKeyCountName ?>" value="<?php echo $receta_grid->KeyCount ?>">
<?php echo $receta_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($receta->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $receta_grid->FormKeyCountName ?>" id="<?php echo $receta_grid->FormKeyCountName ?>" value="<?php echo $receta_grid->KeyCount ?>">
<?php echo $receta_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($receta->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="frecetagrid">
</div>
<?php

// Close recordset
if ($receta_grid->Recordset)
	$receta_grid->Recordset->Close();
?>
<?php if ($receta_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($receta_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($receta_grid->TotalRecs == 0 && $receta->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($receta_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($receta->Export == "") { ?>
<script type="text/javascript">
frecetagrid.Init();
</script>
<?php } ?>
<?php
$receta_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$receta_grid->Page_Terminate();
?>

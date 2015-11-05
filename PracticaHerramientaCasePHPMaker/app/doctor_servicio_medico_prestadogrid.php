<?php

// Create page object
if (!isset($doctor_servicio_medico_prestado_grid)) $doctor_servicio_medico_prestado_grid = new cdoctor_servicio_medico_prestado_grid();

// Page init
$doctor_servicio_medico_prestado_grid->Page_Init();

// Page main
$doctor_servicio_medico_prestado_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$doctor_servicio_medico_prestado_grid->Page_Render();
?>
<?php if ($doctor_servicio_medico_prestado->Export == "") { ?>
<script type="text/javascript">

// Page object
var doctor_servicio_medico_prestado_grid = new ew_Page("doctor_servicio_medico_prestado_grid");
doctor_servicio_medico_prestado_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = doctor_servicio_medico_prestado_grid.PageID; // For backward compatibility

// Form object
var fdoctor_servicio_medico_prestadogrid = new ew_Form("fdoctor_servicio_medico_prestadogrid");
fdoctor_servicio_medico_prestadogrid.FormKeyCountName = '<?php echo $doctor_servicio_medico_prestado_grid->FormKeyCountName ?>';

// Validate form
fdoctor_servicio_medico_prestadogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_iddoctor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor_servicio_medico_prestado->iddoctor->FldCaption(), $doctor_servicio_medico_prestado->iddoctor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idservicio_medico_prestado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor_servicio_medico_prestado->idservicio_medico_prestado->FldCaption(), $doctor_servicio_medico_prestado->idservicio_medico_prestado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idservicio_medico_prestado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($doctor_servicio_medico_prestado->idservicio_medico_prestado->FldErrMsg()) ?>");

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
fdoctor_servicio_medico_prestadogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "iddoctor", false)) return false;
	if (ew_ValueChanged(fobj, infix, "idservicio_medico_prestado", false)) return false;
	return true;
}

// Form_CustomValidate event
fdoctor_servicio_medico_prestadogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdoctor_servicio_medico_prestadogrid.ValidateRequired = true;
<?php } else { ?>
fdoctor_servicio_medico_prestadogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdoctor_servicio_medico_prestadogrid.Lists["x_iddoctor"] = {"LinkField":"x_iddoctor","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdoctor_servicio_medico_prestadogrid.Lists["x_idservicio_medico_prestado"] = {"LinkField":"x_idservicio_medico_prestado","Ajax":true,"AutoFill":false,"DisplayFields":["x_idservicio_medico_prestado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($doctor_servicio_medico_prestado->CurrentAction == "gridadd") {
	if ($doctor_servicio_medico_prestado->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$doctor_servicio_medico_prestado_grid->TotalRecs = $doctor_servicio_medico_prestado->SelectRecordCount();
			$doctor_servicio_medico_prestado_grid->Recordset = $doctor_servicio_medico_prestado_grid->LoadRecordset($doctor_servicio_medico_prestado_grid->StartRec-1, $doctor_servicio_medico_prestado_grid->DisplayRecs);
		} else {
			if ($doctor_servicio_medico_prestado_grid->Recordset = $doctor_servicio_medico_prestado_grid->LoadRecordset())
				$doctor_servicio_medico_prestado_grid->TotalRecs = $doctor_servicio_medico_prestado_grid->Recordset->RecordCount();
		}
		$doctor_servicio_medico_prestado_grid->StartRec = 1;
		$doctor_servicio_medico_prestado_grid->DisplayRecs = $doctor_servicio_medico_prestado_grid->TotalRecs;
	} else {
		$doctor_servicio_medico_prestado->CurrentFilter = "0=1";
		$doctor_servicio_medico_prestado_grid->StartRec = 1;
		$doctor_servicio_medico_prestado_grid->DisplayRecs = $doctor_servicio_medico_prestado->GridAddRowCount;
	}
	$doctor_servicio_medico_prestado_grid->TotalRecs = $doctor_servicio_medico_prestado_grid->DisplayRecs;
	$doctor_servicio_medico_prestado_grid->StopRec = $doctor_servicio_medico_prestado_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$doctor_servicio_medico_prestado_grid->TotalRecs = $doctor_servicio_medico_prestado->SelectRecordCount();
	} else {
		if ($doctor_servicio_medico_prestado_grid->Recordset = $doctor_servicio_medico_prestado_grid->LoadRecordset())
			$doctor_servicio_medico_prestado_grid->TotalRecs = $doctor_servicio_medico_prestado_grid->Recordset->RecordCount();
	}
	$doctor_servicio_medico_prestado_grid->StartRec = 1;
	$doctor_servicio_medico_prestado_grid->DisplayRecs = $doctor_servicio_medico_prestado_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$doctor_servicio_medico_prestado_grid->Recordset = $doctor_servicio_medico_prestado_grid->LoadRecordset($doctor_servicio_medico_prestado_grid->StartRec-1, $doctor_servicio_medico_prestado_grid->DisplayRecs);

	// Set no record found message
	if ($doctor_servicio_medico_prestado->CurrentAction == "" && $doctor_servicio_medico_prestado_grid->TotalRecs == 0) {
		if ($doctor_servicio_medico_prestado_grid->SearchWhere == "0=101")
			$doctor_servicio_medico_prestado_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$doctor_servicio_medico_prestado_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$doctor_servicio_medico_prestado_grid->RenderOtherOptions();
?>
<?php $doctor_servicio_medico_prestado_grid->ShowPageHeader(); ?>
<?php
$doctor_servicio_medico_prestado_grid->ShowMessage();
?>
<?php if ($doctor_servicio_medico_prestado_grid->TotalRecs > 0 || $doctor_servicio_medico_prestado->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdoctor_servicio_medico_prestadogrid" class="ewForm form-inline">
<div id="gmp_doctor_servicio_medico_prestado" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_doctor_servicio_medico_prestadogrid" class="table ewTable">
<?php echo $doctor_servicio_medico_prestado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$doctor_servicio_medico_prestado_grid->RenderListOptions();

// Render list options (header, left)
$doctor_servicio_medico_prestado_grid->ListOptions->Render("header", "left");
?>
<?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->Visible) { // iddoctor_servicio_medico_prestado ?>
	<?php if ($doctor_servicio_medico_prestado->SortUrl($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado) == "") { ?>
		<th data-name="iddoctor_servicio_medico_prestado"><div id="elh_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado" class="doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado"><div class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iddoctor_servicio_medico_prestado"><div><div id="elh_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado" class="doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor_servicio_medico_prestado->iddoctor->Visible) { // iddoctor ?>
	<?php if ($doctor_servicio_medico_prestado->SortUrl($doctor_servicio_medico_prestado->iddoctor) == "") { ?>
		<th data-name="iddoctor"><div id="elh_doctor_servicio_medico_prestado_iddoctor" class="doctor_servicio_medico_prestado_iddoctor"><div class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->iddoctor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iddoctor"><div><div id="elh_doctor_servicio_medico_prestado_iddoctor" class="doctor_servicio_medico_prestado_iddoctor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->iddoctor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor_servicio_medico_prestado->iddoctor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor_servicio_medico_prestado->iddoctor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->Visible) { // idservicio_medico_prestado ?>
	<?php if ($doctor_servicio_medico_prestado->SortUrl($doctor_servicio_medico_prestado->idservicio_medico_prestado) == "") { ?>
		<th data-name="idservicio_medico_prestado"><div id="elh_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="doctor_servicio_medico_prestado_idservicio_medico_prestado"><div class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idservicio_medico_prestado"><div><div id="elh_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="doctor_servicio_medico_prestado_idservicio_medico_prestado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor_servicio_medico_prestado->idservicio_medico_prestado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$doctor_servicio_medico_prestado_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$doctor_servicio_medico_prestado_grid->StartRec = 1;
$doctor_servicio_medico_prestado_grid->StopRec = $doctor_servicio_medico_prestado_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($doctor_servicio_medico_prestado_grid->FormKeyCountName) && ($doctor_servicio_medico_prestado->CurrentAction == "gridadd" || $doctor_servicio_medico_prestado->CurrentAction == "gridedit" || $doctor_servicio_medico_prestado->CurrentAction == "F")) {
		$doctor_servicio_medico_prestado_grid->KeyCount = $objForm->GetValue($doctor_servicio_medico_prestado_grid->FormKeyCountName);
		$doctor_servicio_medico_prestado_grid->StopRec = $doctor_servicio_medico_prestado_grid->StartRec + $doctor_servicio_medico_prestado_grid->KeyCount - 1;
	}
}
$doctor_servicio_medico_prestado_grid->RecCnt = $doctor_servicio_medico_prestado_grid->StartRec - 1;
if ($doctor_servicio_medico_prestado_grid->Recordset && !$doctor_servicio_medico_prestado_grid->Recordset->EOF) {
	$doctor_servicio_medico_prestado_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $doctor_servicio_medico_prestado_grid->StartRec > 1)
		$doctor_servicio_medico_prestado_grid->Recordset->Move($doctor_servicio_medico_prestado_grid->StartRec - 1);
} elseif (!$doctor_servicio_medico_prestado->AllowAddDeleteRow && $doctor_servicio_medico_prestado_grid->StopRec == 0) {
	$doctor_servicio_medico_prestado_grid->StopRec = $doctor_servicio_medico_prestado->GridAddRowCount;
}

// Initialize aggregate
$doctor_servicio_medico_prestado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$doctor_servicio_medico_prestado->ResetAttrs();
$doctor_servicio_medico_prestado_grid->RenderRow();
if ($doctor_servicio_medico_prestado->CurrentAction == "gridadd")
	$doctor_servicio_medico_prestado_grid->RowIndex = 0;
if ($doctor_servicio_medico_prestado->CurrentAction == "gridedit")
	$doctor_servicio_medico_prestado_grid->RowIndex = 0;
while ($doctor_servicio_medico_prestado_grid->RecCnt < $doctor_servicio_medico_prestado_grid->StopRec) {
	$doctor_servicio_medico_prestado_grid->RecCnt++;
	if (intval($doctor_servicio_medico_prestado_grid->RecCnt) >= intval($doctor_servicio_medico_prestado_grid->StartRec)) {
		$doctor_servicio_medico_prestado_grid->RowCnt++;
		if ($doctor_servicio_medico_prestado->CurrentAction == "gridadd" || $doctor_servicio_medico_prestado->CurrentAction == "gridedit" || $doctor_servicio_medico_prestado->CurrentAction == "F") {
			$doctor_servicio_medico_prestado_grid->RowIndex++;
			$objForm->Index = $doctor_servicio_medico_prestado_grid->RowIndex;
			if ($objForm->HasValue($doctor_servicio_medico_prestado_grid->FormActionName))
				$doctor_servicio_medico_prestado_grid->RowAction = strval($objForm->GetValue($doctor_servicio_medico_prestado_grid->FormActionName));
			elseif ($doctor_servicio_medico_prestado->CurrentAction == "gridadd")
				$doctor_servicio_medico_prestado_grid->RowAction = "insert";
			else
				$doctor_servicio_medico_prestado_grid->RowAction = "";
		}

		// Set up key count
		$doctor_servicio_medico_prestado_grid->KeyCount = $doctor_servicio_medico_prestado_grid->RowIndex;

		// Init row class and style
		$doctor_servicio_medico_prestado->ResetAttrs();
		$doctor_servicio_medico_prestado->CssClass = "";
		if ($doctor_servicio_medico_prestado->CurrentAction == "gridadd") {
			if ($doctor_servicio_medico_prestado->CurrentMode == "copy") {
				$doctor_servicio_medico_prestado_grid->LoadRowValues($doctor_servicio_medico_prestado_grid->Recordset); // Load row values
				$doctor_servicio_medico_prestado_grid->SetRecordKey($doctor_servicio_medico_prestado_grid->RowOldKey, $doctor_servicio_medico_prestado_grid->Recordset); // Set old record key
			} else {
				$doctor_servicio_medico_prestado_grid->LoadDefaultValues(); // Load default values
				$doctor_servicio_medico_prestado_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$doctor_servicio_medico_prestado_grid->LoadRowValues($doctor_servicio_medico_prestado_grid->Recordset); // Load row values
		}
		$doctor_servicio_medico_prestado->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($doctor_servicio_medico_prestado->CurrentAction == "gridadd") // Grid add
			$doctor_servicio_medico_prestado->RowType = EW_ROWTYPE_ADD; // Render add
		if ($doctor_servicio_medico_prestado->CurrentAction == "gridadd" && $doctor_servicio_medico_prestado->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$doctor_servicio_medico_prestado_grid->RestoreCurrentRowFormValues($doctor_servicio_medico_prestado_grid->RowIndex); // Restore form values
		if ($doctor_servicio_medico_prestado->CurrentAction == "gridedit") { // Grid edit
			if ($doctor_servicio_medico_prestado->EventCancelled) {
				$doctor_servicio_medico_prestado_grid->RestoreCurrentRowFormValues($doctor_servicio_medico_prestado_grid->RowIndex); // Restore form values
			}
			if ($doctor_servicio_medico_prestado_grid->RowAction == "insert")
				$doctor_servicio_medico_prestado->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$doctor_servicio_medico_prestado->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($doctor_servicio_medico_prestado->CurrentAction == "gridedit" && ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT || $doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) && $doctor_servicio_medico_prestado->EventCancelled) // Update failed
			$doctor_servicio_medico_prestado_grid->RestoreCurrentRowFormValues($doctor_servicio_medico_prestado_grid->RowIndex); // Restore form values
		if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) // Edit row
			$doctor_servicio_medico_prestado_grid->EditRowCnt++;
		if ($doctor_servicio_medico_prestado->CurrentAction == "F") // Confirm row
			$doctor_servicio_medico_prestado_grid->RestoreCurrentRowFormValues($doctor_servicio_medico_prestado_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$doctor_servicio_medico_prestado->RowAttrs = array_merge($doctor_servicio_medico_prestado->RowAttrs, array('data-rowindex'=>$doctor_servicio_medico_prestado_grid->RowCnt, 'id'=>'r' . $doctor_servicio_medico_prestado_grid->RowCnt . '_doctor_servicio_medico_prestado', 'data-rowtype'=>$doctor_servicio_medico_prestado->RowType));

		// Render row
		$doctor_servicio_medico_prestado_grid->RenderRow();

		// Render list options
		$doctor_servicio_medico_prestado_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($doctor_servicio_medico_prestado_grid->RowAction <> "delete" && $doctor_servicio_medico_prestado_grid->RowAction <> "insertdelete" && !($doctor_servicio_medico_prestado_grid->RowAction == "insert" && $doctor_servicio_medico_prestado->CurrentAction == "F" && $doctor_servicio_medico_prestado_grid->EmptyRow())) {
?>
	<tr<?php echo $doctor_servicio_medico_prestado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$doctor_servicio_medico_prestado_grid->ListOptions->Render("body", "left", $doctor_servicio_medico_prestado_grid->RowCnt);
?>
	<?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->Visible) { // iddoctor_servicio_medico_prestado ?>
		<td data-name="iddoctor_servicio_medico_prestado"<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->CellAttributes() ?>>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_iddoctor_servicio_medico_prestado" name="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" id="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->OldValue) ?>">
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_servicio_medico_prestado_grid->RowCnt ?>_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado">
<span<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_iddoctor_servicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->CurrentValue) ?>">
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ListViewValue() ?></span>
<input type="hidden" data-field="x_iddoctor_servicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->FormValue) ?>">
<input type="hidden" data-field="x_iddoctor_servicio_medico_prestado" name="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" id="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->OldValue) ?>">
<?php } ?>
<a id="<?php echo $doctor_servicio_medico_prestado_grid->PageObjName . "_row_" . $doctor_servicio_medico_prestado_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($doctor_servicio_medico_prestado->iddoctor->Visible) { // iddoctor ?>
		<td data-name="iddoctor"<?php echo $doctor_servicio_medico_prestado->iddoctor->CellAttributes() ?>>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $doctor_servicio_medico_prestado_grid->RowCnt ?>_doctor_servicio_medico_prestado_iddoctor" class="form-group doctor_servicio_medico_prestado_iddoctor">
<select data-field="x_iddoctor" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor"<?php echo $doctor_servicio_medico_prestado->iddoctor->EditAttributes() ?>>
<?php
if (is_array($doctor_servicio_medico_prestado->iddoctor->EditValue)) {
	$arwrk = $doctor_servicio_medico_prestado->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_servicio_medico_prestado->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor_servicio_medico_prestado->iddoctor->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $doctor_servicio_medico_prestado->Lookup_Selecting($doctor_servicio_medico_prestado->iddoctor, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" id="s_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" id="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor->OldValue) ?>">
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $doctor_servicio_medico_prestado_grid->RowCnt ?>_doctor_servicio_medico_prestado_iddoctor" class="form-group doctor_servicio_medico_prestado_iddoctor">
<select data-field="x_iddoctor" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor"<?php echo $doctor_servicio_medico_prestado->iddoctor->EditAttributes() ?>>
<?php
if (is_array($doctor_servicio_medico_prestado->iddoctor->EditValue)) {
	$arwrk = $doctor_servicio_medico_prestado->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_servicio_medico_prestado->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor_servicio_medico_prestado->iddoctor->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $doctor_servicio_medico_prestado->Lookup_Selecting($doctor_servicio_medico_prestado->iddoctor, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" id="s_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor_servicio_medico_prestado->iddoctor->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->iddoctor->ListViewValue() ?></span>
<input type="hidden" data-field="x_iddoctor" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor->FormValue) ?>">
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" id="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->Visible) { // idservicio_medico_prestado ?>
		<td data-name="idservicio_medico_prestado"<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->CellAttributes() ?>>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->getSessionValue() <> "") { ?>
<span id="el<?php echo $doctor_servicio_medico_prestado_grid->RowCnt ?>_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_idservicio_medico_prestado">
<span<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $doctor_servicio_medico_prestado_grid->RowCnt ?>_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_idservicio_medico_prestado">
<?php
	$wrkonchange = trim(" " . @$doctor_servicio_medico_prestado->idservicio_medico_prestado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$doctor_servicio_medico_prestado->idservicio_medico_prestado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" style="white-space: nowrap; z-index: <?php echo (9000 - $doctor_servicio_medico_prestado_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="sv_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->PlaceHolder) ?>"<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_idservicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->PlaceHolder) ?>">
<?php
 $sSqlWrk = "SELECT `idservicio_medico_prestado`, `idservicio_medico_prestado` AS `DispFld` FROM `servicio_medico_prestado`";
 $sWhereWrk = "`idservicio_medico_prestado` LIKE '{query_value}%'";

 // Call Lookup selecting
 $doctor_servicio_medico_prestado->Lookup_Selecting($doctor_servicio_medico_prestado->idservicio_medico_prestado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="q_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fdoctor_servicio_medico_prestadogrid.CreateAutoSuggest("x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado", true);
</script>
</span>
<?php } ?>
<input type="hidden" data-field="x_idservicio_medico_prestado" name="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->OldValue) ?>">
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->getSessionValue() <> "") { ?>
<span id="el<?php echo $doctor_servicio_medico_prestado_grid->RowCnt ?>_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_idservicio_medico_prestado">
<span<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $doctor_servicio_medico_prestado_grid->RowCnt ?>_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_idservicio_medico_prestado">
<?php
	$wrkonchange = trim(" " . @$doctor_servicio_medico_prestado->idservicio_medico_prestado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$doctor_servicio_medico_prestado->idservicio_medico_prestado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" style="white-space: nowrap; z-index: <?php echo (9000 - $doctor_servicio_medico_prestado_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="sv_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->PlaceHolder) ?>"<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_idservicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->PlaceHolder) ?>">
<?php
 $sSqlWrk = "SELECT `idservicio_medico_prestado`, `idservicio_medico_prestado` AS `DispFld` FROM `servicio_medico_prestado`";
 $sWhereWrk = "`idservicio_medico_prestado` LIKE '{query_value}%'";

 // Call Lookup selecting
 $doctor_servicio_medico_prestado->Lookup_Selecting($doctor_servicio_medico_prestado->idservicio_medico_prestado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="q_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fdoctor_servicio_medico_prestadogrid.CreateAutoSuggest("x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado", true);
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ListViewValue() ?></span>
<input type="hidden" data-field="x_idservicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->FormValue) ?>">
<input type="hidden" data-field="x_idservicio_medico_prestado" name="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$doctor_servicio_medico_prestado_grid->ListOptions->Render("body", "right", $doctor_servicio_medico_prestado_grid->RowCnt);
?>
	</tr>
<?php if ($doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_ADD || $doctor_servicio_medico_prestado->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdoctor_servicio_medico_prestadogrid.UpdateOpts(<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($doctor_servicio_medico_prestado->CurrentAction <> "gridadd" || $doctor_servicio_medico_prestado->CurrentMode == "copy")
		if (!$doctor_servicio_medico_prestado_grid->Recordset->EOF) $doctor_servicio_medico_prestado_grid->Recordset->MoveNext();
}
?>
<?php
	if ($doctor_servicio_medico_prestado->CurrentMode == "add" || $doctor_servicio_medico_prestado->CurrentMode == "copy" || $doctor_servicio_medico_prestado->CurrentMode == "edit") {
		$doctor_servicio_medico_prestado_grid->RowIndex = '$rowindex$';
		$doctor_servicio_medico_prestado_grid->LoadDefaultValues();

		// Set row properties
		$doctor_servicio_medico_prestado->ResetAttrs();
		$doctor_servicio_medico_prestado->RowAttrs = array_merge($doctor_servicio_medico_prestado->RowAttrs, array('data-rowindex'=>$doctor_servicio_medico_prestado_grid->RowIndex, 'id'=>'r0_doctor_servicio_medico_prestado', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($doctor_servicio_medico_prestado->RowAttrs["class"], "ewTemplate");
		$doctor_servicio_medico_prestado->RowType = EW_ROWTYPE_ADD;

		// Render row
		$doctor_servicio_medico_prestado_grid->RenderRow();

		// Render list options
		$doctor_servicio_medico_prestado_grid->RenderListOptions();
		$doctor_servicio_medico_prestado_grid->StartRowCnt = 0;
?>
	<tr<?php echo $doctor_servicio_medico_prestado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$doctor_servicio_medico_prestado_grid->ListOptions->Render("body", "left", $doctor_servicio_medico_prestado_grid->RowIndex);
?>
	<?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->Visible) { // iddoctor_servicio_medico_prestado ?>
		<td>
<?php if ($doctor_servicio_medico_prestado->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado">
<span<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iddoctor_servicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iddoctor_servicio_medico_prestado" name="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" id="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor_servicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor_servicio_medico_prestado->iddoctor->Visible) { // iddoctor ?>
		<td>
<?php if ($doctor_servicio_medico_prestado->CurrentAction <> "F") { ?>
<span id="el$rowindex$_doctor_servicio_medico_prestado_iddoctor" class="form-group doctor_servicio_medico_prestado_iddoctor">
<select data-field="x_iddoctor" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor"<?php echo $doctor_servicio_medico_prestado->iddoctor->EditAttributes() ?>>
<?php
if (is_array($doctor_servicio_medico_prestado->iddoctor->EditValue)) {
	$arwrk = $doctor_servicio_medico_prestado->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_servicio_medico_prestado->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $doctor_servicio_medico_prestado->iddoctor->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $doctor_servicio_medico_prestado->Lookup_Selecting($doctor_servicio_medico_prestado->iddoctor, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" id="s_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } else { ?>
<span id="el$rowindex$_doctor_servicio_medico_prestado_iddoctor" class="form-group doctor_servicio_medico_prestado_iddoctor">
<span<?php echo $doctor_servicio_medico_prestado->iddoctor->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_servicio_medico_prestado->iddoctor->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_iddoctor" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_iddoctor" name="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" id="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_iddoctor" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->iddoctor->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->Visible) { // idservicio_medico_prestado ?>
		<td>
<?php if ($doctor_servicio_medico_prestado->CurrentAction <> "F") { ?>
<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->getSessionValue() <> "") { ?>
<span id="el$rowindex$_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_idservicio_medico_prestado">
<span<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_idservicio_medico_prestado">
<?php
	$wrkonchange = trim(" " . @$doctor_servicio_medico_prestado->idservicio_medico_prestado->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$doctor_servicio_medico_prestado->idservicio_medico_prestado->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" style="white-space: nowrap; z-index: <?php echo (9000 - $doctor_servicio_medico_prestado_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="sv_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->PlaceHolder) ?>"<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_idservicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->PlaceHolder) ?>">
<?php
 $sSqlWrk = "SELECT `idservicio_medico_prestado`, `idservicio_medico_prestado` AS `DispFld` FROM `servicio_medico_prestado`";
 $sWhereWrk = "`idservicio_medico_prestado` LIKE '{query_value}%'";

 // Call Lookup selecting
 $doctor_servicio_medico_prestado->Lookup_Selecting($doctor_servicio_medico_prestado->idservicio_medico_prestado, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="q_x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fdoctor_servicio_medico_prestadogrid.CreateAutoSuggest("x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado", true);
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_idservicio_medico_prestado">
<span<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_idservicio_medico_prestado" name="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="x<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_idservicio_medico_prestado" name="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" id="o<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>_idservicio_medico_prestado" value="<?php echo ew_HtmlEncode($doctor_servicio_medico_prestado->idservicio_medico_prestado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$doctor_servicio_medico_prestado_grid->ListOptions->Render("body", "right", $doctor_servicio_medico_prestado_grid->RowCnt);
?>
<script type="text/javascript">
fdoctor_servicio_medico_prestadogrid.UpdateOpts(<?php echo $doctor_servicio_medico_prestado_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($doctor_servicio_medico_prestado->CurrentMode == "add" || $doctor_servicio_medico_prestado->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $doctor_servicio_medico_prestado_grid->FormKeyCountName ?>" id="<?php echo $doctor_servicio_medico_prestado_grid->FormKeyCountName ?>" value="<?php echo $doctor_servicio_medico_prestado_grid->KeyCount ?>">
<?php echo $doctor_servicio_medico_prestado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $doctor_servicio_medico_prestado_grid->FormKeyCountName ?>" id="<?php echo $doctor_servicio_medico_prestado_grid->FormKeyCountName ?>" value="<?php echo $doctor_servicio_medico_prestado_grid->KeyCount ?>">
<?php echo $doctor_servicio_medico_prestado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdoctor_servicio_medico_prestadogrid">
</div>
<?php

// Close recordset
if ($doctor_servicio_medico_prestado_grid->Recordset)
	$doctor_servicio_medico_prestado_grid->Recordset->Close();
?>
<?php if ($doctor_servicio_medico_prestado_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($doctor_servicio_medico_prestado_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado_grid->TotalRecs == 0 && $doctor_servicio_medico_prestado->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($doctor_servicio_medico_prestado_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->Export == "") { ?>
<script type="text/javascript">
fdoctor_servicio_medico_prestadogrid.Init();
</script>
<?php } ?>
<?php
$doctor_servicio_medico_prestado_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$doctor_servicio_medico_prestado_grid->Page_Terminate();
?>

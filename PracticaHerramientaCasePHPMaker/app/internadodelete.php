<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "internadoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$internado_delete = NULL; // Initialize page object first

class cinternado_delete extends cinternado {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'internado';

	// Page object name
	var $PageObjName = 'internado_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (internado)
		if (!isset($GLOBALS["internado"]) || get_class($GLOBALS["internado"]) == "cinternado") {
			$GLOBALS["internado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["internado"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'internado', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idinternado->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $internado;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($internado);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("internadolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in internado class, internadoinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->idinternado->setDbValue($rs->fields('idinternado'));
		$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
		$this->fecha_final->setDbValue($rs->fields('fecha_final'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->idhabitacion->setDbValue($rs->fields('idhabitacion'));
		$this->idcuenta->setDbValue($rs->fields('idcuenta'));
		$this->costo_diario->setDbValue($rs->fields('costo_diario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idinternado->DbValue = $row['idinternado'];
		$this->fecha_inicio->DbValue = $row['fecha_inicio'];
		$this->fecha_final->DbValue = $row['fecha_final'];
		$this->estado->DbValue = $row['estado'];
		$this->fecha->DbValue = $row['fecha'];
		$this->idhabitacion->DbValue = $row['idhabitacion'];
		$this->idcuenta->DbValue = $row['idcuenta'];
		$this->costo_diario->DbValue = $row['costo_diario'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->costo_diario->FormValue == $this->costo_diario->CurrentValue && is_numeric(ew_StrToFloat($this->costo_diario->CurrentValue)))
			$this->costo_diario->CurrentValue = ew_StrToFloat($this->costo_diario->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idinternado
		// fecha_inicio
		// fecha_final
		// estado
		// fecha
		// idhabitacion
		// idcuenta
		// costo_diario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idinternado
			$this->idinternado->ViewValue = $this->idinternado->CurrentValue;
			$this->idinternado->ViewCustomAttributes = "";

			// fecha_inicio
			$this->fecha_inicio->ViewValue = $this->fecha_inicio->CurrentValue;
			$this->fecha_inicio->ViewValue = ew_FormatDateTime($this->fecha_inicio->ViewValue, 5);
			$this->fecha_inicio->ViewCustomAttributes = "";

			// fecha_final
			$this->fecha_final->ViewValue = $this->fecha_final->CurrentValue;
			$this->fecha_final->ViewValue = ew_FormatDateTime($this->fecha_final->ViewValue, 5);
			$this->fecha_final->ViewCustomAttributes = "";

			// estado
			if (strval($this->estado->CurrentValue) <> "") {
				switch ($this->estado->CurrentValue) {
					case $this->estado->FldTagValue(1):
						$this->estado->ViewValue = $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->CurrentValue;
						break;
					case $this->estado->FldTagValue(2):
						$this->estado->ViewValue = $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->CurrentValue;
						break;
					case $this->estado->FldTagValue(3):
						$this->estado->ViewValue = $this->estado->FldTagCaption(3) <> "" ? $this->estado->FldTagCaption(3) : $this->estado->CurrentValue;
						break;
					default:
						$this->estado->ViewValue = $this->estado->CurrentValue;
				}
			} else {
				$this->estado->ViewValue = NULL;
			}
			$this->estado->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 5);
			$this->fecha->ViewCustomAttributes = "";

			// idhabitacion
			$this->idhabitacion->ViewValue = $this->idhabitacion->CurrentValue;
			$this->idhabitacion->ViewCustomAttributes = "";

			// idcuenta
			$this->idcuenta->ViewValue = $this->idcuenta->CurrentValue;
			$this->idcuenta->ViewCustomAttributes = "";

			// costo_diario
			$this->costo_diario->ViewValue = $this->costo_diario->CurrentValue;
			$this->costo_diario->ViewCustomAttributes = "";

			// idinternado
			$this->idinternado->LinkCustomAttributes = "";
			$this->idinternado->HrefValue = "";
			$this->idinternado->TooltipValue = "";

			// fecha_inicio
			$this->fecha_inicio->LinkCustomAttributes = "";
			$this->fecha_inicio->HrefValue = "";
			$this->fecha_inicio->TooltipValue = "";

			// fecha_final
			$this->fecha_final->LinkCustomAttributes = "";
			$this->fecha_final->HrefValue = "";
			$this->fecha_final->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// idhabitacion
			$this->idhabitacion->LinkCustomAttributes = "";
			$this->idhabitacion->HrefValue = "";
			$this->idhabitacion->TooltipValue = "";

			// idcuenta
			$this->idcuenta->LinkCustomAttributes = "";
			$this->idcuenta->HrefValue = "";
			$this->idcuenta->TooltipValue = "";

			// costo_diario
			$this->costo_diario->LinkCustomAttributes = "";
			$this->costo_diario->HrefValue = "";
			$this->costo_diario->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['idinternado'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "internadolist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($internado_delete)) $internado_delete = new cinternado_delete();

// Page init
$internado_delete->Page_Init();

// Page main
$internado_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$internado_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var internado_delete = new ew_Page("internado_delete");
internado_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = internado_delete.PageID; // For backward compatibility

// Form object
var finternadodelete = new ew_Form("finternadodelete");

// Form_CustomValidate event
finternadodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finternadodelete.ValidateRequired = true;
<?php } else { ?>
finternadodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($internado_delete->Recordset = $internado_delete->LoadRecordset())
	$internado_deleteTotalRecs = $internado_delete->Recordset->RecordCount(); // Get record count
if ($internado_deleteTotalRecs <= 0) { // No record found, exit
	if ($internado_delete->Recordset)
		$internado_delete->Recordset->Close();
	$internado_delete->Page_Terminate("internadolist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $internado_delete->ShowPageHeader(); ?>
<?php
$internado_delete->ShowMessage();
?>
<form name="finternadodelete" id="finternadodelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($internado_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $internado_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="internado">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($internado_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $internado->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($internado->idinternado->Visible) { // idinternado ?>
		<th><span id="elh_internado_idinternado" class="internado_idinternado"><?php echo $internado->idinternado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($internado->fecha_inicio->Visible) { // fecha_inicio ?>
		<th><span id="elh_internado_fecha_inicio" class="internado_fecha_inicio"><?php echo $internado->fecha_inicio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($internado->fecha_final->Visible) { // fecha_final ?>
		<th><span id="elh_internado_fecha_final" class="internado_fecha_final"><?php echo $internado->fecha_final->FldCaption() ?></span></th>
<?php } ?>
<?php if ($internado->estado->Visible) { // estado ?>
		<th><span id="elh_internado_estado" class="internado_estado"><?php echo $internado->estado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($internado->fecha->Visible) { // fecha ?>
		<th><span id="elh_internado_fecha" class="internado_fecha"><?php echo $internado->fecha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($internado->idhabitacion->Visible) { // idhabitacion ?>
		<th><span id="elh_internado_idhabitacion" class="internado_idhabitacion"><?php echo $internado->idhabitacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($internado->idcuenta->Visible) { // idcuenta ?>
		<th><span id="elh_internado_idcuenta" class="internado_idcuenta"><?php echo $internado->idcuenta->FldCaption() ?></span></th>
<?php } ?>
<?php if ($internado->costo_diario->Visible) { // costo_diario ?>
		<th><span id="elh_internado_costo_diario" class="internado_costo_diario"><?php echo $internado->costo_diario->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$internado_delete->RecCnt = 0;
$i = 0;
while (!$internado_delete->Recordset->EOF) {
	$internado_delete->RecCnt++;
	$internado_delete->RowCnt++;

	// Set row properties
	$internado->ResetAttrs();
	$internado->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$internado_delete->LoadRowValues($internado_delete->Recordset);

	// Render row
	$internado_delete->RenderRow();
?>
	<tr<?php echo $internado->RowAttributes() ?>>
<?php if ($internado->idinternado->Visible) { // idinternado ?>
		<td<?php echo $internado->idinternado->CellAttributes() ?>>
<span id="el<?php echo $internado_delete->RowCnt ?>_internado_idinternado" class="form-group internado_idinternado">
<span<?php echo $internado->idinternado->ViewAttributes() ?>>
<?php echo $internado->idinternado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($internado->fecha_inicio->Visible) { // fecha_inicio ?>
		<td<?php echo $internado->fecha_inicio->CellAttributes() ?>>
<span id="el<?php echo $internado_delete->RowCnt ?>_internado_fecha_inicio" class="form-group internado_fecha_inicio">
<span<?php echo $internado->fecha_inicio->ViewAttributes() ?>>
<?php echo $internado->fecha_inicio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($internado->fecha_final->Visible) { // fecha_final ?>
		<td<?php echo $internado->fecha_final->CellAttributes() ?>>
<span id="el<?php echo $internado_delete->RowCnt ?>_internado_fecha_final" class="form-group internado_fecha_final">
<span<?php echo $internado->fecha_final->ViewAttributes() ?>>
<?php echo $internado->fecha_final->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($internado->estado->Visible) { // estado ?>
		<td<?php echo $internado->estado->CellAttributes() ?>>
<span id="el<?php echo $internado_delete->RowCnt ?>_internado_estado" class="form-group internado_estado">
<span<?php echo $internado->estado->ViewAttributes() ?>>
<?php echo $internado->estado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($internado->fecha->Visible) { // fecha ?>
		<td<?php echo $internado->fecha->CellAttributes() ?>>
<span id="el<?php echo $internado_delete->RowCnt ?>_internado_fecha" class="form-group internado_fecha">
<span<?php echo $internado->fecha->ViewAttributes() ?>>
<?php echo $internado->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($internado->idhabitacion->Visible) { // idhabitacion ?>
		<td<?php echo $internado->idhabitacion->CellAttributes() ?>>
<span id="el<?php echo $internado_delete->RowCnt ?>_internado_idhabitacion" class="form-group internado_idhabitacion">
<span<?php echo $internado->idhabitacion->ViewAttributes() ?>>
<?php echo $internado->idhabitacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($internado->idcuenta->Visible) { // idcuenta ?>
		<td<?php echo $internado->idcuenta->CellAttributes() ?>>
<span id="el<?php echo $internado_delete->RowCnt ?>_internado_idcuenta" class="form-group internado_idcuenta">
<span<?php echo $internado->idcuenta->ViewAttributes() ?>>
<?php echo $internado->idcuenta->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($internado->costo_diario->Visible) { // costo_diario ?>
		<td<?php echo $internado->costo_diario->CellAttributes() ?>>
<span id="el<?php echo $internado_delete->RowCnt ?>_internado_costo_diario" class="form-group internado_costo_diario">
<span<?php echo $internado->costo_diario->ViewAttributes() ?>>
<?php echo $internado->costo_diario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$internado_delete->Recordset->MoveNext();
}
$internado_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
finternadodelete.Init();
</script>
<?php
$internado_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$internado_delete->Page_Terminate();
?>

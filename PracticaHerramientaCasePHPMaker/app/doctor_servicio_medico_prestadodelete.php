<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "doctor_servicio_medico_prestadoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$doctor_servicio_medico_prestado_delete = NULL; // Initialize page object first

class cdoctor_servicio_medico_prestado_delete extends cdoctor_servicio_medico_prestado {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'doctor_servicio_medico_prestado';

	// Page object name
	var $PageObjName = 'doctor_servicio_medico_prestado_delete';

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

		// Table object (doctor_servicio_medico_prestado)
		if (!isset($GLOBALS["doctor_servicio_medico_prestado"]) || get_class($GLOBALS["doctor_servicio_medico_prestado"]) == "cdoctor_servicio_medico_prestado") {
			$GLOBALS["doctor_servicio_medico_prestado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["doctor_servicio_medico_prestado"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'doctor_servicio_medico_prestado', TRUE);

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
		$this->iddoctor_servicio_medico_prestado->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $doctor_servicio_medico_prestado;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($doctor_servicio_medico_prestado);
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
			$this->Page_Terminate("doctor_servicio_medico_prestadolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in doctor_servicio_medico_prestado class, doctor_servicio_medico_prestadoinfo.php

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
		$this->iddoctor_servicio_medico_prestado->setDbValue($rs->fields('iddoctor_servicio_medico_prestado'));
		$this->iddoctor->setDbValue($rs->fields('iddoctor'));
		$this->idservicio_medico_prestado->setDbValue($rs->fields('idservicio_medico_prestado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->iddoctor_servicio_medico_prestado->DbValue = $row['iddoctor_servicio_medico_prestado'];
		$this->iddoctor->DbValue = $row['iddoctor'];
		$this->idservicio_medico_prestado->DbValue = $row['idservicio_medico_prestado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// iddoctor_servicio_medico_prestado
		// iddoctor
		// idservicio_medico_prestado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// iddoctor_servicio_medico_prestado
			$this->iddoctor_servicio_medico_prestado->ViewValue = $this->iddoctor_servicio_medico_prestado->CurrentValue;
			$this->iddoctor_servicio_medico_prestado->ViewCustomAttributes = "";

			// iddoctor
			$this->iddoctor->ViewValue = $this->iddoctor->CurrentValue;
			$this->iddoctor->ViewCustomAttributes = "";

			// idservicio_medico_prestado
			$this->idservicio_medico_prestado->ViewValue = $this->idservicio_medico_prestado->CurrentValue;
			$this->idservicio_medico_prestado->ViewCustomAttributes = "";

			// iddoctor_servicio_medico_prestado
			$this->iddoctor_servicio_medico_prestado->LinkCustomAttributes = "";
			$this->iddoctor_servicio_medico_prestado->HrefValue = "";
			$this->iddoctor_servicio_medico_prestado->TooltipValue = "";

			// iddoctor
			$this->iddoctor->LinkCustomAttributes = "";
			$this->iddoctor->HrefValue = "";
			$this->iddoctor->TooltipValue = "";

			// idservicio_medico_prestado
			$this->idservicio_medico_prestado->LinkCustomAttributes = "";
			$this->idservicio_medico_prestado->HrefValue = "";
			$this->idservicio_medico_prestado->TooltipValue = "";
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
				$sThisKey .= $row['iddoctor_servicio_medico_prestado'];
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
		$Breadcrumb->Add("list", $this->TableVar, "doctor_servicio_medico_prestadolist.php", "", $this->TableVar, TRUE);
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
if (!isset($doctor_servicio_medico_prestado_delete)) $doctor_servicio_medico_prestado_delete = new cdoctor_servicio_medico_prestado_delete();

// Page init
$doctor_servicio_medico_prestado_delete->Page_Init();

// Page main
$doctor_servicio_medico_prestado_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$doctor_servicio_medico_prestado_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var doctor_servicio_medico_prestado_delete = new ew_Page("doctor_servicio_medico_prestado_delete");
doctor_servicio_medico_prestado_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = doctor_servicio_medico_prestado_delete.PageID; // For backward compatibility

// Form object
var fdoctor_servicio_medico_prestadodelete = new ew_Form("fdoctor_servicio_medico_prestadodelete");

// Form_CustomValidate event
fdoctor_servicio_medico_prestadodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdoctor_servicio_medico_prestadodelete.ValidateRequired = true;
<?php } else { ?>
fdoctor_servicio_medico_prestadodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($doctor_servicio_medico_prestado_delete->Recordset = $doctor_servicio_medico_prestado_delete->LoadRecordset())
	$doctor_servicio_medico_prestado_deleteTotalRecs = $doctor_servicio_medico_prestado_delete->Recordset->RecordCount(); // Get record count
if ($doctor_servicio_medico_prestado_deleteTotalRecs <= 0) { // No record found, exit
	if ($doctor_servicio_medico_prestado_delete->Recordset)
		$doctor_servicio_medico_prestado_delete->Recordset->Close();
	$doctor_servicio_medico_prestado_delete->Page_Terminate("doctor_servicio_medico_prestadolist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $doctor_servicio_medico_prestado_delete->ShowPageHeader(); ?>
<?php
$doctor_servicio_medico_prestado_delete->ShowMessage();
?>
<form name="fdoctor_servicio_medico_prestadodelete" id="fdoctor_servicio_medico_prestadodelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($doctor_servicio_medico_prestado_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $doctor_servicio_medico_prestado_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="doctor_servicio_medico_prestado">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($doctor_servicio_medico_prestado_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $doctor_servicio_medico_prestado->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->Visible) { // iddoctor_servicio_medico_prestado ?>
		<th><span id="elh_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado" class="doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado"><?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->iddoctor->Visible) { // iddoctor ?>
		<th><span id="elh_doctor_servicio_medico_prestado_iddoctor" class="doctor_servicio_medico_prestado_iddoctor"><?php echo $doctor_servicio_medico_prestado->iddoctor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->Visible) { // idservicio_medico_prestado ?>
		<th><span id="elh_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="doctor_servicio_medico_prestado_idservicio_medico_prestado"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$doctor_servicio_medico_prestado_delete->RecCnt = 0;
$i = 0;
while (!$doctor_servicio_medico_prestado_delete->Recordset->EOF) {
	$doctor_servicio_medico_prestado_delete->RecCnt++;
	$doctor_servicio_medico_prestado_delete->RowCnt++;

	// Set row properties
	$doctor_servicio_medico_prestado->ResetAttrs();
	$doctor_servicio_medico_prestado->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$doctor_servicio_medico_prestado_delete->LoadRowValues($doctor_servicio_medico_prestado_delete->Recordset);

	// Render row
	$doctor_servicio_medico_prestado_delete->RenderRow();
?>
	<tr<?php echo $doctor_servicio_medico_prestado->RowAttributes() ?>>
<?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->Visible) { // iddoctor_servicio_medico_prestado ?>
		<td<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->CellAttributes() ?>>
<span id="el<?php echo $doctor_servicio_medico_prestado_delete->RowCnt ?>_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado">
<span<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->iddoctor->Visible) { // iddoctor ?>
		<td<?php echo $doctor_servicio_medico_prestado->iddoctor->CellAttributes() ?>>
<span id="el<?php echo $doctor_servicio_medico_prestado_delete->RowCnt ?>_doctor_servicio_medico_prestado_iddoctor" class="form-group doctor_servicio_medico_prestado_iddoctor">
<span<?php echo $doctor_servicio_medico_prestado->iddoctor->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->iddoctor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->Visible) { // idservicio_medico_prestado ?>
		<td<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->CellAttributes() ?>>
<span id="el<?php echo $doctor_servicio_medico_prestado_delete->RowCnt ?>_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="form-group doctor_servicio_medico_prestado_idservicio_medico_prestado">
<span<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$doctor_servicio_medico_prestado_delete->Recordset->MoveNext();
}
$doctor_servicio_medico_prestado_delete->Recordset->Close();
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
fdoctor_servicio_medico_prestadodelete.Init();
</script>
<?php
$doctor_servicio_medico_prestado_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$doctor_servicio_medico_prestado_delete->Page_Terminate();
?>

<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "doctor_servicio_medico_prestadoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "servicio_medico_prestadoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$doctor_servicio_medico_prestado_view = NULL; // Initialize page object first

class cdoctor_servicio_medico_prestado_view extends cdoctor_servicio_medico_prestado {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'doctor_servicio_medico_prestado';

	// Page object name
	var $PageObjName = 'doctor_servicio_medico_prestado_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["iddoctor_servicio_medico_prestado"] <> "") {
			$this->RecKey["iddoctor_servicio_medico_prestado"] = $_GET["iddoctor_servicio_medico_prestado"];
			$KeyUrl .= "&amp;iddoctor_servicio_medico_prestado=" . urlencode($this->RecKey["iddoctor_servicio_medico_prestado"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (servicio_medico_prestado)
		if (!isset($GLOBALS['servicio_medico_prestado'])) $GLOBALS['servicio_medico_prestado'] = new cservicio_medico_prestado();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'doctor_servicio_medico_prestado', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["iddoctor_servicio_medico_prestado"] <> "") {
				$this->iddoctor_servicio_medico_prestado->setQueryStringValue($_GET["iddoctor_servicio_medico_prestado"]);
				$this->RecKey["iddoctor_servicio_medico_prestado"] = $this->iddoctor_servicio_medico_prestado->QueryStringValue;
			} else {
				$sReturnUrl = "doctor_servicio_medico_prestadolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "doctor_servicio_medico_prestadolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "doctor_servicio_medico_prestadolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "");

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
			if (strval($this->iddoctor->CurrentValue) <> "") {
				$sFilterWrk = "`iddoctor`" . ew_SearchString("=", $this->iddoctor->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->iddoctor, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->iddoctor->ViewValue = $rswrk->fields('DispFld');
					$this->iddoctor->ViewValue .= ew_ValueSeparator(2,$this->iddoctor) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->iddoctor->ViewValue = $this->iddoctor->CurrentValue;
				}
			} else {
				$this->iddoctor->ViewValue = NULL;
			}
			$this->iddoctor->ViewCustomAttributes = "";

			// idservicio_medico_prestado
			$this->idservicio_medico_prestado->ViewValue = $this->idservicio_medico_prestado->CurrentValue;
			if (strval($this->idservicio_medico_prestado->CurrentValue) <> "") {
				$sFilterWrk = "`idservicio_medico_prestado`" . ew_SearchString("=", $this->idservicio_medico_prestado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idservicio_medico_prestado`, `idservicio_medico_prestado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `servicio_medico_prestado`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idservicio_medico_prestado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idservicio_medico_prestado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idservicio_medico_prestado->ViewValue = $this->idservicio_medico_prestado->CurrentValue;
				}
			} else {
				$this->idservicio_medico_prestado->ViewValue = NULL;
			}
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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "servicio_medico_prestado") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idservicio_medico_prestado"] <> "") {
					$GLOBALS["servicio_medico_prestado"]->idservicio_medico_prestado->setQueryStringValue($_GET["fk_idservicio_medico_prestado"]);
					$this->idservicio_medico_prestado->setQueryStringValue($GLOBALS["servicio_medico_prestado"]->idservicio_medico_prestado->QueryStringValue);
					$this->idservicio_medico_prestado->setSessionValue($this->idservicio_medico_prestado->QueryStringValue);
					if (!is_numeric($GLOBALS["servicio_medico_prestado"]->idservicio_medico_prestado->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "servicio_medico_prestado") {
				if ($this->idservicio_medico_prestado->QueryStringValue == "") $this->idservicio_medico_prestado->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "doctor_servicio_medico_prestadolist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, ew_CurrentUrl());
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($doctor_servicio_medico_prestado_view)) $doctor_servicio_medico_prestado_view = new cdoctor_servicio_medico_prestado_view();

// Page init
$doctor_servicio_medico_prestado_view->Page_Init();

// Page main
$doctor_servicio_medico_prestado_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$doctor_servicio_medico_prestado_view->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var doctor_servicio_medico_prestado_view = new ew_Page("doctor_servicio_medico_prestado_view");
doctor_servicio_medico_prestado_view.PageID = "view"; // Page ID
var EW_PAGE_ID = doctor_servicio_medico_prestado_view.PageID; // For backward compatibility

// Form object
var fdoctor_servicio_medico_prestadoview = new ew_Form("fdoctor_servicio_medico_prestadoview");

// Form_CustomValidate event
fdoctor_servicio_medico_prestadoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdoctor_servicio_medico_prestadoview.ValidateRequired = true;
<?php } else { ?>
fdoctor_servicio_medico_prestadoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdoctor_servicio_medico_prestadoview.Lists["x_iddoctor"] = {"LinkField":"x_iddoctor","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdoctor_servicio_medico_prestadoview.Lists["x_idservicio_medico_prestado"] = {"LinkField":"x_idservicio_medico_prestado","Ajax":true,"AutoFill":false,"DisplayFields":["x_idservicio_medico_prestado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $doctor_servicio_medico_prestado_view->ExportOptions->Render("body") ?>
<?php
	foreach ($doctor_servicio_medico_prestado_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $doctor_servicio_medico_prestado_view->ShowPageHeader(); ?>
<?php
$doctor_servicio_medico_prestado_view->ShowMessage();
?>
<form name="fdoctor_servicio_medico_prestadoview" id="fdoctor_servicio_medico_prestadoview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($doctor_servicio_medico_prestado_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $doctor_servicio_medico_prestado_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="doctor_servicio_medico_prestado">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->Visible) { // iddoctor_servicio_medico_prestado ?>
	<tr id="r_iddoctor_servicio_medico_prestado">
		<td><span id="elh_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado"><?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->FldCaption() ?></span></td>
		<td<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->CellAttributes() ?>>
<span id="el_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado" class="form-group">
<span<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->iddoctor->Visible) { // iddoctor ?>
	<tr id="r_iddoctor">
		<td><span id="elh_doctor_servicio_medico_prestado_iddoctor"><?php echo $doctor_servicio_medico_prestado->iddoctor->FldCaption() ?></span></td>
		<td<?php echo $doctor_servicio_medico_prestado->iddoctor->CellAttributes() ?>>
<span id="el_doctor_servicio_medico_prestado_iddoctor" class="form-group">
<span<?php echo $doctor_servicio_medico_prestado->iddoctor->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->iddoctor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->Visible) { // idservicio_medico_prestado ?>
	<tr id="r_idservicio_medico_prestado">
		<td><span id="elh_doctor_servicio_medico_prestado_idservicio_medico_prestado"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->FldCaption() ?></span></td>
		<td<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->CellAttributes() ?>>
<span id="el_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="form-group">
<span<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fdoctor_servicio_medico_prestadoview.Init();
</script>
<?php
$doctor_servicio_medico_prestado_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$doctor_servicio_medico_prestado_view->Page_Terminate();
?>

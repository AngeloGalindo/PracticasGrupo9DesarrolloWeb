<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "servicio_medico_prestadoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cuentainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "servicio_medicoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "doctor_servicio_medico_prestadogridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$servicio_medico_prestado_view = NULL; // Initialize page object first

class cservicio_medico_prestado_view extends cservicio_medico_prestado {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'servicio_medico_prestado';

	// Page object name
	var $PageObjName = 'servicio_medico_prestado_view';

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

		// Table object (servicio_medico_prestado)
		if (!isset($GLOBALS["servicio_medico_prestado"]) || get_class($GLOBALS["servicio_medico_prestado"]) == "cservicio_medico_prestado") {
			$GLOBALS["servicio_medico_prestado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["servicio_medico_prestado"];
		}
		$KeyUrl = "";
		if (@$_GET["idservicio_medico_prestado"] <> "") {
			$this->RecKey["idservicio_medico_prestado"] = $_GET["idservicio_medico_prestado"];
			$KeyUrl .= "&amp;idservicio_medico_prestado=" . urlencode($this->RecKey["idservicio_medico_prestado"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (cuenta)
		if (!isset($GLOBALS['cuenta'])) $GLOBALS['cuenta'] = new ccuenta();

		// Table object (servicio_medico)
		if (!isset($GLOBALS['servicio_medico'])) $GLOBALS['servicio_medico'] = new cservicio_medico();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'servicio_medico_prestado', TRUE);

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
		$this->idservicio_medico_prestado->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

			// Process auto fill for detail table 'doctor_servicio_medico_prestado'
			if (@$_POST["grid"] == "fdoctor_servicio_medico_prestadogrid") {
				if (!isset($GLOBALS["doctor_servicio_medico_prestado_grid"])) $GLOBALS["doctor_servicio_medico_prestado_grid"] = new cdoctor_servicio_medico_prestado_grid;
				$GLOBALS["doctor_servicio_medico_prestado_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $servicio_medico_prestado;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($servicio_medico_prestado);
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
			if (@$_GET["idservicio_medico_prestado"] <> "") {
				$this->idservicio_medico_prestado->setQueryStringValue($_GET["idservicio_medico_prestado"]);
				$this->RecKey["idservicio_medico_prestado"] = $this->idservicio_medico_prestado->QueryStringValue;
			} else {
				$sReturnUrl = "servicio_medico_prestadolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "servicio_medico_prestadolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "servicio_medico_prestadolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
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

		// Show detail edit/copy
		if ($this->getCurrentDetailTable() <> "") {

			// Detail Edit
			$item = &$option->Add("detailedit");
			$item->Body = "<a class=\"ewAction ewDetailEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable())) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a>";
			$item->Visible = (TRUE);

			// Detail Copy
			$item = &$option->Add("detailcopy");
			$item->Body = "<a class=\"ewAction ewDetailCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable())) . "\">" . $Language->Phrase("MasterDetailCopyLink") . "</a>";
			$item->Visible = (TRUE);
		}
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_doctor_servicio_medico_prestado"
		$item = &$option->Add("detail_doctor_servicio_medico_prestado");
		$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("doctor_servicio_medico_prestado", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("doctor_servicio_medico_prestadolist.php?" . EW_TABLE_SHOW_MASTER . "=servicio_medico_prestado&fk_idservicio_medico_prestado=" . strval($this->idservicio_medico_prestado->CurrentValue) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["doctor_servicio_medico_prestado_grid"] && $GLOBALS["doctor_servicio_medico_prestado_grid"]->DetailView) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=doctor_servicio_medico_prestado")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "doctor_servicio_medico_prestado";
		}
		if ($GLOBALS["doctor_servicio_medico_prestado_grid"] && $GLOBALS["doctor_servicio_medico_prestado_grid"]->DetailEdit) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=doctor_servicio_medico_prestado")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "doctor_servicio_medico_prestado";
		}
		if ($GLOBALS["doctor_servicio_medico_prestado_grid"] && $GLOBALS["doctor_servicio_medico_prestado_grid"]->DetailAdd) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=doctor_servicio_medico_prestado")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "doctor_servicio_medico_prestado";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = TRUE;
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "doctor_servicio_medico_prestado";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		$this->idservicio_medico_prestado->setDbValue($rs->fields('idservicio_medico_prestado'));
		$this->idcuenta->setDbValue($rs->fields('idcuenta'));
		$this->idservicio_medico->setDbValue($rs->fields('idservicio_medico'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->costo->setDbValue($rs->fields('costo'));
		$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
		$this->fecha_final->setDbValue($rs->fields('fecha_final'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idservicio_medico_prestado->DbValue = $row['idservicio_medico_prestado'];
		$this->idcuenta->DbValue = $row['idcuenta'];
		$this->idservicio_medico->DbValue = $row['idservicio_medico'];
		$this->estado->DbValue = $row['estado'];
		$this->costo->DbValue = $row['costo'];
		$this->fecha_inicio->DbValue = $row['fecha_inicio'];
		$this->fecha_final->DbValue = $row['fecha_final'];
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

		// Convert decimal values if posted back
		if ($this->costo->FormValue == $this->costo->CurrentValue && is_numeric(ew_StrToFloat($this->costo->CurrentValue)))
			$this->costo->CurrentValue = ew_StrToFloat($this->costo->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idservicio_medico_prestado
		// idcuenta
		// idservicio_medico
		// estado
		// costo
		// fecha_inicio
		// fecha_final

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idservicio_medico_prestado
			$this->idservicio_medico_prestado->ViewValue = $this->idservicio_medico_prestado->CurrentValue;
			$this->idservicio_medico_prestado->ViewCustomAttributes = "";

			// idcuenta
			if (strval($this->idcuenta->CurrentValue) <> "") {
				$sFilterWrk = "`idcuenta`" . ew_SearchString("=", $this->idcuenta->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idcuenta, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idcuenta->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idcuenta->ViewValue = $this->idcuenta->CurrentValue;
				}
			} else {
				$this->idcuenta->ViewValue = NULL;
			}
			$this->idcuenta->ViewCustomAttributes = "";

			// idservicio_medico
			if (strval($this->idservicio_medico->CurrentValue) <> "") {
				$sFilterWrk = "`idservicio_medico`" . ew_SearchString("=", $this->idservicio_medico->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idservicio_medico`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `servicio_medico`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idservicio_medico, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idservicio_medico->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idservicio_medico->ViewValue = $this->idservicio_medico->CurrentValue;
				}
			} else {
				$this->idservicio_medico->ViewValue = NULL;
			}
			$this->idservicio_medico->ViewCustomAttributes = "";

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

			// costo
			$this->costo->ViewValue = $this->costo->CurrentValue;
			$this->costo->ViewCustomAttributes = "";

			// fecha_inicio
			$this->fecha_inicio->ViewValue = $this->fecha_inicio->CurrentValue;
			$this->fecha_inicio->ViewValue = ew_FormatDateTime($this->fecha_inicio->ViewValue, 7);
			$this->fecha_inicio->ViewCustomAttributes = "";

			// fecha_final
			$this->fecha_final->ViewValue = $this->fecha_final->CurrentValue;
			$this->fecha_final->ViewValue = ew_FormatDateTime($this->fecha_final->ViewValue, 7);
			$this->fecha_final->ViewCustomAttributes = "";

			// idservicio_medico_prestado
			$this->idservicio_medico_prestado->LinkCustomAttributes = "";
			$this->idservicio_medico_prestado->HrefValue = "";
			$this->idservicio_medico_prestado->TooltipValue = "";

			// idcuenta
			$this->idcuenta->LinkCustomAttributes = "";
			$this->idcuenta->HrefValue = "";
			$this->idcuenta->TooltipValue = "";

			// idservicio_medico
			$this->idservicio_medico->LinkCustomAttributes = "";
			$this->idservicio_medico->HrefValue = "";
			$this->idservicio_medico->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// costo
			$this->costo->LinkCustomAttributes = "";
			$this->costo->HrefValue = "";
			$this->costo->TooltipValue = "";

			// fecha_inicio
			$this->fecha_inicio->LinkCustomAttributes = "";
			$this->fecha_inicio->HrefValue = "";
			$this->fecha_inicio->TooltipValue = "";

			// fecha_final
			$this->fecha_final->LinkCustomAttributes = "";
			$this->fecha_final->HrefValue = "";
			$this->fecha_final->TooltipValue = "";
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
			if ($sMasterTblVar == "cuenta") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idcuenta"] <> "") {
					$GLOBALS["cuenta"]->idcuenta->setQueryStringValue($_GET["fk_idcuenta"]);
					$this->idcuenta->setQueryStringValue($GLOBALS["cuenta"]->idcuenta->QueryStringValue);
					$this->idcuenta->setSessionValue($this->idcuenta->QueryStringValue);
					if (!is_numeric($GLOBALS["cuenta"]->idcuenta->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "servicio_medico") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idservicio_medico"] <> "") {
					$GLOBALS["servicio_medico"]->idservicio_medico->setQueryStringValue($_GET["fk_idservicio_medico"]);
					$this->idservicio_medico->setQueryStringValue($GLOBALS["servicio_medico"]->idservicio_medico->QueryStringValue);
					$this->idservicio_medico->setSessionValue($this->idservicio_medico->QueryStringValue);
					if (!is_numeric($GLOBALS["servicio_medico"]->idservicio_medico->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "cuenta") {
				if ($this->idcuenta->QueryStringValue == "") $this->idcuenta->setSessionValue("");
			}
			if ($sMasterTblVar <> "servicio_medico") {
				if ($this->idservicio_medico->QueryStringValue == "") $this->idservicio_medico->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("doctor_servicio_medico_prestado", $DetailTblVar)) {
				if (!isset($GLOBALS["doctor_servicio_medico_prestado_grid"]))
					$GLOBALS["doctor_servicio_medico_prestado_grid"] = new cdoctor_servicio_medico_prestado_grid;
				if ($GLOBALS["doctor_servicio_medico_prestado_grid"]->DetailView) {
					$GLOBALS["doctor_servicio_medico_prestado_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["doctor_servicio_medico_prestado_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["doctor_servicio_medico_prestado_grid"]->setStartRecordNumber(1);
					$GLOBALS["doctor_servicio_medico_prestado_grid"]->idservicio_medico_prestado->FldIsDetailKey = TRUE;
					$GLOBALS["doctor_servicio_medico_prestado_grid"]->idservicio_medico_prestado->CurrentValue = $this->idservicio_medico_prestado->CurrentValue;
					$GLOBALS["doctor_servicio_medico_prestado_grid"]->idservicio_medico_prestado->setSessionValue($GLOBALS["doctor_servicio_medico_prestado_grid"]->idservicio_medico_prestado->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "servicio_medico_prestadolist.php", "", $this->TableVar, TRUE);
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
if (!isset($servicio_medico_prestado_view)) $servicio_medico_prestado_view = new cservicio_medico_prestado_view();

// Page init
$servicio_medico_prestado_view->Page_Init();

// Page main
$servicio_medico_prestado_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$servicio_medico_prestado_view->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var servicio_medico_prestado_view = new ew_Page("servicio_medico_prestado_view");
servicio_medico_prestado_view.PageID = "view"; // Page ID
var EW_PAGE_ID = servicio_medico_prestado_view.PageID; // For backward compatibility

// Form object
var fservicio_medico_prestadoview = new ew_Form("fservicio_medico_prestadoview");

// Form_CustomValidate event
fservicio_medico_prestadoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fservicio_medico_prestadoview.ValidateRequired = true;
<?php } else { ?>
fservicio_medico_prestadoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fservicio_medico_prestadoview.Lists["x_idcuenta"] = {"LinkField":"x_idcuenta","Ajax":true,"AutoFill":false,"DisplayFields":["x_idcuenta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fservicio_medico_prestadoview.Lists["x_idservicio_medico"] = {"LinkField":"x_idservicio_medico","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $servicio_medico_prestado_view->ExportOptions->Render("body") ?>
<?php
	foreach ($servicio_medico_prestado_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $servicio_medico_prestado_view->ShowPageHeader(); ?>
<?php
$servicio_medico_prestado_view->ShowMessage();
?>
<form name="fservicio_medico_prestadoview" id="fservicio_medico_prestadoview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($servicio_medico_prestado_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $servicio_medico_prestado_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="servicio_medico_prestado">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($servicio_medico_prestado->idservicio_medico_prestado->Visible) { // idservicio_medico_prestado ?>
	<tr id="r_idservicio_medico_prestado">
		<td><span id="elh_servicio_medico_prestado_idservicio_medico_prestado"><?php echo $servicio_medico_prestado->idservicio_medico_prestado->FldCaption() ?></span></td>
		<td<?php echo $servicio_medico_prestado->idservicio_medico_prestado->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_idservicio_medico_prestado" class="form-group">
<span<?php echo $servicio_medico_prestado->idservicio_medico_prestado->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->idservicio_medico_prestado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->idcuenta->Visible) { // idcuenta ?>
	<tr id="r_idcuenta">
		<td><span id="elh_servicio_medico_prestado_idcuenta"><?php echo $servicio_medico_prestado->idcuenta->FldCaption() ?></span></td>
		<td<?php echo $servicio_medico_prestado->idcuenta->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_idcuenta" class="form-group">
<span<?php echo $servicio_medico_prestado->idcuenta->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->idcuenta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->idservicio_medico->Visible) { // idservicio_medico ?>
	<tr id="r_idservicio_medico">
		<td><span id="elh_servicio_medico_prestado_idservicio_medico"><?php echo $servicio_medico_prestado->idservicio_medico->FldCaption() ?></span></td>
		<td<?php echo $servicio_medico_prestado->idservicio_medico->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_idservicio_medico" class="form-group">
<span<?php echo $servicio_medico_prestado->idservicio_medico->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->idservicio_medico->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_servicio_medico_prestado_estado"><?php echo $servicio_medico_prestado->estado->FldCaption() ?></span></td>
		<td<?php echo $servicio_medico_prestado->estado->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_estado" class="form-group">
<span<?php echo $servicio_medico_prestado->estado->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->estado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->costo->Visible) { // costo ?>
	<tr id="r_costo">
		<td><span id="elh_servicio_medico_prestado_costo"><?php echo $servicio_medico_prestado->costo->FldCaption() ?></span></td>
		<td<?php echo $servicio_medico_prestado->costo->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_costo" class="form-group">
<span<?php echo $servicio_medico_prestado->costo->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->costo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->fecha_inicio->Visible) { // fecha_inicio ?>
	<tr id="r_fecha_inicio">
		<td><span id="elh_servicio_medico_prestado_fecha_inicio"><?php echo $servicio_medico_prestado->fecha_inicio->FldCaption() ?></span></td>
		<td<?php echo $servicio_medico_prestado->fecha_inicio->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_fecha_inicio" class="form-group">
<span<?php echo $servicio_medico_prestado->fecha_inicio->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->fecha_inicio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($servicio_medico_prestado->fecha_final->Visible) { // fecha_final ?>
	<tr id="r_fecha_final">
		<td><span id="elh_servicio_medico_prestado_fecha_final"><?php echo $servicio_medico_prestado->fecha_final->FldCaption() ?></span></td>
		<td<?php echo $servicio_medico_prestado->fecha_final->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_fecha_final" class="form-group">
<span<?php echo $servicio_medico_prestado->fecha_final->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->fecha_final->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php
	if (in_array("doctor_servicio_medico_prestado", explode(",", $servicio_medico_prestado->getCurrentDetailTable())) && $doctor_servicio_medico_prestado->DetailView) {
?>
<?php if ($servicio_medico_prestado->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("doctor_servicio_medico_prestado", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "doctor_servicio_medico_prestadogrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fservicio_medico_prestadoview.Init();
</script>
<?php
$servicio_medico_prestado_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$servicio_medico_prestado_view->Page_Terminate();
?>

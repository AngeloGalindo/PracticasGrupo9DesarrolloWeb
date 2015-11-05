<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "internadoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "habitacioninfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "pacienteinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "internado_diariogridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$internado_view = NULL; // Initialize page object first

class cinternado_view extends cinternado {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'internado';

	// Page object name
	var $PageObjName = 'internado_view';

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

		// Table object (internado)
		if (!isset($GLOBALS["internado"]) || get_class($GLOBALS["internado"]) == "cinternado") {
			$GLOBALS["internado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["internado"];
		}
		$KeyUrl = "";
		if (@$_GET["idinternado"] <> "") {
			$this->RecKey["idinternado"] = $_GET["idinternado"];
			$KeyUrl .= "&amp;idinternado=" . urlencode($this->RecKey["idinternado"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (habitacion)
		if (!isset($GLOBALS['habitacion'])) $GLOBALS['habitacion'] = new chabitacion();

		// Table object (paciente)
		if (!isset($GLOBALS['paciente'])) $GLOBALS['paciente'] = new cpaciente();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'internado', TRUE);

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

			// Process auto fill for detail table 'internado_diario'
			if (@$_POST["grid"] == "finternado_diariogrid") {
				if (!isset($GLOBALS["internado_diario_grid"])) $GLOBALS["internado_diario_grid"] = new cinternado_diario_grid;
				$GLOBALS["internado_diario_grid"]->Page_Init();
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
			if (@$_GET["idinternado"] <> "") {
				$this->idinternado->setQueryStringValue($_GET["idinternado"]);
				$this->RecKey["idinternado"] = $this->idinternado->QueryStringValue;
			} else {
				$sReturnUrl = "internadolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "internadolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "internadolist.php"; // Not page request, return to list
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

		// "detail_internado_diario"
		$item = &$option->Add("detail_internado_diario");
		$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("internado_diario", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("internado_diariolist.php?" . EW_TABLE_SHOW_MASTER . "=internado&fk_idinternado=" . strval($this->idinternado->CurrentValue) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["internado_diario_grid"] && $GLOBALS["internado_diario_grid"]->DetailView) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=internado_diario")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "internado_diario";
		}
		if ($GLOBALS["internado_diario_grid"] && $GLOBALS["internado_diario_grid"]->DetailEdit) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=internado_diario")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "internado_diario";
		}
		if ($GLOBALS["internado_diario_grid"] && $GLOBALS["internado_diario_grid"]->DetailAdd) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=internado_diario")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "internado_diario";
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
			$DetailTableLink .= "internado_diario";
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
		$this->idinternado->setDbValue($rs->fields('idinternado'));
		$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
		$this->fecha_final->setDbValue($rs->fields('fecha_final'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->idhabitacion->setDbValue($rs->fields('idhabitacion'));
		$this->idpaciente->setDbValue($rs->fields('idpaciente'));
		$this->es_operacion->setDbValue($rs->fields('es_operacion'));
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
		$this->idpaciente->DbValue = $row['idpaciente'];
		$this->es_operacion->DbValue = $row['es_operacion'];
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
		// idinternado
		// fecha_inicio
		// fecha_final
		// estado
		// fecha
		// idhabitacion
		// idpaciente
		// es_operacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idinternado
			$this->idinternado->ViewValue = $this->idinternado->CurrentValue;
			$this->idinternado->ViewCustomAttributes = "";

			// fecha_inicio
			$this->fecha_inicio->ViewValue = $this->fecha_inicio->CurrentValue;
			$this->fecha_inicio->ViewValue = ew_FormatDateTime($this->fecha_inicio->ViewValue, 7);
			$this->fecha_inicio->ViewCustomAttributes = "";

			// fecha_final
			$this->fecha_final->ViewValue = $this->fecha_final->CurrentValue;
			$this->fecha_final->ViewValue = ew_FormatDateTime($this->fecha_final->ViewValue, 7);
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
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// idhabitacion
			if (strval($this->idhabitacion->CurrentValue) <> "") {
				$sFilterWrk = "`idhabitacion`" . ew_SearchString("=", $this->idhabitacion->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idhabitacion`, `numero` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `habitacion`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idhabitacion, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idhabitacion->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idhabitacion->ViewValue = $this->idhabitacion->CurrentValue;
				}
			} else {
				$this->idhabitacion->ViewValue = NULL;
			}
			$this->idhabitacion->ViewCustomAttributes = "";

			// idpaciente
			if (strval($this->idpaciente->CurrentValue) <> "") {
				$sFilterWrk = "`idpaciente`" . ew_SearchString("=", $this->idpaciente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idpaciente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idpaciente->ViewValue = $rswrk->fields('DispFld');
					$this->idpaciente->ViewValue .= ew_ValueSeparator(2,$this->idpaciente) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->idpaciente->ViewValue = $this->idpaciente->CurrentValue;
				}
			} else {
				$this->idpaciente->ViewValue = NULL;
			}
			$this->idpaciente->ViewCustomAttributes = "";

			// es_operacion
			if (strval($this->es_operacion->CurrentValue) <> "") {
				switch ($this->es_operacion->CurrentValue) {
					case $this->es_operacion->FldTagValue(1):
						$this->es_operacion->ViewValue = $this->es_operacion->FldTagCaption(1) <> "" ? $this->es_operacion->FldTagCaption(1) : $this->es_operacion->CurrentValue;
						break;
					case $this->es_operacion->FldTagValue(2):
						$this->es_operacion->ViewValue = $this->es_operacion->FldTagCaption(2) <> "" ? $this->es_operacion->FldTagCaption(2) : $this->es_operacion->CurrentValue;
						break;
					default:
						$this->es_operacion->ViewValue = $this->es_operacion->CurrentValue;
				}
			} else {
				$this->es_operacion->ViewValue = NULL;
			}
			$this->es_operacion->ViewCustomAttributes = "";

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

			// idpaciente
			$this->idpaciente->LinkCustomAttributes = "";
			$this->idpaciente->HrefValue = "";
			$this->idpaciente->TooltipValue = "";

			// es_operacion
			$this->es_operacion->LinkCustomAttributes = "";
			$this->es_operacion->HrefValue = "";
			$this->es_operacion->TooltipValue = "";
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
			if ($sMasterTblVar == "habitacion") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idhabitacion"] <> "") {
					$GLOBALS["habitacion"]->idhabitacion->setQueryStringValue($_GET["fk_idhabitacion"]);
					$this->idhabitacion->setQueryStringValue($GLOBALS["habitacion"]->idhabitacion->QueryStringValue);
					$this->idhabitacion->setSessionValue($this->idhabitacion->QueryStringValue);
					if (!is_numeric($GLOBALS["habitacion"]->idhabitacion->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "paciente") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idpaciente"] <> "") {
					$GLOBALS["paciente"]->idpaciente->setQueryStringValue($_GET["fk_idpaciente"]);
					$this->idpaciente->setQueryStringValue($GLOBALS["paciente"]->idpaciente->QueryStringValue);
					$this->idpaciente->setSessionValue($this->idpaciente->QueryStringValue);
					if (!is_numeric($GLOBALS["paciente"]->idpaciente->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "habitacion") {
				if ($this->idhabitacion->QueryStringValue == "") $this->idhabitacion->setSessionValue("");
			}
			if ($sMasterTblVar <> "paciente") {
				if ($this->idpaciente->QueryStringValue == "") $this->idpaciente->setSessionValue("");
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
			if (in_array("internado_diario", $DetailTblVar)) {
				if (!isset($GLOBALS["internado_diario_grid"]))
					$GLOBALS["internado_diario_grid"] = new cinternado_diario_grid;
				if ($GLOBALS["internado_diario_grid"]->DetailView) {
					$GLOBALS["internado_diario_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["internado_diario_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["internado_diario_grid"]->setStartRecordNumber(1);
					$GLOBALS["internado_diario_grid"]->idinternado->FldIsDetailKey = TRUE;
					$GLOBALS["internado_diario_grid"]->idinternado->CurrentValue = $this->idinternado->CurrentValue;
					$GLOBALS["internado_diario_grid"]->idinternado->setSessionValue($GLOBALS["internado_diario_grid"]->idinternado->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "internadolist.php", "", $this->TableVar, TRUE);
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
if (!isset($internado_view)) $internado_view = new cinternado_view();

// Page init
$internado_view->Page_Init();

// Page main
$internado_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$internado_view->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var internado_view = new ew_Page("internado_view");
internado_view.PageID = "view"; // Page ID
var EW_PAGE_ID = internado_view.PageID; // For backward compatibility

// Form object
var finternadoview = new ew_Form("finternadoview");

// Form_CustomValidate event
finternadoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finternadoview.ValidateRequired = true;
<?php } else { ?>
finternadoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
finternadoview.Lists["x_idhabitacion"] = {"LinkField":"x_idhabitacion","Ajax":true,"AutoFill":false,"DisplayFields":["x_numero","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
finternadoview.Lists["x_idpaciente"] = {"LinkField":"x_idpaciente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $internado_view->ExportOptions->Render("body") ?>
<?php
	foreach ($internado_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $internado_view->ShowPageHeader(); ?>
<?php
$internado_view->ShowMessage();
?>
<form name="finternadoview" id="finternadoview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($internado_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $internado_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="internado">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($internado->idinternado->Visible) { // idinternado ?>
	<tr id="r_idinternado">
		<td><span id="elh_internado_idinternado"><?php echo $internado->idinternado->FldCaption() ?></span></td>
		<td<?php echo $internado->idinternado->CellAttributes() ?>>
<span id="el_internado_idinternado" class="form-group">
<span<?php echo $internado->idinternado->ViewAttributes() ?>>
<?php echo $internado->idinternado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($internado->fecha_inicio->Visible) { // fecha_inicio ?>
	<tr id="r_fecha_inicio">
		<td><span id="elh_internado_fecha_inicio"><?php echo $internado->fecha_inicio->FldCaption() ?></span></td>
		<td<?php echo $internado->fecha_inicio->CellAttributes() ?>>
<span id="el_internado_fecha_inicio" class="form-group">
<span<?php echo $internado->fecha_inicio->ViewAttributes() ?>>
<?php echo $internado->fecha_inicio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($internado->fecha_final->Visible) { // fecha_final ?>
	<tr id="r_fecha_final">
		<td><span id="elh_internado_fecha_final"><?php echo $internado->fecha_final->FldCaption() ?></span></td>
		<td<?php echo $internado->fecha_final->CellAttributes() ?>>
<span id="el_internado_fecha_final" class="form-group">
<span<?php echo $internado->fecha_final->ViewAttributes() ?>>
<?php echo $internado->fecha_final->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($internado->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_internado_estado"><?php echo $internado->estado->FldCaption() ?></span></td>
		<td<?php echo $internado->estado->CellAttributes() ?>>
<span id="el_internado_estado" class="form-group">
<span<?php echo $internado->estado->ViewAttributes() ?>>
<?php echo $internado->estado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($internado->fecha->Visible) { // fecha ?>
	<tr id="r_fecha">
		<td><span id="elh_internado_fecha"><?php echo $internado->fecha->FldCaption() ?></span></td>
		<td<?php echo $internado->fecha->CellAttributes() ?>>
<span id="el_internado_fecha" class="form-group">
<span<?php echo $internado->fecha->ViewAttributes() ?>>
<?php echo $internado->fecha->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($internado->idhabitacion->Visible) { // idhabitacion ?>
	<tr id="r_idhabitacion">
		<td><span id="elh_internado_idhabitacion"><?php echo $internado->idhabitacion->FldCaption() ?></span></td>
		<td<?php echo $internado->idhabitacion->CellAttributes() ?>>
<span id="el_internado_idhabitacion" class="form-group">
<span<?php echo $internado->idhabitacion->ViewAttributes() ?>>
<?php echo $internado->idhabitacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($internado->idpaciente->Visible) { // idpaciente ?>
	<tr id="r_idpaciente">
		<td><span id="elh_internado_idpaciente"><?php echo $internado->idpaciente->FldCaption() ?></span></td>
		<td<?php echo $internado->idpaciente->CellAttributes() ?>>
<span id="el_internado_idpaciente" class="form-group">
<span<?php echo $internado->idpaciente->ViewAttributes() ?>>
<?php echo $internado->idpaciente->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($internado->es_operacion->Visible) { // es_operacion ?>
	<tr id="r_es_operacion">
		<td><span id="elh_internado_es_operacion"><?php echo $internado->es_operacion->FldCaption() ?></span></td>
		<td<?php echo $internado->es_operacion->CellAttributes() ?>>
<span id="el_internado_es_operacion" class="form-group">
<span<?php echo $internado->es_operacion->ViewAttributes() ?>>
<?php echo $internado->es_operacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php
	if (in_array("internado_diario", explode(",", $internado->getCurrentDetailTable())) && $internado_diario->DetailView) {
?>
<?php if ($internado->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("internado_diario", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "internado_diariogrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
finternadoview.Init();
</script>
<?php
$internado_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$internado_view->Page_Terminate();
?>

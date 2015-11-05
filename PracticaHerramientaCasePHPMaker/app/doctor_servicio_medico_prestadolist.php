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

$doctor_servicio_medico_prestado_list = NULL; // Initialize page object first

class cdoctor_servicio_medico_prestado_list extends cdoctor_servicio_medico_prestado {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'doctor_servicio_medico_prestado';

	// Page object name
	var $PageObjName = 'doctor_servicio_medico_prestado_list';

	// Grid form hidden field names
	var $FormName = 'fdoctor_servicio_medico_prestadolist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "doctor_servicio_medico_prestadoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "doctor_servicio_medico_prestadodelete.php";
		$this->MultiUpdateUrl = "doctor_servicio_medico_prestadoupdate.php";

		// Table object (servicio_medico_prestado)
		if (!isset($GLOBALS['servicio_medico_prestado'])) $GLOBALS['servicio_medico_prestado'] = new cservicio_medico_prestado();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'doctor_servicio_medico_prestado', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up master detail parameters
			$this->SetUpMasterParms();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "servicio_medico_prestado") {
			global $servicio_medico_prestado;
			$rsmaster = $servicio_medico_prestado->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("servicio_medico_prestadolist.php"); // Return to master page
			} else {
				$servicio_medico_prestado->LoadListRowValues($rsmaster);
				$servicio_medico_prestado->RowType = EW_ROWTYPE_MASTER; // Master row
				$servicio_medico_prestado->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount();
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->iddoctor_servicio_medico_prestado->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->iddoctor_servicio_medico_prestado->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->iddoctor_servicio_medico_prestado); // iddoctor_servicio_medico_prestado
			$this->UpdateSort($this->iddoctor); // iddoctor
			$this->UpdateSort($this->idservicio_medico_prestado); // idservicio_medico_prestado
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->idservicio_medico_prestado->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->iddoctor_servicio_medico_prestado->setSort("");
				$this->iddoctor->setSort("");
				$this->idservicio_medico_prestado->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->iddoctor_servicio_medico_prestado->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fdoctor_servicio_medico_prestadolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("iddoctor_servicio_medico_prestado")) <> "")
			$this->iddoctor_servicio_medico_prestado->CurrentValue = $this->getKey("iddoctor_servicio_medico_prestado"); // iddoctor_servicio_medico_prestado
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($doctor_servicio_medico_prestado_list)) $doctor_servicio_medico_prestado_list = new cdoctor_servicio_medico_prestado_list();

// Page init
$doctor_servicio_medico_prestado_list->Page_Init();

// Page main
$doctor_servicio_medico_prestado_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$doctor_servicio_medico_prestado_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var doctor_servicio_medico_prestado_list = new ew_Page("doctor_servicio_medico_prestado_list");
doctor_servicio_medico_prestado_list.PageID = "list"; // Page ID
var EW_PAGE_ID = doctor_servicio_medico_prestado_list.PageID; // For backward compatibility

// Form object
var fdoctor_servicio_medico_prestadolist = new ew_Form("fdoctor_servicio_medico_prestadolist");
fdoctor_servicio_medico_prestadolist.FormKeyCountName = '<?php echo $doctor_servicio_medico_prestado_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdoctor_servicio_medico_prestadolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdoctor_servicio_medico_prestadolist.ValidateRequired = true;
<?php } else { ?>
fdoctor_servicio_medico_prestadolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdoctor_servicio_medico_prestadolist.Lists["x_iddoctor"] = {"LinkField":"x_iddoctor","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdoctor_servicio_medico_prestadolist.Lists["x_idservicio_medico_prestado"] = {"LinkField":"x_idservicio_medico_prestado","Ajax":true,"AutoFill":false,"DisplayFields":["x_idservicio_medico_prestado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($doctor_servicio_medico_prestado_list->TotalRecs > 0 && $doctor_servicio_medico_prestado->getCurrentMasterTable() == "" && $doctor_servicio_medico_prestado_list->ExportOptions->Visible()) { ?>
<?php $doctor_servicio_medico_prestado_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php if (($doctor_servicio_medico_prestado->Export == "") || (EW_EXPORT_MASTER_RECORD && $doctor_servicio_medico_prestado->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "servicio_medico_prestadolist.php";
if ($doctor_servicio_medico_prestado_list->DbMasterFilter <> "" && $doctor_servicio_medico_prestado->getCurrentMasterTable() == "servicio_medico_prestado") {
	if ($doctor_servicio_medico_prestado_list->MasterRecordExists) {
		if ($doctor_servicio_medico_prestado->getCurrentMasterTable() == $doctor_servicio_medico_prestado->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($doctor_servicio_medico_prestado_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $doctor_servicio_medico_prestado_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "servicio_medico_prestadomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$doctor_servicio_medico_prestado_list->TotalRecs = $doctor_servicio_medico_prestado->SelectRecordCount();
	} else {
		if ($doctor_servicio_medico_prestado_list->Recordset = $doctor_servicio_medico_prestado_list->LoadRecordset())
			$doctor_servicio_medico_prestado_list->TotalRecs = $doctor_servicio_medico_prestado_list->Recordset->RecordCount();
	}
	$doctor_servicio_medico_prestado_list->StartRec = 1;
	if ($doctor_servicio_medico_prestado_list->DisplayRecs <= 0 || ($doctor_servicio_medico_prestado->Export <> "" && $doctor_servicio_medico_prestado->ExportAll)) // Display all records
		$doctor_servicio_medico_prestado_list->DisplayRecs = $doctor_servicio_medico_prestado_list->TotalRecs;
	if (!($doctor_servicio_medico_prestado->Export <> "" && $doctor_servicio_medico_prestado->ExportAll))
		$doctor_servicio_medico_prestado_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$doctor_servicio_medico_prestado_list->Recordset = $doctor_servicio_medico_prestado_list->LoadRecordset($doctor_servicio_medico_prestado_list->StartRec-1, $doctor_servicio_medico_prestado_list->DisplayRecs);

	// Set no record found message
	if ($doctor_servicio_medico_prestado->CurrentAction == "" && $doctor_servicio_medico_prestado_list->TotalRecs == 0) {
		if ($doctor_servicio_medico_prestado_list->SearchWhere == "0=101")
			$doctor_servicio_medico_prestado_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$doctor_servicio_medico_prestado_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$doctor_servicio_medico_prestado_list->RenderOtherOptions();
?>
<?php $doctor_servicio_medico_prestado_list->ShowPageHeader(); ?>
<?php
$doctor_servicio_medico_prestado_list->ShowMessage();
?>
<?php if ($doctor_servicio_medico_prestado_list->TotalRecs > 0 || $doctor_servicio_medico_prestado->CurrentAction <> "") { ?>
<div class="ewGrid">
<form name="fdoctor_servicio_medico_prestadolist" id="fdoctor_servicio_medico_prestadolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($doctor_servicio_medico_prestado_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $doctor_servicio_medico_prestado_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="doctor_servicio_medico_prestado">
<div id="gmp_doctor_servicio_medico_prestado" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($doctor_servicio_medico_prestado_list->TotalRecs > 0) { ?>
<table id="tbl_doctor_servicio_medico_prestadolist" class="table ewTable">
<?php echo $doctor_servicio_medico_prestado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$doctor_servicio_medico_prestado_list->RenderListOptions();

// Render list options (header, left)
$doctor_servicio_medico_prestado_list->ListOptions->Render("header", "left");
?>
<?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->Visible) { // iddoctor_servicio_medico_prestado ?>
	<?php if ($doctor_servicio_medico_prestado->SortUrl($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado) == "") { ?>
		<th data-name="iddoctor_servicio_medico_prestado"><div id="elh_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado" class="doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado"><div class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iddoctor_servicio_medico_prestado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $doctor_servicio_medico_prestado->SortUrl($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado) ?>',1);"><div id="elh_doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado" class="doctor_servicio_medico_prestado_iddoctor_servicio_medico_prestado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor_servicio_medico_prestado->iddoctor->Visible) { // iddoctor ?>
	<?php if ($doctor_servicio_medico_prestado->SortUrl($doctor_servicio_medico_prestado->iddoctor) == "") { ?>
		<th data-name="iddoctor"><div id="elh_doctor_servicio_medico_prestado_iddoctor" class="doctor_servicio_medico_prestado_iddoctor"><div class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->iddoctor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="iddoctor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $doctor_servicio_medico_prestado->SortUrl($doctor_servicio_medico_prestado->iddoctor) ?>',1);"><div id="elh_doctor_servicio_medico_prestado_iddoctor" class="doctor_servicio_medico_prestado_iddoctor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->iddoctor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor_servicio_medico_prestado->iddoctor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor_servicio_medico_prestado->iddoctor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->Visible) { // idservicio_medico_prestado ?>
	<?php if ($doctor_servicio_medico_prestado->SortUrl($doctor_servicio_medico_prestado->idservicio_medico_prestado) == "") { ?>
		<th data-name="idservicio_medico_prestado"><div id="elh_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="doctor_servicio_medico_prestado_idservicio_medico_prestado"><div class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idservicio_medico_prestado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $doctor_servicio_medico_prestado->SortUrl($doctor_servicio_medico_prestado->idservicio_medico_prestado) ?>',1);"><div id="elh_doctor_servicio_medico_prestado_idservicio_medico_prestado" class="doctor_servicio_medico_prestado_idservicio_medico_prestado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($doctor_servicio_medico_prestado->idservicio_medico_prestado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$doctor_servicio_medico_prestado_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($doctor_servicio_medico_prestado->ExportAll && $doctor_servicio_medico_prestado->Export <> "") {
	$doctor_servicio_medico_prestado_list->StopRec = $doctor_servicio_medico_prestado_list->TotalRecs;
} else {

	// Set the last record to display
	if ($doctor_servicio_medico_prestado_list->TotalRecs > $doctor_servicio_medico_prestado_list->StartRec + $doctor_servicio_medico_prestado_list->DisplayRecs - 1)
		$doctor_servicio_medico_prestado_list->StopRec = $doctor_servicio_medico_prestado_list->StartRec + $doctor_servicio_medico_prestado_list->DisplayRecs - 1;
	else
		$doctor_servicio_medico_prestado_list->StopRec = $doctor_servicio_medico_prestado_list->TotalRecs;
}
$doctor_servicio_medico_prestado_list->RecCnt = $doctor_servicio_medico_prestado_list->StartRec - 1;
if ($doctor_servicio_medico_prestado_list->Recordset && !$doctor_servicio_medico_prestado_list->Recordset->EOF) {
	$doctor_servicio_medico_prestado_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $doctor_servicio_medico_prestado_list->StartRec > 1)
		$doctor_servicio_medico_prestado_list->Recordset->Move($doctor_servicio_medico_prestado_list->StartRec - 1);
} elseif (!$doctor_servicio_medico_prestado->AllowAddDeleteRow && $doctor_servicio_medico_prestado_list->StopRec == 0) {
	$doctor_servicio_medico_prestado_list->StopRec = $doctor_servicio_medico_prestado->GridAddRowCount;
}

// Initialize aggregate
$doctor_servicio_medico_prestado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$doctor_servicio_medico_prestado->ResetAttrs();
$doctor_servicio_medico_prestado_list->RenderRow();
while ($doctor_servicio_medico_prestado_list->RecCnt < $doctor_servicio_medico_prestado_list->StopRec) {
	$doctor_servicio_medico_prestado_list->RecCnt++;
	if (intval($doctor_servicio_medico_prestado_list->RecCnt) >= intval($doctor_servicio_medico_prestado_list->StartRec)) {
		$doctor_servicio_medico_prestado_list->RowCnt++;

		// Set up key count
		$doctor_servicio_medico_prestado_list->KeyCount = $doctor_servicio_medico_prestado_list->RowIndex;

		// Init row class and style
		$doctor_servicio_medico_prestado->ResetAttrs();
		$doctor_servicio_medico_prestado->CssClass = "";
		if ($doctor_servicio_medico_prestado->CurrentAction == "gridadd") {
		} else {
			$doctor_servicio_medico_prestado_list->LoadRowValues($doctor_servicio_medico_prestado_list->Recordset); // Load row values
		}
		$doctor_servicio_medico_prestado->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$doctor_servicio_medico_prestado->RowAttrs = array_merge($doctor_servicio_medico_prestado->RowAttrs, array('data-rowindex'=>$doctor_servicio_medico_prestado_list->RowCnt, 'id'=>'r' . $doctor_servicio_medico_prestado_list->RowCnt . '_doctor_servicio_medico_prestado', 'data-rowtype'=>$doctor_servicio_medico_prestado->RowType));

		// Render row
		$doctor_servicio_medico_prestado_list->RenderRow();

		// Render list options
		$doctor_servicio_medico_prestado_list->RenderListOptions();
?>
	<tr<?php echo $doctor_servicio_medico_prestado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$doctor_servicio_medico_prestado_list->ListOptions->Render("body", "left", $doctor_servicio_medico_prestado_list->RowCnt);
?>
	<?php if ($doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->Visible) { // iddoctor_servicio_medico_prestado ?>
		<td data-name="iddoctor_servicio_medico_prestado"<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->CellAttributes() ?>>
<span<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->iddoctor_servicio_medico_prestado->ListViewValue() ?></span>
<a id="<?php echo $doctor_servicio_medico_prestado_list->PageObjName . "_row_" . $doctor_servicio_medico_prestado_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($doctor_servicio_medico_prestado->iddoctor->Visible) { // iddoctor ?>
		<td data-name="iddoctor"<?php echo $doctor_servicio_medico_prestado->iddoctor->CellAttributes() ?>>
<span<?php echo $doctor_servicio_medico_prestado->iddoctor->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->iddoctor->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($doctor_servicio_medico_prestado->idservicio_medico_prestado->Visible) { // idservicio_medico_prestado ?>
		<td data-name="idservicio_medico_prestado"<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->CellAttributes() ?>>
<span<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ViewAttributes() ?>>
<?php echo $doctor_servicio_medico_prestado->idservicio_medico_prestado->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$doctor_servicio_medico_prestado_list->ListOptions->Render("body", "right", $doctor_servicio_medico_prestado_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($doctor_servicio_medico_prestado->CurrentAction <> "gridadd")
		$doctor_servicio_medico_prestado_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($doctor_servicio_medico_prestado_list->Recordset)
	$doctor_servicio_medico_prestado_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($doctor_servicio_medico_prestado->CurrentAction <> "gridadd" && $doctor_servicio_medico_prestado->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($doctor_servicio_medico_prestado_list->Pager)) $doctor_servicio_medico_prestado_list->Pager = new cPrevNextPager($doctor_servicio_medico_prestado_list->StartRec, $doctor_servicio_medico_prestado_list->DisplayRecs, $doctor_servicio_medico_prestado_list->TotalRecs) ?>
<?php if ($doctor_servicio_medico_prestado_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($doctor_servicio_medico_prestado_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $doctor_servicio_medico_prestado_list->PageUrl() ?>start=<?php echo $doctor_servicio_medico_prestado_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($doctor_servicio_medico_prestado_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $doctor_servicio_medico_prestado_list->PageUrl() ?>start=<?php echo $doctor_servicio_medico_prestado_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $doctor_servicio_medico_prestado_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($doctor_servicio_medico_prestado_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $doctor_servicio_medico_prestado_list->PageUrl() ?>start=<?php echo $doctor_servicio_medico_prestado_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($doctor_servicio_medico_prestado_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $doctor_servicio_medico_prestado_list->PageUrl() ?>start=<?php echo $doctor_servicio_medico_prestado_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $doctor_servicio_medico_prestado_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $doctor_servicio_medico_prestado_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $doctor_servicio_medico_prestado_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $doctor_servicio_medico_prestado_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($doctor_servicio_medico_prestado_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($doctor_servicio_medico_prestado_list->TotalRecs == 0 && $doctor_servicio_medico_prestado->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($doctor_servicio_medico_prestado_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fdoctor_servicio_medico_prestadolist.Init();
</script>
<?php
$doctor_servicio_medico_prestado_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$doctor_servicio_medico_prestado_list->Page_Terminate();
?>

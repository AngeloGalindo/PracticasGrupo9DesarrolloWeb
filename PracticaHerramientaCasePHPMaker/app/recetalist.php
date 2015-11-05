<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "recetainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "empleadoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "turnoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "medicinainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$receta_list = NULL; // Initialize page object first

class creceta_list extends creceta {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'receta';

	// Page object name
	var $PageObjName = 'receta_list';

	// Grid form hidden field names
	var $FormName = 'frecetalist';
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

		// Table object (receta)
		if (!isset($GLOBALS["receta"]) || get_class($GLOBALS["receta"]) == "creceta") {
			$GLOBALS["receta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["receta"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "recetaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "recetadelete.php";
		$this->MultiUpdateUrl = "recetaupdate.php";

		// Table object (empleado)
		if (!isset($GLOBALS['empleado'])) $GLOBALS['empleado'] = new cempleado();

		// Table object (turno)
		if (!isset($GLOBALS['turno'])) $GLOBALS['turno'] = new cturno();

		// Table object (medicina)
		if (!isset($GLOBALS['medicina'])) $GLOBALS['medicina'] = new cmedicina();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'receta', TRUE);

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
		$this->idreceta->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $receta;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($receta);
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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "medicina") {
			global $medicina;
			$rsmaster = $medicina->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("medicinalist.php"); // Return to master page
			} else {
				$medicina->LoadListRowValues($rsmaster);
				$medicina->RowType = EW_ROWTYPE_MASTER; // Master row
				$medicina->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "empleado") {
			global $empleado;
			$rsmaster = $empleado->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("empleadolist.php"); // Return to master page
			} else {
				$empleado->LoadListRowValues($rsmaster);
				$empleado->RowType = EW_ROWTYPE_MASTER; // Master row
				$empleado->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "turno") {
			global $turno;
			$rsmaster = $turno->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("turnolist.php"); // Return to master page
			} else {
				$turno->LoadListRowValues($rsmaster);
				$turno->RowType = EW_ROWTYPE_MASTER; // Master row
				$turno->RenderListRow();
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
			$this->idreceta->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idreceta->FormValue))
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
			$this->UpdateSort($this->idreceta); // idreceta
			$this->UpdateSort($this->idempleado); // idempleado
			$this->UpdateSort($this->idmedicina); // idmedicina
			$this->UpdateSort($this->fecha); // fecha
			$this->UpdateSort($this->cantidad); // cantidad
			$this->UpdateSort($this->precio_unitario); // precio_unitario
			$this->UpdateSort($this->idturno); // idturno
			$this->UpdateSort($this->idcuenta); // idcuenta
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
				$this->idmedicina->setSessionValue("");
				$this->idempleado->setSessionValue("");
				$this->idturno->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idreceta->setSort("");
				$this->idempleado->setSort("");
				$this->idmedicina->setSort("");
				$this->fecha->setSort("");
				$this->cantidad->setSort("");
				$this->precio_unitario->setSort("");
				$this->idturno->setSort("");
				$this->idcuenta->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->idreceta->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.frecetalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->idreceta->setDbValue($rs->fields('idreceta'));
		$this->idempleado->setDbValue($rs->fields('idempleado'));
		$this->idmedicina->setDbValue($rs->fields('idmedicina'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->precio_unitario->setDbValue($rs->fields('precio_unitario'));
		$this->idturno->setDbValue($rs->fields('idturno'));
		$this->idcuenta->setDbValue($rs->fields('idcuenta'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idreceta->DbValue = $row['idreceta'];
		$this->idempleado->DbValue = $row['idempleado'];
		$this->idmedicina->DbValue = $row['idmedicina'];
		$this->fecha->DbValue = $row['fecha'];
		$this->cantidad->DbValue = $row['cantidad'];
		$this->precio_unitario->DbValue = $row['precio_unitario'];
		$this->idturno->DbValue = $row['idturno'];
		$this->idcuenta->DbValue = $row['idcuenta'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idreceta")) <> "")
			$this->idreceta->CurrentValue = $this->getKey("idreceta"); // idreceta
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

		// Convert decimal values if posted back
		if ($this->precio_unitario->FormValue == $this->precio_unitario->CurrentValue && is_numeric(ew_StrToFloat($this->precio_unitario->CurrentValue)))
			$this->precio_unitario->CurrentValue = ew_StrToFloat($this->precio_unitario->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idreceta
		// idempleado
		// idmedicina
		// fecha
		// cantidad
		// precio_unitario
		// idturno
		// idcuenta

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idreceta
			$this->idreceta->ViewValue = $this->idreceta->CurrentValue;
			$this->idreceta->ViewCustomAttributes = "";

			// idempleado
			if (strval($this->idempleado->CurrentValue) <> "") {
				$sFilterWrk = "`idempleado`" . ew_SearchString("=", $this->idempleado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idempleado`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idempleado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idempleado->ViewValue = $rswrk->fields('DispFld');
					$this->idempleado->ViewValue .= ew_ValueSeparator(1,$this->idempleado) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->idempleado->ViewValue = $this->idempleado->CurrentValue;
				}
			} else {
				$this->idempleado->ViewValue = NULL;
			}
			$this->idempleado->ViewCustomAttributes = "";

			// idmedicina
			if (strval($this->idmedicina->CurrentValue) <> "") {
				$sFilterWrk = "`idmedicina`" . ew_SearchString("=", $this->idmedicina->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idmedicina`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `medicina`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idmedicina, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idmedicina->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idmedicina->ViewValue = $this->idmedicina->CurrentValue;
				}
			} else {
				$this->idmedicina->ViewValue = NULL;
			}
			$this->idmedicina->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// cantidad
			$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
			$this->cantidad->ViewCustomAttributes = "";

			// precio_unitario
			$this->precio_unitario->ViewValue = $this->precio_unitario->CurrentValue;
			$this->precio_unitario->ViewCustomAttributes = "";

			// idturno
			if (strval($this->idturno->CurrentValue) <> "") {
				$sFilterWrk = "`idturno`" . ew_SearchString("=", $this->idturno->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `turno`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idturno, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idturno->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idturno->ViewValue = $this->idturno->CurrentValue;
				}
			} else {
				$this->idturno->ViewValue = NULL;
			}
			$this->idturno->ViewCustomAttributes = "";

			// idcuenta
			$this->idcuenta->ViewValue = $this->idcuenta->CurrentValue;
			$this->idcuenta->ViewCustomAttributes = "";

			// idreceta
			$this->idreceta->LinkCustomAttributes = "";
			$this->idreceta->HrefValue = "";
			$this->idreceta->TooltipValue = "";

			// idempleado
			$this->idempleado->LinkCustomAttributes = "";
			$this->idempleado->HrefValue = "";
			$this->idempleado->TooltipValue = "";

			// idmedicina
			$this->idmedicina->LinkCustomAttributes = "";
			$this->idmedicina->HrefValue = "";
			$this->idmedicina->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";
			$this->cantidad->TooltipValue = "";

			// precio_unitario
			$this->precio_unitario->LinkCustomAttributes = "";
			$this->precio_unitario->HrefValue = "";
			$this->precio_unitario->TooltipValue = "";

			// idturno
			$this->idturno->LinkCustomAttributes = "";
			$this->idturno->HrefValue = "";
			$this->idturno->TooltipValue = "";

			// idcuenta
			$this->idcuenta->LinkCustomAttributes = "";
			$this->idcuenta->HrefValue = "";
			$this->idcuenta->TooltipValue = "";
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
			if ($sMasterTblVar == "medicina") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idmedicina"] <> "") {
					$GLOBALS["medicina"]->idmedicina->setQueryStringValue($_GET["fk_idmedicina"]);
					$this->idmedicina->setQueryStringValue($GLOBALS["medicina"]->idmedicina->QueryStringValue);
					$this->idmedicina->setSessionValue($this->idmedicina->QueryStringValue);
					if (!is_numeric($GLOBALS["medicina"]->idmedicina->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "empleado") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idempleado"] <> "") {
					$GLOBALS["empleado"]->idempleado->setQueryStringValue($_GET["fk_idempleado"]);
					$this->idempleado->setQueryStringValue($GLOBALS["empleado"]->idempleado->QueryStringValue);
					$this->idempleado->setSessionValue($this->idempleado->QueryStringValue);
					if (!is_numeric($GLOBALS["empleado"]->idempleado->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "turno") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idturno"] <> "") {
					$GLOBALS["turno"]->idturno->setQueryStringValue($_GET["fk_idturno"]);
					$this->idturno->setQueryStringValue($GLOBALS["turno"]->idturno->QueryStringValue);
					$this->idturno->setSessionValue($this->idturno->QueryStringValue);
					if (!is_numeric($GLOBALS["turno"]->idturno->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "medicina") {
				if ($this->idmedicina->QueryStringValue == "") $this->idmedicina->setSessionValue("");
			}
			if ($sMasterTblVar <> "empleado") {
				if ($this->idempleado->QueryStringValue == "") $this->idempleado->setSessionValue("");
			}
			if ($sMasterTblVar <> "turno") {
				if ($this->idturno->QueryStringValue == "") $this->idturno->setSessionValue("");
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
if (!isset($receta_list)) $receta_list = new creceta_list();

// Page init
$receta_list->Page_Init();

// Page main
$receta_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$receta_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var receta_list = new ew_Page("receta_list");
receta_list.PageID = "list"; // Page ID
var EW_PAGE_ID = receta_list.PageID; // For backward compatibility

// Form object
var frecetalist = new ew_Form("frecetalist");
frecetalist.FormKeyCountName = '<?php echo $receta_list->FormKeyCountName ?>';

// Form_CustomValidate event
frecetalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frecetalist.ValidateRequired = true;
<?php } else { ?>
frecetalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frecetalist.Lists["x_idempleado"] = {"LinkField":"x_idempleado","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frecetalist.Lists["x_idmedicina"] = {"LinkField":"x_idmedicina","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frecetalist.Lists["x_idturno"] = {"LinkField":"x_idturno","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($receta_list->TotalRecs > 0 && $receta->getCurrentMasterTable() == "" && $receta_list->ExportOptions->Visible()) { ?>
<?php $receta_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php if (($receta->Export == "") || (EW_EXPORT_MASTER_RECORD && $receta->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "medicinalist.php";
if ($receta_list->DbMasterFilter <> "" && $receta->getCurrentMasterTable() == "medicina") {
	if ($receta_list->MasterRecordExists) {
		if ($receta->getCurrentMasterTable() == $receta->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($receta_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $receta_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "medicinamaster.php" ?>
<?php
	}
}
?>
<?php
$gsMasterReturnUrl = "empleadolist.php";
if ($receta_list->DbMasterFilter <> "" && $receta->getCurrentMasterTable() == "empleado") {
	if ($receta_list->MasterRecordExists) {
		if ($receta->getCurrentMasterTable() == $receta->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($receta_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $receta_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "empleadomaster.php" ?>
<?php
	}
}
?>
<?php
$gsMasterReturnUrl = "turnolist.php";
if ($receta_list->DbMasterFilter <> "" && $receta->getCurrentMasterTable() == "turno") {
	if ($receta_list->MasterRecordExists) {
		if ($receta->getCurrentMasterTable() == $receta->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($receta_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $receta_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "turnomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$receta_list->TotalRecs = $receta->SelectRecordCount();
	} else {
		if ($receta_list->Recordset = $receta_list->LoadRecordset())
			$receta_list->TotalRecs = $receta_list->Recordset->RecordCount();
	}
	$receta_list->StartRec = 1;
	if ($receta_list->DisplayRecs <= 0 || ($receta->Export <> "" && $receta->ExportAll)) // Display all records
		$receta_list->DisplayRecs = $receta_list->TotalRecs;
	if (!($receta->Export <> "" && $receta->ExportAll))
		$receta_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$receta_list->Recordset = $receta_list->LoadRecordset($receta_list->StartRec-1, $receta_list->DisplayRecs);

	// Set no record found message
	if ($receta->CurrentAction == "" && $receta_list->TotalRecs == 0) {
		if ($receta_list->SearchWhere == "0=101")
			$receta_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$receta_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$receta_list->RenderOtherOptions();
?>
<?php $receta_list->ShowPageHeader(); ?>
<?php
$receta_list->ShowMessage();
?>
<?php if ($receta_list->TotalRecs > 0 || $receta->CurrentAction <> "") { ?>
<div class="ewGrid">
<form name="frecetalist" id="frecetalist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($receta_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $receta_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="receta">
<div id="gmp_receta" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($receta_list->TotalRecs > 0) { ?>
<table id="tbl_recetalist" class="table ewTable">
<?php echo $receta->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$receta_list->RenderListOptions();

// Render list options (header, left)
$receta_list->ListOptions->Render("header", "left");
?>
<?php if ($receta->idreceta->Visible) { // idreceta ?>
	<?php if ($receta->SortUrl($receta->idreceta) == "") { ?>
		<th data-name="idreceta"><div id="elh_receta_idreceta" class="receta_idreceta"><div class="ewTableHeaderCaption"><?php echo $receta->idreceta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idreceta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $receta->SortUrl($receta->idreceta) ?>',1);"><div id="elh_receta_idreceta" class="receta_idreceta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idreceta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idreceta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idreceta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->idempleado->Visible) { // idempleado ?>
	<?php if ($receta->SortUrl($receta->idempleado) == "") { ?>
		<th data-name="idempleado"><div id="elh_receta_idempleado" class="receta_idempleado"><div class="ewTableHeaderCaption"><?php echo $receta->idempleado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idempleado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $receta->SortUrl($receta->idempleado) ?>',1);"><div id="elh_receta_idempleado" class="receta_idempleado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idempleado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idempleado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idempleado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->idmedicina->Visible) { // idmedicina ?>
	<?php if ($receta->SortUrl($receta->idmedicina) == "") { ?>
		<th data-name="idmedicina"><div id="elh_receta_idmedicina" class="receta_idmedicina"><div class="ewTableHeaderCaption"><?php echo $receta->idmedicina->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idmedicina"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $receta->SortUrl($receta->idmedicina) ?>',1);"><div id="elh_receta_idmedicina" class="receta_idmedicina">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idmedicina->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idmedicina->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idmedicina->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->fecha->Visible) { // fecha ?>
	<?php if ($receta->SortUrl($receta->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_receta_fecha" class="receta_fecha"><div class="ewTableHeaderCaption"><?php echo $receta->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $receta->SortUrl($receta->fecha) ?>',1);"><div id="elh_receta_fecha" class="receta_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->cantidad->Visible) { // cantidad ?>
	<?php if ($receta->SortUrl($receta->cantidad) == "") { ?>
		<th data-name="cantidad"><div id="elh_receta_cantidad" class="receta_cantidad"><div class="ewTableHeaderCaption"><?php echo $receta->cantidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cantidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $receta->SortUrl($receta->cantidad) ?>',1);"><div id="elh_receta_cantidad" class="receta_cantidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->cantidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->cantidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->cantidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->precio_unitario->Visible) { // precio_unitario ?>
	<?php if ($receta->SortUrl($receta->precio_unitario) == "") { ?>
		<th data-name="precio_unitario"><div id="elh_receta_precio_unitario" class="receta_precio_unitario"><div class="ewTableHeaderCaption"><?php echo $receta->precio_unitario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="precio_unitario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $receta->SortUrl($receta->precio_unitario) ?>',1);"><div id="elh_receta_precio_unitario" class="receta_precio_unitario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->precio_unitario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->precio_unitario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->precio_unitario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->idturno->Visible) { // idturno ?>
	<?php if ($receta->SortUrl($receta->idturno) == "") { ?>
		<th data-name="idturno"><div id="elh_receta_idturno" class="receta_idturno"><div class="ewTableHeaderCaption"><?php echo $receta->idturno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idturno"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $receta->SortUrl($receta->idturno) ?>',1);"><div id="elh_receta_idturno" class="receta_idturno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idturno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idturno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idturno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($receta->idcuenta->Visible) { // idcuenta ?>
	<?php if ($receta->SortUrl($receta->idcuenta) == "") { ?>
		<th data-name="idcuenta"><div id="elh_receta_idcuenta" class="receta_idcuenta"><div class="ewTableHeaderCaption"><?php echo $receta->idcuenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idcuenta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $receta->SortUrl($receta->idcuenta) ?>',1);"><div id="elh_receta_idcuenta" class="receta_idcuenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $receta->idcuenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($receta->idcuenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($receta->idcuenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$receta_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($receta->ExportAll && $receta->Export <> "") {
	$receta_list->StopRec = $receta_list->TotalRecs;
} else {

	// Set the last record to display
	if ($receta_list->TotalRecs > $receta_list->StartRec + $receta_list->DisplayRecs - 1)
		$receta_list->StopRec = $receta_list->StartRec + $receta_list->DisplayRecs - 1;
	else
		$receta_list->StopRec = $receta_list->TotalRecs;
}
$receta_list->RecCnt = $receta_list->StartRec - 1;
if ($receta_list->Recordset && !$receta_list->Recordset->EOF) {
	$receta_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $receta_list->StartRec > 1)
		$receta_list->Recordset->Move($receta_list->StartRec - 1);
} elseif (!$receta->AllowAddDeleteRow && $receta_list->StopRec == 0) {
	$receta_list->StopRec = $receta->GridAddRowCount;
}

// Initialize aggregate
$receta->RowType = EW_ROWTYPE_AGGREGATEINIT;
$receta->ResetAttrs();
$receta_list->RenderRow();
while ($receta_list->RecCnt < $receta_list->StopRec) {
	$receta_list->RecCnt++;
	if (intval($receta_list->RecCnt) >= intval($receta_list->StartRec)) {
		$receta_list->RowCnt++;

		// Set up key count
		$receta_list->KeyCount = $receta_list->RowIndex;

		// Init row class and style
		$receta->ResetAttrs();
		$receta->CssClass = "";
		if ($receta->CurrentAction == "gridadd") {
		} else {
			$receta_list->LoadRowValues($receta_list->Recordset); // Load row values
		}
		$receta->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$receta->RowAttrs = array_merge($receta->RowAttrs, array('data-rowindex'=>$receta_list->RowCnt, 'id'=>'r' . $receta_list->RowCnt . '_receta', 'data-rowtype'=>$receta->RowType));

		// Render row
		$receta_list->RenderRow();

		// Render list options
		$receta_list->RenderListOptions();
?>
	<tr<?php echo $receta->RowAttributes() ?>>
<?php

// Render list options (body, left)
$receta_list->ListOptions->Render("body", "left", $receta_list->RowCnt);
?>
	<?php if ($receta->idreceta->Visible) { // idreceta ?>
		<td data-name="idreceta"<?php echo $receta->idreceta->CellAttributes() ?>>
<span<?php echo $receta->idreceta->ViewAttributes() ?>>
<?php echo $receta->idreceta->ListViewValue() ?></span>
<a id="<?php echo $receta_list->PageObjName . "_row_" . $receta_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($receta->idempleado->Visible) { // idempleado ?>
		<td data-name="idempleado"<?php echo $receta->idempleado->CellAttributes() ?>>
<span<?php echo $receta->idempleado->ViewAttributes() ?>>
<?php echo $receta->idempleado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($receta->idmedicina->Visible) { // idmedicina ?>
		<td data-name="idmedicina"<?php echo $receta->idmedicina->CellAttributes() ?>>
<span<?php echo $receta->idmedicina->ViewAttributes() ?>>
<?php echo $receta->idmedicina->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($receta->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $receta->fecha->CellAttributes() ?>>
<span<?php echo $receta->fecha->ViewAttributes() ?>>
<?php echo $receta->fecha->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($receta->cantidad->Visible) { // cantidad ?>
		<td data-name="cantidad"<?php echo $receta->cantidad->CellAttributes() ?>>
<span<?php echo $receta->cantidad->ViewAttributes() ?>>
<?php echo $receta->cantidad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($receta->precio_unitario->Visible) { // precio_unitario ?>
		<td data-name="precio_unitario"<?php echo $receta->precio_unitario->CellAttributes() ?>>
<span<?php echo $receta->precio_unitario->ViewAttributes() ?>>
<?php echo $receta->precio_unitario->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($receta->idturno->Visible) { // idturno ?>
		<td data-name="idturno"<?php echo $receta->idturno->CellAttributes() ?>>
<span<?php echo $receta->idturno->ViewAttributes() ?>>
<?php echo $receta->idturno->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($receta->idcuenta->Visible) { // idcuenta ?>
		<td data-name="idcuenta"<?php echo $receta->idcuenta->CellAttributes() ?>>
<span<?php echo $receta->idcuenta->ViewAttributes() ?>>
<?php echo $receta->idcuenta->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$receta_list->ListOptions->Render("body", "right", $receta_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($receta->CurrentAction <> "gridadd")
		$receta_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($receta->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($receta_list->Recordset)
	$receta_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($receta->CurrentAction <> "gridadd" && $receta->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($receta_list->Pager)) $receta_list->Pager = new cPrevNextPager($receta_list->StartRec, $receta_list->DisplayRecs, $receta_list->TotalRecs) ?>
<?php if ($receta_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($receta_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $receta_list->PageUrl() ?>start=<?php echo $receta_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($receta_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $receta_list->PageUrl() ?>start=<?php echo $receta_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $receta_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($receta_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $receta_list->PageUrl() ?>start=<?php echo $receta_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($receta_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $receta_list->PageUrl() ?>start=<?php echo $receta_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $receta_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $receta_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $receta_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $receta_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($receta_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($receta_list->TotalRecs == 0 && $receta->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($receta_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
frecetalist.Init();
</script>
<?php
$receta_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$receta_list->Page_Terminate();
?>

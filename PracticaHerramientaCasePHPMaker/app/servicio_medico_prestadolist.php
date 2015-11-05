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

$servicio_medico_prestado_list = NULL; // Initialize page object first

class cservicio_medico_prestado_list extends cservicio_medico_prestado {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'servicio_medico_prestado';

	// Page object name
	var $PageObjName = 'servicio_medico_prestado_list';

	// Grid form hidden field names
	var $FormName = 'fservicio_medico_prestadolist';
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

		// Table object (servicio_medico_prestado)
		if (!isset($GLOBALS["servicio_medico_prestado"]) || get_class($GLOBALS["servicio_medico_prestado"]) == "cservicio_medico_prestado") {
			$GLOBALS["servicio_medico_prestado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["servicio_medico_prestado"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "servicio_medico_prestadoadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "servicio_medico_prestadodelete.php";
		$this->MultiUpdateUrl = "servicio_medico_prestadoupdate.php";

		// Table object (cuenta)
		if (!isset($GLOBALS['cuenta'])) $GLOBALS['cuenta'] = new ccuenta();

		// Table object (servicio_medico)
		if (!isset($GLOBALS['servicio_medico'])) $GLOBALS['servicio_medico'] = new cservicio_medico();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'servicio_medico_prestado', TRUE);

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

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "cuenta") {
			global $cuenta;
			$rsmaster = $cuenta->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("cuentalist.php"); // Return to master page
			} else {
				$cuenta->LoadListRowValues($rsmaster);
				$cuenta->RowType = EW_ROWTYPE_MASTER; // Master row
				$cuenta->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "servicio_medico") {
			global $servicio_medico;
			$rsmaster = $servicio_medico->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("servicio_medicolist.php"); // Return to master page
			} else {
				$servicio_medico->LoadListRowValues($rsmaster);
				$servicio_medico->RowType = EW_ROWTYPE_MASTER; // Master row
				$servicio_medico->RenderListRow();
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
			$this->idservicio_medico_prestado->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idservicio_medico_prestado->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		$this->BuildSearchSql($sWhere, $this->idservicio_medico_prestado, $Default, FALSE); // idservicio_medico_prestado
		$this->BuildSearchSql($sWhere, $this->idcuenta, $Default, FALSE); // idcuenta
		$this->BuildSearchSql($sWhere, $this->idservicio_medico, $Default, FALSE); // idservicio_medico
		$this->BuildSearchSql($sWhere, $this->estado, $Default, FALSE); // estado
		$this->BuildSearchSql($sWhere, $this->costo, $Default, FALSE); // costo
		$this->BuildSearchSql($sWhere, $this->fecha_inicio, $Default, FALSE); // fecha_inicio
		$this->BuildSearchSql($sWhere, $this->fecha_final, $Default, FALSE); // fecha_final

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->idservicio_medico_prestado->AdvancedSearch->Save(); // idservicio_medico_prestado
			$this->idcuenta->AdvancedSearch->Save(); // idcuenta
			$this->idservicio_medico->AdvancedSearch->Save(); // idservicio_medico
			$this->estado->AdvancedSearch->Save(); // estado
			$this->costo->AdvancedSearch->Save(); // costo
			$this->fecha_inicio->AdvancedSearch->Save(); // fecha_inicio
			$this->fecha_final->AdvancedSearch->Save(); // fecha_final
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->idservicio_medico_prestado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idcuenta->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idservicio_medico->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->costo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_inicio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_final->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->idservicio_medico_prestado->AdvancedSearch->UnsetSession();
		$this->idcuenta->AdvancedSearch->UnsetSession();
		$this->idservicio_medico->AdvancedSearch->UnsetSession();
		$this->estado->AdvancedSearch->UnsetSession();
		$this->costo->AdvancedSearch->UnsetSession();
		$this->fecha_inicio->AdvancedSearch->UnsetSession();
		$this->fecha_final->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->idservicio_medico_prestado->AdvancedSearch->Load();
		$this->idcuenta->AdvancedSearch->Load();
		$this->idservicio_medico->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->costo->AdvancedSearch->Load();
		$this->fecha_inicio->AdvancedSearch->Load();
		$this->fecha_final->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idcuenta); // idcuenta
			$this->UpdateSort($this->idservicio_medico); // idservicio_medico
			$this->UpdateSort($this->estado); // estado
			$this->UpdateSort($this->costo); // costo
			$this->UpdateSort($this->fecha_inicio); // fecha_inicio
			$this->UpdateSort($this->fecha_final); // fecha_final
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

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->idcuenta->setSessionValue("");
				$this->idservicio_medico->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idcuenta->setSort("");
				$this->idservicio_medico->setSort("");
				$this->estado->setSort("");
				$this->costo->setSort("");
				$this->fecha_inicio->setSort("");
				$this->fecha_final->setSort("");
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

		// "detail_doctor_servicio_medico_prestado"
		$item = &$this->ListOptions->Add("detail_doctor_servicio_medico_prestado");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["doctor_servicio_medico_prestado_grid"])) $GLOBALS["doctor_servicio_medico_prestado_grid"] = new cdoctor_servicio_medico_prestado_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = FALSE;
			$item->ShowInButtonGroup = FALSE;
		}

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
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_doctor_servicio_medico_prestado"
		$oListOpt = &$this->ListOptions->Items["detail_doctor_servicio_medico_prestado"];
		if (TRUE) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("doctor_servicio_medico_prestado", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("doctor_servicio_medico_prestadolist.php?" . EW_TABLE_SHOW_MASTER . "=servicio_medico_prestado&fk_idservicio_medico_prestado=" . strval($this->idservicio_medico_prestado->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["doctor_servicio_medico_prestado_grid"]->DetailView) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=doctor_servicio_medico_prestado")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "doctor_servicio_medico_prestado";
			}
			if ($GLOBALS["doctor_servicio_medico_prestado_grid"]->DetailEdit) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=doctor_servicio_medico_prestado")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "doctor_servicio_medico_prestado";
			}
			if ($GLOBALS["doctor_servicio_medico_prestado_grid"]->DetailAdd) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=doctor_servicio_medico_prestado")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "doctor_servicio_medico_prestado";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
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
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->idservicio_medico_prestado->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_doctor_servicio_medico_prestado");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=doctor_servicio_medico_prestado") . "\">" . $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["doctor_servicio_medico_prestado"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["doctor_servicio_medico_prestado"]->DetailAdd);
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "doctor_servicio_medico_prestado";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "");

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fservicio_medico_prestadolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fservicio_medico_prestadolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere);

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

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// idservicio_medico_prestado

		$this->idservicio_medico_prestado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idservicio_medico_prestado"]);
		if ($this->idservicio_medico_prestado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idservicio_medico_prestado->AdvancedSearch->SearchOperator = @$_GET["z_idservicio_medico_prestado"];

		// idcuenta
		$this->idcuenta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idcuenta"]);
		if ($this->idcuenta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idcuenta->AdvancedSearch->SearchOperator = @$_GET["z_idcuenta"];

		// idservicio_medico
		$this->idservicio_medico->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idservicio_medico"]);
		if ($this->idservicio_medico->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idservicio_medico->AdvancedSearch->SearchOperator = @$_GET["z_idservicio_medico"];

		// estado
		$this->estado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estado"]);
		if ($this->estado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estado->AdvancedSearch->SearchOperator = @$_GET["z_estado"];

		// costo
		$this->costo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_costo"]);
		if ($this->costo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->costo->AdvancedSearch->SearchOperator = @$_GET["z_costo"];

		// fecha_inicio
		$this->fecha_inicio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_inicio"]);
		if ($this->fecha_inicio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_inicio->AdvancedSearch->SearchOperator = @$_GET["z_fecha_inicio"];

		// fecha_final
		$this->fecha_final->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_final"]);
		if ($this->fecha_final->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_final->AdvancedSearch->SearchOperator = @$_GET["z_fecha_final"];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idservicio_medico_prestado")) <> "")
			$this->idservicio_medico_prestado->CurrentValue = $this->getKey("idservicio_medico_prestado"); // idservicio_medico_prestado
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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// idcuenta
			$this->idcuenta->EditAttrs["class"] = "form-control";
			$this->idcuenta->EditCustomAttributes = "";

			// idservicio_medico
			$this->idservicio_medico->EditAttrs["class"] = "form-control";
			$this->idservicio_medico->EditCustomAttributes = "";

			// estado
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			$arwrk[] = array($this->estado->FldTagValue(3), $this->estado->FldTagCaption(3) <> "" ? $this->estado->FldTagCaption(3) : $this->estado->FldTagValue(3));
			$this->estado->EditValue = $arwrk;

			// costo
			$this->costo->EditAttrs["class"] = "form-control";
			$this->costo->EditCustomAttributes = "";
			$this->costo->EditValue = ew_HtmlEncode($this->costo->AdvancedSearch->SearchValue);
			$this->costo->PlaceHolder = ew_RemoveHtml($this->costo->FldCaption());

			// fecha_inicio
			$this->fecha_inicio->EditAttrs["class"] = "form-control";
			$this->fecha_inicio->EditCustomAttributes = "";
			$this->fecha_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_inicio->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_inicio->PlaceHolder = ew_RemoveHtml($this->fecha_inicio->FldCaption());

			// fecha_final
			$this->fecha_final->EditAttrs["class"] = "form-control";
			$this->fecha_final->EditCustomAttributes = "";
			$this->fecha_final->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_final->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_final->PlaceHolder = ew_RemoveHtml($this->fecha_final->FldCaption());
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->idservicio_medico_prestado->AdvancedSearch->Load();
		$this->idcuenta->AdvancedSearch->Load();
		$this->idservicio_medico->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
		$this->costo->AdvancedSearch->Load();
		$this->fecha_inicio->AdvancedSearch->Load();
		$this->fecha_final->AdvancedSearch->Load();
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
if (!isset($servicio_medico_prestado_list)) $servicio_medico_prestado_list = new cservicio_medico_prestado_list();

// Page init
$servicio_medico_prestado_list->Page_Init();

// Page main
$servicio_medico_prestado_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$servicio_medico_prestado_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var servicio_medico_prestado_list = new ew_Page("servicio_medico_prestado_list");
servicio_medico_prestado_list.PageID = "list"; // Page ID
var EW_PAGE_ID = servicio_medico_prestado_list.PageID; // For backward compatibility

// Form object
var fservicio_medico_prestadolist = new ew_Form("fservicio_medico_prestadolist");
fservicio_medico_prestadolist.FormKeyCountName = '<?php echo $servicio_medico_prestado_list->FormKeyCountName ?>';

// Form_CustomValidate event
fservicio_medico_prestadolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fservicio_medico_prestadolist.ValidateRequired = true;
<?php } else { ?>
fservicio_medico_prestadolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fservicio_medico_prestadolist.Lists["x_idcuenta"] = {"LinkField":"x_idcuenta","Ajax":true,"AutoFill":false,"DisplayFields":["x_idcuenta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fservicio_medico_prestadolist.Lists["x_idservicio_medico"] = {"LinkField":"x_idservicio_medico","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fservicio_medico_prestadolistsrch = new ew_Form("fservicio_medico_prestadolistsrch");

// Validate function for search
fservicio_medico_prestadolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fservicio_medico_prestadolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fservicio_medico_prestadolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fservicio_medico_prestadolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($servicio_medico_prestado_list->TotalRecs > 0 && $servicio_medico_prestado->getCurrentMasterTable() == "" && $servicio_medico_prestado_list->ExportOptions->Visible()) { ?>
<?php $servicio_medico_prestado_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($servicio_medico_prestado_list->SearchOptions->Visible()) { ?>
<?php $servicio_medico_prestado_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php if (($servicio_medico_prestado->Export == "") || (EW_EXPORT_MASTER_RECORD && $servicio_medico_prestado->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "cuentalist.php";
if ($servicio_medico_prestado_list->DbMasterFilter <> "" && $servicio_medico_prestado->getCurrentMasterTable() == "cuenta") {
	if ($servicio_medico_prestado_list->MasterRecordExists) {
		if ($servicio_medico_prestado->getCurrentMasterTable() == $servicio_medico_prestado->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($servicio_medico_prestado_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $servicio_medico_prestado_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "cuentamaster.php" ?>
<?php
	}
}
?>
<?php
$gsMasterReturnUrl = "servicio_medicolist.php";
if ($servicio_medico_prestado_list->DbMasterFilter <> "" && $servicio_medico_prestado->getCurrentMasterTable() == "servicio_medico") {
	if ($servicio_medico_prestado_list->MasterRecordExists) {
		if ($servicio_medico_prestado->getCurrentMasterTable() == $servicio_medico_prestado->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($servicio_medico_prestado_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $servicio_medico_prestado_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "servicio_medicomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$servicio_medico_prestado_list->TotalRecs = $servicio_medico_prestado->SelectRecordCount();
	} else {
		if ($servicio_medico_prestado_list->Recordset = $servicio_medico_prestado_list->LoadRecordset())
			$servicio_medico_prestado_list->TotalRecs = $servicio_medico_prestado_list->Recordset->RecordCount();
	}
	$servicio_medico_prestado_list->StartRec = 1;
	if ($servicio_medico_prestado_list->DisplayRecs <= 0 || ($servicio_medico_prestado->Export <> "" && $servicio_medico_prestado->ExportAll)) // Display all records
		$servicio_medico_prestado_list->DisplayRecs = $servicio_medico_prestado_list->TotalRecs;
	if (!($servicio_medico_prestado->Export <> "" && $servicio_medico_prestado->ExportAll))
		$servicio_medico_prestado_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$servicio_medico_prestado_list->Recordset = $servicio_medico_prestado_list->LoadRecordset($servicio_medico_prestado_list->StartRec-1, $servicio_medico_prestado_list->DisplayRecs);

	// Set no record found message
	if ($servicio_medico_prestado->CurrentAction == "" && $servicio_medico_prestado_list->TotalRecs == 0) {
		if ($servicio_medico_prestado_list->SearchWhere == "0=101")
			$servicio_medico_prestado_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$servicio_medico_prestado_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$servicio_medico_prestado_list->RenderOtherOptions();
?>
<?php if ($servicio_medico_prestado->Export == "" && $servicio_medico_prestado->CurrentAction == "") { ?>
<form name="fservicio_medico_prestadolistsrch" id="fservicio_medico_prestadolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($servicio_medico_prestado_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fservicio_medico_prestadolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="servicio_medico_prestado">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$servicio_medico_prestado_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$servicio_medico_prestado->RowType = EW_ROWTYPE_SEARCH;

// Render row
$servicio_medico_prestado->ResetAttrs();
$servicio_medico_prestado_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($servicio_medico_prestado->estado->Visible) { // estado ?>
	<div id="xsc_estado" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $servicio_medico_prestado->estado->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_estado" id="z_estado" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_estado" id="x_estado" value="{value}"<?php echo $servicio_medico_prestado->estado->EditAttributes() ?>></div>
<div id="dsl_x_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $servicio_medico_prestado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->estado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x_estado" id="x_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $servicio_medico_prestado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $servicio_medico_prestado_list->ShowPageHeader(); ?>
<?php
$servicio_medico_prestado_list->ShowMessage();
?>
<?php if ($servicio_medico_prestado_list->TotalRecs > 0 || $servicio_medico_prestado->CurrentAction <> "") { ?>
<div class="ewGrid">
<form name="fservicio_medico_prestadolist" id="fservicio_medico_prestadolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($servicio_medico_prestado_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $servicio_medico_prestado_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="servicio_medico_prestado">
<div id="gmp_servicio_medico_prestado" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($servicio_medico_prestado_list->TotalRecs > 0) { ?>
<table id="tbl_servicio_medico_prestadolist" class="table ewTable">
<?php echo $servicio_medico_prestado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$servicio_medico_prestado_list->RenderListOptions();

// Render list options (header, left)
$servicio_medico_prestado_list->ListOptions->Render("header", "left");
?>
<?php if ($servicio_medico_prestado->idcuenta->Visible) { // idcuenta ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->idcuenta) == "") { ?>
		<th data-name="idcuenta"><div id="elh_servicio_medico_prestado_idcuenta" class="servicio_medico_prestado_idcuenta"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->idcuenta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idcuenta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicio_medico_prestado->SortUrl($servicio_medico_prestado->idcuenta) ?>',1);"><div id="elh_servicio_medico_prestado_idcuenta" class="servicio_medico_prestado_idcuenta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->idcuenta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->idcuenta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->idcuenta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->idservicio_medico->Visible) { // idservicio_medico ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->idservicio_medico) == "") { ?>
		<th data-name="idservicio_medico"><div id="elh_servicio_medico_prestado_idservicio_medico" class="servicio_medico_prestado_idservicio_medico"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->idservicio_medico->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idservicio_medico"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicio_medico_prestado->SortUrl($servicio_medico_prestado->idservicio_medico) ?>',1);"><div id="elh_servicio_medico_prestado_idservicio_medico" class="servicio_medico_prestado_idservicio_medico">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->idservicio_medico->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->idservicio_medico->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->idservicio_medico->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->estado->Visible) { // estado ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->estado) == "") { ?>
		<th data-name="estado"><div id="elh_servicio_medico_prestado_estado" class="servicio_medico_prestado_estado"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicio_medico_prestado->SortUrl($servicio_medico_prestado->estado) ?>',1);"><div id="elh_servicio_medico_prestado_estado" class="servicio_medico_prestado_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->costo->Visible) { // costo ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->costo) == "") { ?>
		<th data-name="costo"><div id="elh_servicio_medico_prestado_costo" class="servicio_medico_prestado_costo"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->costo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="costo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicio_medico_prestado->SortUrl($servicio_medico_prestado->costo) ?>',1);"><div id="elh_servicio_medico_prestado_costo" class="servicio_medico_prestado_costo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->costo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->costo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->costo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->fecha_inicio->Visible) { // fecha_inicio ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->fecha_inicio) == "") { ?>
		<th data-name="fecha_inicio"><div id="elh_servicio_medico_prestado_fecha_inicio" class="servicio_medico_prestado_fecha_inicio"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->fecha_inicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_inicio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicio_medico_prestado->SortUrl($servicio_medico_prestado->fecha_inicio) ?>',1);"><div id="elh_servicio_medico_prestado_fecha_inicio" class="servicio_medico_prestado_fecha_inicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->fecha_inicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->fecha_inicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->fecha_inicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($servicio_medico_prestado->fecha_final->Visible) { // fecha_final ?>
	<?php if ($servicio_medico_prestado->SortUrl($servicio_medico_prestado->fecha_final) == "") { ?>
		<th data-name="fecha_final"><div id="elh_servicio_medico_prestado_fecha_final" class="servicio_medico_prestado_fecha_final"><div class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->fecha_final->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_final"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $servicio_medico_prestado->SortUrl($servicio_medico_prestado->fecha_final) ?>',1);"><div id="elh_servicio_medico_prestado_fecha_final" class="servicio_medico_prestado_fecha_final">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $servicio_medico_prestado->fecha_final->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($servicio_medico_prestado->fecha_final->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($servicio_medico_prestado->fecha_final->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$servicio_medico_prestado_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($servicio_medico_prestado->ExportAll && $servicio_medico_prestado->Export <> "") {
	$servicio_medico_prestado_list->StopRec = $servicio_medico_prestado_list->TotalRecs;
} else {

	// Set the last record to display
	if ($servicio_medico_prestado_list->TotalRecs > $servicio_medico_prestado_list->StartRec + $servicio_medico_prestado_list->DisplayRecs - 1)
		$servicio_medico_prestado_list->StopRec = $servicio_medico_prestado_list->StartRec + $servicio_medico_prestado_list->DisplayRecs - 1;
	else
		$servicio_medico_prestado_list->StopRec = $servicio_medico_prestado_list->TotalRecs;
}
$servicio_medico_prestado_list->RecCnt = $servicio_medico_prestado_list->StartRec - 1;
if ($servicio_medico_prestado_list->Recordset && !$servicio_medico_prestado_list->Recordset->EOF) {
	$servicio_medico_prestado_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $servicio_medico_prestado_list->StartRec > 1)
		$servicio_medico_prestado_list->Recordset->Move($servicio_medico_prestado_list->StartRec - 1);
} elseif (!$servicio_medico_prestado->AllowAddDeleteRow && $servicio_medico_prestado_list->StopRec == 0) {
	$servicio_medico_prestado_list->StopRec = $servicio_medico_prestado->GridAddRowCount;
}

// Initialize aggregate
$servicio_medico_prestado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$servicio_medico_prestado->ResetAttrs();
$servicio_medico_prestado_list->RenderRow();
while ($servicio_medico_prestado_list->RecCnt < $servicio_medico_prestado_list->StopRec) {
	$servicio_medico_prestado_list->RecCnt++;
	if (intval($servicio_medico_prestado_list->RecCnt) >= intval($servicio_medico_prestado_list->StartRec)) {
		$servicio_medico_prestado_list->RowCnt++;

		// Set up key count
		$servicio_medico_prestado_list->KeyCount = $servicio_medico_prestado_list->RowIndex;

		// Init row class and style
		$servicio_medico_prestado->ResetAttrs();
		$servicio_medico_prestado->CssClass = "";
		if ($servicio_medico_prestado->CurrentAction == "gridadd") {
		} else {
			$servicio_medico_prestado_list->LoadRowValues($servicio_medico_prestado_list->Recordset); // Load row values
		}
		$servicio_medico_prestado->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$servicio_medico_prestado->RowAttrs = array_merge($servicio_medico_prestado->RowAttrs, array('data-rowindex'=>$servicio_medico_prestado_list->RowCnt, 'id'=>'r' . $servicio_medico_prestado_list->RowCnt . '_servicio_medico_prestado', 'data-rowtype'=>$servicio_medico_prestado->RowType));

		// Render row
		$servicio_medico_prestado_list->RenderRow();

		// Render list options
		$servicio_medico_prestado_list->RenderListOptions();
?>
	<tr<?php echo $servicio_medico_prestado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$servicio_medico_prestado_list->ListOptions->Render("body", "left", $servicio_medico_prestado_list->RowCnt);
?>
	<?php if ($servicio_medico_prestado->idcuenta->Visible) { // idcuenta ?>
		<td data-name="idcuenta"<?php echo $servicio_medico_prestado->idcuenta->CellAttributes() ?>>
<span<?php echo $servicio_medico_prestado->idcuenta->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->idcuenta->ListViewValue() ?></span>
<a id="<?php echo $servicio_medico_prestado_list->PageObjName . "_row_" . $servicio_medico_prestado_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->idservicio_medico->Visible) { // idservicio_medico ?>
		<td data-name="idservicio_medico"<?php echo $servicio_medico_prestado->idservicio_medico->CellAttributes() ?>>
<span<?php echo $servicio_medico_prestado->idservicio_medico->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->idservicio_medico->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $servicio_medico_prestado->estado->CellAttributes() ?>>
<span<?php echo $servicio_medico_prestado->estado->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->estado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->costo->Visible) { // costo ?>
		<td data-name="costo"<?php echo $servicio_medico_prestado->costo->CellAttributes() ?>>
<span<?php echo $servicio_medico_prestado->costo->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->costo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->fecha_inicio->Visible) { // fecha_inicio ?>
		<td data-name="fecha_inicio"<?php echo $servicio_medico_prestado->fecha_inicio->CellAttributes() ?>>
<span<?php echo $servicio_medico_prestado->fecha_inicio->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->fecha_inicio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($servicio_medico_prestado->fecha_final->Visible) { // fecha_final ?>
		<td data-name="fecha_final"<?php echo $servicio_medico_prestado->fecha_final->CellAttributes() ?>>
<span<?php echo $servicio_medico_prestado->fecha_final->ViewAttributes() ?>>
<?php echo $servicio_medico_prestado->fecha_final->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$servicio_medico_prestado_list->ListOptions->Render("body", "right", $servicio_medico_prestado_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($servicio_medico_prestado->CurrentAction <> "gridadd")
		$servicio_medico_prestado_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($servicio_medico_prestado->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($servicio_medico_prestado_list->Recordset)
	$servicio_medico_prestado_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($servicio_medico_prestado->CurrentAction <> "gridadd" && $servicio_medico_prestado->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($servicio_medico_prestado_list->Pager)) $servicio_medico_prestado_list->Pager = new cPrevNextPager($servicio_medico_prestado_list->StartRec, $servicio_medico_prestado_list->DisplayRecs, $servicio_medico_prestado_list->TotalRecs) ?>
<?php if ($servicio_medico_prestado_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($servicio_medico_prestado_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $servicio_medico_prestado_list->PageUrl() ?>start=<?php echo $servicio_medico_prestado_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($servicio_medico_prestado_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $servicio_medico_prestado_list->PageUrl() ?>start=<?php echo $servicio_medico_prestado_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $servicio_medico_prestado_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($servicio_medico_prestado_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $servicio_medico_prestado_list->PageUrl() ?>start=<?php echo $servicio_medico_prestado_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($servicio_medico_prestado_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $servicio_medico_prestado_list->PageUrl() ?>start=<?php echo $servicio_medico_prestado_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $servicio_medico_prestado_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $servicio_medico_prestado_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $servicio_medico_prestado_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $servicio_medico_prestado_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($servicio_medico_prestado_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($servicio_medico_prestado_list->TotalRecs == 0 && $servicio_medico_prestado->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($servicio_medico_prestado_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fservicio_medico_prestadolistsrch.Init();
fservicio_medico_prestadolist.Init();
</script>
<?php
$servicio_medico_prestado_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$servicio_medico_prestado_list->Page_Terminate();
?>

<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "hospitalinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "municipioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "nivelgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "turnogridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "empleadogridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$hospital_add = NULL; // Initialize page object first

class chospital_add extends chospital {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'hospital';

	// Page object name
	var $PageObjName = 'hospital_add';

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

		// Table object (hospital)
		if (!isset($GLOBALS["hospital"]) || get_class($GLOBALS["hospital"]) == "chospital") {
			$GLOBALS["hospital"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["hospital"];
		}

		// Table object (municipio)
		if (!isset($GLOBALS['municipio'])) $GLOBALS['municipio'] = new cmunicipio();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'hospital', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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

			// Process auto fill for detail table 'nivel'
			if (@$_POST["grid"] == "fnivelgrid") {
				if (!isset($GLOBALS["nivel_grid"])) $GLOBALS["nivel_grid"] = new cnivel_grid;
				$GLOBALS["nivel_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'turno'
			if (@$_POST["grid"] == "fturnogrid") {
				if (!isset($GLOBALS["turno_grid"])) $GLOBALS["turno_grid"] = new cturno_grid;
				$GLOBALS["turno_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'empleado'
			if (@$_POST["grid"] == "fempleadogrid") {
				if (!isset($GLOBALS["empleado_grid"])) $GLOBALS["empleado_grid"] = new cempleado_grid;
				$GLOBALS["empleado_grid"]->Page_Init();
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
		global $EW_EXPORT, $hospital;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($hospital);
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
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["idhospital"] != "") {
				$this->idhospital->setQueryStringValue($_GET["idhospital"]);
				$this->setKey("idhospital", $this->idhospital->CurrentValue); // Set up key
			} else {
				$this->setKey("idhospital", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("hospitallist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "hospitalview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->telefono->CurrentValue = NULL;
		$this->telefono->OldValue = $this->telefono->CurrentValue;
		$this->idmunicipio->CurrentValue = NULL;
		$this->idmunicipio->OldValue = $this->idmunicipio->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue($objForm->GetValue("x_telefono"));
		}
		if (!$this->idmunicipio->FldIsDetailKey) {
			$this->idmunicipio->setFormValue($objForm->GetValue("x_idmunicipio"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->telefono->CurrentValue = $this->telefono->FormValue;
		$this->idmunicipio->CurrentValue = $this->idmunicipio->FormValue;
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
		$this->idhospital->setDbValue($rs->fields('idhospital'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->idmunicipio->setDbValue($rs->fields('idmunicipio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idhospital->DbValue = $row['idhospital'];
		$this->nombre->DbValue = $row['nombre'];
		$this->direccion->DbValue = $row['direccion'];
		$this->telefono->DbValue = $row['telefono'];
		$this->estado->DbValue = $row['estado'];
		$this->idmunicipio->DbValue = $row['idmunicipio'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idhospital")) <> "")
			$this->idhospital->CurrentValue = $this->getKey("idhospital"); // idhospital
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idhospital
		// nombre
		// direccion
		// telefono
		// estado
		// idmunicipio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idhospital
			$this->idhospital->ViewValue = $this->idhospital->CurrentValue;
			$this->idhospital->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->ViewCustomAttributes = "";

			// telefono
			$this->telefono->ViewValue = $this->telefono->CurrentValue;
			$this->telefono->ViewCustomAttributes = "";

			// estado
			if (strval($this->estado->CurrentValue) <> "") {
				switch ($this->estado->CurrentValue) {
					case $this->estado->FldTagValue(1):
						$this->estado->ViewValue = $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->CurrentValue;
						break;
					case $this->estado->FldTagValue(2):
						$this->estado->ViewValue = $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->CurrentValue;
						break;
					default:
						$this->estado->ViewValue = $this->estado->CurrentValue;
				}
			} else {
				$this->estado->ViewValue = NULL;
			}
			$this->estado->ViewCustomAttributes = "";

			// idmunicipio
			if (strval($this->idmunicipio->CurrentValue) <> "") {
				$sFilterWrk = "`idmunicipio`" . ew_SearchString("=", $this->idmunicipio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idmunicipio`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `municipio`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idmunicipio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idmunicipio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idmunicipio->ViewValue = $this->idmunicipio->CurrentValue;
				}
			} else {
				$this->idmunicipio->ViewValue = NULL;
			}
			$this->idmunicipio->ViewCustomAttributes = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// idmunicipio
			$this->idmunicipio->LinkCustomAttributes = "";
			$this->idmunicipio->HrefValue = "";
			$this->idmunicipio->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// idmunicipio
			$this->idmunicipio->EditAttrs["class"] = "form-control";
			$this->idmunicipio->EditCustomAttributes = "";
			if ($this->idmunicipio->getSessionValue() <> "") {
				$this->idmunicipio->CurrentValue = $this->idmunicipio->getSessionValue();
			if (strval($this->idmunicipio->CurrentValue) <> "") {
				$sFilterWrk = "`idmunicipio`" . ew_SearchString("=", $this->idmunicipio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idmunicipio`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `municipio`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idmunicipio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idmunicipio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idmunicipio->ViewValue = $this->idmunicipio->CurrentValue;
				}
			} else {
				$this->idmunicipio->ViewValue = NULL;
			}
			$this->idmunicipio->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->idmunicipio->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idmunicipio`" . ew_SearchString("=", $this->idmunicipio->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idmunicipio`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `municipio`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idmunicipio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idmunicipio->EditValue = $arwrk;
			}

			// Edit refer script
			// nombre

			$this->nombre->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// telefono
			$this->telefono->HrefValue = "";

			// idmunicipio
			$this->idmunicipio->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->idmunicipio->FldIsDetailKey && !is_null($this->idmunicipio->FormValue) && $this->idmunicipio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idmunicipio->FldCaption(), $this->idmunicipio->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("nivel", $DetailTblVar) && $GLOBALS["nivel"]->DetailAdd) {
			if (!isset($GLOBALS["nivel_grid"])) $GLOBALS["nivel_grid"] = new cnivel_grid(); // get detail page object
			$GLOBALS["nivel_grid"]->ValidateGridForm();
		}
		if (in_array("turno", $DetailTblVar) && $GLOBALS["turno"]->DetailAdd) {
			if (!isset($GLOBALS["turno_grid"])) $GLOBALS["turno_grid"] = new cturno_grid(); // get detail page object
			$GLOBALS["turno_grid"]->ValidateGridForm();
		}
		if (in_array("empleado", $DetailTblVar) && $GLOBALS["empleado"]->DetailAdd) {
			if (!isset($GLOBALS["empleado_grid"])) $GLOBALS["empleado_grid"] = new cempleado_grid(); // get detail page object
			$GLOBALS["empleado_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, FALSE);

		// telefono
		$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, NULL, FALSE);

		// idmunicipio
		$this->idmunicipio->SetDbValueDef($rsnew, $this->idmunicipio->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->idhospital->setDbValue($conn->Insert_ID());
			$rsnew['idhospital'] = $this->idhospital->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("nivel", $DetailTblVar) && $GLOBALS["nivel"]->DetailAdd) {
				$GLOBALS["nivel"]->idhospital->setSessionValue($this->idhospital->CurrentValue); // Set master key
				if (!isset($GLOBALS["nivel_grid"])) $GLOBALS["nivel_grid"] = new cnivel_grid(); // Get detail page object
				$AddRow = $GLOBALS["nivel_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["nivel"]->idhospital->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("turno", $DetailTblVar) && $GLOBALS["turno"]->DetailAdd) {
				$GLOBALS["turno"]->idhospital->setSessionValue($this->idhospital->CurrentValue); // Set master key
				if (!isset($GLOBALS["turno_grid"])) $GLOBALS["turno_grid"] = new cturno_grid(); // Get detail page object
				$AddRow = $GLOBALS["turno_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["turno"]->idhospital->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("empleado", $DetailTblVar) && $GLOBALS["empleado"]->DetailAdd) {
				$GLOBALS["empleado"]->idhospital->setSessionValue($this->idhospital->CurrentValue); // Set master key
				if (!isset($GLOBALS["empleado_grid"])) $GLOBALS["empleado_grid"] = new cempleado_grid(); // Get detail page object
				$AddRow = $GLOBALS["empleado_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["empleado"]->idhospital->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
			if ($sMasterTblVar == "municipio") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idmunicipio"] <> "") {
					$GLOBALS["municipio"]->idmunicipio->setQueryStringValue($_GET["fk_idmunicipio"]);
					$this->idmunicipio->setQueryStringValue($GLOBALS["municipio"]->idmunicipio->QueryStringValue);
					$this->idmunicipio->setSessionValue($this->idmunicipio->QueryStringValue);
					if (!is_numeric($GLOBALS["municipio"]->idmunicipio->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "municipio") {
				if ($this->idmunicipio->QueryStringValue == "") $this->idmunicipio->setSessionValue("");
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
			if (in_array("nivel", $DetailTblVar)) {
				if (!isset($GLOBALS["nivel_grid"]))
					$GLOBALS["nivel_grid"] = new cnivel_grid;
				if ($GLOBALS["nivel_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["nivel_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["nivel_grid"]->CurrentMode = "add";
					$GLOBALS["nivel_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["nivel_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["nivel_grid"]->setStartRecordNumber(1);
					$GLOBALS["nivel_grid"]->idhospital->FldIsDetailKey = TRUE;
					$GLOBALS["nivel_grid"]->idhospital->CurrentValue = $this->idhospital->CurrentValue;
					$GLOBALS["nivel_grid"]->idhospital->setSessionValue($GLOBALS["nivel_grid"]->idhospital->CurrentValue);
				}
			}
			if (in_array("turno", $DetailTblVar)) {
				if (!isset($GLOBALS["turno_grid"]))
					$GLOBALS["turno_grid"] = new cturno_grid;
				if ($GLOBALS["turno_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["turno_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["turno_grid"]->CurrentMode = "add";
					$GLOBALS["turno_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["turno_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["turno_grid"]->setStartRecordNumber(1);
					$GLOBALS["turno_grid"]->idhospital->FldIsDetailKey = TRUE;
					$GLOBALS["turno_grid"]->idhospital->CurrentValue = $this->idhospital->CurrentValue;
					$GLOBALS["turno_grid"]->idhospital->setSessionValue($GLOBALS["turno_grid"]->idhospital->CurrentValue);
				}
			}
			if (in_array("empleado", $DetailTblVar)) {
				if (!isset($GLOBALS["empleado_grid"]))
					$GLOBALS["empleado_grid"] = new cempleado_grid;
				if ($GLOBALS["empleado_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["empleado_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["empleado_grid"]->CurrentMode = "add";
					$GLOBALS["empleado_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["empleado_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["empleado_grid"]->setStartRecordNumber(1);
					$GLOBALS["empleado_grid"]->idhospital->FldIsDetailKey = TRUE;
					$GLOBALS["empleado_grid"]->idhospital->CurrentValue = $this->idhospital->CurrentValue;
					$GLOBALS["empleado_grid"]->idhospital->setSessionValue($GLOBALS["empleado_grid"]->idhospital->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "hospitallist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($hospital_add)) $hospital_add = new chospital_add();

// Page init
$hospital_add->Page_Init();

// Page main
$hospital_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$hospital_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var hospital_add = new ew_Page("hospital_add");
hospital_add.PageID = "add"; // Page ID
var EW_PAGE_ID = hospital_add.PageID; // For backward compatibility

// Form object
var fhospitaladd = new ew_Form("fhospitaladd");

// Validate form
fhospitaladd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idmunicipio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $hospital->idmunicipio->FldCaption(), $hospital->idmunicipio->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fhospitaladd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fhospitaladd.ValidateRequired = true;
<?php } else { ?>
fhospitaladd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fhospitaladd.Lists["x_idmunicipio"] = {"LinkField":"x_idmunicipio","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $hospital_add->ShowPageHeader(); ?>
<?php
$hospital_add->ShowMessage();
?>
<form name="fhospitaladd" id="fhospitaladd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($hospital_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $hospital_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="hospital">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($hospital->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_hospital_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $hospital->nombre->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hospital->nombre->CellAttributes() ?>>
<span id="el_hospital_nombre">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($hospital->nombre->PlaceHolder) ?>" value="<?php echo $hospital->nombre->EditValue ?>"<?php echo $hospital->nombre->EditAttributes() ?>>
</span>
<?php echo $hospital->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hospital->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_hospital_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $hospital->direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hospital->direccion->CellAttributes() ?>>
<span id="el_hospital_direccion">
<input type="text" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($hospital->direccion->PlaceHolder) ?>" value="<?php echo $hospital->direccion->EditValue ?>"<?php echo $hospital->direccion->EditAttributes() ?>>
</span>
<?php echo $hospital->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hospital->telefono->Visible) { // telefono ?>
	<div id="r_telefono" class="form-group">
		<label id="elh_hospital_telefono" for="x_telefono" class="col-sm-2 control-label ewLabel"><?php echo $hospital->telefono->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $hospital->telefono->CellAttributes() ?>>
<span id="el_hospital_telefono">
<input type="text" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($hospital->telefono->PlaceHolder) ?>" value="<?php echo $hospital->telefono->EditValue ?>"<?php echo $hospital->telefono->EditAttributes() ?>>
</span>
<?php echo $hospital->telefono->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($hospital->idmunicipio->Visible) { // idmunicipio ?>
	<div id="r_idmunicipio" class="form-group">
		<label id="elh_hospital_idmunicipio" for="x_idmunicipio" class="col-sm-2 control-label ewLabel"><?php echo $hospital->idmunicipio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $hospital->idmunicipio->CellAttributes() ?>>
<?php if ($hospital->idmunicipio->getSessionValue() <> "") { ?>
<span id="el_hospital_idmunicipio">
<span<?php echo $hospital->idmunicipio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $hospital->idmunicipio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idmunicipio" name="x_idmunicipio" value="<?php echo ew_HtmlEncode($hospital->idmunicipio->CurrentValue) ?>">
<?php } else { ?>
<span id="el_hospital_idmunicipio">
<select data-field="x_idmunicipio" id="x_idmunicipio" name="x_idmunicipio"<?php echo $hospital->idmunicipio->EditAttributes() ?>>
<?php
if (is_array($hospital->idmunicipio->EditValue)) {
	$arwrk = $hospital->idmunicipio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($hospital->idmunicipio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idmunicipio`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `municipio`";
$sWhereWrk = "";

// Call Lookup selecting
$hospital->Lookup_Selecting($hospital->idmunicipio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idmunicipio" id="s_x_idmunicipio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idmunicipio` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $hospital->idmunicipio->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("nivel", explode(",", $hospital->getCurrentDetailTable())) && $nivel->DetailAdd) {
?>
<?php if ($hospital->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("nivel", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "nivelgrid.php" ?>
<?php } ?>
<?php
	if (in_array("turno", explode(",", $hospital->getCurrentDetailTable())) && $turno->DetailAdd) {
?>
<?php if ($hospital->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("turno", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "turnogrid.php" ?>
<?php } ?>
<?php
	if (in_array("empleado", explode(",", $hospital->getCurrentDetailTable())) && $empleado->DetailAdd) {
?>
<?php if ($hospital->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("empleado", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "empleadogrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fhospitaladd.Init();
</script>
<?php
$hospital_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$hospital_add->Page_Terminate();
?>

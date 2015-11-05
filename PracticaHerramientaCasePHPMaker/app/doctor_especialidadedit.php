<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "doctor_especialidadinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "especialidadinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "doctorgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$doctor_especialidad_edit = NULL; // Initialize page object first

class cdoctor_especialidad_edit extends cdoctor_especialidad {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'doctor_especialidad';

	// Page object name
	var $PageObjName = 'doctor_especialidad_edit';

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

		// Table object (doctor_especialidad)
		if (!isset($GLOBALS["doctor_especialidad"]) || get_class($GLOBALS["doctor_especialidad"]) == "cdoctor_especialidad") {
			$GLOBALS["doctor_especialidad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["doctor_especialidad"];
		}

		// Table object (especialidad)
		if (!isset($GLOBALS['especialidad'])) $GLOBALS['especialidad'] = new cespecialidad();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'doctor_especialidad', TRUE);

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
		$this->iddoctor_especialidad->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

			// Process auto fill for detail table 'doctor'
			if (@$_POST["grid"] == "fdoctorgrid") {
				if (!isset($GLOBALS["doctor_grid"])) $GLOBALS["doctor_grid"] = new cdoctor_grid;
				$GLOBALS["doctor_grid"]->Page_Init();
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
		global $EW_EXPORT, $doctor_especialidad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($doctor_especialidad);
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["iddoctor_especialidad"] <> "") {
			$this->iddoctor_especialidad->setQueryStringValue($_GET["iddoctor_especialidad"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->iddoctor_especialidad->CurrentValue == "")
			$this->Page_Terminate("doctor_especialidadlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("doctor_especialidadlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
					else
						$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->iddoctor_especialidad->FldIsDetailKey)
			$this->iddoctor_especialidad->setFormValue($objForm->GetValue("x_iddoctor_especialidad"));
		if (!$this->iddoctor->FldIsDetailKey) {
			$this->iddoctor->setFormValue($objForm->GetValue("x_iddoctor"));
		}
		if (!$this->idespecialidad->FldIsDetailKey) {
			$this->idespecialidad->setFormValue($objForm->GetValue("x_idespecialidad"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->iddoctor_especialidad->CurrentValue = $this->iddoctor_especialidad->FormValue;
		$this->iddoctor->CurrentValue = $this->iddoctor->FormValue;
		$this->idespecialidad->CurrentValue = $this->idespecialidad->FormValue;
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
		$this->iddoctor_especialidad->setDbValue($rs->fields('iddoctor_especialidad'));
		$this->iddoctor->setDbValue($rs->fields('iddoctor'));
		$this->idespecialidad->setDbValue($rs->fields('idespecialidad'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->iddoctor_especialidad->DbValue = $row['iddoctor_especialidad'];
		$this->iddoctor->DbValue = $row['iddoctor'];
		$this->idespecialidad->DbValue = $row['idespecialidad'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// iddoctor_especialidad
		// iddoctor
		// idespecialidad

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// iddoctor_especialidad
			$this->iddoctor_especialidad->ViewValue = $this->iddoctor_especialidad->CurrentValue;
			$this->iddoctor_especialidad->ViewCustomAttributes = "";

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

			// idespecialidad
			if (strval($this->idespecialidad->CurrentValue) <> "") {
				$sFilterWrk = "`idespecialidad`" . ew_SearchString("=", $this->idespecialidad->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idespecialidad`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `especialidad`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idespecialidad, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idespecialidad->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idespecialidad->ViewValue = $this->idespecialidad->CurrentValue;
				}
			} else {
				$this->idespecialidad->ViewValue = NULL;
			}
			$this->idespecialidad->ViewCustomAttributes = "";

			// iddoctor_especialidad
			$this->iddoctor_especialidad->LinkCustomAttributes = "";
			$this->iddoctor_especialidad->HrefValue = "";
			$this->iddoctor_especialidad->TooltipValue = "";

			// iddoctor
			$this->iddoctor->LinkCustomAttributes = "";
			$this->iddoctor->HrefValue = "";
			$this->iddoctor->TooltipValue = "";

			// idespecialidad
			$this->idespecialidad->LinkCustomAttributes = "";
			$this->idespecialidad->HrefValue = "";
			$this->idespecialidad->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// iddoctor_especialidad
			$this->iddoctor_especialidad->EditAttrs["class"] = "form-control";
			$this->iddoctor_especialidad->EditCustomAttributes = "";
			$this->iddoctor_especialidad->EditValue = $this->iddoctor_especialidad->CurrentValue;
			$this->iddoctor_especialidad->ViewCustomAttributes = "";

			// iddoctor
			$this->iddoctor->EditAttrs["class"] = "form-control";
			$this->iddoctor->EditCustomAttributes = "";
			if (trim(strval($this->iddoctor->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`iddoctor`" . ew_SearchString("=", $this->iddoctor->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `doctor`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->iddoctor, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->iddoctor->EditValue = $arwrk;

			// idespecialidad
			$this->idespecialidad->EditAttrs["class"] = "form-control";
			$this->idespecialidad->EditCustomAttributes = "";
			if ($this->idespecialidad->getSessionValue() <> "") {
				$this->idespecialidad->CurrentValue = $this->idespecialidad->getSessionValue();
			if (strval($this->idespecialidad->CurrentValue) <> "") {
				$sFilterWrk = "`idespecialidad`" . ew_SearchString("=", $this->idespecialidad->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idespecialidad`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `especialidad`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idespecialidad, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idespecialidad->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idespecialidad->ViewValue = $this->idespecialidad->CurrentValue;
				}
			} else {
				$this->idespecialidad->ViewValue = NULL;
			}
			$this->idespecialidad->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->idespecialidad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idespecialidad`" . ew_SearchString("=", $this->idespecialidad->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idespecialidad`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `especialidad`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idespecialidad, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idespecialidad->EditValue = $arwrk;
			}

			// Edit refer script
			// iddoctor_especialidad

			$this->iddoctor_especialidad->HrefValue = "";

			// iddoctor
			$this->iddoctor->HrefValue = "";

			// idespecialidad
			$this->idespecialidad->HrefValue = "";
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
		if (!$this->iddoctor->FldIsDetailKey && !is_null($this->iddoctor->FormValue) && $this->iddoctor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->iddoctor->FldCaption(), $this->iddoctor->ReqErrMsg));
		}
		if (!$this->idespecialidad->FldIsDetailKey && !is_null($this->idespecialidad->FormValue) && $this->idespecialidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idespecialidad->FldCaption(), $this->idespecialidad->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("doctor", $DetailTblVar) && $GLOBALS["doctor"]->DetailEdit) {
			if (!isset($GLOBALS["doctor_grid"])) $GLOBALS["doctor_grid"] = new cdoctor_grid(); // get detail page object
			$GLOBALS["doctor_grid"]->ValidateGridForm();
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// iddoctor
			$this->iddoctor->SetDbValueDef($rsnew, $this->iddoctor->CurrentValue, 0, $this->iddoctor->ReadOnly);

			// idespecialidad
			$this->idespecialidad->SetDbValueDef($rsnew, $this->idespecialidad->CurrentValue, 0, $this->idespecialidad->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("doctor", $DetailTblVar) && $GLOBALS["doctor"]->DetailEdit) {
						if (!isset($GLOBALS["doctor_grid"])) $GLOBALS["doctor_grid"] = new cdoctor_grid(); // Get detail page object
						$EditRow = $GLOBALS["doctor_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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
			if ($sMasterTblVar == "especialidad") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idespecialidad"] <> "") {
					$GLOBALS["especialidad"]->idespecialidad->setQueryStringValue($_GET["fk_idespecialidad"]);
					$this->idespecialidad->setQueryStringValue($GLOBALS["especialidad"]->idespecialidad->QueryStringValue);
					$this->idespecialidad->setSessionValue($this->idespecialidad->QueryStringValue);
					if (!is_numeric($GLOBALS["especialidad"]->idespecialidad->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "especialidad") {
				if ($this->idespecialidad->QueryStringValue == "") $this->idespecialidad->setSessionValue("");
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
			if (in_array("doctor", $DetailTblVar)) {
				if (!isset($GLOBALS["doctor_grid"]))
					$GLOBALS["doctor_grid"] = new cdoctor_grid;
				if ($GLOBALS["doctor_grid"]->DetailEdit) {
					$GLOBALS["doctor_grid"]->CurrentMode = "edit";
					$GLOBALS["doctor_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["doctor_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["doctor_grid"]->setStartRecordNumber(1);
					$GLOBALS["doctor_grid"]->iddoctor->FldIsDetailKey = TRUE;
					$GLOBALS["doctor_grid"]->iddoctor->CurrentValue = $this->iddoctor->CurrentValue;
					$GLOBALS["doctor_grid"]->iddoctor->setSessionValue($GLOBALS["doctor_grid"]->iddoctor->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "doctor_especialidadlist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, ew_CurrentUrl());
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
if (!isset($doctor_especialidad_edit)) $doctor_especialidad_edit = new cdoctor_especialidad_edit();

// Page init
$doctor_especialidad_edit->Page_Init();

// Page main
$doctor_especialidad_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$doctor_especialidad_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var doctor_especialidad_edit = new ew_Page("doctor_especialidad_edit");
doctor_especialidad_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = doctor_especialidad_edit.PageID; // For backward compatibility

// Form object
var fdoctor_especialidadedit = new ew_Form("fdoctor_especialidadedit");

// Validate form
fdoctor_especialidadedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_iddoctor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor_especialidad->iddoctor->FldCaption(), $doctor_especialidad->iddoctor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idespecialidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor_especialidad->idespecialidad->FldCaption(), $doctor_especialidad->idespecialidad->ReqErrMsg)) ?>");

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
fdoctor_especialidadedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdoctor_especialidadedit.ValidateRequired = true;
<?php } else { ?>
fdoctor_especialidadedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdoctor_especialidadedit.Lists["x_iddoctor"] = {"LinkField":"x_iddoctor","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdoctor_especialidadedit.Lists["x_idespecialidad"] = {"LinkField":"x_idespecialidad","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $doctor_especialidad_edit->ShowPageHeader(); ?>
<?php
$doctor_especialidad_edit->ShowMessage();
?>
<form name="fdoctor_especialidadedit" id="fdoctor_especialidadedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($doctor_especialidad_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $doctor_especialidad_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="doctor_especialidad">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($doctor_especialidad->iddoctor_especialidad->Visible) { // iddoctor_especialidad ?>
	<div id="r_iddoctor_especialidad" class="form-group">
		<label id="elh_doctor_especialidad_iddoctor_especialidad" class="col-sm-2 control-label ewLabel"><?php echo $doctor_especialidad->iddoctor_especialidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $doctor_especialidad->iddoctor_especialidad->CellAttributes() ?>>
<span id="el_doctor_especialidad_iddoctor_especialidad">
<span<?php echo $doctor_especialidad->iddoctor_especialidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_especialidad->iddoctor_especialidad->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_iddoctor_especialidad" name="x_iddoctor_especialidad" id="x_iddoctor_especialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->iddoctor_especialidad->CurrentValue) ?>">
<?php echo $doctor_especialidad->iddoctor_especialidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($doctor_especialidad->iddoctor->Visible) { // iddoctor ?>
	<div id="r_iddoctor" class="form-group">
		<label id="elh_doctor_especialidad_iddoctor" for="x_iddoctor" class="col-sm-2 control-label ewLabel"><?php echo $doctor_especialidad->iddoctor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $doctor_especialidad->iddoctor->CellAttributes() ?>>
<span id="el_doctor_especialidad_iddoctor">
<select data-field="x_iddoctor" id="x_iddoctor" name="x_iddoctor"<?php echo $doctor_especialidad->iddoctor->EditAttributes() ?>>
<?php
if (is_array($doctor_especialidad->iddoctor->EditValue)) {
	$arwrk = $doctor_especialidad->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_especialidad->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `iddoctor`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `doctor`";
$sWhereWrk = "";

// Call Lookup selecting
$doctor_especialidad->Lookup_Selecting($doctor_especialidad->iddoctor, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_iddoctor" id="s_x_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $doctor_especialidad->iddoctor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($doctor_especialidad->idespecialidad->Visible) { // idespecialidad ?>
	<div id="r_idespecialidad" class="form-group">
		<label id="elh_doctor_especialidad_idespecialidad" for="x_idespecialidad" class="col-sm-2 control-label ewLabel"><?php echo $doctor_especialidad->idespecialidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $doctor_especialidad->idespecialidad->CellAttributes() ?>>
<?php if ($doctor_especialidad->idespecialidad->getSessionValue() <> "") { ?>
<span id="el_doctor_especialidad_idespecialidad">
<span<?php echo $doctor_especialidad->idespecialidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor_especialidad->idespecialidad->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idespecialidad" name="x_idespecialidad" value="<?php echo ew_HtmlEncode($doctor_especialidad->idespecialidad->CurrentValue) ?>">
<?php } else { ?>
<span id="el_doctor_especialidad_idespecialidad">
<select data-field="x_idespecialidad" id="x_idespecialidad" name="x_idespecialidad"<?php echo $doctor_especialidad->idespecialidad->EditAttributes() ?>>
<?php
if (is_array($doctor_especialidad->idespecialidad->EditValue)) {
	$arwrk = $doctor_especialidad->idespecialidad->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor_especialidad->idespecialidad->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idespecialidad`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `especialidad`";
$sWhereWrk = "";

// Call Lookup selecting
$doctor_especialidad->Lookup_Selecting($doctor_especialidad->idespecialidad, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idespecialidad" id="s_x_idespecialidad" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idespecialidad` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $doctor_especialidad->idespecialidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("doctor", explode(",", $doctor_especialidad->getCurrentDetailTable())) && $doctor->DetailEdit) {
?>
<?php if ($doctor_especialidad->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("doctor", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "doctorgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fdoctor_especialidadedit.Init();
</script>
<?php
$doctor_especialidad_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$doctor_especialidad_edit->Page_Terminate();
?>

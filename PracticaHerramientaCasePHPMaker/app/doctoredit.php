<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "doctorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "doctor_especialidadinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "turnoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "consultagridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$doctor_edit = NULL; // Initialize page object first

class cdoctor_edit extends cdoctor {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'doctor';

	// Page object name
	var $PageObjName = 'doctor_edit';

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

		// Table object (doctor)
		if (!isset($GLOBALS["doctor"]) || get_class($GLOBALS["doctor"]) == "cdoctor") {
			$GLOBALS["doctor"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["doctor"];
		}

		// Table object (doctor_especialidad)
		if (!isset($GLOBALS['doctor_especialidad'])) $GLOBALS['doctor_especialidad'] = new cdoctor_especialidad();

		// Table object (turno)
		if (!isset($GLOBALS['turno'])) $GLOBALS['turno'] = new cturno();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'doctor', TRUE);

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

			// Process auto fill for detail table 'consulta'
			if (@$_POST["grid"] == "fconsultagrid") {
				if (!isset($GLOBALS["consulta_grid"])) $GLOBALS["consulta_grid"] = new cconsulta_grid;
				$GLOBALS["consulta_grid"]->Page_Init();
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
		global $EW_EXPORT, $doctor;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($doctor);
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
		if (@$_GET["iddoctor"] <> "") {
			$this->iddoctor->setQueryStringValue($_GET["iddoctor"]);
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
		if ($this->iddoctor->CurrentValue == "")
			$this->Page_Terminate("doctorlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("doctorlist.php"); // No matching record, return to list
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
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->apellido->FldIsDetailKey) {
			$this->apellido->setFormValue($objForm->GetValue("x_apellido"));
		}
		if (!$this->cui->FldIsDetailKey) {
			$this->cui->setFormValue($objForm->GetValue("x_cui"));
		}
		if (!$this->telefono->FldIsDetailKey) {
			$this->telefono->setFormValue($objForm->GetValue("x_telefono"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->idturno->FldIsDetailKey) {
			$this->idturno->setFormValue($objForm->GetValue("x_idturno"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->iddoctor->FldIsDetailKey)
			$this->iddoctor->setFormValue($objForm->GetValue("x_iddoctor"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->iddoctor->CurrentValue = $this->iddoctor->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->apellido->CurrentValue = $this->apellido->FormValue;
		$this->cui->CurrentValue = $this->cui->FormValue;
		$this->telefono->CurrentValue = $this->telefono->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->idturno->CurrentValue = $this->idturno->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
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
		$this->iddoctor->setDbValue($rs->fields('iddoctor'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->apellido->setDbValue($rs->fields('apellido'));
		$this->cui->setDbValue($rs->fields('cui'));
		$this->telefono->setDbValue($rs->fields('telefono'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->idturno->setDbValue($rs->fields('idturno'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->iddoctor->DbValue = $row['iddoctor'];
		$this->nombre->DbValue = $row['nombre'];
		$this->apellido->DbValue = $row['apellido'];
		$this->cui->DbValue = $row['cui'];
		$this->telefono->DbValue = $row['telefono'];
		$this->direccion->DbValue = $row['direccion'];
		$this->idturno->DbValue = $row['idturno'];
		$this->estado->DbValue = $row['estado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// iddoctor
		// nombre
		// apellido
		// cui
		// telefono
		// direccion
		// idturno
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// iddoctor
			$this->iddoctor->ViewValue = $this->iddoctor->CurrentValue;
			$this->iddoctor->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// apellido
			$this->apellido->ViewValue = $this->apellido->CurrentValue;
			$this->apellido->ViewCustomAttributes = "";

			// cui
			$this->cui->ViewValue = $this->cui->CurrentValue;
			$this->cui->ViewCustomAttributes = "";

			// telefono
			$this->telefono->ViewValue = $this->telefono->CurrentValue;
			$this->telefono->ViewCustomAttributes = "";

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->ViewCustomAttributes = "";

			// idturno
			$this->idturno->ViewValue = $this->idturno->CurrentValue;
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

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// apellido
			$this->apellido->LinkCustomAttributes = "";
			$this->apellido->HrefValue = "";
			$this->apellido->TooltipValue = "";

			// cui
			$this->cui->LinkCustomAttributes = "";
			$this->cui->HrefValue = "";
			$this->cui->TooltipValue = "";

			// telefono
			$this->telefono->LinkCustomAttributes = "";
			$this->telefono->HrefValue = "";
			$this->telefono->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// idturno
			$this->idturno->LinkCustomAttributes = "";
			$this->idturno->HrefValue = "";
			$this->idturno->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// apellido
			$this->apellido->EditAttrs["class"] = "form-control";
			$this->apellido->EditCustomAttributes = "";
			$this->apellido->EditValue = ew_HtmlEncode($this->apellido->CurrentValue);
			$this->apellido->PlaceHolder = ew_RemoveHtml($this->apellido->FldCaption());

			// cui
			$this->cui->EditAttrs["class"] = "form-control";
			$this->cui->EditCustomAttributes = "";
			$this->cui->EditValue = ew_HtmlEncode($this->cui->CurrentValue);
			$this->cui->PlaceHolder = ew_RemoveHtml($this->cui->FldCaption());

			// telefono
			$this->telefono->EditAttrs["class"] = "form-control";
			$this->telefono->EditCustomAttributes = "";
			$this->telefono->EditValue = ew_HtmlEncode($this->telefono->CurrentValue);
			$this->telefono->PlaceHolder = ew_RemoveHtml($this->telefono->FldCaption());

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// idturno
			$this->idturno->EditAttrs["class"] = "form-control";
			$this->idturno->EditCustomAttributes = "";
			if ($this->idturno->getSessionValue() <> "") {
				$this->idturno->CurrentValue = $this->idturno->getSessionValue();
			$this->idturno->ViewValue = $this->idturno->CurrentValue;
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
			} else {
			$this->idturno->EditValue = ew_HtmlEncode($this->idturno->CurrentValue);
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
					$this->idturno->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$rswrk->Close();
				} else {
					$this->idturno->EditValue = ew_HtmlEncode($this->idturno->CurrentValue);
				}
			} else {
				$this->idturno->EditValue = NULL;
			}
			$this->idturno->PlaceHolder = ew_RemoveHtml($this->idturno->FldCaption());
			}

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->estado->EditValue = $arwrk;

			// Edit refer script
			// nombre

			$this->nombre->HrefValue = "";

			// apellido
			$this->apellido->HrefValue = "";

			// cui
			$this->cui->HrefValue = "";

			// telefono
			$this->telefono->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// idturno
			$this->idturno->HrefValue = "";

			// estado
			$this->estado->HrefValue = "";
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
		if (!$this->nombre->FldIsDetailKey && !is_null($this->nombre->FormValue) && $this->nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombre->FldCaption(), $this->nombre->ReqErrMsg));
		}
		if (!$this->idturno->FldIsDetailKey && !is_null($this->idturno->FormValue) && $this->idturno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idturno->FldCaption(), $this->idturno->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->idturno->FormValue)) {
			ew_AddMessage($gsFormError, $this->idturno->FldErrMsg());
		}
		if (!$this->estado->FldIsDetailKey && !is_null($this->estado->FormValue) && $this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("consulta", $DetailTblVar) && $GLOBALS["consulta"]->DetailEdit) {
			if (!isset($GLOBALS["consulta_grid"])) $GLOBALS["consulta_grid"] = new cconsulta_grid(); // get detail page object
			$GLOBALS["consulta_grid"]->ValidateGridForm();
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

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, "", $this->nombre->ReadOnly);

			// apellido
			$this->apellido->SetDbValueDef($rsnew, $this->apellido->CurrentValue, NULL, $this->apellido->ReadOnly);

			// cui
			$this->cui->SetDbValueDef($rsnew, $this->cui->CurrentValue, NULL, $this->cui->ReadOnly);

			// telefono
			$this->telefono->SetDbValueDef($rsnew, $this->telefono->CurrentValue, NULL, $this->telefono->ReadOnly);

			// direccion
			$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, $this->direccion->ReadOnly);

			// idturno
			$this->idturno->SetDbValueDef($rsnew, $this->idturno->CurrentValue, 0, $this->idturno->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", $this->estado->ReadOnly);

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
					if (in_array("consulta", $DetailTblVar) && $GLOBALS["consulta"]->DetailEdit) {
						if (!isset($GLOBALS["consulta_grid"])) $GLOBALS["consulta_grid"] = new cconsulta_grid(); // Get detail page object
						$EditRow = $GLOBALS["consulta_grid"]->GridUpdate();
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
			if ($sMasterTblVar == "doctor_especialidad") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_iddoctor"] <> "") {
					$GLOBALS["doctor_especialidad"]->iddoctor->setQueryStringValue($_GET["fk_iddoctor"]);
					$this->iddoctor->setQueryStringValue($GLOBALS["doctor_especialidad"]->iddoctor->QueryStringValue);
					$this->iddoctor->setSessionValue($this->iddoctor->QueryStringValue);
					if (!is_numeric($GLOBALS["doctor_especialidad"]->iddoctor->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "turno") {
				if ($this->idturno->QueryStringValue == "") $this->idturno->setSessionValue("");
			}
			if ($sMasterTblVar <> "doctor_especialidad") {
				if ($this->iddoctor->QueryStringValue == "") $this->iddoctor->setSessionValue("");
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
			if (in_array("consulta", $DetailTblVar)) {
				if (!isset($GLOBALS["consulta_grid"]))
					$GLOBALS["consulta_grid"] = new cconsulta_grid;
				if ($GLOBALS["consulta_grid"]->DetailEdit) {
					$GLOBALS["consulta_grid"]->CurrentMode = "edit";
					$GLOBALS["consulta_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["consulta_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["consulta_grid"]->setStartRecordNumber(1);
					$GLOBALS["consulta_grid"]->iddoctor->FldIsDetailKey = TRUE;
					$GLOBALS["consulta_grid"]->iddoctor->CurrentValue = $this->iddoctor->CurrentValue;
					$GLOBALS["consulta_grid"]->iddoctor->setSessionValue($GLOBALS["consulta_grid"]->iddoctor->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "doctorlist.php", "", $this->TableVar, TRUE);
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
if (!isset($doctor_edit)) $doctor_edit = new cdoctor_edit();

// Page init
$doctor_edit->Page_Init();

// Page main
$doctor_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$doctor_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var doctor_edit = new ew_Page("doctor_edit");
doctor_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = doctor_edit.PageID; // For backward compatibility

// Form object
var fdoctoredit = new ew_Form("fdoctoredit");

// Validate form
fdoctoredit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor->nombre->FldCaption(), $doctor->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idturno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor->idturno->FldCaption(), $doctor->idturno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idturno");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($doctor->idturno->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $doctor->estado->FldCaption(), $doctor->estado->ReqErrMsg)) ?>");

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
fdoctoredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdoctoredit.ValidateRequired = true;
<?php } else { ?>
fdoctoredit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdoctoredit.Lists["x_idturno"] = {"LinkField":"x_idturno","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $doctor_edit->ShowPageHeader(); ?>
<?php
$doctor_edit->ShowMessage();
?>
<form name="fdoctoredit" id="fdoctoredit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($doctor_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $doctor_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="doctor">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($doctor->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_doctor_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $doctor->nombre->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $doctor->nombre->CellAttributes() ?>>
<span id="el_doctor_nombre">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->nombre->PlaceHolder) ?>" value="<?php echo $doctor->nombre->EditValue ?>"<?php echo $doctor->nombre->EditAttributes() ?>>
</span>
<?php echo $doctor->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($doctor->apellido->Visible) { // apellido ?>
	<div id="r_apellido" class="form-group">
		<label id="elh_doctor_apellido" for="x_apellido" class="col-sm-2 control-label ewLabel"><?php echo $doctor->apellido->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $doctor->apellido->CellAttributes() ?>>
<span id="el_doctor_apellido">
<input type="text" data-field="x_apellido" name="x_apellido" id="x_apellido" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->apellido->PlaceHolder) ?>" value="<?php echo $doctor->apellido->EditValue ?>"<?php echo $doctor->apellido->EditAttributes() ?>>
</span>
<?php echo $doctor->apellido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($doctor->cui->Visible) { // cui ?>
	<div id="r_cui" class="form-group">
		<label id="elh_doctor_cui" for="x_cui" class="col-sm-2 control-label ewLabel"><?php echo $doctor->cui->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $doctor->cui->CellAttributes() ?>>
<span id="el_doctor_cui">
<input type="text" data-field="x_cui" name="x_cui" id="x_cui" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->cui->PlaceHolder) ?>" value="<?php echo $doctor->cui->EditValue ?>"<?php echo $doctor->cui->EditAttributes() ?>>
</span>
<?php echo $doctor->cui->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($doctor->telefono->Visible) { // telefono ?>
	<div id="r_telefono" class="form-group">
		<label id="elh_doctor_telefono" for="x_telefono" class="col-sm-2 control-label ewLabel"><?php echo $doctor->telefono->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $doctor->telefono->CellAttributes() ?>>
<span id="el_doctor_telefono">
<input type="text" data-field="x_telefono" name="x_telefono" id="x_telefono" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->telefono->PlaceHolder) ?>" value="<?php echo $doctor->telefono->EditValue ?>"<?php echo $doctor->telefono->EditAttributes() ?>>
</span>
<?php echo $doctor->telefono->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($doctor->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_doctor_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $doctor->direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $doctor->direccion->CellAttributes() ?>>
<span id="el_doctor_direccion">
<input type="text" data-field="x_direccion" name="x_direccion" id="x_direccion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($doctor->direccion->PlaceHolder) ?>" value="<?php echo $doctor->direccion->EditValue ?>"<?php echo $doctor->direccion->EditAttributes() ?>>
</span>
<?php echo $doctor->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($doctor->idturno->Visible) { // idturno ?>
	<div id="r_idturno" class="form-group">
		<label id="elh_doctor_idturno" class="col-sm-2 control-label ewLabel"><?php echo $doctor->idturno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $doctor->idturno->CellAttributes() ?>>
<?php if ($doctor->idturno->getSessionValue() <> "") { ?>
<span id="el_doctor_idturno">
<span<?php echo $doctor->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $doctor->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idturno" name="x_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->CurrentValue) ?>">
<?php } else { ?>
<span id="el_doctor_idturno">
<?php
	$wrkonchange = trim(" " . @$doctor->idturno->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$doctor->idturno->EditAttrs["onchange"] = "";
?>
<span id="as_x_idturno" style="white-space: nowrap; z-index: 8930">
	<input type="text" name="sv_x_idturno" id="sv_x_idturno" value="<?php echo $doctor->idturno->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($doctor->idturno->PlaceHolder) ?>"<?php echo $doctor->idturno->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_idturno" name="x_idturno" id="x_idturno" value="<?php echo ew_HtmlEncode($doctor->idturno->CurrentValue) ?>"<?php echo $wrkonchange ?> placeholder="<?php echo ew_HtmlEncode($doctor->idturno->PlaceHolder) ?>">
<?php
$sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld` FROM `turno`";
$sWhereWrk = "`descripcion` LIKE '{query_value}%'";

// Call Lookup selecting
$doctor->Lookup_Selecting($doctor->idturno, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_idturno" id="q_x_idturno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fdoctoredit.CreateAutoSuggest("x_idturno", true);
</script>
</span>
<?php } ?>
<?php echo $doctor->idturno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($doctor->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_doctor_estado" for="x_estado" class="col-sm-2 control-label ewLabel"><?php echo $doctor->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $doctor->estado->CellAttributes() ?>>
<span id="el_doctor_estado">
<select data-field="x_estado" id="x_estado" name="x_estado"<?php echo $doctor->estado->EditAttributes() ?>>
<?php
if (is_array($doctor->estado->EditValue)) {
	$arwrk = $doctor->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($doctor->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
<?php echo $doctor->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_iddoctor" name="x_iddoctor" id="x_iddoctor" value="<?php echo ew_HtmlEncode($doctor->iddoctor->CurrentValue) ?>">
<?php
	if (in_array("consulta", explode(",", $doctor->getCurrentDetailTable())) && $consulta->DetailEdit) {
?>
<?php if ($doctor->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("consulta", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "consultagrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fdoctoredit.Init();
</script>
<?php
$doctor_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$doctor_edit->Page_Terminate();
?>

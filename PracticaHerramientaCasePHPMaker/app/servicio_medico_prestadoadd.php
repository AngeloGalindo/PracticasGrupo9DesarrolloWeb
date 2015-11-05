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

$servicio_medico_prestado_add = NULL; // Initialize page object first

class cservicio_medico_prestado_add extends cservicio_medico_prestado {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'servicio_medico_prestado';

	// Page object name
	var $PageObjName = 'servicio_medico_prestado_add';

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

		// Table object (servicio_medico_prestado)
		if (!isset($GLOBALS["servicio_medico_prestado"]) || get_class($GLOBALS["servicio_medico_prestado"]) == "cservicio_medico_prestado") {
			$GLOBALS["servicio_medico_prestado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["servicio_medico_prestado"];
		}

		// Table object (cuenta)
		if (!isset($GLOBALS['cuenta'])) $GLOBALS['cuenta'] = new ccuenta();

		// Table object (servicio_medico)
		if (!isset($GLOBALS['servicio_medico'])) $GLOBALS['servicio_medico'] = new cservicio_medico();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'servicio_medico_prestado', TRUE);

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
			if (@$_GET["idservicio_medico_prestado"] != "") {
				$this->idservicio_medico_prestado->setQueryStringValue($_GET["idservicio_medico_prestado"]);
				$this->setKey("idservicio_medico_prestado", $this->idservicio_medico_prestado->CurrentValue); // Set up key
			} else {
				$this->setKey("idservicio_medico_prestado", ""); // Clear key
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
					$this->Page_Terminate("servicio_medico_prestadolist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "servicio_medico_prestadoview.php")
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
		$this->idcuenta->CurrentValue = NULL;
		$this->idcuenta->OldValue = $this->idcuenta->CurrentValue;
		$this->idservicio_medico->CurrentValue = 1;
		$this->costo->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idcuenta->FldIsDetailKey) {
			$this->idcuenta->setFormValue($objForm->GetValue("x_idcuenta"));
		}
		if (!$this->idservicio_medico->FldIsDetailKey) {
			$this->idservicio_medico->setFormValue($objForm->GetValue("x_idservicio_medico"));
		}
		if (!$this->costo->FldIsDetailKey) {
			$this->costo->setFormValue($objForm->GetValue("x_costo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->idcuenta->CurrentValue = $this->idcuenta->FormValue;
		$this->idservicio_medico->CurrentValue = $this->idservicio_medico->FormValue;
		$this->costo->CurrentValue = $this->costo->FormValue;
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

			// costo
			$this->costo->LinkCustomAttributes = "";
			$this->costo->HrefValue = "";
			$this->costo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// idcuenta
			$this->idcuenta->EditAttrs["class"] = "form-control";
			$this->idcuenta->EditCustomAttributes = "";
			if ($this->idcuenta->getSessionValue() <> "") {
				$this->idcuenta->CurrentValue = $this->idcuenta->getSessionValue();
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
			} else {
			if (trim(strval($this->idcuenta->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idcuenta`" . ew_SearchString("=", $this->idcuenta->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cuenta`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idcuenta, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idcuenta->EditValue = $arwrk;
			}

			// idservicio_medico
			$this->idservicio_medico->EditAttrs["class"] = "form-control";
			$this->idservicio_medico->EditCustomAttributes = "";
			if ($this->idservicio_medico->getSessionValue() <> "") {
				$this->idservicio_medico->CurrentValue = $this->idservicio_medico->getSessionValue();
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
			} else {
			if (trim(strval($this->idservicio_medico->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idservicio_medico`" . ew_SearchString("=", $this->idservicio_medico->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idservicio_medico`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `servicio_medico`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idservicio_medico, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idservicio_medico->EditValue = $arwrk;
			}

			// costo
			$this->costo->EditAttrs["class"] = "form-control";
			$this->costo->EditCustomAttributes = "";
			$this->costo->EditValue = ew_HtmlEncode($this->costo->CurrentValue);
			$this->costo->PlaceHolder = ew_RemoveHtml($this->costo->FldCaption());
			if (strval($this->costo->EditValue) <> "" && is_numeric($this->costo->EditValue)) $this->costo->EditValue = ew_FormatNumber($this->costo->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// idcuenta

			$this->idcuenta->HrefValue = "";

			// idservicio_medico
			$this->idservicio_medico->HrefValue = "";

			// costo
			$this->costo->HrefValue = "";
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
		if (!$this->idservicio_medico->FldIsDetailKey && !is_null($this->idservicio_medico->FormValue) && $this->idservicio_medico->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idservicio_medico->FldCaption(), $this->idservicio_medico->ReqErrMsg));
		}
		if (!$this->costo->FldIsDetailKey && !is_null($this->costo->FormValue) && $this->costo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->costo->FldCaption(), $this->costo->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->costo->FormValue)) {
			ew_AddMessage($gsFormError, $this->costo->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("doctor_servicio_medico_prestado", $DetailTblVar) && $GLOBALS["doctor_servicio_medico_prestado"]->DetailAdd) {
			if (!isset($GLOBALS["doctor_servicio_medico_prestado_grid"])) $GLOBALS["doctor_servicio_medico_prestado_grid"] = new cdoctor_servicio_medico_prestado_grid(); // get detail page object
			$GLOBALS["doctor_servicio_medico_prestado_grid"]->ValidateGridForm();
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

		// Check referential integrity for master table 'servicio_medico'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_servicio_medico();
		if (strval($this->idservicio_medico->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@idservicio_medico@", ew_AdjustSql($this->idservicio_medico->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["servicio_medico"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "servicio_medico", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// idcuenta
		$this->idcuenta->SetDbValueDef($rsnew, $this->idcuenta->CurrentValue, NULL, FALSE);

		// idservicio_medico
		$this->idservicio_medico->SetDbValueDef($rsnew, $this->idservicio_medico->CurrentValue, 0, strval($this->idservicio_medico->CurrentValue) == "");

		// costo
		$this->costo->SetDbValueDef($rsnew, $this->costo->CurrentValue, 0, strval($this->costo->CurrentValue) == "");

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
			$this->idservicio_medico_prestado->setDbValue($conn->Insert_ID());
			$rsnew['idservicio_medico_prestado'] = $this->idservicio_medico_prestado->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("doctor_servicio_medico_prestado", $DetailTblVar) && $GLOBALS["doctor_servicio_medico_prestado"]->DetailAdd) {
				$GLOBALS["doctor_servicio_medico_prestado"]->idservicio_medico_prestado->setSessionValue($this->idservicio_medico_prestado->CurrentValue); // Set master key
				if (!isset($GLOBALS["doctor_servicio_medico_prestado_grid"])) $GLOBALS["doctor_servicio_medico_prestado_grid"] = new cdoctor_servicio_medico_prestado_grid(); // Get detail page object
				$AddRow = $GLOBALS["doctor_servicio_medico_prestado_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["doctor_servicio_medico_prestado"]->idservicio_medico_prestado->setSessionValue(""); // Clear master key if insert failed
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
				if ($GLOBALS["doctor_servicio_medico_prestado_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["doctor_servicio_medico_prestado_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["doctor_servicio_medico_prestado_grid"]->CurrentMode = "add";
					$GLOBALS["doctor_servicio_medico_prestado_grid"]->CurrentAction = "gridadd";

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
if (!isset($servicio_medico_prestado_add)) $servicio_medico_prestado_add = new cservicio_medico_prestado_add();

// Page init
$servicio_medico_prestado_add->Page_Init();

// Page main
$servicio_medico_prestado_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$servicio_medico_prestado_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var servicio_medico_prestado_add = new ew_Page("servicio_medico_prestado_add");
servicio_medico_prestado_add.PageID = "add"; // Page ID
var EW_PAGE_ID = servicio_medico_prestado_add.PageID; // For backward compatibility

// Form object
var fservicio_medico_prestadoadd = new ew_Form("fservicio_medico_prestadoadd");

// Validate form
fservicio_medico_prestadoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idservicio_medico");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $servicio_medico_prestado->idservicio_medico->FldCaption(), $servicio_medico_prestado->idservicio_medico->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_costo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $servicio_medico_prestado->costo->FldCaption(), $servicio_medico_prestado->costo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_costo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($servicio_medico_prestado->costo->FldErrMsg()) ?>");

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
fservicio_medico_prestadoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fservicio_medico_prestadoadd.ValidateRequired = true;
<?php } else { ?>
fservicio_medico_prestadoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fservicio_medico_prestadoadd.Lists["x_idcuenta"] = {"LinkField":"x_idcuenta","Ajax":true,"AutoFill":false,"DisplayFields":["x_idcuenta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fservicio_medico_prestadoadd.Lists["x_idservicio_medico"] = {"LinkField":"x_idservicio_medico","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $servicio_medico_prestado_add->ShowPageHeader(); ?>
<?php
$servicio_medico_prestado_add->ShowMessage();
?>
<form name="fservicio_medico_prestadoadd" id="fservicio_medico_prestadoadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($servicio_medico_prestado_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $servicio_medico_prestado_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="servicio_medico_prestado">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($servicio_medico_prestado->idcuenta->Visible) { // idcuenta ?>
	<div id="r_idcuenta" class="form-group">
		<label id="elh_servicio_medico_prestado_idcuenta" for="x_idcuenta" class="col-sm-2 control-label ewLabel"><?php echo $servicio_medico_prestado->idcuenta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $servicio_medico_prestado->idcuenta->CellAttributes() ?>>
<?php if ($servicio_medico_prestado->idcuenta->getSessionValue() <> "") { ?>
<span id="el_servicio_medico_prestado_idcuenta">
<span<?php echo $servicio_medico_prestado->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idcuenta" name="x_idcuenta" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el_servicio_medico_prestado_idcuenta">
<select data-field="x_idcuenta" id="x_idcuenta" name="x_idcuenta"<?php echo $servicio_medico_prestado->idcuenta->EditAttributes() ?>>
<?php
if (is_array($servicio_medico_prestado->idcuenta->EditValue)) {
	$arwrk = $servicio_medico_prestado->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idcuenta`, `idcuenta` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuenta`";
$sWhereWrk = "";

// Call Lookup selecting
$servicio_medico_prestado->Lookup_Selecting($servicio_medico_prestado->idcuenta, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idcuenta" id="s_x_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $servicio_medico_prestado->idcuenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicio_medico_prestado->idservicio_medico->Visible) { // idservicio_medico ?>
	<div id="r_idservicio_medico" class="form-group">
		<label id="elh_servicio_medico_prestado_idservicio_medico" for="x_idservicio_medico" class="col-sm-2 control-label ewLabel"><?php echo $servicio_medico_prestado->idservicio_medico->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $servicio_medico_prestado->idservicio_medico->CellAttributes() ?>>
<?php if ($servicio_medico_prestado->idservicio_medico->getSessionValue() <> "") { ?>
<span id="el_servicio_medico_prestado_idservicio_medico">
<span<?php echo $servicio_medico_prestado->idservicio_medico->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $servicio_medico_prestado->idservicio_medico->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idservicio_medico" name="x_idservicio_medico" value="<?php echo ew_HtmlEncode($servicio_medico_prestado->idservicio_medico->CurrentValue) ?>">
<?php } else { ?>
<span id="el_servicio_medico_prestado_idservicio_medico">
<select data-field="x_idservicio_medico" id="x_idservicio_medico" name="x_idservicio_medico"<?php echo $servicio_medico_prestado->idservicio_medico->EditAttributes() ?>>
<?php
if (is_array($servicio_medico_prestado->idservicio_medico->EditValue)) {
	$arwrk = $servicio_medico_prestado->idservicio_medico->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($servicio_medico_prestado->idservicio_medico->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idservicio_medico`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `servicio_medico`";
$sWhereWrk = "";

// Call Lookup selecting
$servicio_medico_prestado->Lookup_Selecting($servicio_medico_prestado->idservicio_medico, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idservicio_medico" id="s_x_idservicio_medico" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idservicio_medico` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $servicio_medico_prestado->idservicio_medico->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($servicio_medico_prestado->costo->Visible) { // costo ?>
	<div id="r_costo" class="form-group">
		<label id="elh_servicio_medico_prestado_costo" for="x_costo" class="col-sm-2 control-label ewLabel"><?php echo $servicio_medico_prestado->costo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $servicio_medico_prestado->costo->CellAttributes() ?>>
<span id="el_servicio_medico_prestado_costo">
<input type="text" data-field="x_costo" name="x_costo" id="x_costo" size="30" placeholder="<?php echo ew_HtmlEncode($servicio_medico_prestado->costo->PlaceHolder) ?>" value="<?php echo $servicio_medico_prestado->costo->EditValue ?>"<?php echo $servicio_medico_prestado->costo->EditAttributes() ?>>
</span>
<?php echo $servicio_medico_prestado->costo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("doctor_servicio_medico_prestado", explode(",", $servicio_medico_prestado->getCurrentDetailTable())) && $doctor_servicio_medico_prestado->DetailAdd) {
?>
<?php if ($servicio_medico_prestado->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("doctor_servicio_medico_prestado", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "doctor_servicio_medico_prestadogrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fservicio_medico_prestadoadd.Init();
</script>
<?php
$servicio_medico_prestado_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$servicio_medico_prestado_add->Page_Terminate();
?>

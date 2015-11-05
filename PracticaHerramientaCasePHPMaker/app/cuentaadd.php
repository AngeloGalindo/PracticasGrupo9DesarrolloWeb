<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cuentainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "pacienteinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "servicio_medico_prestadogridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "consultagridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "internado_diariogridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$cuenta_add = NULL; // Initialize page object first

class ccuenta_add extends ccuenta {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'cuenta';

	// Page object name
	var $PageObjName = 'cuenta_add';

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

		// Table object (cuenta)
		if (!isset($GLOBALS["cuenta"]) || get_class($GLOBALS["cuenta"]) == "ccuenta") {
			$GLOBALS["cuenta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cuenta"];
		}

		// Table object (paciente)
		if (!isset($GLOBALS['paciente'])) $GLOBALS['paciente'] = new cpaciente();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cuenta', TRUE);

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

			// Process auto fill for detail table 'servicio_medico_prestado'
			if (@$_POST["grid"] == "fservicio_medico_prestadogrid") {
				if (!isset($GLOBALS["servicio_medico_prestado_grid"])) $GLOBALS["servicio_medico_prestado_grid"] = new cservicio_medico_prestado_grid;
				$GLOBALS["servicio_medico_prestado_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'consulta'
			if (@$_POST["grid"] == "fconsultagrid") {
				if (!isset($GLOBALS["consulta_grid"])) $GLOBALS["consulta_grid"] = new cconsulta_grid;
				$GLOBALS["consulta_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

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
		global $EW_EXPORT, $cuenta;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cuenta);
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
			if (@$_GET["idcuenta"] != "") {
				$this->idcuenta->setQueryStringValue($_GET["idcuenta"]);
				$this->setKey("idcuenta", $this->idcuenta->CurrentValue); // Set up key
			} else {
				$this->setKey("idcuenta", ""); // Clear key
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
					$this->Page_Terminate("cuentalist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "cuentaview.php")
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
		$this->idpaciente->CurrentValue = 1;
		$this->fecha_inicio->CurrentValue = date ();
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idpaciente->FldIsDetailKey) {
			$this->idpaciente->setFormValue($objForm->GetValue("x_idpaciente"));
		}
		if (!$this->fecha_inicio->FldIsDetailKey) {
			$this->fecha_inicio->setFormValue($objForm->GetValue("x_fecha_inicio"));
			$this->fecha_inicio->CurrentValue = ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->idpaciente->CurrentValue = $this->idpaciente->FormValue;
		$this->fecha_inicio->CurrentValue = $this->fecha_inicio->FormValue;
		$this->fecha_inicio->CurrentValue = ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 7);
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
		$this->idcuenta->setDbValue($rs->fields('idcuenta'));
		$this->idpaciente->setDbValue($rs->fields('idpaciente'));
		$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
		$this->fecha_final->setDbValue($rs->fields('fecha_final'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idcuenta->DbValue = $row['idcuenta'];
		$this->idpaciente->DbValue = $row['idpaciente'];
		$this->fecha_inicio->DbValue = $row['fecha_inicio'];
		$this->fecha_final->DbValue = $row['fecha_final'];
		$this->estado->DbValue = $row['estado'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idcuenta")) <> "")
			$this->idcuenta->CurrentValue = $this->getKey("idcuenta"); // idcuenta
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
		// idcuenta
		// idpaciente
		// fecha_inicio
		// fecha_final
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idcuenta
			$this->idcuenta->ViewValue = $this->idcuenta->CurrentValue;
			$this->idcuenta->ViewCustomAttributes = "";

			// idpaciente
			if (strval($this->idpaciente->CurrentValue) <> "") {
				$sFilterWrk = "`idpaciente`" . ew_SearchString("=", $this->idpaciente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
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
					$rswrk->Close();
				} else {
					$this->idpaciente->ViewValue = $this->idpaciente->CurrentValue;
				}
			} else {
				$this->idpaciente->ViewValue = NULL;
			}
			$this->idpaciente->ViewCustomAttributes = "";

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
					default:
						$this->estado->ViewValue = $this->estado->CurrentValue;
				}
			} else {
				$this->estado->ViewValue = NULL;
			}
			$this->estado->ViewCustomAttributes = "";

			// idpaciente
			$this->idpaciente->LinkCustomAttributes = "";
			$this->idpaciente->HrefValue = "";
			$this->idpaciente->TooltipValue = "";

			// fecha_inicio
			$this->fecha_inicio->LinkCustomAttributes = "";
			$this->fecha_inicio->HrefValue = "";
			$this->fecha_inicio->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// idpaciente
			$this->idpaciente->EditAttrs["class"] = "form-control";
			$this->idpaciente->EditCustomAttributes = "";
			if ($this->idpaciente->getSessionValue() <> "") {
				$this->idpaciente->CurrentValue = $this->idpaciente->getSessionValue();
			if (strval($this->idpaciente->CurrentValue) <> "") {
				$sFilterWrk = "`idpaciente`" . ew_SearchString("=", $this->idpaciente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
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
					$rswrk->Close();
				} else {
					$this->idpaciente->ViewValue = $this->idpaciente->CurrentValue;
				}
			} else {
				$this->idpaciente->ViewValue = NULL;
			}
			$this->idpaciente->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->idpaciente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idpaciente`" . ew_SearchString("=", $this->idpaciente->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `paciente`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idpaciente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idpaciente->EditValue = $arwrk;
			}

			// fecha_inicio
			$this->fecha_inicio->EditAttrs["class"] = "form-control";
			$this->fecha_inicio->EditCustomAttributes = "";
			$this->fecha_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_inicio->CurrentValue, 7));
			$this->fecha_inicio->PlaceHolder = ew_RemoveHtml($this->fecha_inicio->FldCaption());

			// Edit refer script
			// idpaciente

			$this->idpaciente->HrefValue = "";

			// fecha_inicio
			$this->fecha_inicio->HrefValue = "";
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
		if (!$this->idpaciente->FldIsDetailKey && !is_null($this->idpaciente->FormValue) && $this->idpaciente->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idpaciente->FldCaption(), $this->idpaciente->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fecha_inicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_inicio->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("servicio_medico_prestado", $DetailTblVar) && $GLOBALS["servicio_medico_prestado"]->DetailAdd) {
			if (!isset($GLOBALS["servicio_medico_prestado_grid"])) $GLOBALS["servicio_medico_prestado_grid"] = new cservicio_medico_prestado_grid(); // get detail page object
			$GLOBALS["servicio_medico_prestado_grid"]->ValidateGridForm();
		}
		if (in_array("consulta", $DetailTblVar) && $GLOBALS["consulta"]->DetailAdd) {
			if (!isset($GLOBALS["consulta_grid"])) $GLOBALS["consulta_grid"] = new cconsulta_grid(); // get detail page object
			$GLOBALS["consulta_grid"]->ValidateGridForm();
		}
		if (in_array("internado_diario", $DetailTblVar) && $GLOBALS["internado_diario"]->DetailAdd) {
			if (!isset($GLOBALS["internado_diario_grid"])) $GLOBALS["internado_diario_grid"] = new cinternado_diario_grid(); // get detail page object
			$GLOBALS["internado_diario_grid"]->ValidateGridForm();
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

		// idpaciente
		$this->idpaciente->SetDbValueDef($rsnew, $this->idpaciente->CurrentValue, 0, strval($this->idpaciente->CurrentValue) == "");

		// fecha_inicio
		$this->fecha_inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_inicio->CurrentValue, 7), NULL, FALSE);

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
			$this->idcuenta->setDbValue($conn->Insert_ID());
			$rsnew['idcuenta'] = $this->idcuenta->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("servicio_medico_prestado", $DetailTblVar) && $GLOBALS["servicio_medico_prestado"]->DetailAdd) {
				$GLOBALS["servicio_medico_prestado"]->idcuenta->setSessionValue($this->idcuenta->CurrentValue); // Set master key
				if (!isset($GLOBALS["servicio_medico_prestado_grid"])) $GLOBALS["servicio_medico_prestado_grid"] = new cservicio_medico_prestado_grid(); // Get detail page object
				$AddRow = $GLOBALS["servicio_medico_prestado_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["servicio_medico_prestado"]->idcuenta->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("consulta", $DetailTblVar) && $GLOBALS["consulta"]->DetailAdd) {
				$GLOBALS["consulta"]->idcuenta->setSessionValue($this->idcuenta->CurrentValue); // Set master key
				if (!isset($GLOBALS["consulta_grid"])) $GLOBALS["consulta_grid"] = new cconsulta_grid(); // Get detail page object
				$AddRow = $GLOBALS["consulta_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["consulta"]->idcuenta->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("internado_diario", $DetailTblVar) && $GLOBALS["internado_diario"]->DetailAdd) {
				$GLOBALS["internado_diario"]->idcuenta->setSessionValue($this->idcuenta->CurrentValue); // Set master key
				if (!isset($GLOBALS["internado_diario_grid"])) $GLOBALS["internado_diario_grid"] = new cinternado_diario_grid(); // Get detail page object
				$AddRow = $GLOBALS["internado_diario_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["internado_diario"]->idcuenta->setSessionValue(""); // Clear master key if insert failed
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

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
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
			if (in_array("servicio_medico_prestado", $DetailTblVar)) {
				if (!isset($GLOBALS["servicio_medico_prestado_grid"]))
					$GLOBALS["servicio_medico_prestado_grid"] = new cservicio_medico_prestado_grid;
				if ($GLOBALS["servicio_medico_prestado_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["servicio_medico_prestado_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["servicio_medico_prestado_grid"]->CurrentMode = "add";
					$GLOBALS["servicio_medico_prestado_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["servicio_medico_prestado_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["servicio_medico_prestado_grid"]->setStartRecordNumber(1);
					$GLOBALS["servicio_medico_prestado_grid"]->idcuenta->FldIsDetailKey = TRUE;
					$GLOBALS["servicio_medico_prestado_grid"]->idcuenta->CurrentValue = $this->idcuenta->CurrentValue;
					$GLOBALS["servicio_medico_prestado_grid"]->idcuenta->setSessionValue($GLOBALS["servicio_medico_prestado_grid"]->idcuenta->CurrentValue);
				}
			}
			if (in_array("consulta", $DetailTblVar)) {
				if (!isset($GLOBALS["consulta_grid"]))
					$GLOBALS["consulta_grid"] = new cconsulta_grid;
				if ($GLOBALS["consulta_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["consulta_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["consulta_grid"]->CurrentMode = "add";
					$GLOBALS["consulta_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["consulta_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["consulta_grid"]->setStartRecordNumber(1);
					$GLOBALS["consulta_grid"]->idcuenta->FldIsDetailKey = TRUE;
					$GLOBALS["consulta_grid"]->idcuenta->CurrentValue = $this->idcuenta->CurrentValue;
					$GLOBALS["consulta_grid"]->idcuenta->setSessionValue($GLOBALS["consulta_grid"]->idcuenta->CurrentValue);
				}
			}
			if (in_array("internado_diario", $DetailTblVar)) {
				if (!isset($GLOBALS["internado_diario_grid"]))
					$GLOBALS["internado_diario_grid"] = new cinternado_diario_grid;
				if ($GLOBALS["internado_diario_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["internado_diario_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["internado_diario_grid"]->CurrentMode = "add";
					$GLOBALS["internado_diario_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["internado_diario_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["internado_diario_grid"]->setStartRecordNumber(1);
					$GLOBALS["internado_diario_grid"]->idcuenta->FldIsDetailKey = TRUE;
					$GLOBALS["internado_diario_grid"]->idcuenta->CurrentValue = $this->idcuenta->CurrentValue;
					$GLOBALS["internado_diario_grid"]->idcuenta->setSessionValue($GLOBALS["internado_diario_grid"]->idcuenta->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "cuentalist.php", "", $this->TableVar, TRUE);
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
if (!isset($cuenta_add)) $cuenta_add = new ccuenta_add();

// Page init
$cuenta_add->Page_Init();

// Page main
$cuenta_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cuenta_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var cuenta_add = new ew_Page("cuenta_add");
cuenta_add.PageID = "add"; // Page ID
var EW_PAGE_ID = cuenta_add.PageID; // For backward compatibility

// Form object
var fcuentaadd = new ew_Form("fcuentaadd");

// Validate form
fcuentaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idpaciente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuenta->idpaciente->FldCaption(), $cuenta->idpaciente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cuenta->fecha_inicio->FldErrMsg()) ?>");

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
fcuentaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcuentaadd.ValidateRequired = true;
<?php } else { ?>
fcuentaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcuentaadd.Lists["x_idpaciente"] = {"LinkField":"x_idpaciente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $cuenta_add->ShowPageHeader(); ?>
<?php
$cuenta_add->ShowMessage();
?>
<form name="fcuentaadd" id="fcuentaadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cuenta_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cuenta_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cuenta">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($cuenta->idpaciente->Visible) { // idpaciente ?>
	<div id="r_idpaciente" class="form-group">
		<label id="elh_cuenta_idpaciente" for="x_idpaciente" class="col-sm-2 control-label ewLabel"><?php echo $cuenta->idpaciente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cuenta->idpaciente->CellAttributes() ?>>
<?php if ($cuenta->idpaciente->getSessionValue() <> "") { ?>
<span id="el_cuenta_idpaciente">
<span<?php echo $cuenta->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $cuenta->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idpaciente" name="x_idpaciente" value="<?php echo ew_HtmlEncode($cuenta->idpaciente->CurrentValue) ?>">
<?php } else { ?>
<span id="el_cuenta_idpaciente">
<select data-field="x_idpaciente" id="x_idpaciente" name="x_idpaciente"<?php echo $cuenta->idpaciente->EditAttributes() ?>>
<?php
if (is_array($cuenta->idpaciente->EditValue)) {
	$arwrk = $cuenta->idpaciente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($cuenta->idpaciente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
$sWhereWrk = "";

// Call Lookup selecting
$cuenta->Lookup_Selecting($cuenta->idpaciente, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idpaciente" id="s_x_idpaciente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpaciente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $cuenta->idpaciente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cuenta->fecha_inicio->Visible) { // fecha_inicio ?>
	<div id="r_fecha_inicio" class="form-group">
		<label id="elh_cuenta_fecha_inicio" for="x_fecha_inicio" class="col-sm-2 control-label ewLabel"><?php echo $cuenta->fecha_inicio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $cuenta->fecha_inicio->CellAttributes() ?>>
<span id="el_cuenta_fecha_inicio">
<input type="text" data-field="x_fecha_inicio" name="x_fecha_inicio" id="x_fecha_inicio" placeholder="<?php echo ew_HtmlEncode($cuenta->fecha_inicio->PlaceHolder) ?>" value="<?php echo $cuenta->fecha_inicio->EditValue ?>"<?php echo $cuenta->fecha_inicio->EditAttributes() ?>>
<?php if (!$cuenta->fecha_inicio->ReadOnly && !$cuenta->fecha_inicio->Disabled && @$cuenta->fecha_inicio->EditAttrs["readonly"] == "" && @$cuenta->fecha_inicio->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fcuentaadd", "x_fecha_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $cuenta->fecha_inicio->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("servicio_medico_prestado", explode(",", $cuenta->getCurrentDetailTable())) && $servicio_medico_prestado->DetailAdd) {
?>
<?php if ($cuenta->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("servicio_medico_prestado", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "servicio_medico_prestadogrid.php" ?>
<?php } ?>
<?php
	if (in_array("consulta", explode(",", $cuenta->getCurrentDetailTable())) && $consulta->DetailAdd) {
?>
<?php if ($cuenta->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("consulta", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "consultagrid.php" ?>
<?php } ?>
<?php
	if (in_array("internado_diario", explode(",", $cuenta->getCurrentDetailTable())) && $internado_diario->DetailAdd) {
?>
<?php if ($cuenta->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("internado_diario", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "internado_diariogrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcuentaadd.Init();
</script>
<?php
$cuenta_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$cuenta_add->Page_Terminate();
?>

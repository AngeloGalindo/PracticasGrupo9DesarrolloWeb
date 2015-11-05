<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "consultainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "cuentainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "doctorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$consulta_add = NULL; // Initialize page object first

class cconsulta_add extends cconsulta {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'consulta';

	// Page object name
	var $PageObjName = 'consulta_add';

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

		// Table object (consulta)
		if (!isset($GLOBALS["consulta"]) || get_class($GLOBALS["consulta"]) == "cconsulta") {
			$GLOBALS["consulta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["consulta"];
		}

		// Table object (cuenta)
		if (!isset($GLOBALS['cuenta'])) $GLOBALS['cuenta'] = new ccuenta();

		// Table object (doctor)
		if (!isset($GLOBALS['doctor'])) $GLOBALS['doctor'] = new cdoctor();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'consulta', TRUE);

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
		global $EW_EXPORT, $consulta;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($consulta);
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
			if (@$_GET["idconsulta"] != "") {
				$this->idconsulta->setQueryStringValue($_GET["idconsulta"]);
				$this->setKey("idconsulta", $this->idconsulta->CurrentValue); // Set up key
			} else {
				$this->setKey("idconsulta", ""); // Clear key
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
					$this->Page_Terminate("consultalist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "consultaview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
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
		$this->idcuenta->CurrentValue = 1;
		$this->fecha_cita->CurrentValue = NULL;
		$this->fecha_cita->OldValue = $this->fecha_cita->CurrentValue;
		$this->iddoctor->CurrentValue = 1;
		$this->costo->CurrentValue = NULL;
		$this->costo->OldValue = $this->costo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idcuenta->FldIsDetailKey) {
			$this->idcuenta->setFormValue($objForm->GetValue("x_idcuenta"));
		}
		if (!$this->fecha_cita->FldIsDetailKey) {
			$this->fecha_cita->setFormValue($objForm->GetValue("x_fecha_cita"));
			$this->fecha_cita->CurrentValue = ew_UnFormatDateTime($this->fecha_cita->CurrentValue, 7);
		}
		if (!$this->iddoctor->FldIsDetailKey) {
			$this->iddoctor->setFormValue($objForm->GetValue("x_iddoctor"));
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
		$this->fecha_cita->CurrentValue = $this->fecha_cita->FormValue;
		$this->fecha_cita->CurrentValue = ew_UnFormatDateTime($this->fecha_cita->CurrentValue, 7);
		$this->iddoctor->CurrentValue = $this->iddoctor->FormValue;
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
		$this->idconsulta->setDbValue($rs->fields('idconsulta'));
		$this->idcuenta->setDbValue($rs->fields('idcuenta'));
		$this->fecha_cita->setDbValue($rs->fields('fecha_cita'));
		$this->iddoctor->setDbValue($rs->fields('iddoctor'));
		$this->costo->setDbValue($rs->fields('costo'));
		$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
		$this->fecha_final->setDbValue($rs->fields('fecha_final'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idconsulta->DbValue = $row['idconsulta'];
		$this->idcuenta->DbValue = $row['idcuenta'];
		$this->fecha_cita->DbValue = $row['fecha_cita'];
		$this->iddoctor->DbValue = $row['iddoctor'];
		$this->costo->DbValue = $row['costo'];
		$this->fecha_inicio->DbValue = $row['fecha_inicio'];
		$this->fecha_final->DbValue = $row['fecha_final'];
		$this->estado->DbValue = $row['estado'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idconsulta")) <> "")
			$this->idconsulta->CurrentValue = $this->getKey("idconsulta"); // idconsulta
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
		// idconsulta
		// idcuenta
		// fecha_cita
		// iddoctor
		// costo
		// fecha_inicio
		// fecha_final
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idconsulta
			$this->idconsulta->ViewValue = $this->idconsulta->CurrentValue;
			$this->idconsulta->ViewCustomAttributes = "";

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

			// fecha_cita
			$this->fecha_cita->ViewValue = $this->fecha_cita->CurrentValue;
			$this->fecha_cita->ViewValue = ew_FormatDateTime($this->fecha_cita->ViewValue, 7);
			$this->fecha_cita->ViewCustomAttributes = "";

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

			// idcuenta
			$this->idcuenta->LinkCustomAttributes = "";
			$this->idcuenta->HrefValue = "";
			$this->idcuenta->TooltipValue = "";

			// fecha_cita
			$this->fecha_cita->LinkCustomAttributes = "";
			$this->fecha_cita->HrefValue = "";
			$this->fecha_cita->TooltipValue = "";

			// iddoctor
			$this->iddoctor->LinkCustomAttributes = "";
			$this->iddoctor->HrefValue = "";
			$this->iddoctor->TooltipValue = "";

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

			// fecha_cita
			$this->fecha_cita->EditAttrs["class"] = "form-control";
			$this->fecha_cita->EditCustomAttributes = "";
			$this->fecha_cita->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_cita->CurrentValue, 7));
			$this->fecha_cita->PlaceHolder = ew_RemoveHtml($this->fecha_cita->FldCaption());

			// iddoctor
			$this->iddoctor->EditAttrs["class"] = "form-control";
			$this->iddoctor->EditCustomAttributes = "";
			if ($this->iddoctor->getSessionValue() <> "") {
				$this->iddoctor->CurrentValue = $this->iddoctor->getSessionValue();
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
			} else {
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

			// fecha_cita
			$this->fecha_cita->HrefValue = "";

			// iddoctor
			$this->iddoctor->HrefValue = "";

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
		if (!$this->idcuenta->FldIsDetailKey && !is_null($this->idcuenta->FormValue) && $this->idcuenta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idcuenta->FldCaption(), $this->idcuenta->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fecha_cita->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_cita->FldErrMsg());
		}
		if (!ew_CheckNumber($this->costo->FormValue)) {
			ew_AddMessage($gsFormError, $this->costo->FldErrMsg());
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

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// idcuenta
		$this->idcuenta->SetDbValueDef($rsnew, $this->idcuenta->CurrentValue, 0, strval($this->idcuenta->CurrentValue) == "");

		// fecha_cita
		$this->fecha_cita->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_cita->CurrentValue, 7), NULL, FALSE);

		// iddoctor
		$this->iddoctor->SetDbValueDef($rsnew, $this->iddoctor->CurrentValue, 0, strval($this->iddoctor->CurrentValue) == "");

		// costo
		$this->costo->SetDbValueDef($rsnew, $this->costo->CurrentValue, NULL, FALSE);

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
			$this->idconsulta->setDbValue($conn->Insert_ID());
			$rsnew['idconsulta'] = $this->idconsulta->DbValue;
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
			if ($sMasterTblVar == "doctor") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_iddoctor"] <> "") {
					$GLOBALS["doctor"]->iddoctor->setQueryStringValue($_GET["fk_iddoctor"]);
					$this->iddoctor->setQueryStringValue($GLOBALS["doctor"]->iddoctor->QueryStringValue);
					$this->iddoctor->setSessionValue($this->iddoctor->QueryStringValue);
					if (!is_numeric($GLOBALS["doctor"]->iddoctor->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "doctor") {
				if ($this->iddoctor->QueryStringValue == "") $this->iddoctor->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "consultalist.php", "", $this->TableVar, TRUE);
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
if (!isset($consulta_add)) $consulta_add = new cconsulta_add();

// Page init
$consulta_add->Page_Init();

// Page main
$consulta_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$consulta_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var consulta_add = new ew_Page("consulta_add");
consulta_add.PageID = "add"; // Page ID
var EW_PAGE_ID = consulta_add.PageID; // For backward compatibility

// Form object
var fconsultaadd = new ew_Form("fconsultaadd");

// Validate form
fconsultaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idcuenta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $consulta->idcuenta->FldCaption(), $consulta->idcuenta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_cita");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($consulta->fecha_cita->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_costo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($consulta->costo->FldErrMsg()) ?>");

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
fconsultaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconsultaadd.ValidateRequired = true;
<?php } else { ?>
fconsultaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fconsultaadd.Lists["x_idcuenta"] = {"LinkField":"x_idcuenta","Ajax":true,"AutoFill":false,"DisplayFields":["x_idcuenta","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fconsultaadd.Lists["x_iddoctor"] = {"LinkField":"x_iddoctor","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $consulta_add->ShowPageHeader(); ?>
<?php
$consulta_add->ShowMessage();
?>
<form name="fconsultaadd" id="fconsultaadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($consulta_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $consulta_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="consulta">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($consulta->idcuenta->Visible) { // idcuenta ?>
	<div id="r_idcuenta" class="form-group">
		<label id="elh_consulta_idcuenta" for="x_idcuenta" class="col-sm-2 control-label ewLabel"><?php echo $consulta->idcuenta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $consulta->idcuenta->CellAttributes() ?>>
<?php if ($consulta->idcuenta->getSessionValue() <> "") { ?>
<span id="el_consulta_idcuenta">
<span<?php echo $consulta->idcuenta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->idcuenta->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idcuenta" name="x_idcuenta" value="<?php echo ew_HtmlEncode($consulta->idcuenta->CurrentValue) ?>">
<?php } else { ?>
<span id="el_consulta_idcuenta">
<select data-field="x_idcuenta" id="x_idcuenta" name="x_idcuenta"<?php echo $consulta->idcuenta->EditAttributes() ?>>
<?php
if (is_array($consulta->idcuenta->EditValue)) {
	$arwrk = $consulta->idcuenta->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->idcuenta->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$consulta->Lookup_Selecting($consulta->idcuenta, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idcuenta" id="s_x_idcuenta" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcuenta` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $consulta->idcuenta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($consulta->fecha_cita->Visible) { // fecha_cita ?>
	<div id="r_fecha_cita" class="form-group">
		<label id="elh_consulta_fecha_cita" for="x_fecha_cita" class="col-sm-2 control-label ewLabel"><?php echo $consulta->fecha_cita->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $consulta->fecha_cita->CellAttributes() ?>>
<span id="el_consulta_fecha_cita">
<input type="text" data-field="x_fecha_cita" name="x_fecha_cita" id="x_fecha_cita" placeholder="<?php echo ew_HtmlEncode($consulta->fecha_cita->PlaceHolder) ?>" value="<?php echo $consulta->fecha_cita->EditValue ?>"<?php echo $consulta->fecha_cita->EditAttributes() ?>>
<?php if (!$consulta->fecha_cita->ReadOnly && !$consulta->fecha_cita->Disabled && @$consulta->fecha_cita->EditAttrs["readonly"] == "" && @$consulta->fecha_cita->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fconsultaadd", "x_fecha_cita", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $consulta->fecha_cita->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($consulta->iddoctor->Visible) { // iddoctor ?>
	<div id="r_iddoctor" class="form-group">
		<label id="elh_consulta_iddoctor" for="x_iddoctor" class="col-sm-2 control-label ewLabel"><?php echo $consulta->iddoctor->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $consulta->iddoctor->CellAttributes() ?>>
<?php if ($consulta->iddoctor->getSessionValue() <> "") { ?>
<span id="el_consulta_iddoctor">
<span<?php echo $consulta->iddoctor->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $consulta->iddoctor->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_iddoctor" name="x_iddoctor" value="<?php echo ew_HtmlEncode($consulta->iddoctor->CurrentValue) ?>">
<?php } else { ?>
<span id="el_consulta_iddoctor">
<select data-field="x_iddoctor" id="x_iddoctor" name="x_iddoctor"<?php echo $consulta->iddoctor->EditAttributes() ?>>
<?php
if (is_array($consulta->iddoctor->EditValue)) {
	$arwrk = $consulta->iddoctor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($consulta->iddoctor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$consulta->Lookup_Selecting($consulta->iddoctor, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_iddoctor" id="s_x_iddoctor" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`iddoctor` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $consulta->iddoctor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($consulta->costo->Visible) { // costo ?>
	<div id="r_costo" class="form-group">
		<label id="elh_consulta_costo" for="x_costo" class="col-sm-2 control-label ewLabel"><?php echo $consulta->costo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $consulta->costo->CellAttributes() ?>>
<span id="el_consulta_costo">
<input type="text" data-field="x_costo" name="x_costo" id="x_costo" size="30" placeholder="<?php echo ew_HtmlEncode($consulta->costo->PlaceHolder) ?>" value="<?php echo $consulta->costo->EditValue ?>"<?php echo $consulta->costo->EditAttributes() ?>>
</span>
<?php echo $consulta->costo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fconsultaadd.Init();
</script>
<?php
$consulta_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$consulta_add->Page_Terminate();
?>

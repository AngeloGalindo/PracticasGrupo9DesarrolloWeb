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

$internado_edit = NULL; // Initialize page object first

class cinternado_edit extends cinternado {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'internado';

	// Page object name
	var $PageObjName = 'internado_edit';

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

		// Table object (internado)
		if (!isset($GLOBALS["internado"]) || get_class($GLOBALS["internado"]) == "cinternado") {
			$GLOBALS["internado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["internado"];
		}

		// Table object (habitacion)
		if (!isset($GLOBALS['habitacion'])) $GLOBALS['habitacion'] = new chabitacion();

		// Table object (paciente)
		if (!isset($GLOBALS['paciente'])) $GLOBALS['paciente'] = new cpaciente();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'internado', TRUE);

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["idinternado"] <> "") {
			$this->idinternado->setQueryStringValue($_GET["idinternado"]);
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
		if ($this->idinternado->CurrentValue == "")
			$this->Page_Terminate("internadolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("internadolist.php"); // No matching record, return to list
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
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->idhabitacion->FldIsDetailKey) {
			$this->idhabitacion->setFormValue($objForm->GetValue("x_idhabitacion"));
		}
		if (!$this->idpaciente->FldIsDetailKey) {
			$this->idpaciente->setFormValue($objForm->GetValue("x_idpaciente"));
		}
		if (!$this->es_operacion->FldIsDetailKey) {
			$this->es_operacion->setFormValue($objForm->GetValue("x_es_operacion"));
		}
		if (!$this->idinternado->FldIsDetailKey)
			$this->idinternado->setFormValue($objForm->GetValue("x_idinternado"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idinternado->CurrentValue = $this->idinternado->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->idhabitacion->CurrentValue = $this->idhabitacion->FormValue;
		$this->idpaciente->CurrentValue = $this->idpaciente->FormValue;
		$this->es_operacion->CurrentValue = $this->es_operacion->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// estado
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			$arwrk[] = array($this->estado->FldTagValue(3), $this->estado->FldTagCaption(3) <> "" ? $this->estado->FldTagCaption(3) : $this->estado->FldTagValue(3));
			$this->estado->EditValue = $arwrk;

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// idhabitacion
			$this->idhabitacion->EditAttrs["class"] = "form-control";
			$this->idhabitacion->EditCustomAttributes = "";
			if ($this->idhabitacion->getSessionValue() <> "") {
				$this->idhabitacion->CurrentValue = $this->idhabitacion->getSessionValue();
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
			} else {
			if (trim(strval($this->idhabitacion->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idhabitacion`" . ew_SearchString("=", $this->idhabitacion->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idhabitacion`, `numero` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `habitacion`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idhabitacion, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idhabitacion->EditValue = $arwrk;
			}

			// idpaciente
			$this->idpaciente->EditAttrs["class"] = "form-control";
			$this->idpaciente->EditCustomAttributes = "";
			if ($this->idpaciente->getSessionValue() <> "") {
				$this->idpaciente->CurrentValue = $this->idpaciente->getSessionValue();
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
			} else {
			if (trim(strval($this->idpaciente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idpaciente`" . ew_SearchString("=", $this->idpaciente->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `paciente`";
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

			// es_operacion
			$this->es_operacion->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->es_operacion->FldTagValue(1), $this->es_operacion->FldTagCaption(1) <> "" ? $this->es_operacion->FldTagCaption(1) : $this->es_operacion->FldTagValue(1));
			$arwrk[] = array($this->es_operacion->FldTagValue(2), $this->es_operacion->FldTagCaption(2) <> "" ? $this->es_operacion->FldTagCaption(2) : $this->es_operacion->FldTagValue(2));
			$this->es_operacion->EditValue = $arwrk;

			// Edit refer script
			// estado

			$this->estado->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// idhabitacion
			$this->idhabitacion->HrefValue = "";

			// idpaciente
			$this->idpaciente->HrefValue = "";

			// es_operacion
			$this->es_operacion->HrefValue = "";
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
		if ($this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!$this->idhabitacion->FldIsDetailKey && !is_null($this->idhabitacion->FormValue) && $this->idhabitacion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idhabitacion->FldCaption(), $this->idhabitacion->ReqErrMsg));
		}
		if (!$this->idpaciente->FldIsDetailKey && !is_null($this->idpaciente->FormValue) && $this->idpaciente->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idpaciente->FldCaption(), $this->idpaciente->ReqErrMsg));
		}
		if ($this->es_operacion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->es_operacion->FldCaption(), $this->es_operacion->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("internado_diario", $DetailTblVar) && $GLOBALS["internado_diario"]->DetailEdit) {
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

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", $this->estado->ReadOnly);

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, $this->fecha->ReadOnly);

			// idhabitacion
			$this->idhabitacion->SetDbValueDef($rsnew, $this->idhabitacion->CurrentValue, 0, $this->idhabitacion->ReadOnly);

			// idpaciente
			$this->idpaciente->SetDbValueDef($rsnew, $this->idpaciente->CurrentValue, 0, $this->idpaciente->ReadOnly);

			// es_operacion
			$this->es_operacion->SetDbValueDef($rsnew, $this->es_operacion->CurrentValue, "", $this->es_operacion->ReadOnly);

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
					if (in_array("internado_diario", $DetailTblVar) && $GLOBALS["internado_diario"]->DetailEdit) {
						if (!isset($GLOBALS["internado_diario_grid"])) $GLOBALS["internado_diario_grid"] = new cinternado_diario_grid(); // Get detail page object
						$EditRow = $GLOBALS["internado_diario_grid"]->GridUpdate();
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
				if ($GLOBALS["internado_diario_grid"]->DetailEdit) {
					$GLOBALS["internado_diario_grid"]->CurrentMode = "edit";
					$GLOBALS["internado_diario_grid"]->CurrentAction = "gridedit";

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
if (!isset($internado_edit)) $internado_edit = new cinternado_edit();

// Page init
$internado_edit->Page_Init();

// Page main
$internado_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$internado_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var internado_edit = new ew_Page("internado_edit");
internado_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = internado_edit.PageID; // For backward compatibility

// Form object
var finternadoedit = new ew_Form("finternadoedit");

// Validate form
finternadoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado->estado->FldCaption(), $internado->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($internado->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idhabitacion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado->idhabitacion->FldCaption(), $internado->idhabitacion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idpaciente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado->idpaciente->FldCaption(), $internado->idpaciente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_es_operacion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $internado->es_operacion->FldCaption(), $internado->es_operacion->ReqErrMsg)) ?>");

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
finternadoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finternadoedit.ValidateRequired = true;
<?php } else { ?>
finternadoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
finternadoedit.Lists["x_idhabitacion"] = {"LinkField":"x_idhabitacion","Ajax":true,"AutoFill":false,"DisplayFields":["x_numero","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
finternadoedit.Lists["x_idpaciente"] = {"LinkField":"x_idpaciente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","x_apellido",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $internado_edit->ShowPageHeader(); ?>
<?php
$internado_edit->ShowMessage();
?>
<form name="finternadoedit" id="finternadoedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($internado_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $internado_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="internado">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($internado->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_internado_estado" class="col-sm-2 control-label ewLabel"><?php echo $internado->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $internado->estado->CellAttributes() ?>>
<span id="el_internado_estado">
<div id="tp_x_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_estado" id="x_estado" value="{value}"<?php echo $internado->estado->EditAttributes() ?>></div>
<div id="dsl_x_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $internado->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x_estado" id="x_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $internado->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $internado->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($internado->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_internado_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $internado->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $internado->fecha->CellAttributes() ?>>
<span id="el_internado_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($internado->fecha->PlaceHolder) ?>" value="<?php echo $internado->fecha->EditValue ?>"<?php echo $internado->fecha->EditAttributes() ?>>
<?php if (!$internado->fecha->ReadOnly && !$internado->fecha->Disabled && @$internado->fecha->EditAttrs["readonly"] == "" && @$internado->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("finternadoedit", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $internado->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($internado->idhabitacion->Visible) { // idhabitacion ?>
	<div id="r_idhabitacion" class="form-group">
		<label id="elh_internado_idhabitacion" for="x_idhabitacion" class="col-sm-2 control-label ewLabel"><?php echo $internado->idhabitacion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $internado->idhabitacion->CellAttributes() ?>>
<?php if ($internado->idhabitacion->getSessionValue() <> "") { ?>
<span id="el_internado_idhabitacion">
<span<?php echo $internado->idhabitacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idhabitacion->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idhabitacion" name="x_idhabitacion" value="<?php echo ew_HtmlEncode($internado->idhabitacion->CurrentValue) ?>">
<?php } else { ?>
<span id="el_internado_idhabitacion">
<select data-field="x_idhabitacion" id="x_idhabitacion" name="x_idhabitacion"<?php echo $internado->idhabitacion->EditAttributes() ?>>
<?php
if (is_array($internado->idhabitacion->EditValue)) {
	$arwrk = $internado->idhabitacion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->idhabitacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idhabitacion`, `numero` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `habitacion`";
$sWhereWrk = "";

// Call Lookup selecting
$internado->Lookup_Selecting($internado->idhabitacion, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idhabitacion" id="s_x_idhabitacion" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhabitacion` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $internado->idhabitacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($internado->idpaciente->Visible) { // idpaciente ?>
	<div id="r_idpaciente" class="form-group">
		<label id="elh_internado_idpaciente" for="x_idpaciente" class="col-sm-2 control-label ewLabel"><?php echo $internado->idpaciente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $internado->idpaciente->CellAttributes() ?>>
<?php if ($internado->idpaciente->getSessionValue() <> "") { ?>
<span id="el_internado_idpaciente">
<span<?php echo $internado->idpaciente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $internado->idpaciente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idpaciente" name="x_idpaciente" value="<?php echo ew_HtmlEncode($internado->idpaciente->CurrentValue) ?>">
<?php } else { ?>
<span id="el_internado_idpaciente">
<select data-field="x_idpaciente" id="x_idpaciente" name="x_idpaciente"<?php echo $internado->idpaciente->EditAttributes() ?>>
<?php
if (is_array($internado->idpaciente->EditValue)) {
	$arwrk = $internado->idpaciente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->idpaciente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idpaciente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, `apellido` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paciente`";
$sWhereWrk = "";

// Call Lookup selecting
$internado->Lookup_Selecting($internado->idpaciente, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idpaciente" id="s_x_idpaciente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idpaciente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $internado->idpaciente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($internado->es_operacion->Visible) { // es_operacion ?>
	<div id="r_es_operacion" class="form-group">
		<label id="elh_internado_es_operacion" class="col-sm-2 control-label ewLabel"><?php echo $internado->es_operacion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $internado->es_operacion->CellAttributes() ?>>
<span id="el_internado_es_operacion">
<div id="tp_x_es_operacion" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_es_operacion" id="x_es_operacion" value="{value}"<?php echo $internado->es_operacion->EditAttributes() ?>></div>
<div id="dsl_x_es_operacion" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $internado->es_operacion->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($internado->es_operacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_es_operacion" name="x_es_operacion" id="x_es_operacion_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $internado->es_operacion->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $internado->es_operacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_idinternado" name="x_idinternado" id="x_idinternado" value="<?php echo ew_HtmlEncode($internado->idinternado->CurrentValue) ?>">
<?php
	if (in_array("internado_diario", explode(",", $internado->getCurrentDetailTable())) && $internado_diario->DetailEdit) {
?>
<?php if ($internado->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("internado_diario", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "internado_diariogrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
finternadoedit.Init();
</script>
<?php
$internado_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$internado_edit->Page_Terminate();
?>

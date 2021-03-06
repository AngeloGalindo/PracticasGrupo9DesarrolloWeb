<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "paisinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "continenteinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "departamentogridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "laboratoriogridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$pais_edit = NULL; // Initialize page object first

class cpais_edit extends cpais {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'pais';

	// Page object name
	var $PageObjName = 'pais_edit';

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

		// Table object (pais)
		if (!isset($GLOBALS["pais"]) || get_class($GLOBALS["pais"]) == "cpais") {
			$GLOBALS["pais"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pais"];
		}

		// Table object (continente)
		if (!isset($GLOBALS['continente'])) $GLOBALS['continente'] = new ccontinente();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pais', TRUE);

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

			// Process auto fill for detail table 'departamento'
			if (@$_POST["grid"] == "fdepartamentogrid") {
				if (!isset($GLOBALS["departamento_grid"])) $GLOBALS["departamento_grid"] = new cdepartamento_grid;
				$GLOBALS["departamento_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'laboratorio'
			if (@$_POST["grid"] == "flaboratoriogrid") {
				if (!isset($GLOBALS["laboratorio_grid"])) $GLOBALS["laboratorio_grid"] = new claboratorio_grid;
				$GLOBALS["laboratorio_grid"]->Page_Init();
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
		global $EW_EXPORT, $pais;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pais);
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
		if (@$_GET["idpais"] <> "") {
			$this->idpais->setQueryStringValue($_GET["idpais"]);
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
		if ($this->idpais->CurrentValue == "")
			$this->Page_Terminate("paislist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("paislist.php"); // No matching record, return to list
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
		if (!$this->nombre_oficial->FldIsDetailKey) {
			$this->nombre_oficial->setFormValue($objForm->GetValue("x_nombre_oficial"));
		}
		if (!$this->gentilicio->FldIsDetailKey) {
			$this->gentilicio->setFormValue($objForm->GetValue("x_gentilicio"));
		}
		if (!$this->flag->FldIsDetailKey) {
			$this->flag->setFormValue($objForm->GetValue("x_flag"));
		}
		if (!$this->idcontinente->FldIsDetailKey) {
			$this->idcontinente->setFormValue($objForm->GetValue("x_idcontinente"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->idpais->FldIsDetailKey)
			$this->idpais->setFormValue($objForm->GetValue("x_idpais"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idpais->CurrentValue = $this->idpais->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->nombre_oficial->CurrentValue = $this->nombre_oficial->FormValue;
		$this->gentilicio->CurrentValue = $this->gentilicio->FormValue;
		$this->flag->CurrentValue = $this->flag->FormValue;
		$this->idcontinente->CurrentValue = $this->idcontinente->FormValue;
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
		$this->idpais->setDbValue($rs->fields('idpais'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->nombre_oficial->setDbValue($rs->fields('nombre oficial'));
		$this->gentilicio->setDbValue($rs->fields('gentilicio'));
		$this->flag->setDbValue($rs->fields('flag'));
		$this->idcontinente->setDbValue($rs->fields('idcontinente'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idpais->DbValue = $row['idpais'];
		$this->nombre->DbValue = $row['nombre'];
		$this->nombre_oficial->DbValue = $row['nombre oficial'];
		$this->gentilicio->DbValue = $row['gentilicio'];
		$this->flag->DbValue = $row['flag'];
		$this->idcontinente->DbValue = $row['idcontinente'];
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
		// idpais
		// nombre
		// nombre oficial
		// gentilicio
		// flag
		// idcontinente
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idpais
			$this->idpais->ViewValue = $this->idpais->CurrentValue;
			$this->idpais->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// nombre oficial
			$this->nombre_oficial->ViewValue = $this->nombre_oficial->CurrentValue;
			$this->nombre_oficial->ViewCustomAttributes = "";

			// gentilicio
			$this->gentilicio->ViewValue = $this->gentilicio->CurrentValue;
			$this->gentilicio->ViewCustomAttributes = "";

			// flag
			$this->flag->ViewValue = $this->flag->CurrentValue;
			$this->flag->ViewCustomAttributes = "";

			// idcontinente
			if (strval($this->idcontinente->CurrentValue) <> "") {
				$sFilterWrk = "`idcontinente`" . ew_SearchString("=", $this->idcontinente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idcontinente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `continente`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idcontinente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idcontinente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idcontinente->ViewValue = $this->idcontinente->CurrentValue;
				}
			} else {
				$this->idcontinente->ViewValue = NULL;
			}
			$this->idcontinente->ViewCustomAttributes = "";

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

			// nombre oficial
			$this->nombre_oficial->LinkCustomAttributes = "";
			$this->nombre_oficial->HrefValue = "";
			$this->nombre_oficial->TooltipValue = "";

			// gentilicio
			$this->gentilicio->LinkCustomAttributes = "";
			$this->gentilicio->HrefValue = "";
			$this->gentilicio->TooltipValue = "";

			// flag
			$this->flag->LinkCustomAttributes = "";
			$this->flag->HrefValue = "";
			$this->flag->TooltipValue = "";

			// idcontinente
			$this->idcontinente->LinkCustomAttributes = "";
			$this->idcontinente->HrefValue = "";
			$this->idcontinente->TooltipValue = "";

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

			// nombre oficial
			$this->nombre_oficial->EditAttrs["class"] = "form-control";
			$this->nombre_oficial->EditCustomAttributes = "";
			$this->nombre_oficial->EditValue = ew_HtmlEncode($this->nombre_oficial->CurrentValue);
			$this->nombre_oficial->PlaceHolder = ew_RemoveHtml($this->nombre_oficial->FldCaption());

			// gentilicio
			$this->gentilicio->EditAttrs["class"] = "form-control";
			$this->gentilicio->EditCustomAttributes = "";
			$this->gentilicio->EditValue = ew_HtmlEncode($this->gentilicio->CurrentValue);
			$this->gentilicio->PlaceHolder = ew_RemoveHtml($this->gentilicio->FldCaption());

			// flag
			$this->flag->EditAttrs["class"] = "form-control";
			$this->flag->EditCustomAttributes = "";
			$this->flag->EditValue = ew_HtmlEncode($this->flag->CurrentValue);
			$this->flag->PlaceHolder = ew_RemoveHtml($this->flag->FldCaption());

			// idcontinente
			$this->idcontinente->EditAttrs["class"] = "form-control";
			$this->idcontinente->EditCustomAttributes = "";
			if ($this->idcontinente->getSessionValue() <> "") {
				$this->idcontinente->CurrentValue = $this->idcontinente->getSessionValue();
			if (strval($this->idcontinente->CurrentValue) <> "") {
				$sFilterWrk = "`idcontinente`" . ew_SearchString("=", $this->idcontinente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idcontinente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `continente`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idcontinente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idcontinente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idcontinente->ViewValue = $this->idcontinente->CurrentValue;
				}
			} else {
				$this->idcontinente->ViewValue = NULL;
			}
			$this->idcontinente->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->idcontinente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idcontinente`" . ew_SearchString("=", $this->idcontinente->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idcontinente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `continente`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idcontinente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idcontinente->EditValue = $arwrk;
			}

			// estado
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			$this->estado->EditValue = $arwrk;

			// Edit refer script
			// nombre

			$this->nombre->HrefValue = "";

			// nombre oficial
			$this->nombre_oficial->HrefValue = "";

			// gentilicio
			$this->gentilicio->HrefValue = "";

			// flag
			$this->flag->HrefValue = "";

			// idcontinente
			$this->idcontinente->HrefValue = "";

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
		if (!$this->idcontinente->FldIsDetailKey && !is_null($this->idcontinente->FormValue) && $this->idcontinente->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idcontinente->FldCaption(), $this->idcontinente->ReqErrMsg));
		}
		if ($this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("departamento", $DetailTblVar) && $GLOBALS["departamento"]->DetailEdit) {
			if (!isset($GLOBALS["departamento_grid"])) $GLOBALS["departamento_grid"] = new cdepartamento_grid(); // get detail page object
			$GLOBALS["departamento_grid"]->ValidateGridForm();
		}
		if (in_array("laboratorio", $DetailTblVar) && $GLOBALS["laboratorio"]->DetailEdit) {
			if (!isset($GLOBALS["laboratorio_grid"])) $GLOBALS["laboratorio_grid"] = new claboratorio_grid(); // get detail page object
			$GLOBALS["laboratorio_grid"]->ValidateGridForm();
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
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, $this->nombre->ReadOnly);

			// nombre oficial
			$this->nombre_oficial->SetDbValueDef($rsnew, $this->nombre_oficial->CurrentValue, NULL, $this->nombre_oficial->ReadOnly);

			// gentilicio
			$this->gentilicio->SetDbValueDef($rsnew, $this->gentilicio->CurrentValue, NULL, $this->gentilicio->ReadOnly);

			// flag
			$this->flag->SetDbValueDef($rsnew, $this->flag->CurrentValue, NULL, $this->flag->ReadOnly);

			// idcontinente
			$this->idcontinente->SetDbValueDef($rsnew, $this->idcontinente->CurrentValue, 0, $this->idcontinente->ReadOnly);

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
					if (in_array("departamento", $DetailTblVar) && $GLOBALS["departamento"]->DetailEdit) {
						if (!isset($GLOBALS["departamento_grid"])) $GLOBALS["departamento_grid"] = new cdepartamento_grid(); // Get detail page object
						$EditRow = $GLOBALS["departamento_grid"]->GridUpdate();
					}
					if (in_array("laboratorio", $DetailTblVar) && $GLOBALS["laboratorio"]->DetailEdit) {
						if (!isset($GLOBALS["laboratorio_grid"])) $GLOBALS["laboratorio_grid"] = new claboratorio_grid(); // Get detail page object
						$EditRow = $GLOBALS["laboratorio_grid"]->GridUpdate();
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
			if ($sMasterTblVar == "continente") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idcontinente"] <> "") {
					$GLOBALS["continente"]->idcontinente->setQueryStringValue($_GET["fk_idcontinente"]);
					$this->idcontinente->setQueryStringValue($GLOBALS["continente"]->idcontinente->QueryStringValue);
					$this->idcontinente->setSessionValue($this->idcontinente->QueryStringValue);
					if (!is_numeric($GLOBALS["continente"]->idcontinente->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "continente") {
				if ($this->idcontinente->QueryStringValue == "") $this->idcontinente->setSessionValue("");
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
			if (in_array("departamento", $DetailTblVar)) {
				if (!isset($GLOBALS["departamento_grid"]))
					$GLOBALS["departamento_grid"] = new cdepartamento_grid;
				if ($GLOBALS["departamento_grid"]->DetailEdit) {
					$GLOBALS["departamento_grid"]->CurrentMode = "edit";
					$GLOBALS["departamento_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["departamento_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["departamento_grid"]->setStartRecordNumber(1);
					$GLOBALS["departamento_grid"]->idpais->FldIsDetailKey = TRUE;
					$GLOBALS["departamento_grid"]->idpais->CurrentValue = $this->idpais->CurrentValue;
					$GLOBALS["departamento_grid"]->idpais->setSessionValue($GLOBALS["departamento_grid"]->idpais->CurrentValue);
				}
			}
			if (in_array("laboratorio", $DetailTblVar)) {
				if (!isset($GLOBALS["laboratorio_grid"]))
					$GLOBALS["laboratorio_grid"] = new claboratorio_grid;
				if ($GLOBALS["laboratorio_grid"]->DetailEdit) {
					$GLOBALS["laboratorio_grid"]->CurrentMode = "edit";
					$GLOBALS["laboratorio_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["laboratorio_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["laboratorio_grid"]->setStartRecordNumber(1);
					$GLOBALS["laboratorio_grid"]->idpais->FldIsDetailKey = TRUE;
					$GLOBALS["laboratorio_grid"]->idpais->CurrentValue = $this->idpais->CurrentValue;
					$GLOBALS["laboratorio_grid"]->idpais->setSessionValue($GLOBALS["laboratorio_grid"]->idpais->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "paislist.php", "", $this->TableVar, TRUE);
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
if (!isset($pais_edit)) $pais_edit = new cpais_edit();

// Page init
$pais_edit->Page_Init();

// Page main
$pais_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pais_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var pais_edit = new ew_Page("pais_edit");
pais_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = pais_edit.PageID; // For backward compatibility

// Form object
var fpaisedit = new ew_Form("fpaisedit");

// Validate form
fpaisedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idcontinente");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pais->idcontinente->FldCaption(), $pais->idcontinente->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pais->estado->FldCaption(), $pais->estado->ReqErrMsg)) ?>");

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
fpaisedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpaisedit.ValidateRequired = true;
<?php } else { ?>
fpaisedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpaisedit.Lists["x_idcontinente"] = {"LinkField":"x_idcontinente","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $pais_edit->ShowPageHeader(); ?>
<?php
$pais_edit->ShowMessage();
?>
<form name="fpaisedit" id="fpaisedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pais_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pais_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pais">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($pais->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_pais_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $pais->nombre->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pais->nombre->CellAttributes() ?>>
<span id="el_pais_nombre">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->nombre->PlaceHolder) ?>" value="<?php echo $pais->nombre->EditValue ?>"<?php echo $pais->nombre->EditAttributes() ?>>
</span>
<?php echo $pais->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pais->nombre_oficial->Visible) { // nombre oficial ?>
	<div id="r_nombre_oficial" class="form-group">
		<label id="elh_pais_nombre_oficial" for="x_nombre_oficial" class="col-sm-2 control-label ewLabel"><?php echo $pais->nombre_oficial->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pais->nombre_oficial->CellAttributes() ?>>
<span id="el_pais_nombre_oficial">
<input type="text" data-field="x_nombre_oficial" name="x_nombre_oficial" id="x_nombre_oficial" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->nombre_oficial->PlaceHolder) ?>" value="<?php echo $pais->nombre_oficial->EditValue ?>"<?php echo $pais->nombre_oficial->EditAttributes() ?>>
</span>
<?php echo $pais->nombre_oficial->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pais->gentilicio->Visible) { // gentilicio ?>
	<div id="r_gentilicio" class="form-group">
		<label id="elh_pais_gentilicio" for="x_gentilicio" class="col-sm-2 control-label ewLabel"><?php echo $pais->gentilicio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pais->gentilicio->CellAttributes() ?>>
<span id="el_pais_gentilicio">
<input type="text" data-field="x_gentilicio" name="x_gentilicio" id="x_gentilicio" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->gentilicio->PlaceHolder) ?>" value="<?php echo $pais->gentilicio->EditValue ?>"<?php echo $pais->gentilicio->EditAttributes() ?>>
</span>
<?php echo $pais->gentilicio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pais->flag->Visible) { // flag ?>
	<div id="r_flag" class="form-group">
		<label id="elh_pais_flag" for="x_flag" class="col-sm-2 control-label ewLabel"><?php echo $pais->flag->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pais->flag->CellAttributes() ?>>
<span id="el_pais_flag">
<input type="text" data-field="x_flag" name="x_flag" id="x_flag" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($pais->flag->PlaceHolder) ?>" value="<?php echo $pais->flag->EditValue ?>"<?php echo $pais->flag->EditAttributes() ?>>
</span>
<?php echo $pais->flag->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pais->idcontinente->Visible) { // idcontinente ?>
	<div id="r_idcontinente" class="form-group">
		<label id="elh_pais_idcontinente" for="x_idcontinente" class="col-sm-2 control-label ewLabel"><?php echo $pais->idcontinente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pais->idcontinente->CellAttributes() ?>>
<?php if ($pais->idcontinente->getSessionValue() <> "") { ?>
<span id="el_pais_idcontinente">
<span<?php echo $pais->idcontinente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pais->idcontinente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idcontinente" name="x_idcontinente" value="<?php echo ew_HtmlEncode($pais->idcontinente->CurrentValue) ?>">
<?php } else { ?>
<span id="el_pais_idcontinente">
<select data-field="x_idcontinente" id="x_idcontinente" name="x_idcontinente"<?php echo $pais->idcontinente->EditAttributes() ?>>
<?php
if (is_array($pais->idcontinente->EditValue)) {
	$arwrk = $pais->idcontinente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pais->idcontinente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idcontinente`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `continente`";
$sWhereWrk = "";

// Call Lookup selecting
$pais->Lookup_Selecting($pais->idcontinente, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idcontinente" id="s_x_idcontinente" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idcontinente` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $pais->idcontinente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pais->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_pais_estado" class="col-sm-2 control-label ewLabel"><?php echo $pais->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pais->estado->CellAttributes() ?>>
<span id="el_pais_estado">
<div id="tp_x_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_estado" id="x_estado" value="{value}"<?php echo $pais->estado->EditAttributes() ?>></div>
<div id="dsl_x_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $pais->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pais->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x_estado" id="x_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $pais->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $pais->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_idpais" name="x_idpais" id="x_idpais" value="<?php echo ew_HtmlEncode($pais->idpais->CurrentValue) ?>">
<?php
	if (in_array("departamento", explode(",", $pais->getCurrentDetailTable())) && $departamento->DetailEdit) {
?>
<?php if ($pais->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("departamento", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "departamentogrid.php" ?>
<?php } ?>
<?php
	if (in_array("laboratorio", explode(",", $pais->getCurrentDetailTable())) && $laboratorio->DetailEdit) {
?>
<?php if ($pais->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("laboratorio", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "laboratoriogrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fpaisedit.Init();
</script>
<?php
$pais_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$pais_edit->Page_Terminate();
?>

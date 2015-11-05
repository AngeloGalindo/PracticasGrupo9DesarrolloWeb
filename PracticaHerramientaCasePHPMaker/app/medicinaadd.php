<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "medicinainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "laboratorioinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "recetagridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$medicina_add = NULL; // Initialize page object first

class cmedicina_add extends cmedicina {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'medicina';

	// Page object name
	var $PageObjName = 'medicina_add';

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

		// Table object (medicina)
		if (!isset($GLOBALS["medicina"]) || get_class($GLOBALS["medicina"]) == "cmedicina") {
			$GLOBALS["medicina"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["medicina"];
		}

		// Table object (laboratorio)
		if (!isset($GLOBALS['laboratorio'])) $GLOBALS['laboratorio'] = new claboratorio();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'medicina', TRUE);

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

			// Process auto fill for detail table 'receta'
			if (@$_POST["grid"] == "frecetagrid") {
				if (!isset($GLOBALS["receta_grid"])) $GLOBALS["receta_grid"] = new creceta_grid;
				$GLOBALS["receta_grid"]->Page_Init();
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
		global $EW_EXPORT, $medicina;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($medicina);
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
			if (@$_GET["idmedicina"] != "") {
				$this->idmedicina->setQueryStringValue($_GET["idmedicina"]);
				$this->setKey("idmedicina", $this->idmedicina->CurrentValue); // Set up key
			} else {
				$this->setKey("idmedicina", ""); // Clear key
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
					$this->Page_Terminate("medicinalist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "medicinaview.php")
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
		$this->descripcion->CurrentValue = NULL;
		$this->descripcion->OldValue = $this->descripcion->CurrentValue;
		$this->estado->CurrentValue = "Activo";
		$this->idlaboratorio->CurrentValue = 1;
		$this->idhospital->CurrentValue = 1;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
		if (!$this->idlaboratorio->FldIsDetailKey) {
			$this->idlaboratorio->setFormValue($objForm->GetValue("x_idlaboratorio"));
		}
		if (!$this->idhospital->FldIsDetailKey) {
			$this->idhospital->setFormValue($objForm->GetValue("x_idhospital"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
		$this->idlaboratorio->CurrentValue = $this->idlaboratorio->FormValue;
		$this->idhospital->CurrentValue = $this->idhospital->FormValue;
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
		$this->idmedicina->setDbValue($rs->fields('idmedicina'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->idlaboratorio->setDbValue($rs->fields('idlaboratorio'));
		$this->idhospital->setDbValue($rs->fields('idhospital'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idmedicina->DbValue = $row['idmedicina'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->estado->DbValue = $row['estado'];
		$this->idlaboratorio->DbValue = $row['idlaboratorio'];
		$this->idhospital->DbValue = $row['idhospital'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idmedicina")) <> "")
			$this->idmedicina->CurrentValue = $this->getKey("idmedicina"); // idmedicina
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
		// idmedicina
		// descripcion
		// estado
		// idlaboratorio
		// idhospital

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idmedicina
			$this->idmedicina->ViewValue = $this->idmedicina->CurrentValue;
			$this->idmedicina->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
			$this->descripcion->ViewCustomAttributes = "";

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

			// idlaboratorio
			if (strval($this->idlaboratorio->CurrentValue) <> "") {
				$sFilterWrk = "`idlaboratorio`" . ew_SearchString("=", $this->idlaboratorio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idlaboratorio`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `laboratorio`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idlaboratorio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idlaboratorio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idlaboratorio->ViewValue = $this->idlaboratorio->CurrentValue;
				}
			} else {
				$this->idlaboratorio->ViewValue = NULL;
			}
			$this->idlaboratorio->ViewCustomAttributes = "";

			// idhospital
			$this->idhospital->ViewValue = $this->idhospital->CurrentValue;
			$this->idhospital->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// idlaboratorio
			$this->idlaboratorio->LinkCustomAttributes = "";
			$this->idlaboratorio->HrefValue = "";
			$this->idlaboratorio->TooltipValue = "";

			// idhospital
			$this->idhospital->LinkCustomAttributes = "";
			$this->idhospital->HrefValue = "";
			$this->idhospital->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
			$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

			// estado
			$this->estado->EditAttrs["class"] = "form-control";
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->estado->EditValue = $arwrk;

			// idlaboratorio
			$this->idlaboratorio->EditAttrs["class"] = "form-control";
			$this->idlaboratorio->EditCustomAttributes = "";
			if ($this->idlaboratorio->getSessionValue() <> "") {
				$this->idlaboratorio->CurrentValue = $this->idlaboratorio->getSessionValue();
			if (strval($this->idlaboratorio->CurrentValue) <> "") {
				$sFilterWrk = "`idlaboratorio`" . ew_SearchString("=", $this->idlaboratorio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idlaboratorio`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `laboratorio`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idlaboratorio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idlaboratorio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idlaboratorio->ViewValue = $this->idlaboratorio->CurrentValue;
				}
			} else {
				$this->idlaboratorio->ViewValue = NULL;
			}
			$this->idlaboratorio->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->idlaboratorio->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idlaboratorio`" . ew_SearchString("=", $this->idlaboratorio->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idlaboratorio`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `laboratorio`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idlaboratorio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idlaboratorio->EditValue = $arwrk;
			}

			// idhospital
			$this->idhospital->EditAttrs["class"] = "form-control";
			$this->idhospital->EditCustomAttributes = "";
			$this->idhospital->EditValue = ew_HtmlEncode($this->idhospital->CurrentValue);
			$this->idhospital->PlaceHolder = ew_RemoveHtml($this->idhospital->FldCaption());

			// Edit refer script
			// descripcion

			$this->descripcion->HrefValue = "";

			// estado
			$this->estado->HrefValue = "";

			// idlaboratorio
			$this->idlaboratorio->HrefValue = "";

			// idhospital
			$this->idhospital->HrefValue = "";
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
		if (!$this->estado->FldIsDetailKey && !is_null($this->estado->FormValue) && $this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}
		if (!$this->idlaboratorio->FldIsDetailKey && !is_null($this->idlaboratorio->FormValue) && $this->idlaboratorio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idlaboratorio->FldCaption(), $this->idlaboratorio->ReqErrMsg));
		}
		if (!$this->idhospital->FldIsDetailKey && !is_null($this->idhospital->FormValue) && $this->idhospital->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idhospital->FldCaption(), $this->idhospital->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->idhospital->FormValue)) {
			ew_AddMessage($gsFormError, $this->idhospital->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("receta", $DetailTblVar) && $GLOBALS["receta"]->DetailAdd) {
			if (!isset($GLOBALS["receta_grid"])) $GLOBALS["receta_grid"] = new creceta_grid(); // get detail page object
			$GLOBALS["receta_grid"]->ValidateGridForm();
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

		// descripcion
		$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, NULL, FALSE);

		// estado
		$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", strval($this->estado->CurrentValue) == "");

		// idlaboratorio
		$this->idlaboratorio->SetDbValueDef($rsnew, $this->idlaboratorio->CurrentValue, 0, strval($this->idlaboratorio->CurrentValue) == "");

		// idhospital
		$this->idhospital->SetDbValueDef($rsnew, $this->idhospital->CurrentValue, 0, strval($this->idhospital->CurrentValue) == "");

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
			$this->idmedicina->setDbValue($conn->Insert_ID());
			$rsnew['idmedicina'] = $this->idmedicina->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("receta", $DetailTblVar) && $GLOBALS["receta"]->DetailAdd) {
				$GLOBALS["receta"]->idmedicina->setSessionValue($this->idmedicina->CurrentValue); // Set master key
				if (!isset($GLOBALS["receta_grid"])) $GLOBALS["receta_grid"] = new creceta_grid(); // Get detail page object
				$AddRow = $GLOBALS["receta_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["receta"]->idmedicina->setSessionValue(""); // Clear master key if insert failed
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
			if ($sMasterTblVar == "laboratorio") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idlaboratorio"] <> "") {
					$GLOBALS["laboratorio"]->idlaboratorio->setQueryStringValue($_GET["fk_idlaboratorio"]);
					$this->idlaboratorio->setQueryStringValue($GLOBALS["laboratorio"]->idlaboratorio->QueryStringValue);
					$this->idlaboratorio->setSessionValue($this->idlaboratorio->QueryStringValue);
					if (!is_numeric($GLOBALS["laboratorio"]->idlaboratorio->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "laboratorio") {
				if ($this->idlaboratorio->QueryStringValue == "") $this->idlaboratorio->setSessionValue("");
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
			if (in_array("receta", $DetailTblVar)) {
				if (!isset($GLOBALS["receta_grid"]))
					$GLOBALS["receta_grid"] = new creceta_grid;
				if ($GLOBALS["receta_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["receta_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["receta_grid"]->CurrentMode = "add";
					$GLOBALS["receta_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["receta_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["receta_grid"]->setStartRecordNumber(1);
					$GLOBALS["receta_grid"]->idmedicina->FldIsDetailKey = TRUE;
					$GLOBALS["receta_grid"]->idmedicina->CurrentValue = $this->idmedicina->CurrentValue;
					$GLOBALS["receta_grid"]->idmedicina->setSessionValue($GLOBALS["receta_grid"]->idmedicina->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "medicinalist.php", "", $this->TableVar, TRUE);
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
if (!isset($medicina_add)) $medicina_add = new cmedicina_add();

// Page init
$medicina_add->Page_Init();

// Page main
$medicina_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$medicina_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var medicina_add = new ew_Page("medicina_add");
medicina_add.PageID = "add"; // Page ID
var EW_PAGE_ID = medicina_add.PageID; // For backward compatibility

// Form object
var fmedicinaadd = new ew_Form("fmedicinaadd");

// Validate form
fmedicinaadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $medicina->estado->FldCaption(), $medicina->estado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idlaboratorio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $medicina->idlaboratorio->FldCaption(), $medicina->idlaboratorio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idhospital");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $medicina->idhospital->FldCaption(), $medicina->idhospital->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idhospital");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($medicina->idhospital->FldErrMsg()) ?>");

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
fmedicinaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmedicinaadd.ValidateRequired = true;
<?php } else { ?>
fmedicinaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmedicinaadd.Lists["x_idlaboratorio"] = {"LinkField":"x_idlaboratorio","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $medicina_add->ShowPageHeader(); ?>
<?php
$medicina_add->ShowMessage();
?>
<form name="fmedicinaadd" id="fmedicinaadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($medicina_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $medicina_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="medicina">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($medicina->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label id="elh_medicina_descripcion" for="x_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $medicina->descripcion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $medicina->descripcion->CellAttributes() ?>>
<span id="el_medicina_descripcion">
<input type="text" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($medicina->descripcion->PlaceHolder) ?>" value="<?php echo $medicina->descripcion->EditValue ?>"<?php echo $medicina->descripcion->EditAttributes() ?>>
</span>
<?php echo $medicina->descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($medicina->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_medicina_estado" for="x_estado" class="col-sm-2 control-label ewLabel"><?php echo $medicina->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $medicina->estado->CellAttributes() ?>>
<span id="el_medicina_estado">
<select data-field="x_estado" id="x_estado" name="x_estado"<?php echo $medicina->estado->EditAttributes() ?>>
<?php
if (is_array($medicina->estado->EditValue)) {
	$arwrk = $medicina->estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($medicina->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $medicina->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($medicina->idlaboratorio->Visible) { // idlaboratorio ?>
	<div id="r_idlaboratorio" class="form-group">
		<label id="elh_medicina_idlaboratorio" for="x_idlaboratorio" class="col-sm-2 control-label ewLabel"><?php echo $medicina->idlaboratorio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $medicina->idlaboratorio->CellAttributes() ?>>
<?php if ($medicina->idlaboratorio->getSessionValue() <> "") { ?>
<span id="el_medicina_idlaboratorio">
<span<?php echo $medicina->idlaboratorio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $medicina->idlaboratorio->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idlaboratorio" name="x_idlaboratorio" value="<?php echo ew_HtmlEncode($medicina->idlaboratorio->CurrentValue) ?>">
<?php } else { ?>
<span id="el_medicina_idlaboratorio">
<select data-field="x_idlaboratorio" id="x_idlaboratorio" name="x_idlaboratorio"<?php echo $medicina->idlaboratorio->EditAttributes() ?>>
<?php
if (is_array($medicina->idlaboratorio->EditValue)) {
	$arwrk = $medicina->idlaboratorio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($medicina->idlaboratorio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idlaboratorio`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `laboratorio`";
$sWhereWrk = "";

// Call Lookup selecting
$medicina->Lookup_Selecting($medicina->idlaboratorio, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idlaboratorio" id="s_x_idlaboratorio" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idlaboratorio` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $medicina->idlaboratorio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($medicina->idhospital->Visible) { // idhospital ?>
	<div id="r_idhospital" class="form-group">
		<label id="elh_medicina_idhospital" for="x_idhospital" class="col-sm-2 control-label ewLabel"><?php echo $medicina->idhospital->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $medicina->idhospital->CellAttributes() ?>>
<span id="el_medicina_idhospital">
<input type="text" data-field="x_idhospital" name="x_idhospital" id="x_idhospital" size="30" placeholder="<?php echo ew_HtmlEncode($medicina->idhospital->PlaceHolder) ?>" value="<?php echo $medicina->idhospital->EditValue ?>"<?php echo $medicina->idhospital->EditAttributes() ?>>
</span>
<?php echo $medicina->idhospital->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("receta", explode(",", $medicina->getCurrentDetailTable())) && $receta->DetailAdd) {
?>
<?php if ($medicina->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("receta", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "recetagrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fmedicinaadd.Init();
</script>
<?php
$medicina_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$medicina_add->Page_Terminate();
?>

<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "turnoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "hospitalinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "tipo_turnoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "doctorgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "recetagridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$turno_add = NULL; // Initialize page object first

class cturno_add extends cturno {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'turno';

	// Page object name
	var $PageObjName = 'turno_add';

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

		// Table object (turno)
		if (!isset($GLOBALS["turno"]) || get_class($GLOBALS["turno"]) == "cturno") {
			$GLOBALS["turno"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["turno"];
		}

		// Table object (hospital)
		if (!isset($GLOBALS['hospital'])) $GLOBALS['hospital'] = new chospital();

		// Table object (tipo_turno)
		if (!isset($GLOBALS['tipo_turno'])) $GLOBALS['tipo_turno'] = new ctipo_turno();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'turno', TRUE);

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

			// Process auto fill for detail table 'doctor'
			if (@$_POST["grid"] == "fdoctorgrid") {
				if (!isset($GLOBALS["doctor_grid"])) $GLOBALS["doctor_grid"] = new cdoctor_grid;
				$GLOBALS["doctor_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

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
		global $EW_EXPORT, $turno;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($turno);
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
			if (@$_GET["idturno"] != "") {
				$this->idturno->setQueryStringValue($_GET["idturno"]);
				$this->setKey("idturno", $this->idturno->CurrentValue); // Set up key
			} else {
				$this->setKey("idturno", ""); // Clear key
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
					$this->Page_Terminate("turnolist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "turnoview.php")
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
		$this->idtipo_turno->CurrentValue = 1;
		$this->idhospital->CurrentValue = 1;
		$this->estado->CurrentValue = "Activo";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->idtipo_turno->FldIsDetailKey) {
			$this->idtipo_turno->setFormValue($objForm->GetValue("x_idtipo_turno"));
		}
		if (!$this->idhospital->FldIsDetailKey) {
			$this->idhospital->setFormValue($objForm->GetValue("x_idhospital"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->idtipo_turno->CurrentValue = $this->idtipo_turno->FormValue;
		$this->idhospital->CurrentValue = $this->idhospital->FormValue;
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
		$this->idturno->setDbValue($rs->fields('idturno'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->idtipo_turno->setDbValue($rs->fields('idtipo_turno'));
		$this->idhospital->setDbValue($rs->fields('idhospital'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idturno->DbValue = $row['idturno'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->idtipo_turno->DbValue = $row['idtipo_turno'];
		$this->idhospital->DbValue = $row['idhospital'];
		$this->estado->DbValue = $row['estado'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idturno")) <> "")
			$this->idturno->CurrentValue = $this->getKey("idturno"); // idturno
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
		// idturno
		// descripcion
		// idtipo_turno
		// idhospital
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idturno
			$this->idturno->ViewValue = $this->idturno->CurrentValue;
			$this->idturno->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
			$this->descripcion->ViewCustomAttributes = "";

			// idtipo_turno
			if (strval($this->idtipo_turno->CurrentValue) <> "") {
				$sFilterWrk = "`idtipo_turno`" . ew_SearchString("=", $this->idtipo_turno->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idtipo_turno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_turno`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idtipo_turno, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idtipo_turno->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idtipo_turno->ViewValue = $this->idtipo_turno->CurrentValue;
				}
			} else {
				$this->idtipo_turno->ViewValue = NULL;
			}
			$this->idtipo_turno->ViewCustomAttributes = "";

			// idhospital
			if (strval($this->idhospital->CurrentValue) <> "") {
				$sFilterWrk = "`idhospital`" . ew_SearchString("=", $this->idhospital->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idhospital, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idhospital->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idhospital->ViewValue = $this->idhospital->CurrentValue;
				}
			} else {
				$this->idhospital->ViewValue = NULL;
			}
			$this->idhospital->ViewCustomAttributes = "";

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

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// idtipo_turno
			$this->idtipo_turno->LinkCustomAttributes = "";
			$this->idtipo_turno->HrefValue = "";
			$this->idtipo_turno->TooltipValue = "";

			// idhospital
			$this->idhospital->LinkCustomAttributes = "";
			$this->idhospital->HrefValue = "";
			$this->idhospital->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);
			$this->descripcion->PlaceHolder = ew_RemoveHtml($this->descripcion->FldCaption());

			// idtipo_turno
			$this->idtipo_turno->EditAttrs["class"] = "form-control";
			$this->idtipo_turno->EditCustomAttributes = "";
			if ($this->idtipo_turno->getSessionValue() <> "") {
				$this->idtipo_turno->CurrentValue = $this->idtipo_turno->getSessionValue();
			if (strval($this->idtipo_turno->CurrentValue) <> "") {
				$sFilterWrk = "`idtipo_turno`" . ew_SearchString("=", $this->idtipo_turno->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idtipo_turno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_turno`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idtipo_turno, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idtipo_turno->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idtipo_turno->ViewValue = $this->idtipo_turno->CurrentValue;
				}
			} else {
				$this->idtipo_turno->ViewValue = NULL;
			}
			$this->idtipo_turno->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->idtipo_turno->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idtipo_turno`" . ew_SearchString("=", $this->idtipo_turno->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idtipo_turno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_turno`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idtipo_turno, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idtipo_turno->EditValue = $arwrk;
			}

			// idhospital
			$this->idhospital->EditAttrs["class"] = "form-control";
			$this->idhospital->EditCustomAttributes = "";
			if ($this->idhospital->getSessionValue() <> "") {
				$this->idhospital->CurrentValue = $this->idhospital->getSessionValue();
			if (strval($this->idhospital->CurrentValue) <> "") {
				$sFilterWrk = "`idhospital`" . ew_SearchString("=", $this->idhospital->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idhospital, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idhospital->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idhospital->ViewValue = $this->idhospital->CurrentValue;
				}
			} else {
				$this->idhospital->ViewValue = NULL;
			}
			$this->idhospital->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->idhospital->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idhospital`" . ew_SearchString("=", $this->idhospital->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `hospital`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idhospital, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idhospital->EditValue = $arwrk;
			}

			// estado
			$this->estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
			$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
			$this->estado->EditValue = $arwrk;

			// Edit refer script
			// descripcion

			$this->descripcion->HrefValue = "";

			// idtipo_turno
			$this->idtipo_turno->HrefValue = "";

			// idhospital
			$this->idhospital->HrefValue = "";

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
		if (!$this->idtipo_turno->FldIsDetailKey && !is_null($this->idtipo_turno->FormValue) && $this->idtipo_turno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idtipo_turno->FldCaption(), $this->idtipo_turno->ReqErrMsg));
		}
		if (!$this->idhospital->FldIsDetailKey && !is_null($this->idhospital->FormValue) && $this->idhospital->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idhospital->FldCaption(), $this->idhospital->ReqErrMsg));
		}
		if ($this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("doctor", $DetailTblVar) && $GLOBALS["doctor"]->DetailAdd) {
			if (!isset($GLOBALS["doctor_grid"])) $GLOBALS["doctor_grid"] = new cdoctor_grid(); // get detail page object
			$GLOBALS["doctor_grid"]->ValidateGridForm();
		}
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

		// idtipo_turno
		$this->idtipo_turno->SetDbValueDef($rsnew, $this->idtipo_turno->CurrentValue, 0, strval($this->idtipo_turno->CurrentValue) == "");

		// idhospital
		$this->idhospital->SetDbValueDef($rsnew, $this->idhospital->CurrentValue, 0, strval($this->idhospital->CurrentValue) == "");

		// estado
		$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, "", strval($this->estado->CurrentValue) == "");

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
			$this->idturno->setDbValue($conn->Insert_ID());
			$rsnew['idturno'] = $this->idturno->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("doctor", $DetailTblVar) && $GLOBALS["doctor"]->DetailAdd) {
				$GLOBALS["doctor"]->idturno->setSessionValue($this->idturno->CurrentValue); // Set master key
				if (!isset($GLOBALS["doctor_grid"])) $GLOBALS["doctor_grid"] = new cdoctor_grid(); // Get detail page object
				$AddRow = $GLOBALS["doctor_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["doctor"]->idturno->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("receta", $DetailTblVar) && $GLOBALS["receta"]->DetailAdd) {
				$GLOBALS["receta"]->idturno->setSessionValue($this->idturno->CurrentValue); // Set master key
				if (!isset($GLOBALS["receta_grid"])) $GLOBALS["receta_grid"] = new creceta_grid(); // Get detail page object
				$AddRow = $GLOBALS["receta_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["receta"]->idturno->setSessionValue(""); // Clear master key if insert failed
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
			if ($sMasterTblVar == "hospital") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idhospital"] <> "") {
					$GLOBALS["hospital"]->idhospital->setQueryStringValue($_GET["fk_idhospital"]);
					$this->idhospital->setQueryStringValue($GLOBALS["hospital"]->idhospital->QueryStringValue);
					$this->idhospital->setSessionValue($this->idhospital->QueryStringValue);
					if (!is_numeric($GLOBALS["hospital"]->idhospital->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "tipo_turno") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idtipo_turno"] <> "") {
					$GLOBALS["tipo_turno"]->idtipo_turno->setQueryStringValue($_GET["fk_idtipo_turno"]);
					$this->idtipo_turno->setQueryStringValue($GLOBALS["tipo_turno"]->idtipo_turno->QueryStringValue);
					$this->idtipo_turno->setSessionValue($this->idtipo_turno->QueryStringValue);
					if (!is_numeric($GLOBALS["tipo_turno"]->idtipo_turno->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "hospital") {
				if ($this->idhospital->QueryStringValue == "") $this->idhospital->setSessionValue("");
			}
			if ($sMasterTblVar <> "tipo_turno") {
				if ($this->idtipo_turno->QueryStringValue == "") $this->idtipo_turno->setSessionValue("");
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
				if ($GLOBALS["doctor_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["doctor_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["doctor_grid"]->CurrentMode = "add";
					$GLOBALS["doctor_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["doctor_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["doctor_grid"]->setStartRecordNumber(1);
					$GLOBALS["doctor_grid"]->idturno->FldIsDetailKey = TRUE;
					$GLOBALS["doctor_grid"]->idturno->CurrentValue = $this->idturno->CurrentValue;
					$GLOBALS["doctor_grid"]->idturno->setSessionValue($GLOBALS["doctor_grid"]->idturno->CurrentValue);
				}
			}
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
					$GLOBALS["receta_grid"]->idturno->FldIsDetailKey = TRUE;
					$GLOBALS["receta_grid"]->idturno->CurrentValue = $this->idturno->CurrentValue;
					$GLOBALS["receta_grid"]->idturno->setSessionValue($GLOBALS["receta_grid"]->idturno->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "turnolist.php", "", $this->TableVar, TRUE);
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
if (!isset($turno_add)) $turno_add = new cturno_add();

// Page init
$turno_add->Page_Init();

// Page main
$turno_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$turno_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var turno_add = new ew_Page("turno_add");
turno_add.PageID = "add"; // Page ID
var EW_PAGE_ID = turno_add.PageID; // For backward compatibility

// Form object
var fturnoadd = new ew_Form("fturnoadd");

// Validate form
fturnoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idtipo_turno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $turno->idtipo_turno->FldCaption(), $turno->idtipo_turno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idhospital");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $turno->idhospital->FldCaption(), $turno->idhospital->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $turno->estado->FldCaption(), $turno->estado->ReqErrMsg)) ?>");

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
fturnoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fturnoadd.ValidateRequired = true;
<?php } else { ?>
fturnoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fturnoadd.Lists["x_idtipo_turno"] = {"LinkField":"x_idtipo_turno","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fturnoadd.Lists["x_idhospital"] = {"LinkField":"x_idhospital","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $turno_add->ShowPageHeader(); ?>
<?php
$turno_add->ShowMessage();
?>
<form name="fturnoadd" id="fturnoadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($turno_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $turno_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="turno">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($turno->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label id="elh_turno_descripcion" for="x_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $turno->descripcion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $turno->descripcion->CellAttributes() ?>>
<span id="el_turno_descripcion">
<input type="text" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($turno->descripcion->PlaceHolder) ?>" value="<?php echo $turno->descripcion->EditValue ?>"<?php echo $turno->descripcion->EditAttributes() ?>>
</span>
<?php echo $turno->descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($turno->idtipo_turno->Visible) { // idtipo_turno ?>
	<div id="r_idtipo_turno" class="form-group">
		<label id="elh_turno_idtipo_turno" for="x_idtipo_turno" class="col-sm-2 control-label ewLabel"><?php echo $turno->idtipo_turno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $turno->idtipo_turno->CellAttributes() ?>>
<?php if ($turno->idtipo_turno->getSessionValue() <> "") { ?>
<span id="el_turno_idtipo_turno">
<span<?php echo $turno->idtipo_turno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idtipo_turno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idtipo_turno" name="x_idtipo_turno" value="<?php echo ew_HtmlEncode($turno->idtipo_turno->CurrentValue) ?>">
<?php } else { ?>
<span id="el_turno_idtipo_turno">
<select data-field="x_idtipo_turno" id="x_idtipo_turno" name="x_idtipo_turno"<?php echo $turno->idtipo_turno->EditAttributes() ?>>
<?php
if (is_array($turno->idtipo_turno->EditValue)) {
	$arwrk = $turno->idtipo_turno->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->idtipo_turno->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idtipo_turno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_turno`";
$sWhereWrk = "";

// Call Lookup selecting
$turno->Lookup_Selecting($turno->idtipo_turno, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idtipo_turno" id="s_x_idtipo_turno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idtipo_turno` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $turno->idtipo_turno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($turno->idhospital->Visible) { // idhospital ?>
	<div id="r_idhospital" class="form-group">
		<label id="elh_turno_idhospital" for="x_idhospital" class="col-sm-2 control-label ewLabel"><?php echo $turno->idhospital->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $turno->idhospital->CellAttributes() ?>>
<?php if ($turno->idhospital->getSessionValue() <> "") { ?>
<span id="el_turno_idhospital">
<span<?php echo $turno->idhospital->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $turno->idhospital->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idhospital" name="x_idhospital" value="<?php echo ew_HtmlEncode($turno->idhospital->CurrentValue) ?>">
<?php } else { ?>
<span id="el_turno_idhospital">
<select data-field="x_idhospital" id="x_idhospital" name="x_idhospital"<?php echo $turno->idhospital->EditAttributes() ?>>
<?php
if (is_array($turno->idhospital->EditValue)) {
	$arwrk = $turno->idhospital->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->idhospital->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idhospital`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `hospital`";
$sWhereWrk = "";

// Call Lookup selecting
$turno->Lookup_Selecting($turno->idhospital, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idhospital" id="s_x_idhospital" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idhospital` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $turno->idhospital->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($turno->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_turno_estado" class="col-sm-2 control-label ewLabel"><?php echo $turno->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $turno->estado->CellAttributes() ?>>
<span id="el_turno_estado">
<div id="tp_x_estado" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_estado" id="x_estado" value="{value}"<?php echo $turno->estado->EditAttributes() ?>></div>
<div id="dsl_x_estado" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $turno->estado->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($turno->estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_estado" name="x_estado" id="x_estado_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $turno->estado->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $turno->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("doctor", explode(",", $turno->getCurrentDetailTable())) && $doctor->DetailAdd) {
?>
<?php if ($turno->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("doctor", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "doctorgrid.php" ?>
<?php } ?>
<?php
	if (in_array("receta", explode(",", $turno->getCurrentDetailTable())) && $receta->DetailAdd) {
?>
<?php if ($turno->getCurrentDetailTable() <> "") { ?>
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
fturnoadd.Init();
</script>
<?php
$turno_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$turno_add->Page_Terminate();
?>

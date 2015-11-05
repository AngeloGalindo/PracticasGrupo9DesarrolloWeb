<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "recetainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "empleadoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "turnoinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "medicinainfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$receta_add = NULL; // Initialize page object first

class creceta_add extends creceta {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'receta';

	// Page object name
	var $PageObjName = 'receta_add';

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

		// Table object (receta)
		if (!isset($GLOBALS["receta"]) || get_class($GLOBALS["receta"]) == "creceta") {
			$GLOBALS["receta"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["receta"];
		}

		// Table object (empleado)
		if (!isset($GLOBALS['empleado'])) $GLOBALS['empleado'] = new cempleado();

		// Table object (turno)
		if (!isset($GLOBALS['turno'])) $GLOBALS['turno'] = new cturno();

		// Table object (medicina)
		if (!isset($GLOBALS['medicina'])) $GLOBALS['medicina'] = new cmedicina();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'receta', TRUE);

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
		global $EW_EXPORT, $receta;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($receta);
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
			if (@$_GET["idreceta"] != "") {
				$this->idreceta->setQueryStringValue($_GET["idreceta"]);
				$this->setKey("idreceta", $this->idreceta->CurrentValue); // Set up key
			} else {
				$this->setKey("idreceta", ""); // Clear key
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
					$this->Page_Terminate("recetalist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "recetaview.php")
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
		$this->idempleado->CurrentValue = 1;
		$this->idmedicina->CurrentValue = 1;
		$this->fecha->CurrentValue = NULL;
		$this->fecha->OldValue = $this->fecha->CurrentValue;
		$this->cantidad->CurrentValue = 1;
		$this->precio_unitario->CurrentValue = 0;
		$this->idturno->CurrentValue = 1;
		$this->idcuenta->CurrentValue = 1;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idempleado->FldIsDetailKey) {
			$this->idempleado->setFormValue($objForm->GetValue("x_idempleado"));
		}
		if (!$this->idmedicina->FldIsDetailKey) {
			$this->idmedicina->setFormValue($objForm->GetValue("x_idmedicina"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->cantidad->FldIsDetailKey) {
			$this->cantidad->setFormValue($objForm->GetValue("x_cantidad"));
		}
		if (!$this->precio_unitario->FldIsDetailKey) {
			$this->precio_unitario->setFormValue($objForm->GetValue("x_precio_unitario"));
		}
		if (!$this->idturno->FldIsDetailKey) {
			$this->idturno->setFormValue($objForm->GetValue("x_idturno"));
		}
		if (!$this->idcuenta->FldIsDetailKey) {
			$this->idcuenta->setFormValue($objForm->GetValue("x_idcuenta"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->idempleado->CurrentValue = $this->idempleado->FormValue;
		$this->idmedicina->CurrentValue = $this->idmedicina->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->cantidad->CurrentValue = $this->cantidad->FormValue;
		$this->precio_unitario->CurrentValue = $this->precio_unitario->FormValue;
		$this->idturno->CurrentValue = $this->idturno->FormValue;
		$this->idcuenta->CurrentValue = $this->idcuenta->FormValue;
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
		$this->idreceta->setDbValue($rs->fields('idreceta'));
		$this->idempleado->setDbValue($rs->fields('idempleado'));
		$this->idmedicina->setDbValue($rs->fields('idmedicina'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->precio_unitario->setDbValue($rs->fields('precio_unitario'));
		$this->idturno->setDbValue($rs->fields('idturno'));
		$this->idcuenta->setDbValue($rs->fields('idcuenta'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idreceta->DbValue = $row['idreceta'];
		$this->idempleado->DbValue = $row['idempleado'];
		$this->idmedicina->DbValue = $row['idmedicina'];
		$this->fecha->DbValue = $row['fecha'];
		$this->cantidad->DbValue = $row['cantidad'];
		$this->precio_unitario->DbValue = $row['precio_unitario'];
		$this->idturno->DbValue = $row['idturno'];
		$this->idcuenta->DbValue = $row['idcuenta'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idreceta")) <> "")
			$this->idreceta->CurrentValue = $this->getKey("idreceta"); // idreceta
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

		if ($this->precio_unitario->FormValue == $this->precio_unitario->CurrentValue && is_numeric(ew_StrToFloat($this->precio_unitario->CurrentValue)))
			$this->precio_unitario->CurrentValue = ew_StrToFloat($this->precio_unitario->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idreceta
		// idempleado
		// idmedicina
		// fecha
		// cantidad
		// precio_unitario
		// idturno
		// idcuenta

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idreceta
			$this->idreceta->ViewValue = $this->idreceta->CurrentValue;
			$this->idreceta->ViewCustomAttributes = "";

			// idempleado
			if (strval($this->idempleado->CurrentValue) <> "") {
				$sFilterWrk = "`idempleado`" . ew_SearchString("=", $this->idempleado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idempleado`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idempleado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idempleado->ViewValue = $rswrk->fields('DispFld');
					$this->idempleado->ViewValue .= ew_ValueSeparator(1,$this->idempleado) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->idempleado->ViewValue = $this->idempleado->CurrentValue;
				}
			} else {
				$this->idempleado->ViewValue = NULL;
			}
			$this->idempleado->ViewCustomAttributes = "";

			// idmedicina
			if (strval($this->idmedicina->CurrentValue) <> "") {
				$sFilterWrk = "`idmedicina`" . ew_SearchString("=", $this->idmedicina->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idmedicina`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `medicina`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idmedicina, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idmedicina->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idmedicina->ViewValue = $this->idmedicina->CurrentValue;
				}
			} else {
				$this->idmedicina->ViewValue = NULL;
			}
			$this->idmedicina->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// cantidad
			$this->cantidad->ViewValue = $this->cantidad->CurrentValue;
			$this->cantidad->ViewCustomAttributes = "";

			// precio_unitario
			$this->precio_unitario->ViewValue = $this->precio_unitario->CurrentValue;
			$this->precio_unitario->ViewCustomAttributes = "";

			// idturno
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

			// idcuenta
			$this->idcuenta->ViewValue = $this->idcuenta->CurrentValue;
			$this->idcuenta->ViewCustomAttributes = "";

			// idempleado
			$this->idempleado->LinkCustomAttributes = "";
			$this->idempleado->HrefValue = "";
			$this->idempleado->TooltipValue = "";

			// idmedicina
			$this->idmedicina->LinkCustomAttributes = "";
			$this->idmedicina->HrefValue = "";
			$this->idmedicina->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// cantidad
			$this->cantidad->LinkCustomAttributes = "";
			$this->cantidad->HrefValue = "";
			$this->cantidad->TooltipValue = "";

			// precio_unitario
			$this->precio_unitario->LinkCustomAttributes = "";
			$this->precio_unitario->HrefValue = "";
			$this->precio_unitario->TooltipValue = "";

			// idturno
			$this->idturno->LinkCustomAttributes = "";
			$this->idturno->HrefValue = "";
			$this->idturno->TooltipValue = "";

			// idcuenta
			$this->idcuenta->LinkCustomAttributes = "";
			$this->idcuenta->HrefValue = "";
			$this->idcuenta->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// idempleado
			$this->idempleado->EditAttrs["class"] = "form-control";
			$this->idempleado->EditCustomAttributes = "";
			if ($this->idempleado->getSessionValue() <> "") {
				$this->idempleado->CurrentValue = $this->idempleado->getSessionValue();
			if (strval($this->idempleado->CurrentValue) <> "") {
				$sFilterWrk = "`idempleado`" . ew_SearchString("=", $this->idempleado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idempleado`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idempleado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idempleado->ViewValue = $rswrk->fields('DispFld');
					$this->idempleado->ViewValue .= ew_ValueSeparator(1,$this->idempleado) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->idempleado->ViewValue = $this->idempleado->CurrentValue;
				}
			} else {
				$this->idempleado->ViewValue = NULL;
			}
			$this->idempleado->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->idempleado->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idempleado`" . ew_SearchString("=", $this->idempleado->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idempleado`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empleado`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idempleado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idempleado->EditValue = $arwrk;
			}

			// idmedicina
			$this->idmedicina->EditAttrs["class"] = "form-control";
			$this->idmedicina->EditCustomAttributes = "";
			if ($this->idmedicina->getSessionValue() <> "") {
				$this->idmedicina->CurrentValue = $this->idmedicina->getSessionValue();
			if (strval($this->idmedicina->CurrentValue) <> "") {
				$sFilterWrk = "`idmedicina`" . ew_SearchString("=", $this->idmedicina->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idmedicina`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `medicina`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idmedicina, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idmedicina->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idmedicina->ViewValue = $this->idmedicina->CurrentValue;
				}
			} else {
				$this->idmedicina->ViewValue = NULL;
			}
			$this->idmedicina->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->idmedicina->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idmedicina`" . ew_SearchString("=", $this->idmedicina->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idmedicina`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `medicina`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idmedicina, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idmedicina->EditValue = $arwrk;
			}

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// cantidad
			$this->cantidad->EditAttrs["class"] = "form-control";
			$this->cantidad->EditCustomAttributes = "";
			$this->cantidad->EditValue = ew_HtmlEncode($this->cantidad->CurrentValue);
			$this->cantidad->PlaceHolder = ew_RemoveHtml($this->cantidad->FldCaption());

			// precio_unitario
			$this->precio_unitario->EditAttrs["class"] = "form-control";
			$this->precio_unitario->EditCustomAttributes = "";
			$this->precio_unitario->EditValue = ew_HtmlEncode($this->precio_unitario->CurrentValue);
			$this->precio_unitario->PlaceHolder = ew_RemoveHtml($this->precio_unitario->FldCaption());
			if (strval($this->precio_unitario->EditValue) <> "" && is_numeric($this->precio_unitario->EditValue)) $this->precio_unitario->EditValue = ew_FormatNumber($this->precio_unitario->EditValue, -2, -1, -2, 0);

			// idturno
			$this->idturno->EditAttrs["class"] = "form-control";
			$this->idturno->EditCustomAttributes = "";
			if ($this->idturno->getSessionValue() <> "") {
				$this->idturno->CurrentValue = $this->idturno->getSessionValue();
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
			if (trim(strval($this->idturno->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idturno`" . ew_SearchString("=", $this->idturno->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `turno`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idturno, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idturno->EditValue = $arwrk;
			}

			// idcuenta
			$this->idcuenta->EditAttrs["class"] = "form-control";
			$this->idcuenta->EditCustomAttributes = "";
			$this->idcuenta->EditValue = ew_HtmlEncode($this->idcuenta->CurrentValue);
			$this->idcuenta->PlaceHolder = ew_RemoveHtml($this->idcuenta->FldCaption());

			// Edit refer script
			// idempleado

			$this->idempleado->HrefValue = "";

			// idmedicina
			$this->idmedicina->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// cantidad
			$this->cantidad->HrefValue = "";

			// precio_unitario
			$this->precio_unitario->HrefValue = "";

			// idturno
			$this->idturno->HrefValue = "";

			// idcuenta
			$this->idcuenta->HrefValue = "";
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
		if (!$this->idempleado->FldIsDetailKey && !is_null($this->idempleado->FormValue) && $this->idempleado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idempleado->FldCaption(), $this->idempleado->ReqErrMsg));
		}
		if (!$this->idmedicina->FldIsDetailKey && !is_null($this->idmedicina->FormValue) && $this->idmedicina->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idmedicina->FldCaption(), $this->idmedicina->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!$this->cantidad->FldIsDetailKey && !is_null($this->cantidad->FormValue) && $this->cantidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cantidad->FldCaption(), $this->cantidad->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->cantidad->FormValue)) {
			ew_AddMessage($gsFormError, $this->cantidad->FldErrMsg());
		}
		if (!$this->precio_unitario->FldIsDetailKey && !is_null($this->precio_unitario->FormValue) && $this->precio_unitario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->precio_unitario->FldCaption(), $this->precio_unitario->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->precio_unitario->FormValue)) {
			ew_AddMessage($gsFormError, $this->precio_unitario->FldErrMsg());
		}
		if (!$this->idturno->FldIsDetailKey && !is_null($this->idturno->FormValue) && $this->idturno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idturno->FldCaption(), $this->idturno->ReqErrMsg));
		}
		if (!$this->idcuenta->FldIsDetailKey && !is_null($this->idcuenta->FormValue) && $this->idcuenta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->idcuenta->FldCaption(), $this->idcuenta->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->idcuenta->FormValue)) {
			ew_AddMessage($gsFormError, $this->idcuenta->FldErrMsg());
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

		// idempleado
		$this->idempleado->SetDbValueDef($rsnew, $this->idempleado->CurrentValue, 0, strval($this->idempleado->CurrentValue) == "");

		// idmedicina
		$this->idmedicina->SetDbValueDef($rsnew, $this->idmedicina->CurrentValue, 0, strval($this->idmedicina->CurrentValue) == "");

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, FALSE);

		// cantidad
		$this->cantidad->SetDbValueDef($rsnew, $this->cantidad->CurrentValue, 0, strval($this->cantidad->CurrentValue) == "");

		// precio_unitario
		$this->precio_unitario->SetDbValueDef($rsnew, $this->precio_unitario->CurrentValue, 0, strval($this->precio_unitario->CurrentValue) == "");

		// idturno
		$this->idturno->SetDbValueDef($rsnew, $this->idturno->CurrentValue, 0, strval($this->idturno->CurrentValue) == "");

		// idcuenta
		$this->idcuenta->SetDbValueDef($rsnew, $this->idcuenta->CurrentValue, 0, strval($this->idcuenta->CurrentValue) == "");

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
			$this->idreceta->setDbValue($conn->Insert_ID());
			$rsnew['idreceta'] = $this->idreceta->DbValue;
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
			if ($sMasterTblVar == "medicina") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idmedicina"] <> "") {
					$GLOBALS["medicina"]->idmedicina->setQueryStringValue($_GET["fk_idmedicina"]);
					$this->idmedicina->setQueryStringValue($GLOBALS["medicina"]->idmedicina->QueryStringValue);
					$this->idmedicina->setSessionValue($this->idmedicina->QueryStringValue);
					if (!is_numeric($GLOBALS["medicina"]->idmedicina->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "empleado") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idempleado"] <> "") {
					$GLOBALS["empleado"]->idempleado->setQueryStringValue($_GET["fk_idempleado"]);
					$this->idempleado->setQueryStringValue($GLOBALS["empleado"]->idempleado->QueryStringValue);
					$this->idempleado->setSessionValue($this->idempleado->QueryStringValue);
					if (!is_numeric($GLOBALS["empleado"]->idempleado->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
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
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "medicina") {
				if ($this->idmedicina->QueryStringValue == "") $this->idmedicina->setSessionValue("");
			}
			if ($sMasterTblVar <> "empleado") {
				if ($this->idempleado->QueryStringValue == "") $this->idempleado->setSessionValue("");
			}
			if ($sMasterTblVar <> "turno") {
				if ($this->idturno->QueryStringValue == "") $this->idturno->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "recetalist.php", "", $this->TableVar, TRUE);
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
if (!isset($receta_add)) $receta_add = new creceta_add();

// Page init
$receta_add->Page_Init();

// Page main
$receta_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$receta_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var receta_add = new ew_Page("receta_add");
receta_add.PageID = "add"; // Page ID
var EW_PAGE_ID = receta_add.PageID; // For backward compatibility

// Form object
var frecetaadd = new ew_Form("frecetaadd");

// Validate form
frecetaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_idempleado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->idempleado->FldCaption(), $receta->idempleado->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idmedicina");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->idmedicina->FldCaption(), $receta->idmedicina->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($receta->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->cantidad->FldCaption(), $receta->cantidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cantidad");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($receta->cantidad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_precio_unitario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->precio_unitario->FldCaption(), $receta->precio_unitario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_precio_unitario");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($receta->precio_unitario->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idturno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->idturno->FldCaption(), $receta->idturno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idcuenta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $receta->idcuenta->FldCaption(), $receta->idcuenta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_idcuenta");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($receta->idcuenta->FldErrMsg()) ?>");

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
frecetaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frecetaadd.ValidateRequired = true;
<?php } else { ?>
frecetaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frecetaadd.Lists["x_idempleado"] = {"LinkField":"x_idempleado","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frecetaadd.Lists["x_idmedicina"] = {"LinkField":"x_idmedicina","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frecetaadd.Lists["x_idturno"] = {"LinkField":"x_idturno","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $receta_add->ShowPageHeader(); ?>
<?php
$receta_add->ShowMessage();
?>
<form name="frecetaadd" id="frecetaadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($receta_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $receta_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="receta">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($receta->idempleado->Visible) { // idempleado ?>
	<div id="r_idempleado" class="form-group">
		<label id="elh_receta_idempleado" for="x_idempleado" class="col-sm-2 control-label ewLabel"><?php echo $receta->idempleado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $receta->idempleado->CellAttributes() ?>>
<?php if ($receta->idempleado->getSessionValue() <> "") { ?>
<span id="el_receta_idempleado">
<span<?php echo $receta->idempleado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idempleado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idempleado" name="x_idempleado" value="<?php echo ew_HtmlEncode($receta->idempleado->CurrentValue) ?>">
<?php } else { ?>
<span id="el_receta_idempleado">
<select data-field="x_idempleado" id="x_idempleado" name="x_idempleado"<?php echo $receta->idempleado->EditAttributes() ?>>
<?php
if (is_array($receta->idempleado->EditValue)) {
	$arwrk = $receta->idempleado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idempleado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$receta->idempleado) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idempleado`, `nombre` AS `DispFld`, `apellido` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleado`";
$sWhereWrk = "";

// Call Lookup selecting
$receta->Lookup_Selecting($receta->idempleado, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idempleado" id="s_x_idempleado" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idempleado` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $receta->idempleado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($receta->idmedicina->Visible) { // idmedicina ?>
	<div id="r_idmedicina" class="form-group">
		<label id="elh_receta_idmedicina" for="x_idmedicina" class="col-sm-2 control-label ewLabel"><?php echo $receta->idmedicina->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $receta->idmedicina->CellAttributes() ?>>
<?php if ($receta->idmedicina->getSessionValue() <> "") { ?>
<span id="el_receta_idmedicina">
<span<?php echo $receta->idmedicina->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idmedicina->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idmedicina" name="x_idmedicina" value="<?php echo ew_HtmlEncode($receta->idmedicina->CurrentValue) ?>">
<?php } else { ?>
<span id="el_receta_idmedicina">
<select data-field="x_idmedicina" id="x_idmedicina" name="x_idmedicina"<?php echo $receta->idmedicina->EditAttributes() ?>>
<?php
if (is_array($receta->idmedicina->EditValue)) {
	$arwrk = $receta->idmedicina->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idmedicina->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idmedicina`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `medicina`";
$sWhereWrk = "";

// Call Lookup selecting
$receta->Lookup_Selecting($receta->idmedicina, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idmedicina" id="s_x_idmedicina" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idmedicina` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $receta->idmedicina->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($receta->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_receta_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $receta->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $receta->fecha->CellAttributes() ?>>
<span id="el_receta_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($receta->fecha->PlaceHolder) ?>" value="<?php echo $receta->fecha->EditValue ?>"<?php echo $receta->fecha->EditAttributes() ?>>
<?php if (!$receta->fecha->ReadOnly && !$receta->fecha->Disabled && @$receta->fecha->EditAttrs["readonly"] == "" && @$receta->fecha->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("frecetaadd", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $receta->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($receta->cantidad->Visible) { // cantidad ?>
	<div id="r_cantidad" class="form-group">
		<label id="elh_receta_cantidad" for="x_cantidad" class="col-sm-2 control-label ewLabel"><?php echo $receta->cantidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $receta->cantidad->CellAttributes() ?>>
<span id="el_receta_cantidad">
<input type="text" data-field="x_cantidad" name="x_cantidad" id="x_cantidad" size="30" placeholder="<?php echo ew_HtmlEncode($receta->cantidad->PlaceHolder) ?>" value="<?php echo $receta->cantidad->EditValue ?>"<?php echo $receta->cantidad->EditAttributes() ?>>
</span>
<?php echo $receta->cantidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($receta->precio_unitario->Visible) { // precio_unitario ?>
	<div id="r_precio_unitario" class="form-group">
		<label id="elh_receta_precio_unitario" for="x_precio_unitario" class="col-sm-2 control-label ewLabel"><?php echo $receta->precio_unitario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $receta->precio_unitario->CellAttributes() ?>>
<span id="el_receta_precio_unitario">
<input type="text" data-field="x_precio_unitario" name="x_precio_unitario" id="x_precio_unitario" size="30" placeholder="<?php echo ew_HtmlEncode($receta->precio_unitario->PlaceHolder) ?>" value="<?php echo $receta->precio_unitario->EditValue ?>"<?php echo $receta->precio_unitario->EditAttributes() ?>>
</span>
<?php echo $receta->precio_unitario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($receta->idturno->Visible) { // idturno ?>
	<div id="r_idturno" class="form-group">
		<label id="elh_receta_idturno" for="x_idturno" class="col-sm-2 control-label ewLabel"><?php echo $receta->idturno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $receta->idturno->CellAttributes() ?>>
<?php if ($receta->idturno->getSessionValue() <> "") { ?>
<span id="el_receta_idturno">
<span<?php echo $receta->idturno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $receta->idturno->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_idturno" name="x_idturno" value="<?php echo ew_HtmlEncode($receta->idturno->CurrentValue) ?>">
<?php } else { ?>
<span id="el_receta_idturno">
<select data-field="x_idturno" id="x_idturno" name="x_idturno"<?php echo $receta->idturno->EditAttributes() ?>>
<?php
if (is_array($receta->idturno->EditValue)) {
	$arwrk = $receta->idturno->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($receta->idturno->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
$sSqlWrk = "SELECT `idturno`, `descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `turno`";
$sWhereWrk = "";

// Call Lookup selecting
$receta->Lookup_Selecting($receta->idturno, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_idturno" id="s_x_idturno" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`idturno` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php } ?>
<?php echo $receta->idturno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($receta->idcuenta->Visible) { // idcuenta ?>
	<div id="r_idcuenta" class="form-group">
		<label id="elh_receta_idcuenta" for="x_idcuenta" class="col-sm-2 control-label ewLabel"><?php echo $receta->idcuenta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $receta->idcuenta->CellAttributes() ?>>
<span id="el_receta_idcuenta">
<input type="text" data-field="x_idcuenta" name="x_idcuenta" id="x_idcuenta" size="30" placeholder="<?php echo ew_HtmlEncode($receta->idcuenta->PlaceHolder) ?>" value="<?php echo $receta->idcuenta->EditValue ?>"<?php echo $receta->idcuenta->EditAttributes() ?>>
</span>
<?php echo $receta->idcuenta->CustomMsg ?></div></div>
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
frecetaadd.Init();
</script>
<?php
$receta_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$receta_add->Page_Terminate();
?>

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

$receta_view = NULL; // Initialize page object first

class creceta_view extends creceta {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{C2CC4043-40A3-40C7-9AE6-B21670F505A9}";

	// Table name
	var $TableName = 'receta';

	// Page object name
	var $PageObjName = 'receta_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["idreceta"] <> "") {
			$this->RecKey["idreceta"] = $_GET["idreceta"];
			$KeyUrl .= "&amp;idreceta=" . urlencode($this->RecKey["idreceta"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (empleado)
		if (!isset($GLOBALS['empleado'])) $GLOBALS['empleado'] = new cempleado();

		// Table object (turno)
		if (!isset($GLOBALS['turno'])) $GLOBALS['turno'] = new cturno();

		// Table object (medicina)
		if (!isset($GLOBALS['medicina'])) $GLOBALS['medicina'] = new cmedicina();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'receta', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idreceta->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idreceta"] <> "") {
				$this->idreceta->setQueryStringValue($_GET["idreceta"]);
				$this->RecKey["idreceta"] = $this->idreceta->QueryStringValue;
			} else {
				$sReturnUrl = "recetalist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "recetalist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "recetalist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "");

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// idreceta
			$this->idreceta->LinkCustomAttributes = "";
			$this->idreceta->HrefValue = "";
			$this->idreceta->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
			$this->setSessionWhere($this->GetDetailFilter());

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
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, ew_CurrentUrl());
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($receta_view)) $receta_view = new creceta_view();

// Page init
$receta_view->Page_Init();

// Page main
$receta_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$receta_view->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var receta_view = new ew_Page("receta_view");
receta_view.PageID = "view"; // Page ID
var EW_PAGE_ID = receta_view.PageID; // For backward compatibility

// Form object
var frecetaview = new ew_Form("frecetaview");

// Form_CustomValidate event
frecetaview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frecetaview.ValidateRequired = true;
<?php } else { ?>
frecetaview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frecetaview.Lists["x_idempleado"] = {"LinkField":"x_idempleado","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellido","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frecetaview.Lists["x_idmedicina"] = {"LinkField":"x_idmedicina","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frecetaview.Lists["x_idturno"] = {"LinkField":"x_idturno","Ajax":true,"AutoFill":false,"DisplayFields":["x_descripcion","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $receta_view->ExportOptions->Render("body") ?>
<?php
	foreach ($receta_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $receta_view->ShowPageHeader(); ?>
<?php
$receta_view->ShowMessage();
?>
<form name="frecetaview" id="frecetaview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($receta_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $receta_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="receta">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($receta->idreceta->Visible) { // idreceta ?>
	<tr id="r_idreceta">
		<td><span id="elh_receta_idreceta"><?php echo $receta->idreceta->FldCaption() ?></span></td>
		<td<?php echo $receta->idreceta->CellAttributes() ?>>
<span id="el_receta_idreceta" class="form-group">
<span<?php echo $receta->idreceta->ViewAttributes() ?>>
<?php echo $receta->idreceta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($receta->idempleado->Visible) { // idempleado ?>
	<tr id="r_idempleado">
		<td><span id="elh_receta_idempleado"><?php echo $receta->idempleado->FldCaption() ?></span></td>
		<td<?php echo $receta->idempleado->CellAttributes() ?>>
<span id="el_receta_idempleado" class="form-group">
<span<?php echo $receta->idempleado->ViewAttributes() ?>>
<?php echo $receta->idempleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($receta->idmedicina->Visible) { // idmedicina ?>
	<tr id="r_idmedicina">
		<td><span id="elh_receta_idmedicina"><?php echo $receta->idmedicina->FldCaption() ?></span></td>
		<td<?php echo $receta->idmedicina->CellAttributes() ?>>
<span id="el_receta_idmedicina" class="form-group">
<span<?php echo $receta->idmedicina->ViewAttributes() ?>>
<?php echo $receta->idmedicina->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($receta->fecha->Visible) { // fecha ?>
	<tr id="r_fecha">
		<td><span id="elh_receta_fecha"><?php echo $receta->fecha->FldCaption() ?></span></td>
		<td<?php echo $receta->fecha->CellAttributes() ?>>
<span id="el_receta_fecha" class="form-group">
<span<?php echo $receta->fecha->ViewAttributes() ?>>
<?php echo $receta->fecha->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($receta->cantidad->Visible) { // cantidad ?>
	<tr id="r_cantidad">
		<td><span id="elh_receta_cantidad"><?php echo $receta->cantidad->FldCaption() ?></span></td>
		<td<?php echo $receta->cantidad->CellAttributes() ?>>
<span id="el_receta_cantidad" class="form-group">
<span<?php echo $receta->cantidad->ViewAttributes() ?>>
<?php echo $receta->cantidad->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($receta->precio_unitario->Visible) { // precio_unitario ?>
	<tr id="r_precio_unitario">
		<td><span id="elh_receta_precio_unitario"><?php echo $receta->precio_unitario->FldCaption() ?></span></td>
		<td<?php echo $receta->precio_unitario->CellAttributes() ?>>
<span id="el_receta_precio_unitario" class="form-group">
<span<?php echo $receta->precio_unitario->ViewAttributes() ?>>
<?php echo $receta->precio_unitario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($receta->idturno->Visible) { // idturno ?>
	<tr id="r_idturno">
		<td><span id="elh_receta_idturno"><?php echo $receta->idturno->FldCaption() ?></span></td>
		<td<?php echo $receta->idturno->CellAttributes() ?>>
<span id="el_receta_idturno" class="form-group">
<span<?php echo $receta->idturno->ViewAttributes() ?>>
<?php echo $receta->idturno->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($receta->idcuenta->Visible) { // idcuenta ?>
	<tr id="r_idcuenta">
		<td><span id="elh_receta_idcuenta"><?php echo $receta->idcuenta->FldCaption() ?></span></td>
		<td<?php echo $receta->idcuenta->CellAttributes() ?>>
<span id="el_receta_idcuenta" class="form-group">
<span<?php echo $receta->idcuenta->ViewAttributes() ?>>
<?php echo $receta->idcuenta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
frecetaview.Init();
</script>
<?php
$receta_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$receta_view->Page_Terminate();
?>

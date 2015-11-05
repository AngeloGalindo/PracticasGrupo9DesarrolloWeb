<?php

// Global variable for table object
$consulta = NULL;

//
// Table class for consulta
//
class cconsulta extends cTable {
	var $idconsulta;
	var $idcuenta;
	var $fecha_cita;
	var $iddoctor;
	var $costo;
	var $fecha_inicio;
	var $fecha_final;
	var $estado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'consulta';
		$this->TableName = 'consulta';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// idconsulta
		$this->idconsulta = new cField('consulta', 'consulta', 'x_idconsulta', 'idconsulta', '`idconsulta`', '`idconsulta`', 3, -1, FALSE, '`idconsulta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idconsulta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idconsulta'] = &$this->idconsulta;

		// idcuenta
		$this->idcuenta = new cField('consulta', 'consulta', 'x_idcuenta', 'idcuenta', '`idcuenta`', '`idcuenta`', 3, -1, FALSE, '`idcuenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idcuenta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idcuenta'] = &$this->idcuenta;

		// fecha_cita
		$this->fecha_cita = new cField('consulta', 'consulta', 'x_fecha_cita', 'fecha_cita', '`fecha_cita`', 'DATE_FORMAT(`fecha_cita`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fecha_cita`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_cita->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_cita'] = &$this->fecha_cita;

		// iddoctor
		$this->iddoctor = new cField('consulta', 'consulta', 'x_iddoctor', 'iddoctor', '`iddoctor`', '`iddoctor`', 3, -1, FALSE, '`iddoctor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['iddoctor'] = &$this->iddoctor;

		// costo
		$this->costo = new cField('consulta', 'consulta', 'x_costo', 'costo', '`costo`', '`costo`', 131, -1, FALSE, '`costo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->costo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['costo'] = &$this->costo;

		// fecha_inicio
		$this->fecha_inicio = new cField('consulta', 'consulta', 'x_fecha_inicio', 'fecha_inicio', '`fecha_inicio`', 'DATE_FORMAT(`fecha_inicio`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fecha_inicio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_inicio->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_inicio'] = &$this->fecha_inicio;

		// fecha_final
		$this->fecha_final = new cField('consulta', 'consulta', 'x_fecha_final', 'fecha_final', '`fecha_final`', 'DATE_FORMAT(`fecha_final`, \'%d/%m/%Y\')', 135, 7, FALSE, '`fecha_final`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_final->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_final'] = &$this->fecha_final;

		// estado
		$this->estado = new cField('consulta', 'consulta', 'x_estado', 'estado', '`estado`', '`estado`', 202, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['estado'] = &$this->estado;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "cuenta") {
			if ($this->idcuenta->getSessionValue() <> "")
				$sMasterFilter .= "`idcuenta`=" . ew_QuotedValue($this->idcuenta->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "doctor") {
			if ($this->iddoctor->getSessionValue() <> "")
				$sMasterFilter .= "`iddoctor`=" . ew_QuotedValue($this->iddoctor->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "cuenta") {
			if ($this->idcuenta->getSessionValue() <> "")
				$sDetailFilter .= "`idcuenta`=" . ew_QuotedValue($this->idcuenta->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "doctor") {
			if ($this->iddoctor->getSessionValue() <> "")
				$sDetailFilter .= "`iddoctor`=" . ew_QuotedValue($this->iddoctor->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_cuenta() {
		return "`idcuenta`=@idcuenta@";
	}

	// Detail filter
	function SqlDetailFilter_cuenta() {
		return "`idcuenta`=@idcuenta@";
	}

	// Master filter
	function SqlMasterFilter_doctor() {
		return "`iddoctor`=@iddoctor@";
	}

	// Detail filter
	function SqlDetailFilter_doctor() {
		return "`iddoctor`=@iddoctor@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`consulta`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`consulta`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('idconsulta', $rs))
				ew_AddFilter($where, ew_QuotedName('idconsulta') . '=' . ew_QuotedValue($rs['idconsulta'], $this->idconsulta->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`idconsulta` = @idconsulta@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idconsulta->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idconsulta@", ew_AdjustSql($this->idconsulta->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "consultalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "consultalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("consultaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("consultaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "consultaadd.php?" . $this->UrlParm($parm);
		else
			return "consultaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("consultaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("consultaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("consultadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idconsulta->CurrentValue)) {
			$sUrl .= "idconsulta=" . urlencode($this->idconsulta->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["idconsulta"]; // idconsulta

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->idconsulta->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->idconsulta->setDbValue($rs->fields('idconsulta'));
		$this->idcuenta->setDbValue($rs->fields('idcuenta'));
		$this->fecha_cita->setDbValue($rs->fields('fecha_cita'));
		$this->iddoctor->setDbValue($rs->fields('iddoctor'));
		$this->costo->setDbValue($rs->fields('costo'));
		$this->fecha_inicio->setDbValue($rs->fields('fecha_inicio'));
		$this->fecha_final->setDbValue($rs->fields('fecha_final'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// idconsulta
		// idcuenta
		// fecha_cita
		// iddoctor
		// costo
		// fecha_inicio
		// fecha_final
		// estado
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

		// idconsulta
		$this->idconsulta->LinkCustomAttributes = "";
		$this->idconsulta->HrefValue = "";
		$this->idconsulta->TooltipValue = "";

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

		// fecha_inicio
		$this->fecha_inicio->LinkCustomAttributes = "";
		$this->fecha_inicio->HrefValue = "";
		$this->fecha_inicio->TooltipValue = "";

		// fecha_final
		$this->fecha_final->LinkCustomAttributes = "";
		$this->fecha_final->HrefValue = "";
		$this->fecha_final->TooltipValue = "";

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// idconsulta
		$this->idconsulta->EditAttrs["class"] = "form-control";
		$this->idconsulta->EditCustomAttributes = "";
		$this->idconsulta->EditValue = $this->idconsulta->CurrentValue;
		$this->idconsulta->ViewCustomAttributes = "";

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
		}

		// costo
		$this->costo->EditAttrs["class"] = "form-control";
		$this->costo->EditCustomAttributes = "";
		$this->costo->EditValue = ew_HtmlEncode($this->costo->CurrentValue);
		$this->costo->PlaceHolder = ew_RemoveHtml($this->costo->FldCaption());
		if (strval($this->costo->EditValue) <> "" && is_numeric($this->costo->EditValue)) $this->costo->EditValue = ew_FormatNumber($this->costo->EditValue, -2, -1, -2, 0);

		// fecha_inicio
		$this->fecha_inicio->EditAttrs["class"] = "form-control";
		$this->fecha_inicio->EditCustomAttributes = "";
		$this->fecha_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_inicio->CurrentValue, 7));
		$this->fecha_inicio->PlaceHolder = ew_RemoveHtml($this->fecha_inicio->FldCaption());

		// fecha_final
		$this->fecha_final->EditAttrs["class"] = "form-control";
		$this->fecha_final->EditCustomAttributes = "";
		$this->fecha_final->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_final->CurrentValue, 7));
		$this->fecha_final->PlaceHolder = ew_RemoveHtml($this->fecha_final->FldCaption());

		// estado
		$this->estado->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->estado->FldTagValue(1), $this->estado->FldTagCaption(1) <> "" ? $this->estado->FldTagCaption(1) : $this->estado->FldTagValue(1));
		$arwrk[] = array($this->estado->FldTagValue(2), $this->estado->FldTagCaption(2) <> "" ? $this->estado->FldTagCaption(2) : $this->estado->FldTagValue(2));
		$arwrk[] = array($this->estado->FldTagValue(3), $this->estado->FldTagCaption(3) <> "" ? $this->estado->FldTagCaption(3) : $this->estado->FldTagValue(3));
		$this->estado->EditValue = $arwrk;

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->idconsulta->Exportable) $Doc->ExportCaption($this->idconsulta);
					if ($this->idcuenta->Exportable) $Doc->ExportCaption($this->idcuenta);
					if ($this->fecha_cita->Exportable) $Doc->ExportCaption($this->fecha_cita);
					if ($this->iddoctor->Exportable) $Doc->ExportCaption($this->iddoctor);
					if ($this->costo->Exportable) $Doc->ExportCaption($this->costo);
					if ($this->fecha_inicio->Exportable) $Doc->ExportCaption($this->fecha_inicio);
					if ($this->fecha_final->Exportable) $Doc->ExportCaption($this->fecha_final);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
				} else {
					if ($this->idconsulta->Exportable) $Doc->ExportCaption($this->idconsulta);
					if ($this->idcuenta->Exportable) $Doc->ExportCaption($this->idcuenta);
					if ($this->fecha_cita->Exportable) $Doc->ExportCaption($this->fecha_cita);
					if ($this->iddoctor->Exportable) $Doc->ExportCaption($this->iddoctor);
					if ($this->costo->Exportable) $Doc->ExportCaption($this->costo);
					if ($this->fecha_inicio->Exportable) $Doc->ExportCaption($this->fecha_inicio);
					if ($this->fecha_final->Exportable) $Doc->ExportCaption($this->fecha_final);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->idconsulta->Exportable) $Doc->ExportField($this->idconsulta);
						if ($this->idcuenta->Exportable) $Doc->ExportField($this->idcuenta);
						if ($this->fecha_cita->Exportable) $Doc->ExportField($this->fecha_cita);
						if ($this->iddoctor->Exportable) $Doc->ExportField($this->iddoctor);
						if ($this->costo->Exportable) $Doc->ExportField($this->costo);
						if ($this->fecha_inicio->Exportable) $Doc->ExportField($this->fecha_inicio);
						if ($this->fecha_final->Exportable) $Doc->ExportField($this->fecha_final);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
					} else {
						if ($this->idconsulta->Exportable) $Doc->ExportField($this->idconsulta);
						if ($this->idcuenta->Exportable) $Doc->ExportField($this->idcuenta);
						if ($this->fecha_cita->Exportable) $Doc->ExportField($this->fecha_cita);
						if ($this->iddoctor->Exportable) $Doc->ExportField($this->iddoctor);
						if ($this->costo->Exportable) $Doc->ExportField($this->costo);
						if ($this->fecha_inicio->Exportable) $Doc->ExportField($this->fecha_inicio);
						if ($this->fecha_final->Exportable) $Doc->ExportField($this->fecha_final);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>

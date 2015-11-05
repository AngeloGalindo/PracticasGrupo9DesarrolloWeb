<?php

// Global variable for table object
$receta = NULL;

//
// Table class for receta
//
class creceta extends cTable {
	var $idreceta;
	var $idempleado;
	var $idmedicina;
	var $fecha;
	var $cantidad;
	var $precio_unitario;
	var $idturno;
	var $idcuenta;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'receta';
		$this->TableName = 'receta';
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

		// idreceta
		$this->idreceta = new cField('receta', 'receta', 'x_idreceta', 'idreceta', '`idreceta`', '`idreceta`', 3, -1, FALSE, '`idreceta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idreceta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idreceta'] = &$this->idreceta;

		// idempleado
		$this->idempleado = new cField('receta', 'receta', 'x_idempleado', 'idempleado', '`idempleado`', '`idempleado`', 3, -1, FALSE, '`idempleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idempleado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idempleado'] = &$this->idempleado;

		// idmedicina
		$this->idmedicina = new cField('receta', 'receta', 'x_idmedicina', 'idmedicina', '`idmedicina`', '`idmedicina`', 3, -1, FALSE, '`idmedicina`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idmedicina->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idmedicina'] = &$this->idmedicina;

		// fecha
		$this->fecha = new cField('receta', 'receta', 'x_fecha', 'fecha', '`fecha`', 'DATE_FORMAT(`fecha`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha'] = &$this->fecha;

		// cantidad
		$this->cantidad = new cField('receta', 'receta', 'x_cantidad', 'cantidad', '`cantidad`', '`cantidad`', 3, -1, FALSE, '`cantidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cantidad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cantidad'] = &$this->cantidad;

		// precio_unitario
		$this->precio_unitario = new cField('receta', 'receta', 'x_precio_unitario', 'precio_unitario', '`precio_unitario`', '`precio_unitario`', 131, -1, FALSE, '`precio_unitario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->precio_unitario->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['precio_unitario'] = &$this->precio_unitario;

		// idturno
		$this->idturno = new cField('receta', 'receta', 'x_idturno', 'idturno', '`idturno`', '`idturno`', 3, -1, FALSE, '`idturno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idturno->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idturno'] = &$this->idturno;

		// idcuenta
		$this->idcuenta = new cField('receta', 'receta', 'x_idcuenta', 'idcuenta', '`idcuenta`', '`idcuenta`', 3, -1, FALSE, '`idcuenta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idcuenta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idcuenta'] = &$this->idcuenta;
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
		if ($this->getCurrentMasterTable() == "medicina") {
			if ($this->idmedicina->getSessionValue() <> "")
				$sMasterFilter .= "`idmedicina`=" . ew_QuotedValue($this->idmedicina->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "empleado") {
			if ($this->idempleado->getSessionValue() <> "")
				$sMasterFilter .= "`idempleado`=" . ew_QuotedValue($this->idempleado->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "turno") {
			if ($this->idturno->getSessionValue() <> "")
				$sMasterFilter .= "`idturno`=" . ew_QuotedValue($this->idturno->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "medicina") {
			if ($this->idmedicina->getSessionValue() <> "")
				$sDetailFilter .= "`idmedicina`=" . ew_QuotedValue($this->idmedicina->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "empleado") {
			if ($this->idempleado->getSessionValue() <> "")
				$sDetailFilter .= "`idempleado`=" . ew_QuotedValue($this->idempleado->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "turno") {
			if ($this->idturno->getSessionValue() <> "")
				$sDetailFilter .= "`idturno`=" . ew_QuotedValue($this->idturno->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_medicina() {
		return "`idmedicina`=@idmedicina@";
	}

	// Detail filter
	function SqlDetailFilter_medicina() {
		return "`idmedicina`=@idmedicina@";
	}

	// Master filter
	function SqlMasterFilter_empleado() {
		return "`idempleado`=@idempleado@";
	}

	// Detail filter
	function SqlDetailFilter_empleado() {
		return "`idempleado`=@idempleado@";
	}

	// Master filter
	function SqlMasterFilter_turno() {
		return "`idturno`=@idturno@";
	}

	// Detail filter
	function SqlDetailFilter_turno() {
		return "`idturno`=@idturno@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`receta`";
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
	var $UpdateTable = "`receta`";

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
			if (array_key_exists('idreceta', $rs))
				ew_AddFilter($where, ew_QuotedName('idreceta') . '=' . ew_QuotedValue($rs['idreceta'], $this->idreceta->FldDataType));
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
		return "`idreceta` = @idreceta@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idreceta->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idreceta@", ew_AdjustSql($this->idreceta->CurrentValue), $sKeyFilter); // Replace key value
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
			return "recetalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "recetalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("recetaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("recetaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "recetaadd.php?" . $this->UrlParm($parm);
		else
			return "recetaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("recetaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("recetaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("recetadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idreceta->CurrentValue)) {
			$sUrl .= "idreceta=" . urlencode($this->idreceta->CurrentValue);
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
			$arKeys[] = @$_GET["idreceta"]; // idreceta

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
			$this->idreceta->CurrentValue = $key;
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
		$this->idreceta->setDbValue($rs->fields('idreceta'));
		$this->idempleado->setDbValue($rs->fields('idempleado'));
		$this->idmedicina->setDbValue($rs->fields('idmedicina'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->cantidad->setDbValue($rs->fields('cantidad'));
		$this->precio_unitario->setDbValue($rs->fields('precio_unitario'));
		$this->idturno->setDbValue($rs->fields('idturno'));
		$this->idcuenta->setDbValue($rs->fields('idcuenta'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// idreceta
		// idempleado
		// idmedicina
		// fecha
		// cantidad
		// precio_unitario
		// idturno
		// idcuenta
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// idreceta
		$this->idreceta->EditAttrs["class"] = "form-control";
		$this->idreceta->EditCustomAttributes = "";
		$this->idreceta->EditValue = $this->idreceta->CurrentValue;
		$this->idreceta->ViewCustomAttributes = "";

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
		}

		// idcuenta
		$this->idcuenta->EditAttrs["class"] = "form-control";
		$this->idcuenta->EditCustomAttributes = "";
		$this->idcuenta->EditValue = ew_HtmlEncode($this->idcuenta->CurrentValue);
		$this->idcuenta->PlaceHolder = ew_RemoveHtml($this->idcuenta->FldCaption());

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
					if ($this->idreceta->Exportable) $Doc->ExportCaption($this->idreceta);
					if ($this->idempleado->Exportable) $Doc->ExportCaption($this->idempleado);
					if ($this->idmedicina->Exportable) $Doc->ExportCaption($this->idmedicina);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->cantidad->Exportable) $Doc->ExportCaption($this->cantidad);
					if ($this->precio_unitario->Exportable) $Doc->ExportCaption($this->precio_unitario);
					if ($this->idturno->Exportable) $Doc->ExportCaption($this->idturno);
					if ($this->idcuenta->Exportable) $Doc->ExportCaption($this->idcuenta);
				} else {
					if ($this->idreceta->Exportable) $Doc->ExportCaption($this->idreceta);
					if ($this->idempleado->Exportable) $Doc->ExportCaption($this->idempleado);
					if ($this->idmedicina->Exportable) $Doc->ExportCaption($this->idmedicina);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->cantidad->Exportable) $Doc->ExportCaption($this->cantidad);
					if ($this->precio_unitario->Exportable) $Doc->ExportCaption($this->precio_unitario);
					if ($this->idturno->Exportable) $Doc->ExportCaption($this->idturno);
					if ($this->idcuenta->Exportable) $Doc->ExportCaption($this->idcuenta);
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
						if ($this->idreceta->Exportable) $Doc->ExportField($this->idreceta);
						if ($this->idempleado->Exportable) $Doc->ExportField($this->idempleado);
						if ($this->idmedicina->Exportable) $Doc->ExportField($this->idmedicina);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->cantidad->Exportable) $Doc->ExportField($this->cantidad);
						if ($this->precio_unitario->Exportable) $Doc->ExportField($this->precio_unitario);
						if ($this->idturno->Exportable) $Doc->ExportField($this->idturno);
						if ($this->idcuenta->Exportable) $Doc->ExportField($this->idcuenta);
					} else {
						if ($this->idreceta->Exportable) $Doc->ExportField($this->idreceta);
						if ($this->idempleado->Exportable) $Doc->ExportField($this->idempleado);
						if ($this->idmedicina->Exportable) $Doc->ExportField($this->idmedicina);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->cantidad->Exportable) $Doc->ExportField($this->cantidad);
						if ($this->precio_unitario->Exportable) $Doc->ExportField($this->precio_unitario);
						if ($this->idturno->Exportable) $Doc->ExportField($this->idturno);
						if ($this->idcuenta->Exportable) $Doc->ExportField($this->idcuenta);
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

<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<?php
include('app_db_config.php');
require_once('dbops.php');     
$MyOps = new DBOps($usr_name,$usr_pwd,$target_db,$target_host);

function fncDesignCombo($query,$name,$function,$prefix,$suffix,$selected,$error)
	{
	include('app_db_config.php');
	require_once('dbops.php');     
	$MyOps = new DBOps($usr_name,$usr_pwd,$target_db,$target_host);
	$count=0;
	$combo="<select id ='".$name."' name ='".$name."' ".$function." >";
	$combo.=$prefix;
	$res = $MyOps->list_orders($query);
	if ($res)
		{
		while ($row = mysql_fetch_assoc($res)) 
			{
			$count++;
			if ($selected==$row['id'])
				$combo.="<option value='".$row['id']."' selected>".$row["name"]." *</option>";
			else
				$combo.="<option value='".$row['id']."'>".$row["name"]."</option>";
			}
		}
	
	$combo.=$suffix."</select>";
	if ($count==0)
		{
		$alert="<SCRIPT LANGUAGE=\"JavaScript\">Alertify.dialog.labels.ok ='Aceptar';Alertify.dialog.alert('<img src=\'general_repository/image/stop_48x48.png\'><b><big>Error en  ".$error."</big></b>');</SCRIPT>";
		$combo.=$alert." <img src='general_repository/image/stop_24x24.png'> <font color =red><b>Error en el Selector</b></font>";
		}
	return $combo;
	}
	
function fncExecuteQuery($object, $query, &$arrayData, $option)
	{

	$res = $object->list_orders($query);
	if($res)
		{
		while ($row = mysql_fetch_assoc($res)) 
			{
			fncSetDataToArray($row, $arrayData, $option);
			}
		}
	}
function fncCreateArray($query)
	{
	include('app_db_config.php');
	require_once('dbops.php');     
	$MyOps = new DBOps($usr_name,$usr_pwd,$target_db,$target_host);
	$count=0;
	$array="";
	$res = $MyOps->list_orders($query);
	if ($res)
		{
		while ($row = mysql_fetch_assoc($res)) 
			{
			$count++;
			$array[$row['id']]=$row['name'];
			}
		}
	return $array;
	}	

function fncGetMonth()
	{
	return array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto', '09'=>'Septiembre', '10'=>'Octubre', '11'=>'Noviembre', '12'=>'Diciembre');
	}

function fncGetDay()
	{
	return array('1'=>'Lunes','2'=>'Martes','3'=>'Mi&eacute;rcoles','4'=>'Jueves','5'=>'Viernes','6'=>'S&aacute;bado','0'=>'Domingo');
	}	
	
function fncDesignComboArray($res,$name,$function,$prefix,$suffix,$selected,$error)
	{
	$count=0;
	$combo="<select id ='".$name."' name ='".$name."' ".$function." >";
	$combo.=$prefix;
	foreach ($res as $key => $row) 
		{
		$count++;
		if ($selected==$key)
			$combo.="<option value='".$key."' selected>".$res[$key]." *</option>";
		else
			$combo.="<option value='".$key."'>".$res[$key]."</option>";
		}
		
	
	$combo.=$suffix."</select>";
	if ($count==0)
		{
		$alert="<SCRIPT LANGUAGE=\"JavaScript\">Alertify.dialog.labels.ok ='Aceptar';Alertify.dialog.alert('<img src=\'general_repository/image/stop_48x48.png\'><b><big>Error en  ".$error."</big></b>');</SCRIPT>";
		$combo.=$alert." <img src='general_repository/image/stop_24x24.png'> <font color =red><b>Error en el Selector</b></font>";
		}
	return $combo;
	}
	

function fncDesignComboArrayMultiple($res,$name,$function,$prefix,$suffix,$selected,$error)
	{
	$count=0;
	$combo="<select multiple id ='".$name."' name ='".$name."' ".$function." >";
	$combo.=$prefix;
	foreach ($res as $key => $row) 
		{
		$count++;
		if ($selected==$key)
			$combo.="<option value='".$key."' selected>".$res[$key]." *</option>";
		else
			$combo.="<option value='".$key."'>".$res[$key]."</option>";
		}
		
	
	$combo.=$suffix."</select>";
	if ($count==0)
		{
		$alert="<SCRIPT LANGUAGE=\"JavaScript\">Alertify.dialog.labels.ok ='Aceptar';Alertify.dialog.alert('<img src=\'general_repository/image/stop_48x48.png\'><b><big>Error en  ".$error."</big></b>');</SCRIPT>";
		$combo.=$alert." <img src='general_repository/image/stop_24x24.png'> <font color =red><b>Error en el Selector</b></font>";
		}
	return $combo;
	}
	
function fncDesignComboMultiple($query,$name,$function,$prefix,$suffix,$selected,$error)
	{
	include('app_db_config.php');
	require_once('dbops.php');     
	$MyOps = new DBOps($usr_name,$usr_pwd,$target_db,$target_host);
	$count=0;
	$combo="<select multiple id ='".$name."' name ='".$name."' ".$function."  class='select2' style='width:275px;'>";
	$combo.=$prefix;
	$res = $MyOps->list_orders($query);
	if ($res)
		{
		while ($row = mysql_fetch_assoc($res)) 
			{
			$count++;
			if ($selected==$row['id'])
				$combo.="<option value='".$row['id']."' selected>".$row["name"]." *</option>";
			else
				$combo.="<option value='".$row['id']."'>".$row["name"]."</option>";
			}
		}
	
	$combo.=$suffix."</select>";
	if ($count==0)
		{
		$alert="<SCRIPT LANGUAGE=\"JavaScript\">Alertify.dialog.labels.ok ='Aceptar';Alertify.dialog.alert('<img src=\'general_repository/image/stop_48x48.png\'><b><big>Error en  ".$error."</big></b>');</SCRIPT>";
		$combo.=$alert." <img src='general_repository/image/stop_24x24.png'> <font color =red><b>Error en el Selector</b></font>";
		}
	return $combo;
	}
?>
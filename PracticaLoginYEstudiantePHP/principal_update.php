<?php
include "app_page.php";

include('general_repository/php/app_db_config.php');
require_once('general_repository/php/dbops.php');     
$MyOps = new DBOps($usr_name,$usr_pwd,$target_db,$target_host);
$query= "SELECT carnet FROM estudiante order by 1 desc limit 1 ;";
$res = $MyOps->list_orders($query);
if ($res)
	{
	while ($row = mysql_fetch_assoc($res)) 
		{
		$ultimo_carne=$row["carnet"];
		}
	}
$query = " INSERT INTO estudiante ( Nombres, Apellidos, Sexo, FechaNacimiento) 
			VALUES ('".$_POST["nombre"]."', '".$_POST["apellido"]."', '".$_POST["sexo"]."', '".$_POST["fecha"]."');";
		if ($MyOps->insert_to_db($query))
		{
			echo "Se Creo";
		};

?>

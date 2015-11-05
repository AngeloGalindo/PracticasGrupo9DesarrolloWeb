<?php
session_start(); // Initialize Session data
ob_start(); // Turn on output buffering v
date_default_timezone_set("America/Guatemala");

include('app_db_config.php');
?>

<script src="myscripts/jquery-1.4.4.js" type="text/javascript"></script>
<?php date_default_timezone_set("America/Guatemala"); 

include('general_repository/php/app_db_config.php');
require_once('general_repository/php/dbops.php');  
setlocale(LC_NUMERIC, 'C');?>
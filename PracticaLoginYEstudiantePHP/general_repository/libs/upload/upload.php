
<?php

function bytesToSize1024($bytes, $precision = 2) {
    $unit = array('B','KB','MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}

$sFileName = $_FILES['image_file']['name'];
$sFileName = str_replace(' ', '', $sFileName);
$sFileType = $_FILES['image_file']['type'];
$sFileSize = bytesToSize1024($_FILES['image_file']['size'], 1);
$tmp_name = $_FILES['image_file']['tmp_name'];
$now = date (Ymdis);
	
move_uploaded_file($tmp_name, "data/".$now."_".$sFileName);
echo "<input type='hidden' id='archivo' value = '".$now."_".$sFileName."' hidden>";
echo "<SCRIPT LANGUAGE=\"JavaScript\">alert(2);</SCRIPT>";
echo <<<EOF

<p>Archivo: {$sFileName} fue cargado.</p>
<p>Tipo: {$sFileType}</p>
<p>Tama&ntilde;o: {$sFileSize}</p>
EOF;
?>

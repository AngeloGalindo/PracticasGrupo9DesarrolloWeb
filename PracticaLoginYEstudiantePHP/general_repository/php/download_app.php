
<?php

if (!isset($_GET["fn"]) || empty($_GET["fn"])) 
	{
   exit();
	}

//Utilizamos basename por seguridad, devuelve el 
//nombre del archivo eliminando cualquier ruta. 
$archivo = basename($_GET["fn"]);
echo "archivo es".$archivo;
$ruta = $_GET["fn"];
if (is_file($ruta))
{
   header('Content-Type: application/force-download');
   header('Content-Disposition: attachment; filename='.$archivo);
   header('Content-Transfer-Encoding: binary');
   //header('Content-Length: '.filesize($ruta));  <---- linea borrada
   readfile($ruta);
}
else
   exit();
?>
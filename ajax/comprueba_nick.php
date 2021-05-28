<?php
include "../inc/inc.php";
$n=trim(str_replace("'","",$_GET["n"]));
if (strlen($n)<3) echo "LONGITUD_INSUFICIENTE|";
else if (preg_match('/[^0-9A-Za-z]/',$n)) echo "ERRONEO|";
else {
	$query="SELECT id FROM porrista WHERE nick='".$n."'";
	$res=bd_getAll($query,$conexion);
	$existe=bd_num($res);

	if ($existe) echo "KO|";
	else echo "OK|";
}
?>

<?php

if ($_POST["accion"]=="registrarnick") {
	$query="SELECT id FROM porrista WHERE nick='".str_replace("'","",$_POST["nick"])."' AND pagado='si'";
	$res=bd_getAll($query,$conexion);
	$arra=bd_fetch($res);
	$nickARegistrar=$arra["id"];
	if ($nickARegistrar) {
		setcookie("nickRegistrado",$_POST["nick"],time()+60*60*24*30);
		$nickRegistrado=$_POST["nick"];
	}
	else {
		$cuenta_nickNoExiste="<p><b><span class='red'>Atención: </span></b> El nick que quieres registrar no existe o no hemos verificado el pago de su apuesta. Por favor inténtalo de nuevo</p>";
	}
}
else {
	$nickRegistrado=$_COOKIE["nickRegistrado"];
}

?>
<?php
include "../inc/inc.php";
$id=intval($_GET["id"]);
$nuevoEstado=$_GET["nuevoEstado"];

$query="UPDATE porrista SET pagado='".$nuevoEstado."' WHERE id='".$id."'";
$res=bd_getAll($query,$conexion);

echo $nuevoEstado."|";
?>

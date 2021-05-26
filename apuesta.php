<?php
include "inc/globales.php";
if (date("Y-m-d H:i:s")>=$FECHA_APERTURA_PORRA_SEGUNDA_FASE || $_GET["forrest"]==1) $apuesta2=1; else $apuesta=1;
/*$apuesta=1;*/
include "index.php";
?>

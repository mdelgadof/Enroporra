<? include "inc/inc.php";
$nick=str_replace("'","",$_GET["nick"]);
$query="SELECT id,nombre,apellido FROM porrista WHERE nick='$nick'";
$res=mysql_query($query,$conexion);
$arra=mysql_fetch_array($res);
$id=$arra["id"];
$nombre=$arra["nombre"]." ".$arra["apellido"];
if (!is_numeric($id)) exit();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Created by: Reality Software | www.realitysoftware.ca
Released by: Flash MP3 Player | www.flashmp3player.org
Note: This is a free template released under the Creative Commons Attribution 3.0 license,
which means you can use it in any way you want provided you keep links to authors intact.
Don't want our links in template? You can pay a link removal fee: www.realitysoftware.ca/templates/
You can also purchase a PSD-file for this template.
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Enroporra</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
    <div id="main">
    	<div id="content">
<?php
			echo "<p><h1>La apuesta de <span class='red'>$nombre</span></h1></p>";
			/*echo porra($id,2,true);
			echo "<br>";*/
			echo porra($id);
			echo porra($id,2);
?>
		<center><br><br><input type='button' value='Cerrar' onClick='window.close()'></center>
        </div>
    </div>
</div>
</body>
</html>

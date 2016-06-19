<?
$query="SELECT * FROM equipo ORDER BY id";
$res=mysql_query($query,$conexion);
$contador=0;
while ($arra=mysql_fetch_array($res)) {
	$contador++;
	if ($contador%4==1) echo "<div style='float:left; margin-right:40px'>";
	echo "<b>".$arra["id"]."</b> <img src='/images/badges/".$arra["bandera"]."' width=32><p>".$arra["nombre"]."</p>";
	if ($contador%4==0) echo "</div>";
}
?>

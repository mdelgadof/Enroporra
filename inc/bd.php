<?

if ($pruebasLocal) {
	$mysql_host = "localhost";
	$mysql_database = "enroporra";
	$mysql_user = "root";
	$mysql_password = "";
}
else {
	$mysql_host = "localhost";
	$mysql_database = "enroporra";
	$mysql_user = "enroporra";
	$mysql_password = "enRoMyS;";
}

$conexion=@mysql_connect ($mysql_host,$mysql_user,$mysql_password);
@mysql_select_db ($mysql_database);

?>

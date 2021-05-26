<?php

	$mysql_host = "localhost";
	$mysql_database = "enroporra";
	$mysql_user = "enroporra";
	$mysql_password = "enr0_sql";


$conexion=mysql_connect ($mysql_host,$mysql_user,$mysql_password);
@mysql_select_db ($mysql_database);

?>
<?php
if ($_SERVER["SERVER_NAME"]=="enroporra.test") {
    define('DB_HOST',"localhost");
    define('DB_NAME',"enroporra");
    define('DB_USER',"root");
    define('DB_PASSWORD',"");

    define ("DOCUMENT_ROOT","C:/Users/Miguel/ClientesCodigo/Miguel/Enroporra/");
	define ("WEB_ROOT","http://enroporra.test/");
	$pruebasLocal=true;
}
else {
    define('DB_HOST',"localhost");
    define('DB_NAME',"enroporra");
    define('DB_USER',"enroporra");
    define('DB_PASSWORD',"enroporra");

    define ("DOCUMENT_ROOT","/var/www/www.enroporra.es/");
	define ("WEB_ROOT","https://www.enroporra.es");
	$pruebasLocal=false;
}

define ("FULL_TABLE_HTML",DOCUMENT_ROOT."templates/clasificacion.html");

include DOCUMENT_ROOT."/inc/bd.php";
include DOCUMENT_ROOT."/inc/funciones.php";
include DOCUMENT_ROOT."/inc/cookies.php";
include DOCUMENT_ROOT."/inc/globales.php";
?>
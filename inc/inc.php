<?php
if ($_SERVER["SERVER_NAME"]=="enroporra.test") {
	define ("DOCUMENT_ROOT","C:/Users/Miguel/ClientesCodigo/Miguel/Enroporra");
	define ("WEB_ROOT","http://enroporra.test/");
	$pruebasLocal=true;
}
else {
	define ("DOCUMENT_ROOT","/var/www/www.enroporra.com/");
	define ("WEB_ROOT","http://www.enroporra.com");
	$pruebasLocal=false;
}
include DOCUMENT_ROOT."/inc/bd.php";
include DOCUMENT_ROOT."/inc/funciones.php";
include DOCUMENT_ROOT."/inc/cookies.php";
include DOCUMENT_ROOT."/inc/globales.php";
?>
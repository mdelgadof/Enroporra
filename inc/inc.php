<?
if ($_SERVER["SERVER_NAME"]=="localhost") {
	define ("DOCUMENT_ROOT","E:/www.enroporra.co.cc");
	define ("WEB_ROOT","http://www.enroporra.com");
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
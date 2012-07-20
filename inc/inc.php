<?
if ($_SERVER["SERVER_NAME"]=="localhost") {
	define ("DOCUMENT_ROOT","/www/htdocs/enroporra");
	define ("WEB_ROOT","http://localhost/enroporra");
	$pruebasLocal=true;
}
else {
	define ("DOCUMENT_ROOT","/data/www/www.enroporra.co.cc/");
	define ("WEB_ROOT","http://www.enroporra.co.cc");
	$pruebasLocal=false;
}
include DOCUMENT_ROOT."/inc/bd.php";
include DOCUMENT_ROOT."/inc/funciones.php";
include DOCUMENT_ROOT."/inc/cookies.php";
include DOCUMENT_ROOT."/inc/globales.php";
?>

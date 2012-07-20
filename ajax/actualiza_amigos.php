<?
include "../inc/inc.php";
$v=$_GET["v"];
$n=$_GET["n"];
$amigosEnro=$_COOKIE["amigosEnro"];

if ($v) {
	if ($amigosEnro) {
		if (strpos(",".$n.",",$amigosEnro)===false) {
			$amigosEnro.=$n.",";
		}
	}
	else $amigosEnro=",".$n.",";
}
else {
	if ($amigosEnro) {
		$amigosEnro=str_replace(",".$n.",",",",$amigosEnro);
		$amigosEnro=str_replace(",".$n.",",",",$amigosEnro);
		if ($amigosEnro==",") $amigosEnro="";
	}
}

setcookie("amigosEnro",$amigosEnro,time()+60*60*24*30,"/");
echo $amigosEnro;
?>

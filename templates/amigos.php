<?
echo "<h1 class='red'>Amigos</h1>";

$arrayAmigos=array();
if ($_COOKIE["amigosEnro"]) {
	eval("\$arrayAmigos=array(".convierteAmigos($_COOKIE["amigosEnro"]).");");
}

if (count($arrayAmigos)) {
	echo "<br>".clasificacion("amigos");
	echo "<br><br><h1>Listado total de participantes</h1><br>";
}
else {
	echo "<p>Aqu� tienes un listado de todos los participantes de <b>Enroporra</b>. Selecciona a tus amigos y conocidos
	(o a los que quieras) y, recargando la p�gina, podr�s ver la clasificaci�n s�lo con los nombres que t� hayas elegido.
	Esperamos que te sirva, y te recordamos que esta clasificaci�n parcial que se publica en AMIGOS no sirve a la hora de
	repartir ning�n premio, ya que para esto s�lo vale la <a href='clasificacion.php'>clasificaci�n general</a></p>";
}

$query="SELECT * FROM porrista WHERE pagado='si' ORDER BY nombre,apellido";
$res=mysql_query($query,$conexion);
$num=mysql_num_rows($res);

echo "<table>";
while ($arra=mysql_fetch_array($res)) {
	$checked = in_array($arra["nick"],$arrayAmigos) ? "checked":"";
	echo "<tr><td><input type='checkbox' value='".$arra["nick"]."' onClick='amigar(this)' ".$checked."> ".normalizaNombre($arra["nombre"]." ".$arra["apellido"])."&nbsp;&nbsp;&nbsp;</td>
	<td><div id='".$arra["nick"]."'></div></td></tr>";
}
echo "</table><br><br>";

?>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>
<script type='text/javascript'>
	function amigar(check) {

		var date = new Date();
		var time = date.getTime();

		var valor = (check.checked) ? 1:0;
		var nick = check.value;

		$.ajax({
			url: "ajax/actualiza_amigos.php",
			data: "n="+nick+"&v="+valor+"&t="+time,
			success: function(msg){
				if (valor) $("#"+nick).html("<font color='green'>OK A�adido a tus amigos. Recarga la p�gina para ver la clasificaci�n</font>");
				else $("#"+nick).html("<font color='red'>OK Eliminado de tus amigos. Recarga la p�gina para ver la clasificaci�n</font>");
      		}
      	});

	}
</script>

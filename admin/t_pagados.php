<h1 class='red'>Administración de pagos</h1>
<p>A continuación va un listado de todas las porras recibidas en el sistema. Los no pagados salen antes, y luego los ordenamos por número de apostante, los más nuevos primero. Simplemente marcando el check que hay a la izquierda de cada número marcamos la apuesta como pagada, y quitándolo la marcamos como no pagada. A todos los efectos de clasificación, en la página de cara al público sólo saldrán las porras marcadas como pagadas Aquí.</p>
<?php

$query="SELECT pagado,count(*) s FROM porrista GROUP BY pagado";
$res=bd_getAll($query,$conexion);
$pagados=$nopagados=0;
while ($arra=bd_fetch($res)) {
	if ($arra["pagado"]=="si") $pagados=$arra["s"];
	if ($arra["pagado"]=="no") $nopagados=$arra["s"];
}
$porristas=$pagados+$nopagados;

echo "<h1 class='red'>JUGADORES: $porristas</h1> (pagados <span class='red'>$pagados</span>, por pagar <span class='red'>$nopagados</span>)<p></p>";

$query="SELECT p.id,p.nombre,p.apellido,p.nick,p.pagado,p.forma_pago,p.comisionero,p.telefono,p.email FROM porrista p ORDER BY p.pagado DESC,p.id DESC";
$res=bd_getAll($query,$conexion);

echo "<table>";
while ($arra=bd_fetch($res)) {
	$checked = ($arra["pagado"]=="si") ? "checked":"";
	$telefono=$email="";
	if ($arra["telefono"]) $telefono="Tel: ".$arra["telefono"]." / ";
	if ($arra["email"]) $email="Email: <a href='mailto:".$arra["email"]."'>".$arra["email"]."</a>";
	echo "<tr><td valign='top'>
		<input type='checkbox' value='1' id='pagado_".$arra["id"]."' ".$checked." onClick='javascript:cambiaEstadoPago(".$arra["id"].")'></td>
		<td>&nbsp;<b><span class='red'>".$arra["id"]."</span> ".$arra["nick"]."</b> <a href='javascript:window.open(\"../popup_porra.php?nick=".$arra["nick"]."\",\"\",\"width=500,height=700\")'><img src='../images/ojo.png' border=0 width=16 height=16></a> (".$arra["nombre"]." ".$arra["apellido"].")&nbsp;
		<b><span class='red'>".$arra["forma_pago"]." ".$arra["comisionero"]."</span></b><br>".
		$telefono.$email."
		</td><td valign='top'><div id='mensaje_".$arra["id"]."'></div></td></tr>";
}
echo "</table><br><br>&nbsp;";
?>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>
<script type='text/javascript'>

	function cambiaEstadoPago(id_porrista) {
		
		var date = new Date();
		var time = date.getTime();
				
		if ($('#pagado_'+id_porrista).attr('checked')) nuevoEstado='si';
		else nuevoEstado='no';
		
		$.ajax({ 
			url: "../ajax/cambia_estado_pago.php", 
			data: "id="+id_porrista+"&nuevoEstado="+nuevoEstado+"&t="+time,
			success: function(msg){
				msg = msg.split("|");
				msg = msg[0];
				if (msg=="si") {
        			$("#mensaje_"+id_porrista).html("<font color='green'>OK: Marcamos al apostante como pagado</font>");
				}
				else if (msg=="no") {
					$("#mensaje_"+id_porrista).html("<font color='red'>OK: Marcamos al apostante como <b>NO</b> pagado</font>");
				}
      		}
      	});
	}

</script>

<?
$query="SELECT * FROM noticias WHERE activa='si' ORDER BY fecha DESC";
$res=mysql_query($query,$conexion);
while ($arra=mysql_fetch_array($res)) {
	echo "<h1 class='red'>".$arra["titular"]."</h1>";
	echo "<p>".$arra["cuerpo"]."</p>";
}
?>
<h1 class='red'>BIENVENIDO</h1>
<p>
Bienvenido a la ENROPORRA de <?= $NOMBRE_TORNEO ?>. El mejor site para seguir online los resultados y pron&oacute;sticos de una gran y divertida <b>Porra</b>. <br><br>

Llevamos <?= (date("Y")-1994) ?> a&ntilde;os (desde EE.UU. 1994) organizando Porras sin &aacute;nimo de lucro (entre amigos) en todas las Eurocopas y Mundiales de F&uacute;tbol.<br><br>

La Porra consta de dos fases, que se detallan m&aacute;s ampliamente en las <a href='<?= $ENLACE_BASES ?>' target='_blank'>BASES</a>:<br><br>

<b>a)</b> Primera fase > Se elaboran pron&oacute;sticos de todos los partidos de la primera fase, otorg&aacute;ndose puntos tanto por acertar el ganador (o empate) como por el resultado. Igualmente en la primera fase hay que apostar por un m&aacute;ximo goleador para el torneo que ir&aacute; dando puntos por cada gol que anote en el campeonato. Si al final del campeonato tu jugador se corona como el m&aacute;ximo goleador del torneo (no se contar&aacute;n asistencias ni ning&uacute;n otro par&aacute;metro distinto del de los goles), tendr&aacute;s puntos adicionales.<br><br>

<b>b)</b> Segunda fase > Se presenta una vez conocido el cuadro final del torneo. Igualmente hay que apostar por ganadores y resultados, elaborando el cuadro final. Los puntos se reparten igual que en la primera fase. Tambi&eacute;n te puedes llevar puntos adicionales si aciertas el &aacute;rbitro de la final. <b>IMPORTANTE</b>: Entre la primera y segunda fase habr&aacute; muy poco tiempo, por lo que habr&aacute; que hacerlo r&aacute;pidamente en ese margen de tiempo.<br><br>

<b>c)</b> Habr&aacute; premios (10% de la recaudaci&oacute;n) para el ganador de la Primera Fase y para los cinco primeros clasificados al finalizar la Segunda Fase. (40% recaudaci&oacute;n para el primero, 20% para el segundo, 15% para el tercero, 10% para el cuarto y 5% para el quinto).<br><br>

<b>d)</b> Existe una comisi&oacute;n que resuelve todas las dudas e incidencias durante el desarrollo de la Porra. <br><br>

<b>e)</b> El participante en la Porra acepta las condiciones y t&eacute;rminos de la misma. <b>Importante:</b> Si no se ha formalizado el pago antes del inicio del primer partido, se quedar&aacute; excluido de la misma, tal y como dicen las bases.<br><br>


Gracias por visitarnos y <a href='apuesta.php'>&iexcl;Rellena tu apuesta!</a><br><br>&nbsp;
</p>

<? include "mapa.php" ?>

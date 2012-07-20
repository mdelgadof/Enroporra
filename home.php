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
Bienvenido a la ENROPORRA de la Eurocopa de Polonia y Ucrania 2012. El mejor site para seguir online los resultados y pronósticos de la <b>Porra de la Eurocopa de Fútbol</b>. <br><br>

Llevamos 18 años (desde EE.UU. 1994) organizando Porras sin ánimo de lucro (entre amigos) en todas las Eurocopas y Mundiales de Fútbol.<br><br>

La Porra consta de dos fases, que se detallan más ampliamente en las <a href='<?= $ENLACE_BASES ?>' target='_blank'>BASES</a>:<br><br>

<b>a)</b> Primera fase > Se elaboran pronósticos de todos los partidos de la primera fase, otorgándose puntos tanto por acertar el ganador (o empate) como por el resultado. Igualmente en la primera fase hay que apostar por un Pichichi para el torneo que irá dando puntos por cada gol que anote en el campeonato. Si al final del campeonato tu Pichichi se corona como el máximo goleador del torneo, tendrás puntos adicionales.<br><br>

<b>b)</b> Segunda fase > Se presenta una vez conocido el cuadro final del torneo. Igualmente hay que apostar por ganadores y resultados, elaborando el cuadro final. Los puntos se reparten igual que en la primera fase. También te puedes llevar puntos adicionales si aciertas el árbitro de la final. <b>IMPORTANTE</b>: Entre la 1ª y 2ª fase habrá muy poco tiempo, por lo que habrá que hacerlo rápidamente en ese margen de tiempo.<br><br>

<b>c)</b> Habrá premios (10% de la recaudación) para el ganador de la Primera Fase y para los cinco primeros clasificados al finalizar la Segunda Fase. (40% recaudación para el primero, 20% para el segundo, 15% para el tercero, 10% para el cuarto y 5% para el quinto).<br><br>

<b>d)</b> Existe una comisión que resuelve todas las dudas e incidencias durante el desarrollo de la Porra. <br><br>

<b>e)</b> El participante en la Porra acepta las condiciones y términos de la misma. <b>Importante:</b> Si no se ha formalizado el pago antes del inicio del primer partido, se quedará excluido de la misma, tal y como dicen las bases.<br><br>


Gracias por visitarnos y <a href='apuesta.php'>¡Rellena tu apuesta!</a><br><br>&nbsp;
</p>

<? include "mapa.php" ?>

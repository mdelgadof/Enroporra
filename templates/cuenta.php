<?
	if ($_GET["nick"]!="") $nick=$_GET["nick"];
        else if ($nickRegistrado) $nick=$nickRegistrado;
        else $nick="";
        if ($nick) {
            $nick=trim(str_replace("'","",$nick));
            $query="SELECT pagado,id,nombre,apellido FROM porrista WHERE nick='$nick'";
            $res=mysql_query($query,$conexion);
            $num=mysql_num_rows($res);
            $arra=mysql_fetch_array($res);
        }

if ($_GET["accion"]=="ver") {
	if (!$num) echo "<p>Ups, no tenemos registrado ning�n nick llamado <span class='red'>$nick</span>, �puede que te hayas equivocado? Prueba de nuevo o env�anos un email a <a href='mailto:".$EMAIL_ADMIN."'>".$EMAIL_ADMIN."</a></p>";
	else {
		if ($arra["pagado"]=="no") {
			echo "<p>Existe un nick <span class='red'>$nick</span> pero todav�a no aparece en el sistema como pagado. Si ya has efectuado el pago, lo activaremos a la mayor brevedad. En cualquier caso, si tienes cualquier duda nos puedes escribir a <a href='mailto:".$EMAIL_ADMIN."'>".$EMAIL_ADMIN."</a></p>";
		}
		else {
			echo "<h1 class='red'>Apuesta de ".$arra["nombre"]." ".$arra["apellido"]."</h1><br><br>";
                        if (date("Y-m-d H:i:s")<=$FECHA_PRIMER_PARTIDO_SEGUNDA_FASE && date("Y-m-d H:i:s")>=$FECHA_APERTURA_PORRA_SEGUNDA_FASE) 
				echo "Para que nadie juegue con ventaja, no se publicar�n las apuestas de la segunda fase hasta que empiecen las eliminatorias.";
			else {
				echo "<h1>Segunda fase</h1><br>";
				echo porra($arra["id"],2);
			}
			echo "<br><br><h1>Primera fase</h1><br>";
			echo porra($arra["id"]);
			echo "<br><br><a href='clasificacion.php'>Volver a la clasificaci�n</a>";
		}
	}
}

else {

	$formularioNickCuenta="
			<form name='formu' action='cuenta.php' method='post'>
			<input type='hidden' name='accion' value='registrarnick'>
			Nick: <input type='text' name='nick' class='inputArea' style='width:200px'> <input type='submit' value='Registrar'><br><br>
			</form>
	";

	echo "<h1 class='red'>Mi cuenta</h1>";

	echo $cuenta_nickNoExiste;

	if (!$nickRegistrado) {
		echo "<br>En este ordenador que est�s usando ahora mismo no sabemos qui�n eres. Por favor, escribe tu nick para que podamos darte informaci�n personalizada de c�mo va tu apuesta y de cu�les son tus pron�sticos para los pr�ximos partidos:";
		echo "<p>".$formularioNickCuenta."</p>";

	}
	else {
		echo "<br><h1>Hola, $nickRegistrado</h1>";
		echo "<p>Este es el espacio personalizado de tu apuesta como jugador de <b>Enroporra</b>. Intentaremos ir a�adiendo funcionalidades a este apartado. Por lo pronto, podr�s ir recordando tu apuesta en el apartado <span class='red'>Pr�ximos partidos</span> que aparece al comienzo de cada secci�n, debajo del marcador m�s apostado, pero esperamos poder ofrecerte unas cuantas cosas m�s seg�n vaya avanzando ".$NOMBRE_TORNEO.". Chequea de vez en cuando aqu� tu cuenta, es posible que encuentres novedades. <b>�Mucha suerte!</b></p>";
                echo "<h1 class='red'>Mi apuesta</h1><br><br>";
                echo "<h1>Primera fase</h1><br><br>";
                echo porra($arra["id"]);
		echo "<p>�No eres <span class='red'>".strtoupper($nickRegistrado)."</span>? Dinos qui�n eres:".$formularioNickCuenta."</p><br><br>";
	}

	echo "<p>Si quieres consultar cu�l es la apuesta de cualquier otro apostante de <b>Enroporra</b> puedes hacerlo en la <a href='clasificacion.php'>clasificaci�n</a></p>";
}

?>

<?php
	if ($_POST["accion"]=="insertar") {

		$nick=trim(str_replace("'","",$_POST["nick"]));
		$nombre=trim(str_replace("'","",$_POST["nombre"]));
		$apellido=trim(str_replace("'","",$_POST["apellido"]));
		$forma_pago=trim(str_replace("'","",$_POST["forma_pago"]));
		$comisionero= ($forma_pago=="en mano a miembro de comisión") ? trim(str_replace("'","",$_POST["comisionero"])):"";
		$telefono=trim(str_replace("'","",$_POST["telefono"]));
		$email=trim(str_replace("'","",$_POST["email"]));

		// Validación de porrista
		$query="SELECT id FROM porrista WHERE nick='".$nick."'";
		$res=bd_getAll($query,$conexion);
		if (bd_num($res) || $nick=="") {
			echo "<h1 class='red'>Error</h1><p>Se ha producido un error en la inserción, el nick ya existe o no se ha introducido correctamente. La apuesta no se ha guardado. Prueba de nuevo <a href='apuesta.php'>haciendo click Aquí</a>. Disculpa las molestias</p>";
		}

		else {

			// Inserción de porrista...
			$query="INSERT INTO porrista SET nombre='".$nombre."',apellido='".$apellido."',nick='".$nick."',id_goleador='".intval($_POST["goleador"])."',forma_pago='".$forma_pago."',telefono='".$telefono."',email='".$email."',comisionero='".$comisionero."',id_competicion=1";
            $id_porrista=bd_insert($query,$conexion);

			// Inserción de sus resultados
			$apuestas=array();

			// Recogida de datos
			foreach ($_POST as $clave => $valor) {
				if (substr($clave,0,10)=="resultado_") {
					$temp=explode("_",$clave);
					$apuestas[$temp[1]][$temp[2]]=intval($valor);
				}
			}

			// Inserción en BD
			foreach ($apuestas as $partido => $resultado) {

				$query="SELECT id_equipo1,id_equipo2 FROM partido WHERE id='".$partido."'";
				$res=bd_getAll($query,$conexion);
				$temp=bd_fetch($res);

				$id_equipo1=$temp["id_equipo1"];
				$id_equipo2=$temp["id_equipo2"];

				if ($resultado[1]>$resultado[2]) $quiniela="1";
				else if ($resultado[1]<$resultado[2]) $quiniela="2";
				else $quiniela="X";

				$query="INSERT INTO apuesta SET id_porrista='".$id_porrista."',id_partido='".$partido."',resultado1='".$resultado[1]."',resultado2='".$resultado[2]."',id_equipo1='".$id_equipo1."',id_equipo2='".$id_equipo2."',quiniela='".$quiniela."'";
				$res=bd_getAll($query,$conexion);
			}

			echo "<h1 class='red'>Inserción correcta</h1><p>Gracias, <span class='red'><b>".$nombre."</b></span>. Se ha anotado tu apuesta para el nick <span class='red'><b>".$nick."</b></span>. Tu número de apostante es el <span class='red'><b>".$id_porrista."</b></span>, apúntalo para cualquier consulta a la administración relativa a tu apuesta.<br><br>Recuerda que la apuesta sólo será efectiva si has efectuado el pago de 10 euros (ver <a href='".$ENLACE_BASES."' target='_blank'>bases</a>). Las formas de pago es este año exclusivamente, indicando el número de tu apuesta, tu nombre o tu nick, por transferencia a la cuenta corriente ".$CUENTA_BANCO." (titular: ".$TITULAR_BANCO.") o por Bizum al número <b>".$BIZUM."</b>. Gracias por participar y <b>¡mucha suerte!</b></p>";

			echo "<h1 class='red'>Tu apuesta</h1><p>Esta es la apuesta que hemos registrado para el nick <span class='red'><b>".$nick."</b></span>:</p>";

			echo porra($id_porrista);
		}
	}

	else {

		if (date("Y-m-d H:i:s")>=date("Y-m-d H:i:s",strtotime($FECHA_PRIMER_PARTIDO)-1800) || $_GET["testing"]==1) {
			echo "<h1 class='red'>RIEN NE VA PLUS!</h1>
			<p><b>¡Ha empezado ".$NOMBRE_TORNEO."!</b> La Comisión de <b>Enroporra</b> ha cerrado las apuestas y ya no se admiten nuevos participantes. Esta pantalla permanecerá cerrada hasta el comienzo de la fase de eliminatorias de ".$NOMBRE_TORNEO.", en la que todos los apostantes tendrán que rellenar los cruces de ".$PRIMERA_ELIMINATORIA." y sucesivos, y elegir al árbitro de la final. ¡Suerte a los participantes! y, si no te ha dado tiempo a ser uno de ellos para ".$NOMBRE_TORNEO.", ¡te esperamos en ".$NOMBRE_SIGUIENTE_TORNEO."!</p>";
			exit();
		}

?>

		<form name='insertar_porra' id='insertar_porra' action='apuesta.php' method='post' onSubmit='return validaform();'>
		<input type='hidden' name='accion' value='insertar'>
		<input type='hidden' name='controlNick' id='controlNick' value='0'>
			<h1 class='red'>Rellena tu apuesta</h1>
                <p>A continuación tienes el calendario con todos los partidos de la primera fase y un listado de goleadores. Hay que insertar un resultado en cada recuadro para completar tu apuesta. Está permitido el empate. Recuerda que se reparten puntos tanto por el acierto del resultado exacto como por el acierto del vencedor (o empate).</p>

				<table>
					<tr><td nowrap><h1>TU NOMBRE:</h1></td><td><input type='text' name='nombre' class='inputArea' style='width:150px;'></td><td colspan='2'></td></tr>
					<tr><td nowrap><h1>TU APELLIDO:</h1></td><td><input type='text' name='apellido' class='inputArea' style='width:150px;'></td><td colspan='2'></td></tr>
					<tr><td nowrap><h1>TU NICK:</h1></td><td><input type='text' name='nick' id='nick' class='inputArea' onBlur='test()' style='width:150px;'></td><td>&nbsp;</td><td><div id='mensajeValidacionNick'></div></td></tr>
					<tr><td colspan='10'><p>* Tu nick nos servirá para identificar tu apuesta de manera única. Si quieres apostar varias veces, usa un nick distinto para cada apuesta</p></td></tr>
					<tr><td valign='top'><h1>FORMA DE<br>PAGO:</h1></td>
						<td colspan='10'><a name='formapago'></a>
							<div id='capa_formapago'>
							<!--<input type='radio' name='forma_pago' value='sobre en Enro'> Método tradicional: sobre en el bar Enro<br>-->
							<input type='radio' name='forma_pago' value='transferencia bancaria'> Por transferencia (Acuérdate de poner tu nombre y nick en el concepto) a <font color='green'><b><?php echo $NOMBRE_BANCO ?></b> CC <?php echo $CUENTA_BANCO ?></font> (Titular: <?php echo $TITULAR_BANCO ?>)<br>
                            <input type='radio' name='forma_pago' value='bizum'> Por Bizum (Acuérdate de poner tu nombre y nick en el concepto) al número <span style='color:green'><b><?php echo $BIZUM ?></b></span><br>
							<!--<input type='radio' name='forma_pago' value='en mano a miembro de comisión'> Entrego el importe a un miembro de la Comisión.
							</div>
							<div id='capa_comisionero'>&nbsp;&nbsp;&nbsp;&nbsp;¿A quién? <input type='radio' name='comisionero' value='Fede'> Fede &nbsp;&nbsp;<input type='radio' name='comisionero' value='Tomás'> Tomás &nbsp;&nbsp;<input type='radio' name='comisionero' value='Rogelio'> Rogelio</div>--><br>
						</td>
						<td>&nbsp;</td><td></td>

					</tr>
					<tr><td colspan='10'><p>* Recuerda que tu Porra no será validada hasta que no se compruebe el pago del importe.</p></td></tr>
					<tr><td colspan='10'><h1>Para que la administración te contacte:</h1></td></tr>
					<tr><td><br>Teléfono (no obligatorio):</td><td><br><input type='text' name='telefono' id='telefono'></td><td>&nbsp;</td><td></td></tr>
					<tr><td>Email (no obligatorio):</td><td><input type='text' name='email' id='email'></td><td>&nbsp;</td><td></td></tr>
					<tr><td colspan='10'>[ Pero déjanos algo para que podamos contactarte, no lo usaremos para nada más :) ]</td></tr>
					<tr><td colspan='10'><p>&nbsp;</p></td></tr>
				</table>


<?php

	// Combo de goleadores
	$query="SELECT j.id,j.nombre,e.nombre pais FROM equipo e,jugador j WHERE j.id_equipo=e.id ORDER BY nombre";
	$res=bd_getAll($query,$conexion);
	echo "<p><select name='goleador'><option value=''>Elige a tu pichichi...</option>";
	while ($arra=bd_fetch($res)) {
		echo "<option value='".$arra["id"]."'>".$arra["nombre"]." (".$arra["pais"].")</option>";
	}
	echo "</select><br>";
	echo "* Si no encuentras a tu goleador en la lista que hemos diseñado, puedes enviar un correo a la siguiente dirección: <a href='mailto:".$EMAIL_ADMIN."'>".$EMAIL_ADMIN."</a>";
	echo "</p>";

	// Partidos de la primera fase
	$query="SELECT p.*,e1.nombre nombre1,e1.bandera bandera1,e2.nombre nombre2,e2.bandera bandera2 FROM partido p,equipo e1,equipo e2 WHERE p.id_equipo1=e1.id AND p.id_equipo2=e2.id AND p.fase=1 ORDER BY fecha,hora";
	$res=bd_getAll($query,$conexion);

	echo "<table>";
	while ($arra=bd_fetch($res)) {
		echo "<tr><td colspan=4>Fecha: ".date("d/m/Y",strtotime($arra["fecha"]))." ".$arra["hora"]."</td></tr>";
		echo "<tr><td><img class='badge-apuesta' width=32 height=32 src='".WEB_ROOT."/images/badges/".$arra["bandera1"]."'></td><td><h1 class='red'>".$arra["nombre1"]."</h1></td><td><input class='inputArea' name='resultado_".$arra["id"]."_1' type='text'></td>";
		echo "<tr><td><img class='badge-apuesta' width=32 height=32 src='".WEB_ROOT."/images/badges/".$arra["bandera2"]."'></td><td><h1 class='red'>".$arra["nombre2"]."</h1></td><td><input class='inputArea' name='resultado_".$arra["id"]."_2' type='text'></td>";
		echo "<tr><td colspan=4 height=10></td></tr>";
	}
	echo "</table>";
	echo "<br><input type='submit' value='Enviar apuesta'><br>&nbsp;";
?>
</form>
<?php } /* END no hay acción de ningún tipo */ ?>

<h1 class='red'>Bar Restaurante Enro</h1>
<p>Plaza del Duque de Pastrana, 3. Madrid<br>
<a target='_blank' href='http://maps.google.es/maps/place?cid=3875553414921857654&q=Enro&hl=es&cd=1&ei=yk0GTNzaJ5bUjAfPprHLCA&dtab=0&sll=40.468008,-3.677945&sspn=0.006295,0.006295&ie=UTF8&ll=40.469523,-3.680093&spn=0,0&t=h&z=18&iwloc=lyrftr:h,3875553414921857654,40.467964,-3.677995'>Haz click Aquí para ver cómo llegar</a>
</p>

<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>
<script type='text/javascript'>

	function validaform() {

		var formu=document.getElementById("insertar_porra");

		if (trim(formu.nombre.value)=="") {
			alert("Por favor, rellena tu nombre");
			formu.nombre.focus();
			return false;
		}
		if (trim(formu.apellido.value)=="") {
			alert("Por favor, rellena tu apellido");
			formu.apellido.focus();
			return false;
		}
		if (trim(formu.nick.value)=="") {
			alert("Por favor, rellena tu nick");
			formu.nick.focus();
			return false;
		}
		/*if (!test()) {
			alert("El nick no es correcto, por favor, escribe un nick correcto");
			formu.nick.focus();
			return false;
		}*/
		forma_pago = $('#capa_formapago input:radio:checked').val();
  		if (forma_pago==undefined) {
			alert('Por favor, selecciona una forma de pago');
			location.href='#formapago';
			return false;
		}
		else if (forma_pago=="en mano a miembro de comisión") {
			comisionero = $('#capa_comisionero input:radio:checked').val();
			if (comisionero==undefined) {
				alert('Si le vas a dar el dinero a un miembro de la comisión, por favor indícanos a cuál');
				location.href='#formapago';
				return false;
			}
		}
		if (trim(formu.goleador.value)=="") {
			alert("Por favor, elige a tu Pichichi. Si no está en la lista elige uno cualquiera y envíanos un email a <?php echo $EMAIL_ADMIN ?>");
			formu.goleador.focus();
			return false;
		}

		for(j=0;j<formu.elements.length;++j) {
			campo=formu.elements[j];
			if (campo.name.indexOf("esultado_")==1) {
				campo.value=trim(campo.value);
				if (!is_int(campo.value)) {
					alert("Hay un resultado que no es correcto o está sin rellenar. Revísalo por favor");
					campo.focus();
					return false;
				}
			}
		}

		return true;
	}

	function trim(cadena) {

		for(i=0; i<cadena.length; ) {
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(i+1, cadena.length);
			else
				break;
		}

		for(i=cadena.length-1; i>=0; i=cadena.length-1) {
			if(cadena.charAt(i)==" ")
				cadena=cadena.substring(0,i);
			else
				break;
		}

		return cadena;
	}

	function is_int(value){

		if (value=="") return false;

		for (i = 0 ; i < value.length ; i++) {
			if ((value.charAt(i) < '0') || (value.charAt(i) > '9')) return false;
		}
		return true;
	}

	function test() {

		var date = new Date();
		var time = date.getTime();
		var devuelve = 1;

		$.ajax({
			url: "ajax/comprueba_nick.php",
			data: "n="+$("#nick").val()+"&t="+time,
			success: function(msg){
				msg = msg.split("|");
				msg = msg[0];
				if (msg=="OK") {
        			$("#nick").removeClass("red");
        			$("div#mensajeValidacionNick").html("<font color='green'>OK: El nick es correcto y está libre</font>");
        			devuelve = true;
				}
				else if (msg=="LONGITUD_INSUFICIENTE") {
					$("#nick").addClass("red");
					alert("El nick es demasiado corto, debe tener al menos 3 caracteres (letras y/o números). Prueba a elegir otro");
					$("div#mensajeValidacionNick").html("<font color='red'>ERROR: Nick demasiado corto</font>");
					devuelve = false;
				}
				else if (msg=="ERRONEO") {
					$("#nick").addClass("red");
					alert("El nick sólo puede contener letras y/o números. Prueba a elegir otro");
					$("div#mensajeValidacionNick").html("<font color='red'>ERROR: El nick sólo puede contener letras y números</font>");
					devuelve = false;
				}
				else if (msg=="KO") {
					$("#nick").addClass("red");
					alert("Lo sentimos, este nick ya existe :( Prueba a elegir otro");
					$("div#mensajeValidacionNick").html("<font color='red'>ERROR: Este nick ya pertenece a otro jugador</font>");
					devuelve = false;
				}
      		}
      	});

      	return devuelve;
	}
</script>

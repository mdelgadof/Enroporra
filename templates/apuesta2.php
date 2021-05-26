<?php
	if ($_POST["accion"]=="insertar") {

		$nick=trim(str_replace("'","",$_POST["nick"]));
		$id=trim(str_replace("'","",$_POST["id"]));
		$telefono=trim(str_replace("'","",$_POST["telefono"]));
		$email=trim(str_replace("'","",$_POST["email"]));

		// Validación de porrista
		$query="SELECT id FROM porrista WHERE nick='".$nick."' AND id='".$id."'";
		$res=mysql_query($query,$conexion);
		if (!mysql_num_rows($res) || $nick=="") {
			$ko=true;
			$query="SELECT telefono,email,id FROM porrista WHERE nick='".$nick."'";
			$res=mysql_query($query,$conexion);
			$arra=mysql_fetch_array($res);
			if ($telefono==$arra["telefono"] && $telefono!="") $ko=false;
			else if ($email==$arra["email"] && $email!="") $ko=false;
			if ($ko) {
				echo "<h1 class='red'>Error</h1><p>El nick que nos das no existe en <b>Enroporra ".$NOMBRE_TORNEO."</b>, o no se ha introducido correctamente, o no coincide con el número de apostante. No hemos podido verificar tu identidad. La apuesta no se ha guardado. Prueba de nuevo <a href='javascript:history.go(-1)'>haciendo click Aquí</a>. o escríbenos un email a <a href='mailto:".$EMAIL_ADMIN."'>".$EMAIL_ADMIN."</a></p>";
				exit();
			}
			else $id_porrista=$arra["id"];
		}
		else $id_porrista=$id;

		// Segunda comprobación
		$query="SELECT id FROM apuesta WHERE id_partido=".$PRIMER_PARTIDO_SEGUNDA_FASE." AND id_porrista='".$id_porrista."'";
		$res=mysql_query($query,$conexion);
		if (mysql_num_rows($res)) {
				echo "<h1 class='red'>Error</h1><p>Hemos detectado que ya existe una apuesta previa para el nick ".strtoupper($nick)." en <b>Enroporra ".$NOMBRE_TORNEO."</b>. Hemos guardado esta apuesta, pero hasta que la comisión verifique la válida, mostramos la primera. Por favor, envíanos un email a <a href='mailto:".$EMAIL_ADMIN."'>".$EMAIL_ADMIN."</a></p>";
				$duplicada=true;
				$id_porrista*=-1;
		}

		// Inserción de sus resultados
		$apuestas=array();

		// Recogida de datos
		foreach ($_POST as $clave => $valor) {
			if (substr($clave,0,2)=="r_") {
				$temp=explode("_",$clave);
				$apuestas[$temp[1]]["r"][$temp[2]]=intval($valor);
			}
			if (substr($clave,0,2)=="e_") {
				$temp=explode("_",$clave);
				$apuestas[$temp[1]]["e"][$temp[2]]=intval($valor);
			}
		}

		// Inserción en BD
		foreach ($apuestas as $partido => $datos) {

			$id_equipo1=$datos["e"][1];
			$id_equipo2=$datos["e"][2];

			if ($datos["r"][1]>$datos["r"][2]) $quiniela="1";
			else if ($datos["r"][1]<$datos["r"][2]) $quiniela="2";
			else $quiniela=$_POST["q_".$partido];

			$query="INSERT INTO apuesta SET id_porrista='".$id_porrista."',id_partido='".$partido."',resultado1='".$datos["r"][1]."',resultado2='".$datos["r"][2]."',id_equipo1='".$id_equipo1."',id_equipo2='".$id_equipo2."',quiniela='".$quiniela."'";
			$res=mysql_query($query,$conexion);
		}
		$query="UPDATE porrista SET id_arbitro='".intval($_POST["arbitro"])."' WHERE id='".$id_porrista."'";
		$res=mysql_query($query,$conexion);

		if ($duplicada) {
			echo "<p>Esta es la primera apuesta de <span class='red'>".strtoupper($nick)."</span> que figura en nuestra base de datos:</p>";
			$id_porrista*=-1;
		}
		else {
			echo "<h1 class='red'>Apuesta insertada</h1><br><br><p>OK, hemos insertado la apuesta de la segunda fase para el nick <span class='red'>".strtoupper($nick)."</span>. Aquí está el detalle:</p>";
		}
		echo porra($id_porrista,2);
		echo "<br><br>";
		exit();
	}

	else {

		// Cerramos definitivamente apuestas media hora antes del primer partido de la segunda fase
        if (time()>=(strtotime($FECHA_PRIMER_PARTIDO_SEGUNDA_FASE)-30*60)) {
			echo "<h1 class='red'>¡AHORA SÍ QUE LA SUERTE ESTÁ ECHADA!</h1>
			<p><b>Se cerró el plazo definitivo de apuestas</b> La Comisión de <b>Enroporra</b> ha cerrado las apuestas porque ya ha empezado la fase de eliminatorias de ".$NOMBRE_TORNEO.". De entre los apostantes que hayan rellenado esta segunda fase saldrá nuestro ganador. ¡Mucha suerte a todos!</p>";
			exit();
		}

	}

	function cajaRounded($cadena) {

		return <<< EOT
		<div class="cajaFaseFinal">
        $cadena
		</div>
EOT;

	}

	function fichaEliminatoria($id_partido) {

		global $conexion,$arrayEliminatorias;
		$query="SELECT p.*,e1.nombre equipo1,e2.nombre equipo2,e1.bandera bandera1,e2.bandera bandera2 FROM partido p LEFT JOIN equipo e1 ON e1.id=p.id_equipo1 LEFT JOIN equipo e2 ON e2.id=p.id_equipo2 WHERE p.id='".$id_partido."'";
		$res=mysql_query($query,$conexion);
		$arra=mysql_fetch_array($res);

		$rotulo=array("","","OCTAVOS","CUARTOS","SEMIS","","FINAL");

		if ($arra["id_equipo1"]==0) {
			$nombre1="???";
			$bandera1=WEB_ROOT."/images/ask.jpg";
		}
		else {
			$nombre1=strtoupper(substr($arra["equipo1"],0,3));
			$bandera1=WEB_ROOT."/images/badges/".$arra["bandera1"];
		}
		if ($arra["id_equipo2"]==0) {
			$nombre2="???";
			$bandera2=WEB_ROOT."/images/ask.jpg";
		}
		else {
			$nombre2=strtoupper(substr($arra["equipo2"],0,3));
			$bandera2=WEB_ROOT."/images/badges/".$arra["bandera2"];
		}

		$content = "<span class='red'><b>".($id_partido)."</b></span> ".$rotulo[$arra["fase"]]."<br>".date("d/m/Y H:i",strtotime($arra["fecha"]." ".$arra["hora"]))."<br>";
		$content.= "<table>";
		$content.= "<tr><td><div id='b_".$id_partido."_1'><img src='".$bandera1."' width=22 height=22></div></td><td width=5></td><td><div id='n_".$id_partido."_1'><h2>".$nombre1."</h2></div></td><td width=5></td><td><input type='text' name='r_".$id_partido."_1' id='r_".$id_partido."_1' class='inputArea' style='height:20px; width:10px; font-size:20px;' maxlength=1 onBlur='siguienteEliminatoria(".$id_partido.",0)'></td></tr>";
		$content.= "<tr><td><div id='b_".$id_partido."_2'><img src='".$bandera2."' width=22 height=22></div></td><td width=5></td><td><div id='n_".$id_partido."_2'><h2>".$nombre2."</h2></div></td><td width=5></td><td><input type='text' name='r_".$id_partido."_2' id='r_".$id_partido."_2' class='inputArea' style='height:20px; width:10px; font-size:20px;' maxlength=1 onBlur='siguienteEliminatoria(".$id_partido.",0)'></td></tr>";
		$content.= "</table></div>";
		$content.= "<div id='capaGana_".$id_partido."' style='display:none'>&nbsp;Gana por penaltis:<br>

				<div style='float:left'><input type='radio' name='q_".$id_partido."' value='1' onClick='siguienteEliminatoria(".$id_partido.",1)'></div>
				<div style='float:left' id='n2_".$id_partido."_1' align='left'>".$nombre1."</div>
				<div style='float:left'>&nbsp;&nbsp;&nbsp;<input type='radio' name='q_".$id_partido."' value='2' onClick='siguienteEliminatoria(".$id_partido.",2)'></div>
				<div style='float:left' id='n2_".$id_partido."_2'>".$nombre2."</div>

			<input type='hidden' name='e_".$id_partido."_1' id='e_".$id_partido."_1' value='".$arra["id_equipo1"]."'>
			<input type='hidden' name='e_".$id_partido."_2' id='e_".$id_partido."_2' value='".$arra["id_equipo2"]."'>
			</div>
		";

		return cajaRounded($content);

	}

	$query="SELECT * FROM arbitro ORDER BY nombre";
	$res=mysql_query($query,$conexion);
	while ($arra=mysql_fetch_array($res)) {
		$optionsArbitros.="<option value='".$arra["id"]."'>".$arra["nombre"]."</option>";
	}

	echo "
		<table align='center'>
			<form name='fase2' method='post' action='apuesta.php' onSubmit='return revisaForm();'>
			<input type='hidden' name='accion' value='insertar'>
			<tr>
				<td valign='top' colspan=4>
					<h1 class='red'>APUESTA EN LA SEGUNDA FASE</h1><br><br>
					Introduce tu <b>nick</b>: <input type='text' class='inputArea' style='width:300px;' name='nick' id='nick'><br><br>
                                        <span class='red'><b>Debes rellenar al menos uno de los tres campos siguientes para poder identificarte:</b></span><br><br>
					Introduce tu <b>número de apostante</b> (si no lo recuerdas déjalo en blanco):<input type='text' class='inputArea' style='width:50px;' name='id' id='id'><br>
					Introduce tu <b>email</b> (si lo pusiste): <input type='text' class='inputArea' style='width:300px;' name='email' id='email'><br>
					Introduce tu <b>teléfono</b> (si lo pusiste): <input type='text' class='inputArea' style='width:300px;' name='telefono' id='telefono'><br>
                                        <span class='red'><b>Si no recuerdas ninguno de los tres, envíanos un mail a</span> <a href='mailto:".$EMAIL_ADMIN."'>".$EMAIL_ADMIN."</a></b><br><br>
					Rellena tu <b>apuesta</b>. Si marcas empate te preguntaremos quién pasa por penaltis. En estos casos los puntos por acertar el resultado sólo se dan si el ganador es el que tú dices.
				</td>
			</tr>";

    $query="SELECT id,fase FROM partido WHERE fase>1 ORDER BY id";
    $res=mysql_query($query,$conexion);

    $fase=0;

    while ($arra=mysql_fetch_array($res)) {
        if ($fase==0) {
            echo "<tr>";
            $fase=$arra["fase"];
            $partido=0;
        }
        if ($fase!=$arra["fase"]) {
            $fase=$arra["fase"];
            $partido=0;
            echo "<td height=20></td></tr><tr>";
        }
        $partido++;

        if ($fase==4&&$partido==1) echo "<td></td>";
        $tdfinal = ($fase==6) ? "colspan='4' align='center'":"";

        echo "<td ".$tdfinal." valign='top'>".fichaEliminatoria($arra["id"])."</td>";

        if ($fase==4&&$partido==2) echo "<td></td>";

        if ($partido==4||($fase==4&&$partido==2)||$fase==6) {
            echo "</tr><tr>";
            $partido=0;
        }
    }

    echo"
			    <td height=20></td></tr>
			<tr>
				<td colspan='4' align='center' valign='top'>El árbitro de la final:<br><select id='arbitro' name='arbitro'><option value=''>Elige a tu árbitro para la final...</option>".$optionsArbitros."</select></td>
			</tr>
			<tr><td height=20></td></tr>
			<tr>
				<td colspan='4' align='center' valign='top'><input type='submit' value='Insertar apuesta'></td>
			</tr>
			</form>
		</table>";

?>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>
<script type='text/javascript'>

	jQuery(document).on('keydown', 'input.large', function(ev) {
	    if(ev.which === 13) {
        	// Will change backgroundColor to blue as example

        	// Avoid form submit
	        return false;
	    }
	});
	function revisaForm() {

		if ($("#nick").val()=="") {
			alert("Sin tu nick no podemos validar la apuesta. Si no lo recuerdas envíanos un email a <?php echo $EMAIL_ADMIN ?>");
			$("#nick").focus();
			return false;
		}

		if ($("#id").val()=="" && $("#telefono").val()=="" && $("#email").val()=="") {
			alert("Necesitamos al menos uno de los tres campos (número de apostante, teléfono o email). Si no recuerdas ninguno envíanos un email a <?php echo $EMAIL_ADMIN ?>");
			$("#email").focus();
			return false;
		}

                for (i=437; i<=451; i++) {

			if (i==363) continue;

			siguienteEliminatoria(i,0);

			if ($("#e_"+i+"_1").val()=="" || $("#e_"+i+"_1").val()=="0") {
				alert("Falta el primer equipo por decidir en el partido "+i);
				return false;
			}
			if ($("#e_"+i+"_2").val()=="" || $("#e_"+i+"_2").val()=="0") {
				alert("Falta el segundo equipo por decidir en el partido "+i);
				return false;
			}
			if (!($("#r_"+i+"_1").val()>=0 && $("#r_"+i+"_1").val()<=9)) {
				alert("Por favor, completa todos los resultados, falta el primero en el partido "+i);
				$("#r_"+i+"_1").focus();
				return false;
			}
			if (!($("#r_"+i+"_2").val()>=0 && $("#r_"+i+"_2").val()<=9)) {
				alert("Por favor, completa todos los resultados, falta el segundo en el partido "+i);
				$("#r_"+i+"_2").focus();
				return false;
			}
		}

		if ($("#arbitro").val()=="") {
			alert("Elige tu árbitro para la final");
			$("#arbitro").focus();
			return false;
		}


		return true;
	}

	function siguienteEliminatoria(partido,quiniela) {

		var date = new Date();
		var time = date.getTime();
		winner = 0;

		var arrayEliminatorias = new Array();

        <?php
            $query="SELECT id,vencedor_eliminatoria FROM partido WHERE vencedor_eliminatoria!='' ORDER BY id";
            $res=mysql_query($query,$conexion);
            while ($arra=mysql_fetch_array($res)) {
            ?>
                arrayEliminatorias[<?php echo $arra["id"] ?>] = "<?php echo $arra["vencedor_eliminatoria"] ?>";
        <?php
            }
        ?>

		resultado1=$("#r_"+partido+"_1").val();
		resultado2=$("#r_"+partido+"_2").val();

		if (resultado1==""||resultado2=="") return;

		else if (resultado1>resultado2) {
			$("#capaGana_"+partido).hide();
			winner=1;
		}
		else if (resultado2>resultado1) {
			$("#capaGana_"+partido).hide();
      		winner=2;
		}
		else {
			$("#capaGana_"+partido).fadeIn();
			//if (quiniela) winner=quiniela;
			winner=$('#capaGana_'+partido+' input:radio:checked').val();
		}

		if (partido==64) return;

		if (winner) {
   			$("#b_"+arrayEliminatorias[partido]).html($("#b_"+partido+"_"+winner).html());
   			$("#n_"+arrayEliminatorias[partido]).html($("#n_"+partido+"_"+winner).html());
   			$("#n2_"+arrayEliminatorias[partido]).html($("#n2_"+partido+"_"+winner).html());
  			$("#e_"+arrayEliminatorias[partido]).val($("#e_"+partido+"_"+winner).val());
		}
		else {
   			$("#b_"+arrayEliminatorias[partido]).html("<img src='<?php echo WEB_ROOT ?>/images/ask.jpg' width=22 height=22>");
   			$("#n_"+arrayEliminatorias[partido]).html("<h2>???</h2>");
   			$("#n2_"+arrayEliminatorias[partido]).html("???");
   			$("#e_"+arrayEliminatorias[partido]).val(0);
		}

		temp=arrayEliminatorias[partido].split("_");
		siguienteEliminatoria(temp[0],0);
	}
</script>

<?php
	if ($_GET["accion"]=="eliminar_gol") {
		$id_gol=intval($_GET["id"]);
		$query="DELETE FROM goles WHERE id='$id_gol'";
		$res=bd_getAll($query,$conexion);
		echo "<script>location.href='partidos.php?t=".time()."'</script>";
		exit();
	}

	if ($_POST["accion"]=="insertar") {

		// Inserción de partidos
		$partidos=array();

		// Recogida de datos
		foreach ($_POST as $clave => $valor) {
			// Creamos resultados para los partidos
			if (substr($clave,0,10)=="resultado_") {
				$temp=explode("_",$clave);
				if ($valor=="") $valor=-1;
				$partidos[$temp[1]][$temp[2]]=intval($valor);
			}
			// Insertamos los goleadores
			if (substr($clave,0,9)=="goleador_" && $valor) {
				$temp=explode("_",$clave);
				$query="INSERT INTO goles SET id_goleador='".$valor."',id_partido='".$temp[1]."'";
				$res=bd_getAll($query,$conexion);
			}
			// Incluso si son nuevos
			if (substr($clave,0,14)=="nuevogoleador_" && $valor) {
				$temp=explode("_",$clave);
				$query="INSERT INTO jugador SET nombre='".str_replace("'","",$valor)."', id_equipo='".$temp[1]."'";
				$res=bd_getAll($query,$conexion);
				$id_jugador=bd_insert();
				$query="INSERT INTO goles SET id_goleador='".$id_jugador."',id_partido='".$temp[2]."'";
				$res=bd_getAll($query,$conexion);
			}
		}

		// Inserción en BD de partidos
		foreach ($partidos as $partido => $resultado) {

			if ($resultado[1]>$resultado[2]) $quiniela="1";
			else if ($resultado[1]<$resultado[2]) $quiniela="2";
			else {
				if ($_POST["fase"]==1) $quiniela="X";
				else $quiniela=$_POST["q_".$partido];
			}

			$query="UPDATE partido SET resultado1='".$resultado[1]."',resultado2='".$resultado[2]."',quiniela='".$quiniela."' WHERE id='".$partido."'";
			$res=bd_getAll($query,$conexion);
		}

		echo "<h1 class='red'>Inserción correcta</h1><p>OK, se han insertado los resultados y goleadores que has colocado. Más abajo puedes ver cómo queda el calendario real</p>";

	}

?>
		<form name='insertar_resultados' id='insertar_resultados' action='partidos.php' method='post' onSubmit='return validaform();'>
		<input type='hidden' name='accion' value='insertar'>
			<h1 class='red'>Resultados reales</h1>
                <p>Aquí escribimos los resultados y goleadores que se vayan produciendo en la realidad durante <?php echo $NOMBRE_TORNEO ?>. Esto nos permitirá tener actualizada la home con los últimos partidos y a la vez permitirá que la clasificación se vaya generando automáticamente. Por motivos obvios, el sistema no deja introducir el resultado de un partido que todavía no se ha jugado (o lo que es lo mismo, tiene fecha futura).</p>
<?php

	date_default_timezone_set("Europe/Madrid");

	// Partidos de la primera fase
	$faseQ = (date("Y-m-d H:i:s")<=$FECHA_PRIMER_PARTIDO_SEGUNDA_FASE) ? "p.fase=1":"p.fase>1";
        $query="SELECT p.*,e1.nombre nombre1,e1.bandera bandera1,e2.nombre nombre2,e2.bandera bandera2 FROM partido p,equipo e1,equipo e2 WHERE p.id_equipo1=e1.id AND p.id_equipo2=e2.id AND fecha<='".date("Y-m-d")."' AND ".$faseQ." ORDER BY fecha DESC, hora DESC";
	/*echo $query;
	exit();*/
	$res=bd_getAll($query,$conexion);
	$num=bd_num($res);
	if (!$num) echo "Todavía no ha empezado $NOMBRE_TORNEO, no se pueden poner los resultados de los partidos :)<br><br>";

	echo "<table>";
	while ($arra=bd_fetch($res)) {

		$valor1= ($arra["resultado1"]>=0 && $arra["resultado1"]!="") ? $arra["resultado1"]:"";
		$valor2= ($arra["resultado2"]>=0 && $arra["resultado2"]!="") ? $arra["resultado2"]:"";

		$comboGoleadores=optionsGoleadores("",$arra["id_equipo1"],$arra["id_equipo2"]);

		echo "<input type='hidden' name='fase' value='".$arra["fase"]."'>";
                echo "<tr><td colspan=4>Fecha: ".date("d/m/Y H:i",strtotime($arra["fecha"]." ".$arra["hora"]))."</td></tr>";
		echo "<tr><td><img width=32 height=32 src='".WEB_ROOT."/images/badges/".$arra["bandera1"]."'></td><td><h1 class='red'>".$arra["nombre1"]."</h1></td><td><input class='inputArea' value='".$valor1."' name='resultado_".$arra["id"]."_1' type='text'></td>";
		echo "<tr><td><img width=32 height=32 src='".WEB_ROOT."/images/badges/".$arra["bandera2"]."'></td><td><h1 class='red'>".$arra["nombre2"]."</h1></td><td><input class='inputArea' value='".$valor2."' name='resultado_".$arra["id"]."_2' type='text'></td>";
		if ($arra["fase"]>1 && $arra["resultado1"]==$arra["resultado2"]) {
			$checked1 = ($arra["quiniela"]=="1") ? "checked":"";
			$checked2 = ($arra["quiniela"]=="2") ? "checked":"";
			echo "
			<tr>
				<td colspan='2'> <input type='radio' name='q_".$arra["id"]."' value='1' ".$checked1."> Gana <b>".$arra["nombre1"]."</b> por penaltis</td>
				<td colspan='2'> <input type='radio' name='q_".$arra["id"]."' value='2' ".$checked2."> Gana <b>".$arra["nombre2"]."</b> por penaltis</td>
			</tr>";
		}

		$query="SELECT g.id,j.nombre,e.nombre as pais FROM goles g,jugador j,equipo e WHERE g.id_goleador=j.id AND e.id=j.id_equipo AND g.id_partido='".$arra["id"]."' ORDER BY pais";
		$res2=bd_getAll($query,$conexion);
		echo "<tr><td colspan='4'><span class='red'>GOLES:</span> ";
		while ($arra2=bd_fetch($res2)) {
			echo $arra2["nombre"]." (".$arra2["pais"].") <a href='partidos.php?accion=eliminar_gol&id=".$arra2["id"]."'>(eliminar)</a> / ";
		}
		echo "</td></tr>";

		for ($i=1; $i<=5; $i++) {
			echo "
			<tr>
				<td colspan='2'><select name='goleador_".$arra["id"]."_".$i."'><option value=''>Elige goleador...</option>".$comboGoleadores."</select></td>
				<td colspan='2'><select name='goleador_".$arra["id"]."_".($i+5)."'><option value=''>Elige goleador...</option>".$comboGoleadores."</select></td>
			</tr>";
		}

		echo "
			<tr>
				<td colspan='2'><img width=16 height=16 src='".WEB_ROOT."/images/badges/".$arra["bandera1"]."'> Nuevo goleador:<br><input type='text' class='inputArea' style='width:200px;' name='nuevogoleador_".$arra["id_equipo1"]."_".$arra["id"]."'></td>
				<td colspan='2'><img width=16 height=16 src='".WEB_ROOT."/images/badges/".$arra["bandera2"]."'> Nuevo goleador:<br><input type='text' class='inputArea' style='width:200px;' name='nuevogoleador_".$arra["id_equipo2"]."_".$arra["id"]."'></td>
			</tr>";

		echo "<tr><td colspan=4 height=20></td></tr>";
	}
	echo "</table>";

	if ($num) echo "<br><input type='submit' value='Actualizar resultados'><br>&nbsp;";
?>
</form>

<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>
<script type='text/javascript'>

	function validaform() {

		var formu=document.getElementById("insertar_resultados");

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

</script>


<?php

function clasificacion($tipo="completa") {

	global $conexion,$_COOKIE,$nickRegistrado,$NOMBRE_TORNEO;

	date_default_timezone_set("Europe/Madrid");

	if ($tipo=="completa") {
		$cabecera="<span class='red'>GENERAL</span>";
		$condicionQuery=" AND id NOT IN (141,115,149)";
		$destacados=5;
	}
	if ($tipo=="amigos") {
		$cabecera="<span class='red'>de tu grupo de amigos</span>";
		$amigosEnro=convierteAmigos($_COOKIE["amigosEnro"]);
		if ($amigosEnro) {
			$condicionQuery=" AND nick IN ($amigosEnro)";
		}
		else return $devuelve;
		$destacados=1;
	}

	$query="SELECT count(*) c FROM partido WHERE fecha<='".date("Y-m-d")."' AND resultado1>=0";
	$res=bd_getAll($query,$conexion);
	$arra=bd_fetch($res);
	$partidos=$arra["c"];

	if ($partidos) {

		$nombresExistentes=array();
		$devuelve.= "Clasificación $cabecera a d�a de hoy (<span class='red'><b>$partidos</b></span> partidos disputados y apuntados) :<br><br>";

		$query="SELECT id FROM partido WHERE resultado1<0 ORDER BY fecha,hora LIMIT 4";
		$res=bd_getAll($query,$conexion);
		$proximosPartidos=array();
		while ($arra=bd_fetch($res)) {
			$proximosPartidos[]=$arra["id"];
		}

		$arrayPorristas=array();
		$query="SELECT id,nick,nombre,apellido,id_goleador,id_arbitro FROM porrista WHERE pagado='si' ".$condicionQuery;
		$res=bd_getAll($query,$conexion);
		$i=0;
		while ($arra=bd_fetch($res)) {
			$arrayPorristas[$i]["nick"]=$arra["nick"];
			$arrayPorristas[$i]["id"]=$arra["id"];
			$arrayPorristas[$i]["nombre"]=normalizaNombre($arra["nombre"]." ".$arra["apellido"]);
			$arrayPorristas[$i]["id_goleador"]=$arra["id_goleador"];
			$arrayPorristas[$i]["id_arbitro"]=$arra["id_arbitro"];
			if (in_array($arrayPorristas[$i]["nombre"],$nombresExistentes)) $arrayPorristas[$i]["nombre"].=" (2)";
			else $nombresExistentes[]=$arrayPorristas[$i]["nombre"];
			list($arrayPorristas[$i]["puntos"],$arrayPorristas[$i]["string"])=puntos($arra["id"]);
			$i++;
		}

		usort($arrayPorristas, "cmp");

		$clasificacion=1; $puntuacionAnterior="";
		$devuelve.= "<table>";
		$devuelve.= "<tr><td colspan='2'></td><td>Puntos</td><td>Apuesta</td><td>&nbsp;&nbsp;&nbsp;&nbsp;Apuesta en pr�ximos partidos</td></tr>";
		foreach ($arrayPorristas as $porrista) {

			if ($bgColor=="#FFFFFF") $bgColor="#EEEEEE"; else $bgColor="#FFFFFF";

			if ($clasificacion<=$destacados) {
				$head1="<h2>";
				$head2="</h2>";
			}
			else $head1=$head2="";

			/*$query="SELECT count(*) FROM goles WHERE id_goleador='".$porrista["id_goleador"]."'";
			$res=bd_getAll($query,$conexion);
			$arra=bd_fetch($res);
			$goles=$arra[0]; $golesString="";
			for ($i=1; $i<=$goles; $i++) $golesString.="<img src='".WEB_ROOT."/images/balon.png' width=10 height=10>&nbsp;";*/

			$clasificacionString = ($puntuacionAnterior==$porrista["puntos"]) ? "-":$clasificacion;
			$puntuacionAnterior=$porrista["puntos"];

			$colorDestacado = (strtolower($nickRegistrado)==strtolower($porrista["nick"])) ? "bgColor='#FFFF00'":"";
			$colorFuturaApuesta = (strtolower($nickRegistrado)==strtolower($porrista["nick"])) ? "bgColor='#FFFF00'":"bgColor='$bgColor'";

			$devuelve.= "<tr ".$colorDestacado."><td nowrap>";
			$devuelve.= $head1."<span class='red'>".$clasificacionString."</span> ".$porrista["nombre"]." [<span class='red'>".$porrista["puntos"]."</span>]".$head2."<br>".$golesString;
			$devuelve.= "</td><td width='20'></td><td align='center'>";
			$devuelve.= "<div id='enlace_".str_replace(" ","",strtoupper($porrista["nick"]))."'><a alt='Ver los puntos que lleva ".$porrista["nombre"]."' href='javascript:verDetalle(\"".str_replace(" ","",strtoupper($porrista["nick"]))."\")'><img src='".WEB_ROOT."/images/bombilla.jpg' alt='Ver los puntos que lleva ".$porrista["nombre"]."' width=32 height=32></a></div>";
			$devuelve.= "</td><td align='center'><a href='".WEB_ROOT."/cuenta.php?accion=ver&nick=".$porrista["nick"]."'><img src='".WEB_ROOT."/images/sobre.jpg' alt='Ver la apuesta completa de ".$porrista["nombre"]."' width=32 height=32></a></td>";
			$devuelve.= "<td ".$colorFuturaApuesta." nowrap>".apuestaPartidos($porrista["id"],$proximosPartidos)."</td>";
			$devuelve.= "</tr>";
			$devuelve.= "<tr><td colspan='5'>";
			$devuelve.= "<div id='detalle_".str_replace(" ","",strtoupper($porrista["nick"]))."' style='display:none'><p>".$porrista["string"]."</p></div>";
			$devuelve.= "</td></tr>";
			$clasificacion++;
			if ($clasificacion==($destacados+1)) $devuelve.= "<tr><td colspan='5' height=20></td></tr>";
		}
		$devuelve.= "</table><br><br>";

	} // END existen partidos reales

	else {
		$devuelve.= "<p>Todavía no ha comenzado ".$NOMBRE_TORNEO." en Enroporra :)</p>";
	}

	$WEB_ROOT=WEB_ROOT;
	$devuelve.= <<< EOT
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>
		<script type='text/javascript'>
		function verDetalle(nombre) {
			$("#detalle_"+nombre).show();
			$("#enlace_"+nombre).html("<a href='javascript:ocultarDetalle(\""+nombre+"\")'><img src='$WEB_ROOT/images/bombillaoff.jpg' alt='Ver los puntos que lleva "+nombre+"' width=32 height=32></a>");
		}
		function ocultarDetalle(nombre) {
			$("#detalle_"+nombre).hide();
			$("#enlace_"+nombre).html("<a href='javascript:verDetalle(\""+nombre+"\")'><img src='$WEB_ROOT/images/bombilla.jpg' alt='Ver los puntos que lleva "+nombre+"' width=32 height=32></a>");
		}

		</script>
EOT;

	return $devuelve;

} // END funcion clasificacion

?>

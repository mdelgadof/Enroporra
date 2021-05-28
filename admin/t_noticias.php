<?php
	function formNoticia($id_noticia=0) {

		global $conexion;

		if ($id_noticia) {
			$query="SELECT * FROM noticias WHERE id='".$id_noticia."'";
			$res=bd_getAll($query,$conexion);
			$arra=bd_fetch($res);
			$titular=$arra["titular"];
			$cuerpo=str_replace("<br />","",$arra["cuerpo"]);
			$selSi = ($arra["activa"]=="si") ? "selected":"";
			$selNo = ($arra["activa"]=="no") ? "selected":"";
			$rotulo="Actualizar";
			$hidden="actualizar";
			$hidden2="<input type='hidden' name='id' value='$id_noticia'>";
		}
		else {
			$titular=$cuerpo=$selSi=$selNo="";
			$rotulo="Insertar";
			$hidden="insertar";
			$hidden2="";
		}
		$devuelve.="
		<form name='noticia_".$id_noticia."' method='post' action='noticias.php'>
		<input type='hidden' name='accion' value='".$hidden."'>
		".$hidden2."
		<table>
			<tr>
				<td><h1>Titular</h1></td>
				<td width=20></td>
				<td><input name='titular' type='text' class='inputArea' style='width:500px' value='".$titular."'></td>
			</tr>
			<tr>
				<td valign='top'><h1>Cuerpo HTML</h1></td>
				<td width=20></td>
				<td><textarea name='cuerpo' style='width:500px; height:400px;'>".$cuerpo."</textarea></td>
			</tr>
			<tr>
				<td><h1>Visible en home</h1></td>
				<td width=20></td>
				<td>
					<select name='activa' class='inputArea' style='width:50px'>
					<option value=''></option>
					<option value='si' ".$selSi.">Sí</option>
					<option value='no' ".$selNo.">No</option>
					</select>
				</td>
			</tr>
		</table>
		<input type='submit' value='".$rotulo."'>
		</form>";
		return $devuelve;
	}

	if ($_POST["accion"]=="insertar"||$_POST["accion"]=="actualizar") {

		// Recogida de datos
		foreach ($_POST as $clave => $valor) {

			if ($clave=="accion" || $clave=="id") continue;
			$valor=nl2br(str_replace("'","",$valor));
			$queryD.=$clave."='".$valor."',";

		}
		$queryD.="fecha='".date("Y-m-d H:i:s")."'";

		if ($_POST["accion"]=="insertar") $query="INSERT INTO noticias SET ";
		else if ($_POST["accion"]=="actualizar") $query="UPDATE noticias SET ";

		$query.=$queryD;
		if ($_POST["id"]) $query.=" WHERE id='".$_POST["id"]."'";
		$res=bd_getAll($query,$conexion);

		echo "<h1 class='red'>Noticia insertada/modificada correctamente</h1><p>OK, se han insertado/modificado la noticia.</p>";

	}

	echo "<h1 class='red'>Insertar noticia</h1>
	<p>".formNoticia()."</p>";

	echo "<h1 class='red'>Modificar noticias</h1>";
	$query="SELECT * FROM noticias ORDER BY fecha DESC";
	$res=bd_getAll($query,$conexion);

	while ($arra=bd_fetch($res)) {
		echo "<p>".formNoticia($arra["id"])."</p>";
	}
	echo "<br><br>";
?>

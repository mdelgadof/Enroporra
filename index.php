<?php include "inc/inc.php"; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Created by: Reality Software | www.realitysoftware.ca
Released by: Flash MP3 Player | www.flashmp3player.org
Note: This is a free template released under the Creative Commons Attribution 3.0 license,
which means you can use it in any way you want provided you keep links to authors intact.
Don't want our links in template? You can pay a link removal fee: www.realitysoftware.ca/templates/
You can also purchase a PSD-file for this template.
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Enroporra - <?php echo $NOMBRE_TORNEO ?></title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/headers<?php echo rand(1,12) ?>.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="http://www.uefa.com/imgml/favicon/comp/euro2016.ico" />
</head>
<body>
<div id="container">
	<!-- header -->
    <div id="header">
    	<div id="logo"><a href="<?php echo WEB_ROOT ?>">&nbsp;</a></div>
        <div id="menu">
        	<ul>
              <li><a href="<?php echo WEB_ROOT ?>">Home</a></li>
              <li><a href="apuesta.php">Apuesta</a></li>
              <li><a href="<?php echo $ENLACE_BASES ?>" target='_blank'>Bases</a></li>
              <li><a href="cuenta.php">Mi cuenta</a></li>
              <li><a href="amigos.php">Amigos</a></li>
              <li><a href="clasificacion.php">Clasificaci&oacute;n</a></li>
              <li><a href="<?php echo $ENLACE_PARTIDOS ?>">Partidos</a></li>
              <li><a href='mailto:<?php echo $EMAIL_ADMIN ?>'>Contacto</a></li>
          </ul>
      </div>
  </div>
    <!--end header -->
    <!-- main -->
    <div id="main">
    	<div id="content">
			<div style='float:right'>
				<a href="https://twitter.com/comisionporra" class="twitter-follow-button" data-show-count="false" data-lang="es" data-size="large">Seguir a @comisionporra en Twitter</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
			<div style='clear:both'></div>

			<?php
				date_default_timezone_set("Europe/Madrid");

				if (!$conexion) {
					$excusa[0]="Nuestro servidor de base de datos se ha ido a tomar una ca&ntilde;a al Enro. Vuelve en un rato.";
					$excusa[1]="Nuestro servidor de base de datos ha ido al ba&ntilde;o un momento. Maldita ensaladilla de chiringuito...";
					$excusa[2]="Nuestro servidor de base de datos est&aacute; viendo los res&uacute;menes de los partidos, me ha dicho que ahora viene.";
					$excusa[3]="A nuestro servidor de base de datos le han dicho que ya est&aacute; bien de tanto Mundial y tanta historia y se ha ido con la novia a dar un paseo. Enseguida vuelve.";
					echo "<h1 class='red'>Uuuuuups</h1>
					<p>".$excusa[rand(0,3)]." Gracias por la paciencia :-)</p>";
				}
				else  {
				
					if ($apuesta!=1 && $apuesta2!=1) {
					
						echo "<h1 class='red'>Pr&oacute;ximos partidos</h1>(Nota: puedes consultar los <a href='".$ENLACE_PARTIDOS."' target='_blank'>partidos</a> y la <a href='".$ENLACE_GOLEADORES."' target='_blank'>clasificaci&oacute;n de goleadores</a> en la p&aacute;gina de la UEFA)<br><br>";
						$horaHoy = (date("H")<2) ? "00:00:00":date("H:i:s",strtotime(date("Y-m-d H:i:s"))-7200);
						
						$query="SELECT id FROM partido WHERE ((fecha='".date("Y-m-d")."' AND hora>='".$horaHoy."') OR fecha>'".date("Y-m-d")."') AND fase!=6 ORDER BY fecha, hora LIMIT 3";
						//if ($_GET["test"]==1) echo $query."<br>";
						$res=bd_getAll($query,$conexion);
						echo "<div style='margin:auto'><table><tr>";
						$contador=0;
						while($arra=bd_fetch($res)) {
							echo"<td valign='top'>".partido($arra["id"])."</td>";
							echo"<td width=40></td>";
							$contador++;
							//if ($contador==2) echo "</tr><tr>";
						}
						echo "</tr></table></div>";
						
						
						if (0) {

							echo "<br><h1 class='red'>&Uacute;ltimos partidos jugados</h1><br>";

							$query="SELECT id FROM partido WHERE resultado1>=0 AND resultado2>=0 ORDER BY fecha DESC, hora DESC LIMIT 3";
							//if ($_GET["test"]==1) echo $query."<br>";
							$res=bd_getAll($query,$conexion);
							echo "<div style='margin:auto'><table><tr>";
							$contador=0;
							while($arra=bd_fetch($res)) {
								echo"<td valign='top'>".partido_jugado($arra["id"])."</td>";
								$contador++;
								//if ($contador==2) echo "</tr><tr>";
							}
						
							echo "</tr></table></div>";
						}
					}
					


/*echo"<div align='center'>
<div><img src='/images/copa_euro.jpg' height='194'> <img src='/images/copa_mundial.jpg' height='194'> <img src='/images/copa_euro.jpg' height='194'></div>
<div><h1>ESPAÑA TRICAMPEONA ¡FELICIDADES!</h1></div>
</div>";*/

echo "<br><br>"; //Son las <b>".date("H:i")."</b><br><br>";
?>
            <div id="content-left" <?php if ($cuenta==1||$clasificacion==1||$clasificacion_1==1||$amigos==1||$apuesta2==1) echo 'style="width:100% !important"'; ?>>
<?php

					if ($apuesta==1) include "templates/apuesta.php";
					else if ($cuenta==1) include "templates/cuenta.php";
					else if ($equipos==1) include "templates/equipos.php";
					else if ($clasificacion==1) include "templates/clasificacion.php";
                    else if ($clasificacion_1==1) include "templates/clasificacion_1.php";
					else if ($amigos==1) include "templates/amigos.php";
					else if ($apuesta2==1) include "templates/apuesta2.php";
					else { $home=1; include "home.php"; }

			?>
            </div>
            <?php                 } // END else la conexion a BD funciona correctamente

            if ($apuesta==1 || $home==1) { ?>
            <div id="content-right">
                <?php include "templates/twitter.html"; ?>
            </div>
            <?php } ?>
            <div style='clear:both'></div>
        </div>
    </div>
    <!-- end main -->
    <!-- footer -->
    <div id="footer">
    <div id="left_footer">Copyright 1994-<?php echo date("Y") ?> Enroporra
    </div>
    <div id="right_footer">

    </div>
    </div>
    <!-- end footer -->
</div>
<div style="text-align: center; font-size: 0.75em;">Design downloaded from <a href="http://www.freewebtemplates.com/">free website templates</a>.</div>
</body>
</html>

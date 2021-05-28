<?php include "inc/inc.php";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<title>Enroporra - Mundial 2010</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/headers<?php echo rand(1,12) ?>.css" rel="stylesheet" type="text/css" />
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
              <li><a href="bases_enroporra_mundial_2010.pdf" target='_blank'>Bases</a></li>
              <li><a href="cuenta.php">Mi cuenta</a></li>
              <li><a href="clasificacion.php">Clasificación</a></li>
              <li><a href='mailto:<?php echo $EMAIL_ADMIN ?>'>Contacto</a></li>
          </ul>
      </div>
  </div>
    <!--end header -->
    <!-- main -->
    <div id="main">
    	<div id="content">
			<?php
			if ($_GET["test"]==1) {
				echo "<h1 class='red'>Próximos partidos</h1> ";
				$query="SELECT id FROM partido WHERE ((fecha='".date("Y-m-d")."' AND hora>'".date("H:i:s")."') OR fecha>'".date("Y-m-d")."') ORDER BY fecha, hora LIMIT 50";
				$res=bd_getAll($query,$conexion);
				echo "<table><tr>";
				$i=0;
				while($arra=bd_fetch($res)) {
					$i++;
					echo"<td>".partido($arra["id"])."</td>";
					if ($i%3==0) echo"</tr><tr>";
				}
				echo "</tr></table>";
				echo "<br><br>";
			}
			if ($apuesta==1) include "templates/apuesta.php";
			else if ($cuenta==1) include "templates/cuenta.php";
			else if ($clasificacion==1) include "templates/clasificacion.php";
			else include "home.php";
			?>
        </div>
    </div>
    <!-- end main -->
    <!-- footer -->
    <div id="footer">
    <div id="left_footer">Copyright 2010 Enroporra
    </div>
    <div id="right_footer">

<!-- Please do not change or delete these links. Read the license! Thanks. :-) -->
<a href="http://www.realitysoftware.ca/services/website-development/design/">Web design</a> released by <a href="http://www.flashmp3player.org/">Flash MP3 Player</a><br>
Site hosted on <a href="http://www.000webhost.com/">000webhost.com</a>

    </div>
    </div>
    <!-- end footer -->
</div>
<div style="text-align: center; font-size: 0.75em;">Design downloaded from <a href="http://www.freewebtemplates.com/">free website templates</a>.</div></body>
</html>

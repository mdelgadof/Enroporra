<? include "../inc/inc.php";
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
<title>Admin Enroporra - Mundial 2010</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/headers<?= rand(1,12) ?>.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
	<!-- header -->
    <div id="header">
    	<div id="logo"><a href="/">&nbsp;</a></div>
        <div id="menu">
        	<ul>
              <li><a href="<?= WEB_ROOT ?>/admin">Home Admin</a></li>
              <li><a href="pagados.php">Pagados</a></li>
              <li><a href="partidos.php">Partidos</a></li>
              <li><a href="apuesta2.php">Apuesta 2 Fase</a></li>
              <li><a href="emails.php">Emails</a></li>
              <li><a href="noticias.php">Noticias Home</a></li>
          </ul>
      </div>
  </div>
    <!--end header -->
    <!-- main -->
    <div id="main">
    	<div id="content">
			<?
			if ($pagados==1) include "t_pagados.php";
			else if ($partidos==1) include "t_partidos.php";
			else if ($apuesta2==1) include "t_apuesta2.php";
			else if ($noticias==1) include "t_noticias.php";
                        else if ($emails==1) include "t_emails.php";
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
<a href="http://www.realitysoftware.ca/services/website-development/design/">Web design</a> released by <a href="http://www.flashmp3player.org/">Flash MP3 Player</a>

    </div>
    </div>
    <!-- end footer -->
</div>
<div style="text-align: center; font-size: 0.75em;">Design downloaded from <a href="http://www.freewebtemplates.com/">free website templates</a>.</div></body>
</html>

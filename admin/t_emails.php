<h1 class='red'>Listado de emails</h1>
<p>A continuación va un listado de todos los emails que tenemos en base de datos, ordenados por "pagados", "no pagados" y "todos", separados por punto y coma para que sea sencillo copiar y pegarlos en la copia oculta para enviar emails informativos.</p>
<?

$query="SELECT pagado,email FROM porrista WHERE email!=''";
$res=mysql_query($query,$conexion);
$pagados=$nopagados=0;
$stringPagados=$stringNoPagados="";
while ($arra=mysql_fetch_array($res)) {
	if ($arra["pagado"]=="si") {
            $pagados++;
            $stringPagados.=$arra["email"]."; ";
        }
	if ($arra["pagado"]=="no") {
            $nopagados++;
            $stringNoPagados.=$arra["email"]."; ";
        }
}
$porristas=$pagados+$nopagados;


echo "<h1 class='red'>EMAILS JUGADORES PAGADOS:</h1> (pagados <span class='red'>$pagados</span>)<p>$stringPagados</p>";
echo "<h1 class='red'>EMAILS JUGADORES NO PAGADOS:</h1> (no pagados <span class='red'>$nopagados</span>)<p>$stringNoPagados</p>";
echo "<h1 class='red'>EMAILS JUGADORES TOTALES:</h1> (totales <span class='red'>$porristas</span>)<p>".$stringPagados.$stringNoPagados."</p>";
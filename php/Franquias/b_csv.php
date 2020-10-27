<?php 
require "../connect/conexao_conecta.php";

$rd = rand(10,25);
$path = "/var/www/html/tmp/arquivo$rd.csv"; 
$do = unlink($path);

$venc1 = implode(preg_match("~\/~", $vencimento1) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $vencimento1) == 0 ? "-" : "/", $vencimento1)));
$venc2 = implode(preg_match("~\/~", $vencimento2) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $vencimento2) == 0 ? "-" : "/", $vencimento2)));
$intervalo = "and a.vencimento between '$venc1' and '$venc2'";
if (!$venc1 || !venc2) $intervalo = "";

$sql_csv = "select a.numdoc, mid(b.logon,1,LOCATE('S',b.ogon)-1) as logon, c.razaosoc, c.nomefantasia, a.valor, DATE_FORMAT(a.vencimento,'%d/%m/%Y') as 								vencimento, a.valorpg, DATE_FORMAT(a.datapg,'%d/%m/%Y') as datapg, a.numboleto, a.origem_pgto
			into outfile '$path' fields terminated by ';' enclosed by '' lines terminated by '\r\n'
			from titulos a
			inner join logon b on a.codloja = b.codloja
			inner join cadastro c on a.codloja = c.codloja
			where mid(b.logon,1,LOCATE('S',b.logon)-1) between '$codigo1' and '$codigo2' $situacao $intervalo
			group by numdoc
			order by $ordem, a.vencimento";
$res = mysql_query($sql_csv,$con) or die("Erro no SQL");
echo "<div align=\"center\" class=formulario><a href=\"http://189.16.26.132/tmp/arquivo$rd.csv\">Clique aqui para ver o relat&oacute;rio</a></div>";
?>
</center>
<form method="post" action="painel.php?pagina1=Franquias/b_baixafatura.php">
<div align="center"><input type="submit" value="       Voltar       " /></div>
</form>
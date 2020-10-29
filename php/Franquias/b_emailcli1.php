<?php
require "connect/sessao_r.php";
//require "../connect/conexao_conecta.php";

$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;

$criterio = $_POST['criterio'];
if (empty($criterio)) $criterio = $_GET['criterio'];
$status = $_POST['status'];
if (empty($status)) $status = $_GET['status'];
$lpp = $_POST['lpp'];
if (empty($lpp)) $lpp = $_GET['lpp'];
$pagina = $_GET['pagina'];
if (empty($pagina)) $pagina = $_GET['pagina'];

if ($status==2) $tipo = "";
else if ($status==0) $tipo = "and a.sitcli<2";
else $tipo = "and a.sitcli=2";

$sql2 = mysql_query("select MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, a.razaosoc, a.nomefantasia, a.email from cs2.cadastro a
					inner join logon b on a.codloja=b.codloja
					where a.email != '' and a.id_franquia='$id_franquia' $tipo group by a.codloja order by $criterio", $con);
$total = mysql_num_rows($sql2);

$paginas = ceil($total / $lpp);
if (!isset($pagina)) { $pagina = 0; }
$inicio = $pagina * $lpp;
$sql2 = mysql_query("select MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, a.razaosoc, a.nomefantasia, a.email from cs2.cadastro a
					inner join logon b on a.codloja=b.codloja
					where a.email != '' and a.id_franquia='$id_franquia' $tipo group by a.codloja order by $criterio limit $inicio, $lpp", $con);
if ($total == 0) {
	echo "<p align=\"center\" class=\"titulo\">N&atilde;o h&aacute; nenhum email para esta franquia</p>";
} else {
	echo "<table width=\"650\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"bodyText\">
			<tr>
				<td colspan=\"4\" class=\"titulo\">LISTA DE EMAILS</td>
			</tr>
			<tr bgcolor=\"FF9966\">
				<td align=\"center\">Nome de Fantasia</td>
				<td align=\"center\">Email</td>
			</tr>";
	while ($valor = mysql_fetch_array($sql2)) {
		$razao = $valor['razaosoc'];
		$nome = $valor[nomefantasia];
		$string = $razao;
		$limite = 25;
		$sizeName = strlen($string);
		//
		$string0 = $nome;
		$limite0 = 25;
		$sizeName0 = strlen($string0);
		echo "<tr bgcolor=\"#E5E5E5\">
		<td>&nbsp;";
			  for($num0=0;$num0<$limite0;$num0++) {
    				print($string0[$num0]);
					}
					if($sizeName0>$limite0){echo"...";}
		echo "</td>
		<td>&nbsp;<a href=\"mailto:$valor[email]\">$valor[email]</a></td>
		</tr>";
	}
	echo "<tr>
			<td colspan=\"4\" bgcolor=\"FF9966\" align=\"center\">Temos um total de <b>$total</b> emails registrados!</td>
		</tr>
		<tr>
			<td colspan=\"4\" class=\"titulo\">P&aacute;gina $pagina</td>
		</tr>
	</table>";
mysql_free_result($sql2);
?>
<center>
<?php
}
if($pagina > 0) {
   $menos = $pagina - 1;
   $url = "$PHP_SELF?pagina1=Franquias/b_emailcli1.php&pagina=$menos&lpp=$lpp&criterio=$criterio&status=$status";
   echo "<a href=\"$url\" class=\"bodyText\"> << Anterior</a>"; // Vai para a p�gina anterior
}
for($i=0;$i<$paginas;$i++) { // Gera um loop com o link para as p�ginas
   $url = "$PHP_SELF?pagina1=Franquias/b_emailcli1.php&pagina=$i&lpp=$lpp&criterio=$criterio&status=$status";
   echo " | <a href=\"$url\" class=\"bodyText\">$i</a>";
}
if($pagina < ($paginas - 1)) {
   $mais = $pagina + 1;
   $url = "$PHP_SELF?pagina1=Franquias/b_emailcli1.php&pagina=$mais&lpp=$lpp&criterio=$criterio&status=$status";
   echo " | <a href=\"$url\" class=\"bodyText\">Pr&oacute;xima >></a>";
}
?>
</center>
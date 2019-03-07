<?php
require "connect/sessao_r.php";
///$nome2 = $_SESSION['ss_restrito'];
echo $tipo ."e".
if (!isset($nome2) && ($tipo != "a")) exit;

if ($tipo == "a") $frq = "";
else $frq = "where a.franquia = '$id_franquia'";
$comando = "select a.id, a.atendente, b.fantasia from cs2.atendentes a
			inner join franquia b on a.franquia = b.id
			$frq
			order by b.fantasia, a.atendente";
$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0") {
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"20\">
			<td align=\"center\" width=\"100%\">Nenhuma atendente cadastrado!</td>
			</tr>
		  </table>";
} else {
	echo "<table width=\"500\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\" align=center>
	 		<tr>
				<td colspan=\"3\" height=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" class=\"titulo\">
				<td align=\"center\">Codigo</td>
				<td align=\"center\">Nome Atendente</td>
				<td align=\"center\">Franquia</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$id = $matriz['id'];
		$atendente = $matriz['atendente'];
	  	$fantasia = $matriz['fantasia'];

	  	echo "<tr height=\"22\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		echo "<td align=\"center\">$id</td>
	  	      	<td>$atendente</td>
				<td align=\"center\">$fantasia</td>
			</tr>";
		}
		$a = $a - 1;
		echo "<tr class=\"subtitulodireita\">
				<td colspan=\"2\">Número de total de atendentes habilitados: <strong>$a</strong></td>
				<td></td>
			</tr>
			<tr>
				<td colspan=\"3\" height=\"1\" bgcolor=\"#666666\"></td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<center>
<form id="form1" name="form1" method="post" action="painel.php?pagina1=Franquias/b_cadatendente.php">
  <input type="submit" name="cadastrar" id="cadastrar" class="botao3d" value="Cadastrar Atendente" />
</form>
</center>
<center>
	<input name="button" type="button" class="botao3d" onClick="javascript: history.back();" value="Voltar" />
</center>
<?
require "connect/sessao.php";

$cep=str_replace("-","",$cep);
$cep=str_replace(".","",$cep);

$comando = "SELECT * FROM consulta.cep_brasil WHERE cep=$cep ORDER BY denominacao ASC";
$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0")
	{
	echo "<table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"20\" class=\"titulo\">
			<td align=\"center\" width=\"100%\" >Nenhuma correspondencia para esse CEP!</td></tr></table>";
	}
	else
	{
	echo "<table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"quente\">
	 		<tr>
				<td colspan=\"7\" height=\"16\" bgcolor=\"#999999\"></td>
			</tr>
	 		<tr>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\">
				<td align=\"center\" >CEP</td>
				<td align=\"center\" >Tipo</td>
				<td align=\"center\" >Denominação</td>
				<td align=\"center\" >Complemento</td>
				<td align=\"center\" >Bairro</td>
				<td align=\"center\" >Cidade</td>
				<td align=\"center\" >UF</td>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr>
				<td colspan=\"7\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$cep = $matriz['cep'];
	  	$tipo = $matriz['tipo'];
	  	$denominacao = $matriz['denominacao'];
		$complemento = $matriz['complemento'];
		$bairro = $matriz['bairro'];
		$cidade = $matriz['cidade'];
		$uf = $matriz['uf'];
		$string = $denominacao;
		$limite = 35;
		$sizeName = strlen($string);
		//
		$string0 = $bairro;
		$limite0 = 25;
		$sizeName0 = strlen($string0);
	  	// concatenamos as variáveis do endereço numa única variável
		$endereco = $tipo.' '.$denominacao.' '.$complemento;
		echo "<tr height=\"22\">
	  	   	  	<a href=\"painel.php?pagina1=clientes/a_incclient.php&cep=$cep&endereco=$endereco&bairro=$bairro$cidade=$cidade&uf=$uf\" onMouseOver=\"window.status='Incluir CEP'; return true\">
				<td align=\"center\">$cep</td>
				<td align=\"left\">$tipo</td>
	  	      	<td align=\"left\"><font color=\"#0000ff\">&nbsp;";
			  for($num=0;$num<$limite;$num++) {
    				print($string[$num]);
					}
					if($sizeName>$limite){echo"...";}
		echo "</font></td>
				<td align=\"left\">$complemento</td>
	  	      	<td align=\"left\">";
			  for($num0=0;$num0<$limite0;$num0++) {
    				print($string0[$num0]);
					}
					if($sizeName0>$limite0){echo"...";}
		echo "</td>
			  	<td align=\"left\">$cidade</td>
				<td align=\"left\">$uf</td>
	  	      	</a>
	  	      	</tr>";
		}
		echo "<tr>
				<td colspan=\"6\" align=\"right\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<div align="center" class="bodyText">Para incluir clique no registro desejado</div>
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
<?
require "connect/sessao.php";

$codloja  = $_GET['codloja'];

// Pesquisa Titulos do cliente

$command = "SELECT a.codloja, c.razaosoc, a.numdoc, a.vencimento, a.valor, a.datapg, a.valorpg, a.descricao_repasse
						   FROM cs2.titulos_recebafacil a
						   INNER JOIN cs2.logon b on a.codloja=b.codloja
						   INNER JOIN cs2.cadastro c on a.codloja=c.codloja
						   WHERE a.numdoc like '%$codloja'
						   ORDER by a.vencimento";
$res = mysql_query($command,$con);
$linhas = mysql_num_rows($res);
$registro = mysql_fetch_array($res);
$linhas1 = $linhas + 3;

//começa a tabela

    $codloja = $registro['codloja'];
	$razaosoc = $registro['razaosoc'];
	
	echo "<table align=\"center\" width=\"800\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"bodyText\">
	 		<tr>
				<td colspan=\"8\" class=\"titulo\">T&iacute;tulos [ Credi&aacute;rio / Recupere ]</td>
			</tr>
			<tr>
				<td colspan=\"3\" class=\"subtitulodireita\">Codigo</td>
				<td colspan=\"5\" class=\"subtitulopequeno\">$codloja</td>
			</tr>
			<tr>
				<td colspan=\"3\" class=\"subtitulodireita\">Razao Social</td>
				<td colspan=\"5\" class=\"subtitulopequeno\">$razaosoc</td>
			</tr>
	 		<tr>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" bgcolor=\"FF9966\">
				<td align=\"center\" >No. Boleto</td>
				<td align=\"center\" >Vencimento</td>
				<td align=\"center\" >Valor</td>
				<td align=\"center\" >Data Pagamento</td>
				<td align=\"center\" >Valor Pago</td>
				<td align=\"center\">Origem</td>
				<td align=\"center\"> ? </td>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr>
				<td colspan=\"7\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>";
			
	for ($a=1; $a<=$linhas; $a++){
		$boleto = $registro['numdoc'];
		$venc = $registro['vencimento'];
		$valor = $registro['valor'];
		$dtpagamento = $registro['datapg'];
		$valorpg = $registro['valorpg'];
		$descricao = $registro['descricao_repasse'];
		if ( $dtpagamento != "" && $descricao == '') {
			echo "<tr height=\"22\" bgcolor=\"#E5E5E5\">
				<td align=\"center\">$boleto</td>				
	            <td align=\"center\">$venc</td>
	  	      	<td align=\"center\">$valor</td>
				<td align=\"center\">$dtpagamento</td>
				<td align=\"center\">$valorpg</td>
				<td align=\"center\">$descricao</td>
				<td align=\"center\"> &nbsp;&nbsp; </td>
			</tr>";
		}elseif ( $dtpagamento != "" && $descricao == 'CLIENTE RECEBEU O TITULO NO ESTABELECIMENTO') {
			echo "<tr height=\"22\" bgcolor=\"#E5E5E5\">
				<td align=\"center\">$boleto</td>				
	            <td align=\"center\">$venc</td>
	  	      	<td align=\"center\">$valor</td>
				<td align=\"center\">$dtpagamento</td>
				<td align=\"center\">$valorpg</td>
				<td align=\"center\">$descricao</td>";
				echo "<td align=\"center\">
					<a href=\"painel.php?pagina1=area_restrita/b_cancelabaixa_fatura_crediario_recupere.php&numdoc=$boleto\" onMouseOver=\"window.status='Quitar Titulo'; return true\">
						<IMG SRC=\"../img/exclaim.gif\" width=\"16\" height=\"16\" border=\"0\">
					</a>
				</td>";
			echo "</tr>";
		}else{
			echo "<tr height=\"22\" bgcolor=\"#E5E5E5\">
				<td align=\"center\">$boleto</td>				
	            <td align=\"center\">$venc</td>
	  	      	<td align=\"center\">$valor</td>
				<td align=\"center\">$dtpagamento</td>
				<td align=\"center\">$valorpg</td>
				<td align=\"center\">$descricao</td>";
			echo "<td align=\"center\">
					<a href=\"painel.php?pagina1=area_restrita/b_baixa_fatura_crediario_recupere.php&numdoc=$boleto\" onMouseOver=\"window.status='Quitar Titulo'; return true\">
						<IMG SRC=\"../img/cancela.gif\" width=\"16\" height=\"16\" border=\"0\">
					</a>
				</td>";
			echo "</tr>";
		}
	}
	echo "</table>";
	
$res = mysql_close ($con);
?>
<br />
<table border="0" align="center">
<tr>
<td width="20%">&nbsp;</td>				
<td width="40%" align="right"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
</table>
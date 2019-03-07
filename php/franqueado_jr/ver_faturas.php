<?
require "connect/sessao.php";

$codloja  = $_GET['codloja'];
$razaosoc = $_GET['razaosoc'];
$logon 	  = $_GET['logon'];

?>
<script language="javascript">
function geraNotificacao(p_codloja, p_soma) {
	    popup  = window.open('clientes/popup_notificacao_data.php?codloja='+p_codloja+'&soma='+p_soma, 'janela', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+700+',height='+700+',left='+5+', top='+5+',screenX='+100+',screenY='+100+'');
  }
  
  /*   
 function faturaDoCliente() {
    form = document.frm;
    form.action = 'painel.php?pagina1=clientes/a_faturas.php';
	form.submit();
 }*/  
</script>

<?
//pega da tabela títulos todas as ocorrências para esse codloja
$command = "SELECT mid(numboleto,10,17) as boleto, date_format(vencimento,'%d/%m/%Y') as venc, valor, date_format(datapg,'%d/%m/%Y') as dtpagamento, valorpg, origem_pgto 
from titulos where codloja=$codloja and numboleto is not null order by vencimento";
$res = mysql_query($command,$con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas + 3;
//começa a tabela
	echo "<table align=\"center\" width=\"600\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"bodyText\">
	 		<tr>
				<td colspan=\"7\" class=\"titulo\">Faturas</td>
			</tr>
			<tr>
				<td colspan=\"3\" class=\"subtitulodireita\">Codigo</td>
				<td colspan=\"4\" class=\"subtitulopequeno\">$logon</td>
			</tr>
			<tr>
				<td colspan=\"3\" class=\"subtitulodireita\">Razao Social</td>
				<td colspan=\"4\" class=\"subtitulopequeno\">$razaosoc</td>
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
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr>
				<td colspan=\"6\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
		$boleto = $matriz['boleto'];
		$venc = $matriz['venc'];
		$valor = $matriz['valor'];
		$valor = number_format($valor, 2, ',', '.');		
		$dtpagamento = $matriz['dtpagamento'];
		$valorpg = $matriz['valorpg'];
		$origem = $matriz['origem_pgto'];
		if (!$valorpg) $soma = $soma + $valor;
		echo "<tr height=\"22\" bgcolor=\"#E5E5E5\">
				<td align=\"center\">$boleto</td>
	  	   	  	<td align=\"center\">$venc</td>
	  	      	<td align=\"center\">$valor</td>
				<td align=\"center\">$dtpagamento</td>
				<td align=\"center\">$valorpg</td>
				<td align=\"center\">$origem</td>
			</tr>";
		}
		 if ( empty($soma) ) $soma = '0,00';
		 $soma = number_format($soma, 2, ',', '.');
		 
	  	 echo "
			<tr height=\"20\" class=\"subtitulodireita\">
			    <td colspan=\"7\">Soma das Faturas não pagas: R$ $soma</td>
				<td></td>
			</tr>
			<tr>
				<td colspan=\"7\" height=\"1\" bgcolor=\"#666666\"></td>
			</tr>
		</table>";
	
$res = mysql_close ($con);
?>
<br />
<table border="0" align="center">
<tr>

<!--
<td width="40%" align="left"><input type="button" onClick="geraNotificacao(<?=$codloja?>,<?=$soma?>);" value="Gerar Notificação" /></td>

<td width="20%">&nbsp;</td>	 -->			
<td width="100%" align="right"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
</table>
<?php
require "connect/sessao.php";
///include ('../../../inform/boleto/data.php');

$codloja  = $_GET['codloja'];
$razaosoc = $_GET['razaosoc'];
$logon 	  = $_GET['logon'];

function diferenca_entre_datas($data,$dt_base,$formato) {

	if ( $formato == 'DD/MM/AAAA' ){
		$d_data = substr($data,0,2);
		$m_data = substr($data,3,2);
		$a_data = substr($data,6,4);
		$d_base = substr($dt_base,0,2);
		$m_base = substr($dt_base,3,2);
		$a_base = substr($dt_base,6,4);
	}else{
		return "FORMATO INVALIDO";
		exit;
	}
	$dias_data = floor(gmmktime (0,0,0,$m_data,$d_data,$a_data)/ 86400);
	$dias_base = floor(gmmktime (0,0,0,$m_base,$d_base,$a_base)/ 86400);
	$val = $dias_data - $dias_base;
	return $val;
}
?>

<script language="javascript">
function geraNotificacao(p_codloja, p_soma) {
	    popup  = window.open('clientes/popup_notificacao_data.php?codloja='+p_codloja+'&soma='+p_soma, 'janela', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+700+',height='+700+',left='+5+', top='+5+',screenX='+100+',screenY='+100+'');
  }  

function alterar(){
	if(confirm("Tem certeza que deseja Alterar ?")) {
	} else {
		return false
	}
}

</script>

<?php
//pega da tabela t�tulos todas as ocorr�ncias para esse codloja
$command = "SELECT 
					numdoc AS boleto, date_format(vencimento,'%d/%m/%Y') AS venc, valor, 
					date_format(datapg,'%d/%m/%Y') AS dtpagamento, valorpg, origem_pgto, vencimento, isento_juros
			FROM    titulos 
			WHERE codloja=$codloja and valorpg is NULL
			AND numboleto IS NOT NULL ORDER BY vencimento";
$res = mysql_query($command,$con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas + 3;
//come�a a tabela
	echo "<table align=\"center\" width=\"600\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"bodyText\">
	 		<tr>
				<td colspan=\"8\" class=\"titulo\">Faturas</td>
			</tr>
			<tr>
				<td colspan=\"3\" class=\"subtitulodireita\">Codigo</td>
				<td colspan=\"5\" class=\"subtitulopequeno\">$logon</td>
			</tr>
			<tr>
				<td colspan=\"3\" class=\"subtitulodireita\">Razao Social</td>
				<td colspan=\"5\" class=\"subtitulopequeno\">$razaosoc</td>
			</tr>
	 		<tr>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" bgcolor=\"649ffc\">
				<td align=\"center\"  width=\"15%\">No. Boleto</td>
				<td align=\"center\"  width=\"15%\">Vencimento</td>
				<td align=\"center\"  width=\"14%\">Valor</td>
				<td align=\"center\"  width=\"14%\"><font color='red'><b>Valor + Juros</b></font></td>
				<td align=\"center\"  width=\"15%\">Data Pagamento</td>
				<td align=\"center\"  width=\"15%\">Valor Pago</td>
				<td align=\"center\"  width=\"12%\">Origem</td>

			</tr>
			<tr>
				<td colspan=\"7\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
		$boleto = $matriz['boleto'];
		$venc = $matriz['venc'];
		$valor = $matriz['valor'];
		
		/* condi��o para mostra o pagamento com juros*/
		$date = date("d/m/Y",time());
		$vencimento = $matriz['vencimento'];
		$vencimentof = substr($vencimento,8,2)."/".substr($vencimento,5,2)."/".substr($vencimento,0,4);
		$dif = diferenca_entre_datas($date,$vencimentof,'DD/MM/AAAA');
		
		if ( $matriz['isento_juros'] == 'N' ){
			if ( $dif > 0 ){
				//$valor = $nvalor;
				$nvalor = str_replace(',','.',$valor);			
				$multa = ($nvalor * 2) / 100;
				$encargos = ($nvalor * 0.0015 ) * $dif;
				$xencargos = number_format ($encargos, 2, ',', '.');
				$encargosdia = ($nvalor * 0.0015 );
				$encargosdia = number_format ($encargosdia, 2);
				$encargos = number_format ($encargos, 2);
				$_valor =  $nvalor + $multa + $encargos;
				$multa = number_format ($multa, 2, ',', '.');
				$valor_cobrado = number_format ($_valor, 2, ',', '.');
			}else{
				$valor_cobrado = $matriz['valor'];
			}
		}else{
			$valor_cobrado = 'ISENTO JUROS';
		}
		
		/********************************************/
		
		$dtpagamento = $matriz['dtpagamento'];
		$valorpg = $matriz['valorpg'];
		$origem = $matriz['origem_pgto'];
		if (!$valorpg) $soma = $soma + $valor;
		echo "<tr height=\"22\" bgcolor=\"#E5E5E5\">
				<td align=\"center\"><u><a href='clientes/a_isentajuros3.php?numdoc=$boleto&codloja=$codloja&logon=$logon&razaosoc=$razaosoc' onclick='return alterar()'><font color='blue'>$boleto</font></a></u></td>				
	            <td align=\"center\">$venc</td>
	  	      	<td align=\"center\">$valor</td>
				<td align=\"center\">";
				if($dtpagamento == ""){
					echo "<font color='red'>".$valor_cobrado."</font>";
				}			
				echo "</td>
				<td align=\"center\">$dtpagamento</td>
				<td align=\"center\">$valorpg</td>
				<td align=\"center\">$origem</td>
			</tr>";
		}
	  	 echo "
			<tr height=\"20\" class=\"subtitulodireita\">
			    <td colspan=\"8\">Soma das Faturas não pagas: R$ $soma</td>
				<td></td>
			</tr>
			<tr>
				<td colspan=\"8\" height=\"1\" bgcolor=\"#666666\"></td>
			</tr>
		</table>";
	
$res = mysql_close ($con);
?>
<br />
<table border="0" align="center">
<tr>
<td align="center">
<input type='button' value='Voltar' style='cursor:pointer' onClick="document.location='painel.php?pagina1=clientes/a_isentajuros.php'"/>
</td>
</table>
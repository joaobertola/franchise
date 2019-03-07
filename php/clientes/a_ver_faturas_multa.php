<?php

require "connect/sessao.php";
require "connect/conexao_conecta.php";

// pega o saldo do credi�rio/credupere

$saldo_crediario = '0,00';
$sql_saldo = "SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='$codloja' order by id";
$qr2 = mysql_query($sql_saldo,$con) or die ("\nErro ao gerar o extrato\n".mysql_error()."\n\n");
while ($matriz = mysql_fetch_array($qr2)) {
	$saldo_crediario 	= number_format($matriz['saldo'],2,",",".");
	$sdo_crediario      = $matriz['saldo'];
}

$sql_cliente = "select mid(a.logon,1,5) as logon, b.razaosoc, b.nomefantasia from logon a
							inner join cadastro b on a.codloja=b.codloja
							where b.codloja = '$codloja'";
							
$resulta = mysql_query($sql_cliente,$con);
$linha = mysql_num_rows($resulta);
if ($linha > 0){
	$matriz = mysql_fetch_array($resulta); 
	$logon = $matriz['logon'];
	$razaosoc = $matriz['razaosoc'];
	$razaosoc = $matriz['nomefantasia'];
}

//pega da tabela t�tulos todas as ocorr�ncias para esse codloja
$command = "SELECT 
					numdoc AS boleto, date_format(vencimento,'%d/%m/%Y') AS venc, valor, 
					date_format(datapg,'%d/%m/%Y') AS dtpagamento, valorpg, origem_pgto, 
					vencimento,isento_juros
			FROM    titulos 
			WHERE codloja = '$codloja' AND referencia = 'MULTA'
			AND numboleto IS NOT NULL ORDER BY vencimento";
$res = mysql_query($command,$con);
$linhas = mysql_num_rows ($res);

if ( $linhas > 0 ){
	
$linhas1 = $linhas + 3;
//come�a a tabela
	echo "<table align='center' width='750' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
	 		<tr>
				<td colspan='11' class='titulo'>Faturas REFERENTE A MULTA CONTRATUAL</td>
			</tr>
	 		<tr>
				<td rowspan='$linhas1' width='1' bgcolor='#999999'></td>
			</tr>
			<tr height='20' bgcolor='FF9966'>
				<td align='center'  width='10%'>No. Boleto</td>
				<td align='center'  width='10%'>Vencimento</td>
				<td align='center'  width='10%'>Valor</td>
				<td align='center'  width='10%'><font color='red'><b>Valor + Juros</b></font></td>
				<td align='center'  width='10%'>Data Pagamento</td>
				<td align='center'  width='10%'>Valor Pago</td>
				<td align='center'  width='10%'>&nbsp;</td>
				<td align='center'  width='10%'>Origem</td>
				<td align='center'  width='10%'> ... </td>
				<td align='center'  width='10%'> ... </td>
			</tr>
			<tr>
				<td colspan='10' height='1' bgcolor='#666666'>
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
		
		$txt_valorcobrado = '';
		
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
				$valor_cobrado = number_format ($valor, 2, ',', '.');
			}
		}else{
			$txt_valorcobrado = '(Isento Juros)';
			$valor_cobrado = number_format ($valor, 2, ',', '.');
		}
		
		/********************************************/
		
		$dtpagamento = $matriz['dtpagamento'];
		$valorpg = $matriz['valorpg'];
		$origem = $matriz['origem_pgto'];
		if (!$valorpg) $soma = $soma + $valor;
		echo "<tr height='22' bgcolor='#E5E5E5'>
				<td align='center'><u><a href='https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?numdoc=$boleto'><font color='blue'>$boleto</font></a></u></td>
	            <td align='center'>$venc</td>
	  	      	<td align='center'>$valor</td>
				<td align='center'>";
				if($dtpagamento == ""){
					// NAO PAGO
					echo "<font color='red'>$valor_cobrado $txt_valorcobrado</font>";
					$img_pg = '&nbsp;';
					if ( $saldo_crediario > 0 ){
						if ( $dif >= 10 ){
							if ( $sdo_crediario > $_valor ){
								$img_cancel_pgto = "<a href=\"painel.php?pagina1=Franquias/b_baixartitulo_crediario.php&numdoc=$boleto&codloja=$codloja&valorcomjuros=$valor_cobrado&logon=$logon\" onMouseOver=\"window.status='Recebimento de T�tulo usando o SALDO CREDOR DO CRED�RIO/RECUPERE/BOLETO'; return true\" title='Quitar Titulo usando SALDO CRED�RIO/RECUPERE/BOLETO'><IMG SRC=\"../img/compensacao.gif\" width=\"70\" height=\"16\" border=\"0\"></a>";
							}else $img_cancel_pgto = '&nbsp;';
						}else $img_cancel_pgto = '&nbsp;';
					}else{
						$img_cancel_pgto = '&nbsp;';
					}
					if ( $_SESSION['id'] == 163 or $_SESSION['id'] == 4 or $_SESSION['id'] == 1204){
						$img_desconto = "<a href=\"painel.php?pagina1=clientes/a_fatura_desconto.php&numdoc=$boleto&codloja=$codloja\" onMouseOver=\"window.status='Desconto na Fatura'; return true\" title='Gravar Desconto nesta fatura' onclick='return alerta_desconto()'><IMG SRC=\"../img/menos.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
					}
				}else{
					$img_pg = "<img src='https://www.webcontrolempresas.com.br/franquias/img/img_v.gif'> Pago";
					if ( $_SESSION['id'] == 163 && ( $origem == 'FRANQUIA' or $origem == 'BANCO') ){
						$img_cancel_pgto = "<a href=\"painel.php?pagina1=Franquias/b_cancelabaixa.php&numdoc=$boleto&codloja=$codloja\" onMouseOver=\"window.status='Cancela Recebimento de T�tulo'; return true\" title='Clique para cancelar o recebimento do Titulo' onclick='return alerta()'><IMG SRC=\"../img/exclaim.gif\" width=\"16\" height=\"16\" border=\"0\"></a>";
					}else{
						$img_cancel_pgto = '&nbsp;';
					}
				}
				
				echo "</td>
				<td align='center'>$dtpagamento</td>
				<td align='center'>$valorpg</td>
				<td align='center'>$img_pg</td>
				<td align='center'>$origem</td>
				<td align='center'>$img_cancel_pgto</td>
				<td align='center'>$img_desconto</td>
			</tr>";
		}
		$soma = number_format($soma,2);
	  	 echo "
			<tr height='20' class='subtitulodireita'>
			    <td colspan='11'>Soma das Faturas n�o pagas: R$ $soma</td>
				<td></td>
			</tr>
			<tr>
				<td colspan='11' height='1' bgcolor='#666666'></td>
			</tr>
		</table>
		";
}
$res = mysql_close ($con);
?>
<br />
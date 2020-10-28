<?php

require "connect/sessao.php";
require "connect/conexao_conecta.php";

// pega o saldo do credi�rio/credupere

$sql_cliente = "select CAST(MID(a.logon,1,6) AS UNSIGNED) as logon, b.razaosoc, b.nomefantasia from logon a
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
					a.numboleto AS boleto, date_format(a.vencimento,'%d/%m/%Y') AS venc, a.valor, 
					date_format(a.datapg,'%d/%m/%Y') AS dtpagamento, a.valorpg,  
					a.vencimento, a.contrato, date_format(b.data_vencimento,'%d/%m/%Y') as venc_orig, 
					b.valor_parcela as vr_orig
			FROM    cs2.titulos_antecipacao a
			inner join cs2.cadastro_emprestimo b ON a.contrato = b.protocolo AND a.id_antecipacao = b.id
			WHERE a.codloja = '$codloja' and a.valorpg IS NULL and  b.valor_pagamento IS NULL
			ORDER BY vencimento";
$res = mysql_query($command,$con) or die ("Erro: SQL : $command)");
$linhas = mysql_num_rows ($res);

if ( $linhas > 0 ){
	
$linhas1 = $linhas + 3;
//come�a a tabela
	echo "<table align='center' width='800' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
	 		<tr>
				<td colspan='11' class='titulo'>Faturas REFERENTE A ANTECIPA&Ccedil;&Atilde;O DE CR&Eacute;DITO</td>
			</tr>
			<tr height='20' bgcolor='FF9966'>
				<td align='center'  width='10%'>No. Boleto</td>
				<td align='center'  width='10%'>Contrato</td>
				<td align='center'  width='10%'>Venc. Orig.</td>
				<td align='center'  width='10%'>Vr. Orig.</td>
				<td align='center'  width='10%'>Venc. Atualiz.</td>
				<td align='center'  width='10%'>Vr Atualiz.</td>
				<td align='center'  width='10%'><font color='red'><b>Valor + Juros</b></font></td>
				<td align='center'  width='10%'>Data Pagamento</td>
				<td align='center'  width='10%'>Valor Pago</td>
				<td align='center'  width='10%'>&nbsp;</td>
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
		$contrato = $matriz['contrato'];
		$venc_orig = $matriz['venc_orig'];
		$vr_orig = $matriz['vr_orig'];
		$vr_orig = number_format($vr_orig,2,",",".");
	
		
		/* condi��o para mostra o pagamento com juros*/
		$date = date("d/m/Y",time());
		$vencimento = $matriz['vencimento'];
		$vencimentof = substr($vencimento,8,2)."/".substr($vencimento,5,2)."/".substr($vencimento,0,4);
		$dif = diferenca_entre_datas($date,$vencimentof,'DD/MM/AAAA');
		
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
		
		/********************************************/
		
		$dtpagamento = $matriz['dtpagamento'];
		$valor_pagamento = $matriz['valor_pagamento'];
		$valorpg = $matriz['valorpg'];
		$origem = $matriz['origem_pgto'];
		if (!$valorpg) $soma_antecipacao = $soma_antecipacao + $valor;
		echo "<tr height='22' bgcolor='#E5E5E5'>
				<td align='center'><u><a href='../../inform/boleto_antecipa/boleto.php?numdoc=$boleto'><font color='blue'>$boleto</font></a></u></td>
				<td align='center'>$contrato</td>
				<td align='center'>$venc_orig</td>
				<td align='center'>$vr_orig</td>
	            <td align='center'>$venc</td>
	  	      	<td align='center'>$valor</td>
				<td align='center'>";
				if($dtpagamento == ""){
					// NAO PAGO
					echo "<font color='red'>$valor_cobrado</font>";
					$img_pg = '&nbsp;';
				}else{
					$img_pg = "<img src='../../franquias/img/img_v.gif'> Pago";
				}
				echo "</td>
				<td align='center'>$dtpagamento</td>
				<td align='center'>$valorpg</td>
				<td align='center'>$img_pg</td>
			</tr>";
		}
		$vsoma_antecipacao = number_format($soma_antecipacao,2);
	  	 echo "
			<tr height='20' class='subtitulodireita'>
			    <td colspan='11'>Soma das Faturas (Antecipa&ccedil;&atilde;o) n�o pagas: R$ $vsoma_antecipacao</td>
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
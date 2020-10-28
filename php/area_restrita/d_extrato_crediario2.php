<?php
	$codigo = $_REQUEST['logon'];

	if ($tipo == 'b') $frq = " and b.id_franquia = '$id_franquia'";
	else $frq = "";

	$sql = "select a.codloja, CAST(MID(a.logon,1,6) AS UNSIGNED) as logon, b.nomefantasia, b.cidade, b.uf, b.banco_cliente
			from cs2.logon a
			inner join cs2.cadastro b on a.codloja=b.codloja 
			where CAST(MID(a.logon,1,6) AS UNSIGNED)='$codigo' $frq limit 1";
	$ql = mysql_query($sql,$con);
	$linha = @mysql_num_rows($ql);
	if (empty($linha)) {
		print "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\"); history.back();</script>";
		exit;
	} else {
		$matriz = mysql_fetch_array($ql);
		$codigo = $matriz['codloja'];
		$logon = $matriz['logon'];
		$nomefantasia = $matriz['nomefantasia'];
		$cidade = $matriz['cidade'];
		$uf = $matriz['uf'];
		$banco_cliente = $matriz['banco_cliente'];
	}
	
	echo "<table width=\"500\" border=\"0\" align=center>
			<tr>
				<td width=\"50%\" class=\"subtitulodireita\">ID:</td>
				<td width=\"50%\" class=\"subtitulopequeno\">$codigo</td>
			</tr>
			<tr>
				<td class=\"subtitulodireita\">Código de Cliente</td>
				<td class=\"subtitulopequeno\">$logon</td>
			</tr>
			<tr>
				<td class=\"subtitulodireita\">Nome de Fantasia</td>
				<td class=\"subtitulopequeno\">$nomefantasia</td>
			</tr>
			<tr>
				<td class=\"subtitulodireita\">Cidade</td>
				<td class=\"subtitulopequeno\">$cidade ($uf)</td>
			</tr>
			<tr>
				<td class=\"subtitulodireita\">Banco</td>
				<td class=\"subtitulopequeno\">$banco_cliente</td>
			</tr>
		</table><br>";
	
	$sql2 = "select date_format(data,'%d/%m/%Y') as data, discriminacao, date_format(venc_titulo,'%d/%m/%Y') as venc,
			valor_titulo, valor, tarifa_bancaria, operacao, saldo, id, date_format(datapgto,'%d/%m/%Y') as datapgto, numboleto, tx_adm
			from cs2.contacorrente_recebafacil
			where codloja='$codigo' order by id";
	$qr2 = mysql_query($sql2,$con) or die ("\nErro ao gerar o extrato\n".mysql_error()."\n\n");
	$linhas = mysql_num_rows($qr2);
	if ($linhas==0) {
		echo "<table width=\"680\" border=\"0\" align=center >
				<tr height=\"20\" class=\"titulo\">
					<td align=\"center\" width=\"100%\" >Nenhum boleto processado durante este periodo</td>
				</tr>
			</table>";
	} else {
		echo "<table cellspacing=\"0\" border=\"0\" width=\"100%\" class=\"bodyText\">
				<tr class=\"subtitulodireita\"> 
				  <th align=\"center\">Data Lançamento</th>
				  <th align=\"center\">Descri&ccedil;&atilde;o - CPF - Nome Consumidor</th>
				  <th align=\"center\">Nro. Documento</th>
				  <th align=\"center\">Venc. T&iacute;tulo</th>
				  <th align=\"center\">Valor Titulo</th>
				  <th align=\"center\">Data Pgto</th>
				  <th align=\"center\">Valor Pago</th>
				  <th align=\"center\">Oper</th>
				  <th align=\"center\">Tipo Servi&ccedil;o</th>
				  <th align=\"center\">Tarifa Bancaria</th>
				  <th align=\"center\">Tx Adm (2,5%)</th>
				  <th align=\"center\">Saldo</th>
				  <th align=\"center\">Protocolo</th>
				</tr>";
		while ($matriz = mysql_fetch_array($qr2)) {
			$a = $a + 1;
			$data_rf 		= $matriz['data'];
			$discriminacao 	= $matriz['discriminacao'];
			$venc_rf 		= $matriz['venc'];
			if (empty($venc_rf)) $venc_rf = "-";
			$valor_titulo 	= number_format($matriz['valor_titulo'],2,",",".");
			$datapgto 		= $matriz['datapgto'];
			$valor_rf 		= number_format($matriz['valor'],2,",",".");
			$numboleto 		= $matriz['numboleto'];
			if ( $numboleto <> '' ){
				$qr_titulo 		= mysql_query("select tp_titulo from cs2.titulos_recebafacil 
											where numboleto = '$numboleto' or numboleto_itau = '$numboleto' or numboleto_bradesco = '$numboleto' or numboleto_hsbc = '$numboleto'",$con);
				$tp_titulo		= @mysql_result($qr_titulo,0,"tp_titulo");

				if ($tp_titulo == 1) $tipo_servico = "Recupere System";
				else if ($tp_titulo == 2) $tipo_servico = "Crediario System";
				else if ($tp_titulo == 3) $tipo_servico = "Boleto System";
				else $tipo_servico = '-';
			}else $tipo_servico = '-';
			
			$tarifa_bancaria= $matriz['tarifa_bancaria'];
			$tx_adm 		= number_format($matriz['tx_adm'],2,",",".");
			
			$saldo 			= number_format($matriz['saldo'],2,",",".");
			$protocolo 		= $matriz['id'];
			echo "<tr height=\"22\"";
			if (($a%2) == 0) {
				echo "bgcolor=\"#E5E5E5\">";
			} else {
				echo ">";
			}
			echo "<td class=\"tabela\">$data_rf</td>
				  <td class=\"tabela\">";
			if ($matriz['operacao'] == 0) {
				$operacao = '<font color=green>C</font>';
				if ( substr($discriminacao,0,7) == 'ESTORNO' ){
					$discriminacao = "<font color=red>$discriminacao</font>";
					$datapgto = '-';
				}
				echo "$discriminacao";
				
				
			} else {
				$operacao  = '<font color=red>D</font>';
				if ( substr($discriminacao,0,8) == 'DEPOSITO' ){
					$msg_complementar = ' Dt Deposito: '.$datapgto.')';
					$datapgto = '-';
				}else $msg_complementar = '';
				echo "<a href=\"#\" onClick=\"window.open('area_restrita/d_detalhe_repasse.php?protocolo=$protocolo','Repasse','toolbar=yes,location=no, directories=yes, status=yes, menubar=yes, scrollbars=yes, resizable=yes, width=640, height=480'); return false;\"><font color=#0066FF>$discriminacao $msg_complementar</font></a>";
			}
			echo "</td>
				  <td class=\"tabela\">$numboleto</td>
				  <td class=\"tabela\" align=\"center\">$venc_rf</td>
				  <td class=\"tabela\" align=\"right\">$valor_titulo</td>
				  <td class=\"tabela\" align=\"center\">$datapgto</td>
				  <td class=\"tabela\" align=\"right\">$valor_rf</td>
				  <td class=\"tabela\" align=\"center\">$operacao</td>
				  <td class=\"tabela\" align=\"center\">$tipo_servico</td>
				  <td class=\"tabela\" align=\"right\">$tarifa_bancaria</td>
				  <td class=\"tabela\" align=\"right\">$tx_adm</td>
				  <td class=\"tabela\" align=\"right\">$saldo</td>
				  <td class=\"tabela\">$protocolo</td>
				</tr>";
		} //fim while
		echo "<tr align=\"right\" bgcolor=\"#B6CBF6\" height=\"22\">
				  <td colspan=\"11\">Saldo da conta corrente R$&nbsp;</td>
				  <td><b>$saldo</b></td>
				  <td>&nbsp;</td>
				</tr>";
				?>
                 <tr align="right" bgcolor="#B6CBF6" height="22">
          	<td colspan="11" align="left">
				<font color="#000000">
		          	<b>Observação 1: </b>Os boletos <b><u>pagos e compensados</u></b> na  <b><font color="#0033CC">Segunda, Terça, Quarta-Feira, Quinta-Feira e Sexta-Feira</font></b> serão<br> creditados na conta bancária do associado na Terça-Feira da pr&oacute;xima semana (até 21:00 hs).
					<p>- Taxa de cobran&ccedil;a banc&aacute;ria : R$ 4,95.</p>
	            	<p>- Taxa de administra&ccedil;&atilde;o = 2,5%.</p>
            	</font>
			</td>
          	<td>&nbsp;</td>
          	<td>&nbsp;</td>
        </tr>
                <?php
				
		echo "</table>";
	}
//fim else


// Antecipacao.
if ( $codigo <> '' ){
// Verificando se o associado tem ou teve alguma antecipacao solicitada
$sql_ant = "SELECT date_format(data_solicitacao,'%d/%m/%Y') as data_solicitacao, 
					vr_emprestimo_solicitado, qtd_parcelas, protocolo,
					descricao_deposito, date_format(data_deposito,'%d/%m/%Y') AS data_deposito,
					bco_origem_deposito 
			FROM cs2.cadastro_emprestimo 
			WHERE codloja = $codigo 
			GROUP BY protocolo
			ORDER BY id";
$qry_ant = mysql_query($sql_ant,$con) or die ("1 - Erro SQL: $sql_ant" );
if ( mysql_num_rows($qry_ant ) > 0 ){
	?>
<table  width="900" border="0" cellspacing="0" cellpadding="3" align="center">
	<tr>
		<td class='Titulo_consulta' height='84' align="center" style="font-size:18px; color:#F60">Extrato de Antecipa&ccedil;&atilde;o de Cr&eacute;dito de Boletos</td>
	</tr>
</table>
<table  width="900" border="0" cellspacing="0" cellpadding="3" align="center">

    <?php
	while ( $res = mysql_fetch_array($qry_ant) ){
		$protocolo                = $res['protocolo'];
		$data_solicitacao         = $res['data_solicitacao'];
		$vr_emprestimo_solicitado = $res['vr_emprestimo_solicitado'];
		$vr_emprestimo_solicitado = number_format($vr_emprestimo_solicitado,2,",",".");
		$qtd_parcelas             = $res['qtd_parcelas'];
		$data_deposito            = $res['data_deposito'];
		$descricao_deposito       = $res['descricao_deposito'];
		$bco_origem_deposito      = $res['bco_origem_deposito'];

		if ( trim($descricao_deposito) == '' )
			$descricao_deposito = 'Aguardando libera&ccedil;&atilde;o do lote banc&aacute;rio.';
		?>
		<tr>
			<th class="total" width="15%" style="text-align:right; font-size:10px">Protocolo:</th>
			<th class="total" width="22%" style="text-align:left;color:#F00; font-size:10px"><?=$protocolo?></th>
			<th class="total" width="19%" style="text-align:right; font-size:10px">Data Solicita&ccedil;&atilde;o:</th>
			<th class="total" colspan="3" style="text-align:left; font-size:10px"><?=$data_solicitacao?></th>
		</tr>
		<tr>
			<th class="total" style="text-align:right; font-size:10px">Antecipa&ccedil;&atilde;o Solicitada:</th>
			<th class="total" style="text-align:left; font-size:10px">R$ <?=$vr_emprestimo_solicitado?></th>
			<th class="total" colspan="4" style="font-size:10px;color:#00F"><?=$descricao_deposito?></th>
		</tr>
	    <tr>
			<td colspan="4">
				<tr bgcolor="#CCCCCC">
					<th class="total" style="font-size:10px">Parcela</th>
					<th class="total" style="font-size:10px">Vencimento</th>
					<th class="total" style="font-size:10px">Vr Parcela</th>
					<th width="18%" class="total" style="font-size:10px">Data Pagamento</th>
					<th width="15%" class="total" style="font-size:10px">Valor Pagamento</th>
					<th width="11%" class="total" style="font-size:10px">Detalhes</th>
				</tr>
        <?php
		$sql_ant2 = "SELECT numero_parcela, date_format(data_vencimento,'%d/%m/%Y') as data_vencimento,
							valor_parcela, date_format(data_pagamento,'%d/%m/%Y') as data_pagamento,
							valor_pagamento 
					 FROM cs2.cadastro_emprestimo 
					 WHERE protocolo = '$protocolo'
					 ORDER BY numero_parcela";
		$qry_ant2 = mysql_query($sql_ant2,$con) or die ("Erro SQL: $sql_ant2" );
		while ( $res2 = mysql_fetch_array($qry_ant2) ){
			$numero_parcela = $res2['numero_parcela'];
			$vencimento = $res2['data_vencimento'];
			$valor_parcela = number_format($res2['valor_parcela'],2,",",".");
			$data_pagamento = $res2['data_pagamento'];
			$valor_pagamento = $res2['valor_pagamento'];
			$detalhes = $res2['$detalhes'];
			
			echo "<tr>
					<td class='html' style='font-size:10px'>$numero_parcela</td>
					<td class='html' style='font-size:10px'>$vencimento</td>
					<td class='html' style='font-size:10px'>$valor_parcela</td>
					<td class='html' style=vfont-size:10px'>$data_pagamento</td>
					<td class='html' style='font-size:10px'>$valor_pagamento</td>
					<td class='html' style='font-size:10px'>$detalhes</td>
				</tr>";
		}
		?>
			</td>
		</tr>
		<tr>
			<th class="total" colspan="6">&nbsp;</th>
		</tr>
		<tr>
			<th colspan="6">&nbsp;</th>
		</tr>
		<tr>
			<th colspan="6">&nbsp;</th>
		</tr>
        <?php
	}
} }
?>
</table>
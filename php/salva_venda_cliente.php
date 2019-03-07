<?php

	require "connect/conexao_conecta.php";	
		
	$id_venda   = $_REQUEST['iptIdVenda'];
	$codloja    = $_REQUEST['codloja'];
	$forma_pgto = $_REQUEST['forma_pgto'];
	$vencimento = $_REQUEST['vencimento'];
	$valor      = $_REQUEST['valor'];

	try{
		
		// Buscando	o que comprou
		$sql = "SELECT b.qtd, c.descricao, b.valor_unitario FROM cs2.cadastro_equipamento a
				INNER JOIN cs2.cadastro_equipamento_descricao b ON a.id = b.id_cadastro_equipamento
				INNER JOIN cs2.produto c ON b.codigo_barra = c.codigo
				WHERE a.id = $id_venda";
		$res = mysql_query($sql,$con);
		$descricao_deposito = 'Aquisi&ccedil;&atilde;o de Equipamentos:<br>';

		while ( @$reg = mysql_fetch_array($res) ){
			$qtd  = $reg['qtd'];
			$desc = $reg['descricao'];
			$vlr  = $reg['valor_unitario'];
			$vlr  = number_format($vlr,2,',','.');
			$descricao_deposito	 .= "
			$qtd - $desc - Valor Unit&aacute;rio: R$ $vlr <br>";
		}

		// Gravando os itens comprados
		$vencimento = substr($vencimento,6,4)."-".substr($vencimento,3,2)."-".substr($vencimento,0,2);
		$valor = str_replace('.','',$valor);
		$valor = str_replace(',','.',$valor);
		
		$sql = "
			INSERT INTO cs2.cadastro_equipamento_pagamento(codloja,id_venda,vencimento,valor,id_formapgto)
			VALUES ( '$codloja','$id_venda','$vencimento',$valor,$forma_pgto )
		";
		$res = mysql_query($sql,$con);
		
		
		$sql = "SELECT 
					DATE_FORMAT(vencimento, '%d/%m/%Y') as vencimento, 
					valor , 
					CASE id_formapgto 
						WHEN '1' THEN 'DINHEIRO'
						WHEN '2' THEN 'CHEQUE'
						WHEN '3' THEN 'CARTAO DE CREDITO'
						WHEN '4' THEN 'BOLETO'
						WHEN '5' THEN 'CARTAO DE DEBITO'
					END as pgto
					FROM cs2.cadastro_equipamento_pagamento
		        WHERE id_venda = $id_venda";
		$res = mysql_query($sql,$con);
		$saida = "
		<table border='0' width='50%' align='center'>
			<tr>
				<td colspan='3' align='center'>PAGAMENTOS REALIZADOS</td>
			</tr>
			<tr>
				<td width='40%' bgcolor='#E8E8E8'>Forma Pgto</td>
				<td width='40%' bgcolor='#E8E8E8'>Vencimento</td>
				<td width='20%' bgcolor='#E8E8E8'>Valor</td>
			</tr>
		";
		while (@$reg = mysql_fetch_array($res) ){
			$vec = $reg['vencimento'];
			$vlr = number_format($reg['valor'],2,',','.');
			$frm = $reg['pgto'];
			$saida .= "
				<tr>
					<td>$frm</td>
					<td>$vec</td>
					<td>R$ $vlr</td>
				</tr>
				";
		}
		$saida .= "</table>";
		echo $saida;
	}
	catch (SoapFault $e){
		//return $e->getMessage();
		echo "ERRO - Houve um erro ao gravar os recebimentos. Favor verificar no Extrato do Franqueado";
	}
?>
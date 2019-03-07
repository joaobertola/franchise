<?

	require "connect/conexao_conecta.php";	
		
	$id_venda     = $_REQUEST['iptIdVenda'];
	$id_franquia  = $_REQUEST['id_franquia'];
	$qtd_parcelas = $_REQUEST['qtd_parcela'];
	$vencimentos  = $_REQUEST['vencimento'];
	$tp_movimento = $_REQUEST['tipo_pag'];

	try{
		// Buscando o valor total da venda
		$sql = "SELECT sum(  b.qtd * b.valor_unitario ) AS valor_total FROM cs2.franquia_equipamento a
				INNER JOIN cs2.franquia_equipamento_descricao b ON a.id = b.id_franquia_equipamento
				WHERE a.id = $id_venda";
		$res = mysql_query($sql);
		$valor_total = mysql_result($res,0,'valor_total');
		$valor_parcela = ( $valor_total / $qtd_parcelas );
	
		// Buscando	o que comprou
	
		$sql = "SELECT b.qtd, c.descricao, b.valor_unitario, b.numero_serie FROM cs2.franquia_equipamento a
				INNER JOIN cs2.franquia_equipamento_descricao b ON a.id = b.id_franquia_equipamento
				INNER JOIN cs2.produto c ON b.codigo_barra = c.codigo
				WHERE a.id = $id_venda";
		$res = mysql_query($sql);
		if ( $tp_movimento == 'D' )
			$descricao_deposito = 'Aquisi&ccedil;&atilde;o de Equipamentos:<br>';
		while ( $reg = mysql_fetch_array($res) ){
			$qtd                 = $reg['qtd'];
			$desc                = $reg['descricao'];
			$numero_serie        = $reg['numero_serie'];
			$vlr                 = $reg['valor_unitario'];
			$vlr                 = number_format($vlr,2,',','.');
			$descricao_deposito	.= "$qtd - $desc - Valor Unit&aacute;rio: R$ $vlr <br>N&uacute;mero de S&eacute;rie: $numero_serie<br>";
		}
		$j = 0;
		for( $i = 0 ; $i < $qtd_parcelas ; $i++ ){
			
			$j++;
			// Gravando os itens comprados
			$nparc = str_pad($j,3,0,STR_PAD_LEFT);
			$vencimento = $vencimentos[$i];
			$vencimento = substr($vencimento,6,4)."-".substr($vencimento,3,2)."-".substr($vencimento,0,2);
			
			$sql = "
				INSERT INTO cs2.cadastro_emprestimo_franquia
					(
						id_franquia, data_solicitacao, hora_solicitacao, qtd_parcelas, numero_parcela,
						data_vencimento, vr_emprestimo_solicitado, valor_parcela, protocolo,
						depositado_cta_cliente, descricao_deposito, data_deposito, tp_movimento
					)
				VALUES
					(
						'$id_franquia' , NOW() , NOW() , '$qtd_parcelas', '$nparc',
						'$vencimento', $valor_total, $valor_parcela, 'Venda: $id_venda',
						'S', '$descricao_deposito', NOW(), '$tp_movimento'
					)
			";
			$res = mysql_query($sql);
		}
		
		echo "<script>alert('Registro gravado com sucesso.')</script>";
		
	}
	catch (SoapFault $e){
		//return $e->getMessage();
		echo "<script>alert('Houve um erro ao gravar os recebimentos. Favor verificar no Extrato do Franqueado')</script>";
	}
	
?>
<script language="javascript">
	window.location.href="../php/painel.php?pagina1=area_restrita/d_equipamentos.php";
</script>
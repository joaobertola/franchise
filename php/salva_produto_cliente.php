<?php

	require "connect/conexao_conecta.php";	
	
	$id_venda     = $_REQUEST['id_venda'];
	$codigo       = $_REQUEST['codloja'];
	$codigo_barra = $_REQUEST['codigo_barra'];
	$numero_serie = $_REQUEST['numero_serie'];
	$id_produto = $_REQUEST['id_produto'];
        
	$valor        = $_REQUEST['valor'];
	$idAgendador = $_REQUEST['id_agendador'];
	$id_agendador = $_REQUEST['id_agendador'];
	$id_consultor = $_REQUEST['id_consultor'];
	$data_venda   = $_REQUEST['data_venda'];
	$qtd = $_REQUEST['qtd'];

	if($qtd == '' || empty($qtd)){
		$qtd = 1;
	}
	$data_venda = substr($data_venda,6,4).'-'.substr($data_venda,3,2).'-'.substr($data_venda,0,2);
	
	$valor        = str_replace('.','',$valor);
	$valor        = str_replace(',','.',$valor);
	
	if ( $id_venda == '' ){
		$sql = "
			INSERT INTO cs2.cadastro_equipamento(codloja, data_compra, id_agendador, id_consultor, data_venda )
			VALUES( '$codigo' , NOW(), '$id_agendador', '$id_consultor', '$data_venda' )
		";
		$res = mysql_query($sql,$con);
		$id_venda = mysql_insert_id($con);

	}

	if($numero_serie == '' || empty($numero_serie)){

		$sql = "SELECT fed.id, fe.id AS id_venda FROM cs2.franquia_equipamento_descricao fed INNER JOIN cs2.franquia_equipamento fe ON fe.id = fed.id_franquia_equipamento WHERE  id_funcionario = '$id_consultor' AND codigo_barra = '$codigo_barra' AND fe.consignacao = 'S'";
		$resAux = mysql_query($sql,$con);

		@$id =  mysql_result($resAux,0,'id');

		if($id){

			$sql = "UPDATE cs2.franquia_equipamento_descricao SET saldo = saldo - $qtd WHERE id = '$id'";
			$resAux2 = mysql_query($sql,$con);
			$sql2 = "INSERT INTO cs2.franquia_equipamento_descricao_log(id_franquia_equipamento, data_hora, qtd, tipo) VALUES ('$id',NOW(),'$qtd', 'V')";
			$res2 = mysql_query($sql2, $con);

		}
	}

	$sqlVenda = "SELECT
					COUNT(*) AS qtd,
					f.nome,
					fed.numero_serie,
					fed.codigo_barra,
					fe.id_consultor,
					fe.id
				FROM cs2.cadastro_equipamento fe
				INNER JOIN cs2.cadastro_equipamento_descricao fed
				ON fe.id = fed.id_cadastro_equipamento
				INNER JOIN cs2.funcionario f
				ON f.id_consultor_assistente = fe.id_consultor
				WHERE fed.numero_serie = '$numero_serie'
				AND fed.numero_serie != ''
				";

	$resVenda = mysql_query($sqlVenda,$con);
	$qtdVerificar = mysql_result($resVenda,0,'qtd');
	$nome = mysql_result($resVenda,0,'nome');

	if($qtdVerificar > 0){

		$arrResult['erro'] = '0';
		$arrResult['nome'] = $nome;

		echo '0]['.$nome ;
		die;
	}else{
	// Gravando os itens comprados
	$sql = "
		INSERT INTO cs2.cadastro_equipamento_descricao
			( id_cadastro_equipamento, qtd, codigo_barra, numero_serie, valor_unitario, id_produto )
		VALUES
			( '$id_venda' , '$qtd' , '$codigo_barra' , '$numero_serie', '$valor', '$id_produto' )
	";
	$res = mysql_query($sql, $con);

    $sqlAux = "UPDATE cs2.franquia_equipamento fe
    			INNER JOIN cs2.franquia_equipamento_descricao fed
    			ON fed.id_franquia_equipamento = fe.id
    			SET fe.consignacao = 'N'
    			WHERE fed.numero_serie = '$numero_serie'
    			AND fed.numero_serie != ''";

	$resAux = mysql_query($sqlAux, $con);
	// Selecionando todas as compras e voltando com a resposta
	$sql = "SELECT a.qtd, a.numero_serie, b.descricao, a.valor_unitario FROM cs2.cadastro_equipamento_descricao a
			INNER JOIN base_web_control.produto b ON b.id_cadastro = 62735 AND a.codigo_barra = b.codigo_barra
			WHERE id_cadastro_equipamento = $id_venda
			ORDER BY a.id";

	$res = mysql_query($sql, $con);
	$i          = 0;
	$soma_total = 0;
	$saida = "
	$id_venda][
	<meta charset='iso-8859-1' />
	<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
	<tr>
		<td colspan='6' height='1' bgcolor='#999999'></td>
	</tr>
	<tr height='20' class='titulo'>
		<td width='10%'>Qtde</td>
		<td width='35%'>Descrição</td>
		<td width='15%'>Série</td>
		<td width='15%'>Vr. Unitário</td>
		<td width='15%'>Total</td>
		<td width='10%'>Ação</td>
	</tr>
	";
	while ( $reg = mysql_fetch_array($res) ){
		$qtd = $reg['qtd'];
		$descricao = $reg['descricao'];
		$serie = $reg['numero_serie'];
		$vr_unit = $reg['valor_unitario'];
		
		$total = $qtd * $vr_unit;
		
		$soma_total += $total;
		
		$vr_unit = number_format($vr_unit,2,',','.');
		$total = number_format($total,2,',','.');
		
		$saida .=  "<tr height='22'";
		if (($a%2) == 0) {
			$saida .= "bgcolor='#E5E5E5'>";
		} else {
			$saida .= ">";
		}
		$totalOriginal = $total;
		$i++;

		$saida .= "    <td width='10%' align='center'>$qtd</td>";
		$saida .= "    <td width='35%' align='center'>$descricao</td>";
		$saida .= "    <td width='15%' align='center'>$serie</td>";
		$saida .= "    <td width='15%' align='center'>R$ $vr_unit</td>";
		$saida .= "    <td width='15%' align='center'>R$ $total</td>";
		$saida .= "    <td width='10%' align='center'>&nbsp;-&nbsp;</td>";
		$saida .= "</tr>";	

	}
	$soma_totalv = number_format($soma_total,2,',','.');
	$saida .= "<tr class='subtitulodireita'>
			<td id='totalGeral_' align='right' colspan='6'>Total Geral R$ $soma_totalv</td>
	           </tr>";
	$saida .= "<tr>
				  <td>&nbsp;</td>
			   </tr>";
	$saida .= '</table>';

	$saida .= "<input type='hidden' class='vlrTotalOriginal' value='$totalOriginal'>";
	echo $saida;
	}
	
?>
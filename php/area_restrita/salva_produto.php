<?php

	require "connect/conexao_conecta.php";	
	
	$id_venda     = $_REQUEST['id_venda'];
	$id_franquia  = $_REQUEST['id_franquia'];
	$codigo_barra = $_REQUEST['codigo_barra'];
	$numero_serie = $_REQUEST['numero_serie'];
	$vrproduto    = $_REQUEST['vr_produto'];
	$tipo_pag     = $_REQUEST['tipo_pag'];
	
	if ( $id_venda == '' ){
		$sql = "
			INSERT INTO cs2.franquia_equipamento(id_franquia, data )
			VALUES('$id_franquia', NOW())
		";
		$res = mysql_query($sql, $con);
		$id_venda = mysql_insert_id();
		
	}
	if ( $tipo_pag == 'D' ){
		// Buscando o valor unitario do produto
		$sql = "SELECT valor from cs2.produto WHERE codigo = '$codigo_barra' ";
		$res = mysql_query($sql,$con);
		$valor = mysql_result($res,0,'valor');
	}else{
		$valor = str_replace('.','',$vrproduto);
		$valor = str_replace(',','.',$valor);
	}
	
	// Gravando os itens comprados
	$sql = "
		INSERT INTO cs2.franquia_equipamento_descricao
			( id_franquia_equipamento, qtd, codigo_barra, numero_serie, valor_unitario )
		VALUES
			( '$id_venda' , 1 , '$codigo_barra' , '$numero_serie', '$valor' )
	";
	$res = mysql_query($sql, $con);
	
	// Selecionando todas as compras e voltando com a resposta
	$sql = "SELECT a.qtd, a.numero_serie, b.descricao, a.valor_unitario FROM cs2.franquia_equipamento_descricao a
			INNER JOIN cs2.produto b ON a.codigo_barra = b.codigo
			WHERE id_franquia_equipamento = $id_venda
			ORDER BY id";
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
		<td width='35%'>Descri��o</td>
		<td width='15%'>S�rie</td>
		<td width='15%'>Vr. Unit�rio</td>
		<td width='15%'>Total</td>
		<td width='10%'>A��o</td>
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
		
		$i++;

		$saida .= "    <td width='10%' align='center'>$qtd</td>";
		$saida .= "    <td width='35%' align='center'>$descricao</td>";
		$saida .= "    <td width='15%' align='center'>$serie</td>";
		$saida .= "    <td width='15%' align='center'>R$ $vr_unit</td>";
		$saida .= "    <td width='15%' align='center'>R$ $total</td>";
		$saida .= "    <td width='10%' align='center'>&nbsp;-&nbsp;</td>";
		$saida .= "</tr>"; 
		

	}
	$data = date('d/m/Y');
	
	$soma_totalv = number_format($soma_total,2,',','.');
	$saida .= "<tr class='subtitulodireita'>
				  <td  align='right' colspan='6'>Total Geral R$ $soma_totalv</td>
			   </tr>";
	$saida .= "<tr>
				  <td>&nbsp;</td>
			   </tr>
			   <tr>
			   		<td align='right' colspan='6'>
				      Data da Venda : 
					  <input type='text' name='data_venda' id='data_venda' value='$data' />
			   </tr>
			   <tr>
				  <td>&nbsp;</td>
			   </tr>
               <tr>
				  <td align='right' colspan='6'>
				      Qtd Parcelas : 
					  <select name='qtd_parcela' id='qtd_parcela'onchange='mostra_parcelas(this.value,$soma_total,$id_venda)'>
					     <option value='0'>.. Selecione ..</option>
					  	 <option value='1'>1</option>
						 <option value='2'>2</option>
						 <option value='3'>3</option>
						 <option value='4'>4</option>
						 <option value='5'>5</option>
						 <option value='6'>6</option>
						 <option value='7'>7</option>
						 <option value='8'>8</option>
						 <option value='9'>9</option>
						 <option value='10'>10</option>
						 <option value='11'>11</option>
						 <option value='12'>12</option>
					  </select>
					</td>	
			   </tr>";
	$saida .= '</table>';
	echo $saida;
	


?>
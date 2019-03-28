<?php

require "connect/sessao.php";

$id = $_REQUEST['id'];

if ( empty($id) ){
	echo "<script>javascript: alert('Selecione uma funcion�rio');history.go(-3)</script>";
	exit;
}
	
$sql = "SELECT nome FROM cs2.funcionario WHERE id = $id";
$res = mysql_query($sql,$con) or die ("Erro SQL : $sql");
if ( mysql_num_rows($res) > 0 ){
	while ( $reg = mysql_fetch_array($res) ){
		$nome_consultor	= $reg['nome'];
		echo "	<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
			<tr>
				<td align='center' bgcolor='#CCCCCC' bordercolor='#FF0000'><a href=\"painel.php?pagina1=Franquias/pagamentos_funcionarios_novo.php&id_func=$id\">Novo Registro</a></td>
				<td align='center' colspan='4' height='1' bgcolor='#CCCCCC'>EXTRATO DE PAGAMENTOS A FUNCIONARIOS ( $nome_consultor )</td>
						</tr>
						<tr height='20' bgcolor='FF9966'>
							<td align='center'>Data</td>
							<td align='center'>Valor</td>
							<td align='left'>Descricao</td>
							<td align='left'>&nbsp;</td>
							<td align='left'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan='5' height='1' bgcolor='#666666'></td>
						</tr>";
	}
}

$registro = 0;
$sql = "SELECT date_format(data_pgto,'%d/%m/%Y') as data_pgto, valor_pgto, descricao, origem, id 
		FROM cs2.contacorrente_funcionario 
		WHERE id_func = $id";
$res = mysql_query($sql,$con) or die ("Erro SQL : $sql");
if ( mysql_num_rows($res) > 0 ){
	while ( $reg = mysql_fetch_array($res) ){
		$registro++;
		$data	= $reg['data_pgto'];
		$valor	= ' R$ '.number_format($reg['valor_pgto'],2,","," ");

		$descricao	= $reg['descricao'];
		$origem		= $reg['origem'];
		$id_lanc	= $reg['id'];
		
		if ( $origem == 1 ){  // retorno do lote banc�rio, nao pode ser alterado
			$bt_alterar = '';
			$bt_excluir = '';
		}else{
			$bt_alterar = "<a href=\"painel.php?pagina1=Franquias/pagamentos_funcionarios_alterar.php&id_func=$id&id_lanc=$id_lanc\">Alterar</a>";
			$bt_excluir = "<a href=\"painel.php?pagina1=Franquias/pagamentos_funcionarios_excluir.php&id_func=$id&id_lanc=$id_lanc\">Excluir</a>";
		}
		echo "<tr height='24'";
		if (($registro%2) == 0) {
			echo "bgcolor='#E5E5E5'>";
		} else {
			echo ">";
		}
		echo "	<td width='10%' align='center'>$data</td>
 			    <td width='10%' align='center'>$valor</td>
				<td width='70%' align='left'>$descricao</td>
				<td width='5%' align='left'>$bt_alterar</td>
				<td width='5%' align='left'>$bt_excluir</td>
			</tr>";
	}
}
echo "<tr>
		<td colspan='5' bgcolor='FF9966'>Total de registros : $registro</td>
	  </tr>";

?>
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
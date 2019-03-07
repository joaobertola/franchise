<?php

$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
if (!$con) {
	echo 'Erro na conexao com o Servidor<br>';
	echo mysql_error();
	exit;
} else {
	$database = mysql_select_db("cs2",$con);
	if (!$database) {
		echo 'Erro na conexão com o Banco de dados<br>';
		echo mysql_error();
	}
}

$escolha  = $_REQUEST['escolha'];
$escolha2 = $_REQUEST['escolha2'];

$qtd = count($escolha);
$qtd2 = count($escolha2);


if ( $qtd > 0 && $qtd2 > 0 ){
	echo "<script>alert('Por Favor, selecione somente VERDE ou VERMELHO ');history.back()</script>";
	exit;
}

if ( $qtd == 0 && $qtd2 == 0 ){
	echo "<script>alert('V. Sa. Poderia selecionar alguem ? Obrigado.');history.back()</script>";
	exit;

}

if ( $qtd == 0 ){
	$escolha = $escolha2;
	$qtd = $qtd2;
}


echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='1' class='bodyText'>";
		echo "<tr height='20' bgcolor='FF9966'>";
		echo "	<td>Cliente</td>";
		echo "	<td>Depositado na conta de:</td>";
		echo "	<td>Descri&ccedil;&atilde;o</td>";
		echo "	<td>Saldo Anterior</td>";
		echo "	<td>$Valor Debitado</td>";
		echo "	<td>Saldo Atual</td>";
		echo "</tr>";

$total_pago = 0;
for ( $i = 0 ; $i < $qtd ; $i++ ){

	$codloja = $escolha[$i];
	// Buscando na tabela do valor a debitar
	$sql = "SELECT a.coluna1, a.valor, b.nomefantasia FROM cs2.acerto_saldoerrado a 
			INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
			WHERE a.codloja = $codloja";
	$qry = mysql_query($sql,$con);
	while ( $reg = mysql_fetch_array($qry) ){
		$valor      = $reg['valor'];
		$cliente    = $reg['nomefantasia'];
		$vlr        = ($valor * 1 ) / 100;
		$total_pago += $vlr;
		
		$coluna1    = $reg['coluna1'];
		$data_dep   = substr($coluna1,93,8);
		$data_processamento = substr($coluna1,158,4).'-'.substr($coluna1,156,2).'-'.substr($coluna1,154,2);
		$data_dep   = substr($data_dep,154,2).'/'.substr($data_dep,156,2).'/'.substr($data_dep,158,4);
		$bco        = substr($coluna1,20,3);
		$age        = substr($coluna1,24,4);
		$cta        = (substr($coluna1,29,12)*1).'-'.trim(substr($coluna1,41,2));
		$nome       = substr($coluna1,43,50);
	
		$descricao = "DEPOSITO C/C ASSOCIADO (Bco: $bco Ag: $age Conta: $cta";
		$comprova  = 'DOC/TED DEPOSITADO COM SUCESSO PELO BANCO TRANSMISSOR';

		$sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil 
					WHERE codloja= '$codloja' 
					ORDER BY id DESC 
					LIMIT 1";
		$qr_sdo = mysql_query($sql_sdo,$con) or die ("ERRO: $sql_sdo");
		$saldo = mysql_result($qr_sdo,0,'saldo');
		$saldo_anterior = mysql_result($qr_sdo,0,'saldo');
		$saldo_anterior = number_format($saldo_anterior,2,",",".");
		
		$vlr_debitado = number_format($vlr,2,",",".");
		
		if ( empty($saldo) ) $saldo = '0';
		$saldo -= $vlr;
		# registrando pagamento
		$sql_insert = "INSERT INTO cs2.contacorrente_recebafacil
						( 
							codloja, data, datapgto, tarifa_bancaria, 
							operacao, discriminacao, valor, comprovante, origem, saldo 
						)
						VALUES
						(
							'$codloja',now(),'$data_processamento','Null',
							1,'$descricao','$vlr','$comprova','WEL','$saldo'
						)";
		$qr_insert = mysql_query($sql_insert,$con) or die ("ERRO: $sql_insert");
	
		echo "<tr>";
		echo "	<td>$cliente</td>";
		echo "	<td>$nome</td>";
		echo "	<td>$descricao</td>";
		echo "	<td>$saldo_anterior</td>";
		echo "	<td>$vlr_debitado</td>";
		echo "	<td>$saldo</td>";
		echo "</tr>";
	
		$saldo_anterior = number_format($saldo,2,",",".");
		
		$saldo -= 8;
		$sql_insert = "	INSERT INTO cs2.contacorrente_recebafacil
							( codloja, data, datapgto, tarifa_bancaria, operacao, discriminacao, valor, origem, saldo )
						VALUES('$codloja',now(),'$data_processamento','Null',1,'DOC - TRANSFERÊNCIA ENTRE BANCOS', 8 ,'WEL','$saldo')";
		$qr_insert = mysql_query($sql_insert,$con) or die ("ERRO: $sql_insert");
		$saldo_atual = number_format($saldo,2,",",".");
		
		echo "<tr>";
		echo "	<td>$cliente</td>";
		echo "	<td>$nome</td>";
		echo "	<td>$comprova</td>";
		echo "	<td>$saldo_anterior</td>";
		echo "	<td>8,00</td>";
		echo "	<td>$saldo_atual</td>";
		echo "</tr>";

		$sqlx = "UPDATE cs2.acerto_saldoerrado SET pago = 1 WHERE codloja = $codloja";
		$qry = mysql_query($sqlx,$con) or die ("ERRO: $sqlx");

	}
}
$total_pago = number_format($total_pago,2,",",".");
?>
<tr>
	<td colspan="6">Total Pago: <?=$total_pago?></td>
</table>
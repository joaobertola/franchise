<?php

require "connect/sessao.php";

$valorpg = $_REQUEST['valorpg'];
$valorpg = str_replace('.','',$valorpg);
$valorpg = str_replace(',','.',$valorpg);
$datapg =  $_REQUEST['datapg'];

$datapg = substr($datapg,6,4).'-'.substr($datapg,3,2).'-'.substr($datapg,0,2);

$numdoc = $_REQUEST['numdoc'];

# PESQUISA NA TABELA TITULOS_RECEBAFACIL
$sql = "SELECT	a.codloja,a.valor,a.numdoc,a.vencimento,b.razaosoc,b.cidade,
                a.cpfcnpj_devedor, c.Nom_Nome, b.banco_cliente, b.agencia_cliente, 
                b.conta_cliente, b.cpfcnpj_doc, b.nome_doc, a.tp_titulo 
        FROM cs2.titulos_recebafacil a
        INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
        LEFT OUTER JOIN base_inform.Nome_Brasil c ON a.cpfcnpj_devedor = c.Nom_CPF
        WHERE 
            a.numboleto = '$numdoc' or 
            a.numboleto_bradesco = '$numdoc' or 
            a.numboleto_itau = '$numdoc'";

$qr_sql = mysql_query($sql,$con) or die ("ERRO: $sql");
$qtd  = mysql_num_rows($qr_sql);
if ( $qtd > 0 ){
	$dados = mysql_fetch_array($qr_sql);
	$tp_tit = $dados['tp_titulo'];
	$Valor_Bol = $dados['valor'];
	$Vencimento = $dados['vencimento'];
	$codloja = $dados['codloja'];
	
	$juros = $valorpg - $Valor_Bol;

	# Atualizando tabela titulo_recebafacil
	$sql_update = "	UPDATE cs2.titulos_recebafacil 
					SET 
						datapg = '$datapg',
						valorpg='$valorpg',
						juros='$juros' 
					WHERE 
						numboleto = '$numdoc' or 
						numboleto_bradesco = '$numdoc' or 
						numboleto_itau = '$numdoc'";
	$qr_update = mysql_query($sql_update,$con) or die ("ERRO: $sql_update");
	# Verificando se o titulo já está na tabela Conta Corrente Receba Fácil
	$sql_cta = "SELECT count(*) as qtd FROM cs2.contacorrente_recebafacil WHERE numboleto = '$numdoc'";
	$qr_cta = mysql_query($sql_cta,$con) or die ("ERRO: $sql_cta");
	$qtd = mysql_result($qr_cta,0,'qtd');
	if ( empty($qtd) ) $qtd = 0;
	if ( $qtd == 0 ){
		$Text = ' Titulo Receb. Bco : '.$dados['cpfcnpj_devedor'].' '.$dados['Nom_Nome'];
		// verifico o saldo do cliente
		$sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil
					WHERE codloja = '$codloja' order by id desc limit 1";
		$qr_sdo = mysql_query($sql_sdo,$con) or die ("ERRO: $sql_sdo");
		@$saldo = mysql_result($qr_sdo,0,'saldo');
		if ( empty($saldo) ) $saldo = '0';
                if ( trim($saldo) == '' )
                    $saldo = '0';
                
		$tx_adm = 0;
		
		$tx_adm = ( $valorpg * 0.02 );

		$saldo += $valorpg;
		$saldo = ($saldo - ( 4.95 + $tx_adm) );
		$sql_ins = "INSERT INTO cs2.contacorrente_recebafacil(
                                                    numboleto,codloja,data,discriminacao,venc_titulo,
                                                    valor_titulo,valor,saldo,datapgto,tx_adm)
                            SELECT numboleto_bradesco,a.codloja, now(),'$Text', a.vencimento, a.valor,
                                    '$valorpg',' $saldo','$datapg','$tx_adm'
                            FROM cs2.titulos_recebafacil a
                            INNER JOIN cs2.cadastro b ON a.codloja=b.codloja
                            WHERE a.numboleto = '$numdoc' or 
                                      a.numboleto_bradesco = '$numdoc' or 
                                      a.numboleto_itau = '$numdoc'";
		$qr_ins = mysql_query($sql_ins,$con) or die ("ERRO(X): $sql_ins");
	}
	echo "<script>alert('Titulo Baixado com sucesso')</script>";
}else
	echo "<script>alert('Titulo NAO ENCONTRADO !!! ')</script>";

?>
<script>
	window.location.href="painel.php?pagina1=clientes/a_baixa_titulo_cred_rec_bol.php";
</script>

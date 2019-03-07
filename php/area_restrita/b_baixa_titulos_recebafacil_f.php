<?php

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$numdoc  = $_POST['numdoc'];
$datapg  = $_POST['datapg'];
$datapg  = substr($datapg,6,4).'-'.substr($datapg,3,2).'-'.substr($datapg,0,2);
$valorpg = $_POST['valorpg'];

$valorpg = str_replace('.','',$valorpg);
$valorpg = str_replace(',','.',$valorpg);

$comando = "SELECT a.codloja, a.cpfcnpj_devedor, b.Nom_Nome FROM cs2.titulos_recebafacil a
			INNER JOIN base_inform.Nome_Brasil b  ON b.Nom_CPF=a.cpfcnpj_devedor
			WHERE a.numdoc = '$numdoc' and a.datapg is NULL
			ORDER BY b.id desc
            LIMIT 1
			";
$conex = mysql_query($comando, $con) or die ("Erro SQL : $comando");
$matriz = mysql_fetch_array($conex);
$codloja = $matriz['codloja'];
if ( $codloja != '' ){
	$cpfcnpj_devedor = $matriz['cpfcnpj_devedor'];
	$Nom_Nome = $matriz['Nom_Nome'];
	
	# Achou o Titulo, baixando o mesmo
	$comando = "UPDATE cs2.titulos_recebafacil set datapg = '$datapg', valorpg = '$valorpg'
				WHERE numdoc = '$numdoc'";
	$conex = mysql_query($comando, $con) or die ("Erro SQL : $comando");
	# Verifico se o titulo já foi lancado na conta corrente, caso não INSIRO
	$comando = "SELECT count(*) qtd FROM cs2.contacorrente WHERE numboleto = '$numdoc'";
	$conex = mysql_query($comando, $con) or die ("Erro SQL : $comando");
	$matriz = mysql_fetch_array($conex);
	$qtd = $matriz['qtd'];
	if ( empty($qtd) ) $qtd = '0';
	if ( $qtd == 0 ){
		// verifico o saldo do cliente
		$sql_sdo = "SELECT saldo FROM cs2.contacorrente_recebafacil
					WHERE codloja='$codloja' order by id desc limit 1";
		$qr_sdo = mysql_query($sql_sdo,$con) or die ("ERRO: $sql_sdo");
		$saldo = mysql_result($qr_sdo,0,'saldo');
		if ( empty($saldo) ) $saldo = '0';
		$tx_adm = ( $valorpg * 0.02 );
		$saldo += $valorpg;
		$saldo = ($saldo - ( 3.5 + $tx_adm) );
		$Text = 'Titulo Baixa Manual : '.$cpfcnpj_devedor.' '.$Nom_Nome;
		$sql_ins = "INSERT INTO cs2.contacorrente_recebafacil(
							numboleto , codloja , data , discriminacao , venc_titulo ,
							valor_titulo , valor , saldo , datapgto , tx_adm )
					SELECT numboleto , a.codloja , now(), '$Text' , a.vencimento , a.valor ,
							'$valorpg ',' $saldo' , '$datapg' , '$tx_adm'
					FROM cs2.titulos_recebafacil a
					INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
					WHERE a.numboleto = '$numdoc'";
		$qr_ins = mysql_query($sql_ins,$con) or die ("ERRO: $sql_ins");
		$mensagem = "RECEBIMENTO DE TÍTULO REALIZADO COM SUCESSO";
	}else{
		$mensagem = "ATENÇÃO , ESTE TÍTULO JÁ FOI LANÇADO, NÃO SERÁ POSSÍVEL PROSSEGUIR.";
	}
}else{
	$mensagem = " TÍTULO NÃO ENCONTRADO PARA BAIXA ! ";
}

echo "<script>alert(\" $mensagem \");</script>";
mysql_close($con);

echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_baixa_titulos_crediario_recupere.php\";>";

?>
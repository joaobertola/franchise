<?php
require_once("../connect/conexao_conecta.php");
$codloja = $_REQUEST['codloja'];
$codigo  = $_REQUEST['codigo'];
$codcons = $_REQUEST['codcons'];
$qtd     = $_REQUEST['qtd'];
$id      = @implode(',', $_REQUEST['id']);   
$venda   = str_replace(".","",$_REQUEST['venda']);
$venda   = str_replace(",",".",$venda);
$tx_mens = str_replace(".","",$_REQUEST['tx_mens']);
$tx_mens = str_replace(",",".",$tx_mens);

//echo "<pre>";
//print_r($_REQUEST);
//exit;

$msg_txt = '';
$sql = "SELECT tx_mens FROM cs2.cadastro WHERE codloja = $codloja AND tx_mens != ".$tx_mens;
$qry = mysql_query ($sql, $con);
while($rs = mysql_fetch_array($qry)){
    $tx_mens_anterior = $rs['tx_mens'];
    $msg_txt = "Alterado Valor Mensalidade: R$ ".number_format($tx_mens_anterior,2,',','.').' para R$ '.number_format($tx_mens,2,',','.').'<br>';
}

$sql = "SELECT * FROM cs2.valconscli WHERE codloja = '$codloja' AND id IN($id) ORDER BY id ";
$qry = mysql_query ($sql, $con);

$sql_up = "UPDATE cadastro SET tx_mens = '$tx_mens' WHERE codloja = '$codloja'";
$qry_up = mysql_query ($sql_up, $con);

$cont=0;
while($rs = mysql_fetch_array($qry)){
    
        // VALOR DAS CONSULTAS
        $sql_vlr = "SELECT valorcons FROM cs2.valconscli WHERE codloja = '$codloja' AND id = '{$rs['id']}'";
	$qry_vlr = mysql_query ($sql_vlr, $con);
	$valorcons = mysql_result($qry_vlr, 0, 'valorcons');
	if( $valorcons <> $venda[$cont] ){
            $sql_up = "UPDATE valconscli SET valorcons = '$venda[$cont]' WHERE codloja = '$codloja' AND id = '{$rs['id']}'";
            $qry_up = mysql_query ($sql_up, $con);
            $msg_txt .= "Alterado o VALOR UNITARIO da consulta [ ".$codcons[$cont].' ] de R$ '.$valorcons.' para '.$venda[$cont].'<br>';
        }
        
        // BONIFICADAS
	
	$sql_qtd = "SELECT qtd FROM cs2.bonificadas WHERE codloja = '$codloja' AND tpcons = '{$codcons[$cont]}'";
	$qry_qtd = mysql_query ($sql_qtd, $con);
	$total_qtd = mysql_num_rows($qry_qtd);
	if( ($total_qtd == 0) and ($qtd[$cont] > 0) ){
		$sql_up = "INSERT INTO cs2.bonificadas (codloja, tpcons, qtd)VALUES('$codloja', '{$codcons[$cont]}', '$qtd[$cont]')";
		$qry_up = mysql_query ($sql_up, $con);
                $msg_txt .= "Adicionado consulta bonificada [ ".$codcons[$cont].' ] de 0 para '.$qtd[$cont].'<br>';
	}elseif($total_qtd > 0){
                $total_existente = mysql_result($qry_qtd, 0, 'qtd');
		$sql_up = "UPDATE cs2.bonificadas SET qtd = '$qtd[$cont]' WHERE codloja = '$codloja' AND tpcons = '{$codcons[$cont]}'";
		$qry_up = mysql_query ($sql_up, $con);
                $qtd_new = $qtd[$cont];
                if ( $qtd_new == '' ) $qtd_new = 0;
                if ( $total_existente != $qtd_new )
                    $msg_txt .= "Alterado consulta bonificada [ ".$codcons[$cont]." ] de $total_existente para ".$qtd_new.'<br>';
	}
	$cont++;		
}

if ( $msg_txt != '' ){

    // Gravando o historico
    $sql = "INSERT INTO cs2.cadastro_alteracoes(codloja,alteracoes)
            VALUES('$codloja','$msg_txt')";
    $qry = mysql_query ($sql, $con);
}

header("Location: ../painel.php?pagina1=Franquias/b_tabelapreco1.php&codigo=$codigo&msg=1");
?>
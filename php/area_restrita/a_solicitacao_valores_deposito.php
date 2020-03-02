<?php

require "../connect/conexao_conecta.php";

$id_pedido   = $_REQUEST['id_pedido'];
$dt_deposito = $_REQUEST['data_deposito'];

if ( trim($dt_deposito) == '' )
    $dt_deposito = 'NULL';
else
    $dt_deposito = substr($dt_deposito,6,4).'-'.substr($dt_deposito,3,2).'-'.substr($dt_deposito,0,2);

echo $sql_u = "UPDATE cs2.solicitacao_valores
			SET
				data_deposito = '$dt_deposito'
		  WHERE id = '$id_pedido'";
$qr2 = mysql_query($sql_u,$con) or die('Erro Mysql : '.$sql_u);

?>
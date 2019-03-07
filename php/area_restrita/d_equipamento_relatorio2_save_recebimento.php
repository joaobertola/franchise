<?php

require "../connect/conexao_conecta.php";

//echo "<pre>";
//print_r( $_REQUEST);
//die;
    
$data = $_REQUEST['data_confirmacao'];
$data = substr($data,6,4).'-'.substr($data,3,2).'-'.substr($data,0,2);

if ( $_REQUEST['cmd'] == 'del')
    $data = 'NULL';
else
    $data = "'$data'";

$sql_parcela = "UPDATE cs2.cadastro_equipamento_pagamento "
        . "SET dt_conf_recebimento = $data "
        . "WHERE id = '{$_REQUEST['id_pagamento']}'";

if (mysql_query($sql_parcela, $con) or die('ERRO')) {
    echo 1;
} else {
    echo 0;
}
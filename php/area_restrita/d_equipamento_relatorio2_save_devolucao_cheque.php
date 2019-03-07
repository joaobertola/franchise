<?php

require "../connect/conexao_conecta.php";

$sql_parcela = "UPDATE cs2.cadastro_equipamento_pagamento "
        . "SET devol_doc = '{$_REQUEST['cmd_devol']}' "
        . "WHERE id = '{$_REQUEST['id_pagamento']}'";

if (mysql_query($sql_parcela, $con) or die('ERRO')) {
    echo 1;
} else {
    echo 0;
}
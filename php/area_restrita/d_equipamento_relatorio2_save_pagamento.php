<?php

require "../connect/conexao_conecta.php";

if (isset($_GET['update'])) {


    if (isset($_REQUEST['id']) && $_REQUEST['id'] > 0) { 
        if (isset($_REQUEST['data_pagamento'])) {
            $dataPagamento = date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['data_pagamento'])));
            $set[] = " data_pagamento='{$dataPagamento}'";
        }
        if (isset($_REQUEST['vencimento_pagamento'])) {
            $vencimentoPagamento = date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['vencimento_pagamento'])));
            $set[] = " data_vencimento='{$vencimentoPagamento}'";
        }
        if (isset($_REQUEST['valor_parcela'])) {
            $set[] = "valor_parcela='{$_REQUEST['valor_parcela']}'";
        }
        if (isset($_REQUEST['valor_pagamento'])) {
            $set[] = "valor_pagamento='{$_REQUEST['valor_pagamento']}'";
        }

        if (count($set) > 0) {
            $setQ = implode(',', $set);

            $sql_parcela = "UPDATE cs2.cadastro_emprestimo_franquia SET $setQ WHERE id = '{$_REQUEST['id']}'";


            if (mysql_query($sql_parcela, $con) or die('ERRO')) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
} elseif (isset($_GET['delete'])) {
    print_r($_REQUEST);
    if (isset($_REQUEST['id']) && is_int($_REQUEST['id'])) {
        $sql_parcela = "DELETE FROM  cs2.cadastro_emprestimo_franquia WHERE id = '{$_REQUEST['id']}'";
        if (mysql_query($sql_parcela, $con) or die('ERRO')) {
            echo 1;
        } else {
            echo 0;
        }
    }
}
 
<?php


session_start();

$array_Permissoes = array('163', '4');

if ( in_array($_SESSION['id'], $array_Permissoes) ){

    if (isset($_REQUEST['idSolicitacao'])) {

        require "../connect/conexao_conecta.php";

        $id = $_REQUEST['idSolicitacao'];

        if (isset($_REQUEST['item'])) {
            foreach ($_REQUEST['item'] as $k => $v) {
                foreach ($v as $k1 => $v1) {

                    $item = "SELECT id FROM cs2.franquia_solicitacao_equipamento_itens_auth_financeiro
                                        WHERE id_solicitacao = '$id' AND id_solicitacao_equipamento_itens = '$k' AND qtd = '$k1' ";
                    $resItens = mysql_query($item, $con);
                    if ($q = mysql_fetch_array($resItens)) {
                        $update = "
                            UPDATE cs2.franquia_solicitacao_equipamento_itens_auth_financeiro SET
                            numb_serie = '$v1', data_update = '" . date('Y-m-d H:i:s') . "' WHERE id_solicitacao = $id AND id_solicitacao_equipamento_itens = $k AND qtd = $k1 ";
                        mysql_query($update, $con);
                    } else {
                        $insert = "
                            INSERT INTO cs2.franquia_solicitacao_equipamento_itens_auth_financeiro 
                            (id_solicitacao,id_solicitacao_equipamento_itens,qtd,numb_serie,data_insert)
                            VALUES
                            ($id,$k,$k1,'$v1','" . date('Y-m-d H:i:s') . "')";
                        mysql_query($insert, $con);
                    }
                }
            }
        }
    } else {
        return false;
    }
} else {
    return false;
}
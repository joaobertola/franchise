<?php

require "../connect/conexao_conecta.php";

if (isset($_REQUEST['update'])) {
    $numdoc = $_REQUEST['numdoc'];
    $venc   = $_REQUEST['data_vencimento'];
    $venc   = substr($venc,6,4).'-'.substr($venc,3,2).'-'.substr($venc,0,2);
    /*
    $sql = "UPDATE cs2.titulos 
            SET vencimento_alterado = '$venc'
            WHERE numdoc = '$numdoc'";
    if (mysql_query($sql, $con) or die('ERRO')) {
        echo 1;
        $sql = "INSERT INTO cs2.titulos_movimentacao(numdoc, vencimento) VALUES('$numdoc','$venc')";
        mysql_query($sql, $con);
    } else {
        echo 0;
    }
     * "
     */
    echo 1;
}
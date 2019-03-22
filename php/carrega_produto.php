<?php

require "connect/conexao_conecta.php";

$codigo = $_REQUEST['codigo'];

$sql = "SELECT p.id, descricao, custo_medio_venda, codigo_barra, comissao_valor, pcc.controle_qtd AS controle_qtd "
    . "FROM base_web_control.produto p
    LEFT OUTER base_web_control.produto_configuracoes_comercial pcc
    ON pcc.id_produto = p.id "
    . " WHERE id_cadastro = '62735' AND ( codigo_barra = '$codigo' or identificacao_interna = '$codigo' )";
//echo $sql;
//die;
$res = mysql_query($sql, $con);
if (mysql_num_rows($res) > 0 ){
    $descricao = mysql_result($res,0,'descricao');
    $valor = mysql_result($res,0,'custo_medio_venda');
    $valor = number_format($valor,2,'.','');
    $controleQtd = mysql_result($res,0,'controle_qtd');

    $id = mysql_result($res,0,'id');
    $codigop = mysql_result($res,0,'codigo_barra');
    $comissao_valor = mysql_result($res,0,'comissao_valor');
    $comissao = ($valor * $comissao_valor) / 100;
    $comissao = number_format($comissao,2,',','.');
    echo "$descricao][$valor][$codigop][$comissao][$controleQtd][$id";
}else{
    echo "PRODUTO NAO ENCONTRADO][0.00][0][0][";
    die;
}

?>
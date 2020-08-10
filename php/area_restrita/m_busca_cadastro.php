<?php

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";
require "../connect/funcoes.php";

$nomeFantasia = "Cliente não encontrado.";

$codloja = $_POST['codloja'];

$sql = "SELECT c.nomefantasia FROM cs2.cadastro c WHERE c.codloja = " . $codloja;

$qry = mysql_query($sql, $con);

$total = mysql_num_rows($qry);

if ($total > 0) {

    $result = mysql_fetch_assoc($qry);
    
    $nomeFantasia = $result['nomefantasia'];
    
}

echo $nomeFantasia;

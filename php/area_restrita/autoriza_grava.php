<?php
require "connect/sessao.php";

$cod_loja = $_REQUEST['cod_loja'];
$id_franquia = $_REQUEST['id_franquia'];
$codigo = $_REQUEST['codigo'];

$banco = $_REQUEST['banco'];
$agencia = $_REQUEST['agencia'];
$agencia = str_replace('-', '', $agencia);

$tpconta = $_REQUEST['tpconta'];
$conta = $_REQUEST['conta'];


$conta = str_replace('-', '', $conta);

$titular = $_REQUEST['titular'];
$cpf_cnpj = $_REQUEST['cpf_cnpj'];
$id_conta = $_REQUEST['id_conta'];
$status = $_REQUEST['status'];

if ($_REQUEST['acao'] == "1") {
    $sql = "insert into autorizacao_conta(banco, conta, agencia, titular, cpf_cnpj, data, id_franquia, cod_loja, tpconta)
          values
          ('$banco', '$conta', '$agencia', '$titular', '$cpf_cnpj', now(), '$id_franquia', '$cod_loja', '$tpconta')";
    $qry = mysql_query($sql, $con);

    ?>
    <script>
        alert("Gravado com Sucesso ");
        window.location.href = "../php/painel.php?pagina1=area_restrita/autoriza_conta_listagem.php&codigo=<?=$codigo?>";
    </script>

    <?php
}

if ($_REQUEST['acao'] == "2") {
    $sql = "update autorizacao_conta set
         banco = '$banco', 
		 tpconta = '$tpconta', 
         conta = '$conta', 
         agencia = '$agencia', 
         titular = '$titular', 
         cpf_cnpj = '$cpf_cnpj',
         status   = '$status' 
         where
          id = '$id_conta'   ";
    $q = mysql_query($sql, $con);
    ?>
    <script>
        alert("Alterado com Sucesso ");
        window.location.href = "../php/painel.php?pagina1=area_restrita/autoriza_conta_listagem.php&codigo=<?=$codigo?>";
    </script>

    <?php

}

print_r($_REQUEST);
?>


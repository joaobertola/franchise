<?php

include("../connect/sessao.php");
include("../connect/conexao_conecta.php"); 

$codloja = $_REQUEST['codloja'];
$codigo = $_REQUEST['codigo'];

$cadastro = "UPDATE cadastro SET dt_atualizacao_virtual = NOW(), sitcli = '0' WHERE codloja ='$codloja'";
mysql_query($cadastro,$con) or die("ERRO SQL [$cadastro]");

echo "<script>alert('Cadastro DESBLOQUEADO com sucesso!');</script>'";

mysql_close($con);
echo "<meta http-equiv='refresh' content='0; url=../php/painel.php?pagina1=clientes/a_bloq_acesso.php&codigo=$codigo'>";

?>
<?php

include("../connect/sessao.php");
include("../connect/conexao_conecta.php"); 

$codloja = $_REQUEST['codloja'];
$codigo  = $_REQUEST['codigo'];

$cadastro = "UPDATE cs2.logon SET sitlog = 0 WHERE codloja ='$codloja'";
mysql_query($cadastro,$con) or die("ERRO SQL [$cadastro]");

$cadastro = "INSERT INTO cs2.logon_desbloqueio_confianca
				(
				codloja, data
				)
			 VALUES
			 	(
				$codloja, NOW()
				)";
mysql_query($cadastro,$con) or die("ERRO SQL [$cadastro]");

echo "<script>alert('Acesso DESBLOQUEADO com sucesso!');</script>'";

mysql_close($con);
echo "<meta http-equiv='refresh' content='0; url=../painel.php?pagina1=clientes/a_bloq_acesso.php&codigo=$codigo'>";

?>
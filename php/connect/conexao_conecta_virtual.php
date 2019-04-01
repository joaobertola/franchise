<?php

$con_virtual = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
if (!$con_virtual) {
	echo 'Erro na conexao com o Servidor<br>';
	echo mysql_error();
	exit;
} else {
	$databasex = mysql_select_db("dbsites",$con_virtual);
	if (!$databasex) {
		echo 'Erro na conexão com o Banco de dados - : [conexao_conecta_virtual] <br>';
		echo mysql_error();
	}
}

?>
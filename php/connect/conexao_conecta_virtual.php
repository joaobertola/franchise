<?php

$con_virtual = @mysql_pconnect("10.2.2.7", "root", "cntos43");
if (!$con_virtual) {
	echo 'Erro na conexao com o Servidor<br>';
	echo mysql_error();
	exit;
} else {
	$databasex = mysql_select_db("dbsites",$con_virtual);
	if (!$databasex) {
		echo 'Erro na conexão com o Banco de dados<br>';
		echo mysql_error();
	}
}

?>
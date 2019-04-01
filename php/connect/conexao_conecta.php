<?php

$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

if (!$con) {
	echo 'Erro na conexao com o Servidor 1<br>';
	echo mysql_error();
	exit;
} else {
	$database = mysql_select_db("cs2",$con);
	if (!$database) {
		echo 'Erro na conexão com o Banco de dados 1<br>';
		echo mysql_error();
	}
}

$conemail = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
if (!$conemail) {
//	echo 'Erro na conexão com o Servidor 2<br>';
//	echo mysql_error();
//	exit;
} else {
	$database = mysql_select_db("dbsites",$conemail);
	if (!$database) {
		echo 'Erro na conexão com o Banco de dados 2<br>';
		echo mysql_error();
	}
}


$connf = @mysql_pconnect("10.2.2.19", "csinform", "inform4416#scf");
if (!$connf) {
	echo 'Erro na conexão com o Servidor 3<br>';
	echo mysql_error();
	exit;
} else {
	$database = mysql_select_db("certificadosclientes",$connf);
	if (!$database) {
		echo 'Erro na conexão com o Banco de dados 3<br>';
		echo mysql_error();
	}
}



?>
<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php";

$logon = $_REQUEST['logon'];

$sql = "SELECT a.nomefantasia, b.sitlog FROM cs2.cadastro a
		INNER JOIN cs2.logon b on a.codloja = b.codloja
		WHERE mid(logon,1,LOCATE('S',logon)-1) = '$logon'";
$qry = mysql_query($sql,$con);
$nome = mysql_result($qry,0,'nomefantasia').';'.mysql_result($qry,0,'sitlog');
echo $nome;
?>

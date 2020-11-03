<?php

echo "xxxxx";
exit;


require "../connect/conexao_conecta.php";

$codigo = $_REQUEST['codigo'];

echo $sql = "SELECT nomefantasia FROM cs2.cadastro a
		INNER JOIN cs2.logon b ON a.codloja = b.codloja
		WHERE MID(b.logon,1,LOCATE('S', b.logon) - 1) = $codigo";
//$qr   = mysql_query($sql,$con);
//	$nome = mysql_result($qr,0,'nomefantasia');
//echo $nome;
?>
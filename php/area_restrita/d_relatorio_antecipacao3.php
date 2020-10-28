    	<link href="../../css/style.css" rel="stylesheet" type="text/css" >
		<link href="../../css/tabela.css" rel="stylesheet" type="text/css" >
		<link href="../../css/galeria.css" rel="stylesheet" type="text/css" >

<?php

require "../connect/conexao_conecta.php";

	$protocolo  = $_REQUEST['protocolo'];
	
	$sql = "SELECT a.codloja, CAST(MID(b.logon,1,6) AS UNSIGNED) logon FROM cs2.contacorrente_antecipacao a
			INNER JOIN cs2.logon b ON a.codloja = b.codloja
			WHERE a.protocolo = '$protocolo'
			LIMIT 1";
	
	$qry = mysql_query($sql,$con);
	while ( $reg = mysql_fetch_array($qry) ){
		$codloja = $reg['codloja'];
		$logon = $reg['logon'];
		include("../../../inform/limite_cliente.php");

		include("d_extrato_crediario.php");
		


	}
	

?>
<?php

require "../connect/conexao_conecta.php";

$numdoc    = $_REQUEST['numdoc'];
$cobradora = $_REQUEST['cobradora'];

$sql = "SELECT * FROM cs2.titulos_cobradora WHERE numdoc = '$numdoc' order by id";
$qr = mysql_query($sql,$con) or die ("\n 01: Erro ao pesquisar [atualiza_cobradora_titulos]\n".mysql_error()."\n\n");

if ( mysql_num_rows($qr) > 0 ){
	$id = mysql_result($qr,0,'id');
	
	$sql_u = "UPDATE cs2.titulos_cobradora
				SET
					id_cobradora = '$cobradora',
					data_gravacao = NOW(),
					hora_gravacao = NOW()
			  WHERE id = '$id'";
	$qr2 = mysql_query($sql_u,$con) or die ("\n 02: Erro ao pesquisar [atualiza_cobradora_titulos]\n".mysql_error()."\n\n");		  

}else{

	$sql_u = "INSERT INTO cs2.titulos_cobradora
				(numdoc, id_cobradora, data_gravacao, hora_gravacao )
			  VALUES
			  	('$numdoc' , '$cobradora', NOW() , NOW() )";
	$qr2 = mysql_query($sql_u,$con) or die ("\n 02: Erro ao pesquisar [atualiza_cobradora_titulos]\n".mysql_error()."\n\n");		  


}

?>
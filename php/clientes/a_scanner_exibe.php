<?php

	//require "connect/conexao_conecta.php";
	$codloja = $_REQUEST["codloja"];
	
	//EXECUTA A QUERY 
	$sql = mysql_query("SELECT id, tp_imagem FROM base_inform.cadastro_imagem WHERE codloja = '$codloja' ORDER BY tp_imagem") or die("erro"); 
	
	while ($row = mysql_fetch_row($sql)) {
		
		if ( $row[1] == 'CF' ) echo "Contrato : Frente <br>";
		elseif ( $row[1] == 'TB' ) echo "Tabela de Pre�os<br>";
		elseif ( $row[1] == 'CV' ) echo "Contrato : Verso<br>";
		elseif ( $row[1] == 'DT' ) echo "Termo de Responsabilidade<br>";
		
		echo "<img src=https://www.webcontrolempresas.com.br/franquias/php/clientes/a_scanner_gera.php?id=".$row[0]."' width='100%' height='100%' border='1'>";
		echo "<br><br>";
		
		
		
	}
?>


<?php

require "../connect/conexao_conecta.php";

$placa     = $_REQUEST['placa'];
$idcliente = $_REQUEST['idcliente'];
$comando   = $_REQUEST['comando'];

$sql = "SELECT id FROM base_web_control.cliente_veiculo
		WHERE id_cliente = $idcliente AND placa = '$placa'";
$qr = mysql_query($sql,$con) or die ("\n 01: Erro ao pesquisar [atualiza_veiculo]\n".mysql_error()."\n\n");

if ( $comando = 'N' ){ // Novo Registro
	if ( mysql_num_rows($qr) == 0 ){
		$sql_u = "INSERT INTO base_web_control.cliente_veiculo
					(placa, id_cliente)
				  VALUES
			  		('$placa' , '$idcliente')";
		$qr2 = mysql_query($sql_u,$con) or die ("\n 02: Erro ao pesquisar [atualiza_veiculo]\n".mysql_error()."\n\n");		  
	}
}

?>
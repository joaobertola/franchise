<?php

	require "../connect/conexao_conecta.php";
	
	//RECEBE PARÂMETRO 
	$id = $_REQUEST["id"];
 
	//EXIBE IMAGEM 
	$sql = mysql_query("SELECT id, imagem, tp_imagem FROM base_inform.cadastro_imagem WHERE id= $id",$con);	
	$row = mysql_fetch_array($sql); 
	$tipo = ''; 
	$bytes = $row["imagem"]; 

	//EXIBE IMAGEM 
	header("Content-type: ".$tipo.""); 
	echo $bytes; 
?>


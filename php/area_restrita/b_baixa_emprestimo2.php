<?php
	
	require "connect/sessao.php";
	require "connect/conexao_conecta.php";
	
	$emprestimo  = $_REQUEST['id_emprestimo'];
	$id_franquia = $_REQUEST['id_franquia'];
	$datapg      = $_REQUEST['datapg'];
	$datapg      = substr($datapg,6,4).'-'.substr($datapg,3,2).'-'.substr($datapg,0,2);
	$valorpg     = $_REQUEST['valorpg'];
	$valorpg     = str_replace('.','',$valorpg);
	$valorpg     = str_replace(',','.',$valorpg);

	# Achou o Titulo, baixando o mesmo
	$comando = "UPDATE cs2.cadastro_emprestimo_franquia 
					SET data_pagamento = '$datapg', valor_pagamento = '$valorpg'
				WHERE id = '$emprestimo'";
	$conex = mysql_query($comando, $con) or die ("Erro SQL : $comando");

	echo "<script>alert('Parcela baixada com Sucesso.');</script>";
	mysql_close($con);

	echo "<meta http-equiv='refresh' content=\"0; url=../php/painel.php?pagina1=area_restrita/d_lancamento.php&id_frq=$id_franquia\";>";

?>
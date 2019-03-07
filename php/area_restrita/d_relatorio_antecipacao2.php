<?php

require "../connect/conexao_conecta.php";


	echo "<pre>";
	print_r( $_REQUEST );
	print_r( $_SESSION );
	
	$acao  = $_REQUEST['acao'];
	$datai = $_REQUEST['datai'];
	$datai = substr($datai,6,4).'-'.substr($datai,3,2).'-'.substr($datai,0,2);
	
	$dataf = $_REQUEST['dataf'];
	$dataf = substr($dataf,6,4).'-'.substr($dataf,3,2).'-'.substr($dataf,0,2);
	
	
	if ( $acao == 'antecipado' ){
		
		$sql = "SELECT * FROM cadastro_emprestimo 
				WHERE depositado_cta_cliente = 'S'
				AND data_deposito BETWEEN '$datai' AND '$dataf'
				ORDER BY id";
		
	}
	
	$qry = mysql_query($sql,$con);
	while ( $reg = mysql_fetch_array($qry) ){
		
		echo "dfsdfsfsdfsdf";
		
	}
	

?>
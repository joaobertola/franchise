<?php

//include("connect/sessao_r.php");
include("../connect/conexao_conecta.php");
	

$sql_ver_lote = "SELECT * FROM cs2.nota_xmlgerado WHERE status = 'P'";
$qry_lote = mysql_query($sql_ver_lote, $con);
if ( mysql_num_rows($qry_lote) > 0 ){
	while ( $reg = mysql_fetch_array($qry_lote) ){
		$data = $reg['data'];
		$hora = $reg['hora'];
		$referencia = $reg['referencia'];
		$numero_lote = $reg['numero_lote'];
		$numero_rps_inicio = $reg['numero_rps_inicio'];
		$numero_rps_final = $reg['numero_rps_final'];

        echo"Refer&ecirc;ncia: $referencia<br>
		     Data do arquivo: $data $hora<br>
	         N&uacute;mero do Lote: $numero_lote <br>
    	     N&uacute;mero do RPS Inicial: $numero_rps_inicio <br>
	         N&uacute;mero do RPS Final: $numero_rps_final <br><br>";
	
		echo "Data da Confirma&ccedil;&atilde;o: <input type='text' id='dtconfirmacao' name='dtconfirmacao' /><br><br>";
		echo "<input type='hidden' name='lote_baixar' value = '$numero_lote' >";
		echo "<input type='hidden' name='referencia_baixar' value = '$referencia' >";
		echo "<input type='hidden' name='rps_inicio_baixa' value = '$numero_rps_inicio' >";
		echo "<input type='hidden' name='rps_final_baixa' value = '$numero_rps_final' >";
		
		echo "<input type='button' name='confirma' value = 'Confirmar Lote' onclick='baixa_lote()' >";
	
	}
}else{
	echo "N&Atilde;O existe LOTE a ser LIBERADO !";
}
?>
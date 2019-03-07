<?php
require "connect/sessao.php";

$numdoc = $_GET['numdoc'];

$comando = "SELECT a.codloja, c.razaosoc, a.numdoc, a.vencimento, a.valor, a.datapg, a.valorpg, a.descricao_repasse
						   FROM cs2.titulos_recebafacil a
						   INNER JOIN cs2.logon b on a.codloja=b.codloja
						   INNER JOIN cs2.cadastro c on a.codloja=c.codloja
						   WHERE a.numdoc = '$numdoc'";

$conex = mysql_query($comando, $con);
$matriz = mysql_fetch_array($conex);

$codloja = $matriz['codloja'];

if ( $codloja != '' ){
	echo "TITULO BAIXADO COM SUCESSO";
}else{
	echo "ERRO EO BAIXAR O TITULO";
}
?>
<?php

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$numdoc = $_POST['numdoc'];

$comando = "SELECT codloja FROM cs2.titulos_recebafacil WHERE numdoc = '$numdoc'";
$conex = mysql_query($comando, $con) or die ("Erro SQL : $comando");
$matriz = mysql_fetch_array($conex);
$codloja = $matriz['codloja'];
if ( $codloja != '' ){
	$comando = "UPDATE cs2.titulos_recebafacil set datapg = NULL, valorpg = NULL, descricao_repasse = NULL
				WHERE numdoc = '$numdoc'";
	$conex = mysql_query($comando, $con) or die ("Erro SQL : $comando");
	$mensagem = "RECEBIMENTO DE TÍTULO CANCELADO COM SUCESSO";
}else{
	$mensagem = "ERRO AO CANCELAR O RECEBIMENTO DO TÍTULO ";
}
echo "<script>alert(\" $mensagem \");</script>";
mysql_close($con);
echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_baixa_titulos_crediario_recupere.php\";>";

?>
<?php
/**
 * @file atualiza_doc_indicacao.php
 * @brief
 * @author ARLLON DIAS
 * @date 14/09/2016
 * @version 1.0
 **/

require "../connect/conexao_conecta.php";

$numdoc    = $_REQUEST['num_doc'] == 'DEPOSITO' ? 'DEPOSITO C/C' : $_REQUEST['num_doc'];

$id = $_REQUEST['id'];

if($_REQUEST['fatBoni'] == 1){
	$sql_u = "UPDATE base_web_control.indica_amigo
			SET
				fatura_bonificar = '$numdoc'
		  WHERE id = '$id'";
	$qr2 = mysql_query($sql_u,$con) or die ("\n 02: Erro ao pesquisar [atualiza_cobradora_titulos]\n".mysql_error()."\n\n");

}else{
	$id_func = isset($_REQUEST['id_func'] ) ? 'null' : "'$id_func'";
	$numdoc = $_REQUEST['id_func']=0 ? 'null' : "'$numdoc'";
	echo $sql_u = "UPDATE base_web_control.indica_amigo_log
			SET
				num_doc = $numdoc,
				id_funcionario = $id_func
		  WHERE id = '$id'";
	die;$qr2 = mysql_query($sql_u,$con) or die ("\n 02: Erro ao pesquisar [atualiza_cobradora_titulos]\n".mysql_error()."\n\n");
}

?>
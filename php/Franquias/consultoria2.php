<?php

require "../connect/sessao.php";
include("../connect/conexao_conecta.php");

function fConverteDataGravaBanco($p_data_padrao){
       $dia = substr($p_data_padrao, 0,2);
	   $mes = substr($p_data_padrao, 3,2);
	   $ano = substr($p_data_padrao, 6,9);	
	   $data_bd.=$ano;
	   $data_bd.="-";
	   $data_bd.=$mes;
	   $data_bd.="-";
	   $data_bd.=$dia;
	   return ($data_bd);
} 

$codloja = $_REQUEST['codloja'];
$consultora = $_REQUEST['consultora'];
$data_visita = fConverteDataGravaBanco($_REQUEST['data_visita']);

$idfranqueado = $_REQUEST['idfranqueado'];
$status = $_REQUEST['status'];
$cidade = $_REQUEST['cidade'];

if($_REQUEST['acao'] == '1'){
	
	$sql = "UPDATE  cadastro SET 
				nome_consultoria = '$consultora',
				data_consultoria = '$data_visita'
		    WHERE
				codloja = '{$_REQUEST['codloja']}'";
	
	$qry = mysql_query($sql, $con) or die("Erro SQL : $sql");
	
	header("Location: ../painel.php?pagina1=Franquias/b_relcligeral.php&status=$status&id_franquia=$idfranqueado&cidade=$cidade");
}
?>
<?php

$id = $_REQUEST['id'];
$data_consultoria = $_REQUEST['data_consultoria'];
$realizada = $_REQUEST['realizada'];
$cobrar_consultoria = $_REQUEST['cobrar_consultoria'];

if ( $data_consultoria ){
    $data_consultoria = substr($data_consultoria,6,4).'-'.substr($data_consultoria,3,2).'-'.substr($data_consultoria,0,2);
    $data_consultoria = "'$data_consultoria'";
}else
    $data_consultoria = 'NULL';
	
$idFuncionario = $_REQUEST['consultora'];

include("../connect/conexao_conecta.php");

if ( $idFuncionario == 0 ){
    $consultora = 'NULL';
}else{
    $sqlFunc = "SELECT substring_index(nome, ' ', 1) AS nome_consultor FROM cs2.funcionario WHERE id = '$idFuncionario'";
    $resFunc = mysql_query($sqlFunc,$con);
    $consultora = mysql_result($resFunc, 0, 'nome_consultor');
}

$sql_update =  "UPDATE base_inform.cadastro_imagem 
                SET 
                        data_consultoria = $data_consultoria,
                        consultora = '$consultora',
                        id_funcionario = '$idFuncionario',
                        consultoria_realizada = '$realizada',
                        cobrar_consultoria = '$cobrar_consultoria'
                WHERE id = $id";
$qry_update = mysql_query($sql_update, $con) or die("Erro SQL:  $sql_update");

echo "Registro gravado com sucesso";

?>
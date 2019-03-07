<?php 

$id_franquia = $_REQUEST['id_franquia'];
$id_requisic = $_REQUEST['id'];

// Itens
$sql = "DELETE FROM cs2.solicitacao_valores_item WHERE id_sol = '$id_requisic'";
$qry = mysql_query($sql, $con) or die("Erro SQL: $sql");

// Arquivos
$sql = "SELECT
			id, nome_arquivo
		FROM cs2.solicitacao_valores_arq 
		WHERE id_sol = $id_requisic";
$qry = mysql_query($sql, $con) or die("Erro SQL: $sql");
if ( mysql_num_rows($qry) > 0 ){
	$nome_arquivo = mysql_result($qry,0,'nome_arquivo');
	$sql_d = "DELETE from cs2.solicitacao_valores_arq
			  WHERE id_sol = $id_requisic";
	$qry_d = mysql_query($sql_d, $con) or die("Erro SQL: $sql_d");
	// apagando o arquivo no servidor;
	unlink("../area_restrita/upload/arquivo_solicitacao/".$nome_arquivo);
}

// Excluindo a Mae
$sql = "DELETE FROM cs2.solicitacao_valores WHERE id = '$id_requisic'";
$qry = mysql_query($sql, $con) or die("Erro SQL: $sql");


echo "<script>alert('Registro EXCLUIDO com sucesso.')</script>";

echo "<meta http-equiv='refresh' content=\"0; url= painel.php?pagina1=area_restrita/a_solicitacao_valores_rel.php&rel_franquia=$id_franquia\";>";


?>
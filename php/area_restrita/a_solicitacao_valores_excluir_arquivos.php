<?php
error_reporting (E_ALL ^ E_NOTICE);

$id_arq      = $_REQUEST['id'];
$id_franquia = $_REQUEST['id_franquia'];

$sql = "SELECT
			a.id, a.nome_arquivo, b.id_franquia
		FROM cs2.solicitacao_valores_arq a
		INNER JOIN cs2.solicitacao_valores b ON a.id_sol = b.id
		WHERE a.id = $id_arq AND b.id_franquia = $id_franquia";
$qry = mysql_query($sql) or die("Erro SQL: $sql");
if ( mysql_num_rows($qry) > 0 ){
	$nome_arquivo = mysql_result($qry,0,'nome_arquivo');
	$sql_d = "DELETE from cs2.solicitacao_valores_arq
			  WHERE id = $id_arq";
	$qry_d = mysql_query($sql_d) or die("Erro SQL: $sql_d");
	// apagando o arquivo no servidor;
	unlink("../area_restrita/upload/arquivo_solicitacao/".$nome_arquivo);
}

echo "<script>alert('Registro EXCLUIDO com sucesso.')</script>";

echo "<meta http-equiv='refresh' content=\"0; url= painel.php?pagina1=area_restrita/a_solicitacao_valores_altera.php&id=$id_arq&id_franquia=$id_franquia\";>";

?>
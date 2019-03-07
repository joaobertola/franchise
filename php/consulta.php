<?php
//require "connect/sessao.php";
require "connect/conexao_conecta.php";
$categoria = $_POST['categoria'];

$categoria = addslashes($_GET["id"]);

$nsql = "SELECT a.id, a.nome, a.qtd, a.tpcons, a.tpcons2, a.qtd2, 
				b.nome as descricao, c.nome as descricao2, a.mensagem 
		FROM tabela_valor a
		LEFT OUTER JOIN cs2.valcons b on a.tpcons = b.codcons 
		LEFT OUTER JOIN cs2.valcons c on a.tpcons2 = c.codcons 
		WHERE categoria = '$categoria' AND mostrar = 0 
		ORDER BY qtd";

$comando = mysql_query($nsql, $con);
while($row = mysql_fetch_assoc($comando))
{
	$valor_pct = $row["nome"];
	$nome1 	   = $row['descricao'];
	$qtd1      = $row['qtd'];
	$msg 	   = $row['mensagem'];
	$nome2 = $row['descricao2'];
	$qtd2 = $row['qtd2'];

	$txt = "R$ $valor_pct ";
	
	if ( $qtd2 > 0 ) $txt .= "Gratuitas: $nome2 : $qtd2 - $nome1 : $qtd1";
	else $txt .= "$msg";
	
	echo $txt."|".$row["id"].",";
}
?>

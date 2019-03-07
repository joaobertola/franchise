<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php";

$uf = addslashes($_GET["id"]); // pegamos o id passado pelo select
$comando = mysql_query("SELECT * FROM cidade WHERE uf = '$uf' ORDER BY estado"); // selecionamos todas as subcategorias que pertencem à categoria selecionada
while( $row = mysql_fetch_assoc($comando) )
{
	echo $row["estado"] . "|" . $row["id"] . ","; // apresentamos cada subcategoria dessa forma "NOME|CODIGO,NOME|CODIGO,NOME|CODIGO,...", exatamente da maneira que iremos tratar no JavaScript
}
?>

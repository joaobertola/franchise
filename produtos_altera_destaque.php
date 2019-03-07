<?php
include ("php/connect/conexao_conecta.php");

$valor = str_replace(".", "", $_GET['valor']);
$valor = str_replace(",", ".", $valor);

$sel = " UPDATE tabela_clientes SET {$_GET['campo']} = '{$valor}' WHERE codigo ='{$_GET['id']}'";
$sql = mysql_query($sel)or die(mysql_error());
?>
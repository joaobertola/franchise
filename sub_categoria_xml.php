<?php
include ("php/connect/conexao_conecta.php");

#seleciona todas as subcategorias da categoria escolhida
$sqlSltCategoria  = " SELECT id, nome AS cidade";
$sqlSltCategoria .= " FROM subcategoria";
$sqlSltCategoria .= " WHERE";
$sqlSltCategoria .= " categoria = '{$_GET['idEstado']}'";
$sqlSltCategoria .= " ORDER BY categoria";
$rstSltCategoria = mysql_query($sqlSltCategoria) or die(mysql_error());

header("Content-Type: application/xml");
$xml  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\r\n";
$xml .= "<cidades>\r\n";

$totalBanco = 0;

while($rowsCategoria = mysql_fetch_array($rstSltCategoria))
{
	
	$xml .= "\t<cidade>\r\n";
	$xml .= "\t\t<id>{$rowsCategoria['cidade']}</id>\r\n";
	$xml .= "\t\t<nome>{$rowsCategoria['cidade']}</nome>\r\n";
	$xml .= "\t</cidade>\r\n";
}

$xml .= "</cidades>\r\n";
echo $xml;
?>
<?php
//RECEBE PAR�METRO 

$codloja = $_REQUEST["codloja"]; 

//CONECTA AO MYSQL 
//require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$sql = mysql_query("SELECT logomarca AS foto FROM cs2.cadastro WHERE codloja = $codloja", $con);
$row = mysql_fetch_array($sql);
$tipo = $row["tipofoto"]; 
$bytes = $row["foto"]; 
//EXIBE IMAGEM 
header("Content-type: ".$tipo.""); 
echo $bytes; 
?>
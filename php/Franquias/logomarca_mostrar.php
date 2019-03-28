<?php
//RECEBE PARÂMETRO 
$id = $_REQUEST["codloja"]; 

//CONECTA AO MYSQL 
include ("../connect/sessao.php");
include ("../connect/conexao_conecta.php";

//EXIBE IMAGEM 
$sql = mysql_query("SELECT logomarca AS foto FROM cs2.cadastro WHERE codloja = ".$id."",$con); 

$row = mysql_fetch_array($sql); 
$tipo = $row["tipofoto"]; 
$bytes = $row["foto"]; 
//EXIBE IMAGEM 
header("Content-type: ".$tipo.""); 
echo $bytes;
?>
<?php

//RECEBE PAR�METRO 
$idx = $_GET["idx"]; 

//CONECTA AO MYSQL 
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

//EXIBE IMAGEM 
$sql = "SELECT foto,tipofoto FROM franquia WHERE id = $idx";
$qry_sql = mysql_query($sql,$con); 

$row = mysql_fetch_array($qry_sql); 
$tipo = $row["tipofoto"]; 
$bytes = $row["foto"]; 

//EXIBE IMAGEM 
header("Content-type: ".$tipo.""); 
echo $bytes;

?>

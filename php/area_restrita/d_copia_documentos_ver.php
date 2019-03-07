<?php

//RECEBE PARAMETRO

$idx = $_GET["id"];

//CONECTA AO MYSQL
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

//EXIBE IMAGEM 
$sql = "SELECT imagem FROM base_inform.cadastro_imagem WHERE id =  $idx";
$qry_sql = mysql_query($sql,$con);

$row = mysql_fetch_array($qry_sql);
$tipo = "";
$bytes = $row["imagem"];
//EXIBE IMAGEM
header("Content-type: ".$tipo."");
echo $bytes;
?>
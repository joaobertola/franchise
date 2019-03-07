<?php
$usermy="csinform";
$passwordmy="inform4416#scf";
$nomedb="base_web_control";

$conexao=@mysql_pconnect("10.2.2.3",$usermy,$passwordmy)or die ("Problemas ao conectar no servidor de banco de dados".mail("lucianomancini@gmail.com","problemas na conexao com mysql"," From:lucianomancini@gmail.com"));
$bd=mysql_select_db($nomedb,$conexao) or die("Nao foi posivel selecionar o banco de dados contate o administrador erro 30");
?>
<?php
require "sessao.php";

$usuario = $_SESSION['ss_name'];

$senha = $_POST["senha"];

if (($usuario!="")||($senha!="")) {
	include "conexao_conecta.php";
	$res = mysql_query("select * from franquia where usuario='$usuario' and senha_restrita='$senha'",$con);
	$linha = mysql_num_rows($res);
	$matriz = mysql_fetch_array($res); 
	$res = mysql_close($con);
	if ($linha!=0) {
		$login		= $matriz['usuario'];
		$tipo		= $matriz['tipo'];
		
		session_start();
		
		$_SESSION['ss_restrito']= $login;
		
//		session_register("ss_tipo");
		header("Location: ../painel.php");
	} else {
		print"<script>alert(\"Login ou senha incorreta!\");history.back();</script>";
		exit;
	}
} else {
	header("Location: ../../erro/index.php");
}

?>
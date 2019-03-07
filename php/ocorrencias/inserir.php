<?php
include "config.php";
require "../connect/sessao.php";
require "../connect/conexao_conecta.php"; 

$id 		= $_POST['id'];
$codigo 	= $_POST['codigo'];
$nomec 		= $_POST['nomec'];
$franquia   = $_POST['franquia'];
$tipo_ocorr = $_POST['tipo_ocorr'];
$atendente  = $_POST['atendente'];
$ocorrencia = $_POST['ocorrencia'];
$id_atendente = $_POST['atendente2'];

//coloca para franquia matriz qdo feito por determinados c�digos
if (($franquia == 0) || ($franquia == 4) || ($franquia == 5) || ($franquia == 46) || ($franquia == 59) || ($franquia == 60) || ($franquia == 74) || ($franquia == 163)) $franquia = 1;

//isto serve para incrementar o �ltimo valor do protocolo
$resposta = mysql_query("SELECT (protocolo + 1) as protocolo FROM cs2.controle", $con);
$registro = mysql_fetch_array($resposta);
$prot	= $registro["protocolo"];

//$conexao = mysql_connect($db[host], $db[user], $db[senha]);
if($con) {
	$sql = "INSERT INTO cs2.ocorrencias (codigo, nomec, franquia, tipo_ocorr, atendente, ocorrencia, data, protocolo, id_atendente) VALUES('$codigo', '$nomec', '$franquia', '$tipo_ocorr', '$atendente' , '$ocorrencia', now(), '$prot', '$id_atendente')";
	
	if($codigo == "" || $ocorrencia == "" || $id_atendente == "")
		echo "<script>alert(\"Não foi possível enviar sua mensagem. \\nVolte e complete o formulário corretamente!\"); window.location = 'javascript:history.back(-1)';</script>";
	else $query = mysql_query( $sql, $con);
	
	if ($query) { 
	//insert na controle
		$conecta="update cs2.controle set protocolo = protocolo + 1";
		$resposta = mysql_query($conecta, $con);
		
		header("Location: ../painel.php?pagina1=ocorrencias/mensagens.php&codloja=$codigo&mens=1");
		
		//echo("<script>alert(\"$thanks_msg\");window.location = '../painel.php?pagina1=ocorrencias/mensagens.php&codloja=$codigo';	< /script>");
		
		//header()/
	} else 
		echo("<script>alert(\"$error_msg \n <b>ERRO:</b>".mysql_error()."\");window.location = '../painel.php?pagina1=ocorrencias/a_ocorrencia.php;</script>");
	
} else echo("<b>Erro na tentativa de conex�o.</b> ".mysql_error()."\n");
mysql_close($con);
?>
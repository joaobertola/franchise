<?
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

//coloca para franquia matriz qdo feito por determinados códigos
if (($franquia == 0) || ($franquia == 4) || ($franquia == 5) || ($franquia == 46) || ($franquia == 59) || ($franquia == 60) || ($franquia == 74)) $franquia = 1;

//isto serve para incrementar o último valor do protocolo
$resposta = mysql_query("SELECT (protocolo + 1) as protocolo FROM cs2.controle");
$registro = mysql_fetch_array($resposta);
$prot	= $registro["protocolo"];

$conexao = mysql_connect($db[host], $db[user], $db[senha]);
if($conexao) {
	$sql = "INSERT INTO $table_name (codigo, nomec, franquia, tipo_ocorr, atendente, ocorrencia, data, protocolo, id_atendente) VALUES('$codigo', '$nomec', '$franquia', '$tipo_ocorr', '$atendente' , '$ocorrencia', now(), '$prot', '$id_atendente')";
	
	if($codigo == "" || $ocorrencia == "" || $id_atendente == "")
		echo "<script>alert(\"Não foi possível enviar sua mensagem. \\nVolte e complete o formulário corretamente!\"); window.location = 'javascript:history.back(-1)';</script>";
	else $query = mysql_db_query($db[nome], $sql, $conexao);
	
	if ($query) { 
	//insert na controle
		$conecta="update cs2.controle set protocolo = protocolo + 1";
		$resposta = mysql_query($conecta, $con);
		
		echo("<script>alert(\"$thanks_msg\");window.location = '../painel.php?pagina1=franqueado_jr/ocorrencia_mensagens.php&codloja=$codigo';	</script>");
	} else 
		echo("<script>alert(\"$error_msg \n <b>ERRO:</b>".mysql_error()."\");window.location = '../painel.php?pagina1=franqueado_jr/ocorrencia;</script>");
	
} else echo("<b>Erro na tentativa de conexão.</b> ".mysql_error()."\n");
mysql_close($con);
?>
<?php 
$date = date("d/m/Y h:i");

include ("smtp.class.php");

$nome = $_POST['nome'];
$empresa = $_POST['empresa'];
$email = $_POST['email'];
$fone = $_POST['fone'];
$cel = $_POST['cel'];
$mensagem = $_POST['mensagem'];


$assunto="Inform System - nao cliente";
$configuracao="
\nNome: $nome
\nEmpresa: $empresa
\nE-Mail: $email
\nFone: $fone\ Celular: $cel
\nMensagem: $mensagem
\nEnviado em: $date";


//ENVIO DA MENSAGEM ORIGINAL

$host = "10.2.2.7"; // host do servidor SMTP 
$smtp = new Smtp($host);
$smtp->user = "administrativo@informsystem.com.br"; // usuario do servidor SMTP 
$smtp->pass = "informbrasil"; // senha dousuario do servidor SMTP
$smtp->debug = true; // ativar a autenticaç SMTP
$from = "administrativo@informsystem.com.br";
	
if($smtp->Send('administrativo@informsystem.com.br', $from, $assunto, $configuracao )) $mensagem = "OK";
else $mensagem = "ERR";

if($mensagem=="OK"){
	echo "<script>window.location='index.php?web=sucesso'</script>";
}else {
	echo "$date Erro ao enviar o e-mail para $to \n";
	echo "<script>history.back()</script>";
}

?>

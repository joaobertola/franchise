<?php 
$date = date("d/m/Y h:i");

include ("smtp.class.php");

$departamento = $_POST['departamento'];
$codigo = $_POST['codigo'];
$contato = $_POST['contato'];
$email = $_POST['email'];
$fone = $_POST['fone'];
$cel = $_POST['cel'];
$mensagem = $_POST['mensagem'];

$assunto="Inform System - Cliente";
$configuracao="
\nDepartamento: $departamento
\nCodigo: $codigo
\nContato: $contato
\nE-Mail: $email
\nFone: $fone\ Celular: $cel
\nMensagem: $mensagem
\nEnviado em: $date";

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
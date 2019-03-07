<?php

echo "Id: $id_franquia <br>";

echo "Remetente: $remetente  Senha: $senha";

exit;




require "../connect/conexao_conecta.php"; 

include ("smtp.class.php");

$data = date("Y-m-d H:i:s");



if ( $id_franquia == 163 ){
	$sql_email = "select vpopmail.webcontrolempresas_com_br Where pw_name = '$remetente' and pw_clear_passwd = '$senha'";
	
}

if ($frq == 'todos') {
	
	$sql = "select id, email, razaosoc from franquia where sitfrq <> 1 and id_franquia_master = 0 and classificacao = 'M' and LENGTH(email)>0 
			order by id";
	$ql = mysql_query($sql, $con);
	$linha = mysql_num_rows ($ql);
	while($registro = mysql_fetch_array($ql)){
		$email = $registro['email'];
		$razao = $registro['razaosoc'];
		
		$configuracao="<br>Nome: $nome <br>Contato: $contato <br>Bairro: $bairro - CEP: $cep <br>Cidade: $cidade<br>UF: $uf<br>E-Mail: $email<br>Fone: $fone \ Celular: $cel<br>CPF: $cpf / Cnpj: $cnpj <br>Mensagem: $mensagem
<p>Enviado em: $date";
		
		$host = "10.2.2.7"; // host do servidor SMTP 
		$smtp = new Smtp($host);
		$smtp -> user  = "administrativo@webcontrolempresas.com.br"; // usuario do servidor SMTP
		$smtp -> pass  = "informbrasil"; // senha dousuario do servidor SMTP
		$smtp -> debug = true; // ativar a autentica� SMTP
		$from = "administrativo@webcontrolempresas.com.br";
		if($smtp->Send($email, $from, $assunto, $conteudo )) $mensagem = "OK";
		else $mensagem = "ERR";
		echo "$mensagem :  $razao <br>";
	}
}else{
	$sql = "select id, email, razaosoc from franquia where sitfrq <> 1 and id_franquia_master = 0 and LENGTH(email)>0 and id = $frq";
	$ql = mysql_query($sql, $con);
	$linha = mysql_num_rows ($ql);
	while($registro = mysql_fetch_array($ql)){
		$email = $registro['email'];
		$razao = $registro['razaosoc'];
		
		$host = "10.2.2.7"; // host do servidor SMTP 
		$smtp = new Smtp($host);
		$smtp -> user  = "administrativo@webcontrolempresas.com.br"; // usuario do servidor SMTP
		$smtp -> pass  = "informbrasil"; // senha dousuario do servidor SMTP
		$smtp -> debug = true; // ativar a autentica� SMTP
		$from = "administrativo@webcontrolempresas.com.br";
		if($smtp->Send($email, $from, $assunto, $conteudo )) $mensagem = "OK";
		else $mensagem = "ERR";
		echo "$mensagem :  $razao -> $email<br>";
	}
	
}
echo "<script>alert(\"Email (s) enviado com sucesso!\");</script>";
mysql_close($con);
//echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_email.php&mail=area_restrita/d_lista_email.php \";>";
?>


<?php 
$date = date("d/m/Y h:i"); //data para envio do email
$data = date("Y-m-d"); //data para incluir no banco


//pega os parámetros
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$naturalidade = $_POST['nacionalidade'];
$endereco = $_POST['logradouro'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$bairro = $_POST['bairro'];
$log = $endereco.", ".$numero;
$cidade = $_POST['localidade'];
$uf = $_POST['uf'];
$cep = $_POST['cep'];
$email = $_POST['email'];
$tempo = $_POST['tempo'];
$interesse = $_POST['interesse'];
$obs = $_POST['obs'];
$fone = $_POST['fone'];
$xfone = $_POST['fone'];
$cel = $_POST['cel'];
$xcel = $_POST['cel'];
$cel2 = $_POST['cel2'];
$xcel2 = $_POST['cel2'];


//trata as variáveis
$cpf=str_replace("/","",$cpf);
$cpf=str_replace("-","",$cpf);
$cpf=str_replace(".","",$cpf);
$cpf=trim($cpf);

$fone=str_replace("(","",$fone);
$fone=str_replace(")","",$fone);
$fone=str_replace("-","",$fone);
$fone=str_replace(" ","",$fone);
$cel=str_replace("(","",$cel);
$cel=str_replace(")","",$cel);
$cel=str_replace("-","",$cel);
$cel=str_replace(" ","",$cel);

$cel2=str_replace("(","",$cel2);
$cel2=str_replace(")","",$cel2);
$cel2=str_replace("-","",$cel2);
$cel2=str_replace(" ","",$cel2);
$erro = '';

if ( empty($nome) ) $erro .= "NOME INVÁLIDO >> ";
if ( empty($cpf) ) $erro .= "CPF INVÁLIDO >> ";
if ( $cpf == '00000000000' ) $erro .= "CPF INVÁLIDO >> ";
elseif ( $cpf == '11111111111' ) $erro .= "CPF INVÁLIDO >> ";
elseif ( $cpf == '22222222222' ) $erro .= "CPF INVÁLIDO >> ";
elseif ( $cpf == '33333333333' ) $erro .= "CPF INVÁLIDO >> ";
elseif ( $cpf == '44444444444' ) $erro .= "CPF INVÁLIDO >> ";
elseif ( $cpf == '55555555555' ) $erro .= "CPF INVÁLIDO >> ";
elseif ( $cpf == '66666666666' ) $erro .= "CPF INVÁLIDO >> ";
elseif ( $cpf == '77777777777' ) $erro .= "CPF INVÁLIDO >> ";
elseif ( $cpf == '88888888888' ) $erro .= "CPF INVÁLIDO >> ";
elseif ( $cpf == '99999999999' ) $erro .= "CPF INVÁLIDO >> ";

if ( empty($endereco) ) $erro .= "ENDEREÇO INVÁLIDO >> ";
 if ( empty($fone) ) $erro .= "TELEFONE INVÁLIDO >> ";
  if ( empty($email) ) $erro .= "E-MAIL INVÁLIDO >> ";

if ( ! empty($erro) ){
	echo '<script>alert("'.$erro.'");history.back()</script>';
	exit;
}
require "restrito/conexao_conecta.php";

#  gravando nome dos pretendentes a franquia para consulta
$sql =" SELECT count(*) qtd from base_inform.Nome_Brasil 
		WHERE Nom_CPF = '$cpf' and Nom_Tp = '0' and  Nom_Nome = '$nome'";
$qr = mysql_query($sql,$con) or die ("Erro na selecionar o ID nome");
$qtd = mysql_result($qr,0,'qtd');
if($qtd==0){
	$sql = "INSERT INTO base_inform.Nome_Brasil(Origem_Nome_Id,Nom_CPF,Nom_Tp,Nom_Nome)
			VALUES('2',$cpf,'0','$nome')";
	$qr=mysql_query($sql,$con) or die ("Erro na gravar o Nome $sql");
}

#  gravando endereço dos pretendentes a franquia para consulta
if ( !empty($id_tp_log) or ($id_tp_log > 0) ){
	# VERIFICANDO SE EXISTE O REGISTRO OU O ENDEREÇO
	$sql = "SELECT count(*) qtd 
			FROM base_inform.Endereco
			WHERE CPF='$cpf' AND logradouro = '$endereco'";
	$qr = mysql_query($sql,$con) or die ("Erro !!!  2532  $sql");
	$qtd = mysql_result($qr,0,'qtd');
	if($qtd == 0){
		# NÃO EXISTE REGISTRO OU O LOGRADOURO É NOVO, CADASTRAR UM NOVO
		$sql = "INSERT INTO base_inform.Endereco(CPF,Tipo,Origem_Nome_id,Tipo_Log_id,logradouro,
										numero,complemento,bairro,cidade,uf,cep,data_cadastro)
				VALUES('$cpf','0','2','$id_tp_log','$endereco',
					   '$numero','$complemento','$bairro','$cidade','$uf','$cep',now())";
		$qr = mysql_query($sql,$con) or die (mysql_error()." $sql --- Erro ao inserir");
	}
}

# Gravando pretendentes a franquia
$sql = "INSERT INTO cs2.pretendentes
			(
				nome, cpfcnpj, naturalidade, endereco, cidade, uf, fone, celular, complemento,
				email, tp_residencia, cid_atuacao, obs, data_envio, celular2, cep, bairro
			) 
		VALUES
			( '$nome', '$cpf', '$naturalidade', '$log', '$cidade', '$uf', '$fone', '$cel', '$complemento',
			  '$email', '$tempo', '$interesse', '$obs', '$data', '$cel2', '$cep', '$bairro' )";
$qr = mysql_query($sql, $con) or die ("impossivel adicionar novo usuario");

include ("smtp.class.php");

$assunto="Inform System - Pretendentes de Franquia";
$configuracao="<br>Nome: $nome 
<br>CPF ou CNPJ: $cpf 
<br>Nacionalidade: $nacionalidade 
<br>Endereço: $endereco, $numero
<br>Bairro: $bairro
<br>Complemento: $complemento 
<br>Cidade: $cidade - UF: $uf
<br>Cep: $cep
<br>Telefone Fixo: $xfone 
<br>Celular 1 : $xcel
<br>Celular 2 : $xcel2
<br>E-mail: $email 
<br>Tempo que reside na cidade acima citada: $tempo 
<br>Cidade / Região de interesse para atuação da franquia: $interesse 
<p>Observações: $obs
<p>Enviado em: $date";

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

<?php 
$date = date("d/m/Y h:i");

include ("smtp.class.php");

$cnpj = $_POST['cnpj'];
$cnpj = str_replace("/","",$cnpj);
$cnpj = str_replace("-","",$cnpj);
$cnpj = str_replace(".","",$cnpj);
$cnpj = trim($cnpj);

$nome = $_POST['nome'];

$id_tp_log = $_POST['id_tp_log'];
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$cep = $_POST['cep'];
$cidade = $_POST['cidade'];
$uf = $_POST['uf'];
$email = $_POST['email'];

$fone = $_POST['fone'];
$fone=str_replace("(","",$fone);
$fone=str_replace(")","",$fone);
$fone=str_replace("-","",$fone);

$cel = $_POST['cel'];
$cel=str_replace("(","",$cel);
$cel=str_replace(")","",$cel);
$cel=str_replace("-","",$cel);

$cpf = $_POST['cpf'];
$cpf=str_replace("/","",$cpf);
$cpf=str_replace("-","",$cpf);
$cpf=str_replace(".","",$cpf);
$cpf=trim($cpf);
$contato = $_POST['contato'];

$mensagem = $_POST['mensagem'];

$erro = '';

if ( empty($nome) ) $erro .= "NOME INVÁLIDO >> ";
if ( empty($contato) ) $erro .= "CONTATO INVÁLIDO >> ";
if ( empty($cpf) && empty($cnpj) ) $erro .= "INFORME O CNPJ da Empresa ou CPF do CONTATO >> ";
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

if ( ! empty($erro) ){
	echo '<script>alert("'.$erro.'");history.back()</script>';
	exit;
}
require "restrito/conexao_conecta.php";

if ( !isset($cnpj) ){
	#  gravando nome dos pretendentes a franquia para consulta
	$sql =" SELECT count(*) qtd from base_inform.Nome_Brasil 
			WHERE Nom_CPF = '$cnpj' and Nom_Tp = '1' and  Nom_Nome = '$nome'";
	$qr = mysql_query($sql,$con) or die ("Erro na selecionar o ID nome");
	$qtd = mysql_result($qr,0,'qtd');
	if($qtd==0){
		$sql = "INSERT INTO base_inform.Nome_Brasil(Origem_Nome_Id,Nom_CPF,Nom_Tp,Nom_Nome)
				VALUES('2',$cnpj,'1','$nome')";
		$qr=mysql_query($sql,$con) or die ("Erro na gravar o Nome $sql");
	}
}
if ( !isset($cpf) ){
	#  gravando nome dos pretendentes a franquia para consulta
	$sql =" SELECT count(*) qtd from base_inform.Nome_Brasil 
			WHERE Nom_CPF = '$cpf' and Nom_Tp = '0' and  Nom_Nome = '$contato'";
	$qr = mysql_query($sql,$con) or die ("Erro na selecionar o ID nome");
	$qtd = mysql_result($qr,0,'qtd');
	if($qtd==0){
		$sql = "INSERT INTO base_inform.Nome_Brasil(Origem_Nome_Id,Nom_CPF,Nom_Tp,Nom_Nome)
				VALUES('2',$cpf,'0','$contato')";
		$qr=mysql_query($sql,$con) or die ("Erro na gravar o Nome $sql");
	}
}

if ( !isset($cnpj) ){
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
					VALUES('$cpf','1','2','$id_tp_log','$endereco',
						   '$numero','$complemento','$bairro','$cidade','$uf','$cep',now())";
			$qr = mysql_query($sql,$con) or die (mysql_error()." $sql --- Erro ao inserir");
		}
	}
}

$assunto="Inform System - Associe-se";
$configuracao="<br>Nome: $nome <br>Contato: $contato <br>Bairro: $bairro - CEP: $cep <br>Cidade: $cidade<br>UF: $uf<br>E-Mail: $email<br>Fone: $fone \ Celular: $cel<br>CPF: $cpf / Cnpj: $cnpj <br>Mensagem: $mensagem
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
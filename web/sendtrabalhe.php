<?php 
$date = date("d/m/Y h:i");

include ("smtp.class.php");

$AreaInteresse = $_POST['AreaInteresse'];
$SalarioPretendido = $_POST['SalarioPretendido'];
$Nome = $_POST['Nome'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];

$EstadoCivil = $_POST['EstadoCivil'];
$datanascimento = $_POST['datanascimento'];
$sexo = $_POST['Sexo'];

$Nacionalidade = $_POST['Nacionalidade'];
$Naturalidade = $_POST['Naturalidade'];
$RecadoCom = $_POST['RecadoCom'];
$EstadoCivil = $_POST['EstadoCivil'];

$id_tp_log = $_POST['id_tp_log'];
$Endereco = $_POST['Endereco'];
$numero = $_POST['numero'];
$Complemento = $_POST['Complemento'];
$Bairro = $_POST['Bairro'];
$CEP = $_POST['CEP'];
$CEP = str_replace("-","",$CEP);
$Cidade = $_POST['Cidade'];
$uf = $_POST['uf'];
$email = $_POST['email'];
$fone = $_POST['fone'];
$cel = $_POST['cel'];
$trabalhou = $_POST['trabalhou'];
$comosoube = $_POST['comosoube'];

$fone=str_replace("(","",$fone);
$fone=str_replace(")","",$fone);
$fone=str_replace("-","",$fone);

$cel=str_replace("(","",$cel);
$cel=str_replace(")","",$cel);
$cel=str_replace("-","",$cel);

$cpf=str_replace("/","",$cpf);
$cpf=str_replace("-","",$cpf);
$cpf=str_replace(".","",$cpf);
$cpf=trim($cpf);


$erro = '';

if ( empty($Nome) ) $erro .= "NOME INVÁLIDO >> ";
if ( empty($cpf) && empty($cnpj) ) $erro .= "INFORME O Numero do CPF >> ";
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
if ( empty($id_tp_log) ) $erro .= "Tipo do Endereço INVÁLIDO >> ";
if ( empty($Endereco) ) $erro .= "ENDEREÇO INVÁLIDO >> ";
if ( empty($fone) ) $erro .= "TELEFONE INVÁLIDO >> ";

if ( ! empty($erro) ){
	echo '<script>alert("'.$erro.'");history.back()</script>';
	exit;
}

require "restrito/conexao_conecta.php";

#  gravando nome dos pretendentes a franquia para consulta
$sql =" SELECT count(*) qtd from base_inform.Nome_Brasil 
		WHERE Nom_CPF = '$cpf' and Nom_Tp = '0' and  Nom_Nome = '$Nome'";
$qr = mysql_query($sql,$con) or die ("Erro na selecionar o ID nome");
$qtd = mysql_result($qr,0,'qtd');
if($qtd==0){
	$sql = "INSERT INTO base_inform.Nome_Brasil(Origem_Nome_Id,Nom_CPF,Nom_Tp,Nom_Nome)
			VALUES('2',$cpf,'0','$Nome')";
	$qr=mysql_query($sql,$con) or die ("Erro na gravar o Nome $sql");
}

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
				VALUES('$cpf','1','2','$id_tp_log','$Endereco',
					   '$numero','$Complemento','$Bairro','$Cidade','$uf','$CEP',now())";
		$qr = mysql_query($sql,$con) or die (mysql_error()." $sql --- Erro ao inserir");
	}
}

$assunto="Inform System - Trabalhe Conosco";
$configuracao="
<br>Nome: $Nome
<br>Area de Interesse: $AreaInteresse
<br>Salario Pretendido: $SalarioPretendido
<br>Endereco: $Endereco
<br>Numero: $numero
<br>Complemento: $Complemento
<br>Bairro: $Bairro
<br>Cidade: $Cidade
<br>UF: $uf
<br>CEP: $CEP
<br>E-Mail: $email
<br>Fone: $fone
<br>Celular: $cel
<br>Recado: $RecadoCom
<br>CPF: $cpf
<br>RG: $rg
<br>Nacionalidade: $Nacionalidade
<br>Naturalidade: $Naturalidade
<br>Estado Civil: $EstadoCivil

<br>Ja trabalhou na Inform System?: $trabalhou
<br>Como soube?: $comosoube
<br>Enviado em: $date";


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
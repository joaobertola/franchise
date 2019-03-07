
<?php

include("../connect/conexao_conecta.php");
include("class.send.php");

$tipo = $_REQUEST['tipo'];
$nome = $_REQUEST['nome'];
$email = $_REQUEST['email'];
$id = $_REQUEST['id'];

if ($tipo == 'franquia') {

    $arquivo = "/var/www/html/franquias/php/area_restrita/PROPOSTA_DE_REPRESENTACAO_FRANCHISING.pdf";

    $mensagem_x = "O sistema enviou e-mail de Proposta Representa��o Franchising Web Control Empresas";
    # Buscando o conte�do do TEXTO para envio.
    $sql_texto = "SELECT texto_email from cs2.pretendentes_status WHERE id = 1";
    $res_texto = mysql_query($sql_texto, $con) or die("Erro SQL: $sql_texto");
    $assunto = "Proposta de Franchising Web Control Empresas";
} else {

    $arquivo = "/var/www/html/franquias/php/area_restrita/PROPOSTA_DE_MICRO_FRANCHISING.pdf";
    $mensagem_x = "O sistema enviou e-mail de Proposta Micro Franquia - Web Control Empresas";
    # Buscando o conte�do do TEXTO para envio.
    $sql_texto = "SELECT texto_email from cs2.pretendentes_status WHERE id = 10";
    $res_texto = mysql_query($sql_texto, $con) or die("Erro SQL: $sql_texto");
    $assunto = "Proposta de Micro Franchising Web Control Empresas";
}

$texto_email = mysql_result($res_texto, 0, 'texto_email');
$texto_email = str_replace('{nome_candidato}', $nome, $texto_email);

$contato = new SendEmail;
$contato->nomeEmail = $nome; // Nome do Responsavel que vai receber o E-Mail		
$contato->paraEmail = $email; // Email que vai receber a mensagem
$contato->copiaOculta = "teixeira@webcontrolempresas.com.br";
$contato->copiaEmail = "danillo@webcontrolempresas.com.br";
$contato->copiaNome = "Danillo Araujo";
$contato->configHost = "10.2.2.7"; // Endere�o do seu SMTP
$contato->configPort = 25; // Porta usada pelo seu servidor. Padr�o 25
$contato->configUsuario = "danillo@webcontrolempresas.com.br"; // Login do email que ira utilizar
$contato->configSenha = "25031964"; // Senha do email
$contato->remetenteEmail = "danillo@webcontrolempresas.com.br"; // E-mail que vai ser exibido no remetente da mensagem
$contato->remetenteNome = "Gerente de Franquias"; // Um nome para o remetente
$contato->assuntoEmail = $assunto; // Assunto da mensagem
$contato->conteudoEmail = $texto_email; // Conteudo da mensagem se voce quer enviar.

$contato->confirmacao = 1; // Se for 1 exibi a mensagem de confirma��o
$contato->erroMsg = "Uma mensagem de erro aqui"; // pode colocar uma mensagem de erro aqui!!
$contato->confirmacaoErro = 1; //  1= exibi o erro 0= n�o exibi o erro 

$contato->anexo = "$arquivo";
try {
    $contato->enviar(); // envia a mensagem
    //grava a ocorrencia
    $sql_oco = "insert into cs2.ocorr_pretendentes (pretendente, msg, data) values ('$id', '$mensagem_x', now())";
    $qr_oco = mysql_query($sql_oco, $con) or die("Erro SQL: $sql_oco");
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
    exit;
}

echo "<script>alert('Email enviado com sucesso!');</script>";

echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/d_pretendentes.php&id=$id \";>";
?>
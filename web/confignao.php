<?php

//CONFIGURAÇÕES SOBRE SEU SITE
$site="Inform System";
$email = "cpd@informsystem.com.br";
$nome = "Inform System";
$exibir='index.php?web=sucesso';

//ESSA VARIAVEL DEFINE SE É O USUARIO QUEM DIGITA O ASSUNTO OU SE DEVE ASSUMIR O ASSUNTO DEFINIDO 
//POR VOCÊ CASO O USUARIO DEFINA O ASSUNTO PONHA "s" NO LUGAR DE "n" E CRIE O CAMPO DE NOME 
//'assunto' NO FORMULARIO DE ENVIO
$assunto_usuario="n";

//CONFIGURAÇOES DA MENSAGEM ORIGINAL
$cabecalho="From: $name <$email>\n";
$assunto="Inform System";
$configuracao="
\nNome: $nome
\nEmpresa: $empresa
\nE-Mail: $email
\nFone: $fone\ Celular: $cel
\nMensagem: $mensagem
\nEnviado em: $date";
 
//CONFIGURAÇÕES DA MENSAGEM DE RESPOSTA
// CASO $assunto_digitado_pelo_usuario="s" ESSA VARIAVEL RECEBERA AUTOMATICAMENTE A CONFIGURACAO
// "Re: $assunto"
$assunto_resposta = "EMAIL RECEBIDO";
$cabecalho_resposta = "From: $nome_do_site <$email>\n";
$configuracao_resposta="
Seu email foi recebi com sucesso!
\nEnviado em: $date";

?>

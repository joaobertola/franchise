<?php

//CONFIGURA��ES SOBRE SEU SITE
$nome_do_site="Inform System";
$email_para_onde_vai_a_mensagem = "administrativo@informsystem.com.br";
$nome_de_quem_recebe_a_mensagem = "Inform System";
$exibir_apos_enviar='index.php?web=sucesso';

//ESSA VARIAVEL DEFINE SE � O USUARIO QUEM DIGITA O ASSUNTO OU SE DEVE ASSUMIR O ASSUNTO DEFINIDO 
//POR VOC� CASO O USUARIO DEFINA O ASSUNTO PONHA "s" NO LUGAR DE "n" E CRIE O CAMPO DE NOME 
//'assunto' NO FORMULARIO DE ENVIO
$assunto_digitado_pelo_usuario="n";

//CONFIGURA�OES DA MENSAGEM ORIGINAL
$cabecalho_da_mensagem_original="From: $name <$email>\n";
$assunto_da_mensagem_original="Inform System";
$configuracao_da_mensagem_original="\nNome: $nome \nContato: $contato \nBairro: $bairro - CEP: $cep \nCidade: $cidade\nUF: $uf\nE-Mail: $email\nFone: $fone \ Celular: $cel\nCPF: $cpf / Cnpj: $cnpj \
nMensagem: $mensagem
\nEnviado em: $date";
 
//CONFIGURA��ES DA MENSAGEM DE RESPOSTA
// CASO $assunto_digitado_pelo_usuario="s" ESSA VARIAVEL RECEBERA AUTOMATICAMENTE A CONFIGURACAO
// "Re: $assunto"
$assunto_da_mensagem_de_resposta = "EMAIL RECEBIDO";
$cabecalho_da_mensagem_de_resposta = "From: $nome_do_site <$email_para_onde_vai_a_mensagem>\n";
$configuracao_da_mensagem_de_resposta="
Seu email foi recebi com sucesso!
\nEnviado em: $date";

?>

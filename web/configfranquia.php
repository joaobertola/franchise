<?php

//CONFIGURAÇÕES SOBRE SEU SITE
$nome_do_site="Inform System";
$email_para_onde_vai_a_mensagem = "cpd@informsystem.com.br";
$nome_de_quem_recebe_a_mensagem = "Inform System";
$exibir_apos_enviar='index.php?web=sucesso';

//ESSA VARIAVEL DEFINE SE É O USUARIO QUEM DIGITA O ASSUNTO OU SE DEVE ASSUMIR O ASSUNTO DEFINIDO 
//POR VOCÊ CASO O USUARIO DEFINA O ASSUNTO PONHA "s" NO LUGAR DE "n" E CRIE O CAMPO DE NOME 
//'assunto' NO FORMULARIO DE ENVIO
$assunto_digitado_pelo_usuario="n";

//CONFIGURAÇOES DA MENSAGEM ORIGINAL
$cabecalho_da_mensagem_original="From: $name <$email>\n";
$assunto_da_mensagem_original="Inform System";
$configuracao_da_mensagem_original="\nNome: $nome \nCPF: $cpf \nNaturalidade: $naturalidade \n Endreço: $endereco - Completo: $complemento \nCidade: $cidade - UF: $uf\nFone: $fone \ Celular: $cel\nE-mail: $email \n\n Tempo que reside na cidade acima citada: $tempo \n\n Cidade / Região de interesse para atuação da franquia: $interesse \n\n Observações: $obs\n

\nEnviado em: $date";
 
//CONFIGURAÇÕES DA MENSAGEM DE RESPOSTA
// CASO $assunto_digitado_pelo_usuario="s" ESSA VARIAVEL RECEBERA AUTOMATICAMENTE A CONFIGURACAO
// "Re: $assunto"
$assunto_da_mensagem_de_resposta = "EMAIL RECEBIDO";
$cabecalho_da_mensagem_de_resposta = "From: $nome_do_site <$email_para_onde_vai_a_mensagem>\n";
$configuracao_da_mensagem_de_resposta="
Seu email foi recebi com sucesso!
\nEnviado em: $date";

?>

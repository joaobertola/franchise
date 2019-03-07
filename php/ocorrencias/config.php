<?php

###################
## Configura��es ##
###################

## Configura��es do Banco de Dados
$db = Array();
$db[nome] = "cs2";
$db[host] = "10.2.2.3";
$db[user] = "csinform";
$db[senha] = "inform4416#scf";

## Configura��es caso voc� esteja usando Query String em seu site, caso n�o estaja usando n�o altere nada!
$link = Array();
$link[index] = "index.php"; // URL da index, ex: go=index&ext=php
$link[postar] = "?act=postar"; // URL da pagina para postar a mensagem
$link[remover] = "?act=remover"; // URL da parte de admin para remover mensagens
$link[login] = "ocorrencias/vlogin"; // URL da pagina de login das ocorr�ncias
$link[ranking] = "ranking/vlogin"; // URL da pagina de login do ranking de vendas
$link[admin] = "admin.php"; // URL da pagina de admin

## Configura��es de pagina��o
$lpp = "8"; // N�mero de mensagens por p�gina
$paginacao = Array();
$paginacao[link] = "$PHP_SELF?"; // Se vc usa string coloque o seguinte tipo de link: $PHP_SELF?go=ocorrencias&qr=index&ext=php& (n�o esque�a do "&" no final do link), caso n�o use string deixe somente $PHP_SELF?

## Cores do Layout do Mural
$c = Array();
$cor[bgcolor] = "#FFFFFF"; // Cor do fundo da p�gina
$cor[topico] = "#B6CBF6"; // Cor do fundo do t�pico de cada mensagem

## Outras configura��es
$useron = "1"; // Mostrar quantos usu�rios est�o online ( 1 = sim / 0 = n�o )
$titulo = "REGISTRO DE ATENDIMENTO"; // T�tulo da Tela
$thanks_msg = "Ocorrência enviada com sucesso!"; // Mensagem que aparecer� quando a mensagem for enviada com sucesso
$error_msg = "Nâo foi possivel enviar a ocorrência. Tente novamente."; // Mensagem de erro ao enviar a mensagem
$smyles = "1"; // Mostrar Smyles para postagem (1 para sim / 0 para n�o)
$credits = "<font size=\"1\" face=\"Verdana\">Este sistema é para uso exclusivo das franquias da <b>Web Control Empresas Tecnologia em Informações Ltda.</b></font>"; // Mensagem principal da index
$url_site = "https://www.webcontrolempresas.com.br"; // Url do site


## Altere essas vari�veis somente de souber oque est� fazendo
$table_name = "ocorrencias"; // Nome da tabela.
$table_user = "franquia"; // Nome da tabela dos administradores.
$copyright = "<font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">Desenvolvido por <a href=\"mailto:cpd@webcontrolempresas.com.br\">Web Control Empresas</a>.</font>"; // Mensagem de copyright: OBS: proibido mudar esse linha!!!!

?>
<?php

include("receita.php");

$doc = $_REQUEST['insc'];
$letras = $_REQUEST['letra'];

$consulta = curl_captcha_rf($doc,$letras);

if ( $consulta == '0' ){?>
	<script>
		alert('Você informou a letra Errada, digite novamente');;
	</script>
	<meta HTTP-EQUIV = "Refresh" CONTENT = "0; URL = painel.php?pagina1=clientes/a_incclient2.php&cnpj_receita=<?=$doc?>">
	<?
	exit;
}else if ( $consulta == '2' ){?>
	<script>
		alert('Número do CNPJ Inválido');
	</script>
	<meta HTTP-EQUIV = "Refresh" CONTENT = "0; URL = painel.php?pagina1=clientes/a_incclient2.php&cnpj_receita=<?=$doc?>">
	<?
	exit;
}


//$doc é o CNPJ a ser pesquisa sem PONTOS, BARRAS e TRAÇO.
//$letras é o valor do campo onde foi digitado o Captcha.

$ficha = rf_ficha_receita($consulta);

$cnpj = rf_ficha_dados($ficha, '1');
$cnpj = eregi_replace("([^0-9])","",$cnpj);
$abertura = rf_ficha_dados($ficha, '2');
$razaosoc = rf_ficha_dados($ficha, '3');
$fantasia = rf_ficha_dados($ficha, '4');
$fantasia = str_replace('*','',$fantasia);
$logradouro = rf_ficha_dados($ficha, '9');
$numero = rf_ficha_dados($ficha, '10');
$compl = rf_ficha_dados($ficha, '11');
$cep = rf_ficha_dados($ficha, '12');
$cep = str_replace('.','',$cep);
#$cep = eregi_replace("([^0-9])","",$cep);
$bairro = rf_ficha_dados($ficha, '13');
$cidade = rf_ficha_dados($ficha, '14');
$uf = rf_ficha_dados($ficha, '15');

/*
1 = CNPJ
2 = Data de Abertura
3 = Razão Social
4 = Nome Fantasia
5 = Atividade Principal
6 = Atividade Secundária
7 = Natureza Jurídica
8 = Porte*
9 = Logradouro
10 = Numero
11 = Complemento
12 = CEP**
13 = Bairro
14 = Município
15 = UF
16 = Situação Cadastral
17 = Data da Situação
18 = Motivo da Situação
19 = Situação Especial
20 = Data da Situação Especial 
*/

?>
<meta HTTP-EQUIV = "Refresh" CONTENT = "0; URL = painel.php?pagina1=clientes/a_incclient2.php&ok=1&razaosocial_receita=<?=$razaosoc?>&cnpj_receita=<?=$doc?>&fantasia_receita=<?=$fantasia?>&endereco_receita=<?=$logradouro?>&numero_receita=<?=$numero?>&compl_receita=<?=$compl?>&bairro_receita=<?=$bairro?>&cidade_receita=<?=$cidade?>&uf_receita=<?=$uf?>&cep_receita=<?=$cep?>">
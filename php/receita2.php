<?php

include("receita.php");

$doc = $_REQUEST['insc'];
$letras = $_REQUEST['letra'];

$consulta = curl_captcha_rf($doc,$letras);

if ( $consulta == '0' ){?>
	<script>
		alert('Voc� informou a letra Errada, digite novamente');;
	</script>
	<meta HTTP-EQUIV = "Refresh" CONTENT = "0; URL = painel.php?pagina1=clientes/a_incclient2.php&cnpj_receita=<?=$doc?>">
	<?
	exit;
}else if ( $consulta == '2' ){?>
	<script>
		alert('N�mero do CNPJ Inv�lido');
	</script>
	<meta HTTP-EQUIV = "Refresh" CONTENT = "0; URL = painel.php?pagina1=clientes/a_incclient2.php&cnpj_receita=<?=$doc?>">
	<?
	exit;
}


//$doc � o CNPJ a ser pesquisa sem PONTOS, BARRAS e TRA�O.
//$letras � o valor do campo onde foi digitado o Captcha.

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
3 = Raz�o Social
4 = Nome Fantasia
5 = Atividade Principal
6 = Atividade Secund�ria
7 = Natureza Jur�dica
8 = Porte*
9 = Logradouro
10 = Numero
11 = Complemento
12 = CEP**
13 = Bairro
14 = Munic�pio
15 = UF
16 = Situa��o Cadastral
17 = Data da Situa��o
18 = Motivo da Situa��o
19 = Situa��o Especial
20 = Data da Situa��o Especial 
*/

?>
<meta HTTP-EQUIV = "Refresh" CONTENT = "0; URL = painel.php?pagina1=clientes/a_incclient2.php&ok=1&razaosocial_receita=<?=$razaosoc?>&cnpj_receita=<?=$doc?>&fantasia_receita=<?=$fantasia?>&endereco_receita=<?=$logradouro?>&numero_receita=<?=$numero?>&compl_receita=<?=$compl?>&bairro_receita=<?=$bairro?>&cidade_receita=<?=$cidade?>&uf_receita=<?=$uf?>&cep_receita=<?=$cep?>">
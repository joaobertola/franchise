<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$data = date('Y-m-d H:i:s');
$id         = $_POST['id'];
$senha      = $_POST['senha'];
$franquia   = $_POST['franquia'];
$razao      = $_POST['franquia'];
$cnpj       = $_POST['cnpj'];
$endereco   = $_POST['endereco'];
/*
$numero     = $_POST['numero'];
$compl      = $_POST['complemento'];
*/
$bairro     = $_POST['bairro'];
$cidade     = $_POST['cidade'];
$uf         = $_POST['uf'];
$cep        = $_POST['cep'];
$telefone   = $_POST['telefone'];
$fone_res   = $_POST['fone_res'];
$email      = $_POST['email'];
$ctacte     = $_POST['ctacte'];
$banco      = $_POST['banco'];
$agencia    = $_POST['agencia'];
$tpconta    = $_POST['tpconta'];
$conta      = $_POST['conta'];
$titular    = $_POST['titular'];
$cpftitular = $_POST['cpftitular'];
$habilitar_ranking = $_POST['habilitar_ranking'];

$tx_adesao = $_REQUEST['tx_adesao'];
$tx_adesao = str_replace(".","",$tx_adesao);
$tx_adesao = str_replace(",",".",$tx_adesao);

$tx_pacote = $_REQUEST['tx_pacote'];
$tx_pacote = str_replace(".","",$tx_pacote);
$tx_pacote = str_replace(",",".",$tx_pacote);

$tx_software = $_REQUEST['tx_software'];
$tx_software = str_replace(".","",$tx_software);
$tx_software = str_replace(",",".",$tx_software);

$comissao = $_REQUEST['comissao'];
$comissao = str_replace(",",".",$comissao);


//trata as variaveis para o formato padr�o
$telefone=str_replace("(","",$telefone);
$telefone=str_replace(")","",$telefone);
$telefone=str_replace("-","",$telefone);

$celular1=str_replace("(","",$celular1);
$celular1=str_replace(")","",$celular1);
$celular1=str_replace("-","",$celular1);

$celular2 =str_replace("(","",$celular2);
$celular2=str_replace(")","",$celular2);
$celular2=str_replace("-","",$celular2);

$fone_res=str_replace("(","",$fone_res);
$fone_res=str_replace(")","",$fone_res);
$fone_res=str_replace("-","",$fone_res);

$cnpj=str_replace("/","",$cnpj);
$cnpj=str_replace("-","",$cnpj);
$cnpj=str_replace(".","",$cnpj);

$cpf1=str_replace("/","",$cpf1);
$cpf1=str_replace("-","",$cpf1);
$cpf1=str_replace(".","",$cpf1);

$cpf2=str_replace("/","",$cpf2);
$cpf2=str_replace("-","",$cpf2);
$cpf2=str_replace(".","",$cpf2);

$data_abertura = implode(preg_match("~\/~", $data_abertura) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_abertura) == 0 ? "-" : "/", $data_abertura)));

$data_apoio = implode(preg_match("~\/~", $data_apoio) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_apoio) == 0 ? "-" : "/", $data_apoio)));

$comando = "UPDATE franquia SET
				senha 		= '$senha',
				fantasia	= '$franquia',
				razaosoc	= '$razao',
				cpfcnpj		= '$cnpj',
				endereco	= '$endereco',
				bairro		= '$bairro',
				cidade		= '$cidade',
				uf		= '$uf',
				cep		= '$cep',
				fone1		= '$telefone',
				fone2		= '$fone_res',
				email		= '$email',
				ctacte		= '$ctacte',
				banco		= '$banco',
				agencia		= '$agencia',
				tpconta		= '$tpconta',
				conta		= '$conta',
				titular		= '$titular',
				cpftitular	= '$cpftitular',
				comissao_frqjr  = '$comissao',
                                tx_adesao       = '$tx_adesao',
                                tx_pacote       = '$tx_pacote',
                                tx_software     = '$tx_software',
                                habilitar_ranking = '$habilitar_ranking'
			WHERE id = '$id'";

mysql_query($comando,$con);

header("location: ../painel.php?pagina1=franqueado_jr/mostra_franqueado_jr.php&id=$id&Sucesso=1");
exit;
?>
<?php

require "connect/sessao.php";

//coloca a data de hoje
$data = date('Y-m-d H:i:s');

$razaosoc 	= $_POST['razaosoc'];
$insc 		= $_POST['insc'];
$nomefantasia = $_POST['nomefantasia'];
$uf 		= $_POST['uf'];
$localidade = $_POST['localidade'];
$bairro 	= $_POST['bairro'];
$logradouro	= $_POST['logradouro'];
$numero		= $_POST['numero'];
$complemento = $_POST['complemento'];
$end 		= $logradouro." ".$numero." ".$complemento;
$cep 		= $_POST['cep'];
$fone 		= $_POST['fone'];
$fax 		= $_POST['fax'];
$email 		= $_POST['email'];
$assinatura = $_POST['assinatura'];
$pacote		= $_POST['pacote'];
$obs 		= $_POST['obs'];
$contrato 	= $_POST['contrato'];
$id_franquia_junior = $_POST['franqueado'];
$id_franquia_master = $_POST['id_franquia_master'];
$id_ramo 	= $_POST['id_ramo'];
$celular 	= $_POST['celular'];
$fone_res 	= $_POST['fone_res'];
$socio1 	= $_POST['socio1'];
$socio2 	= $_POST['socio2'];
$cpfsocio1 	= $_POST['cpfsocio1'];
$cpfsocio2 	= $_POST['cpfsocio2'];
$fatura 	= $_POST['fatura'];
$vendedor 	= $_POST['vendedor'];
$origem 	= $_POST['origem'];

//trata as variaveis para o formato padr�o
$fone=str_replace("(","",$fone);
$fone=str_replace(")","",$fone);
$fone=str_replace("-","",$fone);

$fax=str_replace("(","",$fax);
$fax=str_replace(")","",$fax);
$fax=str_replace("-","",$fax);

$celular=str_replace("(","",$celular);
$celular=str_replace(")","",$celular);
$celular=str_replace("-","",$celular);

$fone_res=str_replace("(","",$fone_res);
$fone_res=str_replace(")","",$fone_res);
$fone_res=str_replace("-","",$fone_res);

$insc=str_replace("/","",$insc);
$insc=str_replace("-","",$insc);
$insc=str_replace(".","",$insc);

$cpfsocio1=str_replace("/","",$cpfsocio1);
$cpfsocio1=str_replace("-","",$cpfsocio1);
$cpfsocio1=str_replace(".","",$cpfsocio1);

$cpfsocio2=str_replace("/","",$cpfsocio2);
$cpfsocio2=str_replace("-","",$cpfsocio2);
$cpfsocio2=str_replace(".","",$cpfsocio2);

$cep=str_replace("-","",$cep);

$sql8="select count(*) quant from cs2.cadastro where insc=$insc and sitcli < 2";
$ql8=mysql_query($sql8,$con);
$resp=mysql_fetch_array($ql8);
$qtd=$resp["quant"];
//verifica se tem cnpj duplicado
if ($qtd > 0 ){
	echo "<script>alert(\"CNPJ Cadastrado para outro cliente, favor verificar!\");history.back();</script>";
	exit;
}

//captura o valor da assinatura mensal
$variavel = "SELECT a.nome, b.tx_adesao 
			 FROM cs2.tabela_valor a 
			 INNER JOIN cs2.franquia b ON b.id = $id_franquia_master
			 WHERE a.id='$pacote' 
			 AND a.categoria='$assinatura'";
$response = mysql_query($variavel,$con);
while ($array = mysql_fetch_array($response)) {
	$tx_mens = $array['nome'];
	$tx_adesao = $array['tx_adesao'];
}

if (empty($tx_mens)) $tx_mens = 0;
if ($tx_mens == '0'){
	echo "<script>alert(\"Favor escolher o Pacote de Pesquisas novamente!\");history.back();</script>";
	exit;
}

//cria um novo registro na tabela cadastro
$comando = "insert into cadastro
(razaosoc, insc, nomefantasia, uf, cidade, bairro, end, cep, fone, fax, email, tx_mens, tx_adesao, debito, diapagto,
id_franquia, dt_cad, sitcli, obs, classificacao, contrato, ramo_atividade, celular, fone_res, socio1, socio2, cpfsocio1, cpfsocio2, emissao_financeiro, pendencia_contratual, vendedor, origem, qtd_acessos,id_franquia_jr,hora_cadastro) 
values
('$razaosoc', '$insc', '$nomefantasia', '$uf', '$localidade', '$bairro', '$end', '$cep', '$fone', '$fax', '$email', '$tx_mens', '$tx_adesao', 'B', '30', '$id_franquia_master',  now() , '0', '$obs', 'Mensal', '$contrato', '$id_ramo', '$celular', '$fone_res', '$socio1', '$socio2', '$cpfsocio1', '$cpfsocio2', '$fatura', '1', '$vendedor', '$origem', '0','$id_franquia_junior',now())";

// registrando log
$teste = str_replace(chr(39),'',$comando);
$sql = "insert into cs2.sql_cadastro(comando_sql,datahora) values('$teste',now())";
mysql_query($sql, $con);

// continuacao
$res = mysql_query ($comando, $con);

//pega o codloja
$sql = "select codloja from cadastro where insc='$insc'";
$resposta = mysql_query($sql, $con);
while ($array = mysql_fetch_array($resposta))	{
	$codloja	= $array['codloja'];
}

//isto me gera a senha aleat�ria d 5 d�gitos
require "senha_aleatoria_numerica.php";

//isto serve para incrementar o �ltimo valor do c�digo
$conecta = "SELECT (logon + 1) as logon FROM cs2.controle";
$resposta = mysql_query($conecta, $con);
while ($registro = mysql_fetch_array($resposta))	{
	$codigo	= $registro["logon"];
}

$sai = false;
do{
	$sql = "select count(*) qtd from cs2.logon where MID(logon,1,LOCATE('S', logon) - 1)='$codigo'";
	$ql8=mysql_query($sql,$con);
	$consulta=mysql_fetch_array($ql8);
	$qtd=$consulta["qtd"];
	if ( $qtd == 0){
	    $sai = true;
    	$logon = $codigo.'S'.$senha;
	}else{
		$codigo++;
	}
}while ($sai == false);

// atualizando na tabela controle o ultimo codigo gerado
$conecta="update cs2.controle set logon = $codigo";
$resposta = mysql_query($conecta, $con);


// $logon = $codigo.'S0'.$senha;

$command = "insert into logon (codloja, logon, dt_atv) values ('$codloja', '$logon', '$data')";
$result = mysql_query($command, $con);

// caso for somente negativa��o
if ( $assinatura == '7' ) {
	mysql_query("update cadastro set classe='1' where codloja='$codloja'");
}

//insere tabela de pre�os e consultas liberadas
$sql = "select codcons,valor from cs2.valcons";
$inserre = mysql_query($sql,$con);

while ($registro= mysql_fetch_array($inserre)) {
   $codcons = $registro["codcons"];
   $valcons = $registro["valor"];

   # Qtd Padrao
   $qtd = '50';

   // libera 5 consultas para Pesquisa Ligth e Pesquisa Restritiva
   if ( $codcons == 'A0208' || $codcons == 'A0301' ) $qtd = '5';
   
   // libera 25 consultas para Pesquisa Cartorial e Pesquisa Empresarial
   if ( $codcons == 'A0203' || $codcons == 'A0115' ) $qtd = '25';
   
   if ( $assinatura == '7' ) $qtd= '0';
   
   $tabela = "INSERT INTO cs2.valconscli (codloja, codcons, valorcons) VALUES ('$codloja','$codcons','$valcons')";
   $result1 = mysql_query($tabela, $con) or die ("Erro: $tabela");
   $liberadas = "INSERT INTO cs2.cons_liberada (codloja, tpcons, qtd, consumo) VALUES('$codloja','$codcons','$qtd','0')";
   $result2 = mysql_query($liberadas, $con)  or die ("Erro: $liberadas");
} 

//insere consultas bonificadas
$sql = "INSERT INTO bonificadas (codloja, tpcons, qtd) SELECT '$codloja', tpcons, qtd FROM tabela_valor WHERE id = '$pacote'";
$resposta = mysql_query($sql, $con);

$res = mysql_close ($con);
echo "<script>alert(\"Cliente cadastrado com sucesso!\");</script>";
echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=franqueado_jr/mostra_cliente.php&codloja=$codloja\";>";

?>
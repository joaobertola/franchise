<?php
require "connect/sessao.php";
require "connect/funcoes.php";

//coloca a data de hoje
$data = date('Y-m-d H:i:s');

$razaosoc     = str_replace("'","",$_POST['razaosoc']);
$nomefantasia = str_replace("'","",$_POST['nomefantasia']);

$atendente_resp = $_POST['atendente_resp'];
$insc 		= $_POST['insc'];
$uf 		= $_POST['uf'];
$localidade = $_POST['localidade'];
$bairro 	= $_POST['bairro'];
$logradouro	= $_POST['logradouro'];
$numero		= $_POST['numero'];
$complemento = $_POST['complemento'];
//$end 		= $logradouro." ".$numero." ".$complemento;
$cep 		= $_POST['cep'];
$fone 		= $_POST['fone'];
$fax 		= $_POST['fax'];
$email 		= $_POST['email'];
$assinatura = $_POST['assinatura'];
$pacote		= $_POST['pacote'];
$obs 		= $_POST['obs'];
$contrato 	= $_POST['contrato'];
$franqueado = $_POST['franqueado'];
$id_ramo 	= $_POST['id_ramo'];
$celular 	= $_POST['celular'];
$fone_res 	= $_POST['fone_res'];
$socio1 	= $_POST['socio1'];
$socio2 	= $_POST['socio2'];
$cpfsocio1 	= $_POST['cpfsocio1'];
$cpfsocio2 	= $_POST['cpfsocio2'];
$fatura 	= $_POST['fatura'];
$vendedor 	= $_REQUEST['vendedor'];
$origem 	= $_POST['origem'];

//altera��o para nota fiscal
$inscricao_estadual = str_replace("'","",$_REQUEST['inscricao_estadual']);
$inscricao_estadual = str_replace(" ","",$inscricao_estadual);
$cnae_fiscal = str_replace("'","",$_REQUEST['cnae_fiscal']);
$cnae_fiscal = str_replace(" ","",$cnae_fiscal);
$inscricao_municipal = str_replace("'","",$_REQUEST['inscricao_municipal']);
$inscricao_municipal = str_replace(" ","",$inscricao_municipal);
$inscricao_estadual_tributario = str_replace("'","",$_REQUEST['inscricao_estadual_tributario']);
$inscricao_estadual_tributario = str_replace(" ","",$inscricao_estadual_tributario);

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

if ( empty($vendedor) ) {
	echo "<script>alert(\"Preencha o campo [VENDEDOR] Obrigado !\");history.back();</script>";
	exit;
}

$sql8="select count(*) quant from cs2.cadastro where insc=$insc";
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
			 FROM tabela_valor a 
			 INNER JOIN franquia b ON b.id=2
			 WHERE a.id='$pacote' and a.categoria='$assinatura'";
$response = mysql_query($variavel,$con);
while ($array = mysql_fetch_array($response)) {
	$tx_mens = $array['nome'];
	$tx_adesao = $array['tx_adesao'];
}
if (empty($tx_mens)) $tx_mens = 0;
if (tx_mens == '0'){
	echo "<script>alert(\"Favor escolher o Pacote de Pesquisas novamente!\");history.back();</script>";
	exit;
}
//cria um novo registro na tabela cadastro
$comando = "insert into cadastro
(atendente_resp, razaosoc, insc, nomefantasia, uf, cidade, bairro, end, numero, complemento, cep, fone, fax, email, tx_mens, tx_adesao, debito, diapagto, id_franquia, dt_cad, sitcli, obs, classificacao, contrato, ramo_atividade, celular, fone_res, socio1, socio2, cpfsocio1, cpfsocio2, emissao_financeiro, pendencia_contratual, vendedor, origem, qtd_acessos, hora_cadastro, inscricao_estadual, cnae_fiscal, inscricao_municipal, inscricao_estadual_tributario) 
values
('$atendente_resp', '$razaosoc', '$insc', '$nomefantasia', '$uf', '$localidade', '$bairro', '$logradouro',  '$numero','$complemento','$cep', '$fone', '$fax', '$email', '$tx_mens', '$tx_adesao', 'B', '30', '$franqueado',  now() , '0', '$obs', 'Mensal', '$contrato', '$id_ramo', '$celular', '$fone_res', '$socio1', '$socio2', '$cpfsocio1', '$cpfsocio2', '$fatura', '1', '$vendedor', '$origem', '0', now() , '$inscricao_estadual', '$cnae_fiscal', '$inscricao_municipal', '$inscricao_estadual_tributario')";
$res = mysql_query ($comando, $con);

// registrando log
$teste = str_replace(chr(39),'',$comando);
$sql = "insert into cs2.sql_cadastro(comando_sql,datahora) values('$teste',now())";
mysql_query($sql, $con);

//pega o codloja
$sql = "select codloja from cadastro where insc='$insc'";
$resposta = mysql_query($sql, $con);
while ($array = mysql_fetch_array($resposta))	{
	$codloja = $array['codloja'];
}

//isto me gera a senha aleat�ria d 4 d�gitos
require "senha_aleatoria.php";

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
   $qtd = '25';

   if ( $codcons == 'A0208' || $codcons == 'A0301' ) $qtd = '5'; // 05 consultas para Pesquisa Ligth e Pesquisa Restritiva
   else if ( $codcons == 'A0203' || $codcons == 'A0115' ) $qtd = '25'; // 25 consultas para Pesquisa Cartorial e Pesquisa Empresarial
   else if ( $codcons == 'A0207' ) $qtd = '5'; // 5 consultas para Pesquisa Personnalite
   else if ( $codcons == 'A0100' ) $qtd = '100'; // 100 consultas para Pesquisa BACEN
   
   if ( $assinatura == '7' ) $qtd= '0';
   
   if ( substr($codcons,0,1) == 'F' ) $qtd = '20'; // Features
   
   $tabela = "Insert into cs2.valconscli values('$codloja','$codcons','$valcons')";
   $result1 = mysql_query($tabela, $con) or die ("Erro: $tabela");
   $liberadas = "insert into cs2.cons_liberada values('$codloja','$codcons','$qtd','0')";
   $result2 = mysql_query($liberadas, $con)  or die ("Erro: $liberadas");
} 

//insere consultas bonificadas

$sql_t = "select tpcons, qtd, tpcons2, qtd2 from tabela_valor where id = '$pacote'";
$qry_t = mysql_query($sql_t, $con);
while($row_t = mysql_fetch_array($qry_t)){
	
	$tpcons = $row_t['tpcons'];
	$qtd	= $row_t['qtd'];
	$tpcons2= $row_t['tpcons2'];
	$qtd2	= $row_t['qtd2'];

	// LOG
	$teste = "Resultado: Pacote 1: [$tpcons - $qtd] Pacote 2: [$tpcons2 - $qtd2]";
	$sql = "insert into cs2.sql_cadastro(comando_sql,datahora) values('$teste',now())";
	mysql_query($sql, $con);

	$sql = "insert into bonificadas (codloja, tpcons, qtd) 
			VALUES( '$codloja', '$tpcons', '$qtd' )";
	mysql_query($sql, $con);
	
	// LOG
	$teste = str_replace(chr(39),'',$sql);
	$sql = "insert into cs2.sql_cadastro(comando_sql,datahora) values('$teste',now())";
	mysql_query($sql, $con);


	if($qtd2 > 0){
		$sql = "insert into bonificadas (codloja, tpcons, qtd) 
				VALUES( '$codloja', '$tpcons2', '$qtd2' )";
		mysql_query($sql, $con);
		
		$teste = str_replace(chr(39),'',$sql);
		$sql = "insert into cs2.sql_cadastro(comando_sql,datahora) values('$teste',now())";
		mysql_query($sql, $con);
	
	}
}

$res = mysql_close ($con);

// Cria uma conta de Email para o cliente novo;
verifica_email($franqueado,$codloja,$nomefantasia);

echo "<script>alert(\"Cliente cadastrado com sucesso!\");</script>";
echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/most_cliente.php&codloja=$codloja\";>";
?>
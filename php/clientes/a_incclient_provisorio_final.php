<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";
require "smtp.class.php";


function enviaErroCpd($p_parametro){
	$data = date("d/m/Y");
	$hora = date("H:m:s");
	$smtp = new Smtp("10.2.2.7"); // host do servidor SMTP 
	$smtp->user = "cpd@webcontrolempresas.com.br"; // usuario do servidor SMTP
	$smtp->pass = "#9%kxP*-11"; // senha dousuario do servidor SMTP
	$smtp->debug = true; // ativar a autentica� SMTP
	$to = "erro_sistema@webcontrolempresas.com.br";
	$from = "erro_sistema@webcontrolempresas.com.br";
	$assunto = "Erro Cadastro de Cliente ";
	$msg .= "Data: $data \n ";
	$msg .= "Hora: $hora \n";
	$msg .= "Local do SQL: $p_parametro";
	$smtp->Send($to, $from, $assunto, $msg);
}

function substitui_acentos($value){ 
	$trocaeste=array( "(", ")","'","�","�","�","�","�","�","�","�","�","�","�","�","�","�",";","'","�"); 
	$poreste=array( "", "","","O","C","U","U","O","O","O","O","A","A","A","A","E","I","","",""); 
	$value=str_replace($trocaeste,$poreste,$value); 
	$value = strtoupper($value);
	return $value; 
}

//coloca a data de hoje
$data = date('Y-m-d H:i:s');

$razaosoc     = str_replace("'","",$_POST['razaosoc']);
$nomefantasia = str_replace("'","",$_POST['nomefantasia']);

$razaosoc       = substitui_acentos($razaosoc);
$nomefantasia   = substitui_acentos($nomefantasia);

$atendente_resp = substitui_acentos($_POST['atendente_resp']);
$insc 		      = $_POST['insc'];
if ( strlen($insc > 11 ) ) $Tipo = 1;
else $Tipo = 0;
$uf 	     	    = $_POST['uf'];
$localidade     = substitui_acentos($_POST['localidade']);
$bairro       	= substitui_acentos($_POST['bairro']);
$logradouro	    = substitui_acentos($_POST['logradouro']);
$numero		      = substitui_acentos($_POST['numero']);
$complemento    = substitui_acentos($_POST['complemento']);

$cep 		= $_POST['cep'];
$fone 		= $_POST['fone'];
$fax 		= $_POST['fax'];
$email 		= $_POST['email'];
$assinatura = $_POST['assinatura'];
$pacote		= $_POST['pacote'];

$obs 		= substitui_acentos($_POST['obs']);
$contrato 	= $_POST['contrato'];
$franqueado = substitui_acentos($_POST['franqueado']);
$id_ramo 	= $_POST['id_ramo'];
$celular 	= $_POST['celular'];
$fone_res 	= $_POST['fone_res'];
$socio1 	= substitui_acentos($_POST['socio1']);
$socio2 	= substitui_acentos($_POST['socio2']);
$cpfsocio1 	= $_POST['cpfsocio1'];
$cpfsocio2 	= $_POST['cpfsocio2'];

$fatura 	= $_POST['fatura'];
$vendedor 	= substitui_acentos($_REQUEST['vendedor']);
$origem 	  = substitui_acentos($_POST['origem']);

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

$sql8="SELECT COUNT(*) quant FROM cs2.cadastro WHERE insc='$insc'";
$ql8=mysql_query($sql8,$con);
$resp=mysql_fetch_array($ql8);
$qtd=$resp["quant"];
//verifica se tem cnpj duplicado
if ($qtd > 0 ){
	echo "<script>alert(\"CNPJ Cadastrado para outro cliente, favor verificar!\");history.back();</script>";
	exit;
}

//captura o valor da assinatura mensal
$cont_assinatura = 0;
$variavel = "SELECT a.nome, b.tx_adesao 
			 FROM cs2.tabela_valor a 
			 INNER JOIN cs2.franquia b ON b.id=$franqueado
			 WHERE a.id='$pacote' 
			 AND a.categoria='$assinatura'";
$response = mysql_query($variavel,$con);
$cont_assinatura = mysql_num_rows($response);
if($cont_assinatura == 0){
	echo "<script>alert(\"Erro na verfica��o de assinatura, entre em contato com o Departamento de Informatica !\");history.back();</script>";
	exit;
}
while ($array = mysql_fetch_array($response)) {
	$tx_mens = $array['nome'];
	$tx_adesao = $array['tx_adesao'];
}
if (empty($tx_mens)) $tx_mens = 0;
if ($tx_mens == '0'){
	echo "<script>alert(\"Favor escolher o Pacote de Pesquisas novamente....!\");history.back();</script>";
	exit;
}

//cria um novo registro na tabela cadastro
$comando = "INSERT INTO cs2.cadastro_provisorio
(atendente_resp, razaosoc, insc, nomefantasia, uf, cidade, bairro, end, numero, complemento, cep, fone, fax, email, tx_mens, tx_adesao, id_franquia, dt_cad, obs, ramo_atividade, celular, fone_res, socio1, socio2, cpfsocio1, cpfsocio2, vendedor, origem, hora_cadastro, inscricao_estadual, cnae_fiscal, inscricao_municipal, inscricao_estadual_tributario) 
VALUES
('$atendente_resp', '$razaosoc', '$insc', '$nomefantasia', '$uf', '$localidade', '$bairro', '$logradouro',  '$numero','$complemento','$cep', '$fone', '$fax', '$email', '$tx_mens', '$tx_adesao', '$franqueado',  now() , '$obs',  '$id_ramo', '$celular', '$fone_res', '$socio1', '$socio2', '$cpfsocio1', '$cpfsocio2', '$vendedor', '$origem', now() , '$inscricao_estadual', '$cnae_fiscal', '$inscricao_municipal', '$inscricao_estadual_tributario')";
$res = mysql_query ($comando, $con);

if(!$res){
	echo "<script>alert(\"Erro na inser��o do cliente provisorio, entre em contato com o Departamento de Informatica !\");history.back();</script>";
	exit;
}

grava_dados($insc, $Tipo, $razaosoc, $logradouro, $numero, $complemento, $bairro, $localidade, $uf, $cep, $email, $fone, $celular, $cpfsocio1, $socio1, $cpfsocio2, $socio2);

echo "<script>alert(\"Cliente cadastrado com sucesso!.  Dentro de alguns minutos sua solicitacao sera atendida\");</script>";
echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_inc_cliente_provisorio.php\";>";

?>
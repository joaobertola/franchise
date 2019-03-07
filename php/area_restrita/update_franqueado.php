<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = date('Y-m-d H:i:s');

$qtd_contrato_mes = $_POST['qtd_contrato_mes'] ;
$id			 = $_POST['id'];
$senha       = $_POST['senha'];
$senha_restrita = $_POST['senha_restrita'];
$franquia	 = $_POST['franquia'];
$razao       = $_POST['razao'];
$cnpj        = $_POST['cnpj'];
$endereco    = $_POST['endereco'];
$bairro      = $_POST['bairro'];
$cidade      = $_POST['cidade'];
$uf          = $_POST['uf'];
$cep         = $_POST['cep'];
$telefone    = $_POST['telefone'];
$fone_res    = $_POST['fone_res'];
$fone3       = $_POST['fone3'];
$email       = $_POST['email'];
$nome_prop1  = $_POST['nome_prop1'];
$cpf1        = $_POST['cpf1'];
$celular1    = $_POST['celular1'];
$operadora_1 = $_POST['operadora_1'];
$nome_prop2  = $_POST['nome_prop2'];
$cpf2        = $_POST['cpf2'];
$celular2    = $_POST['celular2'];
$operadora_2 = $_POST['operadora_2'];
$ctacte		 = $_POST['ctacte'];
$banco 		 = $_POST['banco'];
$agencia 	 = $_POST['agencia'];
$tpconta 	 = $_POST['tpconta'];
$conta 		 = $_POST['conta'];
$titular 	 = $_POST['titular'];
$cpftitular  = $_POST['cpftitular'];
$id_gerente	 = $_POST['id_gerente'];
$data_abertura    = $_POST['data_abertura'];
$data_apoio	      = $_POST['data_apoio'];
$sitfrq           = $_POST['sitfrq'];
$sitrep           = $_POST['sitrep'];
$classificacao    = $_POST['classificacao'];
$participacao_frq = (isset($_POST['participacao_frq'] ) ? $_POST['participacao_frq'] : '');


$dt_cad = $_POST['dt_cad'];
$tx_adesao = $_REQUEST['tx_adesao'];
$tx_adesao = str_replace(".","",$tx_adesao);
$tx_adesao = str_replace(",",".",$tx_adesao);
$tx_pacote = $_REQUEST['tx_pacote'];
$tx_pacote = str_replace(".","",$tx_pacote);
$tx_pacote = str_replace(",",".",$tx_pacote);
$tx_software = $_REQUEST['tx_software'];
$tx_software = str_replace(".","",$tx_software);
$tx_software = str_replace(",",".",$tx_software);	

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

$fone3 = str_replace("(","",$fone3);
$fone3 = str_replace(")","",$fone3);
$fone3 = str_replace("-","",$fone3);

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

$dt_cad = implode(preg_match("~\/~", $dt_cad) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $dt_cad) == 0 ? "-" : "/", $dt_cad)));

# Verificando se o numero do telefone da franquia mudou.
# Caso tenha mudado o sistema dever� alterar os telefones constante nos sites do clientes pertencentes a ele.
// die();
$sql_fone_franquia = "SELECT fone1 from cs2.franquia
					  WHERE id = '$id'";
$qry_fone_franquia = mysql_query($sql_fone_franquia,$con);
$fone_franquia = mysql_result($qry_fone_franquia,0,'fone1');

if ( $fone_franquia != $telefone ){
	
	$sql_cliente_franquia = "SELECT codloja FROM cs2.cadastro WHERE id_franquia = $id";
	$qry_cliente_franquia = mysql_query($sql_cliente_franquia,$con);
	while ( $cli = mysql_fetch_array($qry_cliente_franquia) ){
		$codloja = $cli['codloja'];
		require "../connect/conexao_conecta_virtual.php";
		$sql_virtual = "SELECT fra_nomesite	FROM dbsites.tbl_framecliente WHERE fra_codloja = $codloja";
// 		echo $sql_virtual;exit;
		$qry_virtual = mysql_query($sql_virtual,$con_virtual) or die ('Erro: '.$sql_virtual);
		@$fra_nomesite = mysql_result($qry_virtual,0,'fra_nomesite');
		
		if ( !empty($fra_nomesite) ){
			$fone_site = '('.substr($telefone,0,2).') '.substr($telefone,2,4).'-'.substr($telefone,6,4);		
			$msg = "Todos os direitos reservados - Web Control Empresas Pesquisas e Soluções Inteligentes - www.webcontrolempresas.com.br - $fone_site";
			$update = "UPDATE dbsitesv2.tbl_adicionais SET adi_rodape = '$msg' WHERE adi_codloja = $codloja";
			$qry_update = mysql_query($update,$con_virtual);
		}
		mysql_close($con_virtual);
	}
	
}

$comando = "UPDATE franquia SET
				senha 		= '$senha',
				senha_restrita = '$senha_restrita',
				fantasia	= '$franquia',
				razaosoc	= '$razao',
				cpfcnpj		= '$cnpj',
				endereco	= '$endereco',
				bairro		= '$bairro',
				cidade		= '$cidade',
				uf			= '$uf',
				cep			= '$cep',
				fone1		= '$telefone',
				fone2		= '$fone_res',
				fone3		= '$fone3',
				email		= '$email',
				nom01socio	= '$nome_prop1',
				doc01socio	= '$cpf1',
				cel01socio	= '$celular1',
				nom02socio 	= '$nome_prop2',
				doc02socio 	= '$cpf2',
				cel02socio 	= '$celular2',
				ctacte		= '$ctacte',
				banco		= '$banco',
				agencia		= '$agencia',
				tpconta		= '$tpconta',
				conta		= '$conta',
				titular		= '$titular',
				cpftitular	= '$cpftitular',
				operadora_1 = '$operadora_1',
				operadora_2 = '$operadora_2',
				qtd_contrato_mes = '$qtd_contrato_mes' ";
				
if ( ($_SESSION['id'] == 163) or ($_SESSION['id']==46) or ($_SESSION['id']==59) ){
	 $comando .= " , id_gerente	= '$id_gerente',
	 			  sitfrq = '$sitfrq',
				  situacao_repasse = '$sitrep',
				  classificacao = '$classificacao' ";
}

$comando .= ", data_abertura   = '$data_abertura',
				dt_cad	       = '$dt_cad',				
				tx_adesao	   = '$tx_adesao',
				tx_pacote	   = '$tx_pacote',
				tx_software	   = '$tx_software',
				data_apoio     = '$data_apoio',
				comissao_frqjr = '$participacao_frq'
			WHERE id = '$id'";

mysql_query($comando,$con);

//atualiza o jornal radios
$j_cidade1       = $_REQUEST['j_cidade1'];
$j_uf1           = $_REQUEST['j_uf1'];
$j_fone1		 = str_replace("-","",$_REQUEST['j_fone1']);
$j_fone2		 = str_replace("-","",$_REQUEST['j_fone2']);
$j_contato1      = $_REQUEST['j_contato1'];
$j_jornal_radio1 = $_REQUEST['j_jornal_radio1'];
$j_titular_conta1= $_REQUEST['j_titular_conta1'];
$j_cpf_cnpj1     = $_REQUEST['j_cpf_cnpj1'];
$j_banco1        = $_REQUEST['j_banco1'];
$j_agencia1 	 = $_REQUEST['j_agencia1'];
$j_conta1        = $_REQUEST['j_conta1'];
$j_email1        = $_REQUEST['j_email1'];

$j_cidade2       = $_REQUEST['j_cidade2'];
$j_uf2           = $_REQUEST['j_uf2'];
$j_fone3		 = str_replace("-","",$_REQUEST['j_fone3']);
$j_fone4		 = str_replace("-","",$_REQUEST['j_fone4']);
$j_contato2      = $_REQUEST['j_contato2'];
$j_jornal_radio2 = $_REQUEST['j_jornal_radio2'];
$j_titular_conta2= $_REQUEST['j_titular_conta2'];
$j_cpf_cnpj2     = $_REQUEST['j_cpf_cnpj2'];
$j_banco2        = $_REQUEST['j_banco2'];
$j_agencia2 	 = $_REQUEST['j_agencia2'];
$j_conta2        = $_REQUEST['j_conta2'];
$j_email2        = $_REQUEST['j_email2'];

$j_cidade3       = $_REQUEST['j_cidade3'];
$j_uf3           = $_REQUEST['j_uf3'];
$j_fone5		 = str_replace("-","",$_REQUEST['j_fone5']);
$j_fone6		 = str_replace("-","",$_REQUEST['j_fone6']);
$j_contato3      = $_REQUEST['j_contato3'];
$j_jornal_radio3 = $_REQUEST['j_jornal_radio3'];
$j_titular_conta3= $_REQUEST['j_titular_conta3'];
$j_cpf_cnpj3     = $_REQUEST['j_cpf_cnpj3'];
$j_banco3        = $_REQUEST['j_banco3'];
$j_agencia3 	 = $_REQUEST['j_agencia3'];
$j_conta3        = $_REQUEST['j_conta3'];
$j_email3        = $_REQUEST['j_email3'];

if($_REQUEST['grava_1'] == 1){
	$sql_jornal_1 = "UPDATE cs2.franquia_relacao_jornal SET
					cidade        = '$j_cidade1', 
					uf			  = '$j_uf1', 
					fone_1		  = '$j_fone1', 
					fone_2		  = '$j_fone2', 
					contato		  = '$j_contato1', 
					jornal_radio  = '$j_jornal_radio1', 
					titular_conta = '$j_titular_conta1', 
					cpf_cnpj      = '$j_cpf_cnpj1', 
					banco		  = '$j_banco1', 
					agencia       = '$j_agencia1', 
					conta         = '$j_conta1',
					email         = '$j_email1'
					WHERE
						id        = '{$_REQUEST['id_1']}'
					AND
					   id_franquia = '{$_REQUEST['id_franquia']}'";					      
	$qry_jornal_1 = mysql_query ($sql_jornal_1, $con);	
}else{
	$sql_jornal_1 = "INSERT INTO cs2.franquia_relacao_jornal (id_franquia, cidade, uf, fone_1, fone_2, contato, jornal_radio, titular_conta, cpf_cnpj, banco, agencia, conta, data_hora_cadastro, email)
					 VALUES('{$_REQUEST['id_franquia']}', '$j_cidade1', '$j_uf1', '$j_fone1', '$j_fone2', '$j_contato1', '$j_jornal_radio1', '$j_titular_conta1', '$j_cpf_cnpj1', '$j_banco1', '$j_agencia1', '$j_conta1', now(), '$j_email1')";
	$qry_jornal_1 = mysql_query ($sql_jornal_1, $con);
}

if($_REQUEST['grava_2'] == 1){
	$sql_jornal_2 = "UPDATE cs2.franquia_relacao_jornal SET
					cidade        = '$j_cidade2', 
					uf			  = '$j_uf2', 
					fone_1		  = '$j_fone3', 
					fone_2		  = '$j_fone4', 
					contato		  = '$j_contato2', 
					jornal_radio  = '$j_jornal_radio2', 
					titular_conta = '$j_titular_conta2', 
					cpf_cnpj      = '$j_cpf_cnpj2', 
					banco		  = '$j_banco2', 
					agencia       = '$j_agencia2', 
					conta         = '$j_conta2',
					email         = '$j_email2'
					WHERE
						id        = '{$_REQUEST['id_2']}'
					AND
					   id_franquia = '{$_REQUEST['id_franquia']}'";					      
	$qry_jornal_2 = mysql_query ($sql_jornal_2, $con);	
}else{
	$sql_jornal_2 = "INSERT INTO cs2.franquia_relacao_jornal (id_franquia, cidade, uf, fone_1, fone_2, contato, jornal_radio, titular_conta, cpf_cnpj, banco, agencia, conta, data_hora_cadastro, email)
					 VALUES('{$_REQUEST['id_franquia']}', '$j_cidade2', '$j_uf2', '$j_fone3', '$j_fone4', '$j_contato2', '$j_jornal_radio2', '$j_titular_conta2', '$j_cpf_cnpj2', '$j_banco2', '$j_agencia2', '$j_conta2', now(), '$j_email1')";
	$qry_jornal_2 = mysql_query ($sql_jornal_2, $con);
}

if($_REQUEST['grava_3'] == 1){
	$sql_jornal_3 = "UPDATE cs2.franquia_relacao_jornal SET
					cidade        = '$j_cidade3', 
					uf			  = '$j_uf3', 
					fone_1		  = '$j_fone5', 
					fone_2		  = '$j_fone6', 
					contato		  = '$j_contato3', 
					jornal_radio  = '$j_jornal_radio3', 
					titular_conta = '$j_titular_conta3', 
					cpf_cnpj      = '$j_cpf_cnpj3', 
					banco		  = '$j_banco3', 
					agencia       = '$j_agencia3', 
					conta         = '$j_conta3',
					email         = '$j_email3'
					WHERE
						id        = '{$_REQUEST['id_3']}'
					AND
					   id_franquia = '{$_REQUEST['id_franquia']}'";					      
	$qry_jornal_3 = mysql_query ($sql_jornal_3, $con);
}else{
	$sql_jornal_3 = "INSERT INTO cs2.franquia_relacao_jornal (id_franquia, cidade, uf, fone_1, fone_2, contato, jornal_radio, titular_conta, cpf_cnpj, banco, agencia, conta, data_hora_cadastro, email)
					 VALUES('{$_REQUEST['id_franquia']}', '$j_cidade3', '$j_uf3', '$j_fone5', '$j_fone6', '$j_contato3', '$j_jornal_radio3', '$j_titular_conta3', '$j_cpf_cnpj3', '$j_banco3', '$j_agencia3', '$j_conta3', now(), '$j_email1')";
	$qry_jornal_3 = mysql_query ($sql_jornal_3, $con);
}

//echo "<pre>";
//print_r($_REQUEST);
//exit;
echo "<script>alert(\"Franqueado atualizado com sucesso!\");</script>";
echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=area_restrita/most_franqueado.php&id=$id\";>";
?>
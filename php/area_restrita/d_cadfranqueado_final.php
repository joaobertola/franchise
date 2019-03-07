<?php
//session_start();
// require_once('../connect/sessao.php');
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

//coloca a data de hoje
$data = date('Y-m-d H:i:s');

$id = $_POST['id'];
$senha = $_POST['senha'];
$senha_restrita = $_POST['senha_restrita'];
$franquia = $_POST['franquia'];
$razao = $_POST['razao'];
$cnpj = $_POST['cnpj'];
$logradouro = $_POST['logradouro'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$endereco = $logradouro.", ".$numero." - ".$complemento;
$bairro = $_POST['bairro'];
$cidade = $_POST['localidade'];
$uf = $_POST['uf'];
$cep = $_POST['cep'];
$telefone = $_POST['telefone'];
$fone_res = $_POST['fone_res'];
$email = $_POST['email'];
$nome_prop1 = $_POST['nome_prop1'];
$cpf1 = $_POST['cpf1'];
$celular1 = $_POST['celular1'];
$operadora_1 = $_POST['operadora_1'];
$nome_prop2 = $_POST['nome_prop2'];
$cpf2 = $_POST['cpf2'];
$celular2 = $_POST['celular2'];
$operadora_2 = $_POST['operadora_2'];
$banco = $_POST['banco'];
$agencia = $_POST['agencia'];
$tpconta = $_POST['tpconta'];
$conta = $_POST['conta'];
$titular = $_POST['titular'];
$cpftitular = $_POST['cpftitular'];

$id_gerente = $_POST['id_gerente'];
$data_abertura = $_POST['data_abertura'];
$data_apoio = $_POST['data_apoio'];
$classificacao = $_POST['classificacao'];

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
$data_abertura = implode(preg_match("~\/~", $data_abertura) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_abertura) == 0 ? "-" : "/", $data_abertura)));

$data_apoio = implode(preg_match("~\/~", $data_apoio) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_apoio) == 0 ? "-" : "/", $data_apoio)));

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

$end = $logradouro." ".$numero." ".$complemento;
   
$comando = "INSERT INTO franquia(id, senha, senha_restrita, fantasia, razaosoc, cpfcnpj, endereco, bairro, cidade, uf, cep, fone1, fone2, email, nom01socio, doc01socio, cel01socio, nom02socio, doc02socio, cel02socio, dt_cad, sitfrq, usuario, tipo, banco, agencia, tpconta, conta, titular, cpftitular, tx_adesao, tx_pacote, tx_software, classificacao, id_gerente, data_apoio, data_abertura, operadora_1, operadora_2)
			VALUES ('$id','$senha','$senha_restrita','$franquia','$razao','$cnpj','$endereco','$bairro','$cidade','$uf','$cep','$telefone','$fone_res','$email','$nome_prop1','$cpf1','$celular1','$nome_prop2','$cpf2','$celular2','$data',0,'$id','b','$banco', '$agencia', '$tpconta', '$conta', '$titular', '$cpftitular', '$tx_adesao', '$tx_pacote', '$tx_software', '$classificacao', '$id_gerente', '$data_apoio', '$data_abertura', '$operadora_1', '$operadora_2')";
			
$res = mysql_query ($comando, $con);
$id_franquia = mysql_insert_id();

$cidade1       = $_REQUEST['cidade1'];
$uf1           = $_REQUEST['uf1'];
$fone1		   = str_replace("-","",$_REQUEST['fone1']);
$fone2		   = str_replace("-","",$_REQUEST['fone2']);
$contato1      = $_REQUEST['contato1'];
$jornal_radio1 = $_REQUEST['jornal_radio1'];
$titular_conta1= $_REQUEST['titular_conta1'];
$cpf_cnpj1     = $_REQUEST['cpf_cnpj1'];
$banco1        = $_REQUEST['banco1'];
$agencia1 	   = $_REQUEST['agencia1'];
$conta1        = $_REQUEST['conta1'];
$email1        = $_REQUEST['email1'];

$cidade2       = $_REQUEST['cidade2'];
$uf2           = $_REQUEST['uf2'];
$fone3		   = str_replace("-","",$_REQUEST['fone3']);
$fone4		   = str_replace("-","",$_REQUEST['fone4']);
$contato2      = $_REQUEST['contato2'];
$jornal_radio2 = $_REQUEST['jornal_radio2'];
$titular_conta2= $_REQUEST['titular_conta2'];
$cpf_cnpj2     = $_REQUEST['cpf_cnpj2'];
$banco2        = $_REQUEST['banco2'];
$agencia2 	   = $_REQUEST['agencia2'];
$conta2        = $_REQUEST['conta2'];
$email2        = $_REQUEST['email2'];

$cidade3       = $_REQUEST['cidade3'];
$uf3           = $_REQUEST['uf3'];
$fone5		   = str_replace("-","",$_REQUEST['fone5']);
$fone6		   = str_replace("-","",$_REQUEST['fone6']);
$contato3      = $_REQUEST['contato3'];
$jornal_radio3 = $_REQUEST['jornal_radio3'];
$titular_conta3= $_REQUEST['titular_conta3'];
$cpf_cnpj3     = $_REQUEST['cpf_cnpj3'];
$banco3        = $_REQUEST['banco3'];
$agencia3 	   = $_REQUEST['agencia3'];
$conta3        = $_REQUEST['conta3'];
$email3        = $_REQUEST['email3'];

$sql_jornal_1 = "INSERT INTO cs2.franquia_relacao_jornal (id_franquia, cidade, uf, fone_1, fone_2, contato, jornal_radio, titular_conta, cpf_cnpj, banco, agencia, conta, data_hora_cadastro, email)
				 VALUES('$id_franquia', '$cidade1', '$uf1', '$fone1', '$fone2', '$contato1', '$jornal_radio1', '$titular_conta1', '$cpf_cnpj1', '$banco1', '$agencia1', '$conta1', now(), '$email1')";
$qry_jornal_1 = mysql_query($sql_jornal_1, $con);

$sql_jornal_2 = "INSERT INTO cs2.franquia_relacao_jornal (id_franquia, cidade, uf, fone_1, fone_2, contato, jornal_radio, titular_conta, cpf_cnpj, banco, agencia, conta, data_hora_cadastro, email)
				 VALUES('$id_franquia', '$cidade2', '$uf2', '$fone3', '$fone4', '$contato2', '$jornal_radio2', '$titular_conta2', '$cpf_cnpj2', '$banco2', '$agencia2', '$conta2', now(), '$email2')";
$qry_jornal_2 = mysql_query($sql_jornal_2, $con);

$sql_jornal_3 = "INSERT INTO cs2.franquia_relacao_jornal (id_franquia, cidade, uf, fone_1, fone_2, contato, jornal_radio, titular_conta, cpf_cnpj, banco, agencia, conta, data_hora_cadastro, email)
				 VALUES('$id_franquia', '$cidade3', '$uf3', '$fone5', '$fone6', '$contato3', '$jornal_radio3', '$titular_conta3', '$cpf_cnpj3', '$banco3', '$agencia3', '$conta3', now(), '$email3')";
$qry_jornal_3 = mysql_query($sql_jornal_3, $con);

$res = mysql_close($con);
echo "<script>alert('Franqueado Cadastrado com Sucesso!');</script>";
echo "<meta http-equiv='refresh' content='0; url= painel.php?pagina1=area_restrita/most_franqueado.php&id=$id';>";
?>
<?php

//session_start();

$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];

//coloca a data de hoje
$data = date('Y-m-d H:i:s');

$id = $_POST['id'];
$senha = $_POST['senha'];
$senha_restrita = $_POST['senha_restrita'];
# 
$franquia = $_POST['franquia'];
$razao = $_POST['franquia'];

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
$nome_prop2 = $_POST['nome_prop2'];
$cpf2 = $_POST['cpf2'];
$celular2 = $_POST['celular2'];
$banco = $_POST['banco'];
$agencia = $_POST['agencia'];
$tpconta = $_POST['tpconta'];
$conta = $_POST['conta'];
$titular = $_POST['titular'];
$cpftitular = $_POST['cpftitular'];
$comissao = $_POST['comissao'];

$idfranquiamaster = $_REQUEST['id_franquia'];

# Buscando estes dados da Franquia MASTER.

$comando = "select tx_adesao, tx_pacote, tx_software from cs2.franquia where id = '$idfranquiamaster'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$tx_adesao = $matriz['tx_adesao'];
$tx_pacote = $matriz['tx_pacote'];
$tx_software = $matriz['tx_software'];

//trata as variaveis para o formato padrão
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
   
$comando = "INSERT INTO franquia( senha, senha_restrita, fantasia, razaosoc, cpfcnpj, endereco, bairro, cidade, uf, cep, fone1, fone2, email01socio, nom01socio, doc01socio, cel01socio, nom02socio, doc02socio, cel02socio, dt_cad, sitfrq, usuario, tipo, banco, agencia, tpconta, conta, titular, cpftitular, tx_adesao, tx_pacote, tx_software, id_franquia_master, classificacao, comissao_frqjr) 
			VALUES ('$senha','$senha_restrita','$franquia','$razao','$cnpj','$endereco','$bairro','$cidade','$uf','$cep','$telefone','$fone_res','$email','$nome_prop1','$cpf1','$celular1','$nome_prop2','$cpf2','$celular2','$data',0,'$id','b','$banco', '$agencia', '$tpconta', '$conta', '$titular', '$cpftitular', '$tx_adesao', '$tx_pacote', '$tx_software', '$idfranquiamaster', 'J', $comissao)";
$res = mysql_query ($comando, $con);

# buscando Id no novo Franqueado JR

$sql_id = "SELECT id FROM cs2.franquia WHERE senha = '$senha'";
$query_id = mysql_query ($sql_id, $con) or die ("erro sql  $sql_id");
$consulta = mysql_fetch_array($query_id);
$id = $consulta['id'];

$sql_update = "UPDATE cs2.franquia SET usuario = '$id' WHERE id = '$id'";
$res = mysql_query ($sql_update, $con);

echo "<script>alert(\"Franqueado JUNIOR => cadastrado com sucesso ! \");</script>";
echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=franqueado_jr/mostra_franqueado_jr.php&id=$id\";>";
?>
<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php"; 

$codloja 			= $_POST['codloja'];
$razao 				= $_POST['razao'];
$nomef 				= $_POST['nomef'];
$endereco 			= $_POST['endereco'];
$bairro 			= $_POST['bairro'];
$cidade 			= $_POST['cidade'];
$uf 				= $_POST['uf'];
$cep 				= $_POST['cep'];
$cnpj				= $_POST['cnpj'];
$telefone 			= $_POST['telefone'];
$fax 				= $_POST['fax'];
$email 				= $_POST['email'];
$fone_res 			= $_POST['fone_res'];
$celular 			= $_POST['celular'];
$nome_prop1 		= $_POST['nome_prop1'];
$cpf1 				= $_POST['cpf1'];
$nome_prop2 		= $_POST['nome_prop2'];
$cpf2 				= $_POST['cpf2'];
$ramo 				= $_POST['ramo'];
$vendedor 			= $_POST['vendedor'];
$classe 			= $_POST['classe'];
$fatura 			= $_POST['fatura'];
$obs 				= $_POST['obs'];
$franqueado 		= $_POST['franqueado'];
$banco_cliente 		= $_REQUEST['banco_cliente'];
$agencia_cliente 	= $_POST['agencia_cliente'];
$conta_cliente 		= $_POST['conta_cliente'];
$cpfcnpj_doc 		= $_POST['cpfcnpj_doc'];
$nome_doc 			= $_POST['nome_doc'];
$tpconta 			= $_POST['tpconta'];
$id_franquia_jr 	= $_POST['id_franquia_jr'];

$renegociacao_tabela = $_POST['renegociacao_tabela'];
$dia = substr($renegociacao_tabela, 0,2);
$mes = substr($renegociacao_tabela, 3,2);
$ano = substr($renegociacao_tabela, 6,9);	
$data_bd.=$ano;
$data_bd.="-";
$data_bd.=$mes;
$data_bd.="-";
$data_bd.=$dia; 

//trata as variaveis para o formato padrão
$telefone=str_replace("(","",$telefone);
$telefone=str_replace(")","",$telefone);
$telefone=str_replace("-","",$telefone);

$fax=str_replace("(","",$fax);
$fax=str_replace(")","",$fax);
$fax=str_replace("-","",$fax);

$celular=str_replace("(","",$celular);
$celular=str_replace(")","",$celular);
$celular=str_replace("-","",$celular);

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

$cep=str_replace("-","",$cep);
$cep=str_replace(".","",$cep);

if ( empty($banco_cliente) ) 
  $banco_cliente='NULL';
  
if ( empty($agencia_cliente) ) 
	$agencia_cliente = 'NULL';
else
	$agencia_cliente = str_replace("-","",$agencia_cliente);

if ( empty($conta_cliente) ) 
	$conta_cliente = 'NULL';
else
	$conta_cliente = str_replace("-","",$conta_cliente);


$query = "UPDATE cadastro SET 
razaosoc ='$razao',
nomefantasia ='$nomef',
end ='$endereco',
bairro ='$bairro',
cidade ='$cidade',
uf ='$uf',
cep ='$cep',
insc ='$cnpj',
fone ='$telefone',
fax ='$fax',
email ='$email',
fone_res ='$fone_res',
celular = '$celular',
socio1 ='$nome_prop1',
cpfsocio1 ='$cpf1',
socio2 ='$nome_prop2',
cpfsocio2 ='$cpf2',
ramo_atividade ='$ramo',
vendedor ='$vendedor',
classe='$classe',
emissao_financeiro ='$fatura',
obs ='$obs'
";

if($_SESSION['ss_tipo'] == "a"){
 $query .= " , 
			id_franquia     ='$franqueado' , 
			id_franquia_jr  = '$id_franquia_jr' ";
}

if( ($_SESSION['ss_tipo'] == "a") || ($_SESSION['ss_tipo'] == "c") ){
	if ( $_REQUEST['id_funcionario'] != '' ){
		
		$query .= " , 
			banco_cliente   = '$banco_cliente',
			agencia_cliente = '$agencia_cliente',
			conta_cliente   = '$conta_cliente',
			cpfcnpj_doc     = '$cpfcnpj_doc',
			tpconta         = '$tpconta',
			nome_doc        = '$nome_doc' ";
			
		$sqlx = "INSERT INTO cadastro_mudanca_cta(codloja, banco, agencia, conta, tpconta, doc, nome, id_funcionario, data)
				 VALUES('$codloja', '$banco_cliente', '$agencia_cliente', '$conta_cliente', '$tpconta', '$cpfcnpj_doc', '$nome_doc', '{$_REQUEST['id_funcionario']}', NOW())";
		mysql_query($sqlx, $con);
	}
}

$query .= " 
		 , renegociacao_tabela = '$data_bd'		  
		WHERE codloja ='$codloja'";


// registrando log
$teste = str_replace(chr(39),'',$query);
$sql = "insert into cs2.sql_cadastro(comando_sql,datahora) values('$teste',now())";
mysql_query($sql, $con);

// continuando
mysql_query($query,$con);


//$pagina1 = "clientes/a_altcliente.php";
mysql_close($con);
header("Location: ../painel.php?pagina1=clientes/most_cliente.php&codloja=$codloja "); 	
//echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=clientes/most_cliente.php&codloja=$codloja \";>";
?>
<?php
require_once('../connect/sessao.php');
//session_start();
include("../../../web_control/funcao_php/conexao.php");
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//$data_cadastro = date("Y-m-d");
//
//if ( $name == "" ){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

//echo "Funcao DESABILITADA. Contate o Departamento de Inform�tica.";
//exit;		

$id_modulo = $_REQUEST['id_modulo'];
$cod_cliente = $_REQUEST['cod_cliente'];

if( $_SESSION['id'] != '163'){
	echo "PERMISSAO NEGADA !";
	exit;
}

//echo "PERMISSAO NEGADA !";
//exit;


// excluindo Atendimento
if($id_modulo == "1"){
	$sql = "DELETE   FROM base_web_control.atendimento WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry = mysql_query($sql);	
	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=1&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Classifica��o & Produto & Entrada de Nota Fiscal

if($id_modulo == "2"){
	$sql_1 = "DELETE   FROM base_web_control.classificacao WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_1 = mysql_query($sql_1);
	
	$sql_2 = "DELETE   FROM base_web_control.produto WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_2 = mysql_query($sql_2);
	
	$sql_3 = "DELETE   FROM base_web_control.nota_fiscal WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_3 = mysql_query($sql_3);
	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=2&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Produto & Entrada de Nota Fiscal     
if($id_modulo == "3"){
	$sql_1 = "DELETE   FROM base_web_control.produto WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_1 = mysql_query($sql_1);
	
	$sql_2 = "DELETE   FROM base_web_control.nota_fiscal WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_2 = mysql_query($sql_2);

	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=3&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Entrada de Nota Fiscal
if($id_modulo == "4"){
	$sql_1 = "DELETE   FROM base_web_control.nota_fiscal WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_1 = mysql_query($sql_1);

	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=4&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Cliente & Atendimento & Venda
if($id_modulo == "5"){	
	//seleciona as vendas
	$sql_1 = "SELECT id FROM base_web_control.venda WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_1 = mysql_query($sql_1);
	while($rs_1 = mysql_fetch_array($qry_1)){
		$id_venda_tmp .= $rs_1['id'].",";	
	}
	$id_venda = substr($id_venda_tmp, 0, -1);  	
	
	$sql_2 = "DELETE FROM base_web_control.venda WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_2 = mysql_query($sql_2);	

	$sql_3 = "DELETE FROM base_web_control.venda_itens WHERE id_venda IN($id_venda)";
	$qry_3 = mysql_query($sql_3);	

	$sql_5 = "DELETE FROM base_web_control.venda_pagamento WHERE id_venda IN($id_venda)";
	$qry_5 = mysql_query($sql_5);	

	$sql_6 = "DELETE FROM base_web_control.venda_pagamento_cheque WHERE id_Venda IN($id_venda)";
	$qry_6 = mysql_query($sql_6);

	$sql_7 = "DELETE FROM base_web_control.atendimento WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_7 = mysql_query($sql_7);	
	
	$sql_8 = "DELETE FROM base_web_control.cliente WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_8 = mysql_query($sql_8);	
	
	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=5&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Compromisso
if($id_modulo == "6"){
	$sql_1 = "DELETE FROM base_web_control.compromisso WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_1 = mysql_query($sql_1);	

	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=6&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Contas � Pagar
if($id_modulo == "7"){
	$sql_1 = "DELETE FROM base_web_control.contas_pagar WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_1 = mysql_query($sql_1);	

	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=7&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Contas � Pagar & Descri��o de Contas � Pagar
if($id_modulo == "8"){
	$sql_1 = "DELETE FROM base_web_control.contas_pagar WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_1 = mysql_query($sql_1);	

	$sql_2 = "DELETE FROM base_web_control.descricao_contas_pagar WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_2 = mysql_query($sql_2);	

	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=8&cod_cliente=$cod_cliente");
	exit;
}

// Fornecedor & Servi�os do Fornecedor & Entrada de Nota Fiscal
if($id_modulo == "9"){
	$sql_1 = "DELETE FROM base_web_control.fornecedor WHERE id_cadastro = '{$_REQUEST['id_cadastro']}' AND tipo_cadastro = 'F'";
	$qry_1 = mysql_query($sql_1);	

	$sql_2 = "DELETE FROM base_web_control.fornecedor_servico WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_2 = mysql_query($sql_2);	

	$sql_3 = "DELETE FROM base_web_control.nota_fiscal WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_3 = mysql_query($sql_3);	

	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=9&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Venda
if($id_modulo == "10"){
	//seleciona as vendas
	$sql_1 = "SELECT id FROM base_web_control.venda WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_1 = mysql_query($sql_1);
	while($rs_1 = mysql_fetch_array($qry_1)){
		$id_venda_tmp .= $rs_1['id'].",";	
	}
	$id_venda = substr($id_venda_tmp, 0, -1);  	
	
	$sql_2 = "DELETE FROM base_web_control.venda WHERE id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_2 = mysql_query($sql_2);	

	$sql_3 = "DELETE FROM base_web_control.venda_itens WHERE id_venda IN($id_venda)";
	$qry_3 = mysql_query($sql_3);	

	$sql_5 = "DELETE FROM base_web_control.venda_pagamento WHERE id_venda IN($id_venda)";
	$qry_5 = mysql_query($sql_5);	

	$sql_6 = "DELETE FROM base_web_control.venda_pagamento_cheque WHERE id_Venda IN($id_venda)";
	$qry_6 = mysql_query($sql_6);

	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=10&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Eliminar Todas as Entradas
if($id_modulo == "11"){
	$sql_1 = "DELETE FROM base_web_control.fornecedor WHERE id_cadastro = '{$_REQUEST['id_cadastro']}' AND tipo_cadastro = 'C'";
	$qry_1 = mysql_query($sql_1);	

	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=11&cod_cliente=$cod_cliente");
	exit;
}

// excluindo Ordem de Servi�os
if($id_modulo == "12"){
	
	$sql_1 = "SELECT a.id from base_web_control.ordem_servico a
			  INNER JOIN base_web_control.cliente b ON a.id_cliente  = b.id
			  WHERE b.id_cadastro = '{$_REQUEST['id_cadastro']}'";
	$qry_1 = mysql_query($sql_1);	
	while($rs_1 = mysql_fetch_array($qry_1)){
		$id_os = $rs_1['id'];	
		$sql_2 = "DELETE FROM base_web_control.ordem_servico_itens WHERE id_servico = '$id_os'";
		$qry_2 = mysql_query($sql_2);
		$sql_3 = "DELETE FROM base_web_control.ordem_servico WHERE id = '$id_os'";
		$qry_3 = mysql_query($sql_3);
	}
	header("Location: ../painel.php?pagina1=area_restrita/web_control_eliminar_informacoes.php&msg=12&cod_cliente=$cod_cliente");
	exit;
}


?>


<?php

function limpaGeralMascaras($p_paramento){
	$p_paramento = str_replace(".","",$p_paramento);
	$p_paramento = str_replace("-","",$p_paramento);
	$p_paramento = str_replace("/","",$p_paramento);
	$p_paramento = str_replace(" ","",$p_paramento);
	$p_paramento = str_replace("'","",$p_paramento);
	$p_paramento = str_replace("(","",$p_paramento);
	$p_paramento = str_replace(")","",$p_paramento);
	return $p_paramento;
}

function limpaStringMaiscula($p_paramento){
	$p_paramento = str_replace("'","",$p_paramento);
	$p_paramento = strtoupper($p_paramento);
	return $p_paramento;
}

function converteDataGravaBancoFuncionario($p_data_padrao){
       $dia = substr($p_data_padrao, 0,2);
	   $mes = substr($p_data_padrao, 3,2);
	   $ano = substr($p_data_padrao, 6,9);	
	   $data_bd.=$ano;
	   $data_bd.="-";
	   $data_bd.=$mes;
	   $data_bd.="-";
	   $data_bd.=$dia;
	   return $data_bd;
}

include("../connect/conexao_conecta.php");

$data_pgto	= converteDataGravaBancoFuncionario($_REQUEST['data_pgto']);
$valor_pgto = $_REQUEST['valor'];
$valor_pgto = str_replace(".","",$valor_pgto);
$valor_pgto = str_replace(",",".",$valor_pgto);
$descricao  = $_REQUEST['descricao'];
$id_func    = $_REQUEST['id_func'];
$id_lanc    = $_REQUEST['id_lanc'];

if($_REQUEST['acao'] == 'G'){
	// Novo registro
	$sql = "INSERT INTO cs2.contacorrente_funcionario(id_func, data_pgto, valor_pgto, descricao, origem)
			VALUES('$id_func', '$data_pgto', '$valor_pgto', '$descricao', '2')";
	$qry = mysql_query($sql, $con);
	$id = mysql_insert_id();
	if($qry){ ?>
    <script language="javascript">
		alert('Registro INCLUIDO com sucesso');
		window.location.href="../painel.php?pagina1=Franquias/extrato_pagamentos_funcionarios.php&id=<?=$id_func?>";
	</script>
	<?php }
}

if($_REQUEST['acao'] == 'A'){  // Alterar dados
	$sql = "UPDATE cs2.contacorrente_funcionario SET
					data_pgto = '$data_pgto',
         			valor_pgto = '$valor_pgto',
         			descricao = '$descricao'
			WHERE id = '$id_lanc'";
	$qry = mysql_query($sql, $con) or die ("Erro SQL: $sql");
  
  if($qry){ ?>
    <script language="javascript">
		alert('Registro Alterado com sucesso');
		window.location.href="../painel.php?pagina1=Franquias/extrato_pagamentos_funcionarios.php&id=<?=$id_func?>";
	</script>
	<?php }
}

if($_REQUEST['acao'] == 'D'){  // Apagar dados
	$sql = "DELETE FROM cs2.contacorrente_funcionario 
			WHERE id = '$id_lanc'";
	$qry = mysql_query($sql, $con) or die ("Erro SQL: $sql");
  
  if($qry){ ?>
    <script language="javascript">
		alert('Registro APAGADO com sucesso');
		window.location.href="painel.php?pagina1=Franquias/extrato_pagamentos_funcionarios.php&id=<?=$id_func?>";
	</script>
	<?php }
}
?>
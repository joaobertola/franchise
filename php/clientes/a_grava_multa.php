<?php
require "connect/sessao.php";
require "connect/funcoes.php";

$acao = $_REQUEST['acao'] == 0 ? '1' : '0';
$operacao = $_REQUEST['acao'] == 0 ? '1' : '0';
$texto = $_REQUEST['acao'] == 0 ? "MULTA IRREGULARIDADE CONTRATUAL ({$_REQUEST['codigo']})" : "ESTORNO DE IRREGULARIDADE CONTRATUAL ({$_REQUEST['codigo']})";

if( ($_SESSION['id'] == 163) or ($_SESSION['id'] == 46) or ($_SESSION['id'] == 4) or ($_SESSION['id'] == 1204) ) {
	
	$sql = "SELECT id_franquia FROM cs2.cadastro WHERE codloja = {$_REQUEST['codloja']}";
	$qry = mysql_query ($sql, $con);
	$id_franqueado = mysql_result($qry,0,'id_franquia');
	
	$sql_p = "UPDATE cs2.cadastro 
				  SET multa_contratual = '$acao'
			 WHERE codloja = '{$_REQUEST['codloja']}'";
	$qry_p = mysql_query ($sql_p, $con);
	
	$sql_p = "INSERT INTO cs2.contacorrente(franqueado, discriminacao, valor, operacao, data)
		      VALUES( '$id_franqueado' , '$texto' , 200.00, '$operacao' , NOW() )";
	$qry_p = mysql_query ($sql_p, $con);
	
}
		
?>
<script language="javascript">
	alert('Registro Gravado com sucesso');
	window.location.href="painel.php?pagina1=clientes/a_cons_id.php&id=<?=$_REQUEST['codloja']?>";
</script>

<?php
include("../connect/conexao_conecta.php");


if($_REQUEST['acao'] == '1'){
$sql = "UPDATE cs2.pretendentes SET status = '3' WHERE id = '{$_REQUEST['id']}'";
$res = mysql_query ($sql, $con);

$sql5 = "INSERT INTO ocorr_pretendentes (pretendente, msg, data) 
		VALUES 
		('{$_REQUEST['id']}', 'PROPOSTA DE FRANQUIA NEGADA PELO DEPARTAMENTO DE FRANQUIAS', now())";
		$qr = mysql_query($sql5, $con) or die ("erro ao incluir o comentario".mysql_error());
}elseif($_REQUEST['acao'] == '2'){
$sql = "UPDATE cs2.pretendentes SET status = '2' WHERE id = '{$_REQUEST['id']}'";
$res = mysql_query ($sql, $con);

$sql5 = "INSERT INTO ocorr_pretendentes (pretendente, msg, data) 
		VALUES 
		('{$_REQUEST['id']}', 'N&Atilde;O OBTIVE CONTATO', now())";
		$qr = mysql_query($sql5, $con) or die ("erro ao incluir o comentario".mysql_error());
}
?>
<script language="javascript">
	window.location.href="../painel.php?pagina1=area_restrita/d_pretendentes.php&id=<?=$_REQUEST['id']?>";
</script>
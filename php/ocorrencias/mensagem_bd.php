<?php
require "connect/sessao.php";
require "connect/conexao_conecta.php";

//EXCLUI A MENSAGEM
if( ($_REQUEST['acao'] == 1) and ($_SESSION['id'] == 163) ){
	$sql = "DELETE FROM cs2.ocorrencias 
			WHERE id = '{$_REQUEST['id']}'
			AND codigo = '{$_REQUEST['codloja']}'";
	$qry = mysql_query($sql, $con);?>
		<script language="javascript">
			window.location.href="painel.php?pagina1=ocorrencias/mensagens.php&codloja=<?=$_REQUEST['codloja']?>&pagina=<?=$_REQUEST['pagina']?>";
		</script>
	<?php
	exit;
}

//ALTERA A MENSAGEM
if( ($_REQUEST['acao'] == 2) and ($_SESSION['id'] == 163) ){
	$sql = "UPDATE cs2.ocorrencias SET ocorrencia = '{$_REQUEST['ocorrencia']}'
			WHERE id = '{$_REQUEST['id']}'
			AND codigo = '{$_REQUEST['codloja']}'";
	$qry = mysql_query($sql, $con);
?>
		<script language="javascript">
			window.location.href="painel.php?pagina1=ocorrencias/mensagens.php&codloja=<?=$_REQUEST['codloja']?>&pagina=<?=$_REQUEST['pagina']?>";
		</script>
	<?php
	exit;
} 
?>
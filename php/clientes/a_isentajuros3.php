<?php

require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$user = strtoupper($_SESSION['ss_name']);

if ( $user <> 'WELLINGTON' and $user <> 'FRANQUIASNACIONAL' and $user <> 'ADMINISTRATIVO' and $user <> 'ANANIAS'){
	echo "usuario sem permissao.  $user";
	exit;
}

// Isentando o cliente dos juros

$sql_pesquisa = "SELECT isento_juros FROM cs2.titulos WHERE numdoc = '".$_REQUEST['numdoc']."'";
$qry_pesquisa = mysql_query($sql_pesquisa,$con) or die("erro sql 01");
if ( mysql_num_rows($qry_pesquisa) == 0 ){
	echo "Nenhum registro encontrado para o Documento";
	exit;
}
$registro = mysql_fetch_array($qry_pesquisa);
$isento = $registro['isento_juros'];

if ( $isento == 'S' ) $acao = 'N';
else $acao = 'S'; 


$sql_isenta = "UPDATE cs2.titulos SET isento_juros = '$acao' where numdoc = '".$_REQUEST['numdoc']."'";
$qry_isenta = mysql_query($sql_isenta,$con) or die("erro sql 02");

?>
<script language="javascript">
//alert('Registro alterado com sucesso ! ');
window.location.href="../painel.php?pagina1=clientes/a_isentajuros2.php&codloja=<?=$_REQUEST['codloja']?>&logon=<?=$_REQUEST['logon']?>&razaosoc=<?=$_REQUEST['razaosoc']?>";

</script>
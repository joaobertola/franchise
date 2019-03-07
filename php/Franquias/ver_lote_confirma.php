<?
	
include("../connect/sessao_r.php");
include("../connect/conexao_conecta.php");
	
$dtconfirmacao     = $_REQUEST['dtconfirmacao'];
$dtconfirmacao     = substr($dtconfirmacao,6,4).'-'.substr($dtconfirmacao,3,2).'-'.substr($dtconfirmacao,0,2);
$lote_baixar       = $_REQUEST['lote_baixar'];
$referencia_baixar = $_REQUEST['referencia_baixar'];
$rps_inicio_baixa  = $_REQUEST['rps_inicio_baixa'];
$rps_final_baixa   = $_REQUEST['rps_final_baixa'];

$sql_update = " UPDATE cs2.nota_xmlgerado 
				SET
					status = 'L',
					data_liberacao = '$dtconfirmacao'
				WHERE numero_lote = '$lote_baixar'";
$qry_update = mysql_query($sql_update) or die($sql_update);

$sql_update = " UPDATE cs2.nota_controle
				SET
					numero_lote = '$rps_inicio_baixa',
					numero_rps = '$rps_final_baixa'";
$qry_update = mysql_query($sql_update) or die($sql_update);


?>
<script language="javascript">
	alert('Registro atualizado com sucesso !!!');history.back();
</script>
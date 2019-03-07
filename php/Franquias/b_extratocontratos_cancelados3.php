<?
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$data_inicial = $_REQUEST['dti'];
$data_final   = $_REQUEST['dtf'];
$vendedor     = $_REQUEST['vendedor'];
$franqueado   = $_REQUEST['franquia'];

$sql = "SELECT 
			count(*) qtd, c.motivo
		FROM cadastro a
		INNER JOIN pedidos_cancelamento b ON a.codloja = b.codloja
		INNER JOIN motivo_cancel c ON b.id_mot_cancelamento  = c.id
		WHERE 
			b.data_documento BETWEEN '$data_inicial' AND '$data_final' 
			AND a.id_franquia = $franqueado
			AND a.vendedor = '$vendedor'
		GROUP BY b.id_mot_cancelamento
		ORDER BY qtd DESC";
$qry = mysql_query($sql,$con);
?>
<table align='center'  width="500" border="0" cellpadding="0" cellspacing="1" class="bodyText">
<tr>
	<td colspan="2" align="center">CIENTES CANCELADOS<br>Periodo: <?=$data_inicial?> a <?=$data_final?></td>
</tr>
<tr height="20" bgcolor="#B6CBF6">
	<td align="center" colspan="2">Aut&ocirc;nomo :<?=$vendedor?></td>
</tr>
<tr height="20" bgcolor="#B6CBF6">
	<td align="center" width="40%" >Quantidade</td>
	<td align="center" width="60%" >Motivo</td>
</tr>
<?
while ( $reg= mysql_fetch_array($qry) ){
	$qtd    = $reg['qtd'];
	$motivo = $reg['motivo'];
	$tot   += $qtd;
	echo "<tr>
			<td align='center'>$qtd</td>
			<td align='left'>$motivo</td>
		  </tr>";
}
?>
<tr>
	<td colspan="2"><hr></td>
</tr>
<tr>
	<td align="center"><?=$tot?></td>
	<td align="center">&nbsp;</td>
</tr>

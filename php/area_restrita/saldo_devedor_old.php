<script language="javascript">

function desconta(codigo){
	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","clientes/salva_nois2.php?cliente="+codigo,true);
	xmlhttp.send();
}

function CheckAll() {

	for (var i=0;i<document.form.elements.length;i++) {
		var x = document.form.elements[i];
		if (x.name == 'escolha[]')
			x.checked = document.form.selall.checked;
	}
}

function CheckAll2() {

	for (var i=0;i<document.form.elements.length;i++) {
		var x = document.form.elements[i];
		if (x.name == 'escolha2[]')
			x.checked = document.form.selall2.checked;
	}
}

function Debitar_todos(){
	frm = document.form;
	frm.action = 'salva_nois2.php';
	frm.submit();

}

</script>

<form action="#" method="post" class="form" name="form">
<table align='center' width='800' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
<tr height='20' bgcolor='FF9966'>
	<td>Codigo</td>
	<td>Cliente</td>
	<td>Contrato</td>
	<td>Saldo</td>

</tr>
<?php

$con = @mysql_pconnect("10.2.2.3", "csinform", "inform4416#scf");
if (!$con) {
	echo 'Erro na conexao com o Servidor<br>';
	echo mysql_error();
	exit;
} else {
	$database = mysql_select_db("cs2",$con);
	if (!$database) {
		echo 'Erro na conex�o com o Banco de dados<br>';
		echo mysql_error();
	}
}

$sql = "SELECT a.codloja, b.nomefantasia, b.sitcli
		FROM cs2.contacorrente_recebafacil a
	    INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
		GROUP BY a.codloja";
$qry = mysql_query($sql,$con);
$cont=0;
$total_pa=0;
$total_pc=0;
$total_prejuizo = 0;
$qtd_pa=0;
$qtd_pc=0;
while ( $reg = mysql_fetch_array( $qry ) ){

	$codloja = $reg['codloja'];
	$valor   = $reg['valor'];
	$nome    = $reg['nomefantasia'];
	$sitcli  = $reg['sitcli'];
	
	$sql_saldo = "SELECT mid(logon,1,LOCATE('S',logon)-1) as logon FROM cs2.logon WHERE codloja='$codloja'";
	$qr2 = mysql_query($sql_saldo,$con);
	$logon = mysql_result($qr2,0,'logon');
	
	if ( $sitcli == 2 ) $st_ctr = "Ctr Cancelado";
	else $st_ctr = "Ctr Ativo";
	
	$cor_grid = '#F0F0F0';
	$a_cor = array('#FFFFFF', $cor_grid);
		
	// pega o saldo do credi�rio/credupere
	$sql_saldo = "SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='$codloja' order by id";
	$qr2 = mysql_query($sql_saldo,$con) or die ("\nErro ao gerar o extrato\n".mysql_error()."\n\n");
	while ($matriz = mysql_fetch_array($qr2)) {
		$saldo_crediario 	= number_format($matriz['saldo'],2,",",".");
		$sdo_crediario      = $matriz['saldo'];
	}
	if ( $saldo_crediario < 0 ){
		$cont++;
		$total_prejuizo       += $sdo_crediario;
		if ( $sitcli == 2 ){
			$total_pc += $sdo_crediario;
			$qtd_pc++;
		}else{
			$total_pa += $sdo_crediario;
			$qtd_pa++;
		}
		
		echo "<tr bgcolor=".$a_cor[$cont % 2].">
				<td>$logon</td>
				<td>$nome</td>
				<td>$st_ctr</td>
				<td>$saldo_crediario</td>
			  </tr>
			";
		}
}
$total_prejuizo 	= number_format($total_prejuizo,2,",",".");
$total_pa			= number_format($total_pa,2,",",".");
$total_pc			= number_format($total_pc,2,",",".");
?>
    <tr>
		<td colspan="5"><hr></td>
	</tr>
	<tr>
		<td colspan="5">Total Debito Cancelados: (<?=$qtd_pc?>) R$ <?=$total_pc?></td>
	</tr>
    <tr>
		<td colspan="5">Total Debito Ativos: (<?=$qtd_pa?>) R$ <?=$total_pa?></td>
	</tr>
    <tr>
		<td colspan="5"><hr>Listados <?=$cont?> registros</td>
	</tr>
    <tr>
		<td colspan="5">Total a debitar: R$ <?=$total_prejuizo?></td>
	</tr>
</table>
</form>
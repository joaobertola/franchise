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
	<td>Id</td>
	<td>Cliente</td>
	<td>Valor a Debitar</td>
	<td>Saldo</td>
    <td>Devedor ainda</td>
    <td align="center"><input type=checkbox name="selall" onClick="CheckAll()">Marcar todos</td>
    
    <td align="center"><input type=checkbox name="selall2" onClick="CheckAll2()">Marcar todos</td>
    
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
		echo 'Erro na conexão com o Banco de dados<br>';
		echo mysql_error();
	}
}

$sql = "SELECT a.coluna1, a.valor, a.coluna2, a.codloja, a.coluna3, b.nomefantasia
		FROM cs2.acerto_saldoerrado a
	    INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
		WHERE pago = 0 ";
$qry = mysql_query($sql,$con);
$cont=0;
$total_debitar=0;
$total_retido = 0;
while ( $reg = mysql_fetch_array( $qry ) ){
	$cont++;
	$codloja = $reg['codloja'];
	$valor   = $reg['valor'];
	$nome    = $reg['nomefantasia'];
	$valor   = ($valor * 1 ) / 100;
	$total_debitar 	+= $valor; 
	$valor   += 8; 
	$valor_debitar 	= number_format($valor,2,",",".");
	

	$cor_grid = '#F0F0F0';
	$a_cor = array('#FFFFFF', $cor_grid);
		
	// pega o saldo do crediário/credupere
	$saldo_crediario = '0,00';
	$sdo_crediario = 0;
	$sql_saldo = "SELECT saldo FROM cs2.contacorrente_recebafacil WHERE codloja='$codloja' order by id";
	$qr2 = mysql_query($sql_saldo,$con) or die ("\nErro ao gerar o extrato\n".mysql_error()."\n\n");
	while ($matriz = mysql_fetch_array($qr2)) {
		$saldo_crediario 	= number_format($matriz['saldo'],2,",",".");
		$sdo_crediario      = $matriz['saldo'];
		
	}
	$total_retido       += $sdo_crediario;
	if ( $sdo_crediario >= $valor ){
		$link = "<td bgcolor='#00CC33' align='center'><input type='checkbox' name='escolha[]' value='$codloja'/></td>";
		
		$link2 = "<td></td>";
		
	}else{
		$link = "<td></td>";
		$link2 = "<td bgcolor='#FF0000' align='center'><input type='checkbox' name='escolha2[]' value='$codloja'/></td>";
	}
	$dif = $valor - $sdo_crediario;
	
	$dif = number_format($dif,2,",",".");
	
	echo "<tr bgcolor=".$a_cor[$cont % 2].">
			<td>$codloja</td>
			<td>$nome</td>
			<td>$valor_debitar</td>
			<td>$saldo_crediario</td>
			<td>$dif</td>
			$link
			$link2
		  </tr>
		";
	}
	$total_prejuizo = $total_debitar - $total_retido;
	
	$total_debitar 	= number_format($total_debitar,2,",",".");
	$total_retido 	= number_format($total_retido,2,",",".");
	$total_prejuizo = number_format($total_prejuizo,2,",",".");
		
	?>
    <tr>
		<td colspan="5">&nbsp;</td>
	</tr>
    <tr>
		<td colspan="5">Listados <?=$cont?> registros</td>
	</tr>
    <tr>
		<td colspan="5">Total a debitar: R$ <?=$total_debitar?></td>
	</tr>
    <tr>
		<td colspan="5">Total de Saldo: R$ <?=$total_retido?></td>
	</tr>
    <tr>
		<td colspan="5">Total Prejuizo: R$ <?=$total_prejuizo?></td>
	</tr>
	<tr>
		<td colspan="5" align="center">
			<input type="button" value="Debitar todos os selecionados" class="buttons" onClick="Debitar_todos()"/>
		</td>
	</tr>        
</table>
</form>
<?php
include ("php/connect/conexao_conecta.php");

$pre = "SELECT * FROM categoria WHERE id = '{$_GET['assinatura']}'";
$prec = mysql_query ($pre);
if ($rst_tabela = mysql_fetch_array($prec)){
	$tabela    = $rst_tabela['tabela']; 
	$id_tabela = $rst_tabela['id']; 
}

$resulta = "SELECT * FROM $tabela WHERE mensalidade = '{$_GET['valor']}'";
$sql_res = mysql_query ($resulta);
if ($matriz = mysql_fetch_array($sql_res)){
		$mensalidade 		= $matriz['mensalidade'];
		$a1_bacen 			= $matriz['a1_bacen'];
		$a1_gratuidade 		= $matriz['a1_gratuidade'];
		$a1_custo 			= $matriz['a1_custo'];
		$a2_extra_bacen 	= $matriz['a2_extra_bacen'];
		$a2_gratuidade 		= $matriz['a2_gratuidade'];
		$a2_custo 			= $matriz['a2_custo'];
		$a3_financeira 		= $matriz['a3_financeira'];
		$a3_gratuidade 		= $matriz['a3_gratuidade'];
		$a3_custo 			= $matriz['a3_custo'];
		$a4_crediario 		= $matriz['a4_crediario'];
		$a4_gratuidade 		= $matriz['a4_gratuidade'];
		$a4_custo 			= $matriz['a4_custo'];
		$a5_cartorial 		= $matriz['a5_cartorial'];
		$a5_gratuidade 		= $matriz['a5_gratuidade'];
		$a5_custo 			= $matriz['a5_custo'];
		$a6_empresarial 	= $matriz['a6_empresarial'];
		$a6_gratuidade 		= $matriz['a6_gratuidade'];
		$a6_custo 			= $matriz['a6_custo'];
		$a7_cadastral 		= $matriz['a7_cadastral'];
		$a7_gratuidade 		= $matriz['a7_gratuidade'];
		$a7_custo 			= $matriz['a7_custo'];
		$b1_restritiva_nacional = $matriz['b1_restritiva_nacional'];
		$b1_gratuidade 		= $matriz['b1_gratuidade'];
		$b1_custo 			= $matriz['b1_custo'];
		$b2_bacen_plus 		= $matriz['b2_bacen_plus'];
		$b2_gratuidade 		= $matriz['b2_gratuidade'];
		$b2_custo 			= $matriz['b2_custo'];
		$i1_bloqueio_dev 	= $matriz['i1_bloqueio_dev'];
		$i1_gratuidade 		= $matriz['i1_gratuidade'];
		$i1_custo 			= $matriz['i1_custo'];
		$i2_desbloqueio_dev = $matriz['i2_desbloqueio_dev'];
		$i2_gratuidade 		= $matriz['i2_gratuidade'];
		$i2_custo 			= $matriz['i2_custo'];
		$c1_negativa_serasa = $matriz['c1_negativa_serasa'];
		$c1_gratuidade 		= $matriz['c1_gratuidade'];
		$c1_custo 			= $matriz['c1_custo'];
		$c2_cancela_serasa 	= $matriz['c2_cancela_serasa'];
		$c2_gratuidade 		= $matriz['c2_gratuidade'];
		$c2_custo 			= $matriz['c2_custo'];
		$i3_embratel 		= $matriz['i3_embratel'];
		$i3_gratuidade 		= $matriz['i3_gratuidade'];
		$i3_custo 			= $matriz['i3_custo'];
		$i4_locacao 		= $matriz['i4_locacao'];
		$i4_custo 			= $matriz['i4_custo'];
		$i5_ponto_adicional = $matriz['i5_ponto_adicional'];
		$i5_custo 			= $matriz['i5_custo'];
		$i6_taxa_dezembro 	= $matriz['i6_taxa_dezembro'];
}

//altera apenas o valor da mensalidade
$sql = "UPDATE dados SET mensalidade = '{$_GET['valor']}', assinatura = '{$_GET['assinatura']}' WHERE codigo ='{$_GET['id']}'";
$sql = mysql_query($sql);

$commando = "UPDATE tabela_clientes SET
					mensalidade   = '{$_GET['valor']}', 
					a1_bacen 	  = '$a1_bacen',
					a1_gratuidade = '$a1_gratuidade',
					a1_custo 	  = '$a1_custo',
					a2_extra_bacen= '$a2_extra_bacen',
					a2_gratuidade = '$a2_gratuidade',
					a2_custo 	  = '$a2_custo',
					a3_financeira = '$a3_financeira',
					a3_gratuidade = '$a3_gratuidade',
					a3_custo 	  = '$a3_custo',
					a4_crediario  = '$a4_crediario',
					a4_gratuidade = '$a4_gratuidade',
					a4_custo 	  = '$a4_custo',
					a5_cartorial  = '$a5_cartorial',
					a5_gratuidade = '$a5_gratuidade',
					a5_custo 	  = '$a5_custo',
					a6_empresarial= '$a6_empresarial',
					a6_gratuidade = '$a6_gratuidade',
					a6_custo 	  = '$a6_custo',
					a7_cadastral  = '$a7_cadastral',
					a7_gratuidade = '$a7_gratuidade',
					a7_custo 	  = '$a7_custo',
					b1_restritiva_nacional = '$b1_restritiva_nacional',
					b1_gratuidade = '$b1_gratuidade',
					b1_custo 	  = '$b1_custo',
					b2_bacen_plus = '$b2_bacen_plus',
					b2_gratuidade = '$b2_gratuidade',
					b2_custo 	  = '$b2_custo',
					i1_bloqueio_dev = '$i1_bloqueio_dev',
					i1_gratuidade  	= '$i1_gratuidade',
					i1_custo 		= '$i1_custo',
					i2_desbloqueio_dev = '$i2_desbloqueio_dev',
					i2_gratuidade 	= '$i2_gratuidade',
					i2_custo 		= '$i2_custo',
					c1_negativa_serasa = '$c1_negativa_serasa',
					c1_gratuidade 	= '$c1_gratuidade',
					c1_custo 		= '$c1_custo',
					c2_cancela_serasa = '$c2_cancela_serasa',
					c2_gratuidade 	= '$c2_gratuidade',
					c2_custo 		= '$c2_custo',
					i3_embratel 	= '$i3_embratel',
					i3_gratuidade 	= '$i3_gratuidade',
					i3_custo 		= '$i3_custo',
					i4_locacao 		= '$i4_locacao',
					i4_custo 		= '$i4_custo',
					i5_ponto_adicional = '$i5_ponto_adicional',
					i5_custo 		= '$i5_custo',
					i6_taxa_dezembro= '$i6_taxa_dezembro'
			 WHERE
			   	    codigo ='{$_GET['id']}'";
$result = mysql_query($commando);

//seleciona a mensalidade
$sel2 = mysql_query("SELECT * FROM dados WHERE codigo ='{$_GET['id']}'");
while ($matriz = mysql_fetch_array($sel2)){
	$codigo      = $matriz['codigo'];
	$mensalidade1 = number_format($matriz['mensalidade'], 2, ",", ".");
	$ass         = $matriz['assinatura'];
	if ($ass == 1){$desc_assinatura = "Tabela Padr�o Web Control Empresas";}
	if ($ass == 2){$desc_assinatura = "Tabela Potencial Bacen";}
	if ($ass == 3){$desc_assinatura = "Tabela Potencial Extra Bacen";}
	if ($ass == 4){$desc_assinatura = "Tabela Potencial Financeira";}
	if ($ass == 5){$desc_assinatura = "Tabela Potencial Restritiva Nacional";}
}
?>
<input type="text" name="mensalidade"  value="<?php echo $mensalidade1; ?>" class="inputi" onKeyUp ="alteraMensalidadeTotal('<?php echo $codigo;?>', this.value);" onKeydown="Formata(this,20,event,2)" style="width:14%;"/><?php echo "&nbsp;".$desc_assinatura; ?>&nbsp;&nbsp;</b>
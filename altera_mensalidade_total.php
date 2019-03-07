<?php
include ("php/connect/conexao_conecta.php");

$valor = str_replace(".", "", $_GET['valor']);
$valor = str_replace(",", ".", $valor);

echo $sel = " UPDATE dados SET mensalidade = '{$valor}' WHERE codigo ='{$_GET['id']}'";
$sql = mysql_query($sel)or die(mysql_error());

echo $sel2 = mysql_query("SELECT * FROM dados WHERE codigo ='{$_GET['id']}'");
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

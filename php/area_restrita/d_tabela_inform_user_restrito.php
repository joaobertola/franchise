<?php
	echo $_SERVER['PHPSELF'];
	if($jaConectou != true)
	{
		require_once ("../connect/conexao_conecta.php");
	}
	if(isset($codigo))
	{
		$codigo = $codigo;
	}
	else if(isset($_GET['codigo']))
	{
		$codigo = $_GET['codigo'];
	}
	$comando = "select * from tabela_clientes where codigo='$codigo'";
	#echo $comando."<hr />";
	$res = mysql_query($comando, $con);
	$matriz = mysql_fetch_array($res);
	    $codigo      = $matriz['codigo'];
		$mensalidade = $matriz['mensalidade'];
		$a1_bacen = $matriz['a1_bacen'];
		$a1_gratuidade = $matriz['a1_gratuidade'];
		$a1_custo = $matriz['a1_custo'];
		$a2_extra_bacen = $matriz['a2_extra_bacen'];
		$a2_gratuidade = $matriz['a2_gratuidade'];
		$a2_custo = $matriz['a2_custo'];
		$a3_financeira = $matriz['a3_financeira'];
		$a3_gratuidade = $matriz['a3_gratuidade'];
		$a3_custo = $matriz['a3_custo'];
		$a4_crediario = $matriz['a4_crediario'];
		$a4_gratuidade = $matriz['a4_gratuidade'];
		$a4_custo = $matriz['a4_custo'];
		$a5_cartorial = $matriz['a5_cartorial'];
		$a5_gratuidade = $matriz['a5_gratuidade'];
		$a5_custo = $matriz['a5_custo'];
		$a6_empresarial = $matriz['a6_empresarial'];
		$a6_gratuidade = $matriz['a6_gratuidade'];
		$a6_custo = $matriz['a6_custo'];
		$a7_cadastral = $matriz['a7_cadastral'];
		$a7_gratuidade = $matriz['a7_gratuidade'];
		$a7_custo = $matriz['a7_custo'];
		$b1_restritiva_nacional = $matriz['b1_restritiva_nacional'];
		$b1_gratuidade = $matriz['b1_gratuidade'];
		$b1_custo = $matriz['b1_custo'];
		$b2_bacen_plus = $matriz['b2_bacen_plus'];
		$b2_gratuidade = $matriz['b2_gratuidade'];
		$b2_custo = $matriz['b2_custo'];
		$i1_bloqueio_dev = $matriz['i1_bloqueio_dev'];
		$i1_gratuidade = $matriz['i1_gratuidade'];
		$i1_custo = $matriz['i1_custo'];
		$i2_desbloqueio_dev = $matriz['i2_desbloqueio_dev'];
		$i2_gratuidade = $matriz['i2_gratuidade'];
		$i2_custo = $matriz['i2_custo'];
		$c1_negativa_serasa = $matriz['c1_negativa_serasa'];
		$c1_gratuidade = $matriz['c1_gratuidade'];
		$c1_custo = $matriz['c1_custo'];
		$c2_cancela_serasa = $matriz['c2_cancela_serasa'];
		$c2_gratuidade = $matriz['c2_gratuidade'];
		$c2_custo = $matriz['c2_custo'];
		$i3_embratel = $matriz['i3_embratel'];
		$i3_gratuidade = $matriz['i3_gratuidade'];
		$i3_custo = $matriz['i3_custo'];
		$i4_locacao = $matriz['i4_locacao'];
		$i4_custo = $matriz['i4_custo'];
		$i5_ponto_adicional = $matriz['i5_ponto_adicional'];
		$i5_custo = $matriz['i5_custo'];
		$i6_taxa_dezembro = $matriz['i6_taxa_dezembro'];
?>
<script src="../../js/funcoes.js"></script>

<table width="430" border="0" cellpadding="0" cellspacing="0">
  <tr align="right">
    <td width="33%"><input type="text" name="a1_bacen"  value="<?php echo $a1_bacen; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a1_bacen', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td width="33%"><input disabled="disabled" type="text" id="a1_gratuidade" name="a1_gratuidade" value="<?php echo $a1_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a1_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td width="33%"><input disabled="disabled" type="text" name="a1_custo" value="<?php echo $a1_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a1_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

 <tr align="right">   
    <td><input type="text" name="a2_extra_bacen"  value="<?php echo $a2_extra_bacen; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a2_extra_bacen', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td><input disabled="disabled" type="text" id="a2_gratuidade" name="a2_gratuidade" value="<?php echo $a2_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a2_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="a2_custo" value="<?php echo $a2_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a2_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  
  
    <tr align="right">
    <td><input type="text" name="a3_financeira"  value="<?php echo $a3_financeira; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a3_financeira', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td><input disabled="disabled" type="text" id="a3_gratuidade" name="a3_gratuidade" value="<?php echo $a3_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a3_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="a3_custo" value="<?php echo $a3_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a3_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="a4_crediario"  value="<?php echo $a4_crediario; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a4_crediario', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td><input disabled="disabled" type="text" id="a4_gratuidade" name="a4_gratuidade" value="<?php echo $a4_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a4_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="a4_custo" value="<?php echo $a4_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a4_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="a5_cartorial"  value="<?php echo $a5_cartorial; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a5_cartorial', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td><input disabled="disabled" type="text" id="a5_gratuidade" name="a5_gratuidade" value="<?php echo $a5_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a5_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="a5_custo" value="<?php echo $a5_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a5_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="a6_empresarial"  value="<?php echo $a6_empresarial; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a6_empresarial', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td><input disabled="disabled" type="text" id="a6_gratuidade" name="a6_gratuidade" value="<?php echo $a6_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a6_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="a6_custo" value="<?php echo $a6_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a6_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="a7_cadastral"  value="<?php echo $a7_cadastral; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a7_cadastral', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td><input disabled="disabled" type="text" id="a7_gratuidade" name="a7_gratuidade" value="<?php echo $a7_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a7_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="a7_custo" value="<?php echo $a7_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'a7_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="b1_restritiva_nacional"  value="<?php echo $b1_restritiva_nacional; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'b1_restritiva_nacional', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td><input disabled="disabled" type="text" id="b1_gratuidade" name="b1_gratuidade" value="<?php echo $b1_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'b1_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled"type="text" name="b1_custo" value="<?php echo $b1_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'b1_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="b2_bacen_plus"  value="<?php echo $b2_bacen_plus; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'b2_bacen_plus', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td><input disabled="disabled" type="text" id="b2_gratuidade" name="b2_gratuidade" value="<?php echo $b2_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'b2_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="b2_custo" value="<?php echo $b2_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'b2_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="i1_bloqueio_dev"  value="<?php echo $i1_bloqueio_dev; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i1_bloqueio_dev', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td><input disabled="disabled" type="text" id="i1_gratuidade" name="i1_gratuidade" value="<?php echo $i1_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i1_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="i1_custo" value="<?php echo $i1_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i1_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="i2_desbloqueio_dev"  value="<?php echo $i2_desbloqueio_dev; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i2_desbloqueio_dev', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td><td><input disabled="disabled" type="text" id="i2_gratuidade" name="i2_gratuidade" value="<?php echo $i2_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i2_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="i2_custo" value="<?php echo $i2_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i2_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="c1_negativa_serasa"  value="<?php echo $c1_negativa_serasa; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'c1_negativa_serasa', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td><td><input disabled="disabled" type="text" id="c1_gratuidade" name="c1_gratuidade" value="<?php echo $c1_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'c1_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="c1_custo" value="<?php echo $c1_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'c1_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="c2_cancela_serasa"  value="<?php echo $c2_cancela_serasa; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'c2_cancela_serasa', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td><td><input disabled="disabled" type="text" id="c2_gratuidade" name="c2_gratuidade" value="<?php echo $c1_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'c2_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="c2_custo" value="<?php echo $c2_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'c2_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="i3_embratel"  value="<?php echo $i3_embratel; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i3_embratel', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td><td><input disabled="disabled" type="text" id="i3_gratuidade" name="i3_gratuidade" value="<?php echo $i3_gratuidade; ?>" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i3_gratuidade', this.value); mascaraHellas(this.value, this.id, '##########', event)" class="inputi" /></td><td><input disabled="disabled" type="text" name="i3_custo" value="<?php echo $i3_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i3_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="i4_locacao"  value="<?php echo $i4_locacao; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i4_locacao', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td><td>&nbsp;</td><td><input disabled="disabled" type="text" name="i4_custo" value="<?php echo $i4_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i4_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

    <tr align="right">
    <td><input type="text" name="i5_ponto_adicional"  value="<?php echo $i5_ponto_adicional; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i5_ponto_adicional', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td><td>&nbsp;</td><td><input disabled="disabled" type="text" name="i5_custo" value="<?php echo $i5_custo; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i5_custo', this.value);"  onkeypress="return(formataMoeda(this,'.',',',event))"/></td>
  </tr>  

  <tr align="right">
    <td><input type="text" name="i6_taxa_dezembro"  value="<?php echo $i6_taxa_dezembro; ?>" class="inputi" onKeyUp ="alteraDestaque('<?=$matriz['codigo'];?>', 'i6_taxa_dezembro', this.value);" onkeypress="return(formataMoeda(this,'.',',',event))"/>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
$custo = ($a1_custo * $a1_gratuidade) + ($a2_custo * $a2_gratuidade) + ($a3_custo * $a3_gratuidade) + ($a4_custo * $a4_gratuidade) + ($a5_custo * $a5_gratuidade) + ($a6_custo * $a6_gratuidade) + ($a7_custo * $a7_gratuidade) + ($b1_custo * $b1_gratuidade) + ($b2_custo * $b2_gratuidade) + ($i1_custo * $i1_gratuidade) + ($i2_custo * $i2_gratuidade) + ($c1_custo * $c1_gratuidade) + ($c2_custo * $c2_gratuidade) + ($i3_custo * $i3_gratuidade) + $i4_custo + $i5_custo;

$venda = $a1_bacen + $a2_extra_bacen + $a3_financeira + $a4_crediario + $a5_cartorial + $a6_empresarial + $a7_cadastral + $b1_restritiva_nacional + $b2_bacen_plus + $i1_bloqueio_dev + $i2_desbloqueio_dev + $c1_negativa_serasa + $c2_cancela_serasa + $i3_embratel + $i4_locacao + $i5_ponto_adicional + $i6_taxa_dezembro;

$lucro = $mensalidade - $custo; 
?>

<?php
$res = mysql_close ($con);
?>
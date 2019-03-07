<?php
require "connect/sessao.php";

$go 	= $_POST['go'];
$numero_boleto = $_POST['numero_boleto'];

if (empty($go)) {
?>
<script language="javascript">
//fun��o para aceitar somente numeros em determinados campos
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function soNumeros(v){
    return v.replace(/\D/g,"")
}

window.onload = function() {
	document.form.numero_boleto.focus(); 
}
</script>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="form">
<table width=70% align="center">
  <tr>
    <td colspan="2" align="center" class="titulo">BAIXA DE TITULOS CRED/REC/BOL</td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>

  <tr>
    <td class="subtitulodireita">Informe o n&uacute;mero do boleto</td>
    <td class="campoesquerda">
       <input type="text" name="numero_boleto" id="numero_boleto" size="30" maxlength="17" onKeyPress="mascara(this,soNumeros)" />
      <input type="hidden" name="go" value="ingressar" /></td>
  </tr>

  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr align="right">
    <td colspan="2"><input type="submit" value=" Enviar Consulta" name="enviar" /></td>
  </tr>
</table>
</form>
<?php
} // if go=null

if ($go=='ingressar') {
	$sql = "SELECT * FROM cs2.titulos_recebafacil 
							WHERE 
									numboleto = '$numero_boleto' or 
									numboleto_bradesco = '$numero_boleto' or 
									numboleto_itau = '$numero_boleto' or
									numboleto_hsbc = '$numero_boleto'
							";

	$qry = mysql_query($sql, $con);
	$linha = mysql_num_rows($qry);
	if ($linha == 0)
	{
		print"<script>alert(\"Titulo NAO ENCONTRADO ou ja BAIXADO, verifique !!!\");history.back();</script>";
	}
	else 
	{
		$matriz = mysql_fetch_array($qry); 
		$codloja = $matriz['codloja'];
		$banco_faturado = $matriz['bco'];
		$numboleto = $matriz['numboleto'];
		$numboletobradesco = $matriz['numboleto_bradesco'];
		$numboletoitau = $matriz['numboleto_itau'];
		$numboletohsbc = $matriz['numboleto_hsbc'];
		
		
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_baixa_titulo_cred_rec_bol_2.php&codloja=$codloja&banco_faturado=$banco_faturado&numboleto=$numboleto&numboletobradesco=$numboletobradesco&numboletoitau=$numboletoitau&numboletohsbc=$numboletohsbc\";>";
	}
}
?>
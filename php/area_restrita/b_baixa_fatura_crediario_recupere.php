<?php

require "connect/sessao.php";

$numdoc = $_GET['numdoc'];
$comando = "SELECT a.codloja, c.razaosoc, a.numdoc, a.vencimento, a.valor, a.datapg, a.valorpg, a.descricao_repasse
		   FROM cs2.titulos_recebafacil a
		   INNER JOIN cs2.logon b on a.codloja=b.codloja
		   INNER JOIN cs2.cadastro c on a.codloja=c.codloja
		   WHERE a.numdoc = '$numdoc'";
$conex = mysql_query($comando, $con);
$matriz = mysql_fetch_array($conex);
$hoje = date('d/m/Y');
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
// formato mascara data
function data(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    v=v.replace(/(\d{2})(\d)/,"$1/$2")

    return v
}

//fun��o para validar clientes no cadastramento
function validaClientes(){
// validar valor pago
d = document.baixa;
if (d.valorpg.value == ""){
	alert("O campo " + d.valorpg.name + " deve ser preenchido!");
	d.valorpg.focus();
	return false;
}
// validar data de pagamento
if (d.datapg.value == ""){
	alert("O campo " + d.datapg.name + " deve ser preenchido!");
	d.datapg.focus();
	return false;
}
return true;
}
</script>
<body>
<form name="baixa" method="post" onSubmit="return validaClientes();" action="area_restrita/b_baixa_titulos_recebafacil_f.php" >
<table width="90%" border="0" align="center">
<tr>
  <td colspan="2" class="titulo">Baixa de T&iacute;tulos [Credi&aacute;rio / Recupere]</td>
</tr>
<tr>
  <td colspan="2" class="campoesquerda">&nbsp;</td>
  </tr>
<tr>
  <td class="subtitulodireita">N&ordm; do T&iacute;tulo</td>
  <td class="subtitulopequeno"><?php echo $matriz['numdoc']; ?><input name="numdoc" type="hidden" value="<?php echo $matriz['numdoc']; ?>" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Sacado</td>
  <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Valor</td>
  <td class="subtitulopequeno"><?php echo $matriz['valor']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Data de Pagamento </td>
  <td class="subtitulopequeno"><input type="text" name="datapg" size="16" maxlength="10" value="<?php echo $hoje; ?>" onKeyPress="mascara(this,data)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'; validaData(this);" > 
    dd/mm/aaaa</td>
</tr>
<tr>
  <td class="subtitulodireita">Valor pago </td>
  <td class="subtitulopequeno">
  <input type="text" name="valorpg" size="16" maxlength="12" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php
  $valor = $matriz['valor'];
  $valor = number_format($valor,2,',','.');
  echo $valor; ?>" onKeydown="FormataValor(this,20,event,2)" style="text-align:right" >
  </td>
</tr>

<tr>
  <td colspan="2" class="campoesquerda"></td>
</tr>
<tr>
  <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
<tr>
  <td colspan="2"><br>
    <p align="center">  
  <input type="submit" name="Submit" value="  Confirmar Recebimento  ">
  <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " />

    </p></td>
</tr>
</table>
</form>
</body>
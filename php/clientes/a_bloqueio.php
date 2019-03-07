<?php
require "connect/sessao.php";
?>
<script language="javascript">
function check(Form) {
var retorno = true;
if (document.consCodigo.codigo.value == "")
	{
	window.alert("Informe um C�digo de Cliente!");
	document.consCodigo.codigo.focus();
	return false;
	}
if (document.consCodigo.codigo.value == 0)
	{
	window.alert("Informe um C�digo diferente de 0");
	document.consCodigo.codigo.focus();
	return false;
	}
document.consCodigo.submit();
return (true);
}
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
</script>
<br>
<form name="consCodigo" method="post" action="painel.php?pagina1=clientes/a_bloq_acesso.php">
<table width="70%" border="0" align="center">
  <tr class="titulo">
    <td colspan="3">BLOQUEIO E DESBLOQUEIO MANUAL DE ACESSO </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">C&oacute;digo do cliente </td>
    <td class="campoesquerda"><input name="codigo" size="12" maxlength="6" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="mascara(this,soNumeros)" /></td>
    <td class="campoesquerda"><input type="submit" value="Envia" name="envia" onClick="return check(this.form);"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno"><?php echo $nome_franquia; ?></td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="titulo">&nbsp;</td>
  </tr>
</table>
</form>
<div align="center"><input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
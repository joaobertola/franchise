<?php
	require "connect/sessao.php";
?>
<script language="javascript">
function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaço em branco

function confirma() {
	frm = document.form;	
	if(trim(frm.numdoc.value) == ""){
		alert("Falta informar o Numero do Documento !");
		frm.numdoc.focus();
		return false;
	}
	frm.action = 'painel.php?pagina1=area_restrita/voltar_titulo_listar.php';
	frm.submit();
}

window.onload = function(){
	document.form.numdoc.focus(); 
}

</script>

<form name="form" method="post" action="#">
<table border="0" width="60%" align="left" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
  <tr>
    <td colspan="2" align="center" class="titulo">Voltar T&iacute;tulo Exclu&iacute;do</td>
  </tr>
  <tr>
    <td width="25%" class="subtitulodireita" height="23">N&uacute;mero do Documento</td>
    <td width="75%" class="campoesquerda">
       <input type="text" name="numdoc" maxlength="20" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" style="width:50%" /></td>
  </tr>

  <tr><td colspan="2" class="titulo">&nbsp;</td></tr>
  
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="Confirma" name="enviar" onClick="confirma();" style="cursor:pointer" /></td>
  </tr>
</table>
<?php
//session_start();
//
require_once('../connect/sessao.php');
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//$data_cadastro = date("Y-m-d");
//
//if ( $name == "" ){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}
?>
<script>
function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaço em branco

function confirma(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/virtual_flex_listar_excluir_dominio.php';
	frm.submit();
}
function valida(){
	frm = document.form;	
	if(trim(frm.cod_cliente.value) == ""){
		alert("Falta informar o Código do Cliente !");
		frm.cod_cliente.focus();
		return false;
	}	
	confirma();
}
</script>
<p>
<form action="#" method="post" name="form">
<input type="hidden" name="libera" value="1">
<table border='0' width='700' align='center' cellpadding='0' cellspacing='1' style='border:1px dashed #E8E8E8; background-color:#FFFFFF'>
    <tr><td colspan="2" align="center" bgcolor="#E8E8E8" height="25px"><font size="+1">Eliminar Informa&ccedil;&otilde;es Virtual - Flex</font></td></tr>
    <tr>
      <td width="25%" height="25px" bgcolor="#F5F5F5"><b>&nbsp;C&oacute;digo do Cliente</b></td>
      <td width="75%" bgcolor="#F0F0F6"><input type="text" name="cod_cliente" maxlength="10" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" />
      &nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
  <td colspan="2" align="left"><b><font color="#FF0000">As informa&ccedil;&otilde;es que forem exclu&iacute;das n&atilde;o ter&atilde;o como ser recuperadas</font></b>
  </td>
  </tr>
    <tr><td height="40px" align="center"></td>     
    <td><input type="button" name="Confirma" value="Confirma" onclick="valida()" /></td></tr>
</table>
</form>
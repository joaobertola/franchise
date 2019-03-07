<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?
//require "../clientes/connect/sessao.php";
?>
<script language="javascript">
function check(Form) {
var retorno = true;
if (document.frm.codigo.value == "") {
	window.alert("Informe um Código de Cliente!");
	document.frm.codigo.focus();
	return false;
}
	document.frm.submit();
	return (true);
}

window.onload = function(){
	document.frm.codigo.focus(); 
}
</script>

<body>
<br><br><br>                            
<form name="frm" method="post" action="../php/painel.php?pagina1=area_restrita/autoriza_conta_listagem.php">
<table width="70%" align="center">
  <tr>
    <td colspan="2" align="center" class="titulo">Autorização de Conta</td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo do Cliente</td>
    <td class="campoesquerda"><input name="codigo" type="text" id="codigo" size="10" maxlength="6" /></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr align="right">
    <td class="campoesquerda"></td>
    <td class="campoesquerda"><input name="consulta" type="submit" value="Consultar" onClick="return check(this.form);" /></td>
</tr>
</table>
</form>
</body>
</html>

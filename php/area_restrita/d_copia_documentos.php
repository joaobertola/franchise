<?php
require "connect/sessao.php";

$go 	= $_POST['go'];
$codigo = $_POST['codigo'];

if (empty($go)) {
?>
<script language="javascript">
function check(Form) {
var retorno = true;
if (document.AltCliente.codigo.value == "")
	{
	window.alert("Informe um C�digo de Cliente!");
	document.AltCliente.codigo.focus();
	return false;
	}
if (document.AltCliente.codigo.value == 0)
	{
	window.alert("Informe um C�digo diferente de 0");
	document.AltCliente.codigo.focus();
	return false;
	}
if (isNumeroString(document.AltCliente.codigo.value)!=1)
	{
	window.alert("Informe um C�digo num�rico!");
	document.AltCliente.codigo.focus();
	return false;
	}
document.AltCliente.submit();
return (true);
}
</script>

<br><br><br>
<form name="AltCliente" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
<div>
<table width=70% align="center">
  <tr>
    <td colspan="2" align="center" class="titulo">C&Oacute;PIA DE DOCUMENTOS</td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo do cliente</td>
    <td class="campoesquerda">
       <input type="text" name="codigo" id="codigo" size="10" maxlength="6" />
       <input type="hidden" name="go" value="ingressar" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr align="right">
    <td colspan="2"><input type="submit" value=" Enviar Consulta" name="enviar" onClick="return check(this.form);" /></td>
  </tr>
</table>
</div>
</form>
<?php
}
if ($go=='ingressar') {
	if (($tipo == "a") || ($tipo == "c")) {
	$resulta = mysql_query("SELECT b.codloja, a.logon, b.id_franquia, c.email FROM logon a
							INNER JOIN cadastro b ON a.codloja=b.codloja
							INNER JOIN franquia c ON b.id_franquia = c.id
							WHERE mid(logon,1,LOCATE('S',logon)-1)='$codigo'", $con);
	} else {
	$resulta = mysql_query("select b.codloja, a.logon, b.id_franquia, c.email FROM logon a
							INNER JOIN cadastro b ON a.codloja=b.codloja
							INNER JOIN franquia c ON b.id_franquia = c.id
							WHERE mid(logon,1,LOCATE('S',logon)-1)='$codigo' AND id_franquia='$id_franquia', $con");
	}
	$linha = mysql_num_rows($resulta);
	if ($linha == 0) {
		print "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\"); javascript: history.back();</script>";
	} else {
		$matriz = mysql_fetch_array($resulta); 
		$codloja = $matriz['codloja'];
		$email_franquia = $matriz['email'];
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=area_restrita/d_copia_documentos1.php&codloja=$codloja&email_franquia=$email_franquia&logon=$codigo\";>";
	}
}
?>
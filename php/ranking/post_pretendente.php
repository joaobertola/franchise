<?php
$id = $_POST['id'];
$go = $_POST['go'];

if (empty($go)) {
	$sql = "select nome from pretendentes where id='$id'";
	$qr = mysql_query($sql, $con);
	$codigo = mysql_result($qr,0,"nome");
?>
<script language="javascript">
	window.onload = function(){	document.form.msg.focus(); } 
</script>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form">
<table border="0" width="650px" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
<tr>
  <td colspan="2" class="titulo">Incluir Coment&aacute;rio</td>
</tr>

<tr>
  <td class="subtitulodireita">&nbsp;</td>
  <td class="subtitulopequeno">&nbsp;</td>
</tr>

<tr>
  <td class="subtitulodireita">Para:</td>
  <td class="subtitulopequeno">
  	<input name="para" type="text" size="40" maxlength="30" value="<?php echo $codigo; ?>">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
  </td>
</tr>

<tr>
  <td valign="center" class="subtitulodireita">Descri&ccedil;&atilde;o:</td>
  <td class="subtitulopequeno">
 <textarea name="msg" cols="50" rows="8" wrap="VIRTUAL" style="font-family: Verdana; font-size: 10 pt"></textarea></td>
</tr>
<tr>
  <td class="subtitulodireita">&nbsp;</td>
  <td class="subtitulopequeno"><input type="hidden" name="go" value="cadastrar"></td>
</tr>
<tr>
  <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
<tr>
  <td colspan="2" align="center">
  <input type="submit" name="Submit" value=" Comentar ">
  <input type="reset" name="reset" value="  Apagar  ">
  <input type="button" name="voltar" value="  Voltar   " onClick="javascript:history.back();">
  </td>
</tr>
</table>
</form>
<?php
} //fim empty go

if ($go == 'cadastrar') {
	$msg = $_POST['msg'];
	$data = date('Y-m-d H:i:s');
	$sql5 = "insert into ocorr_pretendentes (pretendente, msg, data) values ('$id', '$msg', '$data')";
	$qr = mysql_query($sql5, $con) or die ("erro ao incluir o comentario".mysql_error());
?>
<script language="javascript">
	window.location.href="painel.php?pagina1=area_restrita/d_pretendentes.php&id=<?=$id?>";
</script>
<?php	
}
?>
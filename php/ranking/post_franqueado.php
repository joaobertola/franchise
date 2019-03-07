<?php
require "connect/sessao.php";

$id = $_POST['id'];
$go = $_POST['go'];

if (empty($go)) {
	$sql = "select nome from franqueados where id='$id'";
	$qr = mysql_query($sql, $con);
	$codigo = @mysql_result($qr,0,"nome");
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
<table width="650" border="0" align="center">
<tr>
  <td colspan="2" class="titulo">Incluir Coment&aacute;rio</td>
</tr>

<tr>
  <td class="subtitulodireita">&nbsp;</td>
  <td class="subtitulopequeno">&nbsp;</td>
</tr>

<tr>
  <td class="subtitulodireita">Contato:</td>
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
	$sql5 = "insert into ocorr_franqueados (franqueado, msg, data) values ('$id', '$msg', '$data')";
	$qr = mysql_query($sql5, $con) or die ("erro ao incluir o comentario".mysql_error());
	echo "<script>alert(\"Comentario atualizado com sucesso!\");</script>";
	echo "<meta http-equiv=\"refresh\" content=\"0; url=painel.php?pagina1=Franquias/b_altfranqueado.php&id=$id\";>";
}
?>
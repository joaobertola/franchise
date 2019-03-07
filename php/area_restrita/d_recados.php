<script language="javascript">
function validaRecado(){
d = document.increcado;
// validar assunto
// validar franquia
if (d.frq.value == ""){
	alert("O destinatario deve ser selecionado!");
	d.frq.focus();
	return false;
}
if (d.assunto.value == ""){
	alert("O campo " + d.assunto.name + " deve ser preenchido!");
	d.assunto.focus();
	return false;
}
//validar recado
else if (d.recado.value == ""){
	alert("O campo " + d.recado.name + " deve ser preenchido!");
	d.recado.focus();
	return false;
}
return true;
}
</script>
<table width="550">
	<tr>
		<td width="100%" align="right" class="pageName">Nova Mensagem <img src="../img/message.gif" alt="Nova mensagem"></td>
	</tr>
</table>
<form name="increcado" method="POST" onSubmit="return validaRecado();" action="area_restrita/d_envia_recado.php">
<table width=80% align="center">
  <tr>
    <td colspan="2" bgcolor="#CCCCCC">&nbsp;<input type="image" id="submit" src="../img/mail.gif" alt="Enviar Email" align="middle" />&nbsp;<img src="../img/paper-clip2.gif" border="0" alt="Anexo" align="middle" /></td>
  </tr>
  <tr>
    <td width="130" class="subtitulodireita">&nbsp;</td>
    <td width="614" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Seu nome</td>
    <td class="campoesquerda">
		<?php echo $nome_franquia; ?>
		<input type="hidden" name="nome" id="nome" value="<?php echo $id_franquia; ?>" >	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Para franquia</td>
    <td class="campoesquerda">
		<select name="frq" class="boxnormal">
			<option selected>:: Selecione o Destinat&aacute;rio ::</option>
      <?php
		if ($tipo == "b") {
			$sql = "select id, razaosoc from franquia where sitfrq <> 1 and (tipo = 'a' or tipo = 'c') order by id";
		$resposta = mysql_query($sql);
		while ($array = mysql_fetch_array($resposta))
			{
			$franquia   = $array["id"];
			$nome_franquia = $array["razaosoc"];
			echo "<option value=\"$franquia\">$nome_franquia</option>\n";
			}
		} else {
		$sql = "select id, razaosoc from franquia where sitfrq <> 1 order by id";
		$resposta = mysql_query($sql, $con);
		while ($array = mysql_fetch_array($resposta))
			{
			$franquia   = $array["id"];
			$nome_franquia = $array["razaosoc"];
			echo "<option value=\"$franquia\">$nome_franquia</option>\n";
			}
			
			echo "<option value=\"todos\">Todas as Franquias</option>";
		}
		?>
		</select></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Assunto</td>
    <td class="campoesquerda"><input type="text" name="assunto" id="assunto" size="30" maxlength="50" class="boxnormal" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Mensagem</td>
    <td class="campoesquerda">
   
    <textarea name="recado" wrap="virtual" id="recado" class="boxnormal" cols="100" rows="10" ></textarea></td>


  </tr>

  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
</table>
</form>
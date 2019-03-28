<script language="javascript">
	window.onload = function() { document.form.senha.focus();  }
</script>
<form action="#" method="post" name="form">
<?php
if ( !empty($_REQUEST['senha']) ){

	$usuario = $_SESSION['ss_name'];
	$senha = $_POST["senha"];
	if (($usuario!="")||($senha!="")) {
		$res = mysql_query("SELECT * FROM franquia WHERE usuario='$usuario' AND senha_restrita='$senha'",$con);
		$linha = mysql_num_rows($res);
		$matriz = mysql_fetch_array($res); 
		$res = mysql_close($con);
		if ($linha!=0) {
			//include("b_cadastro_usuario.php");
			?>
				<script>
	                window.location.href="painel.php?pagina1=Franquias/b_cadastro_usuario.php";
                </script>
			<?php
			exit;
		} else {
			print"<script>alert(\"Senha incorreta!\");history.back();</script>";
			exit;
		}
	} else {
		print"<script>alert(\"Login ou senha incorreta!\");history.back();</script>";
		exit;
	}
}else{
?>
<table width="330" height="220" background="../img/background.jpg" align="center" cellspacing="0">
  <tr>
    <td>
      <table width="232" border="0" align="center" cellpadding="1" cellspacing="0">

        <tr>
          <td colspan="2" class="titulo"> Entre aqui com a sua Senha Restrita </td>
        </tr>
        
        <tr>
          <td class="subtitulodireita" >&nbsp;</td>
          <td class="campoesquerda">&nbsp;</td>
        </tr>
        <tr>
          <td width="76" class="subtitulodireita" >Senha</td>
          <td width="180" class="campoesquerda">
          	<input name="senha" type="password" id="senha" value="" size="20" maxlength="20" />
          </td>
        </tr>
        <tr>
          <td class="subtitulodireita" >&nbsp;</td>
          <td class="campoesquerda">&nbsp;</td>
        </tr>
      </table>
      <table width="226" border="0" align="center" cellspacing="1" cellpadding="0">
        <tr>
          <td width="45%" align="right"><input name="Submit" type="submit" value="        Enviar" />
          </td>
          <td width="10%"></td>
          <td width="45%"><input name="Submit2" type="reset" value="Limpar        " />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php } ?>
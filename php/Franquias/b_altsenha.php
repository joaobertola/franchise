<?php
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;

$go 	= $_POST['go'];
$senhaatual = $_POST['senhaatual'];
$senhanova = $_POST['senhanova'];
$senhanova2 = $_POST['senhanova2'];

if (empty($go)) {
?>
<script>
//script para validar as senhas no formulario de altera��o de senha
function validarSenha() {
	d = document.form1;
	senha = d.senha.value;
	senha3 = d.senhaatual.value;
	senha1 = d.senhanova.value;
	senha2 = d.senhanova2.value;
	
	if (senha3 == ""){
		alert("O campo senha atual deve ser preenchido!");
		d.senhaatual.focus();
		return false;
	}
	if ((senha1 == "")||(senha2 == "")){
		alert("A nova senha deve ser diferente de nulo!");
		d.senhanova.focus();
		return false;
	}
	if (senha != senha3) {
		alert('A senha atual nao confere. Favor digite novamente');
		d.senhaatual.focus();
		return false;
	}
	
	if (senha1 != senha2) {
		alert("Confirme novamente as senhas");
		d.senhanova.focus();
		return false;
	}
	return true;
}
</script>
<br><br><br>
<?php
$sql = "select senha from cs2.franquia where id='$id_franquia'";
$qr = mysql_query($sql, $con);
$senha = mysql_result($qr,0,"senha");
?>
<form name="form1" onsubmit="return validarSenha()" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
<table width=70% align="center">
  <tr>
    <td colspan="2" align="center" class="titulo">ALTERA&Ccedil;&Atilde;O DE SENHA</td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franquia</td>
    <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Senha Atual de Acesso</td>
    <td class="campoesquerda"><input type="password" name="senhaatual" id="senhaatual" class="boxnormal" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" size="10" maxlength="6"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nova Senha</td>
    <td class="campoesquerda"><input type="password" name="senhanova" id="senhanova" class="boxnormal" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" size="10" maxlength="6"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Repita a Nova Senha</td>
    <td class="campoesquerda"><input type="password" name="senhanova2" id="senhanova2" class="boxnormal" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" size="10" maxlength="6"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr align="right">
    <td colspan="2">
      <input type="hidden" name="go" value="ingressar" />
      <input type="hidden" name="senha" value="<?php echo $senha; ?>" />
      <input type="submit" value="Alterar Senha" name="enviar" />
    </td>
  </tr>
</table>
</form>
<?php
}
if ($go=='ingressar') {
	//valida��o das senhas
	if (empty($senhaatual)) echo "<script>alert(\"Preencha o campo senha atual!\");history.back();";
	if (empty($senhanova)) echo "<script>alert(\"Preencha a nova senha!\");history.back();";
	if (empty($senhanova2)) echo "<script>alert(\"Repita a nova senha!\");history.back();";
	if ($senhanova != $senhanova2) echo "<script>alert(\"As senhas s�o diferentes!\");history.back();";
	
	//executa a query para buscar a senha atual
	$sql = "select id, senha, sitfrq from cs2.franquia where id='$id_franquia'";
	$qr = mysql_query($sql, $con);
	$linhas = mysql_num_rows($qr);
	if ($linhas == 0) {
		echo "<script>alert(\"Franquia nao existe!\");history.back();";
		exit;
	}
	$matriz = mysql_fetch_array($qr);
	$id = $matriz['id'];
	$qr_senha = $matriz['senha'];
	//if ($senhaatual <> $qr_senha) echo "<script>alert(\"A senha atual n�o corresponde!\");history.back();";
	
	//agora atualiza as senhas
	$sql2 = "update cs2.franquia set senha = '$senhanova' where id = '$id'";
	$qr2 = mysql_query($sql2, $con);
	?>
    <p>&nbsp;</p>
    <table width="70%" align="center">
    	<tr>
        	<td class="titulo" align="center">Senha atualizada com sucesso!</td>
        </tr>
        <tr>
        	<td class="subtitulopequeno" align="center">Feche a janela e abra novamente para efetivizar a mudan�a</td>
        </tr>
        <form method="post" action="connect/destroy.php">
        <tr>
        	<td align="center"><input type="submit" name="fechar" value="Fechar" /></td>
        </tr>
        </form>
    </table>            
    <?php
	exit;
}
?>
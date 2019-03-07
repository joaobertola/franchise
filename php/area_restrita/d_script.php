<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php";

$go = $_POST['go'];

if (empty($go)) {
	$sql = "select script from cs2.controle limit 1";
	$qr = mysql_query($sql, $con) or die ("nao consegui achar o script".mysql_error());
	$conteudo = mysql_result($qr,0,"script");
?>
<html>
<head>
<title>Script de Venda</title>
<link href="../../css/tabela.css" rel="stylesheet" type="text/css">
<style>
body
{
	background-image:url(../../img/fundo.gif);
	background-repeat:no-repeat;
	background-position:top;

}
</style>
</head>
<body>
<form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="650" align="center">
  <tr>
    <td colspan="2" align="center" class="titulo"><br>SCRIPT DE VENDA DE FRANQUIA</td>
  </tr>
  <tr>
    <td width="150" class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Script</td>
    <td class="campoesquerda">
      <textarea name="conteudo" id="conteudo" cols="75" rows="40" class="relatorio"><?php echo $conteudo; ?></textarea>    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda"><input type="hidden" value="salvar" name="go"></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr align="center">
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr align="center">
    <td colspan="2">
    	<input type="submit" value="Salvar e Sair" name="salvar" />
        <input type="button" value="   Sair   " onClick="window.close()">    </td>
  </tr>
</table>
</form>
</body>
</html>
<?php
}

if ($go == "salvar"){
	$script = $_POST['conteudo'];
	if (!empty($script)) {
		$sql = "update cs2.controle set script = '$script'";
		$qr = mysql_query($sql,$con) or die ("nao consegui gravar os dados".mysql_error());
		echo "<script>window.close()</script>";
	} else {
		echo "<script>alert('Escreva alguma coisa!'); history.back();</script>";
	}
}

@mysql_free_result($qr);
mysql_close($con);
?>
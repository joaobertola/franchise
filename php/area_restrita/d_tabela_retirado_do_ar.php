<?php
$go = $_POST['go'];
$codigo = $_POST['codigo'];

if (empty($go)) {
?>
<br><br><br>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >
<table width=50% align="center">
  <tr>
    <td colspan="2" align="center" class="titulo">INFORME O C&Oacute;DIGO DO CLIENTE </td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo do cliente</td>
    <td class="campoesquerda">
       <input type="text" name="codigo" id="codigo" size="10" maxlength="6" />
       <input type="hidden" name="go" value="mostrar" >
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr align="right">
    <td colspan="2"><input type="submit" value=" Alterar Tabelas " name="enviar" /></td>
  </tr>
</table>
</form>
<?php
} //fim if empty go

if ($go == 'mostrar') {
	if ($tipo == 'b') $frq = "and b.id_franquia = '$id_franquia'";
	else $frq = "";
	$sql = "select a.codloja, mid(a.logon,1,5) as logon, b.nomefantasia, b.cidade, b.uf, b.banco_cliente
			from cs2.logon a 
			inner join cs2.cadastro b on a.codloja=b.codloja
			where a.codloja='$codigo' $frq";
	$ql = mysql_query($sql,$con);
	$lin = mysql_num_rows($ql);
	if (empty($lin)) {
		$sql = "select a.codloja, mid(a.logon,1,5) as logon, b.nomefantasia, b.cidade, b.uf, b.banco_cliente
				from cs2.logon a
				inner join cs2.cadastro b on a.codloja=b.codloja 
				where mid(logon,1,5)='$codigo' $frq limit 1";
		$ql = mysql_query($sql,$con);
		$linha = mysql_num_rows($ql);
		if (empty($linha)) {
			print "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\"); history.back();</script>";
			exit;
		} 
	}
	$matriz = mysql_fetch_array($ql);
	$codigo = $matriz['codloja'];
	echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=area_restrita/d_tabela1.php&codloja=$codigo\";>";
	exit;
}
?>
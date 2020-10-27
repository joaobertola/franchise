<?php
require "connect/sessao.php";

$cod_restringe = $_REQUEST['codigo'];

if ( $id_franquia == '4' ){
	if( ($cod_restringe == 19119) || ($cod_restringe == 19120) || ($cod_restringe == 17001) || ($cod_restringe == 63752) || ($cod_restringe == 48785) ){
		$negado = 'SIM';
	}
}elseif ( $id_franquia == '5' ){
	if( ($cod_restringe == 19119) || ($cod_restringe == 19120) || ($cod_restringe == 17001) || ($cod_restringe == 63752)  || ($cod_restringe == 63751) || ($cod_restringe == 48785) ){
		$negado = 'SIM';
	}
}elseif ( $id_franquia == '1204' ){
	if( ($cod_restringe == 19119) || ($cod_restringe == 19120) || ($cod_restringe == 17001) || ($cod_restringe == 63751) || ($cod_restringe == 48785) ){
		$negado = 'SIM';
	}
}

if ( $negado == 'SIM' ){
	echo "<div align='center'><b><font color='red'>Você não é Permitido Visualizar/Alterar esta senha.<p> 
	Contacte a Diretoria.</font></b>";
	echo "<br><br><a href='painel.php?pagina1=clientes/a_altsenha.php'>Retornar</a></div>";
	exit;
}

$codigo = $_POST['codigo'];

if ($tipo == 'b') $frq = "and a.id_franquia = '$id_franquia'";
else $frq = "";

$sql = "SELECT  
				a.codloja, a.razaosoc, 
				mid(b.logon,1,locate('S',b.logon)-1) as logon, u.senha 
		FROM cs2.cadastro a
		INNER JOIN cs2.logon b ON a.codloja=b.codloja
		INNER JOIN base_web_control.webc_usuario u ON a.codloja = u.id_cadastro
		WHERE MID(logon,1,locate('S',logon)-1)='$codigo' and u.login_master = 'S' $frq limit 1";
		
$query = mysql_query($sql,$con);
$line = mysql_num_rows($query);
if ($line == 0) {
	print "<script>alert(\"Cliente não existe ou não pertence a sua franquia!\"); javascript: history.back();</script>";
}

while ($linha = mysql_fetch_array($query)) {
	
	# Senha para baixa de titulo
	$codloja = $linha['codloja'];
	
	$nsql = "SELECT senha FROM cs2.usuarios_crediario_recupere 
			 WHERE codloja = '$codloja' AND nivel = 'A'";
	$nquery = mysql_query($nsql,$con);
	$nline = mysql_num_rows($nquery);
	if ($nline == 0) {
		$senha_baixa_titulo = 'AINDA NAO CADASTRADA';
	}else{
		while ($xlinha = mysql_fetch_array($nquery)) {
			$senha_baixa_titulo = $xlinha['senha'];
		}
	}

?>

<script language="javascript">
function check1(){
frm1 = document.form1;	
if (frm1.password.value == ""){
	alert("Informe a Senha do Cliente!");
	frm1.password.focus();
	return false;
}
if (frm1.password.value == 0){
	alert("Informe uma Senha diferente de 0");
	frm1.password.focus();
	return false;
	}
if (isNumeroString(frm1.password.value)!=1){
	alert("Informe uma Senha numérica!");
	frm1.password.focus();
	return false;
	}
}

function check2(){
frm2 = document.form2;	
if (frm2.password.value == ""){
	alert("Informe a Senha do Cliente!");
	frm2.password.focus();
	return false;
}
if (frm2.password.value == 0){
	alert("Informe uma Senha diferente de 0");
	frm2.password.focus();
	return false;
	}
if (isNumeroString(frm2.password.value)!=1){
	alert("Informe uma Senha numérica!");
	frm2.password.focus();
	return false;
	}
}

</script>

<body>
<form name="form1" method="post" action="clientes/update_senha.php?op=1" onSubmit="return check1()">
<table width="78%" align="center">
  <tr>
    <td colspan="2" align="center" class="titulo"><br />
      ALTERA&Ccedil;&Atilde;O DE SENHA DO CLIENTE </td>
  </tr>
  <tr>
    <td width="50%" class="subtitulodireita">&nbsp;</td>
    <td width="50%" class="campoesquerda">
	<input name="codigo" type="hidden" id="codigo" value="<?php echo $linha['codloja']; ?>"  />
	<input name="logon" type="hidden" id="logon" value="<?php echo $linha['logon']; ?>" />
	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td class="campoesquerda"><?=$linha['razaosoc']?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Senha Atual do Cliente WebControl Empresas</td>
    <td class="campoesquerda"><?=$linha['senha']?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nova senha </td>
    <td class="campoesquerda"><input name="password" type="password" id="password2" maxlength="5" size="10" /><br><font color="#FF0000">Seguran&ccedil;a:</font><br>Insira pelo menos 1 letra do alfabeto.</td>
  </tr>
  
  <tr><td colspan="2" align="center"><input name="alterar" type="submit" id="alterar" value="Alterar Pesquisas e Soluções" onClick="return check(this.form);" /></td></tr>
   <tr><td colspan="2">&nbsp;</td></tr>
</table>   
 </form>
 
<form name="form2" method="post" action="clientes/update_senha.php?op=2" onSubmit="return check2()">  
<input name="codigo" type="hidden" id="codigo" value="<?php echo $linha['codloja']; ?>"  />
<input name="logon" type="hidden" id="logon" value="<?php echo $linha['logon']; ?>" />
<table width="70%" align="center">
  <tr>
    <td class="subtitulodireita" width="50%">Senha  Atual do Cliente Baixar T&iacute;tulos</td>
    <td class="campoesquerda" width="50%"><?=$senha_baixa_titulo?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nova Senha </td>
    <td class="campoesquerda"><input name="senha_baixatitulo" type="password" id="password" maxlength="5" size="10" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr align="right">
    <td colspan="2" align="center">
     <input name="alterar" type="submit" id="alterar" value="Alterar Baixar Títulos" onClick="return check(this.form);"/>
    &nbsp;&nbsp;
    <input type="button" onClick="javascript: history.back();" value="Voltar" /></td>
  </tr>
</table>
</form>
<?php
  }
?>
</body>
</html>

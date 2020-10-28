<?php
require "connect/sessao.php";

$go 	= $_POST['go'];
$codigo = $_POST['codigo'];

if (empty($go)) {
?>
<script language="javascript">
//fun��o para aceitar somente numeros em determinados campos
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function soNumeros(v){
    return v.replace(/\D/g,"")
}

window.onload = function() {
	document.form.codigo.focus(); 
}
</script>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="form">
<table width=70% align="center">
  <tr>
    <td colspan="2" align="center" class="titulo">ISENTAR JUROS DE FATURAS EM ATRASO</td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo do cliente</td>
    <td class="campoesquerda">
       <input type="text" name="codigo" id="codigo" size="10" maxlength="6" onKeyPress="mascara(this,soNumeros)" />
	   <input type="hidden" name="go" value="ingressar" />
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
    <td colspan="2"><input type="submit" value=" Enviar Consulta" name="enviar" /></td>
  </tr>
</table>
</form>
<?php
} // if go=null

if ($go=='ingressar') {
	if (($tipo == "a") || ($tipo == "c")) {
	$resulta = mysql_query("select mid(a.logon,1,5) as logon, b.id_franquia, b.codloja, b.razaosoc from logon a
							inner join cadastro b on a.codloja=b.codloja
							where CAST(MID(logon,1,6) AS UNSIGNED)='$codigo'",$con);
	} else {
	$resulta = mysql_query("select mid(a.logon,1,5) as logon, b.id_franquia, b.codloja, b.razaosoc from logon a
							inner join cadastro b on a.codloja=b.codloja
							where CAST(MID(logon,1,6) AS UNSIGNED)='$codigo' and id_franquia='$id_franquia'",$con);
	}
	$linha = mysql_num_rows($resulta);
	if ($linha == 0)
	{
		print"<script>alert(\"Cliente não existe ou não pertence á sua franquia!\");history.back();</script>";
	}
	else 
	{
		$matriz = mysql_fetch_array($resulta); 
		$codloja = $matriz['codloja'];
		$logon = $matriz['logon'];
		$razaosoc = $matriz['razaosoc'];
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_isentajuros2.php&codloja=$codloja&logon=$logon&razaosoc=$razaosoc\";>";
	}
}
?>
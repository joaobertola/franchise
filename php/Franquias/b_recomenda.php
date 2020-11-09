<?php
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;

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
</script>

<br><br><br>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >
<table width=70% border="0" align="center">
  <tr class="titulo">
    <td colspan="2">INDICA&Ccedil;&Atilde;O DE CLIENTES </td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo do cliente </td>
    <td class="campoesquerda">
		<input name="codigo" size="6" maxlength="6" onKeyPress="mascara(this,soNumeros)" />
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
  <tr>
    <td>&nbsp;</td>
    <td align="right">
		<input name="enviar" type="submit" id="enviar" value="         Verificar" />
	</td>
  </tr>
</table>
</form>
<?php
} // if go=null

if ($go=='ingressar') {
	if (($tipo == "a") || ($tipo == "c")) {
	$resulta = mysql_query("select a.codloja, b.razaosoc, a.logon, b.id_franquia from logon a
							inner join cadastro b on a.codloja=b.codloja
							where MID(a.logon,1,LOCATE('S', a.logon) - 1)='$codigo'",$con);
	} else {
	$resulta = mysql_query("select a.codloja, b.razaosoc, a.logon, b.id_franquia from logon a
							inner join cadastro b on a.codloja=b.codloja
							where MID(a.logon,1,LOCATE('S', a.logon) - 1)='$codigo' and id_franquia='$id_franquia'",$con);
	}
	$linha = mysql_num_rows($resulta);
	if ($linha == 0) {
		print "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\");</script>";
	} else {
		$matriz = mysql_fetch_array($resulta); 
		$codloja = $matriz['codloja'];
		$razaosoc = $matriz['razaosoc'];
		$logon = $codigo;
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=Franquias/b_recomenda1.php&codloja=$codloja&razaosoc=$razaosoc&logon=$logon\";>";

	}
}
?>
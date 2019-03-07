<?php

require "connect/sessao.php";

$go 	= $_POST['go'];
$codigo = $_POST['codigo'];
$tp_libera = $_POST['tp_libera'];

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
<br>
<form name="consCodigo" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
<div>
<table width="70%" border="0" align="center">
  <tr class="titulo">
    <td colspan="3">Baixa de Faturas [ Credi&aacute;rio / Recupere ]</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">N&uacute;mero do Documento </td>
    <td class="campoesquerda"><input name="codigo" size="25" maxlength="7" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      <input type="hidden" name="go" value="ingressar" />	</td>
    <td class="campoesquerda"><input type="submit" value="Envia" name="envia" onClick="return check(this.form);"/></td>
  </tr>
  <tr>
    <td colspan="3" class="titulo">&nbsp;</td>
  </tr>
</table>
</div>
</form>
<div align="center"><input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
<?php
}

if ($go=='ingressar') {
	$resulta = mysql_query("SELECT a.codloja
						   FROM cs2.titulos_recebafacil a
						   INNER JOIN cs2.logon b on a.codloja=b.codloja
						   WHERE a.numdoc like '%$codigo'");
	$linha = mysql_num_rows($resulta);
	if ($linha == 0) {
		print "<script>alert(\"Nenhum boleto foi Encontrado!\"); javascript: history.back();</script>";
	} else {
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=area_restrita/d_ver_faturas_crediariorecupere.php&codloja=$codigo\";>";
	}
}
?>

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
<form name="consCodigo" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
<div>
<table width="70%" border="0" align="center">
  <tr class="titulo">
    <td colspan="3">LIBERA&Ccedil;&Atilde;O DO LIMITE DE CONSULTAS </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">C&oacute;digo do cliente </td>
    <td class="campoesquerda">
     <?php if ($id_franquia == '247'){ ?>
      <input name="codigo" size="10" maxlength="6" value="19120" onKeyPress="mascara(this,soNumeros)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" readonly="readonly" />      
        <?php }else{?>
         <input name="codigo" size="10" maxlength="6" onKeyPress="mascara(this,soNumeros)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
        <?php }?>
    
    
   
      <input type="hidden" name="go" value="ingressar" />	</td>
    <td class="campoesquerda"><input type="submit" value="Envia" name="envia" onClick="return check(this.form);"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">
      <input name="tp_libera" type="radio" value="0" checked> Libera&ccedil;&atilde;o
	  <input name="tp_libera" type="radio" value="1">
	  Redu&ccedil;&atilde;o
    </td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno"><?php echo $nome_franquia; ?></td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="titulo">&nbsp;</td>
  </tr>
</table>
</div>
</form>
<div align="center"><input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
<?php
} // if go=null

if ($go=='ingressar') {
	if ( ($tipo == "a") || ($tipo == "c") || ($id_franquia == '247') ) {
	$resulta = mysql_query("select b.codloja, a.logon, b.id_franquia from logon a
							inner join cadastro b on a.codloja=b.codloja
							where mid(logon,1,5)='$codigo'", $con);
	} else {
	$resulta = mysql_query("select b.codloja, a.logon, b.id_franquia from logon a
							inner join cadastro b on a.codloja=b.codloja
							where mid(logon,1,5)='$codigo' and id_franquia='$id_franquia'", $con);
	}
	$linha = mysql_num_rows($resulta);
	if ($linha == 0) {
		print "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\"); javascript: history.back();</script>";
	} else {
		if ($tp_libera == 0) echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=Franquias/b_liberaconsulta1.php&codloja=$codigo\";>";
		if ($tp_libera == 1) echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=Franquias/b_liberaconsulta2.php&codloja=$codigo\";>";
	}
}
?>
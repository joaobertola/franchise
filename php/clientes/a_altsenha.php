<?php
require "connect/sessao.php";

?>
<script language="javascript">
function check(Form) {
var retorno = true;
if (document.AltSenha.codigo.value == "")
	{
	window.alert("Informe um Código de Cliente!");
	document.AltSenha.codigo.focus();
	return false;
	}
if (document.AltSenha.codigo.value == 0)
	{
	window.alert("Informe um Código diferente de 0");
	document.AltSenha.codigo.focus();
	return false;
	}
if (isNumeroString(document.AltSenha.codigo.value)!=1)
	{
	window.alert("Informe um Código numérico!");
	document.AltSenha.codigo.focus();
	return false;
	}
document.AltSenha.submit();
return (true);
}

window.onload = function(){
	document.AltSenha.codigo.focus(); 
}
</script>

<body>
<br><br><br>
<form name="AltSenha" method="post" action="painel.php?pagina1=clientes/a_altsenha1.php">
<table width="70%" align="center">
    <tr>
        <td colspan="2" align="center" class="titulo">ALTERA&Ccedil;&Atilde;O DE SENHA DO CLIENTE </td>
    </tr>
    <tr>
        <td width="173" class="subtitulodireita">&nbsp;</td>
        <td width="224" class="campoesquerda">&nbsp;</td>
    </tr>
    <tr>
        <td class="subtitulodireita">C&oacute;digo do Cliente</td>
        <td class="campoesquerda">
            <?php if ($id_franquia == '247'){ ?>
                <input name="codigo" type="text" value="19120" id="codigo" class="form-control col30" maxlength="6" readonly />
            <?php }else{?>
                <input name="codigo" type="text" id="codigo" class="form-control col30" maxlength="6" />
            <?php }?>
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
        <td colspan="2">
            <input name="consulta" type="submit" value="         Consultar" onClick="return check(this.form);" />
        </td>
    </tr>
</table>
</form>
</body>
</html>

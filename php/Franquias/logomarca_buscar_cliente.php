<?php
require "connect/sessao.php";
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

function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espa�o em branco

function confirma(){
 	frm = document.form;	
    frm.action = 'painel.php?pagina1=Franquias/logomarca_buscar_cliente_listar.php';
	frm.submit();
}

function valida(){
	frm = document.form;	
	if(trim(frm.codigo.value) == ""){
		alert("Falta informar o Codigo !");
		frm.codigo.focus();
		return false;
	}
	confirma();
}	
</script>
<br>
<form name="form" method="post" action="#">
    <table width="80%" border="0" align="center">
        <tr class="titulo">
            <td colspan="2">INSERIR LOGOMARCA DE CLIENTE</td>
        </tr>
        <tr>
            <td width="30%" class="subtitulodireita">&nbsp;</td>
            <td width="70%" class="subtitulopequeno">&nbsp;</td>
        </tr>

        <tr>
            <td class="subtitulodireita">C&oacute;digo do cliente </td>
            <td class="campoesquerda"><input name="codigo" size="10" maxlength="6" onKeyPress="mascara(this,soNumeros)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
            </td>
        </tr>

        <tr>
            <td class="subtitulodireita">&nbsp;</td>
            <td class="subtitulopequeno"><?php echo $nome_franquia?></td>
        </tr>
        <tr>
            <td class="subtitulodireita">&nbsp;</td>
            <td class="subtitulopequeno">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="titulo"><input type="button" value="Envia" name="envia2" onclick="valida()"/></td>
        </tr>
    </table>
</form>

<?php if($_REQUEST['no'] == "1"){?>
	<script>alert("Cliente nao existe ou nao pertence a sua franquia !");</script>
<?php } ?>

<?php if($_REQUEST['no'] == "2"){?>
	<script>alert("Gravado com sucesso a Logomarca !");</script>
<?php } ?>
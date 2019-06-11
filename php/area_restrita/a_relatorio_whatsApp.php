<?php
require "connect/sessao.php";

?>
<script language="javascript">

function check(Form) {
var retorno = true;
if (document.RelWhats.codigo.value == "")
	{
	window.alert("Informe um Código de Cliente!");
	document.RelWhats.codigo.focus();
	return false;
	}
if (document.RelWhats.codigo.value == 0)
	{
	window.alert("Informe um Código diferente de 0");
	document.RelWhats.codigo.focus();
	return false;
	}
if (isNumeroString(document.RelWhats.codigo.value)!=1)
	{
	window.alert("Informe um Código numérico!");
	document.RelWhats.codigo.focus();
	return false;
	}
document.RelWhats.submit();
    return (true);
}

//função para aceitar somente numeros em determinados campos
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

// formato mascara data
function data(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    return v
}

function validaFone(id,valor){

    valor = valor.replace('(','');
    valor = valor.replace(')','');
    valor = valor.replace(' ','');
    valor = valor.replace('-','');

    
    var ver = valor.substring(2,10);
 
    if ( ver == '00000000' || 
         ver == '11111111' ||
         ver == '22222222' ||
         ver == '33333333' ||
         ver == '44444444' ||
         ver == '55555555' ||
         ver == '66666666' ||
         ver == '77777777' ||
         ver == '88888888' ||
         ver == '99999999' ||
         ver == '12345678' ){
        alert( 'NUMERO de telefone INVÁLIDO, favor corrigir !');
        if ( id == 'fone'){
           $('#fone').val('');
           $('#fone').focus();
       }else if ( id == 'fax'){
           $('#fax').val('');
           $('#fax').focus();
       }else if ( id == 'celular'){
           $('#celular').val('');
           $('#celular').focus();
       }else if ( id == 'fone_res'){
           $('#fone_res').val('');
           $('#fone_res').focus();
       }
    }

}

function xmascara(o,f){
        v_obj=o
        v_fun=f
        setTimeout("xexecmascara()",1)
}
function xexecmascara(){
        v_obj.value=v_fun(v_obj.value)
}

function mtel(v){
        v=v.replace(/\D/g,"");             //Remove tudo o que nao e digito
        v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parenteses em volta dos dois primeiros digitos
        v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hifen entre o quarto e o quinto digitos
        return v;
}
function id( el ){
        return document.getElementById( el );
}


window.onload = function(){
    id('telefone').onkeypress = function(){
        xmascara( this, mtel );
    }
	document.AltSenha.codigo.focus(); 
}
</script>

<body>
<br><br><br>
<form name="RelWhats" method="post" action="painel.php?pagina1=area_restrita/a_relatorio_whatsApp1.php">
<table width="70%" align="center">
    <tr>
        <td colspan="2" align="center" class="titulo">RELATORIO DE ENVIO WHATSAPP</td>
    </tr>
    <tr>
        <td width="173" class="subtitulodireita">&nbsp;</td>
        <td width="224" class="campoesquerda">&nbsp;</td>
    </tr>

    <tr>
        <td class="subtitulodireita">Periodo</td>
        <td class="campoesquerda">
            <input name="dti" type="text" id="dti" class="form-control col30" onKeyPress="mascara(this,data)" maxlength="12" />
            a
            <input name="dtf" type="text" id="dtf" class="form-control col30" onKeyPress="mascara(this,data)" maxlength="12" />
        </td>
    </tr>
   
    <tr>
        <td class="subtitulodireita">Telefone</td>
        <td class="campoesquerda">
            <input name="telefone" type="text" id="telefone" class="form-control col30" onchange="validaFone('fone',this.value)" maxlength="15" />
        </td>
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

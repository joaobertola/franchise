<?php
	$total_array = count($_REQUEST['selected']);
	$id_pretendentes = implode("-", $_REQUEST['selected']);
	
	//seleciona a mensagem para enviar o e-mail
	$sql_msg = "SELECT texto_email, descricao FROM cs2.pretendentes_status WHERE id = '{$_REQUEST['id_status']}'";
	$qry_msg = mysql_query($sql_msg, $con);
	$texto_email = mysql_result($qry_msg,0,'texto_email');	
	$descricao   = mysql_result($qry_msg,0,'descricao');	
?>
<script language="javascript">
function voltar(){
 	frm = document.form1;
    frm.action = 'painel.php?pagina1=area_restrita/pretendentes_form_listar.php';
	frm.submit();
} 

function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaço em branco

function enviar(){
 	frm = document.form1;
	
	if(trim(frm.assunto.value) == ""){
		alert("Falta informar o Assunto !");
		frm.assunto.focus();
		return false;
	}
    frm.action = 'area_restrita/pretendente_envia_email.php';
	frm.submit();
} 

window.onload = function(){	document.form1.assunto.focus(); } 

function mostrar(id){
	if (document.getElementById(id).style.display == 'none'){
		document.getElementById(id).style.display = '';
	}
}

</script>

<form name="form1" method="post" action="#">
<input type="hidden" name="doc" value="<?=$_REQUEST['doc']?>">
<input type="hidden" name="go" value="<?=$_REQUEST['go']?>">
<input type="hidden" name="nome" value="<?=$_REQUEST['nome']?>">
<input type="hidden" name="af" value="<?=$_REQUEST['af']?>">
<input type="hidden" name="at" value="<?=$_REQUEST['at']?>">
<input type="hidden" name="data1" value="<?=$_REQUEST['data1']?>">
<input type="hidden" name="data2" value="<?=$_REQUEST['data2']?>">
<input type="hidden" name="data_envio1" value="<?=$_REQUEST['data_envio1']?>">
<input type="hidden" name="data_envio2" value="<?=$_REQUEST['data_envio2']?>">
<input type="hidden" name="id_status" value="<?=$_REQUEST['id_status']?>">
<input type="hidden" name="id_pretendentes" value="<?=$id_pretendentes?>">

<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #D1D7DC; background-color:#FFFFFF" class="bodyText">
    
<tr><td width="20%" class="subtitulodireita"><b>Assunto:</b></td>
	<td width="80%" class="subtitulopequeno"><input type="text" name="assunto" value="<?=$descricao?>" maxlength="150" style="width:99%"></td></tr>

<tr><td height="25" bgcolor="#B0C4DE" colspan="2" align="center"><b>Texto que ser&aacute; enviado ao Pretendente</b></td><td></td></tr>    
<tr><td colspan="2"><?=$texto_email?></td></tr>
 
<tr><td colspan="2" align="center"><input type="button" value="Enviar" style="cursor:pointer" onfocus="mostrar('aguarde');return true;" onClick="enviar()"/>&nbsp;&nbsp;&nbsp;<input type="button" value="Voltar" style="cursor:pointer" onClick="voltar()"/>
    
</table>
</form>

<p align="center">
<table width="500px" height="50px" id="aguarde" style="display:none;" border="0" cellpadding="0" cellspacing="1" bgcolor="#FF6A6A" align="center">	
<tr>
<td width="10%" align="center"><img src="https://www.webcontrolempresas.com.br/franquias/img/ajax-loader.gif" height="50px"><td align="center"><font style="font-size:18px">Aguarde enviado e-mail</font></td>
</tr>		
</table> 
<p>

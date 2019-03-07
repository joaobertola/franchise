<?php include("../connect/conexao_conecta.php");?>
<script language="javascript">
function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaço em branco

function valida(){
frm = document.form;	
	if(trim(frm.email.value) == "" || frm.email.value.indexOf('@')==-1 || frm.email.value.indexOf('.')==-1){		
		alert("Falta informar o e-mail ou esta incorreto !");
		frm.email.focus();
		return false;
	}
	if(trim(frm.email2.value) == ""){		
		alert("Falta confirmar o e-mail !");
		frm.email2.focus();
		return false;
	}
	if( (frm.email.value) != (frm.email2.value) ){		
		alert("Os e-mails digitados nao conferem !");
		frm.email2.focus();
		return false;
	}
	frm.action = 'popup_cliente_pendencia_email.php';
	frm.submit();
}

window.onload = function(){	document.form.email.focus(); } 
</script>

<form name="form" method="post" action="#">

<input type="hidden" name="opcao" value="<?=$_REQUEST['opcao']?>">
<input type="hidden" name="cod_loja" value="<?=$_REQUEST['cod_loja']?>">
<input type="hidden" name="acao" value="1">

<table border="0" width="450px" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #333; background-color:#FFFFFF">
<tr>
<td colspan="2" align="center" bgcolor="#FF6600"><b>Atualize a conta de e-mail do cliente</b></td></tr>
<tr>
<td bgcolor="#CFCFCF">C&oacute;digo</td>
<td bgcolor="#E8E8E8"><?=$_REQUEST['cod_loja']?></td>
</tr>
<tr>

<td width="35%" bgcolor="#CFCFCF">E-mail</td>
<td width="65%" bgcolor="#E8E8E8" style="font-size:12px"><input type="text" name="email" maxlength="50" style="width:95%;border: 1px solid #999;	background-color: #FFFFFF;" ></td>
</tr>

<tr>
<td bgcolor="#CFCFCF">Confirma o E-mail</td>
<td bgcolor="#E8E8E8" style="font-size:12px"><input type="text" name="email2" maxlength="50" style="width:95%;border: 1px solid #999;
	background-color: #FFFFFF;"></td>
</tr>

<tr>
<td height="30" bgcolor="#E8E8E8" colspan="2" align="center"><input type="button" name="Confirma" value="Confirma" onClick="valida()" style="border-color:#000; border:1p solid; width:20%;background:#CCC;font-weight: bold;text-align: center;text-decoration: none;	cursor:pointer;height:20px;font-size: 11px;">&nbsp;&nbsp;<input type="button" name="Fechar" value="Fechar" onClick="window.close()" style="border-color:#000; border:1p solid; width:20%;background:#CCC;font-weight: bold;text-align: center;text-decoration: none;	cursor:pointer;height:20px;font-size: 11px;"></td>
</tr>
</table>
</form>


<?php if($_REQUEST['acao'] == '1') { 
$email = strtolower($_REQUEST['email']);
$email = trim($email);
$email = str_replace("'","",$email);
 
if (preg_match ("/^[A-Za-z0-9]+([_.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_.-][A-Za-z0-9]+)*\\.[A-Za-z0-9]{2,4}$/", $email)) {
		$sql_at_email= "UPDATE cs2.cadastro SET email = '$email', dt_atualizacao_email = now() 
					   WHERE codloja = '{$_REQUEST['cod_loja']}'";
		$qry_at_email = mysql_query($sql_at_email) or die ("Erro SQL: <br> $sql_atualizo_email <br><br>".mysql_error()."\n\n");
		if($qry_at_email){
		?>
        <script language="javascript">
			alert('Alterado com sucesso ! ');
			window.opener.location.href='../painel.php?pagina1=clientes/relatorio_cliente_pendencia_email.php&opcao=<?=$_REQUEST['opcao']?>';  
			window.close(); 
		</script>
        <?php
		}else{
		?>
          <script language="javascript">
			alert('Erro na alteração ! ');
			window.opener.location.href='../painel.php?pagina1=clientes/relatorio_cliente_pendencia_email.php&?opcao=<?=$_REQUEST['opcao']?>';  
			window.close(); 
		  </script>
        <?php
		}
} else {
		?><script language="javascript">alert('E-mail invalido ! ');</script><?php
}

$sql = "UPDATE cadastro SET email = '$email', dt_atualizacao_email = now() WHERE codloja = '{$_REQUEST['cod_loja']}'";
$qry = mysql_query($sql);
}
?>
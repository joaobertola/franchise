<?php
session_start();

$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];
/*
if (($name=="") || ($tipo!="a")){
	session_unregister($_SESSION['name']);
	session_destroy();
	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
	die;
}
*/
$sql = "SELECT id, descricao FROM cs2.pretendentes_status ORDER BY descricao";
$qry = mysql_query($sql, $con);
?>

<script language="javascript">
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}

// formato mascara data
function data(v){
    v=v.replace(/\D/g,"")//Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    v=v.replace(/(\d{2})(\d)/,"$1/$2")

    return v
}

window.onload = function() { document.form.doc.focus(); }

function seta(){
	document.form.data1.focus();
}

function mostrar(id){
	if (document.getElementById(id).style.display == 'none'){
		document.getElementById(id).style.display = '';
	}
}
</script>

<form method="post" action="painel.php?pagina1=area_restrita/pretendentes_form_listar.php" name="form">
  <table border="0" width="70%" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
    <tr>
      <td class="titulo" colspan="2" align="center" height="25">Relat&oacute;rio de Pretendentes a Franqueado</td>
    </tr>
    
    <tr height="25">
      <td width="30%" class="subtitulodireita">CPF:</td>
      <td width="70%" class="subtitulopequeno"><input type="text" name="doc" value="<?=$_REQUEST['doc']?>" onkeypress="soNumero()" maxlength="11" class="boxnormal" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" style="width:39%" />
        <input type=hidden value="filtrar" name="go" /></td>
    </tr>
    
    <tr height="25">
      <td class="subtitulodireita">Nome:</td>
      <td class="subtitulopequeno"><input type="text" name="nome" value="<?=$_REQUEST['nome']?>" maxlength="20"  class="boxnormal" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" style="width:39%" /></td>
    </tr>
    
    <tr height="25">
      <td class="subtitulodireita">Apresentado Fone?</td>
      <td class="subtitulopequeno"><select name="af" class="boxnormal" style="width:19%">
        <option value="2"></option>
        <option value="1" <?php if($_REQUEST['af'] == '1'){?> selected="selected" <?php }?> >Nao</option>
        <option value="0" <?php if($_REQUEST['af'] == '0'){?> selected="selected" <?php }?> >Sim</option>
      </select></td>
    </tr>
    
    <tr height="25">
      <td class="subtitulodireita">Agendado Treinamento?</td>
      <td class="subtitulopequeno">
     <input type="checkbox" name="at" onclick="seta()" <?php if($_REQUEST['at']){?> checked="checked" <?php } ?> />
      <input type="text" name="data1" id="data1" style="width:15%" onkeypress="formatar('##/##/####', this); soNumero()" maxlength="10" value="<?=$_REQUEST['data1']?>"/>&nbsp;&agrave;&nbsp;<input type="text" name="data2" id="data2"  style="width:15%" onkeypress="formatar('##/##/####', this); soNumero()" maxlength="10"  value="<?=$_REQUEST['data2']?>"/></td>
    </tr>

    <tr height="25">
      <td class="subtitulodireita">Per&iacute;odo</td>
      <td class="subtitulopequeno">
      <input type="text" name="data_envio1" id="data1" style="width:15%" onkeypress="formatar('##/##/####', this); soNumero()" maxlength="10" value="<?=$_REQUEST['data_envio1']?>"/>&nbsp;&agrave;&nbsp;<input type="text" name="data_envio2" id="data2"  style="width:15%" onkeypress="formatar('##/##/####', this); soNumero()" maxlength="10"  value="<?=$_REQUEST['data_envio2']?>"/></td>
    </tr>
    
    <tr height="25">
      <td class="subtitulodireita">Status</td>
      <td class="subtitulopequeno"><select name="id_status" class="boxnormal" style="width:39%">   
		<?php while($rs = mysql_fetch_array($qry)){ ?>
        	<?php if($_REQUEST['id_status'] == $rs['id']) { ?>
            	<option value="<?=$rs['id']?>" selected="selected"><?=$rs['descricao']?></option>	
            <?php } else { ?>
            	<option value="<?=$rs['id']?>"><?=$rs['descricao']?></option>	
			<?php } ?>            
        <?php } ?>
      </select></td>
    </tr>
    
    <tr height="25">
      <td>&nbsp;</td>
      <td><input type="submit" value="Procurar" style="cursor:pointer" onfocus="mostrar('aguarde');return true;"/></td>
    </tr>
  </table>
</form>
<p align="center">
<table width="500px" height="50px" id="aguarde" style="display:none;" border="0" cellpadding="0" cellspacing="1" bgcolor="#FF6A6A" align="center">	
<tr>
<td width="10%" align="center"><img src="https://www.webcontrolempresas.com.br/franquias/img/ajax-loader.gif" height="50px"><td align="center"><font style="font-size:18px">Aguarde carregado as informa&ccedil;&otilde;es.</font></td>
</tr>		
</table> 
<p>
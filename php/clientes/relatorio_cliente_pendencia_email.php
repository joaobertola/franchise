<?php
include("../../../gestor/funcao_php/mascaras.php");

//regra so entra se não for Fanquia Junior
if($_SESSION['id_master'] > 0){
	exit;
}

if($_REQUEST['opcao'] == 1){
$sql = "select a.fone, a.email, a.codloja, a.razaosoc, MID(b.logon,1,LOCATE('S', b.logon) - 1) AS logon
from cs2.cadastro a
inner join cs2.logon b on a.codloja = b.codloja
where sitcli < 2 and email <> '' and dt_atualizacao_email is null
and id_franquia = '{$_SESSION['id']}' ORDER BY a.razaosoc";
$texto = "<u>E-mail's cadastrados por&eacute;m n&atilde;o atualizado</u>";
}else{
$texto = "<u>E-mail's n&atilde;o cadastrados na WEB CONTROL EMPRESAS</u>";
$sql = "select a.fone, a.codloja, a.razaosoc, MID(b.logon,1,LOCATE('S', b.logon) - 1)AS logon 
from cs2.cadastro a
inner join cs2.logon b on a.codloja = b.codloja
where sitcli < 2 and email = '' and dt_atualizacao_email is null
and id_franquia = '{$_SESSION['id']}' ORDER BY a.razaosoc";
}
$qry = mysql_query($sql);
$total = mysql_num_rows($qry)
?>

<script language="javascript">
function alterarEmail(p_cod_loja, p_opcao){
cupom  = open('clientes/popup_cliente_pendencia_email.php?cod_loja='+p_cod_loja+'&opcao='+p_opcao, 'email', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+500+',height='+200+',left='+350+', top='+100+',screenX='+0+',screenY='+0+''); 
}    

function email1(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=clientes/relatorio_cliente_pendencia_email.php&opcao=1';
	frm.submit();
} 


function email2(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=clientes/relatorio_cliente_pendencia_email.php&opcao=2';
	frm.submit();
} 
</script>
<form name="form" method="post" action="#">    
<table border="0" width="90%" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
<tr><td colspan="5">

<table border="0" align="center" width="100%">
<tr><td width="50%" align="left"><input type="button" name="atualiza" title="Clique para visualizar" value="E-mail's cadastrados por&eacute;m n&atilde;o atualizado" onclick="email1()" style="cursor:pointer"/></td>
<td align="right"><input type="button" name="atualiza" title="Clique para visualizar" value="E-mail's n&atilde;o cadastrados na WEB CONTROL EMPRESAS" onclick="email2()" style="cursor:pointer"/></td></tr>
</table>

</td></tr>

<tr><td align="center" colspan="5" bgcolor="#FF6600"><b>Relat&oacute;rio de E-mail's para Corre&ccedil;&atilde;o [<?=$texto?>]</b></td></tr>
<tr bgcolor="#CFCFCF">
<td width="6%"><b>C&oacute;digo</b></td>
<td width="55%"><b>Raz&atilde;o Social</b></td>
<td width="21%"><b>E-mail</b></td>
<td width="13%"><b>Telefone</b></td>
<td width="5%">&nbsp;</td>
</tr>

<?php 
	$a_cor = array("#CFCFCF", "#FFFFFF");
	$cont=0;
	while($rs = mysql_fetch_array($qry)) {
	$cont++;
?>
<tr bgcolor="<?=$a_cor[$cont % 2]?>" >
	<td><?=$rs['logon']?></td>
    <td><?=$rs['razaosoc']?></td>
    <td><?=$rs['email']?></td>
    <td><?=telefoneConverte($rs['fone'])?></td>
    <td align="center"><a href="#" onclick="alterarEmail('<?=$rs['codloja']?>','<?=$_REQUEST['opcao']?>');"><img src="../../../gestor/img/alterar.png" border="0" height="15px" width="15px" title="Alterar o e-mail"></a></td>
</tr>	
<?php } ?>
<tr><td colspan="5" align="right">Total de <b><?=$total?></b> registro(s) encontrado(s)</td></tr>
</table>
</form>
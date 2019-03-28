<?php
include("../connect/conexao_conecta.php");

$id_func = $_REQUEST['id_func'];

$sql = "SELECT nome FROM funcionario WHERE id = $id_func";
$qr = mysql_query($sql);
$nome       = mysql_result($qr, 0, 'nome');
?>
<script type="text/javascript" src="../../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../../js/jquery.meio.mask.js"></script>

<script language="javascript">
jQuery(function($){
   $("#data_id").mask("99/99/9999");     
});

(function($){
$(
	function(){
		$('input:text').setMask();
	}
);
})(jQuery);

function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espa�o em branco

function valida(){
	frm = document.form;	
	
	if(trim(frm.data_pgto.value) == ""){
		alert("Falta informar a Data de Pagamento !");
		frm.data_pgto.focus();
		return false;
	}
	if(trim(frm.valor.value) == ""){
		alert("Falta informar o Valor do Pagamento !");
		frm.valor.focus();
		return false;
	}
	if(trim(frm.descricao.value) == ""){
		alert("Falta informar a DESCRICAO !");
		frm.descricao.focus();
		return false;
	}
	
	frm.action = 'Franquias/pagamentos_funcionarios_bd.php';
	frm.submit();
}

window.onload = function(){	document.form.data_id.focus(); } 
</script>

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/tabela.css" rel="stylesheet" type="text/css" />

</head>

<form name="form" method="post" action="#">
<input type="hidden" name="acao" value="G">
<input type="hidden" name="id_func" value="<?=$_REQUEST['id_func']?>">

<p>&nbsp;</p>

<table border="0" width="99%" align="center" cellpadding="2" cellspacing="1" class="borda_tabela">
<tr>
    <td colspan="2" class="titulo">INCLUS&Atilde;O DE PAGAMENTOS A FUNCION&Aacute;RIOS</td>
</tr>

<tr>
    <td width="30%" class="subtitulodireita">Funcion&aacute;rio</td>
    <td width="70%" class="subtitulopequeno"><?=$nome?></td>
</tr>

<tr>
    <td width="30%" class="subtitulodireita">Data do pagamento</td>
    <td width="70%" class="subtitulopequeno"><input id="data_id" name="data_pgto" type="text" class="boxnormal" style="width:20%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />(*)</td>
</tr>


<tr>
    <td class="subtitulodireita">Valor do Pagamento</td>
    <td class="subtitulopequeno"><input name="valor" alt="decimal" type="text" style="width:20%" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />(*)</td>
</tr>

<tr>
    <td class="subtitulodireita">Descri&ccedil;&atilde;o</td>
    <td class="subtitulopequeno" height="100">
    <textarea name="descricao" rows="5" cols="90">

</textarea>(*)</td>
</tr>

<tr class="altura_button">    
    <td>&nbsp;</td>
    <td><input type="button" value="Confirma&nbsp;" class="botao_padrao" onclick="valida()"/>&nbsp;&nbsp;&nbsp;<input type="button" value="Voltar&nbsp;" class="botao_padrao" onclick="history.back()"/></td>
</tr>

</table>

</form>
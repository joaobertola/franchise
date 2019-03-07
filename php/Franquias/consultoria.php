<?php
include("../connect/conexao_conecta.php");
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
	
	if(trim(frm.data_visita.value) == ""){
		alert("Falta informar a Data de Visita !");
		frm.data_visita.focus();
		return false;
	}
	if(trim(frm.consultora.value) == ""){
		alert("Falta informar o nome da(o) consultor (a) !");
		frm.consultora.focus();
		return false;
	}
	
	frm.action = 'Franquias/consultoria2.php';
	frm.submit();
}

window.onload = function(){	document.form.consultora.focus(); } 
</script>

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/tabela.css" rel="stylesheet" type="text/css" />

</head>

<form name="form" method="post" action="#">
<input type="hidden" name="acao" value="1">
<input type="hidden" name="codloja" value="<?=$_REQUEST['codloja']?>">
<input type="hidden" name="idfranqueado" value="<?=$_REQUEST['idfranqueado']?>">
<input type="hidden" name="status" value="<?=$_REQUEST['status']?>">
<input type="hidden" name="cidade" value="<?=$_REQUEST['cidade']?>">

<p>&nbsp;</p>

<table border="0" width="99%" align="center" cellpadding="2" cellspacing="1" class="borda_tabela">
<tr>
    <td colspan="2" class="titulo">CONSULTORIA</td>
</tr>

<tr>
    <td width="30%" class="subtitulodireita">Cliente</td>
    <td width="70%" class="subtitulopequeno"><?=$_REQUEST['codloja']?></td>
</tr>

<tr>
    <td width="30%" class="subtitulodireita">Data da Visita</td>
    <td width="70%" class="subtitulopequeno"><input id="data_id" name="data_visita" value="<?=date("d/m/Y")?>" type="text" class="boxnormal" style="width:30%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />(*)</td>
</tr>


<tr>
    <td class="subtitulodireita">Consultor(a)</td>
    <td class="subtitulopequeno"><input name="consultora" type="text" class="boxnormal" style="width:30%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"/>(*)</td>
</tr>


<tr class="altura_button">    
    <td>&nbsp;</td>
    <td><input type="button" value="Confirma&nbsp;" class="botao_padrao" onclick="valida()"/>&nbsp;&nbsp;&nbsp;<input type="button" value="Voltar&nbsp;" class="botao_padrao" onclick="history.back()"/></td>
</tr>

</table>

</form>
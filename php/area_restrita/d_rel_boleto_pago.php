<?php
require "connect/sessao.php";

$datax = date('d/m/Y');

?>
<script type="text/javascript">

function valida(){
	frm = document.form;
	if(frm.data1.value == ''){
		alert('Falta informar a Data Inicial !');
		frm.data1.focus();
		return false;
	}
	if(frm.data2.value == ''){
		alert('Falta informar a Data Final!');
		frm.data2.focus();
		return false;
	}
	enviar();
}

function enviar(){
	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/d_rel_boleto_pago2.php';
	frm.submit();	
}

function MM_formtData(e,src,mask) {
	if(window.event) { 
		_TXT = e.keyCode;
	}
	else if(e.which) { 
		_TXT = e.which;
	}
	if(_TXT > 47 && _TXT < 58) {
		var i = src.value.length; 
		var saida = mask.substring(0,1); 
		var texto = mask.substring(i)
		if (texto.substring(0,1) != saida) { 
			src.value += texto.substring(0,1); 
		}
		return true; 
	} else { 
		if (_TXT != 8) {
			return false;
		} else {
			return true;
		}
	}
}
</script>

<form method="post" action="#" name='form'>
<table width=70% border="0" align="center">
	<tr class="titulo">
		<td colspan="2">Titulos Recebido em Banco</p></td>
	</tr>
    <tr>
		<td class="subtitulodireita">&nbsp;</td>
		<td class="campoesquerda">&nbsp;</td>
	</tr>
    <tr>
		<td class="subtitulodireita">Tipo</td>
		<td class="campoesquerda">
        	<select name="situacao">
            	<option value="0">MENSALIDADE</option>
            	<option value="1">CREDIARIO/RECUPERE/BOLETO</option>
            	<option value="2">PROCESSAMENTO NEXXERA</option>
            </select>
        </td>
	</tr>
	<tr>
		<td class="subtitulodireita">Per&iacute;odo de Pagamento:</td>
		<td class="subtitulopequeno">
			<input type="text" name="data1" id="data1" onKeyPress="return MM_formtData(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10" value="<?php echo $datax; ?>"> a
			<input type="text" name="data2" id="data2" onKeyPress="return MM_formtData(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10" value="<?php echo $datax; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="titulo" align="center">
			<input type="button" name="enviar" value="    Processar    " onclick="valida()" />
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
</form>
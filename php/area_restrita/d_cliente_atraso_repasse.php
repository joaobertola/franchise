<?php
require "connect/sessao.php";

$sql = "SELECT codloja, data_solicitacao, qtd_parcelas, numero_parcela,
			   data_vencimento, vr_emprestimo_solicitado, valor_parcela
		FROM cs2.cadastro_emprestimo
		WHERE 
				data_vencimento <= NOW() 
			AND 
				valor_pagamento IS NULL";
$qry = mysql_query($sql);
if ( mysql_num_rows($qry) > 0 ){
	echo "Nenhum cliente com empréstimo ATRASADO.<p><input type='button' onClick='history.back()'\></p>";
	exit;
}

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
	if(frm.id_franquia.value == '0'){
		alert('Selecione a Franquia !');
		frm.id_franquia.focus();
		return false;
	}
	enviar();
}

function enviar(){
	frm = document.form;
    frm.action = 'painel.php?pagina1=area_restrita/d_financeiro_comercial2.php';
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
		<td colspan="2">Relat&oacute;rio Financeiro - Departamento Comercial<p>Objetivo: Visualizar o quanto foi gasto pela Empresa por cada cliente,<br>o quanto foi pago pelo cliente "mensalidade+consumo",<br>mostrando o "LUCRO/PREJUIZO" por cada cliente</p></td>
	</tr>
    <tr>
		<td class="subtitulodireita">&nbsp;</td>
		<td class="campoesquerda">&nbsp;</td>
	</tr>
    <tr>
		<td class="subtitulodireita">Situação</td>
		<td class="campoesquerda">
        	<select name="situacao">
            	<option value="0">ATIVO</option>
            	<option value="1">BLOQUEADO</option>
                <option value="2" selected="selected">CANCELADO</option>
            </select>
        </td>
	</tr>
	<tr>
		<td class="subtitulodireita">Per&iacute;odo: 
        	<select name="chave">
            	<option value="data_cadastro">Data da Cadastro</option>
                <option value="data_cancelamento" selected="selected">Data de Cancelamento</option>
            </select>
        </td>
		<td class="subtitulopequeno">
			<input type="text" name="data1" id="data1" onChange="" onKeyPress="return MM_formtData(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10"> a
			<input type="text" name="data2" id="data2" onChange="" onKeyPress="return MM_formtData(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10">
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Franquia:</td>
		<td class="subtitulopequeno">
            <?php
	    	if (($tipo == "a") || ($tipo == "c")) $todas = '';
			else $todas = "AND id = $id_franquia";
			echo "<select name='id_franquia' id='id_franquia' onchange='CarregaCidade_Franquia(this.value)'>";
			echo $sql = "select * from franquia where sitfrq='0' $todas order by id";
			$resposta = mysql_query($sql);
			$txt_franquia = "<option value='0'>Selecione a Franquia</option>";
			while ($array = mysql_fetch_array($resposta))
				{
				$franquia   = $array["id"];
				$nome_franquia = $array["fantasia"];
				$txt_franquia .="<option value=\"$franquia\">$nome_franquia</option>\n";
				}
				echo $txt_franquia;
			echo "</select>";
			?>
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
<?php
require "connect/sessao.php";
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script language="JavaScript">

function TestaCaracteresConsulta(form, num) {

	var Fantasia;
	var Razao;
	var Cont;
	var Caractere;

	for(Cont = 1; Cont <= 17; Cont ++) {
		if(Cont == 1){
			Caractere = "[";
		} else if(Cont == 2) {
			Caractere = "]";
		} else if(Cont == 3) {
			Caractere = "{";
		} else if(Cont == 4) {
			Caractere = "}";
		} else if(Cont == 5) {
			Caractere = "<";
		} else if(Cont == 6) {
			Caractere = ">";
		} else if(Cont == 7) {
			Caractere = "*";
		} else if(Cont == 8) {
			Caractere = "+";
		} else if(Cont == 9) {
			Caractere = "%";
		} else if(Cont == 10) {
			Caractere = "$";
		} else if(Cont == 11) {
			Caractere = "&";
		} else if(Cont == 12) {
			Caractere = "!";
		} else if(Cont == 13) {
			Caractere = "?";
		} else if(Cont == 14) {
			Caractere = "#";
		} else if(Cont == 15) {
			Caractere = "_";
		} else if(Cont == 16) {
			Caractere = "'";
		} else {
			Caractere = "=";
		}
	
		if(num == 1) {
			var Fantasia = document.consNOM.fantasia.value;
			if( Fantasia.indexOf(Caractere) != -1) {
				window.alert("O formulário não pode conter os seguintes caracteres:\n\n" + "=>  [  ]  {  } <  >\n\n" + "=>  %  $  &  !  ?  #\n\n" + "=>  *  +  =  '  _");
				return( false );
			}
		}

		if(num == 2) {
			var Razao = document.consRAZ.razao.value;
			if( Razao.indexOf(Caractere) != -1 ) {
				window.alert("O formulário não pode conter os seguintes caracteres:\n\n" + "=>  [  ]  {  } <  >\n\n" + "=>  %  $  &  !  ?  #\n\n" + "=>  *  +  =  '  _");
				return( false );
			}
		}
	}	
	if(num == 1) {
		check1(form);
	} else {
		check2(form);
	}
}

function check(Form) {
var retorno = true;
if (document.consCNPJ.cnpj.value == "")
	{
	window.alert("Informe um CNPJ de Cliente!");
	document.consCNPJ.cnpj.focus();
	return false;
	}
if (document.consCNPJ.cnpj.value == 0)
	{
	window.alert("Informe um CNPJ diferente de 0");
	document.consCNPJ.cnpj.focus();
	return false;
	}
document.consCNPJ.submit();
return (true);
}

function check1(Form) {
var retorno1 = true;
if (document.consNOM.fantasia.value == "")
	{
	window.alert("Informe um Nome!");
	document.consNOM.fantasia.focus();
	return false;
	}
document.consNOM.submit();
return (true);
}
function check2(Form) {
var retorno2 = true;
if (document.consRAZ.razao.value == "")
	{
	window.alert("Informe uma Razão Social!");
	document.consRAZ.razao.focus();
	return false;
	}
document.consRAZ.submit();
return (true);
}

function check3(Form) {
var retorno3 = true;
if (document.consCodigo.codigo.value == "")
	{
	window.alert("Informe um Código de Cliente!");
	document.consCodigo.codigo.focus();
	return false;
	}
if (document.consCodigo.codigo.value == 0)
	{
	window.alert("Informe um Código diferente de 0");
	document.consCodigo.codigo.focus();
	return false;
	}
document.consCodigo.submit();
return (true);
}

function check4(Form) {
var retorno4 = true;
if (document.consID.id.value == "")
	{
	window.alert("Informe um ID de Cliente!");
	document.consID.id.focus();
	return false;
	}
if (document.consID.id.value == 0)
	{
	window.alert("Informe um ID diferente de 0");
	document.consID.id.focus();
	return false;
	}
document.consID.submit();
return (true);
}

function check_fone(Form) {
var retorno4 = true;
if (document.consFone.ddd.value == "")
	{
	window.alert("Informe o DDD do Cliente!");
	document.consFone.ddd.focus();
	return false;
	}
if (document.consFone.fone.value == 0)
	{
	window.alert("Informe o Telefone do Cliente !");
	document.consFone.fone.focus();
	return false;
	}
document.consFone.submit();
return (true);
}

// formato mascara CNPJ, telefone e CPF
function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
	documento.value += texto.substring(0,1);
  }
}

window.onload = function() {
	document.consCodigo.codigo.focus(); 
}


function proximoCampo(atual,proximo){
	if(atual.value[0] == '3'){
		if(atual.value.length == 8){
			document.getElementById(proximo).focus();
		}
	}
	else {
		if (atual.value.length >= atual.maxLength) {
			document.getElementById(proximo).focus();
		}
	}
}
</script>

<br>

<table width=90% border="0" align="center">
	<tr class="titulo">
		<td colspan="3">INFORME OS PAR&Acirc;METROS PARA A PESQUISA </td>
	</tr>
	<tr>
		<td width="40%">&nbsp;</td>
		<td width="50%">&nbsp;</td>
		<td width="10%">&nbsp;</td>
	</tr>
	<!--form name="consCodigo" method="post" action="painel.php?pagina1=clientes/a_cons_id.php"-->
	<form name="consCodigo" method="post" action="painel.php?pagina1=clientes/cliente_atualizacao_obrigatoria.php">
		<tr>
			<td class="subtitulodireita">C&oacute;digo do cliente </td>
			<td class="campoesquerda"><input name="codigo" size="12" maxlength="6" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
			<td class="campoesquerda"><input type="button" value="Envia" name="B12" onClick="return check3(this.form);"/></td>
		</tr>
	</form>
	<tr>
		<td colspan="3">
			<table width="100%" <?php if ($tipo == 'b') echo "style=\"display:none\"" ?> >
				<tr>
					<td width="40%">&nbsp;</td>
					<td width="50%">&nbsp;</td>
					<td width="10%">&nbsp;</td>
				</tr>
				<!-- form name="consID" method="post" action="painel.php?pagina1=clientes/a_cons_id.php"-->
				<form name="consID" method="post" action="painel.php?pagina1=clientes/cliente_atualizacao_obrigatoria.php">
					<tr>
						<td class="subtitulodireita">ID do cliente</td>
						<td class="campoesquerda"><input name="id" size="12" maxlength="6" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
						<td class="campoesquerda"><input type="button" value="Envia" name="B13" onClick="return check4(this.form);"/></td>
					</tr>
				</form>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<!--form name="consCNPJ" method="post" action="painel.php?pagina1=clientes/a_cons_cnpj.php"-->
	<form name="consCNPJ" method="post" action="painel.php?pagina1=clientes/cliente_atualizacao_obrigatoria.php">
		<tr>
			<td class="subtitulodireita">CNPJ</td>
			<td class="campoesquerda"><input type="text" name="cnpj" size="21" maxlength="19" onKeyPress="formatar('##.###.###/####-##', this)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
				* digite apenas n&uacute;meros</td>
			<td class="campoesquerda"><input type="button" value="Envia" name="B2" onClick="return check(this.form);" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</form>
	<form name="consFone" method="post" action="painel.php?pagina1=clientes/a_cons_telefone.php">
		<tr>
			<td class="subtitulodireita">Telefone do Cliente</td>
			<td class="campoesquerda">

				<input type="text" name="ddd" size="5" maxlength="2" class="boxnormal"  id="ddd" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onkeyup="proximoCampo(this, 'telefone')" />
				<input type="text" name="fone" size="25" maxlength="9" class="boxnormal" id="telefone" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onkeyup="proximoCampo(this, 'botao')" />
				* digite apenas n&uacute;meros</td>
			<td class="campoesquerda"><input type="button" id="botao" value="Envia" name="B2" onClick="return check_fone(this.form);" /></td>
		</tr>
	</form>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<form name="consNOM" method="post" action="painel.php?pagina1=clientes/a_cons_nome.php">
		<tr>
			<td class="subtitulodireita">Nome de Fantasia </td>
			<td class="campoesquerda"><input type="text" name="fantasia" size="50" maxlength="50" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
			<td class="campoesquerda">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">&nbsp;</td>
			<td class="campoesquerda">
				<input type="radio" name="opnom" value="0" checked />Cliente Ativo
				<input type="radio" name="opnom" value="1" />
				Cliente Bloqueado
				<input type="radio" name="opnom" value="2" />Cancelado</td>
			<td class="campoesquerda"><input type="button" value="Envia" onClick="TestaCaracteresConsulta(form,1);" /></td>
		</tr>
	</form>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<form name="consRAZ" method="post" action="painel.php?pagina1=clientes/a_cons_razao.php">
		<tr>
			<td class="subtitulodireita">Raz&atilde;o Social </td>
			<td class="campoesquerda"><input type="text" name="razao" size="50" maxlength="50" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
			<td class="campoesquerda">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">&nbsp;</td>
			<td class="campoesquerda"><input type="radio" name="opraz" value="0" checked />Cliente Ativo
				<input type="radio" name="opraz" value="1" />
				Cliente Bloqueado
				<input type="radio" name="opraz" value="2" />Cancelado</td>
			<td class="campoesquerda"><input type="button" value="Envia" onClick="TestaCaracteresConsulta(form,2);" /></td>
		</tr>
	</form>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" class="titulo">&nbsp;</td>
	</tr>
</table>
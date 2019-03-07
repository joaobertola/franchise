<?php
require "connect/sessao.php";

$codloja  = $_GET['codloja'];
$razaosoc = $_GET['razaosoc'];
$logon 	  = $_GET['logon'];
?>
<script type="text/javascript">
<!-- Original:  Cyanide_7 (leo7278@hotmail.com) -->
<!-- Web Site:  http://members.xoom.com/cyanide_7 -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
var isNN = (navigator.appName.indexOf("Netscape")!=-1);
function autoTab(input,len, e) {
var keyCode = (isNN) ? e.which : e.keyCode; 
var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
if(input.value.length >= len && !containsElement(filter,keyCode)) {
input.value = input.value.slice(0, len);
input.form[(getIndex(input)+1) % input.form.length].focus();
}
function containsElement(arr, ele) {
var found = false, index = 0;
while(!found && index < arr.length)
if(arr[index] == ele)
found = true;
else
index++;
return found;
}
function getIndex(input) {
var index = -1, i = 0, found = false;
while (i < input.form.length && index == -1)
if (input.form[i] == input)index = i;
else i++;
return index;
}
return true;
}
//  End -->

//fun��o para aceitar somente numeros em determinados campos
function soNumero() {
    var tecla;
    tecla = event.keyCode;
    if (tecla < 48 || tecla > 57)  event.returnValue = false;
}
//para mostrar ou ocultar os campos
function mostrar(id)
{
	if (document.getElementById(id).style.display == 'none')
	{
		document.getElementById(id).style.display = '';
	}
}

function ocultar(id)
{
	document.getElementById(id).style.display = 'none';
}

// formato mascara data
function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
	documento.value += texto.substring(0,1);
  }
}

//Validar data
function validaData(campo)
{
	if (campo.value!="")
	{
		erro=0;
		hoje = new Date();
		anoAtual = hoje.getFullYear();
		mesAtual = hoje.getMonth();
		diaAtual = hoje.getDate();
		barras = campo.value.split("/");
		if (barras.length == 3)
		{
			dia = barras[0];
			mes = barras[1];
			ano = barras[2];
			resultado = (!isNaN(dia) && (dia > 0) && (dia < 32)) && (!isNaN(mes) && (mes > 0) && (mes < 13)) && (!isNaN(ano) && (ano.length == 4) && (ano <= anoAtual && ano >= (anoAtual - 5)));
			if (!resultado)
			{
				alert("Data invalida.");
				campo.focus();
				return false;
			}
		 } 
		 else
		 {
			 alert("Data invalida.");
			 campo.focus();
			 return false;
		 }
	return true;
	}
}
</script>
<form method="post" action="painel.php?pagina1=clientes/a_extratoconsulta1.php" >
	<input type="hidden" name="logon" value="<?php echo $logon ?>">
	<table width=90% border="0" align="center">
		<tr class="titulo">
			<td colspan="2">EXTRATO DE CONSULTAS POR CLIENTE </td>
		</tr>
		<tr>
			<td class="subtitulodireita" width="40%">&nbsp;</td>
			<td class="subtitulopequeno" width="60%">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">C&oacute;digo do cliente </td>
			<td class="subtitulopequeno"><?php echo $logon." - ".$razaosoc ?><input type="hidden" value="<?php echo $codloja; ?>" name="codloja" />
				<input type="hidden" value="<?php echo $razaosoc; ?>" name="razaosoc" /></td>
		</tr>

		<tr>
			<td class="subtitulodireita" height="75">
				Por Per&iacute;odo
				<input type="radio" name="relatorio" value="periodo" onFocus="ocultar('faturamentotb');mostrar('periodotb');return true;" id="periodo" />
				<br>
				Por Faturamento
				<input type="radio" name="relatorio" value="faturamento" onFocus="ocultar('periodotb');mostrar('faturamentotb');return true;" id="faturamento" /></td>
			<td class="subtitulopequeno">
				<table id="periodotb" style="display:none;">
					<tbody>
					<tr>
						<td class="subtitulo" colspan="2">Consultas por Periodo</td>
					</tr>
					<tr>
						<td class="campoesquerda" align="right">Data Inicial (dd/mm/aaaa)</td>
						<td class="campoesquerda"><input name="inicial" id="inicial"
														 type="text" onKeyPress="formatar('##/##/####', this)" onkeyup="autoTab(this, 10, event)"
														 onfocus="this.className='boxover'" onBlur="this.className='boxnormal'; validaData(this);"
														 style="FONT-FAMILY: Arial; FONT-SIZE: 8pt"
														 maxlength="10"></td>
					</tr>
					<tr>
						<td width="50%" class="campoesquerda" align="right">Data Final (dd/mm/aaaa)</td>
						<td width="50%" class="campoesquerda"><input name="final" id="final"
																	 type="text" onKeyPress="formatar('##/##/####', this)"
																	 onfocus="this.className='boxover'" onBlur="this.className='boxnormal'; validaData(this);"
																	 style="FONT-FAMILY: Arial; FONT-SIZE: 8pt"
																	 maxlength="10" /></td>
					</tr>
					</tbody>
				</table>
				<table id="faturamentotb" style="display:none;">
					<tbody>
					<tr>
						<td class="subtitulo" colspan="2">Consultas por Faturamento</td>
					</tr>
					<tr>
						<td width="20%" class="campoesquerda">Faturas Emitidas</td>
						<td width="80%" class="campoesquerda">
							<select name="parametro" id="parametro">

								<?php
								$sql = "select numdoc, date_format(vencimento, '%d/%m/%Y') as vencimento, date_format(dti, '%d/%m/%Y') as dti, date_format(dtf, '%d/%m/%Y') as dtf from titulos where codloja=$codloja order by vencimento";
								$resposta = mysql_query($sql, $con);
								while ($array = mysql_fetch_array($resposta)){
									$numdoc   = $array["numdoc"];
									$vencimento = $array["vencimento"];
									$dti = $array["dti"];
									$dtf = $array["dtf"];
									echo "<option value=\"$numdoc\">$numdoc - $vencimento - $dti - $dtf</option>\n";
								} ?>

							</select>
							<br />(N�mero de documento - Vencimento - Inicio do Faturamento - Fim do Faturamento)
						</td>
					</tr>
					</tbody>
				</table>    </td>
		</tr>
		<tr>
			<td class="subtitulodireita">&nbsp;</td>
			<td class="subtitulopequeno">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="titulo">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="center"><input type="submit" name="enviar" id="enviar" value="      Enviar" />
				<input name="button" type="button" onClick="javascript: history.back();" value="Voltar       " /></td>
		</tr>
	</table>
</form>
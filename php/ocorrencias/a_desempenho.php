		<?php
		require "connect/sessao.php";
		require "connect/conexao_conecta.php";
		$hoje = date('d/m/Y')
		?>
		<script type="text/javascript">
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

		function foco_1(){
			frm = document.form;
			frm.inicial.focus();
		}

		function foco_2(){
			frm = document.form;
			frm.codigo.focus();
		}

		function valida(){
			frm = document.form;
			if ((!frm.relatorio[0].checked) && (!frm.relatorio[1].checked) ){
				alert('Falta informar se o Relatório é por: Período ou Cliente !');
				return false;
			}

			if (frm.relatorio[0].checked){
				if( (frm.inicial.value == '') || (frm.inicial.value == '') ){
					alert('Falta informar a Data Inicial e Final !');
					frm.inicial.focus();
					return false;
				}
			}

			if (frm.relatorio[1].checked){
				if(frm.codigo.value == ''){
					alert('Falta informar o Código do Cliente !');
					frm.codigo.focus();
					return false;
				}
			}
			enviar();
		}

		function enviar(){
			frm = document.form;
			frm.action = 'painel.php?pagina1=ocorrencias/a_rel_desempenho.php';
			frm.submit();
		}

			function MM_formtCep(e,src,mask) {
				if(window.event) { _TXT = e.keyCode; }
				else if(e.which) { _TXT = e.which; }
				if(_TXT > 47 && _TXT < 58) {
					var i = src.value.length; var saida = mask.substring(0,1); var texto = mask.substring(i)
					if (texto.substring(0,1) != saida) { src.value += texto.substring(0,1); }
				return true; } else { if (_TXT != 8) { return false; }
				else { return true; }
			}
		}
		</script>

		<form method="post" name="form" action="#" >
			<table width="90%" border="0" align="center">
				<tr class="titulo">
					<td colspan="2">RELAT&Oacute;RIO DE COBRAN&Ccedil;A / ATENDIMENTO</td>
				</tr>
				<tr>
					<td class="subtitulodireita" width="35%">&nbsp;</td>
					<td class="subtitulopequeno" width="65%">&nbsp;</td>
				</tr>
				<tr>
					<td class="subtitulodireita">Atendente</td>
					<td class="subtitulopequeno"><?php
						if ($tipo == "b") $frq =  " WHERE franquia='$id_franquia'";
						else $frq = " WHERE franquia IN(1)";

						echo "<select name='atendente' class='boxnormal' onChange='foco()'>";
						echo "<option value=''>.: Selecione :.</option>";
						echo "<option value='0'>.: Todos :.</option>";
						$sql = "select id, atendente from cs2.atendentes $frq AND situacao = 'A' order by atendente";
						//	$sql = "select id, atendente from cs2.atendentes WHERE franquia='$id_franquia' order by atendente";
						$resposta = mysql_query($sql, $con);
						while ($array = mysql_fetch_array($resposta)) {
							$id_atendente   = $array["id"];
							$nome_atendente = $array["atendente"];
							echo "<option value=\"$id_atendente\">$nome_atendente</option>\n";
						}
						echo "</select>";
						?>
					</td>
				</tr>
				<tr>
					<td height="50" class="subtitulodireita">
						Por Per&iacute;odo
						<input type="radio" name="relatorio" value="2" onFocus="ocultar('faturamentotb');mostrar('periodotb');return true;" onclick="foco_1()" />
					</td>
					<td rowspan="2" class="subtitulopequeno">
						<table id="periodotb" style="display:none;">
							<tbody>
							<tr>
								<td class="subtitulo" colspan="2">Consultas por Periodo</td>
							</tr>
							<tr>
								<td class="campoesquerda" align="right">Data Inicial (dd/mm/aaaa)</td>
								<td class="campoesquerda"><input name="inicial" id="inicial"
																 type="text" onKeyPress="formatar('##/##/####', this)" onkeyup="autoTab(this, 10, event)"
																 onfocus="this.className='boxover'" onBlur="this.className='boxnormal';"
																 style="FONT-FAMILY: Arial; FONT-SIZE: 8pt"
																 maxlength="10" value="<?php echo $hoje; ?>">&nbsp;dd/mm/aaaa</td>
							</tr>
							<tr>
								<td width="50%" class="campoesquerda" align="right">Data Final (dd/mm/aaaa)</td>
								<td width="50%" class="campoesquerda">
									<input name="final" id="final"
										   type="text" onKeyPress="formatar('##/##/####', this)"
										   onfocus="this.className='boxover'" onBlur="this.className='boxnormal';"
										   style="FONT-FAMILY: Arial; FONT-SIZE: 8pt"
										   maxlength="10" value="<?php echo $hoje; ?>" >&nbsp;dd/mm/aaaa
								</td>
							</tr>
							</tbody>
						</table>
						<table id="faturamentotb" style="display:none;">
							<tbody>
							<tr>
								<td class="subtitulo" colspan="2">Por Cliente</td>
							</tr>
							<tr>
								<td width="50%" class="campoesquerda">Código</td>
								<td width="50%" class="campoesquerda"><input name="codigo" size="6" maxlength="6" onKeyPress="soNumero();" />
								</td>
							</tr>
							</tbody>
						</table></td>
				</tr>
				<tr>
					<td height="50" class="subtitulodireita">
						Por Cliente
						<input type="radio" name="relatorio" value="1" onFocus="ocultar('periodotb');mostrar('faturamentotb');return true;"  onclick="foco_2()"/>
					</td>
				</tr>
				<tr>
					<td class="subtitulodireita">&nbsp;</td>
					<td class="subtitulopequeno"><span class="campoesquerda"><?php echo $nome_franquia; ?></span></td>
				</tr>
				<tr>
					<td colspan="2" class="titulo">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="button" name="enviar" id="enviar" value="      Enviar" onclick="valida()" />
						&nbsp;&nbsp;
						<input name="button" type="button" onClick="javascript: history.back();" value="Voltar       " />
					</td>
				</tr>
			</table>
		</form>
		<br>
		<br>
		<?php if( $id_franquia == '163' ){ ?> 
		<form method="post" action="painel.php?pagina1=Franquias/b_relatendimento_externo2.php" name='listacompleta' id='listacompleta'>
			<table width=70% border="0" align="center">
				<tr class="titulo">
					<td colspan="2">Relat&oacute;rio de Consultoria Externo/Ativo</td>
				</tr>
				<td class="subtitulodireita">&nbsp;</td>
				<td class="campoesquerda">&nbsp;</td>
				</tr>

				<tr>
					<td class="subtitulodireita">Per&iacute;odo</td>
					<td class="subtitulopequeno">
						<input type="text" name="data1" id="data1" onChange="" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10"> a
						<input type="text" name="data2" id="data2" onChange="" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10">

					</td>
				</tr>

				<tr>
					<td class="subtitulodireita">Franquia:</td>
					<td class="subtitulopequeno">
						<div id="cidadeAjax">
							<?php
							if (($tipo == "a") || ($tipo == "c")) $todas = '';
							else $todas = "AND id = $id_franquia";
							echo "<select name='id_franquia' id='id_franquia' onchange='CarregaCidade_Franquia(this.value)'>";
							echo $sql = "select * from franquia where sitfrq='0' $todas order by id";
							$resposta = mysql_query($sql, $con);
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
						</div>
					</td>
				</tr>
				<?php } ?>

				<tr>
					<td colspan="2" class="titulo" align="center">
						<input type="submit" name="enviar" value="    Processar    " />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td></td>
				</tr>
			</table>
		</form>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?
require "connect/sessao.php";
$usuario = $_SESSION['usuario'];
$senha   = $_SESSION['senha'];
$data    = date('d/m/Y');
?>
<script language="javascript">
	function dataNas(c){    // Coloca as / na data
		if(c.value.length ==2 || c.value.length ==5){
			c.value += '/';
		}
	}
	function validaDat(campo,dados) {
		if ( dados ){
			var date=dados;
			var ardt=new Array;
			var ExpReg=new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
			ardt=date.split("/");
			erro=false;
			if ( date.search(ExpReg)==-1){
				erro = true;
			}
			else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
				erro = true;
			else if ( ardt[1]==2) {
				if ((ardt[0]>28)&&((ardt[2]%4)!=0))
					erro = true;
				if ((ardt[0]>29)&&((ardt[2]%4)==0))
					erro = true;
			}
			if (erro) {
				alert('Data Invalida: '+ dados);
				campo.focus();
				campo.value = "";
				return false;
			}
		}
		return true;
	}
	function ver(usuario,senha,dados,id_linha){
		if ( dados != '' ){
			var id = id_linha.replace('id_linha_','');
			var req        = new XMLHttpRequest();
			req.open('GET', "pesquisa_cliente.php?usuario="+usuario+"&senha="+senha+"&logon="+dados, false);
			req.send(null);
			if (req.status != 200) return '';
			var resposta    = req.responseText;
			var array       = resposta.split(';');
			var situacao    = array[1];
			var fantasia    = array[0];
			$('#cli_inp_'+id).val( fantasia );
			$( "#add" ).click();
		}
	}
	/* Fun��o jQuery para remover linha */
	$.removeLinha = function (element)
	{
		/* Conta quantidade de linhas na tabela */
		var linha_total = $('tbody#repetir tr').length;
		/* Condi��o que mant�m pelo menos uma linha na tabela */
		if (linha_total > 1)
		{
			/* Remove os elementos da linha onde est� o bot�o clicado */
			$(element).parent().parent().remove();
		}
		/* Avisa usu�rio de que n�o pode remover a �ltima linha */
		else
		{
			alert("Desculpe, mas voc� n�o pode remover esta �ltima linha!");
		}
	};
	/* Quando o documento estiver carregado� */
	$(document).ready(function()
	{
		/* Vari�vel que armazena limite de linhas (zero � ilimitada) */
		var limite_linhas = 50;
		/* Quando o bot�o adicionar for clicado... */
		$('#add').click(function()
		{
			/* Conta quantidade de linhas na tabela */
			var linha_total = $('tbody#repetir tr').length;
			/* Condi��o que verifica se existe limite de linhas e, se existir, testa se usu�rio atingiu limite */
			if (limite_linhas && limite_linhas > linha_total)
			{
				/* Pega uma linha existente */
				var linha = $('tbody#repetir tr').html();
				/* Conta quantidade de linhas na tabela */
				var linha_total = $('tbody#repetir tr').length;
				linha = linha.replace('cli_inp_1','cli_inp_'+(linha_total + 1));
				linha = linha.replace('id_linha_1','id_linha_'+(linha_total + 1));
				/* Pega a ID da linha atual */
				var linha_id = $('tbody#repetir tr').attr('id');
				/* Acrescenta uma nova linha, incluindo a nova ID da linha */
				$('tbody#repetir').append('<tr id="linha_' + (linha_total + 1) + '">' + linha + '</tr>');
			}
			/* Se usu�rio atingiu limite de linhas� */
			else
			{
				alert("Desculpe, mas voc� s� pode adicionar at� " + limite_linhas + " linhas!");
			}
		});
	});
	function Mostra(idDiv,idImg){
		div = document.getElementById(idDiv);
		img = document.getElementById(idImg);
	    if(div.style.display == 'none'){
			div.style.display = 'block';
			div1.style.display = 'none';
			img.src='https://www.webcontrolempresas.com.br/franquias/img/menos1.gif';
			img1.src='https://www.webcontrolempresas.com.br/franquias/img/mais1.gif';
		}else{
			div.style.display = 'none';
			img.src='https://www.webcontrolempresas.com.br/franquias/img/mais1.gif';
	    }
	}
	function Mostra2(idDiv1,idImg1){
		div1 = document.getElementById(idDiv1);
		img1 = document.getElementById(idImg1);
	    if(div1.style.display == 'none'){
			div1.style.display = 'block';
			div.style.display = 'none';
			img1.src='https://www.webcontrolempresas.com.br/franquias/img/menos1.gif';
			img.src='https://www.webcontrolempresas.com.br/franquias/img/mais1.gif';
		}else{
			div1.style.display = 'none';
			img1.src='https://www.webcontrolempresas.com.br/franquias/img/mais1.gif';
	    }
	}
	function grava_registros(){
		frm = document.form_grava;
		frm.action = 'painel.php?pagina1=grava_premiacao.php';
		frm.submit();
	}
</script>
<body>
	<table width="70%" align="center">
		<tr>
			<td colspan="2" align="center" class="titulo">Premia&ccedil;&otilde;es Gerais</td>
		</tr>
		<tr>
			<td colspan="2" class="campoesquerda">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita" style="text-align:left">
			<a style='cursor: pointer;' class='abre_fecha' onClick="Mostra('movimento','img0')"><img id='img0' src='https://www.webcontrolempresas.com.br/franquias/img/mais1.gif'/><font color="#0000FF"> Inclus&atilde;o / Altera&ccedil;&atilde;o / Exclus&atilde;o</font></a></td>
			<td class="subtitulodireita" style="text-align:left">
			<a style='cursor: pointer;' class='abre_fecha' onClick="Mostra2('relatorio','img0')"><img id='img0' src='https://www.webcontrolempresas.com.br/franquias/img/mais1.gif'/><font color="#0000FF"> Relat&oacute;rio</font></a></td>
		</tr>
        <tr>
			<td colspan="2">
		        <div id="movimento" style='display: none;'>
                    <form name="form_grava" method="post" action="#">
					<table width="70%" align="center">
				        <tr>
				        	<td width="15%" class="subtitulodireita" style="text-align:left">Campanha</td>
				            <td class="campoesquerda"><select name="campanha">
			            		<option value="REV_CANC" selected>REVERS&Atilde;O DE CANCELAMENTO</option>
			                    <option value="ANT_CRED">ANTECIPA&Ccedil;&Atilde;O DE CR&Eacute;DITOS</option>
				            	</select>
				            </td>
				        </tr>
						<tr>
							<td width="15%" class="subtitulodireita" style="text-align:left">Funcion&aacute;rio</td>
				            <td colspan="2" class="campoesquerda">
			            	<?
							$sql_cob = "SELECT id as idcob, nome FROM cs2.funcionario WHERE ativo='S' AND funcao = 'AUXILIAR ADMINISTRATIVO'";
							$cobradora = "<select name='cobradora'>";
							$xres = mysql_query($sql_cob,$con);
							if ( mysql_num_rows($xres) > 0 ){
									$cobradora .= "<option value='0'>Selecione</option>";
									while ( $xreg = mysql_fetch_array($xres) ){
										$id_cob = $xreg['idcob'];
										$nome   = $xreg['nome'];
										if ( $id_cob == $id_cobradora )
											$cobradora .= "<option value='$id_cob' selected='selected'>$nome</option>";
										else
											$cobradora .= "<option value='$id_cob'>$nome</option>";
									}
									$cobradora .= "<select>";
							}
							echo $cobradora;
							?>
				            </td>
						</tr>
						<tr>
							<td class="titulo" colspan="3">&nbsp;</td>
						</tr>
				        <tr>
				        	<td class="campoesquerda">Data</td>
				            <td width="20%" class="campoesquerda">
				            	<input name="data" class="boxnormal" maxlength="10" onKeyUp="dataNas(this)") value="<?=$data?>" onFocus="this.className='boxover'" onBlur="validaDat(this,this.value)"/>
				            </td>
				        </tr>
				        <tr>
				        	<td colspan="3">
								<table width="100%" cellpadding="4" cellspacing="0" border="1">
									<thead>
										<tr>
											<td width="15%">C&oacute;digo Cliente</td>
											<td>Nome Fantasia</td>
											<td align="center">Excluir</td>
										</tr>
									</thead>
				                    <tbody id="repetir">
										<tr id="linha_1">
											<td width="15%">
												<input type="text" maxlength="5" name="logon[]" id='logon' onBlur="ver('<?=$usuario?>','<?=$senha?>',this.value,'id_linha_1')" width="20" value="">
											</td>
				                            <td width="65%" class="campoesquerda">
				                            	<input type="text" id="cli_inp_1" value="" readonly style="width:500px" >
                				            <td width="20" align="center">
												<img src="https://www.webcontrolempresas.com.br/franquias/img/minus.png" id="remove" onClick="$.removeLinha(this);">
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td align="center" colspan="3">
												<input type="hidden" value="Adicionar" id="add" />
				                                <input type="button" value="  Gravar Registros  " onClick="grava_registros()"/>
											</td>
										</tr>
									</tfoot>
								</table>
							</td>
						</tr>
					</table>
					</form>
			    </div>
			</td>
		</tr>
		<tr>
            <td colspan="2">
				<div id="relatorio" style='display: none;'>
                <script language="javascript">
					function valida_form_relatorio(){
						frm = document.form_rel;
						if ( frm.campanha.value == '' ){
							alert("A campanha deve ser selecionada!");
							frm.campanha.focus();
							return false;
						}
						if ( frm.data1.value == '' ){
							alert("Desculpe... Me informe a Data Inicial para continuar !");
							frm.data1.focus();
							return false;
						}
						frm.action = 'painel.php?pagina1=relatorio_premiacao.php';
						frm.submit();
					}
				</script>
				<form name="form_rel" method="post" action="#">
				<table width="100%" align="center">
                	<div class="titulo" align="center">
                    	Relat�rio de Campanha de Premia&ccedil;&otilde;es
                    </div>
			        <tr>
			        	<td width="20%" class="subtitulodireita" style="text-align:left">Campanha</td>
			            <td class="campoesquerda">
                        	<select name="campanha">
		            			<option value="REV_CANC" selected>REVERS&Atilde;O DE CANCELAMENTO</option>
			                    <option value="ANT_CRED">ANTECIPA&Ccedil;&Atilde;O DE CR&Eacute;DITOS</option>
			            	</select>
						</td>
					</tr>
						<tr>
							<td width="15%" class="subtitulodireita" style="text-align:left">Funcion&aacute;rio</td>
				            <td colspan="2" class="campoesquerda">
			            	<?
							$sql_cob = "SELECT id as idcob, nome FROM cs2.funcionario WHERE ativo='S' AND funcao = 'AUXILIAR ADMINISTRATIVO'";
							$cobradora = "<select name='cobradora'>";
							$xres = mysql_query($sql_cob,$con);
							if ( mysql_num_rows($xres) > 0 ){
									$cobradora .= "<option value='0'>Todas</option>";
									while ( $xreg = mysql_fetch_array($xres) ){
										$id_cob = $xreg['idcob'];
										$nome   = $xreg['nome'];
										if ( $id_cob == $id_cobradora )
											$cobradora .= "<option value='$id_cob' selected='selected'>$nome</option>";
										else
											$cobradora .= "<option value='$id_cob'>$nome</option>";
									}
									$cobradora .= "<select>";
							}
							echo $cobradora;
							?>
				            </td>
						</tr>
						<tr>
							<td class="subtitulodireita" style="text-align:left">Informe o Per&iacute;odo</td>
                            <td class="campoesquerda">
								<input type="text" id="data1" name="data1"
                                class="boxnormal" maxlength="10" onKeyUp="dataNas(this)") onFocus="this.className='boxover'" onBlur="validaDat(this,this.value)"/>
                                at&eacute; 
								<input type="text" id="data2" name="data2"
                                class="boxnormal" maxlength="10" onKeyUp="dataNas(this)") onFocus="this.className='boxover'" onBlur="validaDat(this,this.value)"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" name="pesquisa" value="Pesquisar" onClick="valida_form_relatorio()"/>
							</td>
						</tr>
				</table>
                </form>
                </div>
            </td>
		</tr>
	</table>
</body>
</html>

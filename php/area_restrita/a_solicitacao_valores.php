<?

if ( $_REQUEST['rel_franquia'] == '' )
	$id_franquia = $_SESSION['id'];
else
	$id_franquia = $_REQUEST['rel_franquia'];

if ( $id_franquia == 163 ) $id_franquia = 1;

?>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>

<script language="javascript">

function dataNas(c){ // Coloca as / na data
	if(c.value.length ==2 || c.value.length ==5){
		c.value += '/';
	}
}

function soNumero() {
    var tecla;
    tecla = event.keyCode;
    if (tecla < 48 || tecla > 57)  event.returnValue = false;
}

function moeda(z){  
	v = z.value;
	v=v.replace(/\D/g,"")  //permite digitar apenas números
	v=v.replace(/[0-9]{12}/,"inválido")   //limita pra máximo 999.999.999,99
	v=v.replace(/(\d{1})(\d{8})$/,"$1.$2")  //coloca ponto antes dos últimos 8 digitos
	v=v.replace(/(\d{1})(\d{5})$/,"$1.$2")  //coloca ponto antes dos últimos 5 digitos
	v=v.replace(/(\d{1})(\d{1,2})$/,"$1,$2")	//coloca virgula antes dos últimos 2 digitos
	z.value = v;
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

function maiusculo(obj)
{
	obj.value = obj.value.toUpperCase();
}

function trim(str){
	return str.replace(/^\s+|\s+$/g,"");
}

/* Função jQuery para remover linha ITEM */
$.removeLinha = function (element)
{
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir tr').length;
	/* Condição que mantém pelo menos uma linha na tabela */
	if (linha_total > 1)
	{
		/* Remove os elementos da linha onde está o botão clicado */
		$(element).parent().parent().remove();
	}
	/* Avisa usuário de que não pode remover a última linha */
	else
	{
		alert("Desculpe, mas você não pode remover esta última linha!");
	}
};
/* Quando o documento estiver carregado… */
$.adicionaLinha = function (element)
{
	/* Variável que armazena limite de linhas (zero é ilimitada) */
	var limite_linhas = 50;
	/* Quando o botão adicionar for clicado... */
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir tr').length;
	/* Condição que verifica se existe limite de linhas e, se existir, testa se usuário atingiu limite */
	if (limite_linhas && limite_linhas > linha_total)
	{
		/* Pega uma linha existente */
		var linha = $('tbody#repetir tr').html();
		/* Conta quantidade de linhas na tabela */
		var linha_total = $('tbody#repetir tr').length;
		/* Pega a ID da linha atual */
		var linha_id = $('tbody#repetir tr').attr('id');
		/* Acrescenta uma nova linha, incluindo a nova ID da linha */
		$('tbody#repetir').append('<tr id="linha_' + (linha_total + 1) + '">' + linha + '</tr>');
	}
	/* Se usuário atingiu limite de linhas… */
	else
	{
		alert("Desculpe, mas você só pode adicionar até " + limite_linhas + " linhas!");
	}
};
/* Função jQuery para remover linha ARQUIVOS */
$.removeLinha2 = function (element)
{
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir2 tr').length;
	/* Condição que mantém pelo menos uma linha na tabela */
	if (linha_total > 1)
	{
		/* Remove os elementos da linha onde está o botão clicado */
		$(element).parent().parent().remove();
	}
	/* Avisa usuário de que não pode remover a última linha */
	else
	{
		alert("Desculpe, mas você não pode remover esta última linha!");
	}
};
/* Quando o documento estiver carregado… */
$.adicionaLinha2 = function (element)
{
	/* Variável que armazena limite de linhas (zero é ilimitada) */
	var limite_linhas = 10;
	/* Quando o botão adicionar for clicado... */
	/* Conta quantidade de linhas na tabela */
	var linha_total = $('tbody#repetir2 tr').length;
	/* Condição que verifica se existe limite de linhas e, se existir, testa se usuário atingiu limite */
	if (limite_linhas && limite_linhas > linha_total)
	{
		/* Pega uma linha existente */
		var linha = $('tbody#repetir2 tr').html();
		/* Conta quantidade de linhas na tabela */
		var linha_total = $('tbody#repetir2 tr').length;
		/* Pega a ID da linha atual */
		var linha_id = $('tbody#repetir2 tr').attr('id');
		/* Acrescenta uma nova linha, incluindo a nova ID da linha */
		$('tbody#repetir2').append('<tr id="linha_' + (linha_total + 1) + '">' + linha + '</tr>');
	}
	/* Se usuário atingiu limite de linhas… */
	else
	{
		alert("Desculpe, mas você só pode adicionar até " + limite_linhas + " ARQUIVOS!");
	}
};

function gravar(){ 	
	frm = document.form;
	frm.action = 'painel.php?pagina1=area_restrita/a_solicitacao_valores2.php';
	frm.submit();
}

function cancelar(idfranquia){
 	d = document.form1;
    d.action = 'painel.php?pagina1=area_restrita/a_solicitacao_valores_rel.php&rel_franquia='+idfranquia;
	d.submit();
} 


</script>

<form method="post" action="#" enctype="multipart/form-data" name="form">
	<table border="0" align="center" width="700" cellpadding="0" cellspacing="0" class="bodyText">
		<tr>
			<td colspan="3" class="titulo"><br>REQUISI&Ccedil;&Atilde;O DE VALORES</td>
		</tr>
		<tr>
			<td width="200" class="subtitulopequeno">&nbsp;</td>
			<td class="subtitulopequeno" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Franquia</td>
			<td class="subtitulopequeno">
			<?php
			if (($tipo == "a") || ($tipo == "c")) {  
				echo "<select name='rel_franquia' class='boxnormal'>";
				if ($tipo <> "b" ) echo "<option value='TODAS' selected>Todas as Franquias</option>";
			
				$sql = "SELECT id, fantasia FROM franquia 
						WHERE sitfrq = 0 AND classificacao <> 'J'
						ORDER BY id";
				$resposta = mysql_query($sql, $con);
				while ($array = mysql_fetch_array($resposta)) {
					$franquia   = $array["id"];
					$nome_franquia = $franquia.' - '.$array["fantasia"];
					if ( $franquia == $id_franquia ) $select = 'selected';
					else $select = '';
					echo "<option value='$franquia' $select>$nome_franquia</option>\n";
				}
				echo "</select>";
			}else {
				echo $nome_franquia;
				echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia />";
			}
			?>
			</td>
            <td class="subtitulopequeno"><a href="painel.php?pagina1=area_restrita/a_solicitacao_valores_rel.php"><font color="#FF0000">[ Pesquisas ]</font></a></td>
		</tr>
		<tr>
			<td colspan="3" height="15" bgcolor="#CCCCCC"></td>
		</tr>
		<tr>
			<td colspan="3">
				<table width="100%" cellpadding="4" cellspacing="0" border="1">
					<thead>
						<tr>
							<td align="center">Data</td>
							<td align="center">Descri&ccedil;&atilde;o</td>
							<td align="center">Valor</td>
							<td align="center">&nbsp;</td>
						</tr>
					</thead>
					<tbody id="repetir">
						<tr class="linha_1">
							<td align="center">
								<input type="text" name="data[]" size="10" maxlength="10" onKeyUp="dataNas(this)" onKeyPress="soNumero();" onBlur="validaDat(this,this.value)"/>
							</td>
							<td align="center">
								<input type="text" name="descricao[]" size="50" onBlur="maiusculo(this); this.className='boxnormal'" />
							</td>
							<td align="center">
                           		<input type="text" name="valor[]" size="12" maxlength="15" alt="decimal" onKeyUp="moeda(this)"/>
							</td>
							<td align="center">
                            	<img src="../img/minus.png" id="remove" onclick="$.removeLinha(this);" border="0" alt="Remover Linha"/></td>
						</tr>
					</tbody>
                    <tfoot>
                    	<tr>
                        	<td colspan="4" align="center">
								<img src="../img/plus.png" id="add" onclick="$.adicionaLinha(this);"/>
							</td>
						</tr>
                    </tfoot>
				</table>
			</td>
		</tr>
	</table>
   	<table border="0" align="center" width="700" cellpadding="0" cellspacing="0" class="bodyText">
		<tr>
			<td colspan="2" class="titulo"><br>Dados Banc&aacute;rios</td>
		</tr>
		<tr>
			<td width="200" class="subtitulopequeno">&nbsp;</td>
			<td class="subtitulopequeno">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">N&deg; do Banco : </td>
			<td class="subtitulopequeno">
				<input type="text" name="banco" maxlength="3" size="5" onKeyPress="soNumero();" />
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Nome do Banco : </td>
			<td class="subtitulopequeno">
				<input type="text" name="nome_banco" onBlur="maiusculo(this); this.className='boxnormal'" />
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">N&deg; da Ag&ecirc;ncia : </td>
			<td class="subtitulopequeno">
				<input type="text" name="agencia" maxlength="6"  size="5" />
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Tipo de Conta :</td>
			<td class="subtitulopequeno">
				<select name="tipo_conta">
                	<option value="CC">Conta Corrente</option>
                	<option value="CP">Conta Poupan&ccedil;a</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">N&deg; da Conta / DV :</td>
			<td class="subtitulopequeno">
				<input type="text" name="numero_conta" onKeyPress="soNumero();"/> / 
                <input type="text" name="dv_conta"  maxlength="2" size="5" onKeyPress="soNumero();"/>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">CPF/CNPJ : </td>
			<td class="subtitulopequeno">
				<input type="text" name="doc_conta" onKeyPress="soNumero();" />
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Nome : </td>
			<td class="subtitulopequeno">
				<input type="text" name="nome_conta"  size="50" onBlur="maiusculo(this); this.className='boxnormal'" />
			</td>
		</tr>
	</table>
   	<table border="0" align="center" width="700" cellpadding="0" cellspacing="1" class="bodyText">
    	<thead>
			<tr>
				<td colspan="2" class="titulo"><br>Documentos Escaneados</td>
			</tr>
			<tr>
				<td width="200" class="subtitulopequeno">&nbsp;</td>
				<td class="subtitulopequeno">&nbsp;</td>
			</tr>
		</thead>
        <tbody id="repetir2">
			<tr>
				<td class="subtitulopequeno" width="80%">
            		<input type="file" name="documento[]" size="30" style="cursor:pointer"/>
				</td>
                <td class="subtitulopequeno" style="text-align:center">
                            	<img src="../img/minus.png" id="remove" onclick="$.removeLinha2(this);" border="0" title="Remover Arquivo"/>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr class="subtitulopequeno" style="text-align:center">
				<td colspan="2">
					<img src="../img/plus.png" id="add" onclick="$.adicionaLinha2(this);" title="Adicionar Arquivo"/>
				</td>
			</tr>
		</tfoot>
	</table>
   	<table border="0" align="center" width="700" cellpadding="0" cellspacing="0" class="bodyText">
		<tr>
			<td class="subtitulopequeno">&nbsp;</td>
			<td class="subtitulopequeno">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulopequeno" colspan="2" style="text-align:center">
				<input type="submit" name="upload" value="    Solicitar    " onclick="gravar()" />&nbsp;&nbsp;
                <input type="submit" name="upload" value="    Voltar    " onclick="cancelar(<?=$id_franquia?>)" />
			</td>
		</tr>
	</table>
</form>
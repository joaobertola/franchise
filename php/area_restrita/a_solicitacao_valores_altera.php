<?php

$id_usuario_logado = $_SESSION['id'];
$id_pedido   	   = $_REQUEST['id'];
$id_franquia 	   = $_REQUEST['id_franquia'];

$sql = "SELECT
			a.banco, a.agencia, a.tpconta, a.conta, a.dv, a.doc, a.nome, b.fantasia, a.nomebanco, a.data_deposito
		FROM cs2.solicitacao_valores a
		INNER JOIN cs2.franquia b ON a.id_franquia = b.id
		WHERE a.id = '$id_pedido' ";
$qry = mysql_query($sql,$con) or die("Erro SQL: $sql"); 

$nome_franquia = mysql_result($qry,0,'fantasia');
$banco         = mysql_result($qry,0,'banco');
$nomebanco     = mysql_result($qry,0,'nomebanco');

$agencia       = mysql_result($qry,0,'agencia');
$tpconta       = mysql_result($qry,0,'tpconta');
$conta         = mysql_result($qry,0,'conta');
$dv            = mysql_result($qry,0,'dv');
$doc           = mysql_result($qry,0,'doc');
$nome          = mysql_result($qry,0,'nome');
$data_deposito = mysql_result($qry,0,'data_deposito');

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

function remove_arquivo_sol(arq,idfranquia){

	var result = confirm("Confirma Exclusao deste registro ?");
	if (result==true) {
	 	d = document.form1;
		
    	d.action = 'painel.php?pagina1=area_restrita/a_solicitacao_valores_excluir_arquivos.php&id='+arq+'&id_franquia='+idfranquia;
		d.submit();
	}
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
$.adicionaLinha = function (element,dt1,desc1,vlr1)
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
		
		/* Limpando os valores da matriz */
		linha = linha.replace(dt1,'""');
		linha = linha.replace(desc1,'""');
		linha = linha.replace(vlr1,'""');

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
	frm = document.form1;
	frm.action = 'painel.php?pagina1=area_restrita/a_solicitacao_valores_update.php';
	frm.submit();
}

function voltar(){
 	d = document.form1;
    d.action = 'painel.php?pagina1=area_restrita/a_solicitacao_valores_rel.php&rel_franquia=<?=$id_franquia?>';
	d.submit();
}

</script>
<form method="post" action="#" enctype="multipart/form-data" name="form1">
	<table border="0" align="center" width="600" cellpadding="0" cellspacing="0" class="bodyText">
		<tr>
			<td colspan="3" class="titulo"><p>REQUISI&Ccedil;&Atilde;O DE VALORES</p></td>
		</tr>

		<tr>
			<td colspan='3' class='titulo' style="font-size:14px;text-align:'center'"><p><b>N&deg; da Requisi&ccedil;&atilde;o: <?=$id_pedido?></b></p></td>
		</tr>
    
		<tr>
			<td class="subtitulodireita">Franquia</td>
			<td class="subtitulopequeno">
			<?php
				$sql = "SELECT id, fantasia FROM franquia 
						WHERE id = $id_franquia";
				$resposta = mysql_query($sql, $con);
				while ($array = mysql_fetch_array($resposta)) {
					$franquia   = $array["id"];
					$nome_franquia = $franquia.' - '.$array["fantasia"];
				}
				echo $nome_franquia;
			?>
			</td>
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
	                    <?php
						// Pesquisando so itens cadastrados
						// Buscando ITENS
						$sql = "SELECT 
								date_format(data,'%d/%m/%Y') as data ,descricao,valor 
							FROM cs2.solicitacao_valores_item
							WHERE id_sol = $id_pedido";
						$qry = mysql_query($sql, $con) or die("Erro SQL: $sql");
						$total = 0;
						$qtd = 0;
						while ( $reg = mysql_fetch_array($qry) ){
							$qtd++;
							$data = $reg['data'];
							$descricao = $reg['descricao'];
							$valor = number_format($reg['valor'],2,',','.');
							
							if ( $qtd == 1 ){
								$dat1 = $data;
								$desc1 = $descricao;
								$vlr1 = $valor;
							}
							
							$total += $reg['valor'];
							echo "
							<tr class='linha_$qtd'>
								<td align='center'>
									<input type='text' name='data[]' size='10' maxlength='10' onKeyUp='dataNas(this)' onKeyPress='soNumero();' onBlur='validaDat(this,this.value)' value='$data'/>
								</td>
								<td align='left'>
									<input type='text' name='descricao[]' size='70' onBlur='maiusculo(this); this.className='boxnormal' value='$descricao' style='font-size:10px' />
								</td>
								<td align='center'>
                          	 		<input type='text' name='valor[]' size='12' maxlength='15' alt='decimal' onKeyUp='moeda(this)' value='$valor'/>
								</td>
								<td align='center'>
                           	 		<img src='../img/minus.png' id='remove' onclick='$.removeLinha(this);' border='0' alt='Remover Linha'/></td>
							</tr>";
        	        	}
						$total = number_format($total,2,',','.');
						?>

					</tbody>
                    <tfoot>
                    	<tr>
                        	<td colspan="4" align="center">
								<img src="../img/plus.png" id="add" onclick="$.adicionaLinha(this,'<?=$dat1?>','<?=$desc1?>','<?=$vlr1?>');"/>
							</td>
						</tr>
                    </tfoot>
				</table>
			</td>
		</tr>
	</table>
   	<table border="0" align="center" width="600" cellpadding="0" cellspacing="0" class="bodyText">
		<tr>
			<td colspan="4" class="titulo"><p>Dados Banc&aacute;rios</p></td>
		</tr>
		<tr>
			<td width="200" class="subtitulopequeno">&nbsp;</td>
			<td class="subtitulopequeno">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulodireita">N&deg; do Banco : </td>
			<td class="subtitulopequeno">
				<input type="text" name="banco" maxlength="3" size="5" onKeyPress="soNumero();" value="<?=$banco?>" />
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Nome do Banco</td>
			<td class="subtitulopequeno"><input type="text" size="50" name="nome_banco" onBlur="maiusculo(this); this.className='boxnormal'" value="<?=$nomebanco?>" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">N&deg; da Ag&ecirc;ncia : </td>
			<td class="subtitulopequeno">
				<input type="text" name="agencia" maxlength="6" size="5" value="<?=$agencia?>"/>
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
				<input type="text" name="numero_conta" onKeyPress="soNumero();" size="15" value="<?=$conta?>" /> / 
                <input type="text" name="dv_conta"  maxlength="2" size="1" onKeyPress="soNumero();" value="<?=$dv?>" />
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">CPF/CNPJ : </td>
			<td class="subtitulopequeno">
				<input type="text" name="doc_conta" onKeyPress="soNumero();" value="<?=$doc?>" />
			</td>
		</tr>
        <tr>
			<td class="subtitulodireita">Nome : </td>
			<td class="subtitulopequeno">
				<input type="text" name="nome_conta"  size="50" onBlur="maiusculo(this); this.className='boxnormal'" value="<?=$nome?>" />
			</td>
		</tr>
	</table>
   	<table border="0" align="center" width="600" cellpadding="0" cellspacing="1" class="bodyText">
    	<thead>
			<tr>
				<td colspan="2" class="titulo"><p>Documentos Escaneados</p></td>
			</tr>
			<tr>
				<td width="200" class="subtitulopequeno">&nbsp;</td>
				<td class="subtitulopequeno">&nbsp;</td>
			</tr>
		</thead>
        	<?php
			$sql = "SELECT 
						id, nome_arquivo
					FROM cs2.solicitacao_valores_arq
					WHERE id_sol = $id_pedido";
			$qry = mysql_query($sql, $con) or die("Erro SQL: $sql");
			while ( $reg = mysql_fetch_array($qry) ){
				$id_arquivo   = $reg['id'];
				$nome_arquivo = $reg['nome_arquivo'];
				
				echo "<tr class='subtitulopequeno' style='text-align:center'>
						<td><a href='area_restrita/upload/arquivo_solicitacao/$nome_arquivo'>$nome_arquivo</a></td>
						<td class='subtitulopequeno' style='text-align:center'>
							<a href='#' OnClick='remove_arquivo_sol($id_arquivo,$id_franquia)'>
							<img src='/franquias/img/delete.png'>
							</a>								
						</td>
					  </tr>";
			}
			?>
			<tr>
				<td width="200" class="subtitulopequeno">&nbsp;</td>
				<td class="subtitulopequeno">&nbsp;</td>
			</tr>
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
   	<table border="0" align="center" width="600" cellpadding="0" cellspacing="0" class="bodyText">
		<tr>
			<td width="200" class="subtitulopequeno">&nbsp;</td>
			<td class="subtitulopequeno">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitulopequeno" colspan="2" style="text-align:center">
            	<input type="hidden" name="id_pedido" value="<?=$id_pedido?>" />
                <input type="hidden" name="id_franquia" value="<?=$id_franquia?>" />
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<table width='100%'>
					<tr bgcolor='#CCCCCC'>
						<td>OBSERVA&Ccedil;&Atilde;O:<br>
							Ap&oacute;s imprimir esta requisi&ccedil;&atilde;o, dever&aacute; ser anexado as NOTAS, RECIBOS e BOLETOS originais dos respectivos pagamentos e enviar com URG&Ecirc;NCIA ao Departamento Financeiro, SOB PENA DE SUSPENS&Atilde;O DE FUTUROS DEP&Oacute;SITOS
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
	    <tr>
	    	<td colspan='2' class='noprint' align='center'>
				<?php
				if ( $data_deposito == '0000-00-00' Or $id_usuario_logado == 163) { ?>
					<input type="submit" name="upload" value="    Gravar Altera&ccedil;&otilde;es    " onclick="gravar()" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php } ?>
        	    <input type='button' value='     Voltar     ' onclick='voltar()' />
	        </td>
	    </tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr >
			<td colspan="2" align='center'>
			
			________________________________________________</td>
		</tr>
		<tr>
			<td colspan="2" align='center'>Assinatura do Solicitante</td>
		</tr>	
	</table>
</form>
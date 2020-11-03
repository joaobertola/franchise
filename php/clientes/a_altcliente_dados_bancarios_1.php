<?php

require "connect/sessao.php";

$id_funcionario = $_REQUEST['id_funcionario'];

$codloja = $_REQUEST['codloja'];

$comando = "SELECT  a.renegociacao_tabela, a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, 
					a.cidade, a.bairro, a.end, a.cep, a.fone, a.fax, a.email, a.tx_mens, a.id_franquia,
					date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit, a.ramo_atividade, a.obs,
					a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.cpfsocio2, a.emissao_financeiro,
					a.vendedor, MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, MID(logon,LOCATE('S', b.logon) + 1,10) as senha, a.classe, a.banco_cliente,
					a.agencia_cliente, a.conta_cliente, a.cpfcnpj_doc, a.nome_doc, a.tpconta 
			FROM cs2.cadastro a
			INNER JOIN cs2.logon    b on a.codloja = b.codloja
			INNER JOIN cs2.situacao d on a.sitcli = d.codsit
			WHERE a.codloja='$codloja'
			LIMIT 1";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
//tratamento para agencia e conta corrente
$agencia_cliente = $matriz['agencia_cliente'];
$agencia_cliente = strtoupper($agencia_cliente);

///////////////////////////////////////////////////////////////	
$_comando = "SELECT 
					a.renegociacao_tabela, a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, 
					a.bairro, a.end, a.cep, a.fone, a.fax, a.email, a.tx_mens, a.id_franquia, 
					date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit, a.ramo_atividade, a.obs,
					a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, a.cpfsocio2, a.emissao_financeiro, 
					a.vendedor, MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, MID(logon,LOCATE('S', b.logon) + 1,10) as senha, a.classe, a.banco_cliente,
					a.agencia_cliente, a.conta_cliente, a.cpfcnpj_doc, a.nome_doc, a.tpconta 
			FROM cs2.cadastro a
			INNER JOIN cs2.logon    b on a.codloja = b.codloja
			INNER JOIN cs2.situacao d on a.sitcli  = d.codsit
			WHERE a.codloja = '$codloja' 
			LIMIT 1";
$_res = mysql_query ($_comando, $con);

// Seleciona a Franquia Junior

$sql_jr = "SELECT id_franquia_jr FROM cs2.cadastro WHERE codloja = '$codloja'";
$rs_jr = mysql_query ($sql_jr, $con);
$id_franquia_jr = mysql_result($rs_jr,0,'id_franquia_jr');	
				
if (strlen($agencia_cliente) > 4) {
	$agencia_cliente = substr($agencia_cliente,0,4).'-'.substr($agencia_cliente,4,1);
} else {
	$agencia_cliente = substr($agencia_cliente,0,4);
}

$conta_cliente = $matriz['conta_cliente'];
$conta_cliente = substr($conta_cliente,0,-1).'-'.substr($conta_cliente,-1,1);

$renegociacao_tabela =  $matriz['renegociacao_tabela'];
if($renegociacao_tabela != ""){
	$dia = substr($renegociacao_tabela, 8,10);   
	$mes = substr($renegociacao_tabela, 5,2);   
	$ano = substr($renegociacao_tabela, 0,4);   
	$data_view.=$dia;
	$data_view.="/";
	$data_view.=$mes;
	$data_view.="/";
	$data_view.=$ano;
}
?>
<script language="javascript">
//para filtrar banco, agencia e conta
// Formata o campo Agencia 
function formataAgenciaConta(campo){
	campo.value = filtraCampo(campo); vr = campo.value; tam = vr.length;
	if ( tam <= 1 ) campo.value = vr;
	if ( tam > 1 ) campo.value = vr.substr(0, tam-1 ) + '-' + vr.substr(tam-1, tam);
}  
// Formata o campo valor 
function formataValor(campo) {
	campo.value = filtraCampo(campo);
	vr = campo.value; tam = vr.length;
	if ( tam <= 2 ){
		campo.value = vr ;
	}
	if ( (tam > 2) && (tam <= 5) ){
		campo.value = vr.substr( 0, tam - 2 ) + ',' + vr.substr( tam - 2, tam ) ;
	}
	if ( (tam >= 6) && (tam <= 8) ){
		campo.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;
	}
	if ( (tam >= 9) && (tam <= 11) ){
		campo.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;
	} if ( (tam >= 12) && (tam <= 14) ){
		campo.value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;
	} if ( (tam >= 15) && (tam <= 18) ){
		campo.value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;
	}
} 
// Formata o campo valor 
function formataNumerico(campo) {
	campo.value = filtraCampo(campo); vr = campo.value; tam = vr.length;
} 
// limpa todos os caracteres especiais do campo solicitado 
function filtraCampo(campo){
	var s = ""; var cp = "";
	vr = campo.value; tam = vr.length;
	for (i = 0; i < tam ; i++) {
		if (vr.substring(i,i + 1) != "/" && vr.substring(i,i + 1) != "-" && vr.substring(i,i + 1) != "." && vr.substring(i,i + 1) != "," ){
			s = s + vr.substring(i,i + 1);
		}
	} 
	campo.value = s;
	return cp = campo.value
} 
// Seta o ajuda do campo no campo 
function setaTextoAjuda(txt) {
	if(document.getElementById('textoAjuda')) document.getElementById('textoAjuda').innerHTML = txt + ' ' ;
}
function getTeclaPressionada(evt) {
	if(typeof(evt)=='undefined') evt = window.event; return(evt.keyCode ? evt.keyCode : (evt.which ? evt.which : evt.charCode));
} 
// teclas 63230 a 63240 = safari 
function isTeclaEspecial(key) {
	return key<32||(key>=35&&key<=36)||(key>=37&&key<=40)||key==46||(key>=63230&&key<=63240);
}
function isTeclaRelevante(key) {
	return (key == 8)||(key == 46)||(key == 88)||(key>=48&&key<=57)||(key>=96&&key<=105);
}
function isCaracterRelevante(key) {
	return (key == 88)||(key == 120)||(key>=48&&key<=57);
} 
function isCopiaCola(ctrlKey, key) {
	return ctrlKey && (key == 118 || key == 86 || key == 99 || key == 67);
} 
function filtraTeclas(evt) {
	var key = getTeclaPressionada(evt);
	if(isTeclaEspecial(key) || isTeclaRelevante(key) || isCopiaCola(evt.ctrlKey, key)) return true;
	StopEvent(evt);
	return false;
} 
function filtraCaracteres(evt) {
	var key = getTeclaPressionada(evt);
	if(isTeclaEspecial(key) || isCaracterRelevante(key) || isCopiaCola(evt.ctrlKey, key)) return true;
	StopEvent(evt);
	return false;
} 
function StopEvent(evt) {
	if(document.all)evt.returnValue=false;
	else if(evt.preventDefault)evt.preventDefault();
} 
function formataMascara(format, field) {
	var result = "";
	var maskIdx = format.length - 1;
	var error = false;
	var valor = field.value;
	var posFinal = false;
	if( field.setSelectionRange ) {
		if(field.selectionStart == valor.length) posFinal = true;
	} 
	valor = valor.replace(/[^0123456789Xx]/g,'');
	for (var valIdx = valor.length - 1; valIdx >= 0 && maskIdx >= 0; --maskIdx) {
		var chr = valor.charAt(valIdx);
		var chrMask = format.charAt(maskIdx);
		switch (chrMask) {
			case '#':
			if(!(/\d/.test(chr))) error = true; result = chr + result;
			--valIdx;
			break;
			case '@':
			result = chr + result;
			--valIdx; break; default: result = chrMask + result;
		}
	}
	field.value = result;
	field.style.color = error ? 'red' : '';
	if(posFinal) {
		field.selectionStart = result.length; field.selectionEnd = result.length;
	}
	return result;
} 

function MM_formtCep(e,src,mask) {
	if(window.event) { _TXT = e.keyCode; }
	else if(e.which) { _TXT = e.which; }
	if(_TXT > 47 && _TXT < 58) {
		var i = src.value.length;
		var saida = mask.substring(0,1);
		var texto = mask.substring(i)
		if (texto.substring(0,1) != saida) { src.value += texto.substring(0,1); }
    	return true; 
	} else { 
		if (_TXT != 8) { return false; }
		else { return true; }
	}
}

function confirma(){
	frm = document.form;
	frm.action = 'clientes/update_altcliente_dados_bancarios.php';
	frm.submit();
}
 
function refresch(){
	frm = document.form;
	frm.action = 'painel.php?pagina1=clientes/a_altcliente_dados_bancarios.php';
	frm.submit();
} 

function afixar(form,idDiv) {
	div = document.getElementById(idDiv);
	if(div.style.display == 'none') div.style.display = 'block';
	else div.style.display = 'none';
}

function valida_user(){
 	frm = document.form;
	var usuario = frm.senha_user.value;
	if ( usuario == '' ){
		alert('Favor informar a senha para autorizacao');
		exit;
	}
	var req        = new XMLHttpRequest();
	req.open('GET', "connect/valida_user.php?usuario="+usuario, false);
	req.send(null);
	if (req.status != 200) return '';
	var resposta    = req.responseText;

	
	var array = resposta.split(';');
	var id   = array[0];
	var nome = array[1];
	if ( nome  == '' ){
		alert('Senha invalida');
		frm.senha_user.value = '';
		frm.senha_user.focus();
	}
	$('#nome_usuario').text(nome);
	refresch2(<?=$codloja?>,id);
}

function refresch2(codloja,id_funcionario){
	frm = document.form;
	frm.action = 'painel.php?pagina1=clientes/a_altcliente_dados_bancarios_1.php&codloja='+codloja+'&id_funcionario='+id_funcionario;
	frm.submit();
}

</script>
<style type"text/css">
h1 {font-size: 140%;}
form {margin: 30px 50px 0;}
form input, select {
	font-family: Arial;
	font-size: 8pt;
}
form input#numero, form input#uf, form input#cep {float: left; width: 75px;}
address {clear: both; padding: 30px 0;}

</style>
<form method="post" action="#" name="form">
<input type="hidden" name="codloja" value="<?=$codloja?>">
<table>
	<tr>
		<td colspan="2" class="titulo" align="center"><br />DADOS DE CLIENTES<br /></td>
	</tr>
	<tr>
		<td width="136" class="subtitulodireita">ID</td>
		<td class="subtitulopequeno">
			<?php echo $matriz['codloja']; ?>
			<input name="codloja" type="hidden" id="codloja" value="<?php echo $matriz['codloja']; ?>" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">C&oacute;digo de Cliente </td>
			<td class="subtitulopequeno">
				<?php
				echo "<input name=\"codigo\" type=\"hidden\" value=\"".$matriz['logon']."\"  />";
				echo $matriz['logon'];
				?>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Raz&atilde;o Social</td>
			<td class="subtitulopequeno">
				<?php
				if ($tipo == "a" or strtoupper($usuario)== 'FRANQUIAS') {
					echo "<input name=\"razao\" type=\"text\" id=\"razao\" value=\"".$matriz['razaosoc']."\" size=\"65\" maxlength=\"200\" onFocus=\"this.className='boxover'\" onBlur=\"maiusculo(this); this.className='boxnormal'\" />";
				} else {
					echo "<input name=\"razao\" type=\"hidden\" value=\"".$matriz['razaosoc']."\"  />";
					echo $matriz['razaosoc'];
				}
				?>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Nome Fantasia</td>
			<td class="subtitulopequeno">
				<?php
				if ($tipo == "a" or strtoupper($usuario)== 'FRANQUIAS') {
					echo "<input name=\"nomef\" type=\"text\" id=\"razao\" value=\"".$matriz['nomefantasia']."\" size=\"65\" maxlength=\"200\" onFocus=\"this.className='boxover'\" onBlur=\"maiusculo(this); this.className='boxnormal'\" />";
				} else {
					echo "<input name=\"nomef\" type=\"hidden\" value=\"".$matriz['nomefantasia']."\"  />";
					echo $matriz['nomefantasia'];
				}
				?>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">CNPJ</td>
			<td class="subtitulopequeno">
				<?php
				if ($tipo == "a") {
					echo "<input name=\"cnpj\" type=\"text\" id=\"razao\" onKeyPress=\"soNumero(); formatar('##.###.###/####-##', this)\" value=\"".$matriz['insc']."\" size=\"22\" maxlength=\"18\" onFocus=\"this.className='boxover'\" onBlur=\"this.className='boxnormal'\" />";
				} else {
					echo "<input name=\"cnpj\" type=\"hidden\" value=\"".$matriz['insc']."\"  />";
					echo $matriz['insc'];
				}
				?>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Endere&ccedil;o</td>
			<td class="subtitulopequeno"><input name="endereco" type="text" id="endereco" value="<?php echo $matriz['end']; ?>" size="65" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">Bairro</td>
			<td class="subtitulopequeno"><input name="bairro" type="text" id="bairro" value="<?php echo $matriz['bairro']; ?>" size="40" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">UF</td>
			<td class="subtitulopequeno">
				<select name="uf">
					<option value="PR" <?php if ($matriz['uf'] == "PR") { echo "selected"; } ?> >PR</option>
					<option value="SC" <?php if ($matriz['uf'] == "SC") { echo "selected"; } ?> >SC</option>
					<option value="SP" <?php if ($matriz['uf'] == "SP") { echo "selected"; } ?> >SP</option>
					<option value="RS" <?php if ($matriz['uf'] == "RS") { echo "selected"; } ?> >RS</option>
					<option value="GO" <?php if ($matriz['uf'] == "GO") { echo "selected"; } ?> >GO</option>
					<option value="MG" <?php if ($matriz['uf'] == "MG") { echo "selected"; } ?> >MG</option>
					<option value="RJ" <?php if ($matriz['uf'] == "RJ") { echo "selected"; } ?> >RJ</option>
					<option value="BA" <?php if ($matriz['uf'] == "BA") { echo "selected"; } ?> >BA</option>
					<option value="MT" <?php if ($matriz['uf'] == "MT") { echo "selected"; } ?> >MT</option>
					<option value="CE" <?php if ($matriz['uf'] == "CE") { echo "selected"; } ?> >CE</option>
					<option value="AC" <?php if ($matriz['uf'] == "AC") { echo "selected"; } ?> >AC</option>
					<option value="AL" <?php if ($matriz['uf'] == "AL") { echo "selected"; } ?> >AL</option>
					<option value="AM" <?php if ($matriz['uf'] == "AM") { echo "selected"; } ?> >AM</option>
					<option value="AP" <?php if ($matriz['uf'] == "AP") { echo "selected"; } ?> >AP</option>
					<option value="DF" <?php if ($matriz['uf'] == "DF") { echo "selected"; } ?> >DF</option>
					<option value="ES" <?php if ($matriz['uf'] == "ES") { echo "selected"; } ?> >ES</option>
					<option value="MA" <?php if ($matriz['uf'] == "MA") { echo "selected"; } ?> >MA</option>
					<option value="MS" <?php if ($matriz['uf'] == "MS") { echo "selected"; } ?> >MS</option>
					<option value="PA" <?php if ($matriz['uf'] == "PA") { echo "selected"; } ?> >PA</option>
					<option value="PB" <?php if ($matriz['uf'] == "PB") { echo "selected"; } ?> >PB</option>
					<option value="PE" <?php if ($matriz['uf'] == "PE") { echo "selected"; } ?> >PE</option>
					<option value="PI" <?php if ($matriz['uf'] == "PI") { echo "selected"; } ?> >PI</option>
					<option value="RN" <?php if ($matriz['uf'] == "RN") { echo "selected"; } ?> >RN</option>
					<option value="RO" <?php if ($matriz['uf'] == "RO") { echo "selected"; } ?> >RO</option>
					<option value="RR" <?php if ($matriz['uf'] == "RR") { echo "selected"; } ?> >RR</option>
					<option value="SE" <?php if ($matriz['uf'] == "SE") { echo "selected"; } ?> >SE</option>
					<option value="TO" <?php if ($matriz['uf'] == "TO") { echo "selected"; } ?> >TO</option>
			    </select>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Cidade</td>
			<td class="subtitulopequeno"><input name="cidade" type="text" id="cidade" value="<?php echo $matriz['cidade']; ?>" size="30" maxlength="30" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">CEP</td>
			<td class="subtitulopequeno"><input name="cep" type="text" id="cep" onKeyPress="soNumero(); formatar('##.###-###', this)" value="<?php echo $matriz['cep']; ?>" size="12" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">Telefone</td>
			<td class="subtitulopequeno"><input name="telefone" type="text" id="telefone" onKeyPress="soNumero(); formatar('##-####-####', this)" value="<?php echo $matriz['fone']; ?>" size="25" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">Fax</td>
			<td class="subtitulopequeno"><input name="fax" type="text" id="fax" onKeyPress="soNumero(); formatar('##-####-####', this)" value="<?php echo $matriz['fax']; ?>" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">Celular</td>
			<td class="subtitulopequeno"><input name="celular" type="text" id="celular" onKeyPress="soNumero(); formatar('##-####-####', this)" value="<?php echo $matriz['celular']; ?>" size="25" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">Telefone Residencial</td>
			<td class="subtitulopequeno"><input name="fone_res" type="text" id="fone_res" onKeyPress="soNumero(); formatar('##-####-####', this)" value="<?php echo $matriz['fone_res']; ?>" size="25" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">E-mail</td>
			<td class="subtitulopequeno"><input name="email" class="h2" type="text" id="email" value="<?php echo $matriz['email']; ?>" size="25" maxlength="200" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
		</tr>
		<tr>
			<td class="subtitulodireita">Propriet&aacute;rio 1 </td>
			<td class="subtitulopequeno">
				<table border="0" class="subtitulopequeno">
					<tr>
						<td class="campoesquerda">Nome</td>
						<td><input name="nome_prop1" type="text" id="nome_prop1" value="<?php echo $matriz['socio1']; ?>" size="60" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
					</tr>
					<tr>
						<td class="campoesquerda">CPF 1</td>
						<td><input name="cpf1" type="text" id="cpf1" OnKeyPress="soNumero(); formatar('###.###.###-##', this)" value="<?php echo $matriz['cpfsocio1']; ?>" size="17" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Propriet&aacute;rio 2</td>
			<td class="subtitulopequeno">
				<table border="0" class="subtitulopequeno">
					<tr>
						<td class="campoesquerda">Nome</td>
						<td><input name="nome_prop2" type="text" id="nome_prop2" value="<?php echo $matriz['socio2']; ?>" size="60" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
					</tr>
					<tr>
						<td class="campoesquerda">CPF 2</td>
						<td><input name="cpf2" type="text" id="cpf2" OnKeyPress="soNumero(); formatar('###.###.###-##', this)" value="<?php echo $matriz['cpfsocio2']; ?>" size="17" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Segmento Empresarial</td>
			<td class="subtitulopequeno"><input name="ramo" type="text" id="ramo" size="25" maxlength="25" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" value="<?php echo $matriz['ramo_atividade']; ?>" />	</td>
		</tr>
        <!--
		<tr>
			<td class="subtitulodireita">Tipo de Contrato</td>
			<td class="subtitulopequeno">
				<table class="subtitulopequeno" border="0">
					<tr>
						<td><input type="radio" name="classe" value="0" <?php if ($matriz['classe'] == "0"){ echo "checked"; }?> />
Pesquisas / Bloqueios</td>
					</tr>
					<tr>
						<td><input type="radio" name="classe" value="1" <?php if ($matriz['classe'] == "1"){ echo "checked"; }?> />Somente Bloqueios</td>
					</tr>
				</table>
            </td>
		</tr>
        -->
		<tr>
			<td class="subtitulodireita">Renegociação de Tabela</td>
			<td class="subtitulopequeno">
				<input type="text" name="renegociacao_tabela" id="renegociacao_tabela" value="<?=$data_view?>" onChange="" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10" />    
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Vendedor</td>
			<td class="subtitulopequeno" height="22">
				<?php if($_SESSION['ss_tipo'] == "a"){?>
					<input name="vendedor" type="text" id="vendedor" value="<?php echo $matriz['vendedor']; ?>" size="25" maxlength="20" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
				<?php }else {?>
				<?php echo $matriz['vendedor']; ?>
					<input name="hidden" type="hidden" id="vendedor" value="<?php echo $matriz['vendedor']; ?>" size="25" maxlength="20" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
				<?php } ?>
			</td>
		</tr>
		<?php
		if( ($_SESSION['ss_tipo'] == "b") || ($_SESSION['ss_tipo'] == "d") )
			$desabilita = ' disabled="disabled" ';
		else {
			if ( trim($id_funcionario) != '' ){
				$desabilita = '';
				echo "<input type='hidden' name='id_funcionario' value='$id_funcionario' />";
			}else
				$desabilita = ' disabled="disabled" ';
		}
		?>
		<tr>
			<td class="subtitulodireita">Dados da Conta Corrente Receba F&aacute;cil</td>
			<td class="subtitulopequeno">
				<table border="0" class="subtitulopequeno">
					<tr>
						<td colspan="2">
							<label>
								<?php
								if ( trim($id_funcionario) != '' ){
									$sqlx = "SELECT nome FROM cs2.funcionario WHERE id = $id_funcionario";
									$qryx = mysql_query($sqlx,$con);
									$x = mysql_result($qryx,0,'nome');
									echo "<div><font color='red'><b>Autorizado(a) a Trocar a Conta do Cliente: $x</b></font></div>";
								}else{
									?>
									<input type="checkbox" name="pendencia_contratual" value="0" onClick="afixar(form,'senhauser')"><b>Liberar Alteração</b>
									<div id="senhauser" style='display: none;' style="border-color:#F00">
									Favor Informar a Senha :
									<input type="password" name="senha_user" />
									<input type="button" value="[ OK ]" onclick="valida_user()" />
									</div>
								<?php } ?>
							</label>
						</td>
					</tr>
					<tr>
						<td class="campoesquerda">Banco</td>
						<td>
							<select name="banco_cliente" id="banco_cliente" <?=$desabilita?> >
								<option value="0">:: Escolha o Banco ::</option>
								<?php
								$sql = "select * from consulta.banco order by nbanco";
								$resposta = mysql_query($sql, $con);
								while ($array = mysql_fetch_array($resposta)) {
									$banco  = $array["banco"];
									$nbanco = $array["nbanco"];
									echo "<option value=\"$banco\"";
									if ($banco==$matriz['banco_cliente']) echo " selected ";
										echo ">$banco - $nbanco</option>\n";
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="campoesquerda">Ag&ecirc;ncia (ex.: 1234-5)</td>
						<td>
							<input type="text" <?=$desabilita?>
							name="agencia_cliente"
							value="<?php echo $agencia_cliente; ?>"
							title="Informe o prefixo da agência com dígito verificador."
							tabindex="2" 
							size="14" 
							maxlength="6" 
							onKeyDown="return filtraTeclas(event);"
							onKeyPress="return filtraCaracteres(event);"
							onKeyUp="if(isTeclaRelevante(getTeclaPressionada(event))){formatar('####-#', this);}"
							onChange="formatar('####-#', this);"
							onFocus="setaTextoAjuda('Informe o prefixo da agência com dígito verificador.');this.className='boxover'"
							onBlur="setaTextoAjuda('');this.className='boxnormal'" />
						</td>
					</tr>
					<tr>
						<td class="campoesquerda">Conta (ex.: 123456-7) </td>
						<td>
							<input type="text" name="conta_cliente" <?=$desabilita?>
							title="Informe número da conta com dígito verificador."
							value="<?php echo $conta_cliente; ?>"
							tabindex="3"
							size="14"
							maxlength="13"
							onKeyDown="return filtraTeclas(event);"
							onKeyPress="return filtraCaracteres(event);"
							onKeyUp="if(isTeclaRelevante(getTeclaPressionada(event))){formataMascara('################-@', this);}"
							onChange="formataMascara('################-@', this);"
							onFocus="setaTextoAjuda('Informe número da conta com dígito verificador.');this.className='boxover'"
							onBlur="setaTextoAjuda('');this.className='boxnormal'" />
						</td>
					</tr>
					<tr>
						<td class="campoesquerda">Tipo de conta</td>
						<td>
							<input <?=$desabilita?> type="radio" name="tpconta" id="tpconta" value="1" <?php if ($matriz['tpconta'] == 1) echo "checked"; ?> />Conta Corrente<br />
<input <?=$desabilita?> type="radio" name="tpconta" id="tpconta" value="2" <?php if ($matriz['tpconta'] == 2) echo "checked"; ?> />Poupan&ccedil;a</td>
					</tr>
					<tr>
						<td class="campoesquerda">Nome do Respons&aacute;vel</td>
						<td><input type="text" <?=$desabilita?> name="nome_doc" id="nome_doc" value="<?php echo $matriz['nome_doc']; ?>" size="70" maxlength="60" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
					</tr>
					<tr>
						<td class="campoesquerda">CPF / CNPJ </td>
						<td><input name="cpfcnpj_doc" <?=$desabilita?> type="text" id="cpfcnpj_doc" value="<?php echo $matriz['cpfcnpj_doc']; ?>" maxlength="20" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" OnKeyPress="soNumero();" /></td>
					</tr>

					<tr>
						<td colspan="2">
							<table width="100%" bordercolor="#FF6600">
								<tr>
									<td align="center" bgcolor="#FF6600" colspan="3"><b>&Uacute;ltimas Altera&ccedil;&otilde;es de conta realizadas</b></td>
								</tr>
                                <tr bgcolor="#CCCCFF">
                                	<td>Data</td>
                                    <td>Dados Bancarios</td>
                                    <td>Funcionario</td>
                                </tr>
                                <?php
								$sqlx = "SELECT 
											b.nome, date_format(a.data,'%d/%m/%Y %h:%m:%s') as data,
											concat('Banco: ',a.banco,' | Agencia: ',a.agencia,' | Conta: ',a.conta) as dados
										 FROM cs2.cadastro_mudanca_cta a 
										 INNER JOIN funcionario b ON a.id_funcionario = b.id
										 WHERE a.codloja = $codloja
										 ORDER BY a.id DESC";
								$resposta = mysql_query($sqlx,$con);
								while ($reg = mysql_fetch_array($resposta)) {
									$nome = $reg['nome'];
									$data = $reg['data'];
									$dados= $reg['dados'];
									echo "<tr>
											<td>$data</td>
											<td>$dados</td>
											<td>$nome</td>
										  </tr>";
								}
								?>
							</table>
                        </td>
					</tr>
				</table>
			</td>
		</tr>
		<?php if($_SESSION['ss_tipo'] == "a"){?>  
			<tr>
				<td class="subtitulodireita">Franqueado</td>
				<td class="subtitulopequeno">   
				<?php
				if($_REQUEST['franqueado'] == ""){
					$franqueado = mysql_result($_res,0,'id_franquia');	
				}else{
					$franqueado = $_REQUEST['franqueado'];
				}
				?>
				<select name="franqueado" style="width:70%">
					<?php
					$sql = "SELECT id, fantasia FROM franquia WHERE sitfrq=0 AND classificacao != 'J' ORDER BY id";
					$resposta = mysql_query($sql, $con);
					while ($array = mysql_fetch_array($resposta)){
						$id_franqueado = $array["id"];
						$nome_franquia = $array["fantasia"];
						if ($id_franqueado == $_REQUEST['franqueado']) {
							echo "<option value=\"$id_franqueado\" selected>$id_franqueado - $nome_franquia</option>\n"; }
						else {
							echo "<option value=\"$id_franqueado\"";
							if ($id_franqueado==$matriz['id_franquia']) echo "selected";
							echo ">$id_franqueado - $nome_franquia</option>\n";
						}
					}
					?>
				</select>&nbsp;<input type="button" value="OK" onclick="refresch()">          
				</td>
			</tr>
			<tr>
				<td class="subtitulodireita">Franqueado Júnior</td>
				<td class="subtitulopequeno">
					<select name="id_franquia_jr" style="width:70%">
						<option value="0">&nbsp;</option>
						<?php
						$sql_jr = "SELECT id, id_franquia_master, razaosoc FROM cs2.franquia 
									WHERE id_franquia_master = '$franqueado' AND classificacao = 'J'";
						$resp_jr = mysql_query($sql_jr, $con);
						if(mysql_num_rows($resp_jr) > 0){		   
							while($row_jr = mysql_fetch_array($resp_jr)){
								$id_franquia_jr_row = $row_jr["id"];
								$id_franquia_master = $row_jr["id_franquia_master"];
								$razaosoc  = $row_jr["razaosoc"];
								if($id_franquia_jr == $id_franquia_jr_row){
									echo "<option value='$id_franquia_jr_row' selected>$id_franquia_jr_row - $razaosoc</option>";
								}else{
									echo "<option value='$id_franquia_jr_row'>$id_franquia_jr_row - $razaosoc</option>";
								}
							}
							echo "<option value='0'>Nenhum</option>";
						}
						?>
					</select>
				</td>
			</tr>
		<?php }else { ?> 
			<tr>
				<td class="subtitulodireita">Franqueado</td>
				<td class="subtitulopequeno">   
				    <?php
					if($_REQUEST['franqueado'] == ""){
					   $franqueado = mysql_result($_res,0,'id_franquia');	
					}else{
						$franqueado = $_REQUEST['franqueado'];
					}
					?>
					<select name="franqueado" style="width:70%" disabled="disabled">
						<?php
						$sql = "SELECT id, fantasia FROM franquia 
								WHERE sitfrq=0 AND classificacao != 'J' ORDER BY id";
						$resposta = mysql_query($sql, $con);
						while ($array = mysql_fetch_array($resposta)){
							$id_franqueado = $array["id"];
							$nome_franquia = $array["fantasia"];
							if ($id_franqueado == $_REQUEST['franqueado']) {
								echo "<option value=\"$id_franqueado\" selected>$id_franqueado - $nome_franquia</option>\n"; 
							}else{
								echo "<option value=\"$id_franqueado\"";
								if ($id_franqueado==$matriz['id_franquia']) echo "selected";
								echo ">$id_franqueado - $nome_franquia</option>\n";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="subtitulodireita">Franqueado Júnior</td>
				<td class="subtitulopequeno">
					<select name="id_franquia_jr" style="width:70%" disabled="disabled">
						<option value="0">&nbsp;</option>
						<?php
						$sql_jr = " SELECT id, id_franquia_master, razaosoc FROM cs2.franquia
									WHERE id_franquia_master = '$franqueado' AND classificacao = 'J'";
						$resp_jr = mysql_query($sql_jr, $con);
						if(mysql_num_rows($resp_jr) > 0){		   
							while($row_jr = mysql_fetch_array($resp_jr)){
								$id_franquia_jr_row = $row_jr["id"];
								$id_franquia_master = $row_jr["id_franquia_master"];
								$razaosoc  = $row_jr["razaosoc"];
								if($id_franquia_jr == $id_franquia_jr_row){
									echo "<option value='$id_franquia_jr_row' selected>$id_franquia_jr_row - $razaosoc</option>";
								}else{
									echo "<option value='$id_franquia_jr_row'>$id_franquia_jr_row - $razaosoc</option>";
								}
							}
							echo "<option value='0'>Nenhum</option>";
						}
						?>
					</select>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td class="subtitulodireita">Emiss&atilde;o de Nota Fiscal e Fatura</td>
			<td valign="top" class="subtitulopequeno">
				<table class="subtitulopequeno" border="0">
					<tr>
						<td>
							<input type="radio" name="fatura" value="1" <?php if ($matriz['emissao_financeiro'] == "1"){ echo "checked"; }?> />Emite fatura e relaciona a NF &uacute;nica</td>
					</tr>
					<tr>
						<td><input type="radio" name="fatura" value="2" <?php if ($matriz['emissao_financeiro'] == "2"){ echo "checked"; }?> />Emite s&oacute; NF individual</td>
					</tr>
					<tr>
						<td><input type="radio" name="fatura" value="3" <?php if ($matriz['emissao_financeiro'] == "3"){ echo "checked"; }?> />Emite fatura e NF individual</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
			<td class="subtitulopequeno"><textarea name="obs" cols="50" rows="3"><?php echo $matriz['obs']; ?></textarea></td>
		</tr>
		<tr>
			<td class="subtitulodireita">Status</td>
			<td class="formulario"
            	<?php if ($matriz['sitcli'] == "0") {
					echo "bgcolor=\"#33CC66\"";}
				else {
					echo "bgcolor=\"#FF0000\"";}
			?>>
            <font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font></td>
		</tr>
		<?php 
		$ssql=" SELECT 
					date_format(a.data_documento,'%d/%m/%Y') as documento, a.tipo_documento, b.motivo,
					date_format(a.ultima_fatura,'%d/%m/%Y') as ultima
				FROM pedidos_cancelamento a
				INNER JOIN motivo_cancel b on a.id_mot_cancelamento=b.id
				WHERE codloja = '$codloja'";
		$rs = mysql_query($ssql);
		$line = @mysql_num_rows ($rs);
		if ($line != 0) {
			while ($fila = mysql_fetch_object($rs)){
				echo "
					<tr>
						<td class=\"subtitulodireita\">Dados do Cancelamento </td>
						<td class=\"subtitulopequeno\">
							<table>
								<tr>
									<td class=\"subtitulodireita\">Data do Documento</td>
<td class=\"campoesquerda\">$fila->documento</td>
								</tr>
								<tr>
									<td class=\"subtitulodireita\">Doc. de Cancelamento</td>
									<td class=\"campoesquerda\">$fila->tipo_documento</td>
								</tr>
								<tr>
									<td class=\"subtitulodireita\">Motivo do Cancelamento</td>
									<td class=\"campoesquerda\">$fila->motivo</td>
								</tr>
								<tr>
									<td class=\"subtitulodireita\">&Uacute;ltima Fatura</td>
									<td class=\"campoesquerda\">$fila->ultima</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class=\"subtitulodireita\">&nbsp;</td>
						<td class=\"subtitulopequeno\"><input type=\"checkbox\" name=\"negativado\" id=\"negativado\" />
						<label for=\"negativado\">Cliente Negativado (n&atilde;o sair na listagem de cobran&ccedil;a)</label></td>
					</tr>";
			}
			mysql_free_result($rs);
		}
		?>
		<tr>
			<td colspan="2" class="titulo">&nbsp;</td>
		</tr>
	</table>
	<table width="234" align="center">
		<tr align="center">
			<td width="109">
				<input name="alterar" type="button" value=" Modificar " onclick="confirma()" />
			</td>
			<td>
				<input type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
		</tr>
	</table>
</form>
<?php
$res = mysql_close ($con);
?>
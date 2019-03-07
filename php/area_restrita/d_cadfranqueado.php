<?php


if(!file_exists('../connect/sessao.php')){
	require_once('connect/sessao.php');
}else{
	require_once('../connect/sessao.php');

}
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

//seleciona os gerntes de franquia
$sql_gerente = "SELECT id, nome FROM gerente WHERE situacao = 'A' ORDER BY nome";
$qry_gerente = mysql_query ($sql_gerente, $con);

//seleciona as operadoras
$sql_operadora = "SELECT * FROM cs2.operadora 
				  WHERE situacao = 'A'
				  ORDER BY descricao";
$qry_operadora_1 = mysql_query ($sql_operadora, $con);
$qry_operadora_2 = mysql_query ($sql_operadora, $con);				  
?>

<script type="text/javascript">
// fun��o q preenche autom�ticamente o CEP
function addEvent(obj, evt, func) {
  if (obj.attachEvent) {
    return obj.attachEvent(("on"+evt), func);
  } else if (obj.addEventListener) {
    obj.addEventListener(evt, func, true);
    return true;
  }
  return false;
}

function XMLHTTPRequest() {
  try {
    return new XMLHttpRequest(); // FF, Safari, Konqueror, Opera, ...
  } catch(ee) {
    try {
      return new ActiveXObject("Msxml2.XMLHTTP"); // activeX (IE5.5+/MSXML2+)
    } catch(e) {
      try {
        return new ActiveXObject("Microsoft.XMLHTTP"); // activeX (IE5+/MSXML1)
      } catch(E) {
        return false; // doesn't support
      }
    }
  }
}

function buscarEndereco() {
var campos = {
  validcep: document.getElementById("validcep"),
  cep: document.getElementById("cep"),
  logradouro: document.getElementById("logradouro"),
  numero: document.getElementById("numero"),
  complemento: document.getElementById("complemento"),// IMPLEMENTADO NA VERS�O 4.0
  bairro: document.getElementById("bairro"),
  localidade: document.getElementById("localidade"),
  uf: document.getElementById("uf")
};

var ajax = XMLHTTPRequest();
ajax.open("GET", ("../client.php?cep="+campos.cep.value.replace(/[^\d]*/, "")), true);

  ajax.onreadystatechange = function() {
  if (ajax.readyState == 1) {
  campos.logradouro.disabled = true;
  campos.logradouro.value = "carregando...";
  campos.bairro.disabled = true;
  campos.localidade.disabled = true;
  campos.bairro.value = "carregando...";
  
  campos.numero.disabled = true;// IMPLEMENTADO NA VERS�O 4.0
  campos.numero.value = "carregando...";

  campos.complemento.disabled = true;// IMPLEMENTADO NA VERS�O 4.0
  campos.complemento.value = "carregando...";

  campos.uf.disabled = true;
  campos.localidade.value = "carregando...";
  } else if (ajax.readyState == 4) {
  if(ajax.responseText == false){
    campos.validcep.innerHTML = "Cep invalido !!!";
    campos.logradouro.disabled = false;
    campos.logradouro.value = "";
    campos.numero.disabled = false;// IMPLEMENTADO NA VERS�O 4.0
    campos.numero.value = "";// IMPLEMENTADO NA VERS�O 4.0
    campos.complemento.disabled = false;// IMPLEMENTADO NA VERS�O 4.0
    campos.complemento.value = "";// IMPLEMENTADO NA VERS�O 4.0
    campos.bairro.disabled = false;
    campos.localidade.disabled = false;
    campos.bairro.value = "";
    campos.uf.disabled = false;
    campos.localidade.value = "";
  }else{
    campos.validcep.innerHTML = "";
    var r = ajax.responseText, i, logradouro, complemento, numero, bairro, localidade, uf;
    logradouro = r.substring(0, (i = r.indexOf(':')));
    campos.logradouro.disabled = false;
    campos.logradouro.value = unescape(logradouro.replace(/\+/g," "));
	<!-- IMPLEMENTADO NA VERS�O 4.0 -->
	r = r.substring(++i);
    complemento = r.substring(0, (i = r.indexOf(':')));
    campos.complemento.disabled = false;
    campos.complemento.value = unescape(complemento.replace(/\+/g," "));

    r = r.substring(++i);
    bairro = r.substring(0, (i = r.indexOf(':')));
    campos.bairro.disabled = false;
    campos.bairro.value = unescape(bairro.replace(/\+/g," "));
    r = r.substring(++i);
    localidade = r.substring(0, (i = r.indexOf(':')));
    campos.localidade.disabled = false;
    campos.localidade.value = unescape(localidade.replace(/\+/g," "));
	
	<!-- IMPLEMENTADO NA VERS�O 4.0 -->
	r = r.substring(++i);
    numero = r.substring(0, (i = r.indexOf(':')));
    campos.numero.disabled = false;
    campos.numero.value = unescape(numero.replace(/\+/g," "));


    r = r.substring(++i);
    uf = r.substring(0, (i = r.indexOf(';')));
    campos.uf.disabled = false;
    i = campos.uf.options.length;
    while (i--) {
      if (campos.uf.options[i].getAttribute("value") == uf) {
      break;
      }
    }
    campos.uf.selectedIndex = i;
  }
  }
};
ajax.send(null);
}


window.addEvent(
  window,
  "load",
  function() {window.addEvent(document.getElementById("cep"), "blur", buscarEndereco);}
);


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

<script language="javascript">
//abre nova janela para saber o CEP no site dos correios
function BuscaCep() {
window.open ('busca_cep.html','buscaCep','scrollbars=no,resizable=no,width=780,height=500');
}
//fun��o para converter tudo em mai�sculo
function maiusculo(obj)
{
obj.value = obj.value.toUpperCase();
}

function maiusculo(obj)
{
obj.value = obj.value.toUpperCase();
}
//fun��o para aceitar somente numeros em determinados campos
function soNumero() {
    var tecla;
    tecla = event.keyCode;
    if (tecla < 48 || tecla > 57)  event.returnValue = false;
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
//fun��o para validar clientes no cadastramento
function envia(){
 	frm = document.cadfranqueado;
    frm.action = 'painel.php?pagina1=area_restrita/d_cadfranqueado_final.php';
	frm.submit();
}

function validaFranqueado(){
// validar raz�o social
d = document.cadfranqueado;

if (d.franquia.value == ""){
	alert("O campo " + d.franquia.name + " deve ser preenchido!");
	d.franquia.focus();
	return false;
}
// validar nome fantasia
if (d.razao.value == ""){
	alert("O campo " + d.razao.name + " deve ser preenchido!");
	d.razao.focus();
	return false;
}
//validar cnpj
else if (d.cnpj.value == ""){
	alert("O campo " + d.cnpj.name + " deve ser preenchido!");
	d.cnpj.focus();
	return false;
}
//validar cep
else if (d.cep.value == ""){
	alert("O campo " + d.cep.name + " deve ser preenchido!");
	d.cep.focus();
	return false;
}
//validar telefone
else if (d.telefone.value == ""){
	alert("O campo " + d.telefone.name + " deve ser preenchido!");
	d.telefone.focus();
	return false;
}
//validar email
else if (d.email.value == ""){
	alert("O campo " + d.email.name + " deve ser preenchido!");
	d.email.focus();
	return false;
}
//validar email(verificao de endereco eletronico)
parte1 = d.email.value.indexOf("@");
parte2 = d.email.value.indexOf(".");
parte3 = d.email.value.length;
if (!(parte1 >= 3 && parte2 >= 6 && parte3 >= 9)) {
	alert("O campo " + d.email.name + " deve ser conter um endereco eletronico!");
	d.email.focus();
	return false;
}
//validar nome do primeiro propriet�rio
else if (d.nome_prop1.value == ""){
	alert("O campo " + d.nome_prop1.name + " deve ser preenchido!");
	d.nome_prop1.focus();
	return false;
}
//validar cpf
else if (d.cpf1.value == ""){
	alert("O campo " + d.cpf1.name + " deve ser preenchido!");
	d.cpf1.focus();
	return false;
}

else if( (d.celular1.value != "") && (d.operadora_1.value == "0") ){
	alert("O campo Operadora do Celular 1 deve ser preenchido!");
	d.operadora_1.focus();
	return false;
}
else if( (d.celular2.value != "") && (d.operadora_2.value == "0") ){
	alert("O campo Operadora do Celular 2 deve ser preenchido!");
	d.operadora_2.focus();
	return false;
}

else if( (d.celular1.value == "") && (d.operadora_1.value > "0") ){
	alert("O campo Celular 1 da Operadora deve ser preenchido!");
	d.operadora_1.focus();
	return false;
}
else if( (d.celular2.value == "") && (d.operadora_2.value > "0") ){
	alert("O campo Celular 2 da Operadora deve ser preenchido!");
	d.operadora_2.focus();
	return false;
}

envia();
}

/* Mascaras ER */
/* Mascaras ER */
function xmascara(o,f){
        v_obj=o
        v_fun=f
        setTimeout("xexecmascara()",1)
}
function xexecmascara(){
        v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
        v=v.replace(/\D/g,"");             //Remove tudo o que nao e digito
        v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parenteses em volta dos dois primeiros digitos
        v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hifen entre o quarto e o quinto digitos
        return v;
}
function id( el ){
        return document.getElementById( el );
}

window.onload = function(){
        id('celular').onkeypress = function(){
                xmascara( this, mtel );
        }
        id('celular1').onkeypress = function(){
                xmascara( this, mtel );
        }        
        id('tel_comercial').onkeypress = function(){
                xmascara( this, mtel );
        }
        id('tel_residencial').onkeypress = function(){
                xmascara( this, mtel );
        }
        id('fone').onkeypress = function(){
                xmascara( this, mtel );
        }
        id('fax').onkeypress = function(){
                xmascara( this, mtel );
        }
}
</script>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>
<script type="text/javascript" >
/*
(function($){
	// call setMask function on the document.ready event
	$(
		function(){
			$('input:text').setMask();
		}
	);
})(jQuery);
*/
</script>
<style type="text/css">
h1 {font-size: 140%;}
form {margin: 30px 50px 0;}
form input {
	font-family: Arial;
	font-size: 8pt;
}
form input#numero, form input#uf, form input#cep {float: left; width: 75px;}
address {clear: both; padding: 30px 0;}

</style>

<body>
<form name="cadfranqueado" method="post" action="#">
<table border="0" align="center" width="640">
	<tr>
		<td colspan="5" class="titulo">
		<br>
		CADASTRO DE FRANQUEADOS<br></td>
	</tr>
	<tr>
		<td width="105" class="subtitulodireita">&nbsp;</td>
		<td class="subtitulopequeno" colspan="4">(*) Preenchimento obrigat&oacute;rio</td>
	</tr>
	<tr>
		<td class="subtitulodireita">C&oacute;digo do Franqueado </td>
		<td width="248" class="subtitulopequeno">
			<table width="100%">
				<tr>
					<td>
						<?php //isto serve para incrementar o �ltimo valor da coluna c�digo
					 	$conecta = mysql_query("SELECT MAX(id) as id FROM franquia",$con);
						$pega = mysql_result($conecta,0,id);
						$adiciona = $pega + 1;
						$id = sprintf("%03d",$adiciona);
						?>
						<input type="text" name="id" size="10" maxlength="3" value="<?php echo $id; ?>" onKeyPress="soNumero();" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
					</td>
				</tr>
			</table>
		</td>
		<td width="40" class="subtitulopequeno">Classifica&ccedil;&atilde;o</td>
		<td colspan="2" class="subtitulopequeno">
        	<select name="classificacao" id="classificacao">
            	<option value="M" >Master</option>
				<option value="X" >Micro Franquia&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Senha de acesso</td>
		<td class="subtitulopequeno" colspan="4">
			<?php 
			require "senha_aleatoria.php"; 
			?>
			<input name="senha" type="hidden" id="senha" value="<?php echo "$senha"; ?>" />
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Senha da &aacute;rea restrita </td>
		<td class="subtitulopequeno" colspan="4"><?php require "senha_restrita.php"; ?>
		<input type="hidden" value="<?php echo $senha_restrita; ?>" name="senha_restrita" /></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Nome da Franquia </td>
		<td class="subtitulopequeno" colspan="4"><input class="h1" name="franquia" type="text" size="75" maxlength="200"  onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /> *</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Razão Social</td>
		<td class="subtitulopequeno" colspan="4"><input class="h1" name="razao" type="text" size="75" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /> *</td>
	</tr>
	<tr>
		<td class="subtitulodireita">CNPJ</td>
		<td class="subtitulopequeno" colspan="4"><input name="cnpj" type="text" size="22" maxlength="18" onKeyPress="soNumero(); formatar('##.###.###/####-##', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
* digite apenas n&uacute;meros </td>
	</tr>
	<tr>
		<td class="subtitulodireita">CEP</td>
		<td colspan="3" class="subtitulopequeno">
			<table>
				<tr>
					<td><input type="text" name="cep" id="cep" onChange="" onKeyPress="return MM_formtCep(event,this,'#####-###');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="9" /> (*) Autoformata&ccedil;&atilde;o</td>
					<td><div id="validcep" style="color: #FF0000;"></div></td>
				</tr>
			</table>
		</td>
		<td width="167" class="subtitulopequeno"><a onClick="BuscaCep();" style="cursor:hand;"><font color="#FF0000">N&atilde;o se lembra do CEP? Clique Aqui</font></a></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Logradouro</td>
		<td colspan="4" class="subtitulopequeno"><input type="text" name="logradouro" id="logradouro" size="75" maxlength="200"/>
(*)</td>
	</tr>
	<tr>
		<td class="subtitulodireita">N&uacute;mero</td>
		<td class="subtitulopequeno" colspan="4">
<input type="text" name="numero" id="numero" /> 
(*) Opcional </td>
	</tr>
	<tr>
		<td class="subtitulodireita">Complemento</td>
		<td colspan="4" class="subtitulopequeno"><input type="text" name="complemento" id="complemento" /> 
(*) Opcional </td>
	</tr>
	<tr>
		<td class="subtitulodireita">Bairro</td>
		<td colspan="4" class="subtitulopequeno"><input type="text" name="bairro" id="bairro" /> 
(*)</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Localidade</td>
		<td colspan="4" class="subtitulopequeno"><input type="text" name="localidade" id="localidade" /> 
(*) </td>
	</tr>
	<tr>
		<td class="subtitulodireita">UF</td>
		<td colspan="4" class="subtitulopequeno">
        	<select name="uf" id="uf">
				<option value="">-- selecione --</option>
				<option value="AC">Acre</option>
				<option value="AL">Alagoas</option>
				<option value="AP">Amap&aacute;</option>
				<option value="AM">Amazonas</option>
				<option value="BA">Bahia</option>
				<option value="CE">Cear&aacute;</option>
				<option value="DF">Distrito Federal</option>
				<option value="ES">Esp&iacute;rito Santo</option>
				<option value="GO">Goi&aacute;s</option>
				<option value="MA">Maranh&atilde;o</option>
				<option value="MT">Mato Grosso</option>
				<option value="MS">Mato Grosso do Sul</option>
				<option value="MG">Minas Gerais</option>
				<option value="PA">Par&aacute;</option>
				<option value="PB">Para&iacute;ba</option>
				<option value="PR">Paran&aacute;</option>
				<option value="PE">Pernambuco</option>
				<option value="PI">Piau&iacute;</option>
				<option value="RJ">Rio de Janeiro</option>
				<option value="RN">Rio Grande do Norte</option>
				<option value="RS">Rio Grande do Sul</option>
				<option value="RO">Rond&ocirc;nia</option>
				<option value="RR">Roraima</option>
				<option value="SC">Santa Catarina</option>
				<option value="SP">S&atilde;o Paulo</option>
				<option value="SE">Sergipe</option>
				<option value="TO">Tocantins</option>
			</select>(*)
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Telefone Comercial </td>
		<td class="subtitulopequeno" colspan="4"><input name="telefone" type="text" id="tel_comercial" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /> * digite apenas n&uacute;meros, ex. 4130261558</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Telefone
Residencial</td>
		<td class="subtitulopequeno" colspan="4"><input name="fone_res" type="text" id="tel_residencial" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
digite apenas n&uacute;meros, ex. 4130261558</td>
	</tr>
	<tr>
		<td class="subtitulodireita">E-mail</td>
		<td class="subtitulopequeno" colspan="4">
			<input name="email" type="text" id="email" size="40" maxlength="200" class="h2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Proprietário 1 </td>
		<td class="subtitulopequeno" colspan="4">
        	<table border="0">
				<tr>
					<td class="subtitulodireita">Nome</td>
					<td class="campoesquerda">
						<input class="h1" name="nome_prop1" type="text" size="60" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
				</tr>
				<tr>
					<td class="subtitulodireita">CPF 1</td>
					<td class="campoesquerda">
			        	<input name="cpf1" type="text" size="17" maxlength="14" onKeyPress="formatar('###.###.###-##', this); soNumero()" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />
digite apenas n&uacute;meros</td>
				</tr>
				<tr>
					<td class="subtitulodireita">Celular</td>
					<td class="campoesquerda">
			        	<input type="text" name="celular1" id="celular" size="17" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />digite apenas n&uacute;meros, ex. 4199999999</td>
				</tr>
				<tr>
					<td class="subtitulodireita">Operadora</td>
					<td class="campoesquerda">
			        	<select name="operadora_1" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" style="width:35%"/>
			            	<option value="0"></option>
							<?php while($rs_oper_1 = mysql_fetch_array($qry_operadora_1)){?>
							<option value="<?=$rs_oper_1['id']?>"><?=$rs_oper_1['descricao']?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Propriet&aacute;rio 2</td>
		<td class="subtitulopequeno" colspan="4">
			<table border="0">
				<tr>
					<td class="subtitulodireita">Nome</td>
					<td class="campoesquerda">
						<input name="nome_prop2" type="text" class="h1" size="60" maxlength="200" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
				</tr>
				<tr>
					<td class="subtitulodireita">CPF 2</td>
					<td class="campoesquerda">
                    	<input name="cpf2" type="text" size="17" maxlength="14" onKeyPress="formatar('###.###.###-##', this); soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
digite apenas n&uacute;meros</td>
				</tr>
				<tr>
					<td class="subtitulodireita">Celular</td>
					<td class="campoesquerda"><input type="text" name="celular2" id="celular1" size="17" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
digite apenas n&uacute;meros, ex. 4199999999</td>
				</tr>
				<tr>
					<td class="subtitulodireita">Operadora</td>
					<td class="campoesquerda">
						<select name="operadora_2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" style="width:35%"/>
							<option value="0"></option>
							<?php while($rs_oper_2 = mysql_fetch_array($qry_operadora_2)){?>
							<option value="<?=$rs_oper_2['id']?>"><?=$rs_oper_2['descricao']?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Gerente de Franqu&iacute;a Respons&aacute;vel </td>
		<td class="subtitulopequeno" colspan="4">
        	<select name="id_gerente" id="gerente" class="boxnormal" style="width:25%">
				<option value="0">Selecione</option>
				<?php while($rs_gerente = mysql_fetch_array($qry_gerente)){?>
				<?php if($rs_gerente['id'] == $id_gerente){?>
				<option value="<?=$rs_gerente['id']?>" selected="selected">
				<?=$rs_gerente['nome']?>
				</option>
				<?php }else{?>
				<option value="<?=$rs_gerente['id']?>">
				<?=$rs_gerente['nome']?>
				</option>
				<?php }?>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Data da Inaugura&ccedil;&atilde;o</td>
		<td class="subtitulopequeno" colspan="4">
			<input type="text" name="data_abertura" id="data_abertura" onKeyPress="formatar('##/##/####', this)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10"></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Data de Apoio Local </td>
		<td class="subtitulopequeno" colspan="4">
			<input type="text" name="data_apoio" id="data_apoio" onKeyPress="formatar('##/##/####', this)" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="10"></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Banco</td>
		<td class="subtitulopequeno" colspan="4">
			<select name="banco">
				<option value="0">:: Escolha o Banco ::</option>
				<?php
					$sql = "select * from consulta.banco order by nbanco";
					$resposta = mysql_query($sql, $con);
					while ($array = mysql_fetch_array($resposta)) {
						$banco  = $array["banco"];
						$nbanco = $array["nbanco"];
						echo "<option value=\"$banco\">$nbanco</option>\n";
					}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Ag&ecirc;ncia+DV</td>
		<td class="subtitulopequeno" colspan="4">
			<input name="agencia" type="text" size="17" maxlength="14" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />    </td>
	</tr>
	<tr>
		<td class="subtitulodireita">Tipo de Conta</td>
		<td class="subtitulopequeno" colspan="4">
			<input type="radio" name="tpconta" value="1" checked>Conta Corrente
			<input type="radio" name="tpconta" value="2">
			Poupan&ccedil;a
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">N&uacute;mero de Conta+DV</td>
		<td class="subtitulopequeno" colspan="4">
			<input name="conta" type="text" size="17" maxlength="11" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />    </td>
	</tr>
	<tr>
		<td class="subtitulodireita">Titular</td>
		<td class="subtitulopequeno" colspan="4">
			<input class="boxnormal" name="titular" type="text" size="75" maxlength="40" onFocus="this.className='boxover'"onBlur="maiusculo(this); this.className='boxnormal'" /></td>
	</tr>
	<tr>
		<td class="subtitulodireita">CPF do Titular</td>
		<td class="subtitulopequeno" colspan="4">
			<input name="cpftitular" type="text" size="17" maxlength="14" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />    </td>
	</tr>
	<tr>
		<td class="subtitulodireita">Taxa de Implantação</td>
		<td class="subtitulopequeno" colspan="4">
			<input name="tx_adesao" type="text" size="17" maxlength="11" onKeydown="FormataValor(this,20,event,2)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Taxa do Pacote</td>
		<td class="subtitulopequeno" colspan="4">
			<input name="tx_pacote" type="text" size="17" maxlength="11" onKeydown="FormataValor(this,20,event,2)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Taxa de Software</td>
		<td class="subtitulopequeno" colspan="4">
			<input name="tx_software" type="text" size="17" maxlength="11" onKeydown="FormataValor(this,20,event,2)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
	</tr>
	<tr>
		<td colspan="5" class="titulo" align="center">Relatório de Jornais / Rádios</td>
	</tr>
	<?php 
	$f = 1;
	for($i=1; $i<=3 ; $i++){  
	?>
	<tr>
		<td class="subtitulodireita">Cidade</td>
		<td class="subtitulopequeno" colspan="4">
			<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">
						<input name="cidade<?=$i?>" type="text" style="width:90%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>               
					<td width="40%" align="right">UF&nbsp;
                    	<select name="uf<?=$i?>" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" style="width:40%" class="boxnormal"/>
							<option value=""></option>
							<option value="AC">AC</option>
							<option value="AL">AL</option>
							<option value="AP">AP</option>
							<option value="AM">AM</option>
							<option value="BA">BA</option>
							<option value="CE">CE</option>
							<option value="DF">DF</option>
							<option value="ES">ES</option>
							<option value="GO">GO</option>
							<option value="MA">MA</option>
							<option value="MT">MT</option>
							<option value="MS">MS</option>
							<option value="MG">MG</option>
							<option value="PA">PA</option>
							<option value="PB">PB</option>
							<option value="PR">PR</option>
							<option value="PE">PE</option>
							<option value="PI">PI</option>
							<option value="RJ">RJ</option>
							<option value="RN">RN</option>
							<option value="RS">RS</option>
							<option value="RO">RO</option>
							<option value="RR">RR</option>
							<option value="SC">SC</option>
							<option value="SP">SP</option>
							<option value="SE">SE</option>
							<option value="TO">TO</option>
						</select>&nbsp;&nbsp;
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr> 
	<tr>
		<td class="subtitulodireita">Fone 1</td>
		<td class="subtitulopequeno" colspan="4">
			<table border="0" align="left" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="30%">
                    	<input name="fone<?=$f++?>" type="text" style="width:80%" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="formatar('##-####-####', this); soNumero()" class="boxnormal"/></td>
					<td width="30%" align="right">Fone 2&nbsp;</td>
<td width="30%">
						<input name="fone<?=$f++?>" type="text" style="width:93%" maxlength="12" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="formatar('##-####-####', this); soNumero()" class="boxnormal"/></td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Contato</td>
		<td class="subtitulopequeno" colspan="4"><input name="contato<?=$i?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
	</tr> 
	<tr>
		<td class="subtitulodireita">Nome do Jornal ou Rádio</td>
		<td class="subtitulopequeno" colspan="4"><input name="jornal_radio<?=$i?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
	</tr> 
	<tr>
		<td class="subtitulodireita">Titular da Conta</td>
		<td class="subtitulopequeno" colspan="4"><input name="titular_conta<?=$i?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
	</tr> 
	<tr>
		<td class="subtitulodireita">CPF/CNPJ do Titular da Conta</td>
		<td class="subtitulopequeno" colspan="4"><input name="cpf_cnpj<?=$i?>" type="text" style="width:88%" maxlength="14" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
	</tr> 
	<tr>
		<td class="subtitulodireita">Banco</td>
		<td class="subtitulopequeno" colspan="4"><input name="banco<?=$i?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Agência</td>
		<td class="subtitulopequeno" colspan="4"><input name="agencia<?=$i?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Conta</td>
		<td class="subtitulopequeno" colspan="4"><input name="conta<?=$i?>" type="text" style="width:88%" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
	</tr>
	<tr>
		<td class="subtitulodireita">E-mail</td>
		<td class="subtitulopequeno" colspan="4"><input name="email<?=$i?>" type="text" style="width:88%" maxlength="50" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" class="boxnormal"/></td>
	</tr>
	<tr>
		<td colspan="5" class="titulo">&nbsp;</td>
	</tr>
	<?php } ?>
</table>
<table align="center">
	<tr align="center">
		<td><input name="Enviar" type="button" value="Enviar            " onClick="validaFranqueado()" style="cursor:pointer"/>
		</td>
		<td><input name="submit2" type="reset" value="             Cancela" style="cursor:pointer"/></td>
	</tr>
</table>
</form>
</body>
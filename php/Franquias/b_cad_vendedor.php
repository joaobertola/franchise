<?php
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;

$go 	= $_POST['go'];
$nome	= $_POST['nome'];
$cpf	= $_POST['insc'];
$rg		= $_POST['rg'];
$cnh	= $_POST['cnh'];
$data_nasc = $_POST['data_nasc'];
$cep	= $_POST['cep'];
$logradouro	= $_POST['logradouro'];
$numero	= $_POST['numero'];
$complemento = $_POST['complemento'];
$endereco_resid	= $logradouro." ".$numero." ".$complemento;
$bairro_resid = $_POST['bairro'];
$cidade_resid = $_POST['localidade'];
$uf_resid =	$_POST['uf'];
$ponto_referencia = $_POST['ponto_referencia'];
$fone_resid = $_POST['fone_resid'];
$celular_resid = $_POST['celular_resid'];
$empresa1 = $_POST['empresa1'];
$fone_empresa1 = $_POST['fone_empresa1'];
$cargo_empresa1 = $_POST['cargo_empresa1'];
$periodo1_empresa1 = $_POST['periodo1_empresa1'];
$periodo2_empresa1 = $_POST['periodo2_empresa1'];
$resp_empresa1 = $_POST['resp_empresa1'];
$empresa2 = $_POST['empresa2'];
$fone_empresa2 = $_POST['fone_empresa2'];
$cargo_empresa2 = $_POST['cargo_empresa2'];
$periodo1_empresa2 = $_POST['periodo1_empresa2'];
$periodo2_empresa2 = $_POST['periodo2_empresa2'];
$resp_empresa2 = $_POST['resp_empresa2'];
$escolaridade = $_POST['escolaridade'];
$curso	= $_POST['curso'];
$nome_pai = $_POST['nome_pai'];
$nome_mae = $_POST['nome_mae'];
$endereco_pais = $_POST['endereco_pais'];
$bairro_pais = $_POST['bairro_pais'];
$cidade_pais = $_POST['cidade_pais'];
$uf_pais = $_POST['uf_pais'];
$fone_pais = $_POST['fone_pais'];
$comercial_pais = $_POST['comercial_pais'];
$celular_pais = $_POST['celular_pais'];
$nome_conjuge = $_POST['nome_conjuge'];
$trabalho_conjuge = $_POST['trabalho_conjuge'];
$comercial_conjuge = $_POST['comercial_conjuge'];
$celular_conjuge = $_POST['celular_conjuge'];
$nome_irmao = $_POST['nome_irmao'];
$endereco_irmao = $_POST['endereco_irmao'];
$bairro_irmao = $_POST['bairro_irmao'];
$cidade_irmao = $_POST['cidade_irmao'];
$uf_irmao = $_POST['uf_irmao'];
$fone_irmao = $_POST['fone_irmao'];
$comercial_irmao = $_POST['comercial_irmao'];
$celular_irmao = $_POST['celular_irmao'];
$nome_amigo = $_POST['nome_amigo'];
$endereco_amigo = $_POST['endereco_amigo'];
$bairro_amigo = $_POST['bairro_amigo'];
$cidade_amigo = $_POST['cidade_amigo'];
$uf_amigo = $_POST['uf_amigo'];
$fone_amigo = $_POST['fone_amigo'];
$comercial_amigo = $_POST['comercial_amigo'];
$celular_amigo = $_POST['celular_amigo'];
$franqueado = $_POST['franqueado'];
$obs	= $_POST['obs'];
$codv = $_GET['codv'];

if (empty($go)) $go = $_GET['go'];
if (empty($go)) {
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
  complemento: document.getElementById("complemento"),
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
  
  campos.numero.disabled = true;
  campos.numero.value = "carregando...";

  campos.complemento.disabled = true;
  campos.complemento.value = "carregando...";

  campos.uf.disabled = true;
  campos.localidade.value = "carregando...";
  } else if (ajax.readyState == 4) {
  if(ajax.responseText == false){
    campos.validcep.innerHTML = "Cep invalido !!!";
    campos.logradouro.disabled = false;
    campos.logradouro.value = "";
    campos.numero.disabled = false;
    campos.numero.value = "";
    campos.complemento.disabled = false;
    campos.complemento.value = "";
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
	return true; 
	} else { 
		if (_TXT != 8) { return false; }
	 	else { return true; }
	}
}
</script>

<script language="javascript">
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function soNumeros(v){
    return v.replace(/\D/g,"")
}
// formato mascara data
function data(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    v=v.replace(/(\d{2})(\d)/,"$1/$2")
    return v
}
// formato mascara cpf
function cpf(v){
    v=v.replace(/\D/g,"")
    v=v.replace(/(\d{3})(\d)/,"$1.$2")
    v=v.replace(/(\d{3})(\d)/,"$1.$2")
	v=v.replace(/(\d{3})(\d)/,"$1-$2")
    return v
}

//fun��o para validar clientes no cadastramento
function validaVendedor(){
	d = document.incvend;
	//validar nome
	if (d.nome.value == ""){
		alert("O campo " + d.nome.name + " deve ser preenchido!");
		d.nome.focus();
		return false;
	}
	// validar cpf
	if (d.insc.value == ""){
		alert("O campo " + d.insc.name + " deve ser preenchido!");
		d.insc.focus();
		return false;
	}
	//validar rg
	else if (d.rg.value == ""){
		alert("O campo " + d.rg.name + " deve ser preenchido!");
		d.rg.focus();
		return false;
	}
	//validar cep
	else if (d.cep.value == ""){
		alert("O campo " + d.cep.name + " deve ser preenchido!");
		d.cep.focus();
		return false;
	}
	//validar data de nascimento
	else if (d.data_nasc.value == "") {
		alert("O campo " + d.data_nasc.name + " deve ser preenchido!");
		d.data_nasc.focus();
		return false;
	}
	//validar telefone
	else if (d.fone_resid.value == ""){
		alert("O campo " + d.fone_resid.name + " deve ser preenchido!");
		d.fone_resid.focus();
		return false;
	}
	//validar celular
	else if (d.celular_resid.value == ""){
		alert("O campo " + d.celular_resid.name + " deve ser preenchido!");
		d.celular_resid.focus();
		return false;
	}
	//validar nome da m�e
	else if (d.nome_mae.value == ""){
		alert("O campo " + d.nome_mae.name + " deve ser preenchido!");
		d.nome_mae.focus();
		return false;
	}
	//validar nome do conjuge
	else if (d.nome_conjuge.value == ""){
		alert("O campo " + d.nome_conjuge.name + " deve ser preenchido!");
		d.nome_conjuge.focus();
		return false;
	}
	//validar nome do irmao
	else if (d.nome_irmao.value == ""){
		alert("O campo " + d.nome_irmao.name + " deve ser preenchido!");
		d.nome_irmao.focus();
		return false;
	}
	//validar nome do amigo
	else if (d.nome_amigo.value == ""){
		alert("O campo " + d.nome_amigo.name + " deve ser selecionado!");
		d.nome_amigo.focus();
		return false;
	}
return true;
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
<form name="incvend" method="post" onSubmit="return validaVendedor();" action="<?php $_SERVER['PHP_SELF']; ?>" >
<table border="0" align="center" width="643">
  <tr>
    <td colspan="3" class="titulo">
	<br>
	CADASTRO DE VENDEDORES</td>
  </tr>
  <tr>
    <td colspan="3" class="subtitulodireita">Dados Pessoais</td>
    </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td colspan="2" class="subtitulopequeno">(*) Preenchimento obrigat&oacute;rio</td>
    </tr>
  
  <tr>
    <td class="subtitulodireita">Nome Completo</td>
    <td colspan="2" class="subtitulopequeno"><input name="nome" type="text" id="nome" size="50" maxlength="40" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />      
      (*)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C.P.F.</td>
    <td colspan="2" class="subtitulopequeno"><input name="insc" type="text" id="insc" size="20" maxlength="14" OnKeyPress="mascara(this,cpf);" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      (*) digite apenas n&uacute;meros </td>
  </tr>
  <tr>
    <td class="subtitulodireita">R.G.</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="rg" id="rg" maxlength="11" size="20" onKeyPress="mascara(this,soNumeros);" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'">
(*)</td>
    </tr>
  <tr>
    <td class="subtitulodireita">C.N.H.</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="cnh" id="cnh" maxlength="12" size="20" onKeyPress="mascara(this,soNumeros)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'"></td>
    </tr>
  <tr>
    <td class="subtitulodireita">Data de Nascimento</td>
    <td colspan="2" class="subtitulopequeno">
    <input type="text" name="data_nasc" maxlength="10" size="12" onKeyPress="mascara(this,data)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
(*) </td>
    </tr>
  <tr>
    <td class="subtitulodireita">CEP</td>
    <td class="subtitulopequeno">
		<table>
		  	<tr>
				<td><input type="text" name="cep" id="cep" onChange="" onKeyPress="return MM_formtCep(event,this,'#####-###');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="9" /> (*) Autoformata&ccedil;&atilde;o</td>
				<td><div id="validcep" style="color: #FF0000;"></div></td>
			</tr>
		</table>	</td>
	<td class="subtitulopequeno"><a onClick="BuscaCep();" style="cursor:hand;"><font color="#FF0000">N&atilde;o se lembra do CEP? Clique Aqui</font></a
	></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="logradouro" id="logradouro" size="60" maxlength="200" /> 
      (*) </td>
  </tr>
  <tr>
    <td class="subtitulodireita">N&uacute;mero</td>
    <td class="subtitulopequeno" colspan="2">
    <input type="text" name="numero" id="numero" /> 
    (*) Opcional </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Complemento</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="complemento" id="complemento" /> 
      (*) Opcional </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="bairro" id="bairro" /> 
      (*)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="localidade" id="localidade" /> 
      (*) </td>
  </tr>
  <tr>
    <td class="subtitulodireita">UF</td>
    <td colspan="2" class="subtitulopequeno"><select name="uf" id="uf">
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
    </select> 
      (*) </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Ponto de Refer&ecirc;ncia</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="ponto_referencia" id="ponto_referencia" maxlength="100" size="50" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'"></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Telefone Residencial</td>
    <td colspan="2" class="subtitulopequeno"><input name="fone_resid" type="text" id="fone_resid" size="25" maxlength="10" onKeyPress="mascara(this,soNumeros); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      (*) digite apenas n&uacute;meros, ex. 4130261558 </td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="celular_resid" id="celular_resid" size="25" maxlength="10" onKeyPress="mascara(this,soNumeros); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      (*) digite apenas n&uacute;meros, ex. 4199999999</td>
  </tr>
  
  <tr>
    <td colspan="3" class="subtitulodireita">Experi&ecirc;ncia Profissional</td>
    </tr>
  <tr>
    <td class="subtitulodireita">&Uacute;ltima empresa</td>
    <td colspan="2" class="subtitulopequeno"><input name="empresa1" type="text" id="empresa1" size="30" maxlength="20" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Fone</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="fone_empresa1" id="fone_empresa1" size="25" maxlength="10" onKeyPress="mascara(this,soNumeros); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Cargo</td>
    <td colspan="2" class="subtitulopequeno"><input name="cargo_empresa1" type="text" id="cargo_empresa1" size="30" maxlength="20" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Periodo</td>
    <td colspan="2" class="subtitulopequeno">de 
      <input type="text" name="periodo1_empresa1" maxlength="10" size="12" onKeyPress="mascara(this,data)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      at&eacute; <input type="text" name="periodo2_empresa1" maxlength="10" size="12" onKeyPress="mascara(this,data)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Respons&aacute;vel</td>
    <td colspan="2" class="subtitulopequeno"><input name="resp_empresa1" type="text" id="resp_empresa1" size="30" maxlength="20" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Pen&uacute;ltima empresa</td>
    <td colspan="2" class="subtitulopequeno"><input name="empresa2" type="text" id="empresa2" size="30" maxlength="20" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="fone_empresa2" id="fone_empresa2" size="25" maxlength="10" onKeyPress="mascara(this,soNumeros); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cargo</td>
    <td colspan="2" class="subtitulopequeno"><input name="cargo_empresa2" type="text" id="cargo_empresa2" size="30" maxlength="20" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Periodo</td>
    <td colspan="2" class="subtitulopequeno">de
      <input type="text" name="periodo1_empresa2" maxlength="10" size="12" onKeyPress="mascara(this,data)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
at&eacute;
<input type="text" name="periodo2_empresa2" maxlength="10" size="12" onKeyPress="mascara(this,data)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Respons&aacute;vel</td>
    <td colspan="2" class="subtitulopequeno"><input name="resp_empresa2" type="text" id="resp_empresa2" size="30" maxlength="20" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td colspan="3" class="subtitulodireita">Grau de Instru&ccedil;&atilde;o</td>
    </tr>
  <tr>
    <td class="subtitulodireita">Escolaridade</td>
    <td colspan="2" class="subtitulopequeno"><table>
      <tr>
        <td><input type="radio" name="escolaridade" id="escolaridade" value="1"></td>
        <td>1&ordm; Grau</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="radio" name="escolaridade" id="escolaridade" value="2"></td>
        <td>2&ordm; Grau</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="radio" name="escolaridade" id="escolaridade" value="3"></td>
        <td>Superior</td>
        <td>Curso</td>
        <td><input name="curso" type="text" id="curso" size="20" maxlength="15" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" class="subtitulodireita">Refer&ecirc;ncias Pessoais</td>
    </tr>
  <tr>
    <td class="subtitulodireita"><strong>Filia&ccedil;&atilde;o - M&atilde;e</strong></td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="nome_mae" id="nome_mae" size="40" maxlength="40" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />
(*)</td>
  </tr>
  <tr>
    <td class="subtitulodireita"><strong>Filia&ccedil;&atilde;o - Pai</strong></td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="nome_pai" id="nome_pai" size="40" maxlength="40" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="endereco_pais" id="endereco_pais" size="40" maxlength="40" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="bairro_pais" id="bairro_pais" size="30" maxlength="30" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="cidade_pais" id="cidade_pais" size="30" maxlength="30" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">UF</td>
    <td colspan="2" class="subtitulopequeno">
    <select name="uf_pais" id="uf_pais">
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
    </select>
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Residencial</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="fone_pais" id="fone_pais" size="25" maxlength="10" onKeyPress="mascara(this,soNumeros); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Comercial</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="comercial_pais" id="comercial_pais" size="25" maxlength="10" onKeyPress="mascara(this,soNumeros); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="celular_pais" id="celular_pais" size="25" maxlength="10" onKeyPress="mascara(this,soNumeros); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita"><strong>Nome do(a) Esposo(a)</strong></td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="nome_conjuge" id="nome_conjuge" size="40" maxlength="40" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />
(*)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Empresa onde trabalha</td>
    <td colspan="2" class="subtitulopequeno"><input name="trabalho_conjuge" type="text" id="trabalho_conjuge" size="30" maxlength="20" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Comercial</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="comercial_conjuge" id="comercial_conjuge" size="25" maxlength="10" onKeyPress="mascara(this,soNumeros); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="celular_conjuge" id="celular_conjuge" size="25" maxlength="10" onKeyPress="mascara(this,soNumeros); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita"><strong>Nome do Irm&atilde;o(&atilde;)</strong></td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="nome_irmao" id="nome_irmao" size="40" maxlength="40" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />
(*)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="endereco_irmao" id="endereco_irmao" size="40" maxlength="40" onfocus="this.className='boxover'" onblur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="bairro_irmao" id="bairro_irmao" size="30" maxlength="30" onfocus="this.className='boxover'" onblur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="cidade_irmao" id="cidade_irmao" size="30" maxlength="30" onfocus="this.className='boxover'" onblur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">UF</td>
    <td colspan="2" class="subtitulopequeno"><select name="uf_irmao" id="uf_irmao">
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
    </select></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Residencial</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="fone_irmao" id="fone_irmao" size="25" maxlength="10" onkeypress="mascara(this,soNumeros); formatar('##-####-####', this)" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Comercial</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="comercial_irmao" id="comercial_irmao" size="25" maxlength="10" onkeypress="mascara(this,soNumeros); formatar('##-####-####', this)" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="celular_irmao" id="celular_irmao" size="25" maxlength="10" onkeypress="mascara(this,soNumeros); formatar('##-####-####', this)" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita"><strong>Amigo(a) ou Conhecido(a)</strong></td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="nome_amigo" id="nome_amigo" size="40" maxlength="40" onfocus="this.className='boxover'" onblur="maiusculo(this); this.className='boxnormal'" />
(*)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="endereco_amigo" id="endereco_amigo" size="40" maxlength="40" onfocus="this.className='boxover'" onblur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="bairro_amigo" id="bairro_amigo" size="30" maxlength="30" onfocus="this.className='boxover'" onblur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="cidade_amigo" id="cidade_amigo" size="30" maxlength="30" onfocus="this.className='boxover'" onblur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">UF</td>
    <td colspan="2" class="subtitulopequeno"><select name="uf_amigo" id="uf_amigo">
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
    </select></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Residencial</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="fone_amigo" id="fone_amigo" size="25" maxlength="10" onkeypress="mascara(this,soNumeros); formatar('##-####-####', this)" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Comercial</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="comercial_amigo" id="comercial_amigo" size="25" maxlength="10" onkeypress="mascara(this,soNumeros); formatar('##-####-####', this)" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="celular_amigo" id="celular_amigo" size="25" maxlength="10" onkeypress="mascara(this,soNumeros); formatar('##-####-####', this)" onfocus="this.className='boxover'" onblur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franquia</td>
    <td colspan="2" class="subtitulopequeno">
		<?php
    	if (($tipo == "a") || ($tipo == "c")) {  
			echo "<select name=\"franqueado\">";
			$sql = "select id, fantasia from franquia where tipo='b' order by id";
			$resposta = mysql_query($sql);
			while ($array = mysql_fetch_array($resposta))
				{
				$franquia   = $array["id"];
				$nome_franquia = $array["fantasia"];
				echo "<option value=\"$franquia\">$nome_franquia</option>\n";
				}
			echo "</select>";
		}
		else {
			echo $nome_franquia;
			echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia; />";
			}
		?> (*)	</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
    <td colspan="2" class="subtitulopequeno"><label>
      <textarea name="obs" cols="50" rows="3"></textarea>
    </label></td>
  </tr>
  <tr>
    <td colspan="3" class="titulo"><input type="hidden" name="go" value="cadastrar" /></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><input name="submit" type="submit" value="Cadastrar          " />
    <input name="submit2" type="reset" value="             Cancela" /></td>
  </tr>
</table>
</form>
<?php
} // if go=null
if ($go=='cadastrar') {
	$data = date('Y-m-d');

	//tratamento das vari�veis
	$cpf=str_replace("/","",$cpf);
	$cpf=str_replace("-","",$cpf);
	$cpf=str_replace(".","",$cpf);
	
	$data_nasc = implode(preg_match("~\/~", $data_nasc) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $data_nasc) == 0 ? "-" : "/", $data_nasc)));
	$periodo1_empresa1 = implode(preg_match("~\/~", $periodo1_empresa1) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $periodo1_empresa1) == 0 ? "-" : "/", $periodo1_empresa1)));
	$periodo2_empresa1 = implode(preg_match("~\/~", $periodo2_empresa1) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $periodo2_empresa1) == 0 ? "-" : "/", $periodo2_empresa1)));
	$periodo1_empresa2 = implode(preg_match("~\/~", $periodo1_empresa2) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $periodo1_empresa2) == 0 ? "-" : "/", $periodo1_empresa2)));
	$periodo2_empresa2 = implode(preg_match("~\/~", $periodo2_empresa2) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $periodo2_empresa2) == 0 ? "-" : "/", $periodo2_empresa2)));

	$sql2 = "select codv from vendedor where cpf='$cpf'";
	$qr = mysql_query($sql2, $con);
	$linha = mysql_num_rows($qr);
	if ($linha != 0) {
		echo "<script>alert(\"Este vendedor ja esta cadastrado, ou pertence a outra franquia\"); history.back()</script>";
	} else {
		//insere na tabela
		$sql = "insert into vendedor (nome, cpf, rg, cnh, data_nasc, cep_resid, endereco_resid, bairro_resid, cidade_resid, uf_resid, ponto_referencia, fone_resid, celular_resid, empresa1, fone_empresa1, cargo_empresa1, periodo1_empresa1, periodo2_empresa1, resp_empresa1, empresa2, fone_empresa2, cargo_empresa2, periodo1_empresa2, periodo2_empresa2, resp_empresa2, escolaridade, curso, nome_pai, nome_mae, endereco_pais, bairro_pais, cidade_pais, uf_pais, fone_pais, comercial_pais, celular_pais, nome_conjuge, trabalho_conjuge, comercial_conjuge, celular_conjuge, nome_irmao, endereco_irmao, bairro_irmao, cidade_irmao, uf_irmao, fone_irmao, comercial_irmao, celular_irmao, nome_amigo, endereco_amigo, bairro_amigo, cidade_amigo, uf_amigo, fone_amigo, comercial_amigo, celular_amigo, id_franquia, obs, dt_cad, sitven) values (
	'$nome', '$cpf', '$rg', '$cnh', '$data_nasc', '$cep', '$endereco_resid', '$bairro_resid', '$cidade_resid', '$uf_resid', '$ponto_referencia', '$fone_resid', '$celular_resid', '$empresa1', '$fone_empresa1', '$cargo_empresa1', '$periodo1_empresa1', '$periodo2_empresa1', '$resp_empresa1', '$empresa2', '$fone_empresa2', '$cargo_empresa2', '$periodo1_empresa2', '$periodo2_empresa2', '$resp_empresa2', '$escolaridade', '$curso', '$nome_pai', '$nome_mae', '$endereco_pais', '$bairro_pais', '$cidade_pais', '$uf_pais', '$fone_pais', '$comercial_pais', '$celular_pais', '$nome_conjuge', '$trabalho_conjuge', '$comercial_conjuge', '$celular_conjuge', '$nome_irmao', '$endereco_irmao', '$bairro_irmao', '$cidade_irmao', '$uf_irmao', '$fone_irmao', '$comercial_irmao', '$celular_irmao', '$nome_amigo', '$endereco_amigo', '$bairro_amigo', '$cidade_amigo', '$uf_amigo', '$fone_amigo', '$comercial_amigo', '$celular_amigo', '$franqueado', '$obs', '$data', 0)";
		$ql = mysql_query($sql, $con);
		
		//pega o codigo de vendedor
		$resposta = mysql_query($sql2, $con);
		while ($array = mysql_fetch_array($resposta))	{
			$codv	= $array['codv'];
		}
		$res = mysql_close ($con);
		echo "<script>alert(\"Vendedor cadastrado com sucesso!\");</script>";
		
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=Franquias/b_cad_vendedor.php&go=mostrar&codv=$codv\";>";
	}
} //fim go=cadastrar

if ($go=='mostrar') {
	
	$sql = "select a.nome, a.cpf, a.rg, a.cnh, date_format(a.data_nasc,'%d/%m/%Y') as data_nasc, a.cep_resid, a.endereco_resid, a.bairro_resid, 
a.cidade_resid, a.uf_resid, a.ponto_referencia, a.fone_resid, a.celular_resid, a.empresa1, a.fone_empresa1, a.cargo_empresa1, 
date_format(a.periodo1_empresa1,'%d/%m/%Y') as periodo1_empresa1, date_format(a.periodo2_empresa1,'%d/%m/%Y') as periodo2_empresa1, 
a.resp_empresa1, a.empresa2, a.fone_empresa2, a.cargo_empresa2, date_format(a.periodo1_empresa2,'%d/%m/%Y') as periodo1_empresa2,
date_format(a.periodo2_empresa2,'%d/%m/%Y') as periodo2_empresa2, a.resp_empresa2, a.escolaridade, a.curso, a.nome_pai, a.nome_mae, 
a.endereco_pais, a.bairro_pais, a.cidade_pais, a.uf_pais, a.fone_pais, a.comercial_pais, a.celular_pais, a.nome_conjuge, a.trabalho_conjuge, 
a.comercial_conjuge, a.celular_conjuge, a.nome_irmao, a.endereco_irmao, a.bairro_irmao, a.cidade_irmao, a.uf_irmao, a.fone_irmao, 
a.comercial_irmao, a.celular_irmao, a.nome_amigo, a.endereco_amigo, a.bairro_amigo, a.cidade_amigo, a.uf_amigo, a.fone_amigo, a.comercial_amigo, 
a.celular_amigo, b.fantasia, a.obs, date_format(a.dt_cad,'%d/%m/%Y') as dt_cad, a.sitven, c.descsit from vendedor a
inner join franquia b on a.id_franquia=b.id
inner join situacao c on a.sitven=c.codsit
where codv='$codv'";

	$resposta = mysql_query($sql, $con);
	while ($matriz = mysql_fetch_array($resposta))	{
?>

<table border="0" align="center" width="643">
  <tr>
    <td colspan="2" class="titulo"><br />
      VENDEDORES CADASTRADOS</td>
  </tr>
  <tr>
    <td colspan="2" class="subtitulodireita">Dados Pessoais</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franquia</td>
    <td class="subtitulopequeno"><?php echo $matriz['fantasia']; ?></td>
  </tr>
    <tr>
    <td class="subtitulodireita">Vendedor desde</td>
    <td class="subtitulopequeno"><?php echo $matriz['dt_cad']; ?></td>
  </tr>

  <tr>
    <td class="subtitulodireita">Nome Completo</td>
    <td class="subtitulopequeno"><?php echo $matriz['nome']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C.P.F.</td>
    <td class="subtitulopequeno"><?php echo $matriz['cpf']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">R.G.</td>
    <td class="subtitulopequeno"><?php echo $matriz['rg']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C.N.H.</td>
    <td class="subtitulopequeno"><?php echo $matriz['cnh']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data de Nascimento</td>
    <td class="subtitulopequeno"><?php echo $matriz['data_nasc']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td class="subtitulopequeno"><?php echo $matriz['endereco_resid']; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td class="subtitulopequeno"><?php echo $matriz['bairro_resid']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno"><?php echo $matriz['cidade_resid']; ?> (<?php echo $matriz['uf_resid']; ?>)</td>
  </tr>
  
    <tr>
    <td class="subtitulodireita">CEP</td>
    <td class="subtitulopequeno"><?php echo $matriz['cep_resid']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Ponto de Refer&ecirc;ncia</td>
    <td class="subtitulopequeno"><?php echo $matriz['ponto_referencia']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Telefone Residencial</td>
    <td class="subtitulopequeno"><?php echo $matriz['fone_resid']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td class="subtitulopequeno"><?php echo $matriz['celular_resid']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="subtitulodireita">Experi&ecirc;ncia Profissional</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&Uacute;ltima empresa</td>
    <td class="subtitulopequeno"><?php echo $matriz['empresa1']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone</td>
    <td class="subtitulopequeno"><?php echo $matriz['fone_empresa1']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cargo</td>
    <td class="subtitulopequeno"><?php echo $matriz['cargo_empresa1']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Periodo</td>
    <td class="subtitulopequeno">de <?php echo $matriz['periodo1_empresa1']; ?> at&eacute;<?php echo $matriz['periodo2_empresa1']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Respons&aacute;vel</td>
    <td class="subtitulopequeno"><?php echo $matriz['resp_empresa1']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Pen&uacute;ltima empresa</td>
    <td class="subtitulopequeno"><?php echo $matriz['empresa2']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone</td>
    <td class="subtitulopequeno"><?php echo $matriz['fone_empresa2']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cargo</td>
    <td class="subtitulopequeno"><?php echo $matriz['cargo_empresa2']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Periodo</td>
    <td class="subtitulopequeno">de <?php echo $matriz['periodo1_empresa2']; ?> at&eacute; <?php echo $matriz['periodo2_empresa2']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Respons&aacute;vel</td>
    <td class="subtitulopequeno"><?php echo $matriz['resp_empresa2']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="subtitulodireita">Grau de Instru&ccedil;&atilde;o</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Escolaridade</td>
    <td class="subtitulopequeno"><table>
      <tr>
        <td align="right"><input type="radio" name="escolaridade" <?php if ($matriz['escolaridade'] == 1) echo "checked"; ?> /></td>
        <td class="campoesquerda">1&ordm; Grau</td>
        </tr>
      <tr>
        <td align="right"><input type="radio" name="escolaridade" <?php if ($matriz['escolaridade'] == 2) echo "checked"; ?> /></td>
        <td class="campoesquerda">2&ordm; Grau</td>
        </tr>
      <tr>
        <td align="right"><input type="radio" name="escolaridade" <?php if ($matriz['escolaridade'] == 3) echo "checked"; ?> /></td>
        <td class="campoesquerda">Superior</td>
        </tr>
      <tr>
        <td>Curso</td>
        <td class="campoesquerda"><?php echo $matriz['curso']; ?></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2" class="subtitulodireita">Refer&ecirc;ncias Pessoais</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Filia&ccedil;&atilde;o - M&atilde;e</td>
    <td class="subtitulopequeno"><?php echo $matriz['nome_mae']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Filia&ccedil;&atilde;o - Pai</td>
    <td class="subtitulopequeno"><?php echo $matriz['nome_pai']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td class="subtitulopequeno"><?php echo $matriz['endereco_pais']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td class="subtitulopequeno"><?php echo $matriz['bairro_pais']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno"><?php echo $matriz['cidade_pais']; ?> (<?php echo $matriz['uf_pais']; ?>)</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Fone Residencial</td>
    <td class="subtitulopequeno"><?php echo $matriz['fone_pais']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Comercial</td>
    <td class="subtitulopequeno"><?php echo $matriz['comercial_pais']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td class="subtitulopequeno"><?php echo $matriz['celular_pais']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome do(a) Esposo(a)</td>
    <td class="subtitulopequeno"><?php echo $matriz['nome_conjuge']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Empresa onde trabalha</td>
    <td class="subtitulopequeno"><?php echo $matriz['trabalho_conjuge']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Comercial</td>
    <td class="subtitulopequeno"><?php echo $matriz['comercial_conjuge']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td class="subtitulopequeno"><?php echo $matriz['celular_conjuge']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome do Irm&atilde;o(&atilde;)</td>
    <td class="subtitulopequeno"><?php echo $matriz['nome_irmao']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td class="subtitulopequeno"><?php echo $matriz['endereco_irmao']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td class="subtitulopequeno"><?php echo $matriz['bairro_irmao']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno"><?php echo $matriz['cidade_irmao']; ?> (<?php echo $matriz['uf_irmao']; ?>)</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Fone Residencial</td>
    <td class="subtitulopequeno"><?php echo $matriz['fone_irmao']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Comercial</td>
    <td class="subtitulopequeno"><?php echo $matriz['comercial_irmao']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td class="subtitulopequeno"><?php echo $matriz['celular_irmao']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Amigo(a) ou Conhecido(a)</td>
    <td class="subtitulopequeno"><?php echo $matriz['nome_amigo']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td class="subtitulopequeno"><?php echo $matriz['endereco_amigo']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Bairro</td>
    <td class="subtitulopequeno"><?php echo $matriz['bairro_amigo']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno"><?php echo $matriz['cidade_amigo']; ?> (<?php echo $matriz['uf_amigo']; ?>)</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Fone Residencial</td>
    <td class="subtitulopequeno"><?php echo $matriz['fone_amigo']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fone Comercial</td>
    <td class="subtitulopequeno"><?php echo $matriz['comercial_amigo']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td class="subtitulopequeno"><?php echo $matriz['celular_amigo']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
    <td class="subtitulopequeno">
    <textarea name="obs" cols="50" rows="3"><?php echo $matriz['obs']; ?></textarea>	</td>
  </tr>
    <tr>
    <td class="subtitulodireita">Situa&ccedil;&atilde;o do Vendedor</td>
    <td class="formulario" <?php if ($matriz['sitven'] == 0) {
								echo "bgcolor=\"#33CC66\"";
								} else {
								echo "bgcolor=\"#FF0000\"";} ?> ><font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <input type="button" name="imprimir" value="Imprimir" onclick="JavaScript:self.print()"  />
    <input name="button" type="button" value="  Voltar  " onclick="javascript: history.go(-2);" />    </td>
  </tr>
</table>
<?php
	}//fim while
}

if ($go=='exc_sel') {
	$selected_cnt   = count($selected);
	for ($i=0; $i<$selected_cnt; $i++) { 
		$b = $selected[$i];
		$comando = "delete from vendedor where codv='$b'";
		$res = mysql_query ($comando, $con);
	}
	$res = mysql_close ($con);
	echo "<meta http-equiv=\"refresh\" content=\"0; url= ../painel.php?pagina1=Franquias/b_rel_vendedor.php\";>";
	exit;
}
?>
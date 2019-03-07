<?php
session_start();

$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];
$fantasia = $_SESSION["fantasia"];
/*
if (($name=="") || ($tipo!="a")){
	session_unregister($_SESSION['name']);
	session_destroy();
	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
	die;
}
*/
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
function validaFranqueado(){
	// validar raz�o social
	frm = document.cadfranqueado;
	if(frm.franquia.value == ""){
		alert("O campo " + frm.franquia.name + " deve ser preenchido!");
		frm.franquia.focus();
		return false;
	}
	//validar cnpj
	if(frm.cnpj.value == ""){
		alert("O campo " + frm.cnpj.name + " deve ser preenchido!");
		frm.cnpj.focus();
		return false;
	}
	//validar cep
	if(frm.cep.value == ""){
		alert("O campo " + frm.cep.name + " deve ser preenchido!");
		frm.cep.focus();
		return false;
	}
	//validar telefone
	if(frm.telefone.value == ""){
		alert("O campo " + frm.telefone.name + " deve ser preenchido!");
		frm.telefone.focus();
		return false;
	}
	//validar email
	if(frm.email.value == ""){
		alert("O campo " + frm.email.name + " deve ser preenchido!");
		frm.email.focus();
		return false;
	}
	//validar email(verificao de endereco eletronico)
	parte1 = frm.email.value.indexOf("@");
	parte2 = frm.email.value.indexOf(".");
	parte3 = frm.email.value.length;
	if(!(parte1 >= 3 && parte2 >= 6 && parte3 >= 9)) {
		alert("O campo " + frm.email.name + " deve ser conter um endereco eletronico!");
		frm.email.focus();
		return false;
	}
	if(frm.comissao.value == ""){
		alert("O campo " + frm.comissao.name + " deve ser preenchido!");
		frm.comissao.focus();
		return false;
	}	
	//validar nome do primeiro propriet�rio
	if(frm.titular.value == ""){
		alert("O campo " + frm.titular.name + " deve ser preenchido!");
		frm.titular.focus();
		return false;
	}
	//validar cpf
	if(frm.cpftitular.value == ""){
		alert("O campo " + frm.cpftitular.name + " deve ser preenchido!");
		frm.cpftitular.focus();
		return false;
	}
}
</script>

<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script language="JavaScript" src="../js/jquery.meio.mask.js" type="text/javascript"></script>
<script type="text/javascript" >
(function($){
	// call setMask function on the document.ready event
	$(
		function(){
			$('input:text').setMask();
		}
	);
})(jQuery);
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
<form action="painel.php?pagina1=franqueado_jr/cad_franqueado_final_jr.php" method="post" name="cadfranqueado" onSubmit="return validaFranqueado();">
<table border="0" align="center" width="640">
  <tr>
    <td colspan="3" class="titulo">
    <br>
	Franquia MASTER: <?php echo $fantasia;?><br>
	<br>
	Cadastro de FRANQUEADO JUNIOR<br><br></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno" colspan="2">(*) Preenchimento obrigat&oacute;rio</td>
    </tr>
  <tr>
    <td class="subtitulodireita">Senha de acesso</td>
    <td class="subtitulopequeno" colspan="2"><?php 
	require "senha_aleatoria.php";
	?>
      <input name="senha" type="hidden" id="senha" value="<?php echo "$senha"; ?>" /></td>
  </tr>

  <tr>
    <td class="subtitulodireita">Nome do Franqueado</td>
    <td class="subtitulopequeno" colspan="2"><input class="h1" name="franquia" type="text" size="75" maxlength="200"  onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /> *</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">CPF/CNPJ</td>
    <td class="subtitulopequeno" colspan="2"><input name="cnpj" type="text" size="22" maxlength="14" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      * digite apenas n&uacute;meros </td>
  </tr>
<tr>
    <td class="subtitulodireita">CEP</td>
    <td class="subtitulopequeno">
		<table>
		  	<tr>
				<td><input type="text" name="cep" id="cep" onChange="" onKeyPress="return MM_formtCep(event,this,'#####-###');" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" maxlength="9" />(*)</td>
				<td><div id="validcep" style="color: #FF0000;"></div></td>
			</tr>
		</table>	</td>
	<td class="subtitulopequeno"><a onClick="BuscaCep();" style="cursor:hand;"><font color="#FF0000">N&atilde;o se lembra do CEP? Clique Aqui</font></a
	></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Endere&ccedil;o</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="logradouro" id="logradouro" size="75" maxlength="200" /> 
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
    <td class="subtitulodireita">Localidade</td>
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
    <td class="subtitulodireita">Telefone</td>
    <td class="subtitulopequeno" colspan="2"><input name="telefone" type="text" id="telefone" size="25" maxlength="12" onKeyPress="formatar('##-####-####', this); soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /> * digite apenas n&uacute;meros, ex. 4130261558</td>
  </tr>
  
  
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td class="subtitulopequeno" colspan="2"><input name="fone_res" type="text" id="fone_res" size="25" maxlength="12" onKeyPress="formatar('##-####-####', this); soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      digite apenas n&uacute;meros, ex. 4130261558</td>
  </tr>
  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td class="subtitulopequeno" colspan="2"><input name="email" type="text" id="email" size="40" maxlength="200" class="h2" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
  </tr>
  <tr>
    <td colspan="3" class="titulo">Dados Banc&aacute;rios</td>
    </tr>
  <tr>
    <td class="subtitulodireita">Comiss&atilde;o Franqueado JUNIOR</td>
    <td class="subtitulopequeno" colspan="2">
      <input name="comissao" type="text" size="10" maxlength="5" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /> 
      %
    </td>
  <tr>
    <td class="subtitulodireita">Banco</td>
    <td class="subtitulopequeno" colspan="2">
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
    <td class="subtitulopequeno" colspan="2">
      <input name="agencia" type="text" size="17" maxlength="14" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Tipo de Conta</td>
    <td class="subtitulopequeno" colspan="2">
    	<input type="radio" name="tpconta" value="1" checked>Conta Corrente
        <input type="radio" name="tpconta" value="2">
        Poupan&ccedil;a
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">N&uacute;mero de Conta+DV</td>
    <td class="subtitulopequeno" colspan="2">
      <input name="conta" type="text" size="17" maxlength="11" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
    </td>
  </tr>  
  
  <tr>
    <td class="subtitulodireita">Titular</td>
    <td class="subtitulopequeno" colspan="2"><input class="boxnormal" name="titular" type="text" size="75" maxlength="40" onFocus="this.className='boxover'"onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
    <tr>
    <td class="subtitulodireita">CPF do Titular</td>
    <td class="subtitulopequeno" colspan="2">
      <input name="cpftitular" type="text" size="17" maxlength="14" onKeyPress="soNumero()" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      <input name="id_franquia" type="hidden" value="<?php echo $_SESSION["id"]; ?>">
    </td>
  </tr> 

</table>
<table align="center">
      <tr align="center">
        	<td>
            	<input name="submit" type="submit" value="Enviar            " />
	        </td>
    	  	<td>
        		<input name="submit2" type="reset" value="             Cancela" />        </td>
    </tr>
  </table>
</form>
</body>
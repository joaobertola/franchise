<?
require "connect/sessao.php";
require "connect/sessao_r.php";

if ( $class == 'J' )
	$nome_classe = "Franqueado Junior";
elseif ( $class == 'X' )
	$nome_classe = "Micro Franqueado";

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

// fun��o para o drop down das tabelas de pre�o
function pesquisar_dados( valor )
{
  http.open("GET", "consulta.php?id=" + valor, true);
  http.onreadystatechange = handleHttpResponse;
  http.send(null);
}
//pega o resultado e apresenta  no seu devido lugar
function handleHttpResponse()
{
  campo_select = document.forms[0].pacote;
  if (http.readyState == 4) {
    campo_select.options.length = 0;
    results = http.responseText.split(",");
    for( i = 0; i < results.length; i++ )
    {
      string = results[i].split( "|" );
      campo_select.options[i] = new Option( string[0], string[1] );
    }
  }
}

//a pesar que parece coment�rio, � melhor n�o mexer nisto, pq reconhece os browsers
function getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}
var http = getHTTPObject();

//fun��o para validar clientes no cadastramento
function validaClientes(){
// validar raz�o social
d = document.incclient;
if (d.razaosoc.value == ""){
	alert("O campo " + d.razaosoc.name + " deve ser preenchido!");
	d.razaosoc.focus();
	return false;
}
// validar nome fantasia
if (d.nomefantasia.value == ""){
	alert("O campo " + d.nomefantasia.name + " deve ser preenchido!");
	d.nomefantasia.focus();
	return false;
}
//validar cnpj
else if (d.insc.value == ""){
	alert("O campo " + d.insc.name + " deve ser preenchido!");
	d.insc.focus();
	return false;
}
//validar cep
else if (d.cep.value == ""){
	alert("O campo " + d.cep.name + " deve ser preenchido!");
	d.cep.focus();
	return false;
}
else if (d.vendedor.value == "") {
	alert("O campo " + d.vendedor.name + " deve ser preenchido!");
	d.vendedor.focus();
	return false;
}
//validar telefone
else if (d.fone.value == ""){
	alert("O campo " + d.fone.name + " deve ser preenchido!");
	d.fone.focus();
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
else if (d.socio1.value == ""){
	alert("O campo " + d.socio1.name + " deve ser preenchido!");
	d.socio1.focus();
	return false;
}
//validar cpf
else if (d.cpfsocio1.value == ""){
	alert("O campo " + d.cpfsocio1.name + " deve ser preenchido!");
	d.cpfsocio1.focus();
	return false;
}
//validar origem
else if (d.origem.value == ""){
	alert("O campo " + d.origem.name + " deve ser selecionado!. Valor" + d.origem.value);
	d.origem.focus();
	return false;
}
//tabela do cliente
else if (d.assinatura.value == ""){
	alert("O campo " + d.assinatura.name + " deve ser selecionado!. Valor" + d.assinatura.value);
	d.assinatura.focus();
	return false;
}
else if (d.pacote.value == ""){
	alert("O campo pacote deve ser selecionado !! ");
	return false;
}
//validar ramo de atividade
else if (d.id_ramo.value == ""){
	alert("O campo " + d.id_ramo.name + " deve ser preenchido!");
	d.id_ramo.focus();
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

<form name="incclient" method="post" onSubmit="return validaClientes();" action="painel.php?pagina1=franqueado_jr/inclusao_cliente_final.php" >
<table border="0" align="center" width="643">
  <tr>
    <td colspan="3" class="titulo">
    <br>
	INCLUS&Atilde;O DE CLIENTES ( <?=$nome_classe?> )</td>
  </tr>
  <tr>
    <td width="120" class="subtitulodireita">&nbsp;</td>
    <td colspan="2" class="subtitulopequeno">(*) Preenchimento obrigat&oacute;rio</td>
    </tr>
  
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td colspan="2" class="subtitulopequeno"><a href="http://www.receita.fazenda.gov.br/PessoaJuridica/CNPJ/cnpjreva/cnpjreva_solicitacao2.asp" target="_blank"><span class="botao3d">Cart&atilde;o CNPJ - Clique aqui</span></a> (Imprimir e anexar no Roteiro de Confer�ncia)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td colspan="2" class="subtitulopequeno"><a href="../php/clientes/documentos/ROTEIRO_DE_CONFERENCIA_2009.pdf" target="_blank"><span class="botao3d">Roteiro Confer&ecirc;ncia - Clique aqui</span></a> (Confira os contratos e evite inadimpl�ncia)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td colspan="2" class="subtitulopequeno"><input name="razaosoc" type="text" id="razaosoc" size="65" maxlength="50" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />
      (*)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome Fantasia</td>
    <td colspan="2" class="subtitulopequeno"><input name="nomefantasia" type="text" id="nomefantasia" size="65" maxlength="50" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" />
      (*)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">CNPJ</td>
    <td colspan="2" class="subtitulopequeno">
    <?php   
   if (($tipo == "a") or ($tipo == "c")){?>
    <input name="insc" type="text" id="insc" size="22" maxlength="18" onKeyPress="soNumero();" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'; return validadoc_2()" />
    <?php }else{?>
    <input name="insc" type="text" id="insc" size="22" maxlength="18" onKeyPress="soNumero();" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'; return validadoc()" />
    <?php }?>    
      (*) digite apenas n&uacute;meros </td>
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
    <td class="subtitulodireita">Telefone</td>
    <td colspan="2" class="subtitulopequeno"><input name="fone" type="text" id="fone" size="25" maxlength="12" onKeyPress="soNumero(); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      (*) digite apenas n&uacute;meros, ex. 4130261558 </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Fax</td>
    <td colspan="2" class="subtitulopequeno"><input name="fax" type="text" id="fax" size="25" maxlength="12" onKeyPress="soNumero(); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      digite apenas n&uacute;meros, ex. 4130261558</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Celular</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="celular" id="celular" size="25" maxlength="12" onKeyPress="soNumero(); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      digite apenas n&uacute;meros, ex. 4199999999</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Telefone Residencial</td>
    <td colspan="2" class="subtitulopequeno"><input name="fone_res" type="text" id="fone_res" size="25" maxlength="12" onKeyPress="soNumero(); formatar('##-####-####', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
      digite apenas n&uacute;meros, ex. 4130261558</td>
  </tr>
  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td colspan="2" class="subtitulopequeno"><input name="email" type="text" id="email" size="40" maxlength="200" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /> 
      (*) </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Propriet&aacute;rio 1 </td>
    <td colspan="2" class="subtitulopequeno"><table border="0">
      <tr>
        <td>Nome
          <input name="socio1" type="text" id="socio1" size="60" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /> 
          (*) </td>
      </tr>
      <tr>
        <td>CPF 1          
          <input name="cpfsocio1" type="text" id="cpfsocio1" size="17" maxlength="14" OnKeyPress="soNumero(); formatar('###.###.###-##', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
		  (*) digite apenas n&uacute;meros</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Propriet&aacute;rio 2</td>
    <td colspan="2" class="subtitulopequeno"><table border="0">
      <tr>
        <td>Nome
          <input name="socio2" type="text" id="socio2" size="60" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
      </tr>
      <tr>
        <td>CPF 2
          <input name="cpfsocio2" type="text" id="cpfsocio2" size="17" maxlength="14" OnKeyPress="soNumero(); formatar('###.###.###-##', this)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
		  digite apenas n&uacute;meros</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Segmento Empresarial </td>
    <td colspan="2" class="subtitulopequeno"><input name="id_ramo" type="text" id="id_ramo" size="30" maxlength="25" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />
	(*) ex. m&oacute;veis, supermercado, confec&ccedil;&atilde;o, posto de combustiveis, etc. </td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Vendedor</td>
    <td colspan="2" class="subtitulopequeno"><input type="text" name="vendedor" id="vendedor" size="30" maxlength="20" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" ></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Origem do Cliente</td>
    <td colspan="2" class="subtitulopequeno">
		<select name="origem">
			<option selected>:: Selecione ::</option>
			<?
			$conco = "select * from concorrentes";
			$concor = mysql_query($conco,$con);
			while ($tabua = mysql_fetch_array($concor)) {
				$id_concorrente = $tabua['Id'];
				$concorrente    = $tabua['nome_concorrente'];
				echo "<option value=\"$id_concorrente\">$concorrente</option>\n";
			}
			?>
		</select>	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franquia MASTER</td>
    <td colspan="2" class="subtitulopequeno">
		<?php
		echo $nome_franquia_master;
		echo "<input name=\"id_franquia_master\" type=\"hidden\" id=\"id_franquia_master\" value= $id_franquia_master>";
		?></td>
  </tr>

  <tr>
    <td class="subtitulodireita">Franquia JUNIOR</td>
    <td colspan="2" class="subtitulopequeno">
		<?php
		echo $nome_franquia;
		echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia>";
		?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Tabela de Pre&ccedil;os</td>
    <td width="282" class="subtitulopequeno">
		<table class="subtitulopequeno" border="0">
        <tr>
          <td>
            Assinatura			</td>
          <td><select name="assinatura" onChange="pesquisar_dados( this.value )">
            <option></option>
            <?php
				$consulta = mysql_query('SELECT * FROM tabela_tipo ORDER BY nome ASC');
				while( $row = mysql_fetch_assoc($consulta) )
				{
					echo "<option value=\"{$row['id']}\">{$row['nome']}</option>\n";
				}
				?>
          </select></td>
        </tr>
        <tr>
          <td>Pacote</td>
          <td><select name="pacote">
          	  </select></td>
        </tr>
	  </table>	</td>
    <td width="227" class="subtitulopequeno">Faturamento
      <table class="subtitulopequeno" border="0">
        <tr>
          <td><input type="radio" name="fatura" value="1" checked />
            Emiss&atilde;o de fatura </td>
        </tr>
        <tr>
          <td><input type="radio" name="fatura" value="2" />
            Emiss&atilde;o de Nota Fiscal </td>
        </tr>
        <tr>
          <td><input type="radio" name="fatura" value="3" />
            Emiss&atilde;o de fatura e NF</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
    <td colspan="2" class="subtitulopequeno"><label>
      <textarea name="obs" cols="50" rows="3"></textarea>
    </label></td>
  </tr>
  <tr>
    <td colspan="3" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><input name="submit" type="submit" value="Enviar            " />
    <input name="submit2" type="reset" value="             Cancela" /></td>
  </tr>
</table>
</form>
<table width="400" align="center">
<tr class="titulo">
  <td class="total">Dicas Web Control Empresas </td>
</tr>
<tr>
<td class="subtitulopequeno">
	<ul>
  		<li>O cadastro &eacute; realizado apenas uma vez. Sempre que voc&ecirc; quiser alterar alguma informa&ccedil;&atilde;o basta acessar na altera&ccedil;&atilde;o de clientes.</li>
		<li>O cadastro ser&aacute; utilizado apenas para a presta&ccedil;&atilde;o dos servi&ccedil;os, sendo mantido em car&aacute;ter confidencial</li>
	</ul>
</td>
</tr>
</table>
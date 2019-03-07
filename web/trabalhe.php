<link href="sct/geral.css" rel="stylesheet" type="text/css">
<style type="text/css">
form{margin:30px 50px 0;}
form input, select {
	font-family:Arial;
	font-size:8pt;
	color: #F60;
}
</style>
<script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script language="javascript">
//função para converter tudo em maiúsculo
function maiusculo(obj) {
	obj.value = obj.value.toUpperCase();
}

//função para aceitar somente numeros em determinados campos
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

function validaFormTrabalhe(){
d = document.sendtrabalha;
if (d.AreaInteresse.value == '0'){
	alert("O campo Area de Interesse deve ser selecionado!");
	d.AreaInteresse.focus();
	return false;
}
if (d.SalarioPretendido.value == '0'){
	alert("Selecionade o salario pretendido!");
	d.SalarioPretendido.focus();
	return false;
}
if (d.Nome.value == ""){
	alert("Preencha o nome completo!");
	d.Nome.focus();
	return false;
}
if (d.cpf.value == ""){
	alert("Preencha o número do seu CPF !");
	d.cpf.focus();
	return false;
}
if (d.Endereco.value == ""){
	alert("Preencha o endereço completo da sua casa!");
	d.Endereco.focus();
	return false;
}
if (d.numero.value == ""){
	alert("Preencha o número de sua residência !");
	d.numero.focus();
	return false;
}
if (d.Cidade.value == ""){
	alert("Preencha a sua cidade onde reside atualmente!");
	d.Cidade.focus();
	return false;
}
if (d.uf.value == '0'){
	alert("Selecionade o estado onde reside atualmente!");
	d.uf.focus();
	return false;
}
if (d.fone.value == ""){
	alert("Coloque o seu numero de telefone para entrarmos em contato !");
	d.fone.focus();
	return false;
}

return true;
}
</script>

<table border="0" width="100%" cellpadding="10" cellspacing="0">
	<tr>
		<td align="justify">
		<p> 
		  <script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0','width','140','height','23','src','swf/tit_trabalhe','quality','high','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','swf/tit_trabalhe' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="140" height="23">
			<param name="movie" value="swf/tit_trabalhe.swf">
			<param name="quality" value="high">
			<embed src="swf/tit_trabalhe.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="140" height="23"></embed></object></noscript>
		</p>
		<ul>
		  <li>
		    <p>&Eacute; Bom Trabalhar na <strong>Inform System!</strong></p>
		    <p>Venha fazer parte da empresa l&iacute;der em Solu&ccedil;&otilde;es e Pesquisas de &Aacute;nalise de Cr&eacute;dito  para a pequena, m&eacute;dia e grande empresa brasileira, e conquiste a oportunidade de   trabalhar em um ambiente saud&aacute;vel, formado por profissionais especializados nas   mais diversas &aacute;reas de atua&ccedil;&atilde;o.</p>
		  </li>		
		  <script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','650','height','190','title','Campeoes de Venda','src','swf/campeoes','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','swf/campeoes' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="650" height="190" title="Campeoes de Venda">
            <param name="movie" value="swf/campeoes.swf" />
            <param name="quality" value="high" />
            <embed src="swf/campeoes.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="650" height="190"></embed>
	      </object></noscript>
		  <p>Para viver essa excel&ecirc;ncia, procuramos aquele profissional que entenda   criatividade como ferramenta de inova&ccedil;&atilde;o, conhecimento como modo de expandir   id&eacute;ias, e oportunidade qualificada como motiva&ccedil;&atilde;o para crescimento profissional   e pessoal.</p>
		<p>Se esse &eacute; o desafio que voc&ecirc; procura, <b><font color="#ff6600">cadastre-se</font></b> para novas   oportunidades.</p>
       </ul>
      </td>
	</tr>
</table>
<div class="hr"></div>
<form name="sendtrabalha" method="post" onSubmit="return validaFormTrabalhe();" action="index.php?web=sendtrabalhe">
  <TABLE style="FONT-SIZE: 11px; FONT-FAMILY: verdana" cellSpacing=0 cellPadding=5>
    <TR>
      <td width="203"><strong>&Aacute;rea de Interesse</strong></td>
<td colspan=4>
            <select name="AreaInteresse" id="AreaInteresse">
            <option value="0" selected>::Selecione::</option>
            <option>Administrativa</option>
            <option>Cobran&ccedil;as</option>
            <option>Central de Atendimento</option>
            <option>Comercial</option>
            <option>Vendas</option>
    </select> 
            (*)
      </td>
    </TR>
    <TR>
        <td><strong>Salário Pretendido</strong></td>
        <td colspan=4>
            <select name="SalarioPretendido" id="SalarioPretendido">
            <option value="0" selected>::Selecione::</option>
            <option>Até R$500.00</option>
            <option>R$501.00 a R$1.000.00</option>
            <option>R$1.001.00 a R$2.000.00</option>
            <option>R$2.001.00 a R$3.000.00</option>
            <option>R$3.001.00 a R$4.000.00</option>
            <option>R$4.001.00 a R$5.000.00</option>
            <option>R$5.001.00 a R$10.000.00</option>
            </select> 
            (*)
        </td>
  </TR>
  <TR>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </TR>
  <TR>
    <td><strong>Nome</strong></td>
    <td colspan=4><input name="Nome" id="Nome" maxLength=60 size=70 onBlur="maiusculo(this);"> 
    (*)</td>
  </TR>
  <TR>
    <td><strong>CPF</strong></td>
    <td><input type="text" name="cpf" onKeyPress="soNumero(); formatar('###.###.###-##', this)" style="FONT-SIZE: 11px; FONT-FAMILY: verdana" maxLength=14 size=25 >
(*) </td>
    <td><strong>RG</strong></td>
    <td><input type="text" name="rg" maxlength="20" size="17" />
  </TR>
  <TR>
    <td><strong>Estado Civil</strong></td>
    <td colspan=4>
        <input type="radio" name="EstadoCivil" value="Casado" size=70>Casado
        <input type="radio" name="EstadoCivil" value="Divorciado" size=70>Divorciado
        <input type="radio" name="EstadoCivil" value="Solteiro" size=70>Solteiro
        <input type="radio" name="EstadoCivil" value="Viuvo" size=70>Viúvo            </td>
  </TR>
  <tr>
    <td><strong>Data de Nascimento </strong></td>
    <td width="261"><input name="datanascimento" maxlength=10 size=20 onKeyPress="soNumero(); formatar('##/##/####', this)"></td>
    <td width="76"><strong>Sexo</strong></td>
<td width="121">
        <select name="Sexo">
        <option selected>::Selecione::</option>
        <option>Masculino</option>
        <option>Feminino</option>
      </select>            </td>
  </tr>
  <tr>
    <td><strong>Nacionalidade</strong></td>
    <td colspan=4>
        <select name="Nacionalidade">
        <option selected>::Selecione::</option>
        <option>Brasileira</option>
        <option>Brasileira (Naturalizado)</option>
        <option>Estrangeira</option>
        </select>	        </td>
<tr>
    <td><strong>Naturalidade</strong></td>
    <td colspan=4><input name="Naturalidade" maxLength=42 size=35 onBlur="maiusculo(this);"></td>
  </tr>
  <tr>
    <td><strong>Tipo Endereço</strong></td>
    <td colspan="3"><?
						require "restrito/conexao_conecta.php";
						$combo = "<select name='id_tp_log'>
			    	              <option value=''>Selecione</option>";
						$sql_tipo = 'Select id,descricao from apoio.Tipo_Log';
						$qry_sql_tipo = mysql_query($sql_tipo,$con);
						while($rst_tipo = mysql_fetch_array($qry_sql_tipo)) {
							$id_x  = $rst_tipo['id'];
							$descricao  = $rst_tipo['descricao'];                         	
							if ($tplog == $id_x){
								$combo .= "<option selected value='$id_x'>$descricao</option>";
							}else{
								$combo .= "<option value='$id_x'>$descricao</option>";
							}
						}
						echo $combo;
		            ?></td>
    </tr>
  <tr>
    <td><strong> Endere&ccedil;o</strong></td>
    <td colspan="3"><input name="Endereco" id="Endereco" maxlength="40" size="50" onblur="maiusculo(this);" />
      (*)</td>
    </tr>
  <tr>
    <td><strong>N&uacute;mero</strong></td>
    <td><input name="numero" id="numero" maxlength="15" size="20" onKeyPress="soNumero();" onblur="maiusculo(this);" />
(*)</td>
    <td><strong>Compl.</strong></td>
    <td><input name="Complemento" maxlength="20" size="17" onblur="maiusculo(this);" /></td>
  </tr>
  <tr>
    <td><strong>Bairro</strong></td>
    <td><input name="Bairro" maxLength=25 size=40 onBlur="maiusculo(this);">
(*)</td>
    <td><strong>CEP</strong></td>
    <td><input name="CEP" maxLength=9 size=12 onKeyPress="soNumero(); formatar('#####-###', this)"></td>
  </tr>
  <tr>
    <td><strong>Cidade</strong></td>
    <td><input name="Cidade" id="Cidade" maxLength=35 size=40 onBlur="maiusculo(this);">
    (*)</td>
    <td><strong>Estado</strong></td>
    <td>            	
        <select name="uf" id="uf">
        <option value="0" selected>::Escolha::</option>
        <option>AC</option>
        <option>AL</option>
        <option>AM</option>
        <option>AP</option>
        <option>BA</option>
        <option>CE</option>
        <option>DF</option>
        <option>ES</option>
        <option>GO</option>
        <option>MA</option>
        <option>MG</option>
        <option>MS</option>
        <option>MT</option>
        <option>PA</option>
        <option>PB</option>
        <option>PE</option>
        <option>PI</option>
        <option>PR</option>
        <option>RJ</option>
        <option>RN</option>
        <option>RO</option>
        <option>RR</option>
        <option>RS</option>
        <option>SC</option>
        <option>SE</option>
        <option>SP</option>
        <option>TO</option>
        </select> 
        (*)            </td>
  </tr>
  <tr>
    <td><strong>Telefone Residencial</strong></td>
    <td><input type="text" name="fone" id="fone" onKeyPress="soNumero(); formatar('##-####-####', this)" maxLength=25 size=18 >
    ddd+telefone (*)</td>
    <td><strong>Recado c/</strong></td>
    <td><input name="RecadoCom" maxLength=16 size=17 onBlur="maiusculo(this);"></td>
  </tr>
  <tr>
    <td><strong>Celular</strong></td>
    <td><input type="text" name="cel" onKeyPress="soNumero(); formatar('##-####-####', this)" maxLength=25 size=18 >
    ddd+telefone</td>
    <td><strong>E-mail</strong></td>
    <td><input name="email" maxLength=50 size=17></td>
  </tr>
  <tr>
    <td><strong>Já trabalhou na Inform System?</strong></td>
    <td colspan="3">
<input type="radio" name="trabalhou" value="Sim">Sim
<input type="radio" name="trabalhou" value="Nao" checked>Não</td>
  </tr>
  <tr>
    <td><strong>Como soube da Inform System?</strong></td>
    <td colspan=4>
        <select name="comosoube">
        <option selected></option>
        <option>Amigo</option>
        <option>Cartaz</option>
        <option>Internet</option>
        <option>Jornal</option>
        <option>Rádio</option>
        <option>Site Inform System</option>
        <option>Outros</option>
        </select>             </td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td width="100" height="50"><i><b> 
          <input type="submit" name="enviar2" value="Enviar">
          </b></i></td>
      <td width="100"><i><b> 
        <input type="reset" name="reset" value="Limpar">
        </b></i></td>
    </tr>
</table>
</form>
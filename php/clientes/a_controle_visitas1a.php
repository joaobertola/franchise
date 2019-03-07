<script type="text/javascript">


    /* M�scaras ER */
    function xmascara(o,f){
        v_obj=o
        v_fun=f
        setTimeout("xexecmascara()",1)
    }
    function xexecmascara(){
        v_obj.value=v_fun(v_obj.value)
    }
    function mtel(v){
        v=v.replace(/\D/g,"");             //Remove tudo o que n�o � d�gito
        v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca par�nteses em volta dos dois primeiros d�gitos
        v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca h�fen entre o quarto e o quinto d�gitos
        return v;
    }
    function id( el ){
        return document.getElementById( el );
    }
    window.onload = function(){
        id('fone1').onkeypress = function(){
            xmascara( this, mtel );
        }
        id('fone2').onkeypress = function(){
            xmascara( this, mtel );
        }
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

    function verificaEndereco() {
        var campos = {
            cep: document.getElementById("cep_agendamento"),
            endereco: document.getElementById("endereco"),
            bairro: document.getElementById("bairro"),
            cidade: document.getElementById("cidade"),
            uf: document.getElementById("uf")
        };

        var ajax = XMLHTTPRequest();
        ajax.open("GET", ("../client.php?cep="+campos.cep.value.replace(/[^\d]*/, "")), true);

        ajax.onreadystatechange = function() {
            if (ajax.readyState == 1) {
                campos.endereco.disabled = true;
                campos.endereco.value = "carregando...";
                campos.bairro.disabled = true;
                campos.cidade.disabled = true;
                campos.bairro.value = "carregando...";
                campos.uf.disabled = true;
                campos.cidade.value = "carregando...";
            } else if (ajax.readyState == 4) {
                if(ajax.responseText == false){
                    campos.endereco.disabled = false;
                    campos.endereco.value = "";
                    campos.bairro.disabled = false;
                    campos.cidade.disabled = false;
                    campos.bairro.value = "";
                    campos.uf.disabled = false;
                    campos.cidade.value = "";
                }else{
                    var r = ajax.responseText, i, logradouro, complemento, numero, bairro, localidade, uf;
                    logradouro = r.substring(0, (i = r.indexOf(':')));
                    campos.endereco.disabled = false;
                    campos.endereco.value = unescape(logradouro.replace(/\+/g," "));
                    <!-- IMPLEMENTADO NA VERS�O 4.0 -->
                    r = r.substring(++i);
                    complemento = r.substring(0, (i = r.indexOf(':')));
//                    campos.complemento.disabled = false;
//                    campos.complemento.value = unescape(complemento.replace(/\+/g," "));
                    r = r.substring(++i);
                    bairro = r.substring(0, (i = r.indexOf(':')));
                    campos.bairro.disabled = false;
                    campos.bairro.value = unescape(bairro.replace(/\+/g," "));
                    r = r.substring(++i);
                    localidade = r.substring(0, (i = r.indexOf(':')));
                    campos.cidade.disabled = false;
                    campos.cidade.value = unescape(localidade.replace(/\+/g," "));
                    <!-- IMPLEMENTADO NA VERS�O 4.0 -->
                    r = r.substring(++i);
                    numero = r.substring(0, (i = r.indexOf(':')));
//                    campos.numero.disabled = false;
//                    campos.numero.value = unescape(numero.replace(/\+/g," "));
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



</script>

<?php

require "connect/sessao.php";
require "connect/sessao_r.php";

if ( $_REQUEST['rel_franquia'] == '' )
	$id_franquia = $_SESSION['id'];
else
	$id_franquia = $_REQUEST['rel_franquia'];

//echo "<PRE>";
//print_r( $_REQUEST );


if ( $id_franquia == 4 || $id_franquia == 5 || $id_franquia == 163 || $id_franquia == 247 )
    $id_franquia = 1;

?>
<script type="text/javascript" src="../../../inform/js/prototype.js"></script>
<script language="javascript">

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

function Mascara_Hora(hora){ 
var hora01 = ''; 
hora01 = hora01 + hora; 
if (hora01.length == 2){ 
hora01 = hora01 + ':'; 
document.forms[0].hora.value = hora01; 
} 
if (hora01.length == 5){ 
Verifica_Hora(); 
} 
} 
           
function Verifica_Hora(){ 
hrs = (document.forms[0].hora.value.substring(0,2)); 
min = (document.forms[0].hora.value.substring(3,5)); 
               
estado = ""; 
if ((hrs < 00 ) || (hrs > 23) || ( min < 00) ||( min > 59)){ 
estado = "errada"; 
} 
               
if (document.forms[0].hora.value == "") { 
estado = "errada"; 
} 

if (estado == "errada") { 
alert("Hora inválida!"); 
document.forms[0].hora.focus(); 
} 
} 


function maiusculo(obj)
{
	obj.value = obj.value.toUpperCase();
}

function trim(str){
	return str.replace(/^\s+|\s+$/g,"");
}

function valida_dados(){
	d = document.form1;
        if ( d.triplicar_venda[0].checked == false && d.triplicar_venda[1].checked == false ){
		alert("O campo 'Triplicar suas Vendas' deve ser preenchido !");
		return false;
	}
        else if ( d.cad_cliente[0].checked == false && d.cad_cliente[1].checked == false ){
		alert("O campo 'Cadastros de Clientes' deve ser preenchido !");
		return false;
	}
        else if ( d.prod_estoque[0].checked == false && d.prod_estoque[1].checked == false ){
		alert("O campo 'Cadastro de Produto e Estoque' deve ser preenchido !");
		return false;
	}
        else if ( d.boletos[0].checked == false && d.boletos[1].checked == false ){
		alert("O campo 'Emissão de Boletos ou Carnê Crediário' deve ser preenchido !");
		return false;
	}
        else if ( d.nota_fiscal[0].checked == false && d.nota_fiscal[1].checked == false ){
		alert("O campo 'Emissão de Nota Fiscal' deve ser preenchido !");
		return false;
	}
        else if ( d.site[0].checked == false && d.site[1].checked == false ){
		alert("O campo 'Site na Internet' deve ser preenchido !");
		return false;
	}
        else if(trim(d.data_agenda.value) == ""){
		alert("O campo Data do Agendamento deve ser preenchido!");
		d.data_agenda.focus();
		return false;
	}
	else if(trim(d.id_assistente_grava.value) == ""){
		alert("O campo Assistente deve ser preenchido!");
		d.id_assistente_grava.focus();
		return false;
	}
	else if(trim(d.hora.value) == ""){
            console.log('HORA:' + d.hora.value +d.hora.name);
		alert("O campo " + d.hora.name + " deve ser preenchido!");
		d.hora.focus();
		return false;
	}
	else if(trim(d.empresa.value) == ""){
            console.log('EMPRESA:' + d.empresa.value +d.empresa.name);
		alert("O campo " + d.empresa.name + " deve ser preenchido!");
		d.empresa.focus();
		return false;
	}
	else if (d.endereco.value == ""){
		alert("O campo " + d.endereco.name + " deve ser preenchido!");
		d.endereco.focus();
		return false;
	}
	else if (d.fone1.value == ""){
		alert("O campo " + d.fone1.name + " deve ser preenchido!");
		d.fone1.focus();
		return false;
	}
	else if (d.responsavel.value == ""){
		alert("O campo " + d.responsavel.name + " deve ser preenchido+++!");
		d.responsavel.focus();
		return false;
	}
    else if (d.bairro.value == ""){
        alert("O campo " + d.bairro.name + " deve ser preenchido+++!");
        d.responsavel.focus();
        return false;
    }
    else if (d.cidade.value == ""){
        alert("O campo " + d.cidade.name + " deve ser preenchido+++!");
        d.responsavel.focus();
        return false;
    }
//    else if (d.cep_agendamento.value == ""){
//        alert("O campo " + d.cep_agendamento.name + " deve ser preenchido+++!");
//        d.responsavel.focus();
//        return false;
//    }
	grava_Registro();     
}

function grava_Registro(){
 	d = document.form1;
  d.action = 'painel.php?pagina1=clientes/a_controle_visitas_grava.php';
	d.submit();
} 

function pesquisa_dados(){	
	d = document.form2;
	if(trim(d.rel_datai.value) == ""){
		alert("Ops.. Desculpe, voce poderia me informar a Data ? Obrigado !");
		d.rel_datai.focus();
		return false;
	}

  d.action = 'painel.php?pagina1=clientes/a_controle_visitas_relatorio.php';
	d.submit();
}

function cancelar(){
 	d = document.form1;
    d.action = 'painel.php?pagina1=clientes/a_controle_visitas0.php';
	d.submit();
} 
  
function cadastroConsultores() {
  cupom  = open('clientes/lista_consultores.php?id_franquia=<?=$_SESSION['id']?>', 'consultores', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+710+',height='+600+',left='+0+', top='+0+',screenX='+0+',screenY='+0+'');  
}
</script>

<form name="form1" method="post" action="#" >
<table border="0" align="center"  class="table col55">
  <tr>
  	<td colspan="2" class="titulo"><br>SISTEMA&nbsp;&nbsp;DE&nbsp;&nbsp;CONTROLE&nbsp;&nbsp;COMERCIAL</td>
  </tr>
  <tr>
    <td width="150" class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr> 
  
  <tr>
    <td class="subtitulodireita">Data do Cadastro</td>
    <td class="subtitulopequeno">&nbsp;<?=date('d/m/Y')?></td>
  </tr>

  <tr>
    <td class="subtitulodireita">Cliente possui algo para:</td>
    <td class="subtitulopequeno">
        <table border = 1>
            <tr>
                <td width="200">Triplicar suas Vendas</td>
                <td>
                    <input type="radio" name="triplicar_venda" id="triplicar_venda" value="1" <?php echo $_POST['triplicar_venda'] == '1' ? 'checked' : ''?>>Sim
                    <input type="radio" name="triplicar_venda" id="triplicar_venda" value="2" <?php echo $_POST['triplicar_venda'] == '2' ? 'checked' : ''?>>Nao
                </td>
            </tr>
            <tr>
                <td width="200">Cadastros de Clientes</td>
                <td>
                    <input type="radio" name="cad_cliente" value="1" <?php echo $_POST['cad_cliente'] == '1' ? 'checked' : ''?>>Sim
                    <input type="radio" name="cad_cliente" value="2" <?php echo $_POST['cad_cliente'] == '2' ? 'checked' : ''?>>Nao
                </td>            
            </tr>
            <tr>
                <td width="200">Cadastro de Produto e Estoque</td>
                <td>
                    <input type="radio" name="prod_estoque" value="1" <?php echo $_POST['prod_estoque'] == '1' ? 'checked' : ''?>>Sim
                    <input type="radio" name="prod_estoque" value="2" <?php echo $_POST['prod_estoque'] == '2' ? 'checked' : ''?>>Nao
                </td>            
            </tr>
            <tr>
                <td width="200">Emissão de Boletos ou Carnê Crediário</td>
                <td>
                    <input type="radio" name="boletos" value="1" <?php echo $_POST['boletos'] == '1' ? 'checked' : ''?>>Sim
                    <input type="radio" name="boletos" value="2" <?php echo $_POST['boletos'] == '2' ? 'checked' : ''?>>Nao
                </td>            
            </tr>
            <tr>
                <td width="200">Frente de Caixa</td>
                <td>
                    <input type="radio" name="frente_caixa" value="1" <?php echo $_POST['frente_caixa'] == '1' ? 'checked' : ''?>>Sim
                    <input type="radio" name="frente_caixa" value="2" <?php echo $_POST['frente_caixa'] == '2' ? 'checked' : ''?>>Nao
                </td>            
            </tr>
            <tr>
                <td width="200">Emissão de Nota Fiscal</td>
                <td>
                    <input type="radio" name="nota_fiscal" value="1" <?php echo $_POST['nota_fiscal'] == '1' ? 'checked' : ''?>>Sim
                    <input type="radio" name="nota_fiscal" value="2" <?php echo $_POST['nota_fiscal'] == '2' ? 'checked' : ''?>>Nao
                </td>            
            </tr>
            <tr>
                <td width="200">Site na Internet</td>
                <td>
                    <input type="radio" name="site" value="1" <?php echo $_POST['site'] == '1' ? 'checked' : ''?>>Sim
                    <input type="radio" name="site" value="2" <?php echo $_POST['site'] == '2' ? 'checked' : ''?>>Nao
                </td>            
            </tr>
        </table>
        
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">Assistente</td>
    <td class="subtitulopequeno">
    <?php 
        $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' 
        AND tipo_cliente = '1' AND situacao IN('0', '1') ORDER BY situacao, nome";
        $qry = mysql_query($sql_sel, $con);
        echo "<select name='id_assistente_grava' style='width:65%'>";
        ?>
          <option value="">Selecionar</option>
        <?php
        while($rs = mysql_fetch_array($qry)) {
        if($rs['situacao'] == "0"){
                  $sit = "Ativo";
              }elseif($rs['situacao'] == "1"){
                  $sit = "Bloqueado";
              }elseif($rs['situacao'] == "2"){
                  $sit = "Cancelado";
              }
        ?>
        <option value="<?=$rs['id']?>" <?php echo $_POST['id_assistente_grava'] == $rs['id'] ? 'selected' : ''?>><?=$rs['nome']?> - <?=$sit?></option>
    <?php } ?>
    </select>

  <tr>
    <td class="subtitulodireita">Data do Agendamento</td>
    <td class="subtitulopequeno"><input name="data_agenda" type="text" id="data_agenda" value="<?=$_REQUEST['data_agenda']?>" size="15" maxlength="10"  onFocus="this.className='boxover'" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onBlur="this.className='boxnormal'" /></td>  
  </tr>
  
  <tr>
    <td class="subtitulodireita">Consultor</td>
    <td class="subtitulopequeno">
    <?php 
        $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' 
         AND tipo_cliente = '0' AND situacao IN('0') ORDER BY situacao, nome";
        $qry = mysql_query($sql_sel, $con);
        echo "<select name='id_consultor' id='id_consultor' style='width:65%'>";
        ?>
          <option value="">Selecionar</option>
        <?php

        while($rs = mysql_fetch_array($qry)) { 
              if($rs['situacao'] == "0"){
                  $sit = "Ativo";
              }elseif($rs['situacao'] == "1"){
                  $sit = "Bloqueado";
              }elseif($rs['situacao'] == "2"){
                  $sit = "Cancelado";
              }
    ?>
                <?php if($_REQUEST['id_consultor'] == $rs['id']) { ?>
                  <option value="<?=$rs['id']?>" selected><?=$rs['nome']?> - <?=$sit?></option>
                <?php } else { ?>
                  <option value="<?=$rs['id']?>"><?=$rs['nome']?> - <?=$sit?></option>
                <?php } ?>                                                            
    <?php } ?>
    </select>
    <a href="#" onClick="var myAjax = new Ajax.Updater('agenda_horario', 'clientes/carrega_horario_consultor.php?id_consultor='+document.forms[0].id_consultor.value+'&data_agendamento='+document.forms[0].data_agenda.value+'&id_franquia=<?=$id_franquia?>', {method: 'post', parameters: 'foo=bar'})">Verificar hor&aacute;rios ocupados</a>
      </td>
  </tr>
	<tr>
    	<td class="subtitulodireita">Hor&aacute;rios ocupados</td>
    	<td class="subtitulopequeno">
            <div id="agenda_horario">
            ...  
            </div>
        </td>
    </tr>
	<tr>
    	<td class="subtitulodireita">Hora</td>
    	<td class="subtitulopequeno">
    		<input name="hora" type="text" id="hora" size="10" maxlength="5" onFocus="this.className='boxover'" onKeyPress="soNumero();" OnKeyUp="Mascara_Hora(this.value)"  value="<?php echo $_POST['hora'] ?>"/>
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">
    	 <input name="tipo" type="radio" value="Empresa" checked="checked" onclick="tipoEmp(this)"/> Empresa &nbsp;&nbsp;&nbsp;
         <input name="tipo" type="radio" value="Contador" onclick="tipoEmp(this)" <?php echo $_POST['tipo'] == 'Contador' ? 'checked' : ''?>/> Contador &nbsp;&nbsp;&nbsp;
         <input name="tipo" type="radio" value="Sindicato" onclick="tipoEmp(this)" <?php echo $_POST['tipo'] == 'Sindicato' ? 'checked' : ''?>/> Sindicato &nbsp;&nbsp;&nbsp;
         <input name="tipo" type="radio" value="Associacao" onclick="tipoEmp(this)" <?php echo $_POST['tipo'] == 'Associacao' ? 'checked' : ''?>/> Associa&ccedil;&atilde;o &nbsp;&nbsp;&nbsp;
         <input name="tipo" type="radio" value="Shopping" onclick="tipoEmp(this)" <?php echo $_POST['tipo'] == 'Shopping' ? 'checked' : ''?>/> Shopping &nbsp;&nbsp;&nbsp;
         <input name="tipo" type="radio" value="Sebrae" onclick="tipoEmp(this)" <?php echo $_POST['tipo'] == 'Sebrae' ? 'checked' : ''?>/> Sebrae
     </td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">
    <script>
	function tipoEmp(campo) {
		if( campo.getValue() ) document.getElementById('janelinha').innerHTML = campo.getValue();
		document.form1.empresa.focus();
	}
    </script>
    <div id="janelinha">Empresa</div>

    </td>
    <td class="subtitulopequeno"><input name="empresa" type="text" value="<?php echo $_POST['empresa'] ?>" id="empresa" size="75" maxlength="60" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'"/></td>
  </tr>


  <tr>
      <td class="subtitulodireita">CEP</td>
      <td class="subtitulopequeno"><input name="cep_agendamento" type="text" value="<?php echo $cep ?>"  id="cep_agendamento" maxlength="9" onchange="verificaEndereco()" onKeyPress="return MM_formtCep(event,this,'#####-###');"/></td>
  </tr>


  <tr>
      <td class="subtitulodireita">Endere&ccedil;o</td>
    <td class="subtitulopequeno"><input name="endereco" type="text" id="endereco" size="75" value="<?php echo $endereco ?>"  maxlength="100" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>

    <tr>
        <td class="subtitulodireita">N&ordm;</td>
        <td class="subtitulopequeno"><input name="numero" type="text" id="numero" size="75" value="<?php echo $endereco ?>"  maxlength="10" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
    </tr>

  <tr>
      <td class="subtitulodireita">Bairro</td>
    <td class="subtitulopequeno"><input name="bairro" type="text" id="bairro" size="75" maxlength="60" value="<?php echo $bairro ?>" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>

  <tr>
      <td class="subtitulodireita">Cidade</td>
    <td class="subtitulopequeno"><input name="cidade" type="text" id="cidade" size="75" maxlength="50" value="<?php echo $cidade ?>" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
        <tr>
            <td class="subtitulodireita">UF</td>
            <td colspan="2" class="subtitulopequeno">
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
                </select>
                <font color="#FF0000">(*)</font>
            </td>
        </tr>
  <tr>
      <td class="subtitulodireita">Ponto de Referencia</td>
    <td class="subtitulopequeno"><input name="ponto_referencia" type="text" id="ponto_referencia" size="75" maxlength="50" value="<?php echo $_POST['ponto_referencia'] ?>" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>

  <tr>
    <td class="subtitulodireita">Fone 1</td>
    <td class="subtitulopequeno">
    	<input name="fone1" type="text" id="fone1" size="22" maxlength="15" onFocus="this.className='boxover'" value="<?php echo $_POST['fone1'] ?>"/></td>
  </tr>

  <tr>
    <td class="subtitulodireita">Fone 2</td>
    <td class="subtitulopequeno">
        <input type="text" name="fone2" id="fone2" size="22" maxlength="15" onFocus="this.className='boxover'"  value="<?php echo $_POST['fone2'] ?>"/></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Respons&aacute;vel</td>
    <td class="subtitulopequeno"><input type="text" name="responsavel" id="responsavel" size="75" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" value="<?php echo $_POST['responsavel'] ?>"/></td>
  </tr>
    <tr>
        <td class="subtitulodireita">Observa&ccedil;&otilde;es</td>
        <td class="subtitulopequeno"><textarea name="observacao" size="75" style="resize: none;" rows="4" id="observacao" value="<?php echo $_POST['observacao'] ?>"></textarea></td>
    </tr>
  <tr>
    <td colspan="3" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">
		<input name="Gravar" type="button" value=" Gravar " onClick="valida_dados();" />
        &nbsp;&nbsp;&nbsp;
        <input name="Voltar" type="button" value=" Voltar " onClick="cancelar()" />
    </td>
  </tr>
</table>
<?php
require "connect/sessao.php";
require "connect/sessao_r.php";

if ( $_REQUEST['rel_franquia'] == '' )
	$id_franquia = $_SESSION['id'];
else
	$id_franquia = $_REQUEST['rel_franquia'];

//echo "<PRE>";
//print_r( $_REQUEST );


if ( $id_franquia == 163 ) $id_franquia = 1;
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
   if(trim(d.data_agenda.value) == ""){
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
		alert("O campo " + d.hora.name + " deve ser preenchido!");
		d.hora.focus();
		return false;
	}
	else if(trim(d.empresa.value) == ""){
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
  
function cadastroConsultores() {
  cupom  = open('clientes/lista_consultores.php?id_franquia=<?=$_SESSION['id']?>', 'consultores', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width='+710+',height='+600+',left='+0+', top='+0+',screenX='+0+',screenY='+0+'');  
}
</script>

<form name="form1" method="post" action="#" >
<table align="center"  class="table col65 table-striped table-responsive">
    <thead>
      <tr>
        <th colspan="2"><h4 class="text-center">SISTEMA DE CONTROLE COMERCIAL</h4></th>
      </tr>
    </thead>
    <tbody>
  <?php if( ($_SESSION["id"] == '163') or ($_SESSION["id"] == '46') ){ ?>
  <tr>
  	<td colspan="2"><br><a href="painel.php?pagina1=area_restrita/a_bloqueio_franquia.php">Bloqueio Automático de Franquia</a></td>
  </tr>
  <?php } ?>
 
  <tr>
    <td>Cadastrar Assistente ou Consultor</td>
    <td><input type="button" value="Cadastrar Assistente ou Consultor" onClick="cadastroConsultores()" /></td>
  </tr>
  
  <tr>
    <td>Data do Cadastro</td>
    <td>&nbsp;<?=date('d/m/Y')?></td>
  </tr>

  <tr>
    <td>Assistente</td>
    <td>
    <?php 
        $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' 
        AND tipo_cliente = '1' AND situacao IN('0', '1') ORDER BY situacao, nome";
        $qry = mysql_query($sql_sel);
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
        <option value="<?=$rs['id']?>"><?=$rs['nome']?> - <?=$sit?></option>                                                                            
    <?php } ?>
    </select>
    <!--a href="#" onClick="var myAjax = new Ajax.Updater('agenda_horario_comercial', 'clientes/carrega_horario_comercial.php?assitente='+document.forms[0].assitente.value+'&data_agendamento='+document.forms[0].data_agenda.value+'&id_franquia=< ?=$id_franquia?>', {method: 'get', parameters: 'foo=bar'})">Verificar outros agendamentos</a-->
    
    <!--input type="button" onClick="varm myAjax = new Ajax.Updater('agenda_horario_comercial', 'clientes/carrega_horario_comercial.php?assitente='+document.forms[0].assitente.value+'&data_agendamento='+document.forms[0].data_agenda.value+'&id_franquia=< ?= $id_franquia?>', {method: 'post', parameters: 'foo=bar'})" value="Pesquisar Agendamento Comercial"/ -->
    </td>
  </tr>
  <!--tr>
    	<td class="subtitulodireita">Hor&aacute;rios j&aacute; preenchidos</td>
    	<td class="subtitulopequeno"><div id="agenda_horario_comercial">...</div></td>
  </tr-->
    
  <tr>
    <td>Data do Agendamento</td>
    <td><input name="data_agenda" type="text" id="data_agenda" value="<?=$_REQUEST['data_agenda']?>" size="15" maxlength="10"  onFocus="this.className='boxover'" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onBlur="this.className='boxnormal'" /></td>
  </tr>
  
  <tr>
    <td>Consultor</td>
    <td>
    <?php 
        $sql_sel = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' 
         AND tipo_cliente = '0' AND situacao IN('0', '1') ORDER BY situacao, nome";
        $qry = mysql_query($sql_sel);
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
    <!--input type="button" onClick="var myAjax = new Ajax.Updater('agenda_horario', 'clientes/carrega_horario_consultor.php?id_consultor='+document.forms[0].id_consultor.value+'&data_agendamento='+document.forms[0].data_agenda.value+'&id_franquia=<?=$id_franquia?>', {method: 'post', parameters: 'foo=bar'})" value="Pesquisar Agendamento" /-->
    
      </td>
  </tr>
	<tr>
    	<td>Hor&aacute;rios ocupados</td>
    	<td>
            	<div id="agenda_horario">
                                    ...  
              </div>
		</td>
    </tr>
	<tr>
    	<td>Hora</td>
    	<td>
    		<input name="hora" type="text" id="hora" size="10" maxlength="5" onFocus="this.className='boxover'" onKeyPress="soNumero();" OnKeyUp="Mascara_Hora(this.value)"  />
    </td>
  </tr>
  <tr>
    <td>Empresa</td>
    <td><input name="empresa" type="text" id="empresa" size="75" maxlength="60" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'"/></td>
  </tr>

  <tr>
    <td>Endere&ccedil;o</td>
    <td><input name="endereco" type="text" id="endereco" size="75" maxlength="100" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>

  <tr>
    <td>Fone 1</td>
    <td>
    	<input name="fone1" type="text" id="fone1" size="22" maxlength="13" onFocus="this.className='boxover'" onKeyPress="soNumero(); formatar('##-####-####', this)"/></td>
  </tr>

  <tr>
    <td>Fone 2</td>
    <td>
		<input type="text" name="fone2" id="fone2" size="22" maxlength="13" onFocus="this.className='boxover'" onKeyPress="soNumero(); formatar('##-####-####', this)"/></td>
  </tr>
  <tr>
    <td>Respons&aacute;vel</td>
    <td><input type="text" name="responsavel" id="responsavel" size="75" maxlength="200" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'" /></td>
  </tr>
    <tr>
        <td>Observa&ccedil;&otilde;es</td>
        <td>
            <textarea name="observacao" id="observacao" rows="4" onFocus="this.className='boxover'" onBlur="maiusculo(this); this.className='boxnormal'"></textarea></td>
    </tr>
  <tr>
    <td colspan="3" >&nbsp;</td>
  </tr>
    </tbody>
    <tfoot>
  <tr>
    <td colspan="3" align="center"><input name="Gravar" type="button" value=" Gravar " onClick="valida_dados();" /></td>
  </tr>
    </tfoot>
</table>
<br>
<table border="0" align="center" class="table table-striped table-responsive col65">
    <thead>
	<tr>
		<th colspan="3"><h4 class="text-center">Localizador de Visitas</h4></th>
	</tr>
    </thead>
    <tbody>
	<tr>
		<td width="200">N&uacute;mero da Visita</td>
		<td colspan="2">
    		<input name="protocolo" type="text" id="protocolo" size="10" maxlength="6" onFocus="this.className='boxover'" onKeyPress="soNumero();"/>
            &nbsp;&nbsp;
   		<input type="button" onClick="var myAjax = new Ajax.Updater('lista_registro', 'clientes/a_controle_visitas_listagem.php?protocolo='+document.forms[0].protocolo.value+'&id_franquia=<?=$id_franquia?>', {method: 'post', parameters: 'foo=bar'})" value="Localizar" /></td>
	</tr>
	<tr>
    	<td colspan="3" >
			<div id="lista_registro"></div>
		</td>
    </tr>
    </tbody>
</table>
</form>
<br>

<form name="form2" method="post" action="#" >

<table border="0" align="center" width="700">
	<tr>
		<td colspan="3"><h4 class="text-center"> Relat&oacute;rios Comerciais</h4></td>
	</tr>
<tr>
		<td>&nbsp;</td>
        <td colspan="2">
        <?php
		if (($tipo == "a") || ($tipo == "c") || ($tipo == "d")) {  
			echo "<select name=\"rel_franquia\" class=\"boxnormal\" onchange=\"this.form.submit();\" >";
			if ($tipo <> "b" ) echo "<option value=\"TODAS\" selected>Todas as Franquias</option>";
			
			$sql = "SELECT id, fantasia FROM franquia 
					WHERE sitfrq = 0 AND classificacao <> 'J'
					ORDER BY id";
			$resposta = mysql_query($sql, con);
			while ($array = mysql_fetch_array($resposta)) {
				$franquia   = $array["id"];
				$nome_franquia = $franquia.' - '.$array["fantasia"];
				if ( $franquia == $id_franquia ) $select = 'selected';
				else $select = '';
				echo "<option value=\"$franquia\" $select>$nome_franquia</option>\n";
			}
			echo "</select>";
		}
		else {
			echo $nome_franquia;
			echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia />";
			}
			?>
        </td>
	</tr>
    
	<tr>
		<td width="200">Assistente</td>
		<td colspan="2">
    <?php 
        $sql_sela = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' 
        AND tipo_cliente = '1' ORDER BY situacao, nome";
        $qrya = mysql_query($sql_sela);
        echo "<select name='rel_assistente' id='rel_assistente' style='width:42%'>";
        ?>
        <option value="">Todos</option>
        <?php
        while($rs = mysql_fetch_array($qrya)) { 
        if($rs['situacao'] == "0"){
                  $sit = "Ativo";
              }elseif($rs['situacao'] == "1"){
                  $sit = "Bloqueado";
              }elseif($rs['situacao'] == "2"){
                  $sit = "Cancelado";
              }
        ?>
        <option value="<?=$rs['id']?>"><?=$rs['nome']?> - <?=$sit?></option>                                                                            
    <?php } ?>
    </select>
         </td>
	</tr>
    
	<tr>
		<td width="200">Consultor</td>
		<td colspan="2">
    <?php 
        $sql_selb = "SELECT * FROM cs2.consultores_assistente WHERE id_franquia = '$id_franquia' 
         AND tipo_cliente = '0'  ORDER BY situacao, nome";
        $qryb = mysql_query($sql_selb);
        echo "<select name='rel_consultor' id='rel_consultor' style='width:42%'>";
        ?>
        <option value="">Todos</option>
        <?php
        while($rs = mysql_fetch_array($qryb)) { 
              if($rs['situacao'] == "0"){
                  $sit = "Ativo";
              }elseif($rs['situacao'] == "1"){
                  $sit = "Bloqueado";
              } elseif($rs['situacao'] == "2"){
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
    </td>
	</tr>
    
	<tr>
		<td width="200">
        	<select name="tipo_periodo" onchange="rel_datai.focus();">
            	<option value="dtAge" selected="selected" >Per&iacute;odo de Agendamento</option>
            	<option value="dtCad" >Per&iacute;odo de Cadastro</option>
			</select>
		</td>
		<td colspan="2">
    		<input name="rel_datai" type="text" id="rel_datai" size="15" maxlength="10" onFocus="this.className='boxover'" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onBlur="this.className='boxnormal'" />
            &nbsp;&nbsp;&nbsp;&agrave;&nbsp;&nbsp;&nbsp;
            <input name="rel_dataf" type="text" id="rel_dataf" size="15" maxlength="10" onFocus="this.className='boxover'" onKeyPress="return MM_formtCep(event,this,'##/##/####');" onBlur="this.className='boxnormal'" />
         </td>
	</tr>
	
	<tr>
		<td colspan="3" align="center"><input name="pesquisar" type="button" value=" Pesquisar " onClick="pesquisa_dados();" /></td>
	</tr>
</table>
</form>
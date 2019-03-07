<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php
require "connect/sessao.php";

if($_REQUEST['id_conta']){
  $acao = "2";
} else {
  $acao = "1";
}

function mascara_data($p_data_banco)
{
 	   $dia = substr($p_data_banco, 8,2);   
	   $mes = substr($p_data_banco, 5,2);   
	   $ano = substr($p_data_banco, 0,4);   
	   $data_view.=$dia;
	   $data_view.="/";
	   $data_view.=$mes;
	   $data_view.="/";
	   $data_view.=$ano;
	   return ($data_view);
} 

$codigo = $_REQUEST['codigo'];
$sql = "
SELECT
  franquia.razaosoc AS franquia,
  franquia.id AS id_franquia,
  cadastro.razaosoc AS cliente,
  cadastro.nomefantasia,
  cadastro.codloja AS cod_loja
FROM
  logon
  INNER JOIN cadastro ON logon.codloja = cadastro.codloja
  INNER JOIN franquia ON cadastro.id_franquia = franquia.id
WHERE
  logon.logon LIKE '$codigo%'";
$qry = mysql_query($sql,$con);
if(mysql_num_rows($qry) == "0"){ ?>

<script>
  alert("O cliente nao foi encotrado ");
  window.location.href="../php/painel.php?pagina1=area_restrita/autorizacao_conta.php";
</script>

<?php
  exit;
}
$franquia = mysql_result($qry,0,'franquia');
$id_franquia = mysql_result($qry,0,'id_franquia');
$cliente  = mysql_result($qry,0,'cliente');
$cod_loja = mysql_result($qry,0,'cod_loja');
$fantasia = mysql_result($qry,0,'nomefantasia');




$sel = "SELECT 
  f.fantasia,  ac.data,  ac.banco,  ac.agencia,  ac.conta,  ac.titular,
  ac.status,  ac.cpf_cnpj,  l.logon, c.nomefantasia, ac.id AS id_conta, ac.tpconta
FROM
  autorizacao_conta ac
  INNER JOIN cadastro c ON ac.cod_loja = c.codloja
  INNER JOIN franquia f ON f.id = ac.id_franquia
  INNER JOIN logon l ON c.codloja = l.codloja
WHERE ac.cod_loja = '$cod_loja'";
$qry = mysql_query($sel,$con);

if($_REQUEST['acao'] == "2"){
$sel = "SELECT * FROM autorizacao_conta WHERE id = '{$_REQUEST['id_conta']}'";
$q = mysql_query($sel,$con);
$banco = mysql_result($q,0,'banco');
$agencia = mysql_result($q,0,'agencia');
$tpconta = mysql_result($q,0,'tpconta');
$conta = mysql_result($q,0,'conta');
$titular = mysql_result($q,0,'titular');
$cpf_cnpj = mysql_result($q,0,'cpf_cnpj');
$status = mysql_result($q,0,'status');
  
}
?>

<script>

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

function isTeclaRelevante(key) {
	return (key == 8)||(key == 46)||(key == 88)||(key>=48&&key<=57)||(key>=96&&key<=105);
}

function setaTextoAjuda(txt) {
	if(document.getElementById('textoAjuda')) document.getElementById('textoAjuda').innerHTML = txt + ' ' ;
}
function getTeclaPressionada(evt) {
	if(typeof(evt)=='undefined') evt = window.event; return(evt.keyCode ? evt.keyCode : (evt.which ? evt.which : evt.charCode));
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

function confirma(){
  f = document.frm_conta;     
 if(document.frm_conta.banco.value == ""){
		alert("Falta informar o banco !");
		f.banco.focus();
		return false;
	}
  if(f.agencia.value == ""){
		alert("Falta informar o agencia !");
		f.agencia.focus();
		return false;
	}
  if(f.conta.value == ""){
		alert("Falta informar o conta !");
		f.conta.focus();
		return false;
	}
  if(f.titular.value == ""){
		alert("Falta informar o titular !");
		f.titular.focus();
		return false;
	}
  if(f.cpf_cnpj.value == ""){
		alert("Falta informar o cpf / cnpj !");
		f.cpf_cnpj.focus();
		return false;
	}   
  
  f.action = "../php/painel.php?pagina1=area_restrita/autoriza_grava.php&acao=<?=$acao?>";
	f.submit();
  
}

</script>

<style type="text/css">
h1 {font-size: 140%;}
form {margin: 30px 50px 0;}
form input, select {
	font-family: Arial;
	font-size: 8pt;
}
form input#numero, form input#uf, form input#cep {float: left; width: 75px;}
address {clear: both; padding: 30px 0;}

</style>

<form name="frm_conta" method="post" action="#">
<input type="hidden" name="cod_loja" value="<?=$cod_loja?>"/>
<input type="hidden" name="codigo" value="<?=$codigo?>"/>
<input type="hidden" name="id_franquia" value="<?=$id_franquia?>"/>
<input type="hidden" name="id_conta" value="<?=$_REQUEST['id_conta']?>"/>

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF"> 			
    
    <tr>
    <td colspan="2" class="titulo" align="center"><font color="red"><b>
    <div style="font-size:20px">CADASTRAMENTO DE CONTA DE TERCEIROS<br></td>
  </tr>
    <tr height="28px"><td width="25%" class="subtitulodireita">C&oacute;digo</td><td class="subtitulopequeno"><?=$codigo?></td></tr>
    <tr height="28px"><td class="subtitulodireita">Razão Social</td><td class="subtitulopequeno"><?=$cliente?></td></tr>
    <tr height="28px"><td class="subtitulodireita">Nome Fantasia</td><td class="subtitulopequeno"><?=$fantasia?></td></tr>
    <tr height="28px"><td class="subtitulodireita">Franquia</td><td class="subtitulopequeno"><?=$franquia?></td></tr>
    <tr height="28px"><td class="subtitulodireita">N&deg; do Banco</td><td class="subtitulopequeno"><input type="text" name="banco" value="<?=$banco?>"  maxlength="3" size="4"/></td></tr>
    <tr height="28px">
    	<td class="subtitulodireita">Ag&ecirc;ncia</td>
        <td class="subtitulopequeno">
        	<input type="text" name="agencia" value="<?=$agencia?>"
			maxlength="6" 
			onKeyDown="return filtraTeclas(event);"
			onKeyPress="return filtraCaracteres(event);"
			onKeyUp="if(isTeclaRelevante(getTeclaPressionada(event))){formatar('####-#', this);}"
			onChange="formatar('####-#', this);"
			 />
        </td>
	</tr>
    <tr height="28px">
    	<td class="subtitulodireita">Tipo da Conta</td>
    	<td class="subtitulopequeno">
        	<input <?=$desabilita?> type="radio" name="tpconta" id="tpconta" value="1" <?php if ($tpconta == 1) echo "checked"; ?> />Conta Corrente<br />
            <input <?=$desabilita?> type="radio" name="tpconta" id="tpconta" value="2" <?php if ($tpconta == 2) echo "checked"; ?> />Poupan&ccedil;a        </td> 
    
    
    
    <tr height="28px"><td class="subtitulodireita">Conta</td><td class="subtitulopequeno">
    	<input type="text" name="conta" 
        	value="<?=$conta?>" 
            maxlength="20" 
         	onKeyDown="return filtraTeclas(event);"
            onKeyPress="return filtraCaracteres(event);"
            onKeyUp="if(isTeclaRelevante(getTeclaPressionada(event))){formataMascara('################-@', this);}"
            onChange="formataMascara('################-@', this);"
           /></td>
	</tr>
    <tr height="28px"><td class="subtitulodireita">Titular</td><td class="subtitulopequeno"><input type="text" name="titular" value="<?=$titular?>"  maxlength="50" size="50"/></td></tr>
    <tr height="28px"><td class="subtitulodireita">CPF / CNPJ</td><td class="subtitulopequeno"><input type="text" name="cpf_cnpj" value="<?=$cpf_cnpj?>" maxlength="14"/></td></tr>
    
    <?php if($_REQUEST['id_conta']) { ?>
    <tr height="28px"><td class="subtitulodireita">Status</td><td class="subtitulopequeno">
    	Ativo<input type="radio" name="status" value="A" <?php if($status == "A") { ?> checked <?php } ?> />&nbsp;&nbsp;&nbsp;
        Inativo<input type="radio" name="status" value="I" <?php if($status == "I") { ?> checked <?php } ?> />
    </td></tr>
    <?php } ?>
    
    <tr height="28px"><td class="subtitulodireita"></td><td class="subtitulopequeno">
      <input type="button" value="Confirma" onClick="confirma()">
    </td></tr>
    
<tr><td colspan="8">&nbsp;</td></tr>
</form>	
<tr>
  <td colspan="8">

<table border="0" align="center" width="100%" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF"> 			
<tr height="20" bgcolor="#B6CBF6" align="left">
    <td >Banco</td>
    <td>Ag&ecirc;ncia</td>
    <td colspan="2">Conta</td>
    <td>Titular</td>
    <td>CPF / CNPJ</td>
    <td>Libera&ccedil;&atilde;o</td>
    <td>Status</td>
    <td>&nbsp;</td>
</tr>
<?php 
$cont=0;
while($rs = mysql_fetch_array($qry)) {
$cont++; 
      if($rs['status'] == "A"){
        $status = "Ativo";
      } else {
        $status = "Inativo";
      }
      
      if($cont % 2 == 0){
        $cor = "#CCCCCC";
      } else {
        $cor = "#FFFFFF";
      }
?>
 <tr height="20" align="left" bgColor="<?=$cor?>">
    <td><?=$rs['banco']?></td>
    <td><?=$rs['agencia']?></td>
    <td><?php  if ($rs['tpconta']==1 ) echo "C/C";
			else echo "C/P";
	?></td>
    <td><?=$rs['conta']?></td>
    <td><?=$rs['titular']?></td>
    <td><?=$rs['cpf_cnpj']?></td>
    <td><?=mascara_data($rs['data'])?></td>
    <td><?=$status?></td>
    <td><a href="../php/painel.php?pagina1=area_restrita/autoriza_conta_listagem.php&id_conta=<?=$rs['id_conta']?>&acao=2&codigo=<?=$codigo?>"><img src="area_restrita/b_edit.png" border="0"></a></td>
</tr>
<?php } ?>

</table>			

</td></tr>

<tr><td colspan="8" align="center"><input type="button" value="Retorna" onclick="document.location='../php/painel.php?pagina1=area_restrita/autorizacao_conta.php&codigo=<?=$codigo?>'">


</td></tr>

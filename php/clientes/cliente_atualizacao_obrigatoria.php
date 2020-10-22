<?php
require "connect/sessao.php";

function limpaCaracter($p_parametro){
	$p_parametro = str_replace(" ","",$p_parametro);
	$p_parametro = str_replace("-","",$p_parametro);
	$p_parametro = str_replace("(","",$p_parametro);
	$p_parametro = str_replace(")","",$p_parametro);
	$p_parametro = str_replace("-","",$p_parametro);
	$p_parametro = str_replace("/","",$p_parametro);
	$p_parametro = str_replace(".","",$p_parametro);
	$p_parametro = preg_replace("/[^0-9]/", "", $p_parametro);
	return $p_parametro;
}

if($_REQUEST['alterar_cliente'] == "S"){
	$cpfcnpj     = $_REQUEST['cpfcnpj'];
	if ( strlen($cpfcnpj > 11 ) ) $Tipo = '1';

	$codloja     = $_REQUEST['codloja'];
	$codigo      = $_REQUEST['codigo'];
	$endereco    = $_REQUEST['endereco'];
	$numero_endereco = $_REQUEST['numero_endereco'];
	$complemento = $_REQUEST['complemento'];
	$cidade      = $_REQUEST['cidade'];
	$bairro      = $_REQUEST['bairro'];
	$uf          = $_REQUEST['uf'];
	$cep         = limpaCaracter($_REQUEST['cep']);
	$telefone    = limpaCaracter($_REQUEST['telefone']);
	$celular     = limpaCaracter($_REQUEST['celular']);
	$fone_res    = limpaCaracter($_REQUEST['fone_res']);
	$email       = $_REQUEST['email'];
	$cnpj_empresa = $_REQUEST['cnpj_empresa'];
	echo $cnpj_empresa;

	$sql = "UPDATE cs2.cadastro SET
	end             = '$endereco',
	numero          = '$numero_endereco',
	complemento     = '$complemento',
	cidade          = '$cidade',
	bairro          = '$bairro',
	uf              = '$uf',
	cep             = '$cep',
	fone            = '$telefone',
	celular         = '$celular',
	fone_res        = '$fone_res',
	email           = '$email'
	WHERE codloja = '$codloja'";
	$res = mysql_query ($sql, $con);

	//VERIFICA SE TEM O TELEFONE COMERCIAL ANTES DE GRAVAR
	$sql_ver = "SELECT * FROM cs2.cadastro_telefones WHERE codloja = '$codloja' AND telefone = '$telefone'";
	$qry_ver = mysql_query($sql_ver, $con);
	$total_telefone = mysql_num_rows($qry_ver);
	if($total_telefone == 0){
		$sql_insere = "INSERT INTO cs2.cadastro_telefones(codloja, tipo_fone, telefone)VALUES('$codloja', '1', '$telefone')";
		$qry_insere = mysql_query ($sql_insere, $con);
	}

	//VERIFICA SE TEM O TELEFONE CELULAR ANTES DE GRAVAR
	$sql_ver = "SELECT * FROM cadastro_telefones WHERE codloja = '$codloja' AND telefone = '$celular'";
	$qry_ver = mysql_query($sql_ver, $con);
	$total_celular = mysql_num_rows($qry_ver);
	if($total_celular == 0){
		$sql_insere = "INSERT INTO cadastro_telefones (codloja, tipo_fone, telefone)VALUES('$codloja', '2', '$celular')";
		$qry_insere = mysql_query ($sql_insere, $con);
	}

	//VERIFICA SE TEM O TELEFONE RESIDENCIAL ANTES DE GRAVAR
	$sql_ver = "SELECT * FROM cadastro_telefones WHERE codloja = '$codloja' AND telefone = '$fone_res'";
	$qry_ver = mysql_query($sql_ver, $con);
	$total_res = mysql_num_rows($qry_ver);
	if($total_res == 0){
		$sql_insere = "INSERT INTO cadastro_telefones (codloja, tipo_fone, telefone)VALUES('$codloja', '3', '$fone_res')";
		$qry_insere = mysql_query ($sql_insere, $con);
	}
	include("../funcoes/conexao.php");

	include("cliente_grava_nomes.php");

	$nome = '';

	grava_nomes($cpfcnpj, $Tipo, $nome, $data_nascimento, $numero_titulo, $endereco, $id_tipo_log, $numero_endereco, $complemento, $bairro, $cidade, $uf, $cep, $email, $nome_mae, $telefone, $celular, $fone_res2, $fone_res, $empresa_trabalha, $cargo, $endereco_empresa, $rg, $nome_referencia, $fax, $fone_empresa, $cnpj_empresa);
	?>
	<script>window.location.href="painel.php?pagina1=clientes/a_cons_id.php&codigo=<?=$codigo?>";</script>
	<?php
	exit;
}


if($_REQUEST['codigo']){
    if ( strlen($_REQUEST['codigo'] > 5)){
        $sql_sel = "SELECT * FROM cs2.logon WHERE mid(logon,1,6)='{$_REQUEST['codigo']}' ";
    }else{
        $sql_sel = "SELECT * FROM cs2.logon WHERE mid(logon,1,5)='{$_REQUEST['codigo']}' ";
    }
	$res_sel = mysql_query($sql_sel, $con);
	$matriz_sel = mysql_fetch_array($res_sel);
	$codloja = $matriz_sel['codloja'];
}elseif($_REQUEST['cnpj']){   
	$cnpj = limpaCaracter($_REQUEST['cnpj']);
	$sql_sel = "SELECT * FROM cs2.cadastro WHERE insc = '$cnpj' ";
	$res_sel = mysql_query($sql_sel, $con);
	$matriz_sel = mysql_fetch_array($res_sel);
	$codloja = $matriz_sel['codloja'];  
}elseif($_REQUEST['id']){
	$codloja = $_REQUEST['id'];
}

//if ( $_REQUEST['codigo'] == 90481 ){
    echo "<pre>".$sql_sel;
    die;
//}

if ($tipo == "b") $frq = "AND a.id_franquia='{$_SESSION['usuario']}'";
else $frq = "";

$comando = "SELECT 
				a.renegociacao_tabela, a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, 
				a.bairro, a.end, a.cep, a.fone, a.fax, a.email, a.tx_mens, a.id_franquia, 
				date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit, a.ramo_atividade,
				a.obs, a.mensalidade_solucoes, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, 
				a.cpfsocio2, a.emissao_financeiro, a.vendedor, mid(b.logon,1,locate('S',b.logon)-1) as logon, 
				mid(b.logon,locate('S',b.logon)+1,10) as senha, a.classe, a.banco_cliente, a.agencia_cliente,
			    a.conta_cliente, a.cpfcnpj_doc, a.nome_doc, a.tpconta, a.complemento,
				a.inscricao_estadual, a.cnae_fiscal, a.inscricao_municipal, 
				a.inscricao_estadual_tributario, a.numero, a.tipo_cliente, a.insc
			FROM cs2.cadastro a
			inner join cs2.logon b on a.codloja=b.codloja
			inner join cs2.situacao d on a.sitcli=d.codsit
			where a.codloja='$codloja' $frq limit 1";


//if ( $_REQUEST['codigo'] == 90481 ){
    echo "<pre>".$comando;
    die;
//}
$res = mysql_query ($comando, $con);
$total = mysql_num_rows($res);
$matriz = mysql_fetch_array($res);
//tratamento para agencia e conta corrente
$cpfcnpj = $matriz['insc'];
$agencia_cliente = $matriz['agencia_cliente'];
$agencia_cliente = strtoupper($agencia_cliente);

///////////////////////////////////////////////////////////////	
$_comando = "SELECT 
				a.renegociacao_tabela, a.codloja, a.razaosoc, a.insc, a.nomefantasia, a.uf, a.cidade, 
				a.bairro, a.end, a.cep, a.fone, a.fax, a.email, a.tx_mens, a.id_franquia, 
				date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit, a.ramo_atividade, 
				a.obs, a.mensalidade_solucoes, a.celular, a.fone_res, a.socio1, a.socio2, a.cpfsocio1, 
				a.cpfsocio2, a.emissao_financeiro, a.vendedor, mid(b.logon,1,5) as logon, 
				mid(b.logon,7,10) as senha, a.classe, a.banco_cliente, a.agencia_cliente,
				a.conta_cliente, a.cpfcnpj_doc, a.nome_doc, a.tpconta,  a.complemento,
				a.inscricao_estadual, a.cnae_fiscal, a.inscricao_municipal, 
				a.inscricao_estadual_tributario, a.numero
			FROM cs2.cadastro a
			inner join cs2.logon b on a.codloja=b.codloja
			inner join cs2.situacao d on a.sitcli=d.codsit
			where a.codloja='$codloja' $frq limit 1";
$_res = mysql_query ($_comando, $con);
$_total = mysql_num_rows($_res);

//SELECCIONA OS TELEFONES
$sql_fone_1 = "SELECT * FROM cs2.cadastro_telefones WHERE codloja = '$codloja' AND tipo_fone = '1' ORDER BY id";
$qry_fone_1 = mysql_query($sql_fone_1, $con);
$total_1 = mysql_num_rows($qry_fone_1);

$sql_fone_2 = "SELECT * FROM cs2.cadastro_telefones WHERE codloja = '$codloja' AND tipo_fone = '2' ORDER BY id";
$qry_fone_2 = mysql_query($sql_fone_2, $con);
$total_2 = mysql_num_rows($qry_fone_2);

$sql_fone_3 = "SELECT * FROM cs2.cadastro_telefones WHERE codloja = '$codloja' AND tipo_fone = '3' ORDER BY id";
$qry_fone_3 = mysql_query($sql_fone_3, $con);
$total_3 = mysql_num_rows($qry_fone_3);

if( ($total == 0) or ($_total == 0) ){ ?>
  <script>
        alert("Não foi encontrado nenhum cliente ! ");
        window.location.href="painel.php?pagina1=clientes/a_formconsulta.php";
  </script>
<?php } 
///////////////////////////////////////////////////////////////		

function mascaraTel($p_telefone){
	if ($p_telefone == '') {
		return ('');	   
	} else {

		if ( strlen($p_telefone)==10){
		   $a = substr($p_telefone, 0,2);   
		   $b = substr($p_telefone, 2,4);   
		   $c = substr($p_telefone, 6,4);   
		}else{
		   $a = substr($p_telefone, 0,2);   
		   $b = substr($p_telefone, 2,5);   
		   $c = substr($p_telefone, 7,4);   
		}
		$telefone_mascarado  = "(";
		$telefone_mascarado .= $a;
		$telefone_mascarado .= ")&nbsp;";
		$telefone_mascarado .= $b;
		$telefone_mascarado .= "-";
		$telefone_mascarado .= $c;
		return ($telefone_mascarado);
	}
}

function mascaraCepAtu($p_cep_banco){
 	   $a = substr($p_cep_banco, 0,2);   
	   $b = substr($p_cep_banco, 2,3);   
	   $c = substr($p_cep_banco, 5,3);   
	   $cep_view.=$a;
	   $cep_view.=".";
	   $cep_view.=$b;
	   $cep_view.="-";
	   $cep_view.=$c;
	   return ($cep_view);
}

$cep      = limpaCaracter($matriz['cep']);
$cep      = mascaraCepAtu($cep);
$fone     = limpaCaracter($matriz['fone']);
$fone     = mascaraTel($fone);
$celular  = limpaCaracter($matriz['celular']); 
$celular  = mascaraTel($celular);
$fone_res = limpaCaracter($matriz['fone_res']); 
$fone_res = mascaraTel($fone_res);

$conta_cliente = 100000000000 + $matriz['conta_cliente'];
$conta_cliente = substr($conta_cliente,1,10).'-'.substr($conta_cliente,11,1);

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

	if($data_view == '00/00/0000'){
		$data_view = "";
	}
}
?>
<script type="text/javascript" src="../../../web_control/js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../../../web_control/js/jquery.maskedinput-1.1.1.js"></script>
<script type="text/javascript" src="../../../web_control/js/funcoesJavaDiversas.js"></script>

<script language="javascript">

jQuery(function($){
   $("#id_cep").mask("99999-999");
});
  
function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espaço em branco
 
function alterar(){
 	frm = document.form;
	
  if(trim(frm.endereco.value) == ""){
		alert("Falta informar o endereco !");
		frm.endereco.focus();
		return false;
	}
  if(trim(frm.numero_endereco.value) == ""){
		alert("Falta informar o Numero !");
		frm.numero_endereco.focus();
		return false;
	}
  if(trim(frm.cidade.value) == ""){
		alert("Falta informar o Cidade !");
		frm.cidade.focus();
		return false;
	}
  if(trim(frm.bairro.value) == ""){
		alert("Falta informar o Bairro !");
		frm.bairro.focus();
		return false;
	}
  if(trim(frm.cep.value) == ""){
		alert("Falta informar o cep !");
		frm.cep.focus();
		return false;
	}
  if(trim(frm.telefone.value) == ""){
		alert("Falta informar o Fone Comercial !");
		frm.telefone.focus();
		return false;
	}
  if( (frm.telefone.value == "(00) 0000-0000") || (frm.telefone.value == "(11) 1111-1111") || (frm.telefone.value == "(22) 2222-2222") ||
      (frm.telefone.value == "(33) 3333-3333") || (frm.telefone.value == "(44) 4444-4444") || (frm.telefone.value == "(55) 5555-5555") ||
      (frm.telefone.value == "(66) 6666-6666") || (frm.telefone.value == "(77) 7777-7777") ||( frm.telefone.value == "(88) 8888-8888") ||
      (frm.telefone.value == "(99) 9999-9999") ){
		alert("Fone Comercial inválido !");
		frm.telefone.focus();
		return false;
	}
  if(trim(frm.celular.value) == ""){
		alert("Falta informar o Fone Celular !");
		frm.celular.focus();
		return false;
	}
  if( (frm.celular.value == "(00) 0000-0000") || (frm.celular.value == "(11) 1111-1111") || (frm.celular.value == "(22) 2222-2222") ||
      (frm.celular.value == "(33) 3333-3333") || (frm.celular.value == "(44) 4444-4444") || (frm.celular.value == "(55) 5555-5555") ||
      (frm.celular.value == "(66) 6666-6666") || (frm.celular.value == "(77) 7777-7777") ||( frm.celular.value == "(88) 8888-8888") ||
      (frm.celular.value == "(99) 9999-9999") ){
		alert("Fone Celular inválido !");
		frm.celular.focus();
		return false;
	}
  if(trim(frm.fone_res.value) == ""){
		alert("Falta informar o Fone Residencial!");
		frm.fone_res.focus();
		return false;
	}
  if( (frm.fone_res.value == "(00) 0000-0000") || (frm.fone_res.value == "(11) 1111-1111") || (frm.fone_res.value == "(22) 2222-2222") ||
      (frm.fone_res.value == "(33) 3333-3333") || (frm.fone_res.value == "(44) 4444-4444") || (frm.fone_res.value == "(55) 5555-5555") ||
      (frm.fone_res.value == "(66) 6666-6666") || (frm.fone_res.value == "(77) 7777-7777") ||( frm.fone_res.value == "(88) 8888-8888") ||
      (frm.fone_res.value == "(99) 9999-9999") ){
		alert("Fone Residencial inválido !");
		frm.fone_res.focus();
		return false;
	}
  if(trim(frm.email.value) == ""){
		alert("Falta informar o e-mail !");
		frm.email.focus();
		return false;
	} 
  
  frm.action = 'painel.php?pagina1=clientes/cliente_atualizacao_obrigatoria.php';
	frm.submit();
}
</script>

<script type="text/javascript">
/* Máscaras ER */
function xmascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("xexecmascara()",1)
}
function xexecmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que não é digito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca paranteses em volta dos dois primeiros digitos
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
	id('id_telefone').onkeypress = function(){
		xmascara( this, mtel );
	}
	id('id_fone_res').onkeypress = function(){
		xmascara( this, mtel );
	}
	document.form.endereco.focus(); 
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

<form method="post" action="#" name="form">
<input type="hidden" name="codloja" value="<?=$codloja?>">
<input type="hidden" name="codigo" value="<?=$matriz['logon']?>">
<input type="hidden" name="alterar_cliente" value="S">

<table border="0" width="700px" align="center" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
  <tr>
    <td colspan="2" class="titulo" align="center"><font color="red"><b>

    <div style="font-size:20px">ATUALIZA&Ccedil;&Atilde;O DE DADOS<br>
    <div style="font-size:15px">(OBRIGAT&Oacute;RIA)</font></div>


    <br><div style="font-size:14px" align="left"><b>Por gentileza, gostaria de confirmar seus dados !! </b></div><br></td>
  </tr>
  
  <tr height="28px">

    <td class="subtitulodireita">C&oacute;digo</td>



    <td class="subtitulopequeno"><b><?=$matriz['logon']?></b></td>
  </tr>
  
  <tr height="28px">
    <td class="subtitulodireita">Qual sua Raz&atilde;o Social ?</td>
    <td class="subtitulopequeno"><b><?=$matriz['razaosoc']?></b></td>
  </tr>
  
  <tr height="28px">
    <td class="subtitulodireita">Qual o de nome Fantasia ?</td>
    <td class="subtitulopequeno"><b><?=$matriz['nomefantasia']?></b></td>
  </tr>
  <tr height="28px">
    <td class="subtitulodireita" width="20%">Confirme seu Endere&ccedil;o ? </td>
    <td class="subtitulopequeno" width="80%"><input name="endereco" id="id_endereco" type="text" style="width:95%" value="<?php echo $matriz['end']; ?>" maxlength="200" onKeyUp="upperCase(this.id)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />(*)</td>
  </tr>
  
    <tr height="28px">
    <td class="subtitulodireita">N&uacute;mero ?</td>
    <td class="subtitulopequeno"><input name="numero_endereco" type="text" style="width:20%" value="<?=$matriz['numero']?>"  maxlength="10" onFocus="this.className='boxover'" /(*)></td>
  </tr>

   <tr height="28px">
    <td class="subtitulodireita">Complemento ?</td>
    <td class="subtitulopequeno"><input name="complemento" id="id_complemento" type="text" style="width:95%" value="<?=$matriz['complemento']?>" onFocus="this.className='boxover'" onKeyUp="upperCase(this.id)"/></td>
  <tr height="28px">
    <td class="subtitulodireita">Bairro ?</td>
    <td class="subtitulopequeno"><input name="bairro" id="id_bairro" type="text" style="width:95%" value="<?php echo $matriz['bairro']; ?>" maxlength="200" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyUp="upperCase(this.id)"/>(*)</td>
  </tr>  
  </tr>
    <tr height="28px">
    <td class="subtitulodireita">Cidade ?</td>
    <td class="subtitulopequeno"><input name="cidade" type="text" id="id_cidade"  style="width:95%" value="<?php echo $matriz['cidade']; ?>" maxlength="30" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyUp="upperCase(this.id)"/>(*)</td>
  </tr>
  <tr height="28px">
    <td class="subtitulodireita">UF ?</td>
    <td class="subtitulopequeno" style="width:20%">
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
    </select>	</td>
  </tr>

  <tr height="28px">
    <td class="subtitulodireita">CEP ?</td>
    <td class="subtitulopequeno"><input name="cep" type="text" id="id_cep" style="width:20%"  value="<?=$cep?>" maxlength="10" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />(*)</td>
  </tr>
  
  <tr height="28px">
    <td class="subtitulodireita">Fone Comercial ?</td>
    <td class="subtitulopequeno">
      <table border="0" width="100%">
      <tr valign="top">
      <td width="30%"><input name="telefone" id="id_telefone" type="text" style="width:80%"  value="<?=$fone?>" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />(*)</td>
      <td width="70%"><table boder="1">
      <?php while($rs_1 = mysql_fetch_array($qry_fone_1)) { ?>
              <?php 
                $telefone = limpaCaracter($rs_1['telefone']);
                echo "<tr><td><b>".mascaraTel($telefone)."</b></td></tr>";                 
              ?>
      <?php } ?>
      </table>
      </td></tr></table>
    </td>
  </tr>

	<tr height="28px">
		<td class="subtitulodireita">Fone Celular ?</td>
		<td class="subtitulopequeno">
			<table border="0" width="100%">
				<tr valign="top">
					<td width="30%">
                        <input name="celular" id="celular" type="text"  style="width:80%" value="<?=$celular?>" maxlength="16" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />(*)
                        
					</td>
					<td width="70%">
						<table boder="1">
							<?php while($rs_2 = mysql_fetch_array($qry_fone_2)) {  
									$celular = limpaCaracter($rs_2['telefone']);
									echo "<tr><td><b>".mascaraTel($celular)."</b></td></tr>";   
							} ?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
  
  <tr height="28px">
    <td class="subtitulodireita">Fone Residencial ?</td>
    <td class="subtitulopequeno">
    <table border="0" width="100%">
      <tr valign="top">
      <td width="30%"><input name="fone_res" type="text" id="id_fone_res" style="width:80%" value="<?=$fone_res?>" maxlength="15" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />(*)
          <td width="70%"><table boder="1">
          <?php  while($rs_3 = mysql_fetch_array($qry_fone_3)) { ?>
              <?php 
                $fone_res = limpaCaracter($rs_3['telefone']);
                echo "<tr><td><b>".mascaraTel($fone_res)."</b></td></tr>"; 
              ?>
      <?php } ?>
    </table>
      </td></tr></table>
    </td>
  </tr>
  
  <tr height="28px">
    <td class="subtitulodireita">Confirme seu E-mail ?</td>
    <td class="subtitulopequeno"><input name="email" class="h2" type="text" style="width:95%" value="<?php echo strtolower($matriz['email']); ?>" maxlength="200" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" />(*)</td>
  </tr>  
  

  <tr height="28px" align="left"><td colspan="2"><p><b>Obrigado pela confirma&ccedil;&atilde;o, vamos continuar com o atendimento. O que o Sr(a) deseja ? </b></td></tr>
  
  <tr height="28px" align="center"><td colspan="2"><p>Campos com (*) s&atilde;o de preenchimento obrigat&oacute;rio</td></tr>

  
  <tr height="28px" align="center"><td colspan="2"><p>
  	<input type="hidden" name="cpfcnpj" value="<?=$cpfcnpj?>" />
  	<input name="Atualizar o Cadastro do Cliente" type="button" value="Atualizar o Cadastro do Cliente" onclick="alterar()" style="cursor:pointer; height:30px; font-size: 13px;" /></td></tr>
  </table>
</form>
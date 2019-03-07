<?php
include("functions.php");

if (!isset($_POST['empresa'])) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      WebControl Empresas
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="../sct/geral.css" rel="stylesheet" type="text/css">
		<script type="text/javascript"><!--
/*function gid(id) {
	return document.getElementById(id);
}*/
function carregar() {
	gid('uf').value='<?= addslashes($_COOKIE['uf']) ?>';	
}

function confirma(){
	frm = document.form1;
	frm.action = 'equifax.php';
	frm.submit();
}

function valida() {
	frm = document.form1;	
	if(frm.empresa.value == ""){
		alert('Falta informar a Empresa');
		frm.empresa.focus();
		return false
	}	
	if(frm.rua.value == ""){
		alert('Falta informar a Rua/AV');
		frm.rua.focus();
		return false
	}
	if(frm.nr.value == ""){
		alert('Falta informar o Número');
		frm.nr.focus();
		return false
	}
	if(frm.bairro.value == ""){
		alert('Falta informar o Bairro');
		frm.bairro.focus();
		return false
	}
	if(frm.uf.value == ""){
		alert('Falta informar o Estado');
		frm.uf.focus();
		return false
	}
	if(frm.cidade.value == ""){
		alert('Falta informar a Cidade');
		frm.cidade.focus();
		return false
	}
	if(frm.cnpj.value == ""){
		alert('Falta informar o CNPJ');
		frm.cnpj.focus();
		return false
	}
	if(frm.socio1.value == ""){
		alert('Falta informar o Sócio');
		frm.socio1.focus();
		return false
	}
	if(frm.cpf1.value == ""){
		alert('Falta informar o CPF');
		frm.cpf1.focus();
		return false
	}
	confirma();
}

// --></script>
  </head>
  <body style="margin:0" onLoad="carregar();">
    <hr>
    <table width="550" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr valign="top">
        <td width="199" height="75">
          <img src="https://www.webcontrolempresas.com.br/inform/boleto/imgs/web_control_azul.png" width="175" alt="Logo WebControl">        </td>
        <td width="351" height="75" valign="middle">
          <b>Tecnologia, Automação, Consultas e Sites</b>        </td>
      </tr>
    </table>
    <table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
          <td width="550" bgcolor="#999999" valign="top">
            <font color="#FFFFFF" face="Arial,Helvetica" size="-1"><b>Formulário</b></font>
          </td>
        </tr>
        <tr>
          <td bgcolor="#EBEBEB" valign="top">
            <br>
            <div align="center">
              <table align="left" border="0" cellpadding="0" cellspacing="0" width="550">
                <tbody>
                  <tr>
                    <td valign="top" width="653">
                      <table align="center" border="0" cellpadding="0" cellspacing="0" width="97%">
                        <tbody>
                          <tr>
                            <td valign="top">
                              <div align="center">
                                <br>
                                <b><font size="+1">Termo de Negativa&ccedil;&atilde;o na Equifax e Outros Bancos de Dados</font></b><br>
                                <br>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                  <tbody>
                                    <tr>
                                      <td width="10">&nbsp;
                                        
                                      </td>
                                      <td>
                                        <form action="#" method="post" name="form1" id="form1">
                                          <div align="left">
                                            Utilize o formulário abaixo para gerar o Termo.<br>
                                          </div>
                                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                              <tr>
                                                <td width="71">
                                                  <div align="right">
                                                    Empresa:                                                  </div>                                                </td>
                                                <td>&nbsp;                                                </td>
                                                <td width="446" height="25">
                                                  <input name="empresa" class="campos" id="empresa" size="64" maxlength="70" type="text">                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Rua/Av :                                                  </div>                                                </td>
                                                <td width="3">&nbsp;                                                </td>
                                                <td height="25" >
                                                  <input name="rua" class="campos" id="endereco3" size="45" maxlength="60" type="text"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nr: <input name="nr" class="campos" id="nr" size="6" maxlength="6" type="text">&nbsp;&nbsp;                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Compl.:                                                  </div>                                                </td>
                                                <td>&nbsp;                                                </td>
                                                <td height="25">
                                                  <input name="compl" class="campos"  size="15" maxlength="12" type="text"> &nbsp;&nbsp;Bairro: <input name="bairro" class="campos" id="bairro" size="20" maxlength="18" type="text"> &nbsp;&nbsp; </td>
                                              </tr>
                                              <tr>
                                                <td align="right"> UF: </td>
                                                <td>&nbsp;</td>
                                                <td height="25"><select name="uf" class="campos" id="uf">
                                                  <option value="" selected="selected"> UF </option>
                                                  <option value="AC"> AC </option>
                                                  <option value="AL"> AL </option>
                                                  <option value="AM"> AM </option>
                                                  <option value="BA"> BA </option>
                                                  <option value="CE"> CE </option>
                                                  <option value="DF"> DF </option>
                                                  <option value="ES"> ES </option>
                                                  <option value="GO"> GO </option>
                                                  <option value="MA"> MA </option>
                                                  <option value="MG"> MG </option>
                                                  <option value="MS"> MS </option>
                                                  <option value="MT"> MT </option>
                                                  <option value="PA"> PA </option>
                                                  <option value="PB"> PB </option>
                                                  <option value="PE"> PE </option>
                                                  <option value="PI"> PI </option>
                                                  <option value="PR"> PR </option>
                                                  <option value="RJ"> RJ </option>
                                                  <option value="RN"> RN </option>
                                                  <option value="RO"> RO </option>
                                                  <option value="RR"> RR </option>
                                                  <option value="RS"> RS </option>
                                                  <option value="SC"> SC </option>
                                                  <option value="SE"> SE </option>
                                                  <option value="SP"> SP </option>
                                                  <option value="TO"> TO </option>
                                                </select>
                                                &nbsp;&nbsp;&nbsp;CEP:
                                                <input name="cep" class="campos" id="cep" size="20" maxlength="8" type="text"></td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Cidade:                                                  </div>                                                </td>
                                                <td>&nbsp;                                                </td>
                                                <td height="25">
                                                  <input name="cidade" class="campos" id="cidade3" size="25" maxlength="20" type="text"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CNPJ: <input name="cnpj" class="campos" id="cnpj" size="22" maxlength="18" type="text">                                                </td>
                                              </tr>
                                              
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Sócio 1:                                                  </div>                                                </td>
                                                <td>&nbsp;                                                </td>
                                                <td height="25">
                                                  <input name="socio1" class="campos" id="socio1" size="35" maxlength="40" type="text"> CPF: <input name="cpf1" class="campos" id="cpf1" size="15" maxlength="14" type="text">                                                </td>
                                              </tr>
                                              
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Sócio 2:                                                  </div>                                                </td>
                                                <td>&nbsp;                                                </td>
                                                <td height="25">
                                                  <input name="socio2" class="campos" id="socio2" size="35" maxlength="40" type="text"> CPF: <input name="cpf2" class="campos" id="cpf2" size="15" maxlength="14" type="text">                                                </td>
                                              </tr>
                                              
                                              <tr>
                                                <td height="10">
                                                  <div align="right"></div>                                                </td>
                                                <td height="10">&nbsp;                                                </td>
                                                <td height="10">
&nbsp;&nbsp;                                                </td>
                                              </tr>
                                              <tr>
                                                <td colspan="2">                                                </td>
                                                <td>
                                         <input name="Button" class="campos" id="Submit2" value="Gerar Termo" type="button" onClick="valida()"> 			<input name="Reset" class="campos" value="Limpar" type="reset"><br>                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </form>
                                      </td>
                                      <td>&nbsp;
                                        
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </td>
        </tr>
        <tr>
          <td>&nbsp;
            
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
<?php
	} else {
foreach ($_POST as $k => $v) {
	setcookie($k, $v, time()+60*60*6); // 6 horas de cookie
}

$mes = array('-','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
$estado = array(
	'AC'=>'Acre',
	'AL'=>'Alagoas',
	'AP'=>'Amapá',
	'AM'=>'Amazonas',
	'BA'=>'Bahia',
	'CE'=>'Ceará',
	'DF'=>'Distrito Federal',
	'ES'=>'Espírito Santo',
	'GO'=>'Goiás',
	'MA'=>'Maranhão',
	'MT'=>'Mato Grosso',
	'MS'=>'Mato Grosso do Sul',
	'MG'=>'Minas Gerais',
	'PA'=>'Pará',
	'PB'=>'Paraába',
	'PR'=>'Paraná',
	'PE'=>'Pernambuco',
	'PI'=>'Piauí',
	'RN'=>'Rio Grande do Norte',
	'RS'=>'Rio Grande do Sul',
	'RJ'=>'Rio de Janeiro',
	'RO'=>'Rondônia',
	'RR'=>'Roraima',
	'SC'=>'Santa Catarina',
	'SP'=>'São Paulo',
	'SE'=>'Sergipe',
	'TO'=>'Tocantins',
);

// echo '<textarea style="width:99%;height:300px">';
// print_r($_POST);
// echo '</textarea>';

foreach($_POST as $k => $v) {
	$_POST[$k] = htmlentities(stripslashes($v));
}

$r = array(
	'nomeempresa'=>$_POST['empresa'],
	'cidadeempresa'=>$_POST['cidade'],
	'estadoempresa'=>$estado[strtoupper($_POST['uf'])],
	'enderecoempresa'=>$_POST['Rua/Av'],
	'nrempresa'=>$_POST['nr'],
	'bairroempresa'=>$_POST['bairro'],
	'cnpj'=>$_POST['cnpj'],	
	'nomesocio1'=>$_POST['socio1'],	
	'estadocivilsocio1'=>$_POST['civil1'],
	'nomesocio2'=>$_POST['socio2'],
	'cpfsocio1'=>$_POST['cpf1'],
	'cpfsocio2'=>$_POST['cpf2'],
	
);

if ($r['estadoempresa']=='') $r['estadoempresa'] = strtoupper($_POST['uf']);

$erros = '';
/*
foreach($r as $k => $v) {
	if ($v=='') {
		if ($erros == '') $erros = 'Existem campos n�o preenchidos.';
	} else {
		$r[$k] = "<strong>{$v}</strong>";
	}
}
*/
// 62.173.620/0001-80
function fcnpj($cnpj) {
	$cnpj = preg_replace('/[^0-9]/','',$cnpj);
	return preg_replace('/([0-9]+)([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})$/','\1.\2.\3/\4.\5',$cnpj);
}
function fcpf($cpf) {
	$cpf =preg_replace('/[^0-9]/','',$cpf);
	return preg_replace('/([0-9]+)([0-9]{3})([0-9]{3})([0-9]{2})$/','\1.\2.\3-\4',$cpf);
}
$r['cnpj'] = fcnpj($r['cnpj']);

$r['cpfsocio1'] = fcpf($r['cpfsocio1']);
$r['cpfsocio2'] = fcpf($r['cpfsocio2']);

foreach($r as $k => $v) {
	if ($r[$k]=='') $r[$k] = '====';
	$r[$k] = "<strong>{$v}</strong>";
}

$r = array_merge(array(
	'datadodia' => date('d').' de '.$mes[date('n')].' de '.date('Y'),
	'complementoempresa'=>$_POST['compl'],
	'cartorioreconhecimento'=>stripslashes($_POST['cartorio']),
	'referenciasdoendereco'=>nl2br(stripslashes($_POST['referencias'])),
), $r);

$linha = '<div class="linha" style="height:25px;border-bottom:1px solid #000">&nbsp;</div>';

if ($_POST['cartorio']=='') {
	$r['cartorioreconhecimento'] = $linha;
}

if ($_POST['referencias']=='') {
	$r['referenciasdoendereco'] = $linha.$linha.$linha;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      WebControl Empresas
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="../sct/geral.css" rel="stylesheet" type="text/css">
		<!-- link rel="stylesheet" type="text/css" media="print" href="../sct/serasa.css" -->
		<style type="text/css">
		ul, li {margin:0;padding:0;list-style:none}
		* {margin:0;padding:0}
		body {font-family:Arial,'Times New Roman',Verdana,Helvetica,sans-serif;font-size:11pt;}
		</style>
  </head>
  <body style="margin:0">
<?php if ($erros!='') { ?><ul class="nimprimir" style="background:#ccc;padding:10px"><?= $erros ?><li><a href="equifax.php">Voltar</a></li></ul><?php	} ?>
<?php
unset($complemento);
if($_REQUEST['compl'] != ""){
	$complemento = " complemento ";
	$complemento .=  "<b>".$_REQUEST['compl']."</b> ";	
}
?>
<p align="center" style="margin-bottom: 0cm;"><b>TERMO DE ANUÊNCIA PARA NEGATIVAÇÃO NA EQUIFAX</b></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="center" style="margin-bottom: 0cm;"><b>TERMO DE ANUÊNCIA E OUTRAS GARANTIAS E OBRIGAÇÕES</b></div>
<p>&nbsp;</p>
<table border="0" align="center" cellpadding="0" cellspacing="10" width="98%"><tr><td> 
<p align="justify" style="margin-bottom: 0cm;"><b><?=$_REQUEST['empresa']?></b>, sediado na rua <b><?=$_REQUEST['rua']?></b>, n.º <b><?=$_REQUEST['nr']?></b>, <?=$complemento?> com sede na Cidade de <b><?=$_REQUEST['cidade']?></b>, no Estado de <b><?=$_REQUEST['uf']?></b>,  CEP: <b><?=$_REQUEST['cep']?></b>, inscrita no CNPJ/MF sob o n.º <b><?=$_REQUEST['cnpj']?></b>, por seu(s)  representante(s) legal(ais) abaixo assinado(s), doravante denominada simplesmente  "Empresa Cliente", firma o presente Termo de Anuência ao Contrato de Prestação de Serviços celebrado entre a EQUIFAX DO BRASIL LTDA., doravante denominada  simplesmente "EQUIFAX"; e a <b>WEBCONTROL EMPRESAS</b>, doravante denominada simplesmente "<b>INFORM SYSTEM</b>", em 10 de Agosto de 2009, doravante simplesmente o "Contrato", nos seguintes termos.</p>
<p>&nbsp;</p>

<p align="justify" style="margin-bottom: 0cm">1) A Empresa Cliente declara que recebeu uma cópia do Contrato e concorda com seus
  termos. Além disto, por esse instrumento, a Empresa Cliente formaliza o interesse de que as
  informações de débito que tiver enviado ou que venha a enviar á WEBCONTROL EMPRESAS
  (doravante "Informações") passem a integrar o banco de dados da EQUIFAX. Para tanto, a
  Empresa Cliente autoriza o envio das Informações pela WEBCONTROL á EQUIFAX nos
  termos estabelecidos no Contrato, comprometendo-se perante a EQUIFAX, sem prejuízo das
  obrigações que tiver assumido perante a <b>WEBCONTROL</b>, a:</p>
<p>&nbsp;</p>
  
<p align="justify" style="margin-bottom: 0cm">a) manter, pelo prazo de 5 (cinco) anos, a contar da data da inclusão da Informações (ou se maior prazo for exigido em lei, pelo maior prazo) no banco de dados da EQUIFAX,todos os documentos que comprovem a regularidade e procedência do dêbito, tais como: contratos, orçamentos devidamente aprovados, títulos de crédito, notas fiscais, comprovantes de entrega de mercadoria ou de prestações dos serviços;</p>
<p>&nbsp;</p>
  
<p align="justify" style="margin-bottom: 0cm"> b) fornecer á EQUIFAX, no prazo de 5 (cinco) dias, ou no prazo indicado pela EQUIFAX, se houver necessidade de atendimento á ordem judicial ou de oferecimento de defesa judicial, os comprovantes de que trata a letra "a" acima; </p>
<p>&nbsp;</p>
  
<p align="justify" style="margin-bottom: 0cm"> c) prestar todas e quaisquer informações necessárias aos devedores para a regularização de seus débitos;</p>
<p>&nbsp;</p>

<p align="justify" style="margin-bottom: 0cm">  d) fornecer, em até 24 (vinte e quatro) horas, as informações que a EQUIFAX solicitar sobre quaisquer Informações; e</p>
<p>&nbsp;</p>

<p align="justify" style="margin-bottom: 0cm">  e) enviar as informações que devam ser excluídas do banco de dados, sob pena de responder civil e criminalmente pelos danos que as informações constantes indevidamente no banco de dados da EQUIFAX causarem a terceiros e á própria EQUIFAX; esta obrigação remanescerá na hipotese de resolução do Contrato existente entre a Empresa Cliente e a <b>WEBCONTROL EMPRESAS</b>, salvo se a Empresa Cliente solicitar á <b>WEBCONTROL EMPRESAS</b> ou á EQUIFAX, pelos meios disponibilizados pela EQUIFAX, a baixa de todas as informações que tiver fornecido para inclusão no banco de dados da EQUIFAX.</p>
<p>&nbsp;</p>

<p align="justify" style="margin-bottom: 0cm">2) Na hipótese da Empresa Cliente não cumprir com as garantias prestadas e com as obrigações acima assumidas, ficará responsável perante a EQUIFAX por responder por todos os danos, prejuízos, inclusive despesas, custas e honorários advocatícios decorrentes, cujo pagamento venha a ser exigido da EQUIFAX, comprometendo-se a, na hipótese de ação judicial, ou procedimentos perante órgãos de defesa do consumidor,
substituir a EQUIFAX no pólo passivo.</p>
<p>&nbsp;</p>

<p align="justify" style="margin-bottom: 0cm"> 3) A Empresa Cliente assume perante a EQUIFAX e terceiros, a responsabilidade total pela veracidade e exatidão dos dados remetidos á <b>WEBCONTROL EMPRESAS</b>, ficando sob sua responsabilidade responder, na hipótese da EQUIFAX ser acionada, direta ou regressivamente, por eventuais reclamações em consequência da inverdade, inexatidão ou desatualização desses dados.</p>
<p>&nbsp;</p>

<p align="justify" style="margin-bottom: 0cm"> E, por anuir com o Contrato, desejar prestar as garantias acima previstas e assumir as
  obrigações anteriores, a Empresa Cliente assina o presente Termo de Anuência, em 3 (três)
  vias de igual teor e forma, na presença das 2 (duas) testemunhas abaixo assinadas. <br>
  <br />
</p>

<p>&nbsp;</p>
<p align="center" style="margin-bottom: 0cm;">São Paulo, <?= $r['datadodia'] ?>.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td></tr></table>
<table border="0" align="center" width="100%">

<tr><td width="50%">____________________________________________________</td>
<td width="50%">____________________________________________________</td></tr>
<tr><td width="50%">WEBCONTROL EMPRESAS</td><td width="50%"><?=$_REQUEST['empresa']?></td></tr>
<tr><td colspan="2" height="60px"><b>Testemunhas:</b></td></tr>
<tr>
  <td><b>1.____________________________________________________</b></td>
  <td><b>2.____________________________________________________</b></td>
</tr>
<tr>
  <td><b>Nome:</b></td>
  <td><b>Nome:</b></td>
</tr>
<tr>
  <td><b>CPF:</b></td>
  <td><b>CPF:</b></td>
</tr>
</table>

<div class="nimprimir" align="center">
	<a href="#" onClick="window.print()">Imprimir</a>&nbsp;&nbsp;&nbsp;<a href="equifax.php">Voltar</a>
</div>

<?php
	}
?>
<?php
include("functions.php");
proteger();

if (!isset($_POST['empresa'])) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      Inform System Tecnologia em Informações
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="../sct/geral.css" rel="stylesheet" type="text/css">
		<script type="text/javascript"><!--
function gid(id) {
	return document.getElementById(id);
}
function carregar() {
	gid('uf').value='<?= addslashes($_COOKIE['uf']) ?>';
	gid('junta').value='<?= addslashes($_COOKIE['junta']) ?>';
}
// --></script>
  </head>
  <body style="margin:0" onload="carregar();">
    <hr>
    <table width="550" border="0" cellspacing="0" cellpadding="0">
      <tr valign="top">
        <td width="248" height="75">
          <img src="../img/logo_print.jpg" width="175" height="60" alt="Logo InformSystem">
        </td>
        <td width="302" height="75">
          <b>Inform System Tecnologia em Informações</b>
        </td>
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
                              <div align="justify">
                                <br>
                                <strong>Procuração</strong><br>
                                <br>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                  <tbody>
                                    <tr>
                                      <td width="10">
                                        &nbsp;
                                      </td>
                                      <td>
                                        <form action="serasa.php" method="post" name="form1" id="form1">
                                          <div align="left">
                                            Utilize o formulário abaixo para gerar a procuração.<br>
                                          </div>
                                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Empresa:
                                                  </div>
                                                </td>
                                                <td>
                                                  &nbsp;
                                                </td>
                                                <td height="25">
                                                  <input name="empresa" class="campos" id="empresa" size="64" maxlength="70" type="text" value="<?= htmlentities($_COOKIE['empresa']) ?>">
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Rua/Av :
                                                  </div>
                                                </td>
                                                <td width="5">
                                                  &nbsp;
                                                </td>
                                                <td height="25" >
                                                  <input name="Rua/Av" class="campos" id="endereco3" size="45" maxlength="60" type="text" value="<?= htmlentities($_COOKIE['Rua/Av']) ?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nr: <input name="nr" class="campos" id="nr" size="6" maxlength="6" type="text" value="<?= htmlentities($_COOKIE['nr']) ?>"> &nbsp;&nbsp;
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Compl.:
                                                  </div>
                                                </td>
                                                <td>
                                                  &nbsp;
                                                </td>
                                                <td height="25">
                                                  <input name="compl" class="campos" id="compl2" size="15" maxlength="12" type="text" value="<?= htmlentities($_COOKIE['compl']) ?>"> &nbsp;&nbsp;Bairro: <input name="bairro" class="campos" id="bairro" size="20" maxlength="18" type="text" value="<?= htmlentities($_COOKIE['bairro']) ?>"> &nbsp; UF: <select name="uf" class="campos" id="uf">
                                                    <option value="" selected="selected">
                                                      UF
                                                    </option>
                                                    <option value="AC">
                                                      AC
                                                    </option>
                                                    <option value="AL">
                                                      AL
                                                    </option>
                                                    <option value="AM">
                                                      AM
                                                    </option>
                                                    <option value="BA">
                                                      BA
                                                    </option>
                                                    <option value="CE">
                                                      CE
                                                    </option>
                                                    <option value="DF">
                                                      DF
                                                    </option>
                                                    <option value="ES">
                                                      ES
                                                    </option>
                                                    <option value="GO">
                                                      GO
                                                    </option>
                                                    <option value="MA">
                                                      MA
                                                    </option>
                                                    <option value="MG">
                                                      MG
                                                    </option>
                                                    <option value="MS">
                                                      MS
                                                    </option>
                                                    <option value="MT">
                                                      MT
                                                    </option>
                                                    <option value="PA">
                                                      PA
                                                    </option>
                                                    <option value="PB">
                                                      PB
                                                    </option>
                                                    <option value="PE">
                                                      PE
                                                    </option>
                                                    <option value="PI">
                                                      PI
                                                    </option>
                                                    <option value="PR">
                                                      PR
                                                    </option>
                                                    <option value="RJ">
                                                      RJ
                                                    </option>
                                                    <option value="RN">
                                                      RN
                                                    </option>
                                                    <option value="RO">
                                                      RO
                                                    </option>
                                                    <option value="RR">
                                                      RR
                                                    </option>
                                                    <option value="RS">
                                                      RS
                                                    </option>
                                                    <option value="SC">
                                                      SC
                                                    </option>
                                                    <option value="SE">
                                                      SE
                                                    </option>
                                                    <option value="SP">
                                                      SP
                                                    </option>
                                                    <option value="TO">
                                                      TO
                                                    </option>
                                                  </select> &nbsp;
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Cidade:
                                                  </div>
                                                </td>
                                                <td>
                                                  &nbsp;
                                                </td>
                                                <td height="25">
                                                  <input name="cidade" class="campos" id="cidade3" size="25" maxlength="20" type="text" value="<?= htmlentities($_COOKIE['cidade']) ?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CNPJ: <input name="cnpj" class="campos" id="cnpj" size="22" maxlength="18" type="text" value="<?= htmlentities($_COOKIE['cnpj']) ?>">
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Estado Junta Comercial:
                                                  </div>
                                                </td>
                                                <td>
                                                  &nbsp;
                                                </td>
                                                <td height="25">
                                                  <div align="left">
                                                    <select name="junta" class="campos" id="junta">
                                                      <option value="">
                                                        Selecione
                                                      </option>
                                                      <option value="Acre">
                                                        Acre
                                                      </option>
                                                      <option value="Alagoas">
                                                        Alagoas
                                                      </option>
                                                      <option value="Amapá">
                                                        Amapá
                                                      </option>
                                                      <option value="Amazonas">
                                                        Amazonas
                                                      </option>
                                                      <option value="Bahia">
                                                        Bahia
                                                      </option>
                                                      <option value="Ceará">
                                                        Ceará
                                                      </option>
                                                      <option value="Distrito Federal">
                                                        Distrito Federal
                                                      </option>
                                                      <option value="Espírito Santo">
                                                        Espírito Santo
                                                      </option>
                                                      <option value="Goiás">
                                                        Goiás
                                                      </option>
                                                      <option value="Maranhão">
                                                        Maranhão
                                                      </option>
                                                      <option value="Mato Grosso">
                                                        Mato Grosso
                                                      </option>
                                                      <option value="Mato Grosso do Sul">
                                                        Mato Grosso do Sul
                                                      </option>
                                                      <option value="Minas Gerais">
                                                        Minas Gerais
                                                      </option>
                                                      <option value="Pará">
                                                        Pará
                                                      </option>
                                                      <option value="Paraíba">
                                                        Paraíba
                                                      </option>
                                                      <option value="Paraná">
                                                        Paraná
                                                      </option>
                                                      <option value="Pernambuco">
                                                        Pernambuco
                                                      </option>
                                                      <option value="Piauí">
                                                        Piauí
                                                      </option>
                                                      <option value="Rio Grande do Norte">
                                                        Rio Grande do Norte
                                                      </option>
                                                      <option value="Rio Grande do Sul">
                                                        Rio Grande do Sul
                                                      </option>
                                                      <option value="Rio de Janeiro">
                                                        Rio de Janeiro
                                                      </option>
                                                      <option value="Rondônia">
                                                        Rondônia
                                                      </option>
                                                      <option value="Roraima">
                                                        Roraima
                                                      </option>
                                                      <option value="Santa Catarina">
                                                        Santa Catarina
                                                      </option>
                                                      <option value="São Paulo">
                                                        São Paulo
                                                      </option>
                                                      <option value="Sergipe">
                                                        Sergipe
                                                      </option>
                                                      <option value="Tocantins">
                                                        Tocantins
                                                      </option>
                                                    </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sob o nº NIRE: <input name="nire" class="campos" id="nire" size="16" maxlength="12" type="text" value="<?= htmlentities($_COOKIE['nire']) ?>">
                                                  </div>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Sócio 1:
                                                  </div>
                                                </td>
                                                <td>
                                                  &nbsp;
                                                </td>
                                                <td height="25">
                                                  <input name="socio1" class="campos" id="socio1" size="35" maxlength="40" type="text" value="<?= htmlentities($_COOKIE['socio1']) ?>"> CPF: <input name="cpf1" class="campos" id="cpf1" size="15" maxlength="14" type="text" value="<?= htmlentities($_COOKIE['cpf1']) ?>">
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Profissão:
                                                  </div>
                                                </td>
                                                <td>
                                                  &nbsp;
                                                </td>
                                                <td>
                                                  <p>
                                                    <input name="profissao1" class="campos" id="profissao1" size="25" maxlength="20" type="text" value="<?= htmlentities($_COOKIE['profissao1']) ?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Estado Civil: <input name="civil1" class="campos" id="civil1" size="15" maxlength="14" type="text" value="<?= htmlentities($_COOKIE['civil1']) ?>">
                                                  </p>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Sócio 2:
                                                  </div>
                                                </td>
                                                <td>
                                                  &nbsp;
                                                </td>
                                                <td height="25">
                                                  <input name="socio2" class="campos" id="socio2" size="35" maxlength="40" type="text" value="<?= htmlentities($_COOKIE['socio2']) ?>"> CPF: <input name="cpf2" class="campos" id="cpf2" size="15" maxlength="14" type="text" value="<?= htmlentities($_COOKIE['cpf2']) ?>">
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <div align="right">
                                                    Profissão:
                                                  </div>
                                                </td>
                                                <td>
                                                  &nbsp;
                                                </td>
                                                <td height="25">
                                                  <input name="profissao2" class="campos" id="profissao2" size="25" maxlength="20" type="text" value="<?= htmlentities($_COOKIE['profissao2']) ?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Estado Civil: <input name="civil2" class="campos" id="civil2" size="15" maxlength="14" type="text" value="<?= htmlentities($_COOKIE['civil2']) ?>">
                                                </td>
                                              </tr>
																							<tr>
                                                <td align="right">Cidade Sócios:</td><td>&nbsp;</td>
                                                <td height="25"><input name="cidadesocios" class="campos" id="cidadesocios" size="64" maxlength="255" type="text" value="<?= htmlentities($_COOKIE['cidadesocios']) ?>"></td>
                                              </tr>
																							<tr>
                                                <td align="right">Cartório:</td><td>&nbsp;</td>
                                                <td height="25"><input name="cartorio" class="campos" id="cartorio" size="64" maxlength="255" type="text" value="<?= htmlentities($_COOKIE['cartorio']) ?>"></td>
                                              </tr>
																							<tr>
                                                <td align="right" valign="top">Rerefências do endereço:</td><td>&nbsp;</td>
                                                <td height="25"><textarea name="referencias" class="campos" id="referencias" cols="48" rows="5"><?= htmlentities($_COOKIE['referencias']) ?></textarea></td>
                                              </tr>
                                              <tr>
                                                <td height="10">
                                                  <div align="right"></div>
                                                </td>
                                                <td height="10">
                                                  &nbsp;
                                                </td>
                                                <td height="10">
                                                  &nbsp;&nbsp;
                                                </td>
                                              </tr>
                                              <tr>
                                                <td colspan="2">

                                                </td>
                                                <td>
                                                  <input name="Button" class="campos" id="Submit2" value="Gerar Procuração" type="submit"> <input name="Reset" class="campos" value="Limpar" type="reset"><br>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </form>
                                      </td>
                                      <td>
                                        &nbsp;
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
          <td>
            &nbsp;
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
<?php
	} else {
foreach ($_POST as $k => $v) {
	setcookie($k, $v);
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
	'PB'=>'Paraíba',
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
	'estadojunta'=>$_POST['junta'],
	'nire'=>$_POST['nire'],
	'nomesocio1'=>$_POST['socio1'],
	'profissaosocio1'=>$_POST['profissao1'],
	'estadocivilsocio1'=>$_POST['civil1'],
	'nomesocio2'=>$_POST['socio2'],
	'profissaosocio2'=>$_POST['profissao2'],
	'estadocivilsocio2'=>$_POST['civil2'],
	'cpfsocio1'=>$_POST['cpf1'],
	'cpfsocio2'=>$_POST['cpf2'],
	'cidadesocios'=>$_POST['cidadesocios'],
);

if ($r['estadoempresa']=='') $r['estadoempresa'] = strtoupper($_POST['uf']);

$erros = '';

foreach($r as $k => $v) {
	if ($v=='') {
		if ($erros == '') $erros = 'Existem campos não preenchidos.';
	} else {
		$r[$k] = "<strong>{$v}</strong>";
	}
}

// 62.173.620/0001-80
function fcnpj($cnpj) {
	$cnpj = ereg_replace('[^0-9]','',$cnpj);
	return ereg_replace('([0-9]+)([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})$','\1.\2.\3/\4.\5',$cnpj);
}
function fcpf($cpf) {
	$cpf = ereg_replace('[^0-9]','',$cpf);
	return ereg_replace('([0-9]+)([0-9]{3})([0-9]{3})([0-9]{2})$','\1.\2.\3-\4',$cpf);
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
      Inform System Tecnologia em Informações
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="../sct/geral.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" media="print" href="../sct/serasa.css">
		<style type="text/css">
		ul, li {margin:0;padding:0;list-style:none}
		</style>
  </head>
  <body style="margin:0">
<?php if ($erros!='') { ?><ul style="background:#ccc;padding:10px"><?= $erros ?><li><a href="serasa.php">Voltar</a></li></ul><?php	} ?>
<div class="imprimir">
<p align="center" style="margin-bottom: 0cm;"><font size="2" style="font-size: 11pt;"><u><strong>P R O C U R A &Ccedil; &Atilde; O</strong></u></font></p>
<p style="margin-bottom: 0cm;">&nbsp;</p>
<p style="margin-bottom: 0cm;">&nbsp;</p>
<p style="margin-bottom: 0cm;">&nbsp;</p>
<div align="justify" style="margin-bottom: 0cm;"><font size="2" style="font-size: 11pt;"><strong>OUTORGANTE</strong></font></div>
<p>&nbsp;</p>
<p align="justify" style="margin-bottom: 0cm;"><font size="2" style="font-size: 11pt;"><?= $r['nomeempresa'] ?> , com sede na cidade de <?= $r['cidadeempresa'] ?> Estado do <?= $r['estadoempresa'] ?>, na Rua/Av. <?= $r['enderecoempresa'] ?>, n&ordm;  <?= $r['nrempresa'] ?> <?= $r['complementoempresa'] ?> - Bairro <?= $r['bairroempresa'] ?>, inscrita no CNPJ sob o n&ordm; <?= $r['cnpj'] ?> e na Junta Comercial do Estado de <?= $r['estadojunta'] ?> sob o n&ordm; NIRE <?= $r['nire'] ?>, neste ato representada por seu(s) s&oacute;cio(s) Sr(a). <?= $r['nomesocio1'] ?>, <?= $r['profissaosocio1'] ?>, <?= $r['estadocivilsocio1'] ?>, e <?= $r['nomesocio2'] ?>, <?= $r['profissaosocio2'] ?>, <?= $r['estadocivilsocio2'] ?> inscrito(s) no CPF/MF respectivamente sob o(s) n&uacute;mero(s) <?= $r['cpfsocio1'] ?> e <?= $r['cpfsocio2'] ?>, brasileiro(s), residente(s) e domiciliado(s) na cidade de <?= $r['cidadesocios'] ?> com endere&ccedil;o comercial no mesmo local acima citado.</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="justify" style="margin-bottom: 0cm;"><font size="2" style="font-size: 11pt;"><strong>OUTORGADA</strong></font></div>
<p>&nbsp;</p>
<p align="justify" style="margin-bottom: 0cm;"><font size="2" style="font-size: 11pt;">Inform System Tecnologia em Informa&ccedil;&otilde;es Ltda , com sede na cidade de Curitiba - PR, na Avenida Marechal Floriano Peixoto, 306 Conj 11  Edficio Montreal Executive Center no Bairro Centro, CEP 80.010-130, inscrita no CNPJ sob o n&ordm; 06.866.893/0001-39, neste ato representada na forma de seu ato constitutivo.</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="justify" style="margin-bottom: 0cm;"><font size="2" style="font-size: 11pt;"><strong>PODERES</strong></font></div>
<p>&nbsp;</p>
<p align="justify" style="margin-bottom: 0cm;"><font size="2" style="font-size: 11pt;">Espec&iacute;ficos para representar a Outorgante perante a Serasa S. A., com sede na Alameda dos Quinimuras, 187 &ndash; Planalto Paulista &ndash; S&atilde;o Paulo &ndash; SP - CEP 04068-900 , inscrita no CNPJ/MF sob o n.&ordm; 62.173.620/0001-80 e na Junta Comercial do Estado de S&atilde;o Paulo, sob o n&ordm; NIRE 35.3.0006256-6, podendo fornecer os dados das pessoas f&iacute;sicas e jur&iacute;dicas e os seus registros de obriga&ccedil;&otilde;es ou t&iacute;tulos de dividas vencidos e n&atilde;o pagos, referentes aos clientes, para compor o cadastro PEFIN - Pend&ecirc;ncias Financeiras da Serasa, bem como para comandar as exclus&otilde;es das dividas quitadas ou aquelas cujos titulares, por qualquer motivo, n&atilde;o devam constar no PEFIN, podendo, enfim, praticar todos atos previstos no contrato de presta&ccedil;&atilde;o de servi&ccedil;os de informa&ccedil;&otilde;es do cadastro de PEFIN firmado entre a Outorgada e a Serasa, acerca do qual a Outorgante declara estar ciente e conforme com os seus termos. <br />
<br />
O presente mandato ter&aacute; validade por prazo indeterminado, podendo ser revogado a qualquer tempo.</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="justify" style="margin-bottom: 0cm;"><font size="2" style="font-size: 11pt;">Curitiba, <?= $r['datadodia'] ?></font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div class="quebrapagina" align="justify" style="margin-bottom: 0cm;"><font size="2"><strong>OUTORGANTES</strong></font></div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="justify" style="margin-bottom: 0cm;"><font size="2">
		<span style="float:left">____________________________________</span>
		<span style="float:right">____________________________________</span>
	<br style="clear:both" />
		<span style="float:left"><?= $r['nomesocio1'] ?></span>
		<span style="float:right"><?= $r['nomesocio2'] ?></span>
	<br style="clear:both" />
		<span style="float:left">S&oacute;cio 1 </span>
		<span style="float:right">S&oacute;cio 2</span>
</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="justify" style="margin-bottom: 0cm;"><font size="2">CART&Oacute;RIO PARA RECONHECIMENTO DE FIRMA:    <?= $r['cartorioreconhecimento'] ?></font></p>
<p>&nbsp;</p>
<p align="justify" style="margin-bottom: 0cm;"><font size="2">REFER&Ecirc;NCIAS DO ENDERE&Ccedil;O:   <?= $r['referenciasdoendereco'] ?></font></p>
<p>&nbsp;</p>
<div class="linhafim" style="height:25px;border-bottom:1px dashed #000">&nbsp;</div>


</div>

<a href="javascript:self.print()"> <img src="../img/imprimir.jpg" width="65" height="16" border="0"></a>
  &nbsp;&nbsp;&nbsp;<a href="serasa.php">Voltar</a>
<?php
	}
?>
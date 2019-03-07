<?php
require_once("../connect/conexao_conecta.php");

$data_notificacao = $_REQUEST['data_notificacao'];
$codloja          = $_REQUEST['codloja'];
$soma             = $_REQUEST['soma'];

$dia = substr($data_notificacao, 0,2);   
$mes = substr($data_notificacao, 3,2);   
$ano = substr($data_notificacao, 6,4); 
	  
if(!checkdate($mes, $dia, $ano))
{
?>

<table width="600" border="1" cellspacing="0" cellpadding="5" align="center" class="Grande">
  <tr>
    <td bgcolor="#eeeeee" align="center" style="padding-top:10; padding-bottom:10" class="Grande"><strong>DADOS DO CREDOR</strong>
   </td>
  </tr>    
  <tr>
    <td align="center">Data Inv�lida&nbsp;&nbsp;<a href='popup_notificacao_data.php?codloja=<?=$codloja?>&soma=<?=$soma?>'>Retornar</a></td>
  </tr>
</table>
<?php
	exit;
}

# localizando dados do cliente para gera��o da NOTIFICA��O. somente em caso de atraso.


$command = "SELECT a.email, a.razaosoc,a.end,a.bairro,a.cidade,a.uf,a.cep, b.fantasia, b.fone1 FROM cs2.cadastro a
			INNER JOIN cs2.franquia b on a.id_franquia = b.id 
			WHERE a.codloja=$codloja";
$res = mysql_query($command,$con);
$resp=mysql_fetch_array($res);
$razaosoc = $resp["razaosoc"];
$end      = $resp["end"];
$bairro   = $resp["bairro"];
$cidade   = $resp["cidade"];
$uf       = $resp["uf"];
$cep      = cepMascarado($resp["cep"]);
$nome_credor = $resp["fantasia"];
$fone_credor = $resp["fone1"];
$email = $resp["email"];
$fone_credor = '('.substr($fone_credor,0,2).') '.substr($fone_credor,2,4).'-'.substr($fone_credor,6,4);

function cepMascarado($p_cep_banco){
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
?>

<script language="javascript">
function enviaEmail(){ 	
	frm = document.form;
    frm.action = 'popup_email_notificacao_enviar.php';
	frm.submit();
}

function trim(str){return str.replace(/^\s+|\s+$/g,"");}//valida espa�o em branco
 
function valida() {
	frm = document.form;		
	if(trim(frm.email.value) == ""){
		alert("Falta informar o e-mail !");
		frm.email.focus();
		return false;
	}	
	parte1 = frm.email.value.indexOf("@");
	parte2 = frm.email.value.indexOf(".");
	parte3 = frm.email.value.length;
	if (!(parte1 >= 3 && parte2 >= 6 && parte3 >= 9)) {
		   alert ("O campo E-mail deve conter um endereco eletronico correto !");
		   frm.email.focus();
		   return false;
    }
	 enviaEmail();
}
</script>

<style type="text/css" media="print">
.noprint {
	display:none;
}
</style>
<form name="form" method="post" action="#">
<input type="hidden" name="razaosoc" value="<?=$razaosoc?>">
<input type="hidden" name="end" value="<?=$end?>">
<input type="hidden" name="bairro" value="<?=$bairro?>">
<input type="hidden" name="cidade" value="<?=$cidade?>">
<input type="hidden" name="uf" value="<?=$uf?>">
<input type="hidden" name="cep" value="<?=$cep?>">
<input type="hidden" name="nome_credor" value="<?=$nome_credor?>">
<input type="hidden" name="fone_credor" value="<?=$fone_credor?>">
<input type="hidden" name="data_notificacao" value="<?=$data_notificacao?>">
<input type="hidden" name="soma" value="<?=number_format($soma, 2, ',', '.')?>">

<body topmargin="5" leftmargin="0" onload="window.print();">

<table border="0" width="650" align="center" cellpadding="0" cellspacing="5" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
 <tr class="noprint">
    <td colspan="2" bgcolor="#eeeeee" align="center" class="Grande"><b>ENVIAR P/ E-MAIL</b></td>
  </tr>
  <tr class="noprint">
    <td width="20%" bgcolor="#eeeeee"><b>Enviar p/ e-mail:</b></td>
    <td><input type="text" name="email" value="<?=$email?>" style="width:90%"></td>
  </tr>
  
   <tr class="noprint">
    <td colspan="2" align="center">
    			<input type="button" value="Enviar p/ E-mail" onClick="valida()">              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			    <input type="button" value="Imprimir" onClick="window.print();">    			
    </td>
  </tr>
</table>
<br>

<table border="0" width="650" align="center" cellpadding="0" cellspacing="3" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
<tr align="center">
    <td class="Grande" bgcolor="#eeeeee"><b>COMUNICADO DE D&Eacute;BITO</b></td>
  </tr>
  <tr>
    <td class="bodyText">
		<table width="97%" border="0" cellspacing="0" cellpadding="0" align="center" class="bodyText">
        <tr> 
          <td width="13%">Nome:</td>
          <td colspan="3"><b><?=$razaosoc?></b></td>
        </tr>
        <tr> 
          <td>Endere&ccedil;o:<br></td>
          <td colspan="3"><b><?=$end?></b></td>
        </tr>
        <tr> 
          <td>Bairro:<br>            </td>
          <td colspan="3"><b><?=$bairro?></b></td>
        </tr>
        <tr> 
          <td>Cidade:<br>            </td>
          <td width="58%"><b><?=$cidade?></b></td>
          <td width="7%">UF:</td>
          <td width="22%"><b><?=$uf?></b></td>
        </tr>
        <tr> 
          <td>Cep:</td>
          <td colspan="3"><b><?=$cep?></b></td>
        </tr>
      </table>
		<p align="justify">Prezado Sr.(a)<br>
      	<?=$razaosoc?>,</p>
		<p align="justify">Vimos lembrar-lhe sobre o vencimento(s) de sua(s) parcela(s),  correspondente ao seu contrato com nossa  empresa. Temos certeza que somente a falta  de tempo ou o natural esquecimento fez com que V.Sa. deixasse de saldar seu  d&eacute;bito na data do vencimento, cujo pagamento solicitamos seja providenciado  com urg&ecirc;ncia. </p>
		<p align="justify">A prop&oacute;sito, lembramos-lhe que nossas  facilidades e proposta para pagamento devem-se &agrave; confian&ccedil;a depositada em V.Sa..</p>
		<p align="justify">Este acordo lhe dar&aacute; melhores condi&ccedil;&otilde;es para pagamento sem comprometer seu or&ccedil;amento.</p>
<p align="justify">Encaminhamos os BOLETOS em anexo para pagamento em qualquer BANCO, CASAS LOT&Eacute;RICAS, CAIXAS ELETR&Ocirc;NICOS, INTERNET e CORREIOS.  </p>
<p align="justify">Quaisquer d&uacute;vidas referentes aos Boletos em anexo, favor entrar em contato conosco para esclarecimentos que forem necess&aacute;rios.</p>
		<p>Reiteramos nossos protestos de elevada estima e considera&ccedil;&atilde;o.</p>
	  <p>Cordialmente,</p>
	  <p>Departamento Financeiro.<br>
	  </p></td>
  </tr>
  <tr>
    <td align="center" class="bodyText">Obs: Caso j&aacute; tenha efetuado o pagamento, desconsidere este aviso e <br>
      entre em contato imediato com o estabelecimento abaixo.</td>
  </tr>
</table>
<br>

<table border="0" width="650" align="center" cellpadding="0" cellspacing="3" style="border: 1px solid #D1D7DC; background-color:#FFFFFF" class="Grande">
  <tr>
    <td colspan="4" bgcolor="#eeeeee" align="center" class="Grande"><b>DADOS DO D&Eacute;BITO</b></td>
  </tr>
  <tr>
    <td width="121" bgcolor="#eeeeee">Empresa Credora:</td>
	<td width="224"><strong><?=$nome_credor?></strong></td>
    <td width="55" bgcolor="#eeeeee">Telefone:</td>
    <td width="200"><strong><?=$fone_credor?></strong></td>
  </tr>
  <tr>
    <td bgcolor="#eeeeee">Vencimento:</td>
    <td><strong><?=$data_notificacao?></strong></td>
    <td bgcolor="#eeeeee">Valor:</td>
    <td><strong>R$ <?php echo number_format($soma, 2, ',', '.'); ?></strong></td>
  </tr>
</table>
<br>
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="Grande">
   <tr class="noprint">
    <td align="center">
    <input type="button" value="Imprimir" onClick="window.print();">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="Fechar" onClick="window.close();"></td>
  </tr>
</table>

</body>
</form>

</html>


<?php
$res = mysql_close ($con);
?>
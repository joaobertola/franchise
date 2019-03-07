<?php
require "connect/sessao.php";

function SimNao($dados){
	if ($dados) return 'S';
	else return 'N';
}

$codloja  = $_REQUEST['codloja'];
$logon    = $_REQUEST['logon'];
$id       = $_SESSION['id'];

if ( ($id_franquia == '163') or ($id_franquia == '4') or ($id_franquia == '46') ){
	$id_franquia = "";
}else{
	$id_franquia = " AND b.id_franquia = '$id' ";
}

$sql = "SELECT a.codloja, b.razaosoc, b.nomefantasia, a.sitlog, b.sitcli FROM cs2.logon a
		INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
		WHERE b.codloja = '$codloja' $id_franquia";
$qry = mysql_query($sql, $con);
$codloja = mysql_result($qry,0,'codloja');

if ( ! $codloja ){
	echo "<script>alert('Cliente nao encontrado OU Nao pertence a sua FRANQUIA.');history.back()</script>";
	exit;
}

$razaosoc = mysql_result($qry,0,'razaosoc');
$nomefantasia = mysql_result($qry,0,'nomefantasia');
$sitlog = mysql_result($qry,0,'sitlog');
$sitcli = mysql_result($qry,0,'sitcli');

if ( $sitcli != 0 or $sitlog != 0){
	// $negado = 'disabled="disabled"';
	$nomefantasia .= ' - CLIENTE BLOQUEADO OU CANCELADO';
}


$acao    = $_REQUEST['acao'];

if ( $acao == 'G' ){
	
	$nfe         = SimNao($_REQUEST['nfe']);
	$nfce        = SimNao($_REQUEST['nfce']);
	$cte         = SimNao($_REQUEST['cte']);
	$nfse        = SimNao($_REQUEST['nfse']);
	$mdfe        = SimNao($_REQUEST['mdfe']);
	$cfiscal     = SimNao($_REQUEST['cfiscal']);
	
	$nota = '';
	if ($nfe == 'S' )     $nota = 'NFe ';
	if ($nfce == 'S' )    $nota .= 'NFCe ';
	if ($cfiscal == 'S' ) $nota .= 'CupomFiscal';
	if ($cte == 'S' )     $nota .= 'CTe ';
	if ($nfse == 'S' )    $nota .= 'NFSe ';
	if ($mdfe == 'S' )    $nota .= 'MDFe ';
	
	
	$nota = trim($nota);
	$nota = str_replace(' ',' - ',$nota);
	$nota = str_replace('CupomFiscal','Cupom Fiscal',$nota);
	
	$mensagem = "Habilitado uso : $nota";
	
	
	$mensagem .= ' (Usu&aacute;rio: '.$_SESSION['usuario'].")";

	$sql = "INSERT INTO cs2.historico_nfe(codloja,data,hora,mensagem)
			  VALUES('$codloja', NOW(), NOW(), '$mensagem')";
	$qry = mysql_query($sql, $con);

	$sql = "UPDATE cs2.cadastro 
				SET 
					liberar_nfe = 'S',
					nfe         = '$nfe',
					nfce        = '$nfce',
					cte         = '$cte',
					nfse        = '$nfse',
					cfiscal     = '$cfiscal',
					mdfe        = '$mdfe'
			WHERE codloja = '$codloja'";
	$qry = mysql_query($sql,$con);

	
	echo "<script>alert('Registro gravado com sucesso.')</script>";
	
}

$sql = "SELECT nfe, nfce, cte, nfse, cfiscal, mdfe FROM cs2.cadastro
		WHERE codloja = $codloja";
$qry = mysql_query($sql, $con);
$nfe     = mysql_result($qry,0,'nfe');
$nfce    = mysql_result($qry,0,'nfce');
$cte     = mysql_result($qry,0,'cte');
$mdfe    = mysql_result($qry,0,'mdfe');
$nfse    = mysql_result($qry,0,'nfse');

$cfiscal = mysql_result($qry,0,'cfiscal');

if ( $nfe == 'S' || $_GET['hnf'] == true) // NFe
	$escolha_nfe = "checked='checked'";

if ( $nfce == 'S' || $_GET['hnf'] == true) // NFe
	$escolha_nfce = "checked='checked'";

if ( $cte == 'S' ) // NFe
	$escolha_cte = "checked='checked'";

if ( $nfse == 'S' ) // NFSe
	$escolha_nfse = "checked='checked'";

if ( $mdfe == 'S' ) // MDFe
	$escolha_mdfe = "checked='checked'";
	
if ( $cfiscal == 'S' ) // Cfiscal
	$escolha_cfiscal = "checked='checked'";
	
?>
<form name="form1" method="post" action="#">
<table width=70% align="center">
	<tr>
		<td colspan="2" align="center" class="titulo"><br>SOLICITA&Ccedil;&Atilde;O DE USO<br>
		NFe - NFCe - Cupom Fiscal - NFSe - CTe - MDFe<br />&nbsp;</td>
	</tr>
	<tr>
		<td class="subtitulodireita">C&oacute;digo:</td>
		<td class="campoesquerda"><?=$logon?></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Raz&atilde;o Social:</td>
		<td class="campoesquerda"><?=$razaosoc?></td>
	</tr>
	<tr>
		<td class="subtitulodireita">Fantasia:</td>
		<td class="campoesquerda"><?=$nomefantasia?></td>
	</tr>
	<tr height="70">
		<td class="subtitulodireita">M&oacute;dulos a serem LIBERADOS</td>
		<td class="campoesquerda">
			<input type="checkbox" name="nfe" value="NFe" <?php echo $escolha_nfe; ?> /> NFe - Nota Fiscal Eletr&ocirc;nica
			<br />
			<br />
			<input type="checkbox" name="nfce" value="NFCe" <?php echo $escolha_nfce; ?>/> NFCe - Nota Fiscal do Consumidor Eletr&ocirc;nica
            <br />
			<br />
			<input type="checkbox" name="cfiscal" value="Cupom Fiscal" <?php echo $escolha_cfiscal; ?>/> Cupom Fiscal
			<br />
			<br />
			<input type="checkbox" name="nfse" value="NFSe" <?php echo $escolha_nfse; ?>/> NFSe - Nota de Servi&ccedil;o Eletr&ocirc;nica
			<br />
			<br />
			<input type="checkbox" name="cte" value="CTe" <?php echo $escolha_cte; ?>/> CTe - Conhecimento de Transporte Eletr&ocirc;nico
            <br />
			<br />
			<input type="checkbox" name="mdfe" value="MDFe" <?php echo $escolha_mdfe; ?>/> MDFe - Manifesto de Frete Eletr&ocirc;nico
            

		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">&nbsp;</td>
    <tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value=" Gravar " <?=$negado?> />
			<input type="hidden" name="acao" value="G"/>
			<input type="hidden" name="codigo" value="<?=$codigo?>"/>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">&nbsp;</td>
    <tr>
    <tr>
    	<td colspan="2" class="campoesquerda" style="text-align:center; font-size:18px">Hist&oacute;rico:</td>
    </tr>
    <?php
	$sql = "SELECT data,date_format(data,'%d/%m/%Y') AS data2, hora, mensagem FROM cs2.historico_nfe
			WHERE codloja = $codloja
			order by data,hora";
	$qry = mysql_query($sql, $con);
    while ( $reg = mysql_fetch_array($qry) ){
		$data     = $reg['data2'];
		$hora     = $reg['hora'];
		$mensagem = $reg['mensagem'];
		
		echo "
		<tr>
    		<td class='campoesquerda'>$data - $hora</td>
			<td class='campoesquerda'>$mensagem</td>
    	</tr>
		";
		
	}
	?>
</table>
</form>
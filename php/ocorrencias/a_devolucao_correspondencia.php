<?
// require "connect/sessao.php";

$data      = date('d/m/Y');

if (empty($go)) {

?>
<script type="text/javascript" src="../js/jquery-3.1.1.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.1.1.js"></script>
<script language="javascript">

function pesquisa(dados){
	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","grava_devol_correspondencia.php?codigo="+dados.value,false);
	xmlhttp.send();
	var retorno = xmlhttp.responseText;
	
	alert(retorno);
}



</script>
<br><br><br>
<form name='form'>
<table width=70% align="center">
	<tr>
		<td colspan="3" align="center" class="titulo">Inclus&atilde;o de Devolu&ccedil;&atilde;o de Correspondencia</td>
	</tr>
	<tr class="bodyText">
		<td colspan="3" height="15" bgcolor="#CCCCCC"></td>
	</tr>
	<tr>
		<td colspan="3">
			<table width="80%" cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td width="15%">Data de Devolu&ccedil;&atilde;o:</td>
					<td width="24%">
						<input type="text" name="data" size="10" maxlength="10" onkeyup="dataNas(this)" onkeypress="soNumero();" onblur="validaDat(this,this.value)" value="<?=$data?>"/>
					</td>
				</tr>
				<tr >
					<td width="15%">C&oacute;digo:</td>
					<td>
						<input type="text" name="cliente" size="10" onchange="pesquisa(this)" />
					</td>
				</tr>
            </table>
		</td>
	</tr>
</table>

<?php
} // if go=null

if ($go=='ingressar') {
	if (($tipo == "a") || ($tipo == "c")) {
		$sql = "select mid(a.logon,1,5) as logon, b.id_franquia, b.codloja from logon a
				inner join cadastro b on a.codloja=b.codloja
				where mid(logon,1,5)='$codigo'";
	} else {
		$sql = "select mid(a.logon,1,5) as logon, b.id_franquia, b.codloja from logon a
				inner join cadastro b on a.codloja=b.codloja
				where mid(logon,1,5)='$codigo' and id_franquia='$id_franquia'";
	}
	$resulta = mysql_query($sql,$con);
	$linha = mysql_num_rows($resulta);
	if ($linha == 0) {
		print"<script>alert(\"Cliente n�o existe ou nao pertence a sua franqu�a!\");history.go(-1)</script>";
	} else {
		$matriz = mysql_fetch_array($resulta); 
		$codloja = $matriz['codloja'];
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=ocorrencias/mensagens.php&codloja=$codloja\";>";
	}
}
?>
</form>
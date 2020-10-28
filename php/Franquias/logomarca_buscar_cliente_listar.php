<?php
require ("connect/sessao.php");
if (($tipo == "a") || ($tipo == "c")) {
$sql = "SELECT b.codloja, b.nomefantasia, b.razaosoc, b.codloja, a.logon, b.id_franquia 
		FROM logon a
		INNER JOIN cadastro b on a.codloja=b.codloja
		WHERE mid(logon,1,LOCATE('S',logon)-1)='{$_REQUEST['codigo']}'";
} else {
$sql = "SELECT b.codloja, b.nomefantasia, b.razaosoc, b.codloja, a.logon, b.id_franquia 
		FROM logon a
		INNER JOIN cadastro b on a.codloja=b.codloja
		WHERE  mid(logon,1,LOCATE('S',logon)-1)='{$_REQUEST['codigo']}' AND id_franquia='$id_franquia'";
}

$qry = mysql_query($sql, $con);
if (mysql_num_rows($qry) == 0) {
	//echo "<meta http-equiv='refresh' content='9898890; url=painel.php?pagina1=Franquias/logomarca_buscar_cliente.php&no=1'>";	
	exit;
} else {
	$nome_fantasia = mysql_result($qry,0,'nomefantasia');
	$razaosoc = mysql_result($qry,0,'razaosoc');
	$codloja = mysql_result($qry,0,'codloja');
}
?>
<form enctype="multipart/form-data" method="post" action="painel.php?pagina1=Franquias/logomarca_bd.php" name="form">
<input type="hidden" value="<?=$_REQUEST['codigo']?>" name="codigo">
<input type="hidden" value="<?=$codloja?>" name="codloja">
<table width="80%" border="0" align="center">
  <tr class="titulo">
    <td colspan="2">INSERIR LOGOMARCA DO CLIENTE</td>
  </tr>
  <tr>
    <td class="subtitulodireita" colspan="2">&nbsp;</td>    
  </tr>
  <tr>
    <td class="subtitulodireita" width="30%">Nome Fantasia</td>
    <td class="subtitulopequeno" width="70%"><?=$nome_fantasia?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td class="subtitulopequeno"><?=$razaosoc?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Logormarca</td>
    <td class="subtitulopequeno"><input name="image" accept="image/jpeg" type="file">
</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Recomenda&ccedil;&otilde;es</td>
    <td class="subtitulopequeno">A imagem que for enviada deve seguir ao padr&atilde;o descrito abaixo:
    	<br>Formato: <b>JPG</b></td>
  </tr><tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left"><input type="submit" value="&nbsp;&nbsp;Enviar Logomarca&nbsp;&nbsp;" /></td>
  </tr>
</table>
</form>
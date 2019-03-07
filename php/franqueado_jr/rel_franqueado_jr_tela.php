<?php
session_start();
$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];

if($tipo != "a"){
	$franqueado = $_SESSION["id"];
	echo "<meta http-equiv='refresh' content='0; url=painel.php?pagina1=franqueado_jr/rel_franqueado_junior_listagem.php&franqueado=$franqueado'>";		
	exit;
}
?>
<script language="javascript">
function relatorio(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=franqueado_jr/rel_franqueado_junior_listagem.php';
	frm.submit();
 }
 
function valida(){
 	frm = document.form;
	if(frm.franqueado.value <= 0 ){
		alert('Por favor escollha uma Franqueia !');		
		frm.franqueado.focus();
		return false;
	}
	relatorio();
}  
</script>
<style type="text/css">
form {margin: 30px 50px 0;}
form input, select {
	font-family: Arial;
	font-size: 8pt;
}
</style>
<br>
<form method="post" action="#" name="form">
<table width=70% border="0" align="center">
  <tr class="titulo">
    <td colspan="2">Relat&oacute;rio - Franqueado J&uacute;nior</td>
  </tr>
  <tr>
    <td width="30%" class="subtitulodireita">&nbsp;</td>
    <td width="70%" class="campoesquerda">&nbsp;</td>
  </tr>
   <?php if($tipo == "a"){?>
   <tr>
   		<td class="subtitulodireita">Franquia</td>
        <td class="campoesquerda">
        <select name="franqueado" style="width:85%">
        	<option value="0">&nbsp;</option>
        <?php
			$sql = "SELECT id, fantasia FROM franquia WHERE classificacao != 'J' ORDER BY id";
			$resposta = mysql_query($sql,$con);
			while ($array = mysql_fetch_array($resposta))
			{
				$id = $array["id"];
				$nome_franquia = $array["fantasia"];
				if($_REQUEST['franqueado'] == $id)echo "<option value='$id'  selected='selected'>$id - $nome_franquia</option>";
					else echo "<option value='$id'>$id - $nome_franquia</option>";
			}
		?>
        </select></td>
   </tr> 
   <?php }else{ ?> 
   <tr>
		<td class="subtitulodireita">Franquia MASTER</td>
	    <td class="campoesquerda"><?php echo $nome_franquia_master; ?></td>
   </tr>
   <?php } ?>   
 
  
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">
		<input name="enviar" type="button" id="enviar" value="         Verificar" onclick="valida()" style="cursor:pointer"/>	</td>
  </tr>
</table>
</form>

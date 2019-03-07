<?php
require "connect/sessao.php";
include "ocorrencias/config.php";
$codloja = $_POST['codloja'];

$comando = "select razaosoc from cadastro where codloja='$codloja'";
$conex = mysql_query($comando, $con);;
$matriz = mysql_fetch_array($conex);
$hoje = date('d/m/Y H:i');
?>
<script language="javascript">
//função para validar clientes no cadastramento
function validaClientes(){
// validar atendente
d = document.postar;
/*if (d.atendente.value == ""){
	alert("O campo " + d.atendente.name + " deve ser preenchido!");
	d.atendente.focus();
	return false;
}*/
// validar ocorrencia
if (d.ocorrencia.value == ""){
	alert("O campo " + d.ocorrencia.name + " deve ser preenchido!");
	d.ocorrencia.focus();
	return false;
}
return true;
}
</script>
<form name="postar" method="post" onSubmit="return validaClientes();" action="franqueado_jr/ocorrencia_gravar.php" >
<table width="90%" border="0" align="center">
<tr>
  <td colspan="2" class="titulo">REGISTRAR UMA OCORR&Ecirc;NCIA</td>
</tr>
<tr>
  <td class="campoesquerda">&nbsp;</td>
  <td class="campoesquerda">(*) preenchimento obrigat&oacute;rio</td>
  </tr>
<tr>
  <td class="subtitulodireita">ID do cliente:</td>
  <td class="subtitulopequeno"><?php echo $codloja; ?><input name="codigo" type="hidden" value="<?php echo $codloja; ?>" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Nome do Cliente:</td>
  <td class="subtitulopequeno">
  	<?php echo $matriz['razaosoc']; ?>
    <input name="codigo2" type="hidden" value="<?php echo $matriz['razaosoc']; ?>" >
  </td>
</tr>
<tr>
  <td class="subtitulodireita">Franqu&iacute;a:</td>
  <td class="subtitulopequeno"><?php
	$sql = "select * from franquia where id='$id_franquia' order by id";
	$resposta = mysql_query($sql);

	while ($array = mysql_fetch_array($resposta))
	{
		$id		= $array["id"];
		$nome_franquia	= $array["fantasia"];
		
		echo $nome_franquia;
	}
	?>
    <input type="hidden" name="franquia" value="<?php echo $id; ?>" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Tipo de Ocorr&ecirc;ncia</td>
  <td class="subtitulopequeno"><select name="tipo_ocorr" class="boxnormal">
    <option value="1">Cobran&ccedil;a</option>
    <option value="2">Atendimento</option>
    <option value="3">Administrativo</option>
    <option value="4">Comercial</option>
  </select></td>
</tr>
<tr>
  <td class="subtitulodireita">Atendente * </td>
  <td class="subtitulopequeno"><input type="text" name="atendente" size="40" maxlength="40" class="boxnormal" >
  <?
	if ($tipo == "b") $frq =  "where franquia='$id_franquia'";
	else $frq = "";
	echo "<select name=\"atendente2\" class=boxnormal>";
	echo "<option>.: Selecione :.</option>";
	$sql = "select id, atendente from cs2.atendentes $frq order by atendente";
	$resposta = mysql_query($sql);
	while ($array = mysql_fetch_array($resposta)) {
		$id_atendente   = $array["id"];
		$nome_atendente = $array["atendente"];
		echo "<option value=\"$id_atendente\">$nome_atendente</option>\n";
	}
	echo "</select>";
  ?>
  </td>
</tr>
<tr>
  <td valign="top" class="subtitulodireita">Descri&ccedil;&atilde;o*:</td>
  <td class="subtitulopequeno"><textarea name="ocorrencia" cols="50" rows="8" wrap="VIRTUAL" style="font-family: Verdana; font-size: 10 pt"></textarea></td>
</tr>
<tr>
  <td class="subtitulodireita">Data e hora atual:</td>
  <td class="subtitulopequeno"><?php echo $hoje; ?></td>
</tr>
<tr>
  <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
<tr>
  <td colspan="2"><br>
    <p align="center">  
  <input type="submit" name="Submit" value="  Enviar              ">
  <input type="reset" name="reset" value="              Apagar  ">
    </p></td>
</tr>
</table>
</form>
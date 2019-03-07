<?
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;
?>
<script src="../../js/funcoes.js"></script>
<body>
<form name="incluivend" method="post" action="Franquias/cad_vendedor.php" onSubmit="return validaVendedor()">
<table width="70%" border="0" align="center">
  <tr class="titulo">
    <td colspan="2">INCLUS&Atilde;O DE CONSULTOR PARA A FRANQUIA </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Nome Completo </td>
    <td class="campoesquerda"><input type="text" name="nomec" size="60" maxlength="80" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">CPF</td>
    <td class="campoesquerda"><input name="cpf" type="text" maxlength="14" size="17" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data de nascimento</td>
    <td class="campoesquerda">
	<input name="nasce" type="text"> (dd/mm/yyyy)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Tipo de consultor</td>
    <td class="campoesquerda"><select name="cbotipo">
		<option selected>:: Escolha ::</option>
		<option value="1">Franqueado</option>
		<option value="2">Consultor vendas</option>
		<option value="3">Consultor pós-vendas</option>
		<option value="4">Ag. Neg. Franquias</option>
	</select>	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">E-mail</td>
    <td class="campoesquerda"><input name="email" type="text"> (xxx@xx.xx)</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Sexo</td>
    <td class="campoesquerda">
		<input name="sexo[]" type="radio" value="masculino" id="sexo">Masculino
        <input name="sexo[]" type="radio" value="feminino" id="sexo">Feminino	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franqu&iacute;a</td>
    <td class="campoesquerda"><?php
	$sql = "select * from franqueados where id='$nome' order by id";
	$resposta = mysql_query($sql);

	while ($array = mysql_fetch_array($resposta))
	{
		$id		= $array["id"];
		$nome_franquia	= $array["nome_franquia"];
		
		echo $nome_franquia;
	}
	?>
	<input type="hidden" name="franqueado" value="<?php echo $id; ?>" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
	<td class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center">
			<input type="reset" value="             Cancelar" name="cancela" />
            <input name="enviar" type="submit" value="              Enviar">
            <input name="button" type="button" onClick="javascript: history.back();" value="             Voltar" /></td>
  </tr>
</table>
</form>
</body>
</html>

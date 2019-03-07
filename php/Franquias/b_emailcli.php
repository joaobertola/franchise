<?
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;
?>
<form method="post" action="painel.php?pagina1=Franquias/b_emailcli1.php" > 
<table width="70%" border="0" align="center">
	<tr class="titulo">
		<td colspan="2">Escolha como  quer a lista de e-mails</td>
	</tr>
	<tr>
		<td class="subtitulodireita">&nbsp;</td>
		<td class="campoesquerda">&nbsp;</td>
	</tr>
	<tr>
		<td class="subtitulodireita">Ordem da Lista</td>
		<td class="campoesquerda">
			<input name="criterio" type="radio" value="a.codloja" checked />C&Oacute;DIGO <br>
			<input name="criterio" type="radio" value="a.razaosoc" />ALFAB&Eacute;TICA</td>
	</tr>
	<tr>
	  <td class="subtitulodireita">&nbsp;</td>
	  <td class="campoesquerda"><input type="radio" name="status" value="0" />Ativos <input type="radio" name="status" value="1" />Cancelados <input type="radio" name="status" value="2" checked />Todos </td>
    </tr>
	<tr>
	  <td class="subtitulodireita">N&uacute;mero de registros por p&aacute;gina</td>
	  <td class="campoesquerda"><select name="lpp" >
        <option value="10">10</option>
        <option value="20" selected>20</option>
        <option value="30">30</option>
      </select></td>
    </tr>
	<tr>
		<td class="subtitulodireita">&nbsp;</td>
		<td class="campoesquerda">&nbsp;</td>
	</tr>
	<tr class="titulo">
		<td colspan="2"><input type="submit" value="ordenar"></td>
	</tr>
</table>
</form> 
<?php
require "connect/sessao.php";

$mes_atual = date('m');
$ano_atual = date('y');
?>
<script type="text/javascript" language="javascript">
function habilitaVenc() {
	d = document.form1;
	if (d.contano.value === "todos"){
		d.contmes.disabled = true;
		d.contmes.value = "todos";
		d.contmes.style.backgroundColor = "#CCCCCC";
	}
	else {
		d.contmes.disabled = false;
		d.contmes.style.backgroundColor = "#FFFFFF";
	}
return true;
}
</script>
<style type=text/css">
form {margin: 30px 50px 0;}
form input, select {
	font-family: Arial;
	font-size: 8pt;
}
</style>

<form name="form1" method="post" action="painel.php?pagina1=Franquias/b_extratocontratos_cancelados2.php">
<table width=90% border="0" align="center">
  <tr class="titulo">
    <td colspan="2"><p>RANKING DE CANCELAMENTO POR VENDEDOR</p></td>
  </tr>
  <tr>
    <td class="subtitulodireita" width="40%">&nbsp;</td>
    <td class="subtitulopequeno" width="60%">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franquia</td>
	<td class="subtitulopequeno">
			<?php
    	if (($tipo == "a") || ($tipo == "c") || ($tipo == "d")) {  
			echo "<select name=\"franqueado\">";
			$sql = "select id, fantasia from franquia where classificacao != 'J' order by id";
			$resposta = mysql_query($sql,$con);
			while ($array = mysql_fetch_array($resposta)) {
				$franquia   = $array["id"];
				$nome_franquia = $array["fantasia"];
				echo "<option value=\"$franquia\">$nome_franquia</option>\n";
			}
			echo "</select>";
		}
		else {
			echo $nome_franquia;
			echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia />";
		}
		?>
</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="pesq1" value="    Pesquisar    " />
      <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
    </tr>
</table>
</form>
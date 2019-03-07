<?php
require "connect/sessao.php";

if ( $class == 'J' )
	$nome_classe = "Franqueado Junior";
elseif ( $class == 'X' )
	$nome_classe = "Micro Franqueado";
	
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
<style type"text/css">
form {margin: 30px 50px 0;}
form input, select {
	font-family: Arial;
	font-size: 8pt;
}
</style>

<form name="form1" method="post" action="painel.php?pagina1=franqueado_jr/extrato_contratos_ver.php">
<table width=90% border="0" align="center">
  <tr class="titulo">
    <td colspan="2"><p>EXTRATO DE CONTRATOS ( <?=$nome_classe?> )</p>    </td>
  </tr>
  <tr>
    <td class="subtitulodireita" width="40%">&nbsp;</td>
    <td class="subtitulopequeno" width="60%">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Ano</td>
    <td class="campoesquerda">
		<select name="contano" size="1" class="formulariopequeno" onChange="habilitaVenc(this)" >
		<?php
			$ano = date('Y');
			for ( $i = $ano ; $i >= 2011 ; $i-- ){ 
				if ( $i == $ano ) echo "<option value='$i' selected='selected'> $i </option>";
				else echo "<option value='$i'> $i </option>";
			} ?>
			<option value="todos">Todos</option>      
		</select>
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">M&ecirc;s</td>
    <td class="campoesquerda">
	<select name="contmes" size="1" class="formulariopequeno" >
	  <option value="todos">Todos</option>
	  <option value="01" <?php if ($mes_atual == "01"){ echo "selected"; }?>>Janeiro</option>
      <option value="02" <?php if ($mes_atual == "02"){ echo "selected"; }?>>Fevereiro</option>
      <option value="03" <?php if ($mes_atual == "03"){ echo "selected"; }?>>Mar&ccedil;o</option>
      <option value="04" <?php if ($mes_atual == "04"){ echo "selected"; }?>>Abril</option>
      <option value="05" <?php if ($mes_atual == "05"){ echo "selected"; }?>>Maio</option>
      <option value="06" <?php if ($mes_atual == "06"){ echo "selected"; }?>>Junho</option>
      <option value="07" <?php if ($mes_atual == "07"){ echo "selected"; }?>>Julho</option>
      <option value="08" <?php if ($mes_atual == "08"){ echo "selected"; }?>>Agosto</option>
      <option value="09" <?php if ($mes_atual == "09"){ echo "selected"; }?>>Setembro</option>
      <option value="10" <?php if ($mes_atual == "10"){ echo "selected"; }?>>Outubro</option>
      <option value="11" <?php if ($mes_atual == "11"){ echo "selected"; }?>>Novembro</option>
      <option value="12" <?php if ($mes_atual == "12"){ echo "selected"; }?>>Dezembro</option>
	</select>
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita"><input type="radio" value="3" name="opcao" checked /></td>
    <td class="campoesquerda">Extrato de <font color="#FF0000">TODOS</font> os contratos </td>
  </tr>
  <tr>
    <td class="subtitulodireita"><input type="radio" value="0" name="opcao" /></td>
    <td class="campoesquerda">Extrato de contratos <font color="#FF0000">ATIVOS</font></td>
  </tr>
  <tr>
    <td class="subtitulodireita"><input type="radio" value="1" name="opcao" /></td>
    <td class="campoesquerda">Extrato de contratos<font color="#FF0000"> BLOQUEADOS</font></td>
  </tr>
  <tr>
    <td class="subtitulodireita"><input type="radio" value="2" name="opcao" /></td>
    <td class="campoesquerda">Extrato de contratos <font color="#FF0000">CANCELADOS </font></td>
  </tr>
  <tr>
    <td class="subtitulodireita"><input type="radio" value="4" name="opcao" /></td>
    <td class="campoesquerda">Extrato de contratos <font color="#FF0000">PENDENTES DE REGULARIZA&Ccedil;&Atilde;O CONTRATUAL</font></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Ordenar por </td>
    <td class="campoesquerda"><input name="ordenacao" type="radio" value="logon" checked >
      C&oacute;digo
        <input name="ordenacao" type="radio" value="nomefantasia">
      Nome de fantas&iacute;a
      
      <input type="radio" value="dt_cad" name="ordenacao" />
      Data de contrata&ccedil;&atilde;o </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Franquia JUNIOR</td>
    <td class="subtitulopequeno">
			<?php
    	if (($tipo == "a") || ($tipo == "c") || ($tipo == "d")) {  
			echo "<select name=\"franqueado\">";
			if ($tipo == "a" ) echo "<option value=\"todos\" selected>Todas as Franquias</option>";
			$sql = "select id, fantasia from franquia where sitfrq=0 order by id";
			$resposta = mysql_query($sql);
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
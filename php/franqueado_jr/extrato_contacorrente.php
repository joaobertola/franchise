<?php

require "connect/sessao_r.php";


$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($ss_classificacao == "X")) exit;

$mes_atual = date('m');

$sql = "SELECT limite_credito FROM cs2.franquia WHERE id = $id_franquia";

$qry = mysql_query($sql,$con) or die("Erro SQL: $sql");
$limite_credito = mysql_result($qry,0,'limite_credito');

$sql = "SELECT sum(valor_parcela) AS valor 
		FROM cs2.cadastro_emprestimo_franquia
		WHERE id_franquia = $id_franquia
		AND valor_pagamento IS NULL";
$qry = mysql_query($sql,$con) or die("Erro SQL: $sql");
$valor_parcela_avencer = mysql_result($qry,0,'valor');
$valor_parcela_avencer *= 1; 
$limite_credito = $limite_credito - $valor_parcela_avencer;

include("area_restrita/janela_limite_credito.php");

?>


<form method = "post" action = "painel.php?pagina1=franqueado_jr/extrato_contacorrente1.php" >
<table width=560 border="0" align="center">
  <tr class="titulo">
    <td colspan="2">CONTA CORRENTE DO MICRO FRANQUEADO: <?php echo $nome_franquia; ?><input name="id" id="id" type="hidden" value="<?php echo $id_franquia; ?>" ></td>
  </tr>	
      <tr>
        <td class="subtitulodireita">&nbsp;</td>
        <td class="campoesquerda">&nbsp;</td>
      </tr>
              <td class="subtitulodireita">Selecione o tipo de relat&oacute;rio a ser emitido</td>
              <td class="campoesquerda"><input name="situacao" type="radio" value="1" checked /> 
              Consulta do m&ecirc;s atual
              <br>
              <input name="situacao" type="radio" value="2" />
              Consulta do m&ecirc;s de
              <select name="mes" size="1">
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
			<select name="ano" size="1">
			<?php
				$ano = date('Y');
				for ( $i = $ano ; $i >= 2011 ; $i-- ){ 
					if ( $i == $ano ) echo "<option value='$i' selected='selected'> $i </option>";
					else echo "<option value='$i'> $i </option>";
				} ?>
			</select></td>
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
    <td align="center"><input type="submit" name="enviar" id="enviar" value="    Emitir    " />
    <input name="button" type="button" onClick="javascript: history.back();" value="      Voltar      " /></td>
  </tr>
</table>
</form>
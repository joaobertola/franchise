<?php
require "connect/sessao.php";

$mes_atual = date('m');
$ano_atual = date('y');
?>
<body>
<form method="post" action="painel.php?pagina1=ranking/ranking_gerente_franquias_listagem.php">
<table width="70%" border="0" align="center">
  <tr>
    <td colspan="2" class="titulo">RANKING GERENTE DE FRANQUIAS</td>
  </tr>
  <tr>
    <td width="38%" class="subtitulodireita">&nbsp;</td>
    <td width="62%" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">Ano</td>
    <td class="campoesquerda"><select name="ano" size="1" class="formulariopequeno" >
        <option value="2015" <?php if ($ano_atual == "15"){ echo "selected"; }?>> 2015 </option>
        <option value="2014" <?php if ($ano_atual == "14"){ echo "selected"; }?>> 2014 </option>
        <option value="2013" <?php if ($ano_atual == "13"){ echo "selected"; }?>> 2013 </option>
        <option value="2012" <?php if ($ano_atual == "12"){ echo "selected"; }?>> 2012 </option>
        <option value="2011" <?php if ($ano_atual == "11"){ echo "selected"; }?>> 2011 </option>
        <option value="2010" <?php if ($ano_atual == "10"){ echo "selected"; }?>> 2010 </option>
    	<option value="2009" <?php if ($ano_atual == "09"){ echo "selected"; }?>> 2009 </option>
        <option value="2008" <?php if ($ano_atual == "08"){ echo "selected"; }?>> 2008 </option>
        <option value="2007" <?php if ($ano_atual == "07"){ echo "selected"; }?>> 2007 </option>
        <option value="2006"> 2006 </option>
        <option value="2005"> 2005 </option>
      </select></td>
  </tr>
  <tr>
    <td class="subtitulodireita">M&ecirc;s</td>
    <td class="campoesquerda"><select name="mes" size="1">
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
    </select></td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left"><input type="submit" name="pesq1" value="    Pesquisar    " />
    &nbsp;&nbsp;&nbsp;
    <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
  </tr>
</table>
</form>
</body>
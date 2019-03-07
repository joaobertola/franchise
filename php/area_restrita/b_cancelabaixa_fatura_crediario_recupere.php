<?php
require "connect/sessao.php";

$numdoc = $_GET['numdoc'];

$comando = "SELECT a.codloja, c.razaosoc, a.numdoc, a.vencimento, a.valor, a.datapg, a.valorpg, a.descricao_repasse
						   FROM cs2.titulos_recebafacil a
						   INNER JOIN cs2.logon b on a.codloja=b.codloja
						   INNER JOIN cs2.cadastro c on a.codloja=c.codloja
						   WHERE a.numdoc = '$numdoc'";

$conex = mysql_query($comando, $con);
$matriz = mysql_fetch_array($conex);
?>
<body>
<form name="baixa" method="post"  OnSubmit="true" action="area_restrita/b_cancela_baixa_titulos_recebafacil_f.php" >
<table width="90%" border="0" align="center">
<tr>
  <td colspan="2" class="titulo">CANCELAMENTO de Baixa de T&iacute;tulos [Credi&aacute;rio / Recupere]</td>
</tr>
<tr>
  <td colspan="2" class="campoesquerda">&nbsp;</td>
  </tr>
<tr>
  <td class="subtitulodireita">N&ordm; do T&iacute;tulo</td>
  <td class="subtitulopequeno"><?php echo $matriz['numdoc']; ?>
  <input name="numdoc" type="hidden" value="<?php echo $matriz['numdoc']; ?>" ></td>
  <input name="acao" type="hidden" value="cancela_pagamento" ></td>
</tr>
<tr>
  <td class="subtitulodireita">Sacado</td>
  <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Valor</td>
  <td class="subtitulopequeno"><?php echo $matriz['valor']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Data de Pagamento </td>
  <td class="subtitulopequeno"><?php echo $matriz['datapg']; ?></td>
</tr>
<tr>
  <td class="subtitulodireita">Valor pago </td>
    <td class="subtitulopequeno"><?php echo $matriz['valorpg']; ?></td>
</tr>

<tr>
  <td colspan="2" class="campoesquerda"></td>
</tr>
<tr>
  <td colspan="2" class="titulo"><?php echo 'Msg: '.$matriz['descricao_repasse']; ?></td>
  </tr>
<tr>
  <td colspan="2"><br>
    <p align="center">  
  <input type="submit" name="Submit" value="  Cancelar Pagamento  ">
  <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " />
    </p></td>
</tr>
</table>
</form>
</body>
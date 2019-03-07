<?php
require "connect/sessao.php";

$numdoc = $_REQUEST['numdoc'];

$sql = " SELECT numboleto, valorpg, valor, numdoc, codloja, 
				date_format(datapg, '%d/%m/%Y') AS datapg,  
				date_format(vencimento, '%d/%m/%Y') AS vencimento,
				date_format(emissao, '%d/%m/%Y') AS emissao
		  FROM cs2.titulos_recebafacil_excluidos
		  WHERE numdoc like '%$numdoc'";
		  
$qry = mysql_query($sql,$con);
$linhas = mysql_num_rows ($qry);
$numboleto  = mysql_result($qry,0,'numboleto');
$datapg   = mysql_result($qry,0,'datapg');
$valorpg = mysql_result($qry,0,'valorpg');
$valor  = mysql_result($qry,0,'valor');
$vencimento = mysql_result($qry,0,'vencimento');
$emissao = mysql_result($qry,0,'emissao');
$numdoc = mysql_result($qry,0,'numdoc');
$codloja = mysql_result($qry,0,'codloja');

if($linhas == 0){?>
	<script language="javascript">
        alert('A Consulta nao retorno nenhum registro ! ');
        window.location.href="painel.php?pagina1=area_restrita/voltar_titulo_form.php";
    </script>	
<?php
	exit;
}
?>

<script language="javascript">
function confirma() {
	frm = document.form;
	frm.action = 'painel.php?pagina1=area_restrita/voltar_titulo_bd.php';
	frm.submit();
}  
  
function retorna() {
	frm = document.form;
	frm.action = 'painel.php?pagina1=area_restrita/voltar_titulo_form.php';
	frm.submit();
}  
</script>

<form name="form" method="post" action="#">
<input type="hidden" name="numdoc" value="<?=$numdoc?>">
<input type="hidden" name="codloja" value="<?=$codloja?>">
<table border="0" width="60%" align="left" cellpadding="0" cellspacing="1" style="border: 1px solid #D1D7DC; background-color:#FFFFFF">
  <tr>
    <td colspan="2" align="center" class="titulo">Voltar T&iacute;tulo Exclu&iacute;do</td>
  </tr>

  <tr>
    <td width="25%" class="subtitulodireita" height="23">N&uacute;mero do Documento</td>
    <td width="75%" class="campoesquerda"><?=$numdoc?></td>
  </tr>

  <tr>
    <td class="subtitulodireita" height="23">Emiss&atilde;o</td>
    <td class="campoesquerda"><?=$emissao?></td>
  </tr>

  <tr>
    <td class="subtitulodireita" height="23">Vencimento</td>
    <td class="campoesquerda"><?=$vencimento?></td>
  </tr>

  <tr>
    <td class="subtitulodireita" height="23">Valor</td>
    <td class="campoesquerda"><?=number_format($valor,2,',','.')?></td>
  </tr>

  <tr>
    <td class="subtitulodireita" height="23">Valor Pago</td>
    <td class="campoesquerda"><?=number_format($valorpg,2,',','.')?></td>
  </tr>

  <tr>
    <td class="subtitulodireita" height="23">Data Pago</td>
    <td class="campoesquerda"><?=$datapg?></td>
  </tr>

  <tr>
    <td class="subtitulodireita" height="23">N&uacute;mero Boleto</td>
    <td class="campoesquerda"><?=$numboleto?></td>
  </tr>

  <tr><td colspan="2" class="titulo">&nbsp;</td></tr>
  
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="Confirma" name="enviar" onClick="confirma();" style="cursor:pointer" />&nbsp;&nbsp;&nbsp;<input type="button" value="Voltar" name="voltar" onClick="retorna();" style="cursor:pointer" /></td>
  </tr>
</table>

</form>
<?php
require "connect/sessao.php";

$codloja = $_GET['codloja'];
$logon = $_GET['logon'];

$comando = "select a.codloja, MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, a.razaosoc, a.nomefantasia, 
			date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, a.tipo_cliente,
			d.descsit, a.tx_mens from cadastro a
			inner join situacao d on a.sitcli=d.codsit
			inner join logon b on a.codloja=b.codloja
			where a.codloja='$codloja'";

$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$codloja = $matriz['codloja'];

if ( $matriz['tipo_cliente'] == 'A' )
	$command = "select a.tpcons, b.nome, a.qtd, a.consumo from cons_liberada_logon a 
				inner join valcons b on a.tpcons=b.codcons
				where a.codloja=$codloja and MID(a.logon,1,LOCATE('S', a.logon) - 1) = $logon";

else
	$command = "select a.tpcons, b.nome, a.qtd, a.consumo from cons_liberada a 
				inner join valcons b on a.tpcons=b.codcons
				where a.codloja=$codloja";

$result = mysql_query ($command, $con);
$linhas = mysql_num_rows ($result);
$linhas1 = $linhas + 3;
?>
<script src="../js/funcoes.js"></script>
<table border="0" align="center" width="643">
  <tr>
    <td colspan="2" class="titulo" align="center">LIMITE DE CONSULTAS</td>
  </tr>
  <tr>
    <td class="subtitulodireita">ID</td>
    <td class="subtitulopequeno"><?php echo $matriz['codloja']; ?><input type="hidden" name="codloja" value="<?php echo $matriz['codloja']; ?>" /></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo de Cliente </td>
    <td class="campojustificado"><?php echo $matriz['logon']; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cliente desde</td>
    <td class="subtitulopequeno"><?php echo $matriz['data']; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Mensalidade</td>
    <td valign="top" class="subtitulopequeno">R$&nbsp;<?php echo $matriz['tx_mens']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Limite de consultas </td>
    <td>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
	 		<tr>
				<td colspan="7" height="1" bgcolor="#999999"></td>
			</tr>
	 		<tr>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
			<tr height="20">
				
				<td align="center" class="campoesquerda">Tipo</td>
				<td align="center" class="campoesquerda">Descri&ccedil;&atilde;o</td>
				<td align="center" class="campoesquerda">Qtd. Lib.</td>
				<td align="center" class="campoesquerda">Usado</td>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
			<tr>
				<td colspan="6" height="1" bgcolor="#666666">				</td>
			</tr>
		<?php
			for ($a=1; $a<=$linhas; $a++) {
				$matrix = mysql_fetch_array($result);
				$cons = $matrix['tpcons'];
				$produto = $matrix['nome'];
				$venda = $matrix['qtd'];
				$gratuidade = $matrix['consumo'];
				echo "<tr height=\"22\" class=\"bodyText\"";
				if (($a%2) == 0) echo "bgcolor=\"#BDD9E7\">";
				else echo "bgcolor=\"lightsteelblue\">";
				echo "	<td align=\"center\">$cons</td>
						<td align=\"left\">$produto</td>
						<td>$venda</td>
						<td align=\"center\">$gratuidade</td>
					</tr>";
				}
				echo "<tr>
						<td colspan=\"6\" align=\"right\" height=\"1\" bgcolor=\"#666666\"></td>
					</tr>";
			?>			
		</table>	</td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp; </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
		<input name="button" type="button" onClick="location.href='painel.php?pagina1=Franquias/b_liberaconsulta.php'" value="       Voltar       " />
	</td>
  </tr>
  <tr align="right">
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<?php $res = mysql_close ($con); ?>
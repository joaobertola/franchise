<?php

require "connect/sessao.php";

$codigo = $_REQUEST['codigo'];

$sql = "select MID(a.logon,1,LOCATE('S', a.logon) - 1) as logon, b.id_franquia, b.codloja, b.razaosoc from logon a
		inner join cadastro b on a.codloja=b.codloja
		where MID(logon,1,LOCATE('S', logon) - 1)='$codigo'" ;
if ( $class == 'J' )
	$sql .= " and b.id_franquia='$id_franquia_master' and b.id_franquia_jr='$id_franquia'";
else
	$sql .= " and b.id_franquia='$id_franquia'";
	
$resulta = mysql_query( $sql );
$linha = mysql_num_rows($resulta);
if ($linha == 0)
{
	print"<script>alert(\"Cliente n�o Existe ou n�o pertence � sua Franquia!\");history.back();</script>";
	exit;
}

$comando = "select a.codloja, a.razaosoc, a.nomefantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit, a.tx_mens from cadastro a inner join situacao d on a.sitcli=d.codsit inner join logon e on a.codloja=e.codloja where MID(logon,1,LOCATE('S', logon) - 1)='$codigo'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$codloja = $matriz['codloja'];
$command = "select a.codcons, b.nome, a.valorcons, b.vr_custo, c.qtd from valconscli a inner join valcons b on a.codcons=b.codcons left join bonificadas c on a.codloja = c.codloja  and a.codcons = c.tpcons where a.codloja=$codloja";
$result = mysql_query ($command, $con);
$linhas = mysql_num_rows ($result);
$linhas1 = $linhas + 3;

?>
<script language="JavaScript1.2">
<!--
function DoPrinting(){
if (!window.print){
alert("Use o Netscape  ou Internet Explorer \n nas vers�es 4.0 ou superior!")
return
}
window.print()
}
//-->
</script>

<body>
<table border="0" align="center" width="643">
  <tr>
    <td colspan="2" class="titulo" align="center">TABELA DE PRE&Ccedil;O DO CLIENTE: <font color="red"><?php echo $matriz['nomefantasia']; ?></font></td>
  </tr>
  <tr>
    <td class="subtitulodireita">ID</td>
    <td class="subtitulopequeno"><?php echo $matriz['codloja']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo de Cliente </td>
    <td class="campojustificado"><?php echo $codigo; ?></td>
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
    <td class="subtitulodireita">Tabela de Pre&ccedil;os</td>
    <td>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
	 		<tr>
				<td colspan="7" height="1" bgcolor="#999999"></td>
			</tr>
	 		<tr>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
			<tr height="20">
				<td align="center" class="campoesquerda">C&oacute;digo</td>
				<td align="center" class="campoesquerda">Produto</td>
				<td align="center" class="campoesquerda">Venda</td>
				<td align="center" class="campoesquerda">Gratuidade</td>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
			<tr>
				<td colspan="7" height="1" bgcolor="#666666">				</td>
			</tr>
		<?php 
			for ($a=1; $a<=$linhas; $a++) {
				$matrix = mysql_fetch_array($result);
				$codigo = $matrix['codcons'];
				$produto = $matrix['nome'];
				$venda = $matrix['valorcons'];
				$custo = $matrix['vr_custo'];
				$gratuidade = $matrix['qtd'];
				echo "<tr height=\"22\">
						<td align=\"center\" class=\"subtitulopequeno\">$codigo</td>
						<td align=\"left\" class=\"subtitulopequeno\">$produto</td>
						<td align=\"right\" class=\"subtitulopequeno\">$venda</td>
						<td align=\"center\" class=\"subtitulopequeno\">$gratuidade</td>
					</tr>";
				}
				echo "<tr>
						<td colspan=\"6\" align=\"right\" height=\"1\" bgcolor=\"#666666\"></td>
					</tr>";
			?>			
		</table>	</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Status</td>
    <td class="formulario" <?php if ($matriz['sitcli'] == 0) {
								echo "bgcolor=\"#33CC66\"";
								} else {
								echo "bgcolor=\"#FF0000\"";} ?> >
							<font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp; </td>
  </tr>
  <tr class="noprint">
    <td align="right">
			<a href='#' onClick='JavaScript:self.print()'>
			<img src="../../images/print.gif" hspace='5' border='0' alt=' Imprimir '><br>
			<span class="bodyText">Imprimir</span></a>
	</td>
	<td align="center"><input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
</tr>
  <tr align="right" class="noprint">
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<?php $res = mysql_close ($con);?>
</body>
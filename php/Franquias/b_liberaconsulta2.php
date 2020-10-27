<?php
require "connect/sessao.php";

$codigo = $_GET['codloja'];

$comando = "select a.codloja, a.razaosoc, a.nomefantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli,
			d.descsit, a.tx_mens, a.tipo_cliente from cadastro a
			inner join situacao d on a.sitcli=d.codsit
			inner join logon e on a.codloja=e.codloja
			where mid(logon,1,LOCATE('S',logon)-1)='$codigo'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$codloja = $matriz['codloja'];

if ( $matriz['tipo_cliente'] <> 'A' ){
	$command = "select a.tpcons, b.nome, a.qtd, a.consumo from cons_liberada a 
				inner join valcons b on a.tpcons=b.codcons
				where a.codloja=$codloja";
}else{
	$command = "select a.tpcons, b.nome, a.qtd, a.consumo from cons_liberada_logon a 
				inner join valcons b on a.tpcons=b.codcons
				where a.codloja=$codloja and mid(logon,1,LOCATE('S',logon)-1)='$codigo'";
}

$result = mysql_query ($command, $con);
$linhas = mysql_num_rows ($result);
$linhas1 = $linhas + 3;
$sitcli = $matriz['sitcli'];
?>
<script src="../js/funcoes.js"></script>
<body>
<form name="form1" method="post" action="Franquias/b_conslib.php">

<table border="0" align="center" width="690">
  <tr>
    <td colspan="2" class="titulo" align="center">LIMITE DE CONSULTAS</td>
  </tr>
  <tr>
    <td width="233" class="subtitulodireita">ID</td>
    <td width="400" class="subtitulopequeno">
		<?php echo $matriz['codloja']; ?>
      	<input type="hidden" name="codloja" value="<?php echo $matriz['codloja']; ?>" />
		<input type="hidden" name="tplib" value="1" >
        <input type="hidden" name="tipo_cliente" value="<?php echo $matriz['tipo_cliente']; ?>" >
        <input type="hidden" name="logon" value="<?php echo $codigo; ?>" >
	</td>
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
    <td class="subtitulodireita">Limite de consultas<br>
      Diminui <b>UMA (1) consulta</b> <input type="radio" name="limite" value="1" checked><br>
	  Diminui <b>CINCO (5) consultas</b> <input type="radio" name="limite" value="5"><br>
	  Diminui <b>DEZ (10) consultas</b> <input type="radio" name="limite" value="10"><br>
	  Diminui <b>QUINZE (15) consultas</b> <input type="radio" name="limite" value="15"><br>
	  Diminui <b>VINTE (20) consultas</b> <input type="radio" name="limite" value="20"><br>
	  Diminui <b>CINQ�ENTA (50) consultas</b> <input type="radio" name="limite" value="50"><br>
      Diminui <b>CEM (100) consultas</b> <input type="radio" name="limite" value="100"><br>
      Diminui <b>QUINHENTAS (500) consultas</b> <input type="radio" name="limite" value="500"><br>
      Diminui <b>HUM MIL (1000) consultas</b> <input type="radio" name="limite" value="1000">
    </td>
    <td>
		<table width="100%" border="0" cellpadding="0" cellspacing="1">
	 		<tr>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
			<tr height="20">
				<td align="center" class="campoesquerda">Tipo</td>
				<td align="center" class="campoesquerda">Descri&ccedil;&atilde;o</td>
				<td class="campoesquerda"></td>
				<td align="center" class="campoesquerda">Qtd. Lib.</td>
				<td align="center" class="campoesquerda">Usado</td>
				<td rowspan="<?php echo $linhas1; ?>" width="1" bgcolor="#999999"></td>
			</tr>
		<?php
			for ($a=1; $a<=$linhas; $a++) {
				$matrix = mysql_fetch_array($result);
				$cons = $matrix['tpcons'];
				$produto = $matrix['nome'];
				$venda = $matrix['qtd'];
				$gratuidade = $matrix['consumo'];
				echo "<tr height=\"22\" class=\"subtitulopequeno\">
						<td align=\"center\">$cons</td>
						<td align=\"left\">$produto</td>
						<td align=\"center\"><input name=\"selected[]\" type=\"checkbox\" value=\"$cons\" /></td>
						<td align=\"center\">$venda</td>
						<td align=\"center\">$gratuidade</td>
					</tr>";
				}
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
  <tr>
    <td colspan="2" align="center">
		<input type="submit" name="altera" value="Alterar selecionados" <?php if ($sitcli <>0) echo "disabled"; ?> >
		<input name="button" type="button" onClick="location.href='painel.php?pagina1=Franquias/b_liberaconsulta.php'" value="       Voltar       " />
	</td>
  </tr>
  <tr align="right">
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
</form>
<?php $res = mysql_close ($con);
?>
</body>
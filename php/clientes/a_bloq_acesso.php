<?php
require "connect/sessao.php";

$codigo = $_REQUEST['codigo'];

if (($tipo == "a") || ($tipo == "c")) {
$resulta = mysql_query("select a.logon, b.id_franquia from logon a
						inner join cadastro b on a.codloja=b.codloja
						where MID(logon,1,LOCATE('S', logon) - 1)='$codigo'", $con);
} else {
$resulta = mysql_query("select a.logon, b.id_franquia from logon a
						inner join cadastro b on a.codloja=b.codloja
						where MID(logon,1,LOCATE('S', logon) - 1)='$codigo' and id_franquia='$id_franquia'", $con);
}
$linha = mysql_num_rows($resulta);
if ($linha == 0)
{
	echo "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\"); javascript: history.back();</script>";
}
else 
{
	$comando = "select a.codloja, a.razaosoc, a.nomefantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, 
			a.sitcli, c.descsit, 
			d.sitlog, f.descsit as desclog, 
			b.descricao, a.tx_mens from cadastro a
			inner join classif_cadastro b on a.classe=b.id
            inner join situacao c on a.sitcli=c.codsit
			inner join logon d on a.codloja=d.codloja
            inner join situacao f on d.sitlog = f.codsit
			where MID(logon,1,LOCATE('S', logon) - 1) = '$codigo'";
	$res = mysql_query ($comando, $con);
	$matriz = mysql_fetch_array($res);
	$codloja = $matriz['codloja'];
	$sitcli = $matriz['sitcli'];
	$sitlog = $matriz['sitlog'];
	?>
	<script src="../js/funcoes.js"></script>
	<br>
	<form name="form1" method="post" action="clientes/update_acesso.php">
		<table border="0" align="center" width="643">
			<tr>
				<td colspan="2" class="titulo" align="center">BLOQUEIO E DESBLOQUEIO MANUAL DE ACESSO</td>
			</tr>
			<tr>
				<td class="subtitulodireita">ID</td>
				<td class="subtitulopequeno"><?php echo $codloja; ?><input type="hidden" name="codloja" value="<?php echo $codloja; ?>" /></td>
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
				<td class="subtitulodireita">Tipo de Contrato </td>
				<td valign="top" class="subtitulopequeno"><?php echo $matriz['descricao']; ?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Mensalidade</td>
				<td valign="top" class="subtitulopequeno">R$&nbsp;<?php echo $matriz['tx_mens']; ?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Status Contrato</td>
				<td class="formulario" <?php if ($sitcli == 0) {
								echo "bgcolor=\"#33CC66\"";
								} elseif ($sitcli == 1) {
								echo "bgcolor=\"#FFCC00\"";
								} else {
								echo "bgcolor=\"#FF0000\"";} ?> >
							<font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font>
				</td>
			</tr>
			<tr>
				<td class="subtitulodireita">Status Acesso</td>
				<td class="formulario" <?php 
								if ($sitlog == 0) {
									echo "bgcolor=\"#33CC66\"";
								} elseif ($sitlog == 1) {
									echo "bgcolor=\"#FFCC00\"";
								} else {
									echo "bgcolor=\"#FF0000\"";} ?> >
							<font color="#FFFFFF"><?php echo $matriz['desclog']; ?></font>
							<?php if ( $sitcli == 3 and ( $id_franquia == 163 or $id_franquia == 4 or $id_franquia == 46 or  $id_franquia == 55)){ ?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input name="button2" type="button" onclick="location.href='painel.php?pagina1=clientes/a_desbloqueio_virtual.php&codigo=<?=$codigo?>&codloja=<?=$codloja?>'" value=" Desbloqueio Virtual Tempor&aacute;rio " />
							<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="subtitulodireita">Bloquear/Desbloquear Acesso?</td>
				<td class="formulario">
					<?php
					if ( $sitlog == 0 ) { ?>
						<input name="acesso" type="radio" value="1" <?php if ($sitlog!=0) echo "checked"; ?> >
						Bloqueia
					<?php } else { ?>
						<input name="acesso" type="radio" value="0" <?php if ($sitlog==0) echo "checked"; ?> >
						Libera
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="titulo">&nbsp; </td>
			</tr>
            <input type="hidden" name="codigo" value="<?=$codigo?>" />
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="altera" value="Mudar Status" <?php if ($sitcli==2) echo "disabled"; ?> >
					<input name="button" type="button" onClick="location.href='painel.php?pagina1=clientes/a_bloqueio.php'" value="       Voltar       " />
				</td>
			</tr>
			<tr align="right">
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>
	</form>
<?php 
$res = mysql_close ($con);
} 
?>
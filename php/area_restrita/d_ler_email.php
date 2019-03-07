<?php
$id = $_GET['id'];
//seleciona a tabela
$sql = "select a.id, b.razaosoc, c.fantasia, a.assunto, a.recado, DATE_FORMAT(a.data,'%d/%m/%Y %h:%i') as data, a.lido
from correio_franquia a
inner join franquia b on a.nome=b.id
inner join franquia c on a.franquia=c.id
where a.id='$id'
order by a.data desc";
$ql = mysql_query($sql,$con);
while ($coluna = mysql_fetch_array($ql)){
	$nome = $coluna['razaosoc'];
	$franquia = $coluna['fantasia'];
	$assunto = $coluna['assunto'];
	$recado = $coluna['recado'];
	$data = $coluna['data'];
	$lido = $coluna['lido'];
?>
<table border="0" width="100%" class="bodyText">
  <tr>
    <td colspan="2" class="formulario" bgcolor="#CCCCCC">&nbsp;<?= strtoupper($assunto); ?></td>
  </tr>
  <tr>
    <td width="11%" class="subtitulodireita">De:</td>
    <td width="89%" class="campoesquerda"><?= $nome; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Data:</td>
    <td class="campoesquerda"><?= $data; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Para:</td>
    <td class="campoesquerda"><?= $franquia; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Assunto:</td>
    <td class="campoesquerda"><?= $assunto; ?></td>
  </tr>
  <tr>
	<td class="subtitulodireita" valign="top">Anexos:</td>
	<td class="campoesquerda">
  <?php
  $sql2 = "select * from ftp_correio where id_mensagem='$id'";
  $ql2 = mysql_query($sql2,$con);
  while ($resposta = mysql_fetch_array($ql2)){
	$anexo = $resposta['arq_original'];
	$link = $resposta['arq_randomico'];
	echo "<a href=\"http://189.16.26.132/ftp/$link\" target=\"_BLANK\"><img src=\"../img/clip.gif\" border=\'0\'>&nbsp;$anexo</a><br>";
	} ?>
	</td>
  </tr>
  <tr>
    <td colspan="2" class="subtitulopequeno" style="padding-left:10px; padding-right:10px; padding-bottom:10px">
		<fieldset>
			<legend><b>Mensagem</b></legend>
			<?= nl2br($recado); ?>
		</fieldset>
	</td>
  </tr>
</table>
<?php
}
$query ="UPDATE correio_franquia SET lido='1' WHERE id='$id'";
mysql_query($query,$con);
mysql_close($con)
?>
<div align="center">
  <input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " />
</div>
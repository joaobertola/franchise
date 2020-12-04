<?php
require "connect/sessao.php";

$codloja = $_GET['codloja'];
$protocolo = $_GET['protocolo'];
$pagina = $_GET['pagina'];

if ($tipo == "b") $frq = "and franquia = '$id_franquia'";
else $frq = "";

$sql = "select protocolo, codigo from cs2.ocorrencias where protocolo='$protocolo' $frq";
$localiza = mysql_query($sql, $con);
$linprot = mysql_num_rows($localiza);
if ($linprot ==0) {
	print"<script>alert(\"Esse numero de protocolo nao existe;\");;history.go(-1)</script>";
} else {
	$matrix = mysql_fetch_array($localiza);
	$codloja = $matrix['codigo'];
	$protocolo = $matrix['protocolo'];
}


include "ocorrencias/config.php";
include "ocorrencias/javascript.php";

$resulta = mysql_query("select MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, a.codloja, a.nomefantasia, a.fone, a.fone_res, a.celular from cadastro a
							inner join logon b on a.codloja=b.codloja
							where a.codloja=$codloja limit 1");
$matriz = mysql_fetch_array($resulta); 

$sql = mysql_query("SELECT atendente, tipo_ocorr, ocorrencia, date_format(data,'%d/%m/%Y %H:%i') as data, protocolo FROM $table_name WHERE protocolo=$protocolo");
?>

<table align="center" border=0 width="100%">
<tr>
<td class="titulo">
<?php echo $titulo; ?> DO CLIENTE</td>
</tr>
<tr>
	<td align="center">
		<table width="80%" border="0">
			<tr>
				<td width="50%" class="subtitulodireita">C&oacute;digo:</td>
				<td width="50%" class="subtitulopequeno"><?php echo $matriz['logon']; ?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Nome de Fantasia</td>
				<td class="subtitulopequeno"><?php echo $matriz['nomefantasia']; ?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Telefone Comercial</td>
				<td class="subtitulopequeno"><?php echo $matriz['fone']; ?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Telefone Residencial</td>
				<td class="subtitulopequeno"><?php echo $matriz['fone_res']; ?></td>
			</tr>
			<tr>
				<td class="subtitulodireita">Telefone Celular</td>
				<td class="subtitulopequeno"><?php echo $matriz['celular']; ?></td>
			</tr>
		</table>
	</td>
</tr>
<tr>
<td class="titulo">
Ocorr&ecirc;ncia registrada por Protocolo</td>
</tr>
<tr>
<td align="center">
<form method="post" action="painel.php?pagina1=ocorrencias/postar.php&codloja=$codloja" >
<input type="hidden" name="codloja" value="<?php echo $codloja; ?>" />
<input type="submit" value="Nova ocorr&ecirc;ncia" name="nova_ocorr" />
<input name="button" type="button" onClick="javascript: history.back();" value="   Voltar   " />
</form></td>
</tr>
</table>
<p>
<?php
while($valor = mysql_fetch_array($sql)) {
?>
<p align="center">
<div class="bordaBox">
<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
<div class="conteudo">
	<table>
		<tr>
			<td width="150"><strong>Protocolo de Atendimento:</strong></td>
		    <td><?php echo $valor[protocolo]; ?></td>
		</tr>
		<tr>
			<td><strong>Data  e Hora da Ocorr&ecirc;ncia:</strong></td>
		    <td><?php echo $valor[data]; ?></td>
		</tr>
		<tr>
			<td><strong>Tipo de Ocorr&ecirc;ncia:</strong></td>
		    <td>
			  <?php $xuxa = $valor['tipo_ocorr'];
				 if ($xuxa == 1) echo "Cobran&ccedil;a<br>";
				 elseif ($xuxa == 2) echo "Atendimento<br>";
				 elseif ($xuxa == 2) echo "Administrativo<br>";
				 else echo "Comercial<br>"; ?>			</td>
		</tr>
		<tr>
		  <td><strong>Atendente:</strong></td>
		  <td><?php echo $valor[atendente]; ?></td>
	    </tr>
		<tr>
			<td valign="top" class="campoesquerda"><strong>Descri&ccedil;&atilde;o:</strong></td>
		    <td class="campoesquerda"><?php echo $valor[ocorrencia]; ?></td>
		</tr>
	</table>
</div>
<b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>
</div>
</p>
<center>________________________________________________</center>
</p>
<?php
}
?>
</center>
<font face="Arial" size="2">
<p align="center"><?php echo "<a onclick=\"abrir('$link[login]')\" href=\"#\"><font color=#FF0000>&Aacute;rea restrita</font></a>"; ?></p>
</font>
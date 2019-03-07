<?php
require "connect/sessao.php";

$codloja = $_GET['codloja'];
$protocolo = $_GET['protocolo'];
$pagina = $_GET['pagina'];

if ($tipo == "b") $frq = "and franquia = '$id_franquia'";
else $frq = "";

$sql = "select * from cs2.ocorrencias where protocolo='$protocolo' $frq";

$qry_localiza = mysql_query($sql, $con);
$qry_localiza2 = mysql_query($sql, $con);
$linprot = mysql_num_rows($qry_localiza);
if ($linprot ==0) {
	print"<script>alert(\"Esse numero de protocolo nao existe;\");;history.go(-1)</script>";
} else {

	$matrix = mysql_fetch_array($qry_localiza);
	$codloja = $matrix['codigo'];
	$protocolo = $matrix['protocolo'];
}


include "ocorrencias/config.php";
include "ocorrencias/javascript.php";

$resulta = mysql_query("select 
	                        mid(b.logon,1,5) as logon, a.codloja, a.nomefantasia, a.fone, a.fone_res, a.celular from cadastro a
						inner join logon b on a.codloja=b.codloja
						where a.codloja=$codloja limit 1", $con);
$matriz = mysql_fetch_array($resulta); 

$sql = mysql_query("SELECT atendente, tipo_ocorr, ocorrencia, date_format(data,'%d/%m/%Y %H:%i') as data, protocolo 
	                FROM cs2.ocorrencias WHERE protocolo='$protocolo'", $con);
?>

<table class="table table-striped table-responsive col65" align="center">
	<thead>
		<tr>
			<th colspan="2">
				<h4 class="text-center"> <?php echo $titulo; ?> DO CLIENTE</h4>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="2" align="center">
				<table>
					<tr>
						<td width="50%" class="text-right">Código:</td>
						<td width="50%"><?php echo $matriz['logon']; ?></td>
					</tr>
					<tr>
						<td class="text-right">Nome de Fantasia</td>
						<td><?php echo $matriz['nomefantasia']; ?></td>
					</tr>
					<tr>
						<td class="text-right">Telefone Comercial</td>
						<td><?php echo $matriz['fone']; ?></td>
					</tr>
					<tr>
						<td class="text-right">Telefone Residencial</td>
						<td><?php echo $matriz['fone_res']; ?></td>
					</tr>
					<tr>
						<td class="text-right">Telefone Celular</td>
						<td><?php echo $matriz['celular']; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<h4 class="text-center">Ocorrência registrada por Protocolo</h4>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td>
				<form method="post" action="painel.php?pagina1=ocorrencias/postar.php&codloja=$codloja" >
					<input type="hidden" name="codloja" value="<?php echo $codloja; ?>" />
					<input type="submit" value="Nova ocorrência" name="nova_ocorr" class="botao3d" />
			</td>
			<td>
					<input name="button" type="button" onClick="javascript: history.back();" value="Voltar" class="botao3d" />
				</form>
			</td>
		</tr>
	</tfoot>
</table>
<?php
while($valor = mysql_fetch_array($qry_localiza2) ) {
?>
	<table class="table table-striped table-responsive col65" align="center">
		<tr>
			<td width="150"><strong>Protocolo de Atendimento:</strong></td>
		    <td><?php echo $valor[protocolo]; ?></td>
		</tr>
		<tr>
			<td><strong>Data  e Hora da Ocorrência:</strong></td>
		    <td><?php echo $valor[data]; ?></td>
		</tr>
		<tr>
			<td><strong>Tipo de Ocorência:</strong></td>
		    <td>
			  <?php $xuxa = $valor['tipo_ocorr'];
				 if ($xuxa == 1) echo "Cobrança<br>";
				 elseif ($xuxa == 2) echo "Atendimento<br>";
				 elseif ($xuxa == 2) echo "Administrativo<br>";
				 else echo "Comercial<br>"; ?>			</td>
		</tr>
		<tr>
		  <td><strong>Atendente:</strong></td>
		  <td><?php 	 echo $valor[atendente]; ?></td>
	    </tr>
		<tr>
			<td valign="top"><strong>Descrição:</strong></td>
		    <td><?php echo $valor[ocorrencia]; ?></td>
		</tr>
	</table>
<?php
}
?>
<p align="center"><?php echo "<a onclick=\"abrir('$link[login]')\" href=\"#\"><font color=#FF0000>Área restrita</font></a>"; ?></p>

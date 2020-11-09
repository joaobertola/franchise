<?php
require "connect/sessao.php";

$codloja = $_GET['codloja'];
$pagina = $_GET['pagina'];

include "ocorrencias/config.php";
include "ocorrencias/javascript.php";

$natureza_ocorr = array("geral", "Cobran&ccedil;a", "Atendimento", "Administrativo", "Comercial");

$sql3 = "select MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon, a.codloja, a.nomefantasia, a.fone, a.fone_res, a.celular from cadastro a
		inner join logon b on a.codloja=b.codloja
		where a.codloja=$codloja limit 1";
$resulta = mysql_query($sql3, $con);
$matriz = mysql_fetch_array($resulta); 

$sql = "SELECT atendente, tipo_ocorr, ocorrencia, date_format(data,'%d/%m/%Y %H:%i') as data, protocolo FROM $table_name WHERE codigo='$codloja' order by id desc LIMIT 0, 30";
$conex = mysql_query($sql, $con);
$ordem = mysql_num_rows($conex);

// Faz os calculos da pagina��o
$sql2 = mysql_query("SELECT a.atendente, a.tipo_ocorr, a.ocorrencia, date_format(a.data,'%d/%m/%Y %H:%i') as data, a.protocolo, b.atendente as atdte
					FROM cs2.ocorrencias a
					LEFT OUTER JOIN cs2.atendentes b on a.id_atendente = b.id
					WHERE a.codigo='$codloja' order by a.id desc");
$total = mysql_num_rows($sql2); // Esta fun��o ir� retornar o total de linhas na tabela
$paginas = ceil($total / $lpp); // Retorna o total de p�ginas
if(!isset($pagina)) { $pagina = 0; } // Especifica uma valor para variavel pagina caso a mesma n�o esteja setada
$inicio = $pagina * $lpp; // Retorna qual ser� a primeira linha a ser mostrada no MySQL
$sql2 = mysql_query("SELECT a.atendente, a.tipo_ocorr, a.ocorrencia, date_format(a.data,'%d/%m/%Y %H:%i') as data, a.protocolo, b.atendente as atdte
					FROM cs2.ocorrencias a
					LEFT OUTER JOIN cs2.atendentes b on a.id_atendente = b.id
					WHERE a.codigo='$codloja' order by a.id desc
					LIMIT $inicio, $lpp"); // Executa a query no MySQL com o limite de linhas.
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
&Uacute;ltimas ocorr&ecirc;ncias registradas</td>
</tr>
<tr>
<td align="center">
<form method="post" action="painel.php?pagina1=franqueado_jr/ocorrencia_nova.php&codloja=$codloja" >
<input type="hidden" name="codloja" value="<?php echo $codloja; ?>" />
<input type="submit" value="Nova Ocorr&ecirc;ncia" name="nova_ocorr" />
</form></td>
</tr>
</table>
<p>
<?php
if ($total == 0) {
echo "<p align=\"center\"><font size=\"1\" face=\"Verdana\">N&atilde;o h&aacute; nenhuma ocorr&ecirc;ncia registrada at&eacute; o presente momento.</font></p>";
} else {
while($valor = mysql_fetch_array($sql2)) { ?>
<p>
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
			  	echo $natureza_ocorr[$xuxa]."<br>";
			  ?>
            </td>
		</tr>
		<tr>
		  <td><strong>Atendente:</strong></td>
		  <td><?php echo $valor[atendente]; ?> <?php echo $valor[atdte]; ?></td>
	    </tr>
		<tr class="campoesquerda">
			<td valign="top"><strong>Descri&ccedil;&atilde;o:</strong></td>
		    <td><?php echo $valor[ocorrencia]; ?></td>
		</tr>
	</table>
</div>
<b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>
</div>
</p>
<hr noshade="noshade" size="1" width="60%" color="#c0c8c0" align="center" />
</p>
<?php
}
?>
<center>
<?php
}

//destroi o array da natureza da ocorrencia
unset($natureza_ocorr);

// Pagina��o
if($pagina > 0) {
   $menos = $pagina - 1;
   $url = "$paginacao[link]pagina1=franqueado_jr/ocorrencia_mensagens.php&codloja=$codloja&pagina=$menos";
   echo "<a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Anterior'; return true\">Anterior</a>"; // Vai para a p�gina anterior
}
for($i=0;$i<$paginas;$i++) { // Gera um loop com o link para as p�ginas
   $url = "$paginacao[link]pagina1=franqueado_jr/ocorrencia_mensagens.php&codloja=$codloja&pagina=$i";
   echo " | <a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Pagina $i'; return true\">$i</a>";
}
if($pagina < ($paginas - 1)) {
   $mais = $pagina + 1;
   $url = "$paginacao[link]pagina1=franqueado_jr/ocorrencia_mensagens.php&codloja=$codloja&pagina=$mais";
   echo " | <a href=\"$url\" class=\"bodyText\" onMouseOver=\"window.status='Proxima'; return true\">Pr�xima</a>";
}
?>
</center>
<font face="Arial" size="2">
<p align="center">
Temos um total de <b><?php echo $ordem ?></b> <?php if ($ordem == "1") echo "ocorr&ecirc;ncia registrada"; else echo "ocorr&ecirc;ncias registradas"; ?>!
</p>
</font>
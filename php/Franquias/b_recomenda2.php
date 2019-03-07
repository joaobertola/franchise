<?php
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;

$valor   = $_POST['valor'];
$disc 	 = $_POST['descricao'];
$tipo    = $_POST['tipo'];
$codloja = $_POST['codloja'];

$data = date('Y-m-d');
	
$valor=str_replace(".","",$valor);
$valor=str_replace(",",".",$valor);
	
$query = "INSERT INTO servicos (codloja, data, num, disc, valor, tipo) VALUES ('$codloja', '$data', '0', '$disc', '$valor', '$tipo')";

mysql_query($query,$con);
echo "<script>alert(\"Titulo atualizado com sucesso!\");</script>";

$sql = "select date_format(a.data,'%d/%m/%Y') as data, b.razaosoc, a.valor, a.tipo, a.disc from servicos a
		inner join cadastro b on a.codloja=b.codloja
		where a.codloja='$codloja'";
$res = mysql_query($sql, $con);
?>
<table border="0" class="bodyText" width="550" align="center">
  <tr>
	<td colspan="5" class="titulo" align="center">INDICA&Ccedil;&Atilde;O DE CLIENTES </td>
  </tr>
  <?
	$linhas = mysql_num_rows($res);
	if ($linhas == "0")
	{
	echo "<tr>
			<td align=\"center\" colspan=\"5\" class=\"titulo\">Nenhum cliente cadastrado neste periodo!</td>
		  </tr>";
	} else {
	$a = 1;
	echo "	<tr class=\"subtitulodireita\">
				<td align=\"center\">Data</td>
				<td align=\"center\">Cliente</td>
				<td align=\"center\">Valor</td>
				<td align=\"center\">Tipo</td>
				<td align=\"center\">Descri&ccedil;&atilde;o</td>
			</tr>";
	  while ($matriz = mysql_fetch_array($res)) {
		$a = $a + 1;
		$data = $matriz['data'];
		$razaosoc = $matriz['razaosoc'];
		$valor = $matriz['valor'];
		$tipo = $matriz['tipo'];
		$valorpg = $matriz['valorpg'];
		$disc = $matriz['disc'];
		$numboleto = $matriz['numboleto'];

		$string = $disc;
		$limite = 120;
		$sizeName = strlen($string);
		
		echo "<tr ";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo "bgcolor=\"#FFFFFF\">";
		}
		echo "	<td align=\"center\">$data</td>
	  	   	  	<td align=\"center\">$razaosoc</td>
				<td align=\"right\">$ $valor</td>
				<td align=\"center\">$tipo</td>
	  	      	<td>";
			  for($num=0;$num<$limite;$num++) {
    				print($string[$num]);
					}
					if($sizeName>$limite){echo"...";}
		echo "</td></tr>";
		}
	}
  ?>
  <tr>
  	<td colspan="5" class="titulo">&nbsp;</td>
  </tr>
</table>
<br />
<center>
<input type="button" onClick="javascript: history.back();" value="     Voltar      " />
</center>
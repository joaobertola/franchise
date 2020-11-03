<?php
require "connect/sessao.php";

$razao = $_POST['razao'];
$opraz = $_POST['opraz'];


if (($tipo == "a") || ($tipo == "c")) {
	$comando = "SELECT a.codloja, a.razaosoc, a.nomefantasia, a.fone, MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon FROM cs2.cadastro a
	            INNER JOIN cs2.logon b ON a.codloja = b.codloja
	            WHERE a.razaosoc like '%$razao%' and a.sitcli='$opraz' 
	            ORDER BY a.razaosoc ASC";
} else {
	$comando = "SELECT a.codloja, a.razaosoc, a.nomefantasia, a.fone, MID(b.logon,1,LOCATE('S', b.logon) - 1) as logon FROM cs2.cadastro a
	            INNER JOIN cs2.logon b ON a.codloja = b.codloja
	            WHERE a.razaosoc like '%$razao%' and a.sitcli='$opraz' and a.id_franquia='$id_franquia' 
	            ORDER BY a.razaosoc ASC";
}
$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0")
	{
	echo "<table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"20\" class=\"titulo\">
			<td align=\"center\" width=\"100%\" >Nenhum cliente cadastrado!</td></tr></table>";
	}
	else
	{
	echo "<table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\">
	 		<tr>
				<td colspan=\"7\" height=\"1\" bgcolor=\"#999999\"></td>
			</tr>
	 		<tr>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" bgcolor=\"#FF9900\">
				<td align=\"center\" >C&oacute;digo</td>
				<td align=\"center\" >Raz&atilde;o Social</td>
				<td align=\"center\" >Nome de Fantas&iacute;a</td>
				<td align=\"center\" >-</td>
				<td align=\"center\" ><b>Consultar</b></td>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr>
				<td colspan=\"7\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$id = $matriz['codloja'];
	  	$logon = $matriz['logon'];
	  	$razao = $matriz['razaosoc'];
	  	$nome = $matriz['nomefantasia'];
		$telefone = $matriz['fone'];
		$string = $razao;
		$limite = 38;
		$sizeName = strlen($string);
		//
		$string0 = $nome;
		$limite0 = 38;
		$sizeName0 = strlen($string0);
	  	echo "<tr height=\"22\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		echo "	<td align=\"center\">$id</td>
	  	      	<td align=\"left\">&nbsp;<a href=\"painel.php?pagina1=clientes/a_cons_id.php&codigo=$logon\" onMouseOver=\"window.status='Visualizar Cliente'; return true\">";
			  for($num=0;$num<$limite;$num++) {
    				print($string[$num]);
					}
					if($sizeName>$limite){echo"...";}
		echo "</a></td>
	  	      	<td align=\"left\">";
			  for($num0=0;$num0<$limite0;$num0++) {
    				print($string0[$num0]);
					}
					if($sizeName0>$limite0){echo"...";}
		echo "</td>
			  	<td align=\"center\">-</td>
	  	      	<td align=\"center\"><a href=\"painel.php?pagina1=clientes/a_cons_id.php&codigo=$logon\" onMouseOver=\"window.status='Visualizar Cliente'; return true\"><IMG SRC=\"../img/alt.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
	  	      	</tr>";
		}
		echo "<tr>
				<td colspan=\"6\" align=\"right\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
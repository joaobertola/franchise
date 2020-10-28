<?php
require "connect/sessao.php";

$telefone = $_POST['ddd'].$_POST['fone'];

if (($tipo == "a") || ($tipo == "c")) {
	$comando = "";
} else {
	$comando = "AND a.id_franquia='$id_franquia'";
}

$sql = "SELECT CAST(MID(b.logon,1,6) AS UNSIGNED) as logon, a.razaosoc, a.nomefantasia, a.fone, a.codloja 
				FROM cadastro a
				INNER JOIN logon b on a.codloja = b.codloja
				WHERE (a.fone = '$telefone' OR a.fax = '$telefone' OR a.celular = '$telefone' OR a.fone_res = '$telefone') 
				$comando
				GROUP BY a.codloja";
$res = mysql_query ($sql, $con);
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
				<td align=\"center\" >Nome de Fantasia</td>
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
	  	$logon = $matriz['logon'];
		$id = $matriz['codloja'];
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
		echo " 	<td align=\"center\">$logon</td>
	  	      	<td align=\"left\">&nbsp;";
			  for($num=0;$num<$limite;$num++) {
    				print($string[$num]);
					}
					if($sizeName>$limite){echo"...";}
		echo "</font></td>
	  	      	<td align=\"left\"><a href=\"painel.php?pagina1=clientes/a_cons_id.php&codigo=$logon\" onMouseOver=\"window.status='Alterar Cliente'; return true\">";
			  for($num0=0;$num0<$limite0;$num0++) {
    				print($string0[$num0]);
					}
					if($sizeName0>$limite0){echo"...";}
		echo "</a></td>
			  	<td align=\"center\">-</td>
	  	      	<td align=\"center\"><a href=\"painel.php?pagina1=clientes/a_cons_id.php&codigo=$logon\" onMouseOver=\"window.status='Alterar Cliente'; return true\"><IMG SRC=\"../img/alt.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
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
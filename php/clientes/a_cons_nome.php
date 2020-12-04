<?php
require "connect/sessao.php";

$fantasia = $_POST['fantasia'];
$opnom = $_POST['opnom'];

if( $opnom == 0)
	$opnom = '0,5';

if (($tipo == "a") || ($tipo == "c")) {
	$comando = "SELECT c.codloja, c.razaosoc, MID(l.logon,1,LOCATE('S', l.logon) - 1) as logon, c.nomefantasia, c.fone FROM cadastro c left join logon l on l.codloja = c.codloja WHERE nomefantasia like '%$fantasia%' and sitcli in($opnom) ORDER BY nomefantasia ASC";
} else {
	$comando = "SELECT c.codloja, c.razaosoc, MID(l.logon,1,LOCATE('S', l.logon) - 1) as logon, c.nomefantasia, c.fone FROM cadastro c left join logon l on l.codloja = c.codloja WHERE nomefantasia like '%$fantasia%' and sitcli in($opnom) and id_franquia='$id_franquia' ORDER BY nomefantasia ASC";
}

//echo $comando;die;

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
				<td colspan=\"4\" height=\"1\" bgcolor=\"#999999\"></td>
			</tr>
	 		<tr>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" bgcolor=\"#FF9900\">

				<td align=\"center\" >Código</td>
				<td align=\"center\" >Razão Social</td>

				<td align=\"center\" >Nome de Fantasia</td>
				<td align=\"center\" ><b>Consultar</b></td>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr>
				<td colspan=\"4\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$id = $matriz['codloja'];
	  	$razao = $matriz['razaosoc'];
		$codigo = $matriz['logon'];
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
		
		echo "	<td align=\"left\">$codigo</td>
				<td align=\"left\">$razao</td>
	  	      	<td align=\"left\"><a href=\"painel.php?pagina1=clientes/a_cons_id.php&codigo=$codigo\" onMouseOver=\"window.status='Alterar Cliente'; return true\">";
			   print( $nome );
		echo "</a></td>

	  	      	<td align=\"center\"><a href=\"painel.php?pagina1=clientes/a_cons_id.php&codigo=$codigo\" onMouseOver=\"window.status='Alterar Cliente'; return true\"><IMG SRC=\"../img/alt.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
	  	      	</tr>";
		}
		echo "<tr>
				<td colspan=\"5\" align=\"right\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
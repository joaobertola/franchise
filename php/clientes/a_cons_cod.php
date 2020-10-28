<?
require "connect/sessao.php";

$codigo = $_POST['codigo'];

if ($tipo == "b") $frq = "and a.id_franquia='$id_franquia'";
else $frq = "";

$sql = "SELECT 1 as tp, a.codloja, CAST(MID(b.logon,1,6) AS UNSIGNED) as logon, a.razaosoc, a.nomefantasia, a.sitcli, d.descsit FROM cadastro a
		inner join logon b on a.codloja=b.codloja
		inner join situacao d on a.sitcli=d.codsit
		WHERE b.codloja = '$codigo' $frq
		union
		SELECT 2 as tp, a.codloja, CAST(MID(b.logon,1,6) AS UNSIGNED) as logon, a.razaosoc, a.nomefantasia, a.sitcli, d.descsit FROM cadastro a
		inner join logon b on a.codloja=b.codloja
		inner join situacao d on a.sitcli=d.codsit
		where CAST(MID(b.logon,1,6) AS UNSIGNED)='$codigo' $frq ORDER BY logon ASC";

$res = mysql_query ($sql, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0")
	{
	echo "<br>
			<table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
				<tr height=\"20\" class=\"titulo\">
					<td align=\"center\" width=\"100%\" >Nenhum cliente cadastrado!</td>
				</tr>
			</table>";
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
			<tr align=\"center\" bgcolor=\"#FF9900\">
				<td>ID</td>
				<td>C�digo</td>
				<td>Raz�o Social</td>
				<td>Nome de Fantas�a</td>
				<td>Situacao</td>
				<td><b>Consultar</b></td>
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
		$situacao = $matriz['descsit'];
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
		echo " 	<td align=\"center\">$id</td>
				<td align=\"center\"><a href=\"painel.php?pagina1=clientes/a_cons_codigo.php&codigo=$id\" onMouseOver=\"window.status='Alterar Cliente'; return true\">$logon</a></td>
	  	      	<td align=\"left\">&nbsp;";
			  for($num=0;$num<$limite;$num++) {
    				print($string[$num]);
					}
					if($sizeName>$limite){echo"...";}
		echo "</font></td>
	  	      	<td align=\"left\"><a href=\"painel.php?pagina1=clientes/a_cons_codigo.php&codigo=$id\" onMouseOver=\"window.status='Alterar Cliente'; return true\">";
			  for($num0=0;$num0<$limite0;$num0++) {
    				print($string0[$num0]);
					}
					if($sizeName0>$limite0){echo"...";}
		echo "</a></td>
			  	<td align=\"center\">$situacao</td>
	  	      	<td align=\"center\"><a href=\"painel.php?pagina1=clientes/a_cons_codigo.php&codigo=$id\" onMouseOver=\"window.status='Alterar Cliente'; return true\"><IMG SRC=\"../img/alt.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
	  	      	</tr>";
		}
		echo "<tr>
				<td colspan=\"7\" align=\"right\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<div align="center"><input type="button" class="botao3d" onClick="javascript: history.back();" value="Voltar" /></div>
<?
require "connect/sessao.php";

$contano 			   = $_POST['contano'];
$contmes 			   = $_POST['contmes'];
$opcao 				   = $_POST['opcao'];
$canceladoprecancelado = $_POST['canceladoprecancelado'];
$todosmotivos 		   = $_POST['todosmotivos'];
$ordenacao 			   = $_POST['ordenacao'];
$franqueado 		   = $_POST['franqueado'];

if ($opcao<>3) $sitcli="and a.sitcli='$opcao'";
else $sitcli="";

if ($franqueado == 'todos') $frq = "";
else $frq = "and id_franquia_jr=$franqueado";

if ($contano === "todos") {
	$periodo = "%";
} else {
	if ($contmes == "todos") $periodo = "$contano-%";
	else $periodo = "$contano-$contmes%";
}

$comando = "select a.codloja, CAST(MID(b.logon,1,6) AS UNSIGNED) as logon, a.nomefantasia, a.vendedor,
			date_format(a.dt_cad, '%d/%m/%Y') AS data, c.fantasia from cadastro a
			inner join logon b on a.codloja = b.codloja
			inner join franquia c on a.id_franquia = c.id
			where a.dt_cad like '$periodo' $sitcli $frq
			group by a.codloja order by $ordenacao";
if ($opcao == 2) 
$comando = "select a.codloja, CAST(MID(b.logon,1,6) AS UNSIGNED) as logon, c.nomefantasia, c.vendedor, date_format(c.dt_cad, '%d/%m/%Y')
			as data, date_format(a.data_documento, '%d/%m/%Y') as datacanc from pedidos_cancelamento a
			inner join logon b on a.codloja=b.codloja
			inner join cadastro c on a.codloja=c.codloja
			where data_documento like '$periodo' $frq
			group by a.codloja order by $ordenacao";
if ($opcao == 4) 
$comando = "select count(*), a.codloja, b.razaosoc as fantasia, mid(a.nomefantasia,1,25) nomefantasia, a.uf, a.cidade,
			a.bairro, a.end, a.cep, a.fone, a.fax, a.email, a.tx_mens, a.boleto, a.carteira, a.diapagto,
			date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli, d.descsit,CAST(MID(e.logon,1,6) AS UNSIGNED) as logon
			from cs2.cadastro a
			inner join cs2.franquia b on a.id_franquia=b.id 
			left join cs2.situacao d on a.sitcli=d.codsit
			left Join cs2.logon e On a.codloja=e.codloja
			left outer join cs2.pedidos_cancelamento f on a.codloja=f.codloja
			where sitcli<2 $frq and pendencia_contratual = 1  and f.data_documento is NULL
			group by a.codloja order by a.dt_cad";

$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0")
	{
	echo "<table width=\"660\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"20\" class=\"titulo\">
			<td align=\"center\" width=\"100%\">Nenhum cliente cadastrado neste periodo!</td></tr></table>";
	}
	else
	{
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"bodyText\">
	 		<tr>
				<td colspan=\"7\" class=\"titulo\">EXTRATO DE CONTRATOS DO MES $contmes/$contano</td>
			</tr>
			<tr height=\"20\" bgcolor=\"FF9966\">
				<td align=\"center\" >ID</td>
				<td align=\"center\" >Codigo</td>
				<td align=\"center\" >Nome de Fantasia</td>
				<td align=\"center\" >Data de Afilia&ccedil;&atilde;o</td>
				<td align=\"center\" >Data de Cancelamento</td>
				<td align=\"center\" >Franquia</td>
				<td align=\"center\" >Vendedor</td>
			</tr>
			";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$id = $matriz['codloja'];
		$logon = $matriz['logon'];
	  	$nomef = $matriz['nomefantasia'];
		$data = $matriz['data']; 
		$vendedor = $matriz['vendedor'];
		$datacanc = $matriz['datacanc'];
		$fantasia = $matriz['fantasia'];
		if (empty($datacanc)) $datacanc="-";
		$string = $nomef;
		$limite = 30;
		$sizeName = strlen($string);
		//
		echo "<tr height=\"22\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
	  	echo " 	<td align=\"center\">$id</td>
				<td align=\"center\">$logon</td>
	  	      	<td>&nbsp;";
		//if ($tipo != 'd') echo "<a href=\"painel.php?pagina1=clientes/a_cons_codigo.php&codigo=$logon\">";
			  for($num=0;$num<$limite;$num++) {
    				print($string[$num]);
					}
					if($sizeName>$limite){echo"...";}
		echo "</a></td>
	  	      	<td align=\"center\">$data</td>
				<td align=\"center\">$datacanc</td>
				<td align=\"center\">$fantasia</td>
			  	<td align=\"center\">$vendedor</td>
	  	      	</tr>";
		}
		$a = $a - 1;
		echo "
			<tr height=\"20\">
				<td colspan=\"5\" class=\"subtitulodireita\" align=\"center\">Total de Clientes do Periodo $contmes/$contano &nbsp;</td>
				<td class=\"subtitulodireita\"><b> $a</b> Clientes</td>
				<td class=\"subtitulodireita\">&nbsp;</td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
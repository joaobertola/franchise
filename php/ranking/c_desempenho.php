<?php
//pega o periodo
$ano = date(Y);
$mes = date(m);
$mes_anterior = ($mes + 100) - 1;
$mes_anterior = substr($mes_anterior, 1, 3);
if ( $mes_anterior == '00' ) $mes_anterior = '01';

$query = "DROP table IF EXISTS tmp_rank";
$result = mysql_query($query,$con);

$command = "create TEMPORARY table tmp_rank (
		    id_franquia int(11),
		    qtd_atual int(11),
		    qtd_anterior int(11) )";
		   
$cmd = mysql_query($command, $con);

$sql = "    INSERT INTO tmp_rank(id_franquia,qtd_anterior)  
            SELECT id_franquia, count(*) qtd from cadastro 
			WHERE sitcli<2 and dt_cad like '$ano-$mes_anterior%' group by id_franquia";

$resposta = mysql_query($sql,$con);
			
$sql = "    SELECT id_franquia, count(*) qtd from cadastro 
			WHERE sitcli<2 and dt_cad like '$ano-$mes%' group by id_franquia";
		
$resposta = mysql_query($sql,$con);
while ($array = mysql_fetch_array($resposta)) { 
	$idfranquia = $array["id_franquia"];
	$qtd = $array['qtd'];
	$xsql = "UPDATE tmp_rank SET qtd_atual='$qtd' where id_franquia = '$idfranquia' ";
	mysql_query($xsql,$con);
}

//come�a a tabela
echo "<table align=\"center\" width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\">
 		<tr>
			<td colspan=\"7\" class=\"titulo\">Ranking de Desempenho de Vendas</td>
		</tr>
 		<tr height=\"20\" bgcolor=\"87b5ff\">
			<td>&nbsp;</td>
			<td align=\"center\" >Vendas<br>mes anterior</td>
			<td align=\"center\" >Vendas<br>mes atual</td>
			<td align=\"center\" >Franqueado</td>			
		</tr>
		<tr>
			<td align=center><img src=\"../img/acima.gif\" border=\"0\"></td>
			<td colspan=\"5\">&nbsp;</td>
		</tr>";
//$sql = "SELECT id_franquia, qtd_atual, qtd_anterior from tmp_rank";
$sql = "SELECT b.id, b.fantasia, b.fone1, b.fone2, b.cel01socio, a.qtd_atual, a.qtd_anterior 
		FROM tmp_rank a
		INNER JOIN franquia b ON a.id_franquia = b.id
		ORDER BY a.qtd_anterior";
$resposta = mysql_query($sql,$con);
while ($array = mysql_fetch_array($resposta)) { 
	$a = $a + 1;
	$fantasia  	= $array["fantasia"];
	$qtdatual   = $array['qtd_atual'];
	if (empty($qtdatual)) $qtdatual = "-";
	$qtdanterior= $array['qtd_anterior'];
	if (empty($qtdanterior)) $qtdanterior = "-";
	$comercial	= $array['fone1'];
	$residencial= $array['fone2'];
	$celular = $array['cel01socio'];
	$_id = $array['id'];

	echo "<tr height=\"26\"";
	if (($a%2) != 0) {
		echo "bgcolor=\"#E5E5E5\">";
	} else {
		echo ">";
	}
	if ($qtdanterior<=10) echo "<td align=center><img src=\"../img/red.gif\" border=\"0\"></td>";
	if (($qtdanterior>10)&&($qtdanterior<=20)) echo "<td align=center><img src=\"../img/yellow.gif\" border=\"0\"></td>";
	if ($qtdanterior>20) echo "<td align=center><img src=\"../img/green.gif\" border=\"0\"></td>";
	echo "	<td align=\"center\">$qtdanterior</td>
  	   	  	<td align=\"center\">$qtdatual</td>
			<td>
			<a href=\"painel.php?pagina1=Franquias/b_altfranqueado.php&id=$_id\" onMouseOver=\"window.status='Alterar franqueado'; return true\">
			<font color=\"#0000ff\">$fantasia</font></a></td>		
		</tr>";
} //fim while
  	 echo "<tr>
			<td align=center valign=top><img src=\"../img/embaixo.gif\" border=\"0\"></td>
			<td colspan=\"5\">&nbsp;</td>
		</tr>
	 	<tr class=\"subtitulodireita\" height=\"20\">
			<td colspan=\"7\">&nbsp;</td>
		</tr>
	</table>"; //fim tabela

$res = mysql_close ($con);
?>
<br />
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>

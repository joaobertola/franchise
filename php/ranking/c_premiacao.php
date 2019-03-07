<?php
require "connect/sessao.php";

$query = "DROP table IF EXISTS tmp_rank";
$result = mysql_query($query,$con);

$command = "create TEMPORARY table tmp_rank (
		   qtd_venda int(11),
		   qtd_cancel int(11),
		   id_franquia int(11),
		   nome_franquia varchar(60))";
$cmd = mysql_query($command, $con);

$sql = "select count(*) qtdvenda, a.id_franquia, b.fantasia,'VENDAS' as tipo  from cadastro a
	inner join franquia b on a.id_franquia=b.id
	where a.sitcli<2 and a.dt_cad BETWEEN '2007-12-31' and '2008-12-31' group by a.id_franquia 
	UNION
	select count(*) qtdcancel, a.id_franquia, c.fantasia,'CANCEL' as tipo from cadastro a
	inner join pedidos_cancelamento b on a.codloja=b.codloja
	inner join franquia c on a.id_franquia=c.id
	where b.data_documento between '2007-12-31' and '2008-12-31' GROUP by id_franquia order by id_franquia,qtdvenda desc";
$resposta = mysql_query($sql);

while ($array = mysql_fetch_array($resposta)) { 
	$idfranquia = $array["id_franquia"];
	$nome_franquia	= $array["fantasia"];
	$qtd = $array['qtdvenda'];
	$tipo = $array['tipo'];

	$comandox = "select count(*) tot from cs2.tmp_rank where  id_franquia = $idfranquia";
	
	$comandoxx = mysql_query($comandox,$con);
	$linhas = mysql_result($comandoxx,0,'tot');

	if ($linhas == "0") {
	  // inserindo registro

	  if ($tipo == 'VENDAS'){
	    $string = "insert into cs2.tmp_rank(qtd_venda,id_franquia,nome_franquia) values('$qtd','$idfranquia','$nome_franquia')";
		mysql_query($string,$con);
	}
	  else
	    $string = mysql_query("insert into cs2.tmp_rank(qtd_cancel,id_franquia,nome_franquia) values('$qtd','$idfranquia','$nome_franquia')",$con);
	  
	}else{
	  // alterando registro
	  if ($tipo == 'VENDAS')
	    $string = mysql_query("update cs2.tmp_rank set qtd_venda=$qtd where id_franquia=$idfranquia",$con);
	  else
	    mysql_query("update cs2.tmp_rank set qtd_cancel=$qtd where id_franquia=$idfranquia",$con);
	}
}

$resposta = mysql_query("Select a.id_franquia, a.nome_franquia, 
                         ifnull(a.qtd_venda,0)  qtd_venda, 
						 ifnull(a.qtd_cancel,0) qtd_cancel,
						 (ifnull(a.qtd_venda,0) - ifnull(a.qtd_cancel,0)) saldo,
						 b.foto, b.estrela
						 from cs2.tmp_rank a
inner join franquia b on a.id_franquia=b.id
where id_franquia > 1 order by saldo desc",$con);
//come�a a tabela
echo "<table align=\"center\" width=\"600\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"bodyText\">
		<tr>
			<td colspan=\"7\" class=\"titulo\">Ranking de Premia��o Anual</td>
		</tr>
		<tr height=\"20\" bgcolor=\"FF9966\">
			<td align=\"center\">Posi&ccedil;&atilde;o</td>
			<td align=\"center\">Franqueado</td>
			<td align=\"center\">Vendas</td>
			<td align=\"center\">Cancelamentos</td>
			<td align=\"center\">Saldo Liquido</td>
			<td>Premio</td>
			<td></td>
		</tr>
		<tr>
			<td colspan=\"6\" height=\"1\" bgcolor=\"#666666\">
			</td>
		</tr>";

while ($registro = mysql_fetch_array($resposta)) {
	$id	= $registro['id_franquia'];
	$nome	= $registro['nome_franquia'];
	$venda = $registro['qtd_venda'];
	$cancel = $registro['qtd_cancel'];
	$saldo = $registro['saldo'];
	$estrela = $registro['estrela'];
	$a = $a + 1;
	echo "<tr height=\"40\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		echo "	<td align=\"center\">$a &ordm;</td>
	  	   	  	<td align=\"center\">";
		if (!empty($estrela)) {
			if ($estrela >= 10) {
				echo "<img src=\"../img/diamante.gif\"><img src=\"../img/diamante.gif\">";
				for($i=11;$i<=$estrela;$i++){
					echo "<img src=\"../img/estrela.gif\">";
				}
			}
			elseif (($estrela >= 6)&&($estrela <10)) {
				echo "<img src=\"../img/diamante.gif\">";
				for($i=6;$i<=$estrela;$i++){
					echo "<img src=\"../img/estrela.gif\">";
				}
			} else {
				for($i=1;$i<=$estrela;$i++){
					echo "<img src=\"../img/estrela.gif\">";
				}
			}
			echo "<br>";
		}
		echo	"$nome</td>
				<td align=\"center\">$venda</td>
				<td align=\"center\">$cancel</td>";
		echo " 	<td align=\"center\">";
		if ($saldo <= 0) echo "<font color=\"#ff0000\">$saldo</font></td>";
		else echo "$saldo</td>";
		if ($a==1) echo "<td><img src=\"../img/navio.gif\"></td>";
		elseif ($a==2) echo "<td><img src=\"../img/pc.gif\"></td>";
		elseif ($a==3) echo "<td><img src=\"../img/predio.gif\" width=\"40\" height=\"40\" ></td>";
		else echo "<td></td>";
		echo "<td><img src='ranking/d_gera.php?id=".$id."' border='1'></td>
		<td></td>
			</tr>";
}
echo "<tr>
		<td colspan=\"6\" height=\"1\" bgcolor=\"#666666\">
		</td>
	  </tr>
	  </table>";
$res = mysql_close ($con);
?>
<br />
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
<br />
<br />
<table border="1" align="center" width="100%">
<tr>
	<td><?php if (empty($act)) { include "mensagens.php"; } else { include "$act.php"; } ?></td>
	<td><?php if (empty($act)) { include "mensagens.php"; } else { include "$act.php"; } ?></td>
</tr>
</table>
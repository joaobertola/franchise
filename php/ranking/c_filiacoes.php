<?php
require "connect/sessao.php";

function mod($nr1,$nr2){
	$val1 = floor($nr1/$nr2);
	$resto = $nr1 -($val1*$nr2);
	return $val1.'-'.$resto;
}

//faz o ranking de venda de acordo ao numero de vendas do periodo
$command = "select count(*) qtd, b.fantasia, b.estrela from cadastro a
			inner join franquia b on a.id_franquia=b.id
			where a.sitcli< 2
			AND b.id_adm <> 'S' AND ( b.classificacao = 'M' or b.classificacao = 'X' ) 
			group by a.id_franquia 
		    order by qtd desc";
$res = mysql_query($command,$con);
$linhas = mysql_num_rows($res);
$linhas1 = $linhas+3;

//come�a a tabela
	echo "<table class=\"table table-striped table-responsive col65\" align=\"center\">
			<thead>
				<tr>
					<th colspan=\"5\"><h4 class=\"text-center\">Ranking de Carteira de Clientes</h4></th>
				</tr>
				<tr height=\"20\">
					<th class=\"text-center\" ><h5>Posição</h5></th>
					<th class=\"text-center\" ><h5>Franqueado</h5></th>
					<th class=\"text-center\" ><h5>Quantidade</h5></th>
				</tr>
			</thead>
			<tbody>";
	  for ($a=1; $a<=$linhas; $a++) {
	  	$matriz = mysql_fetch_array($res);
		$franquia = $matriz['fantasia'];
		$qtd = $matriz['qtd'];
		$estrela = $matriz['estrela'];

		echo "<tr height=\"26\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";	
		}
		echo "	<td class=\"text-center\">$a &ordm;</td>
	  	   	  	<td class=\"text-center\">";
		if (!empty($estrela)) {
			$resto = mod($estrela,5);

			$array = explode('-',$resto);
			
			$diamante = $array[0];
			$star = $array[1];
			
			for($i=0;$i<$diamante;$i++) {
				echo "<img src=\"../img/diamante.png\"  style=\"width:auto; height:50px; float:left;\">";
			}
			for($j=1;$j<=$star;$j++){
					echo "<img src=\"../img/estrela.png\" style=\"width:auto; height:50px; float: left;\">";
			}

			echo "<br>";
		}
		
		if ($_SESSION['ss_tipo'] != 'a' )
			$qtd = '';
			
		echo "$franquia</td>
	  	      	<td class=\"text-center\"><font color=\"#0000ff\">&nbsp;$qtd</font></font></td>
			</tr>";
		} //fim for
	  	 	
		$sql = "select a.id, a.fantasia,
			 ( SELECT count(*) from cadastro where sitcli < 2 and id_franquia = a.id ) AS total
			 from franquia a 
			where (a.classificacao = 'M' or a.classificacao = 'X') AND a.id_adm <> 'S'
			group by a.id
			order by a.id";
	    $qry = mysql_query($sql, $con);
		while($rs = mysql_fetch_array($qry)){
			if($rs['total'] == 0){
				
				if ( ($a%2) == 0) $cor = "#E5E5E5";
					else $cor = "";
					
				echo "<tr height=\"20\" bgcolor=\"$cor\">
					<td class=\"text-center\" >";
					echo $a ." &ordm;";
					echo "</td>
					<td class=\"text-center\" >".$rs['fantasia']."</td>
					<td calss=\"text-center\"><font color=\"#0000ff\">&nbsp;0</font></font></td>
				</tr>";
				$a++;
			}
		}
		echo "
			<tr class=\"text-right\" height=\"20\">
				<td colspan=\"3\">&nbsp;</td>
			</tr>";
		
		echo "
		</tbody>
		<tfoot>
			<tr>
				<td colspan=\"3\">
				<input type=\"button\" onClick=\"javascript: history.back();\" value=\"Voltar\" class=\"botao3d\" />
				</td>
			</tr>
		</tfoot>
		</table>";
	
$res = mysql_close ($con);
?>

<?php
require "connect/sessao.php";

function mod($nr1,$nr2){
	$val1 = floor($nr1/$nr2);
	$resto = $nr1 -($val1*$nr2);
	return $val1.'-'.$resto;
}

$ano = $_POST['ano'];
$mes = $_POST['mes'];

//fun��o para tratar os resultados com 2 decimais
function FloatFormat($Value, $Precision) 
{ 
    $decimals = log10(abs($Value)); 
    $decimals = - (intval(min($decimals, 0)) - $Precision); 
	if ($decimals = 2 ) $format = "%." . $decimals . "f";
    return sprintf($format, $Value); 
} 

//conta quantas vendas foram realizadas no periodo
$query = "select count(*) from cadastro where dt_cad like '$ano-$mes%'";
$query = mysql_query($query,$con);
$query = mysql_fetch_array($query);
$total = $query[0];
//caso n�o tiver vendas aparece um alerta e volta � pagina anterior
if (!$total) {
	echo "<p>&nbsp;</p>
		 <table align=\"center\">
			<tr>
				<td width=\"500\" class=\"titulo\">Sem vendas registradas neste periodo</td>
			</tr>
		  </table>";
} else {
//conta quantos franqueados est�o cadastrados no sistema
$comando = "select count(*) from franquia where sitfrq=0";
$comando = mysql_query($comando,$con);
$comando = mysql_fetch_array($comando);

//cria o vetor para escrever o m�s por extenso
$sql = "select * from meses where id='$mes'";
$resposta = mysql_query($sql,$con);
while ($array = mysql_fetch_array($resposta))
	{$mes_ano	= $array["mes"];
}
//faz o ranking de venda de acordo ao numero de vendas do periodo
$command = "select count(*) as qtd, sum(valor), ( sum(valor) / count(*) ) as media, c.fantasia, c.id, c.estrela from titulos a
			inner join cadastro b on a.codloja=b.codloja
			inner join franquia c on b.id_franquia = c.id
			where a.vencimento like '$ano-$mes%'
			group by b.id_franquia
			order by media desc";
$res = mysql_query($command,$con);
$linhas = mysql_num_rows($res);
$linhas1 = $linhas+3;
//come�a a tabela
	echo "<table align=\"center\" width=\"600\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\">
	 		<tr>
				<td colspan=\"6\" height=\"1\" bgcolor=\"#999999\"></td>
			</tr>
	 		<tr>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" class=\"titulo\">
				<td width='50' align=\"center\" >Posi&ccedil;&atilde;o</td>
				<td width='80' align=\"center\" >Média do Mês</td>
				<td align=\"center\" >Franqueado</td>
				<td></td>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr>
				<td colspan=\"4\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
		$id = $matriz['id'];
		$franqueado = $matriz['fantasia'];
		$qtd = $matriz['qtd'];
		$media = $matriz['media'];
		$media = number_format($media,2,',','.');
		$estrela = $matriz['estrela'];

		echo "<tr height=\"26\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		if ($_SESSION['id'] != 163 )
			$media = '';
			
		echo "	<td align=\"center\">$a &ordm;</td>
	  	   	  	<td align=\"center\">R$ $media</td>
	  	      	<td align=\"center\">";
		if (!empty($estrela)) {
			$resto = mod($estrela,5);

			$array = explode('-',$resto);
			
			$diamante = $array[0];
			$star = $array[1];
			
			for($i=0;$i<$diamante;$i++) {
				echo "<img src=\"../img/diamante.gif\">";
			}
			for($j=1;$j<=$star;$j++){
					echo "<img src=\"../img/estrela.gif\">";
			}

			echo "<br>";
		}
		echo "	$franqueado</td>
			  </tr>";
		}
		echo "
			<tr class=\"subtitulodireita\" height=\"20\">
				<td colspan=\"5\">&nbsp;</td>
			</tr>
			<tr>
				<td colspan=\"5\" align=\"right\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<br />
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /> </div>
<br />
<br />
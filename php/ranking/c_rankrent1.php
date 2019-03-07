<?php
require "connect/sessao.php";

function fvenc($data,$dt_base) {
	$d_data = substr($data,6,2);
	$m_data = substr($data,4,2);
	$a_data = substr($data,0,4);
	$d_base = substr($dt_base,6,2);
	$m_base = substr($dt_base,4,2);
	$a_base = substr($dt_base,0,4);
	$dias_data = floor(gmmktime (0,0,0,$m_data,$d_data,$a_data)/ 86400);
	$dias_base = floor(gmmktime (0,0,0,$m_base,$d_base,$a_base)/ 86400);
	$val = $dias_data - $dias_base;
	return $val;
}

function mod($nr1,$nr2){
	$val1 = floor($nr1/$nr2);
	$resto = $nr1 -($val1*$nr2);
	return $val1.'-'.$resto;
}

$ano = $_POST['ano'];
if (empty($ano)) $ano = $_GET['ano'];
$mes = $_POST['mes'];
if (empty($mes)) $mes = $_GET['mes'];
$pagina = $_GET['pagina'];

//conta quantas vendas foram realizadas no periodo
$query = "SELECT COUNT(*) FROM cadastro WHERE dt_cad LIKE '$ano-$mes%'";
$query = mysql_query($query,$con);
$query = mysql_fetch_array($query);
$total = $query[0];
//caso não tiver vendas aparece um alerta e volta a pagina anterior
if (!$total) {
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"20\">
			<td align=\"center\" width=\"100%\" class=\"titulo\">Sem vendas registradas neste periodo</td></tr></table>";
} else {


//cria o vetor para escrever o mês por extenso
$sql = "select * from meses where id='$mes'";
$resposta = mysql_query($sql);
while ($array = @mysql_fetch_array($resposta))
	{$mes_ano	= $array["mes"];
}


//faz o ranking de venda de acordo ao numero de vendas do periodo
if( ($tipo == 'a') or ($tipo == 'd') )
{
$command = "SELECT 
				(	SELECT COUNT(*) FROM cs2.cadastro 
					WHERE dt_cad like '$ano-$mes%' AND id_franquia = id AND sitcli < 2 AND contadorsn != 'S'
				) qtd, 
				fantasia, foto, estrela, id as id_franquia, dt_cad
			FROM cs2.franquia 
			WHERE classificacao <> 'J' AND ( MID(fantasia,1,8)='FRANQUIA' or MID(fantasia,1,14)='MICRO-FRANQUIA' )
			AND id != 139 AND id != 247 AND id != 5 AND id != 4
			GROUP BY id
			ORDER BY qtd DESC";
}else{
$command = "SELECT COUNT(*) qtd, a.id_franquia, b.fantasia, b.foto, b.estrela from cadastro a
			INNER JOIN franquia b on a.id_franquia=b.id
			WHERE a.sitcli<2 AND a.contadorsn != 'S' AND a.dt_cad like '$ano-$mes%' AND id_franquia != '163' AND b.classificacao <> 'J'
			GROUP BY a.id_franquia ORDER BY qtd DESC";
}

$res = mysql_query($command,$con);
$linhas = mysql_num_rows($res);
$linhas1 = $linhas+3;

//echo "<pre>".$command;
//começa a tabela
echo "<table align=\"center\" width=\"600\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"quente\">
		  	<tr>
    			<td colspan=6 class=titulo>RANKING DE VENDAS</td>
  			</tr>
	 		<tr>
				<td colspan=\"7\" height=\"1\" bgcolor=\"#999999\"></td>
			</tr>
	 		<tr>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" class=\"total\">
				<td align=\"center\">Posição</td>
				<td align=\"center\">Franqueado</td>
				<td align=\"center\">Quantidade</td>
				<td align=\"center\">Foto</td>
				<td></td>
				<td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr>
				<td colspan=\"5\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>";
			
	for ($a=1; $a<=$linhas; $a++) {
	  	//caso for igual a 0
	  	$matriz = mysql_fetch_array($res);
		if ($matriz['id_franquia'] != 0) 
			$franqueado = $matriz['id_franquia'];
		else
			$franqueado = -1;
		//pega quantidade, nome de fantasia e foto
		$idx = $matriz['id_franquia'];

		$qtd = $matriz['qtd'];
		$nome_franquia	= $matriz['fantasia'];
		$foto	= $matriz['foto'];
		$estrela= $matriz['estrela'];
		$dt_cad = $matriz['dt_cad'];
		$dias = fvenc(str_replace("-","",'2010-04-20'),str_replace("-","",$dt_cad))+1;
		
		//echo "<tr ";
		if (($a%2) == 0) {
			if ( $qtd <= 5 )
				if ( $dias > 90 )
					echo "<tr bgcolor='#E5E5E5' >"; // esquema vermelho
				else echo "<tr bgcolor='#E5E5E5' >";
			else
				if ( $dias > 90 )
					echo "<tr bgcolor='#E5E5E5' >";

		} else {
			if ( $qtd <= 5 ){
				if ( $dias > 90 )
					echo "<tr bgcolor='#FFFFFF' >"; // esquema vermelho
			}	
		}
		
			//EXIBE IMAGEM
		$sql     = "SELECT nome_foto FROM cs2.franquia_foto WHERE id_franquia = $idx";
		$qry_sql = mysql_query($sql,$con);
		$link_foto = '';
		if ( mysql_num_rows($qry_sql) > 0 ){
			while ( $reg_foto = mysql_fetch_array($qry_sql) ){
				$link_foto .= '<img src=area_restrita/'.$reg_foto['nome_foto'].'>';
			}
		}else
			$link_foto = "<img src=ranking/d_gera.php?idx=$idx>";
			
			
		if ($a=="1") {
				echo "<td align=\"center\"><img src=\"../img/ouro.jpg\"></td>
					  <td align=\"center\"><font size=\"5\">";
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
				echo "$nome_franquia</font></td>
					  <td align=\"center\"><font color=\"#006666\" size=\"5\">&nbsp;$qtd</font></td>
					  <td>$link_foto</td>";
			}
			elseif ($a=="2") {
				
				echo "<td align=\"center\"><img src=\"../img/prata.jpg\"></td>
					  <td align=\"center\"><font size=\"4\">";
					  
				if ( ! empty($estrela) ) {
					
					$resto    = mod($estrela,5);
					$array    = explode('-',$resto);
					$diamante = $array[0];
					$star     = $array[1];
					
					for($i=0;$i<$diamante;$i++) {
						echo "<img src=\"../img/diamante.gif\">";
					}
					for($j=1;$j<=$star;$j++){
							echo "<img src=\"../img/estrela.gif\">";
					}

					echo "<br>";
				}
				echo "$nome_franquia</font></td>
					  <td align=\"center\"><font color=\"#006666\" size=\"4\">&nbsp;$qtd</font></td>
					  <td>$link_foto</td>";}
			elseif ($a=="3") {
				echo "<td align=\"center\"><img src=\"../img/bronze.jpg\"></td>
					  <td align=\"center\"><font size=\"3\">";
				if (!empty($estrela)) {
					
					$resto    = mod($estrela,5);
					$array    = explode('-',$resto);
					$diamante = $array[0];
					$star     = $array[1];
					
					for( $i=0 ; $i < $diamante ; $i++ ) {
						echo "<img src=\"../img/diamante.gif\">";
					}
					for($j=1;$j<=$star;$j++){
							echo "<img src=\"../img/estrela.gif\">";
					}

					echo "<br>";
				}
				echo "$nome_franquia</font></td>
					  <td align=\"center\"><font color=\"#006666\" size=\"3\">&nbsp;$qtd</font></td>
					  <td>$link_foto</td>";}
			else {
				echo "<td align=\"center\">$a &ordm;</td>
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
				echo "$nome_franquia</td>
					  <td align=\"center\"><font color=\"#006666\">&nbsp;$qtd</font></font></td>
					  <td>$link_foto</td>
				<td></td>";
		} //fim o if
	  	 echo "</tr>";
	} //fim do for
		echo "<tr>
				<td colspan=\"6\" align=\"right\" height=\"1\" bgcolor=\"#666666\">
				</td>
			</tr>
			<tr>
				<td colspan=\"6\" height=\"20\"></td>
			</tr>
			<tr>
				<td></td>
				<td colspan=\"2\" align=\"right\"><b>Total de vendas do mes de $mes_ano / $ano:</b></td>
				<td align=\"center\"><b><font color=\"#ff6600\">$total</font></b></td>
			</tr>
			<tr>
				<td colspan=\"6\" height=\"20\"></td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<div align="center"><input type="button" onClick="javascript: history.back();" value="       Voltar       " /></div>
<?php 
// include "ranking/mensagens.php"; 
?>
<?php
require_once('../connect/sessao.php');
//session_start();
//
//$name = $_SESSION["ss_name"];
//$tipo = $_SESSION["ss_tipo"];
//if (($name=="") || ($tipo!="a")){
//	session_unregister($_SESSION['name']);
//	session_destroy();
//	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
//	die;
//}

//fun��o para tirar o resto da opera��o
function mod($nr1,$nr2){
	$val1 = floor($nr1/$nr2);
	$resto = $nr1 -($val1*$nr2);
	return $val1.'-'.$resto;
}

$comando = "select id, razaosoc, foto, estrela from franquia where tipo='b' order by id";
$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0")
	{
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"20\">
			<td align=\"center\" width=\"100%\">Nenhuma franquia cadastrada!</td></tr></table>";
	}
	else
	{
	echo "<table width=\"500\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\" align=\"center\">
			<tr height=\"20\" class=\"titulo\">
				<td align=\"center\">Código</td>
				<td align=\"center\">Nome da Franquia</td>
				<td align=\"center\">&nbsp;</td>
				<td align=\"center\">Incluir</td>
				<td align=\"center\">&nbsp;Remover</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$id = $matriz['id'];
	  	$razao = $matriz['razaosoc'];
	  	$foto = $matriz['foto'];
		$estrela = $matriz['estrela'];
		//
	  	echo "<tr ";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		echo " 	<td align=\"center\">$id</td>
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
				
		echo "$razao</td>
			  <td aling=\"center\"><img src='ranking/d_gera.php?id=".$id."' border='1'></td>
			  <td align=\"center\"><a href=\"area_restrita/d_altestrela.php?id=$id&tipo=1\" onMouseOver=\"window.status='Incluir estrelas'; return true\"><IMG SRC=\"../img/alt.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
			  <td align=\"center\"><a href=\"area_restrita/d_altestrela.php?id=$id&tipo=2\" onMouseOver=\"window.status='Tirar estrelas'; return true\"><IMG SRC=\"../img/exc.gif\" width=\"14\" height=\"16\" border=\"0\"></a></td>";
		echo "</tr>";
		
		}
		echo "<tr class=\"subtitulodireita\">
				<td colspan=\"4\">Número de total de franqueados habilitados</td>
				<td align=center><b>$a</b></td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
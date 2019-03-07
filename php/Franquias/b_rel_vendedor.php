<?
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;

if ($tipo == 'b') $frq = "where id_franquia = '$id_franquia'";
else $frq = "";
$comando = "select codv, nome, bairro_resid, cidade_resid, celular_resid from vendedor $frq order by nome";
$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0") {
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
				<td align=\"center\" width=\"100%\" class=titulo><img src=\"../img/triste.gif\" border=0 alt=\"Nada ainda\" >&nbsp;Nenhuma vendedor cadastrado  at&eacute; agora</td>
			</tr>
		  </table>";
} else {
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\">
		  <form id=\"form1\" name=\"form1\" method=\"post\" action=\"painel.php?pagina1=Franquias/b_cad_vendedor.php\">
			<tr height=\"20\" class=\"subtitulodireita\">
				<th align=\"center\"></th>
				<th align=\"center\">Código</th>
				<th align=\"center\">Nome do Vendedor</th>
				<th align=\"center\">Bairro onde reside</th>
				<th align=\"center\">Cidade</th>
				<th align=\"center\">Telefone Celular</th>
				<th align=\"center\"><font color=\"#00ff00\">Alterar</font></th>
				<th align=\"center\"><font color=\"#ff6600\">Excluir</font></th>
				<th align=\"center\">Sit.</th>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$codv = $matriz['codv'];
	  	$nome = $matriz['nome'];
	  	$bairro_resid = $matriz['bairro_resid'];
		$cidade_resid = $matriz['cidade_resid'];
		$celular_resid = $matriz['celular_resid'];
		$sitven = $matriz['sitven'];
		$string = $nome;
		$limite = 25;
		$sizeName = strlen($string);
		//
		$string0 = $nome;
		$limite0 = 25;
		$sizeName0 = strlen($string0);
	  	echo "<tr height=\"22\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		echo " 	<td align=\"center\"><input name=\"selected[]\" type=\"checkbox\" value=\"$codv\" /></td>
	  	   	  	<td align=\"center\">$codv</td>
	  	      	<td align=\"left\">&nbsp;<a href=\"painel.php?pagina1=Franquias/b_cad_vendedor.php&go=mostrar&codv=$codv\" onMouseOver=\"window.status='Alterar franqueado'; return true\"><font color=\"#0000ff\">";
			  for($num=0;$num<$limite;$num++) {
    				print($string[$num]);
					}
					if($sizeName>$limite){echo"...";}
		echo "</font></a></td>
			  <td align=\"center\">$bairro_resid</td>
			  <td align=\"center\">$cidade_resid</td>
			  <td align=\"center\">$celular_resid</td>
			  <td align=\"center\"><a href=\"painel.php?pagina1=area_restrita/b_cad_vendedor.php&id=$id\" onMouseOver=\"window.status='Alterar franqueado'; return true\"><IMG SRC=\"../img/alt.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
			  <td align=\"center\"><a href=\"javaScript:if (confirm ('Tem certeza que deseja excluir o registro?')) window.open('area_restrita/b_cad_vendedor.php?codv=$codv&go='exc_sel','_self');\" onMouseOver=\"window.status='exc_cl'; return true\"><IMG SRC=\"../img/exc.gif\" width=\"14\" height=\"16\" border=\"0\"></a></td>";
			  
	  	if ($sitfrq == 0) {
				echo "<td><IMG SRC=\"../img/si.gif\"></td>";
			} else {
				echo "<td><IMG SRC=\"../img/no.gif\"></td>";}      	
		echo "</tr>";
		
		}
		$a = $a - 1;
		echo "<tr class=\"subtitulodireita\">
				<td></td>
				<td colspan=\"6\">Número de total de vendedores habilitados</td>
				<td>$a</td>
				<td></td>
			</tr>
			<tr>
				<td colspan=\"9\" align=\"right\">
					<input  type=\"submit\" value=\"Excluir registros selecionados\" class=\"botao3d\" />
				</td>
			</tr>
			</form>
		</table>";
} //fim else
$res = mysql_close ($con);
?>
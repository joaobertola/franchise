<?
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];
if (!isset($nome2) && ($tipo != "a")) exit;

$comando = "select * from vendedores order by vendedor";
$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0")
	{
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr style=\"background:url(../../imagem/bg_tabela1.jpg)\" height=\"20\">
			<td align=\"center\" width=\"100%\"><font color=\"#999999\">Nenhum vendedor cadastrado!</font></td></tr></table>";
	} 
	else 
	{
	echo "<table width=\"700\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	 		<tr><td colspan=\"8\" height=\"16\" bgcolor=\"#999999\"></td></tr>
	 		<tr><td rowspan=\"$linhas1\" width=\"1\" bgcolor=\"#999999\"></td></tr>
			<tr style=\"background:url(../../imagem/bg_tabela1.jpg)\" height=\"20\">
			<td align=\"center\" width=\"30\">Sel</td>
			<td align=\"center\" width=\"40\">Num</td>
			<td align=\"center\" width=\"40\">ID</td>
			<td width=\"170\">&nbsp;Consultor</td>
			<td align=\"left\" width=\"120\">&nbsp;Tipo</td>
			<td align=\"center\" width=\"147\">&nbsp;Franquia</td>
			<td align=\"center\" width=\"50\"><font color=\"#00ff00\">Alterar</font></td>
			<td align=\"center\" width=\"50\"><font color=\"#ff6600\">Excluir</font></td></tr>
			<tr><td colspan=\"9\" height=\"1\" bgcolor=\"#666666\">
			<form id=\"form1\" name=\"form1\" method=\"post\" action=\"Franquias/b_exc_vend_sel.php\">
			</td></tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res); 
	  	$id = $matriz['id'];
	  	$vendedor = $matriz['vendedor'];
	  	$tipo = $matriz['tipo_consultor'];
		$franqueado = $matriz['franqueado'];
		$string = $vendedor;
		$limite = 10;
		$sizeName = strlen($string);
		//
		$string0 = $tipo;
		$limite0 = 10;
		$sizeName0 = strlen($string0);
	  	echo "<tr height=\"22\">
			  <td align=\"center\"><input name=\"selected[]\" type=\"checkbox\" value=\"$id\" /></td>
	  	      <td align=\"center\">$a</td>
	  	   	  <td align=\"center\">$id</td>
	  	      <td><font color=\"#0000ff\">&nbsp;";
			   for($num=0;$num<$limite;$num++) { 
    				print($string[$num]); 
					}
					if($sizeName>$limite){echo"...";}	
		echo "</font></td>
	  	      <td align=\"left\"><font color=\"#990000\">&nbsp;";
			  for($num0=0;$num0<$limite0;$num0++) { 
    				print($string0[$num0]); 
					}
					if($sizeName0>$limite0){echo"...";}	
		echo "</font></td>
			  <td align=\"center\"><font color=\"#990000\">$franqueado</font></td>
	  	      <td align=\"center\"><a href=\"Franquias/alt_usu.php?id=$id\" onMouseOver=\"window.status='most_vendedor'; return true\"><IMG SRC=\"../../imagem/alt.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
	  	      <td align=\"center\"><a href=\"javaScript:if (confirm ('Tem certeza que deseja excluir o registro?')) window.open('Franquias/b_exc_vend.php?id=$id','_self');\" onMouseOver=\"window.status='b_exc_vend'; return true\"><IMG SRC=\"../../imagem/exc.gif\" width=\"14\" height=\"16\" border=\"0\"></a></td></tr>";
		}
		echo "<tr><td colspan=\"10\" style=\"background:url(../../imagem/bg_tabela0.jpg)\" height=\"4\"></td></tr>
		<tr><td colspan=\"13\" height=\"5\"></td></tr>
		<tr><td colspan=\"13\" align=\"right\" height=\"23\">
		<input name=\"exc_up_sel\" type=\"image\" id=\"Submit\" value=\"exc_up_sel\" src=\"../../imagem/bt_exc_sel.jpg\"/>
		<a href=\"javaScript:if (confirm ('Tem certeza que deseja excluir todos os registros?')) window.open('usuario/exc_usu_td.php','_self');\" onMouseOver=\"window.status='exc_usu_td'; return true\"><IMG SRC=\"../../imagem/bt_exc_td.jpg\" width=\"230\" height=\"23\" border=\"0\"></a>
		</form>
		&nbsp;</td></tr>
		</table>";
	}
$res = mysql_close ($con);
?>
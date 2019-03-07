<?php
//require_once('../connect/sessao.php');
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

$comando = "SELECT id, usuario, senha, senha_restrita, razaosoc, fantasia, sitfrq 
			FROM franquia 
			WHERE classificacao <> 'J' 
			ORDER BY id";			
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
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\">
	 		<tr>
				<td colspan=\"6\" height=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" class=\"titulo\">
				<td align=\"center\"></td>
				<td align=\"center\">Nome da Franquia</td>
				<td align=\"center\">Razão Social</td>
				<td align=\"center\"><font color=\"#00ff00\">Alterar</font></td>
				<td align=\"center\"><font color=\"#ff6600\">Excluir</font></td>
				<td align=\"center\">Sit.</td>
				
			</tr>
			<tr>
				<td colspan=\"6\" height=\"1\" bgcolor=\"#666666\">
				<form id=\"form1\" name=\"form1\" method=\"post\" action=\"painel.php?pagina1=area_restrita/d_exc_sel.php\">
				</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$id = $matriz['id'];
		$usuario = $matriz['usuario'];
	  	$razao = $matriz['razaosoc'];
	  	$nome = str_pad($id,3,'0',STR_PAD_LEFT).' - '.$matriz['fantasia'];
		$senha = $matriz['senha'];
		$senha_restrita = $matriz['senha_restrita'];
		$sitfrq = $matriz['sitfrq'];
		$string = $nome;
		$limite = 50;
		$sizeName = strlen($string);
		//
		$string0 = $razao;
		$limite0 = 50;
		$sizeName0 = strlen($string0);
	  	echo "<tr height=\"22\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		echo " 	<td align=\"center\"><input name=\"selected[]\" type=\"checkbox\" value=\"$id\" /></td>
	  	      	<td align=\"left\">&nbsp;<a href=\"painel.php?pagina1=area_restrita/d_altfranqueado.php&id=$id\" onMouseOver=\"window.status='Alterar franqueado'; return true\"><font color=\"#0000ff\">";
			  for($num=0;$num<$limite;$num++) {
    				print($string[$num]);
					}
					if($sizeName>$limite){echo"...";}
		echo "</font></a></td>
	  	      	<td align=\"left\">";
			  for($num0=0;$num0<$limite0;$num0++) {
    				print($string0[$num0]);
					}
					if($sizeName0>$limite0){echo"...";}
		echo "</td>
			  <td align=\"center\"><a href=\"painel.php?pagina1=area_restrita/d_altfranqueado.php&id=$id\" onMouseOver=\"window.status='Alterar franqueado'; return true\"><IMG SRC=\"../img/alt.gif\" width=\"16\" height=\"16\" border=\"0\"></a></td>
			  <td align=\"center\"><a href=\"javaScript:if (confirm ('Tem certeza que deseja excluir o registro?')) window.open('area_restrita/d_excfranqueado.php?id=$id','_self');\" onMouseOver=\"window.status='exc_cl'; return true\"><IMG SRC=\"../img/exc.gif\" width=\"14\" height=\"16\" border=\"0\"></a></td>";
			  
	  	if ($sitfrq == 0) {
				echo "<td><IMG SRC=\"../img/si.gif\"></td>";
			} else {
				echo "<td><IMG SRC=\"../img/no.gif\"></td>";}      	
		echo "</tr>";
		
		}
		$a = $a - 1;
		echo "<tr class=\"subtitulodireita\">
				<td></td>
				<td colspan=\"3\">Número de total de franqueados habilitados</td>
				<td>$a</td>
				<td></td>
			</tr>
			<tr>
				<td colspan=\"6\" height=\"1\" bgcolor=\"#666666\"></td>
			</tr>
			<tr>
				<td></td>
				<td colspan=\"6\" align=\"right\">
				<input class=\"botao3d\" type=\"submit\" value=\"Excluir registros selecionados\" />
				</form>
				</td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<?php
session_start();
$name = $_SESSION["ss_name"];
$tipo = $_SESSION["ss_tipo"];
/*
if (($name=="") && ($tipo!="a") && ($tipo!="d")){
	session_unregister($_SESSION['name']);
	session_destroy();
	echo "<meta http-equiv=\"refresh\" content=\"0; url= http://www.webcontrolempresas.com.br/franquias/erro/index.php\";>";
	die;
}
*/
$comando = "SELECT 
				id, usuario, razaosoc, cidade, uf, fone1, cel01socio, nom01socio, gerente, 
				date_format(data_abertura,'%d/%m/%Y') AS data_abertura, 
				date_format(data_apoio,'%d/%m/%Y') AS data_apoio, 
				date_format(dt_cad,'%d/%m/%Y') AS dt_cad 
			FROM 
				franquia WHERE id_franquia_master = '{$_REQUEST['franqueado']}'
			ORDER BY razaosoc";
$res = mysql_query ($comando, $con);
$linhas = mysql_num_rows ($res);
$linhas1 = $linhas+3;
if ($linhas == "0")
	{
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"20\" class='titulo'>
			<td align=\"center\" width=\"100%\"><b>Nenhuma franquia cadastrada !</b></td></tr></table>";
	}
	else
	{
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bodyText\">
	 		<tr>
				<td colspan=\"5\" height=\"1\" bgcolor=\"#999999\"></td>
			</tr>
			<tr height=\"20\" class=\"titulo\">
				<td align=\"center\">Cod</td>
				<td align=\"center\">Nome da Franquia</td>
				<td align=\"center\">Cidade</td>				
				<td align=\"center\">UF</td>
				<td align=\"center\">Dt Cadastro</td>
			</tr>";
	  for ($a=1; $a<=$linhas; $a++)
	  	{
	  	$matriz = mysql_fetch_array($res);
	  	$id = $matriz['id'];
		$usuario = $matriz['usuario'];
	  	$razao = $matriz['razaosoc'];
	  	$cidade = $matriz['cidade'];
		$uf = $matriz['uf'];
		$fone1 = $matriz['fone1'];
		$dt_cad = $matriz['dt_cad'];
		$gerente = $matriz['gerente'];
		$data_abertura = $matriz['data_abertura'];
		$data_apoio = $matriz['data_apoio'];
		$cel01socio = $matriz['cel01socio'];
		$nom01socio = $matriz['nom01socio'];
		$string = $razao;
		$limite = 55;
		$sizeName = strlen($string);
		//
		$string0 = $nom01socio;
		$limite0 = 25;
		$sizeName0 = strlen($string0);
	  	echo "<tr height=\"22\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		echo "<td align=\"center\">$id</td>
	  	      	<td align=\"left\">&nbsp;<a href=\"painel.php?pagina1=franqueado_jr/alt_franqueado_jr.php&id=$id\" onMouseOver=\"window.status='Alterar franqueado'; return true\"><font color=\"#0000ff\">";
			  for($num=0;$num<$limite;$num++) {
    				print($string[$num]);
					}
					if($sizeName>$limite){echo"...";}
		echo "</font></a></td>
				<td align=\"center\">$cidade</td>				
				<td align=\"center\">$uf</td>
				<td align=\"center\">$dt_cad</td>
				<td align=\"center\">";
		echo "	</tr>";
		}
		$a = $a - 1;
		echo "<tr class=\"subtitulodireita\">
				<td></td>
				<td colspan=\"3\">Total de Franqueados Junior habilitados</td>
				<td><strong>$a&nbsp;</strong></td>
				<td></td>
			</tr>
		</table>";
	}
$res = mysql_close ($con);
?>
<form name="form" action="#" method="post">
</form>
<script language="javascript">
function retorna(){
 	frm = document.form;
    frm.action = 'painel.php?pagina1=franqueado_jr/rel_franqueado_jr_tela.php';
	frm.submit();
 }
</script> 
<br>
<?php if($tipo == "a"){?>
<center>
	<input name="button" type="button" class="botao3d" onClick="retorna();" value="Voltar" style="cursor:pointer"//>
</center>
<?php } ?>
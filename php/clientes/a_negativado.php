<?php
require "connect/sessao_r.php";
$nome2 = $_SESSION['ss_restrito'];

if (isset($nome2)||($tipo == "a")) {  

$go 	= $_POST['go'];
$codigo = $_POST['codigo'];
$negativado = $_POST['negativado'];

if (empty($go)) {
?>
<script language="javascript">
//fun��o para aceitar somente numeros em determinados campos
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function soNumeros(v){
    return v.replace(/\D/g,"")
}
</script>

<br><br><br>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >
<table width=70% border="0" align="center">
  <tr class="titulo">
    <td colspan="2">RETIRAR O CLIENTE DA COBRAN&Ccedil;A</td>
  </tr>
  <tr>
    <td width="173" class="subtitulodireita">&nbsp;</td>
    <td width="224" class="campoesquerda">&nbsp;</td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo do cliente </td>
    <td class="campoesquerda">
		<input name="codigo" size="6" maxlength="6" onKeyPress="mascara(this,soNumeros)" />
      	<input type="hidden" name="go" value="ingressar" />
	</td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="campoesquerda"><?php echo $nome_franquia; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">
		<input name="enviar" type="submit" id="enviar" value="         Verificar" />
	</td>
  </tr>
</table>
</form>
<?php


} // if go=null

if ($go=='ingressar') {
	if (($tipo == "a") || ($tipo == "c")) {
	$resulta = mysql_query("select mid(a.logon,1,5) as logon, b.id_franquia, b.codloja, b.razaosoc from logon a
							inner join cadastro b on a.codloja=b.codloja
							where mid(logon,1,5)='$codigo'", $con);
	} else {
	$resulta = mysql_query("select mid(a.logon,1,5) as logon, b.id_franquia, b.codloja, b.razaosoc from logon a
							inner join cadastro b on a.codloja=b.codloja
							where mid(logon,1,5)='$codigo' and id_franquia='$id_franquia'", $con);
	}
	$linha = mysql_num_rows($resulta);
	if ($linha == 0) {
		print "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\");</script>";
	} else {
		$matriz = mysql_fetch_array($resulta); 
		$codloja = $matriz['codloja'];
		$logon = $matriz['logon'];
		$razaosoc = $matriz['razaosoc'];
	}
	$ssql="select date_format(a.data_documento,'%d/%m/%Y') as documento, a.tipo_documento, b.motivo, 
		date_format(a.ultima_fatura,'%d/%m/%Y') as ultima, c.sit_cobranca from pedidos_cancelamento a 
		inner join motivo_cancel b on a.id_mot_cancelamento=b.id
		inner join cadastro c on a.codloja=c.codloja
		where a.codloja='$codloja'";
	$rs = mysql_query($ssql, $con);
	$line = mysql_num_rows ($rs);
	
	if ($line != 0) {
		while ($fila = mysql_fetch_object($rs)) {
			echo "<form method=\"post\" action=\"painel.php?pagina1=clientes/a_negativado.php\" >
			<table align=\"center\" width=\"70%\">
				<tr>
					<td colspan=\"2\" class=\"titulo\">RETIRAR O CLIENTE DA COBRAN&Ccedil;A</td>
				</tr>
				<tr>
					<td class=\"subtitulodireita\" width=50%>ID</td>
					<td class=\"campoesquerda\">
						$codloja
						<input type=\"hidden\" name=\"codigo\" value=\"$codloja\" >
					</td>
				</tr>
				<tr>
					<td class=\"subtitulodireita\">Codigo</td>
					<td class=\"campoesquerda\">$logon</td>
				</tr>
				<tr>
					<td class=\"subtitulodireita\">Razao Social</td>
					<td class=\"campoesquerda\">$razaosoc</td>
				</tr>
				<tr>
					<td class=\"subtitulodireita\" width=50%>Data do Documento</td>
					<td class=\"campoesquerda\">$fila->documento</td>
				</tr>
				<tr>
					<td class=\"subtitulodireita\">Doc. de Cancelamento</td>
					<td class=\"campoesquerda\">$fila->tipo_documento</td>
				</tr>
				<tr>
					<td class=\"subtitulodireita\">Motivo do Cancelamento</td>
					<td class=\"campoesquerda\">$fila->motivo</td>
				</tr>
				<tr>
					<td class=\"subtitulodireita\">&Uacute;ltima Fatura</td>
					<td class=\"campoesquerda\">$fila->ultima</td>
				</tr>
				<tr>
					<td class=\"subtitulodireita\"><label for=\"negativado\">Negativar Cliente? (n&atilde;o sair na listagem de cobran&ccedil;a)</label></td>
					<td class=\"subtitulopequeno\">
						<input type=\"checkbox\" name=\"negativado\" id=\"negativado\" ";
				if (($fila->sit_cobranca)>0) echo "checked";
				echo " />
						<input type=\"hidden\" name=\"go\" value=\"alterar\" />
					</td>
				</tr>
				<tr>
					<td colspan=\"2\" class=\"titulo\">&nbsp;</td>
				</tr>
				<tr>
					<td align=right><input name=\"enviar\" type=\"submit\" value=\"    Enviar   \" /></td>
					<td><input type=\"button\" onClick=\"javascript: history.back();\" value=\"    Voltar   \" /></td>
				</tr>
			</table>
			</form>";
		}
	} else {
		print "<script>alert(\"Cliente nao tem carta de cancelamento!\");history.back()</script>";
	}
} //fim go-ingressar

if ($go=='alterar') {
	if ($negativado == true) $valor = 1;
	else $valor = 0;
	$sql_cob = "update cs2.cadastro set sit_cobranca='$valor' where codloja='$codigo'"; 	
	$sql = mysql_query($sql_cob, $con);
	print "<script>alert(\"Cliente atualizado com sucesso!\");</script>";
	$res = mysql_close ($con);
	echo "<meta http-equiv=\"refresh\" content=\"0; url=painel.php?pagina1=clientes/a_negativado.php\";>";
}

} else
exit;
?>
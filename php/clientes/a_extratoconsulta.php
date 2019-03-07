<?php
require "connect/sessao.php";

$go 	= $_POST['go'];
$codigo = $_POST['codigo'];
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
		$razaosoc = $matriz['razaosoc'];
		$id_franquia = $matriz['id_franquia'];
		$logon = $matriz['logon'];
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=clientes/a_extratoselect.php&codloja=$codloja&razaosoc=$razaosoc&logon=$logon\";>";

	}
}
?>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >
    <table width=90% border="0" align="center">
        <tr class="titulo">
            <td colspan="2">EXTRATO DE CONSULTAS POR CLIENTE </td>
        </tr>
        <tr>
            <td class="subtitulodireita" width="40%">&nbsp;</td>
            <td class="subtitulopequeno" width="60%">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulodireita">C&oacute;digo do cliente </td>
            <td class="subtitulopequeno">
                <input name="codigo" size="6" maxlength="6" onKeyPress="soNumero();" />
                <input type="hidden" name="go" value="ingressar" />
                <input type="submit" name="enviar" id="enviar" value="Validar" />
            </td>
        </tr>
        <tr>
            <td class="subtitulodireita">
                Por Per&iacute;odo
                <input type="radio" name="relatorio" value="periodo" disabled />
                <br>
                Por Faturamento
                <input type="radio" name="relatorio" value="faturamento" disabled /></td>
            <td class="subtitulopequeno">&nbsp;</td>
        </tr>
        <tr>
            <td class="subtitulodireita">&nbsp;</td>
            <td class="subtitulopequeno">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="titulo">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center"><input type="button" disabled value="      Enviar" />
                <input name="button" type="button" onClick="javascript: history.back();" value="Voltar       " /></td>
        </tr>
    </table>
</form>
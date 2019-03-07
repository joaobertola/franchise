<?php
require "connect/sessao.php";

$go 	    = $_REQUEST['go'];
$codigo    = $_REQUEST['codigo'];
$id_franquia = $_SESSION['id'];

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
	
	window.onload = function() {
		document.form.codigo.focus(); 
	}
	</script>
	<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" name="form">
	<table width=70% align="center">
	  <tr>
		<td colspan="2" align="center" class="titulo">EXTRATO DE CONTA CORRENTE RECUPERE / CREDI&Aacute;RIO</td>
	  </tr>
	  <tr>
		<td width="173" class="subtitulodireita">&nbsp;</td>
		<td width="224" class="campoesquerda">&nbsp;</td>
	  </tr>
	  <tr>
		<td class="subtitulodireita">C&oacute;digo do cliente</td>
		<td class="campoesquerda">
		   <input type="text" name="codigo" id="codigo" size="10" maxlength="6" onKeyPress="mascara(this,soNumeros)" />
		   <input type="hidden" name="go" value="ingressar" />
		</td>
	  </tr>
	
	  <tr>
		<td colspan="2" class="titulo">&nbsp;</td>
	  </tr>
	  <tr align="right">
		<td colspan="2"><input type="submit" value=" Enviar Consulta" name="enviar" /></td>
	  </tr>
	</table>
	</form>
	<?php
} // if go=null

if ($go=='ingressar') {
	if ( ($tipo == "a") || ($tipo == "c") ) {
	$resulta = mysql_query("SELECT mid(a.logon,1,5) as logon, b.id_franquia, b.codloja, b.razaosoc 
							FROM logon a
							INNER JOIN cadastro b ON a.codloja=b.codloja
							WHERE mid(logon,1,5)='$codigo'", $con);
	} else {
	$resulta = mysql_query("SELECT MID(a.logon,1,5) AS logon, b.id_franquia, b.codloja, b.razaosoc 
							FROM logon a
							INNER JOIN cadastro b ON a.codloja=b.codloja
							WHERE MID(logon,1,5)='$codigo' AND id_franquia='$id_franquia'", $con);
	}
	$linha = mysql_num_rows($resulta);
	if ($linha == 0)
	{
		print"<script>alert(\"Cliente n�o existe ou n�o pertence � sua franqu�a!\");history.back();</script>";
	}
	else 
	{
		$matriz = mysql_fetch_array($resulta); 
		$codloja = $matriz['codloja'];
		$logon = $matriz['logon'];
		$razaosoc = $matriz['razaosoc'];
		echo "<meta http-equiv=\"refresh\" content=\"0; url= painel.php?pagina1=area_restrita/d_extrato_crediario2.php&codloja=$codloja&logon=$logon&razaosoc=$razaosoc\";>";
	}
}
?>
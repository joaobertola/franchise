<?php
require "connect/sessao.php";

$go 	= $_POST['go'];
if (empty($go)) $go = $_GET['go'];
$id_correio = $_GET['id_correio'];
$codigo = $_POST['codigo'];
$tpregistro = $_POST['tpregistro'];
$franqueado = $_POST['franqueado'];

if ($go == "") {
?>
<script language="javascript">
function mostraRegistro(form, tipo) {
	if (tipo == 1) {
		form.codigo.disabled = false;
		form.codigo.style.backgroundColor = "#FFFFFF";
		form.codigo.focus();
	}
	if (tipo == 2) {
		form.codigo.disabled = true;
		form.codigo.value = "";
		form.codigo.style.backgroundColor = "#CCCCCC";
	}
}

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
<br>
<form name="form1" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
<table border="0" align="center" width="70%">
  <tr class="titulo">
    <td colspan="2">REGISTROS RECUSADOS </td>
  </tr>
  <tr>
    <td class="subtitulodireita" width="173">&nbsp;</td>
    <td class="subtitulopequeno" width="224">&nbsp;</td>
    </tr>
  
  <tr>
    <td class="subtitulodireita">
    	<input type="radio" name="tpregistro" value="1" onFocus="mostraRegistro(document.form1, 1);" checked />
        C&oacute;digo do cliente
    </td>
    <td class="campoesquerda"><input name="codigo" id="codigo" size="12" maxlength="6" class="boxnormal" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" onKeyPress="mascara(this,soNumeros)" /></td>
    </tr>
  <tr>
    <td class="subtitulodireita">
    	<input type="radio" name="tpregistro" value="2" onFocus="mostraRegistro(document.form1, 2);" />
      	Todos os clientes    </td>
    <td class="subtitulopequeno">&nbsp;</td>
    </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">
    	<?php
    	if (($tipo == "a") || ($tipo == "c")) {  
			echo "<select name=\"franqueado\">";
			$sql = "select * from franquia order by id";
			$resposta = mysql_query($sql, $con);
			while ($array = mysql_fetch_array($resposta))
				{
				$franquia   = $array["id"];
				$nome_franquia = $array["fantasia"];
				echo "<option value=\"$franquia\">$nome_franquia</option>\n";
				}
			echo "</select>";
		}
		else {
			echo $nome_franquia;
			echo "<input name=\"franqueado\" type=\"hidden\" id=\"franqueado\" value= $id_franquia; />";
			}
		?>
        <input type="hidden" name="go" value="ingressar" />
    </td>
  </tr>
  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="Envia" name="  Consultar  " onclick="return check(this.form);"/>
      <input name="button" type="button" onClick="javascript: history.back();" value="    Voltar   " />
    </td>
  </tr>
</table>
</form>
<?php } // fim if go=null

if ($go=='ingressar') {
	if ($tpregistro == 1) {
		if (($tipo == "a") || ($tipo == "c")) $frq = "";
		else $frq = "and id_franquia='$franqueado'";
		$sql = "select mid(a.logon,1,5) as logon, b.id_franquia, b.codloja, b.razaosoc from logon a
				inner join cadastro b on a.codloja=b.codloja
				where mid(logon,1,5)='$codigo' $frq";
		$ql = mysql_query($sql,$con);
		$linha = mysql_num_rows($ql);
		if ($linha == 0) 
			print "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\");</script>";
		else {
			$matriz = mysql_fetch_array($ql); 
			$codloja = $matriz['codloja'];
			$criterio = "and a.codloja='$codloja'";
		}
	}
	if ($tpregistro == 2) $criterio = "";
	
	$sql = "Select a.id, mid(c.logon,1,5) as logon, b.razaosoc, date_format(a.data_msg,'%d/%m/%Y') as data_msg, a.titulo, a.data_leitura from cs2.correio_cliente a
			inner join cs2.cadastro b on a.codloja=b.codloja
			inner join cs2.logon c on a.codloja=c.codloja
			where b.id_franquia='$franqueado' and titulo<>'COMUNICADO INTERNO' $criterio
			order by razaosoc, data_msg";
	$qr=mysql_query($sql,$con) or die ("\n erro no segundo\n".mysql_error()."\n\n");
	$nreg = mysql_num_rows($qr);
	if($nreg==0) {
		echo "<table align=\"center\">
		<tr>
			<td class=\"subtitulopequeno\">&nbsp;</td>
		</tr>
		<tr>
			<td class=\"titulo\" width=\"70%\">N&atilde;o tem nenhuma mensagem para este cliente ou franquia</td>
		</tr>
		<tr>
			<td class=\"subtitulopequeno\">&nbsp;</td>
		</tr>
		<tr>
			<td align=center><input name=\"button\" type=\"button\" onClick=\"javascript: history.back();\" value=\"    Voltar   \" /></td>
		</tr>
		</table>";
	} else {
		echo "
<table align=\"center\" class=\"bodyText\" width=\"80%\">
	<tr>
    	<td colspan=\"3\" class=\"titulo\">Relat&oacute;rio de Erros na Inclus&atilde;o de Negativos na Equifax</td>
    </tr>
	<tr>
	  <td class=\"subtitulocentro\">Lido</td>
      <td class=\"subtitulocentro\">Data da mensagem</td>
      <td class=\"subtitulocentro\">Assunto</td>
  </tr>";
  	  for ($a=1; $a<=$nreg; $a++) {
	  	$matriz = mysql_fetch_array($qr);
		$id = $matriz['id'];
		$logon = $matriz['logon'];
		$razaosoc = $matriz['razaosoc'];
		$data_msg = $matriz['data_msg'];
		$titulo = $matriz['titulo'];
		$data_leitura = $matriz['data_leitura'];
		if ($razao <> $razaosoc) {
			echo "<tr>
				<td colspan=3 align=center class=\"PAACDestaque\"><b>$logon - $razaosoc</b></td>
			  </tr>";
			$razao = $razaosoc;
			$totcl = $totcl + 1;
		}
		
		echo "<tr height=\"20\"";
		if (($a%2) == 0) {
			echo "bgcolor=\"#E5E5E5\">";
		} else {
			echo ">";
		}
		echo "	<td align=\"center\">";
		if (empty($data_leitura)) echo "<a href=\"painel.php?pagina1=clientes/a_recusado.php&go=individual&id_correio=$id\"><img src=\"../img/mensagemnaolida.png\" border=\"0\"   style=\"width: auto; height: 15px;\"></a></td>";
		else echo "<img src=\"../img/mensagemlida.png\"   style=\"width: auto; height: 15px;\"></td>";
		echo "</td>
				<td align=\"center\">$data_msg</td>
				<td><a href=\"painel.php?pagina1=clientes/a_recusado.php&go=individual&id_correio=$id\">$titulo</a></td>
			</tr>";
  	 } //fim for
  		echo "
		<tr>
			<td colspan=\"3\" class=\"subtitulocentro\">Total de clientes com erros: $totcl</td>
		</tr>
</table>";

	}
} // fim if go=ingressar 

if ($go=='individual') {
	$sql = "select a.codloja, date_format(a.data_msg,'%d/%m/%Y') as data_msg, a.titulo, a.mensagem, b.razaosoc, b.nomefantasia, b.insc, mid(c.logon,1,5) as logon from correio_cliente a
			inner join cadastro b on a.codloja=b.codloja
			inner join logon c on a.codloja=c.codloja
			where a.id='$id_correio'";
	$ql = mysql_query($sql,$con);
	$matriz = mysql_fetch_array($ql);
	$codloja = $matriz['codloja'];
	$data_msg =  $matriz['data_msg'];
	$titulo = $matriz['titulo'];
	$mensagem = nl2br($matriz['mensagem']);
	$razaosoc = $matriz['razaosoc'];
	$nomefantasia = $matriz['nomefantasia'];
	$logon = $matriz['logon'];
	$insc = $matriz['insc'];
	echo "
		<table align=\"center\" width=\"70%\">
		  <tr>
			<td colspan=\"2\" class=\"titulo\">Detalhes da Restri&ccedil;&atilde;o Comercial</td>
		  </tr>
		  <tr>
			<td colspan=\"2\" class=\"PAACDestaque\">Dados do Cliente</td>
		  </tr>
		  <tr>
			<td class=\"subtitulodireita\">C&oacute;digo</td>
			<td class=\"subtitulopequeno\">$codloja</td>
		  </tr>
		  <tr>
			<td class=\"subtitulodireita\">Raz&atilde;o Social</td>
			<td class=\"subtitulopequeno\">$razaosoc</td>
		  </tr>
		  <tr>
			<td class=\"subtitulodireita\">Nome de Fantasia</td>
			<td class=\"subtitulopequeno\">$nomefantasia</td>
		  </tr>
		  <tr>
			<td class=\"subtitulodireita\">CNPJ</td>
			<td class=\"subtitulopequeno\">$insc</td>
		  </tr>
		  <tr>
			<td colspan=\"2\" class=\"PAACDestaque\">Dados Referentes &agrave; Restri&ccedil;&atilde;o</td>
		  </tr>
		  <tr>
			<td class=\"subtitulodireita\">Data da Ocorr&ecirc;ncia</td>
			<td class=\"subtitulopequeno\">$data_msg</td>
		  </tr>
		  <tr>
			<td class=\"subtitulodireita\">Ocorr&ecirc;ncia</td>
			<td class=\"subtitulopequeno\">$titulo</td>
		  </tr>
		  <tr>
			<td class=\"subtitulodireita\">Mensagem</td>
			<td class=\"subtitulopequeno\">$mensagem</td>
		  </tr>
		  <tr>
			<td colspan=\"2\" class=\"titulo\">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan=2 align=center><input name=\"imprimir\" type=\"button\" onClick='JavaScript:self.print()' value=\" Imprimir \" /><input name=\"button\" type=\"button\" onClick=\"javascript: history.back();\" value=\"    Voltar   \" /></td>
		  </tr>
		</table>";
}
?>
<?php
require "connect/sessao.php";

$go 	= $_POST['go'];
$codigo = $_POST['codigo'];

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
<table width="70%" border="0" align="center">
  <tr class="titulo">
    <td colspan="2">TABELAS DE PRE&Ccedil;OS DE CUSTO</td>
  </tr>
  <tr>
    <td class="subtitulodireita" width="173">&nbsp;</td>
    <td class="subtitulopequeno" width="224">&nbsp;</td>
    </tr>
  
  <tr>
    <td class="subtitulodireita">C&oacute;digo do cliente </td>
    <td class="campoesquerda"><input name="codigo" size="12" maxlength="6" class="boxnormal" onKeyPress="mascara(this,soNumeros)" onFocus="this.className='boxover'" onBlur="this.className='boxnormal'" /></td>
    </tr>

  <tr>
    <td class="subtitulodireita">&nbsp;</td>
    <td class="subtitulopequeno">
		<?php echo $nome_franquia; ?>
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
    <td colspan="2" align="right">
      <input type="submit" name="consultar" id="consultar" value="Consultar" /></td>
  </tr>
</table>
</form>
<?php
} // if go=null

if ($go=='ingressar') {

if (($tipo == "a") || ($tipo == "c")) {
$resulta = mysql_query("select a.logon, b.id_franquia from logon a
						inner join cadastro b on a.codloja=b.codloja
						where mid(logon,1,5)='$codigo'", $con);
} else {
$resulta = mysql_query("select a.logon, b.id_franquia from logon a
						inner join cadastro b on a.codloja=b.codloja
						where mid(logon,1,5)='$codigo' and id_franquia='$id_franquia'", $con);
}
$linha = mysql_num_rows($resulta);
if ($linha == 0)
{
	echo "<script>alert(\"Cliente nao existe ou nao pertence a sua franquia!\"); javascript: history.back();</script>";
} else {
$comando = "select a.codloja, a.razaosoc, a.nomefantasia, date_format(a.dt_cad, '%d/%m/%Y') as data, a.sitcli,
			d.descsit, a.tx_mens from cadastro a
			inner join situacao d on a.sitcli=d.codsit
			inner join logon e on a.codloja=e.codloja
			where mid(logon,1,5)='$codigo'";
$res = mysql_query ($comando, $con);
$matriz = mysql_fetch_array($res);
$codloja = $matriz['codloja'];

$command = "select a.codcons, b.nome, a.valorcons, b.vr_custo, c.qtd from valconscli a 
			inner join valcons b on a.codcons=b.codcons
			left join bonificadas c on a.codloja = c.codloja  and a.codcons = c.tpcons
			where a.codloja=$codloja;";

$result = mysql_query ($command, $con);
$linhas = mysql_num_rows ($result);

?>
<script language="JavaScript1.2">
function DoPrinting(){
	if (!window.print){
		alert("Use o Netscape  ou Internet Explorer \n nas versões 4.0 ou superior!")
		return
	}
	window.print()
}
</script>

<table border="0" align="center" width="643">
  <tr>
    <td colspan="2" class="titulo" align="center">TABELA DE PRE&Ccedil;OS DE CUSTO FORNECEDOR</td>
  </tr>
  <tr>
    <td class="subtitulodireita">ID</td>
    <td class="subtitulopequeno"><?php echo $matriz['codloja']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">C&oacute;digo de Cliente </td>
    <td class="campojustificado"><?php echo $codigo; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Raz&atilde;o Social</td>
    <td class="subtitulopequeno"><?php echo $matriz['razaosoc']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Cliente desde</td>
    <td class="subtitulopequeno"><?php echo $matriz['data']; ?></td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Mensalidade</td>
    <td valign="top" class="subtitulopequeno">R$&nbsp;<?php echo $matriz['tx_mens']; ?></td>
  </tr>
  <tr>
    <td class="subtitulodireita">Tabela de Pre&ccedil;os</td>
    <td>
		<table width="100%" border="0">
			<tr height="20">
				<td align="center" class="campoesquerda">C&oacute;digo</td>
				<td align="center" class="campoesquerda">Produto</td>
				<td align="center" class="campoesquerda">Valor de Custo</td>
				
			</tr>
		<?php
		
			for ($a=1; $a<=$linhas; $a++) {
				$matrix = mysql_fetch_array($result);
				$codigo = $matrix['codcons'];
				$produto = $matrix['nome'];
				$venda = $matrix['valorcons'];
				$custo = $matrix['vr_custo'];
				$gratuidade = $matrix['qtd'];
				echo "<tr height=\"22\">
						<td align=\"center\" class=\"subtitulopequeno\">$codigo</td>
						<td align=\"left\" class=\"subtitulopequeno\">$produto</td>
						<td align=\"right\" class=\"subtitulopequeno\">$custo</td>
						
					</tr>";
				}
			?>			
		</table>	</td>
  </tr>
  
  <tr>
    <td class="subtitulodireita">Status</td>
    <td class="formulario" <?php if ($matriz['sitcli'] == 0) {
								echo "bgcolor=\"#33CC66\"";
								} else {
								echo "bgcolor=\"#FF0000\"";} ?> >
							<font color="#FFFFFF"><?php echo $matriz['descsit']; ?></font></td>
  </tr>
  <tr>
    <td colspan="2" class="titulo">&nbsp; </td>
  </tr>
  <tr class="noprint">
    <td align="right">
			<a href='#' onClick='JavaScript:self.print()'>
			<img src="../../images/print.gif" hspace='5' border='0' alt=' Imprimir '><br>
			<span class="bodyText">Imprimir</span></a>
	</td>
	<td align="center"><input name="button" type="button" onClick="javascript: history.back();" value="       Voltar       " /></td>
</tr>
  <tr align="right" class="noprint">
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<?php
} 

} //fim if go=ingressar
?>
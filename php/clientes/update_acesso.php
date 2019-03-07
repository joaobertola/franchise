<?php
require "../connect/sessao.php";
require "../connect/conexao_conecta.php"; 
$codloja = $_POST['codloja'];
$codigo = $_POST['codigo'];
$acesso  = $_POST['acesso'];

$sql  = "SELECT sitcli FROM cs2.cadastro WHERE codloja=$codloja";
$ql   = mysql_query($sql,$con);
$resp = mysql_fetch_array($ql);
$qtd  = $resp["sitcli"];
if ($qtd > 1 ){
	echo "<script>alert(\"Cliente atualmente CANCELADO. Favor verificar!\");</script>";
	echo "<meta http-equiv=Refresh content=\"0; url= ../painel.php?pagina1=clientes/a_bloq_acesso.php&codigo=$codigo\";>";
	exit;
}

$sql  = "SELECT * FROM cs2.titulos WHERE codloja=$codloja AND datapg is null AND vencimento < NOW()";
$qr   = mysql_query($sql,$con) or die ("\n erro no segundo   $sql  \n".mysql_error()."\n\n");
$nreg = mysql_num_rows($qr);

if ($acesso == 0 and $nreg > 0 ) {
	# acao = desbloquear
	?>
	<link href="../../css/tabela.css" rel="stylesheet" type="text/css" />
	<br />
	<br />
	<table width="50%" align="center" class="bodyText">
		<tr>
			<td rowspan="2"><img src="../../img/triste.gif" alt="boletos em atraso" width="44" height="45" /></td>
			<td class="campoesquerda">Lamentamos, mas este c&oacute;digo n&atilde;o pode ser liberado.<br />
Fa&ccedil;a o cliente pagar as faturas em atraso abaixo</td>
			</tr>
		<tr>
		<?php
		$sql="Select vencimento, valor, numdoc from cs2.titulos where codloja=$codloja and datapg is null order by vencimento asc";
		$qr=mysql_query($sql,$con) or die ("\n erro no segundo\n".mysql_error()."\n\n");
		$nreg = mysql_num_rows($qr);
		if($nreg==0) {
			echo "<td class=\"campojustificado\" style=\"padding-left:5px\">
					<b>Este cliente n&atilde;o registra boletos em aberto para este periodo</b>
					</td>";
		} else {
			echo "<td class='campoesquerda' style='padding-left:5px'>
	    		    <b>Boletos em Aberto:</b><br>";
			
			for($i=0;$i<$nreg;$i++){
				$mes_ano = mysql_result($qr,$i,"vencimento");
				$mes_ano = substr($mes_ano,5,2)."/".substr($mes_ano,0,4);
				$boleto  = $mes_ano;
				$numdoc  = mysql_result($qr,$i,"numdoc");
				echo "<a href='https://www.webcontrolempresas.com.br/inform/boleto/boleto.php?&numdoc=$numdoc'>$boleto</a><br>";
				}
				echo "</td>";
		} // fim else
		$mes = date('m');
		$ano = date('Y');
		# Verificando se o cliente tem VOTO DE CONFIANCA
		$sql_voto = "SELECT * FROM cs2.logon_desbloqueio_confianca
					 WHERE codloja = $codloja AND MONTH(data)=$mes AND YEAR(data)=$ano";
		$qry = mysql_query($sql_voto,$con);
		$nreg = mysql_num_rows($qry);
		if($nreg==0) {
			?>
			<tr>
				<td colspan='3' align='center'>
					<br>
					<input name="button" type="button" onclick="location.href='a_desbloqueio_confianca.php?codigo=<?=$codigo?>&codloja=<?=$codloja?>'" value=" Desbloqueio Inadimplencia (1 dia) " />
				</td>
			</tr>
		<?php
		}			 
		?>
		</tr>
		<tr align="center">
			<td colspan="2"><br /><input type="button" onClick="javascript: history.back();" value="     Voltar     " /></td>
		</tr>
	</table>
	<?php
	exit;
}else{
	# acao = bloquear
	$logon = "UPDATE logon SET sitlog = '$acesso' WHERE codloja = '$codloja'";
	mysql_query($logon,$con);
}
mysql_close($con);
echo "<meta http-equiv=\"refresh\" content=\"0; url=../painel.php?pagina1=clientes/most_cliente.php&codloja=$codloja \";>"; ?>
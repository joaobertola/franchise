<?php
require ("connect/sessao_r.php");
$nome2 = $_SESSION['ss_restrito'];

	$total = 0;
	$logon      = str_replace(" ","",$_REQUEST['logon']);
	$franqueado = str_replace(" ","",$_REQUEST['franqueado']);
	
	$sql = "SELECT distinct(a.codloja), b.razaosoc, b.banco_cliente, b.agencia_cliente, b.conta_cliente, 
			b.cpfcnpj_doc,
			upper(b.nome_doc) as nome, if (b.banco_cliente = 1, 0, 8) vr_repasse, b.tpconta
			from cs2.contacorrente_recebafacil a
			inner join cs2.cadastro b on a.codloja = b.codloja
			INNER JOIN logon l ON l.codloja = b.codloja
			WHERE 1=1 ";
		
		if($franqueado != "") $sql .= " AND b.id_franquia = '$franqueado' ";
		if($logon != "") $sql .= " AND l.logon LIKE '$logon%' ";			
		$sql .= " ORDER BY b.banco_cliente, b.tpconta, b.agencia_cliente";

	$query = mysql_query($sql, $con) or die('impossivel conectar ao servidor');
	$linhas = mysql_num_rows($query);
	if ($linhas > 0) {
		echo "<table class=\"bodyText\" width=100%>
				<tr class=subtitulodireita>
					<td colspan=2 align=center>Cliente</td>
					<td align=center>Tp Conta</td>
					<td align=center>Bco</td>
					<td align=center>Agencia</td>
					<td align=center>Conta</td>
					<td align=center>Doc Titular</td>
					<td align=center>Nome Titular</td>
					<td align=center>Doc</td>
					<td align=center>Vr Repasse</td>
					<td align=center>Sit</td>
				</tr>";
				
		while ($resposta = mysql_fetch_array($query)) {
			$codigo = $resposta['codloja'];
			$razaosoc = $resposta['razaosoc'];
			$tpconta = $resposta['tpconta'];
			if ($tpconta == 1) $ctacte = "CONTA CORRENTE";
			elseif ($tpconta == 2) $ctacte = "CONTA POUPANÇA";
			else $ctacte = "NAO CADASTRADA";
			$banco = $resposta['banco_cliente'];
			$agencia = $resposta['agencia_cliente'];
			$conta = $resposta['conta_cliente'];
			$doc = $resposta['cpfcnpj_doc'];
			$nome = $resposta['nome'];
			$vr_repasse = $resposta['vr_repasse'];
			$vr_repasse1 = number_format($vr_repasse, 2 , ',', '.');
		
			$sql3 = "select count(*) as inadimplente from cs2.titulos where codloja = '$codigo' and datapg is null and vencimento < now()";
			$qr3 = mysql_query($sql3, $con);
			$matrix = mysql_fetch_array($qr3);
			$sitlog = $matrix['inadimplente'];
			$sql2 = "select a.saldo,CAST(MID(b.logon,1,6) AS UNSIGNED) as logon 
					 from  cs2.contacorrente_recebafacil a
					 inner join logon b on a.codloja=b.codloja
					 where a.codloja='$codigo'
					 order by a.id desc
					 limit 1";
			$qr = mysql_query($sql2, $con);
			while($matriz = mysql_fetch_array($qr)) {
				$saldo = $matriz['saldo'];
				$logon = $matriz['logon'];
				$repasse = $saldo - $vr_repasse;			
				if ($sitlog > 0) {
					$sitlog = "I";
					$c = $c + 1;
					$total_inadimplente = $total_inadimplente + $repasse;
				}
				else $sitlog = "";
				if ($saldo > 0) {
					$b = $b +1;
					$total = $total + $repasse;
					$repasse = number_format($repasse, 2, '.', ',');
					echo "<tr ";
					if (($b%2) == 0) echo "bgcolor=\"#E5E5E5\">";
					else echo ">";
					echo "	<td>$logon</td>
							<td>$razaosoc</td>
							<td>$ctacte</td>
							<td>$banco</td>
							<td>$agencia</td>
							<td>$conta</td>
							<td>$doc</td>
							<td>$nome</td>
							<td>$vr_repasse1</td>
							<td align=right>$repasse</td>
							<td align=center><b>$sitlog</b></td>
						  </tr>";
				}
			}
		}
				
		echo "<tr class=subtitulodireita>
				<td colspan=7>&nbsp;</td>
				<td colspan=2 align=right>Total de <b>$b</b> Clientes</td>
				<td align=right>".number_format($total, 2 , ',', '.')."</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr class=subtitulodireita>
				<td colspan=7>&nbsp;</td>
				<td colspan=2 align=right>Total de <b>$c</b> Inadimplentes</td>
				<td align=right>".number_format($total_inadimplente, 2 , ',', '.')."</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr class=subtitulodireita>
				<td colspan=7>&nbsp;</td>
				<td colspan=2 align=right>Total do Repasse</td>
				<td align=right><b>".number_format(($total-$total_inadimplente), 2 , ',', 

'.')."</b></td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td align=center colspan=11><input name=\"button\" type=\"button\" 

onClick=\"javascript: history.back();\" value=\"  Voltar  \" style=\"cursor:pointer\"/></td>
		</table>";
	}else{
		echo "<table width=50% align=center>
				<tr class=subtitulodireita>
					<td align=center>Esta franquia nao tem Repasses Pendentes</td>
				</tr>
				<tr>
					<td align=center><input name=\"button\" type=\"button\" 

onClick=\"javascript: history.back();\" value=\"  Voltar  \" style=\"cursor:pointer\"/></td>
			</table>";
	}
//}
?>
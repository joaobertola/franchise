<?php
echo"<table align='center' width='800' border='0' cellpadding='0' cellspacing='1' class='bodyText'>
	 		<tr>
				<td colspan='5' class='titulo'>Clientes Virtual Flex</td>
			</tr>
	 		<tr>
				<td colspan='5' class='titulo'>Franquia : $id_franquia - $nome_franquia </td>
			</tr>
	 		<tr>
				<td rowspan='$linhas1' width='14%' bgcolor='#999999'></td>
			</tr>
			<tr height='20' bgcolor='FF9966'>
				<td align='center'  width='10%'>C&oacute;digo</td>
				<td align='center'  width='30%'>Fantasia</td>
				<td align='center'  width='30%'>Site</td>
				<td align='center'  width='15%'>Dt. Cria&ccedil;&atilde;o</td>
				<td align='center'  width='15%'>Status</td>
			</tr>
			<tr>
				<td colspan='5' height='1' bgcolor='#666666'>
				</td>
			</tr>";
// Selecionando todos od cliente pertencentes a Franquia

$sql_cliente = "SELECT nomefantasia, CAST(MID(logon,1,6) AS UNSIGNED) as logon, a.sitcli, a.codloja 
				FROM cs2.cadastro a
				INNER JOIN cs2.logon b ON a.codloja = b.codloja
				WHERE a.id_franquia = $id_franquia and a.sitcli <> 2
				GROUP BY a.codloja";
$qry_cliente = mysql_query($sql_cliente, $con);
if ( mysql_num_rows($qry_cliente) > 0){
	while ( $reg = mysql_fetch_array($qry_cliente) ){
		$nomefantasia = $reg['nomefantasia'];
		$logon    = $reg['logon'];
		$sitcli = $reg['sitcli'];
		$codloja = $reg['codloja'];
		
		// Localizando o site do cliente
		require "connect/conexao_conecta_virtual.php";
		
		$sql_virtual = "Select fra_nomesite , fra_dominio, date_format(fra_data_hora,'%d/%m/%Y') as data 
						FROM dbsites.tbl_framecliente
						WHERE fra_codloja = $codloja";
		$qry_virtual = mysql_query($sql_virtual,$con_virtual) or die ('Erro: '.$sql_virtual);
		$fra_nomesite = mysql_result($qry_virtual,0,'fra_nomesite');
		$fra_dominio = mysql_result($qry_virtual,0,'fra_dominio');
		$data = mysql_result($qry_virtual,0,'data');
	
		$site = '';	
		if ( !empty($fra_nomesite) )
			$site = "www.$fra_nomesite.$fra_dominio";
		mysql_close($con_virtual);
		
		if ( $sitcli == 0 ) $sitcli = 'ATIVO';
		elseif ( $sitcli == 1 ) $sitcli = '<font color="#FF0000">BLOQUEADO</font>';
		elseif ( $sitcli == 3 ) $sitcli = '<font color="#FF00FF"><b>BLOQUEADO VIRTUAL FLEX</b></font>';
		
		echo "<tr height='22' bgcolor='#E5E5E5'>
				<td align='center'>$logon</td>
				<td align='center'>$nomefantasia</td>
				<td align='center'><u><a href='http://$site' target='_blank'><font color='#0000FF'><b>$site</b></font></a></td>				
	            <td align='center'>$data</td>
				<td align='center'>$sitcli</td>
			  </tr>";
				
	}
}

echo "</table>";
?>
<?php

require "connect/sessao.php";
require "connect/conexao_conecta.php";
require "connect/funcoes.php";

$datai     = $_REQUEST['datai'];
$dataii    = $_REQUEST['datai'];
$dataf     = $_REQUEST['dataf'];
$dataff    = $_REQUEST['dataf'];

$datai = data_mysql($datai);

if ( ! $dataf )
	$dataf = date('Y-m-d');
else
	$dataf = data_mysql($dataf);


$sql0 = "SELECT count(*) as qtd, a.id_cobradora, b.nome, d.fantasia
         FROM cs2.titulos_cobradora a 
         INNER JOIN cs2.funcionario b ON a.id_cobradora = b.id 
         INNER JOIN cs2.titulos c ON c.numdoc = a.numdoc
         INNER JOIN cs2.franquia d ON d.id = b.id_franqueado
         WHERE c.datapg BETWEEN '$datai' AND '$dataf'
         GROUP BY id_cobradora
         ORDER BY qtd DESC";
$qry0 = mysql_query($sql0,$con) or die($sql0);
$linha_inicial = "";
while ( $reg = mysql_fetch_array($qry0) ){
	$id_cobradora = $reg['id_cobradora'];
	$qtd          = $reg['qtd'];
	$nome_cob     = $reg['nome'];
	$fantasia     = $reg['fantasia'];
	$linha_inicial .= "<tr><td colspan='2'>$qtd - $nome_cob ( $fantasia )</td></tr>";
	
	$sql = "SELECT  c.codloja, a.numdoc, b.nome, 
                        date_format(c.vencimento,'%d/%m/%Y') as vencimento, d.nomefantasia, 
                        date_format(c.datapg,'%d/%m/%Y') as datapg, c.valorpg, a.id_cobradora,
                        e.fantasia as nome_franquia
                FROM cs2.titulos_cobradora a 
                INNER JOIN cs2.funcionario b ON a.id_cobradora = b.id 
                INNER JOIN cs2.titulos c ON c.numdoc = a.numdoc
                INNER JOIN cs2.cadastro d on c.codloja = d.codloja
                INNER JOIN cs2.franquia e ON d.id_franquia = e.id 
                WHERE c.datapg BETWEEN '$datai' AND '$dataf' ";
	$qry = mysql_query($sql,$con) or die($sql);

	if ( mysql_num_rows($qry) > 0 ){
	
		$corpo .= "<tr>
				<td colspan='2' align='center' class='titulo'>RELAT&Oacute;RIO DE RECEBIMENTO</td>
			</tr>
			<tr>
				<td colspan='2' class='campoesquerda'>&nbsp;</td>
			</tr>";
			
		$corpo .= "<tr>
				<td class='subtitulodireita' width='20%'>Auxiliar de Cobran&ccedil;a:</td>
				<td class='campoesquerda' width='80%'>$nome_cob</td>
			  </tr>";


		$corpo .= "<tr>
				<td colspan='2'>
					<table width='100%'>
						<tr height='20' bgcolor='87b5ff'>
							<td width='10%'>Codigo</td>
							<td width='50%'>Fantasia</td>
							<td width='20%'>Vencimento</td>
							<td width='20%'>Data Pagamento</td>
							<td width='20%'>Franquia</td>
						</tr>
						";
		$qtd = 0;

		$nsql = $sql." AND a.id_cobradora = $id_cobradora  ORDER BY d.nomefantasia ";
		$xqry = mysql_query($nsql,$con) or die($nsql);
		while( $nreg = mysql_fetch_array($xqry) ){
				
			$codloja    = $nreg['codloja'];
			$logon		= ver_logon($codloja);
			$fantasia   = $nreg['nomefantasia'];
			$vencimento = $nreg['vencimento'];
			$datapg     = $nreg['datapg'];
			$nome_franquia = $nreg['nome_franquia'];
				
			$corpo .= "	<tr height='22' bgcolor='#E5E5E5'>
						<td width='10%'>$logon</td>
						<td width='50%'>$fantasia</td>
						<td width='20%'>$vencimento</td>
						<td width='20%'>$datapg</td>
						<td width='20%'>$nome_franquia</td>
					</tr>";
			
			$qtd++;
		}
		$corpo .= "<tr><td colspan='2'>Listados : [ $qtd ] Registros.</td></tr>
			  <tr><td colspan='2'>&nbsp;</td></tr>";
		$corpo .= "</table>
		</td>
		</tr>";
		$total += $qtd; 
	}

}

	echo "<table width='800' align='center'>
			<tr>
				<td colspan='2' align='center' class='titulo'>RANKING DE RECEBIMENTO<br>Per&iacute;odo: $dataii &agrave; $dataff</td>
			</tr>
			$linha_inicial
			<tr>
				<td colspan='2' class='campoesquerda'>&nbsp;</td>
			</tr>

			";
	echo $corpo;
			
?>
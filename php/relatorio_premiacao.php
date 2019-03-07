<script>
function PrintDiv(div)
{
	var divContents = $('#'+div).html();
	var printWindow = window.open('', '', 'height=768,width=1024');
	printWindow.document.write('<html><head>');
	printWindow.document.write('</head><body>');
	printWindow.document.write(divContents);
	printWindow.document.write('</body></html>');
	printWindow.document.close();
	printWindow.print();
}
</script>
<?php

require "connect/sessao.php";
require "connect/funcoes.php";
require "connect/conexao_conecta.php";

$campanha	 = $_REQUEST['campanha'];
$funcionario = $_REQUEST['cobradora'];
$data_i      = data_mysql($_REQUEST['data1']);
$data_f      = data_mysql($_REQUEST['data2']);

if ( $data_i == '--' ) $data_i = '2000-01-01';
if ( $data_f == '--' ) $data_f = date('Y-m-d');

$data_ii = data_mysql_i($data_i);
$data_ff = data_mysql_i($data_f);

if ( $funcionario != '0' )
$sql_func = " AND p.id_func = $funcionario ";

echo "<table width='800' cellpadding='1' cellspacing='1'>
	  <tr>
		<td colspan='2' class='titulo'>Relat&oacute;rio de Premia&ccedil;&otilde;es</td>
	  </tr>
	  <tr>
		<td colspan='2' class='subtitulodireita'>&nbsp;</td>
	  </tr>
	  <tr>
		<td class='campoesquerda' width='350'>Campanha:</td>
		<td class='campoesquerda'>$campanha</td>
	  </tr>
 	  <tr>
		<td class='campoesquerda'>Per&iacute;odo</td>
		<td class='campoesquerda'>$data_ii at&eacute; $data_ff</td>
	  </tr>
	  <tr>
		<td colspan='2'>&nbsp;</td>
	  </tr>
</table>";

$data_pesquisa = " AND p.data between '$data_i' AND '$data_f'";
	
$sql = "SELECT p.id_func, count(*) AS qtd, f.nome
		FROM cs2.premiacao p
			INNER JOIN cs2.funcionario f ON p.id_func = f.id 
		WHERE p.tipo_premio = '$campanha' $sql_func $data_pesquisa
	    GROUP BY p.id_func
		ORDER BY qtd DESC";
$qry = mysql_query($sql,$con);
$total_qtd_reg = 0;
while ( $reg = mysql_fetch_array($qry) ){
	
	$func_nome       = $reg['nome'];
	$qtd_reg         = $reg['qtd'];
	$id_func         = $reg['id_func'];
	$nom_funcionario = str_replace(' ','_',$func_nome);
	
	echo "
	<div id='$nom_funcionario'>
	<table width='800' cellpadding='1' cellspacing='1'>
	<tr>
		<td class='campoesquerda' width='70%'>Funcionario(a): <a href='#' onclick=\"PrintDiv('$nom_funcionario')\">$func_nome<a></td>
		<td class='campoesquerda'>Qtd : $qtd_reg</td>
	  </tr>
	  <tr>
		<td colspan='2' class='subtitulodireita'>&nbsp;</td>
	  </tr>";
	
	$sql_2 = "	SELECT p.codloja, c.nomefantasia, date_format(p.data,'%d/%m/%Y') AS data FROM cs2.premiacao p
				INNER JOIN cs2.cadastro c on p.codloja = c.codloja
				WHERE tipo_premio = '$campanha' AND p.id_func = $id_func $data_pesquisa
				ORDER BY p.data,c.nomefantasia";
	$qry_2 = mysql_query($sql_2,$con);
	$a = 0;
	while ( $reg_2 = mysql_fetch_array($qry_2) ){
		$a++;
		$codloja  = $reg_2['codloja'];
		$nome_cli = $reg_2['nomefantasia'];
		$data     = $reg_2['data'];
		$logon = ver_logon($codloja);
		
		echo "<tr ";
			if ( ( $a % 2 ) == 0 ) {
				echo "bgcolor='#E5E5E5'>";
			} else {
				echo ">";
			}
		
		echo "<td colspan='2' style='font-family:Verdana, Geneva, sans-serif;font-size:10px'>$data $logon - $nome_cli</td>
			 </tr>";
		$total_qtd_reg++;
	}
	echo "<tr>
		<td colspan='2'>&nbsp;</td>
	  </tr>
	</table>
	</div>";
}
echo "
<table width='800' cellpadding='1' cellspacing='1'>
	<tr>
		<td colspan='2' class='subtitulodireita'>&nbsp;</td>
	</tr>
	<tr>
		<td class='campoesquerda' width='70%'>Total Geral</td>
		<td class='campoesquerda'>$total_qtd_reg</td>
	</tr>
	<tr>
		<td height='30' class='subtitulodireita' colspan='2' style='text-align:center'>
			<input type='button' value=' Imprimir ' onClick=\"PrintDiv('relatorio_final')\"/>
		</td>
	</tr>
</table>
";
?>
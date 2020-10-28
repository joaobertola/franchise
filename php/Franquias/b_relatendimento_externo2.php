<?php
session_start();

require_once("../funcoes/mascaras.php");
require_once("connect/conexao_conecta.php");

function data_mysql($data){
	// converte data no formato DD/MM/AAAA  para AAAA-MM-DD
	$data = substr($data,6,4)."-".substr($data,3,2) . "-" . substr($data,0,2);
	return $data;
}

$id_franquia = $_REQUEST["id_franquia"];
$data1 = data_mysql($_REQUEST["data1"]);
$data2 = data_mysql($_REQUEST["data2"]);
$consultoria_realizada = $_REQUEST["consultoria_realizada"];

if ( $id_franquia=="" ){
	echo "<script>alert('FRANQUIA INVALIDA !');history.back()</script>";
	die;
}

if ( $consultoria_realizada == '9')
    $adiciona3 = '';
else if ( $consultoria_realizada == '0')
    $adiciona3 = ' AND a.consultoria_realizada = 0 ';
else if ( $consultoria_realizada == '1')
    $adiciona3 = ' AND a.consultoria_realizada = 1 ';

if ( $id_franquia == 1 ){
    $adiciona = " and ( id_franquia = 1 or id_franquia = 1307 or id_franquia = 1308 or id_franquia = 1309 or id_franquia = 1310 or id_franquia = 35 or id_franquia = 1316 ) ";
    $adiciona2 = " and ( b.id_franquia = 1 or b.id_franquia = 1307 or b.id_franquia = 1308 or b.id_franquia = 1309 or b.id_franquia = 1310 or b.id_franquia = 35 or b.id_franquia = 1316 ) ";
}else{
    $adiciona = " and id_franquia = $id_franquia ";
    $adiciona2 = " and b.id_franquia = $id_franquia ";
}

$comando = "SELECT
                DISTINCT(a.consultora) as nome_consultoria
            FROM
                base_inform.cadastro_imagem a
            INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
            WHERE
                a.data_consultoria BETWEEN '$data1' and '$data2'
                $adiciona $adiciona3";
$resi = mysql_query ($comando, $con) or die("Erro SQL: $comando");
if ( mysql_num_rows($resi) == 0 ){
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
			<tr height='20'>
			<td align='center' width='100%'>Nenhum registro foi encontrado....!</td></tr></table>";
}
else
{
	while ( $reg = mysql_fetch_array($resi) ){
		$consultora = $reg['nome_consultoria'];
		
		$sql_lista = "	SELECT
						  a.codloja, UPPER(b.nomefantasia) nomefantasia, UPPER(b.end) end, b.numero, 
						  UPPER(b.bairro) bairro, UPPER(b.cidade) cidade, UPPER(b.uf) uf, b.cep, b.fone, b.fax, 
						  date_format(a.data_consultoria,'%d/%m/%Y') AS data_consultoria, a.consultora
						FROM
						  base_inform.cadastro_imagem a
						INNER JOIN cs2.cadastro b ON a.codloja = b.codloja
						WHERE
						  a.data_consultoria BETWEEN '$data1' AND '$data2'
						  $adiciona2  and a.consultora = '$consultora'
                                                  $adiciona3
						ORDER BY a.data_consultoria, b.nomefantasia";
//		echo '<pre>';
//		echo $sql_lista;
//		die;
		$qr_lista = mysql_query($sql_lista,$con) or die ("Erro: $sql_lista");
		$linhas = mysql_num_rows($qr_lista);
		$qtd_reg = 0;		
		while( $res = mysql_fetch_array($qr_lista) ){
			$qtd_reg++;
			$codloja = $res['codloja'];
			$sql_l = "select CAST(MID(logon,1,6) AS UNSIGNED) as logon FROM cs2.logon WHERE codloja = $codloja";
			$qr_l = mysql_query($sql_l,$con) or die ("Erro: $sql_l");
			$logon = mysql_result($qr_l,0,'logon');

			$nomefantasia = "$logon - ".$res['nomefantasia'];
			$end = $res['end'];
			$numero = $res['numero'];
			$complemento = $res['complemento'];
			$bairro = $res['bairro'];
			$cidade = $res['cidade'];
			$uf = $res['uf'];
			$cep = $res['cep'];
			$fone = $res['fone'];
			$fax = $res['fax'];
			$data_consultoria = $res['data_consultoria'];

			if ( $qtd_reg == 1 ){
				echo "<table class='table table-striped table-responsive col65' align='center' width=100%>
					<thead>
				 		<tr>
							<th colspan='5' height='1' bgcolor='#999999'></td>
						</tr>
						<tr height='20' class='titulo'>
							<th align='left'>Data</th>
							<th align='left'>Cliente</th>
							<th align='left'>Endere&ccedil;o</th>
							<th align='left'>Cidade/UF</th>				
							<th align='left'>Consultor</th>
						</tr>
					</thead>
					<tbody>";
			}
		
	  		echo "<tr";
			if ( ( $qtd_reg % 2 ) == 0 ) {
				echo "bgcolor='#E5E5E5'>";
			} else {
				echo ">";
			}
			echo "<tr style='font-size: 12px'>
				<td>$data_consultoria</td>				
				<td>$nomefantasia</td>
				<td>$end $numero</td>
				<td>$cidade / $uf</td>
				<td>$consultora</td>
			</tr>";
		}
		echo "<tr height='20' class='titulo'>
				<td colspan='5'>Listados : $qtd_reg clientes</td>
			  </tr>
			  <tr  height='20'><td></td></tr>";		
	}

}
echo "
				</tbody>
				<tfoot>
					<tr>
						<td colspan='5'>
							<input name=\"button\" type=\"button\" class=\"botao3d\" onClick=\"javascript: history.back();\" value=\"Voltar\" />
						</td>
					</tr>
				</tfoot>
			</table>";

$res = mysql_close ($con);
?>
<br>


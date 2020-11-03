<?php
session_start();

//include("../../../web_control/funcao_php/mascaras.php");

function data_mysql($data){
    // converte data no formato DD/MM/AAAA  para AAAA-MM-DD
    $data = substr($data,6,4)."-".substr($data,3,2) . "-" . substr($data,0,2);
    return $data;
}

$id_franquia = $_REQUEST["id_franquia"];
$data1 = data_mysql($_REQUEST["data1"]);
$data2 = data_mysql($_REQUEST["data2"]);

if ( $id_franquia=="" ){
    echo "<script>alert('FRANQUIA INVALIDA !');history.back()</script>";
    die;
}

$comando = "SELECT
                DISTINCT(b.consultora) as nome_consultoria 
            FROM
                cadastro a
            INNER JOIN
                base_inform.cadastro_imagem b on a.codloja = b.codloja
            WHERE
                b.data_consultoria BETWEEN '$data1' and '$data2'
                AND a.id_franquia = $id_franquia";
$resi = mysql_query ($comando, $con);
if ( mysql_num_rows($resi) == 0 ){
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
			<tr height='20'>
			<td align='center' width='100%'>Nenhum registro foi encontrado!</td></tr></table>";
}
else
{
	while ( $reg = mysql_fetch_array($resi) ){
		$consultora = $reg['nome_consultoria'];
		
		$sql_lista = "	SELECT
                                        a.codloja, UPPER(a.nomefantasia) nomefantasia, UPPER(a.end) end, a.numero, 
                                        UPPER(a.bairro) bairro,UPPER(a.cidade) cidade, UPPER(a.uf) uf, a.cep, a.fone, 
                                        a.fax, date_format(b.data_consultoria,'%d/%m/%Y') AS data_consultoria,
                                        a.nome_consultoria, MID(c.logon,1,LOCATE('S', c.logon) - 1) as logon 
                                FROM
                                        cs2.cadastro a
                                INNER JOIN
                                        base_inform.cadastro_imagem b on a.codloja = b.codloja 
                                INNER JOIN
                                        cs2.logon c ON a.codloja = c.codloja
                                WHERE
                                  b.data_consultoria BETWEEN '$data1' AND '$data2'
                                  AND a.id_franquia = '$id_franquia' 
                                  AND b.consultora = '$consultora'
                                GROUP BY a.codloja
                                ORDER BY b.data_consultoria";
			
		$qr_lista = mysql_query($sql_lista,$con) or die ("Erro: $sql_lista");
		$linhas = mysql_num_rows($qr_lista);
		$qtd_reg = 0;		
		while( $res = mysql_fetch_array($qr_lista) ){
			$qtd_reg++;
			$codloja = $res['codloja'];
			$logon = $res['logon'];
			$nomefantasia = $res['nomefantasia'];
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
				echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bodyText'>
				 		<tr>
							<td colspan='5' height='1' bgcolor='#999999'></td>
						</tr>
						<tr height='20' class='titulo'>
							<td align='left'>Data</td>
							<td align='left'>Cliente</td>
							<td align='left'>Endereco</td>
							<td align='left'>Cidade/UF</td>				
							<td align='left'>Consultor</td>
						</tr>";
			}
		
	  		echo "<tr height='22'";
			if ( ( $qtd_reg % 2 ) == 0 ) {
				echo "bgcolor='#E5E5E5'>";
			} else {
				echo ">";
			}
			echo "
				<td align='left'>$data_consultoria</td>				
				<td align='left'>$logon - $nomefantasia</td>
				<td align='left'>$end $numero</td>
				<td align='left'>$cidade / $uf</td>
				<td align='left'>$consultora</td>
			</tr>";
		}
		echo "<tr height='20' class='titulo'>
				<td colspan='5'>Listados : $qtd_reg clientes</td>
			  </tr>
			  <tr  height='20'><td></td></tr>";		
	}

}
echo "</table>";

$res = mysql_close ($con);
?>
<br>
<center>
	<input name="button" type="button" class="botao3d" onClick="javascript: history.back();" value="Voltar" />
</center>